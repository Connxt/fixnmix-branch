<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
session_start();
require_once(APPPATH . 'libraries/REST_Controller.php');

class Logout_Controller extends REST_Controller {
	public function __construct() {
		parent::__construct();
		
		if(!$this->session->userdata('branch_auth')) {
			redirect('login', 'refresh');
		}
	}

	public function index_get() {
		$this->session->unset_userdata('branch_auth');
		session_destroy();
		redirect('login', 'refresh');
	}
}