<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Base_model extends CI_Model {
	function __construct() {
		parent::__construct();
	}

	public function get_db_instance() {
		return $this->db;
	}
}