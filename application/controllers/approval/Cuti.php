<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Cuti extends MY_Controller {

	public function __construct()
	{
		parent::__construct();
		$this->load->model('aktivitas/Cuti_model',"cutiModel");
		$this->load->model('Calendar_model',"calendarModel");
		$this->load->model('master/Karyawan_model',"karyawanModel");
		$this->load->model('aktivitas/LogAbsensi_model',"logabsensiModel");
		$this->load->model('Setting_model',"settingModel");
	  $this->load->model('aktivitas/Jadwal_karyawan_model',"JadwalKaryawanModel");
		$this->load->model('Token_model',"tokenModel");
		parent::checkLoginOwner();
	}

	public function index()
	{
		parent::checkLoginUser(); // user login autentic checking

		parent::headerTitle("Approval data > Cuti","Approval Data","Cuti");
		$breadcrumbs = array(
							"Approval Data"	=>	site_url('approval/cuti'),
							"Cuti"		=>	"",
						);
		parent::breadcrumbs($breadcrumbs);

		parent::viewApproval();
	}

	public function ajax_list()
	{
		parent::checkLoginUser(); // user login autentic checking

		if ($this->isPost()) {
			$data = array();

			$orderBy = array(null,null,null,"tanggal","nama","departemen","jabatan","status","jatah_cuti","keterangans","mulai_nerlaku","exp_date");
			$search = array("nama","keterangans");

			$result = $this->cutiModel->findDataTable($orderBy,$search);
			foreach ($result as $item) {
				if ($item->status == "Diterima") {
					$btnAction = 'Sudah di Terima';

				}elseif($item->status == "Ditolak"){
					$btnAction = 'Sudah di Tolak';
				}
				if($item->status == "Proses")
				{
					$btnTools = '<button class="btn btn-warning btn-outline-default btn-mini"><i class="fa fa-print"></i>Print</button>';
					$item->status = '<label class="label label-info">'.$item->status.'</label>';
					$btnAction = '<button class="btn btn-success btn-outline-success btn-mini" onclick="btnApproval('.$item->id_cuti.')"><i class="fa fa-check-square"></i>Terima</button>';
					$btnAction .= '&nbsp;&nbsp;&nbsp;<button class="btn btn-danger btn-outline-danger btn-mini" onclick="btnReject('.$item->id_cuti.')"><i class="fa fa-ban"></i>Tolak</button>';
				}
				else if($item->status == "Diterima")
				{
					$btnTools = '<button class="btn btn-default btn-outline-default btn-mini" onclick="btnPrint('.$item->id_cuti.')"><i class="fa fa-print"></i>Print</button>';
					$item->status = '<label class="label label-success">'.$item->status.'</label>';
				}
				else {
					$item->status = '<label class="label label-danger">'.$item->status.'</label>';
					$btnTools = '<button class="btn btn-warning btn-outline-default btn-mini"><i class="fa fa-print"></i>Print</button>';
				}


				$item->jatah_cuti = 12;
				$item->mulai_berlaku = date_ind("d M Y",$item->mulai_berlaku);
				$item->exp_date = date_ind("d M Y",$item->exp_date);
				$item->button_print = $btnTools;
				$item->button_action = $btnAction;
				$data[] = $item;
			}
			return $this->cutiModel->findDataTableOutput($data,$search);
		}
	}

	public function add()
	{
		parent::checkLoginUser(); // user login autentic checking

		if ($this->isPost()) {
			$tanggal = $this->input->post('tanggal');
			$karyawan = $this->input->post('karyawan');
			$mulaiCuti = $this->input->post('mulaiCuti');
			$akhirCuti = $this->input->post('akhirCuti');
			$keterangan = $this->input->post("keterangan");
			$date1=date_create($mulaiCuti);
			$date2=date_create($akhirCuti);
			$lama=date_diff($date1,$date2)->format("%a")+1;
			$this->form_validation->set_rules('karyawan', 'Karyawan', 'trim|required');
			$this->form_validation->set_rules('mulaiCuti', 'Mulai Cuti', 'trim|required');
			$this->form_validation->set_rules('akhirCuti', 'Akhir Cuti', 'trim|required');
			$this->form_validation->set_rules('keterangan', 'keterangan', 'trim|required');
			// will be change after setting table have been created
			$jatahCuti = 12;

			$data1 = $this->cutiModel->getIdKaryawan1($karyawan);
			$cutiBersama = $this->calendarModel->getallrow();
			$sisaCuti = $jatahCuti- $cutiBersama - $lama;
			//looping data for searcing sisaCuti
			foreach ($data1 as $item) {
								$sisaCuti -= $item->lama_cuti;
							}


			if ($this->form_validation->run() == TRUE) {
				$data = array(
								"tanggal"	=>	date("Y-m-d"),
								"id_karyawan" => $karyawan,
								"sisa_cuti" => $sisaCuti,
								"mulai_berlaku" => $mulaiCuti,
								"exp_date" => $akhirCuti,
								"lama_cuti " => $lama,
								"keterangans"=>	$keterangan
							);
					if($sisaCuti < 0 )
					{
						$this->response->status = false;
						$this->response->message = alertDanger("Jatah Cuti telah digunakan sampai habis.");
					}
					else {
						$insert = $this->cutiModel->insert($data);
						if ($insert) {

							$this->response->status = true;
							$this->response->message = alertSuccess("Berhasil tambah data Aktivitas Cuti.");
						} else {
							$this->response->message = alertDanger("Gagal tambah data Aktivitas Cuti.");
						}
					}
			} else {
				// $this->response->message = validation_errors('<span style="color:red;">', '</span><br>');
				$this->response->error = array(
									"karyawan"	=> form_error("karyawan",'<span style="color:red;">','</span>'),
									"mulaiCuti"	=> form_error("mulaiCuti",'<span style="color:red;">','</span>'),
									"akhirCuti"	=> form_error("akhirCuti",'<span style="color:red;">','</span>'),
									"keterangan"	=> form_error("keterangan",'<span style="color:red;">','</span>'),
								);
			}

		}
		parent::json();
	}

	public function getId($id)
	{
		parent::checkLoginUser(); // user login autentic checking

		if ($this->isPost()) {
			$getById = $this->cutiModel->getById($id);
			$cutiBersama = $this->calendarModel->getallrow();
			$jatahCuti = 12;
			if ($getById) {
				if ($getById->foto != "") {
					$getById->foto = base_url("/")."uploads/master/karyawan/orang/".$getById->foto;
				} else {
					$getById->foto = base_url("/")."assets/images/default/no_user.png";
				}
				$getById->cuti_bersama = $cutiBersama;
				$this->response->status = true;
				$this->response->message = "Data Cuti get by id";
				$this->response->data = $getById;
			} else {
				$this->response->message = alertDanger("Data tidak ada.");
			}
		}
		parent::json();
	}

	public function allKaryawanAjax()
	{
		parent::checkLoginUser(); // user login autentic checking

		if ($this->isPost()) {
			if (!isset($_POST["searchTerm"])) {
				$dataKaryawan = $this->cutiModel->getAllKaryawanAjax();
			} else {
				$searchTerm = $this->input->post("searchTerm");
				$dataKaryawan = $this->cutiModel->getAllKaryawanAjaxSearch($searchTerm);
			}

			$data = array();
			// $data[] = array("id"=>"0", "text"=> "--Pilih Karyawan--");
			foreach ($dataKaryawan as $val) {
				// $row = array();
				$data[] = array("id"=>$val->id, "text"=>$val->nama);
				// $data[] = $row;
			}
			/*if ($dataKaryawan) {
				$this->response->status = true;
				$this->response->message = "Data All karyawan";
				$this->response->data = $dataKaryawan;
			}*/
			parent::json($data);
		}

	}

	public function idKaryawan($id)
	{
		parent::checkLoginUser(); // user login autentic checking

		if ($this->isPost()) {
			$data = $this->cutiModel->getIdKaryawan($id);
			$data1 = $this->cutiModel->getIdKaryawan1($id);
			$cutiBersama = $this->calendarModel->getallrow();
			$sisaCuti = 12- $cutiBersama;
			$cutiDiambil = 0;
			if ($data) {
				foreach ($data1 as $item) {
					$sisaCuti -= $item->lama_cuti;
					$cutiDiambil += $item->lama_cuti;
				}
				$data->cuti_bersama = $cutiBersama;
				$data->sisa_cuti = $sisaCuti;
				$data->cuti_diambil = $cutiDiambil;
				if ($data->foto != "") {
					$data->foto = base_url("/")."uploads/master/karyawan/orang/".$data->foto;
				} else {
					$data->foto = base_url("/")."assets/images/default/no_user.png";
				}
				$this->response->status = true;
				$this->response->message = "data karyawan by id";
				$this->response->data = $data;
			}
		}
		parent::json();
	}

	public function getIdForPrint($id)
		{
			parent::checkLoginUser(); // user login autentic checking

			if ($this->isPost()) {
				$getById = $this->cutiModel->getById($id);
				$setting = $this->cutiModel->get_setting();
				if ($getById) {
					if ($setting->logo != "") {
						$setting->logo = base_url("/")."uploads/setting/".$setting->logo;
					} else {
						$setting->logo = base_url("/")."assets/images/default/no_file_.png";
					}
					$getById->setting = $setting;
					$getById->mulai_berlaku = date_ind("d M Y",$getById->mulai_berlaku);
					$getById->exp_date = date_ind("d M Y",$getById->exp_date);
					$getById->tanggal = date_ind("d M Y",$getById->tanggal);
					$this->response->status = true;
					$this->response->message = "data karyawan by id";
					$this->response->data = $getById;
				}
				else {
					$this->response->message = alertDanger("Data tidak ada.");
				}
			}
			parent::json();
		}

	public function update($id)
	{
		parent::checkLoginUser(); // user login autentic checking

		if ($this->isPost()) {

			$status = $this->input->post('status');;
			$getById = $this->cutiModel->getById($id);
			$getkaryawan = $this->karyawanModel->getById($getById->id_karyawan);
			$kabagGroup = $this->karyawanModel->checkGroupKabag($getkaryawan->id_grup);
			$kabag = "";
			if ($kabagGroup) {
				$kabag = $kabagGroup->kode_karyawan;
			}

			$data = array(
							"status" => $status
						);
			if($status == "Diterima"){
				$pesanNotif = "HRD <u style='color:green;'>Terima</u>";
				$pesanNotifAndroid = "Owner Approval";

				$dataCutiAbsensi = array();

				$start_date = date("Y-m-d", strtotime("-1 day", strtotime($getById->mulai_berlaku)));
				$end_date = $getById->exp_date;
				while (strtotime($start_date) < strtotime($end_date)) {

					$start_date = date ("Y-m-d", strtotime("+1 day", strtotime($start_date)));
					$dataJadwal = $this->karyawanModel->tanggalJadwal($start_date,$getById->id_karyawan);
					if ($dataJadwal != null) {
						$masuk = $dataJadwal->masuk;
						$keluar = $dataJadwal->keluar;
						$jam_masuk = $dataJadwal->masuk;
						$jam_keluar = $dataJadwal->keluar;
						$shift = $dataJadwal->shift;
						$token = 480;
						if ($dataJadwal->shift == "SHIFT-PAGI-12-JAM" || $dataJadwal->shift == "SHIFT-MALAM-12-JAM") {
							$token = 720;
						}
					} else {
						if ($getkaryawan && $getkaryawan->shift != "ya") {
							$this->load->model('master/Shift_model',"shiftModel");
							$hari = date("l", strtotime($start_date));
							$shiftRegular = $this->shiftModel->getByWhere(array("shift"=>"REGULAR","hari"=>$hari));
							if ($shiftRegular) {
								$masuk = $shiftRegular->masuk;
								$keluar = $shiftRegular->keluar;
								$jam_masuk = $shiftRegular->masuk;
								$jam_keluar = $shiftRegular->keluar;
								$shift = $shiftRegular->shift;
								$token = 480;
								$totalkerja = round(abs(strtotime($keluar) - strtotime($masuk))/60);
								if ($shiftRegular->opsi_break == 1) { // durasi
									$token = $totalkerja - $shiftRegular->durasi_break;
								} else if ($shiftRegular->opsi_break == 2) { // tetap
									$totalbreak = round(abs(strtotime($shiftRegular->break_out) - strtotime($shiftRegular->break_in))/60);
									$token = $totalkerja - $totalbreak;
								}
							} else {
								$shiftAbsensi = $this->karyawanModel->lastAbsensi($getkaryawan->kode_karyawan);
								if ($shiftAbsensi) {
									$masuk = $shiftAbsensi[0]->masuk;
									$keluar = $shiftAbsensi[0]->keluar;
									$jam_masuk = $shiftAbsensi[0]->jam_masuk;
									$jam_keluar = $shiftAbsensi[0]->jam_keluar;
									$shift = $shiftAbsensi[0]->shift;
									$token = $shiftAbsensi[0]->token;
								}
							}
						} else {
							$shiftAbsensi = $this->karyawanModel->lastAbsensi($getkaryawan->kode_karyawan);
							$masuk = "00:00:00";
							$keluar = "00:00:00";
							$jam_masuk = "00:00:00";
							$jam_keluar = "00:00:00";
							$shift = "";
							$token = 0;
							if ($shiftAbsensi) {
								$masuk = $shiftAbsensi[0]->masuk;
								$keluar = $shiftAbsensi[0]->keluar;
								$jam_masuk = $shiftAbsensi[0]->jam_masuk;
								$jam_keluar = $shiftAbsensi[0]->jam_keluar;
								$shift = $shiftAbsensi[0]->shift;
								$token = 480;
								if ($shiftAbsensi[0]->shift == "SHIFT-PAGI-12-JAM" || $shiftAbsensi[0]->shift == "SHIFT-MALAM-12-JAM") {
									$token = 720;
								}
							}
						}
					}

					$shiftAbsensi = $this->karyawanModel->lastAbsensiJadwal($getkaryawan->kode_karyawan);
					$golongan = $this->logabsensiModel->getGolonganByKode($getkaryawan->kode_karyawan);
					$jadwalId = $this->JadwalKaryawanModel->getidJadwal();
					$maxjamkerja = $this->logabsensiModel->getmaxjamKerja(sizeof($shiftAbsensi) == 0 ? $jadwalId->id_jadwal : $shiftAbsensi[0]->id_jadwal);
					$setting = $this->settingModel->getById(1);
					$tokenAbsensi = $setting->cuti;
					$payment = 0;
					$tokenpayment = 0;
					if ($golongan->gaji != 0 && $golongan->umk != 0) {
						$tokenpayment = $golongan->gaji / $maxjamkerja->max_jam_kerja;
						$payment = $tokenAbsensi + $golongan->makan + $golongan->transport;
					}
					else if ($golongan->gaji !=0 && $golongan->umk == 0) {
						$tokenpayment = $golongan->gaji / $maxjamkerja->max_jam_kerja;
						$payment = $tokenAbsensi + $golongan->makan + $golongan->transport;
					}
					else if ($golongan->gaji == 0 && $golongan->umk != 0) {
						$tokenpayment = ($golongan->umk / $setting->total_hari) / $maxjamkerja->max_jam_kerja;
						$payment = $tokenAbsensi + $golongan->makan + $golongan->transport;
					}

					$dataRowCuti = array(
									"tanggal" 	=> 	$start_date,
									"kode"		=> 	$getkaryawan->kode_karyawan,
									"kabag"		=>	$kabag,
									"masuk"		=>	$masuk,
									"keluar"	=>	$keluar,
									"ket_masuk"	=>	"Cuti Kerja",
									"jam_masuk"	=>	$jam_masuk,
									"jam_keluar"=>	$jam_keluar,
									"kerja"		=>	"Cuti",
									"shift"		=>	$shift,
									"token"		=>	$maxjamkerja->max_jam_kerja,
									"status" => "Diterima",
									"payment" => $golongan->gaji
								);
					$dataCutiAbsensi[] = $dataRowCuti;
				}
			}
			else {
				$pesanNotif = "HRD <u style='color:red;'>Tolak</u>";
				$pesanNotifAndroid = "Owner Reject";
				$dataCutiAbsensi = false;
			}
			$notifAndroid = $pesanNotifAndroid." data cuti dengan nama ".$getkaryawan->nama." dan no_induk ".$getkaryawan->idfp.".";
			$dataNotif = array(
								"keterangan"=> $pesanNotif." data cuti dengan Nama : <u>".$getkaryawan->nama."</u> dan No Karyawan : <u>".$getkaryawan->idfp."</u>.",
								"user_id"	=>	$this->user->id_pengguna,
								"level"		=>	"hrd",
								"url_direct"=>	"aktivitas/cuti",
							);
			$update = $this->cutiModel->update($id,$data,$dataNotif,$dataCutiAbsensi);
			$token = $this->tokenModel->getByIdfp($getkaryawan->idfp);
			$count = $this->tokenModel->getByIdfpCount($getkaryawan->idfp);
			if ($update) {
				//notif firebase
				parent::insertNotif($dataNotif);
				//notif android
				if($count != 0)
				{
					parent::pushnotif($token,"Approval Cuti","HRD Menerima data cuti","024");
					// parent::pushnotif($token[0]->token,"payroll",$notifAndroid);
				}
				$this->response->status = true;
				$this->response->message = alertSuccess("Berhasil update data Cuti.");
			} else {
				$this->response->message = alertDanger("Gagal update data Cuti.");
			}

		}
		parent::json();
	}

	// public function delete($id)
	// {
	// 	parent::checkLoginUser(); // user login autentic checking
	//
	// 	if ($this->isPost()) {
	// 		$getById = $this->cutiModel->getById($id);
	// 		if ($getById) {
	// 			$delete = $this->cutiModel->delete($id);
	// 			if ($delete) {
	// 				$this->response->status = true;
	// 				$this->response->message = alertSuccess("Data grup Berhasil di hapus.");
	// 			} else {
	// 				$this->response->message = alertDanger("Data sudah tidak ada.");
	// 			}
	// 		} else {
	// 			$this->response->message = alertDanger("Data sudah tidak ada.");
	// 		}
	// 	}
	// 	parent::json();
	// }

}

/* End of file Cuti.php */
/* Location: ./application/controllers/approval/Cuti.php */
