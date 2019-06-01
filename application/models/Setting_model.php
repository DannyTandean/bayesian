<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Setting_model extends CI_Model {

	public $_table;
	public $_primary_key;

	public function __construct()
	{
		parent::__construct();
		$this->_table = "settings_general";
		$this->_primary_key = "id_setting";
	}

	public function setQueryDataTable($search)
	{
		$dataSearch = array();
		foreach ($search as $val) {
			$dataSearch[$val] = $this->input->post("search")["value"];
		}
		$search = $dataSearch;
		$this->db->from($this->_table);
		$this->db->group_start()->or_like($search)->group_end();

	}

	public function findDataTable($columnsOrderBy,$search)
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
		self::setQueryDataTable($search);

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

	public function findDataTableOutput($data=null,$search=false)
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

	public function insert($data)
	{
		if ($this->db->insert($this->_table,$data)) {
			return $this->db->insert_id();
		}
	}

	public function getProvinsi()
	{
		$query = $this->db->get('provinces');
		return $query->result_array();
	}

	public function getProvinsiId($id)
	{
		$this->db->where('id', $id);
		$query = $this->db->get('provinces');
		return $query->row();
	}

	public function getRegencies($id)
	{
		$this->db->select('regencies.*');
		$this->db->where('province_id', $id);
		$this->db->join('provinces', 'provinces.id = regencies.province_id');
		$query = $this->db->get('regencies');
		return $query->result();
	}

	public function getRegenciesId($id,$status=false)
	{
		$this->db->select('regencies.*');
		$this->db->where('id_regencies', $id);
		$this->db->join('provinces', 'provinces.id = regencies.province_id');
		$query = $this->db->get('regencies');
		if($status)
		{
			return $query->row();
		}
		else {
			return $query->result();
		}

	}

	public function getTotalHari()
	{
		$this->db->select('total_hari,otomatis_hari');
		$query = $this->db->get($this->_table);

		return $query->row();
	}

	public function getById($id)
	{
		$this->db->where($this->_primary_key,$id);
		$query = $this->db->get($this->_table);
		return $query->row();
	}

	public function update($id,$data)
	{
		$this->db->where($this->_primary_key,$id);
		return $this->db->update($this->_table,$data);
	}

	public function delete($id)
	{
		$this->db->where($this->_primary_key,$id);
		$this->db->delete($this->_table);
		return $this->db->affected_rows();
	}

	public function getBpjs()
	{
		$this->db->where('id_settings_bpjs', 1);
		$query = $this->db->get('settings_bpjs');

		return $query->row();
	}

	public function updateBpjs($id,$data)
	{
		$this->db->where("id_settings_bpjs",$id);
		return $this->db->update("settings_bpjs",$data);
	}

}

/* End of file Grup_model.php */
/* Location: ./application/models/Setting_model.php */
