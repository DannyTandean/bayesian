<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Kriteria extends MY_Controller {

	public function __construct()
	{
		parent::__construct();
		$this->load->model('master/Kriteria_model',"kriteriaModel");
		$this->load->model('master/Karyawan_model',"karyawanModel");
		parent::checkLoginOwner();

	}

	public function index()
	{
		parent::checkLoginUser(); // user login autentic checking

		parent::headerTitle("Master Data > Kriteria Penilaian","Master Data","Kriteria Penilaian");
		$breadcrumbs = array(
							"Master"	=>	site_url('master/kriteria'),
							"Kriteria Penilaian"		=>	"",
						);
		parent::breadcrumbs($breadcrumbs);

		parent::viewMaster();
	}

	public function ajax_list()
	{
		parent::checkLoginUser(); // user login autentic checking

		if ($this->isPost()) {
			$data = array();

			$orderBy = array(null,null,"nama_penilaian");
			$search = array("nama_penilaian");

			$result = $this->kriteriaModel->findDataTable($orderBy,$search);
			foreach ($result as $item) {
				$btnAction = '<button class="btn btn-warning btn-mini" onclick="btnEdit('.$item->id_list_penilaian.')"><i class="fa fa-pencil-square-o"></i>Edit</button>';
				$btnAction .= '&nbsp;&nbsp;&nbsp;<button class="btn btn-danger btn-mini" onclick="btnDelete('.$item->id_list_penilaian.')"><i class="fa fa-trash-o"></i>Hapus</button>';
				$item->button_action = $btnAction;
				$data[] = $item;
			}
			return $this->kriteriaModel->findDataTableOutput($data,$search);
		}
	}

	public function add()
	{
		parent::checkLoginUser(); // user login autentic checking

		if ($this->isPost()) {
			$penilaian = $this->input->post("penilaian");

			$this->form_validation->set_rules('penilaian', 'Tenilaian', 'trim|required');

			if ($this->form_validation->run() == TRUE) {

				$data = array(
								"nama_penilaian" => $penilaian,
							);

				$insert = $this->kriteriaModel->insert($data);
				if ($insert) {
					$this->response->status = true;
					$this->response->message = alertSuccess("Berhasil Tambah Data Kriteria.");
				} else {
					$this->response->message = alertDanger("Gagal, tambah data Kriteria.");
				}

			} else {
				// $this->response->message = validation_errors('<span style="color:red;">', '</span><br>');
				$this->response->error = array(
									"penilaian"	=> form_error("penilaian",'<span style="color:red;">','</span>'),
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
				$dataKaryawan = $this->kriteriaModel->getAllKaryawanAjax();
			} else {
				$searchTerm = $this->input->post("searchTerm");
				$dataKaryawan = $this->kriteriaModel->getAllKaryawanAjaxSearch($searchTerm);
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
			$data = $this->kriteriaModel->getIdKaryawan($id);
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
			$getById = $this->kriteriaModel->getDataKaryawanSelect($id);
			if ($getById) {
				$this->response->status = true;
				$this->response->message = "Data kriteria get by id";
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
			$getById = $this->kriteriaModel->getById($id);
			if ($getById) {
				$this->response->status = true;
				$this->response->message = "Data kriteria get by id";
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
			$penilaian = $this->input->post("penilaian");

			$this->form_validation->set_rules('penilaian', 'Tenilaian', 'trim|required');

			if ($this->form_validation->run() == TRUE) {
				$data = array(
								"nama_penilaian" => $penilaian,
							);

				$update = $this->kriteriaModel->update($id,$data);
				if ($update) {

					$this->response->status = true;
					$this->response->message = alertSuccess("Berhasil proses update Data Kriteria");
				} else {
					$this->response->message = alertDanger("Gagal, update data Kriteria.");
				}
			} else {
				// $this->response->message = validation_errors('<span style="color:red;">', '</span><br>');
				$this->response->error = array(
									"penilaian"	=> form_error("penilaian",'<span style="color:red;">','</span>'),
								);
			}
		}
		parent::json();
	}

	public function delete($id)
	{
		parent::checkLoginUser(); // user login autentic checking

		if ($this->isPost()) {
			$getById = $this->kriteriaModel->getById($id);
			if ($getById) {
				$delete = $this->kriteriaModel->delete($id);
				if ($delete) {
					$this->response->status = true;
					$this->response->message = alertSuccess("Berhasil proses hapus Data Kriteria.");
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

/* End of file Kriteria.php */
/* Location: ./application/controllers/aktivitas/Kriteria.php */
