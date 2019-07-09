<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Detection extends MY_Controller {

	public function __construct()
	{
		parent::__construct();
		$this->load->model('aktivitas/Detection_model',"detectionModel");

	}

	public function index()
	{
		parent::checkLoginUser(); // user login autentic checking

		parent::headerTitle("Aktivitas Data > Detection Fraud","Aktivitas Data","Detection Fraud");
		$breadcrumbs = array(
							"Aktivitas Data"	=>	site_url('aktivitas/detection'),
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

			$orderBy = array(null,null,"nama","email","no_telp","transaction_limit","report_message");
			$search = array("nama","email","no_telp");

			$result = $this->detectionModel->findDataTable($orderBy,$search);
			foreach ($result as $item) {
				// non fraud
				$payment = $this->detectionModel->getAllPayment();
				$transaction = $this->detectionModel->getAllTransaction();
				$report = $this->detectionModel->getAllReport();
				//fraud
				$paymentFraud = $this->detectionModel->getPaymentFraud($id);
				$transactionFraud = $this->detectionModel->getTransactionFraud($id);
				$reportFraud = $this->detectionModel->getReportFraud($id);
				//total Data

				$totalPayment = $payment + $paymentFraud;
				$totalTransaction = $transaction + $transactionFraud;
				$totalReport = $report + $reportFraud;
				// persentase
				$persenPayment = $payment / $totalPayment;
				$persenPaymentFraud = $paymentFraud / $totalPayment;

				$persenTransaction = $transaction / $totalTransaction;
				$persenTransactionFraud = $transactionFraud / $totalTransaction;

				$persenReport = $report / $totalReport;
				$persenReportFraud = $reportFraud / $totalReport;

				// IP = IP & ip,bulkbuy,payment

				$ipTTT = 0.9;
				$ipTTF = 0.5;
				$ipTFT = 0.5;
				$ipTFF = 0.1;
				$ipFTT = 0.1;
				$ipFTF = 0.5;
				$ipFFT = 0.5;
				$ipFFF = 0.9;

				//Trans = Transaksi  & transaksi,ip,report

				$transTTT = 0.9;
				$transTTF = 0.5;
				$transTFT = 0.5;
				$transTFF = 0.1;
				$transFTT = 0.1;
				$transFTF = 0.5;
				$transFFT = 0.5;
				$transFFF = 0.9;

				
				$data[] = $item;
			}
			return $this->detectionModel->findDataTableOutput($data,$search);
		}
	}


}

/* End of file Detection.php */
/* Location: ./application/controllers/aktivitas/Detection.php */
