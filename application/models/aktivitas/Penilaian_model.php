<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Penilaian_model extends CI_Model {

	public $_table;
	public $_primary_key;

	public function __construct()
	{
		parent::__construct();
		$this->_table = "penilaian";
		$this->_primary_key = "id_penilaian";
	}

	public function setQueryDataTable($search,$after=null,$before=null)
	{
		$dataSearch = array();
		foreach ($search as $val) {
			$dataSearch[$val] = $this->input->post("search")["value"];
		}
		$search = $dataSearch;
		// $this->db->query('select REPLACE(petugas1,petugas1, (select nama from master_karyawan where id = '.$this->db->escape('petugas1').' LIMIT 1) ) as petugas1,REPLACE(petugas2, petugas2 , (select nama from master_karyawan where id = '.$this->db->escape('petugas2').' LIMIT 1) ) as petugas2,tanggal_mulai,tanggal_akhir,keterangan from penilaian');
		$this->db->from($this->_table);
		$this->db->join('master_grup', 'penilaian.id_grup = master_grup.id_grup', 'left');
		$this->db->select('penilaian.*,master_grup.grup');
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

	public function getKaryawan()
	{
		$this->db->select('id,nama');
		$this->db->from("master_karyawan");

		$result = $this->db->get()->result_array();
		return $result;
	}

	public function getKaryawanNama($id)
	{
		$this->db->select('nama');
		$this->db->where('id', $id);
		$query = $this->db->get('master_karyawan');
		return $query->row();
	}

	public function getDataKaryawanSelect($id)
	{
		$this->db->from("master_karyawan");
		$this->db->join('master_departemen', 'master_karyawan.id_departemen = master_departemen.id_departemen');
		$this->db->join('master_jabatan', 'master_karyawan.id_jabatan = master_jabatan.id_jabatan');
		$this->db->where('id', $id);
		$result = $this->db->get()->row();

		return $result;
	}

	public function json($data = null)
	{
    	$this->output->set_header("Content-Type: application/json; charset=utf-8");
    	$this->output->set_content_type('application/json');
	    $this->output->set_output(json_encode($data));
	}

	public function getById($id)
	{
		$this->db->select('penilaian.*,master_grup.grup,master_grup.kode');
		$this->db->where($this->_primary_key,$id);
		$this->db->join('master_grup', 'penilaian.id_grup = master_grup.id_grup', 'left');
		$query = $this->db->get($this->_table);
		return $query->row();
	}

	public function validasiGrup($start,$end,$id)
	{
		$data = array('tanggal_akhir >=' => $start ,'id_grup' => $id);
		$this->db->where($data);
		$this->db->order_by('tanggal_akhir','desc');
		$query = $this->db->get($this->_table);
		return $query->result();
	}

	public function getAllKaryawanAjax()
	{
		$this->db->select("id, nama,status_kerja");
		$this->db->where('status_kerja', "aktif");
		$query = $this->db->get("master_karyawan");
		return $query->result();
	}

	public function getAllKaryawanAjaxSearch($search)
	{
		$this->db->select("id, nama,status_kerja");
		$this->db->where('status_kerja', "aktif");
		$this->db->like('nama', $search)->limit(10);
		$query = $this->db->get("master_karyawan");
		return $query->result();
	}

	public function getAllKaryawanAjaxGrup()
	{
		$query = $this->db->get("master_grup");
		return $query->result();
	}

	public function getAllKaryawanAjaxSearchGrup($search)
	{
		$this->db->like('grup', $search)->limit(10);
		$query = $this->db->get("master_grup");
		return $query->result();
	}

	public function insert($data)
	{

		if ($this->db->insert($this->_table,$data)) {
			return $this->db->insert_id();
		}
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
}

/* End of file penilaian_model.php */
/* Location: ./application/models/aktivitas/penilaian_model.php */
