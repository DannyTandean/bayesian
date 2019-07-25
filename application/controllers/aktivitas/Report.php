<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Report extends MY_Controller {

	public function __construct()
	{
		parent::__construct();
		$this->load->model('aktivitas/Report_model',"reportModel");
		$this->load->model('Users_model',"usersModel");
	}

	public function index()
	{
		parent::checkLoginUser(); // user login autentic checking

		parent::headerTitle("Activity > User Report","Activity","User Report");
		$breadcrumbs = array(
							"Activity"	=>	site_url('aktivitas/report'),
							"User Report"		=>	"",
						);
		parent::breadcrumbs($breadcrumbs);
		parent::viewAktivitas();
	}

	public function ajax_list()
	{
		parent::checkLoginUser(); // user login autentic checking

		if ($this->isPost()) {
			$data = array();

			$orderBy = array(null,null,"nama","user.email","no_telp","transaction_limit");
			$search = array("nama","user.email","no_telp");

			$result = $this->reportModel->findDataTable($orderBy,$search);
			foreach ($result as $item) {
				$btnAction = "";
				if ($item->status == 0 || $item->status == 1) {
					$btnAction .='&emsp;&emsp;&emsp;<button class="btn btn-warning btn-warning btn-mini" onclick="btnBlock('.$item->report_id.','."2".')"><i class="fa fa-times"></i>Valid</button><br><br>';
				}
				else if ($item->status == 2) {
					$btnAction .='&emsp;&emsp;&emsp;<button class="btn btn-warning btn-warning btn-mini" onclick="btnBlock('.$item->report_id.','."0".')"><i class="fa fa-times"></i>Invalid</button><br><br>';
				}
				$btnAction .= '<button class="btn btn-info btn-info btn-mini" onclick="btnDetail('.$item->report_id.')"><i class="fa fa-pencil-square-o"></i>Detail</button>';
				$btnAction .= '&nbsp;&nbsp;&nbsp;<button class="btn btn-danger btn-danger btn-mini" onclick="btnDelete('.$item->report_id.')"><i class="fa fa-trash-o"></i>Delete</button>';
				$item->transaction_limit = "Rp.".number_format($item->transaction_limit,0,",",",");
				$item->button_action = $btnAction;
				$data[] = $item;
			}
			return $this->reportModel->findDataTableOutput($data,$search);
		}
	}

	public function getId($id)
	{
		parent::checkLoginUser(); // user login autentic checking

		if ($this->isPost()) {
			$getById = $this->reportModel->getById($id);
			if ($getById->image != "") {
				$getById->image = base_url("/")."uploads/aktivitas/orang/".$getById->image;
			} else {
				$getById->image = base_url("/")."assets/images/default/no_user.png";
			}
			if ($getById) {
				$getById->create_at = date_ind("d M Y",$getById->create_at);
				$this->response->status = true;
				$this->response->message = "Data laporan user get by id";
				$this->response->data = $getById;
			} else {
				$this->response->message = alertDanger("Data laporan user tidak ada.");
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
				$user_id = $this->reportModel->getById($id);

				$update = $this->reportModel->block($id,$data);
				if ($update) {
					$token = $this->usersModel->getByIdUser($user_id->id_user);
					if (($token->key_notif != null || $token->key_notif != "") && $status == 2) {
						// parent::pushnotif($token->key_notif,$token->nama,"IP anda dicurigai fraud");
					}
					$this->response->status = true;
					$this->response->message = "<span style='color:green'>berhasil update data Laporan.!</span>";
				}
				else {
					$this->response->message = "<span style='color:red'>gagal update data Laporan.!</span>";
				}
			}
		parent::json();
	}

	public function delete($id)
	{
		parent::checkLoginUser(); // user login autentic checking

		if ($this->isPost()) {
			$checkData = $this->reportModel->getById($id);

			if ($checkData) {
				$delete = $this->reportModel->delete($id);
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

}

/* End of file Report.php */
/* Location: ./application/controllers/aktivitas/Report.php */
