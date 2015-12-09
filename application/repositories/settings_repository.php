<?php

class Settings_Repository implements Settings_Repository_Interface {
	private $db;

	function __construct($db) {
		$this->db = $db;
	}

	public function set_default_save_path($path) {
		$this->db->update('settings', array(
			'default_save_path' => $path
		));
	}
	
	public function get_settings() {
		$query = $this->db->query('SELECT * FROM settings');
		return $query->row();
	}
}