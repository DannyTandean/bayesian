<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Reward extends MY_Controller {

	public function __construct()
	{
		parent::__construct();
		$this->load->model('rwd/Reward_model',"rewardModel");
		$this->load->model('master/Karyawan_model', "karyawanModel");
		$this->load->model('Token_model',"tokenModel");
		parent::checkLoginHRD();

	}

	public function index()
	{
		parent::checkLoginUser(); // user login autentic checking

		parent::headerTitle("Approval Owner > Rewards Karyawan","Approval Owner","Rewards Karyawan");
		$breadcrumbs = array(
							"Approval owner"	=>	site_url('approvalowner/reward'),
							"Rewards"		=>	"",
						);
		parent::breadcrumbs($breadcrumbs);
		$data = array();
		$data['karyawan'] = $this->rewardModel->getKaryawan();
		parent::viewData($data);

		parent::viewApprovalOwner();


	}

	public function ajax_list()
	{
		parent::checkLoginUser(); // user login autentic checking

		if ($this->isPost()) {
			$data = array();

			$orderBy = array(null,null,"tanggal","nama","departemen","jabatan","nilai","keteranganr","status");
			$search = array("nama","departemen","jabatan");

			$result = $this->rewardModel->findDataTable($orderBy,$search);
			foreach ($result as $item) {
				if ($item->status == "Diterima") {
					$btnAction = 'Sudah di Terima';
				}elseif ($item->status == "Ditolak") {
					$btnAction = 'Sudah di Tolak';
				}
				if($item->status == "Proses")
				{
					// $btnTools = '<button class="btn btn-warning btn-outline-default btn-mini"><i class="fa fa-print"></i>Print</button>';
					$item->status = '<label class="label label-info">'.$item->status.'</label>';
					$btnAction = '<button class="btn btn-success btn-outline-success btn-mini" onclick="btnApprove('.$item->id_rewards.')"><i class="fa fa-check-square"></i>Terima</button>';
					$btnAction .= '&nbsp;&nbsp;&nbsp;<button class="btn btn-danger btn-outline-danger btn-mini" onclick="btnReject('.$item->id_rewards.')"><i class="fa fa-ban"></i>Tolak</button>';

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

				$item->nilai = "Rp.".number_format($item->nilai,0,",",".");
				$item->tanggal = date_ind("d M Y",$item->tanggal);
				// $item->button_tool = $btnTools;
				$item->button_action = $btnAction;
				$data[] = $item;
			}
			return $this->rewardModel->findDataTableOutput($data,$search);
		}
	}

	public function add()
	{
		parent::checkLoginUser(); // user login autentic checking

		if ($this->isPost()) {
			$tanggal = $this->input->post("tanggal");
			$karyawan = $this->input->post('karyawan');
			$nilai = $this->input->post('nilai');
			$keterangan = $this->input->post("keterangan");

			$this->form_validation->set_rules('tanggal', 'Tanggal', 'trim|required');
			$this->form_validation->set_rules('karyawan', 'Karyawan', 'trim|required');
			$this->form_validation->set_rules('nilai', 'Nilai', 'trim|required');
			$this->form_validation->set_rules('keterangan', 'Keterangan', 'trim|required');

			if ($this->form_validation->run() == TRUE) {
				$data = array(
								"id_karyawan" => $karyawan,
								"tanggal"	=>	$tanggal,
								"nilai" => $nilai,
								"keteranganr"=>	$keterangan,
								"lama" => $lama
							);
				$insert = $this->rewardModel->insert($data);
				if ($insert) {

					$this->response->status = true;
					$this->response->message = alertSuccess("Berhasil tambah data reward karyawan.");
				} else {
					$this->response->message = alertDanger("Gagal tambah data reward karyawan.");
				}

			} else {
				// $this->response->message = validation_errors('<span style="color:red;">', '</span><br>');
				$this->response->error = array(
									"karyawan"	=> form_error("karyawan",'<span style="color:red;">','</span>'),
									"tanggal"	=> form_error("tanggal",'<span style="color:red;">','</span>'),
									"nilai"	=> form_error("nilai",'<span style="color:red;">','</span>'),
									"keteranganr"	=> form_error("keterangan",'<span style="color:red;">','</span>'),
								);
			}
		}
		parent::json();
	}


	public function getNama($id)
	{
		parent::checkLoginUser(); // user login autentic checking

		if ($this->isPost()) {
			$getById = $this->rewardModel->getDataKaryawanSelect($id);
			if ($getById) {
				$this->response->status = true;
				$this->response->message = "Data izin get by id";
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
			$getById = $this->rewardModel->getById($id);
			if ($getById) {
				if ($getById->foto != "") {
					$getById->foto = base_url("/")."uploads/master/karyawan/orang/".$getById->foto;
				} else {
					$getById->foto = base_url("/")."assets/images/default/no_user.png";
				}
				$getById->nilai = "Rp.".number_format($getById->nilai,0,",",".");
				$this->response->status = true;
				$this->response->message = "Data izin get by id";
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
			$getById = $this->rewardModel->getById($id);
			$getKaryawan = $this->karyawanModel->getById($getById->id_karyawan);
			$status = $this->input->post('status');


				$data = array(
								"status" => $status
							);
				if($status == "Diterima"){
					$pesanNotif = "Owner <u style='color:green;'>Terima</u>";
					$pesanNotifAndroid = "Owner Terima";
				}
				else {
					$pesanNotif = "Owner <u style='color:red;'>Tolak</u>";
					$pesanNotifAndroid = "Owner Tolak";
				}

				$notifAndroid = $pesanNotifAndroid." data izin dengan Nama ".$getKaryawan->nama." dan No Karyawan : ".$getKaryawan->idfp.".";
				$dataNotif = array(
											"keterangan"=> 	$pesanNotif." data Reward karyawan dengan Nama <u>".$getKaryawan->nama."</u> dan No Karyawan : <u>".$getKaryawan->idfp."</u>",
											"user_id"	=>	$this->user_id,
											"level"		=>	"hrd",
											"url_direct"=>	"rwd/reward",
										);
				$update = $this->rewardModel->update($id,$data,$dataNotif);
				$token = $this->tokenModel->getByIdfp($getKaryawan->idfp);
				if ($update) {
					if (sizeof($token) != 0) {
						parent::pushnotif($token[0]->token,"Approval Reward","Owner Menerima data Reward Karyawan","027");
					}
					parent::sendNotifTopic("hrd","Approval Reward","Owner menerima data Reward","027");
					parent::insertNotif($dataNotif);

					$this->response->status = true;
					$this->response->message = alertSuccess("Berhasil update data reward karyawan.");
				} else {
					$this->response->message = alertDanger("Gagal update data reward karyawan");
				}

		}
		parent::json();
	}

	public function delete($id)
	{
		parent::checkLoginUser(); // user login autentic checking

		if ($this->isPost()) {
			$getById = $this->rewardModel->getById($id);
			if ($getById) {
				$delete = $this->rewardModel->delete($id);
				if ($delete) {
					$this->response->status = true;
					$this->response->message = alertSuccess("Data reward Berhasil di hapus.");
				} else {
					$this->response->message = alertDanger("Data sudah tidak ada.");
				}
			} else {
				$this->response->message = alertDanger("Data sudah tidak ada.");
			}
		}
		parent::json();
	}

}

/* End of file Reward.php */
/* Location: ./application/controllers/approvalowner/Reward.php */
