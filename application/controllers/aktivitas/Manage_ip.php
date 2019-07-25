<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Manage_ip extends MY_Controller {

	public function __construct()
	{
		parent::__construct();
		$this->load->model('aktivitas/Manage_ip_model',"ipModel");
		$this->load->model('Users_model',"usersModel");
	}

	public function index()
	{
		parent::checkLoginUser(); // user login autentic checking

		parent::headerTitle("Activity > IP","Activity","IP");
		$breadcrumbs = array(
							"Activity"	=>	site_url('aktivitas/manage_ip'),
							"IP"		=>	"",
						);
		parent::breadcrumbs($breadcrumbs);
		parent::viewAktivitas();
	}

	public function ajax_list()
	{
		parent::checkLoginUser(); // user login autentic checking

		if ($this->isPost()) {
			$data = array();

			$orderBy = array(null,null,"nama","ip_address","tipe","status","create_at");
			$search = array("nama","ip_address");

			$result = $this->ipModel->findDataTable($orderBy,$search);
			foreach ($result as $item) {

				$btnAction = '<button class="btn btn-info btn-info btn-mini" onclick="btnDetail('.$item->ip_id.')"><i class="fa fa-pencil-square-o"></i>Detail</button>';


				if ($item->status == 0) {
					$item->status = '<label class="label label-success">Normal</label>';
					$btnAction .= '&nbsp;&nbsp;&nbsp;<button class="btn btn-warning btn-warning btn-mini" onclick="btnBlock('.$item->ip_id.','."1".')"><i class="fa fa-trash-o"></i>Block</button>';

				}
				else if ($item->status == 1) {
					$item->status = '<label class="label label-danger">Block</label>';
					$btnAction .= '&nbsp;&nbsp;&nbsp;<button class="btn btn-warning btn-warning btn-mini" onclick="btnBlock('.$item->ip_id.','."0".')"><i class="fa fa-trash-o"></i>Unblock</button>';
				}
				$btnAction .= '<br><br>&emsp;&emsp;&emsp;<button class="btn btn-danger btn-danger btn-mini" onclick="btnDelete('.$item->ip_id.')"><i class="fa fa-trash-o"></i>Delete</button>';

				if ($item->tipe == 0) {
					$item->tipe = "Transaction";
				}
				else if ($item->tipe == 1) {
					$item->tipe = "Payment";
				}

				$item->create_at = date_ind("d M Y",$item->create_at);
				$item->button_action = $btnAction;
				$data[] = $item;
			}
			return $this->ipModel->findDataTableOutput($data,$search);
		}
	}

	public function getId($id)
	{
		parent::checkLoginUser(); // user login autentic checking

		if ($this->isPost()) {
			$getById = $this->ipModel->getById($id);
			if ($getById->image != "") {
				$getById->image = base_url("/")."uploads/aktivitas/orang/".$getById->image;
			} else {
				$getById->image = base_url("/")."assets/images/default/no_user.png";
			}
			if ($getById) {
				$this->response->status = true;
				$this->response->message = "Data laporan user get by id";
				$this->response->data = $getById;
			} else {
				$this->response->message = alertDanger("Data laporan user tidak ada.");
			}
		}
		parent::json();
	}

	public function delete($id)
	{
		parent::checkLoginUser(); // user login autentic checking

		if ($this->isPost()) {
			$checkData = $this->ipModel->getById($id);

			if ($checkData) {
				$delete = $this->ipModel->delete($id);
				if ($delete) {
					$this->response->status = true;
					$this->response->message = alertSuccess("Data laporan user Berhasil di hapus.");
				} else {
					$this->response->message = alertDanger("Data sudah tidak ada.");
				}
			} else {
				$this->response->message = alertDanger("Data sudah tidak ada.");
			}
		}
		parent::json();
	}

	public function block($id,$status)
	{
		parent::checkLoginUser(); // user login autentic checking
		if ($this->isPost()) {
				$data = array(
												'status' => $status,
										 );
				$user_id = $this->ipModel->getById($id);
				$update = $this->ipModel->block($id,$data);
				if ($update) {
					$token = $this->usersModel->getByIdUser($user_id->id_user);
					if (($token->key_user != null || $token->key_user != "") && $status == 2) {
						parent::pushnotif($token->key_user,$token->nama,"IP anda dicurigai fraud");
					}
					$this->response->status = true;
					$this->response->message = "<span style='color:green'>berhasil update data IP.!</span>";
				}
				else {
					$this->response->message = "<span style='color:red'>gagal update data IP.!</span>";
				}
			}
		parent::json();
	}

}

/* End of file Report.php */
/* Location: ./application/controllers/aktivitas/Report.php */
