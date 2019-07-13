<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Pengujian extends MY_Controller {

	public function __construct()
	{
		parent::__construct();
		$this->load->model('aktivitas/Pengujian_model',"pengujianModel");

	}

	public function index()
	{
		parent::checkLoginUser(); // user login autentic checking

		parent::headerTitle("Aktivitas Data > Detection Fraud","Aktivitas Data","Detection Fraud");
		$breadcrumbs = array(
							"Aktivitas Data"	=>	site_url('aktivitas/Pengujian'),
							"Detection Fraud"		=>	"",
						);
		parent::breadcrumbs($breadcrumbs);
		parent::viewAktivitas();
	}

	public function ajax_list()
	{
		parent::checkLoginUser(); // user login autentic checking

		if ($this->isPost()) {
			$data = array();
			$orderBy = array(null,'isFraud','type','amount','nameOrig','oldbalanceOrg','newbalanceOrig','nameDest','oldbalanceDest','newbalanceDest','isFlaggedFraud');
			$search = array("isFraud","type");

			$result = $this->pengujianModel->findDataTable($orderBy,$search);
			foreach ($result as $item) {
				$data[] = $item;
			}
			return $this->pengujianModel->findDataTableOutput($data,$search);
		}
	}

	public function getFraud()
	{
		// parent::checkLoginUser(); // user login autentic checking

		if ($this->isPost()) {

			$dataset = $this->pengujianModel->getAll();
			$dataFraud = $this->pengujianModel->getAllFraud();
			$datasetFraud = array();
			$fraudData = array();
			$count = 0;
			$fraudTransfer = 0;
			$fraudCashOut = 0;
			$isfraudMax = 0;
			if ($dataset) {
				foreach ($dataFraud as $key => $value) {
					$datasetFraud[] = $value;
				}
				foreach ($dataset as $key => $value) {
					if ($value->type == "PAYMENT") {
						if (($value->oldbalanceOrg - $value->amount) != $value->newbalanceOrig) {
							$fraudData[] = $value;
						}
					}
					// if ((float)$value->amount > (float)200000) {
					// 	$fraudData[] = $value;
					// }
					// else if ($value->type == "DEBIT") {
					// 	if (($value->oldbalanceOrg - $value->amount) != $value->newbalanceOrig) {
					// 		$fraudData[] = $value;
					// 	}
					// }
					// else if ($value->type == "TRANSFER") {
					// 	if (($value->oldbalanceOrg - $value->amount) != $value->newbalanceOrig) {
					// 		$fraudData[] = $value;
					// 	}
					// }
				}
				foreach ($dataFraud as $key => $value) {
					if (in_array($value,$datasetFraud)) {
						$count++;
					}
				}
				$this->response->status = true;
				$this->response->message = spanGreen("berhasil get data fraud.!");
				$this->response->data = sizeof($fraudData);
			}
			else {
				$this->response->message = spanRed("data tidak ada.!");
			}
		}
		parent::json();
	}

}

/* End of file Detection.php */
/* Location: ./application/controllers/aktivitas/Detection.php */
