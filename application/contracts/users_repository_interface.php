<?php

interface Users_Repository_Interface {
	public function import_users(array $users_data);
	public function user_exists_via_username_and_password($username, $password);
	public function user_exists_via_id($user_id);
	public function user_exists_via_username($username);
	public function get_user_via_id($user_id);
	public function get_user_via_username($username);
	public function get_all_users();
}