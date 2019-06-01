<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Dirumahkan extends MY_Controller {

	public function __construct()
	{
		parent::__construct();
		$this->load->model('aktivitas/Dirumahkan_model',"dirumahkanModel");
		$this->load->model('master/Karyawan_model',"karyawanModel");
		$this->load->model('aktivitas/LogAbsensi_model',"logabsensiModel");
		$this->load->model('Setting_model',"settingModel");
	  $this->load->model('aktivitas/Jadwal_karyawan_model',"JadwalKaryawanModel");
		$this->load->model('Token_model',"tokenModel");
		parent::checkLoginOwner();
	}

	public function index()
	{
		parent::checkLoginUser();// user login autentic checking

		parent::headerTitle("Approval Data > Dirumahkan","Approval Data","Dirumahkan");
		$breadcrumbs = array(
							"Approval"	=>	site_url('approval/dirumahkan'),
							"Dirumahkan"		=>	"",
						);
		parent::breadcrumbs($breadcrumbs);
		$data = array();
		$data['karyawan'] = $this->dirumahkanModel->getKaryawan();
		parent::viewData($data);
		parent::viewApproval();
	}

	public function ajax_list()
	{
		parent::checkLoginUser(); // user login autentic checking

		if ($this->isPost()) {
			$data = array();

			$orderBy = array(null,null,"tanggal","nama","departemen","jabatan","keterangans","status","tgl_dirumahkan","akhir_dirumahkan","lama");
			$search = array("nama","departemen","jabatan");

			$result = $this->dirumahkanModel->findDataTable($orderBy,$search);
			foreach ($result as $item) {
				if ($item->status == "Diterima") {
					$btnAction = 'Sudah di Terima';

				}elseif($item->status == "Ditolak"){
					$btnAction = 'Sudah di Tolak';
				}
				if($item->status == "Proses")
				{
					$item->status = '<label class="label label-info">'.$item->status.'</label>';
					$btnAction = '<button class="btn btn-success btn-outline-success btn-mini" onclick="btnApproval('.$item->id_dirumahkan.')"><i class="fa fa-check-square"></i>Terima</button>';
					$btnAction .= '&nbsp;&nbsp;&nbsp;<button class="btn btn-danger btn-outline-danger btn-mini" onclick="btnReject('.$item->id_dirumahkan.')"><i class="fa fa-ban"></i>Tolak</button>';
				}
				else if($item->status == "Diterima")
				{
					$item->status = '<label class="label label-success">'.$item->status.'</label>';
				}
				else {
					$item->status = '<label class="label label-danger">'.$item->status.'</label>';
				}

				$item->tgl_dirumahkan = date_ind("d M Y",$item->tgl_dirumahkan);
				$item->akhir_dirumahkan = date_ind("d M Y",$item->akhir_dirumahkan);

				$item->button_action = $btnAction;
				$data[] = $item;
			}
			return $this->dirumahkanModel->findDataTableOutput($data,$search);
		}
	}

	public function add()
	{
		parent::checkLoginUser(); // user login autentic checking

		if ($this->isPost()) {
			$tanggal = $this->input->post("tanggal");
			$karyawan = $this->input->post('karyawan');
			$mulaiDirumahkan = $this->input->post('mulaiDirumahkan');
			$akhirDirumahkan = $this->input->post('akhirDirumahkan');
			$keterangan = $this->input->post("keterangan");
			$date1=date_create($mulaiDirumahkan);
			$date2=date_create($akhirDirumahkan);
			$lama=date_diff($date1,$date2)->format("%a")+1;
			$this->form_validation->set_rules('tanggal', 'Tanggal', 'trim|required');
			$this->form_validation->set_rules('karyawan', 'Karyawan', 'trim|required');
			$this->form_validation->set_rules('mulaiDirumahkan', 'Mulai Dirumahkan', 'trim|required');
			$this->form_validation->set_rules('akhirDirumahkan', 'Akhir Dirumahkan', 'trim|required');
			$this->form_validation->set_rules('keterangan', 'Keterangan', 'trim|required');

			if ($this->form_validation->run() == TRUE) {
				$data = array(
								"id_karyawan" => $karyawan,
								"tanggal"	=>	$tanggal,
								"tgl_dirumahkan" => $mulaiDirumahkan,
								"akhir_dirumahkan" => $akhirDirumahkan,
								"keterangans"=>	$keterangan,
								"lama" => $lama
							);
				$insert = $this->dirumahkanModel->insert($data);
				$token = $this->tokenModel->getByIdfp($getkaryawan->idfp);
				$count = $this->tokenModel->getByIdfpCount($getkaryawan->idfp);

				if ($insert) {
					if($count != 0)
					{
						parent::pushnotif($token,"Approval Dirumahkan","HRD Menerima data Dirumahkan","034");
					}

					$this->response->status = true;
					$this->response->message = alertSuccess("Berhasil tambah data dirumahkan.");
				} else {
					$this->response->message = alertDanger("Gagal tambah data dirumahkan.");
				}

			} else {
				// $this->response->message = validation_errors('<span style="color:red;">', '</span><br>');
				$this->response->error = array(
									"karyawan"	=> form_error("karyawan",'<span style="color:red;">','</span>'),
									"tanggal"	=> form_error("tanggal",'<span style="color:red;">','</span>'),
									"mulaiDirumahkan"	=> form_error("mulaiDirumahkan",'<span style="color:red;">','</span>'),
									"akhirDirumahkan"	=> form_error("akhirDirumahkan",'<span style="color:red;">','</span>'),
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
			$getById = $this->dirumahkanModel->getDataKaryawanSelect($id);
			if ($getById) {
				$this->response->status = true;
				$this->response->message = "Data dirumahkan get by id";
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
			$getById = $this->dirumahkanModel->getById($id);
			if ($getById) {
				if ($getById->foto != "") {
					$getById->foto = base_url("/")."uploads/master/karyawan/orang/".$getById->foto;
				} else {
					$getById->foto = base_url("/")."assets/images/default/no_user.png";
				}
				$this->response->status = true;
				$this->response->message = "Data dirumahkan get by id";
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
			$getById = $this->dirumahkanModel->getById($id);
			$getkaryawan = $this->karyawanModel->getById($getById->id_karyawan);
			$status = $this->input->post('status');;


				$data = array(
								"status" => $status
							);
				if($status == "Diterima"){
					$pesanNotif = "Owner <u style='color:green;'>Approval</u>";
					$pesanNotifAndroid = "Owner Approval";
				}
				else {
					$pesanNotif = "Owner <u style='color:red;'>Reject</u>";
					$pesanNotifAndroid = "Owner Reject";
				}
				$notifAndroid = $pesanNotifAndroid." data dirumahkan dengan nama ".$getkaryawan->nama." dan no_induk ".$getkaryawan->idfp.".";
				$dataNotif = array(
									"keterangan"=> $pesanNotif." data dirumahkan dengan nama=<u>".$getkaryawan->nama."</u> dan no_induk=<u>".$getkaryawan->idfp."</u>.",
									"user_id"	=>	$this->user->id_pengguna,
									"level"		=>	"hrd",
									"url_direct"=>	"aktivitas/dirumahkan",
								);
				$update = $this->dirumahkanModel->update($id,$data,$dataNotif);
				$token = $this->tokenModel->getByIdfp($getkaryawan->idfp);
				$count = $this->tokenModel->getByIdfpCount($getkaryawan->idfp);
				if ($update) {
					//notif firebase
					parent::insertNotif($dataNotif);
					//notif android
					if($count != 0)
					{
						parent::pushnotif($token,"Approval Dirumahkan","HRD Menerima data Dirumahkan","034");
					}
					$this->response->status = true;
					$this->response->message = alertSuccess("Berhasil update data dirumahkan.");
				} else {
					$this->response->message = alertDanger("Gagal update data dirumahkan.");
				}

		}
		parent::json();
	}

	// public function delete($id)
	// {
	// 	parent::checkLoginUser(); // user login autentic checking
	//
	// 	if ($this->isPost()) {
	// 		$getById = $this->dirumahkanModel->getById($id);
	// 		if ($getById) {
	// 			$delete = $this->dirumahkanModel->delete($id);
	// 			if ($delete) {
	// 				$this->response->status = true;
	// 				$this->response->message = alertSuccess("Data dirumahkan Berhasil di hapus.");
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

/* End of file Dirumahkan.php */
/* Location: ./application/controllers/approval/Dirumahkan.php */
