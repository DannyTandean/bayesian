<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class LogPembayaran_model extends CI_Model {

	public $_table;
	public $_primary_key;

	public function __construct()
	{
		parent::__construct();
		$this->_table = "log_pembayaran";
		$this->_primary_key = "id_log_pinjaman";
	}

	public function setQueryDataTable($search,$where=false,$after=null,$before=null)
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
		if($where)
		{
				$dataWhere = array(
											'status' => $where,
											'sisa !=' => 0,
											'cara_pembayaran' => "Cash"
			 							);
				$this->db->where($dataWhere);
		}
		$this->db->select('log_pembayaran.*,nama,departemen,jabatan,jumlah,bayar,sisa,cara_pembayaran,cicilan,log_pembayaran.keterangan');
		$this->db->join('aktivitas_pinjaman', 'log_pembayaran.id_pinjaman = aktivitas_pinjaman.id_pinjaman');
		$this->db->join('master_karyawan', 'aktivitas_pinjaman.id_karyawan = master_karyawan.id');
		$this->db->join('master_departemen', 'master_karyawan.id_departemen = master_departemen.id_departemen');
		$this->db->join('master_jabatan', 'master_karyawan.id_jabatan = master_jabatan.id_jabatan');
		$this->db->group_start()->or_like($search)->group_end();

	}

	public function findDataTable($columnsOrderBy,$search,$where=false,$after=null,$before=null)
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
		self::setQueryDataTable($search,$where,$after,$before);

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

	public function findDataTableOutput($data=null,$search=false,$after,$before)
	{
		$input = $this->input;

		// query data table
		self::setQueryDataTable($search,$after,$before);
		// $this->db->where('status', $where);
		$getCount = $this->db->count_all_results();

		$response = new stdClass();
		$response->draw = !empty($input->post("draw")) ? $input->post("draw"):null;
		$response->recordsTotal = $getCount;
		$response->recordsFiltered = $getCount;
		$response->data = $data;

		self::json($response);
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
		$this->db->join('master_departemen', 'master_karyawan.id_departemen = master_departemen.id_departemen');
		$this->db->join('master_jabatan', 'master_karyawan.id_jabatan = master_jabatan.id_jabatan');
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

	public function getMoney()
	{
		$this->db->select('jumlah,bayar,sisa');
		$this->db->where('status',"Diterima");
		$query = $this->db->get($this->_table);
		return $query->result();
	}

	public function getIdKaryawan($id)
	{
		$this->db->where(array("master_karyawan.id" => $id));
		$this->db->select("master_karyawan.kode_karyawan, master_karyawan.nama, master_karyawan.foto, master_departemen.departemen, master_jabatan.jabatan");
		$this->db->from("master_karyawan");
		$this->db->join("master_departemen","master_departemen.id_departemen = master_karyawan.id_departemen","LEFT");
		$this->db->join("master_jabatan","master_jabatan.id_jabatan = master_karyawan.id_jabatan","LEFT");
		return $this->db->get()->row();
	}

	public function DataLogPembayaran($id,$data)
	{
		// $sql = 'update aktivitas_pinjaman set sisa = ? where id_karyawan = ?';
		$this->db->where($this->_primary_key,$id);
		// return $this->db->query($sql,$data);
		return $this->db->update($this->_table,$data);
	}

	public function get_setting()
	{
		$query = $this->db->get('settings_general');
		return $query->row();
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

			/*insert data Pinjaman*/
			$dataPinjaman = $this->db->insert($this->_table,$data);

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

	public function getById1($id)
	{
		$this->db->where($this->_primary_key,$id);
		$this->db->select("log_pembayaran.*,master_karyawan.id,master_karyawan.kode_karyawan, master_karyawan.nama, master_karyawan.foto, master_departemen.departemen, master_jabatan.jabatan");
		$this->db->join('aktivitas_pinjaman', 'log_pembayaran.id_pinjaman = aktivitas_pinjaman.id_pinjaman');
		$this->db->join('master_karyawan', 'aktivitas_pinjaman.id_karyawan = master_karyawan.id');
		$this->db->join('master_departemen', 'master_karyawan.id_departemen = master_departemen.id_departemen');
		$this->db->join('master_jabatan', 'master_karyawan.id_jabatan = master_jabatan.id_jabatan');
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

			/*update data Pinjaman*/
			$this->db->where($this->_primary_key,$id);
			$dataPinjaman = $this->db->update($this->_table,$data);

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

	public function delete($id,$dataNotif=null)
	{
		if($dataNotif == null)
		{
			$this->db->where($this->_primary_key,$id);
			$this->db->delete($this->_table);
			return $this->db->affected_rows();
		}
		else {
			$this->db->trans_start();

			/*delete data Pinjaman*/
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

	public function getTotalPembayaran()
	{
		$this->db->select_sum('pembayaran');
		$result = $this->db->get('log_pembayaran');

		return $result->row();
	}
}

/* End of file Pinjaman_model.php */
/* Location: ./application/models/aktivitas/Pinjaman_model.php */
