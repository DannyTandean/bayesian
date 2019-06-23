<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Auth extends MY_Controller {

	protected $response;
	var $user;

	public function __construct()
	{
		parent::__construct();
		$this->user = $this->session->admin;

		$this->response = new stdClass();
		$this->response->status = false;
		$this->response->message = "";
		$this->response->data = new stdClass();
		$this->load->model('Users_model',"usersModel");
	}

	public function index()
	{
		if ($this->user != null) {
			redirect(base_url(),'refresh');
		}

		$this->load->view('auth/index');
	}

	// public function isPost()
	// {
	// 	if (strtoupper($this->input->server("REQUEST_METHOD")) == "POST") {
	// 		return true;
	// 	} else {
	// 		// $this->response->status = false;
	// 		$this->response->message = "Not allowed get request!";
	// 		$this->response->data = null;
	// 		return false;
	// 	}
	// }

	// public function json($data = null)
	// {
	//    	$this->output->set_header("Content-Type: application/json; charset=utf-8");
	//    	$data = isset($data) ? $data : $this->response;
	//    	$this->output->set_content_type('application/json');
	//     	$this->output->set_output(json_encode($data));
 	//    	// echo json_encode($data);
	// }

	public function login_ajax()
	{
		if ($this->isPost()) {
			$this->form_validation->set_rules("username","Username","required");
			$this->form_validation->set_rules("password","Password","required");

			$username = $this->input->post("username");
			$password = $this->input->post("password");

			if ($this->form_validation->run() == true) {

				$where = array(
								"username"	=>	$username,
								"password"	=>	sha1($password)
							);
				$checkAuthentic = $this->usersModel->getByWhere($where);
				if ($checkAuthentic) {

					$this->response->status = true;
					$this->response->message = "<span style='color:blue; font-size: 30px;'><i class='fa fa-spinner fa-spin'></i> Mohon tunggu ....</span>";
					$this->response->data = $checkAuthentic;
					$this->session->set_userdata("admin",$checkAuthentic);

				} else {
					$this->response->message = "error check password";
					$this->response->error = array(
											"account"	=>	"<span style='color:red;'>Username atau Password yang dimasukan salah.!</span>",
										);
				}
			} else {
				$this->response->message = "error form data";
				$this->response->error = array(
										"username"	=>	form_error("username","<span style='color:red;'>","</span>"),
										"password"	=>	form_error("password","<span style='color:red;'>","</span>"),
									);
			}
		}
		parent::json();
	}

	public function logout()
	{
		$this->session->unset_userdata("admin");
		$this->session->sess_destroy();
		redirect("auth");
	}

}

/* End of file Auth.php */
/* Location: ./application/controllers/Auth.php */
