<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Pph extends MY_Controller {

	public function __construct()
	{
		parent::__construct();
		$this->load->model('aktivitas/Pph_model',"pphModel");
    $this->load->model('aktivitas/Penggajian_payment_model',"ppmModel");
		parent::checkLoginOwner();
	}

	public function index()
	{
		parent::checkLoginUser(); // user login autentic checking

		parent::headerTitle("Aktivitas Data > SPT Pajak(PPh)","Aktivitas Data","SPT Pajak(PPh)");
		$breadcrumbs = array(
							"Aktivitas"		=>	site_url('aktivitas/pph'),
							"SPT PPh"	=>	"",
						);
		parent::breadcrumbs($breadcrumbs);

		parent::viewAktivitas();
	}

  public function ajax_list()
	{
		parent::checkLoginUser(); // user login autentic checking

		if ($this->isPost()) {
			$data = array();
			$orderBy = array(null,"nama","status_nikah","tanggungan","status_kerja","npwp");
			$search = array("nama","npwp");
			$result = $this->pphModel->findDataTable($orderBy,$search);
			foreach ($result as $item) {
				// var_dump(date('F') == date("F",strtotime($item->tanggal_proses)));
				// if(date('F') == date("F",strtotime($item->tanggal_proses))){
					$btnAction= "";
					$btnAction .= '&nbsp;&nbsp;<button class="btn btn-outline-info btn-mini" title="Detail"><i class="icofont icofont-ui-zoom-in"></i></button>';
					$item->btn_action = $btnAction;

					// var_dump($where);
					$data[] = $item;
				// }
			}
			return $this->pphModel->findDataTableOutput($data,$search,date('Y-m-d'));
		}
	}

	public function countAllData()
	{
		parent::checkLoginUser(); // user login autentic checking

		if ($this->isPost()) {
			$data = array();
			$result = $this->pphModel->getAllKaryawanPenggajian();
			foreach($result as $item){
				// var_dump(date('F') == date("F",strtotime($item->tanggal_proses)));
				if(date('F') == date("F",strtotime($item->tanggal_proses))){
					$item->penghasilan_bruto_teratur = ($item->gaji_bersih) + ($item->penggajian_tunjangan + $item->gaji_extra + $item->penggajian_kerajinan + $item->penggajian_makan + $item->penggajian_transport + $item->penggajian_tunlain + $item->penggajian_libur) + ($item->reward) + ($item->potongan_bpjs);
					$item->bruto_seluruhnya = $item->penghasilan_bruto_teratur + $item->penggajian_thr;
					$item->biaya_jabatan = $item->bruto_seluruhnya * 0.05;
					$item->bruto_setahun = ($item->penghasilan_bruto_teratur * 12) + $item->penggajian_thr;
					$item->biaya_jabatan_setahun = ($item->penghasilan_bruto_teratur * 0.05 * 12) + ($item->penggajian_thr * 0.05);
					$item->jumlah_pengurangan_setahun = $item->biaya_jabatan_setahun;
					$item->penghasilan_neto_setahun = $item->bruto_setahun - $item->jumlah_pengurangan_setahun;
					$item->ptkp = 54000000;
					if($item->status_nikah == "Menikah"){
						$item->ptkp += 4500000;
					}
					if($item->tanggungan >= 0 && $item->tanggungan <= 3){
						$item->ptkp += ($item->tanggungan * 4500000);
					}else{
						$item->ptkp += (3 * 4500000);
					}
					if($item->penghasilan_neto_setahun - $item->ptkp <= 0){
						$item->pkp_setahun = 0;
						$item->pkp_penghasilan_teratur_setahun = 0;
					}else{
						$item->pkp_setahun = $item->penghasilan_neto_setahun - $item->ptkp;
						$item->pkp_penghasilan_teratur_setahun = (($item->penghasilan_bruto_teratur * 12)-($item->penghasilan_bruto_teratur * 12 * 0.05)) - $item->ptkp;
						if($item->npwp != "-"){
							if($item->pkp_penghasilan_teratur_setahun <= 50000000){
								$item->pph21_teratur_setahun = $item->pkp_penghasilan_teratur_setahun * 0.05;
								$item->pph21_penghasilan_tidak_teratur = ($item->pkp_setahun - $item->pkp_penghasilan_teratur_setahun) * 0.05;
							}else if($item->pkp_penghasilan_teratur_setahun <= 250000000){
								$item->pph21_teratur_setahun = (50000000 * 0.05) + (($item->pkp_penghasilan_teratur_setahun - 50000000) * 0.15);
								$item->pph21_penghasilan_tidak_teratur = ($item->pkp_setahun - $item->pkp_penghasilan_teratur_setahun) * 0.15;
							}else if($item->pkp_penghasilan_teratur_setahun <= 500000000){
								$item->pph21_teratur_setahun = (50000000 * 0.05) + (200000000 * 0.15) + (($item->pkp_penghasilan_teratur_setahun - 250000000) * 0.25);
								$item->pph21_penghasilan_tidak_teratur = ($item->pkp_setahun - $item->pkp_penghasilan_teratur_setahun) * 0.25;
							}else{
								$item->pph21_teratur_setahun = (50000000 * 0.05) + (200000000 * 0.15) + (250000000 * 0.25) + (($item->pkp_penghasilan_teratur_setahun - 500000000) * 0.30);
								$item->pph21_penghasilan_tidak_teratur = ($item->pkp_setahun - $item->pkp_penghasilan_teratur_setahun) * 0.30;
							}
						}else{
							if($item->pkp_penghasilan_teratur_setahun <= 50000000){
								$item->pph21_teratur_setahun = $item->pkp_penghasilan_teratur_setahun * 0.06;
								$item->pph21_penghasilan_tidak_teratur = ($item->pkp_setahun - $item->pkp_penghasilan_teratur_setahun) * 0.06;
							}else if($item->pkp_penghasilan_teratur_setahun <= 250000000){
								$item->pph21_teratur_setahun = (50000000 * 0.06) + (($item->pkp_penghasilan_teratur_setahun - 50000000) * 0.18);
								$item->pph21_penghasilan_tidak_teratur = ($item->pkp_setahun - $item->pkp_penghasilan_teratur_setahun) * 0.18;
							}else if($item->pkp_penghasilan_teratur_setahun <= 500000000){
								$item->pph21_teratur_setahun = (50000000 * 0.06) + (200000000 * 0.18) + (($item->pkp_penghasilan_teratur_setahun - 250000000) * 0.30);
								$item->pph21_penghasilan_tidak_teratur = ($item->pkp_setahun - $item->pkp_penghasilan_teratur_setahun) * 0.30;
							}else{
								$item->pph21_teratur_setahun = (50000000 * 0.06) + (200000000 * 0.18) + (250000000 * 0.30) + (($item->pkp_penghasilan_teratur_setahun - 500000000) * 0.36);
								$item->pph21_penghasilan_tidak_teratur = ($item->pkp_setahun - $item->pkp_penghasilan_teratur_setahun) * 0.36;
							}
						}
						$item->pph21_terutang_setahun = $item->pph21_teratur_setahun + $item->pph21_penghasilan_tidak_teratur;
						$item->pph21_bulan_ini = ($item->pph21_teratur_setahun / 12) + $item->pph21_penghasilan_tidak_teratur;
					}
				}
				var_dump($item);
			}
		}
	}
}

/* End of file pph.php */
/* Location: ./application/controllers/aktivitas/pph.php */
