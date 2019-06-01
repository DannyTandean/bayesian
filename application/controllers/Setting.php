<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Setting extends MY_Controller {

	public function __construct()
	{
		parent::__construct();
		$this->load->model('Setting_model',"settingModel");
		$this->load->model('THR_model',"THRModel");

	}

	public function index()
	{
		parent::checkLoginUser(); // user login autentic checking

		parent::headerTitle("Setting","Setting");
		$breadcrumbs = array(
							"Setting"	=>	site_url('Setting'),
						);
		parent::breadcrumbs($breadcrumbs);

    $data = array();

    $provinsi = $this->settingModel->getProvinsi();
    $data["provinsi"] = $provinsi;
    parent::viewData($data);
		parent::view();
	}

  public function getRegencies($id)
  {
    if ($this->isPost()) {
      parent::checkLoginUser();
      $regencies = $this->settingModel->getRegencies($id);
      if($regencies)
      {
        $this->response->status = true;
        $this->response->message = "get Pronvisi Data";
        $this->response->data = $regencies;
      }
      else {
        $this->response->message = alertDanger("failed get provinsi data");
      }
    }
    parent::json();
  }

	public function ajax_list()
	{
		// parent::checkLoginUser(); // user login autentic checking

		if ($this->isPost()) {
			$data = array();

			$orderBy = array(null,null,"logo","nama_perusahaan","alamat","provinsi","kota_kabupaten","timezone","kode_pos","no_telp","no_fax","email_perusahaan","website","jatah_cuti","budget_penggajian");
			$search = array("nama_perusahaan");

			$result = $this->settingModel->findDataTable($orderBy,$search);
			foreach ($result as $item) {

				$srcPhoto = base_url().'assets/images/default/no_file_.png';
				if ($item->logo != "") {
					$srcPhoto = base_url()."uploads/setting/".$item->logo;
				}
				if ($getById->logo != "") {
	        $getById->logo = base_url("/")."uploads/setting/".$getById->logo;
	      }
				$dataPhoto = '<a href="'.$srcPhoto.'" data-toggle="lightbox" data-title="Logo" data-footer="">
                    <img src="'.$srcPhoto.'" class="img-circle" style="height:60px; width:60px;" alt="photo "'.$item->nama_perusahaan.'>
                </a>';

				$btnAction = '<button class="btn btn-warning btn-outline-warning btn-mini" onclick="btnEdit('.$item->id_setting.')"><i class="fa fa-pencil-square-o"></i>Edit</button>';
				// $btnAction .= '&nbsp;&nbsp;&nbsp;<button class="btn btn-danger btn-outline-danger btn-mini" onclick="btnDelete('.$item->id_setting.')"><i class="fa fa-trash-o"></i>Hapus</button>';

        $regencies = $this->settingModel->getRegenciesId($item->kota_kabupaten,true);
        $provinsi = $this->settingModel->getProvinsiId($item->provinsi);
        $item->budget_penggajian = "Rp.".number_format($item->budget_penggajian,0,",",",");
        $item->provinsi = $provinsi->name;
        $item->kota_kabupaten = $regencies->name;
				$item->foto = $dataPhoto;
				$item->edit_at = date_ind("d M Y",$item->edit_at);
				$item->button_action = $btnAction;
				$data[] = $item;
			}
			return $this->settingModel->findDataTableOutput($data,$search);
		}
	}

	public function ajax_list_thr()
	{
		parent::checkLoginUser(); // user login autentic checking

		if ($this->isPost()) {
			$data = array();

			$orderBy = array(null,null,"tanggal","agama");
			$search = array("agama");

			$result = $this->THRModel->findDataTable($orderBy,$search);
			foreach ($result as $item) {
				$btnAction = '<button class="btn btn-warning btn-mini" onclick="btnEdit1('.$item->id_thr.')"><i class="fa fa-pencil-square-o"></i>Edit</button>';
				$btnAction .= '&nbsp;&nbsp;&nbsp;<button class="btn btn-danger btn-mini" onclick="btnDelete('.$item->id_thr.')"><i class="fa fa-trash-o"></i>Hapus</button>';
				$item->tanggal = date_ind("d M Y",$item->tanggal);
				$item->button_action = $btnAction;
				$data[] = $item;
			}
			return $this->THRModel->findDataTableOutput($data,$search);
		}
	}

	public function getId($id)
	{
		parent::checkLoginUser(); // user login autentic checking

    $getById = $this->settingModel->getById($id);
    if ($getById) {
      if ($getById->logo != "") {
        $getById->logo = base_url("/")."uploads/setting/".$getById->logo;
      } else {
        $getById->logo = base_url("/")."assets/images/default/no_file_.png";
      }
			if ($getById->qrcode != "") {
        $getById->qrcode = base_url("/")."uploads/setting/".$getById->qrcode;
      } else {
        $getById->qrcode = base_url("/")."assets/images/default/no_file_.png";
      }
      $regencies = $this->settingModel->getRegenciesId($getById->kota_kabupaten,true);
		if ($this->isPost()) {
        $getById->kota_kabupaten1 = $regencies->name;
				$this->response->status = true;
				$this->response->message = "Data tanggal get by id";
				$this->response->data = $getById;
			} else {
				$this->response->message = alertDanger("Data tidak ada.");
			}
		}
		parent::json();
	}

	public function getId1($id)
	{
		parent::checkLoginUser(); // user login autentic checking

		if ($this->isPost()) {
			$getById1 = $this->THRModel->getById1($id);
			if ($getById1) {
				$this->response->status = true;
				$this->response->message = "Data THR get by id";
				$this->response->data = $getById1;
			} else {
				$this->response->message = alertDanger("Data tidak ada.");
			}
		}
		parent::json();
	}

	public function getId2($id)
	{
		parent::checkLoginUser(); // user login autentic checking

		if ($this->isPost()) {
			$getById2 = $this->settingModel->getById2($id);
			if ($getById2) {
				$this->response->status = true;
				$this->response->message = "Data THR get by id";
				$this->response->data = $getById2;
			} else {
				$this->response->message = alertDanger("Data tidak ada.");
			}
		}
		parent::json();
	}

	public function _do_upload_setting()
	{
				$config['upload_path']      = 	'uploads/setting/';
				$config['allowed_types']    = 	'gif|jpg|jpeg|png';
				$config['max_size']         = 	1024; // 1mb
				$config['encrypt_name']		=	true;

				$this->load->library('upload', $config);
				$this->upload->initialize($config);
	}

	public function addExtra()
	{
		parent::checkLoginUser(); // user login autentic checking

		if ($this->isPost()) {
			$tanggal = $this->input->post("tanggal11");
			$agama = $this->input->post("agama11");

			$this->form_validation->set_rules('tanggal11', 'Tanggal', 'trim|required');
			$this->form_validation->set_rules('agama11', 'Agama', 'trim|required');

			if ($this->form_validation->run() == TRUE) {
				$data = array(
								"tanggal"	=>	$tanggal,
								"agama"	=>	$agama
							);
				$insert = $this->THRModel->insert($data);
				if ($insert) {
					$this->response->status = true;
					$this->response->message = alertSuccess("Berhasil tambah data THR.");
				} else {
					$this->response->message = alertDanger("Gagal tambah data THR.");
				}

			} else {
				// $this->response->message = validation_errors('<span style="color:red;">', '</span><br>');
				$this->response->error = array(
									"tanggal"	=> form_error("tanggal",'<span style="color:red;">','</span>'),
									"agama"	=> form_error("agama",'<span style="color:red;">','</span>'),
								);
			}
		}
		parent::json();
	}

	public function update($id)
	{
		parent::checkLoginUser(); // user login autentic checking

		if ($this->isPost()) {
			$namaPerusahaan = $this->input->post("namaPerusahaan");
			$alamat = $this->input->post('alamat');
			$provinsi = $this->input->post('provinsi');
			$kabupaten = $this->input->post('kabupaten');
			$kodePos = $this->input->post('kodePos');
			$noTelp =$this->input->post('noTelp');
			$noFax = $this->input->post('noFax');
			$emailPerusahaan = $this->input->post('emailPerusahaan');
			$website = $this->input->post('website');
			$bidangUsaha = $this->input->post('bidangUsaha');
			$jatahCuti = $this->input->post('jatahCuti');
			$budgetPenggajian = $this->input->post('budgetPenggajian');
			$punishment1 = $this->input->post('punishment1');
			$punishment2 = $this->input->post('punishment2');
			$punishment3 = $this->input->post('punishment3');
			$reward1 = $this->input->post('reward1');
			$reward2 = $this->input->post('reward2');
			$reward3 = $this->input->post('reward3');
			$totalkerja = $this->input->post('totalkerja');
			$opsi_hari = $this->input->post('tipeHariPerBulan');

			$opsi_cuti = $this->input->post('opsi_cuti');
			$opsi_dinas = $this->input->post('opsi_dinas');
			$opsi_dirumahkan = $this->input->post('opsi_dirumahkan');
			$opsi_sakit = $this->input->post('opsi_sakit');
			$opsi_izin = $this->input->post('opsi_izin');

			$golongan_cuti = $this->input->post('opsi_golongan_cuti');
			$golongan_dinas = $this->input->post('opsi_golongan_dinas');
			$golongan_dirumahkan = $this->input->post('opsi_golongan_dirumahkan');
			$golongan_sakit = $this->input->post('opsi_golongan_sakit');
			$golongan_izin = $this->input->post('opsi_golongan_izin');

			$cuti = $this->input->post('cuti');
			$sakit = $this->input->post('sakit');
			$izin = $this->input->post('izin');
			$dinas = $this->input->post('dinas');
			$dirumahkan = $this->input->post('dirumahkan');

			$tanggal1 = $this->input->post('tanggal_penggajian1');
			$tanggal2 = $this->input->post('tanggal_penggajian2');
			$tanggal3 = $this->input->post('tanggal_penggajian3');
			$tanggal4 = $this->input->post('tanggal_penggajian4');

			$menitTelat1 = $this->input->post('menitTelat1');
			$menitTelat2 = $this->input->post('menitTelat2');
			$menitTelat3 = $this->input->post('menitTelat3');

			$jumlahPotongan1 = $this->input->post('jumlahPotongan1');
			$jumlahPotongan2 = $this->input->post('jumlahPotongan2');
			$jumlahPotongan3 = $this->input->post('jumlahPotongan3');

			$opsiLibur = $this->input->post('opsi_libur');
			$golongan_libur = $this->input->post('opsi_golongan_libur');
			$hariLibur = $this->input->post('hari_libur');

			$opsiMinggu = $this->input->post('opsi_minggu');
			$golongan_minggu = $this->input->post('opsi_golongan_minggu');
			$hariMinggu = $this->input->post('hari_minggu');

			$opsiKerajinan = $this->input->post('opsi_kerajinan');
			$kerajinan =  $this->input->post('nilaiKerajinan');
			$periodePenilaian = $this->input->post('periode_penilaian');

			$lembur1 = $this->input->post('lembur1');
			$lembur2 = $this->input->post('lembur2');
			$lembur3 = $this->input->post('lembur3');

			$lembur_break1 = $this->input->post('lembur_break1');
			$lembur_break2 = $this->input->post('lembur_break2');
			$lembur_break3 = $this->input->post('lembur_break3');

			$opsiLembur = $this->input->post('opsi_lembur');
			$approvalLembur = $this->input->post('approval_lembur');

			$this->form_validation->set_rules('namaPerusahaan', 'Nama Perusahaan', 'trim|required');
			$this->form_validation->set_rules('emailPerusahaan', 'Email Perusahaan', 'trim|required');
			if ($menitTelat2 != 0 && $menitTelat2 != "") {
				$this->form_validation->set_rules('menitTelat2', 'Waktu telat ', "trim|greater_than[$menitTelat1]");
			}
			if ($menitTelat3 != 0 && $menitTelat3 != "") {
				$this->form_validation->set_rules('menitTelat3', 'Waktu telat ', "trim|greater_than[$menitTelat2]");
			}

			if ($this->form_validation->run() == TRUE) {
				$data = array(
								"nama_perusahaan" => $namaPerusahaan,
								"bidang_usaha" => $bidangUsaha,
								"alamat" => $alamat,
								"provinsi" => $provinsi,
								"kota_kabupaten" => $kabupaten,
								"kode_pos" => $kodePos,
								"no_telp" => $noTelp,
								"no_fax" => $noFax,
								"email_perusahaan" => $emailPerusahaan,
								"website" => $website,
								"jatah_cuti" => $jatahCuti,
								"budget_penggajian " => $budgetPenggajian,
								"reward_top1" => $reward1,
								"reward_top2" => $reward2,
								"reward_top3" => $reward3,
								"punishment_top1" => $punishment1,
								"punishment_top2" => $punishment2,
								"punishment_top3" => $punishment3,
								"total_hari" => $totalkerja,
								"otomatis_hari" => $opsi_hari,
								"opsi_dinas" => $opsi_dinas,
								"golongan_dinas" => $golongan_dinas,
								"dinas" => $dinas,
								"opsi_dirumahkan" => $opsi_dirumahkan,
								"golongan_dirumahkan" => $golongan_dirumahkan,
								"dirumahkan" => $dirumahkan,
								"opsi_sakit" => $opsi_sakit,
								"golongan_sakit" => $golongan_sakit,
								"sakit" => $sakit,
								"opsi_izin" => $opsi_izin,
								"golongan_izin" => $golongan_izin,
								"izin" => $izin,
								"opsi_cuti" => $opsi_cuti,
								"golongan_cuti" => $golongan_cuti,
								"cuti" => $cuti,
								"tanggal_payment1" => $tanggal1,
								"tanggal_payment2" => $tanggal2,
								"tanggal_payment3" => $tanggal3,
								"tanggal_payment4" => $tanggal4,
								'telat1' => $menitTelat1,
								'telat2' => $menitTelat2,
								'telat3' => $menitTelat3,
								'potongan_telat1' => $jumlahPotongan1,
								'potongan_telat2' => $jumlahPotongan2,
								'potongan_telat3' => $jumlahPotongan3,
								'opsi_libur' => $opsiLibur,
								'golongan_libur' => $golongan_libur,
								'hari_libur' => $hariLibur,
								'opsi_minggu' => $opsiMinggu,
								'golongan_minggu' => $golongan_minggu,
								'hari_minggu' => $hariMinggu,
								'opsi_kerajinan' => $opsiKerajinan,
								'kerajinan' => $kerajinan,
								'periode_penilaian' => $periodePenilaian,
								'lembur1' => $lembur1,
								'lembur2' => $lembur2,
								'lembur3' => $lembur3,
								'break1' =>	$lembur_break1,
								'break2' => $lembur_break2,
								'break3' => $lembur_break3,
								'extra_break' => $opsiLembur,
								'extra_approval' => $approvalLembur

							);
							/*for logo setting*/
							$getById = $this->settingModel->getById($id);
							if (!empty($_FILES["photo_setting"]["name"])) {
								// config upload
								self::_do_upload_setting();

								if (!$this->upload->do_upload("photo_setting")) {
									$this->response->message = "error_foto";
									$error_photo_setting = $this->upload->display_errors('<small style="color:red;">', '</small>');
									$this->response->error_photo_setting = $error_photo_setting;
								} else {
									$photo_setting = $this->upload->data();
									$data["logo"]	= $photo_setting["file_name"];
									if (file_exists("uploads/setting/".$getById->logo) && $getById->logo) {
										unlink("uploads/setting/".$getById->logo);
									}
								}

							} else {
								if ($this->input->post("is_delete_photo") == 1) {
									$data["logo"]	= "";
									if (file_exists("uploads/setting/".$getById->logo) && $getById->logo) {
										unlink("uploads/setting/".$getById->logo);
									}
								}
							}

							// for qrcode photo
							if (!empty($_FILES["photo_qrcode"]["name"])) {
								// config upload
								self::_do_upload_setting();

								if (!$this->upload->do_upload("photo_qrcode")) {
									$this->response->message = "error_foto";
									$error_photo_qrcode = $this->upload->display_errors('<small style="color:red;">', '</small>');
									$this->response->error_photo_qrcode = $error_photo_qrcode;
								} else {
									$photo_qrcode = $this->upload->data();
									$data["qrcode"]	= $photo_qrcode["file_name"];
									if (file_exists("uploads/setting/".$getById->qrcode) && $getById->qrcode) {
										unlink("uploads/setting/".$getById->qrcode);
									}
								}

							} else {
								if ($this->input->post("is_delete_photo1") == 1) {
									$data["qrcode"]	= "";
									if (file_exists("uploads/setting/".$getById->qrcode) && $getById->qrcode) {
										unlink("uploads/setting/".$getById->qrcode);
									}
								}
							}

				$update = $this->settingModel->update($id,$data);
				if ($update) {
					$this->response->status = true;
					$this->response->message = alertSuccess("Berhasil update data Setting.");
				} else {
					$this->response->message = alertDanger("Gagal update data Setting.");
				}
			} else {
				// $this->response->messages = validation_errors('<span style="color:red;">', '</span><br>');
				$this->response->error = array(
									"namaPerusahaan"	=> form_error("namaPerusahaan",'<span style="color:red;">','</span>'),
									"emailPerusahaan"	=> form_error("emailPerusahaan",'<span style="color:red;">','</span>'),
									"menitTelat2"	=> form_error("menitTelat2",'<span style="color:red;">','</span>'),
									"menitTelat3"	=> form_error("menitTelat3",'<span style="color:red;">','</span>'),
								);
			}
		}
		parent::json();
	}

	public function updateExtra($id)
	{
		parent::checkLoginUser();

		if ($this->isPost()) {
			$tanggal = $this->input->post("tanggal11");
			$agama = $this->input->post("agama11");

			$this->form_validation->set_rules('tanggal11', 'Tanggal', 'trim|required');
			$this->form_validation->set_rules('agama11', 'Agama', 'trim|required');

			if($this->form_validation->run() == TRUE){
				$data = array(
						"tanggal" =>  $tanggal,
						"agama"	=> $agama
				);
				$update = $this->THRModel->update($id,$data);
				if ($update) {
					$this->response->status = true;
					$this ->response->message = alertSuccess("Berhasil update data THR.");
				} else {
					$this->response->message = alertDanger("Gagal update data THR.");
				}
			} else{
				$this->response->error = array(
									"tanggal"	=> form_error("tanggal",'<span style="color:red;">','</span>'),
									"agama"	=> form_error("agama",'<span style="color:red;">','</span>'),
				);
			}
		}
		parent::json();
	}

	public function delete($id)
	{
		parent::checkLoginUser(); // user login autentic checking

		if ($this->isPost()) {
			$getById1 = $this->THRModel->getById1($id);
			if ($getById1) {
				$delete = $this->THRModel->delete($id);
				if ($delete) {
					$this->response->status = true;
					$this->response->message = alertSuccess("Data THR Berhasil di hapus.");
				} else {
					$this->response->message = alertDanger("Data sudah tidak ada.");
				}
			} else {
				$this->response->message = alertDanger("Data sudah tidak ada.");
			}
		}
		parent::json();
	}

	public function getBpjs()
	{
		parent::checkLoginUser(); // user login autentic checking

		if ($this->isPost()) {
			$bpjs = $this->settingModel->getBpjs();
			if ($bpjs) {
				$this->response->status = true;
				$this->response->messages = alertSuccess("berhasil mengambil data setting bpjs.");
				$this->response->data = $bpjs;
			}
			else {
				$this->response->message = alertDanger("gagal mengambil data setting bpjs.");
			}
		}
		parent::json();
	}

	public function updateBpjs()
	{
		parent::checkLoginUser(); // user login autentic checking

		if ($this->isPost()) {
			$jht_perusahaan = $this->input->post("jht_perusahaan");
			$jht_karyawan = $this->input->post('jht_karyawan');
			$jkk_sangat_rendah = $this->input->post('jkk_sangat_rendah');
			$jkk_rendah = $this->input->post('jkk_rendah');
			$jkk_sedang = $this->input->post('jkk_sedang');
			$jkk_tinggi =$this->input->post('jkk_tinggi');
			$jkk_sangat_tinggi = $this->input->post('jkk_sangat_tinggi');
			$jkm = $this->input->post('jkm');
			$jp_perusahaan = $this->input->post('jp_perusahaan');
			$jp_karyawan = $this->input->post('jp_karyawan');
			$jp_perusahaan_max = $this->input->post('jp_perusahaan_max');
			$jp_karyawan_max = $this->input->post('jp_karyawan_max');
			$bpjs_perusahaan = $this->input->post('bpjs_perusahaan');
			$bpjs_karyawan = $this->input->post('bpjs_karyawan');
			$bpjs_perusahaan_max = $this->input->post('bpjs_perusahaan_max');
			$bpjs_karyawan_max = $this->input->post('bpjs_karyawan_max');


			$data = array(
							"jht_perusahaan" => $jht_perusahaan,
							"jht_karyawan" => $jht_karyawan,
							"jkk1" => $jkk_sangat_rendah,
							"jkk2" => $jkk_rendah,
							"jkk3" => $jkk_sedang,
							"jkk4" => $jkk_tinggi,
							"jkk5" => $jkk_sangat_tinggi,
							"jkm" => $jkm,
							"jp_perusahaan" => $jp_perusahaan,
							"jp_karyawan" => $jp_karyawan,
							"jp_perusahaan_max" => $jp_perusahaan_max,
							"jp_karyawan_max" => $jp_karyawan_max,
							"bpjs_kesehatan_perusahaan" => $bpjs_perusahaan,
							"bpjs_kesehatan_karyawan" => $bpjs_karyawan,
							"bpjs_kesehatan_perusahaan_max" => $bpjs_perusahaan_max,
							"bpjs_kesehatan_karyawan_max" => $bpjs_karyawan_max
						);

				$updateBpjs = $this->settingModel->updateBpjs(1,$data);
				if ($updateBpjs) {
					$this->response->status = true;
					$this->response->message = alertSuccess("Berhasil update data Setting BPJS.");
				} else {
					$this->response->message = alertDanger("Gagal update data Setting BPJS.");
				}
		}
		parent::json();
	}

}

/* End of file Setting.php */
/* Location: ./application/controllers/setting.php */
