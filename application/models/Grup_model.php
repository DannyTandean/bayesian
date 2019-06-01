<?php

/**
*
*/
class Grup_model extends CI_Model
{
	var $select_column = array("kode", "grup", "keterangan");
	var $order_column = array("id_grup", "grup", null, null, null);
	var $order = array('id_grup' => 'desc'); // default order
	public function create()
	{
    $this->db->select('id_grup');
    $query = $this->db->get('master_grup');
    $row = $query->last_row();
		$data = array(
			'kode' => "GRU-".($row->id_grup+1),
			'grup' => $this->input->post('namagrup'),
			'keterangan' => $this->input->post('keterangan')
		);

		$sql = $this->db->insert('master_grup', $data);

		if($sql === true) {
			return true;
		} else {
			return false;
		}
	} // /create function

	public function edit($id = null)
	{
		if($id) {
			$data = array(
        'grup' => $this->input->post('editnamagrup'),
  			'keterangan' => $this->input->post('editKet')
			);

			$this->db->where('id_grup', $id);
			$sql = $this->db->update('master_grup', $data);

			if($sql === true) {
				return true;
			} else {
				return false;
			}
		}

	}

	public function fetchMemberData($id = null)
	{
		if($id) {
			$sql = "SELECT * FROM master_grup WHERE id_grup = ?";
			$query = $this->db->query($sql, array($id));
				return $query->row_array();
		}

		$sql = "SELECT * FROM master_grup";
		$query = $this->db->query($sql);
		return $query->result_array();
	}

	public function remove($id = null) {
		if($id) {
			$sql = "DELETE FROM master_grup WHERE id_grup = ?";
			$query = $this->db->query($sql, array($id));

			// ternary operator
			return ($query === true) ? true : false;
		} // /if
	}
	function make_query()
			 {
						$this->db->select("*");
						$this->db->from("master_grup");
						if($this->input->post('search') != "")
						{
								 $this->db->like("grup", $this->input->post('value'));
								 $this->db->or_like("kode", $this->input->post('value'));
						}
						if($this->input->post('order') != "")
						{
								$this->db->order_by($this->$order_column[$_POST['order']['0']['column']], $_POST['order']['0']['dir']);

						}
						else {
							$this->db->order_by('id_grup', 'DESC');
						}
			 }
	public function get_filtered_data(){
       $this->make_query();
       $query = $this->db->get();
       return $query->num_rows();
	}
	public function get_all_data()
  {
       $this->db->select("*");
       $this->db->from("master_grup");
       return $this->db->count_all_results();
 }


}
