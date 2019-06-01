<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Produksi_model extends CI_Model {

	public $_table;
	public $_primary_key;

	public function __construct()
	{
		parent::__construct();
		$this->_table = "borongan_produksi";
		$this->_primary_key = "id_produksi";
	}

	public function setQueryDataTable($search,$before,$after)
	{
		$dataSearch = array();
		foreach ($search as $val) {
			$dataSearch[$val] = $this->input->post("search")["value"];
		}
		$search = $dataSearch;
		$this->db->from($this->_table);
		if ($before && $after) {
			$this->db->where(array('tanggal >=' => $before, 'tanggal <=' => $after));
		}
		// $this->db->select('borongan_produksi.*,borongan_pekerja.nama,borongan_departemen.departemen');
		$this->db->join('borongan_pekerja', 'borongan_pekerja.id_pekerja = borongan_produksi.id_pekerja','LEFT');
		$this->db->join('borongan_departemen', 'borongan_departemen.id_departemen = borongan_produksi.id_departemen','LEFT');
		$this->db->group_start()->or_like($search)->group_end();

	}

	public function findDataTable($columnsOrderBy,$search,$before=null,$after=null)
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
		self::setQueryDataTable($search,$before,$after);

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

	public function findDataTableOutput($data=null,$search=false,$before=null,$after=null)
	{
		$input = $this->input;

		// query data table
		self::setQueryDataTable($search,$before,$after);

		$getCount = $this->db->count_all_results();

		$response = new stdClass();
		$response->draw = !empty($input->post("draw")) ? $input->post("draw"):null;
		$response->recordsTotal = $getCount;
		$response->recordsFiltered = $getCount;
		$response->data = $data;

		self::json($response);
	}

	public function setQueryDataTableLaporan($search,$before,$after)
	{
		$dataSearch = array();
		foreach ($search as $val) {
			$dataSearch[$val] = $this->input->post("search")["value"];
		}
		$search = $dataSearch;
		$this->db->from($this->_table);
		if ($before && $after) {
			$this->db->where(array('tanggal >=' => $before, 'tanggal <=' => $after));
		}

		$this->db->group_by("borongan_produksi.id_departemen");
		$this->db->join('borongan_pekerja', 'borongan_pekerja.id_pekerja = borongan_produksi.id_pekerja');
		$this->db->join('borongan_departemen', 'borongan_produksi.id_departemen = borongan_departemen.id_departemen');
		$this->db->select("tanggal,nama,departemen,kode,sum(jumlah) as totaljumlah,borongan_departemen.harga,sum(pendapatan) as totalpendapatan");
		$this->db->group_start()->or_like($search)->group_end();

	}

	public function findDataTableLaporan($columnsOrderBy,$search,$before=null,$after=null)
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
		self::setQueryDataTableLaporan($search,$before,$after);

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

	public function findDataTableOutputLaporan($data=null,$search=false,$before=null,$after=null)
	{
		$input = $this->input;

		// query data table
		self::setQueryDataTableLaporan($search,$before,$after);

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

	public function getKaryawan()
	{
		$this->db->select('id_pekerja,nama');
		$this->db->from("borongan_pekerja");

		$result = $this->db->get()->result_array();
		return $result;
	}

	public function getDepartemen()
	{
		$this->db->select('id_departemen,departemen,harga');
		$this->db->from("borongan_departemen");

		$result = $this->db->get()->result_array();
		return $result;
	}

	public function getDataKaryawanSelect($id)
	{
		$this->db->from("borongan_pekerja");
		// $this->db->join('borongan_departemen', 'borongan_pekerja.id_departemen = borongan_departemen.id_departemen');
		$this->db->where('id', $id);
		$result = $this->db->get()->row();

		return $result;
	}

	public function departemenDataAll()
	{

		$this->db->select('id_departemen,departemen');
		$this->db->group_by("departemen");
		$this->db->from("borongan_departemen");

		$result = $this->db->get()->result_array();
		return $result;
	}

	public function getAllKaryawanAjax()
	{
		$this->db->select("id_pekerja, nama");
		$query = $this->db->get("borongan_pekerja");
		return $query->result();
	}

	public function getAllDepartemenAjax()
	{
		$this->db->select("id_departemen, departemen");
		$query = $this->db->get("borongan_departemen");
		return $query->result();
	}

	public function getAllKaryawanAjaxSearch($search)
	{
		$this->db->select("id_pekerja, nama");
		$this->db->like('nama', $search)->limit(10);
		$query = $this->db->get("borongan_pekerja");
		return $query->result();
	}

	public function getIdKaryawan($id)
	{
		$this->db->where(array("borongan_pekerja.id_pekerja" => $id));
		$this->db->select("borongan_pekerja.idfp, borongan_pekerja.nama");
		$this->db->from("borongan_pekerja");
		// $this->db->join("borongan_departemen","borongan_departemen.id_departemen = borongan_pekerja.id_departemen");
		// $this->db->join("borongan_pekerja","borongan_pekerja.id_pekerja = borongan_produksi.id_pekerja");
		return $this->db->get()->row();
	}

	public function getIdDepartemen($id_departemen)
	{
		$this->db->where(array("borongan_departemen.id_departemen" => $id_departemen));
		$this->db->select("borongan_departemen.departemen, borongan_departemen.harga");
		$this->db->from("borongan_departemen");

		return $this->db->get()->row();
	}

	public function insert($data)
	{
		if ($this->db->insert_batch($this->_table,$data)) {
			return $this->db->insert_id();
		}
	}

	public function getProduksi($id_pekerja)
	{
		$where = array(
										// 'start_date >=' => $before,
										// 'pendapatan' => $pendapatan,
										'id_pekerja' => $id_pekerja
									);
		$this->db->where($where);
		$query = $this->db->get($this->_table);
		return $query->row();
	}

	public function getPekerja($start,$end)
	{
		$this->db->group_by('id_pekerja');
		// $this->db->select_sum('pendapatan');
		$data = array(
						'tanggal >=' => $start,
						'tanggal <=' => $end,

					 );
		$this->db->where($data);
		$this->db->select('sum(pendapatan) as pendapatans,id_pekerja');
		$this->db->select('sum(jumlah) as jumlahs,id_pekerja');
		$query = $this->db->get($this->_table);
		return $query->result();
	}

	public function getHariKerja($start,$end,$id)
	{
		// $this->db->group_by('id_pekerja');
		// $this->db->select_sum('pendapatan');
		$data = array(
						'tanggal >=' => $start,
						'tanggal <=' => $end,
						'id_pekerja' => $id
					 );
		$this->db->where($data);
		$query = $this->db->get($this->_table);
		return $query->num_rows();
	}


	public function getById($id)
	{
		$this->db->where($this->_primary_key,$id);
		$this->db->select("borongan_produksi.*,borongan_pekerja.idfp, borongan_pekerja.nama, borongan_departemen.departemen");
		$this->db->join('borongan_pekerja', 'borongan_produksi.id_pekerja = borongan_pekerja.id_pekerja');
		$this->db->join('borongan_departemen', 'borongan_produksi.id_departemen = borongan_departemen.id_departemen');
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

	public function getAllItem()
	{
		$query = $this->db->get('borongan_departemen');

		return $query->result();
	}

	public function getDataProduksiPenggajian($id,$before,$after)
	{
		$data = array(
										'id_pekerja' => $id,
										'tanggal >=' => $before,
										'tanggal <=' => $after
								 );
	$this->db->join('borongan_departemen', 'borongan_produksi.id_departemen = borongan_departemen.id_departemen');
    $this->db->where($data);
		$query = $this->db->get($this->_table);
		return $query->result();
	}
}

/* End of file Izin_model.php */
/* Location: ./application/models/Aktivitas/Izin_model.php */
