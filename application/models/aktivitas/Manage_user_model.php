<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Manage_user_model extends CI_Model {

	public $_table;
	public $_primary_key;

	public function __construct()
	{
		parent::__construct();
		$this->_table = "user";
		$this->_primary_key = "id_user";
	}

	public function setQueryDataTable($search,$after=null,$before=null)
	{
		$dataSearch = array();
		foreach ($search as $val) {
			$dataSearch[$val] = $this->input->post("search")["value"];
		}
		$search = $dataSearch;
		$this->db->from($this->_table);
		if ($after && $before) {
			$this->db->where('tanggal >=', $before);
			$this->db->where('tanggal <=', $after);
		}

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
		$query = $this->db->get($this->_table);
		return $query->row();
	}

	public function block($id,$data)
	{
		$this->db->where($this->_primary_key,$id);
		return $this->db->update($this->_table,$data);
	}

	public function delete($id,$dataNotif=null)
	{
		if($dataNotif==null)
		{
			$this->db->where($this->_primary_key,$id);
			$this->db->delete($this->_table);
			return $this->db->affected_rows();
		}
		else {
			$this->db->trans_start();

			/*delete data Cuti*/
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
	}
}

/* End of file Crup_model.php */
/* Location: ./application/models/aktivitas/Cuti_model.php */
