<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Thr extends MY_Controller {

	public function __construct()
	{
		parent::__construct();
		$this->load->model('aktivitas/Thr_model','thrModel');
		// $this->load->model('THR_model',"THRModel");
	}

	public function index()
	{
		parent::checkLoginUser(); // user login autentic checking

		parent::headerTitle("Aktivitas Data > THR Karyawan","Aktivitas Data","THR Karyawan");
		$breadcrumbs = array(
							"Aktivitas Data"	=>	site_url('aktivitas/thr'),
							"THR"		=>	"",
						);
		parent::breadcrumbs($breadcrumbs);
		parent::viewAktivitas();
	}

	public function ajax_list($before=null,$after=null)
	{
		parent::checkLoginUser(); // user login autentic checking

		if ($this->isPost()) {
			$data = array();

			$orderBy = array(null,null,"tanggal","nama","jabatan","departemen","nilai");
			$search = array("nama","jabatan","departemen");

			$result = $this->thrModel->findDataTable($orderBy,$search,$after,$before);
			foreach ($result as $item) {
				$kodepayroll = "'" . $item->id_thr . "'";
				$btnTools = '<button class="btn btn-default btn-outline-default btn-mini" onclick="btnPrint('.$item->id_thr.')"><i class="fa fa-print"></i>Print</button>';
				$item->tanggal =  date_ind("d M Y",$item->tanggal);
				$item->nilai = "Rp.".number_format($item->nilai,0,",",",");
				$item->button_tool = $btnTools;
				$data[] = $item;
			}
			return $this->thrModel->findDataTableOutput($data,$search,$after,$before);
		}
	}

	public function generate_penggajian()
	{
		//check login
		parent::checkLoginUser();

		if ($this->isPost()) {

			$listKaryawan = $this->thrModel->getAll(array('status_kerja' => "aktif"));
			$setting = $this->thrModel->validThr();
			$listAgama = array();
			foreach ($setting as $key => $vals) {
				$listAgama[] = $vals['agama'];
			}
			$dataThr = array();
			if (sizeof($listAgama) == 0) {
				$this->response->message = "<span style='color:red'>Proses THR tidak sesuai dengan tanggal di Setting.!</span>";
			}
			else {
				foreach ($listKaryawan as $key => $value) {
					if (in_array($value->agama,$listAgama)) {
						$month = strtotime($value->tgl_masuk);
						$end = strtotime(date("Y-m-d"));
						$counterMonth = 0;
						while($month < $end)
						{
							$counterMonth++;
							$month = strtotime("+1 month", $month);
						}
						if ($counterMonth < 4) {
							continue;
						}
						else {
							if ($counterMonth > 12) {
								$thrKaryawan = array(
									'tanggal' => date("Y-m-d"),
									'id_karyawan' => $value->id,
									'nilai' => $value->umk
								);
								$dataThr[] = $thrKaryawan;
							}
							else {
								$thr = ($value->umk/12) * $counterMonth;
								$thrKaryawan = array(
									'tanggal' => date("Y-m-d"),
									'id_karyawan' => $value->id,
									'nilai' => $thr
								);
								$dataThr[] = $thrKaryawan;
							}
						}
					}
					else {
						continue;
					}

				}
				$insert = $this->thrModel->insert($dataThr);
				if ($insert) {
					$this->response->status = true;
					$this->response->message = "<span style='color:green'>berhasil tambah thr karyawan.!</span>";
				}
				else {
					$this->response->message = "<span style='color:red'>gagal tambah thr karyawan.!</span>";
				}
			}
		}
		parent::json();
	}

	public function getSlipGajiKaryawan($id)
	{
		if ($this->isPost()) {
			$dataKaryawan = $this->thrModel->getDataLengkap($id);
			$dataKaryawan->nilai = "Rp.".number_format($dataKaryawan->nilai,0,",",",");
			$this->response->status = true;
			$this->response->data = $dataKaryawan;
		}
		parent::json();
	}

}
