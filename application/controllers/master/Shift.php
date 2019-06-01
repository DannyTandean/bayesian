<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Shift extends MY_Controller {

	public function __construct()
	{
		parent::__construct();
		$this->load->model('master/Shift_model',"shiftModel");

	}

	public function index()
	{
		parent::checkLoginUser(); // user login autentic checking

		parent::headerTitle("Master Data > Shift","Master Data","Shift");
		$breadcrumbs = array(
							"Master Data"	=>	site_url('master/shift'),
							"Shift"		=>	"",
						);
		parent::breadcrumbs($breadcrumbs);

		parent::viewMaster();
	}

	public function ajax_list($name)
	{
		parent::checkLoginUser(); // user login autentic checking

		if ($this->isPost()) {
			$data = array();

			$orderBy = array(null,null,"hari","awal_masuk","masuk","akhir_masuk","awal_keluar","keluar","akhir_keluar","break_out","break_in","durasi_break","opsi_break");
			$search = array("hari");

			$result = $this->shiftModel->findDataTable($orderBy,$search,$name);
			foreach ($result as $item) {
				// if ($item->shift == $name) {
					$btnAction = '<button class="btn btn-warning btn-outline-warning btn-mini" onclick="btnEdit('.$item->id_shift.')"><i class="fa fa-pencil-square-o"></i>Edit</button>';
					// $btnAction .= '&nbsp;&nbsp;&nbsp;<button class="btn btn-danger btn-outline-danger btn-mini" onclick="btnDelete('.$item->id_shift.')"><i class="fa fa-trash-o"></i>Hapus</button>';

					$item->button_action = $btnAction;
					$data[] = $item;
				// }

			}
			return $this->shiftModel->findDataTableOutput($data,$search,$name);
		}
	}

	public function add()
	{
		parent::checkLoginUser(); // user login autentic checking

		function dataHari($hari)
		{
			switch ($hari) {
				case '1':
					return "Monday";
					break;
				case '2':
					return "Tuesday";
					break;
				case '3':
					return "Wednesday";
					break;
				case '4':
					return "Thursday";
					break;
				case '5':
					return "Friday";
					break;
				case '6':
					return "Saturday";
					break;
				case '7':
					return 'Sunday';
					break;
			}
		}

		if ($this->isPost()) {
			$shift = $this->input->post("shift");
			$hari = dataHari($this->input->post("hari"));
			$awalMasuk = $this->input->post('awalMasuk');
			$masuk = $this->input->post('masuk');
			$akhirMasuk = $this->input->post('akhirMasuk');
			$awalKeluar = $this->input->post('awalKeluar');
			$keluar = $this->input->post('keluar');
			$akhirKeluar = $this->input->post('akhirKeluar');
			$break = $this->input->post('break');
			$durasiBreak = $this->input->post('durasiBreak');
			$breakOut = $this->input->post('breakOut');
			$breakIn = $this->input->post('breakIn');

			// $this->form_validation->set_rules('shift', 'Shift', 'trim|required|is_unique[master_shift.shift]');
			$this->form_validation->set_rules('break', 'Break', 'trim|required');
			$this->form_validation->set_rules('hari', 'Hari', 'trim|required');

			if ($this->form_validation->run() == TRUE) {
				$data = array(
								"shift"	=>	$shift,
								"awal_masuk"	=> $awalMasuk,
								"masuk"	=> $masuk,
								"akhir_masuk"	=> $akhirMasuk,
								"awal_keluar"	=> $awalKeluar,
								"keluar"	=> $keluar,
								"akhir_keluar"	=> $akhirKeluar,
								"break_out"	=> $breakOut,
								"break_in"	=> $breakIn,
								"durasi_break"	=> $durasiBreak,
								"opsi_break"	=> $break,
								"hari"	=> $hari
							);
				$insert = $this->shiftModel->insert($data);
				if ($insert) {
					$this->response->status = true;
					$this->response->message = alertSuccess("Berhasil tambah data shift.");
				} else {
					$this->response->message = alertDanger("Gagal tambah data shift.");
				}

			} else {
				// $this->response->message = validation_errors('<span style="color:red;">', '</span><br>');
				$this->response->error = array(
									"shift"	=> form_error("shift",'<span style="color:red;">','</span>'),
									"break"	=> form_error("break",'<span style="color:red;">','</span>'),
									"hari"	=> form_error("hari",'<span style="color:red;">','</span>'),
								);
			}
		}
		parent::json();
	}

	public function getId($id)
	{
		parent::checkLoginUser(); // user login autentic checking

		if ($this->isPost()) {
			$getById = $this->shiftModel->getById($id);
			if ($getById) {
				$this->response->status = true;
				$this->response->message = "Data shift get by id";
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

		function dataHari($hari)
		{
			switch ($hari) {
				case '1':
					return "Monday";
					break;
				case '2':
					return "Tuesday";
					break;
				case '3':
					return "Wednesday";
					break;
				case '4':
					return "Thursday";
					break;
				case '5':
					return "Friday";
					break;
				case '6':
					return "Saturday";
					break;
				case '7':
					return 'Sunday';
					break;
			}
		}
		if ($this->isPost()) {
			$shift = $this->input->post("shift");
			$hari = dataHari($this->input->post("hari"));
			$awalMasuk = $this->input->post('awalMasuk');
			$masuk = $this->input->post('masuk');
			$akhirMasuk = $this->input->post('akhirMasuk');
			$awalKeluar = $this->input->post('awalKeluar');
			$keluar = $this->input->post('keluar');
			$akhirKeluar = $this->input->post('akhirKeluar');
			$break = $this->input->post('break');

			$durasiBreak = $this->input->post('durasiBreak');
			$breakOut = $this->input->post('breakOut');
			$breakIn = $this->input->post('breakIn');
			$awalMasuk1 = strtotime($awalMasuk);
			$masuk1 = strtotime($masuk);
			$akhirMasuk1 = strtotime($akhirMasuk);
			$awalKeluar1 = strtotime($awalKeluar);
			$keluar1 = strtotime($keluar);
			$akhirKeluar1 = strtotime($akhirKeluar);
			$breakOut1 = strtotime($breakOut);
			$breakIn1 = strtotime($breakIn);
			$totalbreak = round(abs($breakOut1 - $breakIn1)/60);
			$totalkerja = round(abs($keluar1 - $masuk1)/60);
			$totaljamkerja = $totalkerja - $totalbreak;
			$totaljamkerja1 = $totalkerja - $durasiBreak;

			function decimalMenit($time)
			{
				$time = explode(":", $time);
				return ($time[0]*60) + ($time[1]);
			}

			// $this->form_validation->set_rules('shift', 'Shift', 'trim|required|is_unique[master_shift.shift]');
			// $this->form_validation->set_rules('hari', 'Hari', 'trim|required|is_unique[master_shift.hari]');
			$this->form_validation->set_rules('break', 'Break', 'trim|required');
			$this->form_validation->set_rules('awalMasuk', 'Awal Masuk', 'trim|required');
			$this->form_validation->set_rules('masuk', 'Masuk', 'trim|required');
			$this->form_validation->set_rules('akhirMasuk', 'Akhir Masuk', 'trim|required');
			$this->form_validation->set_rules('awalKeluar', 'Awal Keluar', 'trim|required');
			$this->form_validation->set_rules('keluar', 'Keluar', 'trim|required');
			$this->form_validation->set_rules('akhirKeluar', 'Akhir Keluar', 'trim|required');

			if ($this->form_validation->run() == TRUE) {

				if ($break == 1) { // durasi
					$breakOut = "00:00:00";
					$breakIn = "00:00:00";
				}

				$data = array(
					"shift"	=>	$shift,
					"awal_masuk"	=> $awalMasuk,
					"masuk"	=> $masuk,
					"akhir_masuk"	=> $akhirMasuk,
					"awal_keluar"	=> $awalKeluar,
					"keluar"	=> $keluar,
					"akhir_keluar"	=> $akhirKeluar,
					"break_out"	=> $breakOut,
					"break_in"	=> $breakIn,
					"durasi_break"	=> $durasiBreak,
					"opsi_break"	=> $break,
					// "hari"	=> $hari
					);
				if ($shift == "REGULAR") {
					if ($masuk1 >= strtotime("11:00:00")) {
						$this->response->message = alertDanger("Jam masuk tidak boleh lebih besar atau sama dengan jam 11:00 .! ");
					} elseif ($awalMasuk1 > $masuk1) {
						$this->response->message = alertDanger("Awal masuk tidak boleh lebih besar dari jam masuk.! ");
					} elseif ($akhirMasuk1 < $masuk1) {
						$this->response->message = alertDanger("Akhir masuk tidak boleh lebih kecil dari jam masuk.! ");
					} elseif ($awalKeluar1 > $keluar1) {
						$this->response->message = alertDanger("Awal keluar tidak boleh lebih besar dari jam keluar.! ");
					} elseif ($akhirKeluar1 < $keluar1) {
						$this->response->message = alertDanger("Akhir keluar tidak boleh lebih kecil dari jam keluar.! ");
					} else {
						if ($break == 1) {
							$totalkerja = $totaljamkerja1;
						}else{
							$totalkerja = $totaljamkerja;
						}
						if ($totalkerja > 480) {
							$this->response->message = alertDanger("Jam Kerja Karyawan Tidak Boleh Lebih Dari 8 jam ");
						}/* elseif ($totalkerja < 480) {
							$this->response->message = alertDanger("Jam Kerja Karyawan Tidak Boleh Kurang Dari 8 jam ");
						}*/ else {
							$update = $this->shiftModel->update($id,$data);
							if ($update) {
								$this->response->status = true;
								$this->response->message = alertSuccess("Berhasil update data shift REGULAR.");
							} else {
								$this->response->message = alertDanger("Gagal update data shift REGULAR.");
							}
						}	
					}
				} elseif ($shift == "SHIFT-PAGI") {
					if ($masuk1 >= strtotime("11:00:00")) {
						$this->response->message = alertDanger("Jam masuk tidak boleh lebih besar atau sama dengan jam 11:00 .!");
					} elseif ($awalMasuk1 > $masuk1) {
						$this->response->message = alertDanger("Awal masuk tidak boleh lebih besar dari jam masuk.! ");
					} elseif ($akhirMasuk1 < $masuk1) {
						$this->response->message = alertDanger("Akhir masuk tidak boleh lebih kecil dari jam masuk.! ");
					} elseif ($awalKeluar1 > $keluar1) {
						$this->response->message = alertDanger("Awal keluar tidak boleh lebih besar dari jam keluar.! ");
					} elseif ($akhirKeluar1 < $keluar1) {
						$this->response->message = alertDanger("Akhir keluar tidak boleh lebih kecil dari jam keluar.! ");
					} else {
						if ($totalkerja > 480) {
							$this->response->message = alertDanger("Jam Kerja Karyawan Tidak Boleh Lebih Dari 8 jam ");
						} elseif ($totalkerja < 480) {
							$this->response->message = alertDanger("Jam Kerja Karyawan Tidak Boleh Kurang Dari 8 jam ");
						} else {
							$update = $this->shiftModel->update($id,$data);
							if ($update) {
								$this->response->status = true;
								$this->response->message = alertSuccess("Berhasil update data shift SHIFT-PAGI.");
							} else {
								$this->response->message = alertDanger("Gagal update data shift SHIFT-PAGI.");
							}
						}	
					}
				} elseif ($shift == "SHIFT-SORE") {
					if ($masuk1 >= strtotime("18:00:00")) {
						$this->response->message = alertDanger("Jam masuk tidak boleh lebih besar atau sama dengan jam 18:00 .!");
					} elseif ($awalMasuk1 > $masuk1) {
						$this->response->message = alertDanger("Awal masuk tidak boleh lebih besar dari jam masuk.! ");
					} elseif ($akhirMasuk1 < $masuk1) {
						$this->response->message = alertDanger("Akhir masuk tidak boleh lebih kecil dari jam masuk.! ");
					} else {
						if ($masuk1 > $keluar1) {
							$heightJam = strtotime("23:59:00");
							$countMasuk = round(abs($heightJam - $masuk1)/60);
							$countKeluar = decimalMenit($keluar);
							$totalMasukKeluar = (($countMasuk + 1) + $countKeluar);
							if ($totalMasukKeluar > 480) {
								$this->response->message = alertDanger("Jam Kerja Karyawan Tidak Boleh Lebih Dari 8 jam ");
							} elseif ($totalMasukKeluar < 480) {
								$this->response->message = alertDanger("Jam Kerja Karyawan Tidak Boleh Kurang Dari 8 jam ");
							} else {
								$update = $this->shiftModel->update($id,$data);
								if ($update) {
									$this->response->status = true;
									$this->response->message = alertSuccess("Berhasil update data shift.");
								} else {
									$this->response->message = alertDanger("Gagal update data shift.");
								}
							}
						} elseif ($totalkerja > 480) {
							$this->response->message = alertDanger("Jam Kerja Karyawan Tidak Boleh Lebih Dari 8 jam ");
						} elseif ($totaljamkerja < 480 ) {
							$this->response->message = alertDanger("Jam Kerja Karyawan Tidak Boleh Kurang Dari 8 jam ");
						} else {
							$update = $this->shiftModel->update($id,$data);
							if ($update) {
								$this->response->status = true;
								$this->response->message = alertSuccess("Berhasil update data shift.");
							} else {
								$this->response->message = alertDanger("Gagal update data shift.");
							}
						}
					}	
				} elseif ($shift == "SHIFT-MALAM") { 
					if ($masuk1 > strtotime("01:00:00") AND $masuk1 < strtotime("01:00:00")) {
						$this->response->message = alertDanger("Jam masuk tidak boleh lebih besar dari jam 01:00 .!");
					} elseif ($masuk1 < strtotime("23:00:00") AND $masuk1 > strtotime("01:00:00")) {
						$this->response->message = alertDanger("Jam masuk tidak boleh lebih kecil dari jam 23:00 dan tidak boleh besar dari jam 01:00 .!");
					} elseif ($masuk1 > strtotime("01:00:00") AND $awalMasuk1 > $masuk1) {
						$this->response->message = alertDanger("Awal masuk tidak boleh lebih besar dari jam masuk.! ");
					}/* elseif ($akhirMasuk1 < $masuk1) {
						$this->response->message = alertDanger("Akhir masuk tidak boleh lebih kecil dari jam masuk.! ");
					}*/ elseif ($awalKeluar1 > $keluar1) {
						$this->response->message = alertDanger("Awal keluar tidak boleh lebih besar dari jam keluar.! ");
					} elseif ($akhirKeluar1 < $keluar1) {
						$this->response->message = alertDanger("Akhir keluar tidak boleh lebih kecil dari jam keluar.! ");
					} else {
						if ($masuk1 > $keluar1) {
							$heightJam = strtotime("23:59:00");
							$countMasuk = round(abs($heightJam - $masuk1)/60);
							$countKeluar = decimalMenit($keluar);
							$totalMasukKeluar = (($countMasuk + 1) + $countKeluar);
							if ($totalMasukKeluar > 480) {
								$this->response->message = alertDanger("Jam Kerja Karyawan Tidak Boleh Lebih Dari 8 jam ");
							} elseif ($totalMasukKeluar < 480) {
								$this->response->message = alertDanger("Jam Kerja Karyawan Tidak Boleh Kurang Dari 8 jam ");
							} else {
								$update = $this->shiftModel->update($id,$data);
								if ($update) {
									$this->response->status = true;
									$this->response->message = alertSuccess("Berhasil update data shift.");
								} else {
									$this->response->message = alertDanger("Gagal update data shift.");
								}
							}
						} elseif ($totalkerja > 480) {
							$this->response->message = alertDanger("Jam Kerja Karyawan Tidak Boleh Lebih Dari 8 jam ");
						} elseif ($totalkerja < 480) {
							$this->response->message = alertDanger("Jam Kerja Karyawan Tidak Boleh Kurang Dari 8 jam ");
						} else {
							$update = $this->shiftModel->update($id,$data);
							if ($update) {
								$this->response->status = true;
								$this->response->message = alertSuccess("Berhasil update data shift.");
							} else {
								$this->response->message = alertDanger("Gagal update data shift.");
							}
						}
					}
				} elseif ($shift == "SHIFT-PAGI-12-JAM") {
					if ($masuk1 >= strtotime("11:00:00")) {
						$this->response->message = alertDanger("Jam masuk tidak boleh lebih besar atau sama dengan jam 11:00 .!");
					} elseif ($awalMasuk1 > $masuk1) {
						$this->response->message = alertDanger("Awal masuk tidak boleh lebih besar dari jam masuk.! ");
					} elseif ($akhirMasuk1 < $masuk1) {
						$this->response->message = alertDanger("Akhir masuk tidak boleh lebih kecil dari jam masuk.! ");
					} elseif ($awalKeluar1 > $keluar1) {
						$this->response->message = alertDanger("Awal keluar tidak boleh lebih besar dari jam keluar.! ");
					} elseif ($akhirKeluar1 < $keluar1) {
						$this->response->message = alertDanger("Akhir keluar tidak boleh lebih kecil dari jam keluar.! ");
					} else {
						if ($totalkerja > 720) {
							$this->response->status = false;
							$this->response->message = alertDanger("Jam Kerja Karyawan Tidak Boleh Lebih Dari 12 jam ");
						} elseif ($totalkerja < 720) {
							$this->response->status = false;
							$this->response->message = alertDanger("Jam Kerja Karyawan Tidak Boleh Kurang Dari 12 jam ");
						} else {
							$update = $this->shiftModel->update($id,$data);
							if ($update) {
								$this->response->status = true;
								$this->response->message = alertSuccess("Berhasil update data shift.");
							} else {
								$this->response->message = alertDanger("Gagal update data shift.");
							}
						} 
					}
				} elseif ($shift == "SHIFT-MALAM-12-JAM") {
					if ($masuk1 >= strtotime("23:00:00")) {
						$this->response->message = alertDanger("Jam masuk tidak boleh lebih besar atau sama dengan jam 23:00 .!");
					} elseif ($awalMasuk1 > $masuk1) {
						$this->response->message = alertDanger("Awal masuk tidak boleh lebih besar dari jam masuk.! ");
					} elseif ($akhirMasuk1 < $masuk1) {
						$this->response->message = alertDanger("Akhir masuk tidak boleh lebih kecil dari jam masuk.! ");
					} elseif ($awalKeluar1 > $keluar1) {
						$this->response->message = alertDanger("Awal keluar tidak boleh lebih besar dari jam keluar.! ");
					} elseif ($akhirKeluar1 < $keluar1) {
						$this->response->message = alertDanger("Akhir keluar tidak boleh lebih kecil dari jam keluar.! ");
					} else {

						$totalkerja3 = round(abs(strtotime("23:59:59") - $masuk1)/60);
						$totalkerja4 = round(abs($keluar1 - strtotime("00:00:00"))/60);
						$totalkerja33 = $totalkerja4 + $totalkerja3;
						if ($masuk1 > $keluar1) {
							$heightJam = strtotime("23:59:00");
							$countMasuk = round(abs($heightJam - $masuk1)/60);
							$countKeluar = decimalMenit($keluar);
							$totalMasukKeluar = (($countMasuk + 1) + $countKeluar);
							if ($totalMasukKeluar > 720) {
								$this->response->message = alertDanger("Jam Kerja Karyawan Tidak Boleh Lebih Dari 12 jam ");
							} elseif ($totalMasukKeluar < 720) {
								$this->response->message = alertDanger("Jam Kerja Karyawan Tidak Boleh Kurang Dari 12 jam ");
							} else {
								$update = $this->shiftModel->update($id,$data);
								if ($update) {
									$this->response->status = true;
									$this->response->message = alertSuccess("Berhasil update data shift.");
								} else {
									$this->response->message = alertDanger("Gagal update data shift.");
								}
							}
						} elseif ($totalkerja33 > 720) {
							$this->response->status = false;
							$this->response->message = alertDanger("Jam Kerja Karyawan Tidak Boleh Lebih Dari 12 jam ");
						} elseif ($totalkerja33 < 720) {
							$this->response->status = false;
							$this->response->message = alertDanger("Jam Kerja Karyawan Tidak Boleh Kurang Dari 12 jam ");
						} else {
							$update = $this->shiftModel->update($id,$data);

							if ($update) {
								$this->response->status = true;
								$this->response->message = alertSuccess("Berhasil update data shift.");
							} else {
								$this->response->message = alertDanger("Gagal update data shift.");
							}
						}
					}
				} elseif ($shift == "MANUAL") {
						if ($keluar1 > strtotime("23:00:00")) {
							$this->response->message = alertDanger("Jam keluar tidak boleh lebih besar dari jam 23:00 .!");
						} elseif ($akhirKeluar1 > strtotime("23:58:00")) {
							$this->response->message = alertDanger("Jam akhir keluar tidak boleh lebih besar dari 23:59 .!");
						} elseif ($awalKeluar1 > strtotime("22:31:00")) {
							$this->response->message = alertDanger("Jam awal keluar tidak boleh lebih besar dari 22:30 .!");
						} elseif ($awalMasuk1 > $masuk1) {
							$this->response->message = alertDanger("Jam  awal masuk tidak boleh lebih besar atau sama dengan jam masuk.");
						} elseif ($awalKeluar1 > $keluar1) {
							$this->response->message = alertDanger("Jam awal keluar tidak boleh lebih besar atau sama dengan jam keluar.");
						} elseif ($akhirMasuk1 < $masuk1) {
							$this->response->message = alertDanger("Jam akhir masuk tidak boleh lebih kecil atau sama dengan jam masuk.");
						}elseif ($akhirKeluar1 < $keluar1) {
							$this->response->message = alertDanger("Jam akhir keluar tidak boleh lebih kecil atau sama dengan jam keluar.");
						}
						else{
							$update = $this->shiftModel->update($id,$data);
							if ($update) {
								$this->response->status = true;
								$this->response->message = alertSuccess("Berhasil update data shift.");
							} else {
								$this->response->message = alertDanger("Gagal update data shift.");
							}
						}	
				}
			} else {
				$this->response->message = validation_errors('<span style="color:red;">', '</span><br>');
				/*$this->response->error = array(
									"shift"	=> form_error("shift",'<span style="color:red;">','</span>'),
									"break"	=> form_error("break",'<span style="color:red;">','</span>'),
									"hari"	=> form_error("hari",'<span style="color:red;">','</span>'),
							);*/
			}

		
	}
		parent::json();
	}

	public function delete($id)
	{
		parent::checkLoginUser(); // user login autentic checking

		if ($this->isPost()) {
			$getById = $this->shiftModel->getById($id);
			if ($getById) {
				$delete = $this->shiftModel->delete($id);
				if ($delete) {
					$this->response->status = true;
					$this->response->message = alertSuccess("Data shift Berhasil di hapus.");
				} else {
					$this->response->message = alertDanger("Data sudah tidak ada.");
				}
			} else {
				$this->response->message = alertDanger("Data sudah tidak ada.");
			}
		}
		parent::json();
	}

}

/* End of file Grup.php */
/* Location: ./application/controllers/master/Grup.php */
