<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Manage_user extends MY_Controller {

	public function __construct()
	{
		parent::__construct();
		$this->load->model('aktivitas/Cuti_model',"cutiModel");
		$this->load->model('Calendar_model',"calendarModel");
		$this->load->model('master/Karyawan_model',"karyawanModel");
		$this->load->model('Token_model',"tokenModel");
		parent::checkLoginOwner();

	}

	public function index()
	{
		parent::checkLoginUser(); // user login autentic checking

		parent::headerTitle("Aktivitas data > Cuti","Aktivitas Data","Cuti");
		$breadcrumbs = array(
							"Aktivitas Data"	=>	site_url('aktivitas/cuti'),
							"Cuti"		=>	"",
						);
		parent::breadcrumbs($breadcrumbs);

		parent::viewAktivitas();
	}

	public function ajax_list()
	{
		parent::checkLoginUser(); // user login autentic checking

		if ($this->isPost()) {
			$data = array();

			$orderBy = array(null,null,null,"tanggal","nama","jabatan","status","departemen","sisa_cuti","keterangans","mulai_berlaku","exp_date","lama_cuti","jatah_cuti");
			$search = array("nama","keterangans");
			$jatahCuti = $this->cutiModel->get_setting();

			$result = $this->cutiModel->findDataTable($orderBy,$search);
			foreach ($result as $item) {
				if($item->status == "Proses")
				{
					$btnTools = '<button class="btn btn-warning btn-outline-default btn-mini"><i class="fa fa-print"></i>Print</button>';
					$item->status = '<label class="label label-info">'.$item->status.'</label>';
				}
				else if($item->status == "Diterima")
				{
					$btnTools = '<button class="btn btn-default btn-outline-default btn-mini" onclick="btnPrint('.$item->id_cuti.')"><i class="fa fa-print"></i>Print</button>';
					$item->status = '<label class="label label-success">'.$item->status.'</label>';
				}
				else {
					$item->status = '<label class="label label-danger">'.$item->status.'</label>';
					$btnTools = '<button class="btn btn-warning btn-outline-default btn-mini"><i class="fa fa-print"></i>Print</button>';
				}
				$btnAction = '<button class="btn btn-warning btn-warning btn-mini" onclick="btnEdit('.$item->id_cuti.')"><i class="fa fa-pencil-square-o"></i>Edit</button>';
				$btnAction .= '&nbsp;&nbsp;&nbsp;<button class="btn btn-danger btn-danger btn-mini" onclick="btnDelete('.$item->id_cuti.')"><i class="fa fa-trash-o"></i>Hapus</button>';

				$item->tanggal = date_ind("d M Y",$item->tanggal);
				$item->jatah_cuti = $jatahCuti->jatah_cuti;
				$item->mulai_berlaku = date_ind("d M Y",$item->mulai_berlaku);
				$item->exp_date = date_ind("d M Y",$item->exp_date);
				$item->button_print = $btnTools;
				$item->button_action = $btnAction;
				$data[] = $item;
			}
			return $this->cutiModel->findDataTableOutput($data,$search);
		}
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
			$opsiCuti = $this->input->post('opsi_cuti');
			$gajiCuti = $this->input->post('gaji_cuti');
			// $date1=date_create($mulaiCuti);
			// $date2=date_create($akhirCuti);
			// $lama=date_diff($date1,$date2)->format("%a")+1;
			$date1=strtotime($mulaiCuti);
			$date2=strtotime($akhirCuti);
			$lama=abs($date1 - $date2)/86400+1;
			$this->form_validation->set_rules('karyawan', 'Karyawan', 'trim|required');
			$this->form_validation->set_rules('mulaiCuti', 'Mulai Cuti', 'trim|required');
			$this->form_validation->set_rules('akhirCuti', 'Akhir Cuti', 'trim|required');
			$this->form_validation->set_rules('keterangan', 'keterangan', 'trim|required');
			// will be change after setting table have been created
			$getJatahCuti = $this->cutiModel->get_setting();
			$jatahCuti = $getJatahCuti->jatah_cuti;
			$nama = $this->karyawanModel->getById($karyawan,"nama");
			$data1 = $this->cutiModel->getIdKaryawan1($karyawan);
			$cutiBersama = $this->calendarModel->getallrow();
			$sisaCuti = $jatahCuti- $cutiBersama - $lama;
			//looping data for searcing sisaCuti
			foreach ($data1 as $item) {
								$sisaCuti -= $item->lama_cuti;
							}

			if ($this->form_validation->run() == TRUE) {
				if (strtotime($mulaiCuti) <= strtotime(date("Y-m-d"))) {
					$this->response->status = false;
					$this->response->message = alertDanger("Tanggal mulai cuti harus lebih besar dari pada tanggal ".date_ind("d M Y",date("Y-m-d")).".!");
				}
				else {
					$checkDataJadwal =  $this->cutiModel->checkDataJadwal($tanggal,$karyawan);
					if (sizeof($checkDataJadwal) >0 && $checkDataJadwal[0]->id_karyawan == $karyawan) {

						$this->response->status = false;
						$this->response->message = alertDanger("Data Karyawan Yang Di Input dengan tanggal yang anda pilih sudah ada.!");
					}
					else {

						if ($opsiCuti == 1) {
							$data = array(
											"tanggal"	=>	date("Y-m-d"),
											"id_karyawan" => $karyawan,
											"sisa_cuti" => $sisaCuti+$lama,
											"mulai_berlaku" => $mulaiCuti,
											"exp_date" => $akhirCuti,
											"lama_cuti " => $lama,
											"opsi_cuti" => $opsiCuti,
											"gaji_cuti" => $gajiCuti,
											"keterangans"=>	$keterangan
										);
								$dataNotif = array(
													"keterangan"=> 	" Tambah data Cuti baru.",
													"user_id"	=>	$this->user->id_pengguna,
													"level"		=>	"hrd",
													"url_direct"=>	"approval/cuti",
												);
								// $dataToken = $this->tokenModel->getByToken("kabag");

								$insert = $this->cutiModel->insert($data,$dataNotif);
								if ($insert) {
									//notif firebase
									parent::insertNotif($dataNotif);
									parent::sendNotifTopic("hrd","Pengajuan Cuti","Karyawan dengan nama ".$nama->nama." mengajukan cuti","002");
									$this->response->status = true;
									$this->response->message = alertSuccess("Berhasil proses Tambah Data Cuti dan menunggu approval dari owner.");
								} else {
									$this->response->message = alertDanger("Gagal, tambah data Cuti.");
								}
						}
						else {
							if($sisaCuti < 0 )
							{
								$this->response->status = false;
								$this->response->message = alertDanger("Jatah Cuti telah digunakan sampai habis.");
							}
							else {
								$data = array(
												"tanggal"	=>	date("Y-m-d"),
												"id_karyawan" => $karyawan,
												"sisa_cuti" => $sisaCuti,
												"mulai_berlaku" => $mulaiCuti,
												"exp_date" => $akhirCuti,
												"lama_cuti " => $lama,
												"opsi_cuti" => $opsiCuti,
												"gaji_cuti" => $gajiCuti,
												"keterangans"=>	$keterangan
											);
								$dataNotif = array(
													"keterangan"=> 	" Tambah data Cuti baru.",
													"user_id"	=>	$this->user->id_pengguna,
													"level"		=>	"hrd",
													"url_direct"=>	"approval/cuti",
												);
								// $dataToken = $this->tokenModel->getByToken("kabag");

								$insert = $this->cutiModel->insert($data,$dataNotif);
								if ($insert) {
									//notif firebase
									parent::insertNotif($dataNotif);
									parent::sendNotifTopic("hrd","Pengajuan Cuti","Karyawan dengan nama ".$nama->nama." mengajukan cuti","002");
									$this->response->status = true;
									$this->response->message = alertSuccess("Berhasil proses Tambah Data Cuti dan menunggu approval dari owner.");
								} else {
									$this->response->message = alertDanger("Gagal, tambah data Cuti.");
								}
							}
						}
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
				$getById->mulai_berlaku1 = $getById->mulai_berlaku;
				$getById->exp_date1 = $getById->exp_date;
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
}

/* End of file cuti.php */
/* Location: ./application/controllers/aktiviats/Cuti.php */
