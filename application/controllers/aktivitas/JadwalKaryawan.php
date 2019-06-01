<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Jadwalkaryawan extends MY_Controller {

	public function __construct()
	{
		parent::__construct();
		$this->load->model('aktivitas/Jadwal_karyawan_model',"JadwalKaryawanModel");
		$this->load->model('master/Karyawan_model',"karyawanModel");
		parent::checkLoginOwner();

	}

	public function index()
	{
		parent::checkLoginUser(); // user login autentic checking

		parent::headerTitle("Aktivitas Data > Jadwal Karyawan","Aktivitas Data","Jadwal Karyawan");
		$breadcrumbs = array(
							"Aktivitas"	=>	site_url('aktivitas/jadwalkaryawan'),
							"Jadwal Karyawan"		=>	"",
						);
		parent::breadcrumbs($breadcrumbs);
		// $data = array();
		// $data['karyawan'] = $this->JadwalKaryawanModel->getKaryawan();
		// parent::viewData($data);

		parent::viewAktivitas();
	}

	public function ajax_list()
	{
		parent::checkLoginUser(); // user login autentic checking

		if ($this->isPost()) {
			$data = array();

			$orderBy = array(null,null,"nama_jadwal","masuk","keluar","break_in","break_out","break_punishment","username");
			$search = array("nama_jadwal","username");

			$result = $this->JadwalKaryawanModel->findDataTable($orderBy,$search);
			foreach ($result as $item) {
				$btnAction = '<button class="btn btn-warning  btn-mini" onclick="btnEdit('.$item->id_jadwal.')"><i class="fa fa-pencil-square-o"></i>Edit</button>';
				$btnAction .= '&nbsp;&nbsp;&nbsp;<button class="btn btn-danger btn-mini" onclick="btnDelete('.$item->id_jadwal.')"><i class="fa fa-trash-o"></i>Hapus</button>';
				$item->button_action = $btnAction;
				$data[] = $item;
			}
			return $this->JadwalKaryawanModel->findDataTableOutput($data,$search);
		}
	}

	public function add()
	{
		parent::checkLoginUser(); // user login autentic checking

		if ($this->isPost()) {
			$namaJadwal = $this->input->post("namaJadwal");
			$masuk = $this->input->post('masuk');
			$keluar = $this->input->post('keluar');
			$breakout = $this->input->post('breakout');
			$breakin = $this->input->post("breakin");
			$lewatHari = $this->input->post('lewatHari');
			$breakPunishment = $this->input->post('breakPunishment');
			$durasi = $this->input->post('durasi');
			$jenisBreak = $this->input->post('breaktipe');
			$tipeBreak = $this->input->post('tipebreak');
			$awalMasuk = $this->input->post('awal_masuk');
			$akhirMasuk = $this->input->post('akhir_masuk');
			$akhirKeluar = $this->input->post('akhir_keluar');
			$jam_kerja = $this->input->post('jam_kerja');
			$shift = $this->input->post('shift');

			if ($lewatHari != 1) {
				$lewatHari = 0;
			}

			$this->form_validation->set_rules('namaJadwal', 'Nama Jadwal', 'trim|required');
			$this->form_validation->set_rules('masuk', 'Masuk', 'trim|required');
			$this->form_validation->set_rules('keluar', 'Keluar', 'trim|required');
			$this->form_validation->set_rules('awal_masuk', 'Awal Masuk', 'trim|required');
			$this->form_validation->set_rules('akhir_masuk', 'Akhir Masuk', 'trim|required');
			$this->form_validation->set_rules('akhir_keluar', 'Akhir Keluar', 'trim|required');
			$this->form_validation->set_rules('jam_kerja', 'Jam Kerja', 'trim|required');

			if ($this->form_validation->run() == TRUE) {
				if ($lewatHari == 1) {
					if ($this->JadwalKaryawanModel->cekTime($keluar,$akhirKeluar) == 0) {

						$data = array(
										"nama_jadwal" => $namaJadwal,
										"masuk"	=>	$masuk,
										"keluar" => $keluar,
										"break" => $tipeBreak,
										"break_out" => $breakout,
										"break_in"=>	$breakin,
										"lewat_hari" => $lewatHari,
										"break_durasi" => $durasi,
										"break_punishment" => $breakPunishment,
										"break_option" => $jenisBreak,
										"awal_masuk" => $awalMasuk,
										"akhir_masuk" => $akhirMasuk,
										"akhir_keluar" => $akhirKeluar,
										"max_jam_kerja" => floatval($jam_kerja)*60,
										'shift' => $shift,
										"username" => $this->user->username
									);
						$insert = $this->JadwalKaryawanModel->insert($data);
						if ($insert) {
							$this->response->status = true;
							$this->response->message = alertSuccess("Berhasil buat Data Jadwal karyawan");
						} else {
							$this->response->message = alertDanger("Gagal, buat data Jadwal karyawan.");
						}
					}
					else {
						$this->response->message = alertDanger("Gagal, silahkan input jadwal dengan inputan waktu keluar dan akhir masuk yang berbeda.");
					}
				}
				else {
					if (strtotime($awalMasuk) < strtotime($masuk)) {
						if (strtotime($masuk) < strtotime($akhirMasuk)) {
							if (strtotime($akhirMasuk) < strtotime($keluar)) {
								if (strtotime($keluar) < strtotime($akhirKeluar)) {
									if ($this->JadwalKaryawanModel->cekTime($keluar,$akhirKeluar) == 0) {

										$data = array(
											"nama_jadwal" => $namaJadwal,
											"masuk"	=>	$masuk,
											"keluar" => $keluar,
											"break" => $tipeBreak,
											"break_out" => $breakout,
											"break_in"=>	$breakin,
											"lewat_hari" => $lewatHari,
											"break_durasi" => $durasi,
											"break_punishment" => $breakPunishment,
											"break_option" => $jenisBreak,
											"awal_masuk" => $awalMasuk,
											"akhir_masuk" => $akhirMasuk,
											"akhir_keluar" => $akhirKeluar,
											"max_jam_kerja" => floatval($jam_kerja)*60,
											'shift' => $shift,
											"username" => $this->user->username
										);
										$insert = $this->JadwalKaryawanModel->insert($data);
										if ($insert) {
											$this->response->status = true;
											$this->response->message = alertSuccess("Berhasil buat Data Jadwal karyawan");
										} else {
											$this->response->message = alertDanger("Gagal, buat data Jadwal karyawan.");
										}
									}
									else {
										// $this->response->message = alertDanger("Gagal, silahkan input jadwal dengan inputan waktu yang berbeda.");
										$this->response->message = alertDanger("Gagal, silahkan input jadwal dengan inputan waktu keluar dan akhir masuk yang berbeda.");
									}
								}
								else {
									$this->response->message = alertDanger("Gagal, silahkan input jadwal dengan inputan waktu akhir keluar lebih besar dari waktu keluar.");
								}
							}
							else {
								$this->response->message = alertDanger("Gagal, silahkan input jadwal dengan inputan waktu keluar lebih besar dari waktu akhir masuk.");
							}
						}
						else {
							$this->response->message = alertDanger("Gagal, silahkan input jadwal dengan inputan waktu akhir masuk lebih besar dari waktu masuk.");
						}
					}
					else {
						$this->response->message = alertDanger("Gagal, silahkan input jadwal dengan inputan waktu masuk lebih besar dari waktu awal masuk.");
					}
				}
			} else {
				// $this->response->message = validation_errors('<span style="color:red;">', '</span><br>');
				$this->response->error = array(
									"namaJadwal"	=> form_error("namaJadwal",'<span style="color:red;">','</span>'),
									"masuk"	=> form_error("masuk",'<span style="color:red;">','</span>'),
									"keluar"	=> form_error("keluar",'<span style="color:red;">','</span>'),
									"awal_masuk"	=> form_error("awal_masuk",'<span style="color:red;">','</span>'),
									"akhir_masuk"	=> form_error("akhir_masuk",'<span style="color:red;">','</span>'),
									"akhir_keluar"	=> form_error("akhir_keluar",'<span style="color:red;">','</span>'),
									"jam_kerja"	=> form_error("jam_kerja",'<span style="color:red;">','</span>'),
								);
			}
		}
		parent::json();
	}

	public function getId($id)
	{
		parent::checkLoginUser(); // user login autentic checking

		if ($this->isPost()) {
			$getById = $this->JadwalKaryawanModel->getById($id);
			if ($getById) {
				$getById->max_jam_kerja = $getById->max_jam_kerja/60;
				$this->response->status = true;
				$this->response->message = "Data jadwal karyawan get by id";
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

		if ($this->isPost()) {
			$namaJadwal = $this->input->post("namaJadwal");
			$masuk = $this->input->post('masuk');
			$keluar = $this->input->post('keluar');
			$breakout = $this->input->post('breakout');
			$breakin = $this->input->post("breakin");
			$lewatHari = $this->input->post('lewatHari');
			$breakPunishment = $this->input->post('breakPunishment');
			$durasi = $this->input->post('durasi');
			$jenisBreak = $this->input->post('breaktipe');
			$awalMasuk = $this->input->post('awal_masuk');
			$akhirMasuk = $this->input->post('akhir_masuk');
			$akhirKeluar = $this->input->post('akhir_keluar');
			$jam_kerja = $this->input->post('jam_kerja');
			$shift = $this->input->post('shift');
			$tipeBreak = $this->input->post('tipebreak');
			$id_jadwal = $this->input->post('idjadwal');
			if ($lewatHari != 1) {
				$lewatHari = 0;
			}

			$this->form_validation->set_rules('namaJadwal', 'Nama Jadwal', 'trim|required');
			$this->form_validation->set_rules('masuk', 'Masuk', 'trim|required');
			$this->form_validation->set_rules('keluar', 'Keluar', 'trim|required');
			$this->form_validation->set_rules('awal_masuk', 'Awal Masuk', 'trim|required');
			$this->form_validation->set_rules('akhir_masuk', 'Akhir Masuk', 'trim|required');
			$this->form_validation->set_rules('akhir_keluar', 'Akhir Keluar', 'trim|required');
			$this->form_validation->set_rules('jam_kerja', 'Jam Kerja', 'trim|required');

			if ($this->form_validation->run() == TRUE) {
				if ($lewatHari == 1) {
					if ($this->JadwalKaryawanModel->cekTimeShiftId($keluar,$akhirKeluar)->id_jadwal == $id_jadwal) {
						// if ($this->JadwalKaryawanModel->cekTime($keluar,$akhirKeluar) == 0) {

							$data = array(
													"nama_jadwal" => $namaJadwal,
													"masuk"	=>	$masuk,
													"keluar" => $keluar,
													"break" => $tipeBreak,
													"break_out" => $breakout,
													"break_in"=>	$breakin,
													"lewat_hari" => $lewatHari,
													"break_durasi" => $durasi,
													"break_punishment" => $breakPunishment,
													"break_option" => $jenisBreak,
													"awal_masuk" => $awalMasuk,
													"akhir_masuk" => $akhirMasuk,
													"akhir_keluar" => $akhirKeluar,
													"max_jam_kerja" => floatval($jam_kerja)*60,
													'shift' => $shift,
													"username" => $this->user->username
							);
							$update = $this->JadwalKaryawanModel->update($id,$data);
							if ($update) {
								$this->response->status = true;
								$this->response->message = alertSuccess("Berhasil edit Data Jadwal karyawan");
							} else {
								$this->response->message = alertDanger("Gagal, edit data Jadwal karyawan.");
							}
						// }
					}
					else {
						var_dump('1');
					}
				}
				else {
					if (strtotime($awalMasuk) < strtotime($masuk)) {
						if (strtotime($masuk) < strtotime($akhirMasuk)) {
							if (strtotime($akhirMasuk) < strtotime($keluar)) {
								if (strtotime($keluar) < strtotime($akhirKeluar)) {
									if ($this->JadwalKaryawanModel->cekTime($keluar,$akhirKeluar) == 0) {

										$data = array(
											"nama_jadwal" => $namaJadwal,
											"masuk"	=>	$masuk,
											"keluar" => $keluar,
											"break" => $tipeBreak,
											"break_out" => $breakout,
											"break_in"=>	$breakin,
											"lewat_hari" => $lewatHari,
											"break_durasi" => $durasi,
											"break_punishment" => $breakPunishment,
											"break_option" => $jenisBreak,
											"awal_masuk" => $awalMasuk,
											"akhir_masuk" => $akhirMasuk,
											"akhir_keluar" => $akhirKeluar,
											"max_jam_kerja" => floatval($jam_kerja)*60,
											'shift' => $shift,
											"username" => $this->user->username
										);
										$update = $this->JadwalKaryawanModel->update($id,$data);
										if ($update) {
											$this->response->status = true;
											$this->response->message = alertSuccess("Berhasil edit Data Jadwal karyawan");
										} else {
											$this->response->message = alertDanger("Gagal, edit data Jadwal karyawan.");
										}
									}
									else {
										$this->response->message = alertDanger("Gagal, silahkan input jadwal dengan inputan waktu yang berbeda.");
									}
								}
								else {
									$this->response->message = alertDanger("Gagal, silahkan input jadwal dengan inputan waktu akhir keluar lebih besar dari waktu keluar.");
								}
							}
							else {
								$this->response->message = alertDanger("Gagal, silahkan input jadwal dengan inputan waktu keluar lebih besar dari waktu akhir masuk.");
							}
						}
						else {
							$this->response->message = alertDanger("Gagal, silahkan input jadwal dengan inputan waktu akhir masuk lebih besar dari waktu masuk.");
						}
					}
					else {
						$this->response->message = alertDanger("Gagal, silahkan input jadwal dengan inputan waktu masuk lebih besar dari waktu awal masuk.");
					}
				}

			} else {
				// $this->response->message = validation_errors('<span style="color:red;">', '</span><br>');
				$this->response->error = array(
									"namaJadwal"	=> form_error("namaJadwal",'<span style="color:red;">','</span>'),
									"masuk"	=> form_error("masuk",'<span style="color:red;">','</span>'),
									"keluar"	=> form_error("keluar",'<span style="color:red;">','</span>'),
									"awal_masuk"	=> form_error("awal_masuk",'<span style="color:red;">','</span>'),
									"akhir_masuk"	=> form_error("akhir_masuk",'<span style="color:red;">','</span>'),
									"akhir_keluar"	=> form_error("akhir_keluar",'<span style="color:red;">','</span>'),
									"jam_kerja"	=> form_error("jam_kerja",'<span style="color:red;">','</span>'),
								);
			}
		}
		parent::json();
	}

	public function delete($id)
	{
		parent::checkLoginUser(); // user login autentic checking

		if ($this->isPost()) {
			$getById = $this->JadwalKaryawanModel->getById($id);
			if ($getById) {
				$delete = $this->JadwalKaryawanModel->delete($id);
				if ($delete) {
					$this->response->status = true;
					$this->response->message = alertSuccess("Berhasil proses hapus Data Dinas");
				} else {
					$this->response->message = alertDanger("Gagal, hapus data..");
				}
			} else {
				$this->response->message = alertDanger("Data sudah tidak ada.");
			}
		}
		parent::json();
	}



}

/* End of file JadwalKaryawan.php */
/* Location: ./application/controllers/aktivitas/JadwalKaryawan.php */
