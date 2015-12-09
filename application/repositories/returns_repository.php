<?php

class Returns_Repository implements Returns_Repository_Interface {
	private $db;

	function __construct($db) {
		$this->db = $db;
	}

	public function new_return(array $items) {
		$this->db->insert('returns', array(
			'status' => Return_Status::Failed,
			'created_at' => date('Y-m-d H:i:s'),
			'updated_at' => date('Y-m-d H:i:s')
		));

		$return_id = $this->db->insert_id();

		foreach($items as $item) {
			$this->db->insert('returned_items', array(
				'return_id' => $return_id,
				'item_id' => $item['itemId'],
				'quantity' => $item['quantity']
			));
		}

		return $return_id;
	}

	public function update_return_status($return_id, $status) {
		$this->db->where('id', $return_id);
		$this->db->update('returns', array(
			'status' => $status,
			'updated_at' => date('Y-m-d H:i:s')
		));
	}

	public function return_exists($return_id) {
		$query = $this->db->query('SELECT id FROM returns WHERE id=' . $return_id);
		if($query->num_rows() >= 1)
			return true;
		else
			return false;
	}

	public function get_return($return_id) {
		$query = $this->db->query('SELECT * FROM returns WHERE id=' . $return_id);
		$row = $query->row();
		return new Return_Class(
			$row->id,
			$row->status,
			$row->created_at,
			$row->updated_at
		);
	}

	public function get_all_returns() {
		$query = $this->db->query('SELECT * FROM returns');
		$result = $query->result();
		$returns = array();

		foreach($result as $row) {
			array_push($returns,
				new Return_Class(
					$row->id,
					$row->status,
					$row->created_at,
					$row->updated_at
				)
			);
		}

		return $returns;
	}

	public function to_return_json($return_id) {
		$settings_repo = new Settings_Repository($this->db);
		$main_id = $settings_repo->get_settings()->main_id;
		$branch_id = $settings_repo->get_settings()->app_id;

		$query = $this->db->query('SELECT item_id, quantity FROM returned_items WHERE return_id=' . $return_id);
		$items = $query->result();

		$data = array();

		array_push($data, array(
			'transaction' => Transaction_Type::Return_Items,
			'id' => $return_id,
			'main_id' => $main_id,
			'branch_id' => $branch_id,
			'items' => $items
		));

		return json_encode($data[0]);
	}

	public function get_item($returned_item_id) {
		$query = $this->db->query('SELECT * FROM returned_items WHERE id=' . $returned_item_id);
		$row = $query->row();

		return new Returned_Item(
			$row->id,
			$row->return_id,
			$row->item_id,
			$row->quantity
		);
	}

	public function get_all_items_from_return($return_id) {
		$query = $this->db->query('SELECT * FROM returned_items WHERE return_id=' . $return_id);
		$result = $query->result();
		$returned_items = array();

		foreach($result as $row) {
			array_push($returned_items,
				new Returned_Item(
					$row->id,
					$row->return_id,
					$row->item_id,
					$row->quantity
				)
			);
		}

		return $returned_items;
	}
}