<?php

interface Uncleared_Items_Repository_Interface {
	public function new_item(Uncleared_Item $uncleared_item);
	public function update_item(Uncleared_Item $uncleared_item);
	public function get_item($uncleared_item_id);
	public function get_all_items();
	public function item_exists($uncleared_item_id);
	public function delete_item($uncleared_item_id);
}