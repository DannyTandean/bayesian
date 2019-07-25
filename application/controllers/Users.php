<?php
defined('BASEPATH') OR exit('No direct script access allowed');
class Users extends MY_Controller {
	public function __construct()
	{
		parent::__construct();
		$this->load->model("users_model","usersModel");
	}
	public function index()
	{
		parent::checkLoginUser(); // check login user
		// parent::headerTitle("Pengguna","Pengguna");
		//
		// $breadcrumbs = array(
		// 					"Pengguna"	=>	site_url('users'),
		// 				);
		// parent::breadcrumbs($breadcrumbs);
		//
		// parent::view();
		redirect(base_url()."aktivitas/transaction");
	}
}
/* End of file Users.php */
/* Location: ./application/controllers/Users.php */
