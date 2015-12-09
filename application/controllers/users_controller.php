<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
session_start();
require_once(APPPATH . 'libraries/REST_Controller.php');

class Users_Controller extends REST_Controller {
	public function __construct() {
		parent::__construct();
		
		if(!$this->session->userdata('branch_auth')) {
			redirect('login', 'refresh');
		}
		
		$this->load->model('base_model');
	}

	public function index_get() {
		$session_data = $this->session->userdata('branch_auth');
		$data['user_id'] = $session_data['user_id'];
		$data['name'] = $session_data['name'];
		$data['app_id'] = $session_data['app_id'];
		$data['current_page'] = "users";
		$this->load->view("users/index", $data);
	}

	public function import_users_post() {
		$users_data = $this->input->post('usersData'); // should be a json object not a string

		if($users_data['transaction'] == Transaction_Type::Export_Users) {
			$users_repo = new Users_Repository($this->base_model->get_db_instance());
			$import_status = $users_repo->import_users($users_data);

			if($import_status <= 0) {
				if($import_status == -1)
					echo -1; // invalid main
				else if($import_status == 0)
					echo 0; // invalid branch
			}
			else if($import_status >= 1) {
				echo 2; // valid users import
			}
		}
		else {
			echo 1; // invalid transaction
		}
	}

	public function username_exists_post() {
		$users_repo = new Users_Repository($this->base_model->get_db_instance());
		echo $users_repo->user_exists_via_username($this->input->post('username'));
	}

	public function get_user_via_id_post() {
		$users_repo = new Users_Repository($this->base_model->get_db_instance());
		$user = $users_repo->get_user_via_id($this->input->post('userId'));
		$data = array();

		array_push($data, array(
			'id' => $user->id,
			'username' => $user->username,
			'user_level_id' => $user->user_level_id,
			'user_level' => $user->user_level,
			'last_name' => $user->last_name,
			'first_name' => $user->first_name,
			'middle_name' => $user->middle_name,
			'created_at' => $user->created_at,
			'updated_at' => $user->updated_at
		));

		echo json_encode($data);
	}

	public function get_user_via_username_post() {
		$users_repo = new Users_Repository($this->base_model->get_db_instance());
		$user = $users_repo->get_user_via_username($this->input->post('username'));
		$data = array();

		array_push($data, array(
			'id' => $user->id,
			'username' => $user->username,
			'user_level_id' => $user->user_level_id,
			'user_level' => $user->user_level,
			'last_name' => $user->last_name,
			'first_name' => $user->first_name,
			'middle_name' => $user->middle_name,
			'created_at' => $user->created_at,
			'updated_at' => $user->updated_at
		));
		echo json_encode($data);
	}

	public function get_all_users_post() {
		$users_repo = new Users_Repository($this->base_model->get_db_instance());
		$users = $users_repo->get_all_users();
		$data = array();

		foreach($users as $user) {
			array_push($data, array(
				'id' => $user->id,
				'username' => $user->username,
				'user_level_id' => $user->user_level_id,
				'user_level' => $user->user_level,
				'last_name' => $user->last_name,
				'first_name' => $user->first_name,
				'middle_name' => $user->middle_name,
				'created_at' => $user->created_at,
				'updated_at' => $user->updated_at
			));
		}

		echo json_encode($data);
	}

	public function decrypt_users_data_post() {
		$enc = new Encryption();
		echo $enc->decrypt($this->input->post('usersData'));
	}
}