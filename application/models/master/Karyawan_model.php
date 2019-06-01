<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Karyawan_model extends CI_Model {

	public $_table;
	public $_primary_key;

	public function __construct()
	{
		parent::__construct();
		$this->_table = "master_karyawan";
		$this->_primary_key = "id";
	}

	public function setQueryDataTable($search,$where=false,$whereNotIn=false)
	{
		$dataSearch = array();
		foreach ($search as $val) {
			$dataSearch[$val] = $this->input->post("search")["value"];
		}
		$search = $dataSearch;
		$this->db->select('id, foto, idfp, nama, tempat_lahir, tgl_lahir, kelamin, telepon, master_jabatan.jabatan, alamat,	status_kerja, start_date,expired_date');
		$this->db->from($this->_table);
		if ($where) {
			$this->db->where($where);
		}
		if ($whereNotIn) {
			$this->db->where_not_in(array_keys($whereNotIn)[0],array_values($whereNotIn)[0]);
		}
		$this->db->group_start()->or_like($search)->group_end();
		$this->db->join('master_jabatan', 'master_jabatan.id_jabatan = master_karyawan.id_jabatan', 'left');

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

	public function setQueryDataTableKontrak($search,$where=false,$whereNotIn=false)
	{
		$dataSearch = array();
		foreach ($search as $val) {
			$dataSearch[$val] = $this->input->post("search")["value"];
		}
		$search = $dataSearch;
		$this->db->select('id, foto, idfp, nama, tempat_lahir, tgl_lahir, kelamin, telepon, master_jabatan.jabatan, alamat,	status_kerja, start_date,expired_date');
		$this->db->from($this->_table);
		if ($where) {
			$this->db->where($where);
		}
		if ($whereNotIn) {
			$this->db->where_not_in(array_keys($whereNotIn)[0],array_values($whereNotIn)[0]);
		}
		$this->db->group_start()->or_like($search)->group_end();
		$this->db->join('master_jabatan', 'master_jabatan.id_jabatan = master_karyawan.id_jabatan', 'left');

	}

	public function findDataTableKontrak($columnsOrderBy,$search,$where=false,$whereNotIn=false)
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
		self::setQueryDataTableKontrak($search,$where,$whereNotIn);

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

	public function findDataTableOutputKontrak($data=null,$search=false,$where=false,$whereNotIn=false)
	{
		$input = $this->input;

		// query data table
		self::setQueryDataTableKontrak($search,$where,$whereNotIn);

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

	public function getById($id,$select=false,$where=false,$row_array=false)
	{
		$selectData = 'master_karyawan.*, master_cabang.cabang, master_departemen.departemen, master_jabatan.jabatan, master_golongan.golongan, master_grup.grup, master_bank.bank';
		if ($select) {
			$selectData = $select;
		}
		$this->db->select($selectData);

		$whereData = array("master_karyawan.id" => $id);
		if ($where) {
			$whereData = $id;
		}
		$this->db->where($whereData);

		$this->db->join('master_cabang', 'master_cabang.id_cabang = master_karyawan.id_cabang', 'left');
		$this->db->join('master_departemen', 'master_departemen.id_departemen = master_karyawan.id_departemen', 'left');
		$this->db->join('master_jabatan', 'master_jabatan.id_jabatan = master_karyawan.id_jabatan', 'left');
		$this->db->join('master_golongan', 'master_golongan.id_golongan = master_karyawan.id_golongan', 'left');
		$this->db->join('master_grup', 'master_grup.id_grup = master_karyawan.id_grup', 'left');
		$this->db->join('master_bank', 'master_bank.id_bank = master_karyawan.id_bank', 'left');
		$query = $this->db->get($this->_table);

		if ($row_array) {
			return $query->row_array();
		} else {
			return $query->row();
		}
	}

	public function getByWhere($where)
	{
		$this->db->where($where);
		$query = $this->db->get($this->_table);
		return $query->row();
	}

	public function getCountKaryawan($where=false) // untuk hitung jumlah karyawan
	{
		if ($where) {
			$this->db->where($where);
		}
		$getCount = $this->db->count_all_results($this->_table);
		return $getCount;
	}

	public function getIdDetail($id)
	{
		$select = "kode_karyawan, idfp, master_karyawan.nama, master_karyawan.alamat, tempat_lahir, tgl_lahir, master_karyawan.telepon, no_wali, master_karyawan.email, tgl_masuk, kelamin, status_nikah, pendidikan, wn, agama, shift, status_kerja, ibu_kandung, suami_istri, tanggungan, npwp, master_karyawan.gaji, rekening, master_karyawan.id_bank, master_bank.bank, master_karyawan.id_departemen, master_departemen.departemen, master_karyawan.id_jabatan, master_jabatan.jabatan, master_karyawan.id_grup, master_grup.grup, master_karyawan.id_golongan, master_golongan.golongan, atas_nama, foto, master_karyawan.id_cabang, master_cabang.cabang, start_date, expired_date, jab_index, kontrak, file_kontrak, otoritas, periode_gaji, qrcode_file";
		return self::getById($id,$select,true,true);
	}

	public function kontrakQuery($sql)
	{
		return $this->db->query($sql)->result();
	}

	public function getByKode($kode)
	{
		$this->db->select("id, foto, file_kontrak, qrcode_file");
		$this->db->where(array("kode_karyawan" => $kode));
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

	public function getAll($where=false,$select=false,$orderBy=false,$result=true)
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
		if ($result) {
			return $query->result();
		} else {
			return $query->result_array();
		}
	}

	public function getAllKaryawanWhereInJadwal($whereIn=false,$select=false,$orderBy=false,$result=true) // untuk sisipan data karyawan di jadwal
	{
		$this->db->from($this->_table);
		if ($whereIn) {
			$this->db->where_in(array_keys($whereIn)[0],array_values($whereIn)[0]);
		}
		if ($select) {
			$select = is_array($select) ? implode(",", $select) : $select;
			$this->db->select($select);
		}
		if ($orderBy) {
		   $orderBy = is_array($orderBy) ? implode(",", $orderBy) : $orderBy;
		   $this->db->order_by($orderBy);
		}
		$this->db->join('master_grup', 'master_grup.id_grup = master_karyawan.id_grup');
		$query = $this->db->get();
		if ($result) {
			return $query->result();
		} else {
			return $query->result_array();
		}
	}

	public function getSelect2Ajax($table,$select,$orderBy)
	{
		$this->db->select($select);
		$this->db->order_by($orderBy);
		$query = $this->db->get($table);
		return $query->result();
	}

	public function getSelect2AjaxSearch($table,$select,$orderBy,$search)
	{
		$this->db->select($select);
		$this->db->order_by($orderBy);
		$this->db->like($search)->limit(10);
		$query = $this->db->get($table);
		return $query->result();
	}

	public function checkGroupKabag($idGroup)
	{
		$this->db->where(array("id_grup" => $idGroup, "otoritas" => 2)); // 2 = kabag
		$data = $this->db->get($this->_table);
		return $data->row();
	}

	public function periodeGaji($periode,$shift)
	{
		$this->db->select('kode_karyawan,nama,idfp,id');
		$where = array(
										'periode_gaji' => $periode,
										'shift' => $shift
									);
		$this->db->where($where);
		$data = $this->db->get($this->_table);
		return $data->result();
	}

	public function periodeGajiKaryawan($periode)
	{
		$this->db->select('kode_karyawan,nama,idfp,id,agama,shift,master_golongan.*');
		$where = array(
										'periode_gaji' => $periode,
										'status_kerja' => 'aktif'
									);
		$this->db->join('master_golongan', 'master_karyawan.id_golongan = master_golongan.id_golongan', 'left');
		$this->db->where($where);
		$data = $this->db->get($this->_table);
		return $data->result();
	}

	public function getAllKaryawanJadwal($id)
	{
		$this->db->select('id,kode_karyawan,periode_gaji,nama,idfp,agama,master_golongan.*');
		$where = array(
										'id' => $id
										// 'shift' => $shift
									);
		$this->db->where($where);
		$this->db->join('master_golongan', 'master_karyawan.id_golongan = master_golongan.id_golongan', 'left');
		$data = $this->db->get($this->_table);

		return $data->row();
	}

	public function getDataLengkap($id,$kode)
	{
		$where = array(
										'id' => $id,
										'kode_payroll' => $kode
									);
		$this->db->select('nama,golongan,idfp,jabatan,cabang,departemen,bank,rekening,agama,master_karyawan.shift,master_karyawan.tgl_masuk,aktivitas_penggajian.*');
		$this->db->join('aktivitas_penggajian', 'master_karyawan.id = aktivitas_penggajian.id_karyawan', 'left');
		$this->db->join('master_cabang', 'master_karyawan.id_cabang = master_cabang.id_cabang', 'left');
		$this->db->join('master_departemen', 'master_karyawan.id_departemen = master_departemen.id_departemen', 'left');
		$this->db->join('master_bank', 'master_karyawan.id_bank = master_bank.id_bank', 'left');
		$this->db->join('master_golongan', 'master_karyawan.id_golongan = master_golongan.id_golongan', 'left');
		$this->db->join('master_jabatan', 'master_karyawan.id_jabatan = master_jabatan.id_jabatan', 'left');
		$this->db->where($where);
		$query = $this->db->get($this->_table);

		return $query->row();
	}

	public function getDataKaryawan($id)
	{
		$this->db->select('id,kode_karyawan,periode_gaji,nama,idfp,agama,master_golongan.*');
		$where = array(
										'id' => $id
										// 'shift' => $shift
									);
		$this->db->where($where);
		$this->db->join('master_golongan', 'master_karyawan.id_golongan = master_golongan.id_golongan', 'left');
		$data = $this->db->get($this->_table);

		return $data->row();
	}

	public function lastAbsensi($kodeKaryawan) // for cuti approval
	{
		$this->db->where('kode', $kodeKaryawan);
		$this->db->order_by('tanggal', 'desc');
		$this->db->limit(1);
		$query = $this->db->get("absensi");
		return $query->result();
	}

	public function lastAbsensiJadwal($kodeKaryawan) // for cuti approval
	{
		$this->db->where('kode', $kodeKaryawan);
		$this->db->order_by('tanggal', 'desc');
		$this->db->where('id_jadwal !=', null);
		$this->db->limit(1);
		$query = $this->db->get("absensi");
		return $query->result();
	}

	public function tanggalJadwal($tanggal,$id_karyawan) // for cuti approval
	{
		$this->db->where(array("tanggal"=>$tanggal, "karyawan_id"=>$id_karyawan));
		$this->db->from('jadwal_token_karyawan');
		$this->db->join('master_shift', 'master_shift.id_shift = jadwal_token_karyawan.shift_id', 'left');
		$query = $this->db->get();

		return $query->row();
	}

}

/* End of file Karyawan_model.php */
/* Location: ./application/models/master/Karyawan_model.php */
