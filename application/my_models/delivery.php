<?php

class Delivery {
	private $data = array();
	
	function __construct($id, $delivery_id_from_main, $created_at, $updated_at) {
		$this->data['id'] = $id;
		$this->data['delivery_id_from_main'] = $delivery_id_from_main;
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