<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Detection_model extends CI_Model {

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
		$this->db->from("user");
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

	public function listTransaction($id)
	{
		$this->db->where('user_id',$id);
		$query = $this->db->get('transaction');

		return $query->result();
	}

	public function getAllTransaction()
	{
		$query = $this->db->get($this->_table);

		return $query->num_rows();
	}

	public function getTransactionFraud($idUser)
	{
		$data = array(
										'user_id'=> $idUser,
										'status' => "2"
								 );
		$this->db->where($data);
		$query = $this->db->get($this->_table);
		return $query->num_rows();
	}

	public function getCreditCardFraud($id)
	{
		$this->db->where('card_user', $id);

		$query = $this->db->get('credit_card');

		return $query->result();
	}

	public function getAllPayment()
	{
		$query = $this->db->get("payment");

		return $query->num_rows();
	}

	public function getPaymentFraud($idUser)
	{
		$data = array(
										'id_user'=> $idUser,
										'status' => "2"
								 );
		$this->db->where($data);
		$query = $this->db->get("payment");
		return $query->num_rows();
	}

	public function getAllReport()
	{
		$query = $this->db->get("report");

		return $query->num_rows();
	}

	public function getReportFraud($idUser)
	{
		$data = array(
										'user_id'=> $idUser,
										'status' => "2"
								 );
		$this->db->where($data);
		$query = $this->db->get("report");
		return $query->num_rows();
	}

	public function getUserFraud($id)
	{
		$this->db->where('id_user', $id);

		$query = $this->db->get('user');

		return $query->row();
	}

}

/* End of file Detection_model.php */
/* Location: ./application/models/aktivitas/Detection_model.php */
