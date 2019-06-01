<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Departemen extends MY_Controller {

	public function __construct()
	{
		parent::__construct();
		$this->load->model('pekerjaborongan/Departemen_model',"departemenModel");
		
	}

	public function index()
	{
		parent::checkLoginUser(); // user login autentic checking

		parent::headerTitle("Pekerja Borongan > Item","Pekerja Borongan","Item");
		$breadcrumbs = array(
							"Pekerja Borongan"	=>	site_url('pekerjaborongan/departemen'),
							"Item"		=>	"",
						);
		parent::breadcrumbs($breadcrumbs);

		parent::viewPekerjaBorongan();
	}

	public function ajax_list()
	{
		parent::checkLoginUser(); // user login autentic checking

		if ($this->isPost()) {
			$data = array();

			$orderBy = array(null,"kode","departemen","harga","keterangan");
			$search = array("kode","departemen","keterangan");

			$result = $this->departemenModel->findDataTable($orderBy,$search);
			foreach ($result as $item) {

				$item->harga = "Rp.".number_format($item->harga,0,",",".");
				
				$btnAction = '<button class="btn btn-warning btn-outline-warning btn-mini" onclick="btnEdit('.$item->id_departemen.')"><i class="fa fa-pencil-square-o"></i>Edit</button>';
				$btnAction .= '&nbsp;&nbsp;&nbsp;<button class="btn btn-danger btn-outline-danger btn-mini" onclick="btnDelete('.$item->id_departemen.')"><i class="fa fa-trash-o"></i>Hapus</button>';

				$item->button_action = $btnAction;
				$data[] = $item;
			}
			return $this->departemenModel->findDataTableOutput($data,$search);
		}
	}

	public function add()
	{
		parent::checkLoginUser(); // user login autentic checking

		if ($this->isPost()) {
			$kode = $this->input->post("kode");
			$departemen = $this->input->post("departemen");
			$harga = $this->input->post("harga");
			$keterangan = $this->input->post("keterangan");

			$this->form_validation->set_rules('kode', 'Kode', 'trim|required|is_unique[borongan_departemen.kode]');
			$this->form_validation->set_rules('departemen', 'Departemen', 'trim|required|is_unique[borongan_departemen.departemen]');
			$this->form_validation->set_rules('harga', 'Harga', 'trim|required');
			$this->form_validation->set_rules('keterangan', 'keterangan', 'trim|required');

			if ($this->form_validation->run() == TRUE) {
				$data = array(
								"kode"	=>	$kode,
								"departemen"	=>	$departemen,
								"harga"	=>	$harga,
								"keterangan"=>	$keterangan
							);
				$insert = $this->departemenModel->insert($data);
				if ($insert) {
					$this->response->status = true;
					$this->response->message = alertSuccess("Berhasil tambah data Item.");
				} else {
					$this->response->message = alertDanger("Gagal tambah data Item.");
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

	public function getId($id)
	{
		parent::checkLoginUser(); // user login autentic checking

		if ($this->isPost()) {
			$getById = $this->departemenModel->getById($id);
			if ($getById) {

				$this->response->status = true;
				$this->response->message = "Data departemen get by id";
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
			$kode = $this->input->post("kode");
			$departemen = $this->input->post("departemen");
			$harga = $this->input->post("harga");
			$keterangan = $this->input->post("keterangan");

			$this->form_validation->set_rules('kode', 'Kode', 'trim|required');
			$this->form_validation->set_rules('departemen', 'Departemen', 'trim|required');
			$this->form_validation->set_rules('harga', 'harga', 'trim|required');
			$this->form_validation->set_rules('keterangan', 'keterangan', 'trim|required');

			if ($this->form_validation->run() == TRUE) {
				$data = array(
								"kode"	=>	$kode,
								"departemen"	=>	$departemen,
								"harga"	=>	$harga,
								"keterangan"	=>	$keterangan
							);
				$update = $this->departemenModel->update($id,$data);
				if ($update) {
					$this->response->status = true;
					$this->response->message = alertSuccess("Berhasil update data Item.");
				} else {
					$this->response->message = alertDanger("Gagal update data Item.");
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
			$getById = $this->departemenModel->getById($id);
			if ($getById) {
				$delete = $this->departemenModel->delete($id);
				if ($delete) {
					$this->response->status = true;
					$this->response->message = alertSuccess("Data Item Berhasil di hapus.");
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

/* End of file Departemen.php */
/* Location: ./application/controllers/pekerjaborongan/Departemen.php */