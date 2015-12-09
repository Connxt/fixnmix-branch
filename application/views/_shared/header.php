<a href="#" class="logo"><img src="<?php echo base_url(); ?>assets/images/logo.png" style="width:20%; position:relative; left:-10px;"/><b style="margin-right:30px;">Bakeshop</b></a>
<nav class="navbar navbar-static-top" role="navigation">
    <a href="#" class="sidebar-toggle" data-toggle="offcanvas" role="button">
        <span class="sr-only">Toggle navigation</span>
    </a>
    <div class="navbar-custom-menu">
        <ul class="nav navbar-nav">
             <li class="">
                <a class="text-muted" id="btn_change_file_path" style="cursor:pointer">
                <span><?php echo $app_id ?></span> <i class="fa fa-gears"></i></a>
            </li>
            <li class="dropdown user user-menu">
                <a href="#" class="dropdown-toggle" data-toggle="dropdown">
                    <span class="hidden-xs"><?php echo $name; ?></span>
                    <i class="fa fa-user fa-fw"></i>
                    <span class="caret"></span>
                </a>
                <ul class="dropdown-menu">
                    <li class="user-footer">
                        <div class="pull-right">
                            <i class="fa fa-sign-out fa-fw"></i>
                            <a href="<?php echo base_url() . 'logout'; ?>" class="btn btn-default btn-flat">Sign out</a>
                        </div>
                    </li>
                </ul>
            </li>
        </ul>
    </div>
</nav>
