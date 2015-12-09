<?php

class Returned_Item {
	private $data = array();

	function __construct($id, $return_id, $item_id, $quantity) {
		$this->data['id'] = $id;
		$this->data['return_id'] = $return_id;
		$this->data['item_id'] = $item_id;
		$this->data['quantity'] = $quantity;
	}

	public function __set($key, $value) {
		$this->data[$key] = $value;
	}

	public function __get($key) {
		return isset($this->data[$key]) ? $this->data[$key] : null;
	}
}