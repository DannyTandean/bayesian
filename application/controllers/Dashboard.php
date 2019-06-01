<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Dashboard extends MY_Controller {

	public function __construct()
	{
		parent::__construct();
		$this->load->model('aktivitas/LogAbsensi_model',"absensiModel");
		$this->load->model('aktivitas/Jadwalkerja_model',"jadwalKerjaModel");
		$this->load->model('aktivitas/Jadwal_karyawan_model',"JadwalKaryawanModel");
	}

	public function index()
	{
		parent::checkLoginUser(); // check login user

		parent::headerTitle("Dashboard","Dashboard");
		$data = array();
		$data['jadwal'] = $this->JadwalKaryawanModel->getAllJadwal();
		parent::viewData($data);

		parent::view();
	}

	public function getjadwal()
	{
		parent::checkLoginUser();
		if ($this->isPost()) {
			$jadwalKaryawan = $this->JadwalKaryawanModel->getAllJadwal();
			if ($jadwalKaryawan) {
				$this->response->status = true;
				$this->response->message = alertSuccess("Berhasil get data Jadwal");
				$this->response->data = $jadwalKaryawan;
			}
			else {
				$this->response->message = alertDanger("Data tidak ada");
			}
		}
		parent::json();
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
			$izin = $this->absensiModel->getAllIzin($tgl,true);
			$cuti = $this->absensiModel->dataAbsensi($tgl,array("kerja"=>"Cuti"));
			// $off = $this->absensiModel->offAbsensiKaryawan($tgl);
			$dinas = $this->absensiModel->dataAbsensi($tgl,array("kerja"=>"Dinas"));
			// $mangkir = ( $totalKaryawan - ($hadir + $sakit + $izin + $cuti + $off + $dinas));
			$mangkir = ( $totalKaryawan - ($hadir + $sakit + $izin + $cuti + $dinas));

			/*for chart kehadiran*/
			$columns = [
		                ["Hadir", $hadir],
		                ["Tidak Hadir", $mangkir],
		                ["Sakit", $sakit],
		                ["Izin", $izin],
		                ["Cuti", $cuti],
		                // ["Off", $off],
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
		    				// "off"				=>	$off,
		    				"dinas"				=>	$dinas,
		    			];
		    /*end for content count chart kehadiran*/

		    /*for shift count data kehadiran per shift*/
				$listShift = $this->JadwalKaryawanModel->getAllJadwal();
				foreach ($listShift as $key => $vals) {
					$shiftCount["shift".$vals->id_jadwal] = $this->absensiModel->dataAbsensi($tgl,array("id_jadwal"=> $vals->id_jadwal),$whereIn);
				}
				$shiftCount["takterjadwal"] = $this->absensiModel->dataAbsensi($tgl,array("id_jadwal"=> null),$whereIn);
		    // $shiftCount = [
		    // 				"regular"	=> 	$this->absensiModel->dataAbsensi($tgl,array("shift"=>"REGULAR"),$whereIn),
		    // 				"pagi"		=>	$this->absensiModel->dataAbsensi($tgl,array("shift"=>"SHIFT-PAGI"),$whereIn),
		    // 				"sore"		=>	$this->absensiModel->dataAbsensi($tgl,array("shift"=>"SHIFT-SORE"),$whereIn),
		    // 				"malam"		=>	$this->absensiModel->dataAbsensi($tgl,array("shift"=>"SHIFT-MALAM"),$whereIn),
		    // 				"pagi12"	=>	$this->absensiModel->dataAbsensi($tgl,array("shift"=>"SHIFT-PAGI-12-JAM"),$whereIn),
		    // 				"malam12"	=>	$this->absensiModel->dataAbsensi($tgl,array("shift"=>"SHIFT-MALAM-12-JAM"),$whereIn),
		    // 				"manual"	=>	$this->absensiModel->dataAbsensi($tgl,array("shift"=>"MANUAL"),$whereIn),
		    // 				"extra"		=>	$this->absensiModel->extraData($tgl),
		    // 			];
		    /*end for shift count data kehadiran per shift*/

		    /*for count data ketepatan kehadiran*/
		    $dataKetepatan = [
		    					"tepat"				=>	$this->absensiModel->dataAbsensi($tgl,array("telat <="=>0),$whereIn),
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
			$whereKontrakNew = 'select * FROM master_karyawan WHERE status_kerja = "aktif" and kontrak != "tidak" and expired_date BETWEEN CURDATE() and DATE_ADD(CURDATE(), INTERVAL 30 DAY)';
			$sisa1_bulan = $this->absensiModel->kontrakKaryawan($whereKontrakNew);

			$whereKontrak["MONTH(expired_date)"] = date("m", strtotime(" + 1 months"));
			if (date("m") >= 12) {
				$whereKontrak["YEAR(expired_date)"] = date("Y", strtotime(" + 1 years"));
			}
			$whereKontrakNew2bln = 'select * FROM master_karyawan WHERE status_kerja = "aktif" and kontrak != "tidak" and expired_date BETWEEN DATE_ADD(CURDATE(), INTERVAL 31 DAY) and DATE_ADD(CURDATE(), INTERVAL 60 DAY)';
			$sisa2_bulan = $this->absensiModel->kontrakKaryawan($whereKontrakNew2bln);

			$whereKontrak["MONTH(expired_date)"] = date("m", strtotime(" + 2 months"));
			if (date("m") >= 11) {
				$whereKontrak["YEAR(expired_date)"] = date("Y", strtotime(" + 1 years"));
			}
			$whereKontrak3blng = 'select * FROM master_karyawan WHERE status_kerja = "aktif" and kontrak != "tidak" and expired_date BETWEEN DATE_ADD(CURDATE(), INTERVAL 61 DAY) and DATE_ADD(CURDATE(), INTERVAL 90 DAY)';
			$sisa3_bulan = $this->absensiModel->kontrakKaryawan($whereKontrak3blng);

			$habis_kontrak = $this->absensiModel->dataKontrak(array("expired_date <= " => date("Y-m-d"),"kontrak !=" => "tidak","status_kerja" => "aktif"));

			$dataKontrak = new StdClass();

			$dataKontrak->sisa1_bulan = sizeof($sisa1_bulan);
			$dataKontrak->sisa2_bulan = sizeof($sisa2_bulan);
			$dataKontrak->sisa3_bulan = sizeof($sisa3_bulan);
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
		    		$totalKryDepartemen = $this->absensiModel->getAllDepartemen(true,$whereDepartemen); // count data departemen
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

	public function dataTableTidakHadir($tgl=false) // mangkir
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
			$izin = $this->absensiModel->getAllIzin($tgl);
			$cuti = $this->absensiModel->dataAbsensi($tgl,array("kerja"=>"Cuti"),false,$selectAbsensi,true);
			$off = $this->absensiModel->offAbsensiKaryawan($tgl,"(master_karyawan.kode_karyawan) AS kode",true);
			$dinas = $this->absensiModel->dataAbsensi($tgl,array("kerja"=>"Dinas"),false,$selectAbsensi,true);
			// var_dump($izin);
			// exit();
			$dataKehadiran = array_merge($hadir,$sakit);
			$dataKehadiran = array_merge($dataKehadiran,$izin);
			$dataKehadiran = array_merge($dataKehadiran,$cuti);
			$dataKehadiran = array_merge($dataKehadiran,$off);
			$dataKehadiran = array_merge($dataKehadiran,$dinas);

			$kodeKehadiran = array();
			foreach ($dataKehadiran as $val) {
				$kodeKehadiran[] = $val->kode;
			}

			$orderBy = array(null,"idfp","nama","master_jabatan.jabatan");
			$search = array("idfp","nama","master_jabatan.jabatan");
			$where = array("tgl_masuk <= " => $tgl,"status_kerja" => "aktif");

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
			$where = array("tanggal"=>$tgl,"status_kerja" => "aktif","status"=>"Diterima");
			$whereIzin = array("tgl_izin <="=>$tgl,"akhir_izin >="=>$tgl,"status_kerja" => "aktif","status"=>"Diterima");

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
			} else if ($statusKerja == "tepat") {
				$where["telat <="]	= 0;
				$whereIn = array(
		    					"kerja" => array("Normal","Normal(Manual)","Ganti"),
		    				);
			} else if ($statusKerja == "terlambat") {
				$where["telat >"]	= 0;
				$whereIn = array(
		    					"kerja" => array("Normal","Normal(Manual)","Ganti"),
		    				);
			}
			else if ($statusKerja == "takterjadwal") {
				$where["absensi.id_jadwal"] = null;
			}

			if ($statusKerja == "izin") {
				$result = $this->absensiModel->findDataTableDashboardizin($orderBy,$search,$whereIzin,$whereIn);
				foreach ($result as $item) {
					// $item->tgl_lahir = date_ind("d M Y", $item->tgl_lahir);
					$data[] = $item;
				}
				return $this->absensiModel->findDataTableOutputDashboardIzin($data,$search,$whereIzin,$whereIn);
			}
			else {
				$result = $this->absensiModel->findDataTableDashboard($orderBy,$search,$where,$whereIn);
				foreach ($result as $item) {
					// $item->tgl_lahir = date_ind("d M Y", $item->tgl_lahir);
					$item->telat = $item->telat." menit";
					$data[] = $item;
				}
				return $this->absensiModel->findDataTableOutputDashboard($data,$search,$where,$whereIn);
			}
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
			// if (!$statusShift || $statusShift == "regular") {
			// 	$where["absensi.shift"]	= "REGULAR";
			//
			// } else if ($statusShift == "pagi") {
			// 	$where["absensi.shift"]	= "SHIFT-PAGI";
			//
			// } else if ($statusShift == "sore") {
			// 	$where["absensi.shift"]	= "SHIFT-SORE";
			//
			// } else if ($statusShift == "malam") {
			// 	$where["absensi.shift"]	= "SHIFT-MALAM";
			//
			// } else if ($statusShift == "pagi12") {
			// 	$where["absensi.shift"]	= "SHIFT-PAGI-12-JAM";
			//
			// } else if ($statusShift == "malam12") {
			// 	$where["absensi.shift"]	= "SHIFT-MALAM-12-JAM";
			// } else if ($statusShift == "manual") {
			// 	$where["absensi.shift"]	= "MANUAL";
			// }

			$where["absensi.id_jadwal"] = $statusShift;

			$result = $this->absensiModel->findDataTableDashboard($orderBy,$search,$where,$whereIn);
			foreach ($result as $item) {
				// $item->tgl_lahir = date_ind("d M Y", $item->tgl_lahir);
				// $item->kelamin = ucfirst($item->kelamin);
				$data[] = $item;
			}
			return $this->absensiModel->findDataTableOutputDashboard($data,$search,$where,$whereIn);
		}
	}

	public function dataTableKehadiranAbsensiExtra($tgl=false)
	{
		$this->load->model('aktivitas/ExtraAbsensi_model',"extraModel");
		parent::checkLoginUser(); // check login user
		if ($this->isPost()) {
			$data = array();
			$orderBy = array(null,"idfp","master_karyawan.nama","master_jabatan.jabatan");
			$search = array("idfp","master_karyawan.nama","master_jabatan.jabatan");
			if (!$tgl) {
				$tgl = date("Y-m-d");
			}
			$where = array("absensi_extra.tanggal"=>$tgl);

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
			$whereKontrakNew = "";
			if (!$statusKontrak || $statusKontrak == "sisa1Bulan") {
				$whereKontrak["MONTH(expired_date)"] = $bulan;
				$whereKontrak["YEAR(expired_date)"] = $tahun;
				// $whereKontrakNew =  array(
				// 														"status_kerja " => "aktif",
				// 														"kontrak !="	=>	"tidak",
				// 														"expired_date >=" => "CURDATE()",
				// 														"expired_date <=" => "DATE_ADD(CURDATE(), INTERVAL 30 DAY)"
				// 													);
				$whereKontrakNew = 'select * FROM master_karyawan left join master_jabatan on master_karyawan.id_jabatan = master_jabatan.id_jabatan WHERE status_kerja = "aktif" and kontrak != "tidak" and expired_date BETWEEN CURDATE() and DATE_ADD(CURDATE(), INTERVAL 30 DAY)';
			} else if ($statusKontrak  == "sisa2bulan") {
				$whereKontrak["MONTH(expired_date)"] = date("m", strtotime(" + 1 months", strtotime($tgl)));
				$whereKontrak["YEAR(expired_date)"] = $tahun;
				if ($bulan >= 12) {
					$whereKontrak["YEAR(expired_date)"] = date("Y", strtotime(" + 1 years", strtotime($tgl)));
				}
				// $whereKontrakNew =  array(
				// 														"status_kerja " => "aktif",
				// 														"kontrak !="	=>	"tidak",
				// 														"expired_date >=" => "DATE_ADD(CURDATE(), INTERVAL 30 DAY)",
				// 														"expired_date <=" => "DATE_ADD(CURDATE(), INTERVAL 60 DAY)"
				// 													);
				$whereKontrakNew = 'select * FROM master_karyawan left join master_jabatan on master_karyawan.id_jabatan = master_jabatan.id_jabatan WHERE status_kerja = "aktif" and kontrak != "tidak" and expired_date BETWEEN DATE_ADD(CURDATE(), INTERVAL 31 DAY) and DATE_ADD(CURDATE(), INTERVAL 60 DAY)';
			} else if ($statusKontrak  == "sisa3bulan") {
				$whereKontrak["MONTH(expired_date)"] = date("m", strtotime(" + 2 months", strtotime($tgl)));
				$whereKontrak["YEAR(expired_date)"] = $tahun;
				if ($bulan >= 11) {
					$whereKontrak["YEAR(expired_date)"] = date("Y", strtotime(" + 1 years", strtotime($tgl)));
				}
				// $whereKontrakNew =  array(
				// 														"status_kerja " => "aktif",
				// 														"kontrak !="	=>	"tidak",
				// 														"expired_date >=" => "DATE_ADD(CURDATE(), INTERVAL 60 DAY)",
				// 														"expired_date <=" => "DATE_ADD(CURDATE(), INTERVAL 90 DAY)"
				// 													);
				$whereKontrakNew = 'select * FROM master_karyawan left join master_jabatan on master_karyawan.id_jabatan = master_jabatan.id_jabatan WHERE status_kerja = "aktif" and kontrak != "tidak" and expired_date BETWEEN DATE_ADD(CURDATE(), INTERVAL 61 DAY) and DATE_ADD(CURDATE(), INTERVAL 90 DAY)';
			} else if ($statusKontrak == "habiskontrak") {
				// $whereKontrak["expired_date <="] = $tgl;
				$whereKontrak = array(
										"expired_date <= " => $tgl,
										"kontrak !="	=>	"tidak",
										"status_kerja" => "aktif"
									);

				$whereKontrakNew = "select * from master_karyawan left join master_jabatan on master_karyawan.id_jabatan = master_jabatan.id_jabatan where expired_date <= CURDATE() and kontrak != 'tidak' and status_kerja = 'aktif'";
			}
			//
			// $result = $this->karyawanModel->findDataTableKontrak($orderBy,$search,$whereKontrakNew);
			// foreach ($result as $item) {
			// 	// $item->tgl_lahir = date_ind("d M Y", $item->tgl_lahir);
			//
			// 	$item->start_date = "<span class='text-success'>".$item->start_date."</span>";
			// 	$item->expired_date = "<span class='text-danger'>".$item->expired_date."</span>";
			//
			// 	$data[] = $item;
			// }
			// return $this->karyawanModel->findDataTableOutputKontrak($data,$search,$whereKontrakNew);
			$kontrakData = $this->karyawanModel->kontrakQuery($whereKontrakNew);
			$this->response->status = true;
			$this->response->message = "berhasil get data kontrak";
			$this->response->data = $kontrakData;
		}
		parent::json();
	}

}

/* End of file Dashboard.php */
/* Location: ./application/controllers/Dashboard.php */
