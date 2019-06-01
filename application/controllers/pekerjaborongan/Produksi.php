<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Produksi extends MY_Controller {

	public function __construct()
	{
		parent::__construct();
		$this->load->model('pekerjaborongan/Produksi_model',"produksiModel");
		$this->load->model('pekerjaborongan/Pekerja_model',"pekerjaModel");

		parent::checkLoginOwner();

	}

	public function index()
	{
		parent::checkLoginUser(); // user login autentic checking

		parent::headerTitle("Pekerja Borongan > Produksi Karyawan","Pekerja Borongan","produksi Karyawan");
		$breadcrumbs = array(
							"Pekerja Borongan"	=>	site_url('pekerjaborongan/produksi'),
							"Produksi"		=>	"",
						);
		parent::breadcrumbs($breadcrumbs);
		$data = array();
		$data['karyawan'] = $this->produksiModel->getKaryawan();
		$data['departemen'] = $this->produksiModel->getDepartemen();
		$data['item_list'] = $this->produksiModel->getAllItem();
		parent::viewData($data);

		parent::viewPekerjaBorongan();


	}

	public function ajax_list()
	{
		parent::checkLoginUser(); // user login autentic checking

		if ($this->isPost()) {
			$data = array();

			$orderBy = array(null,null,"tanggal","nama","departemen","jumlah","harga","pendapatan");
			$search = array("nama","departemen","tanggal");

			$result = $this->produksiModel->findDataTable($orderBy,$search);
			foreach ($result as $item) {

				$item->harga = "Rp.".number_format($item->harga,0,",",".");
				$item->pendapatan = "Rp.".number_format($item->pendapatan,0,",",".");
				$item->tanggal = date_ind("d M Y",$item->tanggal);
				$btnAction = '<button class="btn btn-warning btn-outline-warning btn-mini" onclick="btnEdit('.$item->id_produksi.')"><i class="fa fa-pencil-square-o"></i>Edit</button>';
				$btnAction .= '&nbsp;&nbsp;&nbsp;<button class="btn btn-danger btn-outline-danger btn-mini" onclick="btnDelete('.$item->id_produksi.')"><i class="fa fa-trash-o"></i>Hapus</button>';

				$item->button_action = $btnAction;
				$data[] = $item;
			}
			return $this->produksiModel->findDataTableOutput($data,$search);
		}
	}

	public function add()
	{
		parent::checkLoginUser(); // user login autentic checking

		if ($this->isPost()) {
			$tanggal = $this->input->post("tanggal");
			$karyawan = $this->input->post('karyawan');
			$id_item = $this->input->post('itemId');
			$harga = $this->input->post('total');
			$jumlah = $this->input->post("qty");
			$item = $this->input->post("item");
			$this->form_validation->set_rules('tanggal', 'Tanggal', 'trim|required');
			$this->form_validation->set_rules('karyawan', 'Karyawan', 'trim|required');
			// $this->form_validation->set_rules('jumlah', 'Jumlah', 'trim|required');
			$data = array();
			$i = 0;
			foreach ($jumlah as $val) {
				if (intval($val) == 0) {
					$i++;
					continue;
				}
				$harga[$i] = explode('.',$harga[$i]);
				$harga[$i] = implode("",$harga[$i]);
				$data[] = array(
													'tanggal' => $tanggal,
													'id_pekerja' => $karyawan,
													'id_departemen' => $id_item[$i],
													'jumlah' => $jumlah[$i],
													'pendapatan' => $harga[$i]
											 );
				$i++;
			}

			if ($this->form_validation->run() == TRUE) {

				$insert = $this->produksiModel->insert($data);
				if ($insert) {
					$this->response->status = true;
					$this->response->message = alertSuccess("Berhasil tambah data produksi Karyawan.");
				} else {
					$this->response->message = alertDanger("Gagal tambah data produksi Karyawan.");
				}

			} else {
				// $this->response->message = validation_errors('<span style="color:red;">', '</span><br>');
				$this->response->error = array(
									"karyawan"	=> form_error("karyawan",'<span style="color:red;">','</span>'),
									"tanggal"	=> form_error("tanggal",'<span style="color:red;">','</span>'),
									// "jumlah"	=> form_error("jumlah",'<span style="color:red;">','</span>'),
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
				$dataKaryawan = $this->produksiModel->getAllKaryawanAjax();
			} else {
				$searchTerm = $this->input->post("searchTerm");
				$dataKaryawan = $this->produksiModel->getAllKaryawanAjaxSearch($searchTerm);
			}

			$data = array();
			// $data[] = array("id"=>"0", "text"=> "--Pilih Karyawan--");
			foreach ($dataKaryawan as $val) {
				// $row = array();
				$data[] = array("id"=>$val->id_pekerja, "text"=>$val->nama);
				// $data[] = $row;
			}

			parent::json($data);
		}

	}

	public function allDepartemenAjax()
	{
		parent::checkLoginUser(); // user login autentic checking

		if ($this->isPost()) {
			if (!isset($_POST["searchTerm"])) {
				$dataDepartemen = $this->produksiModel->getAllDepartemenAjax();
			} else {
				$searchTerm = $this->input->post("searchTerm");
				$dataDepartemen = $this->produksiModel->getAllDepartemenAjaxSearch($searchTerm);
			}

			$data = array();
			// $data[] = array("id"=>"0", "text"=> "--Pilih Karyawan--");
			foreach ($dataDepartemen as $val) {
				// $row = array();
				$data[] = array("id"=>$val->id_departemen, "text"=>$val->departemen);
				// $data[] = $row;
			}

			parent::json($data);
		}

	}

	public function idKaryawan($id)
	{
		parent::checkLoginUser(); // user login autentic checking

		if ($this->isPost()) {
			$data = $this->produksiModel->getIdKaryawan($id);
			if ($data) {
				$this->response->status = true;
				$this->response->message = "data karyawan by id";
				$this->response->data = $data;
			}
		}
		parent::json();
	}

	public function idDepartemen($id_departemen)
	{
		parent::checkLoginUser(); // user login autentic checking

		if ($this->isPost()) {
			$data = $this->produksiModel->getIdDepartemen($id_departemen);
			if ($data) {
				$this->response->status = true;
				$this->response->message = "data item by id";
				$this->response->data = $data;
			}
		}
		parent::json();
	}

	public function getNama($id)
	{
		parent::checkLoginUser(); // user login autentic checking

		if ($this->isPost()) {
			$getById = $this->produksiModel->getDataKaryawanSelect($id);
			if ($getById) {
				$this->response->status = true;
				$this->response->message = "Data produksi get by id";
				$this->response->data = $getById;
			} else {
				$this->response->message = alertDanger("Data tidak ada.");
			}
		}
		parent::json();
	}

	public function getDepartemen($id)
	{
		parent::checkLoginUser(); // user login autentic checking

		if ($this->isPost()) {
			$getById = $this->produksiModel->getDepartemenSelect($id);
			if ($getById) {
				$this->response->status = true;
				$this->response->message = "Data produksi get by id";
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
			$getById = $this->produksiModel->getById($id);
			if ($getById) {
				$this->response->status = true;
				$this->response->message = "Data produksi get by id";
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
			$getById = $this->produksiModel->getById($id);
			$karyawan = $this->input->post('karyawan');
			$harga = $this->input->post('harga');
			$tanggal = $this->input->post("tanggal");
			$jumlah = $this->input->post("jumlah");
			$departemen = $this->input->post("departemen");
			$this->form_validation->set_rules('karyawan', 'Karyawan', 'trim|required');
			$this->form_validation->set_rules('jumlah', 'Jumlah', 'trim|required');
			$getKaryawan = $this->produksiModel->getById($karyawan);
			if ($this->form_validation->run() == TRUE) {
				$pendapatan = $harga * $jumlah;
				$data = array(

								"id_pekerja" => $karyawan,
								"tanggal"	=>	$tanggal,
								"harga"=>	$harga,
								"jumlah"=>	$jumlah,
								"id_departemen"	=>	$departemen,
								"pendapatan"=>$pendapatan
							);
				$update = $this->produksiModel->update($id,$data);
				if ($update) {
					$this->response->status = true;
					$this->response->message = alertSuccess("Berhasil update data produksi karyawan.");
				} else {
					$this->response->message = alertDanger("Gagal update data produksi karyawan.");
				}
			} else {
				// $this->response->message = validation_errors('<span style="color:red;">', '</span><br>');
				$this->response->error = array(
									"karyawan"	=> form_error("karyawan",'<span style="color:red;">','</span>'),
									"jumlah"	=> form_error("jumlah",'<span style="color:red;">','</span>'),
								);
			}
		}
		parent::json();
	}

	public function delete($id)
	{
		parent::checkLoginUser(); // user login autentic checking

		if ($this->isPost()) {
			$getById = $this->produksiModel->getById($id);
			if ($getById) {
				$delete = $this->produksiModel->delete($id);
				if ($delete) {
					$this->response->status = true;
					$this->response->message = alertSuccess("Data Pekerja Borongan Berhasil di hapus.");
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

/* End of file produksi.php */
/* Location: ./application/controllers/aktivitas/produksi.php */
