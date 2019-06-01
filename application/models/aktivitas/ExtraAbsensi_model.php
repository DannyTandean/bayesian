<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class ExtraAbsensi_model extends CI_Model {

	public $_table;
	public $_primary_key;

	public function __construct()
	{
		parent::__construct();
		$this->_table = "absensi_extra";
		$this->_primary_key = "id";
	}

	public function setQueryDataTable($search,$where=false,$before=null,$after=null)
	{
		$dataSearch = array();
		foreach ($search as $val) {
			$dataSearch[$val] = $this->input->post("search")["value"];
		}
		$search = $dataSearch;
		$this->db->from($this->_table);
		$this->db->select('absensi_extra.*, master_karyawan.idfp, master_karyawan.nama, master_jabatan.jabatan, master_jabatan.id_jabatan');
		$this->db->join('master_karyawan', 'absensi_extra.kode = master_karyawan.kode_karyawan',"LEFT");
		$this->db->join('master_jabatan', 'master_karyawan.id_jabatan = master_jabatan.id_jabatan',"LEFT");
		$this->db->where('master_karyawan.status_kerja', "aktif");
		$this->db->group_start()->or_like($search)->group_end();
		if ($before && $after) {
			$dataWhere = array(
														'absensi_extra.tanggal >=' => $before,
														'absensi_extra.tanggal <=' => $after
												);
			$this->db->where($dataWhere);
		}
		if ($where) {
			$this->db->where($where);
		}
	}

	public function findDataTable($columnsOrderBy,$search,$where=false,$before=null,$after=null)
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
		self::setQueryDataTable($search,$where,$before,$after);
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

	public function findDataTableOutput($data=null,$search=false,$where=false,$before=null,$after=null)
	{
		$input = $this->input;

		// query data table
		self::setQueryDataTable($search,$where,$before,$after);
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

		$this->db->select('id,nama,kode_karyawan');
		$this->db->from("master_karyawan");

		$result = $this->db->get()->result_array();
		return $result;
	}


	public function getKabag()
	{   $this->db->select('id,nama,kode_karyawan');
		$this->db->from("master_karyawan");
		$this->db->where("otoritas",2);

		$result = $this->db->get()->result_array();
		return $result;
	}

	public function getKabag1($kode)
	{   $this->db->select('nama,departemen,grup,telepon,foto');
		$this->db->from("master_karyawan");
		$this->db->join("master_departemen" , "master_karyawan.id_departemen = master_departemen.id_departemen" );
		$this->db->join("master_grup" , "master_karyawan.id_grup = master_grup.id_grup" );
		$this->db->where("kode_karyawan",$kode);

		$result = $this->db->get()->row();
		return $result;
	}

	public function getShift()
	{

		$this->db->select('id_shift,shift');
		$this->db->group_by("shift");
		$this->db->from("master_shift");

		$result = $this->db->get()->result_array();
		return $result;
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

	public function getById1($id,$select=false,$row_array=false)
	{
		$this->db->where('absensi_extra.id',$id);

		$selectData = "absensi_extra.*,tanggal,master_karyawan.kode_karyawan,master_karyawan.nama,
			master_jabatan.jabatan,absensi_extra.masuk,absensi_extra.keluar,ket_masuk,ket_keluar,master_jabatan.id_jabatan";
		if ($select) {
			$selectData = $select;
		}
		$this->db->select($selectData);
		$this->db->join('master_karyawan', 'absensi_extra.kode = master_karyawan.kode_karyawan');
		$this->db->join('master_jabatan', 'master_karyawan.id_jabatan = master_jabatan.id_jabatan');
		$query = $this->db->get($this->_table);
		if ($row_array) {
			return $query->row_array();
		} else {
			return $query->row();
		}
	}

public function GetNamaKaryawan($kode)
	{

		$this->db->select('nama,jabatan,idfp');

		$this->db->join("master_jabatan",'master_karyawan.id_jabatan = master_jabatan.id_jabatan');
		$this->db->where("kode_karyawan",$kode);
		$query = $this->db->get("master_karyawan");
		return $query->row();
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

	public function getAllJabatanAjax()
	{
		$this->db->select("id_jabatan, jabatan");
		$query = $this->db->get("master_jabatan");
		return $query->result_array();
	}


	public function getAllJabatanAjaxSearch($search)
	{
		$this->db->select("id_jabatan, jabatan");
		$this->db->like('jabatan', $search)->limit(10);
		$query = $this->db->get("master_jabatan");
		return $query->result();
	}

	public function getIdJabatan($nama)
	{
			$this->db->select("id_jabatan, jabatan");
			$this->db->where('jabatan',$nama);
			$query = $this->db->get("master_jabatan");
			return $query->result();
	}

		public function shiftGroup() // for dropdown shift
	{
		$this->db->group_by('shift');
		$data = $this->db->get('master_shift');
		return $data->result();
	}

	public function checkDataJadwal($tanggal,$kode) // check data jadwal yang sudah di input
	{
		$where = array(
						"tanggal"	=>	$tanggal,
						"kode"	=>	$kode
					);
		$this->db->where($where);
		$this->db->group_by(array("tanggal","kode"));
		$data = $this->db->get($this->_table);
		return $data->result();
	}

	public function shiftIdByName($nama_shift,$hari)
	{
		$where = array(
						"shift"	=>	$nama_shift,
						"hari"	=>	$hari
					);
		$this->db->where($where);
		$result = $this->db->get('master_shift')->row();
		return $result;
	}

	public function getByName($id)
	{
		$this->db->where("shift",$id);
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

	public function getExtraPenggajian($before,$after,$kode)
	{
		$where = array(
										'tanggal >=' => $before,
										'tanggal <=' => $after,
										'kode' => $kode
									);
		$this->db->where($where);
		$this->db->select_sum('total');
		$query = $this->db->get($this->_table);

		return $query->result();
	}

	public function getDataPerPeriode($before,$after,$kode)
	{
		$where = array(
										'tanggal >=' => $before,
										'tanggal <=' => $after,
										'kode' => $kode
									);
		$this->db->where($where);
		$query = $this->db->get($this->_table);

		return $query->result();
	}

	public function getDataPerPeriodeStatus($before,$after,$kode,$status)
	{
		$where = array(
										'tanggal >=' => $before,
										'tanggal <=' => $after,
										'kode' => $kode,
										'status' => $status
									);
		$this->db->where($where);
		$query = $this->db->get($this->_table);

		return $query->result();
	}

	public function updatePayment($before,$after,$kode)
	{
		$where = array(
										'tanggal >=' => $before,
										'tanggal <=' => $after,
										'kode' => $kode,
										'payment' => 0
									);
		$data = array(
										'payment' => 1
								 );
		$this->db->where($where);

		return $this->db->update($this->_table,$data);
	}

}

/* End of file ExtraAbsensi_model.php */
/* Location: ./application/models/aktivitas/ExtraAbsensi_model.php */
