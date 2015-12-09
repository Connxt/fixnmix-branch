<?php

interface Branches_Repository_Interface {
	public function new_branch(Branch $branch);
	public function update_branch(Branch $branch);
	public function get_branch($branch_id);
	public function get_all_branches();
	public function branch_exists($branch_id);
}