<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class LogAbsensi extends MY_Controller {

	public function __construct()
	{
		parent::__construct();
		$this->load->model('aktivitas/LogAbsensi_model',"logabsensiModel");
		$this->load->model('master/Karyawan_model', "karyawanModel");
		$this->load->model('aktivitas/ExtraAbsensi_model',"ExtraabsensiModel");
		$this->load->model('master/Golongan_model',"golonganModel");
		$this->load->model('aktivitas/Disiplin_model',"DisiplinModel");
		$this->load->model('Setting_model',"settingModel");
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

	public function ajax_list($before=null,$after=null)
	{
		parent::checkLoginUser(); // user login autentic checking

		if ($this->isPost()) {
			$data = array();
			$orderBy = array(null,null,null,"tanggal","nama","token","payment","nama_jadwal","masuk","break_out","break_in","keluar","kabag","idfp","jabatan");
			$search = array("kabag","tanggal","idfp","nama","jabatan");

			$result = $this->logabsensiModel->findDataTable($orderBy,$search,array('absensi.status' => "Diterima"),false,$before,$after);
			foreach ($result as $item) {
					$btnAction= "";
					// if (date("Y-m-d")<=$item->tanggal) {
						if ($item->kerja == "Normal" || $item->kerja == "Ganti" || $item->kerja == "Normal(Manual)") {
							$btnAction = '<button class="btn btn-warning btn-outline-warning btn-mini" onclick="btnEdit('.$item->id_absensi.')"><i class="fa fa-pencil-square-o"></i></button>';
						}
					// }

					$btnAction .= '&nbsp;&nbsp;<button class="btn btn-outline-info btn-mini" title="Detail" onclick="btnDetail('.$item->id_absensi.')"><i class="icofont icofont-ui-zoom-in"></i></button>';

					$btnAction .= '&nbsp;&nbsp;<button class="btn btn-outline-success btn-mini" title="Kordinat maps" onclick="btnKordinat('.$item->id_absensi.')"><i class="icofont icofont-ui-map"></i></button>';

					$btnAction .= '&nbsp;&nbsp;<button class="btn btn-outline-danger btn-mini" title="Hapus" onclick="btnDelete('.$item->id_absensi.')"><i class="fa fa-trash-o"></i></button>';

					$result1 = $this->logabsensiModel->GetNamaKaryawan($item->kabag);
					if ($result1) {
						$item->kabag = '<button class="btn btn-outline-info btn-mini" title="Details" onclick="btnDetails('.substr($item->kabag,4,7).')"><i class="icofont icofont-ui-zoom-in"></i>'.$result1->idfp.'</button>';
					} else {
						$item->kabag = "Tidak Ada Kabag";
					}
					$item->token = $item->token ." menit";
					$item->payment = "Rp.".number_format($item->payment,0,",",",");
					$item->tanggal = date_ind("d M Y",$item->tanggal);
					$item->button_action = $btnAction;

					$data[] = $item;
			}
			return $this->logabsensiModel->findDataTableOutput($data,$search,array('absensi.status' => "Diterima"),false,$before,$after);
		}
	}

	public function ajax_list_extra($before=null,$after=null)
	{
		parent:: checkLoginUser();

		if($this->isPost()){
			$data = array();
			$orderBy = array(null,null,"tanggal","nama","masuk","keluar","total","payment","kode_kabag","idfp","jabatan");
			$search = array("kode_kabag","nama");
			$setting = $this->settingModel->getById(1);
			$where = array();
			if ($setting->extra_approval == 1) {
				$where["absensi_extra.status"]= "Diterima";
			}
			else {
				$where = null;
			}

			$result = $this->ExtraabsensiModel->findDataTable($orderBy,$search,$where,$before,$after);
			foreach ($result as $item) {
				$btnAction= "";
					// if (date("Y-m-d")<=$item->tanggal) {
						$btnAction = '<button class="btn btn-warning btn-outline-warning btn-mini" onclick="btnEdit1('.$item->id.')"><i class="fa fa-pencil-square-o"></i></button>';
					// }

					$btnAction .= '&nbsp;&nbsp;<button class="btn btn-outline-info btn-mini" title="Detail11" onclick="btnDetail11('.$item->id.')"><i class="icofont icofont-ui-zoom-in"></i></button>';


					$result1 = $this->ExtraabsensiModel->GetNamaKaryawan($item->kode_kabag);
					if ($result1) {
						$item->kode_kabag = '<button class="btn btn-outline-info btn-mini" title="Details" onclick="btnDetails('.substr($item->kode_kabag,4,7).')"><i class="icofont icofont-ui-zoom-in"></i>'.$result1->idfp.'</button>';
					} else {
						$item->kode_kabag = "Data Kabag Tidak Ditemukan";
					}
					$item->total = $item->total ." menit";
					$item->payment = "Rp.".number_format($item->payment,0,",",",");
					$item->tanggal = date_ind("d M Y",$item->tanggal);
					$item->button_action = $btnAction;

					$data[] = $item;
			}
			return $this->ExtraabsensiModel->findDataTableOutput($data,$search,$where,$before,$after);
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

	public function ajax_list_after($before,$after)
	{
		self::ajax_list($before,$after);
	}

	public function ajax_list_after_extra($before,$after)
	{
		self::ajax_list_extra($before,$after);
	}

	public function getJadwalKaryawan($kode)
	{
		if ($this->isPost()) {
			if ($kode != null && $kode != "") {
				$shiftKaryawan = $this->logabsensiModel->getShiftKaryawan($kode);

				$jadwalKaryawan = $this->logabsensiModel->getShiftJadwal($shiftKaryawan->shift == "ya" ? 1 : 0);
				if ($jadwalKaryawan) {
					$this->response->status = true;
					$this->response->message = "berhasil get data jadwal karyawan";
					$this->response->data = $jadwalKaryawan;
				} else {
					$this->response->message = alertDanger("Data tidak ada.");
				}
			}

		}
		parent::json();
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
			$tanggalKeluar = $this->input->post('tanggalKeluar');

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
					$CheckShift =  $this->logabsensiModel->getByIdJadwal($shift);
					$jam_masuk = strtotime($CheckShift->masuk);
					$jam_keluar = strtotime($CheckShift->keluar);
					// *5 = Jadwal Malam
				$telat = 0;
				if ($shift == "5") {
					if ($masuk > "23:00:00") {
						$telat = 0;
					} else if ($masuk > "00:00:00") {
						$telat = round(abs($time1 - $jam_masuk)/60);
					}
				} else {
					if ($time1 > $jam_masuk) {
					$telat = round(abs($time1 - $jam_masuk)/60);
					}
				}
					$durasiBreak = $CheckShift->break_durasi;
					$telat_break = 0;
					if (($break_out != null || $break_out != "") && ($break_in != null || $break_in != "")) {
						if ($CheckShift->opsi_break == 1) { // opsi break durasi
							if ($token2 > $CheckShift->break_durasi) {
								$telat_break = ($token2 - $CheckShift->break_durasi);
							}
						} else if ($CheckShift->opsi_break == 2) { // opsi break tetap
							$telat_break = 0;
							if ($time4 > strtotime($CheckShift->break_in)) {
								$telat_break = round(abs(strtotime($CheckShift->break_in) - $time4)/60);
							}
						}
					}

					// *8 = Jadwal Security Malam
					// *7 = Jadwal Security Pagi
					// *4 = Jadwal Sore
						if ($shift == "8" || $shift == "7") {
							if ($shift == "8") {
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
							if ($shift == "4") {
								if ($time2 > $time1) {
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

					$break_in = $break_in != "" ? $break_in : null;
					$break_out = $break_out != "" ? $break_out : null;
					// $id_jadwal = $this->logabsensiModel->getByIdJadwal($shift);
					// $nama_shift = "";
					// if ($id_jadwal) {
					// 	$nama_shift = $id_jadwal->nama_jadwal;
					// }

					$golongan = $this->logabsensiModel->getGolonganByKode($karyawan);
					$maxjamkerja = $this->logabsensiModel->getmaxjamKerja($shift);
					$setting = $this->settingModel->getById(1);
					$payment = 0;
					$tokenpayment = 0;

					if ($token > $maxjamkerja->max_jam_kerja) {
						$token = $maxjamkerja->max_jam_kerja;
					}
					if ($keluar == "" || $keluar == null) {
						$keluar = null;
						$token = 0;
					}
					else {
						if ($golongan->gaji != 0 && $golongan->umk != 0) {
							$tokenpayment = $golongan->gaji / $maxjamkerja->max_jam_kerja;
							$payment = ($token * $tokenpayment) + $golongan->makan + $golongan->transport;
						}
						else if ($golongan->gaji !=0 && $golongan->umk == 0) {
							$tokenpayment = $golongan->gaji / $maxjamkerja->max_jam_kerja;
							$payment = ($token * $tokenpayment) + $golongan->makan + $golongan->transport;
						}
						else if ($golongan->gaji == 0 && $golongan->umk != 0) {
							$tokenpayment = ($golongan->umk / $setting->total_hari) / $maxjamkerja->max_jam_kerja;
							$payment = ($token * $tokenpayment) + $golongan->makan + $golongan->transport;
						}
					}

					$data = array(
									"kabag"	=>	$kabag,
									"kode" => $karyawan,
									"masuk" => $masuk,
									"break_out" => $break_out,
									"break_in" => $break_in,
									"keluar" => $keluar,
									"tanggal"	=>	$tanggal,
									"tanggal_keluar" => $tanggalKeluar,
									// "shift"	=>	$nama_shift,
									"id_jadwal" => $shift,
									"kerja" => $status,
									"jam_masuk" => $maxjamkerja->masuk,
									"jam_keluar" => $maxjamkerja->keluar,
									"telat" => $telat,
									// "pulang_cepat" => $pulang_cepat,
									"durasi_break"	=> 	$durasiBreak,
									"telat_break"	=>	$telat_break,
									"token" => $token,
									"ket_masuk" => "Ditambahkan Manual,Masuk ke Kabag Kerja  ".$status." ".$tanggal."	".$masuk,
									"ket_keluar" => "Ditambahkan Manual, Keluar Dari Kebag Kerja  ".$status." ".$tanggal."	".$keluar,
									"status" => "Diterima",
									"payment" => $payment
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
			$tanggalKeluar = $this->input->post('tanggalKeluar');
			$karyawan = $this->input->post('karyawan');
			$masuk = $this->input->post('masuk');
			$keluar = $this->input->post('keluar');
			$status= $this->input->post('status');
			$time1 = strtotime($masuk);
			$time2 = strtotime($keluar);
			$hari =	date("l", strtotime($tanggal));
			$token1 = round(abs($time2 - $time1)/60);
			$total = $token1;

			function decimalMenit($time)
			{
				if ($time != "") {
					$time = explode(":", $time);
					return ($time[0]*60) + ($time[1]);
				}
				else {
					return $time;
				}

			}


			$checkDataJadwal =  $this->ExtraabsensiModel->checkDataJadwal($tanggal,$karyawan);


			if (sizeof($checkDataJadwal) > 0 && $checkDataJadwal[0]->kode == $karyawan) {

				$this->response->status = false;
				$this->response->message = alertDanger("Data Karyawan Yang Di Input Hari ini Tidak Boleh Sama");
			}
			else {

			$this->form_validation->set_rules('kabag', 'Kabag', 'trim|required');
			$this->form_validation->set_rules('tanggal', 'Tanggal', 'trim|required');
			$this->form_validation->set_rules('karyawan', 'Karyawan', 'trim|required');


			if ($this->form_validation->run() == TRUE) {
				// if ($time2 < $time1) {
				// 	$heightJam = strtotime("23:59:00");
				// 	$countMasuk = round(abs($heightJam - $time1)/60);
				// 	$countKeluar = decimalMenit($keluar);
				// 	$totalMasukKeluar = (($countMasuk + 1) + $countKeluar);
				// 	$total = $totalMasukKeluar;
				// }

				if ($tanggalKeluar > $tanggal) {
					$heightJam = strtotime("23:59:00");
					$countMasuk = round(abs($heightJam - $time1)/60);
					$countKeluar = decimalMenit($keluar);
					$totalMasukKeluar = (($countMasuk + 1) + $countKeluar);
					$total = $totalMasukKeluar;
				}
				$golongan = $this->golonganModel->getGolongan($karyawan);
				$setting = $this->settingModel->getById(1);
				$totalExtra = 0;
				$breakSetting = 0;

				if ($setting->extra_break == 1) {
					if ($setting->lembur3 != 0) {
						if ($setting->lembur2 != 0) {
							if ($setting->lembur1 != 0) {
								// code...

							}
							else {
								$breakSetting = $setting->break1;
							}
						}
						else {
							if ($setting->lembur1 != 0) {
								$breakSetting = $setting->break2;

							}
							else {
								$breakSetting = $setting->break2;
							}
						}
					}
					else {
						if ($setting->lembur2 != 0) {
							if ($setting->lembur1 != 0) {
								// code...

							}
							else {
								$breakSetting = $setting->break3;
							}
						}
						else {
							if ($setting->lembur1 != 0) {
								$breakSetting = $setting->break3;

							}
							else {
								$breakSetting = $setting->break3;
							}
						}
					}
				}

				if ($golongan->lembur == 1) {
						if ($total <= 60) {
							$totalExtra += 1.5 * ((1/173*$golongan->umk)/60) * ($total - $breakSetting);
						}
						else {
							$totalExtra += 1.5 * ((1/173*$golongan->umk)/60) * $total;
							$totalExtra += 2 * ((1/173*$golongan->umk)/60) * ($total - 60 - $breakSetting);
						}
				}
				else {
					if ($golongan->lembur_tetap == 0 || $golongan->lembur_tetap == null) {
						$golongan->lembur_tetap = 0;
					}
					$totalExtra += ($golongan->lembur_tetap/60) * ($total - $breakSetting);
				}


				$data = array(
								"kode_kabag"	=>	$kabag,
								"kode" => $karyawan,
								"masuk" => $masuk,
								"keluar" => $keluar,
								"tanggal"	=>	$tanggal,
								"tanggal_keluar" => $tanggalKeluar,
								"total" => $total,
								"payment" => $totalExtra,
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
			$tanggalKeluar = $this->input->post('tanggalKeluar');

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

				$CheckShift =  $this->logabsensiModel->getByIdJadwal($shift);
				$jam_masuk = strtotime($CheckShift->masuk);
				$jam_keluar = strtotime($CheckShift->keluar);
				// *5 = Jadwal Malam
				$telat = 0;
				if ($shift == "5") {
					if ($masuk > "23:00:00") {
						$telat = 0;
					} elseif ($masuk > "00:00:00") {
						$telat = round(abs($time1 - $jam_masuk)/60);
					}
				} else {
					if ($time1 > $jam_masuk) {
					$telat = round(abs($time1 - $jam_masuk)/60);
					}
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
				// if ($shift == "MANUAL") {
				// 		$token = $token;
				// } elseif ($keluar == "" || $keluar == null) {
				// 	$keluar = null;
				// 	$token = 0;
				// } else {
				// *8 = Jadwal Security Malam
				// *7 = Jadwal Security Pagi
				// *4 = Jadwal Sore
					if ($shift == "8" || $shift == "7") {
						if ($shift == "8") {
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
						if ($shift == "4") {
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
				// }

				$break_in = $break_in != "" ? $break_in : null;
				$break_out = $break_out != "" ? $break_out : null;
				// $id_jadwal = $this->logabsensiModel->getByIdJadwal($shift);
				// 	$nama_shift = "";
				// 	if ($id_jadwal) {
				// 		$nama_shift = $id_jadwal->nama_jadwal;
				// 	}

				$golongan = $this->logabsensiModel->getGolonganByKode($karyawan);
				$maxjamkerja = $this->logabsensiModel->getmaxjamKerja($shift);
				$setting = $this->settingModel->getById(1);
				$payment = 0;
				$tokenpayment = 0;

				if ($token > $maxjamkerja->max_jam_kerja) {
					$token = $maxjamkerja->max_jam_kerja;
				}
				if ($keluar == "" || $keluar == null) {
					$keluar = null;
					$token = 0;
				}
				else {
					if ($golongan->gaji != 0 && $golongan->umk != 0) {
						$tokenpayment = $golongan->gaji / $maxjamkerja->max_jam_kerja;
						$payment = ($token * $tokenpayment) + $golongan->makan + $golongan->transport;
					}
					else if ($golongan->gaji !=0 && $golongan->umk == 0) {
						$tokenpayment = $golongan->gaji / $maxjamkerja->max_jam_kerja;
						$payment = ($token * $tokenpayment) + $golongan->makan + $golongan->transport;
					}
					else if ($golongan->gaji == 0 && $golongan->umk != 0) {
						$tokenpayment = ($golongan->umk / $setting->total_hari) / $maxjamkerja->max_jam_kerja;
						$payment = ($token * $tokenpayment) + $golongan->makan + $golongan->transport;
					}
				}

				$data = array(
							"kode" => $karyawan,
							"masuk" => $masuk,
							"break_out" => $break_out,
							"break_in" => $break_in,
							"keluar"	=>	$keluar,
							"kerja" => $status,
							"tanggal_keluar" => $tanggalKeluar,
							// "shift"	=>	$nama_shift,
							"id_jadwal" => $shift,
							"jam_masuk" => $masuk,
							"jam_keluar" => $keluar,
							"telat" => $telat,
							// "pulang_cepat" => $pulang_cepat,
							"durasi_break"	=> 	$durasiBreak,
							"telat_break"	=>	$telat_break,
							"token" => $token,
							"ket_masuk" => "Di Update Manual,Masuk ke Kabag Kerja  ".$status." ".$tanggal."	".$masuk,
							"ket_keluar" => "Di Update Manual, Keluar Dari Kebag Kerja  ".$status." ".$tanggal."	".$keluar,
							'lupa_keluar_kabag' => 0,
							"status" => "Diterima",
							"payment" => $payment
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
			$kabag = $this->input->post('kabag');
			$masuk = $this->input->post('masuk');
			$tanggal = $this->input->post("tanggal");
			$tanggalKeluar = $this->input->post('tanggalKeluar');
			$keluar = $this->input->post('keluar');
			$status= $this->input->post('status');
			$time1 = strtotime($masuk);
			$time2 = strtotime($keluar);
			$hari =	date("l", strtotime($tanggal));
			$token1 = round(abs($time1 - $time2)/60);
			$total = $token1;
			function decimalMenit($time)
			{
				$time = explode(":", $time);
				return ($time[0]*60) + ($time[1]);
			}
			$this->form_validation->set_rules('karyawan', 'Karyawan', 'trim|required');

			if ($this->form_validation->run() == TRUE) {
				// if ($time2 < $time1) {
				// 	$heightJam = strtotime("23:59:00");
				// 	$countMasuk = round(abs($heightJam - $time1)/60);
				// 	$countKeluar = decimalMenit($keluar);
				// 	$totalMasukKeluar = (($countMasuk + 1) + $countKeluar);
				// 	$total = $totalMasukKeluar;
				// }
				if ($tanggalKeluar > $tanggal) {
					$heightJam = strtotime("23:59:00");
					$countMasuk = round(abs($heightJam - $time1)/60);
					$countKeluar = decimalMenit($keluar);
					$totalMasukKeluar = (($countMasuk + 1) + $countKeluar);
					$total = $totalMasukKeluar;
				}
				$golongan = $this->golonganModel->getGolongan($karyawan);
				$setting = $this->settingModel->getById(1);
				$totalExtra = 0;
				$breakSetting = 0;

				if ($setting->extra_break == 1) {
					if ($setting->lembur3 != 0) {
						if ($setting->lembur2 != 0) {
							if ($setting->lembur1 != 0) {
								// code...

							}
							else {
								$breakSetting = $setting->break1;
							}
						}
						else {
							if ($setting->lembur1 != 0) {
								$breakSetting = $setting->break2;

							}
							else {
								$breakSetting = $setting->break2;
							}
						}
					}
					else {
						if ($setting->lembur2 != 0) {
							if ($setting->lembur1 != 0) {
								// code...

							}
							else {
								$breakSetting = $setting->break3;
							}
						}
						else {
							if ($setting->lembur1 != 0) {
								$breakSetting = $setting->break3;

							}
							else {
								$breakSetting = $setting->break3;
							}
						}
					}
				}

				if ($golongan->lembur == 1) {
						if ($total <= 60) {
							$totalExtra += 1.5 * ((1/173*$golongan->umk)/60) * ($total - $breakSetting);
						}
						else {
							$totalExtra += 1.5 * ((1/173*$golongan->umk)/60) * $total;
							$totalExtra += 2 * ((1/173*$golongan->umk)/60) * ($total - 60 - $breakSetting);
						}
				}
				else {
					if ($golongan->lembur_tetap == 0 || $golongan->lembur_tetap == null) {
						$golongan->lembur_tetap = 0;
					}
					$totalExtra += ($golongan->lembur_tetap/60) * ($total - $breakSetting);
				}

				$data = array(
								"kode_kabag"	=>	$kabag,
								"kode" => $karyawan,
								"masuk" => $masuk,
								"keluar" => $keluar,
								"tanggal"	=>	$tanggal,
								"tanggal_keluar" => $tanggalKeluar,
								"total" => $total,
								"payment" => $totalExtra,
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
				$this->response->dataKabag = $dataKabag;
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

	public function insertTempAbsensi($id)
	{
		parent::checkLoginUser(); // user login autentic checking

		if ($this->isPost()) {

			$getTempAbsensi = $this->logabsensiModel->getTempAbsensi($id);
			if ($getTempAbsensi != 0) {
				$this->response->message = "data sedang diproses silahkan menungu approval dari owner.";
			}
			else {
				$insert = $this->logabsensiModel->insertTempAbsensi($id);
				if ($insert) {
					$this->response->status = true;
					$this->response->message = "berhasil menghapus data absensi silahkan menungu approval dari owner.";
				} else {
					$this->response->message = "gagal menghapus data absensi.";
				}
			}
		}
		parent::json();
	}

}

/* End of file LogAbsensi.php */
/* Location: ./application/controllers/aktivitas/LogAbsensi.php */
