<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Absensitdkterjadwal extends MY_Controller {

	public function __construct()
	{
		parent::__construct();
		$this->load->model('approval/Absensitdkterjadwal_model', 'absensiModel');
				$this->load->model('aktivitas/Jadwal_karyawan_model',"JadwalKaryawanModel");
		parent::checkLoginOwner();
	}

	public function index()
	{
		parent::checkLoginUser();; // user login autentic checking

		parent::headerTitle("Approval Data > Absensi Tidak Terjadwal","Approval Data","Absensi Tidak Terjadwal");
		$breadcrumbs = array(
							"Approval"	=>	site_url('approval/absensitdkterjadwal'),
							"Absensi Tidak Terjadwal"	=>	"",
						);
		parent::breadcrumbs($breadcrumbs);
		$data = array();
		$data['jadwal'] = $this->JadwalKaryawanModel->all();
		parent::viewData($data);
		parent::viewApproval();
	}

	public function ajax_list()
	{
		parent::checkLoginUser(); // user login autentic checking

		if ($this->isPost()) {
			$data = array();

			$orderBy = array(null,null,"kabag","tanggal","nama","jabatan","absensi.shift","masuk","break_out","break_in","keluar");
			$search = array("kabag","tanggal","nama","jabatan","absensi.shift","masuk","break_out","break_in","keluar");

			$result = $this->absensiModel->findDataTable($orderBy,$search,array('status' => "Proses",'takterjadwal' => 1 ));

			foreach ($result as $item) {

					$btnAction = '<button class="btn btn-success btn-outline-success btn-mini" onclick="btnApproval('.$item->id_absensi.')"><i class="fa fa-pencil-square-o"></i>Terima</button>';
					$btnAction .= '&nbsp;&nbsp;&nbsp;<button class="btn btn-success btn-outline-success btn-mini" onclick="btnNoJadwal('.$item->id_absensi.')"><i class="fa fa-calendar-o"></i>Terima Tanpa Jadwal</button>';
					$btnAction .= '&nbsp;&nbsp;&nbsp;<button class="btn btn-danger btn-outline-danger btn-mini" onclick="btnReject('.$item->id_absensi.')"><i class="fa fa-trash-o"></i>Tolak</button>';

					$item->tanggal = date_ind("d M Y",$item->tanggal);
					$item->button_action = $btnAction;
					$data[] = $item;

			}
			return $this->absensiModel->findDataTableOutput($data,$search,array('status' => "Proses",'takterjadwal' => 1 ));
		}
	}

	public function getId($id)
	{
		parent::checkLoginUser(); // user login autentic checking
		if ($this->isPost()) {
			$getData = $this->absensiModel->getById($id);
			$jadwal = $this->JadwalKaryawanModel->all($getData->shift == "ya" ? 1 : 0);
			if ($getData) {
				if ($getData->foto != "") {
					$getData->foto = base_url("/")."uploads/master/karyawan/orang/".$getData->foto;
				} else {
					$getData->foto = base_url("/")."assets/images/default/no_user.png";
				}

				$this->response->status = true;
				$this->response->message = "Data by id absensi";
				$this->response->data = $getData;
				$this->response->jadwal = $jadwal;
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

	public function update($id)
	{
		parent::checkLoginUser(); // user login autentic checking
		if ($this->isPost()) {
			$idJadwal = $this->input->post('shift');
			$shift = $this->absensiModel->getJadwal($idJadwal);
			// $getData = $this->absensiModel->getWhere(array("id_absensi"=>$id));
			if ($shift) {
					$data = array(
									"id_jadwal" => $idJadwal,
									"shift" => $shift->nama_jadwal,
									"jam_masuk" => $shift->masuk,
									"jam_keluar" => $shift->keluar,
									"status" => "Diterima"
								);
					$update = $this->absensiModel->update($id,$data);
					if ($update) {
						$this->response->status = true;
						$this->response->message = alertSuccess("Berhasil approval absensi tak terduga.");
					} else {
						$this->response->message = alertDanger("Opps, Gagal approval absensi tak terduga.");
					}

			} else {
				$this->response->message = alertDanger("Data tidak ada.!");
			}
		}
		parent::json();
	}

	public function reject($id)
	{
		parent::checkLoginUser(); // user login autentic checking
		if ($this->isPost()) {
			$idJadwal = $this->input->post('id_jadwal');
			$getId = $this->absensiModel->getJadwal($idJadwal);
			if ($getId) {
					$update = $this->absensiModel->update($id,array("status" => "Ditolak"));
					if ($update) {
						$this->response->status = true;
						$this->response->message = alertSuccess("Berhasil Tolak absensi tak terduga.");
					} else {
						$this->response->message = alertDanger("Opps, Gagal Tolak absensi tak terduga.");
					}
			} else {
				$this->response->message = alertDanger("Data tidak ada.");
			}
		}
		parent::json();
	}

	public function approval($id)
	{
		parent::checkLoginUser(); // user login autentic checking
		if ($this->isPost()) {
			$idJadwal = $this->input->post('id_jadwal');
			$getId = $this->absensiModel->getJadwal($idJadwal);
			if ($getId) {
					$update = $this->absensiModel->update($id,array("status" => "Diterima"));
					if ($update) {
						$this->response->status = true;
						$this->response->message = alertSuccess("Berhasil Terima absensi tak terduga.");
					} else {
						$this->response->message = alertDanger("Opps, Gagal Terima absensi tak terduga.");
					}
			} else {
				$this->response->message = alertDanger("Data tidak ada.");
			}
		}
		parent::json();
	}

}

/* End of file Absensitakterduga.php */
/* Location: ./application/controllers/approval/Absensitakterduga.php */
