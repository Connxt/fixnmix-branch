<?php

class Items_Repository implements Items_Repository_Interface {
	private $db;

	function __construct($db) {
		$this->db = $db;
	}

	public function new_item(Item $item) {
		$this->db->insert('items', array(
			'id' => $item->id,
			'description' => $item->description,
			'quantity' => $item->quantity,
			'price' => $item->price,
			'created_at' => date('Y-m-d H:i:s'),
			'updated_at' => date('Y-m-d H:i:s')
		));
	}

	public function update_item(Item $item) {
		$this->db->where('id', $item->id);
		$data = array(
			'description' => $item->description,
			'quantity' => $item->quantity,
			'price' => $item->price,
			'updated_at' => date('Y-m-d H:i:s')
		);
		$this->db->update('items', $data);
	}

	public function get_item($item_id) {
		$query = $this->db->query('SELECT * FROM items WHERE id=' . $item_id);
		$row = $query->row();
		return new Item(
			$row->id,
			$row->description,
			$row->quantity,
			$row->price,
			$row->created_at,
			$row->updated_at
		);
	}

	public function get_all_items() {
		$query = $this->db->query('SELECT * FROM items');
		$result = $query->result();
		$items = array();

		foreach($result as $row) {
			array_push($items,
				new Item(
					$row->id,
					$row->description,
					$row->quantity,
					$row->price,
					$row->created_at,
					$row->updated_at
				)
			);
		}

		return $items;
	}

	public function item_exists($item_id) {
		$query = $this->db->query('SELECT id FROM items WHERE id=' . $item_id);
		if($query->num_rows() >= 1)
			return true;
		else
			return false;
	}

	public function delete_item($item_id) {
		$deliveries_repo = new Deliveries_Repository($this->db);

		if($deliveries_repo->is_item_already_delivered($item_id)) {
			return false;
		}
		else {
			$this->db->delete('items', array('id' => $item_id));
			return true;
		}
	}
}

?>