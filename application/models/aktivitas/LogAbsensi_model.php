<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class LogAbsensi_model extends CI_Model {

	public $_table;
	public $_primary_key;

	public function __construct()
	{
		parent::__construct();
		$this->_table = "absensi";
		$this->_primary_key = "id_absensi";
	}

	public function setQueryDataTable($search,$where=false,$whereIn=false,$before=null,$after=null)
	{
		$dataSearch = array();
		foreach ($search as $val) {
			$dataSearch[$val] = $this->input->post("search")["value"];
		}
		$search = $dataSearch;
		$this->db->from($this->_table);
		$this->db->select('absensi.*,tanggal, master_karyawan.idfp, master_karyawan.nama,aktivitas_jadwal.nama_jadwal,
			master_jabatan.jabatan,absensi.masuk,absensi.break_out,absensi.break_in,absensi.keluar,telat,ket_masuk,ket_keluar,master_jabatan.id_jabatan,master_karyawan.status_kerja');
		$this->db->join('master_karyawan', 'absensi.kode = master_karyawan.kode_karyawan','left');
		$this->db->join('master_jabatan', 'master_karyawan.id_jabatan = master_jabatan.id_jabatan','left');
		$this->db->join('aktivitas_jadwal', 'absensi.id_jadwal = aktivitas_jadwal.id_jadwal','left');
		$this->db->where('status_kerja', "aktif");
		$this->db->group_start()->or_like($search)->group_end();
		if ($before && $after) {
			$dataWhere = array(
														'absensi.tanggal >=' => $before,
														'absensi.tanggal <=' => $after
												);
			$this->db->where($dataWhere);
		}
		if ($where) {
			$this->db->where($where);
		}
		if ($whereIn) {
			$this->db->where_in(array_keys($whereIn)[0],array_values($whereIn)[0]);
		}
	}

	public function findDataTable($columnsOrderBy,$search,$where=false,$whereIn=false,$before=null,$after=null)
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
		self::setQueryDataTable($search,$where,$whereIn,$before,$after);
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

	public function findDataTableOutput($data=null,$search=false,$where=false,$whereIn=false,$before=null,$after=null)
	{
		$input = $this->input;

		// query data table
		self::setQueryDataTable($search,$where,$whereIn,$before,$after);
		$getCount = $this->db->count_all_results();

		$response = new stdClass();
		$response->draw = !empty($input->post("draw")) ? $input->post("draw"):null;
		$response->recordsTotal = $getCount;
		$response->recordsFiltered = $getCount;
		$response->data = $data;

		self::json($response);
	}

	public function setQueryDataTableDashboard($search,$where=false,$whereIn=false)
	{
		$dataSearch = array();
		foreach ($search as $val) {
			$dataSearch[$val] = $this->input->post("search")["value"];
		}
		$search = $dataSearch;
		$this->db->from($this->_table);
		$this->db->select('absensi.*, master_karyawan.idfp, master_karyawan.nama, master_jabatan.jabatan, master_jabatan.id_jabatan, aktivitas_jadwal.nama_jadwal,master_karyawan.status_kerja');
		$this->db->join('master_karyawan', 'absensi.kode = master_karyawan.kode_karyawan');
		$this->db->join('master_jabatan', 'master_karyawan.id_jabatan = master_jabatan.id_jabatan');
		$this->db->join('aktivitas_jadwal', 'absensi.id_jadwal = aktivitas_jadwal.id_jadwal','left');
		$this->db->where('status_kerja', "aktif");
		$this->db->group_start()->or_like($search)->group_end();
		if ($where) {
			$this->db->where($where);
		}
		if ($whereIn) {
			$this->db->where_in(array_keys($whereIn)[0],array_values($whereIn)[0]);
		}
	}

	public function findDataTableDashboard($columnsOrderBy,$search,$where=false,$whereIn=false)
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
		self::setQueryDataTableDashboard($search,$where,$whereIn);
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

	public function findDataTableOutputDashboard($data=null,$search=false,$where=false,$whereIn=false)
	{
		$input = $this->input;

		// query data table
		self::setQueryDataTableDashboard($search,$where,$whereIn);
		$getCount = $this->db->count_all_results();

		$response = new stdClass();
		$response->draw = !empty($input->post("draw")) ? $input->post("draw"):null;
		$response->recordsTotal = $getCount;
		$response->recordsFiltered = $getCount;
		$response->data = $data;

		self::json($response);
	}

	public function setQueryDataTableDashboardIzin($search,$where=false,$whereIn=false)
	{
		$dataSearch = array();
		foreach ($search as $val) {
			$dataSearch[$val] = $this->input->post("search")["value"];
		}
		$search = $dataSearch;
		$this->db->from("aktivitas_izin");
		$this->db->join('master_karyawan', 'aktivitas_izin.id_karyawan = master_karyawan.id', 'left');
		$this->db->join('master_jabatan', 'master_karyawan.id_jabatan = master_jabatan.id_jabatan');
		// $this->db->where('status_kerja', "aktif");
		$this->db->group_start()->or_like($search)->group_end();
		if ($where) {
			$this->db->where($where);
		}
		if ($whereIn) {
			$this->db->where_in(array_keys($whereIn)[0],array_values($whereIn)[0]);
		}
	}

	public function findDataTableDashboardIzin($columnsOrderBy,$search,$where=false,$whereIn=false)
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
		self::setQueryDataTableDashboardIzin($search,$where,$whereIn);
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

	public function findDataTableOutputDashboardIzin($data=null,$search=false,$where=false,$whereIn=false)
	{
		$input = $this->input;

		// query data table
		self::setQueryDataTableDashboardIzin($search,$where,$whereIn);
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

	public function getShiftKaryawan($kode)
	{
		$this->db->where('kode_karyawan', $kode);

		$query = $this->db->get('master_karyawan');

		return $query->row();
	}

	public function getShiftJadwal($shift)
	{
		$this->db->where('shift', $shift);
		$query = $this->db->get('aktivitas_jadwal');

		return $query->result();
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

		$this->db->select('id_jadwal,nama_jadwal');
		$this->db->group_by("nama_jadwal");
		$this->db->from("aktivitas_jadwal");

		$result = $this->db->get()->result_array();
		return $result;
	}

	public function getByIdJadwal($id_jadwal)
	{
		$this->db->where("id_jadwal",$id_jadwal);
		$query = $this->db->get("aktivitas_jadwal");
		return $query->row();
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

	public function getById($id,$select=false,$row_array=false)
	{
		$this->db->where($this->_primary_key,$id);

		$selectData = "absensi.*,tanggal, master_karyawan.kode_karyawan, master_karyawan.nama, master_karyawan.idfp,
			master_jabatan.jabatan,aktivitas_jadwal.nama_jadwal,absensi.masuk,absensi.break_out,absensi.break_in,absensi.keluar,telat,ket_masuk,ket_keluar,master_jabatan.id_jabatan,aktivitas_jadwal.id_jadwal";
		if ($select) {
			$selectData = $select;
		}
		$this->db->select($selectData);
		$this->db->join('master_karyawan', 'absensi.kode = master_karyawan.kode_karyawan','left');
		$this->db->join('master_jabatan', 'master_karyawan.id_jabatan = master_jabatan.id_jabatan','left');
		$this->db->join('aktivitas_jadwal', 'absensi.id_jadwal = aktivitas_jadwal.id_jadwal','left');
		$query = $this->db->get($this->_table);
		if ($row_array) {
			return $query->row_array();
		} else {
			return $query->row();
		}
	}

	public function getGolonganByKode($kode)
	{
		$this->db->where('kode_karyawan', $kode);
		// $this->db->join('master_karyawan', 'absensi.kode = master_karyawan.kode_karyawan', 'left');
		$this->db->join('master_golongan', 'master_karyawan.id_golongan = master_golongan.id_golongan', 'left');
		$query = $this->db->get('master_karyawan');

		return $query->row();
	}

	public function getmaxjamKerja($id)
	{
		$this->db->where('id_jadwal', $id);
		$query = $this->db->get('aktivitas_jadwal');

		return $query->row();
	}

	public function GetNamaKaryawan($kode)
	{

		$this->db->select('nama,jabatan,idfp,kode_karyawan');
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
		$this->db->group_by('nama_jadwal');
		$data = $this->db->get('aktivitas_jadwal');
		return $data->result();
	}

	public function checkDataAbsensiKaryawanJadwal($tanggal,$kode) // check data jadwal yang sudah di input
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

	public function shiftIdByName($nama_shift)
	{
		$where = array(
						"nama_jadwal"	=>	$nama_shift,

					);
		$this->db->where($where);
		$result = $this->db->get('aktivitas_jadwal')->row();
		return $result;
	}

	public function getByName($id)
	{
		$this->db->where("nama_jadwal",$id);
		$query = $this->db->get($this->_table);
		return $query->row();
	}

	public function perBulan($start,$end,$where,$shift)
	{
		// $this->db->where('tanggal >=', $start);
		// $this->db->where('tanggal <=', $end);
		if ($shift == null) {
			$data = array(
				'tanggal >=' => $start,
				'tanggal <=' => $end,
				'kode' => $where,
				'status' => "Diterima",
				'lupa_keluar_kabag !=' => 1
			);
		}
		else {
			$data = array(
				'tanggal >=' => $start,
				'tanggal <=' => $end,
				'kode' => $where,
				'isshift' => $shift,
				'status' => "Diterima",
				'lupa_keluar_kabag !=' => 1
			);
		}
		$this->db->where($data);
		$this->db->select_sum('token');
		$query = $this->db->get($this->_table);

		return $query->result();
	}

	public function DataPerBulan($start,$end,$where,$shift)
	{
		if ($shift == null) {
			$data = array(
										'tanggal >=' => $start,
										'tanggal <=' => $end,
										'kode' => $where,
										'status' => "Diterima",
										'lupa_keluar_kabag !=' => 1,
										'absensi.keluar !=' => null
									);
		}
		else {
			$data = array(
										'tanggal >=' => $start,
										'tanggal <=' => $end,
										'kode' => $where,
										'isshift' => $shift,
										'status' => "Diterima",
										'lupa_keluar_kabag !=' => 1,
										'absensi.keluar !=' => null
									);
		}
		$this->db->select('absensi.*,aktivitas_jadwal.id_jadwal,nama_jadwal,max_jam_kerja');
		$this->db->join('aktivitas_jadwal', 'absensi.id_jadwal = aktivitas_jadwal.id_jadwal');
		$this->db->order_by("tanggal", "desc");
		$this->db->where($data);
		$query = $this->db->get($this->_table);

		return $query->result();
	}

	public function DataPerBulanShift($start,$end,$where,$shift)
	{
		if ($shift == null) {
			$data = array(
										'tanggal >=' => $start,
										'tanggal <=' => $end,
										// "absensi.keluar <=" => $checkout,
										'kode' => $where,
										'status' => "Diterima",
										'lupa_keluar_kabag !=' => 1,
										'absensi.keluar !=' => null
									);
		}
		else {
			$data = array(
										'tanggal >=' => $start,
										'tanggal <=' => $end,
										// "absensi.keluar <=" => $checkout,
										'kode' => $where,
										'isshift' => $shift,
										'status' => "Diterima",
										'lupa_keluar_kabag !=' => 1,
										'absensi.keluar !=' => null
									);
		}
		$this->db->select('absensi.*,aktivitas_jadwal.id_jadwal,nama_jadwal,max_jam_kerja');
		$this->db->join('aktivitas_jadwal', 'absensi.id_jadwal = aktivitas_jadwal.id_jadwal');
		$this->db->order_by("tanggal", "desc");
		$this->db->where($data);
		$query = $this->db->get($this->_table);

		return $query->result();
	}

	public function DataPerBulanAbsensi($start,$end,$where)
	{
		$data = array(
									'tanggal >=' => $start,
									'tanggal <=' => $end,
									'kode' => $where,
									'status' => "Diterima",
									'lupa_keluar_kabag !=' => 1,
									'absensi.keluar !=' => null
								);
		$this->db->join('aktivitas_jadwal', 'absensi.id_jadwal = aktivitas_jadwal.id_jadwal');
		$this->db->select('absensi.*');
		$this->db->order_by("tanggal", "desc");
		$this->db->where($data);
		$query = $this->db->get($this->_table);

		return $query->result();
	}

	public function jumlahAbsensi($start,$end,$where)
	{
		$data = array(
									'tanggal >=' => $start,
									'tanggal <=' => $end,
									'kode' => $where,
									'status' => "Diterima",
									'lupa_keluar_kabag !=' => 1,
									'absensi.keluar !=' => null
								);
		$this->db->join('aktivitas_jadwal', 'absensi.id_jadwal = aktivitas_jadwal.id_jadwal');
		$this->db->select('absensi.*');
		$this->db->group_by('tanggal');
		$this->db->order_by("tanggal", "desc");
		$this->db->where($data);
		$query = $this->db->get($this->_table);

		return $query->num_rows();
	}

	public function getDataTelat($start,$end,$kode)
	{
		$this->db->select('telat');
		$where = array(
										'tanggal >=' => $start,
										'tanggal <=' => $end,
										'kode' => $kode,
										'keluar !=' => null,
										'status' => "Diterima",
										'lupa_keluar_kabag !=' => 1
									);
		$this->db->where($where);
		$query = $this->db->get($this->_table);

		return $query->result();
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

	public function getAllKaryawanSelect2Ajax($where=false,$search=false)
	{
		$this->db->select("id, nama, kode_karyawan, idfp, otoritas, tgl_lahir, kelamin, master_jabatan.jabatan, master_grup.grup");
		$this->db->from("master_karyawan");
		$this->db->join('master_jabatan', 'master_jabatan.id_jabatan = master_karyawan.id_jabatan', 'left');
		$this->db->join('master_grup', 'master_grup.id_grup = master_karyawan.id_grup', 'left');
		$this->db->where('status_kerja', "aktif");
		if ($where) {
			$this->db->where($where);
		}
		if ($search) {
			$this->db->like($search)->limit(10);
		}
		$query = $this->db->get();
		return $query->result();
	}

	/* for dashboard */
	public function dataAbsensi($tgl,$where=false,$whereIn=false,$select=false,$result=false)
	{
		if ($select) {
			$this->db->select($select);
		}
		$this->db->join('master_karyawan', 'absensi.kode = master_karyawan.kode_karyawan', 'left');
		$this->db->where(array("tanggal" => $tgl,"status" =>"Diterima","status_kerja" => "aktif"));
		if ($where) {
			$this->db->where($where);
		}
		if ($whereIn) {
			$this->db->where_in(array_keys($whereIn)[0],array_values($whereIn)[0]);
		}
		// $this->db->join('aktivitas_jadwal', 'absensi.id_jadwal = aktivitas_jadwal.id_jadwal','left');
		if ($result) {
			return $this->db->get($this->_table)->result();
		} else {
			return $this->db->count_all_results($this->_table);
		}
	}

	public function getAllIzin($tgl,$count=false)
	{
		$data = array(
									'tgl_izin <=' => $tgl,
									'akhir_izin >=' =>$tgl,
									'status' => "Diterima"
								);

		$this->db->where($data);
		$this->db->select('kode_karyawan as kode');
		$this->db->join('master_karyawan', 'aktivitas_izin.id_karyawan = master_karyawan.id', 'left');

		if ($count) {
			return $this->db->count_all_results('aktivitas_izin');
		}
		else {
			return $this->db->get('aktivitas_izin')->result();
		}
	}

	public function totalKaryawan($tgl)
	{
		$this->db->where(array("status_kerja" => "aktif","tgl_masuk <=" => $tgl));
		return $this->db->count_all_results('master_karyawan');
	}

	public function offAbsensiKaryawan($tgl,$select=false,$result=false)
	{
		if ($select) {
			$this->db->select($select);
		}
		$this->db->where(array("tanggal" => $tgl, "shift_id" => null));
		if ($result) {
			$this->db->from('jadwal_token_karyawan');
			$this->db->join('master_karyawan', 'master_karyawan.id = jadwal_token_karyawan.karyawan_id');
			$query = $this->db->get();
			return $query->result();
		} else {
			return $this->db->count_all_results("jadwal_token_karyawan");
		}
	}

	public function extraData($tgl)
	{
		$this->db->where(array("tanggal" => $tgl));
		return $this->db->count_all_results('absensi_extra');
	}

	public function dataKontrak($where)
	{
		$this->db->where($where);
		return $this->db->count_all_results('master_karyawan');
	}

	public function kontrakKaryawan($wherekontraknew)
	{
		$query = $this->db->query($wherekontraknew);
		return $query->result();
	}

	public function karyawanUlangTahun($tgl)
	{
		$tgl = explode("-", $tgl);
		$select = array("kode_karyawan","nama","master_jabatan.jabatan","tempat_lahir","tgl_lahir","kelamin","foto");
		$this->db->select($select);
		$where = array(
					"MONTH(tgl_lahir)"	=>	$tgl[1],
					"DAY(tgl_lahir)"	=>	$tgl[2],
					"status_kerja"		=>	"aktif",
				);
		$this->db->where($where);
		$this->db->from('master_karyawan');
		$this->db->join('master_jabatan', 'master_jabatan.id_jabatan = master_karyawan.id_jabatan', 'left');
		$query = $this->db->get();
		return $query->result();

	}

	public function getAllDepartemen($count=false,$where=false)
	{
		$this->db->select('master_karyawan.*, master_departemen.departemen');
		$this->db->order_by('departemen', 'ASC');
		$this->db->from('master_karyawan');
		if ($where) {
			$this->db->where($where);
		}
		$this->db->join('master_departemen', 'master_departemen.id_departemen = master_karyawan.id_departemen', 'left');
		if ($count) {
			return $this->db->count_all_results();
		} else {
			$this->db->group_by('master_departemen.departemen');
			$query = $this->db->get();
			return $query->result();
		}
	}

	public function absensiDepartemen($tgl,$count=false,$whereDepartemen=false)
	{
		$this->db->select('absensi.*, master_departemen.departemen');
		$this->db->where(array("tanggal"=>$tgl,"masuk !="=>null));
		$this->db->where_in("kerja", array("Normal","Normal(Manual)","Ganti"));
		if ($whereDepartemen) {
			$this->db->where($whereDepartemen);
		}
		$this->db->from('absensi');
		$this->db->join('master_karyawan', 'master_karyawan.kode_karyawan = absensi.kode', 'left');
		$this->db->join('master_departemen', 'master_departemen.id_departemen = master_karyawan.id_departemen', 'left');
		if ($count) {
			return $this->db->count_all_results();
		} else {
			$query = $this->db->get();
			return $query->result();
		}
	}
	/* end for dashboard*/
	// temp absensi

	public function insertTempAbsensi($id)
	{
		$data = array(
										'id_absensi' => $id,
										'temp_status' => "Proses"
								 );
		 if ($this->db->insert("temp_absensi",$data)) {
 				return $this->db->insert_id();
 		}
	}

	public function getTempAbsensi($id)
	{
		$data = array(
										'id_absensi' => $id,
										'temp_status' => "Proses"
								 );
		$this->db->where($data);
		$query = $this->db->get('temp_absensi');

		return $query->num_rows();
	}

	// end temp absensi
}

/* End of file LogAbsensi_model.php */
/* Location: ./application/models/aktivitas/LogAbsensi_model.php */
