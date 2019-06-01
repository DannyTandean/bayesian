<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Temp_golongan_model extends CI_Model {

	public $_table;
	public $_primary_key;

	public function __construct()
	{
		parent::__construct();
		$this->_table = "temp_master_golongan";
		$this->_primary_key = "temp_id_golongan";
	}

	public function setQueryDataTable($search)
	{
		$dataSearch = array();
		foreach ($search as $val) {
			$dataSearch[$val] = $this->input->post("search")["value"];
		}
		$search = $dataSearch;
		$this->db->from($this->_table);
		// $this->db->order_by("create_at DESC");
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

	public function insert($data,$dataNotif=null)
	{
		if($dataNotif == null)
		{
			if ($this->db->insert($this->_table,$data)) {
				return $this->db->insert_id();
			}
		}
		else {
			$this->db->trans_start();

			/*insert data Golongan*/
			$dataGolongan = $this->db->insert($this->_table,$data);

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

	public function getById($id)
	{
		$this->db->where($this->_primary_key,$id);
		$query = $this->db->get($this->_table);
		return $query->row();
	}

	public function getByIdDetail($id)
	{
		$this->db->select('id_golongan,golongan,keterangan,gaji,gaji_menit,makan,transport,thr,tunjangan_lain,bpjs,pot_lain,lembur_tetap,umk');
		$this->db->where($this->_primary_key,$id);
		$query = $this->db->get($this->_table);
		return $query->row_array();
	}

	public function getByIdGolonganDetail($id)
	{
		$this->db->select('id_golongan,golongan,keterangan,gaji,makan,transport,thr,tunjangan_lain,bpjs,pot_lain,lembur_tetap,umk,status');
			$data = array('id_golongan' => $id,
			 						'status' => 'Proses'
		 							);
		$this->db->where($data);
		$query = $this->db->get($this->_table);
		return $query->row_array();
	}

	public function addTransaction($id,$data,$status,$dataNotif)
	{
		$this->db->trans_start();
		//Insert master golongan
		$this->db->insert('master_golongan', $data);
		//update status temp master golongan

		$this->db->update('temp_master_golongan',$status,array('temp_id_golongan' => $id ));

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

		if ($this->db->trans_status() === FALSE)
		{
        $this->db->trans_rollback();
				return false;
		}
		else
		{
        $this->db->trans_commit();
				return true;
		}
	}

	public function updateTransaction($id,$idgol,$data,$status,$dataNotif)
	{
		$this->db->trans_start();
		//update master golongan
		$this->db->where('id_golongan', $idgol);
		$this->db->update('master_golongan', $data);
		//update status temp master golongan

		$this->db->where('id_golongan', $idgol);
		$this->db->update('temp_master_golongan', $status,array('temp_id_golongan' => $id ));
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

		if ($this->db->trans_status() === FALSE)
		{
        $this->db->trans_rollback();
				return false;
		}
		else
		{
        $this->db->trans_commit();
				return true;
		}
	}

	public function deleteTransaction($id,$status,$dataNotif)
	{
		$this->db->trans_start();
		//Delete master golongan
		$this->db->delete('master_golongan', array('id_golongan' => $id));
		//update status temp master golongan
		$this->db->where('id_golongan', $id);
		$this->db->update('temp_master_golongan', $status,array('id_golongan' => $id ));
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

		if ($this->db->trans_status() === FALSE)
		{
        $this->db->trans_rollback();
				return false;
		}
		else
		{
        $this->db->trans_commit();
				return true;
		}
	}

	public function getSelectData($id)
	{
		$this->db->select('id_golongan,golongan,keterangan,gaji,gaji_menit,makan,transport,thr,tunjangan_lain,bpjs,pot_lain,lembur,lembur_tetap,umk');
		$this->db->where('temp_id_golongan', $id);
		// $this->db->where($this->_primary_key, $id);
		$query = $this->db->get($this->_table);
		return $query->row();
	}
	public function update($id,$data,$dataNotif=null)
	{
		if($dataNotif == null)
		{
			$this->db->where($this->_primary_key,$id);
			return $this->db->update($this->_table,$data);
		}
		else {
			$this->db->trans_start();

			/*insert data Golongan*/
			$this->db->where($this->_primary_key,$id);
			$dataGolongan = $this->db->update($this->_table,$data);

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

	public function delete($id)
	{
		$this->db->where($this->_primary_key,$id);
		$this->db->delete($this->_table);
		return $this->db->affected_rows();
	}
}

/* End of file Temp_golongan_model.php */
/* Location: ./application/models/approval/Temp_golongan_model.php */
