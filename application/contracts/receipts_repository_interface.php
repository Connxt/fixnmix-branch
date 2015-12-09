<?php

interface Receipts_Repository_Interface {
	public function new_receipt($user_id, array $items);
	public function receipt_exists($receipt_id);
	public function get_receipt($receipt_id);
	public function get_all_receipts();
	public function get_all_items_from_receipt($receipt_id);
	public function get_all_unreported_receipts();
};