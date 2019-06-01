<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Welcome extends CI_Controller {

	/**
	 * Index Page for this controller.
	 *
	 * Maps to the following URL
	 * 		http://example.com/index.php/welcome
	 *	- or -
	 * 		http://example.com/index.php/welcome/index
	 *	- or -
	 * Since this controller is set as the default controller in
	 * config/routes.php, it's displayed at http://example.com/
	 *
	 * So any other public methods not prefixed with an underscore will
	 * map to /index.php/welcome/<method_name>
	 * @see https://codeigniter.com/user_guide/general/urls.html
	 */
	public function index()
	{
		$this->load->view('home');
	}

	public function __construct()
	{
		parent::__construct();
		$this->load->model('app_model');
	}

	public function ceklogin(){
		if(isset($_POST['login'])){
			$this->form_validation->set_rules('username', 'Username', 'required');
			$this->form_validation->set_rules('password', 'Password', 'required');
			$username = $this->input->post('username');
			$password = $this->input->post('password');
			$cek = $this->app_model->veriflogin($username,$password);
			$hasil = count($cek);
			if($this->form_validation->run())
			{
					if($hasil> 0){
						$this->session->set_userdata('username',$username);
						$login = $this->db->get_where('pengguna', array('username' => $username, 'password' => $password ))->row();
						if($login->level == '1'){
							redirect(base_url()."index.php/welcome/home");
						}elseif($login->level == '2'){
							redirect(base_url()."index.php/welcome/home");
						}elseif($login->level == '3'){
							redirect(base_url()."index.php/welcome/home");
						}

					}else{
						redirect(base_url()."index.php/welcome/ceklogin");
					}
			}
		}
		$this->load->view('auth-normal-sign-in');
	}

	public function home(){
		$this->load->view('home');
	}

	public function hrd(){
		$this->load->view('hrd');
	}

	public function owner(){
		$this->load->view('owner');
	}

	public function admin(){
		$this->load->view('admin');
	}

}
