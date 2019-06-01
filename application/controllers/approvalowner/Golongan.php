<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Golongan extends MY_Controller {

	public function __construct()
	{
		parent::__construct();
		$this->load->model('approval/Temp_golongan_model',"tempGolonganModel");
		$this->load->model('master/Golongan_model',"golonganModel");
		parent::checkLoginHRD();
	}

	public function index()
	{
		parent::checkLoginUser(); // user login autentic checking

		parent::headerTitle("Approval Data > Golongan","Approval Data","Golongan");
		$breadcrumbs = array(
							"Approval Data"	=>	site_url('approvalowner/golongan'),
							"Golongan"		=>	"",
						);
		parent::breadcrumbs($breadcrumbs);

		parent::viewApprovalOwner();
	}

	public function ajax_list()
	{
		parent::checkLoginUser(); // user login autentic checking

		if ($this->isPost()) {
			$data = array();

			$orderBy = array(null,null,null,"golongan","gaji","status","status_info","makan","transport","thr","tunjangan_lain","bpjs","pot_lain","lembur_tetap","umk","keterangan","create_at");
			$search = array("golongan","keterangan","status");
			$btnTools = "";
			$result = $this->tempGolonganModel->findDataTable($orderBy,$search);
			foreach ($result as $item) {
				if ($item->status == "Diterima") {
					$btnAction = 'Sudah di Terima';

				}else if($item->status == "Ditolak"){
					$btnAction = 'Sudah di Tolak';
				}
				if($item->status == "Proses")
				{
					$item->status = '<label class="label label-info">'.$item->status.'</label>';
					$btnAction = '<button class="btn btn-success btn-outline-success btn-mini" onclick="btnApproval('.$item->temp_id_golongan.')"><i class="fa fa-check-square"></i>Terima</button>';
					$btnAction .= '&nbsp;&nbsp;&nbsp;<button class="btn btn-danger btn-outline-danger btn-mini" onclick="btnReject('.$item->temp_id_golongan.')"><i class="fa fa-ban"></i>Tolak</button>';
				}
				else if($item->status == "Diterima")
				{
					$item->status = '<label class="label label-success">'.$item->status.'</label>';
				}else {
					$item->status =  '<label class="label label-danger">'.$item->status.'</label>';
				}

				if($item->status_info == "Tambah"){
					$item->status_info = '<label class="label label-info">'.$item->status_info.'</label>';
					$btnTools = "<p align='center'>-</p>";
					// $btnTools = '<button class="btn btn-primary btn-outline-primary btn-mini" onclick="btnDetail('.$item->temp_id_golongan.')" disabled><i class="fa fa-list"></i>Details</button>';

				}else if ($item->status_info == "Edit") {
					$item->status_info = '<label class="label label-warning">'.$item->status_info.'</label>';
					$btnTools = '<button class="btn btn-primary btn-outline-primary btn-mini" onclick="btnDetail('.$item->temp_id_golongan.')"><i class="fa fa-list"></i>Details</button>';

				}else {
					$item->status_info = '<label class="label label-danger">'.$item->status_info.'</label>';
					$btnTools = '<button class="btn btn-primary btn-outline-primary btn-mini" onclick="btnDetail('.$item->temp_id_golongan.')" disabled><i class="fa fa-list"></i>Details</button>';
				}


				$item->gaji = "Rp.".number_format($item->gaji,0,",",",");
				$item->makan = "Rp.".number_format($item->makan,0,",",",");
				$item->transport = "Rp.".number_format($item->transport,0,",",",");
				$item->thr = "Rp.".number_format($item->thr,0,",",",");
				$item->tunjangan_lain = "Rp.".number_format($item->tunjangan_lain,0,",",",");
				$item->bpjs = "Rp.".number_format($item->bpjs,0,",",",");
				$item->pot_lain = "Rp.".number_format($item->pot_lain,0,",",",");
				$item->lembur_tetap = "Rp.".number_format($item->lembur_tetap,0,",",",");
				$item->umk = "Rp.".number_format($item->umk,0,",",",");
				$item->create_at = date_ind("d M Y H:i:s",$item->create_at);
				$item->button_tools = $btnTools;
				$item->button_action = $btnAction;
				$data[] = $item;
			}
			return $this->tempGolonganModel->findDataTableOutput($data,$search);
		}
	}

	public function add()
	{
		parent::checkLoginUser(); // user login autentic checking

		if ($this->isPost()) {
			$golongan = $this->input->post("golongan");
			$gajiGolongan = $this->input->post('gajiGolongan');
			$umk = $this->input->post('umk');
			$THR = $this->input->post('thr');
			// $tunjanganDisiplin = $this->input->post('tunjanganDisiplin');
			$tunjanganMakan = $this->input->post('tunjMakan');
			// $tunjanganKehadiran = $this->input->post('tunjKehadiran');
			$tunjanganTransport = $this->input->post('TunjTransport');
			$tunjanganLain = $this->input->post('TunjLain');
			// $potAbsen = $this->input->post('PotAbsen');
			$potBpjs = $this->input->post('PotBpjs');
			$potLain = $this->input->post('PotLain');
			$sistemLembur = $this->input->post('SistemLembur');
			$LemburTetap = $this->input->post('LemburTetap');
			$keterangan = $this->input->post("keterangan");

			$this->form_validation->set_rules('golongan', 'Golongan', 'trim|required|is_unique[master_golongan.golongan]');
			$this->form_validation->set_rules('gajiGolongan', 'Gaji Golongan', 'trim|required');
			$this->form_validation->set_rules('SistemLembur', 'Sistem Lembur', 'trim|required');
			$this->form_validation->set_rules('keterangan', 'keterangan', 'trim|required');

			if ($this->form_validation->run() == TRUE) {
				$data = array(
								"golongan"	=>	$golongan,
								"gaji"	=> $gajiGolongan,
								// "kehadiran"	=> $tunjanganKehadiran,
								"makan"	=> $tunjanganMakan,
								"transport"	=> $tunjanganTransport,
								"thr"	=> $THR,
								// "tundip"	=> $tunjanganDisiplin,
								"tunjangan_lain"	=> $tunjanganLain,
								// "absen"	=> $potAbsen,
								"bpjs"	=> $potBpjs,
								"pot_lain"	=> $potLain,
								"lembur"	=> $sistemLembur,
								"lembur_tetap" => $LemburTetap,
								"umk"	=> $umk,
								"keterangan"=>	$keterangan
							);
				$insert = $this->golonganModel->insert($data);
				if ($insert) {

					$this->response->status = true;
					$this->response->message = alertSuccess("Berhasil tambah data golongan.");
				} else {
					$this->response->message = alertDanger("Gagal tambah data golongan.");
				}

			} else {
				// $this->response->message = validation_errors('<span style="color:red;">', '</span><br>');
				$this->response->error = array(
									"golongan"	=> form_error("golongan",'<span style="color:red;">','</span>'),
									"gajiGolongan"	=> form_error("golongan",'<span style="color:red;">','</span>'),
									"SistemLembur"	=> form_error("golongan",'<span style="color:red;">','</span>'),
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
			$getById = $this->tempGolonganModel->getById($id);
			if ($getById) {
				$this->response->status = true;
				$this->response->message = "Data golongan get by id";
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
			$dataTemp = $this->tempGolonganModel->getByIdDetail($id);
			$getById = $this->tempGolonganModel->getById($id);
			$dataMaster = $this->golonganModel->getByIdDetail($dataTemp["id_golongan"]);
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
				$data["approve_at"] = $getById->approve_at;
				$this->response->status = true;
				$this->response->message = "Data golongan get by id";
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
			$idGolongan = $this->input->post('idGolongan');
			$selectData = $this->tempGolonganModel->getSelectData($id);
			$status = array(
				'status' => $status1,
				'approve_at' => date("Y-m-d-H:i:s")
			);
			if($status1 == "Diterima"){
				if($statusInfo == "Edit")
				{
					$pesanNotifAndroid = "Owner Approval Edit/Update data golongan dengan nama= ".$selectData->golongan;
					$dataNotif = array(
									"keterangan"=> 	" Owner <u style='color:green;'>Approval</u> Edit/Update data golongan dengan nama=<u>".$selectData->golongan."</u>",
									"user_id"	=>	$this->user->id_pengguna,
									"level"		=>	"hrd",
									"url_direct"=>	"master/golongan",
								);

					$updateTrans = $this->tempGolonganModel->updateTransaction($id,$idGolongan,$selectData,$status,$dataNotif);
					if ($updateTrans) {
						/*notif firebase*/
						parent::insertNotif($dataNotif);

						//notif android

						// parent::sendNotifTopic("hrd","payroll",$pesanNotifAndroid);
						$this->response->status = true;
						$this->response->message = alertSuccess("Berhasil update data golongan.");
					}
					else{
						$this->response->message = alertDanger("Gagal update data golongan.");
					}
				}else if ($statusInfo == "Tambah") {
					$pesanNotifAndroid = "Owner Approval data golongan baru dengan nama= ".$selectData->golongan;
					$dataNotif = array(
									"keterangan"=> 	" <u style='color:green;'>Approval</u> data golongan baru dengan nama=<u>".$selectData->golongan."</u>",
									"user_id"	=>	$this->user->id_pengguna,
									"level"		=>	"hrd",
									"url_direct"=>	"master/golongan",
								);
					$addTrans = $this->tempGolonganModel->addTransaction($id,$selectData,$status,$dataNotif);

					if ($addTrans) {
						/*notif firebase*/
						parent::insertNotif($dataNotif);
						//notif android
						parent::sendNotifTopic("hrd","Approval Golongan","Owner menerima data Golongan","028");
						$this->response->status = true;
						$this->response->message = alertSuccess("Berhasil Add data golongan.");
					}
					else{
						$this->response->message = alertDanger("Gagal Add data golongan.");
					}
				}
				else {
					$pesanNotifAndroid = " Owner Approval hapus data golongan dengan nama= ".$selectData->golongan;
					$dataNotif = array(
									"keterangan"=> 	" Owner <u style='color:green;'>Approval</u> hapus data golongan dengan nama=<u>".$selectData->golongan."</u>",
									"user_id"	=>	$this->user->id_pengguna,
									"level"		=>	"hrd",
									"url_direct"=>	"master/golongan",
								);
					$deleteTrans = $this->tempGolonganModel->deleteTransaction($idGolongan,$status,$dataNotif);

					if ($deleteTrans) {
						/*notif firebase*/
						parent::insertNotif($dataNotif);
						//notif android
						// parent::sendNotifTopic("hrd","payroll",$pesanNotifAndroid);
						$this->response->status = true;
						$this->response->message = alertSuccess("Berhasil Delete data golongan.");
					}
					else{
						$this->response->message = alertDanger("Gagal Delete data golongan.");
					}
				}
			}else{
				// update status when reject

				$pesanNotif1 = "";
				if($statusInfo == "Tambah")
				{
					$pesanNotif1 = "Tambah";
				}
				else if ($statusInfo == "Edit") {
					$pesanNotif1 = "Edit";
				}
				else {
					$pesanNotif1 = "Hapus";
				}
				$pesanNotifAndroid = " Owner Reject ".$pesanNotif1." data golongan dengan nama= ".$selectData->golongan;
				$dataNotif = array(
								"keterangan"=> 	" Owner <u style='color:red;'>Reject </u>".$pesanNotif1." data golongan dengan nama=<u>".$selectData->golongan."</u>",
								"user_id"	=>	$this->user->id_pengguna,
								"level"		=>	"hrd",
								"url_direct"=>	"master/golongan",
							);
				$deleteTrans = $this->tempGolonganModel->update($id,$status,$dataNotif);

				if ($deleteTrans) {
					/*notif firebase*/
					parent::insertNotif($dataNotif);
					//notif android
					// parent::sendNotifTopic("hrd","payroll",$pesanNotifAndroid);
					$this->response->status = true;
					$this->response->message = alertSuccess("Berhasil Reject data golongan.");
				}
				else{
					$this->response->message = alertDanger("Gagal Reject data golongan.");
				}
			}

				// if ($update) {
				// 	$this->response->status = true;
				// 	$this->response->message = alertSuccess("Berhasil update data golongan.");
				// } else {
				// 	$this->response->message = alertDanger("Gagal update data golongan.");
				// }

		}
		parent::json();
	}

	public function delete($id)
	{
		parent::checkLoginUser(); // user login autentic checking

		if ($this->isPost()) {
			$getById = $this->tempGolonganModel->getById($id);
			if ($getById) {
				$delete = $this->tempGolonganModel->delete($id);
				if ($delete) {
					$this->response->status = true;
					$this->response->message = alertSuccess("Data golongan Berhasil di hapus.");
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

/* End of file Golongan.php */
/* Location: ./application/controllers/approvalowner/Golongan.php */
