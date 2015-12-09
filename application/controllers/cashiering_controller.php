<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
session_start();
require_once(APPPATH . 'libraries/REST_Controller.php');

class Cashiering_Controller extends REST_Controller {
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
		$data['current_page'] = 'cashiering';
		$this->load->view('cashiering/index', $data);
	}

	public function get_all_items_post() {
		$items_repo = new Items_Repository($this->base_model->get_db_instance());
		$items = $items_repo->get_all_items();
		$data = array();

		foreach($items as $item) {
			array_push($data, array(
				'id' => $item->id,
				'description' => $item->description,
				'quantity' => $item->quantity,
				'price' => $item->price,
				'created_at' => $item->created_at,
				'updated_at' => $item->updated_at
			));
		}
		echo json_encode($data);
	}

	public function get_item_info_post() {
		$items_repo = new Items_Repository($this->base_model->get_db_instance());
		$item = $items_repo->get_item($this->input->post('itemId'));
		$data = array();

		array_push($data, array(
			'id' => $item->id,
			'description' => $item->description,
			'quantity' => $item->quantity,
			'price' => $item->price,
			'created_at' => $item->created_at,
			'updated_at' => $item->updated_at
		));

		echo json_encode($data);
	}

	public function item_exists_post() {
		$items_repo = new Items_Repository($this->base_model->get_db_instance());
		echo $items_repo->item_exists($this->input->post('itemId'));
	}

	public function is_item_quantity_enough_post() {
		$item_id = $this->input->post('itemId');
		$requested_quantity = $this->input->post('requestedQuantity');

		$items_repo = new Items_Repository($this->base_model->get_db_instance());
		$available_quantity = $items_repo->get_item($item_id)->quantity;

		if($available_quantity >= $requested_quantity)
			echo 1;
		else {
			echo json_encode(array(
				'requested_quantity' => $requested_quantity,
				'available_quantity' => $available_quantity
			));
		}
	}

	public function get_items_with_insufficient_quantity_post() {
		$items = $this->input->post('items'); // [ {itemId: 401, quantity: 20}, {itemId: 402, quantity: 10} ]
		$data = array();
		$items_repo = new Items_Repository($this->base_model->get_db_instance());

		foreach($items as $item) {
			$item_info = $items_repo->get_item($item['itemId']);
			if($item_info->quantity < $item['quantity']) {
				array_push($data, array(
						'id' => $item['itemId'],
						'requested_quantity' => $item['quantity'],
						'available_quantity' => $item_info->quantity
					)
				);
			}
		}

		echo json_encode($data);
	}

	public function get_items_that_do_not_exist_post() {
		$item_ids = $this->input->post('itemIds'); // [1, 2, 3, 4, 5]
		$items_repo = new Items_Repository($this->base_model->get_db_instance());
		$data = array();

		foreach($item_ids as $item_id) {
			if(!$items_repo->item_exists($item_id)) {
				array_push($data, $item_id);
			}
		}

		echo json_encode($data); // outputs the id of the items that does not exist
	}

	public function save_transaction_post() {
		/** 
		 * 	[$items description]
		 * 	@var   [json]
		 *	@example
		 *	[
		 *		{"itemId": 1, "price": 100, "quantity": 2},
		 *		{"itemId": 2, "price": 100, "quantity": 4}
		 *	]
		 */
		$items = $this->input->post('items');
		$session_data = $this->session->userdata('branch_auth');
		$user_id = $session_data['user_id'];

		$items_repo = new Items_Repository($this->base_model->get_db_instance());

		foreach($items as $item) {
			$item_info = $items_repo->get_item($item['itemId']);

			$items_repo->update_item(new Item(
				$item_info->id,
				$item_info->description,
				$item_info->quantity - $item['quantity'],
				$item_info->price,
				null,
				null
			));
		}

		$receipts_repo = new Receipts_Repository($this->base_model->get_db_instance());
		$receipts_repo->new_receipt($user_id, $items);

		echo json_encode($items);
	}
}