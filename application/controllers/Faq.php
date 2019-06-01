<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Faq extends MY_Controller {

	public function __construct()
	{
		parent::__construct();
		$this->load->model("Faq_model","faqModel");
	}

	public function index()
	{
		parent::checkLoginUser(); // check login user

		parent::headerTitle("Support","Faq");

		$breadcrumbs = array(
							"FAQ"	=>	site_url('faq'),
						);
		parent::breadcrumbs($breadcrumbs);

		parent::view();
	}

	public function ajax_list()
	{
		parent::checkLoginUser(); // user login autentic checking

		if ($this->isPost()) {
			$data = array();

			$orderBy = array(null,null,null,"tanggal","pertanyaan","jawaban","penulis");
			$search = array("pertanyaan");

			$result = $this->faqModel->findDataTable($orderBy,$search);
			foreach ($result as $item) {

				$btnAction = '<button class="btn btn-warning btn-mini" onclick="btnEdit('.$item->id_faq.')"><i class="fa fa-pencil-square-o"></i>Edit</button>';
				$btnAction .= '&nbsp;&nbsp;&nbsp;<button class="btn btn-danger btn-mini" onclick="btnDelete('.$item->id_faq.')"><i class="fa fa-trash-o"></i>Hapus</button>';

				$item->tanggal = date_ind("d M Y",$item->tanggal);
				$item->button_action = $btnAction;
				$data[] = $item;
			}
			return $this->faqModel->findDataTableOutput($data,$search);
		}
	}

	public function add()
	{
		parent::checkLoginUser(); // user login autentic checking

		if ($this->isPost()) {
			$tanggal = $this->input->post("tanggal");
			$pertanyaan = $this->input->post("pertanyaan");
			$jawaban = $this->input->post("jawaban");
			$username = $this->input->post('username');

			$this->form_validation->set_rules('tanggal', 'tanggal', 'trim|required');
			$this->form_validation->set_rules('pertanyaan', 'pertanyaan', 'trim|required');
			$this->form_validation->set_rules('jawaban', 'jawaban', 'trim|required');

			if ($this->form_validation->run() == TRUE) {
				$data = array(
								"tanggal" =>	$tanggal,
								"pertanyaan" =>	$pertanyaan,
								"jawaban" =>	$jawaban,
								"penulis" => $username
							);
				$insert = $this->faqModel->insert($data);
				if ($insert) {
					$this->response->status = true;
					$this->response->message = alertSuccess("Berhasil Tambah data FAQ.");
				} else {
					$this->response->message = alertDanger("Gagal Tambah data FAQ.");
				}
			} else {
				// $this->response->message = validation_errors('<span style="color:red;">', '</span><br>');
				$this->response->error = array(
									"pertanyaan"	=> form_error("pertanyaan",'<span style="color:red;">','</span>'),
								);
			}
		}
		parent::json();
	}

	public function getId($id)
	{
		parent::checkLoginUser(); // user login autentic checking

		if ($this->isPost()) {
			$getById = $this->faqModel->getById($id);
			if ($getById){
				$this->response->status = true;
				$this->response->message = "Data faq get by id";
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
			$pertanyaan = $this->input->post("pertanyaan");
			$jawaban = $this->input->post("jawaban");
			$username = $this->input->post('username');

			$this->form_validation->set_rules('tanggal', 'tanggal', 'trim|required');
			$this->form_validation->set_rules('pertanyaan', 'pertanyaan', 'trim|required');
			$this->form_validation->set_rules('jawaban', 'jawaban', 'trim|required');

			if ($this->form_validation->run() == TRUE) {
				$data = array(
								"tanggal" =>	$tanggal,
								"pertanyaan" =>	$pertanyaan,
								"jawaban" =>	$jawaban,
								"penulis" => $username
							);
				$update = $this->faqModel->update($id,$data);
				if ($update) {
					$this->response->status = true;
					$this->response->message = alertSuccess("Berhasil update data FAQ.");
				} else {
					$this->response->message = alertDanger("Gagal update data FAQ.");
				}
			} else {
				// $this->response->message = validation_errors('<span style="color:red;">', '</span><br>');
				$this->response->error = array(
									"pertanyaan"	=> form_error("pertanyaan",'<span style="color:red;">','</span>'),
								);
			}
		}
		parent::json();
	}

	public function delete($id)
	{
		parent::checkLoginUser(); // user login autentic checking

		if ($this->isPost()) {
			$getById = $this->faqModel->getById($id);
			if ($getById) {
				$delete = $this->faqModel->delete($id);
				if ($delete) {

					$this->response->status = true;
					$this->response->message = alertSuccess("Data faq Berhasil di hapus.");
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