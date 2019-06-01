<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class LaporanPenggajian extends MY_Controller {

	public function __construct()
	{
		parent::__construct();
		$this->load->model('pekerjaborongan/Penggajian_model',"penggajianModel");
		$this->load->model('pekerjaborongan/Produksi_model', "produksiModel");
		$this->load->model('pekerjaborongan/Pekerja_model', "pekerjaModel");
		$this->load->model('Setting_model',"settingModel");
	}

	public function index()
	{
		parent::checkLoginUser(); // user login autentic checking

		parent::headerTitle("Pekerja Borongan > Penggajian Karyawan","Pekerja Borongan","Penggajian Karyawan");
		$breadcrumbs = array(
							"Pekerja Borongan"	=>	site_url('pekerjaborongan/LaporanPenggajian'),
							"Laporan Penggajian"		=>	"",
						);
		parent::breadcrumbs($breadcrumbs);
		parent::viewPekerjaBorongan();

	}

	public function ajax_list($before=null,$after=null)
	{
		parent::checkLoginUser(); // user login autentic checking

		if ($this->isPost()) {
			$data = array();

			$orderBy = array(null,null,"kode_payroll","idfp","nama","gaji_total","tanggal_proses");
			$search = array("kode_payroll","idfp","nama","tanggal_proses");
			$total1 = 0;
			$result = $this->penggajianModel->findDataTable($orderBy,$search,$after,$before);
			foreach ($result as $item) {
				$kodepayroll = "'" . $item->kode_payroll . "'";
				$btnTools = '<button class="btn btn-default btn-outline-default btn-mini" onclick="btnPrint('.$item->id_penggajian.','.$kodepayroll.')"><i class="fa fa-print"></i>Print</button>';

//				 $btnTools .= '<button class="btn btn-outline-info btn-mini" title="Detail" onclick="btnDetail('.$item->id_penggajian.')"><i class="icofont icofont-ui-zoom-in"></i></button>';
				$item->tanggal_proses =  date_ind("d M Y",$item->tanggal_proses);
				$total1 += intval($item->total_item);
				$item->gaji_total = "Rp.".number_format($item->gaji_total,0,",",",");
				$item->button_tool = $btnTools;
				$item->total = "Rp.".number_format($total1,0,",",",");
				$data[] = $item;
			}
			return $this->penggajianModel->findDataTableOutput($data,$search,$after,$before);
		}
	}

	public function ajax_list_after($before,$after)
	{
	 	self::ajax_list($before,$after);
	}

}
