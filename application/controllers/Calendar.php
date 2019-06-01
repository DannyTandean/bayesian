<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Calendar extends MY_Controller {

	public function __construct()
	{
		parent::__construct();
		$this->load->model('Calendar_model',"calendarModel");

	}

	public function index()
	{
		parent::checkLoginUser(); // user login autentic checking

		parent::headerTitle("Calendar","Calendar");
		$breadcrumbs = array(
							"Calendar"	=>	site_url('Calendar'),
						);
		parent::breadcrumbs($breadcrumbs);

		parent::view();
	}

	public function ajax_list()
	{
		parent::checkLoginUser(); // user login autentic checking

		if ($this->isPost()) {
			$data = array();

			$orderBy = array(null,null,"tanggal","event","tipe","ikut_potong","keterangan");
			$search = array("event");

			$result = $this->calendarModel->findDataTable($orderBy,$search);
			foreach ($result as $item) {

				$btnAction = '<button class="btn btn-warning btn-outline-warning btn-mini" onclick="btnEdit('.$item->id_calendar.')"><i class="fa fa-pencil-square-o"></i>Edit</button>';
				$btnAction .= '&nbsp;&nbsp;&nbsp;<button class="btn btn-danger btn-outline-danger btn-mini" onclick="btnDelete('.$item->id_calendar.')"><i class="fa fa-trash-o"></i>Hapus</button>';

				$item->tanggal = date_ind("d M Y",$item->tanggal);
				$item->button_action = $btnAction;
				$data[] = $item;
			}
			return $this->calendarModel->findDataTableOutput($data,$search);
		}
	}

	public function add()
	{
		parent::checkLoginUser(); // user login autentic checking

		if ($this->isPost()) {
			$tanggal = $this->input->post("tanggal");
			$event = $this->input->post('event');
			$type = $this->input->post('type');
			$keterangan = $this->input->post("keterangan");
			$ikutPotong = $this->input->post('ikut_potong');

			$this->form_validation->set_rules('tanggal', 'Tanggal', 'trim|required');
			$this->form_validation->set_rules('event', 'Event', 'trim|required');
			$this->form_validation->set_rules('type', 'Type', 'trim|required');
			$this->form_validation->set_rules('keterangan', 'keterangan', 'trim|required');

			if ($this->form_validation->run() == TRUE) {
				$data = array(
								"tanggal"	=>	$tanggal,
								"event"   => $event,
								"tipe"    => $type,
								"keterangan"=>	$keterangan,
								"ikut_potong" => $ikutPotong
							);
				$insert = $this->calendarModel->insert($data);
				if ($insert) {
					$this->response->status = true;
					$this->response->message = alertSuccess("Berhasil tambah data tanggal.");
				} else {
					$this->response->message = alertDanger("Gagal tambah data tanggal.");
				}

			} else {
				// $this->response->message = validation_errors('<span style="color:red;">', '</span><br>');
				$this->response->error = array(
									"tanggal"	=> form_error("tanggal",'<span style="color:red;">','</span>'),
									"event"	=> form_error("event",'<span style="color:red;">','</span>'),
									"tipe"	=> form_error("type",'<span style="color:red;">','</span>'),
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
			$getById = $this->calendarModel->getById($id);
			if ($getById) {
				$this->response->status = true;
				$this->response->message = "Data tanggal get by id";
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
			$event = $this->input->post('event');
			$type = $this->input->post('type');
			$keterangan = $this->input->post("keterangan");
			$ikutPotong = $this->input->post('ikut_potong');

			$this->form_validation->set_rules('tanggal', 'Tanggal', 'trim|required');
			$this->form_validation->set_rules('event', 'Event', 'trim|required');
			$this->form_validation->set_rules('type', 'Type', 'trim|required');
			$this->form_validation->set_rules('keterangan', 'keterangan', 'trim|required');

			if ($this->form_validation->run() == TRUE) {
				$data = array(
								"tanggal"	=>	$tanggal,
								"event"   => $event,
								"tipe"    => $type,
								"keterangan"=>	$keterangan,
								"ikut_potong" => $ikutPotong
							);
				$update = $this->calendarModel->update($id,$data);
				if ($update) {
					$this->response->status = true;
					$this->response->message = alertSuccess("Berhasil update data tanggal.");
				} else {
					$this->response->message = alertDanger("Gagal update data tanggal.");
				}
			} else {
				// $this->response->message = validation_errors('<span style="color:red;">', '</span><br>');
				$this->response->error = array(
									"tanggal"	=> form_error("tanggal",'<span style="color:red;">','</span>'),
									"event"	=> form_error("event",'<span style="color:red;">','</span>'),
									"type"	=> form_error("type",'<span style="color:red;">','</span>'),
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
			$getById = $this->calendarModel->getById($id);
			if ($getById) {
				$delete = $this->calendarModel->delete($id);
				if ($delete) {
					$this->response->status = true;
					$this->response->message = alertSuccess("Data tanggal Berhasil di hapus.");
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

/* End of file Calendar.php */
/* Location: ./application/controllers/Calendar.php */
