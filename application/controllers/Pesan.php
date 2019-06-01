<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Pesan extends MY_Controller {

	public function __construct()
	{
		parent::__construct();
		
	}

	public function index()
	{
		$this->load->library('Db_firebase');

		$firebase = json_encode($this->db_firebase->notif());

		$dataInsert = array(
								"keterangan"	=>	$this->username." Mengetest data baru.",
								"tanggal"		=>	date("Y-m-d"),
								"jam"			=>	date("H:i"),
								"url_direct"	=>	"url_direct",
								"user_id"		=>	$this->user_id,
								"level"			=>	$this->user_level
							);
		$insert = $this->db_firebase->insert($dataInsert);
		$insert = json_encode($insert);

		$data = array( 
						"pesan" 	=> 	"Data pesan test.",
						"firebase"	=>	$firebase,
						"insert"	=>	$insert,
					);

		$this->load->view('index_pesan', $data);
	}

}

/* End of file Pesan.php */
/* Location: ./application/controllers/Pesan.php */