<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
session_start();
require_once(APPPATH . 'libraries/REST_Controller.php');

class Settings_Controller extends REST_Controller {
	public function __construct() {
		parent::__construct();

		if(!$this->session->userdata('branch_auth')) {
			redirect('login', 'refresh');
		}

		$this->load->model('base_model');
	}

	public function index_get() {
		
	}

	public function set_default_save_path_post() {
		$file_path = $this->input->post('path');
		$settings_repo = new Settings_Repository($this->base_model->get_db_instance());
		$settings_repo->set_default_save_path($file_path);
	}

	public function get_default_save_path_post() {
		$settings_repo = new Settings_Repository($this->base_model->get_db_instance());
		echo $settings_repo->get_settings()->default_save_path;
	}

	public function get_app_id_post() {
		$settings_repo = new Settings_Repository($this->base_model->get_db_instance());
		echo $settings_repo->get_settings()->app_id;
	}

	public function get_main_id_post() {
		$settings_repo = new Settings_Repository($this->base_model->get_db_instance());
		echo $settings_repo->get_settings()->main_id;
	}
}