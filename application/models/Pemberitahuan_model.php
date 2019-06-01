<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Pemberitahuan_model extends CI_Model {

	public $_table;
	public $_primary_key;

	public function __construct()
	{
		parent::__construct();
		$this->_table = "pemberitahuan";
		$this->_primary_key = "id";
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

	public function getAllAjax($level,$start,$perPage)
	{
		$this->db->select('pemberitahuan.*, pengguna.photo, pengguna.username, pengguna.nama_pengguna');
		$this->db->where('pemberitahuan.level', $level);
		$this->db->order_by('pemberitahuan.id', 'DESC');
		$this->db->from("pemberitahuan");
		$this->db->join('pengguna', 'pengguna.id_pengguna = pemberitahuan.user_id', 'left');
      	$this->db->limit($start, $perPage);
		$query = $this->db->get();
		$data = $query->result();
		return $data;
	}

	function getRows($params = array(),$level){
		$this->db->select('pemberitahuan.*, pengguna.photo, pengguna.username, pengguna.nama_pengguna, pengguna.level AS user_level');
        $this->db->from($this->_table);
		$this->db->join('pengguna', 'pengguna.id_pengguna = pemberitahuan.user_id', 'left');
        $this->db->where('pemberitahuan.level', $level);
		// $this->db->where(array("pemberitahuan.tanggal >= " => date("Y-m-d", strtotime("-60 days"))));
        //fetch data by conditions
        if(array_key_exists("where",$params)){
            foreach ($params['where'] as $key => $value){
                $this->db->where($key,$value);
            }
        }
        
        if(array_key_exists("order_by",$params)){
            $this->db->order_by($params['order_by']);
        }
        
        if(array_key_exists("id",$params)){
            $this->db->where('id',$params['id']);
            $query = $this->db->get();
            $result = $query->row_array();
        }else{
            //set start and limit
            if(array_key_exists("start",$params) && array_key_exists("limit",$params)){
                $this->db->limit($params['limit'],$params['start']);
            }elseif(!array_key_exists("start",$params) && array_key_exists("limit",$params)){
                $this->db->limit($params['limit']);
            }
            
            if(array_key_exists("returnType",$params) && $params['returnType'] == 'count'){
                $result = $this->db->count_all_results();
            }else{
                $query = $this->db->get();
                $result = ($query->num_rows() > 0)?$query->result_array():FALSE;
            }
        }

        //return fetched data
        return $result;
    }

	public function getAll($level)
	{
		$this->db->select('pemberitahuan.*, pengguna.photo, pengguna.username, pengguna.level AS user_level');
		$this->db->where('pemberitahuan.level', $level);
		// $this->db->where('pemberitahuan.status', 1);

		$this->db->where(array("pemberitahuan.tanggal >= " => date("Y-m-d", strtotime("-60 days"))));

		$this->db->order_by('pemberitahuan.created_at', 'DESC');
		$this->db->from($this->_table);
		$this->db->join('pengguna', 'pengguna.id_pengguna = pemberitahuan.user_id', 'left');
		$data = $this->db->get();
		return $data->result();
	}

	public function getReadDataItem($id,$level) // baca notifikasi per item
	{
		$this->db->where($this->_primary_key,$id);
		$this->db->where("level",$level);
		return $this->db->update($this->_table,array("status" => 0)); // ubah status 0 = sudah di baca
	}

	public function getReadDataAll($level) // baca notifikasi all
	{
		$where = array("level" => $level, "status" => 1);
		$this->db->where($where);
		return $this->db->update($this->_table,array("status" => 0)); // ubah status 0 = sudah di baca
	}

	public function getCountBadge($level)
	{	
		$where = array(
						"status"	=>	1,
						"level"		=>	$level,
					);
		$this->db->where($where);
		$this->db->from($this->_table);
		$data = $this->db->count_all_results();
		return $data;
	}

	public function getById($id)
	{
		$this->db->where($this->_primary_key,$id);
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

}

/* End of file Pemberitahuan_model.php */
/* Location: ./application/models/Pemberitahuan_model.php */