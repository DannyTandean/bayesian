<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Pengujian_model extends CI_Model {

	public $_table;
	public $_primary_key;

	public function __construct()
	{
		parent::__construct();
		$this->_table = "dataset";
		$this->_primary_key = "no";
	}

	public function setQueryDataTable($search,$after=null,$before=null)
	{
		$dataSearch = array();
		foreach ($search as $val) {
			$dataSearch[$val] = $this->input->post("search")["value"];
		}
		$search = $dataSearch;
		$this->db->from($this->_table);
		$this->db->select('COUNT(nameDest) as countname,isFraud,type,amount,nameOrig,oldbalanceOrg,newbalanceOrig,nameDest,oldbalanceDest,newbalanceDest,isFlaggedFraud');
		$this->db->group_by("nameDest");
		$this->db->having('countname > 1');
		// $data = array(
		// 								'type !=' => "CASH_IN",
		// 								'type !=' => "CASH_OUT"
		// 						 );
		// $this->db->where($data);
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

	public function getAll()
	{
		// $data = array(
		// 								'type !=' => "CASH_IN",
		// 								'type !=' => "CASH_OUT"
		// 						 );
		// $this->db->where($data);
		$query = $this->db->get($this->_table);
		return $query->result();
	}

	public function getAllFraud()
	{
		$data = array(
										'type !=' => "CASH_IN",
										'type !=' => "CASH_OUT",
										'isFraud' => 1
								 );
		$this->db->where($data);
		$query = $this->db->get($this->_table);
		return $query->result();
	}


	public function getquery()
	{
		$query = $this->db->query("select nameDest,COUNT(nameDest) as countname,type,newbalanceOrig,oldbalanceDest,isFraud,amount,oldbalanceOrg from dataset GROUP by nameDest HAVING COUNT(nameDest)>1");

		return $query->result();
	}

	public function getqueryFraud()
	{
		$query = $this->db->query("select nameDest,COUNT(nameDest) as countname,type,newbalanceOrig,oldbalanceDest,isFraud,amount,oldbalanceOrg from dataset GROUP by nameDest HAVING COUNT(nameDest)>1 and isFraud = 1");

		return $query->result();
	}

	public function getqueryNonFraud()
	{
		$query = $this->db->query("select nameDest,COUNT(nameDest) as countname,type,newbalanceOrig,oldbalanceDest,isFraud,amount,oldbalanceOrg from dataset GROUP by nameDest HAVING COUNT(nameDest)>1 and isFraud = 0");

		return $query->result();
	}

	public function insert($data)
	{
		if ($this->db->insert_batch($this->_table,$data)) {
			return $this->db->insert_id();
		}
	}

	public function getByStep($id)
	{
		// $this->db->where('nameDest',$id);
		$where = "nameDest = ".$this->db->escape($id)."or nameOrig = ".$this->db->escape($id);
		$this->db->where($where);
		$this->db->order_by('step','asc');
		$query = $this->db->get($this->_table);

		return $query->result();
	}

}

/* End of file Detection_model.php */
/* Location: ./application/models/aktivitas/Detection_model.php */
