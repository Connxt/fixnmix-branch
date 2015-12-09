<?php

class Receipts_Repository implements Receipts_Repository_Interface {
	private $db;

	function __construct($db) {
		$this->db = $db;
	}

	public function new_receipt($user_id, array $items) {
		$this->db->insert('receipts', array(
			'user_id' => $user_id,
			'is_reported' => Receipt_Report_Status::Unreported,
			'created_at' => date('Y-m-d H:i:s'),
			'updated_at' => date('Y-m-d H:i:s')
		));
		$receipt_id = $this->db->insert_id();

		foreach($items as $item) {
			$this->db->insert('receipt_items', array(
				'item_id' => $item['itemId'],
				'receipt_id' => $receipt_id,
				'price' => $item['price'],
				'quantity' => $item['quantity']
			));
		}
	}

	public function update_sales_report_info($receipt_id, $sales_report_id) {
		$this->db->where('id', $receipt_id);
		$this->db->update('receipts', array(
			'sales_report_id' => $sales_report_id,
			'is_reported' => Receipt_Report_Status::Is_Reported
		));
	}

	public function receipt_exists($receipt_id) {
		$query = $this->db->query('SELECT id FROM receipts WHERE id=' . $receipt_id);
		if($query->num_rows() >= 1)
			return true;
		else
			return false;
	}

	public function get_receipt($receipt_id) {
		$query = $this->db->query('SELECT * FROM receipts WHERE id=' . $receipt_id);
		$row = $query->row();
		return new Receipt(
			$row->id,
			$row->user_id,
			$row->sales_report_id,
			$row->is_reported,
			$row->created_at,
			$row->updated_at
		);
	}

	public function get_all_receipts() {
		$query = $this->db->query('SELECT * FROM receipts');
		$result = $query->result();
		$receipts = array();

		foreach($result as $row) {
			array_push($receipts,
				new Receipt(
					$row->id,
					$row->user_id,
					$row->sales_report_id,
					$row->is_reported,
					$row->created_at,
					$row->updated_at
				)
			);
		}

		return $receipts;
	}

	public function get_all_receipts_via_sales_report_id($sales_report_id) {
		$query = $this->db->query('SELECT * FROM receipts WHERE sales_report_id=' . $sales_report_id);
		$result = $query->result();
		$receipts = array();

		foreach($result as $row) {
			array_push($receipts,
				new Receipt(
					$row->id,
					$row->user_id,
					$row->sales_report_id,
					$row->is_reported,
					$row->created_at,
					$row->updated_at
				)
			);
		}

		return $receipts;
	}

	public function get_all_items_from_receipt($receipt_id) {
		$query = $this->db->query('SELECT * FROM receipt_items WHERE receipt_id=' . $receipt_id);
		$result = $query->result();
		$receipt_items = array();

		foreach($result as $row) {
			array_push($receipt_items,
				new Receipt_Item(
					$row->id,
					$row->item_id,
					$row->receipt_id,
					$row->price,
					$row->quantity
				)
			);
		}

		return $receipt_items;
	}

	public function get_all_unreported_receipts() {
		$query = $this->db->query('SELECT * FROM receipts WHERE is_reported=' . Receipt_Report_Status::Unreported);
		$result = $query->result();
		$receipts = array();

		foreach($result as $row) {
			array_push($receipts,
				new Receipt(
					$row->id,
					$row->user_id,
					$row->sales_report_id,
					$row->is_reported,
					$row->created_at,
					$row->updated_at
				)
			);
		}

		return $receipts;
	}
}