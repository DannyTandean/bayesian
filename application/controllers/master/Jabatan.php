<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Jabatan extends MY_Controller {

	public function __construct()
	{
		parent::__construct();
		$this->load->model('master/Jabatan_model',"jabatanModel");

	}

	public function index()
	{
		parent::checkLoginUser(); // user login autentic checking

		parent::headerTitle("Master Data > Jabatan","Master Data","Jabatan");
		$breadcrumbs = array(
							"Master Data"	=>	site_url('master/jabatan'),
							"Jabatan"		=>	"",
						);
		parent::breadcrumbs($breadcrumbs);

		parent::viewMaster();
	}

	public function ajax_list()
	{
		parent::checkLoginUser(); // user login autentic checking

		if ($this->isPost()) {
			$data = array();

			$orderBy = array(null,"jabatan","tunjangan","keterangan");
			$search = array("jabatan","tunjangan","keterangan");

			$result = $this->jabatanModel->findDataTable($orderBy,$search);
			foreach ($result as $item) {

				$item->tunjangan = "Rp.".number_format($item->tunjangan,0,",",".");

				$btnAction = '<button class="btn btn-warning btn-outline-warning btn-mini" onclick="btnEdit('.$item->id_jabatan.')"><i class="fa fa-pencil-square-o"></i>Edit</button>';
				$btnAction .= '&nbsp;&nbsp;&nbsp;<button class="btn btn-danger btn-outline-danger btn-mini" onclick="btnDelete('.$item->id_jabatan.')"><i class="fa fa-trash-o"></i>Hapus</button>';

				$item->button_action = $btnAction;
				$data[] = $item;
			}
			return $this->jabatanModel->findDataTableOutput($data,$search);
		}
	}

	public function add()
	{
		parent::checkLoginUser(); // user login autentic checking

		if ($this->isPost()) {
			$jabatan = $this->input->post("jabatan");
			$tunjangan = $this->input->post("tunjangan");
			$keterangan = $this->input->post("keterangan");

			$this->form_validation->set_rules('jabatan', 'Jabatan', 'trim|required|is_unique[master_jabatan.jabatan]');
			$this->form_validation->set_rules('tunjangan', 'Tunjangan', 'trim|required');
			$this->form_validation->set_rules('keterangan', 'keterangan', 'trim|required');

			if ($this->form_validation->run() == TRUE) {
				$data = array(
								"jabatan"	=>	$jabatan,
								"tunjangan"	=>	$tunjangan,
								"keterangan"=>	$keterangan
							);
				$insert = $this->jabatanModel->insert($data);
				if ($insert) {
					$this->response->status = true;
					$this->response->message = alertSuccess("Berhasil tambah data jabatan.");
				} else {
					$this->response->message = alertDanger("Gagal tambah data jabatan.");
				}

			} else {
				// $this->response->message = validation_errors('<span style="color:red;">', '</span><br>');
				$this->response->error = array(
									"jabatan"	=> form_error("jabatan",'<span style="color:red;">','</span>'),
									"tunjangan"	=> form_error("tunjangan",'<span style="color:red;">','</span>'),
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
			$getById = $this->jabatanModel->getById($id);
			if ($getById) {
				$getById->tunjangan_rp = "Rp.".number_format($getById->tunjangan,0,",",".");

				$this->response->status = true;
				$this->response->message = "Data jabatan get by id";
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
			$jabatan = $this->input->post("jabatan");
			$tunjangan = $this->input->post("tunjangan");
			$keterangan = $this->input->post("keterangan");

			$this->form_validation->set_rules('jabatan', 'Jabatan', 'trim|required');
			$this->form_validation->set_rules('tunjangan', 'Tunjangan', 'trim|required');
			$this->form_validation->set_rules('keterangan', 'keterangan', 'trim|required');

			if ($this->form_validation->run() == TRUE) {
				$data = array(
								"jabatan"	=>	$jabatan,
								"tunjangan"	=>	$tunjangan,
								"keterangan"=>	$keterangan
							);
				$update = $this->jabatanModel->update($id,$data);
				if ($update) {
					$this->response->status = true;
					$this->response->message = alertSuccess("Berhasil update data jabatan.");
				} else {
					$this->response->message = alertDanger("Gagal update data jabatan.");
				}
			} else {
				// $this->response->message = validation_errors('<span style="color:red;">', '</span><br>');
				$this->response->error = array(
									"jabatan"	=> form_error("jabatan",'<span style="color:red;">','</span>'),
									"tunjangan"	=> form_error("tunjangan",'<span style="color:red;">','</span>'),
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
			$getById = $this->jabatanModel->getById($id);
			if ($getById) {
				$delete = $this->jabatanModel->delete($id);
				if ($delete) {
					$this->response->status = true;
					$this->response->message = alertSuccess("Data jabatan Berhasil di hapus.");
				} else {
					$this->response->message = alertDanger("Gagal hapus data jabatan.");
				}
			} else {
				$this->response->message = alertDanger("Data sudah tidak ada.");
			}
		}
		parent::json();
	}

}

/* End of file Jabatan.php */
/* Location: ./application/controllers/master/Jabatan.php */
