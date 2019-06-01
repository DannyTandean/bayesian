<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Reward extends MY_Controller {

	public function __construct()
	{
		parent::__construct();
		$this->load->model('rwd/Reward_model',"rewardModel");
		$this->load->model('master/Karyawan_model', "karyawanModel");
		parent::checkLoginOwner();
	}

	public function index()
	{
		parent::checkLoginUser(); // user login autentic checking

		parent::headerTitle("Rewards & Punishment > Rewards Karyawan","Rewards & Punishment ","Rewards Karyawan");
		$breadcrumbs = array(
							"Rewards & Punishment "	=>	site_url('rwd/reward'),
							"Rewards"		=>	"",
						);
		parent::breadcrumbs($breadcrumbs);
		$data = array();
		$data['karyawan'] = $this->rewardModel->getKaryawan();
		parent::viewData($data);

		parent::viewRwd();


	}

	public function ajax_list()
	{
		parent::checkLoginUser(); // user login autentic checking

		if ($this->isPost()) {
			$data = array();

			$orderBy = array(null,null,"status","tanggal","nama","departemen","jabatan","nilai","keteranganr");
			$search = array("nama","departemen","jabatan","status");


			$result = $this->rewardModel->findDataTable($orderBy,$search);
			foreach ($result as $item) {
				if($item->status == "Proses")
				{
					$btnTools = '<button class="btn btn-warning btn-outline-default btn-mini"><i class="fa fa-print"></i>Print</button>';
					$item->status = '<label class="label label-info">'.$item->status.'</label>';
				}
				else if($item->status == "Diterima")
				{
					$btnTools = '<button class="btn btn-default btn-outline-default btn-mini" onclick="btnPrint('.$item->id_rewards.')"><i class="fa fa-print"></i>Print</button>';
					$item->status = '<label class="label label-success">'.$item->status.'</label>';
				}
				else {
					$item->status = '<label class="label label-danger">'.$item->status.'</label>';
					$btnTools = '<button class="btn btn-warning btn-outline-default btn-mini"><i class="fa fa-print"></i>Print</button>';
				}
				$btnAction = '<button class="btn btn-warning  btn-mini" onclick="btnEdit('.$item->id_rewards.')"><i class="fa fa-pencil-square-o"></i>Edit</button>';
				$btnAction .= '&nbsp;&nbsp;&nbsp;<button class="btn btn-danger btn-mini" onclick="btnDelete('.$item->id_rewards.')"><i class="fa fa-trash-o"></i>Hapus</button>';
				$item->nilai = "Rp.".number_format($item->nilai,0,",",".");
				$item->tanggal = date_ind("d M Y",$item->tanggal);
				$item->button_action = $btnAction;
				$data[] = $item;
			}
			return $this->rewardModel->findDataTableOutput($data,$search);
		}
	}

	public function allKaryawanAjax()
	{
		parent::checkLoginUser(); // user login autentic checking

		if ($this->isPost()) {
			if (!isset($_POST["searchTerm"])) {
				$dataKaryawan = $this->rewardModel->getAllKaryawanAjax();
			} else {
				$searchTerm = $this->input->post("searchTerm");
				$dataKaryawan = $this->rewardModel->getAllKaryawanAjaxSearch($searchTerm);
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
			$data = $this->rewardModel->getIdKaryawan($id);
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
			$getById = $this->rewardModel->getDataKaryawanSelect($id);
			if ($getById) {
				$this->response->status = true;
				$this->response->message = "Data reward get by id";
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
			$getById = $this->rewardModel->getById($id);
			if ($getById) {
				$getById->tanggal_indo = date_ind("d M Y", $getById->tanggal);
				$getById->nilai_rp = "Rp.".number_format($getById->nilai,0,",",".");
				if ($getById->foto != "") {
					$getById->foto = base_url("/")."uploads/master/karyawan/orang/".$getById->foto;
				} else {
					$getById->foto = base_url("/")."assets/images/default/no_user.png";
				}
				$this->response->status = true;
				$this->response->message = "Data reward get by id";
				$this->response->data = $getById;
			} else {
				$this->response->message = alertDanger("Data tidak ada.");
			}
		}
		parent::json();
	}

	public function add()
	{
		parent::checkLoginUser(); // user login autentic checking

		if ($this->isPost()) {
			$tanggal = $this->input->post("tanggal");
			$karyawan = $this->input->post('karyawan');
			$nilai = $this->input->post("nilai");
			$keterangan = $this->input->post("keterangan");
			$this->form_validation->set_rules('tanggal', 'Tanggal', 'trim|required');
			$this->form_validation->set_rules('karyawan', 'Karyawan', 'trim|required');
			$this->form_validation->set_rules('nilai', 'Nilai', 'trim|required');
			$this->form_validation->set_rules('keterangan', 'Keterangan');

			if ($this->form_validation->run() == TRUE) {
				$data = array(
								"id_karyawan" => $karyawan,
								"tanggal"	=>	$tanggal,
								"nilai" => $nilai,
								"keteranganr"=>	$keterangan,
							);
				$dataNotif = array(
											"keterangan"=> 	" Tambah Data Reward Karyawan.",
											"user_id"	=>	$this->user_id,
											"level"		=>	"owner",
											"url_direct"=>	"approvalowner/reward",
										);

				$insert = $this->rewardModel->insert($data,$dataNotif);

				if ($insert) {
					parent::insertNotif($dataNotif);
					parent::sendNotifTopic("owner","Pengajuan Reward","HRD menambah data reward baru","013");
					$this->response->status = true;
					$this->response->message = alertSuccess("Berhasil tambah data Rewards Karyawan.");
				} else {
					$this->response->message = alertDanger("Gagal tambah data Rewards Karyawan.");
				}

			} else {
				// $this->response->message = validation_errors('<span style="color:red;">', '</span><br>');
				$this->response->error = array(
									"karyawan"	=> form_error("karyawan",'<span style="color:red;">','</span>'),
									"tanggal"	=> form_error("tanggal",'<span style="color:red;">','</span>'),
									"nilai"	=> form_error("nilai",'<span style="color:red;">','</span>'),
									"keteranganr"	=> form_error("keterangan",'<span style="color:red;">','</span>'),
								);
			}
		}
		parent::json();
	}


	public function update($id)
	{
		parent::checkLoginUser(); // user login autentic checking

		if ($this->isPost()) {
			$getById = $this->rewardModel->getById($id);
			$karyawan = $this->input->post('karyawan');
			$nilai = $this->input->post("nilai");
			$keterangan = $this->input->post("keterangan");
			$this->form_validation->set_rules('nilai', 'Jumlah Bonus', 'trim|required');
			$this->form_validation->set_rules('keterangan', 'Keterangan', 'trim|required');
			$getKaryawan = $this->karyawanModel->getById($getById->id_karyawan);

			if ($this->form_validation->run() == TRUE) {
				$data = array(
								"id_karyawan" => $karyawan,
								"tanggal"	=>	$getById->tanggal,
								"nilai" => $nilai,
								"keteranganr"=>	$keterangan,
								"updated_at" => date("Y-m-d H:m:s"),
							);
				$dataNotif = array(
											"keterangan"=> 	" Edit/update data Reward karyawan dengan Nama <u>".$getKaryawan->nama."</u> dan No Karyawan : <u>".$getKaryawan->idfp."</u>",
											"user_id"	=>	$this->user_id,
											"level"		=>	"owner",
											"url_direct"=>	"approvalowner/reward",
										);
				$update = $this->rewardModel->update($id,$data,$dataNotif);
				if ($update) {
					parent::insertNotif($dataNotif);
					parent::sendNotifTopic("owner","Informasi","Perubahan data reward ".$getKaryawan->nama,"047");
					$this->response->status = true;
					$this->response->message = alertSuccess("Berhasil update data reward karyawan.");
				} else {
					$this->response->message = alertDanger("Gagal update data reward karyawan.");
				}
			} else {
				// $this->response->message = validation_errors('<span style="color:red;">', '</span><br>');
				$this->response->error = array(
									"karyawan"	=> form_error("karyawan",'<span style="color:red;">','</span>'),
									"tanggal"	=> form_error("tanggal",'<span style="color:red;">','</span>'),
									"nilai"	=> form_error("nilai",'<span style="color:red;">','</span>'),
									"keteranganr"	=> form_error("keterangan",'<span style="color:red;">','</span>'),
								);
			}
		}
		parent::json();
	}

	public function delete($id)
	{
		parent::checkLoginUser(); // user login autentic checking

		if ($this->isPost()) {
			$getById = $this->rewardModel->getById($id);
			$getKaryawan = $this->karyawanModel->getById($getById->id_karyawan);
			if ($getById) {

				$dataNotif = array(
											"keterangan"=> 	" Hapus data Reward karyawan dengan Nama <u>".$getKaryawan->nama."</u> dan No Karyawan : <u>".$getKaryawan->idfp."</u>",
											"user_id"	=>	$this->user_id,
											"level"		=>	"owner",
											"url_direct"=>	"approvalowner/reward",
										);
				$delete = $this->rewardModel->delete($id,$dataNotif);
				if ($delete) {
					parent::insertNotif($dataNotif);
					parent::sendNotifTopic("owner","Informasi","HRD menghapus data reward ".$getKaryawan->nama,"048");
					$this->response->status = true;
					$this->response->message = alertSuccess("Data reward karyawan Berhasil di hapus.");
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

/* End of file Reward.php */
/* Location: ./application/controllers/rwd/Reward.php */
