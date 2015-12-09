<?php

class Sales_Reports_Repository implements Sales_Reports_Repository_Interface {
	private $db;

	function __construct($db) {
		$this->db = $db;
	}

	public function new_sales_report() {
		$receipts_repo = new Receipts_Repository($this->db);
		$unreported_receipts = $receipts_repo->get_all_unreported_receipts();

		if(count($unreported_receipts) >= 1) {
			$this->db->insert('sales_reports', array(
				'status' => Sales_Report_Status::Failed,
				'created_at' => date('Y-m-d H:i:s'),
				'updated_at' => date('Y-m-d H:i:s')
			));
			$sales_report_id = $this->db->insert_id();

			$receipts = array();
			foreach($unreported_receipts as $unreported_receipt) {
				$receipts_repo->update_sales_report_info($unreported_receipt->id, $sales_report_id);
			}

			return $sales_report_id; // returns the sales_report_id
		}
		else {
			return 0; // there are no new sales to report
		}
	}

	public function update_sales_report_status($sales_report_id, $status) {
		$this->db->where('id', $sales_report_id);
		$this->db->update('sales_reports', array(
			'status' => $status,
			'updated_at' => date('Y-m-d H:i:s')
		));
	}

	public function sales_report_exists($sales_report_id) {
		$query = $this->db->query('SELECT id FROM sales_reports WHERE id=' . $sales_report_id);
		if($query->num_rows() >= 1)
			return true;
		else
			return false;
	}

	public function get_sales_report($sales_report_id) {
		$query = $this->db->query('SELECT * FROM sales_reports WHERE id=' . $sales_report_id);
		$row = $query->row();
		return new Sales_Report(
			$row->id,
			$row->status,
			$row->created_at,
			$row->updated_at
		);
	}

	public function get_all_sales_reports() {
		$query = $this->db->query('SELECT * FROM sales_reports');
		$result = $query->result();
		$sales_reports = array();

		foreach($result as $row) {
			array_push($sales_reports,
				new Sales_Report(
					$row->id,
					$row->status,
					$row->created_at,
					$row->updated_at
				)
			);
		}

		return $sales_reports;
	}

	public function to_export_sales_report_json($sales_report_id) {
		$receipts_repo = new Receipts_Repository($this->db);
		$settings_repo = new Settings_Repository($this->db);
		$users_repo = new Users_Repository($this->db);

		$receipts = $receipts_repo->get_all_receipts_via_sales_report_id($sales_report_id);
		$sales = array();
		foreach($receipts as $receipt) {
			$items = array();
			$receipt_items = $receipts_repo->get_all_items_from_receipt($receipt->id);

			foreach($receipt_items as $receipt_item) {
				array_push($items, array(
					'item_id' => $receipt_item->item_id,
					'price' => $receipt_item->price,
					'quantity' => $receipt_item->quantity
				));
			}

			array_push($sales, array(
				'receipt_id' => $receipt->id,
				'username' => $users_repo->get_user_via_id($receipt->user_id)->username,
				'created_at' => $receipt->created_at,
				'updated_at' => $receipt->updated_at,
				'items' => $items
			));
		}

		$data = array(
			'transaction' => Transaction_Type::Export_Sales_Report,
			'id' => $sales_report_id,
			'main_id' => $settings_repo->get_settings()->main_id,
			'branch_id' => $settings_repo->get_settings()->app_id,
			'sales' => $sales
		);

		return json_encode($data);
	}
}