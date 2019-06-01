<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Penggajian extends MY_Controller {

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
							"Pekerja Borongan"	=>	site_url('pekerjaborongan/penggajian'),
							"Penggajian"		=>	"",
						);
		parent::breadcrumbs($breadcrumbs);
		$data = array();
		$data['kode_payroll'] = $this->penggajianModel->getListKodePayroll();
		parent::viewData($data);
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

				// $btnTools .= '<button class="btn btn-outline-info btn-mini" title="Detail" onclick="btnDetail('.$item->id_penggajian.')"><i class="icofont icofont-ui-zoom-in"></i></button>';
				$item->tanggal_proses =  date_ind("d M Y",$item->tanggal_proses);
				$total1 += intval($item->gaji_total);
				$item->gaji_total = "Rp.".number_format($item->gaji_total,0,",",",");
				$item->button_tool = $btnTools;
				$item->total = "Rp.".number_format($total1,0,",",",");
				$data[] = $item;
			}
			return $this->penggajianModel->findDataTableOutput($data,$search,$after,$before);
		}
	}


	public function generate_penggajian()
	{
		//check login
		parent::checkLoginUser();

		if ($this->isPost()) {
			$periode = $this->input->post('periode');
			$before = $this->input->post('before');
			$after = $this->input->post('after');

			$kodePayroll = "";
			$kode_akhir = $this->penggajianModel->getAll(false,array("kode_payroll"),array("LPAD(lower(kode_payroll), 20,0) DESC"));
			if ($kode_akhir == null) {
				$kodePayroll = 'PAYROLL-1';
			}
			else {
				$kodeUrut = (int) substr($kode_akhir[0]->kode_payroll, strpos($kode_akhir[0]->kode_payroll, '-') + 1);
				$kodePayroll = 'PAYROLL-'.($kodeUrut + 1);
			}
			$time = strtotime($before);
			$hari = explode('-',date("Y-m-t",$time));

			$begin = date("Y-m-1",$time);
			$end = date("Y-m-t",$time);

			$begin1 = new DateTime($begin);
			$end1 = new DateTime($end);
			//tanggal dinamis


			$periodeKaryawan = $this->pekerjaModel->periodeGaji($periode);
			$hasilGaji = array();
			$data = array();
			if ($periodeKaryawan != null) {
				foreach ($periodeKaryawan as $val) {
					$data[] = $this->pekerjaModel->getDataPekerja($val->id_pekerja);

				}
			}
			else {
				$data[] = 0;
			}
			$totalPendapatan = 0;
			$totalItem = 0;
			$row = array(); // isi id karyawan
			$row1 = array(); // total perhitungan gaji golongan
			$dataPenggajianALl = array();
			$dataFilterPenggajian = $this->penggajianModel->getValidasiDataPenggajian($periode);
			//check
			if (($dataFilterPenggajian != null) && (strtotime($before) <= strtotime($dataFilterPenggajian->end_date))) {
				$this->response->message = "<span style='color:red;'>Data yang ditambahkan sudah ada </span>";
			}
			else {

					$pendapatan = $this->produksiModel->getPekerja($before,$after);
					if ($pendapatan != null) {
						foreach ($pendapatan as $key => $vals1) {
						$totalHari = $this->produksiModel->getHariKerja($before,$after,$vals1->id_pekerja);
						$totalPendapatan += $vals1->pendapatans;
						$totalItem += $vals1->jumlahs;
							$dataPenggajianPerOrang = array(
																					'kode_payroll' => $kodePayroll,
																					'id_pekerja' => $vals1->id_pekerja,
																					'tanggal_proses' => date("Y-m-d"),
																					'hari_kerja' => $totalHari,
																					'start_date' => $before,
																					'end_date' => $after,
																					'periode_penggajian' => $periode,
																					'gaji_total' => $vals1->pendapatans,
																					'total_item' => $vals1->jumlahs,
																					'keterangan' => $kodePayroll . " Periode " . $before . " s/d " . $after
																				 );

				$dataPenggajianALl[] = $dataPenggajianPerOrang;

				}
				$insert = $this->penggajianModel->insert($dataPenggajianALl);
				if ($insert) {
					//notif firebase
					// parent::insertNotif($dataNotif);
					$this->response->status = true;
					$this->response->message = "<span style='color:green;'>Berhasil proses Tambah Data Penggajian.</span>";
				} else {
					$this->response->message = "<span style='color:red;'>Gagal, tambah data Penggajian.</span>";
				}
					}
					else{
						$this->response->message = "<span style='color:red;'>Karyawan Tidak Ada.</span>";
					}



			}
		}
		parent::json();
	}

	public function getSlipGajiKaryawan($id,$kodePayroll)
	{
		if ($this->isPost()) {
			$dataPenggajianKaryawan = $this->penggajianModel->getDataPenggajianKaryawan($id);
			// var_dump($dataPenggajianKaryawan);
			// exit();
			// $dataAbsensi = $this->logabsensiModel->DataPerBulan($dataPenggajianKaryawan->start_date,$dataPenggajianKaryawan->end_date,$dataPenggajianKaryawan->kode_karyawan);
			$dataKaryawan = $this->pekerjaModel->getDataLengkap($dataPenggajianKaryawan->id_pekerja,$kodePayroll);

			$dataSetting = $this->settingModel->getById(1);
			$dataDepartemenKaryawan = $this->penggajianModel->getDataPenggajianPerKaryawan($dataPenggajianKaryawan->id_pekerja,$kodePayroll);
			// var_dump($dataDepartemenKaryawan);
			// exit();
			$dataProduksiPenggajian = $this->produksiModel->getDataProduksiPenggajian($dataDepartemenKaryawan->id_pekerja,$dataDepartemenKaryawan->start_date,$dataDepartemenKaryawan->end_date);
			//proses perhitungan
			// $dataDepartemenKaryawan->start_date,$dataDepartemenKaryawan->end_date
			$totalItem = 0;
			$totalPendapatan = 0;
			foreach ($dataProduksiPenggajian as $key => $value) {
				$totalItem += $value->jumlah;
				$totalPendapatan += $value->pendapatan;
			}
			$begin  = new DateTime(date("Y-m-1"));
			$end    = new DateTime(date("Y-m-t"));

			$hasilGaji = array();
			$row = array(); // isi id karyawan
			$row1 = array(); // total perhitungan gaji golongan

			$hariKerja = $dataPenggajianKaryawan->hari_kerja;
			// $totalItem = $dataPenggajianKaryawan->total_item;
			$totalGaji = $dataPenggajianKaryawan->gaji_total;
			$harga1 =  $dataDepartemenKaryawan->harga;

			$dataPerhitunganGaji = array(
																		'totalGaji' =>"Rp.".number_format($totalGaji,0,",",","),
																		'totalItem1' => $totalItem,
																		'hariKerja1' => $hariKerja,
																		'harga1' =>"Rp.".number_format($harga1,0,",",","),
																		'totalPendapatan' => "Rp.".number_format($totalPendapatan,0,",",",")
																	);
			$this->response->status = true;
			// $this->response->data = $dataAbsensi;
			$this->response->data1 = $dataKaryawan;
			$this->response->data2 = $dataSetting->nama_perusahaan;
			$this->response->data4 = $dataPerhitunganGaji;
			$this->response->data5 = $dataProduksiPenggajian;
		}
		parent::json();
	}

	public function getSlipGajiKaryawanPeriode($kodePayroll)
	{
		if ($this->isPost()) {
			$dataPenggajianKaryawan = $this->penggajianModel->getDataPenggajianPeriode($kodePayroll);
			// var_dump($dataPenggajianKaryawan);
			// exit();
			// $dataAbsensi = $this->logabsensiModel->DataPerBulan($dataPenggajianKaryawan->start_date,$dataPenggajianKaryawan->end_date,$dataPenggajianKaryawan->kode_karyawan);
			$listDataPenggajian = array();

			foreach ($dataPenggajianKaryawan as $key => $val) {
				$dataKaryawan = $this->pekerjaModel->getDataLengkap($val->id_pekerja,$kodePayroll);
				$dataSetting = $this->settingModel->getById(1);
				$dataDepartemenKaryawan = $this->penggajianModel->getDataPenggajianPerPeriode($val->id_pekerja,$kodePayroll);
				// var_dump($dataDepartemenKaryawan);
				// exit();
				// $dataProduksiPenggajian = $this->produksiModel->getDataProduksiPenggajian($val->id_pekerja,$dataDepartemenKaryawan[0]->start_date,$dataDepartemenKaryawan[0]->end_date);
				//proses perhitungan
				// $dataDepartemenKaryawan[0]->start_date,$dataDepartemenKaryawan[0]->end_date
				$totalItem = 0;
				// $totalPendapatan = 0;
				// foreach ($dataProduksiPenggajian as $key => $value) {
				// 	$totalItem += $value->jumlah;
				// 	$totalPendapatan += $value->pendapatan;
				// }
				$begin  = new DateTime(date("Y-m-1"));
				$end    = new DateTime(date("Y-m-t"));

				$hasilGaji = array();
				$row = array(); // isi id karyawan
				$row1 = array(); // total perhitungan gaji golongan

				$hariKerja = $val->hari_kerja;
				$totalItem = $val->total_item;
				$totalGaji = $val->gaji_total;
				// $harga1 =  $dataDepartemenKaryawan->harga;

				$dataPerhitunganGaji = array(
					'totalGaji' =>"Rp.".number_format($totalGaji,0,",",","),
					'totalItem1' => $totalItem,
					'hariKerja1' => $hariKerja,
					// 'harga1' =>"Rp.".number_format($harga1,0,",",","),
					// 'totalPendapatan' => "Rp.".number_format($totalPendapatan,0,",",","),
					'nama_perusahaan' => $dataSetting->nama_perusahaan,
					'data_karyawan' => $dataDepartemenKaryawan
				);
				$listDataPenggajian[] = $dataPerhitunganGaji;
			}
			$this->response->status = true;
			// $this->response->data = $dataAbsensi;
			$this->response->data = $listDataPenggajian;
			// $this->response->data1 = $dataKaryawan;
			// $this->response->data2 = $dataSetting->nama_perusahaan;
			// $this->response->data4 = $dataPerhitunganGaji;
			// $this->response->data5 = $dataProduksiPenggajian;
		}
		parent::json();
	}

}
