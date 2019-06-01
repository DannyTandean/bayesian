<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Pinjaman extends MY_Controller {

	public function __construct()
	{
		parent::__construct();
		$this->load->model('aktivitas/Pinjaman_model',"pinjamanModel");
		$this->load->model('master/Karyawan_model',"karyawanModel");
		$this->load->model('Token_model',"tokenModel");
		parent::checkLoginHRD();
	}

	public function index()
	{
		parent::checkLoginUser(); // user login autentic checking

		parent::headerTitle("Approval Data > Pinjaman","Approval Data","Pinjaman");
		$breadcrumbs = array(
							"Approval"	=>	site_url('approvalowner/pinjaman'),
							"Pinjaman"		=>	"",
						);
		parent::breadcrumbs($breadcrumbs);
		parent::viewApprovalOwner();
	}

	public function ajax_list()
	{
		parent::checkLoginUser(); // user login autentic checking

		if ($this->isPost()) {
			$data = array();

			$orderBy = array(null,null,null,"tanggal","nama","departemen","status","jabatan","jumlah","bayar","sisa","cicilan","keterangans");
			$search = array("nama","departemen","jabatan");

			$result = $this->pinjamanModel->findDataTable($orderBy,$search,false,null,null);
			foreach ($result as $item) {
				if ($item->status == "Diterima") {
					$btnAction = 'Sudah di Terima';

				}elseif($item->status == "Ditolak"){
					$btnAction = 'Sudah di Tolak';
				}
				if($item->status == "Proses")
				{
					$btnTools = '<button class="btn btn-warning btn-outline-default btn-mini"><i class="fa fa-print"></i>Print</button>';
					$item->status = '<label class="label label-info">'.$item->status.'</label>';
					$btnAction = '<button class="btn btn-success btn-outline-success btn-mini" onclick="btnApproval('.$item->id_pinjaman.')"><i class="fa fa-check-square"></i>Terima</button>';
					$btnAction .= '&nbsp;&nbsp;&nbsp;<button class="btn btn-danger btn-outline-danger btn-mini" onclick="btnReject('.$item->id_pinjaman.')"><i class="fa fa-ban"></i>Tolak</button>';
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


				$item->tanggal = date_ind("d M Y",$item->tanggal);
				$item->bayar = "Rp.".number_format($item->bayar,0,",",",");
				$item->jumlah = "Rp.".number_format($item->jumlah,0,",",",");
				$item->sisa = "Rp.".number_format($item->sisa,0,",",",");
				$item->cicilan = "Rp.".number_format($item->cicilan,0,",",",");
				$item->button_tool = $btnTools;
				$item->button_action = $btnAction;
				$data[] = $item;
			}
			return $this->pinjamanModel->findDataTableOutput($data,$search,false,null,null);
		}
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

	public function update($id)
	{
		parent::checkLoginUser(); // user login autentic checking

		if ($this->isPost()) {
			$status = $this->input->post('status');
			$getById = $this->pinjamanModel->getById($id);
			$getkaryawan = $this->karyawanModel->getById($getById->id_karyawan);
				$data = array(
								"status" => $status
							);
				$pesanNotif = "";
				if($status == "Diterima"){
					$pesanNotif = "Owner <u style='color:green;'>Approval</u>";
					$pesanNotifAndro = "Owner Approval";
				}
				else {
					$pesanNotif = "Owner <u style='color:red;'>Reject</u>";
					$pesanNotifAndro = "Owner Reject";
				}
				$pesanNotifAndroid = $pesanNotifAndro." data pinjaman dengan nama= ".$getkaryawan->nama." dan no_induk= ".$getkaryawan->idfp;
				$dataNotif = array(
									"keterangan"=> $pesanNotif." data pinjaman dengan nama=<u>".$getkaryawan->nama."</u> dan no_induk=<u>".$getkaryawan->idfp."</u>.",
									"user_id"	=>	$this->user->id_pengguna,
									"level"		=>	"hrd",
									"url_direct"=>	"aktivitas/pinjaman",
								);
				$update = $this->pinjamanModel->update($id,$data,$dataNotif);
				$token = $this->tokenModel->getByIdfp($getkaryawan->idfp);
				$count = $this->tokenModel->getByIdfpCount($getkaryawan->idfp);
				if ($update) {
					//notif firebase
					parent::insertNotif($dataNotif);
					if ($count != 0) {
						parent::pushnotif($token,"Approval Pinjaman","Owner Menerima data Pinjaman","051");
					}
					parent::sendNotifTopic("hrd","Approval Pinjaman Karyawan","Owner menerima Pinjaman karyawan","051");
					// parent::sendNotifTopic("hrd","payroll",$pesanNotifAndroid);
					$this->response->status = true;
					$this->response->message = alertSuccess("Berhasil update data Pinjaman.");
				} else {
					$this->response->message = alertDanger("Gagal update data Pinjaman.");
				}
			}
		parent::json();
	}

}

/* End of file Pinjaman.php */
/* Location: ./application/controllers/approvalowner/Pinjaman.php */
