<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Tmp_karyawan_model extends CI_Model {

	public $_table;
	public $_primary_key;

	public function __construct()
	{
		parent::__construct();
		$this->_table = "tmp_master_karyawan";
		$this->_primary_key = "id";
	}

	public function setQueryDataTable($select=false,$search)
	{
		$dataSearch = array();
		foreach ($search as $val) {
			$dataSearch[$val] = $this->input->post("search")["value"];
		}
		$search = $dataSearch;
		if ($select) {
			$this->db->select($select);
		}
		// $this->db->order_by("tmp_master_karyawan.created_at DESC");
		$this->db->from($this->_table);
		$this->db->group_start()->or_like($search)->group_end();
		$this->db->join('master_departemen', 'master_departemen.id_departemen = tmp_master_karyawan.id_departemen', 'left');
		$this->db->join('master_jabatan', 'master_jabatan.id_jabatan = tmp_master_karyawan.id_jabatan', 'left');

	}

	public function findDataTable($select,$columnsOrderBy,$search)
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
		self::setQueryDataTable($select,$search);

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

	public function findDataTableOutput($data=null,$select=false,$search=false)
	{
		$input = $this->input;
		
		// query data table
		self::setQueryDataTable($select,$search);

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

		/*insert data karyawan*/
		$this->db->insert($this->_table,$data);

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
		$this->db->insert("pemberitahuan",$dataPemberitahuan);

		$this->db->trans_complete();
		if ($this->db->trans_status() === FALSE){
		    $this->db->trans_rollback();
		    return FALSE;
		} else {
		    $this->db->trans_commit();
		    return TRUE;
		}
	}

	public function insertTransactionApprove($dataMaster,$idTmpMaster,$dataNotif) // for approval owner karyawan
	{
		$this->db->trans_start();

		$this->db->insert('master_karyawan', $dataMaster);

		$dataTmpMaster = array(
								"status_approve"	=>	"Diterima",
								"updated_at"		=>	date("Y-m-d H:i:s"),
							);

		$this->db->update('tmp_master_karyawan', $dataTmpMaster, array("id" => $idTmpMaster));

		$dataPemberitahuan = array(
									"keterangan"	=>	$dataNotif["keterangan"],
									"tanggal"		=>	date("Y-m-d"),
									"jam"			=>	date("H:i"),
									"url_direct"	=>	$dataNotif["url_direct"],
									"user_id"		=>	$dataNotif["user_id"],
									"level"			=>	$dataNotif["level"],
									"status"		=>	1, //aktif
								);
		$this->db->insert("pemberitahuan",$dataPemberitahuan);

		$this->db->trans_complete();
		if ($this->db->trans_status() === FALSE){
		    $this->db->trans_rollback();
		    return FALSE;
		} else {
		    $this->db->trans_commit();
		    return TRUE;
		}
	}

	public function updateTransactionApprove($kodeKaryawn,$dataMaster,$idTmpMaster,$dataNotif) // for approval owner karyawan
	{
		$this->db->trans_start();

		$this->db->update('master_karyawan', $dataMaster, array("kode_karyawan" => $kodeKaryawn));

		$dataTmpMaster = array(
								"status_approve"	=>	"Diterima",
								"updated_at"		=>	date("Y-m-d H:i:s"),
							);

		$this->db->update('tmp_master_karyawan', $dataTmpMaster, array("id" => $idTmpMaster));

		$dataPemberitahuan = array(
									"keterangan"	=>	$dataNotif["keterangan"],
									"tanggal"		=>	date("Y-m-d"),
									"jam"			=>	date("H:i"),
									"url_direct"	=>	$dataNotif["url_direct"],
									"user_id"		=>	$dataNotif["user_id"],
									"level"			=>	$dataNotif["level"],
									"status"		=>	1, // aktif
								);
		$this->db->insert("pemberitahuan",$dataPemberitahuan);

		$this->db->trans_complete();
		if ($this->db->trans_status() === FALSE){
		    $this->db->trans_rollback();
		    return FALSE;
		} else {
		    $this->db->trans_commit();
		    return TRUE;
		}
	}

	public function deleteTransactionApprove($kodeKaryawn,$idTmpMaster,$dataNotif) // for approval owner karyawan
	{
		$this->db->trans_start();

		$this->db->delete('master_karyawan',array("kode_karyawan" => $kodeKaryawn));

		$dataTmpMaster = array(
								"status_approve"	=>	"Diterima",
								"updated_at"		=>	date("Y-m-d H:i:s"),
							);

		$this->db->update('tmp_master_karyawan', $dataTmpMaster, array("id" => $idTmpMaster));

		$dataPemberitahuan = array(
									"keterangan"	=>	$dataNotif["keterangan"],
									"tanggal"		=>	date("Y-m-d"),
									"jam"			=>	date("H:i"),
									"url_direct"	=>	$dataNotif["url_direct"],
									"user_id"		=>	$dataNotif["user_id"],
									"level"			=>	$dataNotif["level"],
									"status"		=>	1, // aktif
								);
		$this->db->insert("pemberitahuan",$dataPemberitahuan);

		$this->db->trans_complete();
		if ($this->db->trans_status() === FALSE){
		    $this->db->trans_rollback();
		    return FALSE;
		} else {
		    $this->db->trans_commit();
		    return TRUE;
		}
	}

	public function getByWhere($data,$orderBy=false)
	{
		$this->db->where($data);
		if ($orderBy) {
			$this->db->order_by($orderBy);
		}
		$query = $this->db->get($this->_table);
		return $query->row();
	}

	public function getById($id,$select=false,$row_array=false)
	{

		$selectData = 'tmp_master_karyawan.*, master_cabang.cabang, master_departemen.departemen, master_jabatan.jabatan, master_golongan.golongan, master_grup.grup, master_bank.bank';

		if ($select) {
			$selectData = $select;
		}
		$this->db->select($selectData);
		$this->db->where(array("tmp_master_karyawan.id" => $id));
		$this->db->join('master_cabang', 'master_cabang.id_cabang = tmp_master_karyawan.id_cabang', 'left');
		$this->db->join('master_departemen', 'master_departemen.id_departemen = tmp_master_karyawan.id_departemen', 'left');
		$this->db->join('master_jabatan', 'master_jabatan.id_jabatan = tmp_master_karyawan.id_jabatan', 'left');
		$this->db->join('master_golongan', 'master_golongan.id_golongan = tmp_master_karyawan.id_golongan', 'left');
		$this->db->join('master_grup', 'master_grup.id_grup = tmp_master_karyawan.id_grup', 'left');
		$this->db->join('master_bank', 'master_bank.id_bank = tmp_master_karyawan.id_bank', 'left');
		$query = $this->db->get($this->_table);
		
		if ($row_array) {
			return $query->row_array();
		} else {
			return $query->row();
		}
	}

	public function getIdDetail($id)
	{
		$select = "kode_karyawan, idfp, tmp_master_karyawan.nama, tmp_master_karyawan.alamat, tempat_lahir, tgl_lahir, tmp_master_karyawan.telepon, no_wali, tmp_master_karyawan.email, tgl_masuk, kelamin, status_nikah, pendidikan, wn, agama, shift, status_kerja, ibu_kandung, suami_istri, tanggungan, npwp, tmp_master_karyawan.gaji, rekening, tmp_master_karyawan.id_bank, master_bank.bank, tmp_master_karyawan.id_departemen, master_departemen.departemen, tmp_master_karyawan.id_jabatan, master_jabatan.jabatan, tmp_master_karyawan.id_grup, master_grup.grup, tmp_master_karyawan.id_golongan, master_golongan.golongan, atas_nama, foto, tmp_master_karyawan.id_cabang, master_cabang.cabang, start_date, expired_date, jab_index, kontrak, file_kontrak, otoritas, periode_gaji, qrcode_file";
		return self::getById($id,$select,true);
	}

	public function checkStatusApprove($id)
	{
		$this->db->select("status_approve");
		$this->db->where(array("id" => $id));
		$query = $this->db->get($this->_table);
		
		return $query->row();
		
	}

	public function update($id,$data,$dataNotif)
	{

		$this->db->trans_start();

		/*update data karyawan*/
		$this->db->where($this->_primary_key,$id);
		$this->db->update($this->_table,$data);

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

	public function delete($id,$dataNotif)
	{

		$this->db->trans_start();

		/*delete data karyawan*/
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

/* End of file Tmp_karyawan_model.php */
/* Location: ./application/models/master/Tmp_karyawan_model.php */