<?php

interface Deliveries_Repository_Interface {
	public function new_delivery(array $delivery_data);
	public function delivery_exists($delivery_id);
	public function delivery_exists_via_delivery_id_from_main($delivery_id_from_main);
	public function get_delivery($delivery_id);
	public function get_all_deliveries();
	public function get_item($delivered_item_id);
	public function get_all_items_from_delivery($delivery_id);
}