<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Transaction extends MY_Controller {

	public function __construct()
	{
		parent::__construct();
		$this->load->model('aktivitas/Transaction_model',"transactionModel");

	}

	public function index()
	{
		parent::checkLoginUser(); // user login autentic checking

		parent::headerTitle("Aktivitas Data > Transaksi User","Aktivitas Data","Transaksi User");
		$breadcrumbs = array(
							"Aktivitas Data"	=>	site_url('aktivitas/transaction'),
							"Transaksi"		=>	"",
						);
		parent::breadcrumbs($breadcrumbs);
		parent::viewAktivitas();
	}

	public function ajax_list()
	{
		parent::checkLoginUser(); // user login autentic checking

		if ($this->isPost()) {
			$data = array();

			$orderBy = array(null,null,"nama","transaction_amount","payment_amount","payment_card");
			$search = array("nama");

			$result = $this->transactionModel->findDataTable($orderBy,$search);
			foreach ($result as $item) {

				$btnAction = '<button class="btn btn-default btn-default btn-mini" onclick="btnPayment('.$item->transaction_id.')"><i class="fa fa-trash-o"></i>Cek Pembayaran</button>';
				$btnAction .= '&nbsp;&nbsp;&nbsp;<button class="btn btn-info btn-info btn-mini" onclick="btnDetail('.$item->transaction_id.')"><i class="fa fa-pencil-square-o"></i>Detail</button>';
				$btnAction .= '<br><br><button class="btn btn-info btn-info btn-mini" onclick="btnPembeli('.$item->transaction_id.')"><i class="fa fa-pencil-square-o"></i>Detail Pembeli</button>';
				$btnAction .= '&nbsp;&nbsp;&nbsp;<button class="btn btn-default btn-default btn-mini" onclick="btnLokasi('.$item->transaction_id.')"><i class="fa fa-trash-o"></i>Cek lokasi</button>';

				$item->transaction_amount = "Rp.".number_format($item->transaction_amount,0,",",",");
				$item->payment_amount = "Rp.".number_format($item->payment_amount,0,",",",");

				$item->button_action = $btnAction;
				$data[] = $item;
			}
			return $this->transactionModel->findDataTableOutput($data,$search);
		}
	}

	public function getId($id)
	{
		parent::checkLoginUser(); // user login autentic checking

		if ($this->isPost()) {
			$getById = $this->transactionModel->getById($id);
			if ($getById->image != "") {
				$getById->image = base_url("/")."uploads/aktivitas/orang/".$getById->image;
			} else {
				$getById->image = base_url("/")."assets/images/default/no_user.png";
			}
			if ($getById) {
				$this->response->status = true;
				$this->response->message = "Data laporan user get by id";
				$this->response->data = $getById;
			} else {
				$this->response->message = alertDanger("Data Transaksi tidak ada.");
			}
		}
		parent::json();
	}

	public function getPembeliId($id)
	{
		parent::checkLoginUser(); // user login autentic checking

		if ($this->isPost()) {
			$getById = $this->transactionModel->getByIdPembeli($id);
			if ($getById->image != "") {
				$getById->image = base_url("/")."uploads/aktivitas/orang/".$getById->image;
			} else {
				$getById->image = base_url("/")."assets/images/default/no_user.png";
			}
			if ($getById) {
				$this->response->status = true;
				$this->response->message = "Data user get by id";
				$this->response->data = $getById;
			} else {
				$this->response->message = alertDanger("Data user tidak ada.");
			}
		}
		parent::json();
	}

	public function getPaymentId($id)
	{
		parent::checkLoginUser(); // user login autentic checking

		if ($this->isPost()) {
			$getById = $this->transactionModel->getByIdPayment($id);
			if ($getById->transaction_payment == null) {
				$getById->payment_amount = 0;
			}
			if ($getById) {
				$this->response->status = true;
				$this->response->message = "Data transaksi get by id";
				$this->response->data = $getById;
			} else {
				$this->response->message = alertDanger("Data transaksi tidak ada.");
			}
		}
		parent::json();
	}

	public function getCartId($id)
	{
		parent::checkLoginUser(); // user login autentic checking

		if ($this->isPost()) {
			$getById = $this->transactionModel->getByIdCart($id);
			if ($getById->image != "") {
				$getById->image = base_url("/")."uploads/aktivitas/orang/".$getById->image;
			} else {
				$getById->image = base_url("/")."assets/images/default/no_user.png";
			}
			$listCardId = array();
			if ($getById->cart_id == null || $getById->cart_id == "") {
				$listCardId == null;
			}
			else {
				if (strpos($getById->cart_id, ',') !== false) {
					$listCardId =  explode(',',$getById->cart_id);
				}
				else {
					$listCardId[0] = $getById->cart_id;
				}
			}
			$tableCart = "";
			$counter = 1;
			foreach ($listCardId as $key => $value) {
				if ($value == null) {
					continue;
				}
				$cart = $this->transactionModel->getCardId($value);
				if ($cart->product_image != "") {
					$cart->product_image = base_url("/")."uploads/aktivitas/produk/".$cart->product_image;
				} else {
					$cart->product_image = base_url("/")."assets/images/default/no_file_.png";
				}
				$tableCart = '<tr>
											<td>'.$counter.'</td>
											<td>'.$cart->product_name.'</td>
											<td><img class="img-fluid img-circle" src="'.$cart->product_image.'" alt="user-img" style="height: 70px; width: 70px;"></td>
											<td>'.$cart->qty.'</td>
											<td>Rp.'.number_format($cart->total,0,",",",").'</td>
											</tr>';
				$counter++;
			}
			$getById->table = $tableCart;
			if ($getById) {
				$this->response->status = true;
				$this->response->message = "Data transaksi get by id";
				$this->response->data = $getById;
			} else {
				$this->response->message = alertDanger("Data transaksi tidak ada.");
			}
		}
		parent::json();
	}

	public function delete($id)
	{
		parent::checkLoginUser(); // user login autentic checking

		if ($this->isPost()) {
			$checkData = $this->transactionModel->getById($id);

			if ($checkData) {
				$delete = $this->transactionModel->delete($id);
				if ($delete) {
					$this->response->status = true;
					$this->response->message = alertSuccess("Data laporan user Berhasil di hapus.");
				} else {
					$this->response->message = alertDanger("Data sudah tidak ada.");
				}
			} else {
				$this->response->message = alertDanger("Data sudah tidak ada.");
			}
		}
		parent::json();
	}

}

/* End of file Report.php */
/* Location: ./application/controllers/aktivitas/Report.php */
