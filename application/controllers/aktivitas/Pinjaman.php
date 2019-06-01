<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Pinjaman extends MY_Controller {

	public function __construct()
	{
		parent::__construct();
		$this->load->model('aktivitas/Pinjaman_model',"pinjamanModel");
		$this->load->model('master/Karyawan_model',"karyawanModel");
		$this->load->model('aktivitas/LogPembayaran_model',"logpembayaranModel");
		parent::checkLoginOwner();
	}

	public function index()
	{
		parent::checkLoginUser(); // user login autentic checking

		parent::headerTitle("Aktivitas Data > Pinjaman","Aktivitas Data","Pinjaman");
		$breadcrumbs = array(
							"Aktivitas"	=>	site_url('aktivitas/pinjaman'),
							"Pinjaman"		=>	"",
						);
		parent::breadcrumbs($breadcrumbs);
		$data = array();
		$data['karyawan'] = $this->pinjamanModel->getKaryawan();
		parent::viewData($data);

		parent::viewAktivitas();


	}

	public function ajax_list()
	{
		parent::checkLoginUser(); // user login autentic checking

		if ($this->isPost()) {
			$data = array();

			$orderBy = array(null,null,null,"tanggal","nama","departemen","jabatan","jumlah","bayar","sisa","cicilan","keterangans","status");
			$search = array("nama","departemen","jabatan");

			$result = $this->pinjamanModel->findDataTable($orderBy,$search,false,null,null);
			foreach ($result as $item) {
				if($item->status == "Proses")
				{
					$btnTools = '<button class="btn btn-warning btn-outline-default btn-mini"><i class="fa fa-print"></i>Print</button>';
					$item->status = '<label class="label label-info">'.$item->status.'</label>';
				}
				else if($item->status == "Diterima")
				{
					$btnTools = '<button class="btn btn-default btn-outline-default btn-mini" onclick="btnPrint('.$item->id_pinjaman.')"><i class="fa fa-print"></i>Print</button>';
					$item->status = '<label class="label label-success">'.$item->status.'</label>';
				}
				else {
					$item->status = '<label class="label label-danger">'.$item->status.'</label>';
					$btnTools = '<button class="btn btn-warning btn-outline-default btn-mini"><i class="fa fa-print"></i>Print</button>';
				}
				$btnAction = '<button class="btn btn-warning  btn-mini" onclick="btnEdit('.$item->id_pinjaman.')"><i class="fa fa-pencil-square-o"></i>Edit</button>';
				$btnAction .= '&nbsp;&nbsp;&nbsp;<button class="btn btn-danger btn-mini" onclick="btnDelete('.$item->id_pinjaman.')"><i class="fa fa-trash-o"></i>Hapus</button>';

				$item->bayar = "Rp.".number_format($item->bayar,0,",",",");
				$item->jumlah = "Rp.".number_format($item->jumlah,0,",",",");
				$item->sisa = "Rp.".number_format($item->sisa,0,",",",");
				$item->tanggal = date_ind("d M Y",$item->tanggal);
				$item->button_tool = $btnTools;
				$item->button_action = $btnAction;
				$data[] = $item;
			}
			return $this->pinjamanModel->findDataTableOutput($data,$search,false,null,null);
		}
	}

	public function ajax_list_pembayaran($pembayaran)
	{
		parent::checkLoginUser(); // user login autentic checking

		if ($this->isPost()) {
			$data = array();

			$orderBy = array(null,null,"tanggal","nama","departemen","jabatan","jumlah","bayar","sisa","cicilan","keterangans");
			$search = array("nama","departemen","jabatan");

			$result = $this->pinjamanModel->findDataTable($orderBy,$search,$pembayaran,null,null);
			foreach ($result as $item) {

				if($item->status == "Diterima")
				{
					$btnTools = '<button class="btn btn-default btn-outline-default btn-mini m-l-5" onclick="btnPrint('.$item->id_pinjaman.')"><i class="fa fa-print"></i>Print</button>';
					$btnTools .= '&nbsp;&nbsp;&nbsp;<button class="btn btn-primary btn-mini" onclick="btnPaid('.$item->id_pinjaman.')"><i class="fa fa-money"></i></i>Payment</button>';
					$item->status = '<label class="label label-success">'.$item->status.'</label>';
				}

				// $btnActio n = '<button class="btn btn-warning btn-outline-warning btn-mini" onclick="btnEdit('.$item->id_pinjaman.')"><i class="fa fa-pencil-square-o"></i>Edit</button>';
				$btnAction = '&nbsp;&nbsp;&nbsp;<button class="btn btn-danger btn-mini" onclick="btnDelete('.$item->id_pinjaman.')"><i class="fa fa-trash-o"></i>Hapus</button>';

				$item->bayar = "Rp.".number_format($item->bayar,0,",",",");
				$item->jumlah = "Rp.".number_format($item->jumlah,0,",",",");
				$item->sisa = "Rp.".number_format($item->sisa,0,",",",");
				$item->tanggal = date_ind("d M Y",$item->tanggal);
				$item->button_tool = $btnTools;
				$item->button_action = $btnAction;
				$data[] = $item;
			}
			return $this->pinjamanModel->findDataTableOutput($data,$search,$pembayaran,null,null);
		}
	}

	public function ajax_list_log_pembayaran()
	{
		parent::checkLoginUser(); // user login autentic checking

		if ($this->isPost()) {
			$data = array();

			$orderBy = array(null,"tanggal","nama","departemen","jabatan","jumlah","pembayaran","log_sisa","cara_pembayaran","cicilan","keterangan");
			$search = array("nama","departemen","jabatan");

			$result = $this->logpembayaranModel->findDataTable($orderBy,$search,null,null);
			foreach ($result as $item) {
					$btnTools = '<button class="btn btn-default btn-outline-default btn-mini m-l-5" onclick="btnPrint('.$item->id_log_pinjaman.')"><i class="fa fa-print"></i>Print</button>';
					$btnTools .= '&nbsp;&nbsp;&nbsp;<button class="btn btn-primary btn-mini" onclick="btnPaid('.$item->id_log_pinjaman.')"><i class="fa fa-money"></i></i>Payment</button>';

				$btnAction = '&nbsp;&nbsp;&nbsp;<button class="btn btn-danger btn-mini" onclick="btnDelete('.$item->id_log_pinjaman.')"><i class="fa fa-trash-o"></i>Hapus</button>';

				$item->pembayaran = "Rp.".number_format($item->pembayaran,0,",",",");
				$item->jumlah = "Rp.".number_format($item->jumlah,0,",",",");
				$item->log_sisa = "Rp.".number_format($item->log_sisa,0,",",",");
				$item->tanggal = date_ind("d M Y",$item->tanggal);
				$item->button_tool = $btnTools;
				$item->button_action = $btnAction;
				$data[] = $item;
			}
			return $this->logpembayaranModel->findDataTableOutput($data,$search,null,null);
		}
	}

	public function add()
	{
		parent::checkLoginUser(); // user login autentic checking

		if ($this->isPost()) {
			$tanggal = $this->input->post("tanggal");
			$karyawan = $this->input->post('karyawan');
			$jlhPinjaman = $this->input->post('jlhPinjaman');
			$caraPembayaran = $this->input->post('caraPembayaran');
			$cicilan = $this->input->post('cicilan');
			$keterangan = $this->input->post("keterangan");

			// $this->form_validation->set_rules('tanggal', 'Tanggal', 'trim|required');
			$this->form_validation->set_rules('karyawan', 'Karyawan', 'trim|required');
			$this->form_validation->set_rules('jlhPinjaman', 'Jumlah Pinjaman', 'trim|required');
			$this->form_validation->set_rules('caraPembayaran', 'Cara Pembayaran', 'trim|required');
			$this->form_validation->set_rules('keterangan', 'Keterangan', 'trim|required');

			if ($this->form_validation->run() == TRUE) {
				$data = array(
								"id_karyawan" => $karyawan,
								"tanggal"	=>	date("Y-m-d"),
								"jumlah" => $jlhPinjaman,
								"cara_pembayaran" => $caraPembayaran,
								"cicilan" => $cicilan,
								"keterangans"=>	$keterangan,
								"sisa" => $jlhPinjaman
							);

				$dataNotif = array(
									"keterangan"=> 	" Tambah data Pinjaman baru.",
									"user_id"	=>	$this->user->id_pengguna,
									"level"		=>	"owner",
									"url_direct"=>	"approvalowner/pinjaman",
								);
				$insert = $this->pinjamanModel->insert($data,$dataNotif);
				if ($insert) {
					//notif firebase
					parent::insertNotif($dataNotif);
					parent::sendNotifTopic("owner","Pengajuan Pinjaman","Karyawan mengajukan Pinjaman baru","004");
					// parent::sendNotifTopic("owner","payroll",$dataNotif['keterangan']);
					$this->response->status = true;
					$this->response->message = alertSuccess("Berhasil proses Tambah Data Pinjaman dan menunggu approval dari owner.");
				} else {
					$this->response->message = alertDanger("Gagal, tambah data Pinjaman.");
				}

			} else {
				// $this->response->message = validation_errors('<span style="color:red;">', '</span><br>');
				$this->response->error = array(
									"karyawan"			=> form_error("karyawan",'<span style="color:red;">','</span>'),
									"tanggal"			=> form_error("tanggal",'<span style="color:red;">','</span>'),
									"jlhPinjaman"		=> form_error("jlhPinjaman",'<span style="color:red;">','</span>'),
									"caraPembayaran"	=> form_error("caraPembayaran",'<span style="color:red;">','</span>'),
									"keterangans"		=> form_error("keterangan",'<span style="color:red;">','</span>'),
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
				$dataKaryawan = $this->pinjamanModel->getAllKaryawanAjax();
			} else {
				$searchTerm = $this->input->post("searchTerm");
				$dataKaryawan = $this->pinjamanModel->getAllKaryawanAjaxSearch($searchTerm);
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
			$data = $this->pinjamanModel->getIdKaryawan($id);
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
			$getById = $this->pinjamanModel->getDataKaryawanSelect($id);
			if ($getById) {
				$this->response->status = true;
				$this->response->message = "Data Pinjaman get by id";
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
			$getById = $this->pinjamanModel->getById($id);
			if ($getById) {
				if ($getById->foto != "") {
					$getById->foto = base_url("/")."uploads/master/karyawan/orang/".$getById->foto;
				} else {
					$getById->foto = base_url("/")."assets/images/default/no_user.png";
				}
				$this->response->status = true;
				$this->response->message = "Data Pinjaman get by id";
				$this->response->data = $getById;
			} else {
				$this->response->message = alertDanger("Data tidak ada.");
			}
		}
		parent::json();
	}

	public function getId1($id)
	{
		parent::checkLoginUser(); // user login autentic checking

		if ($this->isPost()) {
			$getById1 = $this->logpembayaranModel->getById1($id);
			if ($getById1) {
				$this->response->status = true;
				$this->response->message = "Data Log get by id";
				$this->response->data = $getById1;
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
			$getById = $this->pinjamanModel->getById($id);
			$setting = $this->pinjamanModel->get_setting();
			if ($getById) {
				if ($setting->logo != "") {
					$setting->logo = base_url("/")."uploads/setting/".$setting->logo;
				} else {
					$setting->logo = base_url("/")."assets/images/default/no_file_.png";
				}
				$getById->tanggal = date_ind("d M Y",$getById->tanggal);
				$getById->setting = $setting;
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
			// $getById = $this->pinjamanModel->getById($id);

			$tanggal = $this->input->post("tanggal");
			$karyawan = $this->input->post('karyawan');
			$jlhPinjaman = $this->input->post('jlhPinjaman');
			$caraPembayaran = $this->input->post('caraPembayaran');
			$cicilan = $this->input->post('cicilan');
			$keterangan = $this->input->post("keterangan");
			$payment = $this->input->post('pembpinjaman');

			// $this->form_validation->set_rules('tanggal', 'Tanggal', 'trim|required');
			// $this->form_validation->set_rules('karyawan', 'Karyawan', 'trim|required');
			$this->form_validation->set_rules('jlhPinjaman', 'Jumlah Pinjaman', 'trim|required');
			$this->form_validation->set_rules('caraPembayaran', 'Cara Pembayaran', 'trim|required');
			$this->form_validation->set_rules('keterangan', 'Keterangan', 'trim|required');

			$getkaryawan = $this->karyawanModel->getById($karyawan);

			if ($this->form_validation->run() == TRUE) {
				$data = array(
								"id_karyawan" => $karyawan,
								"tanggal"	=>	date("Y-m-d"),
								"jumlah" => $jlhPinjaman,
								"cara_pembayaran" => $caraPembayaran,
								"cicilan" => $cicilan,
								"keterangans"=>	$keterangan,
								"sisa" => $jlhPinjaman
							);
				$pesanNotifAndroid = " Edit/update data Pinjaman dengan nama= ".$getkaryawan->nama." dan no_induk= ".$getkaryawan->idfp;
				$dataNotif = array(
									"keterangan"=> 	" Edit/update data Pinjaman dengan nama=<u>".$getkaryawan->nama."</u> dan no_induk=<u>".$getkaryawan->idfp."</u>",
									"user_id"	=>	$this->user->id_pengguna,
									"level"		=>	"owner",
									"url_direct"=>	"approvalowner/Pinjaman",
								);
				$update = $this->pinjamanModel->update($id,$data,$dataNotif);
				if ($update) {
					//firebase
					parent::insertNotif($dataNotif);
					parent::sendNotifTopic("owner","Informasi","Perubahan Pinjaman ".$getkaryawan->nama,"041");
					// parent::sendNotifTopic("owner","payroll",$pesanNotifAndroid);
					$this->response->status = true;
					$this->response->message = alertSuccess("Berhasil proses update Data Pinjaman");
				} else {
					$this->response->message = alertDanger("Gagal, update data Pinjaman.");
				}
			} else {
				// $this->response->message = validation_errors('<span style="color:red;">', '</span><br>');
				$this->response->error = array(
									"karyawan"	=> form_error("karyawan",'<span style="color:red;">','</span>'),
									"tanggal"	=> form_error("tanggal",'<span style="color:red;">','</span>'),
									"jlhPinjaman"	=> form_error("jlhPinjaman",'<span style="color:red;">','</span>'),
									"caraPembayaran"	=> form_error("caraPembayaran",'<span style="color:red;">','</span>'),
									"caraPembayaran"	=> form_error("caraPembayaran",'<span style="color:red;">','</span>'),
									"keterangans"	=> form_error("keterangan",'<span style="color:red;">','</span>'),
								);
			}
		}
		parent::json();
	}

	public function updatePinjamans($id)
	{
		parent::checkLoginUser(); // user login autentic checking

		if ($this->isPost()) {
			$getById = $this->pinjamanModel->getById($id);
			$tanggal = $this->input->post("tanggal");
			$karyawan = $this->input->post('karyawan');
			$jlhPinjaman = $this->input->post('jlhPinjaman');
			$keterangan = $this->input->post("keterangan");
			$sisaPinjaman = $this->input->post('sisaPinjaman');
			$payment = $this->input->post('pembpinjaman');
			$hasil = intval($sisaPinjaman) - intval($payment);

			// $this->form_validation->set_rules('tanggal', 'Tanggal', 'trim|required');
			// $this->form_validation->set_rules('karyawan', 'Karyawan', 'trim|required');
			// $this->form_validation->set_rules('jlhPinjaman', 'Jumlah Pinjaman', 'trim|required');
			$this->form_validation->set_rules('keterangan', 'Keterangan', 'trim|required');
			$this->form_validation->set_rules('pembpinjaman', 'Pembayaran Pinjaman', 'trim|is_natural_no_zero|less_than['.($sisaPinjaman+1).']');

			$getkaryawan1 = $this->karyawanModel->getById($getById->id_karyawan);

			if ($this->form_validation->run() == TRUE) {
				$datas = array(
								"bayar" =>$payment,
								"sisa" => $hasil,
				);
				$datapinjamans = array(
								"id_pinjaman" => $id,
								"tanggal" => date("Y-m-d"),
								"log_sisa" => $hasil,
								"pembayaran" => $payment,
								"keterangan" => "Pembayaran tanggal ". date_ind("d M Y",date("Y-m-d")). " Sebesar ". "Rp.".number_format($payment,0,",",","),

				);


				$pesanNotifAndroid = " Data pembayaran pinjaman dengan nama= ".$getkaryawan1->nama." dan no_induk= ".$getkaryawan1->idfp;
				$dataNotif = array(
										"keterangan"=> 	" Data pembayaran pinjaman dengan nama=<u>".$getkaryawan1->nama."</u> dan no_induk=<u>".$getkaryawan1->idfp."</u>",
										"user_id"	=>	$this->user->id_pengguna,
										"level"		=>	"owner",
										"url_direct"=>	"approvalowner/pinjaman",
									);
				// $update = $this->pinjamanModel->DataPinjaman($id,$datas,$datapinjamans);
				$update = $this->pinjamanModel->insertUpdatePinjaman($id,$payment,$datapinjamans);
				if ($update) {
					//firebase
					parent::insertNotif($dataNotif);
					//notif android
					// parent::sendNotifTopic("owner","payroll",$pesanNotifAndroid);
					$this->response->status = true;
					$this->response->message = alertSuccess("Berhasil update Data pembayaran pinjaman dari karyawan.");
				} else {
					$this->response->message = alertDanger("Gagal, update data pembayaran pinjaman.");
				}
			} else {
				// $this->response->message = validation_errors('<span style="color:red;">', '</span><br>');
				$this->response->error = array(
									"karyawan"	=> form_error("karyawan",'<span style="color:red;">','</span>'),
									"tanggal"	=> form_error("tanggal",'<span style="color:red;">','</span>'),
									"jlhPinjaman"	=> form_error("jlhPinjaman",'<span style="color:red;">','</span>'),
									"pembpinjaman"	=> form_error("pembpinjaman",'<span style="color:red;">','</span>'),
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
			$getById = $this->pinjamanModel->getById($id);
			$getkaryawan = $this->karyawanModel->getById($getById->id_karyawan);
			if ($getById) {
				$pesanNotifAndroid = " Hapus data pinjaman dengan nama= ".$getkaryawan->nama." dan no_induk=<u>".$getkaryawan->idfp;
				$dataNotif = array(
										"keterangan"=> 	" Hapus data pinjaman dengan nama=<u>".$getkaryawan->nama."</u> dan no_induk=<u>".$getkaryawan->idfp."</u>",
										"user_id"	=>	$this->user->id_pengguna,
										"level"		=>	"owner",
										"url_direct"=>	"approvalowner/pinjaman",
									);
				$delete = $this->pinjamanModel->delete($id,$dataNotif);

				if ($delete) {
					//firebase
					parent::insertNotif($dataNotif);
					parent::sendNotifTopic("owner","Informasi","Pinjaman ini telah di hapus","042");
					//notif android
					// parent::sendNotifTopic("owner","payroll",$pesanNotifAndroid);
					$this->response->status = true;
					$this->response->message = alertSuccess("Berhasil proses hapus Data pinjaman dan menunggu approval dari owner.");
				} else {
					$this->response->message = alertDanger("Gagal, hapus data..");
				}
			} else {
				$this->response->message = alertDanger("Data sudah tidak ada.");
			}
		}
		parent::json();
	}

}

/* End of file Pinjaman.php */
/* Location: ./application/controllers/aktivitas/Pinjaman.php */
