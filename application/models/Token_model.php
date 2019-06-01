<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Token_model extends CI_Model {

	public $_table;
	public $_primary_key;

	public function __construct()
	{
		parent::__construct();
		$this->_table = "token";
		$this->_primary_key = "id_token";
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

	public function getById($id)
	{
		$this->db->where($this->_primary_key,$id);
		$query = $this->db->get($this->_table);
		return $query->row();
	}

  public function getByToken($token)
	{
		$this->db->where("level",$token);
		$query = $this->db->get($this->_table);
		return $query->result();
	}

	public function getByIdfp($idfp)
	{
		$this->db->where("idfp",$idfp);
		$query = $this->db->get($this->_table);
		return $query->result();
	}

	public function getByIdfpCount($idfp)
	{
		$this->db->where("idfp",$idfp);
		$query = $this->db->get($this->_table);
		return $query->num_rows();
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

/* End of file Token_model.php */
/* Location: ./application/models/Token_model.php */
