<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Absensi extends MY_Controller {

	public function __construct()
	{
		parent::__construct();
		$this->load->model('approval/Tmp_absensi_model',"absensiModel");

		parent::checkLoginHRD();

	}

	public function index()
	{
		parent::checkLoginUser(); // user login autentic checking

		parent::headerTitle("Approval Owner > Absensi Karyawan","Approval Owner","Absensi Karyawan");
		$breadcrumbs = array(
							"Approval owner"	=>	site_url('approvalowner/absensi'),
							"Absensi"		=>	"",
						);
		parent::breadcrumbs($breadcrumbs);
		parent::viewApprovalOwner();
	}

	public function ajax_list()
	{
		parent::checkLoginUser(); // user login autentic checking

		if ($this->isPost()) {
			$data = array();

			$orderBy = array(null,null,"tanggal","nama","departemen","jabatan","nilai","keteranganr","status");
			$search = array("nama","departemen","jabatan");

			$result = $this->absensiModel->findDataTable($orderBy,$search);
			foreach ($result as $item) {
				$tempid = "'" . $item->id_temp_absensi . "'";
				if ($item->temp_status == "Diterima") {
					$btnAction = 'Sudah di Terima';
				}else if ($item->temp_status == "Ditolak") {
					$btnAction = 'Sudah di Tolak';
				}
				if($item->temp_status == "Proses")
				{
					// $btnTools = '<button class="btn btn-warning btn-outline-default btn-mini"><i class="fa fa-print"></i>Print</button>';
					$item->temp_status = '<label class="label label-info">'.$item->temp_status.'</label>';
					$btnAction = '<button class="btn btn-success btn-outline-success btn-mini" onclick="btnApprove('.$item->id_absensi.','.$tempid.')"><i class="fa fa-check-square"></i>Terima</button>';
					$btnAction .= '&nbsp;&nbsp;&nbsp;<button class="btn btn-danger btn-outline-danger btn-mini" onclick="btnReject('.$item->id_absensi.','.$tempid.')"><i class="fa fa-ban"></i>Tolak</button>';

				}
				else if($item->temp_status == "Diterima")
				{
					// $btnTools = '<button class="btn btn-default btn-outline-default btn-mini" onclick="btnPrint('.$item->id_izin.')"><i class="fa fa-print"></i>Print</button>';
					$item->temp_status = '<label class="label label-success">'.$item->temp_status.'</label>';
				}
				else {
					$item->temp_status = '<label class="label label-danger">'.$item->temp_status.'</label>';
					// $btnTools = '<button class="btn btn-warning btn-outline-default btn-mini"><i class="fa fa-print"></i>Print</button>';
				}
				$item->tanggal = date_ind("d M Y",$item->tanggal);
				$item->payment = "Rp.".number_format($item->payment,0,",",",");
				// $item->button_tool = $btnTools;
				$item->button_action = $btnAction;
				$data[] = $item;
			}
			return $this->absensiModel->findDataTableOutput($data,$search);
		}
	}

	public function getId($id)
	{
		parent::checkLoginUser(); // user login autentic checking

		if ($this->isPost()) {
			$getById = $this->absensiModel->getById($id);
			if ($getById) {
				if ($getById->foto != "") {
					$getById->foto = base_url("/")."uploads/master/karyawan/orang/".$getById->foto;
				} else {
					$getById->foto = base_url("/")."assets/images/default/no_user.png";
				}
				$this->response->status = true;
				$this->response->message = "Data Absensi get by id";
				$this->response->data = $getById;
			} else {
				$this->response->message = alertDanger("Data tidak ada.");
			}
		}
		parent::json();
	}

}

/* End of file Absensi.php */
/* Location: ./application/controllers/approvalowner/Absensi.php */
