<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Penggajian_model extends CI_Model {

	public $_table;
	public $_primary_key;

	public function __construct()
	{
		parent::__construct();
		$this->_table = "borongan_penggajian";
		$this->_primary_key = "id_penggajian";
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
			$this->db->where('tanggal_proses >=', $before);
			$this->db->where('tanggal_proses <=', $after);
		}
		if ($bulanLalu) {
			$this->db->where('month(tanggal_proses) >=',date("m",strtotime("-1 months")));
		}
		$this->db->join('borongan_pekerja', 'borongan_penggajian.id_pekerja = borongan_pekerja.id_pekerja');
		// $this->db->join('borongan_departemen', 'borongan_produksi.id_departemen = borongan_departemen.id_departemen');
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
		$this->db->where($this->_primary_key,$id);
		$this->db->select("borongan_penggajian.*,borongan_pekerja.idfp, borongan_pekerja.nama,borongan_departemen.departemen,borongan_departemen.harga");
		$this->db->join('borongan_pekerja', 'borongan_produksi.id_pekerja = borongan_pekerja.id_pekerja');
		$this->db->join('borongan_departemen', 'borongan_produksi.id_departemen = borongan_departemen.id_departemen');
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

	public function getValidasiDataPenggajian($periode)
	{
		$where = array(
										// 'start_date >=' => $before,
										'periode_penggajian' => $periode
									);
		$this->db->where($where);
		$this->db->order_by('end_date','DESC');
		$query = $this->db->get($this->_table);

		return $query->row();
	}

	public function getDataPenggajianKaryawan($id)
	{
		$this->db->where('id_penggajian', $id);
		$this->db->join('borongan_pekerja', 'borongan_penggajian.id_pekerja = borongan_pekerja.id_pekerja', 'left');
		// $this->db->join('borongan_departemen', 'borongan_produksi.id_departemen = borongan_departemen.id_departemen', 'left');
		// $this->db->join('borongan_departemen', 'borongan_penggajian.id_pekerja = borongan_pekerja.id_pekerja', 'left');
		$this->db->select('borongan_penggajian.*,idfp,borongan_penggajian.id_pekerja');
		$query = $this->db->get($this->_table);

		return $query->row();
	}

	public function getDataPenggajianPeriode($periode)
	{
		$this->db->where('kode_payroll', $periode);
		$this->db->join('borongan_pekerja', 'borongan_penggajian.id_pekerja = borongan_pekerja.id_pekerja', 'left');
		// $this->db->join('borongan_departemen', 'borongan_produksi.id_departemen = borongan_departemen.id_departemen', 'left');
		// $this->db->join('borongan_departemen', 'borongan_penggajian.id_pekerja = borongan_pekerja.id_pekerja', 'left');
		$this->db->select('borongan_penggajian.*,idfp,borongan_penggajian.id_pekerja');
		$query = $this->db->get($this->_table);

		return $query->result();
	}

	public function getListKodePayroll()
	{
		$this->db->select('kode_payroll');
		$this->db->group_by('kode_payroll');
		$query = $this->db->get($this->_table);

		return $query->result();
	}

	public function getDataPenggajianPerKaryawan($id,$kode)
	{
		$data = array('kode_payroll' => $kode,
		'borongan_pekerja.id_pekerja' => $id);
		$this->db->where($data);
		// $this->db->select('borongan_departemen.*,start_date,end_date,borongan_penggajian.id_pekerja');
		// $this->db->join('borongan_pekerja', 'borongan_penggajian.id_pekerja = borongan_pekerja.id_pekerja', 'left');
		$this->db->join('borongan_produksi', 'borongan_departemen.id_departemen = borongan_produksi.id_departemen');
		$this->db->join('borongan_pekerja','borongan_produksi.id_pekerja = borongan_pekerja.id_pekerja');
		$this->db->join('borongan_penggajian','borongan_produksi.id_pekerja = borongan_penggajian.id_pekerja');
		$query = $this->db->get('borongan_departemen');

		return $query->row();
	}

	public function getDataPenggajianPerPeriode($id,$kode)
	{
		// $data = array(
		// 								'kode_payroll' => $kode,
		// 								'borongan_pekerja.id_pekerja' => $id
		// 						 );
		//
		// $this->db->select('borongan_penggajian.*,departemen,borongan_pekerja.nama,borongan_produksi.tanggal,borongan_produksi.harga,borongan_produksi.pendapatan,borongan_produksi.jumlah');
		// $this->db->where($data);
		// $this->db->join('borongan_pekerja', 'borongan_penggajian.id_pekerja = borongan_pekerja.id_pekerja');
		// $this->db->join('borongan_produksi', 'borongan_pekerja.id_pekerja = borongan_produksi.id_pekerja');
		// $this->db->join('borongan_departemen', 'borongan_produksi.id_departemen = borongan_departemen.id_departemen');
		// $query = $this->db->get('borongan_penggajian');
		$idPekerja = $this->db->escape($id);
		$kodepayroll = $this->db->escape($kode);
		$sql = 'select bp.id_pekerja,bo.nama,bp.jumlah,bdep.kode,bdep.departemen,bp.tanggal,bpj.start_date,bpj.end_date,bp.harga,bp.pendapatan from borongan_penggajian bpj join borongan_produksi as bp on bp.id_pekerja=bpj.id_pekerja join borongan_pekerja as bo on bp.id_pekerja=bo.id_pekerja join borongan_departemen as bdep on bdep.id_departemen = bp.id_departemen where bp.tanggal BETWEEN bpj.start_date and bpj.end_date and bpj.kode_payroll='.$kodepayroll.' and bpj.id_pekerja='.$idPekerja.' order by bp.id_pekerja asc';
		$query = $this->db->query($sql);

		return $query->result();
	}
}

/* End of file aktivitas_penggajian.php */
/* Location: ./application/models/aktivitas/penggajian_model.php */
