<section class="sidebar">
    <ul class="sidebar-menu">
    	<li class="header">MAIN NAVIGATION</li>
    	<li <?php if($current_page == "cashiering") echo "class='active'"; ?> ><a href="<?php echo base_url() . 'cashiering'; ?>"> Cashiering</a></li>
        <li <?php if($current_page == "items") echo "class='active'"; ?> ><a href="<?php echo base_url() . 'items'; ?>"> Items</a></li>
		<li <?php if($current_page == "reports") echo "class='active'"; ?> ><a href="<?php echo base_url() . 'reports'; ?>"> Reports</a></li>
		<li <?php if($current_page == "users") echo "class='active'"; ?> ><a href="<?php echo base_url() . 'users'; ?>"> Users</a></li>
    </ul>
</section>