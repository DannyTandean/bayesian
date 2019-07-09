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

			$orderBy = array(null,null,"nama","email","no_telp","transaction_limit");
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

				//P(bb,p,ip,ur,t) = P (BB) x P (p) x P (ur) x P (ip | p , b) x P ( t |ur , ip )

				$fraudTF000 = $persenTransaction * $persenPayment * $persenReport * $ipFFF * $transTFF;
				$fraudTF001 = $persenTransaction * $persenPayment * $persenReport * $ipFFF * $transTFT;
				$fraudTF010 = $persenTransaction * $persenPayment * $persenReport * $ipTFF * $transTTF;
				$fraudTF011 = $persenTransaction * $persenPayment * $persenReport * $ipTFF * $transTTT;
				$fraudTF100 = $persenTransaction * $persenPayment * $persenReport * $ipFTF * $transTFF;
				$fraudTF101 = $persenTransaction * $persenPayment * $persenReport * $ipFTF * $transTFT;
				$fraudTF110 = $persenTransaction * $persenPayment * $persenReport * $ipTTF * $transTTF;
				$fraudTF111 = $persenTransaction * $persenPayment * $persenReport * $ipTTF * $transTTT;

				$fraudTT000 = $persenTransaction * $persenPayment * $persenReport * $ipFFT * $transTFF;
				$fraudTT001 = $persenTransaction * $persenPayment * $persenReport * $ipFFT * $transTFT;
				$fraudTT010 = $persenTransaction * $persenPayment * $persenReport * $ipTFT * $transTTF;
				$fraudTT011 = $persenTransaction * $persenPayment * $persenReport * $ipTFT * $transTTT;
				$fraudTT100 = $persenTransaction * $persenPayment * $persenReport * $ipFTT * $transTFF;
				$fraudTT101 = $persenTransaction * $persenPayment * $persenReport * $ipFTT * $transTFT;
				$fraudTT110 = $persenTransaction * $persenPayment * $persenReport * $ipTTT * $transTTF;
				$fraudTT111 = $persenTransaction * $persenPayment * $persenReport * $ipTTT * $transTTT;

				$total1 = $fraudTF000 + $fraudTF001 + $fraudTF010 + $fraudTF011 + $fraudTF100 + $fraudTF101 + $fraudTF110 + $fraudTF111;
				$total2 = $fraudTT000 +	$fraudTT001 +	$fraudTT010 +	$fraudTT011 +	$fraudTT100 +	$fraudTT101 +	$fraudTT110 +	$fraudTT111;

				$total3 = $total1+$total2;

				$data[] = $item;
			}
			return $this->detectionModel->findDataTableOutput($data,$search);
		}
	}


}

/* End of file Detection.php */
/* Location: ./application/controllers/aktivitas/Detection.php */
