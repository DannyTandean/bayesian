<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Bpjs extends MY_Controller {

	public function __construct()
	{
		parent::__construct();
		$this->load->model('statistik/Bpjs_model',"bpjsModel");
		$this->load->model('Calendar_model',"calendarModel");
	}

	public function index()
	{
		parent::checkLoginUser(); // user login autentic checking

		parent::headerTitle("Statistik data > BPJS","Statistik Data","BPJS");
		$breadcrumbs = array(
							"Statistik Data"	=>	site_url('statistik/bpjs'),
							"BPJS"		=>	"",
						);
		parent::breadcrumbs($breadcrumbs);
		// $data = array();
		// $data['departemen'] = $this->departemenModel->getAll();
		// $data['grup'] = $this->grupModel->getAll();
		// parent::viewData($data);
		parent::viewStatistik();
	}

	public function ajax_list($periode=null,$before=null,$after=null)
	{
		parent::checkLoginUser(); // user login autentic checking

		if ($this->isPost()) {
			$data = array();

			$orderBy = array(null,"nama","grup","jabatan","departemen","cabang","totalBpjs");
			$search = array("nama","grup","cabang","departemen","jabatan");

			$result = $this->bpjsModel->findDataTable($orderBy,$search,$periode,$before,$after);
			foreach ($result as $item) {
				// $item->totalBpjs = "Rp.".number_format($item->totalBpjs,0,",",",");
				$data[] = $item;
			}
			return $this->bpjsModel->findDataTableOutput($data,$search,$periode,$before,$after);
		}
	}

	public function ajax_list_after($periode,$before,$after)
	{
		if ($periode == "zero") {
			self::ajax_list(null,$before,$after);
		}
		else if ($periode != "zero") {
			self::ajax_list($periode,$before,$after);
		}
	}

}

/* End of file Bpjs.php */
/* Location: ./application/controllers/statistik/Bpjs.php */
