<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
session_start();
require_once(APPPATH . 'libraries/REST_Controller.php');

class Login_Controller extends REST_Controller {
	public function __construct() {
		parent::__construct();
		
		if($this->session->userdata('branch_auth')) {
			redirect('cashiering', 'refresh');
		}

		$this->load->model('base_model');
	}

	public function index_get() {
		$session_data = $this->session->userdata('branch_auth');
		$data['user_id'] = $session_data['user_id'];
		$data['name'] = $session_data['name'];
		$data['app_id'] = $session_data['app_id'];
		$data['current_page'] = 'login';
		$this->load->view('login/index', $data);
	}

	public function login_post() {
		$username = $this->input->post('username');
		$password = $this->input->post('password');

		$user_repo = new Users_Repository($this->base_model->get_db_instance());

		if($user_repo->user_exists_via_username_and_password($username, $password)) {
			$user = $user_repo->get_user_via_username($username);
			$data = array();

			array_push($data, array(
				'id' => $user->id,
				'username' => $user->username,
				'password' => $user->password,
				'user_level_id' => $user->user_level_id,
				'user_level' => $user->user_level,
				'last_name' => $user->last_name,
				'first_name' => $user->first_name,
				'middle_name' => $user->middle_name,
				'created_at' => $user->created_at,
				'updated_at' => $user->updated_at
			));
			
			$settings_repo = new Settings_Repository($this->base_model->get_db_instance());

			$this->session->set_userdata('branch_auth', array(
				'user_id' => $user->id,
				'name' => $user->first_name . ' ' . $user->last_name,
				'app_id' => $settings_repo->get_settings()->app_id
			));

			echo json_encode($data);
		}
		else
			echo 0;
	}
}