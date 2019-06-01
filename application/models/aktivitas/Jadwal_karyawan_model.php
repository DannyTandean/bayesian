<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Jadwal_karyawan_model extends CI_Model {

	public $_table;
	public $_primary_key;

	public function __construct()
	{
		parent::__construct();
		$this->_table = "aktivitas_jadwal";
		$this->_primary_key = "id_jadwal";
	}

	public function setQueryDataTable($search)
	{
		$dataSearch = array();
		foreach ($search as $val) {
			$dataSearch[$val] = $this->input->post("search")["value"];
		}
		$search = $dataSearch;
		$this->db->from($this->_table);

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

	public function findDataTableOutput($data=null,$search)
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

	public function getById($id)
	{
		$this->db->where($this->_primary_key,$id);
		$this->db->from($this->_table);
		$query = $this->db->get();
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

	public function groupKaryawan()	// for dropdown group
	{
		$where = array(
						"master_karyawan.shift"		=>	"ya",
						"status_kerja"				=>	"aktif",
						"otoritas !="				=>	4, // security
					);
		$this->db->select('master_karyawan.id_grup, master_grup.grup');
		$this->db->group_by('master_karyawan.id_grup');
		$this->db->from('master_karyawan');
		$this->db->where($where);
		$this->db->join('master_grup', 'master_grup.id_grup = master_karyawan.id_grup', 'left');
		$data = $this->db->get();
		return $data->result();
	}

	public function dataKaryawanGroup($idGroup)	// data karyawan per group
	{
		$where = array(
						"master_karyawan.id_grup" 	=> 	$idGroup,
						"master_karyawan.shift"		=>	"ya",
						"status_kerja"				=>	"aktif",
					);
		$this->db->select('master_karyawan.id, master_karyawan.id_grup, master_karyawan.nama, master_karyawan.kode_karyawan, master_karyawan.tgl_lahir, master_karyawan.otoritas, master_grup.grup');
		$this->db->from('master_karyawan');
		$this->db->where($where);
		$this->db->where_not_in('otoritas', array(2,4)); // batasan untuk kabag dan security
		$this->db->join('master_grup', 'master_grup.id_grup = master_karyawan.id_grup', 'left');
		$data = $this->db->get();
		return $data->result();
	}

	public function getidJadwal()
	{
		$query = $this->db->get($this->_table);

		return $query->row();
	}

	public function shiftGroup() // for dropdown shift
	{
		$this->db->group_by('shift');
		$this->db->where(array("shift !=" => "REGULAR"));
		$data = $this->db->get('master_shift');
		return $data->result();
	}

	public function shiftIdByName($namaShift,$hari)
	{
		$this->db->where(array("shift"=>$namaShift,"hari"=>$hari));
		$data = $this->db->get('master_shift');
		return $data->row();
	}

	public function checkDataJadwal($tanggal,$group_id) // check data jadwal yang sudah di input
	{
		$where = array(
						"tanggal"	=>	$tanggal,
						"group_id"	=>	$group_id,
						// "nama_shift"	=>	$nama_shift,
					);
		$this->db->where($where);
		$this->db->group_by(array("tanggal","group_id"));
		$data = $this->db->get($this->_table);
		return $data->result();
	}

	public function getData($tanggal,$group_id,$namaShift=false)
	{
		$this->db->select('jadwal_token_karyawan.*, master_grup.grup');
		$where = array("tanggal"=>$tanggal, "group_id"=>$group_id);
		if ($namaShift) {
			$where["nama_shift"]	=	$namaShift;
		}
		$this->db->where($where);
		$this->db->from($this->_table);
		$this->db->join('master_grup', 'master_grup.id_grup = jadwal_token_karyawan.group_id', 'left');
		$data = $this->db->get();
		return $data->row();
	}

	public function getDataSama($tanggal,$group_id,$namaShift=false,$result=false)
	{
		// ada perubahan yaitu tambah field otoritas di table dan tak perlu join ke table master_karyawan lagi.
		$this->db->select('jadwal_token_karyawan.*, master_grup.grup');
		$where = array("tanggal"=>$tanggal, "group_id"=>$group_id);
		if ($namaShift) {
			$where["nama_shift"]	=	$namaShift;
		}
		$this->db->where($where);
		$this->db->where_not_in('otoritas', array(2,4)); // pengecualian otoritas kabag dan security
		$this->db->from($this->_table);
		$this->db->join('master_karyawan', 'master_karyawan.id = jadwal_token_karyawan.karyawan_id', 'left');
		$this->db->join('master_grup', 'master_grup.id_grup = jadwal_token_karyawan.group_id', 'left');
		$data = $this->db->get();
		if ($result) {
			return $data->result();
		} else {
			return $data->row();
		}
	}

	public function getAllJadwal()
	{
		$query = $this->db->get($this->_table);

		return $query->result();
	}

	public function insertBatchTransaction($data)
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

	public function updateBatchTransaction($tanggal,$group_id,$data)
	{
		$this->db->trans_start();

			$this->db->delete($this->_table, array("tanggal"=>$tanggal, "group_id"=>$group_id));

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

	public function deleteTransaction($tanggal,$group_id)
	{
		$this->db->trans_start();

			$this->db->where(array("tanggal"=>$tanggal, "group_id"=>$group_id));
			$this->db->delete($this->_table);

		$this->db->trans_complete();
		if ($this->db->trans_status() === FALSE){
		    $this->db->trans_rollback();
		    return FALSE;
		} else {
		    $this->db->trans_commit();
		    return TRUE;
		}
	}

	/*for jumlah karyawan*/
	public function setQueryDataTableKaryawan($where,$search)
	{
		$dataSearch = array();
		foreach ($search as $val) {
			$dataSearch[$val] = $this->input->post("search")["value"];
		}
		$search = $dataSearch;
		$this->db->select('jadwal_token_karyawan.id, jadwal_token_karyawan.tanggal, jadwal_token_karyawan.karyawan_id, jadwal_token_karyawan.ganti_karyawan_id, jadwal_token_karyawan.nama_shift, jadwal_token_karyawan.otoritas_kerja, idfp, nama, tgl_lahir, kelamin, otoritas, master_jabatan.jabatan, kode_karyawan');
		$this->db->from($this->_table);
		$this->db->where($where);
		$this->db->group_start()->or_like($search)->group_end();
		$this->db->join('master_karyawan', 'master_karyawan.id = jadwal_token_karyawan.karyawan_id', 'left');
		$this->db->join('master_jabatan', 'master_jabatan.id_jabatan = master_karyawan.id_jabatan', 'left');

	}

	public function findDataTableKaryawan($where,$columnsOrderBy,$search)
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
		self::setQueryDataTableKaryawan($where,$search);

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

	public function findDataTableOutputKaryawan($where,$data=null,$search=false)
	{
		$input = $this->input;

		// query data table
		self::setQueryDataTableKaryawan($where,$search);

		$getCount = $this->db->count_all_results();

		$response = new stdClass();
		$response->draw = !empty($input->post("draw")) ? $input->post("draw"):null;
		$response->recordsTotal = $getCount;
		$response->recordsFiltered = $getCount;
		$response->data = $data;

		self::json($response);
	}

	public function checkAbsensiHariIni($tanggal,$kode_karyawan)
	{
		$this->db->where(array("tanggal"=>$tanggal,"kode"=>$kode_karyawan));
		$data = $this->db->get('absensi');
		return $data->row();
	}

	public function getByIdGanti($id)
	{
		$this->db->select('keterangan');
		$this->db->where($this->_primary_key,$id);
		$query = $this->db->get('jadwal_token_ganti');
		return $query->row();
	}

	public function getAllKaryawanAjax($otoritas=false)
	{
		$this->db->select("id, nama");
		if ($otoritas) {
			$this->db->where(array("otoritas" => $otoritas));
		}
		$query = $this->db->get("master_karyawan");
		return $query->result();
	}

	public function getAllKaryawanAjaxSearch($search,$otoritas=false)
	{
		$this->db->select("id, nama");
		if ($otoritas) {
			$this->db->where(array("otoritas" => $otoritas));
		}
		$this->db->like('nama', $search)->limit(10);
		$query = $this->db->get("master_karyawan");
		return $query->result();
	}

	public function karyawanId($id)
	{
		$this->db->select("foto, nama, idfp, tgl_lahir, kelamin, master_karyawan.id_grup, master_jabatan.jabatan, master_grup.grup");
		$this->db->where(array("master_karyawan.id" => $id));
		$this->db->from('master_karyawan');
		$this->db->join('master_jabatan', 'master_jabatan.id_jabatan = master_karyawan.id_jabatan', 'left');
		$this->db->join('master_grup', 'master_grup.id_grup = master_karyawan.id_grup', 'left');
		$data = $this->db->get();
		return $data->row();
	}

	public function checkIdjadwalInGanti($idJadwalKaryawan)	// check data jadwal_token_ganti per id_jadwal_token
	{
		$this->db->where(array("id_jadwal_token"=>$idJadwalKaryawan));
		$data = $this->db->get("jadwal_token_ganti");
		return $data->row();
	}

	public function checkDuplicateKaryawanGanti($tgl,$groupId,$shiftId,$karyawanId)
	{
		$this->db->where(array("tanggal"=>$tgl,"group_id"=>$groupId,"shift_id"=>$shiftId,"karyawan_id"=>$karyawanId));
		$data = $this->db->get($this->_table);
		return $data->row();
	}

	public function insertGantiKaryawan($idJadwalKaryawan,$dataKaryawan,$dataGantiKaryawan) // for jadwal ganti karyawan
	{
		$this->db->trans_start();

		$this->db->where(array($this->_primary_key=>$idJadwalKaryawan));
		$this->db->update($this->_table,$dataKaryawan);

		$this->db->insert("jadwal_token_ganti", $dataGantiKaryawan);

		$this->db->trans_complete();
		if ($this->db->trans_status() === FALSE){
		    $this->db->trans_rollback();
		    return FALSE;
		} else {
		    $this->db->trans_commit();
		    return TRUE;
		}
	}

	public function updateGantiKaryawan($idJadwalKaryawan,$dataKaryawan,$dataGantiKaryawan) // for jadwal ganti karyawan
 	{
		$this->db->trans_start();

		$where =  array($this->_primary_key=>$idJadwalKaryawan);
		$this->db->update($this->_table,$dataKaryawan, $where);

		if ($dataKaryawan["ganti_karyawan_id"] == null || $dataKaryawan["ganti_karyawan_id"] == "null" || $dataKaryawan["ganti_karyawan_id"] == "" ) {
			$this->db->delete('jadwal_token_ganti', array("id_jadwal_token" => $idJadwalKaryawan));
		} else {
			$this->db->update("jadwal_token_ganti", $dataGantiKaryawan, array("id_jadwal_token" => $idJadwalKaryawan));
		}

		$this->db->trans_complete();
		if ($this->db->trans_status() === FALSE){
		    $this->db->trans_rollback();
		    return FALSE;
		} else {
		    $this->db->trans_commit();
		    return TRUE;
		}
	}

	public function checkGroupKabag($idGroup)
	{
		$this->db->where(array("id_grup"=>$idGroup,"otoritas" => 2)); // otoritas kabag
		$data = $this->db->get('master_karyawan');
		return $data->row();
	}

	public function checkGroupKabagJadwal($tgl,$idGroup)
	{
		$this->db->select('jadwal_token_karyawan.*, master_karyawan.nama, master_karyawan.idfp, master_karyawan.otoritas');
		$this->db->where(array("tanggal"=>$tgl,"group_id"=>$idGroup,"otoritas" => 2)); // otoritas kabag
		$this->db->from($this->_table);
		$this->db->join('master_karyawan', 'master_karyawan.id = jadwal_token_karyawan.karyawan_id', 'left');
		$data = $this->db->get();
		return $data->row();
	}

	/*For Security*/
	public function setQueryDataTableSecurity($select,$where,$search)
	{
		$this->db->select($select);
		$dataSearch = array();
		foreach ($search as $val) {
			$dataSearch[$val] = $this->input->post("search")["value"];
		}
		$search = $dataSearch;
		$this->db->from($this->_table);
		$this->db->where($where);
		$this->db->group_start()->or_like($search)->group_end();
		$this->db->join('master_karyawan', 'master_karyawan.id = jadwal_token_karyawan.karyawan_id', 'left');
		$this->db->join('master_jabatan', 'master_jabatan.id_jabatan = master_karyawan.id_jabatan', 'left');
	}

	public function findDataTableSecurity($select,$where,$columnsOrderBy,$search)
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
		self::setQueryDataTableSecurity($select,$where,$search);

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

	public function findDataTableOutputSecurity($data=null,$select,$where,$search)
	{
		$input = $this->input;

		// query data table
		self::setQueryDataTableSecurity($select,$where,$search);

		$getCount = $this->db->count_all_results();

		$response = new stdClass();
		$response->draw = !empty($input->post("draw")) ? $input->post("draw"):null;
		$response->recordsTotal = $getCount;
		$response->recordsFiltered = $getCount;
		$response->data = $data;

		self::json($response);
	}

	public function dataSecurity($tanggal,$karyawanId)
	{
		$this->db->where(array("tanggal"=>$tanggal,"karyawan_id" => $karyawanId));
		$data = $this->db->get($this->_table);
		return $data->row();
	}

	public function all($shift=null)
	{
		$this->db->where('shift',$shift);

		$query = $this->db->get($this->_table);

		return $query->result();
	}

	public function cekTime($keluar,$akhirKeluar)
	{
		$data = array(
										'keluar >=' => $keluar,
										'akhir_keluar <=' => $akhirKeluar
								 );
		$this->db->where($data);
		$query = $this->db->get($this->_table);

		return $query->num_rows();
	}


	public function cekTimeShiftId($keluar,$akhirKeluar)
	{
		$data = array(
										'keluar >=' => $keluar,
										'akhir_keluar <=' => $akhirKeluar
								 );
		$this->db->where($data);
		$query = $this->db->get($this->_table);

		return $query->row();
	}

}

/* End of file Jadwalkerja_model.php */
/* Location: ./application/models/aktivitas/Jadwalkerja_model.php */
