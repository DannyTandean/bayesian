<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class PDM extends MY_Controller {

	public function __construct()
	{
		parent::__construct();
		$this->load->model('approval/Temp_PDM_model',"tempPDMModel");
		$this->load->model('aktivitas/PDM_model',"PDMModel");
		$this->load->model('master/Golongan_model',"golonganModel");
		$this->load->model('master/Karyawan_model', "karyawanModel");
		$this->load->model('Token_model',"tokenModel");
		parent::checkLoginHRD();
	}

	public function index()
	{
		parent::checkLoginUser(); // user login autentic checking

		parent::headerTitle("Approval Owner > Promosi Demosi Mutasi","Approval Data","Promosi Demosi Mutasi");
		$breadcrumbs = array(
							"Approval Data"	=>	site_url('approvalowner/PDM'),
							"PDM"		=>	"",
						);
		parent::breadcrumbs($breadcrumbs);

		parent::viewApprovalOwner();
	}

	public function ajax_list()
	{
		parent::checkLoginUser(); // user login autentic checking

		if ($this->isPost()) {
			$data = array();

			$orderBy = array(null,null,null,"nama","id_cabang1","id_departemen1","id_jabatan1","id_golongan1","id_grup1","keterangans","status");
			$search = array("nama","id_departemen1","id_jabatan1","status");

			$btnTools = "";
			$result = $this->tempPDMModel->findDataTable($orderBy,$search);
			foreach ($result as $item) {
				$item->id_cabang2 = $this->tempPDMModel->cabang($item->id_cabang1);
				$item->id_departemen2 = $this->tempPDMModel->departemen($item->id_departemen1);
				$item->id_jabatan2 = $this->tempPDMModel->jabatan($item->id_jabatan1);
				$item->id_golongan2 = $this->tempPDMModel->golongan($item->id_golongan1);
				$item->id_grup2 = $this->tempPDMModel->grup($item->id_grup1);
				if ($item->status == "Diterima") {
					$btnAction = 'Sudah di Terima';

				}else if($item->status == "Ditolak"){
					$btnAction = 'Sudah di Tolak';
				}
				if($item->status == "Proses")
				{
					$item->status = '<label class="label label-info">'.$item->status.'</label>';
					$btnAction = '<button class="btn btn-success btn-outline-success btn-mini" onclick="btnApproval('.$item->temp_id_promosi.')"><i class="fa fa-check-square"></i>Terima</button>';
					$btnAction .= '&nbsp;&nbsp;&nbsp;<button class="btn btn-danger btn-outline-danger btn-mini" onclick="btnReject('.$item->temp_id_promosi.')"><i class="fa fa-ban"></i>Tolak</button>';
				}
				else if($item->status == "Diterima")
				{
					$item->status = '<label class="label label-success">'.$item->status.'</label>';
				}else {
					$item->status =  '<label class="label label-danger">'.$item->status.'</label>';
				}

				if($item->status_info == "Tambah"){
					$item->status_info = '<label class="label label-info">'.$item->status_info.'</label>';
					$btnTools = '<button class="btn btn-primary btn-outline-primary btn-mini" onclick="btnDetail('.$item->temp_id_promosi.')" disabled><i class="fa fa-list"></i>Details</button>';

				}else if ($item->status_info == "Edit") {
					$item->status_info = '<label class="label label-warning">'.$item->status_info.'</label>';
					$btnTools = '<button class="btn btn-primary btn-outline-primary btn-mini" onclick="btnDetail('.$item->temp_id_promosi.')"><i class="fa fa-list"></i>Details</button>';

				}else {
					$item->status_info = '<label class="label label-danger">'.$item->status_info.'</label>';
					$btnTools = '<button class="btn btn-primary btn-outline-primary btn-mini" onclick="btnDetail('.$item->temp_id_promosi.')" disabled><i class="fa fa-list"></i>Details</button>';
				}



				$item->create_at = date_ind("d M Y H:i:s",$item->create_at);
				$item->button_tools = $btnTools;
				$item->button_action = $btnAction;
				$data[] = $item;
			}
			return $this->tempPDMModel->findDataTableOutput($data,$search);
		}
	}


	public function getId($id)
	{
		parent::checkLoginUser(); // user login autentic checking

		if ($this->isPost()) {
			$getById = $this->tempPDMModel->getById($id);
			if ($getById) {
				if ($getById->foto != "") {
					$getById->foto = base_url("uploads/master/karyawan/orang/".$getById->foto);
				}
				else{
					$getById->foto = base_url("assets/images/default/no_user.png");
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

	public function getId2Model($id)
	{
		// parent::checkLoginUser(); // user login autentic checking

		if ($this->isPost()) {
			$dataTemp = $this->tempPDMModel->getByIdDetail1($id);
			$getById = $this->tempPDMModel->getById($id);
			$dataMaster = $this->PDMModel->getByIdDetail1($dataTemp["id_promosi"]);

			$data = array();

			if($dataTemp){
				foreach ($dataTemp as $key => $value) {
					if ($dataTemp[$key] == $dataMaster[$key]) {
						$data[$key] = " <span> ".$dataMaster[$key]." &nbsp;&nbsp;&nbsp;<i class='fa fa-arrow-circle-right'></i>&nbsp;&nbsp;&nbsp;".$value."</span>";
						// $data[$key] = $value;
					}
					else {
						if(in_array($value,$dataTemp)){
								$data[$key] = "<span style='color:blue;'>".$dataMaster[$key]."&nbsp;&nbsp;&nbsp;<i class='fa fa-arrow-circle-right'></i>&nbsp;&nbsp;&nbsp; ".$value."</span>";
						}
					}
				}
				if ($getById->foto != "") {
					$getById->foto = base_url("uploads/master/karyawan/orang/".$getById->foto);
				}
				else{
					$getById->foto = base_url("assets/images/default/no_user.png");
				}
				$data["foto"]=$getById->foto;
				/*$data["approve_at"] = $getById->approve_at;*/
				$this->response->status = true;
				$this->response->message = "Data promosi get by id";
				$this->response->data = $data;
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

			$status1 = $this->input->post('status');
			$statusInfo = $this->input->post('statusInfo');
			$idPromosi = $this->input->post('idPromosi');
			$idKaryawan = $this->input->post('idKaryawan');
			$selectData = $this->tempPDMModel->getSelectData($id);
			$getById = $this->tempPDMModel->getById($id);
			$getKaryawan = $this->karyawanModel->getById($getById->id_karyawan);
			$data = array(

								// "id_karyawan" => $idKaryawan,
								"id_cabang" => $selectData->id_cabang1,
								"id_departemen" => $selectData->id_departemen1,
								"id_jabatan" => $selectData->id_jabatan1,
								"id_golongan" => $selectData->id_golongan1,
								"id_grup" => $selectData->id_grup1,

							);
			$data1 = array(

								"id_karyawan" => $idKaryawan,
								"tanggal" => $selectData->tanggal,
								"judul" =>$selectData->judul,
								"keterangans" => $selectData->keterangans

							);

			$status = array(
				'status' => $status1,
				'approve_at' => date("Y-m-d-H:i:s")
			);

			if($status1 == "Diterima"){
				$dataKaryawan = $this->karyawanModel->getById($idKaryawan);
				$dataGolongan = $this->golonganModel->getbyid($selectData->id_golongan1);
				$dataUpdate = array();
				if ($dataKaryawan->periode_gaji == "Bulanan") {
					$dataUpdate["gaji"] = $dataGolongan->umk;
				}
				else if ($dataKaryawan->periode_gaji == "2-Mingguan") {
					$dataUpdate["gaji"] = $dataGolongan->gaji * 12;
				}
				else if ($dataKaryawan->periode_gaji == "1-Mingguan") {
					$dataUpdate["gaji"] = $dataGolongan->gaji * 6;
				}

				$updateDataKaryawan = $this->karyawanModel->update($idKaryawan,$dataUpdate);
				if($statusInfo == "Edit")
				{
					$dataNotif = array(
											"keterangan"=> 	" Owner <u style='color:green;'>Terima</u> update / edit data promosi demosi mutasi karyawan baru dengan Nama <u>".$getKaryawan->nama."</u> dan No Karyawan : <u>".$getKaryawan->idfp."</u>",
											"user_id"	=>	$this->user_id,
											"level"		=>	"hrd",
											"url_direct"=>	"aktivitas/PDM",
										);
					$updateTrans = $this->tempPDMModel->updateTransaction($id,$idPromosi,$idKaryawan,$data,$status,$dataNotif);
					$token = $this->tokenModel->getByIdfp($getKaryawan->idfp);
					$count = $this->tokenModel->getByIdfpCount($getKaryawan->idfp);
					if ($updateTrans) {

						parent::insertNotif($dataNotif);
						if ($count != 0) {
							parent::pushnotif($token,"Approval PDM","Owner Menerima data EDIT PDM Karyawan","056");
						}
						parent::sendNotifTopic("hrd","Approval PDM","Owner Menerima data EDIT PDM Karyawan","056");
						$this->response->status = true;
						$this->response->message = alertSuccess("Berhasil update edit data Karyawan.");
					}
					else{
						$this->response->message = alertDanger("Gagal update edit data Karyawan.");
					}
				}else if ($statusInfo == "Tambah") {
					$dataNotif = array(
											"keterangan"=> 	" Owner <u style='color:green;'>Terima</u> Tambah data promosi demosi mutasi karyawan baru dengan Nama <u>".$getKaryawan->nama."</u> dan No Karyawan : <u>".$getKaryawan->idfp."</u>",
											"user_id"	=>	$this->user_id,
											"level"		=>	"hrd",
											"url_direct"=>	"aktivitas/PDM",
										);

					$addTrans = $this->tempPDMModel->addTransaction($id,$idKaryawan,$data,$data1,$status,$dataNotif);
					$token = $this->tokenModel->getByIdfp($getKaryawan->idfp);
					$count = $this->tokenModel->getByIdfpCount($getKaryawan->idfp);

					// $updateTrans = $this->tempPDMModel->updateTransaction($id,$idPromosi,$idKaryawan,$data,$status, $dataNotif);
					if ($addTrans) {

						parent::insertNotif($dataNotif);
						if ($count != 0) {
							parent::pushnotif($token,"Approval PDM","Owner Menerima TAMBAH data PDM Karyawan","057");
						}
						parent::sendNotifTopic("hrd","Approval PDM","Owner Menerima TAMBAH data PDM Karyawan","057");
						$this->response->status = true;
						$this->response->message = alertSuccess("Berhasil Tambah data PDM Karyawan.");
					}
					else{
						$this->response->message = alertDanger("Gagal Tambah data PDM Karyawan.");
					}
				}
				else {
					$dataNotif = array(
											"keterangan"=> 	" Owner <u style='color:green;'>Terima</u> hapus data promosi demosi mutasi karyawan dengan Nama <u>".$getKaryawan->nama."</u> dan No Karyawan : <u>".$getKaryawan->idfp."</u>",
											"user_id"	=>	$this->user_id,
											"level"		=>	"hrd",
											"url_direct"=>	"aktivitas/PDM",
										);

					$deleteTrans = $this->tempPDMModel->deleteTransaction($idPromosi,$status,$dataNotif);
					$token = $this->tokenModel->getByIdfp($getKaryawan->idfp);
					$count = $this->tokenModel->getByIdfpCount($getKaryawan->idfp);
					if ($deleteTrans) {
						parent::insertNotif($dataNotif);
						if ($count != 0) {
							parent::pushnotif($token,"Approval PDM","Owner Menerima HAPUS data PDM Karyawan","058");
						}
						parent::sendNotifTopic("hrd","Approval PDM","Owner Menerima HAPUS data PDM Karyawan","058");
						$this->response->status = true;
						$this->response->message = alertSuccess("Berhasil Delete data Karyawan.");
					}
					else{
						$this->response->message = alertDanger("Gagal Delete data Karyawan.");
					}
				}
			}else{
				$dataNotif = array(
											"keterangan"=> 	" Owner <u style='color:red;'>Tolak</u> data promosi demosi mutasi karyawan dengan Nama <u>".$getKaryawan->nama."</u> dan No Induk <u>".$getKaryawan->idfp."</u>",
											"user_id"	=>	$this->user_id,
											"level"		=>	"hrd",
											"url_direct"=>	"aktivitas/PDM",
										);
				$deleteTrans = $this->tempPDMModel->update($id,$status,$dataNotif);

				if ($deleteTrans) {
					parent::insertNotif($dataNotif);
					$this->response->status = true;
					$this->response->message = alertSuccess("Data PDM Karyawan Ditolak.");
				}
				else{
					$this->response->message = alertDanger("Gagal tambah data Karyawan.");
				}
			}

		}
		parent::json();
	}

	public function delete($id)
	{
		parent::checkLoginUser(); // user login autentic checking


		if ($this->isPost()) {

			$getById = $this->tempPDMModel->getById($id);
			if ($getById) {
				$delete = $this->tempPDMModel->delete($id);
				if ($delete) {
					$this->response->status = true;
					$this->response->message = alertSuccess("Data Karyawan Berhasil di hapus.");
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

/* End of file PDM.php */
/* Location: ./application/controllers/approvalowner/PDM.php */
