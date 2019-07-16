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

		parent::headerTitle("Activity > Detection Fraud","Activity","Detection Fraud");
		$breadcrumbs = array(
							"Activity"	=>	site_url('aktivitas/detection'),
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

			$orderBy = array(null,"nama","email","no_telp",null,null);
			$search = array("nama","email","no_telp");

			$result = $this->detectionModel->findDataTable($orderBy,$search);
			foreach ($result as $item) {
				$stringFraudcase = "";
				// non fraud
				$payment = $this->detectionModel->getAllPayment();
				$transaction = $this->detectionModel->getAllTransaction();
				$report = $this->detectionModel->getAllReport();
				//fraud
				$paymentFraud = $this->detectionModel->getPaymentFraud($item->id_user);
				$transactionFraud = $this->detectionModel->getTransactionFraud($item->id_user);
				$reportFraud = $this->detectionModel->getReportFraud($item->id_user);
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
				// var_dump($persenPayment." ".$persenTransaction." ". $persenReport);
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

				$fraudTF000 = $persenTransactionFraud * $persenPaymentFraud * $persenReportFraud * $ipFFF * $transTFF;
				$fraudTF001 = $persenTransactionFraud * $persenPaymentFraud * $persenReportFraud * $ipFFF * $transTFT;
				$fraudTF010 = $persenTransactionFraud * $persenPaymentFraud * $persenReportFraud * $ipTFF * $transTTF;
				$fraudTF011 = $persenTransactionFraud * $persenPaymentFraud * $persenReportFraud * $ipTFF * $transTTT;
				$fraudTF100 = $persenTransactionFraud * $persenPaymentFraud * $persenReportFraud * $ipFTF * $transTFF;
				$fraudTF101 = $persenTransactionFraud * $persenPaymentFraud * $persenReportFraud * $ipFTF * $transTFT;
				$fraudTF110 = $persenTransactionFraud * $persenPaymentFraud * $persenReportFraud * $ipTTF * $transTTF;
				$fraudTF111 = $persenTransactionFraud * $persenPaymentFraud * $persenReportFraud * $ipTTF * $transTTT;

				$fraudTT000 = $persenTransactionFraud * $persenPaymentFraud * $persenReportFraud * $ipFFT * $transTFF;
				$fraudTT001 = $persenTransactionFraud * $persenPaymentFraud * $persenReportFraud * $ipFFT * $transTFT;
				$fraudTT010 = $persenTransactionFraud * $persenPaymentFraud * $persenReportFraud * $ipTFT * $transTTF;
				$fraudTT011 = $persenTransactionFraud * $persenPaymentFraud * $persenReportFraud * $ipTFT * $transTTT;
				$fraudTT100 = $persenTransactionFraud * $persenPaymentFraud * $persenReportFraud * $ipFTT * $transTFF;
				$fraudTT101 = $persenTransactionFraud * $persenPaymentFraud * $persenReportFraud * $ipFTT * $transTFT;
				$fraudTT110 = $persenTransactionFraud * $persenPaymentFraud * $persenReportFraud * $ipTTT * $transTTF;
				$fraudTT111 = $persenTransactionFraud * $persenPaymentFraud * $persenReportFraud * $ipTTT * $transTTT;

				$total1 = $fraudTF000 + $fraudTF001 + $fraudTF010 + $fraudTF011 + $fraudTF100 + $fraudTF101 + $fraudTF110 + $fraudTF111;
				$total2 = $fraudTT000 +	$fraudTT001 +	$fraudTT010 +	$fraudTT011 +	$fraudTT100 +	$fraudTT101 +	$fraudTT110 +	$fraudTT111;
				$listTransaksi = $this->detectionModel->listTransaction($item->id_user);
				$stringTrans = "";
				$counter = 0;

				foreach ($listTransaksi as $key => $value) {
					if ($counter == sizeof($listTransaksi)-1) {
						$stringTrans .= $value->transaction_id;
					}
					else {
						$stringTrans .= $value->transaction_id.",";
					}
					$counter++;
				}
				if ($stringTrans == "") {
					$stringTrans = "-";
				}

				if ($paymentFraud > 0) {
					$stringFraudcase .= "Payment,";
				}
				if ($transactionFraud > 0) {
					$stringFraudcase .= "Bulk Buy,";
				}
				if ($reportFraud > 0) {
					$stringFraudcase .= "Report";
				}

				if (($total1 == 0 || $total2 == 0) || ($total1 == 0 && $total2 == 0)) {
					$total3 = 0;
				}
				else {
					$total3 = $total2/($total1 + $total2);
				}
				$item->transaction_limit = "Rp.".number_format($item->transaction_limit,0,",",",");
				$item->Fraudcase = $stringFraudcase;
				$item->fraudProbability = number_format((float)($total3 * 100), 2, '.', '')."%";
				$item->listTransaksi = $stringTrans;
				$data[] = $item;
			}
			return $this->detectionModel->findDataTableOutput($data,$search);
		}
	}


}

/* End of file Detection.php */
/* Location: ./application/controllers/aktivitas/Detection.php */
