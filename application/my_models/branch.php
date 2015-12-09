<?php

class Branch {
	private $data = array();

	function __construct($id, $description) {
		$this->data['id'] = $id;
		$this->data['description'] = $description;
	}

	public function __set($key, $value) {
		$this->data[$key] = $value;
	}

	public function __get($key) {
		return isset($this->data[$key]) ? $this->data[$key] : null;
	}
}