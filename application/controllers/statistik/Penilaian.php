<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Penilaian extends MY_Controller {

	public function __construct()
	{
		parent::__construct();
		$this->load->model('statistik/Hasil_penilaian_model',"penilaianModel");
		$this->load->model('master/Departemen_model',"departemenModel");
		$this->load->model('master/Grup_model',"grupModel");
		$this->load->model('Calendar_model',"calendarModel");
	}

	public function index()
	{
		parent::checkLoginUser(); // user login autentic checking

		parent::headerTitle("Statistik data > Penilaian","Statistik Data","Penilaian");
		$breadcrumbs = array(
							"Statistik Data"	=>	site_url('statistik/penilaian'),
							"Penilaian"		=>	"",
						);
		parent::breadcrumbs($breadcrumbs);
		$data = array();
		$data['departemen'] = $this->departemenModel->getAll();
		$data['grup'] = $this->grupModel->getAll();
		parent::viewData($data);
		parent::viewStatistik();
	}

	public function ajax_list($periode=null,$before=null,$after=null,$departemen=null,$grup=null)
	{
		parent::checkLoginUser(); // user login autentic checking

		if ($this->isPost()) {
			$data = array();

			$orderBy = array(null,"tanggal","nama","grup","jabatan","departemen","cabang","hasil_nilai");
			$search = array("nama","grup","cabang","departemen","jabatan");

			$result = $this->penilaianModel->findDataTable($orderBy,$search,$periode,$before,$after,$departemen,$grup);
			foreach ($result as $item) {

				$item->tanggal = date_ind("d M Y",$item->tanggal);
				$item->hasil_nilai = number_format($item->hasil_nilai,2,'.','');
				$data[] = $item;
			}
			return $this->penilaianModel->findDataTableOutput($data,$search,$periode,$before,$after,$departemen,$grup);
		}
	}

	public function ajax_list_after($periode,$before,$after,$departemen,$grup)
	{
		if ($departemen == "zero" && $grup == "zero") {
			self::ajax_list($periode,$before,$after,null,null);
		}
		else if ($departemen == "zero") {
			self::ajax_list($periode,$before,$after,null,$grup);
		}
		else if ($grup == "zero") {
			self::ajax_list($periode,$before,$after,$departemen,null);
		}
	}

}

/* End of file Penilaian.php */
/* Location: ./application/controllers/statistik/Penilaian.php */
