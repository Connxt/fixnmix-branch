<?php

class User_Levels_Repository implements User_levels_Repository_Interface {
	private $db;

	function __construct($db) {
		$this->db = $db;
	}

	public function get_user_level_id($user_level) {
		$query = $this->db->query('SELECT user_level_id FROM user_levels WHERE user_level=' . $user_level);
		$row = $query->row();
		return $row->user_level_id;
	}

	public function get_user_level($user_level_id) {
		$query = $this->db->query('SELECT user_level FROM user_levels WHERE id=' . $user_level_id);
		$row = $query->row();
		return $row->user_level;
	}
}