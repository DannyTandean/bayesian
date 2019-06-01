<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Penilaian extends MY_Controller {

	public function __construct()
	{
		parent::__construct();
		$this->load->model('aktivitas/Penilaian_model',"penilaianModel");
		$this->load->model('master/Karyawan_model', "karyawanModel");
		$this->load->model('Token_model',"tokenModel");
		parent::checkLoginOwner();

	}

	public function index()
	{
		parent::checkLoginOwner(); // user login autentic checking

		parent::headerTitle("Aktivitas Data > Penilaian Karyawan","Aktivitas Data","Penilaian Karyawan");
		$breadcrumbs = array(
							"Aktivitas Data"	=>	site_url('aktivitas/penilaian'),
							"Penilaian karyawan"		=>	"",
						);
		parent::breadcrumbs($breadcrumbs);
		parent::viewAktivitas();
	}

	public function ajax_list()
	{
		parent::checkLoginUser(); // user login autentic checking

		if ($this->isPost()) {
			$data = array();

			$orderBy = array(null,null,"tanggal_mulai","tanggal_akhir","petugas1","petugas2","penilaian.keterangan");
			$search = array("petugas1","petugas2","penilaian.keterangan");

			$result = $this->penilaianModel->findDataTable($orderBy,$search);

			foreach ($result as $item) {
				if ($item->petugas2 == null) {
					$item->petugas2 = "-";
				}
				$btnAction = '<button class="btn btn-warning btn-warning btn-mini" onclick="btnEdit('.$item->id_penilaian.')"><i class="fa fa-pencil-square-o"></i>Edit</button>';
				$btnAction .= '&nbsp;&nbsp;&nbsp;<button class="btn btn-danger btn-danger btn-mini" onclick="btnDelete('.$item->id_penilaian.')"><i class="fa fa-trash-o"></i>Hapus</button>';
				$item->tanggal_mulai = date_ind("d M Y",$item->tanggal_mulai);
				$item->tanggal_akhir = date_ind("d M Y",$item->tanggal_akhir);
				$item->button_action = $btnAction;
				$data[] = $item;
			}
			return $this->penilaianModel->findDataTableOutput($data,$search);
		}
	}

	public function allKaryawanAjax()
	{
		parent::checkLoginUser(); // user login autentic checking

		if ($this->isPost()) {
			if (!isset($_POST["searchTerm"])) {
				$dataKaryawan = $this->penilaianModel->getAllKaryawanAjax();
			} else {
				$searchTerm = $this->input->post("searchTerm");
				$dataKaryawan = $this->penilaianModel->getAllKaryawanAjaxSearch($searchTerm);
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

	public function allKaryawanAjaxGrup()
	{
		parent::checkLoginUser(); // user login autentic checking

		if ($this->isPost()) {
			if (!isset($_POST["searchTerm"])) {
				$dataKaryawan = $this->penilaianModel->getAllKaryawanAjaxGrup();
			} else {
				$searchTerm = $this->input->post("searchTerm");
				$dataKaryawan = $this->penilaianModel->getAllKaryawanAjaxSearchGrup($searchTerm);
			}

			$data = array();
			// $data[] = array("id"=>"0", "text"=> "--Pilih Karyawan--");
			foreach ($dataKaryawan as $val) {
				// $row = array();
				$data[] = array("id"=>$val->id_grup, "text"=>$val->grup);
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

	public function add()
	{
		parent::checkLoginUser(); // user login autentic checking

		if ($this->isPost()) {
			$tanggalMulai = $this->input->post("tanggal_mulai");
			$tanggalAkhir = $this->input->post('tanggal_akhir');
			$opsiPetugas = $this->input->post('opsi_petugas');
			$petugas1 = $this->input->post('karyawan');
			$petugas2 = $this->input->post('karyawan1');
			$keterangan = $this->input->post('keterangan');
			$grup = $this->input->post('grup');
			$periode = $this->input->post('periode');

			$namaPetugas2Var = null;
			$this->form_validation->set_rules('keterangan', 'Keterangan', 'trim|required');
			if ($opsiPetugas == 1) {
				$this->form_validation->set_rules('karyawan1', 'Petugas 2', 'trim|required');
				$namaPetugas2 = $this->penilaianModel->getKaryawanNama($petugas2);
				$namaPetugas2Var = $namaPetugas2->nama;
			}

			if ($this->form_validation->run() == TRUE) {
				$namaPetugas1 = $this->penilaianModel->getKaryawanNama($petugas1);
				$nama = $namaPetugas1->nama;
				$validasiGrup = $this->penilaianModel->validasiGrup($tanggalMulai,$tanggalAkhir,$grup);
				if (sizeof($validasiGrup) > 0) {
					$this->response->message = alertDanger("Grup yang dipilih sedang melakukan penilaian.");
				}
				else {
					$data = array(
									"periode" => $periode,
									"tanggal_mulai"	=>	$tanggalMulai,
									"tanggal_akhir" => $tanggalAkhir,
									"opsi_petugas" => $opsiPetugas,
									"id_petugas1" => $petugas1,
									"id_petugas2" => $petugas2,
									"petugas1" => $nama,
									"petugas2" => $namaPetugas2Var,
									"id_grup" => $grup,
									"keterangan"=>	$keterangan,
								);

					$dataToken = $this->tokenModel->getByToken("kabag");
					$insert = $this->penilaianModel->insert($data);
					if ($insert) {
						$this->response->status = true;
						$this->response->message = alertSuccess("Berhasil tambah data penilaian Karyawan.");
					} else {
						$this->response->message = alertDanger("Gagal tambah data penilaian Karyawan.");
					}
				}
			} else {
				// $this->response->message = validation_errors('<span style="color:red;">', '</span><br>');
				$this->response->error = array(
									"keterangan"	=> form_error("keterangan",'<span style="color:red;">','</span>'),
									"karyawan1"	=> form_error("petguas2",'<span style="color:red;">','</span>'),
								);
			}
		}
		parent::json();
	}

	public function getNama($id)
	{
		parent::checkLoginUser(); // user login autentic checking

		if ($this->isPost()) {
			$getById = $this->penilaianModel->getDataKaryawanSelect($id);
			if ($getById) {
				$this->response->status = true;
				$this->response->message = "Data sakit get by id";
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
			$getById = $this->penilaianModel->getById($id);
			if ($getById) {
				$this->response->status = true;
				$this->response->message = "Data penilaian get by id";
				$this->response->data = $getById;
			} else {
				$this->response->message = alertDanger("Data penilaian tidak ada.");
			}
		}
		parent::json();
	}

	public function update($id)
	{
		parent::checkLoginUser(); // user login autentic checking

		if ($this->isPost()) {
			$tanggalMulai = $this->input->post("tanggal_mulai");
			$tanggalAkhir = $this->input->post('tanggal_akhir');
			$opsiPetugas = $this->input->post('opsi_petugas');
			$petugas1 = $this->input->post('karyawan');
			$petugas2 = $this->input->post('karyawan1');
			$keterangan = $this->input->post('keterangan');
			$grup = $this->input->post('grup');
			$periode = $this->input->post('periode');

			$namaPetugas2Var = null;
			$this->form_validation->set_rules('keterangan', 'Keterangan', 'trim|required');
			if ($opsiPetugas == 1) {
				$this->form_validation->set_rules('karyawan1', 'Petugas 2', 'trim|required');
				$namaPetugas2 = $this->penilaianModel->getKaryawanNama($petugas2);
				$namaPetugas2Var = $namaPetugas2->nama;
			}

			if ($this->form_validation->run() == TRUE) {
				$namaPetugas1 = $this->penilaianModel->getKaryawanNama($petugas1);
				$nama = $namaPetugas1->nama;
				$data = array(
								"periode" => $periode,
								"tanggal_mulai"	=>	$tanggalMulai,
								"tanggal_akhir" => $tanggalAkhir,
								"opsi_petugas" => $opsiPetugas,
								"id_petugas1" => $petugas1,
								"id_petugas2" => $petugas2,
								"petugas1" => $nama,
								"petugas2" => $namaPetugas2Var,
								"id_grup" => $grup,
								"keterangan"=>	$keterangan,
							);

				$dataToken = $this->tokenModel->getByToken("kabag");
				$update = $this->penilaianModel->update($id,$data);
				if ($update) {
					$this->response->status = true;
					$this->response->message = alertSuccess("Berhasil tambah data penilaian Karyawan.");
				} else {
					$this->response->message = alertDanger("Gagal tambah data penilaian Karyawan.");
				}

			} else {
				// $this->response->message = validation_errors('<span style="color:red;">', '</span><br>');
				$this->response->error = array(
									"keterangan"	=> form_error("keterangan",'<span style="color:red;">','</span>'),
									"karyawan1"	=> form_error("petguas2",'<span style="color:red;">','</span>'),
								);
			}
		}
		parent::json();
	}

	public function delete($id)
	{
		parent::checkLoginUser(); // user login autentic checking

		if ($this->isPost()) {

				$delete = $this->penilaianModel->delete($id);
				if ($delete) {
					$this->response->status = true;
					$this->response->message = alertSuccess("Data sakit karyawan Berhasil di hapus.");
				} else {
					$this->response->message = alertDanger("Data sudah tidak ada.");
				}
		}
		parent::json();
	}

}

/* End of file Penilaian.php */
/* Location: ./application/controllers/aktivitas/Penilaian.php */
