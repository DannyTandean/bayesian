<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Sakit extends MY_Controller {

	public function __construct()
	{
		parent::__construct();
		$this->load->model('aktivitas/Sakit_model',"sakitModel");
		$this->load->model('master/Karyawan_model', "karyawanModel");
		$this->load->model('Token_model',"tokenModel");
		parent::checkLoginOwner();
		
	}

	public function index()
	{
		parent::checkLoginOwner(); // user login autentic checking

		parent::headerTitle("Aktivitas Data > Sakit Karyawan","Aktivitas Data","Sakit Karyawan");
		$breadcrumbs = array(
							"Aktivitas Data"	=>	site_url('aktivitas/sakit'),
							"Sakit"		=>	"",
						);
		parent::breadcrumbs($breadcrumbs);
		$data = array();
		$data['karyawan'] = $this->sakitModel->getKaryawan();
		parent::viewData($data);
		parent::viewAktivitas();
	}

	public function ajax_list()
	{
		parent::checkLoginUser(); // user login autentic checking

		if ($this->isPost()) {
			$data = array();

			$orderBy = array(null,null,"tanggal","nama","departemen","jabatan","status","tgl_sakit","akhir_sakit","keterangans","file","lama");
			$search = array("nama","departemen","jabatan");

			$result = $this->sakitModel->findDataTable($orderBy,$search);
			foreach ($result as $item) {
				if($item->status == "Proses")
				{
					$item->status = '<label class="label label-info">'.$item->status.'</label>';
				}
				else if($item->status == "Diterima")
				{
					$item->status = '<label class="label label-success">'.$item->status.'</label>';
				}
				else {
					$item->status = '<label class="label label-danger">'.$item->status.'</label>';
				}
				$srcPhoto = base_url().'assets/images/default/no_file_.png';
				if ($item->file != "") {
					$srcPhoto = base_url()."uploads/aktivitas/sakit/".$item->file;
				}
				$dataPhoto = '<a href="'.$srcPhoto.'" data-toggle="lightbox" data-title="Foto File" data-footer="">
                    <img src="'.$srcPhoto.'" class="img-square" style="height:60px; width:60px;" alt="photo "'.$item->nama.'>
                </a>';
                $item->file = $dataPhoto;

				$btnAction = '<button class="btn btn-warning btn-warning btn-mini" onclick="btnEdit('.$item->id_sakit.')"><i class="fa fa-pencil-square-o"></i>Edit</button>';
				$btnAction .= '&nbsp;&nbsp;&nbsp;<button class="btn btn-danger btn-danger btn-mini" onclick="btnDelete('.$item->id_sakit.')"><i class="fa fa-trash-o"></i>Hapus</button>';
				$item->tanggal = date_ind("d M Y",$item->tanggal);
				$item->tgl_sakit = date_ind("d M Y",$item->tgl_sakit);
				$item->akhir_sakit = date_ind("d M Y",$item->akhir_sakit);
				$item->button_action = $btnAction;
				$data[] = $item;
			}
			return $this->sakitModel->findDataTableOutput($data,$search);
		}
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
		
	// add()
	// 
	public function add()
	{
		parent::checkLoginUser(); // user login autentic checking

		if ($this->isPost()) {
			$tanggal = $this->input->post("tanggal");
			$karyawan = $this->input->post('karyawan');
			$mulaiSakit = $this->input->post('mulaiSakit');
			$akhirSakit = $this->input->post('akhirSakit');
			$file = $this->input->post("file");
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
				
				$checkDataJadwal =  $this->sakitModel->checkDataJadwal($tanggal,$karyawan);
				if (sizeof($checkDataJadwal) >0 && $checkDataJadwal[0]->id_karyawan == $karyawan) { 
				
					$this->response->status = false;
					$this->response->message = alertDanger("Data Karyawan Yang Di Input dengan tanggal yang anda pilih sudah ada.!");
				} else {
				$data = array(
								"id_karyawan" => $karyawan,
								"tanggal"	=>	$tanggal,
								"tgl_sakit" => $mulaiSakit,
								"akhir_sakit" => $akhirSakit,
								"file" => $file,
								"keterangans"=>	$keterangan,
								"lama" => $lama
							);
				$dataNotif = array(
									"keterangan"=> 	" Tambah data Sakit karyawan baru.",
									"user_id"	=>	$this->user->id_pengguna,
									"level"		=>	"hrd",
									"url_direct"=>	"approval/sakit",
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
				

				$dataToken = $this->tokenModel->getByToken("kabag");
				$insert = $this->sakitModel->insert($data,$dataNotif);
				if ($insert) {
					parent::insertNotif($dataNotif);
					parent::sendNotifTopic("hrd","Pengajuan Sakit","Karyawan mengajukan data Sakit baru","001");
					$this->response->status = true;
					$this->response->message = alertSuccess("Berhasil tambah data Sakit Karyawan menunggu approval dari HRD.");
				} else {
					$this->response->message = alertDanger("Gagal tambah data Sakit Karyawan.");
				}
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

	public function update($id)
	{
		parent::checkLoginUser(); // user login autentic checking

		if ($this->isPost()) {
			$getById = $this->sakitModel->getById($id);
			$karyawan = $this->input->post('karyawan');
			$mulaiSakit = $this->input->post('mulaiSakit');
			$akhirSakit = $this->input->post('akhirSakit');
			$keterangan = $this->input->post("keterangan");
			$date1=date_create($mulaiSakit);
			$date2=date_create($akhirSakit);
			$lama=date_diff($date1,$date2)->format("%a")+1;
			$this->form_validation->set_rules('mulaiSakit', 'Mulai Sakit', 'trim|required');
			$this->form_validation->set_rules('akhirSakit', 'Akhir Sakit', 'trim|required');
			$this->form_validation->set_rules('keterangan', 'Keterangan', 'trim|required');
			$getKaryawan = $this->karyawanModel->getById($karyawan);
			if ($this->form_validation->run() == TRUE) {
				$data = array(
								"id_karyawan" => $karyawan,
								"tanggal"	=>	$getById->tanggal,
								"tgl_sakit" => $mulaiSakit,
								"akhir_sakit" => $akhirSakit,
								"keterangans"=>	$keterangan,
								"lama" => $lama
							);
				$dataNotif = array(
											"keterangan"=> 	" Edit/update data Sakit karyawan dengan Nama <u>".$getKaryawan->nama."</u> dan No Karyawan : <u>".$getKaryawan->idfp."</u>",
											"user_id"	=>	$this->user_id,
											"level"		=>	"hrd",
											"url_direct"=>	"approval/sakit",
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
					
				} 
				$dataToken = $this->tokenModel->getByToken("kabag");
				$update = $this->sakitModel->update($id,$data,$dataNotif);
				if ($update) {
					parent::insertNotif($dataNotif);
					parent::sendNotifTopic("hrd","Informasi","Perubahan Sakit".$getKaryawan->nama,"037");
					$this->response->status = true;
					$this->response->message = alertSuccess("Berhasil update data izin karyawan.");
				} else {
					$this->response->message = alertDanger("Gagal update data izin karyawan.");
				}
			} else {
				// $this->response->message = validation_errors('<span style="color:red;">', '</span><br>');
				$this->response->error = array(
									"karyawan"	=> form_error("karyawan",'<span style="color:red;">','</span>'),
									"tanggal"	=> form_error("tanggal",'<span style="color:red;">','</span>'),
									"mulaiIzin"	=> form_error("mulaiIzin",'<span style="color:red;">','</span>'),
									"akhirIzin"	=> form_error("akhirIzin",'<span style="color:red;">','</span>'),
									"keterangans"	=> form_error("keterangan",'<span style="color:red;">','</span>'),
								);
			}
		}
		parent::json();
	}


	public function delete($id)
	{
		parent::checkLoginUser(); // user login autentic checking
		
		if ($this->isPost()) {
			$checkData = $this->sakitModel->getById($id);
			$getKaryawan = $this->karyawanModel->getById($checkData->id_karyawan);
			if ($checkData) {

				$dataNotif = array(
										"keterangan"=> 	" Hapus data Sakit karyawan dengan Nama <u>".$getKaryawan->nama."</u> dan No Karyawan : <u>".$getKaryawan->idfp."</u>",
										"user_id"	=>	$this->user_id,
										"level"		=>	"hrd",
										"url_direct"=>	"aktivitas/sakit",
									);
				$whereAbsensi = array(
										"kode"			=>	$getKaryawan->kode_karyawan,
										"tanggal >="	=> 	$checkData->tgl_sakit,
										"tanggal <="	=>	$checkData->akhir_sakit,
										"kerja"			=>	"Sakit",
									);

				$delete = $this->sakitModel->delete($id,$dataNotif,$whereAbsensi);
				if ($delete) {
					parent::insertNotif($dataNotif);
					parent::sendNotifTopic("hrd","Informasi","Data Sakit ini telah di hapus","038");
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
/* Location: ./application/controllers/master/Sakit.php */