<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Bank extends MY_Controller {

	public function __construct()
	{
		parent::__construct();
		$this->load->model('master/Bank_model',"bankModel");
		
	}

	public function index()
	{
		parent::checkLoginUser(); // user login autentic checking

		parent::headerTitle("Master Data > Bank","Master Data","Bank");
		$breadcrumbs = array(
							"Master Data"	=>	site_url('master/bank'),
							"Bank"		=>	"",
						);
		parent::breadcrumbs($breadcrumbs);

		parent::viewMaster();
	}

	public function ajax_list()
	{
		parent::checkLoginUser(); // user login autentic checking

		if ($this->isPost()) {
			$data = array();

			$orderBy = array(null,"kode","bank","keterangan");
			$search = array("kode","bank","keterangan");

			$result = $this->bankModel->findDataTable($orderBy,$search);
			foreach ($result as $item) {
				
				$btnAction = '<button class="btn btn-warning btn-outline-warning btn-mini" onclick="btnEdit('.$item->id_bank.')"><i class="fa fa-pencil-square-o"></i>Edit</button>';
				$btnAction .= '&nbsp;&nbsp;&nbsp;<button class="btn btn-danger btn-outline-danger btn-mini" onclick="btnDelete('.$item->id_bank.')"><i class="fa fa-trash-o"></i>Hapus</button>';

				$item->button_action = $btnAction;
				$data[] = $item;
			}
			return $this->bankModel->findDataTableOutput($data,$search);
		}
	}

	public function add()
	{
		parent::checkLoginUser(); // user login autentic checking

		if ($this->isPost()) {
			$kode = $this->input->post("kode");
			$bank = $this->input->post("bank");
			$keterangan = $this->input->post("keterangan");

			$this->form_validation->set_rules('kode', 'Kode', 'trim|required|is_unique[master_bank.kode]');
			$this->form_validation->set_rules('bank', 'Bank', 'trim|required');
			$this->form_validation->set_rules('keterangan', 'keterangan', 'trim|required');

			if ($this->form_validation->run() == TRUE) {
				$data = array(
								"kode" =>	$kode,
								"bank" =>	$bank,
								"keterangan" =>	$keterangan
							);
				$insert = $this->bankModel->insert($data);
				if ($insert) {
					$this->response->status = true;
					$this->response->message = alertSuccess("Berhasil tambah data bank.");
				} else {
					$this->response->message = alertDanger("Gagal tambah data bank.");
				}
				
			} else {
				// $this->response->message = validation_errors('<span style="color:red;">', '</span><br>');
				$this->response->error = array(
									"kode"	=> form_error("kode",'<span style="color:red;">','</span>'),
									"bank"	=> form_error("bank",'<span style="color:red;">','</span>'),
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
			$getById = $this->bankModel->getById($id);
			if ($getById){
				$this->response->status = true;
				$this->response->message = "Data bank get by id";
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
			$bank = $this->input->post("bank");
			$keterangan = $this->input->post("keterangan");

			$this->form_validation->set_rules('kode', 'kode', 'trim|required');
			$this->form_validation->set_rules('bank', 'bank', 'trim|required');
			$this->form_validation->set_rules('keterangan', 'keterangan', 'trim|required');

			if ($this->form_validation->run() == TRUE) {
				$data = array(
								"kode"		 =>	$kode,
								"bank"		 =>	$bank,
								"keterangan" =>	$keterangan
							);
				$update = $this->bankModel->update($id,$data);
				if ($update) {
					$this->response->status = true;
					$this->response->message = alertSuccess("Berhasil update data bank.");
				} else {
					$this->response->message = alertDanger("Gagal update data bank.");
				}
			} else {
				// $this->response->message = validation_errors('<span style="color:red;">', '</span><br>');
				$this->response->error = array(
									"kode"	=> form_error("kode",'<span style="color:red;">','</span>'),
									"bank"	=> form_error("bank",'<span style="color:red;">','</span>'),
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
			$getById = $this->bankModel->getById($id);
			if ($getById) {
				$delete = $this->bankModel->delete($id);
				if ($delete) {
					$this->response->status = true;
					$this->response->message = alertSuccess("Data bank Berhasil di hapus.");
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

/* End of file Bank.php */
/* Location: ./application/controllers/master/Bank.php */