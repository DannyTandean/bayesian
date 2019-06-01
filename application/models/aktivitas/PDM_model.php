<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class PDM_model extends CI_Model {

	public $_table;
	public $_primary_key;

	public function __construct()
	{
		parent::__construct();
		$this->_table = "aktivitas_promosi";
		$this->_primary_key = "id_promosi";
	}

	public function setQueryDataTable($search)
	{
		$dataSearch = array();
		foreach ($search as $val) {
			$dataSearch[$val] = $this->input->post("search")["value"];
		}
		$search = $dataSearch;
		$this->db->from($this->_table);
		$this->db->join('master_karyawan', 'aktivitas_promosi.id_karyawan = master_karyawan.id');
		$this->db->join('master_cabang', 'master_karyawan.id_cabang = master_cabang.id_cabang');
		$this->db->join('master_departemen', 'master_karyawan.id_departemen = master_departemen.id_departemen');
		$this->db->join('master_jabatan', 'master_karyawan.id_jabatan = master_jabatan.id_jabatan');
		$this->db->join('master_grup', 'master_karyawan.id_grup = master_grup.id_grup');
		// $this->db->join('master_shift', 'master_karyawan.shift = master_shift.id_shift');
		$this->db->join('master_golongan', 'master_karyawan.id_golongan = master_golongan.id_golongan');
		$this->db->group_start()->or_like($search)->group_end();

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

	public function getKaryawan()
	{
		$this->db->select('id,nama');
		$this->db->from("master_karyawan");

		$result = $this->db->get()->result_array();
		return $result;
	}
	public function getDataKaryawanSelect($id)
	{
		$this->db->from("master_karyawan");
		$this->db->join('master_cabang', 'master_karyawan.id_cabang = master_cabang.id_cabang');
		$this->db->join('master_departemen', 'master_karyawan.id_departemen = master_departemen.id_departemen');
		$this->db->join('master_jabatan', 'master_karyawan.id_jabatan = master_jabatan.id_jabatan');
		$this->db->join('master_grup', 'master_karyawan.id_grup = master_grup.id_grup');
		// $this->db->join('master_shift', 'master_karyawan.shift = master_shift.id_shift');
		$this->db->join('master_golongan', 'master_karyawan.id_golongan = master_golongan.id_golongan');
		$this->db->where('id', $id);
		$result = $this->db->get()->row();

		return $result;
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

	public function getAllCabangAjax()
	{
		$this->db->select("id_cabang, cabang");
		$query = $this->db->get("master_cabang");
		return $query->result_array();
	}

	public function getAllDepartemenAjax()
	{
		$this->db->select("id_departemen, departemen");
		$query = $this->db->get("master_departemen");
		return $query->result_array();
	}

	public function getAllJabatanAjax()
	{
		$this->db->select("id_jabatan, jabatan");
		$query = $this->db->get("master_jabatan");
		return $query->result_array();
	}


	public function getAllGolonganAjax()
	{
		$this->db->select("id_golongan, golongan");
		$query = $this->db->get("master_golongan");
		return $query->result_array();
	}

	public function getAllGrupAjax()
	{
		$this->db->select("id_grup, grup");
		$query = $this->db->get("master_grup");
		return $query->result_array();
	}


	public function getAllCabangAjaxSearch($search)
	{
		$this->db->select("id_cabang, cabang");
		$this->db->like('cabang', $search)->limit(10);
		$query = $this->db->get("master_cabang");
		return $query->result();
	}

	public function getAllDepartemenAjaxSearch($search)
	{
		$this->db->select("id_departemen, departemen");
		$this->db->like('departemen', $search)->limit(10);
		$query = $this->db->get("master_departemen");
		return $query->result();
	}

	public function getAllJabatanAjaxSearch($search)
	{
		$this->db->select("id_jabatan, jabatan");
		$this->db->like('jabatan', $search)->limit(10);
		$query = $this->db->get("master_jabatan");
		return $query->result();
	}

	public function getAllGolonganAjaxSearch($search)
	{
		$this->db->select("id_golongan, golongan");
		$this->db->like('golongan', $search)->limit(10);
		$query = $this->db->get("master_golongan");
		return $query->result();
	}

	public function getIdCabang($nama)
	{
			$this->db->select("id_cabang, cabang");
			$this->db->where('cabang',$nama);
			$query = $this->db->get("master_cabang");
			return $query->result();
	}
	public function getIdDepartemen($nama)
	{
			$this->db->select("id_departemen, departemen");
			$this->db->where('departemen',$nama);
			$query = $this->db->get("master_departemen");
			return $query->result();
	}
	public function getIdJabatan($nama)
	{
			$this->db->select("id_jabatan, jabatan");
			$this->db->where('jabatan',$nama);
			$query = $this->db->get("master_jabatan");
			return $query->result();
	}
	public function getIdGolongan($nama)
	{
			$this->db->select("id_golongan, golongan");
			$this->db->where('golongan',$nama);
			$query = $this->db->get("master_golongan");
			return $query->result();
	}
	public function getIdGrup($nama)
	{
			$this->db->select("id_grup, grup");
			$this->db->where('grup',$nama);
			$query = $this->db->get("master_grup");
			return $query->result();
	}



	public function getAllGrupAjaxSearch($search)
	{
		$this->db->select("id_grup, grup");
		$this->db->like('grup', $search)->limit(10);
		$query = $this->db->get("master_grup");
		return $query->result();
	}

	public function getIdKaryawan($id)
	{
		$this->db->where(array("master_karyawan.id" => $id));
		$this->db->select("master_karyawan.kode_karyawan, master_karyawan.nama, master_karyawan.foto,master_cabang.cabang, master_departemen.departemen,master_jabatan.jabatan,master_grup.grup,master_golongan.golongan");
		$this->db->from("master_karyawan");
		$this->db->join("master_cabang", "master_karyawan.id_cabang = master_cabang.id_cabang","LEFT");
		$this->db->join("master_departemen","master_departemen.id_departemen = master_karyawan.id_departemen","LEFT");
		$this->db->join("master_jabatan","master_jabatan.id_jabatan = master_karyawan.id_jabatan","LEFT");
		$this->db->join("master_grup", "master_karyawan.id_grup = master_grup.id_grup","LEFT");
		// $this->db->join("master_shift", "master_karyawan.id_shift = master_shift.id_shift","LEFT");
		$this->db->join("master_golongan", "master_karyawan.id_golongan = master_golongan.id_golongan","LEFT");
		return $this->db->get()->row();
	}

	public function findDataTableOutput($data=null,$search=false)
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

	public function getById($id,$select=false,$row_array=false)
	{
		$this->db->where($this->_primary_key,$id);

		$selectData = "aktivitas_promosi.*,master_karyawan.id, master_karyawan.kode_karyawan, master_karyawan.nama, master_karyawan.foto,
			master_cabang.cabang, master_departemen.departemen, master_jabatan.jabatan, master_golongan.golongan,master_grup.grup,keterangans,tanggal, master_cabang.id_cabang, master_departemen.id_departemen,master_jabatan.id_jabatan, master_golongan.id_golongan, master_grup.id_grup";
		if ($select) {
			$selectData = $select;
		}
		$this->db->select($selectData);
		$this->db->join('master_karyawan', 'aktivitas_promosi.id_karyawan = master_karyawan.id');
		$this->db->join('master_cabang', 'master_karyawan.id_cabang = master_cabang.id_cabang');
		$this->db->join('master_departemen', 'master_karyawan.id_departemen = master_departemen.id_departemen');
		$this->db->join('master_jabatan', 'master_karyawan.id_jabatan = master_jabatan.id_jabatan');
		$this->db->join('master_grup', 'master_karyawan.id_grup = master_grup.id_grup');
		// $this->db->join('master_shift', 'master_karyawan.id_shift = master_shift.id_shift');
		$this->db->join('master_golongan', 'master_karyawan.id_golongan = master_golongan.id_golongan');
		$query = $this->db->get($this->_table);
		if ($row_array) {
			return $query->row_array();
		} else {
			return $query->row();
		}
	}

	public function getByIdDetail($id)
	{
		$select = "master_karyawan.kode_karyawan,master_cabang.id_cabang,master_departemen.id_departemen,master_jabatan.id_jabatan,master_grup.id_grup,master_golongan.id_golongan,keterangans";
		return self::getById($id,$select,true);
	}


	public function getByIdDetail1($id)
	{

		$this->db->select('id_promosi,kode_karyawan,nama,judul,cabang,departemen,jabatan,grup,golongan,keterangans');
		$this->db->join('master_karyawan', 'aktivitas_promosi.id_karyawan = master_karyawan.id');
		$this->db->join('master_cabang', 'master_karyawan.id_cabang = master_cabang.id_cabang');
		$this->db->join('master_departemen', 'master_karyawan.id_departemen = master_departemen.id_departemen');
		$this->db->join('master_jabatan', 'master_karyawan.id_jabatan = master_jabatan.id_jabatan');
		$this->db->join('master_grup', 'master_karyawan.id_grup = master_grup.id_grup');
		$this->db->join('master_golongan', 'master_karyawan.id_golongan = master_golongan.id_golongan');
		$this->db->where($this->_primary_key,$id);
		$query = $this->db->get($this->_table);
		return $query->row_array();
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

/* End of file PDM_model.php */
/* Location: ./application/models/Aktivitas/PDM_model.php */
