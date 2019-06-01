<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Karyawan extends MY_Controller {

	public function __construct()
	{
		parent::__construct();
		$this->load->model('approval/Tmp_karyawan_model',"tmpKaryawanModel");
		$this->load->model('master/Karyawan_model', "karyawanModel");
		parent::checkLoginHRD();
	}

	public function index()
	{
		parent::checkLoginUser();// user login autentic checking

		parent::headerTitle("Approval Owner > Karyawan","Approval Owner","Karyawan");
		$breadcrumbs = array(
							"Approval Owner"	=>	site_url('approvalowner/karyawan'),
							"Karyawan"		=>	"",
						);
		parent::breadcrumbs($breadcrumbs);

		parent::viewApprovalOwner();
	}

	public function ajax_list()
	{
		parent::checkLoginUser(); // user login autentic checking

		if ($this->isPost()) {
			$data = array();
			$select = 'id, status_approve, info_approve, tanggal_approve, foto, nama, master_departemen.departemen, master_jabatan.jabatan, alamat, kelamin, tmp_master_karyawan.created_at';

			$orderBy = array(null,null,"status_approve","nama","master_departemen.departemen","master_jabatan.jabatan","alamat","kelamin","tmp_master_karyawan.created_at");
			$search = array("status_approve","info_approve","tanggal_approve","nama","master_departemen.departemen","master_jabatan.jabatan","alamat","kelamin");

			$result = $this->tmpKaryawanModel->findDataTable($select,$orderBy,$search);
			foreach ($result as $item) {

				$srcPhoto = base_url().'assets/images/default/no_user.png';
				if ($item->foto != "") {
					$srcPhoto = base_url()."uploads/master/karyawan/orang/".$item->foto;
				}
				$dataPhoto = '<a href="'.$srcPhoto.'" data-toggle="lightbox" data-title="Photo Karyawan" data-footer="">
                    <img src="'.$srcPhoto.'" class="img-circle" style="height:60px; width:60px;" alt="photo "'.$item->nama.'>
                </a>';

				$item->tanggal_approve = date_ind("d M Y", $item->tanggal_approve);
				$item->created_at = date_ind("d M Y", $item->created_at);
				$item->kelamin = ucfirst($item->kelamin);
				$item->info_approve = ucfirst($item->info_approve);
				if ($item->info_approve == "Tambah") {
					$item->info_approve = "<span class='text-info'>".$item->info_approve."</span>";
				} else if ($item->info_approve == "Edit") {
					$item->info_approve = "<span style='color:orange;'>".$item->info_approve."</span>";
				} else if ($item->info_approve == "Hapus") {
					$item->info_approve = "<span class='text-danger'><strike>".$item->info_approve."</strike></span>";
				}

				$item->foto = $dataPhoto;

				if ($item->status_approve == "Proses") {
					$btnAction = '<center><button class="btn btn-outline-primary btn-mini" style="margin-bottom: 5px;" onclick="btnDetail('.$item->id.')" title="Detail"><i class="fa fa-search-plus"></i></button></center>';
					$btnAction .= '<button class="btn btn-outline-success btn-mini" onclick="btnApproval('.$item->id.')" title="Terima"><i class="fa fa-check-square"></i></button>';

					$btnAction .= '&nbsp;&nbsp;<button class="btn btn-outline-danger btn-mini" onclick="btnReject('.$item->id.')" title="Tolak"><i class="fa fa-ban"></i></button>';
				} else {
					$btnAction = '<center><button class="btn btn-outline-primary btn-mini" style="margin-bottom: 5px;" onclick="btnDetail('.$item->id.')" title="Detail"><i class="fa fa-search-plus"></i></button></center>';
				}

				$item->button_action = $btnAction;

				if ($item->status_approve == "Proses") {
					$item->status_approve = '<label class="label label-inverse-info">'.$item->status_approve.'</label>';
				} else if ($item->status_approve == "Diterima") {
					$item->status_approve = '<label class="label label-inverse-success">'.$item->status_approve.'</label>';
				} else if ($item->status_approve == "Tolak") {
					$item->status_approve = '<label class="label label-inverse-danger">'.$item->status_approve.'</label>';
				}
				$item->status_approve = $item->status_approve." ".$item->info_approve;

				$data[] = $item;
			}
			return $this->tmpKaryawanModel->findDataTableOutput($data,$select,$search);
		}
	}

	public function getId($id)
	{
		parent::checkLoginUser(); // user login autentic checking

		if ($this->isPost()) {
			$getById = $this->tmpKaryawanModel->getById($id);
			if ($getById) {
				$getById->tanggal_approve_indo = date_ind("d M Y", $getById->tanggal_approve);

				if ($getById->foto != "") {
					$getById->foto = base_url("/")."uploads/master/karyawan/orang/".$getById->foto;
				} else {
					$getById->foto = base_url("/")."assets/images/default/no_user.png";
				}

				if ($getById->shift == "ya") {
					$getById->shift_text = "Ya <u>( Pagi, Sore, Malam )</u>";
				} else {
					$getById->shift_text = "Tidak <u>( Regular )</u>";
				}

				$this->response->status = true;
				$this->response->message = "Data temporer karyawan get by id";
				$this->response->data = $getById;
			} else {
				$this->response->message = alertDanger("Data tidak ada.");
			}
		}
		parent::json();
	}

	public function getIdDetail($id)
	{
		parent::checkLoginUser(); // user login autentic checking

		if ($this->isPost()) {

			$dataTemporer = $this->tmpKaryawanModel->getIdDetail($id);
			if ($dataTemporer) {
				$status_approve = $this->tmpKaryawanModel->checkStatusApprove($id);
				if ($dataTemporer["kode_karyawan"] == "NO-KODE") {

					$dataTemporer["tgl_lahir_indo"] = date_ind("d M Y", $dataTemporer["tgl_lahir"]);
					$dataTemporer["tgl_masuk_indo"] = date_ind("d M Y", $dataTemporer["tgl_masuk"]);
					$dataTemporer["start_date_indo"] = date_ind("d M Y", $dataTemporer["start_date"]);
					$dataTemporer["expired_date_indo"] = date_ind("d M Y", $dataTemporer["expired_date"]);
					$dataTemporer["gaji_rp"]	=	"Rp.".number_format($dataTemporer["gaji"],0,",",".");

					if ($dataTemporer["foto"] != "") {
						$dataTemporer["foto"] = base_url("/")."uploads/master/karyawan/orang/".$dataTemporer["foto"];
					} else {
						$dataTemporer["foto"] = base_url("/")."assets/images/default/no_user.png";
					}

					$dataTemporer["foto_master"]		=  $dataTemporer["foto"];
					$dataTemporer["foto_temporer"]	=  "";
					$dataTemporer["foto_status"]		=  0;

					if ($dataTemporer["file_kontrak"] != "") {
						$dataTemporer["file_kontrak"] = base_url("/")."uploads/master/karyawan/kontrak/".$dataTemporer["file_kontrak"];
					} else {
						$dataTemporer["file_kontrak"] = base_url("/")."assets/images/default/no_file_.png";
					}

					$dataTemporer["file_kontrak_master"]		=  $dataTemporer["file_kontrak"];
					$dataTemporer["file_kontrak_temporer"]	=  "";
					$dataTemporer["file_status"]	=	0;
					$dataTemporer["status_approve"]	= $status_approve->status_approve;

					if($dataTemporer["otoritas"] == 1){
						$dataTemporer["otoritas"] = "Karyawan";
					} else if($dataTemporer["otoritas"] == 2){
						$dataTemporer["otoritas"] = "Kabag";
					} else if($dataTemporer["otoritas"] == 3){
						$dataTemporer["otoritas"] = "HRD";
					} else if($dataTemporer["otoritas"] == 4){
						$dataTemporer["otoritas"] = "Security";
					} else if($dataTemporer["otoritas"] == 5){
						$dataTemporer["otoritas"] = "Asisten Kabag";
					}

					if ($dataTemporer["shift"] == "ya") {
						$dataTemporer["shift_text"] = "Ya <u>( Pagi, Sore, Malam )</u>";
					} else {
						$dataTemporer["shift_text"] = "Tidak <u>( Regular )</u>";
					}

					$this->response->status = true;
					$this->response->data = $dataTemporer;
					$this->response->message = "Data temporer karyawan get by id";
				} else {
					$dataMaster = $this->karyawanModel->getIdDetail(array("master_karyawan.kode_karyawan" => $dataTemporer['kode_karyawan']));
					if ($dataMaster) {
						$dataTemporer["tgl_lahir_indo"] = date_ind("d M Y", $dataTemporer["tgl_lahir"]);
						$dataTemporer["tgl_masuk_indo"] = date_ind("d M Y", $dataTemporer["tgl_masuk"]);
						$dataTemporer["start_date_indo"] = date_ind("d M Y", $dataTemporer["start_date"]);
						$dataTemporer["expired_date_indo"] = date_ind("d M Y", $dataTemporer["expired_date"]);
						$dataTemporer["gaji_rp"]	=	"Rp.".number_format($dataTemporer["gaji"],0,",",".");

						if($dataTemporer["otoritas"] == 1){
							$dataTemporer["otoritas"] = "Karyawan";
						} else if($dataTemporer["otoritas"] == 2){
							$dataTemporer["otoritas"] = "Kabag";
						} else if($dataTemporer["otoritas"] == 3){
							$dataTemporer["otoritas"] = "HRD";
						} else if($dataTemporer["otoritas"] == 4){
							$dataTemporer["otoritas"] = "Security";
						} else if($dataTemporer["otoritas"] == 5){
							$dataTemporer["otoritas"] = "Asisten Kabag";
						}

						if ($dataTemporer["shift"] == "ya") {
							$dataTemporer["shift_text"] = "Ya <u>( Pagi, Sore, Malam )</u>";
						} else {
							$dataTemporer["shift_text"] = "Tidak <u>( Regular )</u>";
						}

						if($dataMaster["otoritas"] == 1){
							$dataMaster["otoritas"] = "Karyawan";
						} else if($dataMaster["otoritas"] == 2){
							$dataMaster["otoritas"] = "Kabag";
						} else if($dataMaster["otoritas"] == 3){
							$dataMaster["otoritas"] = "HRD";
						} else if($dataMaster["otoritas"] == 4){
							$dataMaster["otoritas"] = "Security";
						} else if($dataMaster["otoritas"] == 5){
							$dataMaster["otoritas"] = "Asisten Kabag";
						}

						if ($dataMaster["shift"] == "ya") {
							$dataMaster["shift_text"] = "Ya <u>( Pagi, Sore, Malam )</u>";
						} else {
							$dataMaster["shift_text"] = "Tidak <u>( Regular )</u>";
						}

						$dataMaster["tgl_lahir_indo"] = date_ind("d M Y", $dataMaster["tgl_lahir"]);
						$dataMaster["tgl_masuk_indo"] = date_ind("d M Y", $dataMaster["tgl_masuk"]);
						$dataMaster["start_date_indo"] = date_ind("d M Y", $dataMaster["start_date"]);
						$dataMaster["expired_date_indo"] = date_ind("d M Y", $dataMaster["expired_date"]);
						$dataMaster["gaji_rp"]	=	"Rp.".number_format($dataMaster["gaji"],0,",",".");

						$dataDetail = array();
						foreach ($dataTemporer as $key => $val) {
							// if (in_array($val, $dataMaster)) {
							if($dataTemporer[$key] == $dataMaster[$key]){
								$dataDetail[$key] = $val;
							} else {
								$dataDetail[$key]	= "<span style='color:blue;'>".$dataMaster[$key]." <i class='fa fa fa-arrow-circle-right'></i> ".$val."</span>";
							}
						}

						/*foto karyawan*/
						if ($dataMaster["foto"] != "") {
							$dataMaster["foto"] = base_url("/")."uploads/master/karyawan/orang/".$dataMaster["foto"];
						} else {
							$dataMaster["foto"] = base_url("/")."assets/images/default/no_user.png";
						}

						if ($dataTemporer["foto"] != "") {
							$dataTemporer["foto"] = base_url("/")."uploads/master/karyawan/orang/".$dataTemporer["foto"];
						} else {
							$dataTemporer["foto"] = base_url("/")."assets/images/default/no_user.png";
						}

						if ($dataTemporer["foto"] != $dataMaster["foto"]) {
							$dataDetail["foto_master"]		=  $dataMaster["foto"];
							$dataDetail["foto_temporer"]	=  $dataTemporer["foto"];
							$dataDetail["foto_status"]		=  1;
						} else {
							$dataDetail["foto_master"]		=  $dataMaster["foto"];
							$dataDetail["foto_temporer"]	=  "";
							$dataDetail["foto_status"]		=  0;
						}
						/*end foto karyawan*/

						/*file kontrak*/
						if ($dataMaster["file_kontrak"] != "") {
							$dataMaster["file_kontrak"] = base_url("/")."uploads/master/karyawan/kontrak/".$dataMaster["file_kontrak"];
						} else {
							$dataMaster["file_kontrak"] = base_url("/")."assets/images/default/no_file_.png";
						}

						if ($dataTemporer["file_kontrak"] != "") {
							$dataTemporer["file_kontrak"] = base_url("/")."uploads/master/karyawan/kontrak/".$dataTemporer["file_kontrak"];
						} else {
							$dataTemporer["file_kontrak"] = base_url("/")."assets/images/default/no_file_.png";
						}

						if ($dataTemporer["file_kontrak"] != $dataMaster["file_kontrak"]) {
							$dataDetail["file_kontrak_master"]		=  $dataMaster["file_kontrak"];
							$dataDetail["file_kontrak_temporer"]	=  $dataTemporer["file_kontrak"];
							$dataDetail["file_status"]	=	1; // check data perubahan file kontrak
						} else {
							$dataDetail["file_kontrak_master"]		=  $dataMaster["file_kontrak"];
							$dataDetail["file_kontrak_temporer"]	=  "";
							$dataDetail["file_status"]	=	0;
						}
						/*end file kontrak */
						$dataDetail["status_approve"]	= $status_approve->status_approve;
						$this->response->data = $dataDetail;
						$this->response->status = true;
						$this->response->message = "Data temporer karyawan get by id";
					} else {
						$this->response->message = alertDanger("Data karyawan tidak ada atau mungkin sudah di hapus.!");
					}
				}
			} else {
				$this->response->message = alertDanger("Data tidak ada.");
			}

		}
		parent::json();
	}

	public function approve($id)
	{
		parent::checkLoginUser(); // user login autentic checking

		if ($this->isPost()) {

			$tmpData = $this->tmpKaryawanModel->getById($id); // check data temporer master karyawan
			if ($tmpData) {
				$getById = $this->karyawanModel->getByWhere(array("kode_karyawan"=>$tmpData->kode_karyawan));
				if($tmpData->status_approve == "Proses") {

				    $data  = array(
								"idfp"			=>	$tmpData->idfp,
								"kode_karyawan"	=>	$tmpData->kode_karyawan,
								"nama"			=>	$tmpData->nama,
								"alamat"		=>	$tmpData->alamat,
								"tempat_lahir"	=>	$tmpData->tempat_lahir,
								"tgl_lahir"		=>	$tmpData->tgl_lahir,
								"telepon"		=>	$tmpData->telepon,
								"email"			=>	$tmpData->email,
								"no_wali"		=>	$tmpData->no_wali,
								"kelamin"		=>	$tmpData->kelamin,
								"status_nikah"	=>	$tmpData->status_nikah,
								"pendidikan"	=>	$tmpData->pendidikan,
								"wn"			=>	$tmpData->wn,
								"agama"			=>	$tmpData->agama,
								"id_cabang"		=>	$tmpData->id_cabang,
								"id_departemen"	=>	$tmpData->id_departemen,
								"id_jabatan"	=>	$tmpData->id_jabatan,
								"jab_index"		=>	$tmpData->jab_index,
								"id_golongan"	=>	$tmpData->id_golongan,
								"shift"			=>	$tmpData->shift,
								"id_grup"		=>	$tmpData->id_grup,
								"tgl_masuk"		=>	$tmpData->tgl_masuk,
								"kontrak"		=>	$tmpData->kontrak,
								"ibu_kandung"	=>	$tmpData->ibu_kandung,
								"suami_istri"	=> 	$tmpData->suami_istri,
								"tanggungan"	=>	$tmpData->tanggungan,
								"npwp"			=>	$tmpData->npwp,
								"gaji"			=>	$tmpData->gaji,
								"periode_gaji"	=>	$tmpData->periode_gaji,
								"rekening"		=>	$tmpData->rekening,
								"id_bank"		=>	$tmpData->id_bank,
								"atas_nama"		=>	$tmpData->atas_nama,
								"otoritas"		=>	$tmpData->otoritas,
								"status_kerja"	=>	$tmpData->status_kerja,
								"foto"			=>	$tmpData->foto,
								"wajah" => $tmpData->wajah,
								"start_date"	=>	$tmpData->start_date,
								"expired_date"	=>	$tmpData->expired_date,
								"file_kontrak"	=>	$tmpData->file_kontrak,
								"qrcode_file"	=>	$tmpData->qrcode_file,
						);

					if($tmpData->kode_karyawan == "NO-KODE") {

						if ($tmpData->info_approve == "tambah") {
							$kode_akhir = $this->karyawanModel->getAll(false,array("kode_karyawan"),array("LPAD(lower(kode_karyawan), 10,0) DESC"));
							if ($kode_akhir == null) {
								$kodeKaryawan = 'EMP-1';
							} else {
						        $kodeUrut = (int) substr($kode_akhir[0]->kode_karyawan, strpos($kode_akhir[0]->kode_karyawan, '-') + 1);
						        $kodeKaryawan = 'EMP-'.($kodeUrut + 1);
						    }
						    $data["kode_karyawan"]	= $kodeKaryawan;
							$image_name = md5(uniqid().$kodeKaryawan).'.png'; //buat name dari qr code sesuai dengan kodeKaryawan
							self::qrCodeGenerateUpload($kodeKaryawan,$image_name);
							$data["qrcode_file"] = $image_name;

							$dataNotif = array(
											"keterangan"=> 	" Owner <u style='color:green;'>Approval</u> data karyawan baru dengan nama=<u>".$tmpData->nama."</u> dan no_induk=<u>".$tmpData->idfp."</u>",
											"user_id"	=>	$this->user_id,
											"level"		=>	"hrd",
											"url_direct"=>	"master/karyawan",
										);
							$insert = $this->tmpKaryawanModel->insertTransactionApprove($data,$tmpData->id,$dataNotif);
							if ($insert) {
								/*notif firebase*/
								parent::insertNotif($dataNotif);
								$keteranganBody = $this->username." Owner Approval data karyawan baru dengan nama=".$tmpData->nama." dan no_induk=".$tmpData->idfp;
								// parent::sendNotifTopic("hrd","payroll",$keteranganBody);
								parent::sendNotifTopic("hrd","Approval Karyawan","Owner menerima data tambah karyawan dengan nama = ".$tmpData->nama,"031");

								$this->response->status = true;
								$this->response->message = alertSuccess("Berhasil Approval Tambah Data karyawan.");
							} else {
								$this->response->message = alertDanger("Gagal, Approval Tambah data karyawan.");
							}
						}
					} else {
						$dataMasterKaryawan = $this->karyawanModel->getByKode($tmpData->kode_karyawan);
						if ($tmpData->info_approve == "edit") {

							if ($getById->qrcode_file == "") {
								$image_name = md5(uniqid().$tmpData->kode_karyawan).'.png'; //buat name dari qr code sesuai dengan kodeKaryawan
								self::qrCodeGenerateUpload($tmpData->kode_karyawan,$image_name);
								$data["qrcode_file"] = $image_name;
							} else if ($getById->qrcode_file != "") {
								if (!file_exists("uploads/master/karyawan/qrcode/".$getById->qrcode_file)) {
									self::qrCodeGenerateUpload($tmpData->kode_karyawan,$getById->qrcode_file);
									$data["qrcode_file"] = $image_name;
								}
							}

							$dataNotif = array(
											"keterangan"=> 	" Owner <u style='color:green;'>Approval</u> Edit/Update data karyawan dengan nama=<u>".$tmpData->nama."</u> dan no_induk=<u>".$tmpData->idfp."</u>",
											"user_id"	=>	$this->user_id,
											"level"		=>	"hrd",
											"url_direct"=>	"master/karyawan",
										);
							$update = $this->tmpKaryawanModel->updateTransactionApprove($tmpData->kode_karyawan,$data,$tmpData->id,$dataNotif);
							if ($update) {
								/*notif firebase*/
								parent::insertNotif($dataNotif);
								$keteranganBody = $this->username." Owner Approval Edit/Update data karyawan dengan nama=".$getById->nama." dan no_induk=".$getById->idfp;
								// parent::sendNotifTopic("hrd","payroll",$keteranganBody);
								parent::sendNotifTopic("hrd","Approval Karyawan","Owner menerima edit data karyawan dengan nama = ".$tmpData->nama,"029");


								if($dataMasterKaryawan->foto != $tmpData->foto) {
									if (file_exists("uploads/master/karyawan/orang/".$dataMasterKaryawan->foto) && $dataMasterKaryawan->foto) {
										unlink("uploads/master/karyawan/orang/".$dataMasterKaryawan->foto);
									}
								}

								if($dataMasterKaryawan->file_kontrak != $tmpData->file_kontrak) {
									if (file_exists("uploads/master/karyawan/kontrak/".$dataMasterKaryawan->file_kontrak) && $dataMasterKaryawan->file_kontrak) {
										unlink("uploads/master/karyawan/kontrak/".$dataMasterKaryawan->file_kontrak);
									}
								}

								$this->response->status = true;
								$this->response->message = alertSuccess("Berhasil Approval Update Data karyawan.");
							} else {
								$this->response->message = alertDanger("Gagal, Approval Update data karyawan.");
							}
						} else if ($tmpData->info_approve == "hapus") {
							$dataNotif = array(
											"keterangan"=> 	" Owner <u style='color:green;'>Approval</u> hapus data karyawan dengan nama=<u>".$tmpData->nama."</u> dan no_induk=<u>".$tmpData->idfp."</u>",
											"user_id"	=>	$this->user_id,
											"level"		=>	"hrd",
											"url_direct"=>	"master/karyawan",
										);
							$delete = $this->tmpKaryawanModel->deleteTransactionApprove($tmpData->kode_karyawan,$tmpData->id,$dataNotif);
							if ($delete) {
								/*notif firebase*/
								parent::insertNotif($dataNotif);
								$keteranganBody = $this->username." Owner Approval hapus data karyawan dengan nama=".$getById->nama." dan no_induk=".$getById->idfp;
								// parent::sendNotifTopic("hrd","payroll",$keteranganBody);
								parent::sendNotifTopic("hrd","Approval Karyawan","Owner menerima hapus data karyawan dengan nama = ".$tmpData->nama,"030");

								$this->response->status = true;
								$this->response->message = alertSuccess("Berhasil Approval Hapus Data karyawan.");
							} else {
								$this->response->message = alertDanger("Gagal, Approval Hapus data karyawan.");
							}
						}
					}
				} else {
					$this->response->message = alertDanger("Hanya status <label class='label label-info'>Proses</label> yang boleh di approve.!");
				}
			} else {
				$this->response->message = alertDanger("Data Tidak Ada.!");
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
		$this->ciqrcode->generate($params); // fungsi untuk generate QR CODE
	}

	public function reject($id)
	{
		parent::checkLoginUser(); // user login autentic checking

		if ($this->isPost()) {

			$tmpData = $this->tmpKaryawanModel->getById($id); // check data temporer master karyawan
			if ($tmpData) {
				if ($tmpData->kode_karyawan == "NO-KODE") {
					$getById = $this->tmpKaryawanModel->getById($id);
				} else {
					$getById = $this->karyawanModel->getByWhere(array("kode_karyawan"=>$tmpData->kode_karyawan));
				}
				// var_dump($getById);
				// exit();

				if($tmpData->status_approve == "Proses") {
					$data = array(
								"status_approve"	=>	"Tolak",
								"updated_at"		=>	date("Y-m-d H:i:s"),
							);

					$pesanNotif = "";
					if ($tmpData->info_approve == "tambah") {
						$pesanNotif = "data karyawan baru";
					} else if ($tmpData->info_approve == "edit") {
						$pesanNotif = "Edit/update data karyawan";
					} else if ($tmpData->info_approve == "hapus") {
						$pesanNotif = "Hapus data karyawan";
					}

					$dataNotif = array(
										"keterangan"=> 	" Owner <u style='color:red;'>Reject</u> ".$pesanNotif." dengan nama=<u>".$tmpData->nama."</u> dan no_induk=<u>".$tmpData->idfp."</u>",
										"user_id"	=>	$this->user_id,
										"level"		=>	"hrd",
										"url_direct"=>	"master/karyawan",
									);
					$insert = $this->tmpKaryawanModel->update($id,$data,$dataNotif);
					if ($insert) {
						/*notif firebase*/
						parent::insertNotif($dataNotif);
						$keteranganBody = $this->username." Owner Reject data karyawan dengan nama=".$getById->nama." dan no_induk=".$getById->idfp;
						// parent::sendNotifTopic("hrd","Approval Karyawan","Owner Menolak "+$pesanNotif,"030");

						$this->response->status = true;
						$this->response->message = alertSuccess("Berhasil Reject Data karyawan.");
					} else {
						$this->response->message = alertDanger("Gagal, Reject Data karyawan.");
					}
				} else {
					$this->response->message = alertDanger("Hanya status <label class='label label-info'>Proses</label> yang boleh di Reject.!");
				}
			} else {
				$this->response->message = alertDanger("Data Tidak Ada.!");
			}

		}
		parent::json();
	}
}

/* End of file Karyawan.php */
/* Location: ./application/controllers/approvalowner/Karyawan.php */
