<!DOCTYPE html>
<html>
<head>
	<title>Reports</title>
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
	                    		<h3 class="box-title"><i class="fa fa-bar-chart"></i> Reports</h3>
			                	<div class="box-tools pull-right">
			                    	<button class="btn btn-box-tool" data-widget="collapse"><i class="fa fa-minus"></i></button>
			                  	</div>
			                </div>
	                        <div class="box-body">
	                        	<div role="tabpanel">
		                        	<div class="nav-tabs-custom">
								  		<ul class="nav nav-tabs" role="tablist">
										    <li role="presentation" class="active"><a href="#sales_report" aria-controls="sales_report" role="tab" data-toggle="tab">Sales Reports</a></li>
										    <li role="presentation"><a href="#receipts_report" aria-controls="receipts_report" role="tab" data-toggle="tab">Receipts</a></li>
										    <li role="presentation"><a href="#return_report" aria-controls="return_report" role="tab" data-toggle="tab">Returns</a></li>
										    <li role="presentation"><a href="#deliveries_report" aria-controls="deliveries_report" role="tab" data-toggle="tab">Deliveries</a></li>
								  		</ul>
								  		<div class="tab-content">
								    		<div role="tabpanel-sales-report" class="tab-pane active" id="sales_report">
								    			<br>
								    			<table id="sales_report_table" class="table" cellspacing="0" width="100%">
						                          	<thead>
						                            	<tr>
							                                <th>Sales Report ID</th>
							                                <th>Total Amount</th>
							                                <th>Status</th>
						                              	</tr>
					                            	</thead>
					                        		<tbody>
						                            </tbody>
						                        </table>
						                        <button type="button" class="btn btn-success" id="btn_generate_new_report"><span class="glyphicon glyphicon-plus"></span> New Report</button>
						                        <button type="button" class="btn btn-info" id="btn_view_sales_report"><span class="glyphicon glyphicon-eye-open"></span></button>
						                        <button type="button" class="btn btn-default" id="btn_refresh_sales_report"><span class="glyphicon glyphicon-refresh"></span></button>
						                        <button type="button" class="btn btn-primary pull-right" id="btn_generate_file"><i class="fa fa-file"></i>  Generate File</button>
								    		</div>
								    		<div role="tabpanel-receipts-reports" class="tab-pane" id="receipts_report">
								    			<br>
								    			<table id="receipts_reports_table" class="table" cellspacing="0" width="100%">
						                          	<thead>
						                            	<tr>
							                                <th>Receipt ID</th>
							                                <th>Total Amount</th>
							                                <th>Date</th>
						                              	</tr>
					                            	</thead>
					                        		<tbody>
						                            </tbody>
						                        </table>
						                        <button type="button" class="btn btn-info" id="btn_view_items_from_receipts"><span class="glyphicon glyphicon-eye-open"></span></button>
						                        <button type="button" class="btn btn-default" id="btn_refresh_receipts_report"><span class="glyphicon glyphicon-refresh"></span></button>
								    		</div>
								    		<div role="tabpanel-return-reports" class="tab-pane" id="return_report">
								    			<br>
								    			<table id="return_report_table" class="table" cellspacing="0" width="100%">
						                          	<thead>
						                            	<tr>
							                                <th>Return ID</th>
							                                <th>Date</th>
							                                <th>Status</th>
						                              	</tr>
					                            	</thead>
					                        		<tbody>
						                            </tbody>
						                        </table>
						                        <button type="button" class="btn btn-primary" id="btn_generate_file_returns"><i class="fa fa-file"></i>  Generate File</button>
						                        <button type="button" class="btn btn-info" id="btn_view_item_returns"><span class="glyphicon glyphicon-eye-open"></span></button>
						                        <button type="button" class="btn btn-default" id="btn_refresh_return_reports"><span class="glyphicon glyphicon-refresh"></span></button>
								    		</div>
								    		<div role="tabpanel-deliveries-report" class="tab-pane" id="deliveries_report">
								    			<br>
								    			<table id="deliveries_report_table" class="table" cellspacing="0" width="100%">
						                          	<thead>
						                            	<tr>
							                                <th>Delivery ID</th>
							                                <th>Date</th>
						                              	</tr>
					                            	</thead>
					                        		<tbody>
						                            </tbody>
						                        </table>
						                        <button type="button" class="btn btn-info" id="btn_view_items_from_deliveries"><span class="glyphicon glyphicon-eye-open"></span></button>
						                        <button type="button" class="btn btn-default" id="btn_refresh_deliveries_report"><span class="glyphicon glyphicon-refresh"></span></button>
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
	<div class="modal fade" id="new_sales_reports_to_generate_modal" tabindex="-1" role="dialog" aria-labelledby="new_sales_reports_to_generate_modal_label" aria-hidden="true">
	  	<div class="modal-dialog modal-sm">
	    	<div class="modal-content">
	    		<form id="frm_new_sales_reports_to_generate" method="post">
			      	<div class="modal-header">
			        	<button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
			        	<h4 class="modal-title" id="new_sales_reports_to_generate_modal_label">List of Available Sales To Generate</h4>
			      	</div>
			      	<div class="modal-body">
			        	<div class="row">
			        		<div class="col-lg-12">
		                        <table id="new_sales_report_table" class="table" cellspacing="0" width="100%">
		                          	<thead>
		                            	<tr>
			                                <th>Receipt ID</th>
			                                <th>Total Amount</th>
		                              	</tr>
	                            	</thead>
	                        		<tbody>
		                            </tbody>
		                        </table>
		                        <div id="new_sales_grand_total"></div>
		                    </div>
	                    </div>
			      	</div>
			      	<div class="modal-footer">
			        	<button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
			        	<button type="button" class="btn btn-success" id="btn_confirm_generate"><span class="glyphicon glyphicon-check"></span> Generate</button>
			      	</div>
			    </form>
	    	</div>
	  	</div>
	</div>
	<div class="modal fade" id="sales_report_file_export_status_modal" tabindex="-1" role="dialog" aria-labelledby="sales_report_file_export_status_modal_label" aria-hidden="true" data-backdrop = "static">
	  	<div class="modal-dialog modal-sm">
	    	<div class="modal-content">
	    		<form id="frm_sales_report_file_export_status_modal" method="post">
			      	<div class="modal-header">
			        	<button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
			        	<h4 class="modal-title" id="sales_report_file_export_status_modal_label">File Export Status</h4>
			      	</div>
			      	<div class="col-lg-12" >
				      	<div id="sales_report_file_export_status_message"></div>
				    </div>
			      	<div class="modal-body">
		                <div class="row">
	                    	<div class="col-lg-12">
            					<table id="sales_report_file_export_status_table" class="table" cellspacing="0" width="100%">
		                          	<thead>
		                            	<tr>
			                                <th>Transaction</th>
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
	<div class="modal fade" id="view_sales_report_modal" tabindex="-1" role="dialog" aria-labelledby="view_sales_report_modal_label" aria-hidden="true" data-backdrop = "static">
	  	<div class="modal-dialog modal-md">
	    	<div class="modal-content">
	    		<form id="frm_view_sales_report" method="post">
			      	<div class="modal-header">
			        	<button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
			        	<h4 class="modal-title" id="view_sales_report_modal_label">Receipts List</h4>
			      	</div>
			      	<div class="modal-body">
		                <div class="row">
	                    	<div class="col-lg-12">
            					<table id="view_sales_report_table" class="table" cellspacing="0" width="100%">
		                          	<thead>
		                            	<tr>
			                                <th>Receipt ID</th>
			                                <th>Total Amount</th>
			                                <th>Date</th>
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

	<!-- receipts -->
	<div class="modal fade" id="view_items_from_receipt_modal" tabindex="-1" role="dialog" aria-labelledby="view_items_from_receipt_modal_label" aria-hidden="true" data-backdrop = "static">
	  	<div class="modal-dialog modal-md">
	    	<div class="modal-content">
	    		<form id="frm_view_items_from_receipt" method="post">
			      	<div class="modal-header">
			        	<button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
			        	<h4 class="modal-title" id="view_items_from_receipt_modal_label">Items List</h4>
			      	</div>
			      	<div class="modal-body">
		                <div class="row">
	                    	<div class="col-lg-12">
            					<table id="view_items_from_receipt_table" class="table" cellspacing="0" width="100%">
		                          	<thead>
		                            	<tr>
			                                <th>Item ID</th>
			                                <th>Receipt ID</th>
			                                <th>Price</th>
			                                <th>Quantity</th>
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

	<!-- returns -->
	<div class="modal fade" id="view_items_from_returns_modal" tabindex="-1" role="dialog" aria-labelledby="view_items_from_returns_modal_label" aria-hidden="true" data-backdrop = "static">
	  	<div class="modal-dialog modal-md">
	    	<div class="modal-content">
	    		<form id="frm_view_items_from_returns" method="post">
			      	<div class="modal-header">
			        	<button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
			        	<h4 class="modal-title" id="view_items_from_returns_modal_label">Items Return List</h4>
			      	</div>
			      	<div class="modal-body">
		                <div class="row">
	                    	<div class="col-lg-12">
            					<table id="view_items_from_returns_table" class="table" cellspacing="0" width="100%">
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

	<!-- deliveries -->
	<div class="modal fade" id="view_items_from_deliveries_modal" tabindex="-1" role="dialog" aria-labelledby="view_items_from_deliveries_modal_label" aria-hidden="true" data-backdrop = "static">
	  	<div class="modal-dialog modal-md">
	    	<div class="modal-content">
	    		<form id="frm_view_items_from_deliveries" method="post">
			      	<div class="modal-header">
			        	<button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
			        	<h4 class="modal-title" id="view_items_from_deliveries_modal_label">Items Deliveries List</h4>
			      	</div>
			      	<div class="modal-body">
		                <div class="row">
	                    	<div class="col-lg-12">
            					<table id="view_items_from_deliveries_table" class="table" cellspacing="0" width="100%">
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
	<script type="text/javascript">Reports.run();</script>
</body>
</html>