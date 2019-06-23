<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Users extends MY_Controller {

	public function __construct()
	{
		parent::__construct();
		$this->load->model("users_model","usersModel");
	}

	public function index()
	{
		parent::checkLoginUser(); // check login user

		// parent::headerTitle("Pengguna","Pengguna");
		//
		// $breadcrumbs = array(
		// 					"Pengguna"	=>	site_url('users'),
		// 				);
		// parent::breadcrumbs($breadcrumbs);
		//
		// parent::view();
		redirect(base_url()."aktivitas/transaction");
	}

	public function ajax_list()
	{
		parent::checkLoginUser(); // user login autentic checking

		if ($this->isPost()) {
			$data = array();

			$orderBy = array(null,null,"username","level","nama_pengguna","keterangan");
			$search = array("username","level","nama_pengguna","keterangan");

			$result = $this->usersModel->findDataTable($orderBy,$search);
			foreach ($result as $item) {

				$srcPhoto = 'assets/images/default/no_user.png';
				if ($item->photo != "") {
					$srcPhoto = "uploads/pengguna/".$item->photo;
				}
				$dataPhoto = '<a href="'.$srcPhoto.'" data-toggle="lightbox" data-title="Photo Pengguna" data-footer="">
                    <img src="'.$srcPhoto.'" class="img-circle" style="height:60px; width:60px;" alt="photo "'.$item->nama_pengguna.'>
                </a>';

				$item->photo = $dataPhoto;

				$btnAction = "";
				if ($this->user->level == "admin" || $this->user->level == "owner") {
					if ($item->id_pengguna == $this->user->id_pengguna) {
						$btnAction = '<button class="btn btn-warning btn-mini" onclick="btnEdit('.$item->id_pengguna.')"><i class="fa fa-pencil-square-o"></i>Edit</button>';

					}

					if ($item->id_pengguna != $this->user->id_pengguna) {
						if ($item->level == "hrd" || $item->level =="owner") {
							$btnAction = '<button class="btn btn-warning btn-mini" onclick="btnEdit('.$item->id_pengguna.')"><i class="fa fa-pencil-square-o"></i>Edit</button>';
							$btnAction .= '&nbsp;&nbsp;&nbsp;<button class="btn btn-danger btn-mini" onclick="btnDelete('.$item->id_pengguna.')"><i class="fa fa-trash-o"></i>Hapus</button>';
						}
					}
				} else  {
					if ($item->id_pengguna == $this->user->id_pengguna) {
						$btnAction = '<button class="btn btn-warning btn-mini" onclick="btnEdit('.$item->id_pengguna.')"><i class="fa fa-pencil-square-o"></i>Edit</button>';
					}
				}

				$item->button_action = $btnAction;
				$data[] = $item;
			}
			return $this->usersModel->findDataTableOutput($data,$search);
		}
	}

	public function _do_upload()
	{
		$config['upload_path']      = 	'uploads/pengguna/';
        $config['allowed_types']    = 	'gif|jpg|jpeg|png';
        $config['max_size']         = 	2048; // 2mb
        /*$config['max_width']        = 	2000;
        $config['max_height']       =	1500;*/
        $config['encrypt_name']		=	true;

        $this->load->library('upload', $config);
        $this->upload->initialize($config);
	}

	public function add()
	{
		parent::checkLoginUser(); // user login autentic checking

		if ($this->isPost()) {
			$nama = $this->input->post("nama");
			$username = $this->input->post("username");
			$password = $this->input->post("password");
			$ulangi_password = $this->input->post("ulangi_password");
			// $keterangan = $this->input->post("keterangan");
			$level = $this->input->post("level");

			$this->form_validation->set_rules('nama', 'Nama', 'trim|required');
			$this->form_validation->set_rules('username', 'Username', 'trim|required|is_unique[pengguna.username]');
			$this->form_validation->set_rules('password', 'Password', 'trim|required|min_length[6]');
			$this->form_validation->set_rules('ulangi_password', 'ulangi Password', 'trim|required|matches[password]');
			// $this->form_validation->set_rules('keterangan', 'Keterangan', 'trim|required');
			$this->form_validation->set_rules('level', 'Level', 'trim|required');

			if ($this->form_validation->run() == TRUE) {
				// config upload photo
				self::_do_upload();
				$data = array(
								"nama_pengguna"	=>	$nama,
								"username"		=>	$username,
								"password"		=>	sha1($password),
								// "keterangan"	=>	$keterangan,
								"level"			=>	$level,
							);

				if (!empty($_FILES["photo"]["name"])) {
					if (!$this->upload->do_upload("photo")) {
						$this->response->message = "Error photo";
						$this->response->error = ["photo" => spanRed("<b>Error photo :</b>".$this->upload->display_errors())];
					} else {
						$photoUser = $this->upload->data();
						$data["photo"]	= $photoUser["file_name"];
						$insert = $this->usersModel->insert($data);
						if ($insert) {
							$this->response->status = true;
							$this->response->message = alertSuccess("Berhasil tambah data Pengguna.");
						} else {
							$this->response->message = alertDanger("Gagal tambah data Pengguna.");
						}
					}
				} else {
					$data["photo"]	= "";
					$insert = $this->usersModel->insert($data);
					if ($insert) {
						$this->response->status = true;
						$this->response->message = alertSuccess("Berhasil tambah data Pengguna.");
						// $this->response->data = $data;
					} else {
						$this->response->message = alertDanger("Gagal tambah data Pengguna.");
					}
				}

			} else {
				$this->response->message = validation_errors('<span style="color:red;">', '</span><br>');
				$this->response->error = array(
									"nama"	=> form_error("nama",'<span style="color:red;">','</span>'),
									"username"	=> form_error("username",'<span style="color:red;">','</span>'),
									"password"	=> form_error("password",'<span style="color:red;">','</span>'),
									"ulangi_password"	=> form_error("ulangi_password",'<span style="color:red;">','</span>'),
									// "keterangan"=> form_error("keterangan",'<span style="color:red;">','</span>'),
									"level"=> form_error("level",'<span style="color:red;">','</span>'),
								);
			}
		}
		parent::json();
	}

	public function getId($id)
	{
		parent::checkLoginUser(); // user login autentic checking

		if ($this->isPost()) {
			$getById = $this->usersModel->getById($id);
			if ($getById) {
				$srcPhoto = 'assets/images/default/no_user.png';
				if ($getById->photo != "") {
					$srcPhoto = "uploads/pengguna/".$getById->photo;
				}
				$getById->photo = $srcPhoto;

				$this->response->status = true;
				$this->response->message = "Data username get by id";
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
			$nama = $this->input->post("nama");
			$password = $this->input->post("password");
			$ulangi_password = $this->input->post("ulangi_password");
			$level = $this->input->post("level");

			$this->form_validation->set_rules('nama', 'Nama', 'trim|required');
			if ($password != "") {
				$this->form_validation->set_rules('password', 'Password', 'trim|required|min_length[6]');
				$this->form_validation->set_rules('ulangi_password', 'ulangi Password', 'trim|required|matches[password]');

			}
			$this->form_validation->set_rules('level', 'Level', 'trim|required');

			if ($this->form_validation->run() == TRUE) {

				// config upload photo
				self::_do_upload();
				$getById = $this->usersModel->getById($id); // check user id;
				if ($getById) {

					$data = array(
									"nama_pengguna"	=>	$nama,
									"level"			=>	$level,
								);
					if ($password != "") {
						$data["password"]	=	sha1($password);
					}

					if (!empty($_FILES["photo"]["name"])) {
						if (!$this->upload->do_upload("photo")) {
							$this->response->message = "Error photo";
							$this->response->error = ["photo" => spanRed("<b>Error photo :</b>".$this->upload->display_errors())];
						} else {
							$photoUser = $this->upload->data();
							$data["photo"]	= $photoUser["file_name"];
							if (file_exists("uploads/pengguna/".$getById->photo) && $getById->photo) {
								unlink("uploads/pengguna/".$getById->photo);
							}
							$update = $this->usersModel->update($id,$data);
							if ($update) {
								$this->response->status = true;
								$this->response->message = alertSuccess("Berhasil update data Pengguna.");
							} else {
								$this->response->message = alertDanger("Gagal update data Pengguna.");
							}
						}
					} else {
						if ($this->input->post("is_delete") == 1) {
							$data["photo"]	= "";
							if (file_exists("uploads/pengguna/".$getById->photo) && $getById->photo) {
								unlink("uploads/pengguna/".$getById->photo);
							}
						}
						$update = $this->usersModel->update($id,$data);
						if ($update) {
							$this->response->status = true;
							$this->response->message = alertSuccess("Berhasil update data Pengguna.");
						} else {
							$this->response->message = alertDanger("Gagal update data Pengguna.");
						}
					}
				} else {
					$this->response->message = alertDanger("Data sudah tidak ada..!");
				}
			} else {
				$this->response->message = validation_errors('<span style="color:red;">', '</span><br>');
				$this->response->error = array(
									"nama"	=> form_error("nama",'<span style="color:red;">','</span>'),
									"password"	=> form_error("password",'<span style="color:red;">','</span>'),
									"ulangi_password"	=> form_error("ulangi_password",'<span style="color:red;">','</span>'),
									"level"=> form_error("level",'<span style="color:red;">','</span>'),
								);
			}
		}
		parent::json();
	}

	public function delete($id)
	{
		parent::checkLoginUser(); // user login autentic checking

		if ($this->isPost()) {
			$data = $this->usersModel->getById($id);
			if($data){
				$delete = $this->usersModel->delete($id);
				if ($delete) {
					$this->response->status = true;
					$this->response->message = alertSuccess("Data berhasil di hapus..");
					if (file_exists("uploads/pengguna/".$data->photo) && $data->photo) {
						unlink("uploads/pengguna/".$data->photo);
					}
				} else {
					$this->response->status = false;
					$this->response->message = alertDanger("Opps, terjadi kesalahan.<br>Mungkin sudah dihapus pengguna lain");
				}
			} else {
				$this->response->message = alertDanger("Data tidak ada.");
			}
		}
		parent::json();
	}


}

/* End of file Users.php */
/* Location: ./application/controllers/Users.php */
