<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Absensiextra extends MY_Controller {

	public function __construct()
	{
		parent::__construct();
		$this->load->model('approval/Absensiextra_model', 'absensiModel');
		parent::checkLoginOwner();
	}

	public function index()
	{
		parent::checkLoginUser();; // user login autentic checking

		parent::headerTitle("Approval Data > Absensi Lembur","Approval Data","Absensi Lembur");
		$breadcrumbs = array(
							"Approval"	=>	site_url('approval/absensiextra'),
							"Absensi Lembur"	=>	"",
						);
		parent::breadcrumbs($breadcrumbs);
		parent::viewApproval();
	}

	public function ajax_list()
	{
		parent::checkLoginUser(); // user login autentic checking

		if ($this->isPost()) {
			$data = array();

			// $orderBy = array(null,null,"kode_kabag","tanggal","nama","jabatan","masuk","break_out","break_in","keluar");
			// $search = array("kode_kabag","tanggal","nama","jabatan","masuk","break_out","break_in","keluar");

			$orderBy = array(null,null,"kode_kabag","tanggal","nama","jabatan","masuk","keluar");
			$search = array("kode_kabag","tanggal","nama","jabatan","masuk","keluar");

			$result = $this->absensiModel->findDataTable($orderBy,$search,array('absensi_extra.status' => "Proses",'status_kerja' => "aktif"));

			foreach ($result as $item) {

					$btnAction = '&nbsp;&nbsp;&nbsp;<button class="btn btn-success btn-outline-success btn-mini" onclick="btnApproval('.$item->id.')"><i class="fa fa-calendar-o"></i>Terima</button>';
					$btnAction .= '&nbsp;&nbsp;&nbsp;<button class="btn btn-danger btn-outline-danger btn-mini" onclick="btnReject('.$item->id.')"><i class="fa fa-trash-o"></i>Tolak</button>';
					$kabag = $this->absensiModel->getKabag($item->kode_kabag);
					$item->kode_kabag = $kabag->nama;
					$item->tanggal = date_ind("d M Y",$item->tanggal);
					$item->button_action = $btnAction;
					$data[] = $item;

			}
			return $this->absensiModel->findDataTableOutput($data,$search,array('absensi_extra.status' => "Proses",'status_kerja' => "aktif"));
		}
	}

	public function getId($id)
	{
		parent::checkLoginUser(); // user login autentic checking
		if ($this->isPost()) {
			$getData = $this->absensiModel->getById($id);
			if ($getData) {
				if ($getData->foto != "") {
					$getData->foto = base_url("/")."uploads/master/karyawan/orang/".$getData->foto;
				} else {
					$getData->foto = base_url("/")."assets/images/default/no_user.png";
				}

				$this->response->status = true;
				$this->response->message = "Data by id absensi";
				$this->response->data = $getData;
			} else {
				$this->response->message = alertDanger("Data tidak ada.!");
			}
		}
		parent::json();
	}

	public function getIdJadwal($id)
	{
		parent::checkLoginUser(); // user login autentic checking
		if ($this->isPost()) {
			$getData = $this->absensiModel->getJadwalId($id);

			if ($getData) {
				$this->response->status = true;
				$this->response->message = "Data by id temp_absensi";
				$this->response->data = $getData;
			} else {
				$this->response->message = alertDanger("Data tidak ada.!");
			}
		}
		parent::json();
	}

	public function getKabag($kode)
	{
		if ($this->isPost()) {
			$getById = $this->absensiModel->getKabag($kode);
			if ($getById) {
				if ($getById->foto != "") {
					$getById->foto = base_url("/")."uploads/master/karyawan/orang/".$getById->foto;
				} else {
					$getById->foto = base_url("/")."assets/images/default/no_user.png";
				}
				$this->response->status = true;
				$this->response->message = "Data Kabag get by id";
				$this->response->data = $getById;
			} else {
				$this->response->message = alertDanger("Data tidak ada.");
			}
		}
		parent::json();
	}

	public function reject($id)
	{
		parent::checkLoginUser(); // user login autentic checking
		if ($this->isPost()) {
					$update = $this->absensiModel->update($id,array("status" => "Ditolak"));
					if ($update) {
						$this->response->status = true;
						$this->response->message = alertSuccess("Berhasil Tolak absensi lembur.");
					} else {
						$this->response->message = alertDanger("Opps, Gagal Tolak absensi lembur.");
					}
		}
		parent::json();
	}

	public function approval($id)
	{
		parent::checkLoginUser(); // user login autentic checking
		if ($this->isPost()) {
			$update = $this->absensiModel->update($id,array("status" => "Diterima"));
			if ($update) {
				$this->response->status = true;
				$this->response->message = alertSuccess("Berhasil Terima absensi lembur.");
			} else {
				$this->response->message = alertDanger("Opps, Gagal Terima absensi lembur.");
			}
		}
		parent::json();
	}

}

/* End of file Absensitakterduga.php */
/* Location: ./application/controllers/approval/Absensitakterduga.php */
