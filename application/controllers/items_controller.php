<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
session_start();
require_once(APPPATH . 'libraries/REST_Controller.php');

class Items_Controller extends REST_Controller {
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
		$data['current_page'] = 'items';
		$this->load->view('items/index', $data);
	}

	/**
	 * Item List
	 */
	public function item_exists_post() {
		$items_repo = new Items_Repository($this->base_model->get_db_instance());
		echo $items_repo->item_exists($this->input->post('itemId'));
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


	/**
	 * Receive Items
	 */
	public function receive_items_post() {
		$delivery_data = $this->input->post('deliveryData'); // should be a json object not a string

		if($delivery_data['transaction'] == Transaction_Type::Deliver_Items) {
 			$deliveries_repo = new Deliveries_Repository($this->base_model->get_db_instance());
			$delivery_id = $deliveries_repo->new_delivery($delivery_data);

			if($delivery_id <= 0) {
				if($delivery_id == -2)
					echo -2; // invalid main
				else if($delivery_id == -1)
					echo -1; // invalid branch
				else if($delivery_id == 0)
					echo 0; // delivery exists
			}
			else if($delivery_id >= 1) {
				$items_repo = new Items_Repository($this->base_model->get_db_instance());
				$items = $delivery_data['items'];

				foreach($items as $item) {
					if($items_repo->item_exists($item['item_id'])) {
						$item_info = $items_repo->get_item($item['item_id']);
						$items_repo->update_item(new Item(
							$item_info->id,
							$item['description'],
							$item_info->quantity + $item['quantity'],
							$item['price'],
							null,
							null
						));
					}
					else {
						$items_repo->new_item(new Item(
							$item['item_id'],
							$item['description'],
							$item['quantity'],
							$item['price'],
							null,
							null
						));
					}
					
					$deliveries_repo->new_item(
						new Delivered_Item(
							null, 
							$delivery_id, 
							$item['item_id'], 
							$item['quantity']
						)
					);
				}

				echo 2; // valid delivery
			}
		}
		else {
			echo 1; // invalid transaction
		}
	}

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

	public function is_transaction_valid_post() {
		$main_id = $this->input->post('mainId');
		$branch_id = $this->input->post('branchId');
		$delivery_id_from_main = $this->input->post('deliveryIdFromMain');
		$transaction = $this->input->post('transaction');

		$settings_repo = new Settings_Repository($this->base_model->get_db_instance());
		$deliveries_repo = new Deliveries_Repository($this->base_model->get_db_instance());


		if($transaction == Transaction_Type::Deliver_Items) {
			if($settings_repo->get_settings()->main_id != $main_id)
				echo -2; // invalid main
			else if($settings_repo->get_settings()->app_id != $branch_id)
				echo -1; // invalid branch
			else if($deliveries_repo->delivery_exists_via_delivery_id_from_main($delivery_id_from_main))
				echo 0; // return exists
			else
				echo 2; // valid delivery
		}
		else {
			echo 1; // invalid transaction
		}
	}

	public function decrypt_delivery_data_post() {
		$enc = new Encryption();
		echo $enc->decrypt($this->input->post('deliveryData'));
	}

	/**
	 * Return Items
	 */
	public function new_return_post() {
		$items = $this->input->post('items'); // [ {itemId: 401, quantity: 20}, {itemId: 402, quantity: 10} ]

		$returns_repo = new Returns_Repository($this->base_model->get_db_instance());
		$items_repo = new Items_Repository($this->base_model->get_db_instance());

		$return_id = $returns_repo->new_return($items);

		foreach($items as $item) {
			$item_info = $items_repo->get_item($item['itemId']);
			$items_repo->update_item(
				new Item(
					$item['itemId'],
					$item_info->description,
					$item_info->quantity - $item['quantity'],
					$item_info->price,
					null,
					null
				)
			);
		}

		echo $returns_repo->to_return_json($return_id);
	}

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

	public function write_return_data_to_file_post() {
		$file_path = $this->input->post('filePath'); // e:\sample_path.json
		$return_data = $this->input->post('returnData'); // unparsed json string
		$return_id = $this->input->post('returnId');
		
		$enc = new Encryption();
		$file_size = file_put_contents($file_path, $enc->encrypt($return_data));
		if($file_size >= 1) {
			$returns_repo = new Returns_Repository($this->base_model->get_db_instance());
			$returns_repo->update_return_status($return_id, Return_Status::Success);
		}

		echo $file_size; // if >= 1, write is successful
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
}