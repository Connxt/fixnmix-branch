<?php

interface User_Levels_Repository_Interface {
	public function get_user_level_id($user_level);
	public function get_user_level($user_level_id);
}