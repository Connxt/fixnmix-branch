<?php

class Receipt {
	private $data = array();

	function __construct($id, $user_id, $sales_report_id, $is_reported, $created_at, $updated_at) {
		$this->data['id'] = $id;
		$this->data['user_id'] = $user_id;
		$this->data['sales_report_id'] = $sales_report_id;
		$this->data['is_reported'] = $is_reported;
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