<!DOCTYPE html>
<html>
<head>
	<title>Items</title>
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
	                    		<h3 class="box-title"><i class="fa fa-th-list"></i> Items</h3>
			                	<div class="box-tools pull-right">
			                    	<button class="btn btn-box-tool" data-widget="collapse"><i class="fa fa-minus"></i></button>
			                  	</div>
			                </div>
	                        <div class="box-body">
	                        	<div role="tabpanel">
		                        	<div class="nav-tabs-custom">
								  		<ul class="nav nav-tabs" role="tablist">
										    <li role="presentation" class="active"><a href="#items_list" aria-controls="items_list" role="tab" data-toggle="tab">Items List</a></li>
										    <li role="presentation"><a href="#return_items" aria-controls="return_items" role="tab" data-toggle="tab">Return Items</a></li>
								  		</ul>
								  		<div class="tab-content">
								    		<div role="tabpanel-delivery-logs" class="tab-pane active" id="items_list">
								    			<br>
								    			<table id="items_list_table" class="table" cellspacing="0" width="100%">
						                          	<thead>
						                            	<tr>
							                                <th>Item ID</th>
							                                <th>Description</th>
							                                <th>Price</th>
							                                <th>Quantity</th>
						                              	</tr>
					                            	</thead>
					                        		<tbody>
						                            </tbody>
						                        </table>
						                        <button type="button" class="btn btn-primary" id="btn_receive_items"><span class="glyphicon glyphicon-plus"></span> Receive Items</button>
						                        <button type="button" class="btn btn-default" id="btn_refresh_items"><span class="glyphicon glyphicon-refresh"></span></button>
								    		</div>
								    		<div role="tabpanel-deliver-items" class="tab-pane" id="return_items">
								    			<br>
								    			<button type="button" class="btn btn-default" id="btn_select_return_items" ><i class="fa fa-search"></i> Select Items</button>
								    			<table id="return_items_table" class="table" cellspacing="0" width="100%">
						                          	<thead>
						                            	<tr>
															<th>Item ID</th>
							                                <th>Description</th>
							                                <th>Quantity</th>
							                            </tr>
					                            	</thead>
					                        		<tbody>
						                            </tbody>
						                        </table>
						                        <button type="button" class="btn btn-primary" id="btn_return_items"><i class="fa fa-share"></i> Return Items</button>
						                        <button type="button" class="btn btn-danger" id="btn_delete_item"><i class="fa fa-trash-o"></i></button>
								    		</div>
								  		</div>
									</div>
								</div>
	                        </div><!-- /.box-body -->
	                    </div><!-- /.box -->
	                </div><!-- /.col -->
	            </div><!-- /.row -->
	        </section><!-- /.content -->
	    </div><!-- /.content-wrapper -->
	</div><!-- ./wrapper -->

	<div class="modal fade" id="recieve_items_modal" tabindex="-1" role="dialog" aria-labelledby="view_item_modal_label" aria-hidden="true" data-backdrop = "static">
	  	<div class="modal-dialog modal-md">
	    	<div class="modal-content">
	    		<form id="frm_recieve_items" method="post">
			      	<div class="modal-header">
			        	<button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
			        	<h4 class="modal-title" id="view_item_modal_label">Receive Items</h4>
			      	</div>
			      	<div class="modal-body">
			      		<div class="row">
	                        <div class="col-lg-8">
	                             <label for="receive_items_new">Receive ID : </label>
	                             <span id="receive_items_id"></span>
	                        </div>
	                        <div class="col-lg-4">
	                             <label for="receive_date">Date : </label>
	                             <span id="receive_items_date"></span>
	                        </div>
		                </div>
		                <div class="row">
	                        <div class="col-lg-12">
	                             <label for="receive_from_main_store">Main Store: </label>
	                             <span id="receive_from_main_store_id"></span>
	                        </div>
		                </div>
		                <div class="row">
	                    	<div class="col-lg-12">
            					<table id="receive_items_table" class="table" cellspacing="0" width="100%">
		                          	<thead>
		                            	<tr>
			                                <th>Item ID</th>
			                                <th>Description</th>
			                                <th>Price</th>
			                                <th>Quantity</th>
		                              	</tr>
	                            	</thead>
	                        		<tbody>
	                        			
		                            </tbody>
		                        </table>
	                    	</div>
	                    </div>
	                    <div class="row">
	                    	<div class="col-lg-12" >
	                    		<input type="file" id="input_open_receive_items_file_dialog" name="files" title="Load File"  accept = ".json" style="display:none;" />
	                   		</div>
	                    </div>
			      	</div>
			      	<div class="modal-footer">
			        	<button type="button" class="btn btn-default" data-dismiss="modal">Cancel</button>
			       		<button type="button" class="btn btn-success" id="btn_confirm_receive_items"><span class="glyphicon glyphicon-plus"></span> Confirm</button>
			      	</div>
			    </form>
	    	</div>
	  	</div>
	</div>
	<div class="modal fade" id="select_items_modal" tabindex="-1" role="dialog" aria-labelledby="view_item_modal_label" aria-hidden="true" data-backdrop = "static">
	  	<div class="modal-dialog modal-md">
	    	<div class="modal-content">
	    		<form id="frm_return_items" method="post">
			      	<div class="modal-header">
			        	<button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
			        	<h4 class="modal-title" id="view_item_modal_label">Select Items</h4>
			      	</div>
			      	<div class="modal-body">
		                <div class="row">
	                    	<div class="col-lg-12">
            					<table id="select_items_table" class="table" cellspacing="0" width="100%">
		                          	<thead>
		                            	<tr>
			                                <th>Item ID</th>
			                                <th>Description</th>
			                            </tr>
	                            	</thead>
	                        		<tbody>
	                        		</tbody>
		                        </table>
				          	</div>
	                    </div>
			      	</div>
			      	<div class="modal-footer">
			        	<button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
			       		<button type="button" class="btn btn-success" id="btn_add_items_to_list"><span class="glyphicon glyphicon-plus"></span> Add</button>
			      	</div>
			    </form>
	    	</div>
	  	</div>
	</div>
	<div class="modal fade" id="return_file_status_modal" tabindex="-1" role="dialog" aria-labelledby="return_file_status_modal_label" aria-hidden="true" data-backdrop = "static">
	  	<div class="modal-dialog modal-sm">
	    	<div class="modal-content">
	    		<form id="frm_return_status_modal" method="post">
			      	<div class="modal-header">
			        	<button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
			        	<h4 class="modal-title" id="return_file_status_modal_label">File Export Status</h4>
			      	</div>
			      	<div class="col-lg-12" >
				      	<div id="return_status_message"></div>
				    </div>
			      	<div class="modal-body">
		                <div class="row">
	                    	<div class="col-lg-12">
            					<table id="return_file_status_table" class="table" cellspacing="0" width="100%">
		                          	<thead>
		                            	<tr>
			                                <th>Main ID</th>
			                                <th>Status</th>
			                            </tr>
	                            	</thead>
	                        		<tbody>
	                        		</tbody>
		                        </table>
				          	</div>
	                    </div>
			      	</div>
			      	<div class="modal-footer">
			        	<button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
			      	</div>
			    </form>
	    	</div>
	  	</div>
	</div>

	<?php include("/../_shared/js.php"); ?>
	<script type="text/javascript" src="<?php echo base_url();?>assets/js/app/<?php echo $current_page; ?>.js"></script>
	<script type="text/javascript">Items.run();</script>

	<script type="text/javascript" src="<?php echo base_url();?>assets/js/app/return_items.js"></script>
	<script type="text/javascript">ReturnItems.run();</script>
</body>
</html>