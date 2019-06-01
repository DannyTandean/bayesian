<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Pinjaman extends MY_Controller {

	public function __construct()
	{
		parent::__construct();
		$this->load->model('aktivitas/Pinjaman_model',"pinjamanModel");
		$this->load->model('aktivitas/LogPembayaran_model',"logpembayaranModel");

	}

	public function index()
	{
		parent::checkLoginUser(); // user login autentic checking

		parent::headerTitle("Statistik Data > Pinjaman","Statistik Data","Pinjaman");
		$breadcrumbs = array(
							"Statistik"	=>	site_url('statistik/pinjaman'),
							"Pinjaman"		=>	"",
						);
		parent::breadcrumbs($breadcrumbs);
		$data = array();
		$data['karyawan'] = $this->pinjamanModel->getKaryawan();
		parent::viewData($data);

		parent::viewStatistik();

	}

	public function ajax_list($before=null,$after=null)
	{
		parent::checkLoginUser(); // user login autentic checking

		if ($this->isPost()) {
			$data = array();

			$orderBy = array(null,"tanggal","nama","departemen","jabatan","jumlah","bayar","sisa","cicilan","keterangans","status");
			$search = array("nama","departemen","jabatan");

			$result = $this->pinjamanModel->findDataTable($orderBy,$search,false,$after,$before);
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
				$item->cicilan = "Rp.".number_format($item->cicilan,0,",",",");
				$item->tanggal = date_ind("d M Y",$item->tanggal);
				$item->button_tool = $btnTools;
				$item->button_action = $btnAction;
				$data[] = $item;
			}
			return $this->pinjamanModel->findDataTableOutput($data,$search,false,$after,$before);
		}
	}

	public function ajax_list_after($before,$after)
	{
		self::ajax_list($before,$after);
	}

	public function getTotalPinjaman()
	{
		parent::checkLoginUser(); // user login autentic checking

		$money = $this->pinjamanModel->getMoney();
		$totalPinjaman = 0;
		// $totalBayar = 0;
		$totalSisa = 0;
		foreach ($money as $item) {
			$totalPinjaman += $item->jumlah;
			// $totalBayar += $item->bayar;
			$totalSisa += $item->sisa;
		}
		$bayar = $this->logpembayaranModel->getTotalPembayaran();

		$totalPinjaman = "Rp.".number_format($totalPinjaman,0,",",",");
		// $totalBayar = "Rp.".number_format($totalBayar,0,",",",");
		$totalSisa = "Rp.".number_format($totalSisa,0,",",",");

		$data = array(
									'pinjaman' => $totalPinjaman,
									'bayar'  => "Rp.".number_format($bayar->pembayaran,0,",",","),
									'sisa' => $totalSisa
		);

		$this->response->status = true;
		$this->response->message = "Data Pinjaman";
		$this->response->data = $data;

		parent::json();
	}

}

/* End of file Pinjaman.php */
/* Location: ./application/controllers/statistik/Pinjaman.php */
