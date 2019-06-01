<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Punishment_model extends CI_Model {

	public $_table;
	public $_primary_key;

	public function __construct()
	{
		parent::__construct();
		$this->_table = "rwd_punishment";
		$this->_primary_key = "id";
	}

	public function setQueryDataTable($search)
	{
		$dataSearch = array();
		foreach ($search as $val) {
			$dataSearch[$val] = $this->input->post("search")["value"];
		}
		$search = $dataSearch;
		$this->db->select("rwd_punishment.*, rwd_punishment.keterangan, master_karyawan.kode_karyawan, master_karyawan.nama, master_departemen.departemen, master_jabatan.jabatan");
		$this->db->from($this->_table);
		$this->db->group_start()->or_like($search)->group_end();
		$this->db->join("master_karyawan","master_karyawan.id = rwd_punishment.id_karyawan","LEFT");
		$this->db->join("master_departemen","master_departemen.id_departemen = master_karyawan.id_departemen","LEFT");
		$this->db->join("master_jabatan","master_jabatan.id_jabatan = master_karyawan.id_jabatan","LEFT");

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

	public function getAllKaryawanAjax()
	{
		$this->db->select("id, nama");
		$query = $this->db->get("master_karyawan");
		return $query->result();
	}

	public function getAllKaryawanAjaxSearch($search)
	{
		$this->db->select("id, nama");
		$this->db->like('nama', $search)->limit(10);
		$query = $this->db->get("master_karyawan");
		return $query->result();
	}

	public function getIdKaryawan($id)
	{
		$this->db->where(array("master_karyawan.id" => $id));
		$this->db->select("master_karyawan.kode_karyawan, master_karyawan.idfp, master_karyawan.nama, master_karyawan.foto, master_departemen.departemen, master_jabatan.jabatan");
		$this->db->from("master_karyawan");
		$this->db->join("master_departemen","master_departemen.id_departemen = master_karyawan.id_departemen","LEFT");
		$this->db->join("master_jabatan","master_jabatan.id_jabatan = master_karyawan.id_jabatan","LEFT");
		return $this->db->get()->row();
	}

	public function insert($data,$dataNotif)
	{
		$this->db->trans_start();


		$datakaryawan = $this->db->insert($this->_table,$data);

		/*notif pemberitahuan*/
		$dataPemberitahuan = array(
									"keterangan"	=>	$dataNotif["keterangan"],
									"tanggal"		=>	date("Y-m-d"),
									"jam"			=>	date("H:i"),
									"url_direct"	=>	$dataNotif["url_direct"],
									"user_id"		=>	$dataNotif["user_id"],
									"level"			=>	$dataNotif["level"],
									"status"		=>	1, //aktif
								);
		$dataNotif = $this->db->insert("pemberitahuan",$dataPemberitahuan);
		$insertIdNotif = $this->db->insert_id();

		$this->db->trans_complete();

		if ($this->db->trans_status() === FALSE){
		    $this->db->trans_rollback();
		    return FALSE;
		} else {
		    $this->db->trans_commit();
		    return TRUE;
		}
	}

	public function getById($id)
	{
		$this->db->select("rwd_punishment.*, master_karyawan.kode_karyawan, master_karyawan.nama, master_karyawan.foto, master_departemen.departemen, master_jabatan.jabatan");
		$this->db->where("rwd_punishment.id",$id);
		$this->db->from($this->_table);
		$this->db->join("master_karyawan","master_karyawan.id = rwd_punishment.id_karyawan","LEFT");
		$this->db->join("master_departemen","master_departemen.id_departemen = master_karyawan.id_departemen","LEFT");
		$this->db->join("master_jabatan","master_jabatan.id_jabatan = master_karyawan.id_jabatan","LEFT");
		$query = $this->db->get();
		return $query->row();
	}

	public function update($id,$data,$dataNotif)
	{
		$this->db->trans_start();
		$this->db->where($this->_primary_key,$id);
		$this->db->update($this->_table,$data);

		$dataPemberitahuan = array(
									"keterangan"	=>	$dataNotif["keterangan"],
									"tanggal"		=>	date("Y-m-d"),
									"jam"			=>	date("H:i"),
									"url_direct"	=>	$dataNotif["url_direct"],
									"user_id"		=>	$dataNotif["user_id"],
									"level"			=>	$dataNotif["level"],
									"status"		=>	1, // aktif
								);
		$dataNotif = $this->db->insert("pemberitahuan",$dataPemberitahuan);

		$this->db->trans_complete();

		if ($this->db->trans_status() === FALSE){
		    $this->db->trans_rollback();
		    return FALSE;
		} else {
		    $this->db->trans_commit();
		    return TRUE;
		}
	}

	public function delete($id,$dataNotif)
	{
		$this->db->trans_start();

		$this->db->where($this->_primary_key,$id);
		$this->db->delete($this->_table);

		/*notif pemberitahuan*/
		$dataPemberitahuan = array(
									"keterangan"	=>	$dataNotif["keterangan"],
									"tanggal"		=>	date("Y-m-d"),
									"jam"			=>	date("H:i"),
									"url_direct"	=>	$dataNotif["url_direct"],
									"user_id"		=>	$dataNotif["user_id"],
									"level"			=>	$dataNotif["level"],
									"status"		=>	1, // aktif
								);
		$dataNotif = $this->db->insert("pemberitahuan",$dataPemberitahuan);

		$this->db->trans_complete();

		if ($this->db->trans_status() === FALSE){
		    $this->db->trans_rollback();
		    return FALSE;
		} else {
		    $this->db->trans_commit();
		    return TRUE;
		}
	}

	public function getPunishmentPenggajian($start,$end,$id)
	{
		$where = array(
										'tanggal >=' => $start,
										'tanggal <=' => $end,
										'id_karyawan' => $id,
										'status' => "Diterima"
									);
		$this->db->where($where);
		$this->db->select_sum('nilai');
		$query = $this->db->get($this->_table);

		return $query->result();
	}

}

/* End of file Punishment_model.php */
/* Location: ./application/models/rwd/Punishment_model.php */
