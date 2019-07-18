<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Manage_user extends MY_Controller {

	public function __construct()
	{
		parent::__construct();
		$this->load->model('aktivitas/Manage_user_model',"userModel");
		parent::checkLoginUser();

	}

	public function index()
	{
		parent::checkLoginUser(); // user login autentic checking

		parent::headerTitle("Activity > User","Activity","User");
		$breadcrumbs = array(
							"Aktivitas"	=>	site_url('aktivitas/manage_user'),
							"User"		=>	"",
						);
		parent::breadcrumbs($breadcrumbs);

		parent::viewAktivitas();
	}

	public function ajax_list()
	{
		parent::checkLoginUser(); // user login autentic checking

		if ($this->isPost()) {
			$data = array();

			$orderBy = array(null,null,null,"nama","jenis_kelamin",null,"no_telp");
			$search = array("nama","jenis_kelamin");

			$result = $this->userModel->findDataTable($orderBy,$search);
			foreach ($result as $item) {
				$btnAction = '<button class="btn btn-info btn-info btn-mini" onclick="btnDetail('.$item->id_user.')"><i class="fa fa-pencil-square-o"></i>Detail</button>';

				if($item->block == 0)
				{
					$item->block = '<label class="label label-info">Normal</label>';
					$btnAction .= '&nbsp;&nbsp;&nbsp;<button class="btn btn-warning btn-warning btn-mini" onclick="btnBlock('.$item->id_user.','."1".')"><i class="fa fa-trash-o"></i>Block</button>';
				}
				else if($item->block == 1)
				{
					$item->block = '<label class="label label-danger">Block</label>';
					$btnAction .= '&nbsp;&nbsp;&nbsp;<button class="btn btn-warning btn-warning btn-mini" onclick="btnBlock('.$item->id_user.','."0".')"><i class="fa fa-trash-o"></i>Unblock</button>';
				}
				$btnAction .= '&nbsp;&nbsp;&nbsp;<button class="btn btn-danger btn-danger btn-mini" onclick="btnDelete('.$item->id_user.')"><i class="fa fa-trash-o"></i>Delete</button>';
				$srcPhoto = base_url().'assets/images/default/no_user.png';
				if ($item->image != "") {
					$srcPhoto = base_url()."uploads/aktivitas	/orang/".$item->image;
				}
				$dataPhoto = '<a href="'.$srcPhoto.'" data-toggle="lightbox" data-title="Photo User" data-footer="">
                    <img src="'.$srcPhoto.'" class="img-circle" style="height:60px; width:60px;" alt="photo "'.$item->nama.'>
                </a>';
				$item->create_at = date_ind("d M Y",$item->create_at);
				$item->transaction_limit = "Rp.".number_format($item->transaction_limit,0,",",",");
				$item->button_action = $btnAction;
				$item->image = $dataPhoto;
				$data[] = $item;
			}
			return $this->userModel->findDataTableOutput($data,$search);
		}
	}

	public function getId($id)
	{
		parent::checkLoginUser(); // user login autentic checking

		if ($this->isPost()) {
			$getById = $this->userModel->getById($id);
			if ($getById) {
				if ($getById->image != "") {
					$getById->image = base_url("/")."uploads/aktivitas/orang/".$getById->image;
				} else {
					$getById->image = base_url("/")."assets/images/default/no_user.png";
				}
				$getById->create_at = date_ind("d M Y",$getById->create_at);

				$this->response->status = true;
				$this->response->message = "Data user get by id";
				$this->response->data = $getById;
			} else {
				$this->response->message = alertDanger("Data tidak ada.");
			}
		}
		parent::json();
	}

	public function block($id,$status)
	{
		parent::checkLoginUser(); // user login autentic checking
		if ($this->isPost()) {
				$data = array(
												'block' => $status,
										 );
				$update = $this->userModel->block($id,$data);
				if ($update) {
					$this->response->status = true;
					$this->response->message = "<span style='color:green'>berhasil update data user.!</span>";
				}
				else {
					$this->response->message = "<span style='color:red'>gagal update data user.!</span>";
				}
			}
		parent::json();
	}

	public function delete($id)
	{
		parent::checkLoginUser(); // user login autentic checking

		if ($this->isPost()) {
				$delete = $this->userModel->delete($id);
				$getById = $this->userModel->getById($id);
				if (file_exists("uploads/aktivitas/orang/".$getById->image) && $getById->image) {
							unlink("uploads/aktivitas/orang/".$getById->image);
				}
				if ($delete) {
					$this->response->status = true;
					$this->response->message = "<span style='color:green'>berhasil menghapus data user.!</span>";
				}
				else {
					$this->response->message = "<span style='color:red'>gagal menghapus data user.!</span>";
				}
			}
		parent::json();
	}
}

/* End of file Manage_user.php */
/* Location: ./application/controllers/aktiviats/Manage_user.php */
