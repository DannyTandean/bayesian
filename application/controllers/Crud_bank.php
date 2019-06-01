<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Crud_bank extends CI_Controller {
	public function __construct() {
		parent::__construct();


		$this->load->model('Bank_model',"bankModel");
	}

	public function index()
	{
		$this->load->view('crud_bank');
	}

	public function create()
	{

		$validator = array('success' => false, 'messages' => array());
		$config = array(
	    array(
        'field' => 'namabank',
        'label' => 'nama bank',
        'rules' => 'trim|required'
	    )
		);

		$this->form_validation->set_rules($config);
		$this->form_validation->set_error_delimiters('<p class="text-danger">','</p>');

		if($this->form_validation->run() === true) {

			$createMember = $this->bankModel->create();

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
		$result = array('data' => array());

		$data = $this->bankModel->fetchMemberData();
		foreach ($data as $key => $value) {

			// button
			$buttonsedit = '<button type="button" class="btn btn-success btn-outline-success waves-effect md-trigger"  data-toggle="modal" data-target="#editMemberModal" onclick="editMember('.$value['id_bank'].')">Edit</button>';

      $buttonsremove = '<button type="button" class="btn btn-danger btn-outline-danger waves-effect " onclick="removeMember('.$value['id_bank'].')" >Remove</button>';

			$result['data'][$key] = array(
				$value['id_bank'],
        $value['bank'],
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
			$data = $this->bankModel->fetchMemberData($id);
			echo json_encode($data);
		}
	}

	public function edit($id = null)
	{
		if($id) {
			$validator = array('success' => false, 'messages' => array());

			$config = array(
		    array(
	        'field' => 'editnamabank',
	        'label' => 'editNamabank',
	        'rules' => 'trim|required'
		    )
			);

			$this->form_validation->set_rules($config);
			$this->form_validation->set_error_delimiters('<p class="text-danger">','</p>');

			if($this->form_validation->run() === true) {
				$editMember = $this->bankModel->edit($id);

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

			$removeMember = $this->bankModel->remove($id);
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
