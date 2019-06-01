<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Grup extends MY_Controller {

	public function __construct()
	{
		parent::__construct();
		$this->load->model('master/Grup_model',"grupModel");

	}

	public function index()
	{
		parent::checkLoginUser(); // user login autentic checking

		parent::headerTitle("Master Data > Grup","Master Data","Grup");
		$breadcrumbs = array(
							"Master Data"	=>	site_url('master/grup'),
							"Grup"		=>	"",
						);
		parent::breadcrumbs($breadcrumbs);

		parent::viewMaster();
	}

	public function ajax_list()
	{
		parent::checkLoginUser(); // user login autentic checking

		if ($this->isPost()) {
			$data = array();

			$orderBy = array(null,"grup","keterangan");
			$search = array("grup","keterangan");

			$result = $this->grupModel->findDataTable($orderBy,$search);
			foreach ($result as $item) {

				$btnAction = '<button class="btn btn-warning btn-outline-warning btn-mini" onclick="btnEdit('.$item->id_grup.')"><i class="fa fa-pencil-square-o"></i>Edit</button>';
				$btnAction .= '&nbsp;&nbsp;&nbsp;<button class="btn btn-danger btn-outline-danger btn-mini" onclick="btnDelete('.$item->id_grup.')"><i class="fa fa-trash-o"></i>Hapus</button>';

				$item->button_action = $btnAction;
				$data[] = $item;
			}
			return $this->grupModel->findDataTableOutput($data,$search);
		}
	}

	public function add()
	{
		parent::checkLoginUser(); // user login autentic checking

		if ($this->isPost()) {
			$grup = $this->input->post("grup");
			$keterangan = $this->input->post("keterangan");

			$this->form_validation->set_rules('grup', 'Grup', 'trim|required|is_unique[master_golongan.golongan]');
			$this->form_validation->set_rules('keterangan', 'keterangan', 'trim|required');

			if ($this->form_validation->run() == TRUE) {
				$data = array(
								"grup"	=>	$grup,
								"keterangan"=>	$keterangan
							);
				$insert = $this->grupModel->insert($data);
				if ($insert) {
					$this->response->status = true;
					$this->response->message = alertSuccess("Berhasil tambah data grup.");
				} else {
					$this->response->message = alertDanger("Gagal tambah data grup.");
				}

			} else {
				// $this->response->message = validation_errors('<span style="color:red;">', '</span><br>');
				$this->response->error = array(
									"grup"	=> form_error("grup",'<span style="color:red;">','</span>'),
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
			$getById = $this->grupModel->getById($id);
			if ($getById) {
				$this->response->status = true;
				$this->response->message = "Data grup get by id";
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
			$grup = $this->input->post("grup");
			$keterangan = $this->input->post("keterangan");

			$this->form_validation->set_rules('grup', 'Grup', 'trim|required');
			$this->form_validation->set_rules('keterangan', 'keterangan', 'trim|required');

			if ($this->form_validation->run() == TRUE) {
				$data = array(
								"grup"	=>	$grup,
								"keterangan"=>	$keterangan
							);
				$update = $this->grupModel->update($id,$data);
				if ($update) {
					$this->response->status = true;
					$this->response->message = alertSuccess("Berhasil update data grup.");
				} else {
					$this->response->message = alertDanger("Gagal update data grup.");
				}
			} else {
				// $this->response->message = validation_errors('<span style="color:red;">', '</span><br>');
				$this->response->error = array(
									"grup"	=> form_error("grup",'<span style="color:red;">','</span>'),
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
			$getById = $this->grupModel->getById($id);
			if ($getById) {
				$delete = $this->grupModel->delete($id);
				if ($delete) {
					$this->response->status = true;
					$this->response->message = alertSuccess("Data grup Berhasil di hapus.");
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

/* End of file Grup.php */
/* Location: ./application/controllers/master/Grup.php */
