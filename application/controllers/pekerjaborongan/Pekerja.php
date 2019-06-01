<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Pekerja extends MY_Controller {

	public function __construct()
	{
		parent::__construct();
		$this->load->model('pekerjaborongan/Pekerja_model',"pekerjaModel");
		$this->load->model('master/Karyawan_model', "karyawanModel");
		
	}

	public function index()
	{
		parent::checkLoginUser(); // user login autentic checking

		parent::headerTitle("Pekerja Borongan > Pekerja","Pekerja Borongan","Pekerja");
		$breadcrumbs = array(
							"Pekerja Borongan"	=>	site_url('pekerjaborongan/pekerja'),
							"Pekerja"		=>	"",
						);
		parent::breadcrumbs($breadcrumbs);
		$data = array();
		// $data['departemen'] = $this->pekerjaModel->departemenDataAll();
		$data['bank'] = $this->pekerjaModel->bankDataAll();
		parent::viewData($data);
		parent::viewPekerjaBorongan();
	}

	public function ajax_list()
	{
		parent::checkLoginUser(); // user login autentic checking

		if ($this->isPost()) {
			$data = array();

			$orderBy = array(null,"null","idfp","nama","kelamin","telepon");
			$search = array("idfp","nama");

			$result = $this->pekerjaModel->findDataTable($orderBy,$search);
			foreach ($result as $item) {

				$srcPhoto = base_url().'assets/images/default/no_user.png';
				if ($item->foto != "") {
					$srcPhoto = base_url()."uploads/master/karyawan/orang/".$item->foto;
				}
				$dataPhoto = '<a href="'.$srcPhoto.'" data-toggle="lightbox" data-title="Photo Karyawan" data-footer="">
                    <img src="'.$srcPhoto.'" class="img-circle" style="height:60px; width:60px;" alt="photo "'.$item->nama.'>
                </a>';

                $item->foto = $dataPhoto;
				// $item->harga = "Rp.".number_format($item->harga,0,",",".");
				
				$btnAction = '<button class="btn btn-warning btn-outline-warning btn-mini" onclick="btnEdit('.$item->id_pekerja.')"><i class="fa fa-pencil-square-o"></i></button>';
				$btnAction .= '&nbsp;&nbsp;&nbsp;<button class="btn btn-outline-secondary btn-mini" title="QRcode" onclick="btnQRcode('.$item->id_pekerja.')"><i class="icofont icofont-qr-code"></i></button>';

				$btnAction .= '&nbsp;&nbsp;&nbsp;<button class="btn btn-danger btn-outline-danger btn-mini" onclick="btnDelete('.$item->id_pekerja.')"><i class="fa fa-trash-o"></i></button>';
				
				$item->button_action = $btnAction;
				$data[] = $item;
			}
			return $this->pekerjaModel->findDataTableOutput($data,$search);
		}
	}

	// public function qrCodeGenerate($id)
	// {
	// 	// $this->load->library('qr_code');

	// 	parent::checkLoginUser(); // user login autentic checking

	// 	if ($this->isPost()) {
	// 		$getData = $this->pekerjaborongan->getById($id); // untuk check qrcode_file
	// 		$getId = $this->pekerjaborongan->getById($id); // untuk tampilian view qrcide_file
	// 		if ($getId) {
	// 			$srcPhoto = base_url().'assets/images/default/no_user.png';
	// 			if ($getId->foto != "") {
	// 				$srcPhoto = base_url()."uploads/pekerjaborongan/pekerja/".$getId->foto;
	// 			}
	// 			$getId->foto = $srcPhoto;

	// 			$srcQrCode = base_url("/")."assets/images/default/empty_file.png";
	// 			if ($getData->qrcode_file != "") {
	// 				if (!file_exists("uploads/pekerjaborongan/".$getData->qrcode_file)) {
	// 					$qrcodeUpload = self::qrCodeGenerateUpload($getData->kode_karyawan,$getData->qrcode_file);
	// 					$this->response->file_exists = "tidak ada qrcode_file";
	// 					$this->response->qrcode_upload = $qrcodeUpload;
	// 				} else {
	// 					$this->response->file_exists = "ada qrcode_file";
	// 				}
	// 				$srcQrCode = base_url()."uploads/pekerjaborongan/pekerja/".$getId->qrcode_file;
	// 			} else {
	// 				$image_name = md5(uniqid().$getData->kode_karyawan).'.png'; //buat name dari qr code sesuai dengan kodeKaryawan
	// 				self::qrCodeGenerateUpload($getData->kode_karyawan,$image_name);
	// 				$update = $this->pekerjaborongan->update($id,array("qrcode_file" => $image_name));
	// 				if ($update) {
	// 					$getData = $this->pekerjaborongan->getById($id);
	// 					$srcQrCode = base_url()."uploads/master/karyawan/qrcode/".$getData->qrcode_file;
	// 				}
	// 			}
	// 			$getId->qrcode_file = $srcQrCode;

	// 			$this->response->status = true;
	// 			$this->response->message = "QRcode generate success";
	// 			$this->response->data = $getId;
	// 		} else {
	// 			$this->response->message = alertDanger("Data tidak ada.");
	// 		}
	// 	}
	// 	parent::json();
	// }


	public function _do_upload_orang()
	{
		$config['upload_path']      = 	'uploads/pekerjaborongan/pekerja/';
        $config['allowed_types']    = 	'gif|jpg|jpeg|png';
        $config['max_size']         = 	1024; // 1mb
        $config['encrypt_name']		=	true;

        $this->load->library('upload', $config);
        $this->upload->initialize($config);
	}

	public function add($batasKaryawan=false)
	{
		parent::checkLoginUser(); // user login autentic checking

		if ($this->isPost()) {
			$no_id = $this->input->post("no_id");
			$nama = $this->input->post('nama');
			$alamat = $this->input->post('alamat');
			$tempat_lahir = $this->input->post('tempat_lahir');
			$tgl_lahir = $this->input->post('tgl_lahir');
			$telepon = $this->input->post('telepon');
			$jenis_kelamin = $this->input->post('jenis_kelamin');	
			$tgl_masuk_kerja = $this->input->post('tgl_masuk_kerja');
			$no_rekening = $this->input->post('no_rekening');
			$bank = $this->input->post('bank');
			$periodeGaji =  $this->input->post('periodeGaji');
			$atas_nama = $this->input->post('atas_nama');
			$this->form_validation->set_rules('no_id', 'No ID', 'trim|required|is_unique[borongan_pekerja.idfp]');
			$this->form_validation->set_rules('nama', 'Nama', 'trim|required');
			$this->form_validation->set_rules('alamat', 'Alamat', 'trim|required');
			$this->form_validation->set_rules('tempat_lahir', 'Tempat Lahir', 'trim|required');
			$this->form_validation->set_rules('telepon', 'Telepon', 'trim|required');
			$this->form_validation->set_rules('jenis_kelamin', 'Jenis Kelamin', 'trim|required');


			if ($this->form_validation->run() == TRUE) {
				$data = array(
								"idfp"			=>	strtoupper($no_id),
								"nama"			=>	ucfirst($nama),
								"alamat"		=>	$alamat,
								"tempat_lahir"	=>	$tempat_lahir,
								"tgl_lahir"		=>	$tgl_lahir,
								"telepon"		=>	$telepon,
								"kelamin"		=>	$jenis_kelamin,
								"tgl_masuk"		=>	$tgl_masuk_kerja,
								"rekening"		=>	$no_rekening,
								"id_bank"		=>	$bank,
								"periode_gaji"  =>  $periodeGaji,
								"atas_nama"		=>	$atas_nama
							);
				/*for foto karyawan*/
				if (!empty($_FILES["photo_pekerja"]["name"])) {
					// config upload
					self::_do_upload_orang();

					if (!$this->upload->do_upload("photo_pekerja")) {
						$this->response->message = "error_foto";
						$error_photo_pekerja = $this->upload->display_errors('<small style="color:red;">', '</small>');
						$this->response->error_photo_pekerja = $error_photo_pekerja;
					} else {
						$photo_pekerja = $this->upload->data();
						$data["foto"]	= $photo_pekerja["file_name"];
					}

				} else {
					$data["foto"]	= "";
				}

				  if ($batasKaryawan) {
					    $getCountKaryawan1 = $this->karyawanModel->getCountKaryawan();
						$getCountKaryawan2 = $this->pekerjaModel->getCountKaryawan();
						$getCountKaryawan = $getCountKaryawan1 + $getCountKaryawan2;
						
						if ($getCountKaryawan >= $batasKaryawan) {
							$this->response->message = alertDanger("Opps, Maaf Anda tidak boleh menambah karyawan lagi.<br>Batas karyawan yang boleh di tambah hanya <b>".$batasKaryawan."</b> karyawan saja.");
						} else {
							$insert = $this->pekerjaModel->insert($data);
							if ($insert) {
								$this->response->status = true;
								$this->response->message = alertSuccess("Berhasil tambah data Pekerja.");
							} else {
								$this->response->message = alertDanger("Gagal tambah data Pekerja.");
						  }
						}

				  } else {
				$insert = $this->pekerjaModel->insert($data);
				if ($insert) {
					$this->response->status = true;
					$this->response->message = alertSuccess("Berhasil tambah data Pekerja.");
				} else {
					$this->response->message = alertDanger("Gagal tambah data Pekerja.");
				}
			  }

			} else {
				// $this->response->message = validation_errors('<span style="color:red;">', '</span><br>');
				$this->response->error = array(
									"no_id"	=> form_error("no_id",'<span style="color:red;">','</span>'),
									"nama"	=> form_error("nama",'<span style="color:red;">','</span>'),
									"alamat" => form_error("alamat",'<span style="color:red;">','</span>'),
									"jenis_kelamin" => form_error("Jenis Kelamin",'<span style="color:red;">','</span>'),
									
		
								);
			}
		}

		parent::json();
	}

	public function getId($id)
	{
		parent::checkLoginUser(); // user login autentic checking

		if ($this->isPost()) {
			$getById = $this->pekerjaModel->getById($id);
			if ($getById) {
				if ($getById->foto != "") {
					$getById->foto = base_url("/")."uploads/pekerjaborongan/pekerja/".$getById->foto;
				} else {
					$getById->foto = base_url("/")."assets/images/default/no_user.png";
				}

				$this->response->status = true;
				$this->response->message = "Data pekerja get by id";
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
			$no_id = $this->input->post("no_id");
			$nama = $this->input->post('nama');
			$alamat = $this->input->post('alamat');
			$tempat_lahir = $this->input->post('tempat_lahir');
			$tgl_lahir = $this->input->post('tgl_lahir');
			$telepon = $this->input->post('telepon');
			$jenis_kelamin = $this->input->post('jenis_kelamin');
			$tgl_masuk_kerja = $this->input->post('tgl_masuk_kerja');
			$no_rekening = $this->input->post('no_rekening');
			$bank = $this->input->post('bank');
			$periodeGaji =  $this->input->post('periodeGaji');
			$atas_nama = $this->input->post('atas_nama');
			
			$this->form_validation->set_rules('nama', 'Nama', 'trim|required');
			$this->form_validation->set_rules('alamat', 'Alamat', 'trim|required');
			$this->form_validation->set_rules('tempat_lahir', 'TempatLahir', 'trim|required');
			$this->form_validation->set_rules('telepon', 'Telepon', 'trim|required');
			$this->form_validation->set_rules('jenis_kelamin', 'JenisKelamin', 'trim|required');
			$getById = $this->pekerjaModel->getById($id);

			if ($this->form_validation->run() == TRUE) {
				$data = array(
								// "idfp"			=>	$idfp,
								"nama"			=>	ucfirst($nama),
								"alamat"		=>	$alamat,
								"tempat_lahir"	=>	$tempat_lahir,
								"tgl_lahir"		=>	$tgl_lahir,
								"telepon"		=>	$telepon,			
								"kelamin"		=>	$jenis_kelamin,
								"tgl_masuk"		=>	$tgl_masuk_kerja,
								"rekening"		=>	$no_rekening,
								"id_bank"		=>	$bank,
								"periode_gaji"  =>  $periodeGaji,
								"atas_nama"		=>	$atas_nama
							);
				/*for foto karyawan*/
					if (!empty($_FILES["photo_pekerja"]["name"])) {
						// config upload
						self::_do_upload_orang();

						if (!$this->upload->do_upload("photo_pekerja")) {
							$this->response->message = "error_foto";
							$error_photo_pekerja = $this->upload->display_errors('<small style="color:red;">', '</small>');
							$this->response->error_photo_pekerja = $error_photo_pekerja;
						} else {
							$photo_pekerja = $this->upload->data();
							$data["foto"]	= $photo_pekerja["file_name"];
							/*if (file_exists("uploads/master/karyawan/orang/".$getById->foto) && $getById->foto) {
								unlink("uploads/master/karyawan/orang/".$getById->foto);
							}*/
						}

					} else {
						$data["foto"]	= $getById->foto;
						if ($this->input->post("is_delete_photo") == 1) {
							$data["foto"]	= "";
							/*if (file_exists("uploads/master/karyawan/orang/".$getById->foto) && $getById->foto) {
								unlink("uploads/master/karyawan/orang/".$getById->foto);
							}*/
						}
					}
				$update = $this->pekerjaModel->update($id,$data);
				if ($update) {
					$this->response->status = true;
					$this->response->message = alertSuccess("Berhasil update data pekerja.");
				} else {
					$this->response->message = alertDanger("Gagal update data pekerja.");
				}
			} else {
				// $this->response->message = validation_errors('<span style="color:red;">', '</span><br>');
				$this->response->error = array(
									"kode"	=> form_error("kode",'<span style="color:red;">','</span>'),
									"departemen"	=> form_error("departemen",'<span style="color:red;">','</span>'),
									"harga"	=> form_error("harga",'<span style="color:red;">','</span>'),
									"keterangan"	=> form_error("keterangan",'<span style="color:red;">','</span>'),
								);
			}
		}
		parent::json();
	}

	public function delete($id)
	{
		parent::checkLoginUser(); // user login autentic checking
		
		if ($this->isPost()) {
			$getById = $this->pekerjaModel->getById($id);
			if ($getById) {
				$delete = $this->pekerjaModel->delete($id);
				if ($delete) {
					$this->response->status = true;
					$this->response->message = alertSuccess("Data Pekerja Berhasil di hapus.");
				} else {
					$this->response->message = alertDanger("Data sudah tidak ada.");
				}	
			} else {
				$this->response->message = alertDanger("Data sudah tidak ada.");
			}
		}
		parent::json();
	}

	public function departemenData()
	{
		parent::checkLoginUser(); // user login autentic checking

		if ($this->isPost()) {
			$data = $this->pekerjaModel->departemenDataAll();
			if ($data) {
				$this->response->status = true;
				$this->response->message = "data departemen all";
				$this->response->data = $data;
			} else {
				$this->response->message = "data departemen not found.";
			}
		}
		parent::json();
	}

	public function bankData()
	{
		parent::checkLoginUser(); // user login autentic checking

		// if ($this->isPost()) {
			$data = $this->pekerjaModel->bankDataAll();
			if ($data) {
				$this->response->status = true;
				$this->response->message = "data bank all";
				$this->response->data = $data;
			} else {
				$this->response->message = "data bank not found.";
			}
		// }
		parent::json();
	}

}

/* End of file Departemen.php */
/* Location: ./application/controllers/pekerjaborongan/Departemen.php */