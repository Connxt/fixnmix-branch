<?php

class Sales_Report {
	private $data = array();

	function __construct($id, $status, $created_at, $updated_at) {
		$this->data['id'] = $id;
		$this->data['status'] = $status;
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