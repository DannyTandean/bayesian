<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Pemberitahuan extends MY_Controller {

	public function __construct()
	{
		parent::__construct();
		$this->load->model('Pemberitahuan_model',"notifModel");
		$this->perPage = 4;
	}

	public function index()
	{
		parent::checkLoginUser(); // check login user

		parent::headerTitle("Pemberitahuan","Pemberitahuan");

		$conditions['order_by'] = "pemberitahuan.id DESC";
        $conditions['limit'] = $this->perPage;
        $data = $this->notifModel->getRows($conditions,$this->user_level);
		parent::viewData(array('posts' => $data));

		parent::view();
	}

    public function loadMoreData()
    {
		parent::checkLoginUser(); // check login user
        $conditions = array();
        // Get last post ID
        $lastID = $this->input->post('id');
        
        // Get post rows num
        $conditions['where'] = array('id <'=>$lastID);
        $conditions['returnType'] = 'count';
        $data['postNum'] = $this->notifModel->getRows($conditions,$this->user_level);
        
        // Get posts data from the database
        $conditions['returnType'] = '';
        $conditions['order_by'] = "pemberitahuan.id DESC";
        $conditions['limit'] = $this->perPage;
        $data['posts'] = $this->notifModel->getRows($conditions,$this->user_level);
        
        $data['postLimit'] = $this->perPage;
        
        // Pass data to view
        $this->load->view('pemberitahuan/load-more', $data, false);
    }

	public function getAllData()
	{
		parent::checkLoginUser(); // check login user

		if ($this->isPost()) {
			$data = $this->notifModel->getAll($this->user_level);
			if ($data) {

				foreach ($data as $item) {
					$srcPhoto = base_url().'assets/images/default/no_user.png';
					if ($item->photo != "") {
						$srcPhoto = base_url()."uploads/pengguna/".$item->photo;
					}

					$item->photo = $srcPhoto;
				}

				$this->response->status = true;
				$this->response->message = "Data All Pemberitahuan approvel. user level = ".$this->user_level;
				$this->response->data = $data;
				$this->response->count_badge = $this->notifModel->getCountBadge($this->user_level);
			}
		}
		parent::json();
	}

	public function readPerItem($id)
	{
		parent::checkLoginUser(); // check login user

		if ($this->isPost()) {
			$data = $this->notifModel->getReadDataItem($id,$this->user_level);
			if ($data) {
				$getId = $this->notifModel->getById($id);
				if ($getId) {
					$data = $getId;
				}
				$this->response->status = true;
				$this->response->message = "Data Pemberitahuan per item.";
				$this->response->data = $data;
			}
		}
		parent::json();
	}

	public function readAll()
	{
		parent::checkLoginUser(); // check login user

		if ($this->isPost()) {
			$data = $this->notifModel->getReadDataAll($this->user_level);
			if ($data) {
				$this->response->status = true;
				$this->response->message = "Data Pemberitahuan read all.";
				$this->response->data = $data;
			} else {
				$this->response->message = alertDanger("Opps, Gagal baca semua pemberitahuan.!");
			}
		}
		parent::json();
	}

}

/* End of file Pemberitahuan.php */
/* Location: ./application/controllers/Pemberitahuan.php */