<?php

class User {
	private $data = array();

	function __construct($id, $username, $password, $user_level_id, $user_level, $last_name, $first_name, $middle_name, $created_at, $updated_at) {
		$this->data['id'] = $id;
		$this->data['username'] = $username;
		$this->data['password'] = $password;
		$this->data['user_level_id'] = $user_level_id;
		$this->data['user_level'] = $user_level;
		$this->data['last_name'] = $last_name;
		$this->data['first_name'] = $first_name;
		$this->data['middle_name'] = $middle_name;
		$this->data['created_at'] = $created_at;
		$this->data['updated_at'] = $updated_at;
	}

	public function __set($key, $value) {
		$this->data[$key] = $value;
	}

	public function __get($key) {
		return isset($this->data[$key]) ? $this->data[$key] : null;
	}
}