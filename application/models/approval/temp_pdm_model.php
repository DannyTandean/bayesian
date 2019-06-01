<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Temp_pdm_model extends CI_Model {

	public $_table;
	public $_primary_key;

	public function __construct()
	{
		parent::__construct();
		$this->_table = "temp_aktivitas_promosi";
		$this->_primary_key = "temp_id_promosi";
	}

	public function setQueryDataTable($search)
	{
		$dataSearch = array();
		foreach ($search as $val) {
			$dataSearch[$val] = $this->input->post("search")["value"];
		}
		$search = $dataSearch;
		$this->db->from($this->_table);
		$this->db->join('master_karyawan', 'temp_aktivitas_promosi.id_karyawan = master_karyawan.id');
		$this->db->join('master_cabang', 'master_karyawan.id_cabang = master_cabang.id_cabang');
		$this->db->join('master_departemen', 'master_karyawan.id_departemen = master_departemen.id_departemen');
		$this->db->join('master_jabatan', 'master_karyawan.id_jabatan = master_jabatan.id_jabatan');
		$this->db->join('master_grup', 'master_karyawan.id_grup = master_grup.id_grup');
		// $this->db->join('master_shift', 'master_karyawan.shift = master_shift.id_shift');
		$this->db->join('master_golongan', 'master_karyawan.id_golongan = master_golongan.id_golongan');

		$this->db->order_by("create_at DESC");
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
		}else{
				$orderBy = array("create_at DESC");
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
		$this->db->where($this->_primary_key,$id);
		$this->db->select("temp_aktivitas_promosi.*,master_karyawan.id, master_karyawan.kode_karyawan, master_karyawan.nama, master_karyawan.foto, master_cabang.cabang, master_departemen.departemen,master_grup.grup,master_jabatan.jabatan");

		$this->db->join('master_karyawan', 'temp_aktivitas_promosi.id_karyawan = master_karyawan.id');
		$this->db->join('master_cabang', 'temp_aktivitas_promosi.id_cabang1 = master_cabang.id_cabang');
		$this->db->join('master_departemen', 'temp_aktivitas_promosi.id_departemen1 = master_departemen.id_departemen');
		$this->db->join('master_jabatan', 'temp_aktivitas_promosi.id_jabatan1 = master_jabatan.id_jabatan');
		$this->db->join('master_grup', 'temp_aktivitas_promosi.id_grup1 = master_grup.id_grup');
		// $this->db->join('master_shift', 'temp_aktivitas_promosi.id_shift = master_shift.id_shift');
		$this->db->join('master_golongan', 'temp_aktivitas_promosi.id_golongan1 = master_golongan.id_golongan');
		$query = $this->db->get($this->_table);
		return $query->row();
	}

	public function cabang($id_cabang)
	{
		$this->db->select("cabang");
		$this->db->where("id_cabang",$id_cabang);
		$query = $this->db->get("master_cabang");
		return $query->row();
	}
	public function departemen($id_departemen)
	{
		$this->db->select("departemen");
		$this->db->where("id_departemen",$id_departemen);
		$query = $this->db->get("master_departemen");
		return $query->row();
	}
	public function jabatan($id_jabatan)
	{
		$this->db->select("jabatan");
		$this->db->where("id_jabatan",$id_jabatan);
		$query = $this->db->get("master_jabatan");
		return $query->row();
	}
	public function golongan($id_golongan)
	{
		$this->db->select("golongan");
		$this->db->where("id_golongan",$id_golongan);
		$query = $this->db->get("master_golongan");
		return $query->row();
	}

	public function grup($id_grup)
	{
		$this->db->select("grup");
		$this->db->where("id_grup",$id_grup);
		$query = $this->db->get("master_grup");
		return $query->row();
	}

	public function getByIdDetail($id)
	{
		$select = "master_karyawan.kode_karyawan,master_cabang.id_cabang,master_departemen.id_departemen,master_jabatan.id_jabatan,master_grup.id_grup,master_golongan.id_golongan,keterangans";
		$this->db->select('id_promosi,kode_karyawan,nama,judul,cabang,departemen,jabatan,grup,golongan,keterangans');
		$this->db->join('master_karyawan', 'temp_aktivitas_promosi.id_karyawan = master_karyawan.id');
		$this->db->join('master_cabang', 'master_karyawan.id_cabang = master_cabang.id_cabang');
		$this->db->join('master_departemen', 'master_karyawan.id_departemen = master_departemen.id_departemen');
		$this->db->join('master_jabatan', 'master_karyawan.id_jabatan = master_jabatan.id_jabatan');
		$this->db->join('master_grup', 'master_karyawan.id_grup = master_grup.id_grup');
		$this->db->join('master_golongan', 'master_karyawan.id_golongan = master_golongan.id_golongan');
		$this->db->where($this->_primary_key,$id);
		$query = $this->db->get($this->_table);
		return $query->row_array();
	}

	public function getByIdDetail1($id)
	{

		$this->db->select('id_promosi,kode_karyawan,nama,judul,cabang,departemen,jabatan,grup,golongan,keterangans');
		$this->db->join('master_karyawan', 'temp_aktivitas_promosi.id_karyawan = master_karyawan.id');
		$this->db->join('master_cabang', 'temp_aktivitas_promosi.id_cabang1 = master_cabang.id_cabang');
		$this->db->join('master_departemen', 'temp_aktivitas_promosi.id_departemen1 = master_departemen.id_departemen');
		$this->db->join('master_jabatan', 'temp_aktivitas_promosi.id_jabatan1 = master_jabatan.id_jabatan');
		$this->db->join('master_grup', 'temp_aktivitas_promosi.id_grup1 = master_grup.id_grup');
		$this->db->join('master_golongan', 'temp_aktivitas_promosi.id_golongan1 = master_golongan.id_golongan');
		$this->db->where($this->_primary_key,$id);
		$query = $this->db->get($this->_table);
		return $query->row_array();
	}
	public function getByIdPromosiDetail($id)
	{
		$this->db->select('id_promosi,judul,id_cabang1,id_departemen1,id_jabatan1,id_grup1,id_golongan1,keterangans');
			$data = array('id_promosi' => $id,
			 						'status' => 'Proses'
		 							);
		$this->db->where($data);
		$query = $this->db->get($this->_table);
		return $query->row_array();
	}

	public function addTransaction($id,$idKaryawan,$datatemp,$data,$status,$dataNotif)
	{
		$this->db->trans_start();
		//Insert master golongan
		$datainsert = $this->db->insert('aktivitas_promosi', $data);

		//update status temp master golongan
		$status = array(
								"status"	=>	"Diterima",
								"approve_at"		=>	date("Y-m-d H:i:s"),
							);

		$this->db->update('temp_aktivitas_promosi',$status,array('temp_id_promosi' => $id ));

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

				$this->db->where('id', $idKaryawan);
				$dataKaryawan = $this->db->update('master_karyawan', $datatemp);
		$this->db->trans_complete();

		if ($this->db->trans_status() === FALSE){
        $this->db->trans_rollback();
				return false;
		}
		else
		{
        $this->db->trans_commit();
				return true;
		}
	}

	public function updateTransaction($id,$idpro,$idkaryawan,$data,$status,$dataNotif)
	{
		$this->db->trans_start();
		// //update PDM


		$this->db->where('id', $idkaryawan);
		$dataKaryawan = $this->db->update('master_karyawan', $data);

		//update status PDM

		$this->db->where('id_promosi', $idpro);
		$this->db->update('temp_aktivitas_promosi', $status,array('temp_id_promosi' => $id ));

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
		//Delete Aktivitas Promosi
		$this->db->delete('aktivitas_promosi', array('id_promosi' => $id));
		//update status temp aktivitas Promosi

		$this->db->where('id_promosi', $id);
		$this->db->update('temp_aktivitas_promosi', $status,array('id_promosi' => $id ));

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
		$this->db->select('id_promosi,tanggal,judul,id_cabang1,id_departemen1,id_jabatan1,id_grup1,id_golongan1,keterangans,');
		$this->db->where('temp_id_promosi', $id);
		// $this->db->where($this->_primary_key, $id);
		$query = $this->db->get($this->_table);
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
}

/* End of file Temp_pdm_model.php */
/* Location: ./application/models/approval/Temp_pdm_model.php */
