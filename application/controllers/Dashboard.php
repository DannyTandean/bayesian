<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Dashboard extends MY_Controller {

	public function __construct()
	{
		parent::__construct();
		$this->load->model('aktivitas/LogAbsensi_model',"absensiModel");

	}

	public function index()
	{
		parent::checkLoginUser(); // check login user

		parent::headerTitle("Dashboard","Dashboard");
		parent::view();
	}

	public function getjadwal()
	{
		parent::checkLoginUser();
		if ($this->isPost()) {
			$jadwalKaryawan = $this->JadwalKaryawanModel->getAllJadwal();
			if ($jadwalKaryawan) {
				$this->response->status = true;
				$this->response->message = alertSuccess("Berhasil get data Jadwal");
				$this->response->data = $jadwalKaryawan;
			}
			else {
				$this->response->message = alertDanger("Data tidak ada");
			}
		}
		parent::json();
	}

}

/* End of file Dashboard.php */
/* Location: ./application/controllers/Dashboard.php */
