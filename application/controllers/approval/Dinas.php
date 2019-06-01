<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Dinas extends MY_Controller {

	public function __construct()
	{
		parent::__construct();
		$this->load->model('aktivitas/Dinas_model',"dinasModel");
		$this->load->model('master/Karyawan_model',"karyawanModel");
		$this->load->model('aktivitas/LogAbsensi_model',"logabsensiModel");
		$this->load->model('Setting_model',"settingModel");
	  $this->load->model('aktivitas/Jadwal_karyawan_model',"JadwalKaryawanModel");
		$this->load->model('Token_model',"tokenModel");
		parent::checkLoginOwner();
	}

	public function index()
	{
		parent::checkLoginUser();; // user login autentic checking

		parent::headerTitle("Approval Data > Perjalanan Dinas","Approval Data","Perjalanan Dinas");
		$breadcrumbs = array(
							"Approval"	=>	site_url('approval/dinas'),
							"Dinas"		=>	"",
						);
		parent::breadcrumbs($breadcrumbs);
		$data = array();
		$data['karyawan'] = $this->dinasModel->getKaryawan();
		parent::viewData($data);

		parent::viewApproval();


	}

	public function ajax_list()
	{
		parent::checkLoginUser(); // user login autentic checking

		if ($this->isPost()) {
			$data = array();

			$orderBy = array(null,null,null,"tanggal","nama","departemen","status","jabatan","keterangans","tgl_dinas","akhir_dinas","lama");
			$search = array("nama","departemen","jabatan");

			$result = $this->dinasModel->findDataTable($orderBy,$search);
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
					$btnAction = '<button class="btn btn-success btn-outline-success btn-mini" onclick="btnApproval('.$item->id_dinas.')"><i class="fa fa-check-square"></i>Terima</button>';
				$btnAction .= '&nbsp;&nbsp;&nbsp;<button class="btn btn-danger btn-outline-danger btn-mini" onclick="btnReject('.$item->id_dinas.')"><i class="fa fa-ban"></i>Tolak</button>';
				}
				else if($item->status == "Diterima")
				{
					$btnTools = '<button class="btn btn-default btn-outline-default btn-mini" onclick="btnPrint('.$item->id_dinas.')"><i class="fa fa-print"></i>Print</button>';
					$item->status = '<label class="label label-success">'.$item->status.'</label>';
				}
				else {
					$item->status = '<label class="label label-danger">'.$item->status.'</label>';
					$btnTools = '<button class="btn btn-warning btn-outline-default btn-mini"><i class="fa fa-print"></i>Print</button>';
				}


				$item->tgl_dinas = date_ind("d M Y",$item->tgl_dinas);
				$item->akhir_dinas = date_ind("d M Y",$item->akhir_dinas);
				$item->button_tool = $btnTools;
				$item->button_action = $btnAction;
				$data[] = $item;
			}
			return $this->dinasModel->findDataTableOutput($data,$search);
		}
	}

	public function add()
	{
		parent::checkLoginUser(); // user login autentic checking

		if ($this->isPost()) {
			$tanggal = $this->input->post("tanggal");
			$karyawan = $this->input->post('karyawan');
			$mulaiDinas = $this->input->post('mulaiDinas');
			$akhirDinas = $this->input->post('akhirDinas');
			$keterangan = $this->input->post("keterangan");
			$date1=date_create($mulaiDinas);
			$date2=date_create($akhirDinas);
			$lama=date_diff($date1,$date2)->format("%a")+1;
			$this->form_validation->set_rules('tanggal', 'Tanggal', 'trim|required');
			$this->form_validation->set_rules('karyawan', 'Karyawan', 'trim|required');
			$this->form_validation->set_rules('mulaiDinas', 'Mulai Dinas', 'trim|required');
			$this->form_validation->set_rules('akhirDinas', 'Akhir Dinas', 'trim|required');
			$this->form_validation->set_rules('keterangan', 'Keterangan', 'trim|required');

			if ($this->form_validation->run() == TRUE) {
				$data = array(
								"id_karyawan" => $karyawan,
								"tanggal"	=>	$tanggal,
								"tgl_dinas" => $mulaiDinas,
								"akhir_dinas" => $akhirDinas,
								"keterangans"=>	$keterangan,
								"lama" => $lama
							);
				$insert = $this->dinasModel->insert($data);
				if ($insert) {

					$this->response->status = true;
					$this->response->message = alertSuccess("Berhasil tambah data dinas.");
				} else {
					$this->response->message = alertDanger("Gagal tambah data dinas.");
				}

			} else {
				// $this->response->message = validation_errors('<span style="color:red;">', '</span><br>');
				$this->response->error = array(
									"karyawan"	=> form_error("karyawan",'<span style="color:red;">','</span>'),
									"tanggal"	=> form_error("tanggal",'<span style="color:red;">','</span>'),
									"mulaiDinas"	=> form_error("mulaiDinas",'<span style="color:red;">','</span>'),
									"akhirDinas"	=> form_error("akhirDinas",'<span style="color:red;">','</span>'),
									"keterangans"	=> form_error("keterangan",'<span style="color:red;">','</span>'),
								);
			}
		}
		parent::json();
	}

	public function getNama($id)
	{
		parent::checkLoginUser(); // user login autentic checking

		if ($this->isPost()) {
			$getById = $this->dinasModel->getDataKaryawanSelect($id);
			if ($getById) {
				$this->response->status = true;
				$this->response->message = "Data dinas get by id";
				$this->response->data = $getById;
			} else {
				$this->response->message = alertDanger("Data tidak ada.");
			}
		}
		parent::json();
	}

	public function getId($id)
	{
		parent::checkLoginUser(); // user login autentic checking

		if ($this->isPost()) {
			$getById = $this->dinasModel->getById($id);
			if ($getById) {
					if ($getById->foto != "") {
						$getById->foto = base_url("/")."uploads/master/karyawan/orang/".$getById->foto;
					} else {
						$getById->foto = base_url("/")."assets/images/default/no_user.png";
					}
				$this->response->status = true;
				$this->response->message = "Data dinas get by id";
				$this->response->data = $getById;
			} else {
				$this->response->message = alertDanger("Data tidak ada.");
			}
		}
		parent::json();
	}

	public function getIdForPrint($id)
	{
		parent::checkLoginUser(); // user login autentic checking

		if ($this->isPost()) {
			$getById = $this->dinasModel->getById($id);
			$setting = $this->dinasModel->get_setting();
			if ($getById) {
				if ($setting->logo != "") {
					$setting->logo = base_url("/")."uploads/setting/".$setting->logo;
				} else {
					$setting->logo = base_url("/")."assets/images/default/no_file_.png";
				}
				$getById->setting = $setting;
				$getById->tgl_dinas = date_ind("d M Y",$getById->tgl_dinas);
				$getById->akhir_dinas = date_ind("d M Y",$getById->akhir_dinas);
				$getById->tanggal = date_ind("d M Y",$getById->tanggal);
				$this->response->status = true;
				$this->response->message = "data print by id";
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
			$getById = $this->dinasModel->getById($id);
			$getkaryawan = $this->karyawanModel->getById($getById->id_karyawan);
			$kabagGroup = $this->karyawanModel->checkGroupKabag($getkaryawan->id_grup);
			$kabag = "";
			if ($kabagGroup) {
				$kabag = $kabagGroup->kode_karyawan;
			}

			$status = $this->input->post('status');;

			$data = array(
							"status" => $status
						);
			if($status == "Diterima"){
				$pesanNotif = "HRD <u style='color:green;'>Terima</u>";
				$pesanNotifAndroid = "Owner Approval";

				$dataDinasAbsensi = array();

				$start_date = date("Y-m-d", strtotime("-1 day", strtotime($getById->tgl_dinas)));
				$end_date = $getById->akhir_dinas;
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
						$masuk = "00:00:00";
						$keluar = "00:00:00";
						$jam_masuk = "00:00:00";
						$jam_keluar = "00:00:00";
						$shift = "";
						$token = 0;
						/*var_dump($getkaryawan);
						exit();*/
						if ($getkaryawan && $getkaryawan->shift != "ya") {
							$this->load->model('master/Shift_model',"shiftModel");
							// untuk shift regular
							// check berdasarkan jam shift regular
							$hari = date("l", strtotime($start_date));
							$shiftRegular = $this->shiftModel->getByWhere(array("shift"=>"REGULAR","hari"=>$hari));
							/*var_dump($hari);
							var_dump($shiftRegular);
							exit();*/
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
					$tokenAbsensi = $setting->dinas;

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

					$dataRowDinas = array(
									"tanggal" 	=> 	$start_date,
									"kode"		=> 	$getkaryawan->kode_karyawan,
									"kabag"		=>	$kabag,
									"masuk"		=>	$masuk,
									"keluar"	=>	$keluar,
									"ket_masuk"	=>	"Dinas Kerja",
									"jam_masuk"	=>	$jam_masuk,
									"jam_keluar"=>	$jam_keluar,
									"kerja"		=>	"Dinas",
									"shift"		=>	$shift,
									"token"		=>	$maxjamkerja->max_jam_kerja,
									"status" => "Diterima",
									"payment" => $golongan->gaji
								);
					$dataDinasAbsensi[] = $dataRowDinas;
				}
			}
			else {
				$pesanNotif = "HRD <u style='color:red;'>Tolak</u>";
				$pesanNotifAndroid = "Owner Reject";
				$dataDinasAbsensi = false;
			}
			$notifAndroid = $pesanNotifAndroid." data cuti dengan nama ".$getkaryawan->nama." dan no_induk ".$getkaryawan->idfp.".";
			$dataNotif = array(
								"keterangan"=> $pesanNotif." data dinas dengan Nama : <u>".$getkaryawan->nama."</u> dan No Induk : <u>".$getkaryawan->idfp."</u>",
								"user_id"	=>	$this->user->id_pengguna,
								"level"		=>	"hrd",
								"url_direct"=>	"aktivitas/dinas",
							);
			$update = $this->dinasModel->update($id,$data,$dataNotif,$dataDinasAbsensi);
			$token = $this->tokenModel->getByIdfp($getkaryawan->idfp);
			$count = $this->tokenModel->getByIdfpCount($getkaryawan->idfp);
			if ($update) {
				//notif firebase
				parent::insertNotif($dataNotif);
				//notif android
				if($count != 0)
				{
					// parent::pushnotif($token[0]->token,"payroll",$notifAndroid);
					parent::pushnotif($token,"Approval Dinas","HRD Menerima data Dinas","026");
				}
				$this->response->status = true;
				$this->response->message = alertSuccess("Berhasil update data dinas.");
			} else {
				$this->response->message = alertDanger("Gagal update data dinas.");
			}

		}
		parent::json();
	}

	// public function delete($id)
	// {
	// 	parent::checkLoginUser(); // user login autentic checking
	//
	// 	if ($this->isPost()) {
	// 		$getById = $this->dinasModel->getById($id);
	// 		if ($getById) {
	// 			$delete = $this->dinasModel->delete($id);
	// 			if ($delete) {
	// 				$this->response->status = true;
	// 				$this->response->message = alertSuccess("Data dinas Berhasil di hapus.");
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

/* End of file Dinas.php */
/* Location: ./application/controllers/approval/Dinas.php */
