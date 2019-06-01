<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Penggajian_payment_model extends CI_Model {

	public $_table;
	public $_primary_key;

	public function __construct()
	{
		parent::__construct();
		$this->_table = "penggajian";
		$this->_primary_key = "id_penggajian";
	}

	public function setQueryDataTable($search,$after=null,$before=null,$bulanLalu=false)
	{
		$dataSearch = array();
		foreach ($search as $val) {
			$dataSearch[$val] = $this->input->post("search")["value"];
		}
		$search = $dataSearch;
		$this->db->from("master_karyawan");
		$this->db->join('master_jabatan', 'master_karyawan.id_jabatan = master_jabatan.id_jabatan', 'left');
		$this->db->join('master_departemen', 'master_karyawan.id_departemen = master_departemen.id_departemen', 'left');
		$this->db->join('master_cabang', 'master_karyawan.id_cabang = master_cabang.id_cabang', 'left');
		$this->db->where('master_karyawan.status_kerja', 'aktif');
		if ($after && $before) {
			$this->db->where('tanggal_proses >=', $before);
			$this->db->where('tanggal_proses <=', $after);
		}
		if ($bulanLalu) {
			$this->db->where('month(tanggal_proses) >=',date("m",strtotime("-1 months")));
		}
		// $this->db->join('master_karyawan', 'aktivitas_penggajian.id_karyawan = master_karyawan.id');
		// $this->db->join('master_golongan', 'master_karyawan.id_golongan = master_golongan.id_golongan');
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

	public function setQueryDataTableLog($search,$after=null,$before=null,$bulanLalu=false)
	{
		$dataSearch = array();
		foreach ($search as $val) {
			$dataSearch[$val] = $this->input->post("search")["value"];
		}
		$search = $dataSearch;
		$this->db->from("penggajian");
		$this->db->join('master_karyawan', 'penggajian.id_karyawan = master_karyawan.id', 'left');
		$this->db->join('master_jabatan', 'master_karyawan.id_jabatan = master_jabatan.id_jabatan', 'left');
		$this->db->join('master_departemen', 'master_karyawan.id_departemen = master_departemen.id_departemen', 'left');
		$this->db->join('master_cabang', 'master_karyawan.id_cabang = master_cabang.id_cabang', 'left');
		if ($after && $before) {
			$this->db->where('start_date >=', $before);
			$this->db->where('end_date <=', $after);
		}
		if ($bulanLalu) {
			$this->db->where('month(tanggal_proses) >=',date("m",strtotime("-1 months")));
		}
		// $this->db->join('master_karyawan', 'aktivitas_penggajian.id_karyawan = master_karyawan.id');
		// $this->db->join('master_golongan', 'master_karyawan.id_golongan = master_golongan.id_golongan');
		$this->db->group_start()->or_like($search)->group_end();

	}

	public function findDataTableLog($columnsOrderBy,$search,$after=null,$before=null)
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
		self::setQueryDataTableLog($search,$after,$before);

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


	public function findDataTableOutputlog($data=null,$search=false,$after=null,$before=null)
	{
		$input = $this->input;

		// query data table
		self::setQueryDataTableLog($search,$after,$before);

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
		$this->db->select("master_karyawan.idfp, master_karyawan.nama, master_karyawan.foto,periode_gaji,tgl_masuk,master_golongan.*,bank,jabatan,departemen,rekening,cabang");
		// $this->db->join('master_karyawan', 'penggajian.kode = master_karyawan.kode_karyawan');
		$this->db->join('master_golongan', 'master_karyawan.id_golongan = master_golongan.id_golongan');
		$this->db->join('master_jabatan', 'master_karyawan.id_jabatan = master_jabatan.id_jabatan', 'left');
		$this->db->join('master_bank', 'master_karyawan.id_bank = master_bank.id_bank', 'left');
		$this->db->join('master_departemen', 'master_karyawan.id_departemen = master_departemen.id_departemen', 'left');
		$this->db->join('master_cabang', 'master_karyawan.id_cabang = master_cabang.id_cabang', 'left');
		$this->db->where("id",$id);
		$query = $this->db->get("master_karyawan");
		return $query->row();
	}

	public function getByIdPenggajian($id)
	{
		$this->db->select("master_karyawan.idfp,penggajian.*,master_karyawan.nama, master_karyawan.foto,periode_gaji,tgl_masuk,master_golongan.*,bank,jabatan,departemen,rekening,cabang");
		$this->db->join('master_karyawan', 'penggajian.id_karyawan = master_karyawan.id');
		$this->db->join('master_golongan', 'master_karyawan.id_golongan = master_golongan.id_golongan');
		$this->db->join('master_jabatan', 'master_karyawan.id_jabatan = master_jabatan.id_jabatan', 'left');
		$this->db->join('master_bank', 'master_karyawan.id_bank = master_bank.id_bank', 'left');
		$this->db->join('master_departemen', 'master_karyawan.id_departemen = master_departemen.id_departemen', 'left');
		$this->db->join('master_cabang', 'master_karyawan.id_cabang = master_cabang.id_cabang', 'left');
		$this->db->where("id_penggajian",$id);
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
		$query = $this->db->get($this->_table);
		return $query->result();

	}

	public function getAllKaryawan($periode=false)
	{
		if ($periode) {
			$this->db->where('periode_gaji',$periode);
		}
		$this->db->select('id,nama,status_kerja,npwp,status_nikah,tanggungan');
		$this->db->where('status_kerja', "aktif");
		$query = $this->db->get('master_karyawan');

		return $query->result();
	}

	public function update($periode)
	{
		$query = 'update absensi inner join master_karyawan on absensi.kode = master_karyawan.kode_karyawan set paid = 1 where absensi.tanggal_keluar <= '.$this->db->escape(date("Y-m-d")).'and keluar is not null and periode_gaji = '.$this->db->escape($periode);

		return $this->db->simple_query($query);
	}

	public function updateShift($tgl,$periode)
	{
		$query = 'update absensi inner join master_karyawan on absensi.kode = master_karyawan.kode_karyawan set paid = 1 where absensi.tanggal_keluar <= '.$this->db->escape($tgl).'and keluar is not null and periode_gaji = '.$this->db->escape($periode);

		return $this->db->simple_query($query);
	}

	public function getDataPenggajianTerakhir($periode)
	{
			$where = array(
				'tanggal_proses' => date("Y-m-d"),
				'periode_gaji' => $periode,
				'tipe_payment' => 1
			);

			$this->db->order_by('penggajian.tanggal_proses', 'desc');
			// $this->db->join('master_karyawan', 'penggajian.id_karyawan = master_karyawan.id','left');
			$this->db->join("master_karyawan", 'penggajian.id_karyawan = master_karyawan.id', 'left');
			$this->db->where($where);
			$query = $this->db->get($this->_table);
			// return $query->count_all_results();
			return $query->num_rows();

	}

	public function getSisa($id_karyawan)
	{
		$data = array(
										'sisa !=' => 0,
										'id_karyawan' =>$id_karyawan
								 );

		$this->db->where($data);
		$this->db->order_by('penggajian.tanggal_proses', 'asc');
		$this->db->select_sum('sisa');
		$query = $this->db->get($this->_table);

		return $query->row();
	}

	public function updateSisaPayment($id,$sisa)
	{
		$where = array(
										'id_karyawan' => $id
								 );
	  $data = array('sisa' => $sisa);
		$this->db->where($where);
		return $this->db->update($this->_table,$data);
	}

	public function updateAbsensiPerOrang($kode)
	{
		$query = 'update absensi set paid = 1 where absensi.tanggal <= '.$this->db->escape($tanggal).'and keluar is not null and kode = '.$this->db->escape($kode);
		return $this->db->query($query);
	}

	public function insert($data,$after,$kode)
	{
		if (strlen($kode) > 6 ) {
			$this->db->trans_start();
			$insertBatch = $this->db->insert_batch($this->_table, $data);

			$query = 'update absensi inner join master_karyawan on absensi.kode = master_karyawan.kode_karyawan set paid = 1 where absensi.tanggal <= '.$this->db->escape(date("Y-m-d")).'and keluar is not null kode = '.$this->db->escape($kode);
			$updateAbsensi = $this->db->simple_query($query);
			$this->db->trans_complete();
			if ($this->db->trans_status() === FALSE){
				$this->db->trans_rollback();
				return FALSE;
			} else {
				$this->db->trans_commit();
				return TRUE;
			}
		}
		else {
			$this->db->trans_start();

			$this->db->insert($this->_table,$data[0]);
			$insertData = $this->db->insert_id();

			$query = 'update absensi inner join master_karyawan on absensi.kode = master_karyawan.kode_karyawan set paid = 1 where absensi.tanggal <= '.$this->db->escape(date("Y-m-d")).'and keluar is not null and kode = '.$this->db->escape($kode);
			$updateAbsensi = $this->db->simple_query($query);

			$this->db->trans_complete();
			if ($this->db->trans_status() === FALSE){
				$this->db->trans_rollback();
				return FALSE;
			} else {
				$this->db->trans_commit();
				return TRUE;
			}
		}
	}

	public function insertPeriode($data,$after,$periode)
	{
		if (strlen($periode) > 6 ) {
			$this->db->trans_start();
			$insertBatch = $this->db->insert_batch($this->_table, $data);

			$query = 'update absensi inner join master_karyawan on absensi.kode = master_karyawan.kode_karyawan set paid = 1 where absensi.tanggal <= '.$this->db->escape(date("Y-m-d")).'and keluar is not null periode = '.$this->db->escape($periode);
			$updateAbsensi = $this->db->simple_query($query);
			$this->db->trans_complete();
			if ($this->db->trans_status() === FALSE){
				$this->db->trans_rollback();
				return FALSE;
			} else {
				$this->db->trans_commit();
				return TRUE;
			}
		}
		else {
			$this->db->trans_start();

			$this->db->insert($this->_table,$data[0]);
			$insertData = $this->db->insert_id();

			$query = 'update absensi inner join master_karyawan on absensi.kode = master_karyawan.kode_karyawan set paid = 1 where absensi.tanggal <= '.$this->db->escape(date("Y-m-d")).'and keluar is not null and periode = '.$this->db->escape($periode);
			$updateAbsensi = $this->db->simple_query($query);

			$this->db->trans_complete();
			if ($this->db->trans_status() === FALSE){
				$this->db->trans_rollback();
				return FALSE;
			} else {
				$this->db->trans_commit();
				return TRUE;
			}
		}
	}

	public function insertMultiKaryawan($data,$after)
	{
		if (sizeof($data) > 1 ) {
			$this->db->trans_start();
			$insertBatch = $this->db->insert_batch($this->_table, $data);

			$this->db->trans_complete();
			if ($this->db->trans_status() === FALSE){
				$this->db->trans_rollback();
				return FALSE;
			} else {
				$this->db->trans_commit();
				return TRUE;
			}
		}
		else {
			$this->db->trans_start();

			$this->db->insert($this->_table,$data[0]);
			$insertData = $this->db->insert_id();

			$this->db->trans_complete();
			if ($this->db->trans_status() === FALSE){
				$this->db->trans_rollback();
				return FALSE;
			} else {
				$this->db->trans_commit();
				return TRUE;
			}
		}
	}


	public function getValidasiDataPenggajian($periode,$shift)
	{
		$where = array(
										// 'start_date >=' => $before,
										'shift' => $shift,
										'periode_penggajian' => $periode
									);
		$this->db->where($where);
		$this->db->order_by('end_date','DESC');
		$query = $this->db->get($this->_table);

		return $query->row();
	}

	public function getDataTerakhirPenggajian($id)
	{
		$data = array(
										'id' => $id,
										'paid' => 0,
										'absensi.keluar !=' => null,
										"status" => "Diterima",
										"lupa_keluar_kabag" => 0,
								 );
		$this->db->where($data);
		$this->db->order_by("tanggal", "asc");
		$this->db->join('master_karyawan', 'absensi.kode = master_karyawan.kode_karyawan','left');
		$query = $this->db->get('absensi');
		// var_dump($query);
		return $query->row();
	}

	public function getLastPenggajianId($id)
	{
		$data = array(
										'id' => $id,
										'sisa !=' => 0
								 );
		$this->db->where($data);
		$this->db->order_by("tanggal_proses", "desc");
		$query = $this->db->get($this->_table);

		return $query->row();
	}

	public function idToKode($id)
	{
		$this->db->select('nama,kode_karyawan,agama,periode_gaji,master_golongan.*');
		$this->db->where('id', $id);
		$this->db->join('master_golongan', 'master_karyawan.id_golongan = master_golongan.id_golongan', 'left');
		$query = $this->db->get('master_karyawan');

		return $query->row();
	}

	public function getDataPerBulan($before,$after)
	{
		$data = array(
										'paid' => 0,
										'tanggal >=' => $before,
										'tanggal <=' => $after,
										'status' => "Diterima",
										'lupa_keluar_kabag !=' => 1,
										'absensi.keluar !=' => null
								 );
		$this->db->where($data);
		// $this->db->select_sum('payment');
		$this->db->order_by("tanggal", "desc");
		$query = $this->db->get('absensi');

		return $query->result();

	}

	public function DataPerBulan($start,$end,$where)
	{
		$data = array(
									'tanggal >=' => $start,
									'tanggal <=' => $end,
									'id' => $where,
									'status' => "Diterima",
									'lupa_keluar_kabag !=' => 1
								);
		$this->db->join('aktivitas_jadwal', 'absensi.id_jadwal = aktivitas_jadwal.id_jadwal');
		$this->db->join('absensi', 'master_karyawan.kode_karyawan = absensi.kode', 'left');
		$this->db->order_by("tanggal", "desc");
		$this->db->where($data);
		$query = $this->db->get("master_karyawan");

		return $query->result();
	}


	public function getDataPenggajianKaryawan($id)
	{
		$this->db->where('id_penggajian', $id);
		$this->db->join('master_karyawan', 'aktivitas_penggajian.id_karyawan = master_karyawan.id', 'left');
		$this->db->select('aktivitas_penggajian.*,kode_karyawan,id');
		$query = $this->db->get($this->_table);

		return $query->row();
	}

	public function getDataPenggajianPerKaryawan($id)
	{
		$this->db->where('master_karyawan.id', $id);
		$this->db->select('master_golongan.*');
		$this->db->join('master_karyawan', 'aktivitas_penggajian.id_karyawan = master_karyawan.id', 'left');
		$this->db->join('master_golongan', 'master_karyawan.id_golongan = master_golongan.id_golongan', 'left');
		$query = $this->db->get($this->_table);

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

	public function last_pay_periode($periode)
	{
		$data = array(
										'periode_gaji' => $periode,
										'status_kerja' => "aktif"
								 );
		$this->db->where($data);
		$query = $this->db->get("master_karyawan");
		return $query->result();
	}


}

/* End of file aktivitas_penggajian.php */
/* Location: ./application/models/aktivitas/penggajian_model.php */
