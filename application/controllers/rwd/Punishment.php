<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Punishment extends MY_Controller {

	public function __construct()
	{
		parent::__construct();
		$this->load->model('rwd/Punishment_model',"punishModel");
		$this->load->model('master/Karyawan_model', "karyawanModel");
		$this->load->model('Setting_model',"settingModel");
		parent::checkLoginOwner();
	}

	public function index()
	{
		parent::checkLoginUser(); // user login autentic checking

		parent::headerTitle("Rewards & Punishment > Punishment","Rewards & Punishment","Punishment");
		$breadcrumbs = array(
							"Rewards & Punishment"	=>	site_url('rwd/punishment'),
							"Punishment"		=>	"",
						);
		parent::breadcrumbs($breadcrumbs);

		parent::viewRwd();
	}

	public function ajax_list()
	{
		parent::checkLoginUser(); // user login autentic checking

		if ($this->isPost()) {
			$data = array();

			$orderBy = array(null,null,null,"tanggal","nama","departemen","status","jabatan","nilai","rwd_punishment.keterangan");
			$search = array("tanggal","nama","departemen","jabatan","nilai","status","rwd_punishment.keterangan");

			$result = $this->punishModel->findDataTable($orderBy,$search);
			foreach ($result as $item) {

				$item->tanggal = date_ind("d M Y", $item->tanggal);

				$item->nilai = "Rp.".number_format($item->nilai,0,",",".");
				$enabled = $item->status == "Diterima" ? "" : "disabled";
				$btnTools = '<button class="btn btn-default btn-mini" '.$enabled.' onclick="btnSp('.$item->id.')"><i class="fa fa-print"></i>Print</button>';

				if ($item->status == "Proses") {
					$item->status = '<label class="label label-info">'.$item->status.'</label>';
				} else if ($item->status == "Diterima") {
					$item->status = '<label class="label label-success">'.$item->status.'</label>';
				} else if ($item->status == "Ditolak") {
					$item->status = '<label class="label label-danger">'.$item->status.'</label>';
				}

				$disabled = $item->status == "Tolak" ? "disabled" : "";

				$btnAction = '<button class="btn btn-warning btn-mini" '.$disabled.' onclick="btnEdit('.$item->id.')"><i class="fa fa-pencil-square-o"></i>Edit</button>';
				$btnAction .= '&nbsp;&nbsp;&nbsp;<button class="btn btn-danger btn-mini" onclick="btnDelete('.$item->id.')"><i class="fa fa-trash-o"></i>Hapus</button>';


				$item->button_action = $btnAction;
				$item->button_tools = $btnTools;
				$data[] = $item;
			}
			return $this->punishModel->findDataTableOutput($data,$search);
		}
	}

	public function allKaryawanAjax()
	{
		parent::checkLoginUser(); // user login autentic checking

		if ($this->isPost()) {
			if (!isset($_POST["searchTerm"])) {
				$dataKaryawan = $this->punishModel->getAllKaryawanAjax();
			} else {
				$searchTerm = $this->input->post("searchTerm");
				$dataKaryawan = $this->punishModel->getAllKaryawanAjaxSearch($searchTerm);
			}

			$data = array();
			foreach ($dataKaryawan as $val) {
				$data[] = array("id"=>$val->id, "text"=>$val->nama);
			}
			parent::json($data);
		}

	}

	public function idKaryawan($id)
	{
		parent::checkLoginUser(); // user login autentic checking

		if ($this->isPost()) {
			$data = $this->punishModel->getIdKaryawan($id);
			if ($data) {
				if ($data->foto != "") {
					$data->foto = base_url("/")."uploads/master/karyawan/orang/".$data->foto;
				} else {
					$data->foto = base_url("/")."assets/images/default/no_user.png";
				}
				$this->response->status = true;
				$this->response->message = "data karyawan by id";
				$this->response->data = $data;
			}
		}
		parent::json();
	}

	public function spOption($id)
	{
		parent::checkLoginUser(); // user login autentic checking

		if ($this->isPost()) {

		}
		parent::json();
	}

	public function getIdForPrint($id)
	{
		parent::checkLoginUser(); // user login autentic checking

		if ($this->isPost()) {
			$getById = $this->punishModel->getById($id);
			$setting = $this->settingModel->getById(1);

			if ($getById) {
				if ($setting->logo != "") {
					$setting->logo = base_url("/")."uploads/setting/".$setting->logo;
				} else {
					$setting->logo = base_url("/")."assets/images/default/no_file_.png";
				}
				$getById->setting = $setting;
				$getById->tanggal = date_ind("d M Y",$getById->tanggal);
				$this->response->status = true;
				$this->response->message = "data print by id";
				$this->response->data = $getById;
				$this->response->setting = $setting;
			}
			else {
				$this->response->message = alertDanger("Data tidak ada.");
			}
		}
		parent::json();
	}

	public function add()
	{
		parent::checkLoginUser(); // user login autentic checking

		if ($this->isPost()) {
			$tanggal = $this->input->post("tanggal");
			$nama_karyawan = $this->input->post("nama_karyawan");
			// $judul = $this->input->post("judul");
			$nilai = $this->input->post("nilai");
			$keterangan = $this->input->post("keterangan");
			$sp = $this->input->post('surat_peringatan');

			$this->form_validation->set_rules('tanggal', 'Tanggal', 'trim|required');
			$this->form_validation->set_rules('nama_karyawan', 'Nama Karyawan', 'trim|required');
			// $this->form_validation->set_rules('judul', 'Judul', 'trim|required');
			$this->form_validation->set_rules('nilai', 'Nilai', 'trim|required');
			$this->form_validation->set_rules('keterangan', 'keterangan', 'trim');

			if ($this->form_validation->run() == TRUE) {
				$data = array(
								"tanggal"		=>	$tanggal,
								"id_karyawan"	=>	$nama_karyawan,
								// "judul"			=>	$judul,
								"nilai"			=>	$nilai,
								"surat_peringatan" => $sp,
								"status"		=>	"Proses",
								"keterangan"	=>	$keterangan
							);
				$dataNotif = array(
											"keterangan"=> 	" Tambah Data Punishment Karyawan.",
											"user_id"	=>	$this->user_id,
											"level"		=>	"owner",
											"url_direct"=>	"approvalowner/punishment",
										);
				$insert = $this->punishModel->insert($data,$dataNotif);
				if ($insert) {
					parent::insertNotif($dataNotif);
					parent::sendNotifTopic("owner","Pengajuan Punishment","HRD menambah data punishment baru","012");
					$this->response->status = true;
					$this->response->message = alertSuccess("Berhasil tambah data punishment.");
				} else {
					$this->response->message = alertDanger("Gagal tambah data punishment.");
				}

			} else {
				$this->response->message = validation_errors('<span style="color:red;">', '</span><br>');
				$this->response->error = array(
									"tanggal"	=> form_error("tanggal",'<span style="color:red;">','</span>'),
									"nama_karyawan"	=> form_error("nama_karyawan",'<span style="color:red;">','</span>'),
									// "judul"	=> form_error("judul",'<span style="color:red;">','</span>'),
									"nilai"	=> form_error("nilai",'<span style="color:red;">','</span>'),
									// "keterangan"	=> form_error("keterangan",'<span style="color:red;">','</span>'),
								);
			}
		}
		parent::json();
	}

	public function getId($id)
	{
		parent::checkLoginUser(); // user login autentic checking

		if ($this->isPost()) {
			$getById = $this->punishModel->getById($id);
			if ($getById) {
				$getById->tanggal_indo = date_ind("d M Y", $getById->tanggal);
				$getById->nilai_rp = "Rp.".number_format($getById->nilai,0,",",".");

				if ($getById->foto != "") {
					$getById->foto = base_url("/")."uploads/master/karyawan/orang/".$getById->foto;
				} else {
					$getById->foto = base_url("/")."assets/images/default/no_user.png";
				}

				$this->response->status = true;
				$this->response->message = "Data Punishment get by id";
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
			$nama_karyawan = $this->input->post("nama_karyawan");
			// $judul = $this->input->post("judul");
			$nilai = $this->input->post("nilai");
			$keterangan = $this->input->post("keterangan");
			$sp = $this->input->post('surat_peringatan');

			$this->form_validation->set_rules('tanggal', 'Tanggal', 'trim|required');
			$this->form_validation->set_rules('nama_karyawan', 'Nama Karyawan', 'trim|required');
			// $this->form_validation->set_rules('judul', 'Judul', 'trim|required');
			$this->form_validation->set_rules('nilai', 'Nilai', 'trim|required');
			$this->form_validation->set_rules('keterangan', 'keterangan', 'trim');

			if ($this->form_validation->run() == TRUE) {

				$getId = $this->punishModel->getById($id);
				if($getId->status == "Proses") {
					$data = array(
									"tanggal"		=>	$tanggal,
									"id_karyawan"	=>	$nama_karyawan,
									// "judul"			=>	$judul,
									"nilai"			=>	$nilai,
									"surat_peringatan" => $sp,
									"status"		=>	"Proses",
									"keterangan"	=>	$keterangan
								);
					$dataNotif = array(
											"keterangan"=> 	" Edit/update data Punishment karyawan dengan Nama <u>".$getKaryawan->nama."</u> dan No Karyawan : <u>".$getKaryawan->idfp."</u>",
											"user_id"	=>	$this->user_id,
											"level"		=>	"owner",
											"url_direct"=>	"approvalowner/punishment",
										);
					$update = $this->punishModel->update($id,$data,$dataNotif);
					if ($update) {
						parent::insertNotif($dataNotif);
						parent::sendNotifTopic("owner","Informasi","Perubahan data punishment ".$getkaryawan->nama,"049");
						$this->response->status = true;
						$this->response->message = alertSuccess("Berhasil update data punishment.");
					} else {
						$this->response->message = alertDanger("Gagal update data punishment.");
					}
				} else {
					$this->response->message = alertDanger("Opps, Maaf data punishment yang boleh update hanya berstatus <b>Proses</b>.");
				}
			} else {
				$this->response->message = validation_errors('<span style="color:red;">', '</span><br>');
				$this->response->error = array(
									"tanggal"	=> form_error("tanggal",'<span style="color:red;">','</span>'),
									"nama_karyawan"	=> form_error("nama_karyawan",'<span style="color:red;">','</span>'),
									// "judul"	=> form_error("judul",'<span style="color:red;">','</span>'),
									"nilai"	=> form_error("nilai",'<span style="color:red;">','</span>'),
								);
			}
		}
		parent::json();
	}

	public function delete($id)
	{
		parent::checkLoginUser(); // user login autentic checking

		if ($this->isPost()) {
			$getById = $this->punishModel->getById($id);
			$getKaryawan = $this->karyawanModel->getById($getById->id_karyawan);
			if ($getById) {

				$dataNotif = array(
											"keterangan"=> 	" Hapus data Punishment karyawan dengan Nama <u>".$getKaryawan->nama."</u> dan No Karyawan : <u>".$getKaryawan->idfp."</u>",
											"user_id"	=>	$this->user_id,
											"level"		=>	"owner",
											"url_direct"=>	"approvalowner/punishment",
										);
				if($getById->status == "Proses"){
					$delete = $this->punishModel->delete($id,$dataNotif);
					if ($delete) {
						parent::insertNotif($dataNotif);
						parent::sendNotifTopic("owner","Informasi","HRD menghapus data punishment ".$getkaryawan->nama,"050");
						$this->response->status = true;
						$this->response->message = alertSuccess("Data Punishment Berhasil di hapus.");
					} else {
						$this->response->message = alertDanger("Gagal, hapus data punishment.");
					}
				} else {
					$this->response->message = alertDanger("Opps, Maaf data punishment yang boleh di hapus hanya berstatus <b>Proses</b>.");
				}
			} else {
				$this->response->message = alertDanger("Data sudah tidak ada.");
			}
		}
		parent::json();
	}
}

/* End of file Punishment.php */
/* Location: ./application/controllers/rwd/Punishment.php */
