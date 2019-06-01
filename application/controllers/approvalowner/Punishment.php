<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Punishment extends MY_Controller {

	public function __construct()
	{
		parent::__construct();
		$this->load->model('rwd/Punishment_model',"punishModel");
		$this->load->model('master/Karyawan_model', "karyawanModel");
		$this->load->model('Token_model',"tokenModel");
		parent::checkLoginHRD();
	}

	public function index()
	{
		parent::checkLoginUser(); // user login autentic checking

		parent::headerTitle("Approval Owner > Punishment","Approval Owner","Punishment");
		$breadcrumbs = array(
							"Approval Owner"	=>	site_url('approvalowner/punishment'),
							"Punishment"		=>	"",
						);
		parent::breadcrumbs($breadcrumbs);

		parent::viewApprovalOwner();
	}

	public function ajax_list()
	{
		parent::checkLoginUser(); // user login autentic checking

		if ($this->isPost()) {
			$data = array();

			$orderBy = array(null,null,"tanggal","nama","departemen","status","jabatan","nilai","rwd_punishment.keterangan");
			$search = array("tanggal","nama","departemen","jabatan","nilai","status","rwd_punishment.keterangan");

			$result = $this->punishModel->findDataTable($orderBy,$search);
			foreach ($result as $item) {
				if ($item->status == "Diterima") {
					$btnAction = 'Sudah di Terima';

				}elseif($item->status == "Ditolak"){
					$btnAction = 'Sudah di Tolak';
				}
				if ($item->status == "Proses") {
					$item->status = '<label class="label label-info">'.$item->status.'</label>';
					$btnAction = '<button class="btn btn-outline-success btn-mini"  onclick="btnApproval('.$item->id.')"><i class="fa fa-check-square"></i>Terima</button>';
					$btnAction .= '&nbsp;&nbsp;&nbsp;<button class="btn btn-outline-danger btn-mini" onclick="btnReject('.$item->id.')"><i class="fa fa-ban"></i>Tolak</button>';
				} else if ($item->status == "Diterima") {
					$item->status = '<label class="label label-success">'.$item->status.'</label>';
				} else if ($item->status == "Ditolak") {
					$item->status = '<label class="label label-danger">'.$item->status.'</label>';
				}

				$disabled = $item->status == "Ditolak" ? "disabled" : "";


				$item->tanggal = date_ind("d M Y", $item->tanggal);
				$item->nilai = "Rp.".number_format($item->nilai,0,",",".");
				$item->button_action = $btnAction;
				$data[] = $item;
			}
			return $this->punishModel->findDataTableOutput($data,$search);
		}
	}

	public function getId($id)
	{
		parent::checkLoginUser(); // user login autentic checking

		if ($this->isPost()) {
			$getById = $this->punishModel->getById($id);
			if ($getById) {
				$getById->tanggal_indo = date_ind("d M Y", $getById->tanggal);
				$getById->nilai_rp = "Rp.".number_format($getById->nilai,0,",",".");

				if ($getById->foto != "") {
					$getById->foto = base_url("/")."uploads/master/karyawan/orang/".$getById->foto;
				} else {
					$getById->foto = base_url("/")."assets/images/default/no_user.png";
				}

				$this->response->status = true;
				$this->response->message = "Data Punishment get by id";
				$this->response->data = $getById;
			} else {
				$this->response->message = alertDanger("Data tidak ada.");
			}
		}
		parent::json();
	}

	public function approve($id)
	{
		parent::checkLoginUser(); // user login autentic checking

		if ($this->isPost()) {
			$getById = $this->punishModel->getById($id);
			$getKaryawan = $this->karyawanModel->getById($getById->id_karyawan);
			if ($getById) {
				if ($getById->status == "Proses") {

					$dataNotif = array(
											"keterangan"=> 	"Owner <u style='color:green;'>Terima </u> data Punishment karyawan dengan Nama <u>".$getKaryawan->nama."</u> dan No Karyawan : <u>".$getKaryawan->idfp."</u>",
											"user_id"	=>	$this->user->id_pengguna,
											"level"		=>	"hrd",
											"url_direct"=>	"rwd/reward",
										);
					$update = $this->punishModel->update($id,array("status" => "Diterima"),$dataNotif);
					$token = $this->tokenModel->getByIdfp($getKaryawan->idfp);
					// var_dump($token);
					// exit();
					if ($update) {
						if (sizeof($token) != 0) {
							parent::pushnotif($token[0]->token,"Approval Punishment","Owner Menerima data Punishment","052");
						}
						parent::insertNotif($dataNotif);
						parent::sendNotifTopic("hrd","Approval Punishment","Owner menerima data Punishment","052");
						$this->response->status = true;
						$this->response->message = alertSuccess("Approval data punishment berhasil.");
					} else {
						$this->response->message = alertDanger("Gagal approval data punishment.");
					}
				} else {
					$this->response->message = alertDanger("Opps, Maaf Hanya status Proses yang bisa.");
				}
			} else {
				$this->response->message = alertDanger("Data tidak ada.");
			}
		}
		parent::json();
	}

	public function reject($id)
	{
		parent::checkLoginUser(); // user login autentic checking

		if ($this->isPost()) {
			$getById = $this->punishModel->getById($id);
			$getKaryawan = $this->karyawanModel->getById($getById->id_karyawan);
			if ($getById) {
				if ($getById->status == "Proses") {
					$dataNotif = array(
											"keterangan"=> 	"Owner <u style='color:red;'>Tolak</u> data Punishment karyawan dengan Nama <u>".$getKaryawan->nama."</u> dan No Karyawan : <u>".$getKaryawan->idfp."</u>",
											"user_id"	=>	$this->user->id_pengguna,
											"level"		=>	"hrd",
											"url_direct"=>	"rwd/reward",
										);
					$update = $this->punishModel->update($id,array("status" => "Ditolak"),$dataNotif);
					if ($update) {
						parent::insertNotif($dataNotif);
						$this->response->status = true;
						$this->response->message = alertSuccess("Tolak data punishment berhasil.");
					} else {
						$this->response->message = alertDanger("Gagal tolak data punishment.");
					}
				} else {
					$this->response->message = alertDanger("Opps, Maaf Hanya status Proses yang bisa.");
				}
			} else {
				$this->response->message = alertDanger("Data tidak ada.");
			}
		}
		parent::json();
	}

}

/* End of file Punishment.php */
/* Location: ./application/controllers/approvalowner/Punishment.php */
