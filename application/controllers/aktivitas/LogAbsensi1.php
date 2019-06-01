<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class LogAbsensi extends MY_Controller {

	public function __construct()
	{
		parent::__construct();
		$this->load->model('aktivitas/LogAbsensi_model',"logabsensiModel");
		$this->load->model('master/Karyawan_model', "karyawanModel");
		$this->load->model('aktivitas/ExtraAbsensi_model',"ExtraabsensiModel");
		$this->load->model('aktivitas/Disiplin_model',"DisiplinModel");
		parent::checkLoginOwner();
	}

	public function index()
	{
		parent::checkLoginUser(); // user login autentic checking

		parent::headerTitle("Aktivitas Data > Log Kabag","Aktivitas Data","Log Kabag");
		$breadcrumbs = array(
							"Aktivitas"		=>	site_url('aktivitas/logabsensi'),
							"Log Kabag"	=>	"",
						);
		parent::breadcrumbs($breadcrumbs);
		$data = array();
		$data['karyawan'] = $this->logabsensiModel->getKaryawan();
		$data['jabatan'] = $this->logabsensiModel->getAllJabatanAjax();
		$data['kabag'] = $this->logabsensiModel->getKabag();
		$data['nama_jadwal'] = $this->logabsensiModel->getShift();
		parent::viewData($data);
		parent::viewAktivitas();
	}

	public function ajax_list()
	{
		parent::checkLoginUser(); // user login autentic checking
		
		if ($this->isPost()) {
			$data = array();
			$orderBy = array(null,null,"kabag","tanggal","idfp","nama","jabatan","shift","masuk","break_out","break_in","keluar");
			$search = array("kabag","nama");

			$result = $this->logabsensiModel->findDataTable($orderBy,$search);
			foreach ($result as $item) {
					$btnAction= "";
					if (date("Y-m-d")<=$item->tanggal) {
						if ($item->kerja == "Normal" || $item->kerja == "Ganti" || $item->kerja == "Normal(Manual)") {
							$btnAction = '<button class="btn btn-warning btn-outline-warning btn-mini" onclick="btnEdit('.$item->id_absensi.')"><i class="fa fa-pencil-square-o"></i></button>';
						}
					}

					$btnAction .= '&nbsp;&nbsp;<button class="btn btn-outline-info btn-mini" title="Detail" onclick="btnDetail('.$item->id_absensi.')"><i class="icofont icofont-ui-zoom-in"></i></button>';

					$btnAction .= '&nbsp;&nbsp;<button class="btn btn-outline-success btn-mini" title="Kordinat maps" onclick="btnKordinat('.$item->id_absensi.')"><i class="icofont icofont-ui-map"></i></button>';
		
					$result1 = $this->logabsensiModel->GetNamaKaryawan($item->kabag);
					if ($result1) {
						$item->kabag = '<button class="btn btn-outline-info btn-mini" title="Details" onclick="btnDetails('.substr($item->kabag,4,7).')"><i class="icofont icofont-ui-zoom-in"></i>'.$result1->idfp.'</button>';
					} else {
						$item->kabag = "Tidak Ada Kabag";
					}
					
					$item->tanggal = date_ind("d M Y",$item->tanggal);
					$item->button_action = $btnAction;

					$data[] = $item;		
			}
			return $this->logabsensiModel->findDataTableOutput($data,$search);
		}
	}
	
	public function ajax_list_extra()
	{
		parent:: checkLoginUser();

		if($this->isPost()){
			$data = array();
			$orderBy = array(null,null,"tanggal","kode_kabag","idfp","nama","jabatan","masuk","keluar","total");
			$search = array("kode_kabag","nama");

			$result = $this->ExtraabsensiModel->findDataTable($orderBy,$search);
			foreach ($result as $item) {
				$btnAction= "";
					if (date("Y-m-d")<=$item->tanggal) {
						$btnAction = '<button class="btn btn-warning btn-outline-warning btn-mini" onclick="btnEdit1('.$item->id.')"><i class="fa fa-pencil-square-o"></i></button>';
					}

					$btnAction .= '&nbsp;&nbsp;<button class="btn btn-outline-info btn-mini" title="Detail11" onclick="btnDetail11('.$item->id.')"><i class="icofont icofont-ui-zoom-in"></i></button>';
				
			
					$result1 = $this->ExtraabsensiModel->GetNamaKaryawan($item->kode_kabag);
					if ($result1) {
						$item->kode_kabag = '<button class="btn btn-outline-info btn-mini" title="Details" onclick="btnDetails('.substr($item->kode_kabag,4,7).')"><i class="icofont icofont-ui-zoom-in"></i>'.$result1->idfp.'</button>';
					} else {
						$item->kode_kabag = "Data Kabag Tidak Ditemukan";
					}
					
					$item->tanggal = date_ind("d M Y",$item->tanggal);
					$item->button_action = $btnAction;

					$data[] = $item;		
			}
			return $this->ExtraabsensiModel->findDataTableOutput($data,$search);
		}
	}


	public function ajax_list_disiplin()
	{
		parent:: checkLoginUser();

		if($this->isPost()){
			$data = array();
			$orderBy = array(null,"idfp","nama","jabatan");
			$search = array("nama","idfp");
			$result = $this->DisiplinModel->findDataTable($orderBy,$search);
			foreach ($result as $item) {
					
			
					$result1 = $this->DisiplinModel->GetNamaKaryawan($item->kode);
					$item->nama = $result1->nama;
					$item->jabatan = $result1->jabatan;
					$item->total = (intval($item->total/60))." Jam";
					$data[] = $item;

			}
			return $this->DisiplinModel->findDataTableOutput($data,$search);
		}
	}

	public function getKabag1($kode)
	{
		if ($this->isPost()) {
			$getById = $this->logabsensiModel->getKabag1($kode);
			// var_dump($getById);
			// exit();
			if ($getById) {
				if ($getById->foto != "") {
					$getById->foto = base_url("/")."uploads/master/karyawan/orang/".$getById->foto;
				} else {
					$getById->foto = base_url("/")."assets/images/default/no_user.png";
				}
				$this->response->status = true;
				$this->response->message = "Data Kabag get by id";
				$this->response->data = $getById;
			} else {
				$this->response->message = alertDanger("Data tidak ada.");
			}
		}
		parent::json();

	}
	
	public function add()
	{
		parent::checkLoginUser(); // user login autentic checking

		if ($this->isPost()) {
			$kabag = $this->input->post("kabag");
			$tanggal = $this->input->post("tanggal");
			$karyawan = $this->input->post('karyawan');
			$shift = $this->input->post('shift');
			$masuk = $this->input->post('masuk');
			$break_out = $this->input->post('BreakOut');
			$break_in = $this->input->post('BreakIn');
			$keluar = $this->input->post('keluar');
			$status= $this->input->post('status');
			$time1 = strtotime($masuk);
			$time2 = strtotime($keluar);
			$time3 = strtotime($break_out);
			$time4 = strtotime($break_in);
			$hari =	date("l", strtotime($tanggal));
			$token1 = round(abs($time1 - $time2)/60);
			$token2 = round(abs($time3 - $time4)/60);
			$token = $token1 - $token2;
			$total = $token1 - $token2;

			function decimalMenit($time)
			{
				$time = explode(":", $time);
				return ($time[0]*60) + ($time[1]);
			}

			$this->form_validation->set_rules('kabag', 'Kabag', 'trim|required');
			$this->form_validation->set_rules('tanggal', 'Tanggal', 'trim|required');
			$this->form_validation->set_rules('karyawan', 'Karyawan', 'trim|required');
			$this->form_validation->set_rules('shift', 'Shift', 'trim|required');
			$this->form_validation->set_rules('masuk', 'Masuk', 'trim|required');

			if ($this->form_validation->run() == TRUE) {

				$checkDataJadwal =  $this->logabsensiModel->checkDataJadwal($tanggal,$karyawan);
				if (sizeof($checkDataJadwal) >0 && $checkDataJadwal[0]->kode == $karyawan) { 
				
					$this->response->status = false;
					$this->response->message = alertDanger("Data Karyawan Yang Di Input dengan tanggal yang anda pilih sudah ada.!");
				} else {
					$CheckShift =  $this->logabsensiModel->shiftIdByName($shift);
					$jam_masuk = strtotime($CheckShift->masuk);
					$jam_keluar = strtotime($CheckShift->keluar);
					
					$telat = 0;
					if (strtotime($masuk) > $jam_masuk) {
						$telat = round(abs($time1 - $jam_masuk)/60);
					}
					$durasiBreak = $CheckShift->break_durasi;
					$telat_break = 0;
					if (($break_out != null || $break_out != "") AND ($break_in != null || $break_in != "")) {
						if ($CheckShift->opsi_break == 1) { // opsi break durasi
							if ($token2 > $CheckShift->break_durasi) {
								$telat_break = ($token2 - $CheckShift->break_durasi);
							}
						} elseif ($CheckShift->opsi_break == 2) { // opsi break tetap
							$telat_break = 0;
							if ($time4 > strtotime($CheckShift->break_in)) {
								$telat_break = round(abs(strtotime($CheckShift->break_in) - $time4)/60);
							}
						}
					}
					if ($shift == "MANUAL") {
						$token = $token1-$token2;
					} elseif ($keluar == "" || $keluar == null) {
						$keluar = null;
						$token = 0;
					} else {
						if ($shift == "SHIFT-MALAM-12-JAM" || $shift == "SHIFT-PAGI-12-JAM") {
							if ($shift == "SHIFT-MALAM-12-JAM") {
								if ($time2 < $time1) {
									$heightJam = strtotime("23:59:00");
									$countMasuk = round(abs($heightJam - $time1)/60);
									$countKeluar = decimalMenit($keluar);
									$totalMasukKeluar = (($countMasuk + 1) + $countKeluar);
									if ($totalMasukKeluar >= 720) {
										$token = 720;
									}
								} else {
									if ($token1 >= 720) {
										$token = 720;
									}
								}
							} else {
								if ($token1 >= 720) {
									// $token = 660;
									$token = 720;
								}
							}
						} else {
							if ($shift == "SHIFT-SORE") {
								if ($time2 < $time1) {
									$heightJam = strtotime("23:59:00");
									$countMasuk = round(abs($heightJam - $time1)/60);
									$countKeluar = decimalMenit($keluar);
									$totalMasukKeluar = (($countMasuk + 1) + $countKeluar);
									if ($totalMasukKeluar >= 480) {
										$token = 480;
									}
								} else {
									if ($token1 >= 480) {
										$token = 480;
									}
								}
							} else {
								if ($token1 >= 480) {
									$token = 480;
								}
							}	
						}
					}
					$break_in = $break_in != "" ? $break_in : null;
					$break_out = $break_out != "" ? $break_out : null;
					$data = array(
									"kabag"	=>	$kabag,
									"kode" => $karyawan,
									"masuk" => $masuk,
									"break_out" => $break_out,
									"break_in" => $break_in,
									"keluar" => $keluar,
									"tanggal"	=>	$tanggal,
									"shift"	=>	$shift,
									"kerja" => $status,
									"jam_masuk" => $CheckShift->masuk,
									"jam_keluar" => $CheckShift->keluar,
									"telat" => $telat,
									// "pulang_cepat" => $pulang_cepat,
									"durasi_break"	=> 	$durasiBreak,
									"telat_break"	=>	$telat_break,
									"token" => $token,
									"ket_masuk" => "Ditambahkan Manual,Masuk ke Kabag Kerja  ".$status." ".$tanggal."	".$masuk,
									"ket_keluar" => "Ditambahkan Manual, Keluar Dari Kebag Kerja  ".$status." ".$tanggal."	".$keluar 

								);
					
					$insert = $this->logabsensiModel->insert($data);
					if ($insert) {
						$this->response->status = true;
						$this->response->message = alertSuccess("Berhasil tambah Log Absensi.");
					} else {
						$this->response->message =alertDanger("Gagal menambah data karyawan.");
					}
				}
			} else {
				$this->response->message = validation_errors('<span style="color:red;">', '</span><br>');
				$this->response->error = array(
									"kabag"	=> form_error("kabag",'<span style="color:red;">','</span>'),	
									"karyawan"	=> form_error("karyawan",'<span style="color:red;">','</span>'),
									"tanggal"	=> form_error("tanggal",'<span style="color:red;">','</span>'),
								);
			}
			
		}
		parent::json();
	}

	public function addExtra()
	{
		parent::checkLoginUser(); // user login autentic checking

		if ($this->isPost()) {
			$kabag = $this->input->post("kabag");
			$tanggal = $this->input->post("tanggal");
			$karyawan = $this->input->post('karyawan');
			$masuk = $this->input->post('masuk');
			$keluar = $this->input->post('keluar');
			$status= $this->input->post('status');
			$time1 = strtotime($masuk);
			$time2 = strtotime($keluar);
			$hari =	date("l", strtotime($tanggal));
			$token1 = round(abs($time1 - $time2)/60);
			$total = $token1;

			$checkDataJadwal =  $this->ExtraabsensiModel->checkDataJadwal($tanggal,$karyawan);
				

			if (sizeof($checkDataJadwal) >0 && $checkDataJadwal[0]->kode == $karyawan) { 
			
				$this->response->status = false;
				$this->response->message = alertDanger("Data Karyawan Yang Di Input Hari ini Tidak Boleh Sama");
			}
			else {

			$this->form_validation->set_rules('kabag', 'Kabag', 'trim|required');
			$this->form_validation->set_rules('tanggal', 'Tanggal', 'trim|required');
			$this->form_validation->set_rules('karyawan', 'Karyawan', 'trim|required');


			if ($this->form_validation->run() == TRUE) {
				$data = array(
								"kode_kabag"	=>	$kabag,
								"kode" => $karyawan,
								"masuk" => $masuk,
								"keluar" => $keluar,
								"tanggal"	=>	$tanggal,
								// "kerja" => $status,
								"masuk" => $masuk,
								"keluar" => $keluar,
								"total" => $total,
								"ket_masuk" => "Ditambahkan Manual,Masuk ke Kabag Kerja  ".$status." ".$tanggal."	".$masuk,
								"ket_keluar" => "Ditambahkan Manual, Keluar Dari Kebag Kerja  ".$status." ".$tanggal."	".$keluar 

							);
				$insert = $this->ExtraabsensiModel->insert($data);
				if ($insert) {

					$this->response->status = true;
					$this->response->message = alertSuccess("Berhasil tambah Log Absensi.");
				} else {
					$this->response->message =alertDanger("Gagal menambah data karyawan.");
				}

			} else {
				// $this->response->message = validation_errors('<span style="color:red;">', '</span><br>');
				$this->response->error = array(
									"kabag"	=> form_error("kabag",'<span style="color:red;">','</span>'),	
									"karyawan"	=> form_error("karyawan",'<span style="color:red;">','</span>'),
									"tanggal"	=> form_error("tanggal",'<span style="color:red;">','</span>'),
								);
			}
		  }
		}
		parent::json();
	}
 
	public function allKaryawanAjax()
	{
		parent::checkLoginUser(); // user login autentic checking

		if ($this->isPost()) {
			if (!isset($_POST["searchTerm"])) {
				$dataKaryawan = $this->logabsensiModel->getAllKaryawanAjax();
			} else {
				$searchTerm = $this->input->post("searchTerm");
				$dataKaryawan = $this->logabsensiModel->getAllKaryawanAjaxSearch($searchTerm);
			}

			$data = array();
			// $data[] = array("id"=>"0", "text"=> "--Pilih Karyawan--");
			foreach ($dataKaryawan as $val) {
				$data[] = array("id"=>$val->id, "text"=>$val->nama);
			}
			
			parent::json($data);
		}

	}

	public function update($id)
	{
		parent::checkLoginUser(); // user login autentic checking

		if ($this->isPost()) {
			$karyawan = $this->input->post('karyawan');
			$tanggal = $this->input->post("tanggal");
			$masuk = $this->input->post('masuk');
			$break_out = $this->input->post('BreakOut');
			$break_in = $this->input->post('BreakIn');
			$keluar = $this->input->post('keluar');
			$status= $this->input->post('status');
			$shift = $this->input->post('shift');
			$time1 = strtotime($masuk);
			$time2 = strtotime($keluar);
			$time3 = strtotime($break_out);
			$time4 = strtotime($break_in);
			$token1 = round(abs($time1 - $time2)/60);
			$token2 = round(abs($time3 - $time4)/60);
			$token = $token1 - $token2;
			// $checkDataJadwal =  $this->logabsensiModel->checkDataJadwal($tanggal,$karyawan);
			$hari =	date("l", strtotime(date("Y-m-d")));
			$getById = $this->logabsensiModel->getById($id);
			if ($getById) {
				$hari =	date("l", strtotime($getById->tanggal));
			}

			function decimalMenit($time)
			{
				$time = explode(":", $time);
				return ($time[0]*60) + ($time[1]);
			}

			$this->form_validation->set_rules('karyawan', 'Karyawan', 'trim|required');
			$this->form_validation->set_rules('shift', 'Shift', 'trim|required');
			$this->form_validation->set_rules('masuk', 'Masuk', 'trim|required');

			if ($this->form_validation->run() == TRUE) {

				$CheckShift =  $this->logabsensiModel->shiftIdByName($shift);
				$jam_masuk = strtotime($CheckShift->masuk);
				$jam_keluar = strtotime($CheckShift->keluar);

				$telat = 0;
				if (strtotime($masuk) > $jam_masuk) {
					$telat = round(abs($time1 - $jam_masuk)/60);
				}
				$durasiBreak = $CheckShift->break_durasi;
				$telat_break = 0;
				if (($break_out != null || $break_out != "") AND ($break_in != null || $break_in != "")) {
					if ($CheckShift->opsi_break == 1) { // opsi break durasi
						if ($token2 > $CheckShift->break_durasi) {
							$telat_break = ($token2 - $CheckShift->break_durasi);
						}
					} elseif ($CheckShift->opsi_break == 2) { // opsi break tetap
						$telat_break = 0;
						if ($time4 > strtotime($CheckShift->break_in)) {
							$telat_break = round(abs(strtotime($CheckShift->break_in) - $time4)/60);
						}
					}
				}
				if ($shift == "MANUAL") {
						$token = $token;
				} elseif ($keluar == "" || $keluar == null) {
					$keluar = null;
					$token = 0;
				} else {
					if ($shift == "SHIFT-MALAM-12-JAM" || $shift == "SHIFT-PAGI-12-JAM") {
						if ($shift == "SHIFT-MALAM-12-JAM") {
							if ($time2 < $time1) {
								$heightJam = strtotime("23:59:00");
								$countMasuk = round(abs($heightJam - $time1)/60);
								$countKeluar = decimalMenit($keluar);
								$totalMasukKeluar = (($countMasuk + 1) + $countKeluar);
								if ($totalMasukKeluar >= 720) {
									$token = 720;
								}
							} else {
								if ($token1 >= 720) {
									$token = 720;
								}
							}
						} else {
							if ($token1 >= 720) {
								// $token = 660;
								$token = 720;
							}
						}
					} else {
						if ($shift == "SHIFT-SORE") {
							if ($time2 < $time1) {
								$heightJam = strtotime("23:59:00");
								$countMasuk = round(abs($heightJam - $time1)/60);
								$countKeluar = decimalMenit($keluar);
								$totalMasukKeluar = (($countMasuk + 1) + $countKeluar);
								if ($totalMasukKeluar >= 480) {
									$token = 480;
								}
							} else {
								if ($token1 >= 480) {
									$token = 480;
								}
							}
						} else {
							if ($token1 >= 480) {
								$token = 480;
							}
						}	
					}
				}
				
				$break_in = $break_in != "" ? $break_in : null;
				$break_out = $break_out != "" ? $break_out : null;
				$data = array(	
							"kode" => $karyawan,
							"masuk" => $masuk,
							"break_out" => $break_out,
							"break_in" => $break_in,
							"keluar"	=>	$keluar,
							"kerja" => $status,
							"shift"	=>	$shift,
							"jam_masuk" => $CheckShift->masuk,
							"jam_keluar" => $CheckShift->keluar,
							"telat" => $telat,
							// "pulang_cepat" => $pulang_cepat,
							"durasi_break"	=> 	$durasiBreak,
							"telat_break"	=>	$telat_break,
							"token" => $token,
							"ket_masuk" => "Di Update Manual,Masuk ke Kabag Kerja  ".$status." ".$tanggal."	".$masuk,
							"ket_keluar" => "Di Update Manual, Keluar Dari Kebag Kerja  ".$status." ".$tanggal."	".$keluar

						);
				$update = $this->logabsensiModel->update($id,$data);
				if ($update) {
					$this->response->status = true;
					$this->response->message = alertSuccess("Berhasil Update Log Absensi.");
				} else {
					$this->response->message =alertDanger("Gagal edit Log Absensi.");
				}
			} else {
				$this->response->message = validation_errors('<span style="color:red;">', '</span><br>');
				$this->response->error = array(
												"Karyawan"	=> form_error("karyawan",'<span style="color:red;">','</span>')
											);
			}
		}
		parent::json();
	}

	public function updateExtra($id)
	{
		parent::checkLoginUser(); // user login autentic checking

		if ($this->isPost()) {
			$karyawan = $this->input->post('karyawan');
			$masuk = $this->input->post('masuk');
			$tanggal = $this->input->post("tanggal");
			$keluar = $this->input->post('keluar');
			$status= $this->input->post('status');
			$time1 = strtotime($masuk);
			$time2 = strtotime($keluar);
			$hari =	date("l", strtotime($tanggal));
			$token1 = round(abs($time1 - $time2)/60);
			$total = $token1;
			$this->form_validation->set_rules('karyawan', 'Karyawan', 'trim|required');

			if ($this->form_validation->run() == TRUE) {
				$data = array(	
								"kode" => $karyawan,
								"masuk" => $masuk,
								"keluar"	=>	$keluar,
								"total" => $total,
								"ket_masuk" => "Ditambahkan Manual,Masuk ke Kabag Kerja  ".$status." ".$tanggal."	".$masuk,
								"ket_keluar" => "Ditambahkan Manual, Keluar Dari Kebag Kerja  ".$status." ".$tanggal."	".$keluar 

							);
				$update = $this->ExtraabsensiModel->update($id,$data);
				if ($update) {
					$this->response->status = true;
					$this->response->message = alertSuccess("Berhasil Update Log Absensi.");
				} else {
					$this->response->message =alertDanger("Gagal edit Log Absensi.");
				}

			} else {
				// $this->response->message = validation_errors('<span style="color:red;">', '</span><br>');
				$this->response->error = array(
									
									"Karyawan"	=> form_error("karyawan",'<span style="color:red;">','</span>')
									
					
								);
			}
		}
		parent::json();
	}

	public function getId($id)
	{
		parent::checkLoginUser(); // user login autentic checking

		if ($this->isPost()) {
			$getById = $this->logabsensiModel->getById($id);
			if ($getById) {
				$getById->lat_checkin = floatval($getById->latitudecheckin);
				$getById->long_checkin = floatval($getById->longitudecheckin);
				$getById->lat_breakout = floatval($getById->latitudebreakout);
				$getById->long_breakout = floatval($getById->longitudebreakout);
				$getById->lat_breakin = floatval($getById->latitudebreakin);
				$getById->long_breakin = floatval($getById->longitudebreakin);
				$getById->lat_checkout = floatval($getById->latitudecheckout);
				$getById->long_checkout = floatval($getById->longitudecheckout);

				$getById->text = $getById->nama." - <small>".$getById->idfp."</small>";
				if ($getById->kabag != "") {
					$dataKabag = $this->logabsensiModel->GetNamaKaryawan($getById->kabag);
					if ($dataKabag) {
						$getById->kabag = $dataKabag->nama;
					} else {
						$getById->kabag = "Data kabag tidak ditemukan";
					}
				} else {
					$getById->kabag = "Data kabag tidak ditemukan";
				}
				$this->response->status = true;
				$this->response->message = "Data Absensi get by id";
				$this->response->data = $getById;
			} else {
				$this->response->message = alertDanger("Data tidak ada.");
			}
		}
		parent::json();
	}
	public function getId1($id)
	{
		parent::checkLoginUser(); // user login autentic checking

		if ($this->isPost()) {
			$getById1 = $this->ExtraabsensiModel->getById1($id);
			if ($getById1) {
				$dataKabag = $this->logabsensiModel->GetNamaKaryawan($getById1->kode_kabag);
				if ($dataKabag) {
					$getById1->kode_kabag = $dataKabag->nama;
				} else {
					$getById1->kode_kabag = "Data kabag tidak ditemukan";
				}
				$this->response->status = true;
				$this->response->message = "Data Absensi get by id";
				$this->response->data = $getById1;
			} else {
				$this->response->message = alertDanger("Data tidak ada.");
			}
		}
		parent::json();
	}

	public function ajaxAllKaryawanSelect2($otoritas=false)
	{
		parent::checkLoginUser(); // user login autentic checking

		// if ($this->isPost()) {
			$where = false;
			if ($otoritas) {
				$where = array("master_karyawan.otoritas" => $otoritas);
			}
			if (!isset($_POST["searchTerm"])) {
				$dataKaryawan = $this->logabsensiModel->getAllKaryawanSelect2Ajax($where);
			} else {
				$search = $this->input->post("searchTerm");
				$searchTerm = array("master_karyawan.nama" => $search);
				$dataKaryawan = $this->logabsensiModel->getAllKaryawanSelect2Ajax($where,$searchTerm);
			}
			if ($dataKaryawan) {
				foreach ($dataKaryawan as $val) {
					$val->id = $val->kode_karyawan;
					$val->text = $val->nama." - <small>".$val->idfp."</small>";
					$val->tanggal_lahir_indo = date_ind("d M Y", $val->tgl_lahir);
				}
				$this->response->status = true;
				$this->response->message = "Data karyawan select2 ajax";
				$this->response->data = $dataKaryawan;
			} else {
				$this->response->message = "Data karyawan select2 ajax tidak ada";
			}
		// }
		parent::json();
	}
}

/* End of file LogAbsensi.php */
/* Location: ./application/controllers/aktivitas/LogAbsensi.php */