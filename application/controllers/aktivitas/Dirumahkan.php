<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Dirumahkan extends MY_Controller {

	public function __construct()
	{
		parent::__construct();
		$this->load->model('aktivitas/Dirumahkan_model',"dirumahkanModel");
		$this->load->model('master/Karyawan_model',"karyawanModel");
		parent::checkLoginOwner();

	}

	public function index()
	{
		parent::checkLoginUser(); // user login autentic checking

		parent::headerTitle("Aktivitas Data > Dirumahkan","Aktivitas Data","Dirumahkan");
		$breadcrumbs = array(
							"Aktivitas"	=>	site_url('aktivitas/dirumahkan'),
							"Dirumahkan"		=>	"",
						);
		parent::breadcrumbs($breadcrumbs);
		$data = array();
		$data['karyawan'] = $this->dirumahkanModel->getKaryawan();
		parent::viewData($data);
		parent::viewAktivitas();
	}

	public function ajax_list()
	{
		parent::checkLoginUser(); // user login autentic checking

		if ($this->isPost()) {
			$data = array();

			$orderBy = array(null,null,"tanggal","nama","departemen","jabatan","keterangans","tgl_dirumahkan","akhir_dirumahkan","lama","status");
			$search = array("nama","departemen","jabatan");

			$result = $this->dirumahkanModel->findDataTable($orderBy,$search);
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
				$item->tanggal = date_ind("d M Y",$item->tanggal);
				$item->tgl_dirumahkan = date_ind("d M Y",$item->tgl_dirumahkan);
				$item->akhir_dirumahkan = date_ind("d M Y",$item->akhir_dirumahkan);
				$btnAction = '<button class="btn btn-warning btn-mini" onclick="btnEdit('.$item->id_dirumahkan.')"><i class="fa fa-pencil-square-o"></i>Edit</button>';
				$btnAction .= '&nbsp;&nbsp;&nbsp;<button class="btn btn-danger btn-mini" onclick="btnDelete('.$item->id_dirumahkan.')"><i class="fa fa-trash-o"></i>Hapus</button>';
				$item->button_action = $btnAction;
				$data[] = $item;
			}
			return $this->dirumahkanModel->findDataTableOutput($data,$search);
		}
	}

	public function add()
	{
		parent::checkLoginUser(); // user login autentic checking

		if ($this->isPost()) {
			$tanggal = $this->input->post("tanggal");
			$karyawan = $this->input->post('karyawan');
			$mulaiDirumahkan = $this->input->post('mulaiDirumahkan');
			$akhirDirumahkan = $this->input->post('akhirDirumahkan');
			$keterangan = $this->input->post("keterangan");
			$date1=strtotime($mulaiDirumahkan);
			$date2=strtotime($akhirDirumahkan);
			$lama=abs($date1 - $date2)/86400+1;
			$this->form_validation->set_rules('tanggal', 'Tanggal', 'trim|required');
			$this->form_validation->set_rules('karyawan', 'Karyawan', 'trim|required');
			$this->form_validation->set_rules('mulaiDirumahkan', 'Mulai Dirumahkan', 'trim|required');
			$this->form_validation->set_rules('akhirDirumahkan', 'Akhir Dirumahkan', 'trim|required');
			$this->form_validation->set_rules('keterangan', 'Keterangan', 'trim|required');

			if ($this->form_validation->run() == TRUE) {
				$checkDataJadwal =  $this->dirumahkanModel->checkDataJadwal($tanggal,$karyawan);
				if (sizeof($checkDataJadwal) >0 && $checkDataJadwal[0]->id_karyawan == $karyawan) {
					$this->response->status = false;
					$this->response->message = alertDanger("Data Karyawan Yang Di Input dengan tanggal yang anda pilih sudah ada.!");
				} else {
				$data = array(
								"id_karyawan" => $karyawan,
								"tanggal"	=>	$tanggal,
								"tgl_dirumahkan" => $mulaiDirumahkan,
								"akhir_dirumahkan" => $akhirDirumahkan,
								"keterangans"=>	$keterangan,
								"lama" => $lama
							);
				$dataNotif = array(
									"keterangan"=> 	" Tambah data dirumahkan baru.",
									"user_id"	=>	$this->user->id_pengguna,
									"level"		=>	"hrd",
									"url_direct"=>	"approval/dirumahkan",
								);
				$insert = $this->dirumahkanModel->insert($data,$dataNotif);
				if ($insert) {
					//notif firebase
					parent::insertNotif($dataNotif);
					parent::sendNotifTopic("hrd","pengajuan Dirumahkan","karyawan mengajukan data dirumahkan baru","033");
					$this->response->status = true;
					$this->response->message = alertSuccess("Berhasil proses Tambah Data Dirumahkan dan menunggu approval dari hrd.");
				} else {
					$this->response->message = alertDanger("Gagal, tambah data Dirumahkan.");
				}
			  }
			} else {
				// $this->response->message = validation_errors('<span style="color:red;">', '</span><br>');
				$this->response->error = array(
									"karyawan"	=> form_error("karyawan",'<span style="color:red;">','</span>'),
									"tanggal"	=> form_error("tanggal",'<span style="color:red;">','</span>'),
									"mulaiDirumahkan"	=> form_error("mulaiDirumahkan",'<span style="color:red;">','</span>'),
									"akhirDirumahkan"	=> form_error("akhirDirumahkan",'<span style="color:red;">','</span>'),
									"keterangans"	=> form_error("keterangan",'<span style="color:red;">','</span>'),
								);
			}
		}
		parent::json();
	}

	public function allKaryawanAjax()
	{
		parent::checkLoginUser(); // user login autentic checking

		if ($this->isPost()) {
			if (!isset($_POST["searchTerm"])) {
				$dataKaryawan = $this->dirumahkanModel->getAllKaryawanAjax();
			} else {
				$searchTerm = $this->input->post("searchTerm");
				$dataKaryawan = $this->dirumahkanModel->getAllKaryawanAjaxSearch($searchTerm);
			}

			$data = array();
			// $data[] = array("id"=>"0", "text"=> "--Pilih Karyawan--");
			foreach ($dataKaryawan as $val) {
				// $row = array();
				$data[] = array("id"=>$val->id, "text"=>$val->nama);
				// $data[] = $row;
			}
			/*if ($dataKaryawan) {
				$this->response->status = true;
				$this->response->message = "Data All karyawan";
				$this->response->data = $dataKaryawan;
			}*/
			parent::json($data);
		}

	}

	public function idKaryawan($id)
	{
		parent::checkLoginUser(); // user login autentic checking

		if ($this->isPost()) {
			$data = $this->dirumahkanModel->getIdKaryawan($id);
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

	public function getNama($id)
	{
		parent::checkLoginUser(); // user login autentic checking

		if ($this->isPost()) {
			$getById = $this->dirumahkanModel->getDataKaryawanSelect($id);
			if ($getById) {
				$this->response->status = true;
				$this->response->message = "Data dirumahkan get by id";
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
			$getById = $this->dirumahkanModel->getById($id);
			if ($getById) {
				if ($getById->foto != "") {
					$getById->foto = base_url("/")."uploads/master/karyawan/orang/".$getById->foto;
				} else {
					$getById->foto = base_url("/")."assets/images/default/no_user.png";
				}
				$this->response->status = true;
				$this->response->message = "Data dirumahkan get by id";
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
			$getById = $this->dirumahkanModel->getById($id);
			// $tanggal = $this->input->post("tanggal");
			$karyawan = $this->input->post('karyawan');
			$mulaiDirumahkan = $this->input->post('mulaiDirumahkan');
			$akhirDirumahkan = $this->input->post('akhirDirumahkan');
			$keterangan = $this->input->post("keterangan");
			$date1=strtotime($mulaiDirumahkan);
			$date2=strtotime($akhirDirumahkan);
			$lama=abs($date1 - $date2)/86400+1;
			// $this->form_validation->set_rules('tanggal', 'Tanggal', 'trim|required');
			// $this->form_validation->set_rules('karyawan', 'Karyawan', 'trim|required');
			$this->form_validation->set_rules('mulaiDirumahkan', 'Mulai Dirumahkan', 'trim|required');
			$this->form_validation->set_rules('akhirDirumahkan', 'Akhir Dirumahkan', 'trim|required');
			$this->form_validation->set_rules('keterangan', 'Keterangan', 'trim|required');

			$getkaryawan = $this->karyawanModel->getById($karyawan);

			if ($this->form_validation->run() == TRUE) {
				$data = array(
								"id_karyawan" => $karyawan,
								"tanggal"	=>	$getById->tanggal,
								"tgl_dirumahkan" => $mulaiDirumahkan,
								"akhir_dirumahkan" => $akhirDirumahkan,
								"keterangans"=>	$keterangan,
								"lama" => $lama
							);
				$dataNotif = array(
									"keterangan"=> 	"Edit/update data dirumahkan dengan Nama : <u>".$getkaryawan->nama."</u> dan No Induk : <u>".$getkaryawan->idfp."</u>.",
									"user_id"	=>	$this->user->id_pengguna,
									"level"		=>	"hrd",
									"url_direct"=>	"approval/dirumahkan",
								);
				$update = $this->dirumahkanModel->update($id,$data,$dataNotif);
				if ($update) {
					//notif firebase
					parent::insertNotif($dataNotif);
					parent::sendNotifTopic("hrd","Informasi","perubahan data Dirumahkan". $getkaryawan->nama,"045");
					$this->response->status = true;
					$this->response->message = alertSuccess("Berhasil proses update Data Dirumahkan");
				} else {
					$this->response->message = alertDanger("Gagal, update data Dirumahkan.");
				}
			} else {
				// $this->response->message = validation_errors('<span style="color:red;">', '</span><br>');
				$this->response->error = array(
									"karyawan"	=> form_error("karyawan",'<span style="color:red;">','</span>'),
									"tanggal"	=> form_error("tanggal",'<span style="color:red;">','</span>'),
									"mulaiDirumahkan"	=> form_error("mulaiDirumahkan",'<span style="color:red;">','</span>'),
									"akhirDirumahkan"	=> form_error("akhirDirumahkan",'<span style="color:red;">','</span>'),
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
			$getById = $this->dirumahkanModel->getById($id);
			$getkaryawan = $this->karyawanModel->getById($getById->id_karyawan);
			if ($getById) {
				$dataNotif = array(
									"keterangan"=> 	" Hapus data Dirumahkan dengan Nama : <u>".$getkaryawan->nama."</u> dan No Induk : <u>".$getkaryawan->idfp."</u>",
									"user_id"	=>	$this->user->id_pengguna,
									"level"		=>	"hrd",
									"url_direct"=>	"approval/dirumahkan",
								);
				$delete = $this->dirumahkanModel->delete($id,$dataNotif);
				if ($delete) {
					//notif firebase
					parent::insertNotif($dataNotif);
					parent::sendNotifTopic("hrd","Informasi","Data Dirumahkan ini telah di hapus","046");
					$this->response->status = true;
					$this->response->message = alertSuccess("Berhasil proses hapus Data Dirumahkan.");
				} else {
					$this->response->message = alertDanger("Gagal, hapus data..");
				}
			} else {
				$this->response->message = alertDanger("Data sudah tidak ada.");
			}
		}
		parent::json();
	}

}

/* End of file Dirumahkan.php */
/* Location: ./application/controllers/aktivitas/Dirumahkan.php */
