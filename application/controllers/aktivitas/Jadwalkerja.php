<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Jadwalkerja extends MY_Controller {

	public function __construct()
	{
		parent::__construct();
		$this->load->model('aktivitas/Jadwalkerja_model',"jadwalKerjaModel");
		$this->load->model('master/Karyawan_model', "karyawanModel");
		parent::checkLoginOwner();

	}

	public function index()
	{
		parent::checkLoginUser(); // user login autentic checking

		parent::headerTitle("Aktivitas Data > Jadwal kerja","Aktivitas Data","Jadwal kerja");
		$breadcrumbs = array(
							"Aktivitas"		=>	site_url('aktivitas/jadwalkerja'),
							"Jadwal kerja"	=>	"",
						);
		parent::breadcrumbs($breadcrumbs);
		$dataGroup = $this->jadwalKerjaModel->groupKaryawan();
		$dataShift = $this->jadwalKerjaModel->shiftGroup();
		parent::viewData(array("dataGroup"=>$dataGroup, "dataShift"=>$dataShift));

		parent::viewAktivitas();
	}

	public function ajax_list($bulan="saat_ini",$tglBerlalu=false)
	{
		parent::checkLoginUser(); // user login autentic checking

		if ($this->isPost()) {
			$data = array();
			$select = "jadwal_token_karyawan.*, master_karyawan.otoritas, master_karyawan.id_grup, master_grup.grup";
			$where = array(
							"YEAR(tanggal)" => 	date("Y"),
							// "otoritas !="	=>	4, // otoritas security
						);
			if ($bulan == "saat_ini") {
				$where["MONTH(tanggal)"] = date("m");
				if ($tglBerlalu) {
					$where["tanggal <"]	= date("Y-m-d");
				} else {
					$where["tanggal >="] = date("Y-m-d");
				}
			} else if ($bulan == "lalu") {
				$where["MONTH(tanggal)"] = date("m", strtotime(" -1 months"));
				if (date("m") == 1) {
					$where["YEAR(tanggal)"] = date("Y", strtotime(" -1 years"));
				}
			} else if ($bulan == "depan") {
				$where["MONTH(tanggal)"] = date("m", strtotime(" +1 months"));
				if (date("m") == 12) {
					$where["YEAR(tanggal)"] = date("Y", strtotime(" +1 years"));
				}
			}

			$orderBy = array(null,"tanggal","grup","nama_shift");
			$search = array("tanggal","grup","nama_shift");
			$result = $this->jadwalKerjaModel->findDataTable($select,$where,$orderBy,$search);
			foreach ($result as $item) {

				// $item->tanggal = date_ind("d M Y",$item->tanggal);
				$tgl_idGroup = "'".$item->tanggal."',".$item->group_id.",'".$item->nama_shift."'";

				$karyawan_master = array();
				$getAllMst_karyawan = $this->karyawanModel->getAll(array("id_grup" => $item->id_grup),array("id"),array("id ASC"));
				foreach ($getAllMst_karyawan as $val) {
					$karyawan_master[] = $val->id;
				}
				$karyawan_jadwal = array();
				$getAllJad_karyawan = $this->jadwalKerjaModel->getAll(array("tanggal" => $item->tanggal, "group_id" => $item->group_id),array("karyawan_id"),array("karyawan_id ASC"));
				foreach ($getAllJad_karyawan as $value) {
					$karyawan_jadwal[] = $value->karyawan_id;
				}
				// $diff_karyawan = array_diff($karyawan_master, $karyawan_jadwal);
				$diff_karyawan_jadwal = array_diff($karyawan_jadwal, $karyawan_master);
				$diff_karyawan_master = array_diff($karyawan_master, $karyawan_jadwal);
				
				$countMst_karyawan = count($getAllMst_karyawan);
				$countJad_karyawan = count($getAllJad_karyawan);
				$countDiff_kry_jdwl = count($diff_karyawan_jadwal);
				$countDiff_kry_mstr = count($diff_karyawan_master);

				$btnOpsi = '';
				if ($bulan == "saat_ini") {
					
					// $btnOpsi .= count($diff_karyawan_master)." - ".count($getAllMst_karyawan)
					//     ."<br>".count($diff_karyawan_jadwal)." - ".count($getAllJad_karyawan)."<br>";
					// $btnOpsi .= "jumlah master_karyawan = ".$getAllMst_karyawan." jumlah jadwal_karyawan =".$getAllJad_karyawan."<br>";
					if (($countDiff_kry_jdwl != $countDiff_kry_mstr) && ($countMst_karyawan != $countJad_karyawan)) {
						if ($item->tanggal > date('Y-m-d')) {
							$btnOpsi .= '<button class="btn btn-outline-danger  btn-mini" title="Jumlah Karyawan Bulan Saat ini" onclick="btnKaryawanSaatIni('.$tgl_idGroup.')"><i class="fa fa-users"></i>Perubahan Karyawan</button>';
						} else {
							$btnOpsi .= '<button class="btn btn-outline-dark  btn-mini" title="Jumlah Karyawan Bulan Saat ini" onclick="btnKaryawanSaatIni('.$tgl_idGroup.')"><i class="fa fa-users"></i>Karyawan</button>';
						}
					} else {
						$btnOpsi .= '<button class="btn btn-outline-dark  btn-mini" title="Jumlah Karyawan Bulan Saat ini" onclick="btnKaryawanSaatIni('.$tgl_idGroup.')"><i class="fa fa-users"></i>Karyawan</button>';
					}

					if ($item->tanggal > date('Y-m-d')) {
						$btnOpsi .= '&nbsp;&nbsp;&nbsp;&nbsp;<button class="btn btn-outline-warning  btn-mini" title="Edit Jadwal Kerja" onclick="btnEditSaatIni('.$tgl_idGroup.')"><i class="fa fa fa-pencil-square-o"></i>Edit</button>';
					}
				} else if ($bulan == "depan") {
					// $btnOpsi .= '<button class="btn btn-outline-dark  btn-mini" title="Jumlah Karyawan Bulan depan" onclick="btnKaryawanDepan('.$tgl_idGroup.')"><i class="fa fa-users"></i>Karyawan</button>';

					// if (($countDiff_kry_jdwl != $countDiff_kry_mstr) && ($countMst_karyawan != $countJad_karyawan)) {
					// 	if ($item->tanggal > date('Y-m-d')) {
					// 		$btnOpsi .= '<button class="btn btn-outline-danger  btn-mini" title="Jumlah Karyawan Bulan depan" onclick="btnKaryawanDepan('.$tgl_idGroup.')"><i class="fa fa-users"></i>Perubahan Karyawan</button>';
					// 	} else {
					// 		$btnOpsi .= '<button class="btn btn-outline-dark  btn-mini" title="Jumlah Karyawan Bulan depan" onclick="btnKaryawanDepan('.$tgl_idGroup.')"><i class="fa fa-users"></i>Karyawan</button>';
					// 	}
					// } else {
						$btnOpsi .= '<button class="btn btn-outline-dark  btn-mini" title="Jumlah Karyawan Bulan depan" onclick="btnKaryawanDepan('.$tgl_idGroup.')"><i class="fa fa-users"></i>Karyawan</button>';
					// }

					$btnOpsi .= '&nbsp;&nbsp;&nbsp;&nbsp;<button class="btn btn-outline-warning  btn-mini" title="Edit Jadwal Kerja" onclick="btnEditDepan('.$tgl_idGroup.')"><i class="fa fa fa-pencil-square-o"></i>Edit</button>';
				} else {
					$btnOpsi .= '<button class="btn btn-outline-dark  btn-mini" title="Jumlah Karyawan Bulan Lalu" onclick="btnKaryawanLalu('.$tgl_idGroup.')"><i class="fa fa-users"></i>Karyawan</button>';
				}
				$item->nama_shift = $item->nama_shift == "OFF" ? "<span style='color:red;'>".$item->nama_shift."</span>" : $item->nama_shift;
				$item->button_opsi = $btnOpsi;

				$dataTglGroupSama = $this->jadwalKerjaModel->getDataSama($item->tanggal,$item->group_id,false,true);
				if ($dataTglGroupSama) {
					$rowSame = array();
					foreach ($dataTglGroupSama as $val) {
						$rowSame[] = $val->nama_shift;
					}
					$rowSame = array_unique($rowSame);
					if (count($rowSame) > 1) {
						$item->dataSameShift = $rowSame;
						$item->dataSameShiftCount = count($rowSame);
						$item->tanggal = spanRed("<strike title='Duplicate data'>".$item->tanggal."<strike>");
						$item->grup = spanRed("<strike title='Duplicate data'>".$item->grup."<strike>");
					}
				}
				
				$data[]	= $item;
			}
			return $this->jadwalKerjaModel->findDataTableOutput($data,$select,$where,$search);
		}
	}

	public function ajax_list_jumlah_karyawan($tgl="",$groupId="",$namaShift="")
	{
		parent::checkLoginUser(); // user login autentic checking

		if ($this->isPost()) {
			$data = array();
			$where = array(
								"tanggal"		=>	$tgl,
								"group_id"		=>	$groupId,
								// "nama_shift"	=>	$namaShift,
								"otoritas_kerja !="	=>	4, // otoritas security
							);
			$orderBy = array(null,null,"nama","idfp","nama_shift","tgl_lahir","kelamin","master_jabatan.jabatan");
			$search = array("nama","idfp","nama_shift","tgl_lahir","kelamin","master_jabatan.jabatan");
			$result = $this->jadwalKerjaModel->findDataTableKaryawan($where,$orderBy,$search);
			foreach ($result as $item) {
				$karyawanGanti = $this->jadwalKerjaModel->karyawanId($item->ganti_karyawan_id);
				if ($karyawanGanti) {
					$item->nama = "<strike style='color:red;'>".$item->nama."</strike> <br>".$karyawanGanti->nama;
					$item->idfp = "<strike style='color:red;'>".$item->idfp."</strike> <br>".$karyawanGanti->idfp;
					$item->tgl_lahir = "<strike style='color:red;'>".date_ind("d M Y", $item->tgl_lahir)."</strike> <br>".date_ind("d M Y", $karyawanGanti->tgl_lahir);
					if ($item->kelamin != $karyawanGanti->kelamin) {
						$item->kelamin = "<strike style='color:red;'>".ucfirst($item->kelamin)."</strike> <br>".ucfirst($karyawanGanti->kelamin);
					}
					$item->jabatan = "<strike style='color:red;'>".$item->jabatan."</strike> <br>".$karyawanGanti->jabatan;
				} else {
					$item->tgl_lahir = date_ind("d M Y", $item->tgl_lahir);
					$item->kelamin = ucfirst($item->kelamin);
				}

				$karyawan_master = array();
				$getAllMst_karyawan = $this->karyawanModel->getAll(array("id_grup" => $groupId),array("id"),array("id ASC"));
				foreach ($getAllMst_karyawan as $val) {
					$karyawan_master[] = $val->id;
				}

				$karyawan_jadwal = array();
				$getAllJad_karyawan = $this->jadwalKerjaModel->getAll(array("tanggal" => $tgl, "group_id" => $groupId),array("karyawan_id"),array("karyawan_id ASC"));
				foreach ($getAllJad_karyawan as $value) {
					$karyawan_jadwal[] = $value->karyawan_id;
				}

				$diff_karyawan_master = array_diff($karyawan_master, $karyawan_jadwal);
				$diff_karyawan_jadwal = array_diff($karyawan_jadwal, $karyawan_master);

				// var_dump($diff_karyawan_master);
				// var_dump($diff_karyawan_jadwal);

				$bulanSaatIni = intval(date("m"));
				$bulanItem = intval(date("m", strtotime($item->tanggal)));

				$btnAction = "<span style='color:red;'>sudah berlalu</span>";
				if ($item->tanggal >= date("Y-m-d") && $bulanSaatIni == $bulanItem) {
					$idData = $item->id.",".$item->karyawan_id;
					
					$btnAction = "";
					$checkAbsensiHariIni = $this->jadwalKerjaModel->checkAbsensiHariIni($item->tanggal,$item->kode_karyawan);
					if ($checkAbsensiHariIni) {
						$btnAction = spanGreen("sudah masuk");
						if ($checkAbsensiHariIni->kerja == "Dinas") {
							$btnAction = spanGreen("Sedang Dinas");
						} else if ($checkAbsensiHariIni->kerja == "Sakit") {
							$btnAction = spanGreen("Sedang Sakit");
						} else if ($checkAbsensiHariIni->kerja == "Cuti") {
							$btnAction = spanGreen("Sedang Cuti");
						} else if ($checkAbsensiHariIni->kerja == "Izin") {
							$btnAction = spanGreen("Sedang Izin");
						}  
						if ($item->otoritas_kerja == 2) { // otoritas kabag
							$btnAction = "<span style='color:blue;'>Kabag</span> ".$btnAction;
						} elseif ($item->otoritas_kerja == 3) { // otoritas HRD
							$btnAction = "<span style='color:purple;'>HRD</span> ".$btnAction;
						}
					} else {
						$btnAction = '<button class="btn btn-outline-warning btn-mini" title="Edit" onclick="btnGanti('.$idData.')"><i class="fa fa-pencil-square-o"></i>Ganti</button>';
						if ($item->otoritas_kerja == 2) { // otoritas kabag
							$btnAction = "<span style='color:blue;'>Kabag</span>";
						} elseif ($item->otoritas_kerja == 3) { // otoritas HRD
							$btnAction = "<span style='color:purple;'>HRD</span>";
						}

						if (count($diff_karyawan_jadwal) > 0) {
							// $btnAction .= '<br>'.$diff_karyawan_jadwal[1];
							if ($item->karyawan_id == $diff_karyawan_jadwal[1]) {
								$btnAction = '<button class="btn btn-outline-danger btn-mini" title="Bukan anggota group" onclick="btnHapusKaryawanSaatIni('.$item->id.')"><i class="fa fa-ban"></i>Hapus</button>';
							}
						}
					}
					
				} elseif ($bulanSaatIni < $bulanItem) { // for bulan depan
					// print_r($diff_karyawan_jadwal);
					$btnAction = spanRed("belum berjalan");
					/*if (count($diff_karyawan_jadwal) > 0) {
						// $btnAction .= '<br>'.$diff_karyawan_jadwal[1];
						if ($item->karyawan_id == $diff_karyawan_jadwal[7]) {
							$btnAction = '<button class="btn btn-outline-danger btn-mini" title="Bukan anggota group" onclick="btnHapusKaryawanDepan('.$item->id.')"><i class="fa fa-ban"></i>Hapus</button>';
						}
					}*/
				}

				$item->button_opsi = $btnAction;
				$data[] = $item;
			}
			return $this->jadwalKerjaModel->findDataTableOutputKaryawan($where,$data,$search);
		}
	}

	public function setValidate()
	{
		$this->form_validation->set_rules('tanggal', 'Tanggal', 'trim|required');
		$this->form_validation->set_rules('nama_group', 'Nama Group', 'trim|required');
		$this->form_validation->set_rules('nama_shift', 'Nama Shift', 'trim|required');
	}

	public function checkInsertDateNow()
	{
		parent::checkLoginUser(); // user login autentic checking

		if ($this->isPost()) {
			self::setValidate();

			$tanggal = $this->input->post("tanggal");
			$nama_group = $this->input->post("nama_group");
			$nama_shift = $this->input->post('nama_shift');

			if ($this->form_validation->run() == TRUE) {

				if (date("Y-m-d") == $tanggal) {
					$tanggalNow = "date_now";
					$this->response->status = true;
					$this->response->message = "date_now";
					$this->response->data = alertInfo("Pastikan anda menginput data dengan benar.<br>Data yang sama dengan tanggal saat ini tidak bisa di edit/update lagi.!");
				} else if (date("Y-m-d") < $tanggal) {
					$this->response->status = true;
					$this->response->message = "date_next";
				} else if (date("Y-m-d") > $tanggal) {
					$this->response->status = true;
					$this->response->message = "date_prev";
					$this->response->data = alertDanger("Data yang di input tidak boleh lebih kecil dari tanggal sekarang.!");
				}
			} else {
				$this->response->message = validation_errors('<span style="color:red;">', '</span><br>');
			}

		}
		parent::json();
	}

	/*for bulan saat ini*/
	public function insertBulanSaatIni()
	{
		parent::checkLoginUser(); // user login autentic checking

		if ($this->isPost()) {
			self::setValidate();

			$tanggal = $this->input->post("tanggal");
			$nama_group = $this->input->post("nama_group");
			$nama_shift = $this->input->post('nama_shift');
			$nama_kabag = $this->input->post('nama_kabag');
			$id_kabag = $this->input->post('id_kabag');
			$otoritas_kabag = $this->input->post('otoritas_kabag');
			$nama_shift_kabag = $this->input->post('nama_shift_kabag');

			if ($nama_kabag != "") {
				$this->form_validation->set_rules('nama_shift_kabag', 'Shift Kerja Kabag', 'trim|required');
			}

			if ($this->form_validation->run() == TRUE) {
				$dataKaryawan = $this->jadwalKerjaModel->dataKaryawanGroup($nama_group);
				if ($dataKaryawan) {
					if ($nama_shift == "OFF") {
						$shift = true;
						$shift_id = NULL;
						$tokenKaryawan = 0;
					} elseif ($nama_shift == "SHIFT-PAGI-12-JAM" || $nama_shift == "SHIFT-MALAM-12-JAM") {
						$shift = $this->jadwalKerjaModel->shiftIdByName($nama_shift,date("l", strtotime($tanggal)));
						$shift_id = $shift->id_shift;
						$tokenKaryawan = 12;
					} else {
						$shift = $this->jadwalKerjaModel->shiftIdByName($nama_shift,date("l", strtotime($tanggal)));
						$shift_id = $shift->id_shift;
						$tokenKaryawan = 8;
					}
					if($shift) {
						// rule validate sebelum di insert
						// - pastikan semua data yang di input tidak boleh di insert lagi.
						// - pastikan tanggal yang di input tidak boleh kurang dan lebih dari bulan saat ini.
						// - tanggal yang sama dengan tanggal saat ini tidak boleh di update
						$checkDataJadwal = $this->jadwalKerjaModel->checkDataJadwal($tanggal,$nama_group);
						if (!$checkDataJadwal) {
							$checkBulanSaatIni = intval(date("m"));
							$bulanInput = intval(date("m", strtotime($tanggal)));
							if ($bulanInput < $checkBulanSaatIni) {
								$this->response->message = alertDanger("Opps Maaf, Tanggal yang anda input tidak boleh lebih kecil dari bulan saat ini.!");
							} else if ($bulanInput > $checkBulanSaatIni) {
								$this->response->message = alertDanger("Opps Maaf, Tanggal yang anda input tidak boleh lebih besar dari bulan saat ini.!");
							} else if($bulanInput == $checkBulanSaatIni) {
								$data = array();
								foreach ($dataKaryawan as $item) {
									$row = array();
									$row["tanggal"]			= 	$tanggal;
									$row["karyawan_id"]		= 	$item->id;
									$row["group_id"]		=	$nama_group;
									$row["otoritas_kerja"]	= 	$item->otoritas;
									$row["shift_id"]		=	$shift_id;
									$row["nama_shift"]		=	$nama_shift;
									$row["token"]			=	$tokenKaryawan;
									$data[]	= $row;
								}
								if ($nama_kabag != "" && $id_kabag != "" && $otoritas_kabag != "") { // tambah data kabag group
									$shiftKabag = $this->jadwalKerjaModel->shiftIdByName($nama_shift_kabag,date("l", strtotime($tanggal)));
									if ($nama_shift_kabag == "SHIFT-MALAM-12-JAM" || $nama_shift_kabag == "SHIFT-PAGI-12-JAM") {
										$tokenKabag = 12;
										$shift_id_kabag = $shiftKabag->id_shift;
									} elseif ($nama_shift_kabag == "OFF") {
										$tokenKabag = 0;
										$shift_id_kabag = NULL;
									}  else {
										$tokenKabag = 8;
										$shift_id_kabag = $shiftKabag->id_shift;
									}

									$rowKabag = array();
									$rowKabag["tanggal"]		= 	$tanggal;
									$rowKabag["karyawan_id"]	= 	$id_kabag;
									$rowKabag["group_id"]		=	$nama_group;
									$rowKabag["otoritas_kerja"]	=	$otoritas_kabag;
									$rowKabag["shift_id"]		=	$shift_id_kabag;
									$rowKabag["nama_shift"]		=	$nama_shift_kabag;
									$rowKabag["token"]			=	$tokenKabag;
									$data[] = $rowKabag;
								}

								if ($nama_shift == "OFF" && $nama_shift_kabag != "OFF") {
									$this->response->message = alertDanger("Opps Maaf, Shift group OFF maka shift kabag juga harus OFF bulan saat ini per group.");
								} else if ($nama_shift != "OFF" && $nama_shift_kabag == "OFF") {
									$this->response->message = alertDanger("Opps Maaf, Shift kabag group OFF maka shift group juga harus OFF bulan saat ini per group.");
								} else {
									/*var_dump($data);
									exit();*/

									$insertData = $this->jadwalKerjaModel->insertBatchTransaction($data);
									if ($insertData) {
										$this->response->status = true;
										$this->response->message = alertSuccess("Berhasil tambah data jadwal kerja shift bulan saat ini per group.");
									} else {
										$this->response->message = alertDanger("Opps Maaf, Gagal tambah data jadwal kerja shift bulan saat ini per group.");
									}
								}
							}
						} else {
							$this->response->message = alertDanger("Opps Maaf, Data jadwal bulan saat ini yang anda akan tambah sudah ada.!");
						}
					} else {
						$this->response->message = alertDanger("Opps Maaf, shift kerja yang anda pilih tidak ada di tanggal yang anda input, mungkin sudah di hapus.");
					}
				} else {
					$this->response->message = alertDanger("Opps Maaf, Data karyawan Group yang anda pilih tidak ada, mungkin sudah di hapus.");
				}

			} else {
				$this->response->message = validation_errors('<span style="color:red;">', '</span><br>');
			}
		}
		parent::json();
	}

	public function getData($tanggal,$group_id,$nama_shift=false) //output keluar data per row table jadwal_token_karyawan
	{
		parent::checkLoginUser(); // user login autentic checking

		if ($this->isPost()) {
			$data = $this->jadwalKerjaModel->getData($tanggal,$group_id,$nama_shift);
			if ($data) {

				$karyawan_master = array();
				$getAllMst_karyawan = $this->karyawanModel->getAll(array("id_grup" => $group_id),array("id"),array("id ASC"));
				foreach ($getAllMst_karyawan as $val) {
					$karyawan_master[] = $val->id;
				}
				$karyawan_jadwal = array();
				$getAllJad_karyawan = $this->jadwalKerjaModel->getAll(array("tanggal" => $tanggal, "group_id" => $group_id),array("karyawan_id"),array("karyawan_id ASC"));
				foreach ($getAllJad_karyawan as $value) {
					$karyawan_jadwal[] = $value->karyawan_id;
				}
				$dataKaryawanTambahan = "";
				$diff_karyawan = array_diff($karyawan_master, $karyawan_jadwal);
				if (!$diff_karyawan) {
					$diff_karyawan = "";
				} else {
					$whereIn = array("id" => $diff_karyawan);
					$select = array("id","kode_karyawan","idfp","nama","tgl_lahir","master_karyawan.id_grup","master_grup.grup");
					$orderBy = array("master_karyawan.nama ASC");
					$dataKaryawan = $this->karyawanModel->getAllKaryawanWhereInJadwal($whereIn,$select,$orderBy);
					if($dataKaryawan){
						$dataKaryawanTambahan = $dataKaryawan;
					}
				}

				$this->response->status = true;
				$this->response->message = "Data jadwal berdasarkan tanggal, id_group dan shift";
				$this->response->data = $data;
				$this->response->karyawan_id = $diff_karyawan;
				$this->response->data_karyawan = $dataKaryawanTambahan;
			} else {
				$this->response->message = alertDanger("Data tidak ada.!");
			}
		}
		parent::json();
	}

	public function addManualKaryawanJadwal()
	{
		parent::checkLoginUser(); // user login autentic checking

		if ($this->isPost()) {
			$this->form_validation->set_rules('tambahKaryawan', 'Karyawan Tambahan', 'trim|required');

			$tanggal = $this->input->post("tanggal"); //ada
			$tambahKaryawan = $this->input->post("tambahKaryawan"); //ada
			$nama_group = $this->input->post("nama_group"); //ada
			$shift_id 	= $this->input->post('shift_id'); //belum
			$nama_shift = $this->input->post('nama_shift'); //ada
			$tokenKaryawan = $this->input->post('token'); //belum
			if ($this->form_validation->run() == TRUE) {
				$dataKaryawan = $this->karyawanModel->getByWhere(array("id" => $tambahKaryawan));
				$otoritas = 1;
				if ($dataKaryawan) {
					$otoritas = $dataKaryawan->otoritas;
				}
				$data = array();
				$data["tanggal"]		= 	$tanggal;
				$data["karyawan_id"]	= 	$tambahKaryawan;
				$data["group_id"]		=	$nama_group;
				$data["otoritas_kerja"]	= 	$otoritas;
				$data["shift_id"]		=	$shift_id;
				$data["nama_shift"]		=	$nama_shift;
				$data["token"]			=	$tokenKaryawan;
				//Lanjut besok lagi ya.
				/*var_dump($data);
				exit();*/

				$insert = $this->jadwalKerjaModel->insert($data);
				// $insert = true;
				if ($insert) {
					$this->response->status = true;
					$this->response->message = alertSuccess("Berhasil tambah karyawan di jadwal yang anda pilih.");
					$this->response->data = $data;
				} else {
					$this->response->message = alertDanger("Gagal tambah karyawan di jadwal yang anda pilih.");
				}
			} else {
				$this->response->message = validation_errors('<span style="color:red;">', '</span><br>');
			}
		}
		parent::json();
	}

	public function updateBulanSaatIni($tgl,$idGroup)
	{
		parent::checkLoginUser(); // user login autentic checking

		if ($this->isPost()) {
			self::setValidate();

			$tanggal = $this->input->post("tanggal");
			$nama_group = $this->input->post("nama_group");
			$nama_shift = $this->input->post('nama_shift');
			$nama_kabag = $this->input->post('nama_kabag');
			$id_kabag = $this->input->post('id_kabag');
			$otoritas_kabag = $this->input->post('otoritas_kabag');
			$nama_shift_kabag = $this->input->post('nama_shift_kabag');

			if ($nama_kabag != "") {
				$this->form_validation->set_rules('nama_shift_kabag', 'Shift Kerja Kabag', 'trim|required');
			}
			if ($this->form_validation->run() == TRUE) {
				$dataKaryawan = $this->jadwalKerjaModel->dataKaryawanGroup($nama_group);
				if ($dataKaryawan) {
					if ($nama_shift == "OFF") {
						$shift = true;
						$shift_id = NULL;
						$tokenKaryawan = 0;
					} elseif ($nama_shift == "SHIFT-PAGI-12-JAM" || $nama_shift == "SHIFT-MALAM-12-JAM") {
						$shift = $this->jadwalKerjaModel->shiftIdByName($nama_shift,date("l", strtotime($tanggal)));
						$shift_id = $shift->id_shift;
						$tokenKaryawan = 12;
					} else {
						$shift = $this->jadwalKerjaModel->shiftIdByName($nama_shift,date("l", strtotime($tanggal)));
						$shift_id = $shift->id_shift;
						$tokenKaryawan = 8;
					}
					if($shift) {
						$checkBulanSaatIni = intval(date("m"));
						$bulanInput = intval(date("m", strtotime($tanggal)));
						if ($bulanInput < $checkBulanSaatIni) {
							$this->response->message = alertDanger("Opps Maaf, Tanggal yang anda input tidak boleh lebih kecil dari bulan saat ini.!");
						} else if ($bulanInput > $checkBulanSaatIni) {
							$this->response->message = alertDanger("Opps Maaf, Tanggal yang anda input tidak boleh lebih besar dari bulan saat ini.!");
						} else if($bulanInput == $checkBulanSaatIni) {
							if ($tanggal <= date("Y-m-d")) {
								$this->response->message = alertDanger("Opps Maaf, Tanggal yang anda input tidak boleh lebih kecil dari tanggal sekarang.");
							} else {
								$data = array();
								foreach ($dataKaryawan as $item) {
									$row = array();
									$row["tanggal"]			= 	$tanggal;
									$row["karyawan_id"]		= 	$item->id;
									$row["group_id"]		=	$nama_group;
									$row["otoritas_kerja"]	= 	$item->otoritas;
									$row["shift_id"]		=	$shift_id;
									$row["nama_shift"]		=	$nama_shift;
									$row["token"]			=	$tokenKaryawan;
									$data[]	= $row;
								}

								if ($nama_kabag != "" && $id_kabag != "" && $otoritas_kabag != "") { // tambah data kabag group
									$shiftKabag = $this->jadwalKerjaModel->shiftIdByName($nama_shift_kabag,date("l", strtotime($tanggal)));
									if ($nama_shift_kabag == "SHIFT-MALAM-12-JAM" || $nama_shift_kabag == "SHIFT-PAGI-12-JAM") {
										$tokenKabag = 12;
										$shift_id_kabag = $shiftKabag->id_shift;
									} elseif ($nama_shift_kabag == "OFF") {
										$tokenKabag = 0;
										$shift_id_kabag = NULL;
									} else {
										$tokenKabag = 8;
										$shift_id_kabag = $shiftKabag->id_shift;
									}

									$rowKabag = array();
									$rowKabag["tanggal"]		= 	$tanggal;
									$rowKabag["karyawan_id"]	= 	$id_kabag;
									$rowKabag["group_id"]		=	$nama_group;
									$rowKabag["otoritas_kerja"]	= 	$otoritas_kabag;
									$rowKabag["shift_id"]		=	$shift_id_kabag;
									$rowKabag["nama_shift"]		=	$nama_shift_kabag;
									$rowKabag["token"]			=	$tokenKabag;
									$data[] = $rowKabag;
								}

								// $dataSama = $this->jadwalKerjaModel->getDataSama($tanggal,$nama_group,$nama_shift);
								// if ($dataSama) { // check tanggal, group dan nama shift;
								// 	$this->response->status = true;
								// } else {
									if ($nama_shift == "OFF" && $nama_shift_kabag != "OFF") {
										$this->response->message = alertDanger("Opps Maaf, Shift group OFF maka shift kabag juga harus OFF bulan saat ini per group.");
									} else if ($nama_shift != "OFF" && $nama_shift_kabag == "OFF") {
										$this->response->message = alertDanger("Opps Maaf, Shift kabag group OFF maka shift group juga harus OFF bulan saat ini per group.");
									} else {
										$updateData = $this->jadwalKerjaModel->updateBatchTransaction($tgl,$idGroup,$data);
										if ($updateData) {
											$this->response->status = true;
											$this->response->message = alertSuccess("Berhasil update data jadwal kerja shift bulan saat ini per group.");
										} else {
											$this->response->message = alertDanger("Opps Maaf, Gagal update data jadwal kerja shift bulan saat ini per group.");
										}
									}
								
								// }
							}
						}
					} else {
						$this->response->message = alertDanger("Opps Maaf, shift kerja yang anda pilih tidak ada di tanggal yang anda input, mungkin sudah di hapus.");
					}
				} else {
					$this->response->message = alertDanger("Opps Maaf, Data karyawan Group yang anda pilih tidak ada, mungkin sudah di hapus.");
				}

			} else {
				$this->response->message = validation_errors('<span style="color:red;">', '</span><br>');
			}

		}
		parent::json();
	}

	/*for bulan depan*/
	public function insertBulanDepan()
	{
		parent::checkLoginUser(); // user login autentic checking

		if ($this->isPost()) {
			self::setValidate();

			$tanggal = $this->input->post("tanggal");
			$nama_group = $this->input->post("nama_group");
			$nama_shift = $this->input->post('nama_shift');
			$nama_kabag = $this->input->post('nama_kabag');
			$id_kabag = $this->input->post('id_kabag');
			$otoritas_kabag = $this->input->post('otoritas_kabag');
			$nama_shift_kabag = $this->input->post('nama_shift_kabag');

			if ($this->form_validation->run() == TRUE) {
				$dataKaryawan = $this->jadwalKerjaModel->dataKaryawanGroup($nama_group);
				if ($dataKaryawan) {
					if ($nama_shift == "OFF") {
						$shift = true;
						$shift_id = NULL;
						$tokenKaryawan = 0;
					} elseif ($nama_shift == "SHIFT-PAGI-12-JAM" || $nama_shift == "SHIFT-MALAM-12-JAM") {
						$shift = $this->jadwalKerjaModel->shiftIdByName($nama_shift,date("l", strtotime($tanggal)));
						$shift_id = $shift->id_shift;
						$tokenKaryawan = 12;
					}  else {
						$shift = $this->jadwalKerjaModel->shiftIdByName($nama_shift,date("l", strtotime($tanggal)));
						$shift_id = $shift->id_shift;
						$tokenKaryawan = 8;
					}
					if($shift) {
						// rule validate sebelum di insert
						// - pastikan semua data yang di input tidak boleh di insert lagi.
						// - pastikan tanggal yang di input tidak boleh kurang dan lebih dari bulan saat ini.
						// - tanggal yang sama dengan tanggal saat ini tidak boleh di update
						$checkBulanDepan = intval(date("m", strtotime("+1 months")));
						$bulanInput = intval(date("m", strtotime($tanggal)));

						if ($bulanInput < $checkBulanDepan) {
							$this->response->message = alertDanger("Opps Maaf, Tanggal yang anda input tidak boleh lebih kecil dari bulan depan.!");
						} else if ($bulanInput > $checkBulanDepan) {
							$this->response->message = alertDanger("Opps Maaf, Tanggal yang anda input tidak boleh lebih besar dari bulan depan.!");
						} else if($bulanInput == $checkBulanDepan) {
							$data = array();
							foreach ($dataKaryawan as $item) {
								$row = array();
								$row["tanggal"]			= 	$tanggal;
								$row["karyawan_id"]		= 	$item->id;
								$row["group_id"]		=	$nama_group;
								$row["otoritas_kerja"]	= 	$item->otoritas;
								$row["shift_id"]		=	$shift_id;
								$row["nama_shift"]		=	$nama_shift;
								$row["token"]			=	$tokenKaryawan;
								$data[]	= $row;
							}
							if ($nama_kabag != "") { // tambah data group kabag
								$shiftKabag = $this->jadwalKerjaModel->shiftIdByName($nama_shift_kabag,date("l", strtotime($tanggal)));
								if ($nama_shift_kabag == "SHIFT-MALAM-12-JAM" || $nama_shift_kabag == "SHIFT-PAGI-12-JAM") {
									$tokenKabag = 12;
									$shift_id_kabag = $shiftKabag->id_shift;
								} elseif ($nama_shift_kabag == "OFF") {
									$tokenKabag = 0;
									$shift_id_kabag = NULL;
								} else {
									$tokenKabag = 8;
									$shift_id_kabag = $shiftKabag->id_shift;
								}

								$rowKabag = array();
								$rowKabag["tanggal"]		= 	$tanggal;
								$rowKabag["karyawan_id"]	= 	$id_kabag;
								$rowKabag["group_id"]		=	$nama_group;
								$rowKabag["otoritas_kerja"]	= 	$otoritas_kabag;
								$rowKabag["shift_id"]		=	$shift_id_kabag;
								$rowKabag["nama_shift"]		=	$nama_shift_kabag;
								$rowKabag["token"]			=	$tokenKabag;
								$data[] = $rowKabag;
							}

							$checkDataJadwal = $this->jadwalKerjaModel->checkDataJadwal($tanggal,$nama_group);
							if (!$checkDataJadwal) {
								if ($nama_shift == "OFF" && $nama_shift_kabag != "OFF") {
									$this->response->message = alertDanger("Opps Maaf, Shift group OFF maka shift kabag juga harus OFF bulan saat ini per group.");
								} else if ($nama_shift != "OFF" && $nama_shift_kabag == "OFF") {
									$this->response->message = alertDanger("Opps Maaf, Shift kabag group OFF maka shift group juga harus OFF bulan saat ini per group.");
								} else {
									$insertData = $this->jadwalKerjaModel->insertBatchTransaction($data);
									if ($insertData) {
										$this->response->status = true;
										$this->response->message = alertSuccess("Berhasil tambah data jadwal kerja shift bulan depan per group.");
									} else {
										$this->response->message = alertDanger("Opps Maaf, Gagal tambah data jadwal kerja shift bulan depan per group.");
									}
								}
							} else {
								$this->response->message = alertDanger("Opps Maaf, Data jadwal bulan depan yang anda akan tambah sudah ada.!");
							}
						}
					} else {
						$this->response->message = alertDanger("Opps Maaf, shift kerja yang anda pilih tidak ada di tanggal yang anda input, mungkin sudah di hapus.");
					}
				} else {
					$this->response->message = alertDanger("Opps Maaf, Data karyawan Group yang anda pilih tidak ada, mungkin sudah di hapus.");
				}

			} else {
				$this->response->message = validation_errors('<span style="color:red;">', '</span><br>');
			}

		}
		parent::json();
	}

	public function updateBulanDepan($tgl,$idGroup)
	{
		parent::checkLoginUser(); // user login autentic checking

		if ($this->isPost()) {
			self::setValidate();

			$tanggal = $this->input->post("tanggal");
			$nama_group = $this->input->post("nama_group");
			$nama_shift = $this->input->post('nama_shift');
			$nama_kabag = $this->input->post('nama_kabag');
			$id_kabag = $this->input->post('id_kabag');
			$otoritas_kabag = $this->input->post('otoritas_kabag');
			$nama_shift_kabag = $this->input->post('nama_shift_kabag');

			if ($this->form_validation->run() == TRUE) {
				$dataKaryawan = $this->jadwalKerjaModel->dataKaryawanGroup($nama_group);
				if ($dataKaryawan) {
					if ($nama_shift == "OFF") {
						$shift = true;
						$shift_id = NULL;
						$tokenKaryawan = 0;
					} elseif ($nama_shift == "SHIFT-PAGI-12-JAM" || $nama_shift == "SHIFT-MALAM-12-JAM") {
						$shift = $this->jadwalKerjaModel->shiftIdByName($nama_shift,date("l", strtotime($tanggal)));
						$shift_id = $shift->id_shift;
						$tokenKaryawan = 12;
					} else {
						$shift = $this->jadwalKerjaModel->shiftIdByName($nama_shift,date("l", strtotime($tanggal)));
						$shift_id = $shift->id_shift;
						$tokenKaryawan = 8;
					}
					if($shift) {
						$checkBulanDepan = intval(date("m", strtotime("+1 months")));
						$bulanInput = intval(date("m", strtotime($tanggal)));
						if ($bulanInput < $checkBulanDepan) {
							$this->response->message = alertDanger("Opps Maaf, Tanggal yang anda input tidak boleh lebih kecil dari bulan depan.!");
						} else if ($bulanInput > $checkBulanDepan) {
							$this->response->message = alertDanger("Opps Maaf, Tanggal yang anda input tidak boleh lebih besar dari bulan depan.!");
						} else if($bulanInput == $checkBulanDepan) {
							$data = array();
							foreach ($dataKaryawan as $item) {
								$row = array();
								$row["tanggal"]			= 	$tanggal;
								$row["karyawan_id"]		= 	$item->id;
								$row["group_id"]		=	$nama_group;
								$row["otoritas_kerja"]	= 	$item->otoritas;
								$row["shift_id"]		=	$shift_id;
								$row["nama_shift"]		=	$nama_shift;
								$row["token"]			=	$tokenKaryawan;
								$data[]	= $row;
							}
							if ($nama_kabag != "") { // tambah data group kabag
								$shiftKabag = $this->jadwalKerjaModel->shiftIdByName($nama_shift_kabag,date("l", strtotime($tanggal)));
								if ($nama_shift_kabag == "SHIFT-MALAM-12-JAM" || $nama_shift_kabag == "SHIFT-PAGI-12-JAM") {
									$tokenKabag = 12;
									$shift_id_kabag = $shiftKabag->id_shift;
								} elseif ($nama_shift_kabag == "OFF") {
									$tokenKabag = 0;
									$shift_id_kabag = NULL;
								} else {
									$tokenKabag = 8;
									$shift_id_kabag = $shiftKabag->id_shift;
								}

								$rowKabag = array();
								$rowKabag["tanggal"]		= 	$tanggal;
								$rowKabag["karyawan_id"]	= 	$id_kabag;
								$rowKabag["group_id"]		=	$nama_group;
								$rowKabag["otoritas_kerja"]	= 	$otoritas_kabag;
								$rowKabag["shift_id"]		=	$shift_id_kabag;
								$rowKabag["nama_shift"]		=	$nama_shift_kabag;
								$rowKabag["token"]			=	$tokenKabag;
								$data[] = $rowKabag;
							}

							// $dataSama = $this->jadwalKerjaModel->getDataSama($tanggal,$nama_group,$nama_shift);
							// if ($dataSama) { // check tanggal, group dan nama shift;
							// 	$this->response->status = true;
							// } else {
								if ($nama_shift == "OFF" && $nama_shift_kabag != "OFF") {
									$this->response->message = alertDanger("Opps Maaf, Shift group OFF maka shift kabag juga harus OFF bulan saat ini per group.");
								} else if ($nama_shift != "OFF" && $nama_shift_kabag == "OFF") {
									$this->response->message = alertDanger("Opps Maaf, Shift kabag group OFF maka shift group juga harus OFF bulan saat ini per group.");
								} else {
									$updateData = $this->jadwalKerjaModel->updateBatchTransaction($tgl,$idGroup,$data);
									if ($updateData) {
										$this->response->status = true;
										$this->response->message = alertSuccess("Berhasil update data jadwal kerja shift bulan saat ini per group.");
									} else {
										$this->response->message = alertDanger("Opps Maaf, Gagal update data jadwal kerja shift bulan saat ini per group.");
									}	
								}
							// }
						}
					} else {
						$this->response->message = alertDanger("Opps Maaf, shift kerja yang anda pilih tidak ada di tanggal yang anda input, mungkin sudah di hapus.");
					}
				} else {
					$this->response->message = alertDanger("Opps Maaf, Data karyawan Group yang anda pilih tidak ada, mungkin sudah di hapus.");
				}

			} else {
				$this->response->message = validation_errors('<span style="color:red;">', '</span><br>');
			}

		}
		parent::json();
	}

	public function getIdJadwal($id)
	{
		parent::checkLoginUser(); // user login autentic checking

		if ($this->isPost()) {
			$data = $this->jadwalKerjaModel->getById($id);
			if ($data) {
				if ($data->foto != "") {
					$data->foto = base_url("/")."uploads/master/karyawan/orang/".$data->foto;
				} else {
					$data->foto = base_url("/")."assets/images/default/no_user.png";
				}
				$karyawanGanti = $this->jadwalKerjaModel->karyawanId($data->ganti_karyawan_id);
				if ($karyawanGanti) {
					$data->nama_karyawan_ganti 	= 	$karyawanGanti->nama;
					$dataGanti = $this->jadwalKerjaModel->checkIdjadwalInGanti($id);
					if ($dataGanti) {
						$data->keterangan_ganti	= $dataGanti->keterangan;
					}
				}
				$data->tgl_lahir_indo = date_ind("d M Y", $data->tgl_lahir);
				$this->response->status = true;
				$this->response->message = "Data id jadwal kerja shift";
				$this->response->data = $data;
			} else {
				$this->response->message = alertDanger("Data sudah tidak ada.");
			}
		}
		parent::json();
	}

	public function getIdGanti($id) // untuk show keterangan dari table jadwal_token_ganti
	{
		parent::checkLoginUser(); // user login autentic checking

		if ($this->isPost()) {
			$data = $this->jadwalKerjaModel->getByIdGanti($id);
			if ($data) {
				$this->response->status = true;
				$this->response->message = "Data id ganti karyawan";
				$this->response->data = $data;
			} else {
				$this->response->message = alertDanger("Data sudah tidak ada.");
			}
		}
		parent::json();
	}

	public function getIdKaryawan($id)
	{
		parent::checkLoginUser(); // user login autentic checking

		if ($this->isPost()) {
			$data = $this->jadwalKerjaModel->karyawanId($id);
			if ($data) {
				if ($data->foto != "") {
					$data->foto = base_url("/")."uploads/master/karyawan/orang/".$data->foto;
				} else {
					$data->foto = base_url("/")."assets/images/default/no_user.png";
				}
				$data->tgl_lahir_indo = date_ind("d M Y", $data->tgl_lahir);
				$this->response->status = true;
				$this->response->message = "Data per id karyawan jadwal kerja shift";
				$this->response->data = $data;
			} else {
				$this->response->message = alertDanger("Data Karyawan ganti tidak ada.");
			}
		}
		parent::json();
	}

	public function deleteKaryawan($id)
	{
		parent::checkLoginUser(); // user login autentic checking

		if ($this->isPost()) {
			$data = $this->jadwalKerjaModel->getById($id);
			if ($data) {
				$delete = $this->jadwalKerjaModel->delete($id);
				if ($delete) {
					$this->response->status = true;
					$this->response->message = alertSuccess("Berhasil Hapus karyawan");
				} else {
					$this->response->message = alertDanger("Gagal hapus Karyawan.");
				}
			} else {
				$this->response->message = alertDanger("Data Karyawan tidak ada.");
			}
		}
		parent::json();
	}

	public function allKaryawanAjax($otoritas=false)
	{
		parent::checkLoginUser(); // user login autentic checking

		if ($this->isPost()) {
			if (!isset($_POST["searchTerm"])) {
				$dataKaryawan = $this->jadwalKerjaModel->getAllKaryawanAjax($otoritas);
			} else {
				$searchTerm = $this->input->post("searchTerm");
				$dataKaryawan = $this->jadwalKerjaModel->getAllKaryawanAjaxSearch($searchTerm,$otoritas);
			}

			$data = array();
			$data[] = array("id"=>"null", "text"=>"-kosong-");
			foreach ($dataKaryawan as $val) {
				$item = array(
									"id"=>$val->id, 
									"text"=>$val->nama
								);
				$data[] = $item;
			}
			parent::json($data);
		}
	}

	public function gantiKaryawanSave($idJadwalToken)
	{
		parent::checkLoginUser(); // user login autentic checking
		if ($this->isPost()) {
			$this->form_validation->set_rules('karyawan_ganti', 'Karyawan Ganti', 'trim|required');
			$this->form_validation->set_rules('keterangan', 'Keterangan', 'trim|required');

			if ($this->form_validation->run() == TRUE) {
				$karyawan_ganti = $this->input->post('karyawan_ganti');
				$keterangan = $this->input->post('keterangan');

				$tokenJadwal = 0;
				$dataJadwal = $this->jadwalKerjaModel->getById($idJadwalToken);
				if ($dataJadwal) {
					if ($dataJadwal->nama_shift == "SHIFT-PAGI-12-JAM" || $dataJadwal->nama_shift == "SHIFT-MALAM-12-JAM") {
						$tokenJadwal = 12;
					} elseif ($dataJadwal->nama_shift == "OFF") {
						$tokenJadwal = 0;
					} else {
						$tokenJadwal = 8;
					}
				}

				$dataKaryawan = array(
										"ganti_karyawan_id"	=> $karyawan_ganti,
										// "token"				=> 0
									);
				$dataGantiKaryawan = array(
											"id_jadwal_token"	=> 	$idJadwalToken,
											"token_ganti"		=>	$tokenJadwal,
											"keterangan"		=>	$keterangan
										);
				$dataJadwal = $this->jadwalKerjaModel->getById($idJadwalToken);
				$checkDuplicKryGanti = $this->jadwalKerjaModel->checkDuplicateKaryawanGanti($dataJadwal->tanggal,$dataJadwal->group_id,$dataJadwal->shift_id,$karyawan_ganti);
				if($checkDuplicKryGanti) {
					$this->response->message = alertDanger("Opps Maaf, karyawan ganti yang anda pilih sudah ada di jadwal yang anda pilih.<br>Silahkan pilih yang lainnya.!");
				} else {
					$check_id_jadwal_token = $this->jadwalKerjaModel->checkIdjadwalInGanti($idJadwalToken);
					if ($check_id_jadwal_token) {
						$dataKaryawan["token"]	= 0;
						if ($karyawan_ganti == "null" || $karyawan_ganti == null || $karyawan_ganti == "") {
							$dataKaryawan["token"]	= $tokenJadwal;
							$dataKaryawan["ganti_karyawan_id"]	=	null;
						}
						$update = $this->jadwalKerjaModel->updateGantiKaryawan($idJadwalToken,$dataKaryawan,$dataGantiKaryawan);
						if ($update) {
							$this->response->status = true;
							$this->response->message = alertSuccess("Berhasil Ganti karyawan jadwal kerja");
						} else {
							$this->response->message = alertDanger("Opps, Gagal Ganti karyawan jadwal kerja");
						} 
					} else {
						$dataKaryawan["token"]	= 0;
						$insert = $this->jadwalKerjaModel->insertGantiKaryawan($idJadwalToken,$dataKaryawan,$dataGantiKaryawan);
						if ($insert) {
							$this->response->status = true;
							$this->response->message = alertSuccess("Berhasil Ganti karyawan jadwal kerja");
						} else {
							$this->response->message = alertDanger("Opps, Gagal Ganti karyawan jadwal kerja");
						}
					}
				}
			} else {
				$this->response->message = validation_errors('<span style="color:red;">', '</span><br>');
			}
		}
		parent::json();
	}

	public function checkGroupKabag($idGroup)
	{
		parent::checkLoginUser(); // user login autentic checking
		if ($this->isPost()) {
			$data = $this->jadwalKerjaModel->checkGroupKabag($idGroup);
			if ($data) {
				$this->response->status = true;
				$this->response->message = "Data kabag group";
				$this->response->data = $data;
			} else {
				$this->response->message = "Tidak ada kabag Group";
			}
		}
		parent::json();
	}

	public function checkGroupKabagJadwal($tgl,$idGroup)
	{
		parent::checkLoginUser(); // user login autentic checking
		if ($this->isPost()) {
			$data = $this->jadwalKerjaModel->checkGroupKabagJadwal($tgl,$idGroup);
			if ($data) {
				$this->response->status = true;
				$this->response->message = "Data kabag group jadwal";
				$this->response->data = $data;
			} else {
				$this->response->message = "Tidak ada kabag Group jadwal";
			}
		}
		parent::json();
	}

	/*For Security*/
	public function ajax_list_security($bulan="saat_ini",$tglBerlalu=false)
	{
		parent::checkLoginUser(); // user login autentic checking

		if ($this->isPost()) {
			$data = array();
			$select = "jadwal_token_karyawan.*, master_karyawan.nama, master_karyawan.idfp, master_karyawan.tgl_lahir, master_karyawan.kelamin, master_jabatan.jabatan, master_karyawan.kode_karyawan";
			$where = array(
							"YEAR(tanggal)" => 	date("Y"),
							"otoritas"		=>	4
						);
			if ($bulan == "saat_ini") {
				$where["MONTH(tanggal)"] = date("m");
				if ($tglBerlalu) {
					$where["tanggal <"]	= date("Y-m-d");
				} else {
					$where["tanggal >="] = date("Y-m-d");
				}
			} else if ($bulan == "lalu") {
				$where["MONTH(tanggal)"] = date("m", strtotime(" -1 months"));
			} else if ($bulan == "depan") {
				$where["MONTH(tanggal)"] = date("m", strtotime(" +1 months"));
			}

			$orderBy = array(null,null,"tanggal","nama","idfp","nama_shift","tgl_lahir","kelamin","jabatan");
			$search = array("tanggal","nama","idfp","nama_shift","tgl_lahir","kelamin","jabatan");
			$result = $this->jadwalKerjaModel->findDataTableSecurity($select,$where,$orderBy,$search);
			foreach ($result as $item) {
				// $item->tanggal = date_ind("d M Y",$item->tanggal);
				// untuk button action jika tanggal sudah tepat dengan tanggal sekarang maka hanya bisa di ganti security aja, tapi kalo tanggal input lebih besar dari tanggal sekarang maka muncul button edit.
				$btnOpsi = '';
				if ($bulan == "saat_ini") {
					if ($item->tanggal > date('Y-m-d')) {
						$btnOpsi = '<button class="btn btn-outline-warning  btn-mini" title="Edit Jadwal Kerja" onclick="btnEditSaatIniSecurity('.$item->id.')"><i class="fa fa fa-pencil-square-o"></i>Edit</button>';
					} elseif ($item->tanggal < date('Y-m-d')) {
						$btnOpsi = "<span class='text-danger'>Berlalu</span>";
					} elseif ($item->tanggal == date("Y-m-d")) {
						// Ingat ya button ganti hanya muncul jika security belum absensi masuk. #check ke table absensi
						// jika security sudah masuk tidak boleh ada tombol button ganti, dan menjadi tulisan sudah masuk.
						$btnOpsi = '<button class="btn btn-outline-info  btn-mini" title="Ganti security" onclick="btnGantiSecurity('.$item->id.')"><i class="fa fa fa-pencil-square-o"></i>Ganti</button>';

						$checkAbsensiHariIni = $this->jadwalKerjaModel->checkAbsensiHariIni($item->tanggal,$item->kode_karyawan);
						if ($checkAbsensiHariIni) {
							$btnOpsi = spanGreen("sudah masuk");
						}
					}
				} else if ($bulan == "depan") {
					$btnOpsi = '<button class="btn btn-outline-warning  btn-mini" title="Edit Jadwal Kerja" onclick="btnEditDepanSecurity('.$item->id.')"><i class="fa fa fa-pencil-square-o"></i>Edit</button>';

				} else if($bulan == "lalu") {
					$btnOpsi = "<span class='text-danger'>Berlalau</span>";
				}
				$item->nama_shift = $item->nama_shift == "OFF" ? "<span style='color:red;'>".$item->nama_shift."</span>" : $item->nama_shift;
				$item->button_opsi = $btnOpsi;

				$karyawanGanti = $this->jadwalKerjaModel->karyawanId($item->ganti_karyawan_id);
				if ($karyawanGanti) {
					$item->nama = "<strike style='color:red;'>".$item->nama."</strike> <br>".$karyawanGanti->nama;
					$item->idfp = "<strike style='color:red;'>".$item->idfp."</strike> <br>".$karyawanGanti->idfp;
					$item->tgl_lahir = "<strike style='color:red;'>".date_ind("d M Y", $item->tgl_lahir)."</strike> <br>".date_ind("d M Y", $karyawanGanti->tgl_lahir);
					if ($item->kelamin != $karyawanGanti->kelamin) {
						$item->kelamin = "<strike style='color:red;'>".ucfirst($item->kelamin)."</strike> <br>".ucfirst($karyawanGanti->kelamin);
					}
					if ($item->jabatan != $karyawanGanti->jabatan) {
						$item->jabatan = "<strike style='color:red;'>".$item->jabatan."</strike> <br>".$karyawanGanti->jabatan;
					}
				} else {
					$item->tgl_lahir = date_ind("d M Y", $item->tgl_lahir);
					$item->kelamin = ucfirst($item->kelamin);
				}

				$data[]	= $item;
			}
			return $this->jadwalKerjaModel->findDataTableOutputSecurity($data,$select,$where,$search);
		}
	}

	public function allKaryawanAjaxSecurity()
	{
		parent::checkLoginUser(); // user login autentic checking

		if ($this->isPost()) {
			if (!isset($_POST["searchTerm"])) {
				$dataKaryawan = $this->jadwalKerjaModel->getAllKaryawanAjax(4);
			} else {
				$searchTerm = $this->input->post("searchTerm");
				$dataKaryawan = $this->jadwalKerjaModel->getAllKaryawanAjaxSearch($searchTerm,4);
			}

			$data = array();
			$data[] = array("id"=>"null", "text"=>"-kosong-");
			foreach ($dataKaryawan as $val) {
				$item = array(
									"id"=>$val->id, 
									"text"=>$val->nama
								);
				$data[] = $item;
			}
			parent::json($data);
		}
	}

	public function addSecurity($bulanDepan=false)
	{
		parent::checkLoginUser(); // user login autentic checking

		if ($this->isPost()) {
			$this->form_validation->set_rules('tgl_security', 'Tanggal', 'trim|required');
			$this->form_validation->set_rules('karyawan_security', 'Karyawan security', 'trim|required');
			$this->form_validation->set_rules('shift_security', 'Shift', 'trim|required');

			if ($this->form_validation->run() == TRUE) {
				$tanggal = $this->input->post('tgl_security');
				$karyawan_id = $this->input->post('karyawan_security');
				$shift = $this->input->post('shift_security');

				$dataSecurity = $this->jadwalKerjaModel->dataSecurity($tanggal,$karyawan_id);
				if ($dataSecurity) { // check data security yang sudah ada.
					$this->response->message = alertDanger("Opps Maaf, Data tanggal dan security yang anda input sudah ada.<br>Silahkan pilih yang lain.!");
				} else {

					if ($bulanDepan) {
						$checkBulan = intval(date("m", strtotime("+1 months")));
						$namaBulan = "depan.!";
					} else {
						$checkBulan = intval(date("m"));
						$namaBulan = "saat ini.!";
					}

					$bulanInput = intval(date("m", strtotime($tanggal)));
					if ($bulanInput < $checkBulan) {
						$this->response->message = alertDanger("Opps Maaf, Tanggal yang anda input tidak boleh lebih kecil dari bulan ".$namaBulan);
					} else if ($bulanInput > $checkBulan) {
						$this->response->message = alertDanger("Opps Maaf, Tanggal yang anda input tidak boleh lebih besar dari bulan ".$namaBulan);
					} else if($bulanInput == $checkBulan) {

						$shiftData = $this->jadwalKerjaModel->shiftIdByName($shift,date("l", strtotime($tanggal)));
						if ($shift == "SHIFT-MALAM-12-JAM" || $shift == "SHIFT-PAGI-12-JAM") {
							$tokenSecurity = 12;
							$shift_id = $shiftData->id_shift;
						} elseif ($shift == "OFF") {
							$tokenSecurity = 0;
							$shift_id = NULL;
						} else {
							$tokenSecurity = 8;
							$shift_id = $shiftData->id_shift;
						}

						$dataKaryawan = $this->jadwalKerjaModel->karyawanId($karyawan_id);
						if ($dataKaryawan) {
							$nama_group = $dataKaryawan->id_grup;
						} else {
							$nama_group = null;
						}

						$data = array(
										"tanggal"		=> 	$tanggal,
										"karyawan_id"	=> 	$karyawan_id,
										"group_id"		=>	$nama_group,
										"shift_id"		=>	$shift_id,
										"nama_shift"	=>	$shift,
										"token"			=>	$tokenSecurity,
									);
						$insert = $this->jadwalKerjaModel->insert($data);
						if ($insert) {
							$this->response->status = true;
							$this->response->message = alertSuccess("Berhasil tambah jadwal security");
							$this->response->data = $insert;
						} else {
							$this->response->message = alertDanger("Gagal, tambah jadwal security");
						}
					}
				}
			} else {
				$this->response->message = validation_errors('<span style="color:red;">', '</span><br>');
			}
		}
		parent::json();
	}

	public function getIdSecurity($id)
	{
		parent::checkLoginUser(); // user login autentic checking

		if ($this->isPost()) {
			$data = $this->jadwalKerjaModel->getById($id);
			if ($data) {
				$this->response->status = true;
				$this->response->message = "Data karyawan security";
				$this->response->data = $data;
			} else {
				$this->response->message = alertDanger("Data Karyawan Security tidak ada.");
			}
		}
		parent::json();
	}	

	public function updateSecurity($id,$bulanDepan=false)
	{
		parent::checkLoginUser(); // user login autentic checking

		if ($this->isPost()) {
			$this->form_validation->set_rules('tgl_security', 'Tanggal', 'trim|required');
			$this->form_validation->set_rules('karyawan_security', 'Karyawan security', 'trim|required');
			$this->form_validation->set_rules('shift_security', 'Shift', 'trim|required');

			if ($this->form_validation->run() == TRUE) {
				$tanggal = $this->input->post('tgl_security');
				$karyawan_id = $this->input->post('karyawan_security');
				$shift = $this->input->post('shift_security');

				if ($bulanDepan) {
					$checkBulan = intval(date("m", strtotime("+1 months")));
					$namaBulan = "depan.!";
				} else {
					$checkBulan = intval(date("m"));
					$namaBulan = "saat ini.!";
				}

				$bulanInput = intval(date("m", strtotime($tanggal)));
				if ($bulanInput < $checkBulan) {
					$this->response->message = alertDanger("Opps Maaf, Tanggal yang anda input tidak boleh lebih kecil dari bulan ".$namaBulan);
				} else if ($bulanInput > $checkBulan) {
					$this->response->message = alertDanger("Opps Maaf, Tanggal yang anda input tidak boleh lebih besar dari bulan ".$namaBulan);
				} else if($bulanInput == $checkBulan) {

					$shiftData = $this->jadwalKerjaModel->shiftIdByName($shift,date("l", strtotime($tanggal)));
					if ($shift == "SHIFT-MALAM-12-JAM" || $shift == "SHIFT-PAGI-12-JAM") {
						$tokenSecurity = 12;
						$shift_id = $shiftData->id_shift;
					} elseif ($shift == "OFF") {
						$tokenSecurity = 0;
						$shift_id = NULL;
					} else {
						$tokenSecurity = 8;
						$shift_id = $shiftData->id_shift;
					}

					$dataKaryawan = $this->jadwalKerjaModel->karyawanId($karyawan_id);
					if ($dataKaryawan) {
						$nama_group = $dataKaryawan->id_grup;
					} else {
						$nama_group = null;
					}

					$data = array(
									"tanggal"		=> 	$tanggal,
									"karyawan_id"	=> 	$karyawan_id,
									"group_id"		=>	$nama_group,
									"shift_id"		=>	$shift_id,
									"nama_shift"	=>	$shift,
									"token"			=>	$tokenSecurity,
								);
					$update = $this->jadwalKerjaModel->update($id,$data);
					if ($update) {
						$this->response->status = true;
						$this->response->message = alertSuccess("Berhasil update jadwal security");
						$this->response->data = $update;
					} else {
						$this->response->message = alertDanger("Gagal, update jadwal security");
					}
				}
			} else {
				$this->response->message = validation_errors('<span style="color:red;">', '</span><br>');
			}
		}
		parent::json();
	}

	public function gantiSecuritySave($idJadwalToken)
	{
		parent::checkLoginUser(); // user login autentic checking
		if ($this->isPost()) {
			$this->form_validation->set_rules('karyawan_security_ganti', 'Security Ganti', 'trim|required');
			$this->form_validation->set_rules('keterangan_security', 'Keterangan Security', 'trim|required');

			if ($this->form_validation->run() == TRUE) {
				$karyawan_security_ganti = $this->input->post('karyawan_security_ganti');
				$keterangan_security = $this->input->post('keterangan_security');

				$tokenJadwal = 0;
				$dataJadwal = $this->jadwalKerjaModel->getById($idJadwalToken);
				if ($dataJadwal) {
					if ($dataJadwal->nama_shift == "SHIFT-PAGI-12-JAM" || $dataJadwal->nama_shift == "SHIFT-MALAM-12-JAM") {
						$tokenJadwal = 12;
					} elseif ($dataJadwal->nama_shift == "OFF") {
						$tokenJadwal = 0;
					} else {
						$tokenJadwal = 8;
					}
				}

				$dataKaryawan = array(
										"ganti_karyawan_id"	=> $karyawan_security_ganti,
										// "token"				=> 0
									);
				$dataGantiKaryawan = array(
											"id_jadwal_token"	=> 	$idJadwalToken,
											"token_ganti"		=>	$tokenJadwal,
											"keterangan"		=>	$keterangan_security
										);
				
				$check_id_jadwal_token = $this->jadwalKerjaModel->checkIdjadwalInGanti($idJadwalToken);
				if ($check_id_jadwal_token) {
					$dataKaryawan["token"]	= 0;
					if ($karyawan_security_ganti == "null" || $karyawan_security_ganti == null || $karyawan_security_ganti == "") {
						$dataKaryawan["token"]	= $tokenJadwal;
						$dataKaryawan["ganti_karyawan_id"]	=	null;
					}
					$update = $this->jadwalKerjaModel->updateGantiKaryawan($idJadwalToken,$dataKaryawan,$dataGantiKaryawan);
					if ($update) {
						$this->response->status = true;
						$this->response->message = alertSuccess("Berhasil Ganti Security jadwal kerja");
					} else {
						$this->response->message = alertDanger("Opps, Gagal Ganti Security jadwal kerja");
					} 
				} else {
					$dataKaryawan["token"]	= 0;
					$insert = $this->jadwalKerjaModel->insertGantiKaryawan($idJadwalToken,$dataKaryawan,$dataGantiKaryawan);
					if ($insert) {
						$this->response->status = true;
						$this->response->message = alertSuccess("Berhasil Ganti Security jadwal kerja");
					} else {
						$this->response->message = alertDanger("Opps, Gagal Ganti Security jadwal kerja");
					}
				}
			} else {
				$this->response->message = validation_errors('<span style="color:red;">', '</span><br>');
			}
		}
		parent::json();
	}

}

/* End of file Jadwalkerja.php */
/* Location: ./application/controllers/aktivitas/Jadwalkerja.php */