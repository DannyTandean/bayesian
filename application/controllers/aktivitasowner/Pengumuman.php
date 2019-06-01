<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Pengumuman extends MY_Controller {

	public function __construct()
	{
		parent::__construct();
		$this->load->model('aktivitasowner/Pengumuman_model',"pengumumanModel");
		parent::checkLoginHRD();

	}

	public function index()
	{
		parent::checkLoginUser(); // user login autentic checking

		parent::headerTitle("Aktivitas Data Owner > Pengumuman & Berita","Aktivitas Data Owner","Pengumuman & Berita");
		$breadcrumbs = array(
							"Aktivitas"	=>	site_url('aktivitasowner/pengumuman'),
							"Pengumuman"		=>	"",
						);
		parent::breadcrumbs($breadcrumbs);
		
		parent::viewAktivitasowner();

	}

	public function ajax_list()
	{
		parent::checkLoginUser(); // user login autentic checking

		if ($this->isPost()) {
			$data = array();

			$orderBy = array(null,null,null,"foto","tanggal","judul","tipe","penulis");
			$search = array("judul","penulis");

			$result = $this->pengumumanModel->findDataTable($orderBy,$search);
			foreach ($result as $item) {

				$srcPhoto = base_url().'assets/images/default/no_file_.png';
				if ($item->foto != "") {
					$srcPhoto = base_url()."uploads/aktivitasowner/pengumuman/".$item->foto;
				}
				$dataPhoto = '<a href="'.$srcPhoto.'" data-toggle="lightbox" data-title="Photo Pengumuman" data-footer="">
                    	<img src="'.$srcPhoto.'" class="img-circle" style="height:60px; width:60px;" alt="photo "'.$item->foto.'>
                			</a>';

				$btnTools = '<button class="btn btn-primary btn-mini" onclick="btnDetail('.$item->id_pengumuman.')"><i class="fa fa-list"></i>Details</button>';
				$btnAction = '<button class="btn btn-warning btn-mini" onclick="btnEdit('.$item->id_pengumuman.')"><i class="fa fa-pencil-square-o"></i>Edit</button>';
				$btnAction .= '&nbsp;&nbsp;&nbsp;<button class="btn btn-danger btn-mini" onclick="btnDelete('.$item->id_pengumuman.')"><i class="fa fa-trash-o"></i>Hapus</button>';

				$item->tanggal = date_ind("d M Y",$item->tanggal);
				$item->foto = $dataPhoto;
				$item->button_tool = $btnTools;
				$item->button_action = $btnAction;
				$data[] = $item;
			}
			return $this->pengumumanModel->findDataTableOutput($data,$search);
		}
	}
	public function _do_upload_pengumuman()
	{
		$config['upload_path']      = 	'uploads/aktivitasowner/pengumuman/';
				$config['allowed_types']    = 	'gif|jpg|jpeg|png';
				$config['max_size']         = 	1024; // 1mb
				$config['encrypt_name']		=	true;

				$this->load->library('upload', $config);
				$this->upload->initialize($config);
	}
	public function add()
	{
		parent::checkLoginUser(); // user login autentic checking

		if ($this->isPost()) {
			$tanggal = $this->input->post("tanggal");
			$judul = $this->input->post('judul');
			$tipe = $this->input->post('tipe');
			$isi = $this->input->post('isiPengumuman');
			$username = $this->input->post('username');

			$this->form_validation->set_rules('judul', 'Judul', 'trim|required');
			$this->form_validation->set_rules('tipe', 'Tipe', 'trim|required');
			$this->form_validation->set_rules('isiPengumuman', 'Isi', 'trim|required');


			if ($this->form_validation->run() == TRUE) {
				$data = array(
								"penulis" => $username,
								"tanggal"	=>	$tanggal,
								"judul" => $judul,
								"isi" => $isi,
								"tipe" => $tipe,
							);
							$error_photo_pengumuman = "";
							/*for foto Pengumuman*/
							if (!empty($_FILES["photo_pengumuman"]["name"])) {
								// config upload
								self::_do_upload_pengumuman();

								if (!$this->upload->do_upload("photo_pengumuman")) {
									$this->response->message = "error_file";
									$error_uploadfile = $this->upload->display_errors('<small style="color:red;">', '</small>');
									$this->response->error_uploadfile = $error_uploadfile;
								} else {
									$uploadfile = $this->upload->data();
									$data["foto"]	= $uploadfile["file_name"];
								}

							} else {
								$data["foto"]	= "";
							}
				$pesanNotifAndroid = "Tambah data pengumuman baru";
				$insert = $this->pengumumanModel->insert($data);
				if ($insert) {
					//notif android
					parent::sendNotifTopic("hrd","payroll",$pesanNotifAndroid);
					parent::sendNotifTopic("karyawan","payroll",$pesanNotifAndroid);
					parent::sendNotifTopic("kabag","payroll",$pesanNotifAndroid);
					$this->response->status = true;
					$this->response->message = alertSuccess("Berhasil tambah data Pengumuman.");
				} else {
					$this->response->message = alertDanger("Gagal tambah data Pengumuman.");
				}

			} else {
				$this->response->messages = validation_errors('<span style="color:red;">', '</span><br>');
				$this->response->error = array(
									"judul"	=> form_error("judul",'<span style="color:red;">','</span>'),
									"isi"	=> form_error("isiPengumuman",'<span style="color:red;">','</span>'),
									"tipe"	=> form_error("tipe",'<span style="color:red;">','</span>'),
								);
			}
		}
		parent::json();
	}


	public function getId($id)
	{
		parent::checkLoginUser(); // user login autentic checking

		if ($this->isPost()) {
			$getById = $this->pengumumanModel->getById($id);
			if ($getById) {
				if ($getById->foto != "") {
					$getById->foto = base_url("/")."uploads/aktivitasowner/pengumuman/".$getById->foto;
				} else {
					$getById->foto = base_url("/")."assets/images/default/no_user.png";
				}
				$getById->tanggal = date_ind("d M Y",$getById->tanggal);
				$this->response->status = true;
				$this->response->message = "Data Pengumuman get by id";
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
			$tanggal = $this->input->post("tanggal");
			$judul = $this->input->post('judul');
			$tipe = $this->input->post('tipe');
			$isi = $this->input->post('isiPengumuman');
			$username = $this->input->post('username');

			$this->form_validation->set_rules('judul', 'Judul', 'trim|required');
			$this->form_validation->set_rules('tipe', 'Tipe', 'trim|required');
			$this->form_validation->set_rules('isiPengumuman', 'Isi', 'trim|required');

			if ($this->form_validation->run() == TRUE) {
				$data = array(
								"penulis" => $username,
								"tanggal"	=>	$tanggal,
								"judul" => $judul,
								"isi" => $isi,
								"tipe" => $tipe,
							);
							/*for foto pengumuman*/
							if (!empty($_FILES["photo_pengumuman"]["name"])) {
								// config upload
								self::_do_upload_pengumuman();
								$getById = $this->pengumumanModel->getById($id);
								if (!$this->upload->do_upload("photo_pengumuman")) {
									$this->response->message = "error_foto";
									$error_photo_pengumuman = $this->upload->display_errors('<small style="color:red;">', '</small>');
									$this->response->error_photo_pengumuman = $error_photo_pengumuman;
								} else {
									$photo_pengumuman = $this->upload->data();
									$data["foto"]	= $photo_pengumuman["file_name"];
									if (file_exists("uploads/aktivitasowner/pengumuman/".$getById->foto) && $getById->foto) {
										unlink("uploads/aktivitasowner/pengumuman/".$getById->foto);
									}
								}

							} else {
								if ($this->input->post("is_delete_photo") == 1) {
									$data["foto"]	= "";
									if (file_exists("uploads/aktivitasowner/pengumuman/".$getById->foto) && $getById->foto) {
										unlink("uploads/aktivitasowner/pengumuman/".$getById->foto);
									}
								}
							}
				$pesanNotifAndroid = "Edit data Pengumuman dengan judul ".$judul;
				$update = $this->pengumumanModel->update($id,$data);
				if ($update) {
					//notif android
					parent::sendNotifTopic("hrd","payroll",$pesanNotifAndroid);
					parent::sendNotifTopic("karyawan","payroll",$pesanNotifAndroid);
					parent::sendNotifTopic("kabag","payroll",$pesanNotifAndroid);
					$this->response->status = true;
					$this->response->message = alertSuccess("Berhasil update data Pengumuman.");
				} else {
					$this->response->message = alertDanger("Gagal update data Pengumuman.");
				}
			} else {
				$this->response->messages = validation_errors('<span style="color:red;">', '</span><br>');
				$this->response->error = array(
									"judul"	=> form_error("judul",'<span style="color:red;">','</span>'),
									"isi"	=> form_error("isiPengumuman",'<span style="color:red;">','</span>'),
									"tipe"	=> form_error("tipe",'<span style="color:red;">','</span>'),
								);
			}
		}
		parent::json();
	}

	public function delete($id)
	{
		parent::checkLoginUser(); // user login autentic checking

		if ($this->isPost()) {
			$getById = $this->pengumumanModel->getById($id);
			if ($getById) {
				$delete = $this->pengumumanModel->delete($id);
				if ($delete) {

					if (file_exists("uploads/aktivitasowner/pengumuman/".$getById->foto) && $getById->foto) {
						unlink("uploads/aktivitasowner/pengumuman/".$getById->foto);
					}

					$this->response->status = true;
					$this->response->message = alertSuccess("Data Pengumuman Berhasil di hapus.");
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

/* End of file Pengumuman.php */
/* Location: ./application/controllers/aktivitasowner/Pengumuman.php */
