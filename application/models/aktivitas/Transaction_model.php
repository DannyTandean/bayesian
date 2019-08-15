<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Transaction_model extends CI_Model {

	public $_table;
	public $_primary_key;

	public function __construct()
	{
		parent::__construct();
		$this->_table = "transaction";
		$this->_primary_key = "transaction_id";
	}

	public function setQueryDataTable($search,$after=null,$before=null)
	{
		$dataSearch = array();
		foreach ($search as $val) {
			$dataSearch[$val] = $this->input->post("search")["value"];
		}
		$search = $dataSearch;
		$this->db->from($this->_table);
		$this->db->select('user.*,transaction.transaction_id,transaction_amount,payment_amount,payment_card,transaction.status as transStatus,payment.status as paymentStatus,transaction_id');
		$this->db->join('user', 'user.id_user = transaction.user_id', 'left');
		$this->db->join('payment', 'payment.payment_id = transaction.transaction_payment', 'left');
		$this->db->group_start()->or_like($search)->group_end();

	}

	public function findDataTable($columnsOrderBy,$search,$after=null,$before=null)
	{
		$input = $this->input;
		$orderBy = false;
		if (isset($_POST['order'])) {
			$valColumnName = $columnsOrderBy[$_POST['order']['0']['column']];
			$valKeyword = $_POST['order']['0']['dir'];
			$orderBy = array($valColumnName." ".$valKeyword);
			$orderBy = implode(",", $orderBy);
		}

		// query data table
		self::setQueryDataTable($search,$after,$before);

		$this->db->order_by($orderBy);
		$this->db->limit($input->post("length"),$input->post("start"));
		$data = $this->db->get()->result();
		$no = $input->post("start");
		foreach ($data as $item) {
			$no++;
			$item->no = $no;
		}
		return $data;
	}

	public function findDataTableOutput($data=null,$search=false,$after=null,$before=null)
	{
		$input = $this->input;

		// query data table
		self::setQueryDataTable($search);

		$getCount = $this->db->count_all_results();

		$response = new stdClass();
		$response->draw = !empty($input->post("draw")) ? $input->post("draw"):null;
		$response->recordsTotal = $getCount;
		$response->recordsFiltered = $getCount;
		$response->data = $data;

		self::json($response);
	}

	public function json($data = null)
	{
    	$this->output->set_header("Content-Type: application/json; charset=utf-8");
    	$this->output->set_content_type('application/json');
	    $this->output->set_output(json_encode($data));
	}

	public function blockTransaksi($id,$data)
	{
		$this->db->where($this->_primary_key,$id);
		return $this->db->update($this->_table,$data);
	}

	public function blockPayment($id,$data)
	{
		$this->db->where("payment_id",$id);
		return $this->db->update("payment",$data);
	}

	public function getById($id)
	{
		$this->db->where($this->_primary_key,$id);
		// $this->db->join('user', 'user.id_user = report.report_user');
		$query = $this->db->get($this->_table);
		return $query->row();
	}

	public function getPaymentTransById($id)
	{
		$this->db->select('transaction_amount,payment_amount,payment_card,transaction_payment,transaction.status as transStatus,payment.status as paymentStatus,transaction_id');
		$this->db->where($this->_primary_key,$id);
		$this->db->join('payment', 'payment.payment_id = transaction.transaction_payment', 'left');
		$query = $this->db->get($this->_table);
		return $query->row();
	}

	public function getByIdPembeli($id)
	{
		$this->db->where($this->_primary_key,$id);
		$this->db->join('user', 'user.id_user = '.$this->_table.'.user_id', 'left');
		$query = $this->db->get($this->_table);
		return $query->row();
	}

	public function getByIdCart($id)
	{
		$this->db->where($this->_primary_key,$id);
		// $this->db->join('cart', 'cart.cart_id = '.$this->_table.'.cart_id', 'left');
		$this->db->join('user', 'user.id_user = '.$this->_table.'.user_id', 'left');
		// $this->db->join('product', 'product.product_id = cart.product_id', 'left');
		$query = $this->db->get($this->_table);
		return $query->row();
	}

	public function getCardId($id)
	{
		$this->db->where('cart_id',$id);
		$this->db->join('product', 'product.product_id = cart.product_id', 'left');
		$query = $this->db->get('cart');
		return $query->row();
	}

	public function getByIdPayment($id)
	{
		$this->db->where($this->_primary_key,$id);
		$this->db->join('payment', 'payment.payment_id = '.$this->_table.'.transaction_payment', 'left');
		$query = $this->db->get($this->_table);
		return $query->row();
	}

}

/* End of file Report_model.php */
/* Location: ./application/models/aktivitas/Report_model.php */
