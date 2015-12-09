<?php

interface Returns_Repository_Interface {
	public function new_return(array $items);
	public function update_return_status($return_id, $status);
	public function return_exists($return_id);
	public function get_return($return_id);
	public function get_all_returns();
	public function to_return_json($return_id);
	public function get_item($returned_item_id);
	public function get_all_items_from_return($return_id);
}