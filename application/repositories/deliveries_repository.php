<?php

class Deliveries_Repository implements Deliveries_Repository_Interface {
	private $db;

	function __construct($db) {
		$this->db = $db;
	}

	public function new_delivery(array $delivery_data) {
		$delivery_id_from_main = $delivery_data['id'];
		$branch_id = $delivery_data['branch_id'];
		$main_id = $delivery_data['main_id'];

		$settings_repo = new Settings_Repository($this->db);

		if($settings_repo->get_settings()->app_id != $branch_id) {
			return -1; // invalid branch
		}
		else if($settings_repo->get_settings()->main_id != $main_id) {
			return -2; // invalid main
		}
		else {
			$query = $this->db->query('SELECT id FROM deliveries WHERE delivery_id_from_main=' . $delivery_id_from_main);

			if($query->num_rows() < 1) {
				$this->db->insert('deliveries', array(
					'delivery_id_from_main' => $delivery_id_from_main,
					'created_at' => date('Y-m-d H:i:s'),
					'updated_at' => date('Y-m-d H:i:s')
				));
				return $this->db->insert_id();
			}
			else {
				return 0; // delivery exists
			}
		}
	}

	public function new_item(Delivered_Item $delivered_item) {
		$this->db->insert('delivered_items', array(
			'delivery_id' => $delivered_item->delivery_id,
			'item_id' => $delivered_item->item_id,
			'quantity' => $delivered_item->quantity
		));	
	}

	public function delivery_exists($delivery_id) {
		$query = $this->db->query('SELECT id FROM deliveries WHERE id=' . $delivery_id);
		if($query->num_rows() >= 1)
			return true;
		else
			return false;
	}

	public function delivery_exists_via_delivery_id_from_main($delivery_id_from_main) {
		$query = $this->db->query('SELECT id FROM deliveries WHERE delivery_id_from_main=' . $delivery_id_from_main);
		if($query->num_rows() >= 1)
			return true;
		else
			return false;
	}

	public function get_delivery($delivery_id) {
		$query = $this->db->query('SELECT * FROM deliveries WHERE id=' . $delivery_id);
		$row = $query->row();
		return new Delivery(
			$row->id,
			$row->$delivery_id_from_main,
			$row->created_at,
			$row->updated_at
		);
	}

	public function get_all_deliveries() {
		$query = $this->db->query('SELECT * FROM deliveries');
		$result = $query->result();
		$deliveries = array();

		foreach($result as $row) {
			array_push($deliveries,
				new Delivery(
					$row->id,
					$row->delivery_id_from_main,
					$row->created_at,
					$row->updated_at
				)
			);
		}

		return $deliveries;
	}

	public function get_item($delivered_item_id) {
		$query = $this->db->query('SELECT * FROM delivered_items WHERE id=' . $delivered_item_id);
		$row = $query->row();
		return new Delivered_Item(
			$row->id,
			$row->delivery_id,
			$row->item_id,
			$row->quantity
		);
	}

	public function get_all_items_from_delivery($delivery_id) {
		$query = $this->db->query('SELECT * FROM delivered_items WHERE delivery_id=' . $delivery_id);
		$result = $query->result();
		$delivered_items = array();

		foreach($result as $row) {
			array_push($delivered_items,
				new Delivered_Item(
					$row->id,
					$row->delivery_id,
					$row->item_id,
					$row->quantity
				)
			);
		}

		return $delivered_items;
	}
}