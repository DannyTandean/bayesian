<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Crud_grup extends CI_Controller {
	public function __construct() {
		parent::__construct();


		$this->load->model('Grup_model',"grupModel");
	}

	public function index()
	{
		$this->load->view('crud_grup');
	}

	public function create()
	{

		$validator = array('success' => false, 'messages' => array());
		$config = array(
	    array(
        'field' => 'namagrup',
        'label' => 'nama Grup',
        'rules' => 'trim|required'
	    )
		);

		$this->form_validation->set_rules($config);
		$this->form_validation->set_error_delimiters('<p class="text-danger">','</p>');

		if($this->form_validation->run() === true) {

			$createMember = $this->grupModel->create();

			if($createMember === true) {
				$validator['success'] = true;
				$validator['messages'] = "Successfully added";
			} else {
				$validator['success'] = false;
				$validator['messages'] = "Error while updating the infromation";
			}
		}
		else {
			$validator['success'] = false;
			foreach ($_POST as $key => $value) {
				$validator['messages'][$key] = form_error($key);
			}
		}

		echo json_encode($validator);
	}

	public function fetchMemberData()
	{
		$result = array('data' => array(),
		"draw"            =>     	$this->input->post('draw'),
		"recordsTotal"    =>      $this->grupModel->get_all_data(),
		"recordsFiltered" =>     $this->grupModel->get_filtered_data()
	);

		$data = $this->grupModel->fetchMemberData();
		foreach ($data as $key => $value) {

			// button

			$buttonsedit = '<button type="button" class="btn btn-success btn-outline-success waves-effect md-trigger"  data-toggle="modal" data-target="#editMemberModal" onclick="editMember('.$value['id_grup'].')">Edit</button>';

      $buttonsremove = '<button type="button" class="btn btn-danger btn-outline-danger waves-effect " onclick="removeMember('.$value['id_grup'].')" >Remove</button>';

			$result['data'][$key] = array(
				$value['id_grup'],
        $value['grup'],
				$value['keterangan'],
				$buttonsedit,
        $buttonsremove
			);
		} // /foreach

		echo json_encode($result);
	}

	public function getSelectedMemberInfo($id)
	{
		if($id) {
			$data = $this->grupModel->fetchMemberData($id);
			echo json_encode($data);
		}
	}

	public function edit($id = null)
	{
		if($id) {
			$validator = array('success' => false, 'messages' => array());

			$config = array(
		    array(
	        'field' => 'editnamagrup',
	        'label' => 'editNamagrup',
	        'rules' => 'trim|required'
		    )
			);

			$this->form_validation->set_rules($config);
			$this->form_validation->set_error_delimiters('<p class="text-danger">','</p>');

			if($this->form_validation->run() === true) {
				$editMember = $this->grupModel->edit($id);

				if($editMember === true) {
					$validator['success'] = true;
					$validator['messages'] = "Successfully updated";
				} else {
					$validator['success'] = false;
					$validator['messages'] = "Error while updating the infromation";
				}
			}
			else {
				$validator['success'] = false;
				foreach ($_POST as $key => $value) {
					$validator['messages'][$key] = form_error($key);
				}
			}

			echo json_encode($validator);
		}
	}

	public function remove($id = null)
	{
		if($id) {
			$validator = array('success' => false, 'messages' => array());

			$removeMember = $this->grupModel->remove($id);
			if($removeMember === true) {
				$validator['success'] = true;
				$validator['messages'] = "Successfully removed";
			}
			else {
				$validator['success'] = true;
				$validator['messages'] = "Successfully removed";
			}

			echo json_encode($validator);
		}
	}


}
