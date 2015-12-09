<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
session_start();
require_once(APPPATH . 'libraries/REST_Controller.php');

class Test_Page_Controller extends REST_Controller {
	public function __construct() {
		parent::__construct();

		if(!$this->session->userdata('branch_auth')) {
			redirect('login', 'refresh');
		}

		$this->load->model('base_model');
	}

	public function index_get() {
		$data['current_page'] = 'test_page';
		$this->load->view('test_page/index', $data);

		$this->test_reports();
		$this->get_all_sales_report();
	}

	private function test_reports() {
		$sales_reports_repo = new Sales_Reports_Repository($this->base_model->get_db_instance());
		$sales_report_id = $sales_reports_repo->new_sales_report();
		if($sales_report_id >= 1) {
			echo $sales_reports_repo->to_export_sales_report_json($sales_report_id);
		}
		else {
			echo 0; // there are no receipts to report
		}
	}

	private function get_all_sales_report() {
		$sales_reports_repo = new Sales_Reports_Repository($this->base_model->get_db_instance());
		$receipts_repo = new Receipts_Repository($this->base_model->get_db_instance());

		$data = array();

		foreach($sales_reports_repo->get_all_sales_reports() as $sales_report) {
			$total_amount = 0;
			foreach($receipts_repo->get_all_receipts_via_sales_report_id($sales_report->id) as $receipt) {
				foreach($receipts_repo->get_all_items_from_receipt($receipt->id) as $receipt_item) {
					$total_amount += $receipt_item->price;
				}
			}

			array_push($data, array(
				'id' => $sales_report->id,
				'total_amount' => $total_amount,
				'created_at' => $sales_report->created_at,
				'updated_at' => $sales_report->updated_at
			));
		}

		echo json_encode($data);
	}
}