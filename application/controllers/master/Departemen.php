<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Departemen extends MY_Controller {

	public function __construct()
	{
		parent::__construct();
		$this->load->model('master/Departemen_model',"departemenModel");

	}

	public function index()
	{
		parent::checkLoginUser(); // user login autentic checking

		parent::headerTitle("Master Data > Departemen","Master Data","Departemen");
		$breadcrumbs = array(
							"Master Data"	=>	site_url('master/departemen'),
							"Departemen"		=>	"",
						);
		parent::breadcrumbs($breadcrumbs);

		parent::viewMaster();
	}

	public function ajax_list()
	{
		parent::checkLoginUser(); // user login autentic checking

		if ($this->isPost()) {
			$data = array();

			$orderBy = array(null,"kode","departemen","keterangan");
			$search = array("kode","departemen","keterangan");

			$result = $this->departemenModel->findDataTable($orderBy,$search);
			foreach ($result as $item) {


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
			$keterangan = $this->input->post("keterangan");

			$this->form_validation->set_rules('kode', 'Kode', 'trim|required|is_unique[master_departemen.kode]');
			$this->form_validation->set_rules('departemen', 'Departemen', 'trim|required|is_unique[master_departemen.departemen]');
			$this->form_validation->set_rules('keterangan', 'keterangan', 'trim|required');

			if ($this->form_validation->run() == TRUE) {
				$data = array(
								"kode"	=>	$kode,
								"departemen"	=>	$departemen,
								"keterangan"=>	$keterangan
							);
				$insert = $this->departemenModel->insert($data);
				if ($insert) {
					$this->response->status = true;
					$this->response->message = alertSuccess("Berhasil tambah data departemen.");
				} else {
					$this->response->message = alertDanger("Gagal tambah data departemen.");
				}

			} else {
				// $this->response->message = validation_errors('<span style="color:red;">', '</span><br>');
				$this->response->error = array(
									"kode"			=> form_error("kode",'<span style="color:red;">','</span>'),
									"departemen"	=> form_error("departemen",'<span style="color:red;">','</span>'),
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
			$keterangan = $this->input->post("keterangan");

			$this->form_validation->set_rules('kode', 'Kode', 'trim|required');
			$this->form_validation->set_rules('departemen', 'Departemen', 'trim|required');
			$this->form_validation->set_rules('keterangan', 'keterangan', 'trim|required');

			if ($this->form_validation->run() == TRUE) {
				$data = array(
								"kode"	=>	$kode,
								"departemen"	=>	$departemen,
								"keterangan"=>	$keterangan
							);
				$update = $this->departemenModel->update($id,$data);
				if ($update) {
					$this->response->status = true;
					$this->response->message = alertSuccess("Berhasil update data departemen.");
				} else {
					$this->response->message = alertDanger("Gagal update data departemen.");
				}
			} else {
				// $this->response->message = validation_errors('<span style="color:red;">', '</span><br>');
				$this->response->error = array(
									"kode"			=> form_error("kode",'<span style="color:red;">','</span>'),
									"departemen"	=> form_error("departemen",'<span style="color:red;">','</span>'),
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
					$this->response->message = alertSuccess("Data departemen Berhasil di hapus.");
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
/* Location: ./application/controllers/master/Departemen.php */
