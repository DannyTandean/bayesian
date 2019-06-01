<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Cabang extends MY_Controller {

	public function __construct()
	{
		parent::__construct();
		$this->load->model('master/Cabang_model',"cabangModel");

	}

	public function index()
	{
		parent::checkLoginUser(); // user login autentic checking

		parent::headerTitle("Master Data > Cabang","Master Data","Cabang");
		$breadcrumbs = array(
							"Master Data"	=>	site_url('master/cabang'),
							"Cabang"		=>	"",
						);
		parent::breadcrumbs($breadcrumbs);

		$data = array();

    $provinsi = $this->cabangModel->getProvinsi();
    $data["provinsi"] = $provinsi;
    parent::viewData($data);
		parent::viewMaster();
	}

	public function getRegencies($id)
  {
    if ($this->isPost()) {
      parent::checkLoginUser();
      $regencies = $this->cabangModel->getRegencies($id);
      if($regencies)
      {
        $this->response->status = true;
        $this->response->message = "get Pronvisi Data";
        $this->response->data = $regencies;
      }
      else {
        $this->response->message = alertDanger("failed get provinsi data");
      }
    }
    parent::json();
  }

	public function ajax_list()
	{
		parent::checkLoginUser(); // user login autentic checking

		if ($this->isPost()) {
			$data = array();

			$orderBy = array(null,null,"kode","cabang","alamat","kota","provinsi","kode_pos");
			$search = array("kode","cabang","kode_pos","kota","negara","provinsi");

			$result = $this->cabangModel->findDataTable($orderBy,$search);
			foreach ($result as $item) {

				// $item->tunjangan = "Rp.".number_format($item->tunjangan,0,",",".");

				$btnAction = '<button class="btn btn-warning btn-outline-warning btn-mini" onclick="btnEdit('.$item->id_cabang.')"><i class="fa fa-pencil-square-o"></i>Edit</button>';
				$btnAction .= '&nbsp;&nbsp;&nbsp;<button class="btn btn-danger btn-outline-danger btn-mini" onclick="btnDelete('.$item->id_cabang.')"><i class="fa fa-trash-o"></i>Hapus</button>';

				$item->button_action = $btnAction;
				$data[] = $item;
			}
			return $this->cabangModel->findDataTableOutput($data,$search);
		}
	}

	public function add()
	{
		parent::checkLoginUser(); // user login autentic checking

		if ($this->isPost()) {
			$kode = $this->input->post("kode");
			$cabang = $this->input->post("cabang");
			$alamat = $this->input->post("alamat");
			$kota = $this->input->post("kabupaten");
			$provinsi = $this->input->post("provinsi");
			$kode_pos = $this->input->post("kode_pos");
			$negara = $this->input->post("negara");
			$telepon = $this->input->post("telepon");
			$fax = $this->input->post("fax");
			$email = $this->input->post("email");

			$this->form_validation->set_rules('kode', 'Kode', 'trim|required|is_unique[master_cabang.kode]');
			$this->form_validation->set_rules('cabang', 'Nama', 'trim|required');
			$this->form_validation->set_rules('alamat', 'Alamat', 'trim|required');
			$this->form_validation->set_rules('kabupaten', 'Kabupaten', 'trim|required');
			$this->form_validation->set_rules('provinsi', 'Provinsi', 'trim|required');
			$this->form_validation->set_rules('kode_pos', 'Kode_pos', 'trim|required');
			$this->form_validation->set_rules('negara', 'Negara', 'trim|required');
			$this->form_validation->set_rules('telepon', 'Telepon', 'trim|required');
			$this->form_validation->set_rules('fax', 'Fax', 'trim|required');
			$this->form_validation->set_rules('email', 'email', 'trim|required');

			if ($this->form_validation->run() == TRUE) {
				$data = array(
								"kode"		=>	$kode,
								"cabang"	=>	$cabang,
								"alamat"	=>	$alamat,
								"kota"		=>	$kota,
								"provinsi"	=>	$provinsi,
								"kode_pos"	=>	$kode_pos,
								"negara"	=>	$negara,
								"telepon"	=>	$telepon,
								"fax"		=>	$fax,
								"email"		=>	$email
							);
				$insert = $this->cabangModel->insert($data);
				if ($insert) {
					$this->response->status = true;
					$this->response->message = alertSuccess("Berhasil tambah data cabang.");
				} else {
					$this->response->message = alertDanger("Gagal tambah data cabang.");
				}

			} else {
				$this->response->message = validation_errors('<span style="color:red;">', '</span><br>');
				$this->response->error = array(
									"kode"	=> form_error("kode",'<span style="color:red;">','</span>'),
									"cabang"	=> form_error("cabang",'<span style="color:red;">','</span>'),
									"alamat"	=> form_error("alamat",'<span style="color:red;">','</span>'),
									"kota"	=> form_error("kota",'<span style="color:red;">','</span>'),
									"provinsi"	=> form_error("provinsi",'<span style="color:red;">','</span>'),
									"kode_pos"	=> form_error("kode_pos",'<span style="color:red;">','</span>'),
									"negara"	=> form_error("negara",'<span style="color:red;">','</span>'),
									"telepon"	=> form_error("telepon",'<span style="color:red;">','</span>'),
									"fax"	=> form_error("fax",'<span style="color:red;">','</span>'),
									"email"	=> form_error("email",'<span style="color:red;">','</span>'),
								);
			}
		}
		parent::json();
	}

	public function getId($id)
	{
		parent::checkLoginUser(); // user login autentic checking

		if ($this->isPost()) {
			$getById = $this->cabangModel->getById($id);
			if ($getById) {
				// $getById->tunjangan_rp = "Rp.".number_format($getById->tunjangan,0,",",".");

				$this->response->status = true;
				$this->response->message = "Data cabang get by id";
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
			$cabang = $this->input->post("cabang");
			$alamat = $this->input->post("alamat");
			$kota = $this->input->post("kabupaten");
			$provinsi = $this->input->post("provinsi");
			$kode_pos = $this->input->post("kode_pos");
			$negara = $this->input->post("negara");
			$telepon = $this->input->post("telepon");
			$fax = $this->input->post("fax");
			$email = $this->input->post("email");

			$this->form_validation->set_rules('kode', 'Kode', 'trim|required');
			$this->form_validation->set_rules('cabang', 'Nama', 'trim|required');
			$this->form_validation->set_rules('alamat', 'Alamat', 'trim|required');
			$this->form_validation->set_rules('kabupaten', 'Kabupaten', 'trim|required');
			$this->form_validation->set_rules('provinsi', 'Provinsi', 'trim|required');
			$this->form_validation->set_rules('kode_pos', 'Kode_pos', 'trim|required');
			$this->form_validation->set_rules('negara', 'negara', 'trim|required');
			$this->form_validation->set_rules('telepon', 'Telepon', 'trim|required');
			$this->form_validation->set_rules('fax', 'Fax', 'trim|required');
			$this->form_validation->set_rules('email', 'email', 'trim|required');

			if ($this->form_validation->run() == TRUE) {
				$data = array(
								"kode"		=>	$kode,
								"cabang"	=>	$cabang,
								"alamat"	=>	$alamat,
								"kota"		=>	$kota,
								"provinsi"	=>	$provinsi,
								"kode_pos"	=>	$kode_pos,
								"negara"	=>	$negara,
								"telepon"	=>	$telepon,
								"fax"		=>	$fax,
								"email"		=>	$email
							);
				$update = $this->cabangModel->update($id,$data);
				if ($update) {
					$this->response->status = true;
					$this->response->message = alertSuccess("Berhasil update data cabang.");
				} else {
					$this->response->message = alertDanger("Gagal update data cabang.");
				}
			} else {
				// $this->response->message = validation_errors('<span style="color:red;">', '</span><br>');
				$this->response->error = array(
									"kode"	=> form_error("kode",'<span style="color:red;">','</span>'),
									"cabang"	=> form_error("cabang",'<span style="color:red;">','</span>'),
									"alamat"	=> form_error("alamat",'<span style="color:red;">','</span>'),
									"kota"	=> form_error("kota",'<span style="color:red;">','</span>'),
									"provinsi"	=> form_error("provinsi",'<span style="color:red;">','</span>'),
									"kode_pos"	=> form_error("kode_pos",'<span style="color:red;">','</span>'),
									"negara"	=> form_error("negara",'<span style="color:red;">','</span>'),
									"telepon"	=> form_error("telepon",'<span style="color:red;">','</span>'),
									"fax"	=> form_error("fax",'<span style="color:red;">','</span>'),
									"email"	=> form_error("email",'<span style="color:red;">','</span>'),
								);
			}
		}
		parent::json();
	}

	public function delete($id)
	{
		parent::checkLoginUser(); // user login autentic checking

		if ($this->isPost()) {
			$getById = $this->cabangModel->getById($id);
			if ($getById) {
				$delete = $this->cabangModel->delete($id);
				if ($delete) {
					$this->response->status = true;
					$this->response->message = alertSuccess("Data cabang Berhasil di hapus.");
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

/* End of file Cabang.php */
/* Location: ./application/controllers/master/Cabang.php */
