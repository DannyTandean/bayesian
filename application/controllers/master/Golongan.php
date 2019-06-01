<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Golongan extends MY_Controller {

	public function __construct()
	{
		parent::__construct();
		$this->load->model('approval/Temp_golongan_model',"tempGolonganModel");
		$this->load->model('master/Golongan_model',"golonganModel");
		$this->load->model('master/Karyawan_model', "karyawanModel");
	}

	public function index()
	{
		parent::checkLoginUser(); // user login autentic checking

		parent::headerTitle("Master Data > Golongan","Master Data","Golongan");
		$breadcrumbs = array(
							"Master Data"	=>	site_url('master/golongan'),
							"Golongan"		=>	"",
						);
		parent::breadcrumbs($breadcrumbs);

		parent::viewMaster();
	}

	public function ajax_list()
	{
		parent::checkLoginUser(); // user login autentic checking

		if ($this->isPost()) {
			$data = array();

			$orderBy = array(null,null,"golongan","gaji","makan","transport","thr","tunjangan_lain","bpjs","pot_lain","lembur_tetap","umk","keterangan");
			$search = array("golongan","keterangan");

			$result = $this->golonganModel->findDataTable($orderBy,$search);
			foreach ($result as $item) {

				$btnAction = '<button class="btn btn-warning btn-outline-warning btn-mini" onclick="btnEdit('.$item->id_golongan.')"><i class="fa fa-pencil-square-o"></i>Edit</button>';
				$btnAction .= '&nbsp;&nbsp;&nbsp;<button class="btn btn-danger btn-outline-danger btn-mini" onclick="btnDelete('.$item->id_golongan.')"><i class="fa fa-trash-o"></i>Hapus</button>';
				$item->gaji = "Rp.".number_format($item->gaji,0,",",",");
				$item->makan = "Rp.".number_format($item->makan,0,",",",");
				$item->transport = "Rp.".number_format($item->transport,0,",",",");
				$item->pot_lain = "Rp.".number_format($item->pot_lain,0,",",",");
				$item->umk = "Rp.".number_format($item->umk,0,",",",");
				$item->tunjangan_lain = "Rp.".number_format($item->tunjangan_lain,0,",",",");
				$item->lembur_tetap = "Rp.".number_format($item->lembur_tetap,0,",",",");
				$item->gaji_menit = "Rp.".number_format($item->gaji_menit,0,",",",");
				$item->bpjs = $item->bpjs." %";
				$item->button_action = $btnAction;
				$data[] = $item;
			}
			return $this->golonganModel->findDataTableOutput($data,$search);
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
			$gajiPerMenit = $this->input->post('permenit');
			$potBpjs = $this->input->post('PotBpjs');
			$potLain = $this->input->post('PotLain');
			$sistemLembur = $this->input->post('SistemLembur');
			$LemburTetap = $this->input->post('LemburTetap');
			$keterangan = $this->input->post("keterangan");

			$this->form_validation->set_rules('golongan', 'Golongan', 'trim|required|is_unique[master_golongan.golongan]');
			$this->form_validation->set_rules('gajiGolongan', 'Gaji Harian', 'trim|required');
			$this->form_validation->set_rules('umk', 'Gaji Bulanan', 'trim|required');
			$this->form_validation->set_rules('SistemLembur', 'Sistem Lembur', 'trim|required');
			$this->form_validation->set_rules('keterangan', 'keterangan', 'trim|required');
			$this->form_validation->set_rules('permenit', 'Per Menit', 'trim|required');

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
								"gaji_menit" => $gajiPerMenit,
								"bpjs"	=> $potBpjs,
								"pot_lain"	=> $potLain,
								"lembur"	=> $sistemLembur,
								"lembur_tetap" => $LemburTetap,
								"umk"	=> $umk,
								"keterangan"=>	$keterangan,
								"status_info" => "Tambah"
							);
				$dataNotif = array(
									"keterangan"=> 	" Tambah data golongan baru.",
									"user_id"	=>	$this->user->id_pengguna,
									"level"		=>	"owner",
									"url_direct"=>	"approvalowner/golongan",
								);
				$insert = $this->tempGolonganModel->insert($data,$dataNotif);
				if ($insert) {
					/*notif firebase*/
					parent::insertNotif($dataNotif);
					parent::sendNotifTopic("owner","Pengajuan Golongan","HRD mengajukan Golongan baru","007");
					//android notif
					// parent::sendNotifTopic("owner","payroll",$dataNotif['keterangan']);
					$this->response->status = true;
					$this->response->message = "<span style='color:green;'>Sedang Proses tambah data golongan.</span>";
				} else {
					$this->response->message = "<span style='color:red;'>Gagal menambah data golongan.</span>";
				}

			} else {
				// $this->response->message = validation_errors('<span style="color:red;">', '</span><br>');
				$this->response->error = array(
									"golongan"		=> form_error("golongan",'<span style="color:red;">','</span>'),
									"umk"			=> form_error("umk",'<span style="color:red;">','</span>'),
									"gajiGolongan"	=> form_error("gajiGolongan",'<span style="color:red;">','</span>'),
									"SistemLembur"	=> form_error("SistemLembur",'<span style="color:red;">','</span>'),
									"keterangan"	=> form_error("keterangan",'<span style="color:red;">','</span>'),
									"permenit"	=> form_error("permenit",'<span style="color:red;">','</span>'),
								);
			}
		}
		parent::json();
	}

	public function getId($id)
	{
		parent::checkLoginUser(); // user login autentic checking

		if ($this->isPost()) {
			$getById = $this->golonganModel->getById($id);
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

	public function update($id)
	{
		parent::checkLoginUser(); // user login autentic checking

		if ($this->isPost()) {
			$idGolongan = $this->input->post('idGolongan');
			$golongan = $this->input->post("golongan");
			$gajiGolongan = $this->input->post('gajiGolongan');
			$umk = $this->input->post('umk');
			$THR = $this->input->post('thr');
			$gajiPerMenit = $this->input->post('permenit');
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

			// $this->form_validation->set_rules('golongan', 'Golongan', 'trim|required|is_unique[master_golongan.golongan]');
			$this->form_validation->set_rules('gajiGolongan', 'Gaji Harian', 'trim|required');
			$this->form_validation->set_rules('umk', 'Gaji Bulanan', 'trim|required');
			$this->form_validation->set_rules('SistemLembur', 'Sistem Lembur', 'trim|required');
			$this->form_validation->set_rules('keterangan', 'keterangan', 'trim|required');
			$this->form_validation->set_rules('permenit', 'Per Menit', 'trim|required');

			$dataTemp = $this->tempGolonganModel->getByIdGolonganDetail($id);
			$getById = $this->golonganModel->getById($id);

					if ($dataTemp["status"] == "Proses") {
							$this->response->status = false;
							$this->response->message = "<span style='color:red;'>Data Golongan ini sedang di proses.</span>";
					}
					else {
						if ($this->form_validation->run() == TRUE) {
							$data = array(
											"id_golongan" => $idGolongan,
											"golongan"	=>	$golongan,
											"gaji"	=> $gajiGolongan,
											// "kehadiran"	=> $tunjanganKehadiran,
											"makan"	=> $tunjanganMakan,
											"transport"	=> $tunjanganTransport,
											"thr"	=> $THR,
											// "tundip"	=> $tunjanganDisiplin,
											"gaji_menit" => $gajiPerMenit,
											"tunjangan_lain"	=> $tunjanganLain,
											// "absen"	=> $potAbsen,
											"bpjs"	=> $potBpjs,
											"pot_lain"	=> $potLain,
											"lembur"	=> $sistemLembur,
											"lembur_tetap" => $LemburTetap,
											"umk"	=> $umk,
											"keterangan"=>	$keterangan,
											"status_info" => "Edit"
										);
							$dataNotifAndroid = "Edit/update data Golongan dengan nama= ".$getById->golongan;
							$dataNotif = array(
												"keterangan"=> 	" Edit/update data Golongan dengan nama=<u>".$getById->golongan."</u>",
												"user_id"	=>	$this->user->id_pengguna,
												"level"		=>	"owner",
												"url_direct"=>	"approvalowner/Golongan",
											);
							$update = $this->tempGolonganModel->insert($data,$dataNotif);
							if ($update) {
								/*notif firebase*/
								parent::insertNotif($dataNotif);
								parent::sendNotifTopic("owner","Informasi","HRD mengajukan perubahan data Golongan","005");
								//android notif
								// parent::sendNotifTopic("owner","payroll",$dataNotifAndroid);
								$this->response->status = true;
								$this->response->message = " <span style='color:green;'>Sedang proses update data golongan.</span>";
							} else {
								$this->response->message = "<span style='color:red;'>Gagal update data golongan.</span>";
							}
						} else {
							// $this->response->message = validation_errors('<span style="color:red;">', '</span><br>');
							$this->response->error = array(
											// "golongan"	=> form_error("golongan",'<span style="color:red;">','</span>'),
											"umk"			=> form_error("umk",'<span style="color:red;">','</span>'),
											"gajiGolongan"	=> form_error("gajiGolongan",'<span style="color:red;">','</span>'),
											"SistemLembur"	=> form_error("SistemLembur",'<span style="color:red;">','</span>'),
											"keterangan"	=> form_error("keterangan",'<span style="color:red;">','</span>'),
											"permenit"	=> form_error("permenit",'<span style="color:red;">','</span>'),
										);
						}
					}



		}
		parent::json();
	}

	public function delete($id)
	{
		parent::checkLoginUser(); // user login autentic checking

		if ($this->isPost()) {
			$getById = $this->golonganModel->getById($id);
			$dataTemp = $this->tempGolonganModel->getByIdGolonganDetail($id);
			$dataNotifAndroid = "Hapus data golongan dengan nama= ".$getById->golongan;
			$dataNotif = array(
									"keterangan"=> 	" Hapus data golongan dengan nama=<u>".$getById->golongan."</u>",
									"user_id"	=>	$this->user->id_pengguna,
									"level"		=>	"owner",
									"url_direct"=>	"approvalowner/golongan",
								);

					if ($dataTemp["status"] == "Proses") {
							$this->response->status = false;
							$this->response->message = "<span style='color:red;'>Data Golongan ini sedang di proses.</span>";
					}else {
						if ($getById) {
							$getById->status_info = "Hapus";
							$delete = $this->tempGolonganModel->insert($getById,$dataNotif);
							if ($delete) {
								/*notif firebase*/
								parent::insertNotif($dataNotif);
								parent::sendNotifTopic("owner","Informasi","HRD mengajukan penghapusan data golongan","006");
								//android notif
								// parent::sendNotifTopic("owner","payroll",$dataNotifAndroid);
								$this->response->status = true;
								$this->response->message = alertSuccess("Data golongan sedang di proses untuk di hapus.");
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

/* End of file Golongan.php */
/* Location: ./application/controllers/master/Golongan.php */
