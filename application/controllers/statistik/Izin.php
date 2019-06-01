<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Izin extends MY_Controller {

	public function __construct()
	{
		parent::__construct();
		$this->load->model('aktivitas/Izin_model',"izinModel");
		$this->load->model('Calendar_model',"calendarModel");

	}

	public function index()
	{
		parent::checkLoginUser(); // user login autentic checking

		parent::headerTitle("Statistik Data > Izin Karyawan","Statistik Data","Izin Karyawan");
		$breadcrumbs = array(
							"Statistik Data"	=>	site_url('statistik/izin'),
							"Izin"		=>	"",
						);
		parent::breadcrumbs($breadcrumbs);
		parent::viewstatistik();


	}

	public function ajax_list($before=null,$after=null)
	{
		parent::checkLoginUser(); // user login autentic checking

		// if ($this->isPost()) {
			$data = array();

			$orderBy = array(null,"tanggal","nama","departemen","jabatan","tgl_izin","akhir_izin","keterangans","lama","status");
			$search = array("nama","departemen","jabatan","status");

			$result = $this->izinModel->findDataTable($orderBy,$search,$after,$before);
			foreach ($result as $item) {
				if($item->status == "Proses")
				{
					
					$item->status = '<label class="label label-info">'.$item->status.'</label>';
				}
				else if($item->status == "Diterima")
				{
					
					$item->status = '<label class="label label-success">'.$item->status.'</label>';
				}
				else {
					$item->status = '<label class="label label-danger">'.$item->status.'</label>';
					
				}
				// $btnAction = '<button class="btn btn-warning  btn-mini" onclick="btnEdit('.$item->id_izin.')"><i class="fa fa-pencil-square-o"></i>Edit</button>';
				// $btnAction .= '&nbsp;&nbsp;&nbsp;<button class="btn btn-danger btn-mini" onclick="btnDelete('.$item->id_izin.')"><i class="fa fa-trash-o"></i>Hapus</button>';
				$item->tanggal = date_ind("d M Y",$item->tanggal);
				$item->tgl_izin = date_ind("d M Y",$item->tgl_izin);
				$item->akhir_izin = date_ind("d M Y",$item->akhir_izin);
				// $item->button_tool = $btnTools;
				// $item->button_action = $btnAction;
				$data[] = $item;
			}
			return $this->izinModel->findDataTableOutput($data,$search,$after,$before);
		// }
	}
	public function ajax_list_after($before,$after)
	{
	 	self::ajax_list($before,$after);
	}

	public function add()
	{
		parent::checkLoginUser(); // user login autentic checking

		if ($this->isPost()) {
			$tanggal = $this->input->post("tanggal");
			$karyawan = $this->input->post('karyawan');
			$mulaiIzin = $this->input->post('mulaiIzin');
			$akhirIzin = $this->input->post('akhirIzin');
			$keterangan = $this->input->post("keterangan");
			$date1=date_create($mulaiIzin);
			$date2=date_create($akhirIzin);
			$lama=date_diff($date1,$date2)->format("%a")+1;
			$this->form_validation->set_rules('tanggal', 'Tanggal', 'trim|required');
			$this->form_validation->set_rules('karyawan', 'Karyawan', 'trim|required');
			$this->form_validation->set_rules('mulaiIzin', 'Mulai Izin', 'trim|required');
			$this->form_validation->set_rules('akhirIzin', 'Akhir Izin', 'trim|required');
			$this->form_validation->set_rules('keterangan', 'Keterangan', 'trim|required');

			if ($this->form_validation->run() == TRUE) {
				$data = array(
								"id_karyawan" => $karyawan,
								"tanggal"	=>	$tanggal,
								"tgl_izin" => $mulaiIzin,
								"akhir_izin" => $akhirIzin,
								"keterangans"=>	$keterangan,
								"lama" => $lama
							);
				$insert = $this->izinModel->insert($data);
				if ($insert) {
					$this->response->status = true;
					$this->response->message = alertSuccess("Berhasil tambah data Izin Karyawan.");
				} else {
					$this->response->message = alertDanger("Gagal tambah data Izin Karyawan.");
				}

			} else {
				// $this->response->message = validation_errors('<span style="color:red;">', '</span><br>');
				$this->response->error = array(
									"karyawan"	=> form_error("karyawan",'<span style="color:red;">','</span>'),
									"tanggal"	=> form_error("tanggal",'<span style="color:red;">','</span>'),
									"mulaiIzin"	=> form_error("mulaiIzin",'<span style="color:red;">','</span>'),
									"akhirIzin"	=> form_error("akhirIzin",'<span style="color:red;">','</span>'),
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
				$dataKaryawan = $this->izinModel->getAllKaryawanAjax();
			} else {
				$searchTerm = $this->input->post("searchTerm");
				$dataKaryawan = $this->izinModel->getAllKaryawanAjaxSearch($searchTerm);
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
			$data = $this->izinModel->getIdKaryawan($id);
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
			$getById = $this->izinModel->getDataKaryawanSelect($id);
			if ($getById) {
				$this->response->status = true;
				$this->response->message = "Data izin get by id";
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
			$getById = $this->izinModel->getById($id);
			if ($getById) {
				if ($getById->foto != "") {
					$getById->foto = base_url("/")."uploads/master/karyawan/orang/".$getById->foto;
				} else {
					$getById->foto = base_url("/")."assets/images/default/no_user.png";
				}
				$this->response->status = true;
				$this->response->message = "Data izin get by id";
				$this->response->data = $getById;
			} else {
				$this->response->message = alertDanger("Data tidak ada.");
			}
		}
		parent::json();
	}

	public function getIdForPrint($id)
	{
		parent::checkLoginUser(); // user login autentic checking

		if ($this->isPost()) {
			$getById = $this->izinModel->getById($id);
			$setting = $this->izinModel->get_setting();
			if ($getById) {
				if ($setting->logo != "") {
					$setting->logo = base_url("/")."uploads/setting/".$setting->logo;
				} else {
					$setting->logo = base_url("/")."assets/images/default/no_file_.png";
				}
				$getById->setting = $setting;
				$getById->tgl_izin = date_ind("d M Y",$getById->tgl_izin);
				$getById->akhir_izin = date_ind("d M Y",$getById->akhir_izin);
				$this->response->status = true;
				$this->response->message = "data karyawan by id";
				$this->response->data = $getById;
			}
			else {
				$this->response->message = alertDanger("Data tidak ada.");
			}
		}
		parent::json();
	}

	public function update($id)
	{
		parent::checkLoginUser(); // user login autentic checking

		if ($this->isPost()) {
			$getById = $this->izinModel->getById($id);
			$karyawan = $this->input->post('karyawan');
			$mulaiIzin = $this->input->post('mulaiIzin');
			$akhirIzin = $this->input->post('akhirIzin');
			$keterangan = $this->input->post("keterangan");
			$date1=date_create($mulaiIzin);
			$date2=date_create($akhirIzin);
			$lama=date_diff($date1,$date2)->format("%a")+1;
			$this->form_validation->set_rules('mulaiIzin', 'Mulai Izin', 'trim|required');
			$this->form_validation->set_rules('akhirIzin', 'Akhir Izin', 'trim|required');
			$this->form_validation->set_rules('keterangan', 'Keterangan', 'trim|required');

			if ($this->form_validation->run() == TRUE) {
				$data = array(
								"id_karyawan" => $karyawan,
								"tanggal"	=>	$getById->tanggal,
								"tgl_izin" => $mulaiIzin,
								"akhir_izin" => $akhirIzin,
								"keterangans"=>	$keterangan,
								"lama" => $lama
							);
				$update = $this->izinModel->update($id,$data);
				if ($update) {
					$this->response->status = true;
					$this->response->message = alertSuccess("Berhasil update data izin karyawan.");
				} else {
					$this->response->message = alertDanger("Gagal update data izin karyawan.");
				}
			} else {
				// $this->response->message = validation_errors('<span style="color:red;">', '</span><br>');
				$this->response->error = array(
									"karyawan"	=> form_error("karyawan",'<span style="color:red;">','</span>'),
									"tanggal"	=> form_error("tanggal",'<span style="color:red;">','</span>'),
									"mulaiIzin"	=> form_error("mulaiIzin",'<span style="color:red;">','</span>'),
									"akhirIzin"	=> form_error("akhirIzin",'<span style="color:red;">','</span>'),
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
			$getById = $this->izinModel->getById($id);
			if ($getById) {
				$delete = $this->izinModel->delete($id);
				if ($delete) {
					$this->response->status = true;
					$this->response->message = alertSuccess("Data izin karyawan Berhasil di hapus.");
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

/* End of file Izin.php */
/* Location: ./application/controllers/statistik/Izin.php */
