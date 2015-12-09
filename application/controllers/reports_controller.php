<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
session_start();
require_once(APPPATH . 'libraries/REST_Controller.php');

class Reports_Controller extends REST_Controller {
	public function __construct() {
		parent::__construct();

		if(!$this->session->userdata('branch_auth')) {
			redirect('login', 'refresh');
		}
		
		$this->load->model('base_model');
	}

	public function index_get() {
		$session_data = $this->session->userdata('branch_auth');
		$data['user_id'] = $session_data['user_id'];
		$data['name'] = $session_data['name'];
		$data['app_id'] = $session_data['app_id'];
		$data['current_page'] = 'reports';
		$this->load->view('reports/index', $data);
	}

	/**
	 * Sales
	 */
	public function new_sales_report_post() {
		$sales_reports_repo = new Sales_Reports_Repository($this->base_model->get_db_instance());
		$sales_report_id = $sales_reports_repo->new_sales_report();
		if($sales_report_id >= 1) {
			echo $sales_reports_repo->to_export_sales_report_json($sales_report_id);
		}
		else {
			echo 0; // there are no receipts to report
		}
	}

	public function get_all_receipts_to_report_post() {
		$receipts_repo = new Receipts_Repository($this->base_model->get_db_instance());
		$data = array();
		$receipts = array();
		$grand_total = 0;
		foreach($receipts_repo->get_all_unreported_receipts() as $receipt) {
			$total_amount = 0;
			foreach($receipts_repo->get_all_items_from_receipt($receipt->id) as $receipt_item) {
				$total_amount += ($receipt_item->price * $receipt_item->quantity);
			}
			$grand_total += $total_amount;
			array_push($receipts, array(
				'id' => $receipt->id,
				'total_amount' => $total_amount
			));
		}

		array_push($data, array(
			'grand_total' => $grand_total,
			'receipts' => $receipts
		));

		echo json_encode($data);
	}

	public function write_sales_report_data_to_file_post() {
		$file_path = $this->input->post('filePath'); // e:\sample_path.json
		$sales_report_data = $this->input->post('salesReportData'); // unparsed json string
		$sales_report_id = $this->input->post('salesReportId');
		
		$enc = new Encryption();
		$file_size = file_put_contents($file_path, $enc->encrypt($sales_report_data));
		
		if($file_size >= 1) {
			$sales_reports_repo = new Sales_Reports_Repository($this->base_model->get_db_instance());
			$sales_reports_repo->update_sales_report_status($sales_report_id, Sales_Report_Status::Success);
		}

		echo $file_size; // if >= 1, write is successful
	}

	public function generate_sales_report_data_post() {
		$file_path = $this->input->post('filePath'); // e:\sample_path.json
		$sales_report_id = $this->input->post('salesReportId');

		$sales_reports_repo = new Sales_Reports_Repository($this->base_model->get_db_instance());
		$sales_report_data = $sales_reports_repo->to_export_sales_report_json($sales_report_id);

		$enc = new Encryption();
		$file_size = file_put_contents($file_path, $enc->encrypt($sales_report_data));

		if($file_size >= 1) {
			$sales_reports_repo->update_sales_report_status($sales_report_id, Sales_Report_Status::Success);
		}
		else {
			$sales_reports_repo->update_sales_report_status($sales_report_id, Sales_Report_Status::Failed);	
		}

		echo $file_size; // if >= 1, write is successful
	}

	public function sales_report_exists_post() {
		$sales_reports_repo = new Sales_Reports_Repository($this->base_model->get_db_instance());
		echo $sales_reports_repo->sales_report_exists($this->input->post('salesReportId'));
	}

	public function get_all_sales_reports_post() {
		$sales_reports_repo = new Sales_Reports_Repository($this->base_model->get_db_instance());
		$receipts_repo = new Receipts_Repository($this->base_model->get_db_instance());
		$data = array();

		foreach($sales_reports_repo->get_all_sales_reports() as $sales_report) {
			$total_amount = 0;
			foreach($receipts_repo->get_all_receipts_via_sales_report_id($sales_report->id) as $receipt) {
				foreach($receipts_repo->get_all_items_from_receipt($receipt->id) as $receipt_item) {
					$total_amount += ($receipt_item->price * $receipt_item->quantity);
				}
			}

			array_push($data, array(
				'id' => $sales_report->id,
				'status' => $sales_report->status,
				'total_amount' => $total_amount,
				'created_at' => $sales_report->created_at,
				'updated_at' => $sales_report->updated_at
			));
		}

		echo json_encode($data);
	}

	public function get_all_receipts_via_sales_report_id_post() {
		$receipts_repo = new Receipts_Repository($this->base_model->get_db_instance());
		$sales_report_id = $this->input->post('salesReportId');
		$data = array();
		
		foreach($receipts_repo->get_all_receipts_via_sales_report_id($sales_report_id) as $receipt) {
			$receipt_items = $receipts_repo->get_all_items_from_receipt($receipt->id);
			$total_amount = 0;

			foreach($receipt_items as $receipt_item) {
				$total_amount += ($receipt_item->price * $receipt_item->quantity);
			}

			array_push($data, array(
				'id' => $receipt->id,
				'total_amount' => $total_amount,
				'created_at' => $receipt->created_at
			));
		}

		echo json_encode($data);
	}

	/**
	 * Receipts
	 */
	public function receipt_exists_post() {
		$receipts_repo = new Receipts_Repository($this->base_model->get_db_instance());
		echo $receipts_repo->receipt_exists($this->input->post('receiptId'));
	}

	public function get_receipt_post() {
		$receipts_repo = new Receipts_Repository($this->base_model->get_db_instance());
		$receipt = $receipts_repo->get_receipt($this->input->post('receiptId'));
		$data = array();

		array_push($data, array(
			'id' => $receipt->id,
			'sales_report_id' => $receipt->sales_report_id,
			'is_reported' => $receipt->is_reported,
			'created_at' => $receipt->created_at,
			'updated_at' => $receipt->updated_at
		));

		echo json_encode($data);
	}

	public function get_all_receipts_post() {
		$receipts_repo = new Receipts_Repository($this->base_model->get_db_instance());
		$receipts = $receipts_repo->get_all_receipts();
		$data = array();

		foreach($receipts as $receipt) {
			$receipt_items = $receipts_repo->get_all_items_from_receipt($receipt->id);
			$total_amount = 0;

			foreach($receipt_items as $receipt_item) {
				$total_amount += ($receipt_item->price * $receipt_item->quantity);
			}

			array_push($data, array(
				'id' => $receipt->id,
				'sales_report_id' => $receipt->sales_report_id,
				'is_reported' => $receipt->is_reported,
				'total_amount' => $total_amount,
				'created_at' => $receipt->created_at,
				'updated_at' => $receipt->updated_at
			));
		}

		echo json_encode($data);
	}

	public function get_all_items_from_this_receipt_post() {
		$receipts_repo = new Receipts_Repository($this->base_model->get_db_instance());
		$receipt_items = $receipts_repo->get_all_items_from_receipt($this->input->post('receiptId'));
		$data = array();

		foreach($receipt_items as $receipt_item) {
			$items_repo = new Items_Repository($this->base_model->get_db_instance());
			$item = $items_repo->get_item($receipt_item->item_id);

			array_push($data, array(
				'id' => $receipt_item->id,
				'item_id' => $receipt_item->item_id,
				'receipt_id' => $receipt_item->receipt_id,
				'price' => $receipt_item->price,
				'quantity' => $receipt_item->quantity
			));
		}

		echo json_encode($data);
	}

	/**
	 * Returns
	 */
	public function return_exists_post() {
		$returns_repo = new Returns_Repository($this->base_model->get_db_instance());
		echo $returns_repo->return_exists($this->input->post('returnId'));
	}

	public function get_return_post() {
		$returns_repo = new Returns_Repository($this->base_model->get_db_instance());
		$return = $returns_repo->get_return($this->input->post('returnId'));
		$data = array();

		array_push($data, array(
			'id' => $return->id,
			'status' => $return->status,
			'created_at' => $return->created_at,
			'updated_at' => $return->updated_at
		));

		echo json_encode($data);
	}

	public function get_all_returns_post() {
		$returns_repo = new Returns_Repository($this->base_model->get_db_instance());
		$returns = $returns_repo->get_all_returns();
		$data = array();

		foreach($returns as $return) {
			array_push($data, array(
				'id' => $return->id,
				'created_at' => $return->created_at,
				'status' => $return->status
			));
		}

		echo json_encode($data);
	}

	public function get_all_items_from_this_return_post() {
		$returns_repo = new Returns_Repository($this->base_model->get_db_instance());
		$returned_items = $returns_repo->get_all_items_from_return($this->input->post('returnId'));
		$data = array();

		foreach($returned_items as $returned_item) {
			$items_repo = new Items_Repository($this->base_model->get_db_instance());
			$item = $items_repo->get_item($returned_item->item_id);

			array_push($data, array(
				'id' => $returned_item->id,
				'return_id' => $returned_item->return_id,
				'item_id' => $returned_item->item_id,
				'description' => $item->description,
				'quantity' => $returned_item->quantity
			));
		}

		echo json_encode($data);
	}

	public function generate_return_data_post() {
		$file_path = $this->input->post('filePath'); // e:\sample_path.json
		$return_id = $this->input->post('returnId');

		$returns_repo = new Returns_Repository($this->base_model->get_db_instance());
		$return_data = $returns_repo->to_return_json($return_id);

		$enc = new Encryption();
		$file_size = file_put_contents($file_path, $enc->encrypt($return_data));

		if($file_size >= 1) {
			$returns_repo->update_return_status($return_id, Return_Status::Success);
		}
		else {
			$returns_repo->update_return_status($return_id, Return_Status::Failed);	
		}

		echo $file_size; // if >= 1, write is successful
	}


	/**
	 * Deliveries
	 */
	public function delivery_exists_post() {
		$deliveries_repo = new Deliveries_Repository($this->base_model->get_db_instance());
		echo $deliveries_repo->delivery_exists($this->input->post('deliveryId'));
	}

	public function get_all_deliveries_post() {
		$deliveries_repo = new Deliveries_Repository($this->base_model->get_db_instance());
		$deliveries = $deliveries_repo->get_all_deliveries();
		$data = array();

		foreach($deliveries as $delivery) {
			array_push($data, array(
				'id' => $delivery->id,
				'delivery_id_from_main' => $delivery->delivery_id_from_main,
				'created_at' => $delivery->created_at
			));
		}

		echo json_encode($data);
	}

	public function get_all_items_from_this_delivery_post() {
		$deliveries_repo = new Deliveries_Repository($this->base_model->get_db_instance());
		$delivered_items = $deliveries_repo->get_all_items_from_delivery($this->input->post('deliveryId'));
		$data = array();

		foreach($delivered_items as $delivered_item) {
			$items_repo = new Items_Repository($this->base_model->get_db_instance());
			$item = $items_repo->get_item($delivered_item->item_id);

			array_push($data, array(
				'id' => $delivered_item->id,
				'delivery_id' => $delivered_item->delivery_id,
				'item_id' => $delivered_item->item_id,
				'description' => $item->description,
				'quantity' => $delivered_item->quantity
			));
		}

		echo json_encode($data);
	}
}