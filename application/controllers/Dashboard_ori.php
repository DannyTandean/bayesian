<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Dashboard extends MY_Controller {

	public function __construct()
	{
		parent::__construct();
		$this->load->model('aktivitas/LogAbsensi_model',"absensiModel");
		$this->load->model('aktivitas/Jadwalkerja_model',"jadwalKerjaModel");
		
	}

	public function index()
	{
		parent::checkLoginUser(); // check login user

		parent::headerTitle("Dashboard","Dashboard");

		parent::viewData(array('data1' => "isi dari data 1",'data2' => "isi dari data 22"));

		parent::view();
	}

	public function chartKehadiranAjax($tgl=false)
	{
		parent::checkLoginUser(); // check login user
		if ($this->isPost()) {

			if (!$tgl) {
				$tgl = date("Y-m-d");
			}

			$totalKaryawan = $this->absensiModel->totalKaryawan($tgl);

		    $whereIn = array(
		    					"kerja" => array("Normal","Normal(Manual)","Ganti"),
		    				);

			$hadir = $this->absensiModel->dataAbsensi($tgl,false,$whereIn);
			$sakit = $this->absensiModel->dataAbsensi($tgl,array("kerja"=>"Sakit"));
			$izin = $this->absensiModel->dataAbsensi($tgl,array("kerja"=>"Izin"));
			$cuti = $this->absensiModel->dataAbsensi($tgl,array("kerja"=>"Cuti"));
			$off = $this->absensiModel->offAbsensiKaryawan($tgl);
			$dinas = $this->absensiModel->dataAbsensi($tgl,array("kerja"=>"Dinas"));
			$mangkir = ( $totalKaryawan - ($hadir + $sakit + $izin + $cuti + $off + $dinas));

			/*for chart kehadiran*/
			$columns = [
		                ["Hadir", $hadir],
		                ["Tidak Hadir", $mangkir],
		                ["Sakit", $sakit],
		                ["Izin", $izin],
		                ["Cuti", $cuti],
		                ["Off", $off],
		                ["Perjalanan Dinas",$dinas],
		            ];
		    /*end for chart kehadiran*/

		    /*for content count chart kehadiran*/
		    $dataCount = [
		    				"total_karyawan" 	=> 	$totalKaryawan,
		    				"hadir"			 	=>	$hadir,
		    				"mangkir"		 	=>	$mangkir,
		    				"sakit"				=>	$sakit,
		    				"izin"				=>	$izin,
		    				"cuti"				=>	$cuti,
		    				"off"				=>	$off,
		    				"dinas"				=>	$dinas,
		    			];
		    /*end for content count chart kehadiran*/

		    /*for shift count data kehadiran per shift*/
		    $shiftCount = [
		    				"regular"	=> 	$this->absensiModel->dataAbsensi($tgl,array("shift"=>"REGULAR"),$whereIn),
		    				"pagi"		=>	$this->absensiModel->dataAbsensi($tgl,array("shift"=>"SHIFT-PAGI"),$whereIn),
		    				"sore"		=>	$this->absensiModel->dataAbsensi($tgl,array("shift"=>"SHIFT-SORE"),$whereIn),
		    				"malam"		=>	$this->absensiModel->dataAbsensi($tgl,array("shift"=>"SHIFT-MALAM"),$whereIn),
		    				"pagi12"	=>	$this->absensiModel->dataAbsensi($tgl,array("shift"=>"SHIFT-PAGI-12-JAM"),$whereIn),
		    				"malam12"	=>	$this->absensiModel->dataAbsensi($tgl,array("shift"=>"SHIFT-MALAM-12-JAM"),$whereIn),
		    				"extra"		=>	$this->absensiModel->extraData($tgl),
		    			];
		    /*end for shift count data kehadiran per shift*/

		    /*for count data ketepatan kehadiran*/
		    $dataKetepatan = [
		    					"tepat"				=>	$this->absensiModel->dataAbsensi($tgl,array("telat"=>0),$whereIn),
		    					"terlambat"			=>	$this->absensiModel->dataAbsensi($tgl,array("telat >"=>0),$whereIn),
		    					"total_kehadiran"	=>	$hadir,
		    				];
		    /*end for count data ketepatan kehadiran*/

		    /*for kontrak expired */
			$whereKontrak = array(
									"expired_date > " => date("Y-m-d"),
									"MONTH(expired_date)" => date("m"),
									"YEAR(expired_date)" => date("Y"),
									"kontrak !="	=>	"tidak",
									"status_kerja" => "aktif"
								);
			$sisa1_bulan = $this->absensiModel->dataKontrak($whereKontrak);

			$whereKontrak["MONTH(expired_date)"] = date("m", strtotime(" + 1 months"));
			if (date("m") >= 12) {
				$whereKontrak["YEAR(expired_date)"] = date("Y", strtotime(" + 1 years"));
			}
			$sisa2_bulan = $this->absensiModel->dataKontrak($whereKontrak);

			$whereKontrak["MONTH(expired_date)"] = date("m", strtotime(" + 2 months"));
			if (date("m") >= 11) {
				$whereKontrak["YEAR(expired_date)"] = date("Y", strtotime(" + 1 years"));
			}
			$sisa3_bulan = $this->absensiModel->dataKontrak($whereKontrak);

			$habis_kontrak = $this->absensiModel->dataKontrak(array("expired_date <= " => date("Y-m-d"),"kontrak !=" => "tidak","status_kerja" => "aktif"));

			$dataKontrak = new StdClass();
			$dataKontrak->sisa1_bulan = $sisa1_bulan;
			$dataKontrak->sisa2_bulan = $sisa2_bulan;
			$dataKontrak->sisa3_bulan = $sisa3_bulan;
			$dataKontrak->habis_kontrak = $habis_kontrak;
			/*end for kontrak expired*/

		    /*for Ulang tahun karyawan*/
		    $contentUlangTahun = "";
		    $karyawanUlangTahun = $this->absensiModel->karyawanUlangTahun($tgl);
		    if ($karyawanUlangTahun) {
		    	foreach ($karyawanUlangTahun as $item) {
		    		if ($item->foto != "") {
						$item->foto = base_url("/")."uploads/master/karyawan/orang/".$item->foto;
					} else {
						$item->foto = base_url("/")."assets/images/default/no_user.png";
					}

					$tgl_lahir = explode("-", $item->tgl_lahir);
					
					$tglInput = explode("-", $tgl);
					if ($tglInput[1] == $tgl_lahir[1] && $tglInput[2] == $tgl_lahir[2]) {
						$tanggal = new DateTime($item->tgl_lahir);
						$today = new DateTime(date("Y-m-d"));
				    	$item->umur = $today->diff($tanggal)->y;

		    			$contentUlangTahun .= '
							                    <div class="card-msg b-b-default p-t-10 p-b-10">
							                        <div class="card-msg-img">
							                            <a href="'.$item->foto.'" data-toggle="lightbox" data-title="Foto '.$item->nama.'">
							                                <img src="'.$item->foto.'" class="img-circle" alt="" style="height: 60px; width: 60px;">
							                            </a>
							                        </div>
							                        <div class="card-msg-contain">
							                            <h6>'.$item->nama.'</h6>
							                            <span>'.ucfirst($item->jabatan).'</span>
							                        </div>
							                        <table class="m-t-10 table-responsive" style="font-size: 12px;">
							                            <tr>
							                                <th>Ulang Tahun Ke</th>
							                                <td>&ensp;:&ensp;</td>
							                                <td><u>'.$item->umur.' Tahun</u></td>
							                            </tr>
							                            <tr>
							                                <th>Tanggal Lahir</th>
							                                <td>&ensp;:&ensp;</td>
							                                <td>'.date_ind("d M Y", $item->tgl_lahir).'</td>
							                            </tr>
							                            <tr>
							                                <th>Tempat Lahir</th>
							                                <td>&ensp;:&ensp;</td>
							                                <td>'.$item->tempat_lahir.'</td>
							                            </tr>
							                            <tr>
							                                <th>Jenis Kelamin</th>
							                                <td>&ensp;:&ensp;</td>
							                                <td>'.ucfirst($item->kelamin).'</td>
							                            </tr>
							                        </table>
							                    </div>
				    							';
					}
		    	}
		    }
		    /*end for ulang tahun karyawan*/

		    /*for Departemen kehadiran*/
		    $contentDepartemen = "";
		    $dataDepartemen = $this->absensiModel->getAllDepartemen();
		    if ($dataDepartemen) {
		    	$colDepartemen = '';
		    	foreach ($dataDepartemen as $item) {
		    		$whereDepartemen = array("master_departemen.departemen" => $item->departemen);
		    		$totalKryDepartemen = $this->absensiModel->getAllDepartemen(true,$whereDepartemen);
		    		if ($totalKryDepartemen) {
		    			
		    			$countHadirDepartemen = $this->absensiModel->absensiDepartemen($tgl,true,$whereDepartemen); // per tanggal
		    			// $countHadirDepartemen = 1;
		    			$countMangkirDepartemen = ($totalKryDepartemen - $countHadirDepartemen);

			    		$colDepartemen .= '<div class="col-md-2">
			    								<label>'.$item->departemen.'</label>
			    								<br>
			    								<span class="mytooltip tooltip-effect-5">
			    									<input type="text" class="dial tooltip-item" value="'.$countHadirDepartemen.'" data-min="0" data-max="'.$totalKryDepartemen.'" data-width="80" data-height="80" data-skin="tron"  data-displayprevious="true" data-displayInput="true" data-readonly="true" data-fgColor="#18b193" data-skin="tron" data-thickness=".2">

			    									<span class="tooltip-content clearfix">
				    									<span class="tooltip-text"><b style="font-size: 20px;">'.$item->departemen.'</b><br>
					    									<span class="text-success">Hadir : '.$countHadirDepartemen.' Orang</span><br>
					    									<span class="text-danger">Tidak Hadir : '.$countMangkirDepartemen.' Orang</span><br>
					    									<span class="text-info">Total : '.$totalKryDepartemen.' Orang</span>
			    										</span>
			    									</span>
			    								</span>
			    							</div><br><br><br><br><br><br>';
		    		}
		    	}
		    	$contentDepartemen = $colDepartemen;
		    }
		    /*end for Departemen kehadiran*/



		    $json = new StdClass();
		    $json->tanggal_indo = date_ind("l, d M Y", $tgl); // for tanggal chart kehadiran
		    $json->dataColumns = $columns; // for chart
		    $json->dataCount = $dataCount; // for value absensi
		    $json->shiftCount = $shiftCount; // for value shift
		    $json->dataKetepatan = $dataKetepatan; // for value ketepatan absensi
		    $json->dataUlangTahun = $contentUlangTahun; // for value ulang tahun karyawan
		    $json->dataDepartemen = $contentDepartemen; // for value departemen kehadiran
		    $json->dataKontrak = $dataKontrak;
	   		parent::json($json);
		}
	}

	public function dataTableKaryawan() // for total karyawan 
	{
		$this->load->model('master/Karyawan_model', "karyawanModel");
		parent::checkLoginUser(); // check login user
		if ($this->isPost()) {
			$data = array();

			$orderBy = array(null,"idfp","nama","master_jabatan.jabatan");
			$search = array("idfp","nama","master_jabatan.jabatan");
			$where = array("status_kerja" => "aktif");

			$result = $this->karyawanModel->findDataTable($orderBy,$search,$where);
			foreach ($result as $item) {
				// $item->tgl_lahir = date_ind("d M Y", $item->tgl_lahir);
				// $item->kelamin = ucfirst($item->kelamin);
				$data[] = $item;
			}
			return $this->karyawanModel->findDataTableOutput($data,$search,$where);
		}
	}

	public function dataTableTidakHadir($tgl=false)
	{
		$this->load->model('master/Karyawan_model', "karyawanModel");
		parent::checkLoginUser(); // check login user
		if ($this->isPost()) {

			if (!$tgl) {
				$tgl = date("Y-m-d");
			}

			$selectAbsensi = "kode";
			$hadir = $this->absensiModel->dataAbsensi($tgl,false,array("kerja" => array("Normal","Normal(Manual)","Ganti")),$selectAbsensi,true);
			$sakit = $this->absensiModel->dataAbsensi($tgl,array("kerja"=>"Sakit"),false,$selectAbsensi,true);
			$izin = $this->absensiModel->dataAbsensi($tgl,array("kerja"=>"Izin"),false,$selectAbsensi,true);
			$cuti = $this->absensiModel->dataAbsensi($tgl,array("kerja"=>"Cuti"),false,$selectAbsensi,true);
			$off = $this->absensiModel->offAbsensiKaryawan($tgl,"(master_karyawan.kode_karyawan) AS kode",true);
			$dinas = $this->absensiModel->dataAbsensi($tgl,array("kerja"=>"Dinas"),false,$selectAbsensi,true);

			$dataKehadiran = array_merge($hadir,$sakit);
			$dataKehadiran = array_merge($dataKehadiran,$izin);
			$dataKehadiran = array_merge($dataKehadiran,$cuti);
			$dataKehadiran = array_merge($dataKehadiran,$off);
			$dataKehadiran = array_merge($dataKehadiran,$dinas);

			$kodeKehadiran = array();
			foreach ($dataKehadiran as $val) {
				$kodeKehadiran[] = $val->kode;
			}

			/*var_dump($hadir);
			var_dump($sakit);
			var_dump($izin);
			var_dump($cuti);
			var_dump($off);
			var_dump($dinas);
			var_dump($dataKehadiran);*/
			// var_dump($kodeKehadiran);
			// exit();


			$orderBy = array(null,"idfp","nama","master_jabatan.jabatan");
			$search = array("idfp","nama","master_jabatan.jabatan");
			$where = array("status_kerja" => "aktif","tgl_masuk <= " => $tgl);

			$whereNotIn = false;
			if ($kodeKehadiran) {
				$whereNotIn = array(
									"kode_karyawan"	=> $kodeKehadiran,
								);
			}

			$result = $this->karyawanModel->findDataTable($orderBy,$search,$where,$whereNotIn);
			$data = array();
			foreach ($result as $item) {
				// $item->tgl_lahir = date_ind("d M Y", $item->tgl_lahir);
				// $item->kelamin = ucfirst($item->kelamin);
				$data[] = $item;
			}
			return $this->karyawanModel->findDataTableOutput($data,$search,$where,$whereNotIn);
		}
	}

	public function dataTableKehadiranAbsensi($statusKerja=false,$tgl=false)
	{
		parent::checkLoginUser(); // check login user
		if ($this->isPost()) {
			$data = array();

			$orderBy = array(null,"idfp","nama","master_jabatan.jabatan");
			$search = array("idfp","nama","master_jabatan.jabatan");
			if (!$tgl) {
				$tgl = date("Y-m-d");
			}
			$where = array("tanggal"=>$tgl,"status_kerja" => "aktif");

			$whereIn = false;
			if (!$statusKerja || $statusKerja == "hadir") {
				$whereIn = array(
		    					"kerja" => array("Normal","Normal(Manual)","Ganti"),
		    				);
			} else if ($statusKerja == "sakit") {
				$where["kerja"]	= "Sakit";
			} else if ($statusKerja == "cuti") {
				$where["kerja"]	= "Cuti";
			} else if ($statusKerja == "izin") {
				$where["kerja"]	= "Izin";
			} else if ($statusKerja == "dinas") {
				$where["kerja"]	= "Dinas";
			} else if ($statusKerja == "mangkir") {
				// for mangkir on progress
				
			} else if ($statusKerja == "tepat") {
				$where["telat"]	= 0;
				$whereIn = array(
		    					"kerja" => array("Normal","Normal(Manual)","Ganti"),
		    				);
			} else if ($statusKerja == "terlambat") {
				$where["telat >"]	= 0;
				$whereIn = array(
		    					"kerja" => array("Normal","Normal(Manual)","Ganti"),
		    				);
			} 

			$result = $this->absensiModel->findDataTable($orderBy,$search,$where,$whereIn);
			foreach ($result as $item) {
				// $item->tgl_lahir = date_ind("d M Y", $item->tgl_lahir);
				$item->telat = $item->telat." menit";
				$data[] = $item;
			}
			return $this->absensiModel->findDataTableOutput($data,$search,$where,$whereIn);
		}
	}

	public function dataTableKehadiranAbsensiOff($tgl=false)
	{
		$this->load->model('aktivitas/Jadwalkerja_model',"jadwalKerjaModel");
		parent::checkLoginUser(); // check login user
		if ($this->isPost()) {
			$data = array();
			$select = "master_karyawan.idfp, master_karyawan.nama, master_jabatan.jabatan, jadwal_token_karyawan.tanggal";
			$orderBy = array(null,"idfp","nama","master_jabatan.jabatan");
			$search = array("idfp","nama","master_jabatan.jabatan");
			if (!$tgl) {
				$tgl = date("Y-m-d");
			}
			$where = array("tanggal"=>$tgl,"nama_shift"=>"OFF");

			$result = $this->jadwalKerjaModel->findDataTable($select,$where,$orderBy,$search,false,false);
			foreach ($result as $item) {
				// $item->tgl_lahir = date_ind("d M Y", $item->tgl_lahir);
				// $item->kelamin = ucfirst($item->kelamin);
				$data[] = $item;
			}
			return $this->jadwalKerjaModel->findDataTableOutput($data,$select,$where,$search,false,false);
		}
	}

	public function dataTableKehadiranShift($statusShift=false,$tgl=false)
	{
		parent::checkLoginUser(); // check login user
		if ($this->isPost()) {
			$data = array();

			$orderBy = array(null,"idfp","nama","master_jabatan.jabatan");
			$search = array("idfp","nama","master_jabatan.jabatan");
			if (!$tgl) {
				$tgl = date("Y-m-d");
			}
			$where = array("tanggal"=>$tgl,"status_kerja" => "aktif");

			$whereIn = array(
		    					"kerja" => array("Normal","Normal(Manual)","Ganti"),
		    				);
			if (!$statusShift || $statusShift == "regular") {
				$where["absensi.shift"]	= "REGULAR";

			} else if ($statusShift == "pagi") {
				$where["absensi.shift"]	= "SHIFT-PAGI";

			} else if ($statusShift == "sore") {
				$where["absensi.shift"]	= "SHIFT-SORE";

			} else if ($statusShift == "malam") {
				$where["absensi.shift"]	= "SHIFT-MALAM";

			} else if ($statusShift == "pagi12") {
				$where["absensi.shift"]	= "SHIFT-PAGI-12-JAM";

			} else if ($statusShift == "malam12") {
				$where["absensi.shift"]	= "SHIFT-MALAM-12-JAM";
			}

			$result = $this->absensiModel->findDataTable($orderBy,$search,$where,$whereIn);
			foreach ($result as $item) {
				// $item->tgl_lahir = date_ind("d M Y", $item->tgl_lahir);
				// $item->kelamin = ucfirst($item->kelamin);
				$data[] = $item;
			}
			return $this->absensiModel->findDataTableOutput($data,$search,$where,$whereIn);
		}
	}

	public function dataTableKehadiranAbsensiExtra($tgl=false)
	{
		$this->load->model('aktivitas/ExtraAbsensi_model',"extraModel");
		parent::checkLoginUser(); // check login user
		if ($this->isPost()) {
			$data = array();
			$orderBy = array(null,"idfp","nama","master_jabatan.jabatan");
			$search = array("idfp","nama","master_jabatan.jabatan");
			if (!$tgl) {
				$tgl = date("Y-m-d");
			}
			$where = array("tanggal"=>$tgl);

			$result = $this->extraModel->findDataTable($orderBy,$search,$where);
			foreach ($result as $item) {
				// $item->tgl_lahir = date_ind("d M Y", $item->tgl_lahir);
				// $item->kelamin = ucfirst($item->kelamin);
				$data[] = $item;
			}
			return $this->extraModel->findDataTableOutput($data,$search,$where);
		}
	}

	public function dataTableKontrak($statusKontrak=false,$tgl=false)
	{
		$this->load->model('master/Karyawan_model', "karyawanModel");
		parent::checkLoginUser(); // check login user
		if ($this->isPost()) {
			$data = array();

			if (!$tgl) {
				$tgl = date("Y-m-d");
			}
			$formatDate = explode("-",$tgl);
			$tahun = $formatDate[0];
			$bulan = $formatDate[1];

			$orderBy = array(null,"idfp","nama","master_jabatan.jabatan","start_date","expired_date");
			$search = array("idfp","nama","master_jabatan.jabatan","start_date","expired_date");

			$whereKontrak = array(
									"expired_date > " => $tgl,
									"kontrak !="	=>	"tidak",
									"status_kerja" => "aktif"
								);
			if (!$statusKontrak || $statusKontrak == "sisa1Bulan") {
				$whereKontrak["MONTH(expired_date)"] = $bulan;
				$whereKontrak["YEAR(expired_date)"] = $tahun;
			} else if ($statusKontrak  == "sisa2bulan") {
				$whereKontrak["MONTH(expired_date)"] = date("m", strtotime(" + 1 months", strtotime($tgl)));
				$whereKontrak["YEAR(expired_date)"] = $tahun;
				if ($bulan >= 12) {
					$whereKontrak["YEAR(expired_date)"] = date("Y", strtotime(" + 1 years", strtotime($tgl)));
				}
			} else if ($statusKontrak  == "sisa3bulan") {
				$whereKontrak["MONTH(expired_date)"] = date("m", strtotime(" + 2 months", strtotime($tgl)));
				$whereKontrak["YEAR(expired_date)"] = $tahun;
				if ($bulan >= 11) {
					$whereKontrak["YEAR(expired_date)"] = date("Y", strtotime(" + 1 years", strtotime($tgl)));
				}
			} else if ($statusKontrak == "habiskontrak") {
				// $whereKontrak["expired_date <="] = $tgl;
				$whereKontrak = array(
										"expired_date <= " => $tgl,
										"kontrak !="	=>	"tidak",
										"status_kerja" => "aktif"
									);
			}

			$result = $this->karyawanModel->findDataTable($orderBy,$search,$whereKontrak);
			foreach ($result as $item) {
				// $item->tgl_lahir = date_ind("d M Y", $item->tgl_lahir);
				
				$item->start_date = "<span class='text-success'>".$item->start_date."</span>";
				$item->expired_date = "<span class='text-danger'>".$item->expired_date."</span>";

				$data[] = $item;
			}
			return $this->karyawanModel->findDataTableOutput($data,$search,$whereKontrak);
		}
	}

}

/* End of file Dashboard.php */
/* Location: ./application/controllers/Dashboard.php */