<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Penggajian extends MY_Controller {

	public function __construct()
	{
		parent::__construct();
		$this->load->model('aktivitas/Penggajian_model',"penggajianModel");
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
	}

	public function index()
	{
		parent::checkLoginUser(); // user login autentic checking

		parent::headerTitle("Aktivitas Data > Penggajian Karyawan","Aktivitas Data","Penggajian Karyawan");
		$breadcrumbs = array(
							"Aktivitas Data"	=>	site_url('aktivitas/penggajian'),
							"Penggajian"		=>	"",
						);
		parent::breadcrumbs($breadcrumbs);
		$data = array();
		$data['karyawan'] = $this->karyawanModel->getAll();
		parent::viewData($data);
		parent::viewAktivitas();
	}

	public function ajax_list($before=null,$after=null)
	{
		parent::checkLoginUser(); // user login autentic checking

		if ($this->isPost()) {
			$data = array();

			$orderBy = array(null,null,"kode_payroll","idfp","nama","golongan","token","gajipokok","tambahan","potongan","gajibersih");
			$search = array("kode_payroll","idfp","nama","golongan","tanggal_proses");

			$result = $this->penggajianModel->findDataTable($orderBy,$search,$after,$before);
			foreach ($result as $item) {
				$kodepayroll = "'" . $item->kode_payroll . "'";
				$btnTools = '<button class="btn btn-default btn-outline-default btn-mini" onclick="btnPrint('.$item->id_penggajian.','.$kodepayroll.')"><i class="fa fa-print"></i>Print</button>';
				$item->tanggal_proses =  date_ind("d M Y",$item->tanggal_proses);
				$item->gaji_bersih = "Rp.".number_format($item->gaji_bersih,0,",",",");
				$item->gaji_extra = "Rp.".number_format($item->gaji_extra,0,",",",");
				$item->punishment = "Rp.".number_format($item->punishment,0,",",",");
				$item->reward = "Rp.".number_format($item->reward,0,",",",");
				$item->potongan_telat = "Rp.".number_format($item->potongan_telat,0,",",",");
				// $item->gaji_bersih = "Rp.".number_format($item->gaji_bersih,0,",",",");
				$item->reward = 0;
				$item->punishment = 0;

				$item->button_tool = $btnTools;
				$data[] = $item;
			}
			return $this->penggajianModel->findDataTableOutput($data,$search,$after,$before);
		}
	}

	// public function ajax_list_after($before,$after)
	// {
	//  	self::ajax_list($before,$after);
	// }

	public function generate_penggajian()
	{
		//check login
		parent::checkLoginUser();
		function minutes($time){
				$time = explode(':', $time);
				return ($time[0]*60) + ($time[1]) + ($time[2]/60);
		}
		if ($this->isPost()) {
			$periode = $this->input->post('periode');
			$before = $this->input->post('before');
			$after = $this->input->post('after');
			$shift = $this->input->post('shift');

			$kodePayroll = "";
			$kode_akhir = $this->penggajianModel->getAll(false,array("kode_payroll"),array("LPAD(lower(kode_payroll), 20,0) DESC"));
			if ($kode_akhir == null) {
				$kodePayroll = 'PAYROLL-1';
			}
			else {
				$kodeUrut = (int) substr($kode_akhir[0]->kode_payroll, strpos($kode_akhir[0]->kode_payroll, '-') + 1);
				$kodePayroll = 'PAYROLL-'.($kodeUrut + 1);
			}
			$opsiShift = 0;
			if ($shift == "ya") {
				$opsiShift = 1;
			}
			else {
				$opsiShift = 0;
			}
			// hari pemerintah
			// $hari = 21;
			// $hari = 25;
			$dataTotalHari = $this->settingModel->getTotalHari();

			$hariLibur = $this->calendarModel->getHariLibur($before,$after);
			$time = strtotime($before);
			$hari = explode('-',date("Y-m-t",$time));
			// $totalHari = $hari[2] - $hariLibur;
			$jumlahSabtu = 0;

			$begin = date("Y-m-1",$time);
			$end = date("Y-m-t",$time);

			$begin1 = new DateTime($begin);
			$end1 = new DateTime($end);

			if ($dataTotalHari->otomatis_hari == 0) {
					$totalHari = (int)$dataTotalHari->total_hari;
			}
			else {
				//tanggal dinamis
				while ($begin1 <= $end1) // Loop will work begin to the end date
				{
					if($begin1->format("D") == "Sun") //Check that the day is Sunday here
					{
						  $totalHari -= 1;
					}
					$begin1->modify('+1 day');
				}
			}

			while ($begin1 <= $end1) // Loop will work begin to the end date
			{
				if ($begin1->format("D") == "Sat") {
					$jumlahSabtu += 1;
				}

				$begin1->modify('+1 day');
			}

			$listCalendar = $this->calendarModel->getListCalendar($before,$after);

			foreach ($listCalendar as $calendar) {
				$tglCalendar = new DateTime($calendar->tanggal);
				if ($tglCalendar->format("D") == "Sun") {
					$totalHari += 1;
				}
				if ($tglCalendar->format("D") == "Sat") {
					$jumlahSabtu -= 1;
				}
			}

			// $dataAbsensi = $this->logabsensiModel->perBulan(date("Y-m-1"),date("Y-m-t"),"EMP-1");
			$periodeKaryawan = $this->karyawanModel->periodeGaji($periode,$shift);
			$hasilGaji = array();
			$data = array();

			if ($periodeKaryawan != null) {
				foreach ($periodeKaryawan as $key => $val) {
					$data[] = $this->karyawanModel->getDataKaryawan($val->id);
				}
			}
			else {
				$data[] = null;
			}


			// foreach ($periodeKaryawan as $key => $val) {
			// 	$data[] = $this->karyawanModel->getDataKaryawan($val->id);
			// }

			$row = array(); // isi id karyawan
			$row1 = array(); // total perhitungan gaji golongan
			$dataPenggajianALl = array();
			$dataFilterPenggajian = $this->penggajianModel->getValidasiDataPenggajian($periode,$shift);
			//check
			// var_dump(strtotime($dataFilterPenggajian->end_date));
			// var_dump(strtotime($before));
			// var_dump(strtotime($dataFilterPenggajian->end_date) <= strtotime($before));
			// var_dump(($dataFilterPenggajian != null) && (strtotime($before) <= strtotime($dataFilterPenggajian->end_date)));
			// exit();
			if (($dataFilterPenggajian != null) && (strtotime($before) <= strtotime($dataFilterPenggajian->end_date))) {
				$this->response->message = "<span style='color:red;'>Data yang ditambahkan sudah ada </span>";
			}
			else if ($data == null || $data[0] == null) {
				$this->response->message = "<span style='color:red;'>tidak ada karyawan dengan shift dan periode yang anda pilih</span>";
			}
			else {
				foreach ($data as $val) {
					$token = $this->logabsensiModel->perBulan($before,$after,$val->kode_karyawan,$opsiShift); // total token periode absensi
					// $token = $this->logabsensiModel->perBulan(date("Y-m-1"),date("Y-m-t"),$val->kode_karyawan);
					$row[] = $val->id;
					$dataShift = $this->shiftModel->getSaturdayTime();
					$masuk = minutes($dataShift->masuk);
					$keluar = minutes($dataShift->keluar);
					$totalToken = $keluar - $masuk;
					// var_dump($val->umk);
					// var_dump($val->bpjs);
					// exit();
					if ($val->bpjs > 0) {
						$hasilbpjs = (($val->umk/((($totalHari-$jumlahSabtu)*480)+($jumlahSabtu*$totalToken))) * $token[0]->token) *($val->bpjs/100);
					}
					else {
						$hasilbpjs = 0;
					}
					$totalTransport = $val->transport * $totalHari;
					$totalMakan = $val->makan * $totalHari;
					$dataPinjaman = $this->pinjamanModel->getDataPinjamanPenggajian($val->id);
					$reward = $this->rewardModel->getRewardPenggajian($before,$after,$val->id);
					$punishment = $this->punishModel->getPunishmentPenggajian($before,$after,$val->id);
					$thr = $this->thrModel->getThrPenggajian($before,$after,$val->agama);
					$extra = $this->ExtraabsensiModel->getExtraPenggajian($before,$after,$val->kode_karyawan);
					$jabatan = $this->jabatanModel->getDataPenggajianJabatan($val->id,$before,$after);
					$totalTunjanganJabatan = 0;
					if ($jabatan) {
						$totalTunjanganJabatan = $jabatan->tunjangan;
					}
					else {
						$totalTunjanganJabatan = 0;
					}
					// $totalTunjanganJabatan = $jabatan->tunjangan;

					$totalThr = 0;
					$gajiBersih = 0;
					$totalGaji = 0;
					$totalExtra = 0;
					$totalTelat = 0;
					$potonganTelat = 0;
					$potongPinjaman = 0;
					$telat = $this->logabsensiModel->getDataTelat($before,$after,$val->kode_karyawan);

					foreach ($telat as $value) {
						$totalTelat += $value->telat;
						if ($value->telat != 0) {
							if (($value->telat >= 6) && ($value->telat <= 10)) {
								$potonganTelat += 10000;
							}
							else if (($value->telat >= 11) && ($value->telat <= 29)) {
								$potonganTelat += 25000;
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
						$update = $this->pinjamanModel->insertUpdate($val->id,$dataPinjaman->sisa,$dataLogPinjaman);
						}
						else {
							$dataLogPinjaman = array(
								'id_pinjaman' => $dataPinjaman->id_pinjaman,
								'pembayaran' => $potongPinjaman,
								'tanggal' => date("Y-m-d")
							);
							$update = $this->pinjamanModel->insertUpdate($val->id,$dataPinjaman->cicilan,$dataLogPinjaman);
						}
					}

					if ($token[0]->token == null) {
						$token[0]->token = 1;
					}
					if ($reward[0]->nilai == null) {
						$reward[0]->nilai = 0;
					}
					if ($punishment[0]->nilai == null) {
						$punishment[0]->nilai = 0;
					}

					if ($thr != null &&($thr->agama == $val->agama)) {
						$totalThr = (int)$val->thr;
					}

					if ($val->lembur == 1) {
						$dataExtraperiode = $this->ExtraabsensiModel->getDataPerPeriode($before,$after,$val->kode_karyawan);
						foreach ($dataExtraperiode as $values) {
							if ($values->total == 0) {
								$values->total = 0;
							}
							if ($values->total < 120) {
								$totalExtra += 1.5 * ((1/173*$val->umk)/60) * $values->total;
							}
							else {
								$totalExtra += 1.5 * ((1/173*$val->umk)/60) * $values->total;
								$totalExtra += 2 * ((1/173*$val->umk)/60) * ($values->total - 60);
							}
						}
						// $totalExtra += 1.5 * ((1/173*$val->umk)/60) * $extra->total;
					}
					else {
						if ($extra[0]->total == null) {
							$extra[0]->total = 0;
						}
						if ($val->lembur_tetap == 0 || $val->lembur_tetap == null) {
							$val->lembur_tetap = 0;
						}
						$totalExtra += ($val->lembur_tetap/60) * $extra[0]->total;
					}

					if ($shift == "ya") {
						$dataAbsensiKaryawan = $this->logabsensiModel->DataPerBulan($before,$after,$val->kode_karyawan,$opsiShift);

						foreach ($dataAbsensiKaryawan as $vals) {
								$totalGaji += ((($val->umk/$totalHari)/$vals->max_jam_kerja)* $vals->token);
						}
						$totalGaji += $totalTransport + $totalMakan + ($val->tunjangan_lain) + $totalThr + $reward[0]->nilai + $totalExtra + $totalTunjanganJabatan;
						// $totalGaji = (($val->gaji)/480) * $token[0]->token;
						$gajiBersih = $totalGaji - $hasilbpjs - $punishment[0]->nilai - $potongPinjaman - $potonganTelat;
						$row1[] = $gajiBersih;
					}
					else {
						// $totalGaji = (($val->umk/((($totalHari-$jumlahSabtu)*480)+($jumlahSabtu*$totalToken))) * $token[0]->token) + $totalTransport + $totalMakan + ($val->tunjangan_lain) + $totalThr + $reward[0]->nilai + $totalExtra + $totalTunjanganJabatan;
						// $gajiBersih =  $totalGaji - $hasilbpjs  - $punishment[0]->nilai - $potongPinjaman - $potonganTelat  ;
						// $row1[] = $gajiBersih;
						$dataAbsensiKaryawan = $this->logabsensiModel->DataPerBulan($before,$after,$val->kode_karyawan,$opsiShift);
						foreach ($dataAbsensiKaryawan as $vals) {
								$totalGaji += ((($val->umk/$totalHari)/$vals->max_jam_kerja)* $vals->token);
						}
						$totalGaji += $totalTransport + $totalMakan + ($val->tunjangan_lain) + $totalThr + $reward[0]->nilai + $totalExtra + $totalTunjanganJabatan;
						// $totalGaji = (($val->gaji)/480) * $token[0]->token;
						$gajiBersih = $totalGaji - $hasilbpjs - $punishment[0]->nilai - $potongPinjaman - $potonganTelat;
						$row1[] = $gajiBersih;
					}
					// $row1[] = (($val->umk/(($totalHari*480)+($cuti->lama_cuti*480))) * $token[0]->token) - $hasilbpjs + $totalTransport + $totalMakan + ($val->tunjangan_lain) + $reward[0]->nilai - $punishment[0]->nilai;
					$dataPenggajianPerOrang = array(
																					'kode_payroll' => $kodePayroll,
																					'id_karyawan' => $val->id,
																					'tanggal_proses' => date("Y-m-d"),
																					'hari_kerja' => $totalHari,
																					'penggajian_tunjangan' => $totalTunjanganJabatan,
																					'potongan_bpjs' => $hasilbpjs,
																					'penggajian_transport' => $totalTransport,
																					'penggajian_makan' => $totalMakan,
																					'penggajian_tunlain' => $val->tunjangan_lain,
																					'penggajian_thr' => $totalThr,
																					'reward' => $reward[0]->nilai,
																					'start_date' => $before,
																					'end_date' => $after,
																					'shift' => $shift,
																					'periode_penggajian' => $periode,
																					'punishment' => $punishment[0]->nilai,
																					'token' => $token[0]->token,
																					'gaji_total' => $totalGaji,
																					'gaji_extra' => $totalExtra,
																					'gaji_bersih' => $gajiBersih,
																					'potongan_telat' => $potonganTelat,
																					'potongan_pinjaman' => $potongPinjaman,
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
		}
		parent::json();
	}

	public function getSlipGajiKaryawan($id,$kodePayroll)
	{
		if ($this->isPost()) {
			$dataPenggajianKaryawan = $this->penggajianModel->getDataPenggajianKaryawan($id);
			$dataAbsensi = $this->logabsensiModel->DataPerBulanAbsensi($dataPenggajianKaryawan->start_date,$dataPenggajianKaryawan->end_date,$dataPenggajianKaryawan->kode_karyawan);
			$dataKaryawan = $this->karyawanModel->getDataLengkap($dataPenggajianKaryawan->id,$kodePayroll);
			$dataSetting = $this->settingModel->getById(1);
			$dataGolonganKaryawan = $this->penggajianModel->getDataPenggajianPerKaryawan($dataPenggajianKaryawan->id);
			// var_dump($dataPenggajianKaryawan);
			// var_dump($dataKaryawan);
			// var_dump($dataGolonganKaryawan);
			// exit();
			//proses perhitungan

			// hari pemerintah
			// $hari = 21;
			// $hari = 25;
			// $hariLibur = $this->calendarModel->getHariLibur($before,$after);
			$hari = explode('-',date("Y-m-t"));
			$totalHari = $hari[2];

			$begin  = new DateTime(date("Y-m-1"));
			$end    = new DateTime(date("Y-m-t"));
			while ($begin <= $end) // Loop will work begin to the end date
			{
			    if($begin->format("D") == "Sun") //Check that the day is Sunday here
			    {
						  $totalHari -= 1;
			    }

			    $begin->modify('+1 day');
			}


			$hasilGaji = array();

			$row = array(); // isi id karyawan
			$row1 = array(); // total perhitungan gaji golongan
			// $dataFilterPenggajian = $this->penggajianModel->getValidasiDataPenggajian($periode,$shift);
			// $token = $this->logabsensiModel->perBulan($dataPenggajianKaryawan->start_date,$dataPenggajianKaryawan->end_date,$dataPenggajianKaryawan->kode_karyawan);
			// $hasilbpjs = ceil(((($dataGolonganKaryawan->umk/($totalHari*480)) * $token[0]->token)) * ($dataGolonganKaryawan->bpjs/100));
			$totalTransport = $dataGolonganKaryawan->transport * $totalHari;
			$totalMakan = $dataGolonganKaryawan->makan * $totalHari;
			// $dataPinjaman = $this->pinjamanModel->getDataPinjamanPenggajian($dataPenggajianKaryawan->id);
			$reward = $this->rewardModel->getRewardPenggajian($dataPenggajianKaryawan->start_date,$dataPenggajianKaryawan->end_date,$dataPenggajianKaryawan->id);
			$punishment = $this->punishModel->getPunishmentPenggajian($dataPenggajianKaryawan->start_date,$dataPenggajianKaryawan->end_date,$dataPenggajianKaryawan->id);
			$thr = $this->thrModel->getThrPenggajian($dataPenggajianKaryawan->start_date,$dataPenggajianKaryawan->end_date,$dataKaryawan->agama);
			$extra = $this->ExtraabsensiModel->getExtraPenggajian($dataPenggajianKaryawan->start_date,$dataPenggajianKaryawan->end_date,$dataPenggajianKaryawan->kode_karyawan);
			$jabatan = $this->jabatanModel->getDataPenggajianJabatan($dataPenggajianKaryawan->id,$dataPenggajianKaryawan->start_date,$dataPenggajianKaryawan->end_date);
			$totalTunjanganJabatan = 0;
			if ($jabatan) {
				$totalTunjanganJabatan = $jabatan->tunjangan;
			}
			else {
				$totalTunjanganJabatan = 0;
			}
			// $totalTunjanganJabatan = $jabatan->tunjangan;

			$totalThr = 0;
			$gajiBersih = 0;
			$totalGaji = 0;
			$totalExtra = 0;
			$totalPotongan = 0;
			$totalGaji1 = 0;
			$potongPinjaman = 0;
			$totalTelat = 0;
			$telat = $this->logabsensiModel->getDataTelat($dataPenggajianKaryawan->start_date,$dataPenggajianKaryawan->end_date,$dataPenggajianKaryawan->kode_karyawan);

			foreach ($telat as $value) {
				$totalTelat += $value->telat;
			}
			if ($reward[0]->nilai == null) {
				$reward[0]->nilai = 0;
			}
			if ($punishment[0]->nilai == null) {
				$punishment[0]->nilai = 0;
			}

			if ($thr != null &&($thr->agama == $dataKaryawan->agama)) {
				$totalThr = (int)$dataGolonganKaryawan->thr;
			}
			$counter = 0;
			foreach ($dataAbsensi as $key => $vals) {
					if ($vals->lupa_keluar_kabag == 1) {
							$dataAbsensi[$counter]->kerja = "Lupa Keluar";
					}
					if ($vals->telat < 0) {
						$dataAbsensi[$counter]->telat = 0;
					}
					$counter++;
			}
			$dataGolonganKaryawan->hasilBpjs = "Rp.".number_format($dataPenggajianKaryawan->potongan_bpjs,0,",",",");
			$dataGolonganKaryawan->totalTunjangan = "Rp.".number_format($totalTunjanganJabatan,0,",",",");
			$dataGolonganKaryawan->makan = "Rp.".number_format($dataGolonganKaryawan->makan,0,",",",");
			$dataKaryawan->absensiCount = count($dataAbsensi);
			//end proses perhitungan
			$potonganALl = $dataPenggajianKaryawan->punishment + $dataPenggajianKaryawan->potongan_telat + $dataPenggajianKaryawan->potongan_pinjaman + $dataPenggajianKaryawan->potongan_bpjs;
			$dataPerhitunganGaji = array(
																		'totalGaji' =>"Rp.".number_format($dataPenggajianKaryawan->gaji_total,0,",",","),
																		'gajiBersih' => "Rp.".number_format($dataPenggajianKaryawan->gaji_bersih,0,",",","),
																		'totalPotongan' => "Rp.".number_format($potonganALl,0,",",","),
																		'totalExtra' => "Rp.".number_format($dataPenggajianKaryawan->gaji_extra,0,",",","),
																		'potongan_pinjaman' => "Rp.".number_format($dataPenggajianKaryawan->potongan_pinjaman,0,",",","),
																		'potongan_telat' => "Rp.".number_format($dataPenggajianKaryawan->potongan_telat,0,",",","),
																		'total_telat' => $totalTelat
																	);
			$this->response->status = true;
			$this->response->data = $dataAbsensi;
			$this->response->data1 = $dataKaryawan;
			$this->response->data2 = $dataSetting->nama_perusahaan;
			$this->response->data3 = $dataGolonganKaryawan;
			$this->response->data4 = $dataPerhitunganGaji;
		}
		parent::json();
	}

}
