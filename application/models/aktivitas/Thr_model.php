<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Thr_model extends CI_Model {

	public $_table;
	public $_primary_key;

	public function __construct()
	{
		parent::__construct();
		$this->_table = "aktivitas_thr";
		$this->_primary_key = "id_thr";
	}

	public function setQueryDataTable($search,$after=null,$before=null,$bulanLalu=false)
	{
		$dataSearch = array();
		foreach ($search as $val) {
			$dataSearch[$val] = $this->input->post("search")["value"];
		}
		$search = $dataSearch;
		$this->db->from($this->_table);
		if ($after && $before) {
			$this->db->where('tanggal >=', $before);
			$this->db->where('tanggal <=', $after);
		}
		if ($bulanLalu) {
			$this->db->where('month(tanggal) >=',date("m",strtotime("-1 months")));
		}
		$this->db->join('master_karyawan', 'master_karyawan.id = aktivitas_thr.id_karyawan','LEFT');
		// $this->db->join('master_golongan', 'master_golongan.id_golongan = master_karyawan.id_golongan','LEFT');
		$this->db->join('master_jabatan', 'master_jabatan.id_jabatan = master_karyawan.id_jabatan','LEFT');
		$this->db->join('master_departemen', 'master_departemen.id_departemen = master_karyawan.id_departemen','LEFT');
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
		self::setQueryDataTable($search,$after,$before);

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
		$this->db->select("aktivitas_penggajian.*,master_karyawan.idfp, master_karyawan.nama, master_karyawan.foto,master_golongan.*");
		$this->db->join('master_karyawan', 'aktivitas_penggajian.id_karyawan = master_karyawan.id');
		$this->db->join('master_golongan', 'master_karyawan.id_golongan = master_golongan.id_golongan');
		$this->db->where($this->_primary_key,$id);
		$query = $this->db->get($this->_table);
		return $query->row();
	}

	public function getAll($where=false,$select=false,$orderBy=false)
	{
		if ($where) {
			$this->db->where($where);
		}
		if ($select) {
			$select = is_array($select) ? implode(",", $select) : $select;
			$this->db->select($select);
		}
		if ($orderBy) {
			 $orderBy = is_array($orderBy) ? implode(",", $orderBy) : $orderBy;
			 $this->db->order_by($orderBy);
		}
		$this->db->join('master_golongan', 'master_golongan.id_golongan = master_karyawan.id_golongan', 'left');
		$query = $this->db->get("master_karyawan");
		return $query->result();

	}

	public function getDataLengkap($id)
	{
		$this->db->where($this->_table.'.id_thr',$id);
		$this->db->select('nama,idfp,aktivitas_thr.nilai,bank,cabang,jabatan,departemen,periode_gaji,tgl_masuk,rekening,golongan');
		$this->db->join('master_karyawan', 'master_karyawan.id = '.$this->_table.'.id_karyawan', 'left');
		$this->db->join('master_departemen', 'master_departemen.id_departemen = master_karyawan.id_departemen', 'left');
		$this->db->join('master_jabatan', 'master_jabatan.id_jabatan = master_karyawan.id_jabatan', 'left');
		$this->db->join('master_cabang', 'master_cabang.id_cabang = master_karyawan.id_cabang', 'left');
		$this->db->join('master_bank', 'master_bank.id_bank = master_karyawan.id_bank', 'left');
		$this->db->join('master_golongan', 'master_golongan.id_golongan = master_karyawan.id_golongan', 'left');
		$query = $this->db->get($this->_table);

		return $query->row();
	}

	public function insert($data)
	{
		$this->db->trans_start();

		$this->db->insert_batch($this->_table, $data);

		$this->db->trans_complete();
		if ($this->db->trans_status() === FALSE){
		    $this->db->trans_rollback();
		    return FALSE;
		} else {
		    $this->db->trans_commit();
		    return TRUE;
		}
	}

	public function golonganThr($id)
	{
		$this->db->join('master_golongan', 'master_golongan.id_golongan = master_karyawan.id_golongan', 'left');
		$this->db->where('master_karyawan.id', $id);
		$query = $this->db->get('master_karyawan');

		return $query->row();
	}

	public function validThr()
	{
		$this->db->where('tanggal',date("Y-m-d"));
		$query = $this->db->get("thr");

		return $query->result_array();
	}

}

/* End of file Thr.php */
/* Location: ./application/models/aktivitas/Thr_model.php */
