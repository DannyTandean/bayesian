<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Sakit extends MY_Controller {

	public function __construct()
	{
		parent::__construct();
		$this->load->model('aktivitas/Sakit_model',"sakitModel");
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

		parent::headerTitle("Approval Data > Sakit Karyawan","Approval Data","Sakit Karyawan");
		$breadcrumbs = array(
							"Approval"	=>	site_url('approval/sakit'),
							"Sakit"		=>	"",
						);
		parent::breadcrumbs($breadcrumbs);
		$data = array();
		$data['karyawan'] = $this->sakitModel->getKaryawan();
		parent::viewData($data);

		parent::viewApproval();


	}

	public function ajax_list()
	{
		parent::checkLoginUser(); // user login autentic checking

		if ($this->isPost()) {
			$data = array();

			$orderBy = array(null,null,"tanggal","nama","departemen","jabatan","status","tgl_sakit","akhir_sakit","keterangans","lama");
			$search = array("nama","departemen","jabatan");

			$result = $this->sakitModel->findDataTable($orderBy,$search);
			foreach ($result as $item) {
				if ($item->status == "Diterima") {
					$btnAction = 'Sudah di Terima';

				}elseif($item->status == "Ditolak"){
					$btnAction = 'Sudah di Tolak';
				}
				if($item->status == "Proses")
				{
					// $btnTools = '<button class="btn btn-warning btn-outline-default btn-mini"><i class="fa fa-print"></i>Print</button>';
					$item->status = '<label class="label label-info">'.$item->status.'</label>';
					$btnAction = '<button class="btn btn-success btn-outline-success btn-mini" onclick="btnApprove('.$item->id_sakit.')"><i class="fa fa-check-square"></i>Terima</button>';
					$btnAction .= '&nbsp;&nbsp;&nbsp;<button class="btn btn-danger btn-outline-danger btn-mini" onclick="btnReject('.$item->id_sakit.')"><i class="fa fa-ban"></i>Tolak</button>';
				}
				else if($item->status == "Diterima")
				{
					// $btnTools = '<button class="btn btn-default btn-outline-default btn-mini" onclick="btnPrint('.$item->id_izin.')"><i class="fa fa-print"></i>Print</button>';
					$item->status = '<label class="label label-success">'.$item->status.'</label>';
				}
				else {
					$item->status = '<label class="label label-danger">'.$item->status.'</label>';
					// $btnTools = '<button class="btn btn-warning btn-outline-default btn-mini"><i class="fa fa-print"></i>Print</button>';
				}


				$item->tgl_sakit = date_ind("d M Y",$item->tgl_sakit);
				$item->akhir_sakit = date_ind("d M Y",$item->akhir_sakit);
				// $item->button_tool = $btnTools;
				$item->button_action = $btnAction;
				$data[] = $item;
			}
			return $this->sakitModel->findDataTableOutput($data,$search);
		}
	}

	public function add()
	{
		parent::checkLoginUser(); // user login autentic checking

		if ($this->isPost()) {
			$tanggal = $this->input->post("tanggal");
			$karyawan = $this->input->post('karyawan');
			$mulaiSakit = $this->input->post('mulaiSakit');
			$akhirSakit = $this->input->post('akhirSakit');
			$keterangan = $this->input->post("keterangan");
			$date1=date_create($mulaiSakit);
			$date2=date_create($akhirSakit);
			$lama=date_diff($date1,$date2)->format("%a")+1;
			$this->form_validation->set_rules('tanggal', 'Tanggal', 'trim|required');
			$this->form_validation->set_rules('karyawan', 'Karyawan', 'trim|required');
			$this->form_validation->set_rules('mulaiSakit', 'Mulai Sakit', 'trim|required');
			$this->form_validation->set_rules('akhirSakit', 'Akhir Sakit', 'trim|required');
			$this->form_validation->set_rules('keterangan', 'Keterangan', 'trim|required');

			if ($this->form_validation->run() == TRUE) {
				$data = array(
								"id_karyawan" => $karyawan,
								"tanggal"	=>	$tanggal,
								"tgl_sakit" => $mulaiSakit,
								"akhir_sakit" => $akhirSakit,
								"keterangans"=>	$keterangan,
								"lama" => $lama
							);
				$insert = $this->sakitModel->insert($data);

				if ($insert) {
					// parent::pushNotif("token","Approval Izin","HRD Menerima data Izin","025");
					$this->response->status = true;
					$this->response->message = alertSuccess("Berhasil tambah data sakir.");
				} else {
					$this->response->message = alertDanger("Gagal tambah data sakir.");
				}

			} else {
				// $this->response->message = validation_errors('<span style="color:red;">', '</span><br>');
				$this->response->error = array(
									"karyawan"	=> form_error("karyawan",'<span style="color:red;">','</span>'),
									"tanggal"	=> form_error("tanggal",'<span style="color:red;">','</span>'),
									"mulaiSakit"	=> form_error("mulaiSakit",'<span style="color:red;">','</span>'),
									"akhirSakit"	=> form_error("akhirSakit",'<span style="color:red;">','</span>'),
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
			$getById = $this->sakitModel->getDataKaryawanSelect($id);
			if ($getById) {
				$this->response->status = true;
				$this->response->message = "Data sakit get by id";
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
			$getById = $this->sakitModel->getById($id);
			if ($getById) {
				if ($getById->foto != "") {
					$getById->foto = base_url("/")."uploads/master/karyawan/orang/".$getById->foto;
				} else {
					$getById->foto = base_url("/")."assets/images/default/no_user.png";
				}
				if ($getById->file != "") {
					$getById->file = base_url("/")."uploads/aktivitas/sakit/".$getById->file;
				} else {
					$getById->file = base_url("/")."assets/images/default/no_file_.png";
				}
				$this->response->status = true;
				$this->response->message = "Data sakit get by id";
				$this->response->data = $getById;
			} else {
				$this->response->message = alertDanger("Data tidak ada.");
			}
		}
		parent::json();
	}

	public function update($id)
	{
		parent::checkLoginUser(); // user login autentic checking

		if ($this->isPost()) {
			$getById = $this->sakitModel->getById($id);
			$getKaryawan = $this->karyawanModel->getById($getById->id_karyawan);
			$status = $this->input->post('status');

			$kabagGroup = $this->karyawanModel->checkGroupKabag($getKaryawan->id_grup);
			$kabag = "";
			if ($kabagGroup) {
				$kabag = $kabagGroup->kode_karyawan;
			}

				$data = array(
								"status" => $status
							);
				if($status == "Diterima"){
					$pesanNotif = "HRD <u style='color:green;'>Terima</u>";
					$pesanNotifAndroid = "Terima";

					$dataSakitAbsensi = array();
					$start_date = date("Y-m-d", strtotime("-1 day", strtotime($getById->tgl_sakit)));
					$end_date = $getById->akhir_sakit;
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
							if ($getKaryawan && $getKaryawan->shift != "ya") {
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
									$shiftAbsensi = $this->karyawanModel->lastAbsensi($getKaryawan->kode_karyawan);
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
								$shiftAbsensi = $this->karyawanModel->lastAbsensi($getKaryawan->kode_karyawan);
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

						$shiftAbsensi = $this->karyawanModel->lastAbsensiJadwal($getKaryawan->kode_karyawan);
						$golongan = $this->logabsensiModel->getGolonganByKode($getKaryawan->kode_karyawan);
						$jadwalId = $this->JadwalKaryawanModel->getidJadwal();
						$maxjamkerja = $this->logabsensiModel->getmaxjamKerja(sizeof($shiftAbsensi) == 0 ? $jadwalId->id_jadwal : $shiftAbsensi[0]->id_jadwal);
						$setting = $this->settingModel->getById(1);
						$tokenAbsensi = $setting->sakit;

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

						$dataRowSakit = array(
										"tanggal" 	=> 	$start_date,
										"kode"		=> 	$getKaryawan->kode_karyawan,
										"kabag"		=>	$kabag,
										"masuk"		=>	$masuk,
										"keluar"	=>	$keluar,
										"ket_masuk"	=>	"Sakit Kerja",
										"jam_masuk"	=>	$jam_masuk,
										"jam_keluar"=>	$jam_keluar,
										"kerja"		=>	"Sakit",
										"shift"		=>	$shift,
										"token"		=>	$maxjamkerja->max_jam_kerja,
										"status" => "Diterima",
										"payment" => $golongan->gaji
									);
						$dataSakitAbsensi[] = $dataRowSakit;
					}
				}
				else {
					$pesanNotif = "HRD <u style='color:red;'>Tolak</u>";
					$pesanNotifAndroid = "Tolak";
					$dataSakitAbsensi = false;
				}

				$notifAndroid = $pesanNotifAndroid." data sakit dengan Nama ".$getKaryawan->nama." dan No Karyawan : ".$getKaryawan->idfp.".";
				$dataNotif = array(
										"keterangan"=> 	$pesanNotif." data Sakit karyawan dengan Nama <u>".$getKaryawan->nama."</u> dan No Karyawan : <u>".$getKaryawan->idfp."</u>",
										"user_id"	=>	$this->user_id,
										"level"		=>	"hrd",
										"url_direct"=>	"aktivitas/sakit",
									);
				$update = $this->sakitModel->update($id,$data,$dataNotif,$dataSakitAbsensi);
				$token = $this->tokenModel->getByIdfp($getKaryawan->idfp);
				$count = $this->tokenModel->getByIdfpCount($getKaryawan->idfp);

				if ($update) {
					parent::insertNotif($dataNotif);
					if($count != 0)
					{
						// parent::pushnotif($token[0]->token,"payroll",$notifAndroid);
						parent::pushnotif($token,"Approval Sakit","HRD Menerima data Sakit","055");
					}
					$this->response->status = true;
					$this->response->message = alertSuccess("Berhasil update data sakit.");
				} else {
					$this->response->message = alertDanger("Gagal update data sakit.");
				}

		}
		parent::json();
	}

	// public function delete($id)
	// {
	// 	parent::checkLoginUser(); // user login autentic checking

	// 	if ($this->isPost()) {
	// 		$getById = $this->izinModel->getById($id);
	// 		if ($getById) {
	// 			$delete = $this->izinModel->delete($id);
	// 			if ($delete) {
	// 				$this->response->status = true;
	// 				$this->response->message = alertSuccess("Data izin Berhasil di hapus.");
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

/* End of file Izin.php */
/* Location: ./application/controllers/approval/Izin.php */
