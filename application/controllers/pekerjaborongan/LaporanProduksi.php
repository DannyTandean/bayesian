<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class LaporanProduksi extends MY_Controller {

	public function __construct()
	{
		parent::__construct();
		$this->load->model('pekerjaborongan/Produksi_model',"produksiModel");
		$this->load->model('pekerjaborongan/Pekerja_model',"pekerjaModel");

		parent::checkLoginOwner();

	}

	public function index()
	{
		parent::checkLoginUser(); // user login autentic checking

		parent::headerTitle("Pekerja Borongan > Laporan Produksi Karyawan","Pekerja Borongan","laporan produksi Karyawan");
		$breadcrumbs = array(
							"Pekerja Borongan"	=>	site_url('pekerjaborongan/laporanproduksi'),
							"Laporan Produksi"		=>	"",
						);
		parent::breadcrumbs($breadcrumbs);
		parent::viewPekerjaBorongan();

	}

	public function ajax_list($before=null,$after=null)
	{
		parent::checkLoginUser(); // user login autentic checking

		if ($this->isPost()) {
			$data = array();

			$orderBy = array(null,"tanggal","kode","departemen","jumlah","harga","pendapatan");
			$search = array("nama","departemen");

			$result = $this->produksiModel->findDataTableLaporan($orderBy,$search,$before,$after);
			foreach ($result as $item) {

				$item->harga = "Rp.".number_format($item->harga,0,",",".");
				// $item->pendapatan = "Rp.".number_format($item->pendapatan,0,",",".");
				$item->bulan = date_ind("M",$item->tanggal);

				$data[] = $item;
			}
			return $this->produksiModel->findDataTableOutputLaporan($data,$search,$before,$after);
		}
	}

	public function ajax_list_after($before,$after)
	{
		self::ajax_list($before,$after);
	}

}

/* End of file LaporanProduksi.php */
/* Location: ./application/controllers/aktivitas/LaporanProduksi.php */
