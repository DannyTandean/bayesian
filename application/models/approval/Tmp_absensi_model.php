<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Tmp_absensi_model extends CI_Model {

	public $_table;
	public $_primary_key;

	public function __construct()
	{
		parent::__construct();
		$this->_table = "temp_absensi";
		$this->_primary_key = "id_temp_absensi";
	}

	public function setQueryDataTable($search,$where=false)
	{
		$dataSearch = array();
		foreach ($search as $val) {
			$dataSearch[$val] = $this->input->post("search")["value"];
		}
		$search = $dataSearch;
		if ($where) {
			$this->db->where($where);
		}
		$this->db->from($this->_table);
		$this->db->group_start()->or_like($search)->group_end();
		$this->db->where('temp_status',"Proses");
		$this->db->join('absensi', 'absensi.id_absensi = temp_absensi.id_absensi', 'left');
		$this->db->join('master_karyawan', 'master_karyawan.kode_karyawan = absensi.kode', 'left');
		$this->db->join('master_jabatan', 'master_jabatan.id_jabatan = master_karyawan.id_jabatan', 'left');
		$this->db->join('master_departemen', 'master_departemen.id_departemen = master_karyawan.id_departemen', 'left');
		$this->db->join('aktivitas_jadwal', 'aktivitas_jadwal.id_jadwal = absensi.id_jadwal', 'left');
	}

	public function findDataTable($columnsOrderBy,$search,$where=false)
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
		self::setQueryDataTable($search,$where);
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

	public function findDataTableOutput($data=null,$search=false,$where=false)
	{
		$input = $this->input;

		// query data table
		self::setQueryDataTable($search,$where);
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

	public function getById($id)
	{
		$this->db->select('nama,foto,departemen,jabatan,absensi.*,temp_status,id_temp_absensi,nama_jadwal');
		$this->db->where('id_temp_absensi', $id);
		$this->db->join('absensi', 'absensi.id_absensi = temp_absensi.id_absensi', 'left');
		$this->db->join('master_karyawan', 'master_karyawan.kode_karyawan = absensi.kode', 'left');
		$this->db->join('master_jabatan', 'master_jabatan.id_jabatan = master_karyawan.id_jabatan', 'left');
		$this->db->join('master_departemen', 'master_departemen.id_departemen = master_karyawan.id_departemen', 'left');
		$this->db->join('aktivitas_jadwal', 'aktivitas_jadwal.id_jadwal = absensi.id_jadwal', 'left');
		$data = $this->db->get($this->_table);
		return $data->row();
	}

}

/* End of file Tmp_absensi_model.php */
/* Location: ./application/models/approval/Tmp_absensi_model.php */
