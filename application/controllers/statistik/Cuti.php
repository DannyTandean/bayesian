<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Cuti extends MY_Controller {

	public function __construct()
	{
		parent::__construct();
		$this->load->model('aktivitas/Cuti_model',"cutiModel");
		$this->load->model('Calendar_model',"calendarModel");
	}

	public function index()
	{
		parent::checkLoginUser(); // user login autentic checking

		parent::headerTitle("Statistik data > Cuti","Statistik Data","Cuti");
		$breadcrumbs = array(
							"Statistik Data"	=>	site_url('statistik/cuti'),
							"Cuti"		=>	"",
						);
		parent::breadcrumbs($breadcrumbs);

		parent::viewStatistik();
	}

	public function ajax_list($before=null,$after=null)
	{
		parent::checkLoginUser(); // user login autentic checking

		// if ($this->isPost()) {
			$data = array();

			$orderBy = array(null,"tanggal","nama","departemen","jabatan","status","sisa_cuti","keterangans","mulai_berlaku","exp_date","lama_cuti","jatah_cuti");
			$search = array("nama","keterangans");
			$jatahCuti = $this->cutiModel->get_setting();

			$result = $this->cutiModel->findDataTable($orderBy,$search,$after,$before);
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
				// $btnAction = '<button class="btn btn-warning btn-outline-warning btn-mini" onclick="btnEdit('.$item->id_cuti.')"><i class="fa fa-pencil-square-o"></i>Edit</button>';
				// $btnAction .= '&nbsp;&nbsp;&nbsp;<button class="btn btn-danger btn-outline-danger btn-mini" onclick="btnDelete('.$item->id_cuti.')"><i class="fa fa-trash-o"></i>Hapus</button>';
				$item->tanggal = date_ind("d M Y",$item->tanggal);
				$item->jatah_cuti = $jatahCuti->jatah_cuti;
				$item->mulai_berlaku = date_ind("d M Y",$item->mulai_berlaku);
				$item->exp_date = date_ind("d M Y",$item->exp_date);
				$data[] = $item;
			}
			return $this->cutiModel->findDataTableOutput($data,$search,$after,$before);
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
			$tanggal = $this->input->post('tanggal');
			$karyawan = $this->input->post('karyawan');
			$mulaiCuti = $this->input->post('mulaiCuti');
			$akhirCuti = $this->input->post('akhirCuti');
			$keterangan = $this->input->post("keterangan");
			$date1=date_create($mulaiCuti);
			$date2=date_create($akhirCuti);
			$lama=date_diff($date1,$date2)->format("%a")+1;
			$this->form_validation->set_rules('karyawan', 'Karyawan', 'trim|required');
			$this->form_validation->set_rules('mulaiCuti', 'Mulai Cuti', 'trim|required');
			$this->form_validation->set_rules('akhirCuti', 'Akhir Cuti', 'trim|required');
			$this->form_validation->set_rules('keterangan', 'keterangan', 'trim|required');
			// will be change after setting table have been created
			$getJatahCuti = $this->cutiModel->get_setting();
			$jatahCuti = $getJatahCuti->jatah_cuti;

			$data1 = $this->cutiModel->getIdKaryawan1($karyawan);
			$cutiBersama = $this->calendarModel->getallrow();
			$sisaCuti = $jatahCuti- $cutiBersama - $lama;
			//looping data for searcing sisaCuti
			foreach ($data1 as $item) {
								$sisaCuti -= $item->lama_cuti;
							}


			if ($this->form_validation->run() == TRUE) {
				$data = array(
								"tanggal"	=>	date("Y-m-d"),
								"id_karyawan" => $karyawan,
								"sisa_cuti" => $sisaCuti,
								"mulai_berlaku" => $mulaiCuti,
								"exp_date" => $akhirCuti,
								"lama_cuti " => $lama,
								"keterangans"=>	$keterangan
							);
					if($sisaCuti < 0 )
					{
						$this->response->status = false;
						$this->response->message = alertDanger("Jatah Cuti telah digunakan sampai habis.");
					}
					else {
						$insert = $this->cutiModel->insert($data);
						if ($insert) {
							$this->response->status = true;
							$this->response->message = alertSuccess("Berhasil tambah data Statistik Cuti.");
						} else {
							$this->response->message = alertDanger("Gagal tambah data Statistik Cuti.");
						}
					}
			} else {
				// $this->response->message = validation_errors('<span style="color:red;">', '</span><br>');
				$this->response->error = array(
									"karyawan"	=> form_error("karyawan",'<span style="color:red;">','</span>'),
									"mulaiCuti"	=> form_error("mulaiCuti",'<span style="color:red;">','</span>'),
									"akhirCuti"	=> form_error("akhirCuti",'<span style="color:red;">','</span>'),
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
			$getById = $this->cutiModel->getById($id);
			$cutiBersama = $this->calendarModel->getallrow();
			$getJatahCuti = $this->cutiModel->get_setting();
			$jatahCuti = $getJatahCuti->jatah_cuti;

			if ($getById) {
				if ($getById->foto != "") {
					$getById->foto = base_url("/")."uploads/master/karyawan/orang/".$getById->foto;
				} else {
					$getById->foto = base_url("/")."assets/images/default/no_user.png";
				}
				$getById->mulai_berlaku = date_ind("d M Y",$getById->mulai_berlaku);
				$getById->exp_date = date_ind("d M Y",$getById->exp_date);
				$getById->cuti_bersama = $cutiBersama;
				$this->response->status = true;
				$this->response->message = "Data Cuti get by id";
				$this->response->data = $getById;
			} else {
				$this->response->message = alertDanger("Data tidak ada.");
			}
		}
		parent::json();
	}

	public function allKaryawanAjax()
	{
		parent::checkLoginUser(); // user login autentic checking

		if ($this->isPost()) {
			if (!isset($_POST["searchTerm"])) {
				$dataKaryawan = $this->cutiModel->getAllKaryawanAjax();
			} else {
				$searchTerm = $this->input->post("searchTerm");
				$dataKaryawan = $this->cutiModel->getAllKaryawanAjaxSearch($searchTerm);
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
			$data = $this->cutiModel->getIdKaryawan($id);
			$data1 = $this->cutiModel->getIdKaryawan1($id);
			$cutiBersama = $this->calendarModel->getallrow();
			$getJatahCuti = $this->cutiModel->get_setting();
			$jatahCuti = $getJatahCuti->jatah_cuti;
			$sisaCuti = $jatahCuti- $cutiBersama;
			$cutiDiambil = 0;
			if ($data) {
				foreach ($data1 as $item) {
					$sisaCuti -= $item->lama_cuti;
					$cutiDiambil += $item->lama_cuti;
				}
				$data->cuti_bersama = $cutiBersama;
				$data->sisa_cuti = $sisaCuti;
				$data->cuti_diambil = $cutiDiambil;
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

	public function getIdForPrint($id)
	{
		parent::checkLoginUser(); // user login autentic checking

		if ($this->isPost()) {
			$getById = $this->cutiModel->getById($id);
			$setting = $this->cutiModel->get_setting();
			if ($getById) {
				if ($setting->logo != "") {
					$setting->logo = base_url("/")."uploads/setting/".$setting->logo;
				} else {
					$setting->logo = base_url("/")."assets/images/default/no_file_.png";
				}
				$getById->setting = $setting;
				$getById->mulai_berlaku = date_ind("d M Y",$getById->mulai_berlaku);
				$getById->exp_date = date_ind("d M Y",$getById->exp_date);
				$getById->tanggal = date_ind("d M Y",$getById->tanggal);
				$this->response->status = true;
				$this->response->message = "data print by id";
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
			$tanggal = $this->input->post('tanggal');
			$karyawan = $this->input->post('karyawan');
			$mulaiCuti = $this->input->post('mulaiCuti');
			$akhirCuti = $this->input->post('akhirCuti');
			$keterangan = $this->input->post("keterangan");
			$date1=date_create($mulaiCuti);
			$date2=date_create($akhirCuti);
			$lama=date_diff($date1,$date2)->format("%a")+1;
			$this->form_validation->set_rules('karyawan', 'Karyawan', 'trim|required');
			$this->form_validation->set_rules('mulaiCuti', 'Mulai Cuti', 'trim|required');
			$this->form_validation->set_rules('akhirCuti', 'Akhir Cuti', 'trim|required');
			$this->form_validation->set_rules('keterangan', 'keterangan', 'trim|required');
			// will be change after setting table have been created
			$getJatahCuti = $this->cutiModel->get_setting();
			$jatahCuti = $getJatahCuti->jatah_cuti;

			$data1 = $this->cutiModel->getIdKaryawan1($karyawan);
			$cutiBersama = $this->calendarModel->getallrow();
			$sisaCuti = $jatahCuti- $cutiBersama - $lama;
			//looping data for searcing sisaCuti
			foreach ($data1 as $item) {
								$sisaCuti -= $item->lama_cuti;
							}


			if ($this->form_validation->run() == TRUE) {
				$data = array(
								"tanggal"	=>	date("Y-m-d"),
								"id_karyawan" => $karyawan,
								"sisa_cuti" => $sisaCuti,
								"mulai_berlaku" => $mulaiCuti,
								"exp_date" => $akhirCuti,
								"lama_cuti " => $lama,
								"keterangans"=>	$keterangan
							);
							if($sisaCuti <= 0 )
							{
								$this->response->status = false;
								$this->response->message = alertDanger("telah Melebihi batas dari jatah cuti.");
							}
							else {
								$update = $this->cutiModel->update($id,$data);
								if ($update) {
									$this->response->status = true;
									$this->response->message = alertSuccess("Berhasil update data grup.");
								} else {
									$this->response->message = alertDanger("Gagal update data grup.");
								}
							}
			} else {
				// $this->response->message = validation_errors('<span style="color:red;">', '</span><br>');
				$this->response->error = array(
								"karyawan"	=> form_error("karyawan",'<span style="color:red;">','</span>'),
								"mulaiCuti"	=> form_error("mulaiCuti",'<span style="color:red;">','</span>'),
								"akhirCuti"	=> form_error("akhirCuti",'<span style="color:red;">','</span>'),
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
			$getById = $this->cutiModel->getById($id);
			if ($getById) {
				$delete = $this->cutiModel->delete($id);
				if ($delete) {
					$this->response->status = true;
					$this->response->message = alertSuccess("Data grup Berhasil di hapus.");
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

/* End of file Cuti.php */
/* Location: ./application/controllers/statistik/Cuti.php */
