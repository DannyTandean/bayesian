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
				// $report = $this->detectionModel->getAllReport();
				//fraud
				$paymentFraud = $this->detectionModel->getPaymentFraud($item->id_user);
				$transactionFraud = $this->detectionModel->getTransactionFraud($item->id_user);
				// $reportFraud = $this->detectionModel->getReportFraud($item->id_user);
				$crediCardFraud = $this->detectionModel->getCreditCardFraud($item->username);
				//total Data
				$persenUserbehavior = 0;
				$creditName = 0;
				$creditDelete = 0;
				$creditFraudUser = 0;
				foreach ($crediCardFraud as $key => $credit) {
					if (strpos($credit->card_user, $credit->card_name) !== true) {
					    $creditName = 0.33;
					}
					if ($credit->status == 0) {
						$creditDelete = 0.33;
					}
					if ($credit->card_fraud_user != 0) {
						$creditFraudUser = 0.33;
					}
				}
				$persenUserbehaviorT = $creditName + $creditDelete + $creditFraudUser;
				$persenUserbehaviorF = 1 - ($creditName + $creditDelete + $creditFraudUser);

				$totalPayment = $payment + $paymentFraud;
				$totalTransaction = $transaction + $transactionFraud;
				// $totalReport = $report + $reportFraud;
				// persentase
				$persenPayment = $payment / $totalPayment;
				$persenPaymentFraud = $paymentFraud / $totalPayment;

				// $persenTransaction = $transaction / $totalTransaction;
				// $persenTransactionFraud = $transactionFraud / $totalTransaction;
				//
				// $persenReport = $report / $totalReport;
				// $persenReportFraud = $reportFraud / $totalReport;

				//Trans = Transaksi  & transaksi,ip,report

				$transTTT = 0.9;
				$transTTF = 0.1;
				$transTFT = 0.2;
				$transTFF = 0.8;
				$transFTT = 0.3;
				$transFTF = 0.7;
				$transFFT = 0.7;
				$transFFF = 0.3;

				//P(bb,p,ip,ur,t) = P (BB) x P (p) x P (ur) x P (ip | p , b) x P ( t |ur , ip )

				$fraudTT000 = $transFTT * $persenPaymentFraud * $persenUserbehaviorF;
				$fraudTT001 = $transTTT * $persenPaymentFraud * $persenUserbehaviorT;
				$fraudTT010 = $transFTF * $persenPaymentFraud * $persenUserbehaviorF;
				$fraudTT011 = $transTTF * $persenPaymentFraud * $persenUserbehaviorT;

				$total1 = $fraudTT000 + $fraudTT001;
				$total2 = $fraudTT010 + $fraudTT011;

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
				// if ($transactionFraud > 0) {
				// 	$stringFraudcase .= "User Behavior,";
				// }
				if ($persenUserbehaviorT > 0) {
					$stringFraudcase .= "User Behavior";
				}

				if ($total1 == 0) {
					$total3 = 0;
				}
				else {
					$total3 = $total1 / ($total1 + $total2);
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
