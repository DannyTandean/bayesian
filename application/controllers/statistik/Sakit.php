<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Sakit extends MY_Controller {

	public function __construct()
	{
		parent::__construct();
		$this->load->model('aktivitas/Sakit_model',"sakitModel");
		$this->load->model('Calendar_model',"calendarModel");
		
	}

	public function index()
	{
		parent::checkLoginUser(); // user login autentic checking

		parent::headerTitle("Statistik Data > Sakit Karyawan","Statistik Data","Sakit Karyawan");
		$breadcrumbs = array(
							"Statistik Data"	=>	site_url('statistik/sakit'),
							"Sakit"		=>	"",
						);
		parent::breadcrumbs($breadcrumbs);
		parent::viewStatistik();
	}

	public function ajax_list($before=null,$after=null)
	{
		parent::checkLoginUser(); // user login autentic checking

		// if ($this->isPost()) {
			$data = array();

			$orderBy = array(null,"tanggal","nama","departemen","jabatan","tgl_sakit","akhir_sakit","keterangans","lama");
			$search = array("nama","departemen","jabatan");

			$result = $this->sakitModel->findDataTable($orderBy,$search,$after,$before);
			foreach ($result as $item) {

				$srcPhoto = base_url().'assets/images/default/no_file_.png';
				if ($item->file != "") {
					$srcPhoto = base_url()."uploads/aktivitas/sakit/".$item->file;
				}
				$dataPhoto = '<a href="'.$srcPhoto.'" data-toggle="lightbox" data-title="Foto File" data-footer="">
                    <img src="'.$srcPhoto.'" class="img-square" style="height:60px; width:60px;" alt="photo "'.$item->nama.'>
                </a>';
                $item->file = $dataPhoto;

				// $btnAction = '<button class="btn btn-warning btn-outline-warning btn-mini" onclick="btnEdit('.$item->id_sakit.')"><i class="fa fa-pencil-square-o"></i>Edit</button>';
				// $btnAction = '&nbsp;&nbsp;&nbsp;<button class="btn btn-danger btn-danger btn-mini" onclick="btnDelete('.$item->id_sakit.')"><i class="fa fa-trash-o"></i>Hapus</button>';
				$item->tanggal = date_ind("d M Y",$item->tanggal);
				$item->tgl_sakit = date_ind("d M Y",$item->tgl_sakit);
				$item->akhir_sakit = date_ind("d M Y",$item->akhir_sakit);
				// $item->button_action = $btnAction;
				$data[] = $item;
			}
			return $this->sakitModel->findDataTableOutput($data,$search,$after,$before);
		// }
	}
	public function ajax_list_after($before,$after)
	{
	 	self::ajax_list($before,$after);
	}

	public function _do_upload_file()
	{
		$config['upload_path']      = 	'uploads/aktivitas/sakit/';
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
			$karyawan = $this->input->post("karyawan");
			$keterangan = $this->input->post("keterangan");
			$mulaiSakit = $this->input->post("mulaiSakit");
			$akhirSakit = $this->input->post("akhirSakit");
			$file = $this->input->post("file");
			$date1=date_create($mulaiSakit);
			$date2=date_create($akhirSakit);
			$lama=date_diff($date1,$date2)->format("%a")+1;
			$this->form_validation->set_rules('tanggal', 'Tanggal', 'trim|required');
			$this->form_validation->set_rules('karyawan', 'Karyawan', 'trim|required');
			$this->form_validation->set_rules('keterangan', 'Keterangan', 'trim|required');
			$this->form_validation->set_rules('mulaiSakit', 'Mulai Sakit', 'trim|required');
			$this->form_validation->set_rules('akhirSakit', 'Akhir Sakit', 'trim|required');

			if ($this->form_validation->run() == TRUE) {
				$data = array(
								"id_karyawan"	=>	$karyawan,
								"tanggal"	=>	$tanggal,
								"keterangans"	=>	$keterangan,
								"tgl_sakit"	=>	$mulaiSakit,
								"akhir_sakit"	=>	$akhirSakit,
								"lama"	=>	$lama,
								
							);

				if (!empty($_FILES["uploadfile"]["name"])) {
					// config upload
					self::_do_upload_file();
					
					if (!$this->upload->do_upload("uploadfile")) {
						$this->response->message = "error_file";
						$error_uploadfile = $this->upload->display_errors('<small style="color:red;">', '</small>');
						$this->response->error_uploadfile = $error_uploadfile;
					} else {
						$uploadfile = $this->upload->data();
						$data["file"]	= $uploadfile["file_name"];
					}
					
				} else {
					$data["file"]	= "";
				}

				
				$insert = $this->sakitModel->insert($data);
				if ($insert) {
					$this->response->status = true;
					$this->response->message = alertSuccess("Berhasil tambah data sakit karyawan.");
				} else {
					$this->response->message = alertDanger("Gagal tambah data sakit karyawan.");
				}
				
			} else {
				// $this->response->message = validation_errors('<span style="color:red;">', '</span><br>');
				$this->response->error = array(
									"karyawan"	=> form_error("karyawan",'<span style="color:red;">','</span>'),
									"tanggal"	=> form_error("tanggal",'<span style="color:red;">','</span>'),
									"tgl_sakit"	=> form_error("mulaiSakit",'<span style="color:red;">','</span>'),
									"akhir_sakit"	=> form_error("akhirSakit",'<span style="color:red;">','</span>'),
									"keterangans"	=> form_error("keterangan",'<span style="color:red;">','</span>'),
									"file"	=> form_error("file",'<span style="color:red;">','</span>'),
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

				if ($getById->file != "") {
					$getById->file = base_url("/")."uploads/aktivitas/sakit/".$getById->file;
				} else {
					$getById->file = base_url("/")."assets/images/default/no_file_.png";
				} 

				$this->response->status = true;
				$this->response->message = "Data sakit get by id";
				$this->response->data = $getById;
			} else {
				$this->response->message = alertDanger("Data sakit tidak ada.");
			}
		}
		parent::json();
	}


	public function delete($id)
	{
		parent::checkLoginUser(); // user login autentic checking
		
		if ($this->isPost()) {
			$checkData = $this->sakitModel->getById($id);
			if ($checkData) {
				$delete = $this->sakitModel->delete($id);
				if ($delete) {

					if (file_exists("uploads/aktivitas/sakit/".$checkData->file) && $checkData->file) {
						unlink("uploads/aktivitas/sakit/".$checkData->file);
					}

					$this->response->status = true;

					$this->response->message = alertSuccess("Data sakit karyawan Berhasil di hapus.");
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

/* End of file Sakit.php */
/* Location: ./application/controllers/statistik/Sakit.php */