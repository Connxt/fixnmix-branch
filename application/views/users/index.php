<!DOCTYPE html>
<html>
<head>
	<title>Users</title>
	<?php include("/../_shared/css.php"); ?>
</head>
<body class="skin-green fixed">
	<div class="wrapper">
		<header class="main-header">
            <?php include("/../_shared/header.php"); ?>
        </header>
        <aside class="main-sidebar">
        	<?php include("/../_shared/sidebar.php"); ?>
        </aside>
		<div class="content-wrapper">
	        <section class="content">
	            <div class="row">
	                <div class="col-xs-12">
	                	<div class="box">
	                        <div class="box-header with-border">
	                    		<h3 class="box-title"><i class="fa fa-user"></i> Users</h3>
			                	<div class="box-tools pull-right">
			                    	<button class="btn btn-box-tool" data-widget="collapse"><i class="fa fa-minus"></i></button>
			                  	</div>
			                </div>
	                        <div class="box-body">
		                        <table id="users_table" class="table" cellspacing="0" width="100%">
		                          	<thead>
		                            	<tr>
		                            		<th>User ID</th>
			                                <th>Username</th>
			                                <th>Name</th>
			                                <th>Level</th>
		                              	</tr>
	                            	</thead>
	                        		<tbody>
		                              	
		                            </tbody>
		                        </table>
		                        <button type="button" class="btn btn-primary" id="btn_add_user"><i class="fa fa-reply-all"></i> Add User</button>
		                         <button type="button" class="btn btn-default" id="btn_refresh_user"><span class="glyphicon glyphicon-refresh"></span></button>
	                        </div><!-- /.box-body -->
	                    </div><!-- /.box -->
	                </div><!-- /.col -->
	            </div><!-- /.row -->
	        </section><!-- /.content -->
	    </div><!-- /.content-wrapper -->
	</div><!-- ./wrapper -->

	<div class="modal fade" id="add_users_modal" tabindex="-1" role="dialog" aria-labelledby="view_item_modal_label" aria-hidden="true" data-backdrop = "static">
	  	<div class="modal-dialog modal-md">
	    	<div class="modal-content">
	    		<form id="frm_add_user" method="post">
			      	<div class="modal-header">
			        	<button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
			        	<h4 class="modal-title" id="view_item_modal_label">Add Users</h4>
			      	</div>
			      	<div class="modal-body">
			      		<div class="row">
	                        <div class="col-lg-8">
	                             
	                        </div>
		                </div>
		                <div class="row">
	                    	<div class="col-lg-12">
	        					<table id="add_users_list_table" class="table" cellspacing="0" width="100%">
		                          	<thead>
		                            	<tr>
			                                <th>Username</th>
			                                <th>Name</th>
			                            </tr>
	                            	</thead>
	                        		<tbody>
	                        		</tbody>
		                        </table>
		                    </div>
	                    </div>
	                    
	                    <div class="row">
	                    	<div class="col-lg-12" >
	                    		<input type="file" id="input_open_add_users_dialog" name="files" title="Load File"  accept = ".json" style="display:none;" />
	                   		</div>
	                    </div>
			      	</div>
			      	<div class="modal-footer">
			        	<button type="button" class="btn btn-default" data-dismiss="modal">Cancel</button>
			        	<button type="button" class="btn btn-success" id="btn_confirm_users"><span class="glyphicon glyphicon-plus"></span> Confirm</button>
			       	</div>
			    </form>
	    	</div>
	  	</div>
	</div>

	<?php include("/../_shared/js.php"); ?>
	<script type="text/javascript" src="<?php echo base_url();?>assets/js/app/<?php echo $current_page; ?>.js"></script>
	<script type="text/javascript">Users.run();</script>
</body>
</html>