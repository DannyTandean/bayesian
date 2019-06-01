<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Absensitdkterjadwal_model extends CI_Model {

	public $_table;
	public $_primary_key;

	public function __construct()
	{
		parent::__construct();
		$this->_table = "absensi";
		$this->_primary_key = "id_absensi";
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
		$this->db->join('master_karyawan', 'master_karyawan.kode_karyawan = '.$this->_table.'.kode');
		$this->db->join('master_jabatan', 'master_jabatan.id_jabatan = master_karyawan.id_jabatan');
		$this->db->group_start()->or_like($search)->group_end();
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

	public function getWhere($dataWhere)
	{
		$this->db->select('absensi.*, master_karyawan.idfp, master_karyawan.foto, master_karyawan.nama, master_jabatan.jabatan');
		$this->db->from($this->_table);
		$this->db->where($dataWhere);
		$this->db->join('master_karyawan', 'master_karyawan.kode_karyawan = '.$this->_table.'.kode', 'left');
		$this->db->join('master_jabatan', 'master_jabatan.id_jabatan = master_karyawan.id_jabatan', 'left');
		$data = $this->db->get();
		return $data->row();
	}

	public function getById($id)
	{
		$this->db->where($this->_primary_key,$id);
		$this->db->select("absensi.*,master_karyawan.id,master_karyawan.kode_karyawan, master_karyawan.nama,master_karyawan.shift ,master_karyawan.foto, master_departemen.departemen, master_jabatan.jabatan");
		$this->db->join('master_karyawan', 'absensi.kode = master_karyawan.kode_karyawan');
		$this->db->join('master_departemen', 'master_karyawan.id_departemen = master_departemen.id_departemen');
		$this->db->join('master_jabatan', 'master_karyawan.id_jabatan = master_jabatan.id_jabatan');
		$query = $this->db->get($this->_table);
		return $query->row();
	}

	public function getJadwalId($id)
	{
		$this->db->where("id_jadwal", $id);
		$this->db->select('masuk,keluar');
		$query = $this->db->get("aktivitas_jadwal");
		return $query->row();
	}

	public function update($id,$data) // for reject
	{
		$this->db->where($this->_primary_key,$id);
		return $this->db->update($this->_table,$data);
	}

	public function approvalTransaction($id,$data) // for approval
	{
		$this->db->trans_start();

			$this->db->update('temp_absensi', array("status" => "Diterima"), array($this->_primary_key => $id)); // temp_absensi update

			$this->db->insert('absensi', $data);

		$this->db->trans_complete();
		if ($this->db->trans_status() === FALSE){
		    $this->db->trans_rollback();
		    return FALSE;
		} else {
		    $this->db->trans_commit();
		    return TRUE;
		}
	}

	public function getKabag($kode)
	{   $this->db->select('nama,departemen,grup,telepon,foto');
		$this->db->from("master_karyawan");
		$this->db->join("master_departemen" , "master_karyawan.id_departemen = master_departemen.id_departemen" );
		$this->db->join("master_grup" , "master_karyawan.id_grup = master_grup.id_grup" );
		$this->db->where("kode_karyawan",$kode);

		$result = $this->db->get()->row();
		return $result;
	}

	public function getJadwal($id)
	{
		$query = $this->db->get('aktivitas_jadwal');
		$this->db->select('nama_jadwal');
		return $query->row();
	}

}

/* End of file Tmp_absensi_model.php */
/* Location: ./application/models/approval/Tmp_absensi_model.php */
