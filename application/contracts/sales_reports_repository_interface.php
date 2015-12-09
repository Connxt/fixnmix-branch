<?php

interface Sales_Reports_Repository_Interface {
	public function new_sales_report();
	public function update_sales_report_status($sales_report_id, $status);
	public function sales_report_exists($sales_report_id);
	public function get_sales_report($sales_report_id);
	public function get_all_sales_reports();
	public function to_export_sales_report_json($sales_report_id);
}