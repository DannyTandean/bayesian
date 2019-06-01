<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Pekerja_model extends CI_Model {

	public $_table;
	public $_primary_key;

	public function __construct()
	{
		parent::__construct();
		$this->_table = "borongan_pekerja";
		$this->_primary_key = "id_pekerja";
	}

	public function setQueryDataTable($search,$where=false,$whereNotIn=false)
	{
		$dataSearch = array();
		foreach ($search as $val) {
			$dataSearch[$val] = $this->input->post("search")["value"];
		}
		$search = $dataSearch;
		$this->db->select('id_pekerja, idfp, foto, nama, tempat_lahir, tgl_lahir, kelamin, telepon, alamat');
		$this->db->from($this->_table);
		// if ($where) {
		// 	$this->db->where($where);
		// }
		// if ($whereNotIn) {
		// 	$this->db->where_not_in(array_keys($whereNotIn)[0],array_values($whereNotIn)[0]);
		// }
		$this->db->group_start()->or_like($search)->group_end();
		// $this->db->join('borongan_departemen', 'borongan_departemen.id_departemen = borongan_pekerja.id_departemen', 'left');

	}

	public function findDataTable($columnsOrderBy,$search,$where=false,$whereNotIn=false)
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
		self::setQueryDataTable($search,$where,$whereNotIn);

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

	public function findDataTableOutput($data=null,$search=false,$where=false,$whereNotIn=false)
	{
		$input = $this->input;

		// query data table
		self::setQueryDataTable($search,$where,$whereNotIn);

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

	public function getCountKaryawan() // untuk hitung jumlah karyawan
	{
		$getCount = $this->db->count_all_results($this->_table);
		return $getCount;
	}

	public function insert($data)
	{
		if ($this->db->insert($this->_table,$data)) {
			return $this->db->insert_id();
		}
	}

	public function getById($id,$select=false,$where=false,$row_array=false)
	{
		$selectData = 'borongan_pekerja.*,master_bank.bank';
		if ($select) {
			$selectData = $select;
		}
		$this->db->select($selectData);

		$whereData = array("borongan_pekerja.id_pekerja" => $id);
		if ($where) {
			$whereData = $id;
		}
		$this->db->where($whereData);
		// $this->db->join('borongan_departemen', 'borongan_departemen.id_departemen = borongan_pekerja.id_departemen', 'left');
		$this->db->join('master_bank', 'master_bank.id_bank = borongan_pekerja.id_bank', 'left');
		$query = $this->db->get($this->_table);

		if ($row_array) {
			return $query->row_array();
		} else {
			return $query->row();
		}
	}

	public function getIdDetail($id)
	{
		$select = "kode_karyawan, idfp, borongan_pekerja.nama, borongan_pekerja.alamat, tempat_lahir, tgl_lahir, borongan_pekerja.telepon, tgl_masuk, kelamin, rekening, borongan_pekerja.id_bank, master_bank.bank, borongan_pekerja.id_departemen, borongan_departemen.departemen";
		return self::getById($id,$select,true,true);
	}


	public function getDataLengkap($id,$kode)
	{
		$where = array(
										'borongan_penggajian.id_pekerja' => $id,
										'kode_payroll' => $kode
									);
		// $this->db->select('nama,idfp,bank,rekening,borongan_penggajian.*');
		$this->db->join('borongan_pekerja', 'borongan_penggajian.id_pekerja = borongan_pekerja.id_pekerja', 'left');
		$this->db->join('borongan_produksi', 'borongan_penggajian.id_pekerja = borongan_produksi.id_pekerja', 'left');
		// $this->db->join('borongan_departemen', 'borongan_produksi.id_departemen = borongan_departemen.id_departemen', 'left');
		$this->db->join('master_bank', 'borongan_pekerja.id_bank = master_bank.id_bank', 'left');
		$this->db->where($where);
		$query = $this->db->get('borongan_penggajian');

		return $query->row();
	}

	public function periodeGaji($periode)
	{
		$this->db->select('nama,idfp,id_pekerja');
		$where = array(
										'periode_gaji' => $periode,
									);
		$this->db->where($where);
		$data = $this->db->get($this->_table);
		return $data->result();
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
		$query = $this->db->get($this->_table);
		return $query->result();

	}



	public function bankDataAll()
	{
		$this->db->select('id_bank,bank');
		$this->db->group_by("bank");
		$this->db->from("master_bank");

		$result = $this->db->get()->result_array();
		return $result;
	}



	// public function departemenDataAll()
	// {

	// 	$this->db->select('id_departemen,departemen');
	// 	$this->db->group_by("departemen");
	// 	$this->db->from("borongan_departemen");

	// 	$result = $this->db->get()->result_array();
	// 	return $result;
	// }

	public function getDataPekerja($id)
	{
		$this->db->select('*');
		$where = array(
										'borongan_pekerja.id_pekerja' => $id

									);
		$this->db->where($where);
		// $this->db->join('borongan_departemen', 'borongan_departemen.id_departemen = borongan_produksi.id_departemen');
		$this->db->join('borongan_produksi', 'borongan_produksi.id_pekerja = borongan_pekerja.id_pekerja');
		$data = $this->db->get($this->_table);

		return $data->row();
	}

}

/* End of file Karyawan_model.php */
/* Location: ./application/models/master/Karyawan_model.php */
