<?php

interface Items_Repository_Interface {
	public function new_item(Item $item);
	public function update_item(Item $item);
	public function get_item($item_id);
	public function get_all_items();
	public function item_exists($item_id);
	public function delete_item($item_id);
}