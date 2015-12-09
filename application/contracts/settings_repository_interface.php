<?php

interface Settings_Repository_Interface {
	public function set_default_save_path($path);
	public function get_settings();
}