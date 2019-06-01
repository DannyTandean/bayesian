<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Penggajian_payment extends MY_Controller {

	public function __construct()
	{
		parent::__construct();
		$this->load->model('aktivitas/Penggajian_payment_model',"penggajianModel");
		$this->load->model('aktivitas/LogAbsensi_model',"logabsensiModel");
		$this->load->model('master/Karyawan_model', "karyawanModel");
		$this->load->model('rwd/Punishment_model',"punishModel");
		$this->load->model('rwd/Reward_model',"rewardModel");
		$this->load->model('aktivitas/Cuti_model','cutiModel');
		$this->load->model('THR_model','thrModel');
		$this->load->model('aktivitas/ExtraAbsensi_model',"ExtraabsensiModel");
		$this->load->model('master/Jabatan_model',"jabatanModel");
		$this->load->model('Setting_model',"settingModel");
		$this->load->model('aktivitas/Pinjaman_model',"pinjamanModel");
		$this->load->model('master/Shift_model','shiftModel');
		$this->load->model('Calendar_model',"calendarModel");
		$this->load->model('aktivitas/Pph_model',"pphModel");
	}

	public function index()
	{
		parent::checkLoginUser(); // user login autentic checking

		parent::headerTitle("Aktivitas Data > Penggajian Karyawan","Aktivitas Data","Penggajian Karyawan");
		$breadcrumbs = array(
							"Aktivitas Data"	=>	site_url('aktivitas/Penggajian_payment'),
							"Penggajian"		=>	"",
						);
		parent::breadcrumbs($breadcrumbs);
		// $data = array();
		// $data['karyawan'] = $this->karyawanModel->getAll();
		// parent::viewData($data);
		parent::viewAktivitas();
	}

	public function ajax_list($before=null,$after=null)
	{
		parent::checkLoginUser(); // user login autentic checking

		if ($this->isPost()) {
			$data = array();

			// $orderBy = array(null,"tanggal","nilai_bayar","sisa","keterangan");
			// $search = array("tanggal","nilai_bayar","sisa","keterangan");
			$orderBy = array(null,null,"nama",'idfp','jabatan','departemen','cabang');
			$search = array("nama","idfp","jabatan","departemen","cabang");
			$result = $this->penggajianModel->findDataTable($orderBy,$search);
			foreach ($result as $item) {
				// $kodepayroll = "'" . $item->kode_payroll . "'";
				// $btnTools = '<button class="btn btn-default btn-outline-primary btn-mini" onclick="btnSlip('.$item->id.')"><i class="fa fa-print"></i>Slip</button>';
				$btnTools = '&emsp;<button class="btn btn-default btn-outline-success btn-mini" onclick="btnPay('.$item->id.')"><i class="fa fa-credit-card"></i>Payment</button>';
				// $item->tanggal =  date_ind("d M Y",$item->tanggal);
				// $item->nilai_bayar = "Rp.".number_format($item->nilai_bayar,0,",",",");
				// $item->sisa = "Rp.".number_format($item->sisa,0,",",",");
				$item->button_tool = $btnTools;
				$data[] = $item;
			}
			return $this->penggajianModel->findDataTableOutput($data,$search);
		}
	}

	public function ajax_list_log($before=null,$after=null)
	{
		parent::checkLoginUser(); // user login autentic checking

		if ($this->isPost()) {
			$data = array();

			// $orderBy = array(null,"tanggal","nilai_bayar","sisa","keterangan");
			// $search = array("tanggal","nilai_bayar","sisa","keterangan");
			$orderBy = array(null,null,'tanggal_proses',"nama",'idfp','jabatan','departemen','cabang','hari_kerja','nilai_bayar','sisa');
			$search = array("nama","idfp","jabatan","departemen","cabang","tanggal_proses");
			$result = $this->penggajianModel->findDataTableLog($orderBy,$search,$after,$before);
			foreach ($result as $item) {
				// $kodepayroll = "'" . $item->kode_payroll . "'";
				// $btnTools = '<button class="btn btn-default btn-outline-primary btn-mini" onclick="btnSlip('.$item->id.')"><i class="fa fa-print"></i>Slip</button>';
				$btnTools = '&emsp;<button class="btn btn-default btn-outline-primary btn-mini" onclick="btnSlip('.$item->id_penggajian.')"><i class="fa fa-credit-card"></i>Print</button>';
				$item->tanggal_proses =  date_ind("d M Y",$item->tanggal_proses);
				$item->nilai_bayar = "Rp.".number_format($item->nilai_bayar,0,",",",");
				$item->sisa = "Rp.".number_format($item->sisa,0,",",",");
				$item->button_tool = $btnTools;
				$data[] = $item;
			}
			return $this->penggajianModel->findDataTableOutputlog($data,$search,$after,$before);
		}
	}

	public function ajax_list_log_after($before,$after)
	{
		self::ajax_list_log($before,$after);
	}

	public function allKaryawanAjax()
	{
		parent::checkLoginUser(); // user login autentic checking

		if ($this->isPost()) {
			if (!isset($_POST["searchTerm"])) {
				$dataKaryawan = $this->penggajianModel->getAllKaryawanAjax();
			} else {
				$searchTerm = $this->input->post("searchTerm");
				$dataKaryawan = $this->penggajianModel->getAllKaryawanAjaxSearch($searchTerm);
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

	public function getRekapPenggajian($before,$after,$periode,$bpjs1,$tunjangan1,$waktu)
	{
		if ($this->isPost()) {

			$listKaryawan = $this->penggajianModel->getAllKaryawan($periode);
			$allDataPenggajian = array();
			$today = "";
			foreach ($listKaryawan as $key => $karyawan) {
				$getById = $this->penggajianModel->getById($karyawan->id);

					$dataTotalHari = $this->settingModel->getTotalHari();
					$dataTerakhirPenggajian = $this->penggajianModel->getDataTerakhirPenggajian($karyawan->id);
					$setting = $this->settingModel->getById(1);
							// if ($getById) {
									if ($dataTerakhirPenggajian == null) {
										continue;
									}
									else {
										$checkperiode = true;
										if ($checkperiode) {
											$today = $after;
											$dataTerakhirPenggajian->tanggal = $before;
										}
										else {
											$today = date("Y-m-d");
										}
										// var_dump($dataTerakhirPenggajian->kode);
										$dataAbsenToken = $this->penggajianModel->getDataPerBulan($dataTerakhirPenggajian->tanggal,$today);
										$kode = $this->penggajianModel->idToKode($karyawan->id);
										$dataPinjaman = $this->pinjamanModel->getDataPinjamanPenggajian($karyawan->id);
										$reward = $this->rewardModel->getRewardPenggajian($dataTerakhirPenggajian->tanggal,$today,$karyawan->id);
										$punishment = $this->punishModel->getPunishmentPenggajian($dataTerakhirPenggajian->tanggal,$today,$karyawan->id);
										$thr = $this->thrModel->getThrPenggajian($dataTerakhirPenggajian->tanggal,$today,$kode->agama);
										$extra = $this->ExtraabsensiModel->getExtraPenggajian($dataTerakhirPenggajian->tanggal,$today,$kode->kode_karyawan);
										$jabatan = $this->jabatanModel->getDataPenggajianJabatan($karyawan->id,$dataTerakhirPenggajian->tanggal,$today);
										$token = $this->logabsensiModel->perBulan($dataTerakhirPenggajian->tanggal,$today,$kode->kode_karyawan,null);
										// $hariLibur = $this->calendarModel->getHariLibur($dataTerakhirPenggajian->tanggal,$today);
										$listCalendar = $this->calendarModel->getListCalendar($dataTerakhirPenggajian->tanggal,$today);
										$totalHari = (int)$dataTotalHari->total_hari;
										// $totalGaji = 0;
										$totalHK = 0;
										$totalBreakPunishment = 0;
										$potonganTelat = 0;

										foreach ($dataAbsenToken as $key => $val) {
											// $totalGaji += $val->payment;
											// if (($val->telat >= 6) && ($val->telat <= 10)) {
											// 	$potonganTelat += 10000;
											// }
											// else if (($val->telat >= 11) && ($val->telat <= 29)) {
											// 	$potonganTelat += 25000;
											// }
											$totalBreakPunishment += $val->break_punishment;
										}
										if ($tunjangan1 == "ya") {
											if ($jabatan) {
												$totalTunjanganJabatan = $jabatan->tunjangan;
											}
											else {
												$totalTunjanganJabatan = 0;
											}
										}
										else {
											$totalTunjanganJabatan = 0;
										}

										$totalThr = 0;
										$gajiBersih = 0;
										$totalGaji = 0;
										$totalExtra = 0;
										$totalTelat = 0;
										$potonganTelat = 0;
										$potongPinjaman = 0;
										$totalPotongan = 0;
										$totalTransport = 0;
										$totalMakan = 0;
										$totalKerajinan = 0;
										$totalLibur = 0;

										$telat = $this->logabsensiModel->getDataTelat($dataTerakhirPenggajian->tanggal,$today,$kode->kode_karyawan);

										foreach ($telat as $value) {
											$totalTelat += $value->telat;
											if ($value->telat != 0) {
												if (($value->telat >= $setting->telat1) && ($value->telat <= $setting->telat2)) {
													$potonganTelat += $setting->potongan_telat1;
												}
												else if (($value->telat >= $setting->telat2) && ($value->telat <= $setting->telat3)) {
													$potonganTelat += $setting->potongan_telat2;
												}
											}
										}
										if ($dataPinjaman == null) {
											$potongPinjaman = 0;
										}
										else {
											//pinjaman
											$potongPinjaman = $dataPinjaman->cicilan;
											if ($dataPinjaman->sisa <= $potongPinjaman) {
												$potongPinjaman = $dataPinjaman->sisa;
											}
											else {
												$potongPinjaman = $dataPinjaman->cicilan;
											}
										}
										// token
										if ($token[0]->token == null) {
											$token[0]->token = 0;
										}
										//reward
										if ($reward[0]->nilai == null) {
											$reward[0]->nilai = 0;
										}
										//punishment
										if ($punishment[0]->nilai == null) {
											$punishment[0]->nilai = 0;
										}
										//thr
										if ($thr != null &&($thr->agama == $kode->agama)) {
											$totalThr = (int)$kode->thr;
										}

										//Kerajinan
										if ($setting->opsi_kerajinan == 1) {
											$jumlahAbsensi = $this->logabsensiModel->jumlahAbsensi($dataTerakhirPenggajian->tanggal,$today,$kode->kode_karyawan);
											$totalAbsen = (int)$setting->total_hari - 2;
											if ($jumlahAbsensi >= $totalAbsen) {
												$totalKerajinan += $setting->kerajinan;
											}
										}

										// if ($kode->lembur == 1) {
										// 	$dataExtraperiode = $this->ExtraabsensiModel->getDataPerPeriode($dataTerakhirPenggajian->tanggal,$today,$kode->kode_karyawan);
										// 	foreach ($dataExtraperiode as $values) {
										// 		if ($values->total == 0) {
										// 			$values->total = 0;
										// 		}
										// 		if ($values->total <= 60) {
										// 			$totalExtra += 1.5 * ((1/173*$kode->umk)/60) * $values->total;
										// 		}
										// 		else {
										// 			$totalExtra += 1.5 * ((1/173*$kode->umk)/60) * $values->total;
										// 			$totalExtra += 2 * ((1/173*$kode->umk)/60) * ($values->total - 60);
										// 		}
										// 	}
										// 	// $totalExtra += 1.5 * ((1/173*$val->umk)/60) * $extra->total;
										// }
										// else {
										// 	if ($extra[0]->total == null) {
										// 		$extra[0]->total = 0;
										// 	}
										// 	if ($kode->lembur_tetap == 0 || $kode->lembur_tetap == null) {
										// 		$kode->lembur_tetap = 0;
										// 	}
										// 	$totalExtra += ($kode->lembur_tetap/60) * $extra[0]->total;
										// }


										if ($setting->extra_approval == 0) {
											 $dataExtraperiode = $this->ExtraabsensiModel->getDataPerPeriode($dataTerakhirPenggajian->tanggal,$today,$kode->kode_karyawan);
											 foreach ($dataExtraperiode as $values) {
												 $totalExtra += $values->payment;
											 }
										}
										else {
											$dataExtraperiode = $this->ExtraabsensiModel->getDataPerPeriodeStatus($dataTerakhirPenggajian->tanggal,$today,$kode->kode_karyawan,"Diterima");
											foreach ($dataExtraperiode as $values) {
												$totalExtra += $values->payment;
											}
										}

										$dataAbsensiKaryawan = $this->logabsensiModel->DataPerBulanShift($dataTerakhirPenggajian->tanggal,$today,$kode->kode_karyawan,null);
										$tglCalendar = array();
										foreach ($listCalendar as $key => $calendar) {
											$tglCalendar[] = $calendar->tanggal;
										}
										//gaji hari libur
										// $totalGaji += sizeof($tglCalendar) * $kode->gaji;
										foreach ($dataAbsensiKaryawan as $vals) {
											if (($vals->kerja == "Normal" || $vals->kerja == "Normal(Manual)") && $vals->payment == 0) {
												continue;
											}
											if (strtotime($vals->tanggal) == strtotime($today) && (strtotime($waktu.":00") <= strtotime($vals->masuk))) {
												continue;
											}
											else {
												if (in_array($vals->tanggal,$tglCalendar)) {
													$totalHK += 1;
												}
											$totalHK += 1;
											$totalTransport += $kode->transport;
											$totalMakan += $kode->makan;
											//
											// if (in_array($vals->tanggal,$tglCalendar)) {
											// 	$totalGaji += $kode->gaji;
											// }
											$totalLibur += $vals->tambahan;
											$totalGaji += $vals->payment;
											}

										}

										//bpjs

										$hasilbpjs = 0;
										$hariBerjalan = ((strtotime($today) - strtotime($dataTerakhirPenggajian->tanggal))/86400)+1;
										if ($bpjs1 == "ya") {
											if ($kode->bpjs > 0) {
													$hasilbpjs = ((($kode->umk/($totalHari)) * ($kode->bpjs/100)) * $hariBerjalan);
											}
											else {
												$hasilbpjs = 0;
											}
										}
										else {
											$hasilbpjs = 0;
										}

										$sumSisaPayment = $this->penggajianModel->getSisa($karyawan->id);
										$totalGaji = $totalGaji - ($totalTransport + $totalMakan);

										// $pph = $this->pphModel->getPenggajianPerKaryawan($karyawan->id);

										$penghasilan_bruto_teratur = 0;
										$bruto_seluruhnya = 0;
										$biaya_jabatan = 0;
										$bruto_setahun = 0;
										$biaya_jabatan_setahun = 0;
										$jumlah_pengurangan_setahun = 0;
										$penghasilan_neto_setahun = 0;
										$ptkp = 0;
										$pkp_setahun = 0;
										$pkp_penghasilan_teratur_setahun =0;
										$pph21_teratur_setahun = 0;
										$pph21_penghasilan_tidak_teratur = 0;
										$pph21_terutang_setahun = 0;
										$pph21_bulan_ini = 0;
										// var_dump($totalGaji);
										// foreach($pph as $item){
											// var_dump($totalGaji);
											// var_dump(date('F') == date("F",strtotime($item->tanggal_proses)));
											// if(date('F') == date("F",strtotime($item->tanggal_proses))){
												$penghasilan_bruto_teratur = (($totalGaji) + ($totalTunjanganJabatan + $totalKerajinan + $totalExtra + $totalMakan + $totalTransport + $totalLibur) + ($reward[0]->nilai) + ($hasilbpjs));
												// var_dump($penghasilan_bruto_teratur);
												$bruto_seluruhnya = $penghasilan_bruto_teratur + $totalThr;
												$biaya_jabatan = $bruto_seluruhnya * 0.05;
												$bruto_setahun = ($penghasilan_bruto_teratur * 12) + $totalThr;
												$biaya_jabatan_setahun = ($penghasilan_bruto_teratur * 0.05 * 12) + ($totalThr * 0.05);
												$jumlah_pengurangan_setahun = $biaya_jabatan_setahun;
												$penghasilan_neto_setahun = $bruto_setahun - $jumlah_pengurangan_setahun;
												$ptkp = 54000000;
												if($karyawan->status_nikah == "Menikah"){
													$ptkp += 4500000;
												}
												if($karyawan->tanggungan >= 0 && $karyawan->tanggungan <= 3){
													$ptkp += ($karyawan->tanggungan * 4500000);
												}else{
													$ptkp += (3 * 4500000);
												}
												if($penghasilan_neto_setahun - $ptkp <= 0){
													$pkp_setahun = 0;
													$pkp_penghasilan_teratur_setahun = 0;
													$pph21_bulan_ini=0;
												}else{
													$pkp_setahun = $penghasilan_neto_setahun - $ptkp;
													$pkp_penghasilan_teratur_setahun = (($penghasilan_bruto_teratur * 12)-($penghasilan_bruto_teratur * 12 * 0.05)) - $ptkp;
													if($karyawan->npwp != ""){
														// var_dump("ada");
														if($pkp_penghasilan_teratur_setahun <= 50000000){
															$pph21_teratur_setahun = $pkp_penghasilan_teratur_setahun * 0.05;
															$pph21_penghasilan_tidak_teratur = ($pkp_setahun - $pkp_penghasilan_teratur_setahun) * 0.05;
														}else if($pkp_penghasilan_teratur_setahun <= 250000000){
															$pph21_teratur_setahun = (50000000 * 0.05) + (($pkp_penghasilan_teratur_setahun - 50000000) * 0.15);
															$pph21_penghasilan_tidak_teratur = ($pkp_setahun - $pkp_penghasilan_teratur_setahun) * 0.15;
														}else if($pkp_penghasilan_teratur_setahun <= 500000000){
															$pph21_teratur_setahun = (50000000 * 0.05) + (200000000 * 0.15) + (($pkp_penghasilan_teratur_setahun - 250000000) * 0.25);
															$pph21_penghasilan_tidak_teratur = ($pkp_setahun - $pkp_penghasilan_teratur_setahun) * 0.25;
														}else{
															$pph21_teratur_setahun = (50000000 * 0.05) + (200000000 * 0.15) + (250000000 * 0.25) + (($pkp_penghasilan_teratur_setahun - 500000000) * 0.30);
															$pph21_penghasilan_tidak_teratur = ($pkp_setahun - $pkp_penghasilan_teratur_setahun) * 0.30;
														}
													}else{
														// var_dump("tidak ada");
														if($pkp_penghasilan_teratur_setahun <= 50000000){
															$pph21_teratur_setahun = $pkp_penghasilan_teratur_setahun * 0.06;
															$pph21_penghasilan_tidak_teratur = ($pkp_setahun - $pkp_penghasilan_teratur_setahun) * 0.06;
														}else if($pkp_penghasilan_teratur_setahun <= 250000000){
															$pph21_teratur_setahun = (50000000 * 0.06) + (($pkp_penghasilan_teratur_setahun - 50000000) * 0.18);
															$pph21_penghasilan_tidak_teratur = ($pkp_setahun - $pkp_penghasilan_teratur_setahun) * 0.18;
														}else if($pkp_penghasilan_teratur_setahun <= 500000000){
															$pph21_teratur_setahun = (50000000 * 0.06) + (200000000 * 0.18) + (($pkp_penghasilan_teratur_setahun - 250000000) * 0.30);
															$pph21_penghasilan_tidak_teratur = ($pkp_setahun - $pkp_penghasilan_teratur_setahun) * 0.30;
														}else{
															$pph21_teratur_setahun = (50000000 * 0.06) + (200000000 * 0.18) + (250000000 * 0.30) + (($pkp_penghasilan_teratur_setahun - 500000000) * 0.36);
															$pph21_penghasilan_tidak_teratur = ($pkp_setahun - $pkp_penghasilan_teratur_setahun) * 0.36;
														}
													}
													$pph21_terutang_setahun = $pph21_teratur_setahun + $pph21_penghasilan_tidak_teratur;
													$pph21_bulan_ini = round(($pph21_teratur_setahun / 12)) + $pph21_penghasilan_tidak_teratur;

												}
											// }
											// var_dump($totalGaji);
										// }
										$totalGajiTambahan = $totalGaji + $totalTransport + $totalMakan + ($kode->tunjangan_lain) + $totalThr + $reward[0]->nilai + $totalExtra + $totalTunjanganJabatan + $sumSisaPayment->sisa + $totalKerajinan;
										// $totalGaji = (($val->gaji)/480) * $token[0]->token;
										$totalPotongan += $hasilbpjs + $punishment[0]->nilai + $potongPinjaman + $potonganTelat;
										$gajiBersih = $totalGajiTambahan - $totalPotongan - $pph21_bulan_ini;
										// $row1[] = $gajiBersih;

										$hariKerjaEmploye = sizeof($dataAbsensiKaryawan);
										if ($gajiBersih == 0) {
											$this->response->message = alertDanger("Belum ada nilai untuk di proses..!");
										}
										else {
											// var_dump($pph21_bulan_ini);
											$dataPenggajianPerOrang = array(
																											'id_karyawan' => $karyawan->id,
																											'nama' => $karyawan->nama,
																											'tanggal_proses' => date_ind("d M Y",date("y-m-d")),
																											'hari_kerja_karyawan' => $totalHK,
																											'hari_kerja' => $totalHari,
																											'penggajian_tunjangan' => $totalTunjanganJabatan,
																											'potongan_bpjs' => $hasilbpjs,
																											'penggajian_transport' => $totalTransport,
																											'penggajian_makan' => $totalMakan,
																											'penggajian_tunlain' => $kode->tunjangan_lain,
																											'penggajian_thr' => $totalThr,
																											'reward' => $reward[0]->nilai,
																											'penggajian_libur' => $totalLibur,
																											'penggajian_kerajinan' => $totalKerajinan,
																											'start_date' => date_ind("d M Y",$dataTerakhirPenggajian->tanggal),
																											'end_date' => date_ind("d M Y",$today),
																											'punishment' => $punishment[0]->nilai,
																											'pph' => $pph21_bulan_ini,
																											// 'token' => $token[0]->token,
																											'gaji_total_tambahan' => $totalGajiTambahan,
																											'gaji_total' => $totalGaji,
																											'gaji_extra' => $totalExtra,
																											'gaji_bersih' => $gajiBersih,
																											'potongan_telat' => $potonganTelat,
																											'potongan_pinjaman' => $potongPinjaman,
																											'keterangan' =>$dataTerakhirPenggajian->tanggal . " s/d " . $today,
																											'payment_sisa_sebelumnya' => $sumSisaPayment->sisa
											);
											$allDataPenggajian[] = $dataPenggajianPerOrang;
									}

								}
							// }
							// 	else {
							// 		$this->response->message = alertDanger("Data tidak ada.");
							// }


			}
			$this->response->status = true;
			$this->response->message = "Get Rekap Data penggajian";
			$this->response->data = $allDataPenggajian;

		}
		parent::json();
	}

	public function add()
	{
		parent::checkLoginUser(); // user login autentic checking

		if ($this->isPost()) {
			$idkaryawan = $this->input->post('id_karyawan');
			$hariKerja = $this->input->post('hari_kerja');
			$tunjangan = $this->input->post('tunjangan1');
			$tunjanganLain = $this->input->post('tun_lain1');
			$makan = $this->input->post('makan1');
			$transport = $this->input->post('transport1');
			$reward = $this->input->post('reward1');
			$thr = $this->input->post('thr1');
			$hariKerjaKaryawan = $this->input->post('hari_kerja_karyawan');

			$bpjs = $this->input->post('bpjs1');
			$punishment = $this->input->post('punishment1');
			$pph = $this->input->post('pph');
			$potonganTelat = $this->input->post('telat1');
			$potongPinjaman = $this->input->post('pinjaman1');
			$totalGajiTambahan = $this->input->post('gaji_total_tambahan');
			$gajiAbsensi = $this->input->post('gaji_absensi');
			$gajiExtra = $this->input->post('extra1');
			$kerajinan = $this->input->post('kerajinan');
			$start = $this->input->post('start_date');
			$end = $this->input->post('end_date');
			$paymentSebelumnya = $this->input->post('payment_sisa_sebelumnya');
			$penerimaan = $this->input->post('penerimaan');
			$pengambilan = $this->input->post('pengambilan');
			$sisa = $this->input->post('sisa');

			if ($paymentSebelumnya != 0) {
				$this->penggajianModel->updateSisaPayment($idkaryawan,0);
			}

			if ($pengambilan > $penerimaan) {
				$this->response->message = "<span style='color:red;'>pengambilan gaji lebih besar dari penerimaan gaji.</span>";
			}
			else {
				$kode = $this->penggajianModel->idToKode($idkaryawan);

			 	$dataPinjaman = $this->pinjamanModel->getDataPinjamanPenggajian($idkaryawan);
				if ($dataPinjaman != null) {
					$dataLogPinjaman = array(
						'id_pinjaman' => $dataPinjaman->id_pinjaman,
						'pembayaran' => $potongPinjaman,
						'tanggal' => date("Y-m-d")
					);
					$update = $this->pinjamanModel->insertUpdate($dataPinjaman->id_pinjaman,$potongPinjaman,$dataLogPinjaman);
				}
				$data = array(
											'id_karyawan' => $idkaryawan,
											'tanggal_proses' => date("Y-m-d"),
											'hari_kerja_karyawan' => $hariKerjaKaryawan,
											'hari_kerja' => $hariKerja,
											'penggajian_tunjangan' => $tunjangan,
											'potongan_bpjs' => $bpjs,
											'penggajian_transport' => $transport,
											'penggajian_makan' => $makan,
											'penggajian_tunlain' => $tunjanganLain,
											'penggajian_kerajinan' => $kerajinan,
											'penggajian_thr' => $thr,
											'reward' => $reward,
											'start_date' => $start,
											'end_date' => $end,
											'punishment' => $punishment,
											'pph_bulan_ini' => $pph,
											'gaji_total_tambahan' => $totalGajiTambahan,
											'gaji_total' => $gajiAbsensi,
											'gaji_extra' => $gajiExtra,
											'gaji_bersih' => $penerimaan,
											'potongan_telat' => $potonganTelat,
											'potongan_pinjaman' => $potongPinjaman,
											'paid' => 1,
											'nilai_bayar' => $pengambilan,
											'sisa' => $sisa,
											'tipe_payment' => 0
											// 'keterangan' =>$dataTerakhirPenggajian->tanggal . " s/d " . date("Y-m-d")
										 );
					$dataPenggajian[] = $data;
					$insert = $this->penggajianModel->insert($dataPenggajian,$end,$kode->kode_karyawan);
					if ($insert) {
							$this->response->status = true;
							$this->response->message = "<span style='color:green;'>Berhasil proses Penggajian.</span>";
						} else {
							$this->response->message = "<span style='color:red;'>Gagal, Proses Penggajian.</span>";
						}
			}

		}
		parent::json();
	}

	public function getId($id)
	{
		parent::checkLoginUser(); // user login autentic checking

		if ($this->isPost()) {
			$getById = $this->penggajianModel->getById($id);
			$karyawan = $this->karyawanModel->getByWhere("id = " . $id);
				// var_dump($karyawan->status_nikah);
				$dataTotalHari = $this->settingModel->getTotalHari();
				$dataTerakhirPenggajian = $this->penggajianModel->getDataTerakhirPenggajian($id);
				$setting = $this->settingModel->getById(1);
				if ($dataTerakhirPenggajian == null) {
					$this->response->message = alertDanger("Sudah Melakukan Proses Penggajian Hari Ini.");
				}
				else {
						if ($getById) {
							$dataAbsenToken = $this->penggajianModel->getDataPerBulan($dataTerakhirPenggajian->tanggal,date("Y-m-d"));
							$kode = $this->penggajianModel->idToKode($id);
							$dataPinjaman = $this->pinjamanModel->getDataPinjamanPenggajian($id);
							$reward = $this->rewardModel->getRewardPenggajian($dataTerakhirPenggajian->tanggal,date("Y-m-d"),$id);
							$punishment = $this->punishModel->getPunishmentPenggajian($dataTerakhirPenggajian->tanggal,date("Y-m-d"),$id);
							$thr = $this->thrModel->getThrPenggajian($dataTerakhirPenggajian->tanggal,date("Y-m-d"),$kode->agama);
							$extra = $this->ExtraabsensiModel->getExtraPenggajian($dataTerakhirPenggajian->tanggal,date("Y-m-d"),$kode->kode_karyawan);
							$jabatan = $this->jabatanModel->getDataPenggajianJabatan($id,$dataTerakhirPenggajian->tanggal,date("Y-m-d"));
							$token = $this->logabsensiModel->perBulan($dataTerakhirPenggajian->tanggal,date("Y-m-d"),$kode->kode_karyawan,null);
							// $hariLibur = $this->calendarModel->getHariLibur($dataTerakhirPenggajian->tanggal,date("Y-m-d"));
							$listCalendar = $this->calendarModel->getListCalendar($dataTerakhirPenggajian->tanggal,date("Y-m-d"));
							$totalHari = (int)$dataTotalHari->total_hari;
							// $totalGaji = 0;
							$totalBreakPunishment = 0;
							$potonganTelat = 0;

							foreach ($dataAbsenToken as $key => $val) {
								// $totalGaji += $val->payment;
								// if (($val->telat >= 6) && ($val->telat <= 10)) {
								// 	$potonganTelat += 10000;
								// }
								// else if (($val->telat >= 11) && ($val->telat <= 29)) {
								// 	$potonganTelat += 25000;
								// }
								$totalBreakPunishment += $val->break_punishment;
							}
							if ($jabatan) {
								$totalTunjanganJabatan = $jabatan->tunjangan;
							}
							else {
								$totalTunjanganJabatan = 0;
							}

							// var_dump($pph21_bulan_ini);
							// exit();
							$totalThr = 0;
							$gajiBersih = 0;
							$totalGaji = 0;
							$totalExtra = 0;
							$totalTelat = 0;
							$potonganTelat = 0;
							$potongPinjaman = 0;
							$totalPotongan = 0;
							$totalTransport = 0;
							$totalMakan = 0;
							$totalKerajinan = 0;
							$totalLibur = 0;
							$totalHK = 0;

							$telat = $this->logabsensiModel->getDataTelat($dataTerakhirPenggajian->tanggal,date("Y-m-d"),$kode->kode_karyawan);

							foreach ($telat as $value) {
								$totalTelat += $value->telat;
								if ($value->telat != 0) {
									if (($value->telat >= $setting->telat1) && ($value->telat <= $setting->telat2)) {
										$potonganTelat += $setting->potongan_telat1;
									}
									else if (($value->telat >= $setting->telat2) && ($value->telat <= $setting->telat3)) {
										$potonganTelat += $setting->potongan_telat2;
									}
								}
							}
							if ($dataPinjaman == null) {
								$potongPinjaman = 0;
							}
							else {
								//pinjaman
								$potongPinjaman = $dataPinjaman->cicilan;
								if ($dataPinjaman->sisa <= $potongPinjaman) {
									$potongPinjaman = $dataPinjaman->sisa;
								}
								else {
									$potongPinjaman = $dataPinjaman->cicilan;
								}
							}
							// token
							if ($token[0]->token == null) {
								$token[0]->token = 0;
							}
							//reward
							if ($reward[0]->nilai == null) {
								$reward[0]->nilai = 0;
							}
							//punishment
							if ($punishment[0]->nilai == null) {
								$punishment[0]->nilai = 0;
							}
							//thr
							if ($thr != null &&($thr->agama == $kode->agama)) {
								$totalThr = (int)$kode->thr;
							}

							//Kerajinan
							if ($setting->opsi_kerajinan == 1) {
								$jumlahAbsensi = $this->logabsensiModel->jumlahAbsensi($dataTerakhirPenggajian->tanggal,date("Y-m-d"),$kode->kode_karyawan);
								$totalAbsen = (int)$setting->total_hari - 2;
								if ($jumlahAbsensi >= $totalAbsen) {
									$totalKerajinan += $setting->kerajinan;
								}
							}

							if ($kode->lembur == 1) {
								$dataExtraperiode = $this->ExtraabsensiModel->getDataPerPeriode($dataTerakhirPenggajian->tanggal,date("Y-m-d"),$kode->kode_karyawan);
								foreach ($dataExtraperiode as $values) {
									if ($values->total == 0) {
										$values->total = 0;
									}
									if ($values->total <= 60) {
										$totalExtra += 1.5 * ((1/173*$kode->umk)/60) * $values->total;
									}
									else {
										$totalExtra += 1.5 * ((1/173*$kode->umk)/60) * $values->total;
										$totalExtra += 2 * ((1/173*$kode->umk)/60) * ($values->total - 60);
									}
								}
								// $totalExtra += 1.5 * ((1/173*$val->umk)/60) * $extra->total;
							}
							else {
								if ($extra[0]->total == null) {
									$extra[0]->total = 0;
								}
								if ($kode->lembur_tetap == 0 || $kode->lembur_tetap == null) {
									$kode->lembur_tetap = 0;
								}
								$totalExtra += ($kode->lembur_tetap/60) * $extra[0]->total;
							}

							$dataAbsensiKaryawan = $this->logabsensiModel->DataPerBulan($dataTerakhirPenggajian->tanggal,date("Y-m-d"),$kode->kode_karyawan,null);
							$tglCalendar = array();
							foreach ($listCalendar as $key => $calendar) {
								$tglCalendar[] = $calendar->tanggal;
							}
							//gaji hari libur
							// $totalGaji += sizeof($tglCalendar) * $kode->gaji;
							foreach ($dataAbsensiKaryawan as $vals) {
								if (($vals->kerja == "Normal" || $vals->kerja == "Normal(Manual)") && $vals->payment == 0) {
									continue;
								}
								if (strtotime($vals->tanggal) == strtotime($today) && (strtotime($waktu.":00") <= strtotime($vals->masuk))) {
									continue;
								}
								else {
									if (in_array($vals->tanggal,$tglCalendar)) {
										$totalHK += 1;
									}
									$totalHK += 1;
									$totalTransport += $kode->transport;
									$totalMakan += $kode->makan;
									//
									// if (in_array($vals->tanggal,$tglCalendar)) {
									// 	$totalGaji += $kode->gaji;
									// }
									$totalLibur += $vals->tambahan;
									$totalGaji += $vals->payment;
								}
							}


							//bpjs

							$hasilbpjs = 0;
							if ($kode->bpjs > 0) {
								$hariBerjalan = ((strtotime(date("Y-m-d")) - strtotime($dataTerakhirPenggajian->tanggal))/86400)+1;
								if ($kode->bpjs > 0) {
										$hasilbpjs = ((($kode->umk/($totalHari)) * ($kode->bpjs/100)) * $hariBerjalan);
								}
								else {
									$hasilbpjs = 0;
								}
							}
							else {
								$hasilbpjs = 0;
							}

							$sumSisaPayment = $this->penggajianModel->getSisa($id);
							$totalGaji = $totalGaji - ($totalTransport + $totalMakan);

							$totalGajiTambahan = $totalGaji + $totalTransport + $totalMakan + ($kode->tunjangan_lain) + $totalThr + $reward[0]->nilai + $totalExtra + $totalTunjanganJabatan + $sumSisaPayment->sisa + $totalKerajinan;
							// $totalGaji = (($val->gaji)/480) * $token[0]->token;
							$totalPotongan += $hasilbpjs + $punishment[0]->nilai + $potongPinjaman + $potonganTelat;
							$gajiBersih = $totalGajiTambahan - $totalPotongan;
							// $row1[] = $gajiBersih;

							$penghasilan_bruto_teratur = 0;
							$bruto_seluruhnya = 0;
							$biaya_jabatan = 0;
							$bruto_setahun = 0;
							$biaya_jabatan_setahun = 0;
							$jumlah_pengurangan_setahun = 0;
							$penghasilan_neto_setahun = 0;
							$ptkp = 0;
							$pkp_setahun = 0;
							$pkp_penghasilan_teratur_setahun =0;
							$pph21_teratur_setahun = 0;
							$pph21_penghasilan_tidak_teratur = 0;
							$pph21_terutang_setahun = 0;
							$pph21_bulan_ini = 0;
							// $pph21_bulan_ini = 0;
							// $pph = $this->pphModel->getPenggajianPerKaryawan($id);
							// foreach($pph as $item){
								// var_dump(date('F') == date("F",strtotime($item->tanggal_proses)));
								// if(date('F') == date("F",strtotime($item->tanggal_proses))){
								$penghasilan_bruto_teratur = (($totalGaji) + ($totalTunjanganJabatan + $totalKerajinan + $totalExtra + $totalMakan + $totalTransport + $totalLibur) + ($reward[0]->nilai) + ($hasilbpjs));
								// var_dump($penghasilan_bruto_teratur);
								$bruto_seluruhnya = $penghasilan_bruto_teratur + $totalThr;
								$biaya_jabatan = $bruto_seluruhnya * 0.05;
								$bruto_setahun = ($penghasilan_bruto_teratur * 12) + $totalThr;
								$biaya_jabatan_setahun = ($penghasilan_bruto_teratur * 0.05 * 12) + ($totalThr * 0.05);
								$jumlah_pengurangan_setahun = $biaya_jabatan_setahun;
								$penghasilan_neto_setahun = $bruto_setahun - $jumlah_pengurangan_setahun;
								$ptkp = 54000000;
								// foreach($karyawan as $item){
									// var_dump($item->status_nikah);
									if($karyawan->status_nikah == "Menikah"){
										$ptkp += 4500000;
									}
									if($karyawan->tanggungan >= 0 && $karyawan->tanggungan <= 3){
										$ptkp += ($karyawan->tanggungan * 4500000);
									}else{
										$ptkp += (3 * 4500000);
									}
									if($penghasilan_neto_setahun - $ptkp <= 0){
										$pkp_setahun = 0;
										$pkp_penghasilan_teratur_setahun = 0;
										$pph21_bulan_ini=0;
									}else{
										$pkp_setahun = $penghasilan_neto_setahun - $ptkp;
										$pkp_penghasilan_teratur_setahun = (($penghasilan_bruto_teratur * 12)-($penghasilan_bruto_teratur * 12 * 0.05)) - $ptkp;
										if($karyawan->npwp != ""){
											// var_dump("ada");
											if($pkp_penghasilan_teratur_setahun <= 50000000){
												$pph21_teratur_setahun = $pkp_penghasilan_teratur_setahun * 0.05;
												$pph21_penghasilan_tidak_teratur = ($pkp_setahun - $pkp_penghasilan_teratur_setahun) * 0.05;
											}else if($pkp_penghasilan_teratur_setahun <= 250000000){
												$pph21_teratur_setahun = (50000000 * 0.05) + (($pkp_penghasilan_teratur_setahun - 50000000) * 0.15);
												$pph21_penghasilan_tidak_teratur = ($pkp_setahun - $pkp_penghasilan_teratur_setahun) * 0.15;
											}else if($pkp_penghasilan_teratur_setahun <= 500000000){
												$pph21_teratur_setahun = (50000000 * 0.05) + (200000000 * 0.15) + (($pkp_penghasilan_teratur_setahun - 250000000) * 0.25);
												$pph21_penghasilan_tidak_teratur = ($pkp_setahun - $pkp_penghasilan_teratur_setahun) * 0.25;
											}else{
												$pph21_teratur_setahun = (50000000 * 0.05) + (200000000 * 0.15) + (250000000 * 0.25) + (($pkp_penghasilan_teratur_setahun - 500000000) * 0.30);
												$pph21_penghasilan_tidak_teratur = ($pkp_setahun - $pkp_penghasilan_teratur_setahun) * 0.30;
											}
										}else{
											// var_dump("tidak ada");
											if($pkp_penghasilan_teratur_setahun <= 50000000){
												$pph21_teratur_setahun = $pkp_penghasilan_teratur_setahun * 0.06;
												$pph21_penghasilan_tidak_teratur = ($pkp_setahun - $pkp_penghasilan_teratur_setahun) * 0.06;
											}else if($pkp_penghasilan_teratur_setahun <= 250000000){
												$pph21_teratur_setahun = (50000000 * 0.06) + (($pkp_penghasilan_teratur_setahun - 50000000) * 0.18);
												$pph21_penghasilan_tidak_teratur = ($pkp_setahun - $pkp_penghasilan_teratur_setahun) * 0.18;
											}else if($pkp_penghasilan_teratur_setahun <= 500000000){
												$pph21_teratur_setahun = (50000000 * 0.06) + (200000000 * 0.18) + (($pkp_penghasilan_teratur_setahun - 250000000) * 0.30);
												$pph21_penghasilan_tidak_teratur = ($pkp_setahun - $pkp_penghasilan_teratur_setahun) * 0.30;
											}else{
												$pph21_teratur_setahun = (50000000 * 0.06) + (200000000 * 0.18) + (250000000 * 0.30) + (($pkp_penghasilan_teratur_setahun - 500000000) * 0.36);
												$pph21_penghasilan_tidak_teratur = ($pkp_setahun - $pkp_penghasilan_teratur_setahun) * 0.36;
											}
										}
									}
									$pph21_terutang_setahun = $pph21_teratur_setahun + $pph21_penghasilan_tidak_teratur;
									$pph21_bulan_ini = round(($pph21_teratur_setahun / 12)) + $pph21_penghasilan_tidak_teratur;

								// }
								// }
								// var_dump($item);
							// }

							$hariKerjaEmploye = sizeof($dataAbsensiKaryawan);
							if ($gajiBersih == 0) {
								$this->response->message = alertDanger("Belum ada nilai untuk di proses..!");
							}
							else {
								$dataPenggajianPerOrang = array(
																								'id_karyawan' => $id,
																								'tanggal_proses' => date("Y-m-d"),
																								'hari_kerja_karyawan' => $totalHK,
																								'hari_kerja' => $totalHari,
																								'penggajian_tunjangan' => $totalTunjanganJabatan,
																								'potongan_bpjs' => $hasilbpjs,
																								'penggajian_transport' => $totalTransport,
																								'penggajian_makan' => $totalMakan,
																								'penggajian_tunlain' => $kode->tunjangan_lain,
																								'penggajian_thr' => $totalThr,
																								'reward' => $reward[0]->nilai,
																								'penggajian_libur' => $totalLibur,
																								'penggajian_kerajinan' => $totalKerajinan,
																								'start_date' => $dataTerakhirPenggajian->tanggal,
																								'end_date' => date("Y-m-d"),
																								'punishment' => $punishment[0]->nilai,
																								'pph' => $pph21_bulan_ini,
																								// 'token' => $token[0]->token,
																								'gaji_total_tambahan' => $totalGajiTambahan,
																								'gaji_total' => $totalGaji,
																								'gaji_extra' => $totalExtra,
																								'gaji_bersih' => $gajiBersih,
																								'potongan_telat' => $potonganTelat,
																								'potongan_pinjaman' => $potongPinjaman,
																								'keterangan' =>$dataTerakhirPenggajian->tanggal . " s/d " . date("Y-m-d"),
																								'payment_sisa_sebelumnya' => $sumSisaPayment->sisa
								);

								$this->response->status = true;
								$this->response->message = "Data penggajian get by id";
								$this->response->data = $getById;
								$this->response->dataPenggajian = $dataPenggajianPerOrang;
							}
						}
							else {
								$this->response->message = alertDanger("Data tidak ada.");
						}
				}

		}
		parent::json();
	}

	public function getbyidPenggajian($id)
	{
		parent::checkLoginUser(); // user login autentic checking

		if ($this->isPost()) {
			$getById = $this->penggajianModel->getByIdPenggajian($id);
			$kode = $this->penggajianModel->idToKode($getById->id_karyawan);
			$dataAbsensiKaryawan = $this->logabsensiModel->DataPerBulan($getById->start_date,$getById->end_date,$kode->kode_karyawan,null);
			if ($getById) {
				$totalPotongan = 0;
				$totalPotongan += $getById->potongan_bpjs + $getById->potongan_telat + $getById->potongan_pinjaman + $getById->punishment + $getById->pph_bulan_ini;

				$getById->tanggal_proses = date_ind("d M Y",$getById->tanggal_proses);
				$getById->start_date = date_ind("d M Y",$getById->start_date);
				$getById->end_date = date_ind("d M Y",$getById->end_date);
				$getById->tgl_masuk = date_ind("d M Y",$getById->tgl_masuk);
				$getById->total_potongan = $totalPotongan;
				$totalTelat = 0;

				foreach ($dataAbsensiKaryawan as $key => $value) {
					$value->tanggal = date_ind("d M Y",$value->tanggal);
					if ($value->telat > 0) {
						$totalTelat += $value->telat;
					}
				}
				$getById->total_telat = $totalTelat;
				// var_dump($getById);
				$this->response->status = true;
				$this->response->message = alertSuccess("Berhasil get data slip penggajian karyawan");
				$this->response->data = $getById;
				$this->response->dataAbsensi = $dataAbsensiKaryawan;
			}
			else {
				$this->response->message = alertDanger("Gagal get data slip penggajian karyawan");
			}
		}
		parent::json();
	}

	public function getSlipGajiKaryawan($id)
	{

		if ($this->isPost()) {
			$getById = $this->penggajianModel->getById($id);
			if ($getById) {
				$dataTerakhirPenggajian = $this->penggajianModel->getDataTerakhirPenggajian($id);
				$dataAbsenToken = $this->penggajianModel->getDataPerBulan($dataTerakhirPenggajian->tanggal,date("Y-m-d"));
				$kode = $this->penggajianModel->idToKode($id);
				$dataPinjaman = $this->pinjamanModel->getDataPinjamanPenggajian($id);
				$reward = $this->rewardModel->getRewardPenggajian($dataTerakhirPenggajian->tanggal,date("Y-m-d"),$id);
				$punishment = $this->punishModel->getPunishmentPenggajian($dataTerakhirPenggajian->tanggal,date("Y-m-d"),$id);
				// $thr = $this->thrModel->getThrPenggajian($dataTerakhirPenggajian->tanggal,date("Y-m-d"),$val->agama);
				$extra = $this->ExtraabsensiModel->getExtraPenggajian($dataTerakhirPenggajian->tanggal,date("Y-m-d"),$kode->kode_karyawan);
				$jabatan = $this->jabatanModel->getDataPenggajianJabatan($id,$dataTerakhirPenggajian->tanggal,date("Y-m-d"));
				$totalGaji = 0;
				$totalBreakPunishment = 0;
				$potonganTelat = 0;
				foreach ($dataAbsenToken as $key => $val) {
					$totalGaji += $val->payment;
					if (($val->telat >= 6) && ($val->telat <= 10)) {
						$potonganTelat += 10000;
					}
					else if (($val->telat >= 11) && ($val->telat <= 29)) {
						$potonganTelat += 25000;
					}
					$totalBreakPunishment += $val->break_punishment;
				}
				$this->response->status = true;
				$this->response->message = "Data penggajian get by id";
				$this->response->data = $getById;
			} else {
				$this->response->message = alertDanger("Data tidak ada.");
			}
		}
		parent::json();
	}

	public function generate_penggajian()
	{

		parent::checkLoginUser(); // user login autentic checking

		if ($this->isPost()) {

			$periode = $this->input->post('periode');
			$ikutPotongBpjs = $this->input->post('ikutPotongBpjs');
			$ikutPotongSisa = $this->input->post('ikutPotongSisa');
			$ikutTunjangan = $this->input->post('ikutTunjangan');
			$tanggal = $this->input->post('tanggal');
			$waktu = $this->input->post('waktu');

				$periodeKaryawan = $this->karyawanModel->periodeGajiKaryawan($periode);
				$dataTotalHari = $this->settingModel->getTotalHari();
				$checkDataTerahkirPenggajian = $this->penggajianModel->getDataPenggajianTerakhir($periode);
				$setting = $this->settingModel->getById(1);

				if (sizeof($periodeKaryawan) == 0) {
					$this->response->message = "<span style='color:red' >Tidak dapat melakukan proses penggajian dikarenakan tidak ada karyawan pada periode ".$periode.".</span>";
				}
				else {
					if ($checkDataTerahkirPenggajian != 0) {
						$this->response->message = "<span style='color:red' >Tidak dapat melakukan proses penggajian periode ".$periode." pada hari yang sama.</span>";
					}
					else {
						$dataPenggajianAll = array();
						$checkperiode = true;
						$today = "";
						if ($checkperiode) {
							$today = $tanggal;
							// $dataTerakhirPenggajian->tanggal = $before;
						}
						else {
							$today = date("Y-m-d");
						}
						foreach ($periodeKaryawan as $key => $val) {
							$dataTerakhirPenggajian = $this->penggajianModel->getDataTerakhirPenggajian($val->id);
							if ($dataTerakhirPenggajian == null) {
								continue;
							}

							$dataAbsenToken = $this->penggajianModel->getDataPerBulan($dataTerakhirPenggajian->tanggal,$today);
							$kode = $this->penggajianModel->idToKode($val->id);
							$dataPinjaman = $this->pinjamanModel->getDataPinjamanPenggajian($val->id);
							$reward = $this->rewardModel->getRewardPenggajian($dataTerakhirPenggajian->tanggal,$today,$val->id);
							$punishment = $this->punishModel->getPunishmentPenggajian($dataTerakhirPenggajian->tanggal,$today,$val->id);
							$thr = $this->thrModel->getThrPenggajian($dataTerakhirPenggajian->tanggal,$today,$kode->agama);
							$extra = $this->ExtraabsensiModel->getExtraPenggajian($dataTerakhirPenggajian->tanggal,$today,$kode->kode_karyawan);
							$jabatan = $this->jabatanModel->getDataPenggajianJabatan($val->id,$dataTerakhirPenggajian->tanggal,$today);
							$token = $this->logabsensiModel->perBulan($dataTerakhirPenggajian->tanggal,$today,$kode->kode_karyawan,null);
							// $hariLibur = $this->calendarModel->getHariLibur($dataTerakhirPenggajian->tanggal,$today);
							$listCalendar = $this->calendarModel->getListCalendar($dataTerakhirPenggajian->tanggal,$today);
							$totalHari = 0;
							if ($dataTotalHari->otomatis_hari == 1) {
								$totalHari = (int)$dataTotalHari->total_hari;
							}
							else {
								$date = new DateTime($dataTerakhirPenggajian->tanggal);
								$date1 = $date->format('Y-m-d');
								$totalHari = (int)date("t",strtotime($date1));
							}
							// $totalGaji = 0;
							$totalBreakPunishment = 0;
							$potonganTelat = 0;
							$totalHK = 0;

							foreach ($dataAbsenToken as $key => $vals) {
								// $totalGaji += $vals->payment;
								// if (($vals->telat >= 6) && ($vals->telat <= 10)) {
								// 	$potonganTelat += 10000;
								// }
								// else if (($vals->telat >= 11) && ($vals->telat <= 29)) {
								// 	$potonganTelat += 25000;
								// }
								$totalBreakPunishment += $vals->break_punishment;
							}
							if ($ikutTunjangan == 1) {
								if ($jabatan) {
									$totalTunjanganJabatan = $jabatan->tunjangan;
								}
								else {
									$totalTunjanganJabatan = 0;
								}
							}
							else {
								$totalTunjanganJabatan = 0;
							}

							$totalThr = 0;
							$gajiBersih = 0;
							$totalGaji = 0;
							$totalExtra = 0;
							$totalTelat = 0;
							$potonganTelat = 0;
							$potongPinjaman = 0;
							$totalPotongan = 0;
							$totalTransport = 0;
							$totalMakan = 0;
							$totalLibur = 0;
							$totalKerajinan = 0;

							$telat = $this->logabsensiModel->getDataTelat($dataTerakhirPenggajian->tanggal,$today,$kode->kode_karyawan);

							foreach ($telat as $value) {
								$totalTelat += $value->telat;
								if ($value->telat != 0) {
									if (($value->telat >= $setting->telat1) && ($value->telat <= $setting->telat2)) {
										$potonganTelat += $setting->potongan_telat1;
									}
									else if (($value->telat >= $setting->telat2) && ($value->telat <= $setting->telat3)) {
										$potonganTelat += $setting->potongan_telat2;
									}
								}
							}
							if ($dataPinjaman == null) {
								$potongPinjaman = 0;
							}
							else {
								$potongPinjaman = $dataPinjaman->cicilan;
								if ($dataPinjaman->sisa <= $potongPinjaman) {
									$potongPinjaman = $dataPinjaman->sisa;
									$dataLogPinjaman = array(
																						 'id_pinjaman' => $dataPinjaman->id_pinjaman,
																						 'pembayaran' => $dataPinjaman->sisa,
																						 'tanggal' => $today
																					 );
								$update = $this->pinjamanModel->insertUpdate($dataPinjaman->id_pinjaman,$dataPinjaman->sisa,$dataLogPinjaman);
								}
								else {
									$dataLogPinjaman = array(
										'id_pinjaman' => $dataPinjaman->id_pinjaman,
										'pembayaran' => $potongPinjaman,
										'tanggal' => $today
									);
									$update = $this->pinjamanModel->insertUpdate($dataPinjaman->id_pinjaman,$dataPinjaman->cicilan,$dataLogPinjaman);
								}
							}
							// token
							if ($token[0]->token == null) {
								$token[0]->token = 0;
							}
							//reward
							if ($reward[0]->nilai == null) {
								$reward[0]->nilai = 0;
							}
							//punishment
							if ($punishment[0]->nilai == null) {
								$punishment[0]->nilai = 0;
							}
							//thr
							if ($thr != null &&($thr->agama == $kode->agama)) {
								$totalThr = (int)$kode->thr;
							}

							//Kerajinan
							if ($setting->opsi_kerajinan == 1) {
								$jumlahAbsensi = $this->logabsensiModel->jumlahAbsensi($dataTerakhirPenggajian->tanggal,$today,$kode->kode_karyawan);
								$totalAbsen = (int)$setting->total_hari - 2;
								if ($jumlahAbsensi >= $totalAbsen) {
									$totalKerajinan += $setting->kerajinan;
								}
							}

							// if ($kode->lembur == 1) {
							// 	$dataExtraperiode = $this->ExtraabsensiModel->getDataPerPeriode($dataTerakhirPenggajian->tanggal,$today,$kode->kode_karyawan);
								// foreach ($dataExtraperiode as $values) {
							// 		if ($values->total == 0) {
							// 			$values->total = 0;
							// 		}
							// 		if ($values->total <= 60) {
							// 			$totalExtra += 1.5 * ((1/173*$kode->umk)/60) * $values->total;
							// 		}
							// 		else {
							// 			$totalExtra += 1.5 * ((1/173*$kode->umk)/60) * $values->total;
							// 			$totalExtra += 2 * ((1/173*$kode->umk)/60) * ($values->total - 60);
							// 		}
							// 	}
							// 	// $totalExtra += 1.5 * ((1/173*$val->umk)/60) * $extra->total;
							// }
							// else {
							// 	if ($extra[0]->total == null) {
							// 		$extra[0]->total = 0;
							// 	}
							// 	if ($kode->lembur_tetap == 0 || $kode->lembur_tetap == null) {
							// 		$kode->lembur_tetap = 0;
							// 	}
							// 	$totalExtra += ($kode->lembur_tetap/60) * $extra[0]->total;
							// }

							if ($setting->extra_approval == 0) {
								 $dataExtraperiode = $this->ExtraabsensiModel->getDataPerPeriode($dataTerakhirPenggajian->tanggal,$today,$kode->kode_karyawan);
								 foreach ($dataExtraperiode as $values) {
									 $totalExtra += $values->payment;
								 }
							}
							else {
								$dataExtraperiode = $this->ExtraabsensiModel->getDataPerPeriodeStatus($dataTerakhirPenggajian->tanggal,$today,$kode->kode_karyawan,"Diterima");
								foreach ($dataExtraperiode as $values) {
									$totalExtra += $values->payment;
								}
							}


							// $updateDataExtra = $this->ExtraabsensiModel->updatePayment($dataTerakhirPenggajian->tanggal,$today,$kode->kode_karyawan);

							$dataAbsensiKaryawan = $this->logabsensiModel->DataPerBulanShift($dataTerakhirPenggajian->tanggal,$today,$kode->kode_karyawan,null);
							$tglCalendar = array();
							foreach ($listCalendar as $key => $calendar) {
								$tglCalendar[] = $calendar->tanggal;
							}
							//gaji hari libur
							// $totalGaji += sizeof($tglCalendar) * $kode->gaji;

							foreach ($dataAbsensiKaryawan as $vals) {
								if (($vals->kerja == "Normal" || $vals->kerja == "Normal(Manual)") && $vals->payment == 0) {
									continue;
								}
								if (strtotime($vals->tanggal) == strtotime($today) && (strtotime($waktu.":00") <= strtotime($vals->masuk))) {
									continue;
								}
								else {
									if (in_array($vals->tanggal,$tglCalendar)) {
										$totalHK += 1;
									}
									$totalHK += 1;
									$totalTransport += $kode->transport;
									$totalMakan += $kode->makan;
									// $totalGaji += ((($kode->umk/$totalHari)/$vals->max_jam_kerja)* $vals->token);
									// if ($kode->umk == 0) {
									// 	// $totalGaji += (($kode->gaji/$vals->max_jam_kerja)* $vals->token);
									// 	$totalGaji += ($vals->token / $vals->max_jam_kerja) * $kode->gaji;
									// }
									// else if ($vals->id_jadwal == null) {
									// 	$totalGaji += $vals->token * $kode->gaji_menit;
									// }
									// else {
									// 	$totalGaji += ((($kode->umk/$totalHari)/$vals->max_jam_kerja)* $vals->token);
									// }
									$totalLibur += $vals->tambahan;
									$totalGaji += $vals->payment;
								}
							}

							// sisa payment

							$sisaPayment = $this->penggajianModel->getSisa($val->id);
							$sisapaymentSebelumnya = 0;
							if ($ikutPotongSisa == 1) {
								if ($sisaPayment->sisa != null) {
									$sisapaymentSebelumnya = $sisaPayment->sisa;
									$this->penggajianModel->updateSisaPayment($idkaryawan,0);
								}
								else {
									$sisapaymentSebelumnya = 0;
								}
							}

							//bpjs

							$hasilbpjs = 0;

							if ($ikutPotongBpjs == 0) {
								$hasilbpjs = 0;
							}
							else {
								if ($kode->bpjs > 0) {
									$hariBerjalan = ((strtotime($today) - strtotime($dataTerakhirPenggajian->tanggal))/86400)+1;
									if ($kode->bpjs > 0) {
											$hasilbpjs = ((($kode->umk/($totalHari)) * ($kode->bpjs/100)) * $hariBerjalan);
									}
									else {
										$hasilbpjs = 0;
									}
								}
								else {
									$hasilbpjs = 0;
								}
							}
							$totalGaji = $totalGaji - ($totalTransport + $totalMakan);
							$totalGajiTambahan = $totalGaji + $totalTransport + $totalMakan + ($kode->tunjangan_lain) + $totalThr + $reward[0]->nilai + $totalExtra + $totalTunjanganJabatan + $sisapaymentSebelumnya;
							// $totalGaji = (($val->gaji)/480) * $token[0]->token;
							$totalPotongan += $hasilbpjs + $punishment[0]->nilai + $potongPinjaman + $potonganTelat;
							$gajiBersih = $totalGajiTambahan - $totalPotongan;
							// $row1[] = $gajiBersih;
							$hariKerjaEmploye = sizeof($dataAbsensiKaryawan);
							$dataPenggajianPerOrang = array(
																							'id_karyawan' => $val->id,
																							'tanggal_proses' => $today,
																							'hari_kerja' => $totalHari,
																							'hari_kerja_karyawan' => $hariKerjaEmploye,
																							'nilai_bayar' => $gajiBersih,
																							'penggajian_tunjangan' => $totalTunjanganJabatan,
																							'potongan_bpjs' => $hasilbpjs,
																							'penggajian_transport' => $totalTransport,
																							'penggajian_makan' => $totalMakan,
																							'penggajian_tunlain' => $kode->tunjangan_lain,
																							'penggajian_thr' => $totalThr,
																							'reward' => $reward[0]->nilai,
																							'penggajian_libur' => $totalLibur,
																							'penggajian_kerajinan' => $totalKerajinan,
																							'start_date' => $dataTerakhirPenggajian->tanggal,
																							'end_date' => $today,
																							'punishment' => $punishment[0]->nilai,
																							// 'token' => $token[0]->token,
																							'gaji_total_tambahan' => $totalGajiTambahan,
																							'gaji_total' => $totalGaji,
																							'gaji_extra' => $totalExtra,
																							'gaji_bersih' => $gajiBersih,
																							'potongan_telat' => $potonganTelat,
																							'potongan_pinjaman' => $potongPinjaman,
																							'keterangan' =>$dataTerakhirPenggajian->tanggal . " s/d " . $today,
																							'id_akumulasi' => $sisapaymentSebelumnya,
																							'tipe_payment' => 1
																						 );
							$dataPenggajianAll[] = $dataPenggajianPerOrang;
						}
						if (sizeof($dataPenggajianAll) != 0) {
							$insert = $this->penggajianModel->insertPeriode($dataPenggajianAll,$today,$periode);
						}
						else {
							$this->response->message = "<span style='color:red;'>Gagal, tidak ada data Penggajian.</span>";
						}
						$update = $this->penggajianModel->updateShift($today,$periode);
						if ($update) {
							$this->response->status = true;
							$this->response->message = "<span style='color:green;'>Berhasil proses Tambah Data Penggajian.</span>";
						} else {
							$this->response->message = "<span style='color:red;'>Gagal, tambah data Penggajian.</span>";
						}
					}
				}
		}
		parent::json();
	}

	public function multipelKaryawan()
	{

		parent::checkLoginUser(); // user login autentic checking

		if ($this->isPost()) {

			$listKaryawan = $this->input->post('nama_karyawan');
			$ikutPotongBpjs = $this->input->post('ikutPotongBpjs1');
			$ikutPotongSisa = $this->input->post('ikutPotongSisa1');
			$ikutTunjangan = $this->input->post('ikutTunjangan');
			// $tanggal = $this->input->post('tanggal');
			// $waktu = $this->input->post('waktu');

				// $periodeKaryawan = $this->karyawanModel->periodeGajiKaryawan($periode);
				$dataTotalHari = $this->settingModel->getTotalHari();
				// $checkDataTerahkirPenggajian = $this->penggajianModel->getDataPenggajianTerakhir($periode);
				$setting = $this->settingModel->getById(1);
				$dataPenggajianAll = array();
				foreach ($listKaryawan as $key => $val) {
					$dataTerakhirPenggajian = $this->penggajianModel->getDataTerakhirPenggajian($val);
					if ($dataTerakhirPenggajian == null) {
						continue;
					}
					$dataAbsenToken = $this->penggajianModel->getDataPerBulan($dataTerakhirPenggajian->tanggal,date("Y-m-d"));
					$kode = $this->penggajianModel->idToKode($val);
					$dataPinjaman = $this->pinjamanModel->getDataPinjamanPenggajian($val);
					$reward = $this->rewardModel->getRewardPenggajian($dataTerakhirPenggajian->tanggal,date("Y-m-d"),$val);
					$punishment = $this->punishModel->getPunishmentPenggajian($dataTerakhirPenggajian->tanggal,date("Y-m-d"),$val);
					$thr = $this->thrModel->getThrPenggajian($dataTerakhirPenggajian->tanggal,date("Y-m-d"),$kode->agama);
					$extra = $this->ExtraabsensiModel->getExtraPenggajian($dataTerakhirPenggajian->tanggal,date("Y-m-d"),$kode->kode_karyawan);
					$jabatan = $this->jabatanModel->getDataPenggajianJabatan($val,$dataTerakhirPenggajian->tanggal,date("Y-m-d"));
					$token = $this->logabsensiModel->perBulan($dataTerakhirPenggajian->tanggal,date("Y-m-d"),$kode->kode_karyawan,null);
					// $hariLibur = $this->calendarModel->getHariLibur($dataTerakhirPenggajian->tanggal,date("Y-m-d"));
					$listCalendar = $this->calendarModel->getListCalendar($dataTerakhirPenggajian->tanggal,date("Y-m-d"));
					$totalHari = (int)$dataTotalHari->total_hari;
					// $totalGaji = 0;
					$totalBreakPunishment = 0;
					$potonganTelat = 0;

					foreach ($dataAbsenToken as $key => $vals) {
						// $totalGaji += $vals->payment;
						// if (($vals->telat >= 6) && ($vals->telat <= 10)) {
						// 	$potonganTelat += 10000;
						// }
						// else if (($vals->telat >= 11) && ($vals->telat <= 29)) {
						// 	$potonganTelat += 25000;
						// }
						$totalBreakPunishment += $vals->break_punishment;
					}
					if ($ikutTunjangan == 1) {
						if ($jabatan) {
							$totalTunjanganJabatan = $jabatan->tunjangan;
						}
						else {
							$totalTunjanganJabatan = 0;
						}
					}
					else {
						$totalTunjanganJabatan = 0;
					}

					$totalThr = 0;
					$gajiBersih = 0;
					$totalGaji = 0;
					$totalExtra = 0;
					$totalTelat = 0;
					$potonganTelat = 0;
					$potongPinjaman = 0;
					$totalPotongan = 0;
					$totalTransport = 0;
					$totalMakan = 0;
					$totalLibur = 0;
					$totalKerajinan = 0;
					$totalHK = 0;
					$telat = $this->logabsensiModel->getDataTelat($dataTerakhirPenggajian->tanggal,date("Y-m-d"),$kode->kode_karyawan);

					foreach ($telat as $value) {
						$totalTelat += $value->telat;
						if ($value->telat != 0) {
							if (($value->telat >= $setting->telat1) && ($value->telat <= $setting->telat2)) {
								$potonganTelat += $setting->potongan_telat1;
							}
							else if (($value->telat >= $setting->telat2) && ($value->telat <= $setting->telat3)) {
								$potonganTelat += $setting->potongan_telat2;
							}
						}
					}
					if ($dataPinjaman == null) {
						$potongPinjaman = 0;
					}
					else {
						$potongPinjaman = $dataPinjaman->cicilan;
						if ($dataPinjaman->sisa <= $potongPinjaman) {
							$potongPinjaman = $dataPinjaman->sisa;
							$dataLogPinjaman = array(
																				 'id_pinjaman' => $dataPinjaman->id_pinjaman,
																				 'pembayaran' => $dataPinjaman->sisa,
																				 'tanggal' => date("Y-m-d")
																			 );
						$update = $this->pinjamanModel->insertUpdate($dataPinjaman->id_pinjaman,$dataPinjaman->sisa,$dataLogPinjaman);
						}
						else {
							$dataLogPinjaman = array(
								'id_pinjaman' => $dataPinjaman->id_pinjaman,
								'pembayaran' => $potongPinjaman,
								'tanggal' => date("Y-m-d")
							);
							$update = $this->pinjamanModel->insertUpdate($dataPinjaman->id_pinjaman,$dataPinjaman->cicilan,$dataLogPinjaman);
						}
					}
					// token
					if ($token[0]->token == null) {
						$token[0]->token = 0;
					}
					//reward
					if ($reward[0]->nilai == null) {
						$reward[0]->nilai = 0;
					}
					//punishment
					if ($punishment[0]->nilai == null) {
						$punishment[0]->nilai = 0;
					}
					//thr
					if ($thr != null &&($thr->agama == $kode->agama)) {
						$totalThr = (int)$kode->thr;
					}

					//Kerajinan
					if ($setting->opsi_kerajinan == 1) {
						$jumlahAbsensi = $this->logabsensiModel->jumlahAbsensi($dataTerakhirPenggajian->tanggal,date("Y-m-d"),$kode->kode_karyawan);
						$totalAbsen = (int)$setting->total_hari - 2;
						if ($jumlahAbsensi >= $totalAbsen) {
							$totalKerajinan += $setting->kerajinan;
						}
					}

					if ($kode->lembur == 1) {
						$dataExtraperiode = $this->ExtraabsensiModel->getDataPerPeriode($dataTerakhirPenggajian->tanggal,date("Y-m-d"),$kode->kode_karyawan);
						foreach ($dataExtraperiode as $values) {
							if ($values->total == 0) {
								$values->total = 0;
							}
							if ($values->total <= 60) {
								$totalExtra += 1.5 * ((1/173*$kode->umk)/60) * $values->total;
							}
							else {
								$totalExtra += 1.5 * ((1/173*$kode->umk)/60) * $values->total;
								$totalExtra += 2 * ((1/173*$kode->umk)/60) * ($values->total - 60);
							}
						}
						// $totalExtra += 1.5 * ((1/173*$val->umk)/60) * $extra->total;
					}
					else {
						if ($extra[0]->total == null) {
							$extra[0]->total = 0;
						}
						if ($kode->lembur_tetap == 0 || $kode->lembur_tetap == null) {
							$kode->lembur_tetap = 0;
						}
						$totalExtra += ($kode->lembur_tetap/60) * $extra[0]->total;
					}

					$dataAbsensiKaryawan = $this->logabsensiModel->DataPerBulanShift($dataTerakhirPenggajian->tanggal,date("Y-m-d"),$kode->kode_karyawan,null);
					$tglCalendar = array();
					foreach ($listCalendar as $key => $calendar) {
						$tglCalendar[] = $calendar->tanggal;
					}
					//gaji hari libur
					// $totalGaji += sizeof($tglCalendar) * $kode->gaji;
					foreach ($dataAbsensiKaryawan as $vals) {
						if (($vals->kerja == "Normal" || $vals->kerja == "Normal(Manual)") && $vals->payment == 0) {
							continue;
						}
						if (strtotime($vals->tanggal) == strtotime($today) && (strtotime($waktu.":00") <= strtotime($vals->masuk))) {
							continue;
						}
						else {
							if (in_array($vals->tanggal,$tglCalendar)) {
								$totalHK += 1;
							}
							$totalHK += 1;
							$totalTransport += $kode->transport;
							$totalMakan += $kode->makan;
							// $totalGaji += ((($kode->umk/$totalHari)/$vals->max_jam_kerja)* $vals->token);
							// if ($kode->umk == 0) {
							// 	// $totalGaji += (($kode->gaji/$vals->max_jam_kerja)* $vals->token);
							// 	$totalGaji += ($vals->token / $vals->max_jam_kerja) * $kode->gaji;
							// }
							// else if ($vals->id_jadwal == null) {
							// 	$totalGaji += $vals->token * $kode->gaji_menit;
							// }
							// else {
							// 	$totalGaji += ((($kode->umk/$totalHari)/$vals->max_jam_kerja)* $vals->token);
							// }
							$totalLibur += $vals->tambahan;
							$totalGaji += $vals->payment;
						}
					}

					// sisa payment

					$sisaPayment = $this->penggajianModel->getSisa($val);
					$sisapaymentSebelumnya = 0;
					if ($ikutPotongSisa == 1) {
						if ($sisaPayment->sisa != null) {
							$sisapaymentSebelumnya = $sisaPayment->sisa;
							$this->penggajianModel->updateSisaPayment($idkaryawan,0);
						}
						else {
							$sisapaymentSebelumnya = 0;
						}
					}

					//bpjs

					$hasilbpjs = 0;

					if ($ikutPotongBpjs == 0) {
						$hasilbpjs = 0;
					}
					else {
						if ($kode->bpjs > 0) {
							$hariBerjalan = ((strtotime(date("Y-m-d")) - strtotime($dataTerakhirPenggajian->tanggal))/86400)+1;
							if ($kode->bpjs > 0) {
									$hasilbpjs = ((($kode->umk/($totalHari)) * ($kode->bpjs/100)) * $hariBerjalan);
							}
							else {
								$hasilbpjs = 0;
							}
						}
						else {
							$hasilbpjs = 0;
						}
					}
					$totalGaji = $totalGaji - ($totalTransport + $totalMakan);
					$totalGajiTambahan = $totalGaji + $totalTransport + $totalMakan + ($kode->tunjangan_lain) + $totalThr + $reward[0]->nilai + $totalExtra + $totalTunjanganJabatan + $sisapaymentSebelumnya;
					// $totalGaji = (($val->gaji)/480) * $token[0]->token;
					$totalPotongan += $hasilbpjs + $punishment[0]->nilai + $potongPinjaman + $potonganTelat;
					$gajiBersih = $totalGajiTambahan - $totalPotongan;
					// $row1[] = $gajiBersih;
					$hariKerjaEmploye = sizeof($dataAbsensiKaryawan);
					$dataPenggajianPerOrang = array(
																					'id_karyawan' => $val,
																					'tanggal_proses' => date("Y-m-d"),
																					'hari_kerja' => $totalHari,
																					'hari_kerja_karyawan' => $totalHK,
																					'nilai_bayar' => $gajiBersih,
																					'penggajian_tunjangan' => $totalTunjanganJabatan,
																					'potongan_bpjs' => $hasilbpjs,
																					'penggajian_transport' => $totalTransport,
																					'penggajian_makan' => $totalMakan,
																					'penggajian_tunlain' => $kode->tunjangan_lain,
																					'penggajian_thr' => $totalThr,
																					'reward' => $reward[0]->nilai,
																					'penggajian_libur' => $totalLibur,
																					'penggajian_kerajinan' => $totalKerajinan,
																					'start_date' => $dataTerakhirPenggajian->tanggal,
																					'end_date' => date("Y-m-d"),
																					'punishment' => $punishment[0]->nilai,
																					// 'token' => $token[0]->token,
																					'gaji_total_tambahan' => $totalGajiTambahan,
																					'gaji_total' => $totalGaji,
																					'gaji_extra' => $totalExtra,
																					'gaji_bersih' => $gajiBersih,
																					'potongan_telat' => $potonganTelat,
																					'potongan_pinjaman' => $potongPinjaman,
																					'keterangan' =>$dataTerakhirPenggajian->tanggal . " s/d " . date("Y-m-d"),
																					'id_akumulasi' => $sisapaymentSebelumnya,
																					'tipe_payment' => 1
																				 );
					$dataPenggajianAll[] = $dataPenggajianPerOrang;
					$updateAbsensiPenggajian = $this->penggajianModel->updateAbsensiPerOrang($kode->kode_karyawan);
				}
				$insert = "";
				if (sizeof($dataPenggajianAll) != 0) {
					$insert = $this->penggajianModel->insertMultiKaryawan($dataPenggajianAll,date("Y-m-d"));
				}
				else {
					$this->response->message = "<span style='color:red;'>Gagal, tidak ada data Penggajian.</span>";
				}
				// $update = $this->penggajianModel->update($periode);
				if ($insert) {
					$this->response->status = true;
					$this->response->message = "<span style='color:green;'>Berhasil proses Tambah Data Penggajian.</span>";
				} else {
					$this->response->message = "<span style='color:red;'>Gagal, tambah data Penggajian.</span>";
				}
		}
		parent::json();
	}

	public function last_payment($periode)
	{
		parent::checkLoginUser(); // user login autentic checking

		if ($this->isPost()) {
			$checkDataTerahkirPenggajian = $this->penggajianModel->last_pay_periode($periode);

			if (sizeof($checkDataTerahkirPenggajian) == 0) {
				$this->response->message = "tidak ada karyawan pada periode ini";
			}
			else {
				$lastpayment = $this->penggajianModel->getDataTerakhirPenggajian($checkDataTerahkirPenggajian[0]->id);
				if ($lastpayment != null) {
					$this->response->status = true;
					$this->response->message = "Berhasil get data last payment";
					$this->response->data = "Proses penggajian terakhir pada tanggal " . date_ind("d M Y",$lastpayment->tanggal);
				}
				else {
					$this->response->message = "tidak ada data penggajian terakhir pada periode gaji ".$periode;
				}
			}

			parent::json();
		}

	}

}
