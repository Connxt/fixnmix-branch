<!DOCTYPE html>
<html>
<head>
	<title>Cashiering</title>
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
            <div class="box box-default" style="position: relative;">
                <div class="box-header ui-sortable-handle">
	               <section class="content">
        	            <div class="row">
        	                <div class="col-xs-12">
        	                	<div class="box box-default" style="position: relative;">
                        			<div class="box-header ui-sortable-handle">
                          				<div class="col-lg-2" style="padding-right: 0px; padding-left:5px;">
                                            <input id="cashiering_item_id" type="text" class="form-control" placeholder="Item Id.">
                                        </div>
                                        <div class="col-lg-6" style="padding-right: 0px;">
                                            <input id="cashiering_description" type="text" class="form-control" placeholder="..." disabled>
                                        </div>
                                        <div class="col-lg-2" style="padding-right: 0px;">
                                            <input id="cashiering_quantity" name="cashiering_quantity" type="number" class="form-control" placeholder="Quantity">
                                        </div>
                                        <div class="col-lg-1" style="padding-right: 0px;">
                                            <button id="btn_cashiering_add_item" type="button" class="btn btn-warning" title="Add Item" style="width:100%;"><span class="glyphicon glyphicon-ok"></span> Add</button>
                                        </div>
                                        <div class="col-lg-1" style="padding-right: 0px; padding-left:6px;">
                                            <button id="btn_cashiering_search_item" type="button" class="btn btn-primary" title="Search Item" style="width:100%;"><span class="glyphicon glyphicon-search"></span> Search</button>
                                        </div>
                        			</div><!-- /.box-body -->
                      			</div>
        	                </div><!-- /.col -->
        	            </div>
        	            <div class="row">
        	                <div class="col-xs-9">
        	                	<div class="box box-default" style="position: relative;">
                        			<div class="box-header ui-sortable-handle">
                          				<table id="sold_items_table" class="table" cellspacing="0" width="100%">
                                        <thead>
                                            <tr>
                                                <th>Item ID</th>
                                                <th>Description</th>
                                                <th>Quantity</th>
                                                <th>Price</th>
                                                <th>Total</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                      </tbody>
                                  </table>
                        			</div><!-- /.box-body -->
                      			</div>
        	                </div><!-- /.col -->
        	                <div class="col-xs-3">
        	                	<div class="box box-default" style="position: relative;">
                        			<div class="box-header ui-sortable-handle">
                                        <div class="form-group">
                                            <label for="cashiering_grand_total">Grand Total</label>
                                            <input id="cashiering_grand_total" name="cashiering_grand_total" type="number" class="form-control input-lg" style="text-align: right;" disabled>
                                        </div>
                        			</div><!-- /.box-body -->
                      			</div>
        	                </div><!-- /.col -->
                            <div class="col-xs-3">
                                <div class="box box-default" style="position: relative;">
                                    <div class="box-header ui-sortable-handle">
                                        <div class="form-group">
                                            <label for="cashiering_amount_rendered">Amount Rendered</label>
                                            <input id="cashiering_amount_rendered" name="cashiering_amount_rendered" type="number" class="form-control input-lg" style="text-align: right;">
                                        </div>
                                        <div class="form-group">
                                            <label for="cashiering_change">Change</label>
                                            <input id="cashiering_change" name="cashiering_change" type="number" class="form-control input-lg" style="text-align: right;" disabled>
                                        </div>
                                        <br>
                                        <div class="form-group">
                                            <button id="btn_cashiering_transact" type="button" class="btn btn-primary btn-lg btn-block" title="Transcact Payment"><span class="glyphicon glyphicon-inbox"></span></button>
                                        </div>
                                        <div class="form-group">
                                            <button id="btn_new_transaction" type="button" class="btn btn-warning btn-block" title="New Transaction">New Transaction</button>
                                        </div>
                                        <div class="form-group">
                                            <button id="btn_cancel_transaction" type="button" class="btn btn-default btn-block" title="New Transaction">Cancel Transaction</button>
                                        </div>
                                        <button id="btn_show_modal_help" type="button" class="btn btn-default btn-block"><span class="glyphicon glyphicon-info-sign"></span></button>
                                        <button id="btn_delete_items_in_the_list" type="button" class="btn btn-default btn-block"><i class="fa fa-trash-o"></i></button>
                                        <br>
                                    </div><!-- /.box-body -->
                                </div>
                            </div><!-- /.col -->
        	            </div><!-- /.row -->
	                </section><!-- /.content -->
                </div>
            </div>
	    </div><!-- /.content-wrapper -->
	</div><!-- ./wrapper -->
    <div class="modal fade" id="search_item_modal" tabindex="-1" role="dialog" aria-labelledby="search_item_modal_label" aria-hidden="true">
        <div class="modal-dialog modal-md">
            <div class="modal-content">
                <form id="frm_search_item" method="post">
                    <div class="modal-header">
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                        <h4 class="modal-title" id="search_item_modal_label">Select Item</h4>
                    </div>
                    <div class="modal-body">
                        <div class="row">
                            <div class="col-lg-12">
                                <table id="search_item_table" class="table" cellspacing="0" width="100%">
                                    <thead>
                                        <tr>
                                            <th>Item ID</th>
                                            <th>Description</th>
                                            <th>Quantity</th>
                                            <th>Price</th>
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

    <div class="modal fade" id="view_help_controls_modal" tabindex="-1" role="dialog" aria-labelledby="view_help_controls_modal_label" aria-hidden="true">
        <div class="modal-dialog modal-md">
            <div class="modal-content">
                <form id="frm_view_help_controls" method="post">
                    <div class="modal-header">
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                        <h4 class="modal-title" id="view_help_controls_modal_label">Shortcuts Control</h4>
                    </div>
                    <div class="modal-body">
                        <div class="row">
                            <div class="col-lg-12">
                                <table class="table" style="width: 100%; margin: 0 auto;">
                                    <thead>
                                        <tr>
                                            <th>Controls</th>
                                            <th></th>
                                            <th style="width:35%;">Events</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <tr>
                                            <td>Ctrl + Alt + H</td>
                                            <td><span class="glyphicon glyphicon-arrow-right">&nbsp;&nbsp;&nbsp;&nbsp;</span></td>
                                            <td>Show Help</td>
                                        </tr>
                                        <tr>
                                            <td>Ctrl + Alt + I</td>
                                            <td><span class="glyphicon glyphicon-arrow-right"></span></td>
                                            <td>Focus to item id number field</td>
                                        </tr>
                                        <tr>
                                            <td>Ctrl + Alt + D</td>
                                            <td><span class="glyphicon glyphicon-arrow-right"></span></td>
                                            <td>Delete an item on the list</td>
                                        </tr>
                                        <tr>
                                            <td>Ctrl + Alt + T</td>
                                            <td><span class="glyphicon glyphicon-arrow-right"></span></td>
                                            <td>Focus on the first item on the list</td>
                                        </tr>
                                        <tr>
                                            <td>Ctrl + Alt + F</td>
                                            <td><span class="glyphicon glyphicon-arrow-right"></span></td>
                                            <td>Search for items</td>
                                        </tr>
                                        <tr>
                                            <td>Ctrl + Alt + Backspace</td>
                                            <td><span class="glyphicon glyphicon-arrow-right"></span></td>
                                            <td>Cancel/Clear transaction</td>
                                        </tr>
                                        <tr>
                                            <td>Ctrl + Alt + 0</td>
                                            <td><span class="glyphicon glyphicon-arrow-right"></span></td>
                                            <td>Start new transaction</td>
                                        </tr>
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
	<script type="text/javascript">Cashiering.run();</script>
</body>
</html>