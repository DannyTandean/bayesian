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

		parent::headerTitle("Activity > Pengujian","Activity","Pengujian");
		$breadcrumbs = array(
							"Activity"	=>	site_url('aktivitas/Pengujian'),
							"Pengujian"		=>	"",
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
				// if ($item->countname > 1) {
				// }
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
				$this->response->data = sizeof($dataFraud);
			}
			else {
				$this->response->message = spanRed("data tidak ada.!");
			}
		}
		parent::json();
	}

	public function getSimulation()
	{
		if ($this->isPost()) {
			ini_set("memory_limit", "-1");
			// $get = $this->pengujianModel->getquery();
			$get = $this->pengujianModel->getquery();
			$getFraud = $this->pengujianModel->getqueryFraud();
			$getNonFraud = $this->pengujianModel->getqueryNonFraud();
			// $dataName = array();
			// $data = array();
			$fraud = 0;
			$total = 0;
			$fraudFP = 0;
			$fraudFN = 0;
			$tp=0;
			$tn=0;
			$fp=0;
			$fn=0;
			if ($get) {
				foreach ($get as $key => $value) {

					if (intval($value->newbalanceOrig) == 0 && intval($value->oldbalanceDest) == 0 ) {
						if (($value->type == "TRANSFER" || $value->type == "CASH_OUT") && intval($value->amount) == intval($value->oldbalanceOrg) && intval($value->newbalanceOrig) == 0) {
							if (in_array($value,$getFraud)) {
								$tp++;
							}
							else {
								$fp++;
							}
						}
						else {
							if (in_array($value,$getFraud)) {
								$tp++;
							}
							else {
								$fp++;
							}
						}
					}
					else {
						if (in_array($value,$getNonFraud)) {
							$tn++;
						}
						else {
							$fn++;
						}
					}
				}

				if ($get) {
					$this->response->status = true;
					$this->response->message = spanGreen("berhasil get data fraud.!");
					$this->response->data = sizeof($get)." ".($tp+$tn+$fp+$fn)." ".(sizeof($getFraud) + sizeof($getNonFraud));
					$this->response->tp = $tp;
					$this->response->tn = $tn;
					$this->response->fp = $fp;
					$this->response->fn = $fn;
					// $this->response->fraud = $fraud;
					// $this->response->originFraud = $total;
					// $this->response->fraudFP = $fraudFP;
					// $this->response->fraudFN = $fraudFN;

				}
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
