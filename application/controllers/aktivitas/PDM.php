<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class PDM extends MY_Controller {

	public function __construct()
	{
		parent::__construct();
		$this->load->model('approval/Temp_PDM_model',"tempPDMModel");
		$this->load->model('aktivitas/PDM_model',"PDMModel");
		$this->load->model('master/Karyawan_model', "karyawanModel");
		parent::checkLoginOwner();

	}

	public function index()
	{
		parent::checkLoginUser();// user login autentic checking

		parent::headerTitle("Aktivitas Data > Promosi Demosi Mutasi Karyawan","Aktivitas Data","Promosi Demosi Mutasi Karyawan");
		$breadcrumbs = array(
							"Aktivitas"	=>	site_url('aktivitas/PDM'),
							"PDM"		=>	"",
						);
		parent::breadcrumbs($breadcrumbs);
		$data = array();
		$data['karyawan'] = $this->PDMModel->getKaryawan();
		$data['cabang'] = $this->PDMModel->getAllCabangAjax();
		$data['departemen'] = $this->PDMModel->getAllDepartemenAjax();
		$data['jabatan'] = $this->PDMModel->getAllJabatanAjax();
		$data['golongan'] = $this->PDMModel->getAllGolonganAjax();
		$data['grup'] = $this->PDMModel->getAllGrupAjax();

		parent::viewData($data);

		parent::viewAktivitas();


	}

	public function ajax_list()
	{
		parent::checkLoginUser(); // user login autentic checking

		if ($this->isPost()) {
			$data = array();

			$orderBy = array(null,null,"judul","tanggal","nama","cabang","departemen","jabatan","shift","grup","golongan","keterangans");
			$search = array("nama","departemen","jabatan");

			$result = $this->PDMModel->findDataTable($orderBy,$search);
			foreach ($result as $item) {
				$btnAction = '<button class="btn btn-warning btn-mini" onclick="btnEdit('.$item->id_promosi.')"><i class="fa fa-pencil-square-o"></i>Edit</button>';
				$btnAction .= '&nbsp;&nbsp;&nbsp;<button class="btn btn-danger btn-mini" onclick="btnDelete('.$item->id_promosi.')"><i class="fa fa-trash-o"></i>Hapus</button>';
				$item->tanggal = date_ind("d M Y",$item->tanggal);
				$item->button_action = $btnAction;
				$data[] = $item;
			}
			return $this->PDMModel->findDataTableOutput($data,$search);
		}
	}

	public function add()
	{
		parent::checkLoginUser(); // user login autentic checking

		if ($this->isPost()) {

			$judul = $this->input->post("judul");
			$tanggal = $this->input->post("tanggal");
			$karyawan = $this->input->post('karyawan');
			$cabang = $this->input->post('cabang1');
			$departemen = $this->input->post('departemen1');
			$jabatan = $this->input->post('jabatan1');
			$golongan = $this->input->post('golongan1');
			$grup = $this->input->post('grup1');
			$keterangan = $this->input->post("keterangan");
			$this->form_validation->set_rules('tanggal', 'Tanggal', 'trim|required');
			$this->form_validation->set_rules('judul', 'Judul', 'trim|required');
			$this->form_validation->set_rules('karyawan', 'Karyawan', 'trim|required');
			$this->form_validation->set_rules('keterangan', 'Keterangan', 'trim|required');

			if ($this->form_validation->run() == TRUE) {
				$data = array(
								"judul"	=>	$judul,
								"id_karyawan" => $karyawan,
								"id_cabang1" => $cabang,
								"id_departemen1" => $departemen,
								"id_jabatan1" => $jabatan,
								"id_golongan1" => $golongan,
								"id_grup1" => $grup,
								"tanggal"	=>	$tanggal,
								"keterangans"=>	$keterangan,
								"status_info" => "Tambah"

							);
				$dataNotif = array(
											"keterangan"=> 	" Tambah Data Promosi Demosi Mutasi baru.",
											"user_id"	=>	$this->user_id,
											"level"		=>	"owner",
											"url_direct"=>	"approvalowner/PDM",
										);
				$insert = $this->tempPDMModel->insert($data,$dataNotif);
				if ($insert) {

					parent::insertNotif($dataNotif);
					parent::sendNotifTopic("owner","Pengajuan Promosi/Demosi/Mutasi","HRD mengajukan data Promosi/Demosi/Mutasi baru","011");
					$this->response->status = true;
					$this->response->message = "<span style='color:green;'>Sedang Proses tambah data karyawan.</span>";
				} else {
					$this->response->message ="<span style='color:red;'>Gagal menambah data karyawan.</span>";
				}

			} else {
				// $this->response->message = validation_errors('<span style="color:red;">', '</span><br>');
				$this->response->error = array(
									"judul"	=> form_error("judul",'<span style="color:red;">','</span>'),
									"karyawan"	=> form_error("karyawan",'<span style="color:red;">','</span>'),
									"tanggal"	=> form_error("tanggal",'<span style="color:red;">','</span>'),
									"keterangans"	=> form_error("keterangan",'<span style="color:red;">','</span>'),
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
				$dataKaryawan = $this->PDMModel->getAllKaryawanAjax();
			} else {
				$searchTerm = $this->input->post("searchTerm");
				$dataKaryawan = $this->PDMModel->getAllKaryawanAjaxSearch($searchTerm);
			}

			$data = array();
			// $data[] = array("id"=>"0", "text"=> "--Pilih Karyawan--");
			foreach ($dataKaryawan as $val) {
				// $row = array();
				$data[] = array("id"=>$val->id, "text"=>$val->nama);
				// $data[] = $row;
			}

			parent::json($data);
		}

	}

	public function allCabangAjax()
	{
		parent::checkLoginUser(); // user login autentic checking

		if ($this->isPost()) {
			if (!isset($_POST["searchTerm"])) {
				$dataCabang = $this->PDMModel->getAllCabangAjax();
			} else {
				$searchTerm = $this->input->post("searchTerm");
				$dataCabang = $this->PDMModel->getAllCabangAjaxSearch($searchTerm);
			}

			$data = array();
			// $data[] = array("id"=>"0", "text"=> "--Pilih Karyawan--");
			foreach ($dataCabang as $val) {
				// $row = array();
				$data[] = array("id"=>$val->id_cabang, "text"=>$val->cabang);
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
				$dataDepartemen = $this->PDMModel->getAllDepartemenAjax();
			} else {
				$searchTerm = $this->input->post("searchTerm");
				$dataDepartemen = $this->PDMModel->getAllDepartemenAjaxSearch($searchTerm);
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

	public function allJabatanAjax()
	{
		parent::checkLoginUser(); // user login autentic checking

		if ($this->isPost()) {
			if (!isset($_POST["searchTerm"])) {
				$dataJabatan = $this->PDMModel->getAllJabatanAjax();
			} else {
				$searchTerm = $this->input->post("searchTerm");
				$dataJabatan = $this->PDMModel->getAllJabatanAjaxSearch($searchTerm);
			}

			$data = array();
			// $data[] = array("id"=>"0", "text"=> "--Pilih Karyawan--");
			foreach ($dataJabatan as $val) {
				// $row = array();
				$data[] = array("id"=>$val->id_jabatan, "text"=>$val->jabatan);
				// $data[] = $row;
			}

			parent::json($data);
		}

	}

	public function allGolonganAjax()
	{
		parent::checkLoginUser(); // user login autentic checking

		if ($this->isPost()) {
			if (!isset($_POST["searchTerm"])) {
				$dataGolongan = $this->PDMModel->getAllGolonganAjax();
			} else {
				$searchTerm = $this->input->post("searchTerm");
				$dataGolongan = $this->PDMModel->getAllGolonganAjaxSearch($searchTerm);
			}

			$data = array();
			// $data[] = array("id"=>"0", "text"=> "--Pilih Karyawan--");
			foreach ($dataGolongan as $val) {
				// $row = array();
				$data[] = array("id"=>$val->id_golongan, "text"=>$val->golongan);
				// $data[] = $row;
			}

			parent::json($data);
		}

	}




	public function allGrupAjax()
	{
		parent::checkLoginUser(); // user login autentic checking

		if ($this->isPost()) {
			if (!isset($_POST["searchTerm"])) {
				$dataGrup = $this->PDMModel->getAllGrupAjax();
			} else {
				$searchTerm = $this->input->post("searchTerm");
				$dataGrup = $this->PDMModel->getAllGrupAjaxSearch($searchTerm);
			}

			$data = array();
			// $data[] = array("id"=>"0", "text"=> "--Pilih Karyawan--");
			foreach ($dataGrup as $val) {
				// $row = array();
				$data[] = array("id"=>$val->id_grup, "text"=>$val->grup);
				// $data[] = $row;
			}

			parent::json($data);
		}

	}


	public function idKaryawan($id)
	{
		parent::checkLoginUser(); // user login autentic checking

		if ($this->isPost()) {
			$data = $this->PDMModel->getIdKaryawan($id);
			$cabang = $this->PDMModel->getIdCabang($data->cabang);
			$departemen = $this->PDMModel->getIdDepartemen($data->departemen);
			$jabatan = $this->PDMModel->getIdJabatan($data->jabatan);
			$golongan = $this->PDMModel->getIdGolongan($data->golongan);
			$grup = $this->PDMModel->getIdGrup($data->grup);
			if ($data) {
				if ($data->foto != "") {
					$data->foto = base_url("/")."uploads/master/karyawan/orang/".$data->foto;
				} else {
					$data->foto = base_url("/")."assets/images/default/no_user.png";
				}

				$data->id_cabang = $cabang;
				$data->id_departemen = $departemen;
				$data->id_jabatan = $jabatan;
				$data->id_golongan = $golongan;
				$data->id_grup = $grup;
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
			$getById = $this->PDMModel->getDataKaryawanSelect($id);
			if ($getById) {
				$this->response->status = true;
				$this->response->message = "Data promosi get by id";
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
			$getById = $this->PDMModel->getById($id);

			if ($getById) {
				if ($getById->foto != "") {
					$getById->foto = base_url("/")."uploads/master/karyawan/orang/".$getById->foto;
				} else {
					$getById->foto = base_url("/")."assets/images/default/no_user.png";
				}

				$this->response->status = true;
				$this->response->message = "Data promosi get by id";
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
			$idPromosi = $this->input->post('idPromosi');
			$tanggal = $this->input->post("tanggal");
			$karyawan = $this->input->post("karyawan");
			$judul = $this->input->post("judul");
			$cabang= $this->input->post('cabang1');
			$departemen = $this->input->post('departemen1');
			$jabatan = $this->input->post('jabatan1');
			$golongan = $this->input->post('golongan1');
			$grup = $this->input->post('grup1');
			$keterangan = $this->input->post('keterangan');
			$this->form_validation->set_rules('judul', 'Judul', 'trim|required');
			$this->form_validation->set_rules('cabang', 'Cabang');
			$this->form_validation->set_rules('departemen', 'Departemen');
			$this->form_validation->set_rules('jabatan', 'Jabatan');
			$this->form_validation->set_rules('golongan', 'Golongan');
			$this->form_validation->set_rules('grup', 'Grup');
			$this->form_validation->set_rules('keterangan', 'Keterangan', 'trim|required');
			$dataTemp = $this->tempPDMModel->getByIdPromosiDetail($id);
			$getById = $this->PDMModel->getById($id);
			$getKaryawan = $this->karyawanModel->getById($getById->id_karyawan);

			if ($this->form_validation->run() == TRUE) {
				$data = array(

								"id_promosi" => $idPromosi,
								"judul"	=>	$judul,
								"id_karyawan" => $karyawan,
								"id_cabang1" => $cabang,
								"id_departemen1" => $departemen,
								"id_jabatan1" => $jabatan,
								"id_golongan1" => $golongan,
								"id_grup1" => $grup,
								"tanggal"	=>	$getById->tanggal,
								"keterangans"=>	$keterangan,
								"status_info" => "Edit"
							);

				$dataNotif = array(
											"keterangan"=> 	" Edit/update data promosi demosi mutasi karyawan dengan Nama <u>".$getKaryawan->nama."</u> dan No Karyawan : <u>".$getKaryawan->idfp."</u>",
											"user_id"	=>	$this->user_id,
											"level"		=>	"owner",
											"url_direct"=>	"approvalowner/PDM",
										);

				$update = $this->tempPDMModel->insert($data,$dataNotif);
				if ($update) {
					parent::sendNotifTopic("Owner","Informasi","Perubahan data Promosi/Demosi/Mutasi dengan Nama ".$getKaryawan->nama,"054");
					parent::insertNotif($dataNotif);
					$this->response->status = true;
					$this->response->message = " <span style='color:green;'>Berhasil update data Promosi Karyawan.</span>";
				} else {
					$this->response->message = "<span style='color:red;'>Gagal update data Promosi Karyawan.</span>";
				}
			} else {
				// $this->response->message = validation_errors('<span style="color:red;">', '</span><br>');
				$this->response->error = array(
									"judul"	=> form_error("judul",'<span style="color:red;">','</span>'),
									"karyawan"	=> form_error("karyawan",'<span style="color:red;">','</span>'),
									"tanggal"	=> form_error("tanggal",'<span style="color:red;">','</span>'),
									"cabang"	=> form_error("cabang",'<span style="color:red;">','</span>'),
									"departemen"	=> form_error("departemen",'<span style="color:red;">','</span>'),
									"jabatan"	=> form_error("jabatan",'<span style="color:red;">','</span>'),
									"golongan"	=> form_error("golongan",'<span style="color:red;">','</span>'),
									"grup"	=> form_error("grup",'<span style="color:red;">','</span>'),
									"keterangans"	=> form_error("keterangan",'<span style="color:red;">','</span>'),
								);
			}
		}
		parent::json();
	}

	public function delete($id)
	{
		parent::checkLoginUser(); // user login autentic checking

		if ($this->isPost()) {
			$getById = $this->PDMModel->getById($id);
			$dataTemp = $this->tempPDMModel->getByIdPromosiDetail($id);
			$getKaryawan = $this->karyawanModel->getById($getById->id_karyawan);

			if ($dataTemp["status"] == "Proses") {
							$this->response->status = false;
							$this->response->message = "<span style='color:red;'>Data karyawan ini sedang di proses.</span>";
					}else {
						if ($getById) {
							// $getById->status_info = "Hapus";
							$data = array(
										"judul"	=>	"Hapus Data",
										"id_promosi" => $getById->id_promosi,
										"id_karyawan" => $getById->id_karyawan,
										"id_cabang1" => $getById->id_cabang,
										"id_departemen1" => $getById->id_departemen,
										"id_jabatan1" => $getById->id_jabatan,
										"id_golongan1" => $getById->id_golongan,
										"id_grup1" => $getById->id_grup,
										"tanggal"	=>	$getById->tanggal,
										"keterangans"=>	$getById->keterangans,
										"status_info" => "Hapus"

									);
							$dataNotif = array(
											"keterangan"=> 	" Hapus data promosi demosi mutasi karyawan dengan Nama <u>".$getKaryawan->nama."</u> dan No Karyawan : <u>".$getKaryawan->idfp."</u>",
											"user_id"	=>	$this->user_id,
											"level"		=>	"owner",
											"url_direct"=>	"approvalowner/PDM",
										);
							$delete = $this->tempPDMModel->insert($data,$dataNotif);
							if ($delete) {
								parent::insertNotif($dataNotif);
								$this->response->status = true;
								$this->response->message = alertWarning("Data karyawan sedang di proses untuk di hapus.");
							} else {
								$this->response->message = alertDanger("Data sudah tidak ada.");
							}
						} else {
							$this->response->message = alertDanger("Data sudah tidak ada.");
						}
					}
		}
		parent::json();
	}

}

/* End of file PDM.php */
/* Location: ./application/controllers/aktivitas/PDM.php */
