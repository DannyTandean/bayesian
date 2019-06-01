<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Pph_model extends CI_Model {

	public $_table;
	public $_primary_key;

	public function __construct()
	{
		parent::__construct();
		$this->_table = "aktivitas_pph";
		$this->_primary_key = "id_pph";
	}

	public function setQueryDataTable($search)
	{
		$dataSearch = array();
		foreach ($search as $val) {
			$dataSearch[$val] = $this->input->post("search")["value"];
		}
		$search = $dataSearch;
		$this->db->from('master_karyawan');
		$this->db->join('master_golongan', 'master_karyawan.id_golongan = master_golongan.id_golongan');
		$this->db->join('penggajian', 'penggajian.id_karyawan = master_karyawan.id');
		// $this->db->join('master_karyawan', 'aktivitas_pph.id_pph = master_karyawan.id');
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

  public function findDataTableOutput($data=null,$search=false,$where=false,$whereIn=false)
	{
		$input = $this->input;

		// query data table
		self::setQueryDataTable($search,$where,$whereIn);
		$getCount = $this->db->count_all_results();

		$response = new stdClass();
		$response->draw = !empty($input->post("draw")) ? $input->post("draw"):null;
		$response->recordsTotal = $getCount;
		$response->recordsFiltered = $getCount;
		$response->data = $data;

		self::json($response);
	}

	public function getAllKaryawanPenggajian()
	{
		// $this->db->select('master_karyawan.*','master_golongan.*','penggajian.*');
		// $this->db->where('master_karyawan.id', $id);
		$this->db->join('master_golongan', 'master_karyawan.id_golongan = master_golongan.id_golongan');
		$this->db->join('penggajian', 'penggajian.id_karyawan = master_karyawan.id');
		$query = $this->db->get('master_karyawan');
		return $query->result();
		// $response = new stdClass();
		// $response->draw = !empty($input->post("draw")) ? $input->post("draw"):null;
		// $response->recordsTotal = $getCount;
		// $response->recordsFiltered = $getCount;
		// $response->data = $data;
		//
		// self::json($response);
	}

	public function getPenggajianPerKaryawan($id)
	{
		$this->db->where('master_karyawan.id', $id);
		$this->db->join('master_golongan', 'master_karyawan.id_golongan = master_golongan.id_golongan','left');
		$this->db->join('penggajian', 'penggajian.id_karyawan = master_karyawan.id','left');
		$query = $this->db->get('master_karyawan');
		// var_dump($query);
		return $query->result();
	}

	public function json($data = null)
	{
    	$this->output->set_header("Content-Type: application/json; charset=utf-8");
    	$this->output->set_content_type('application/json');
	    $this->output->set_output(json_encode($data));
	}

}

/* End of file pph_model.php */
/* Location: ./application/models/aktivitas/pph_model.php */
