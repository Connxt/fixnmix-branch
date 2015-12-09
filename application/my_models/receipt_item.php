<?php

class Receipt_Item {
	private $data = array();

	function __construct($id, $item_id, $receipt_id, $price, $quantity) {
		$this->data['id'] = $id;
		$this->data['item_id'] = $item_id;
		$this->data['receipt_id'] = $receipt_id;
		$this->data['price'] = $price;
		$this->data['quantity'] = $quantity;
	}

	public function __set($key, $value) {
		$this->data[$key] = $value;
	}

	public function __get($key) {
		return isset($this->data[$key]) ? $this->data[$key] : null;
	}
}