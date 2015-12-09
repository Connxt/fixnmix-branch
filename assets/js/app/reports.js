var Reports = (function() {
	var obj = {};
	var tableSalesReportsSelectedRowPos = -1;
	var tableSalesReportsCurrentRowPos = -1;
	var tableReceiptsSelectedRowPos = -1;
	var tableReceiptsCurrentRowPos = -1;
	var tableReturnsSelectedRowPos = -1;
	var tableReturnsCurrentRowPos = -1;
	var tableDeliveriesSelectedRowPos = -1;
	var tableDeliveriesCurrentRowPos = -1;
	var initialize = function () {
		obj = {
			$tableSalesReports: $("#sales_report_table").DataTable({
				"columns": [
					{ "sClass": "text-left font-bold", "sWidth": "15%"},
					{ "sClass": "text-left font-bold", "sWidth": "50%"},
					{ "sClass": "text-left font-bold", "sWidth": "5%"}
				],
				"bLengthChange": false, responsive: true, scrollCollapse: false,
				"bPaginate" : true, "bAutoWidth": false,
			}),
			$tableReceipts: $("#receipts_reports_table").DataTable({
				"columns": [
					{ "sClass": "text-left font-bold", "sWidth": "15%"},
					{ "sClass": "text-left font-bold", "sWidth": "50%"},
					{ "sClass": "text-left font-bold", "sWidth": "10%"},
				],
				"bLengthChange": false, responsive: true, scrollCollapse: false,
				"bPaginate" : true, "bAutoWidth": false,
			}),
			$tableNewSalesReport: $("#new_sales_report_table").DataTable({
				"columns": [
					{ "sClass": "text-left font-bold", "sWidth": "50%"},
					{ "sClass": "text-left font-bold", "sWidth": "50%"},
				],
				"bLengthChange": false, responsive: true, scrollCollapse: false,
				"bPaginate" : true, "bAutoWidth": false, "bFilter":false, "bInfo":false
			}),
			$tableSalesReportFileExportStatus: $("#sales_report_file_export_status_table").DataTable({
				"columns": [
					{ "sClass": "text-left font-bold", "sWidth": "60%"},
					{ "sClass": "text-left font-bold", "sWidth": "30%"},
				],
				"bLengthChange": false, responsive: true, scrollCollapse: false,
				"bPaginate" : true, "bAutoWidth": false, "bFilter":false, "bPaginate":false, "bInfo":false
			}),
			$tableViewSalesReport: $("#view_sales_report_table").DataTable({
				"columns": [
					{ "sClass": "text-left font-bold", "sWidth": "35%"},
					{ "sClass": "text-left font-bold", "sWidth": "35%"},
					{ "sClass": "text-left font-bold", "sWidth": "35%"},
				],
				"bLengthChange": false, responsive: true, scrollCollapse: false,
				"bPaginate" : true, "bAutoWidth": false, "bFilter":false
			}),
			$tableViewItemsFromReceipts: $("#view_items_from_receipt_table").DataTable({
				"columns": [
					{ "sClass": "text-left font-bold", "sWidth": "35%"},
					{ "sClass": "text-left font-bold", "sWidth": "35%"},
					{ "sClass": "text-left font-bold", "sWidth": "35%"},
					{ "sClass": "text-left font-bold", "sWidth": "35%"},
				],
				"bLengthChange": false, responsive: true, scrollCollapse: false,
				"bPaginate" : true, "bAutoWidth": false
			}),
			$tableReturns: $("#return_report_table").DataTable({
				"columns": [
					{ "sClass": "text-left font-bold", "sWidth": "15%"},
					{ "sClass": "text-left font-bold", "sWidth": "45%"},
					{ "sClass": "text-left font-bold", "sWidth": "7%"},
				],
				"bLengthChange": false, responsive: true, scrollCollapse: false,
				"bPaginate" : true, "bAutoWidth": false
			}),
			$tableViewItemsFromReturns: $("#view_items_from_returns_table").DataTable({
				"columns": [
					{ "sClass": "text-left font-bold", "sWidth": "15%"},
					{ "sClass": "text-left font-bold", "sWidth": "45%"},
					{ "sClass": "text-left font-bold", "sWidth": "10%"},
				],
				"bLengthChange": false, responsive: true, scrollCollapse: false,
				"bPaginate" : true, "bAutoWidth": false
			}),
			$tableDeliveries: $("#deliveries_report_table").DataTable({
				"columns": [
					{ "sClass": "text-left font-bold", "sWidth": "50%"},
					{ "sClass": "text-left font-bold", "sWidth": "50%"}
				],
				"bLengthChange": false, responsive: true, scrollCollapse: false,
				"bPaginate" : true, "bAutoWidth": false
			}),
			$tableViewItemsFromDeliveries: $("#view_items_from_deliveries_table").DataTable({
				"columns": [
					{ "sClass": "text-left font-bold", "sWidth": "20%"},
					{ "sClass": "text-left font-bold", "sWidth": "50%"},
					{ "sClass": "text-left font-bold", "sWidth": "20%"},
				],
				"bLengthChange": false, responsive: true, scrollCollapse: false,
				"bPaginate" : true, "bAutoWidth": false
			}),
			$keysalesReports: new $.fn.dataTable.KeyTable($("#sales_report_table").dataTable()),
			$keyReceipts: new $.fn.dataTable.KeyTable($("#receipts_reports_table").dataTable()),
			$keyReturns: new $.fn.dataTable.KeyTable($("#return_report_table").dataTable()),
			$keyDeliveries: new $.fn.dataTable.KeyTable($("#deliveries_report_table").dataTable()),
			$keyViewItemsFromReturns: new $.fn.dataTable.KeyTable($("#view_items_from_returns_table").dataTable()),
			$keyViewItemsFromReceipts: new $.fn.dataTable.KeyTable($("#view_items_from_receipt_table").dataTable()),
			$keyViewSalesReport: new $.fn.dataTable.KeyTable($("#view_sales_report_table").dataTable()),
			$keyViewSalesReport: new $.fn.dataTable.KeyTable($("#view_sales_report_table").dataTable()),
			$keyTableReturnsApi: $("#return_report_table").dataTable(),
			$keyTableSalesReportsApi: $("#sales_report_table").dataTable(),
			$keyTableReceiptsApi: $("#receipts_reports_table").dataTable(),
			$keyTableDeliveriesApi: $("#deliveries_report_table").dataTable(),
			$modalNewSalesReportToGenerate: $("#new_sales_reports_to_generate_modal"),
			$modalSalesReportFileExportStatus: $("#sales_report_file_export_status_modal"),
			$modalViewSalesReport: $("#view_sales_report_modal"),
			$modalViewItemsFromReceipt: $("#view_items_from_receipt_modal"),
			$modalViewItemsFromReturn: $("#view_items_from_returns_modal"),
			$modalViewItemsFromDeliveries: $("#view_items_from_deliveries_modal"),
			$btnGenerateNewSalesReport: $("#btn_generate_new_report"),
			$btnViewSalesReport: $("#btn_view_sales_report"),
			$btnGenerateSalesReport: $("#btn_generate_file"),
			$btnConfirmGenerateReport: $("#btn_confirm_generate"),
			$btnViewItemsFromReceipts: $("#btn_view_items_from_receipts"),
			$btnViewItemsFromDeliveries: $("#btn_view_items_from_deliveries"),
			$btnViewItemsFromReturns: $("#btn_view_item_returns"),
			$btnRefreshSalesReport: $("#btn_refresh_sales_report"),
			$btnRefreshReceiptsReport: $("#btn_refresh_receipts_report"),
			$btnRefreshReturnsReport: $("#btn_refresh_return_reports"),
			$btnRefreshDeliveriesReport: $("#btn_refresh_deliveries_report"),
			$btnGenerateReturnFile: $("#btn_generate_file_returns"),
			$salesReportExportFileStatusMessage: $("#sales_report_file_export_status_message"),
			$textNewSalesGrandTotal: $("#new_sales_grand_total"),
			spanSuccess: ("<span class='label label-primary'>Success</span>"),
			spanFailed: ("<span class='label label-danger' style='padding:0.2em 1em 0.3em'>Failed</span>"),
			// function
			showConfirm: function (text, type, confirmButtonText, cancelButtonText, callback){
				swal({
					html:true,
					title: "",
				  	text: text,
				  	type: type,
				  	showCancelButton: true,
				  	confirmButtonClass: "btn-primary",
				  	confirmButtonText: confirmButtonText,
				  	cancelButtonText: cancelButtonText,
				  	closeOnConfirm: true,
				  	confirmButtonColor: "#3c8dbc",
				  	closeOnCancel: true,
				}, callback );	
			},
			showAlert: function(title, type, text){
				swal({
					html:true,
					title: title,
				  	text: text,
				  	type: type,
				  	confirmButtonClass: "btn-primary",
				  	confirmButtonColor: "#3c8dbc",
				  	closeOnConfirm:true
				});	
			},
			refreshSalesReportTable: function (){
				NProgress.start();
				obj.getAllSalesReports().always(function (allSalesReportsData){
					obj.$tableSalesReports.clear().draw(false);
					var allSalesReportsData = JSON.parse(allSalesReportsData);
					for(var i=0; i<allSalesReportsData.length; i++){
						obj.$tableSalesReports.row.add([
							allSalesReportsData[i]['id'],
							numeral(allSalesReportsData[i]['total_amount']).format("0.00"),
							(allSalesReportsData[i]['status'] == 1) ? obj.spanSuccess : obj.spanFailed
						]);
						obj.$tableSalesReports.draw(false);
					}
				}).always(function (){
					NProgress.done();
				});
			},
			refreshReceiptsReportTable: function (){
				NProgress.start();
				obj.getAllReceipts().always(function (getAllReceipts){
					obj.$tableReceipts.clear().draw(false);
					var getAllReceiptsData = JSON.parse(getAllReceipts);
					for(var i=0; i<getAllReceiptsData.length; i++){
						obj.$tableReceipts.row.add([
							getAllReceiptsData[i]["id"],
							numeral(getAllReceiptsData[i]["total_amount"]).format("0.00"),
							moment(getAllReceiptsData[i]["created_at"]).format("MMM DD, YYYY")
						]);
						obj.$tableReceipts.draw(false);
					}
				}).always(function (){
					NProgress.done();
				});;
			},
			refreshReturnsReportTable: function (){
				NProgress.start();
				obj.getAllReturns().always(function (allReturns){
					obj.$tableReturns.clear().draw();
					var allReturnsData = JSON.parse(allReturns);
					for(var i=0; i<allReturnsData.length; i++){
						obj.$tableReturns.row.add([
							allReturnsData[i]["id"],
							moment(allReturnsData[i]["created_at"]).format("MMM DD, YYYY"),
							(allReturnsData[i]["status"] == 1) ? obj.spanSuccess : obj.spanFailed
						]);
					}
					obj.$tableReturns.draw(false);
				}).always(function (){
					NProgress.done();
				});;
			},
			refreshDeliveriesReportTable: function (){
				NProgress.start();
				obj.getAllDeliveries().always(function (allDeliveries){
					obj.$tableDeliveries.clear().draw(false);
					var allDeliveries = JSON.parse(allDeliveries);
					for(var i=0; i<allDeliveries.length; i++){
						obj.$tableDeliveries.row.add([
							allDeliveries[i]["id"],
							moment(allDeliveries[i]["created_at"]).format("MMM DD, YYYY")
						]);
					}
					obj.$tableDeliveries.draw(false);
				}).always(function (){
					NProgress.done();
				});;
			},
			// sales
			getAllNewSalesReport: function (){
				return $.post(BASE_URL + CURRENT_CONTROLLER + "/new_sales_report",{},function (data){});
			},
			getAllReceiptsToReport: function (){
				return $.post(BASE_URL + CURRENT_CONTROLLER + "/get_all_receipts_to_report",{},function (data){});
			},
			getDefaultPath: function (){
				return $.post(BASE_URL + "settings_controller/get_default_save_path",{},function (data){});
			},
			getAppId: function (){
				return $.post(BASE_URL + "settings_controller/get_app_id",{},function (data){});
			},
			getMainId: function (){
				return $.post(BASE_URL + "settings_controller/get_main_id",{},function (data){});
			},
			checkIfSalesReportExist: function (salesReportId){
				return $.post(BASE_URL + CURRENT_CONTROLLER + "/sales_report_exists",{salesReportId:salesReportId},function (data){});
			},
			writeSalesReportDataToFile: function (filePath, salesReportData, salesReportId){
				return $.post(BASE_URL + CURRENT_CONTROLLER + "/write_sales_report_data_to_file",{
							filePath:filePath,
							salesReportData:salesReportData,
							salesReportId:salesReportId
						},function (data){});
			},
			generateSalesReportData: function(filePath, salesReportId){
				return $.post(BASE_URL + CURRENT_CONTROLLER + "/generate_sales_report_data",{
					filePath:filePath,
					salesReportId:salesReportId
				},function (data){});
			},
			getAllSalesReports: function (){
				return $.post(BASE_URL +  CURRENT_CONTROLLER + "/get_all_sales_reports",{},function (data){});
			},
			getAllReceiptsViaSalesReportId: function (salesReportId){
				return $.post(BASE_URL +  CURRENT_CONTROLLER + "/get_all_receipts_via_sales_report_id",{salesReportId:salesReportId},function (data){});
			},
			// ***
			// receipts
			// ***
			getAllReceipts: function (){
				return $.post(BASE_URL +  CURRENT_CONTROLLER + "/get_all_receipts",{},function (data){});
			},
			getAllItemsFromThisReceipts: function (receiptId){
				return $.post(BASE_URL +  CURRENT_CONTROLLER + "/get_all_items_from_this_receipt",{receiptId:receiptId},function (data){});
			},
			checkIfReceiptsExist: function (receiptId){
				return $.post(BASE_URL +  CURRENT_CONTROLLER + "/receipt_exists",{receiptId:receiptId},function (data){});
			},
			// ***
			// returns
			// ***
			getAllReturns: function (){
				return $.post(BASE_URL +  CURRENT_CONTROLLER + "/get_all_returns",{},function (data){});
			},
			checkIfReturnExist: function (returnId){
				return $.post(BASE_URL +  CURRENT_CONTROLLER + "/return_exists",{returnId:returnId},function (data){});
			},
			getAllItemsFromReturn: function (returnId){
				return $.post(BASE_URL +  CURRENT_CONTROLLER + "/get_all_items_from_this_return",{returnId:returnId},function (data){});
			},
			generateFileReturns: function (returnId, filePath){
				return $.post(BASE_URL +  CURRENT_CONTROLLER + "/generate_return_data",{
							returnId:returnId,
							filePath:filePath
						},function (data){});
			},
			// ***
			// deliveries
			// ***
			getAllDeliveries: function (){
				return $.post(BASE_URL +  CURRENT_CONTROLLER + "/get_all_deliveries",{},function (data){});
			},
			checkIfDeliveryExist: function (deliveryId){
				return $.post(BASE_URL +  CURRENT_CONTROLLER + "/delivery_exists",{deliveryId:deliveryId},function (data){});
			},
			getAllItemsFromDelivery: function (deliveryId){
				return $.post(BASE_URL +  CURRENT_CONTROLLER + "/get_all_items_from_this_delivery",{deliveryId:deliveryId},function (data){});
			}
		};
	};

	var bindEvents = function() {

		obj.refreshSalesReportTable();
		
		obj.refreshReceiptsReportTable();

		obj.refreshReturnsReportTable();

		obj.refreshDeliveriesReportTable();

		obj.$btnViewSalesReport.attr("disabled", "disabled");

		obj.$btnGenerateSalesReport.attr("disabled", "disabled");

		obj.$btnViewItemsFromReceipts.attr("disabled", "disabled");

		obj.$keysalesReports.event.focus(null, null, function (node, x, y){
			tableSalesReportsCurrentRowPos = y;
			obj.$btnViewSalesReport.removeAttr("disabled");
			obj.$btnGenerateSalesReport.removeAttr("disabled");
		});

		obj.$keysalesReports.event.blur(null, null, function (node, x, y){
			tableSalesReportsCurrentRowPos = -1;
			obj.$btnGenerateSalesReport.attr("disabled", "disabled");
			obj.$btnViewSalesReport.attr("disabled", "disabled");
		});
		// **
		// keytable receipts
		// **
		obj.$keyReceipts.event.focus(null, null, function (node, x, y){
			tableReceiptsCurrentRowPos = y;
			obj.$btnViewItemsFromReceipts.removeAttr("disabled");
		});

		obj.$keyReceipts.event.blur(null, null, function (node, x, y){
			tableReceiptsCurrentRowPos = -1;
			obj.$btnViewItemsFromReceipts.attr("disabled", "disabled");
		});
		// **
		// keytable returns
        // **
        obj.$keyReturns.event.focus(null, null, function (node, x, y){
			tableReturnsCurrentRowPos = y;
			obj.$btnViewItemsFromReturns.removeAttr("disabled");
			obj.$btnGenerateReturnFile.removeAttr("disabled");
		});

		obj.$keyReturns.event.blur(null, null, function (node, x, y){
			tableReturnsCurrentRowPos = -1;
			obj.$btnViewItemsFromReturns.attr("disabled", "disabled");
			obj.$btnGenerateReturnFile.attr("disabled", "disabled");
		});
		// ***
		// keytable deliveries
		// ***
		obj.$keyDeliveries.event.focus(null, null, function (node, x, y){
			tableDeliveriesCurrentRowPos = y;
			obj.$btnViewItemsFromDeliveries.removeAttr("disabled");
		});

		obj.$keyDeliveries.event.blur(null, null, function (node, x, y){
			tableDeliveriesCurrentRowPos = -1;
			obj.$btnViewItemsFromDeliveries.attr("disabled", "disabled");
		});

		obj.$btnRefreshSalesReport.click(function (){
			obj.refreshSalesReportTable();
		});

		obj.$btnGenerateNewSalesReport.click(function (){
			NProgress.start();
			obj.getAllReceiptsToReport().always(function (getAllReceipts){
				var getAllReceiptsData = JSON.parse(getAllReceipts);
				if((getAllReceiptsData[0]["receipts"].length) >=1){
					obj.$tableNewSalesReport.clear().draw(false);
					for(var i =0; i<(getAllReceiptsData[0]["receipts"].length); i++){
						obj.$tableNewSalesReport.row.add([
							getAllReceiptsData[0]["receipts"][i]["id"],
							numeral(getAllReceiptsData[0]["receipts"][i]["total_amount"]).format("0.00"),
						]);
					}
					obj.$textNewSalesGrandTotal.empty().append("<h4>Grand Total: <strong>" + numeral(getAllReceiptsData[0]['grand_total']).format("0.00") + "</strong></h4>");
					obj.$tableNewSalesReport.draw(false);
					obj.$modalNewSalesReportToGenerate.modal("show");
				}
				else{
					obj.showAlert("Failed To Generate Report", "info", "There is currently no sales report to generate.");
					NProgress.done();
				}
			}).always(function (){
				NProgress.done();
			});
		});

		obj.$btnConfirmGenerateReport.click(function (){
			NProgress.start();
			obj.getAllNewSalesReport().always(function (salesReports){
				obj.getDefaultPath().always(function (defaulthPath){
					var salesReportsData = JSON.parse(salesReports),
						transactionType = salesReportsData.transaction,
						salesReportId = salesReportsData.id,
						mainBranchId = salesReportsData.main_id,
						branchId = salesReportsData.branch_id,
						extension = ".json",
						fileName = (transactionType.toUpperCase() +"_"+ salesReportId +"_"+ mainBranchId +"_" + branchId),
						filePath = (defaulthPath + fileName + extension);
					obj.writeSalesReportDataToFile(filePath, salesReports, salesReportId).always(function (returnData){
						if(returnData >=1){
							obj.$tableSalesReportFileExportStatus.clear().draw();
							obj.$modalNewSalesReportToGenerate.modal("hide");
							obj.$tableSalesReportFileExportStatus.row.add([
								transactionType.toUpperCase(),
								obj.spanSuccess
							]);
							obj.$salesReportExportFileStatusMessage.empty().append("<h5 style='color:#3C8DBC;'>Export success. You can still generate the file again.</h5>");
							obj.$tableSalesReportFileExportStatus.draw(false);
							obj.refreshSalesReportTable();
						}
						else{
							obj.$modalNewSalesReportToGenerate.modal("hide");
							obj.$tableSalesReportFileExportStatus.clear().draw();
							obj.$tableSalesReportFileExportStatus.row.add([
								transactionType.toUpperCase(),
								obj.spanFailed
							]);
							obj.$salesReportExportFileStatusMessage.empty().append("<h5 style='color:red;'>You have a failed export. Please check your path directory settings. You can still generate the file again.</h5>");
							obj.$tableSalesReportFileExportStatus.draw(false);
						}
						obj.$modalSalesReportFileExportStatus.modal("show");
					});
				});
			}).always(function (){
				NProgress.done();
			});
		});

		obj.$btnViewSalesReport.click(function (){
			if(tableSalesReportsCurrentRowPos >= 0){
				tableSalesReportsSelectedRowPos = tableSalesReportsCurrentRowPos;
				obj.$keysalesReports.fnBlur();
				var salesReportId = obj.$keyTableSalesReportsApi._('tr', {"filter":"applied"})[tableSalesReportsSelectedRowPos][0];
				NProgress.start();
				obj.checkIfSalesReportExist(salesReportId).always(function (salesReportExist){
					if(salesReportExist >=1){
						obj.getAllReceiptsViaSalesReportId(salesReportId).always(function (getAllReceipts){
							obj.$tableViewSalesReport.clear().draw();
							var getAllReceipts = JSON.parse(getAllReceipts);
							for(var i=0; i<getAllReceipts.length; i++){
								obj.$tableViewSalesReport.row.add([
									getAllReceipts[i]["id"],
									numeral(getAllReceipts[i]["total_amount"]).format("0.00"),
									moment(getAllReceipts[i]["created_at"]).format("MMM DD, YYYY")
								]);
								obj.$tableViewSalesReport.draw(false);
							}
							obj.$modalViewSalesReport.modal("show");
						});
					}
					else{
						obj.showAlert("Sales Report Does Not Exist", "info", "Sales report ID "+ salesReportId + " has already been deleted in another session.");
						NProgress.done();
					}
				}).always(function (){
					NProgress.done();
				});
			}
		});

		obj.$btnGenerateSalesReport.click(function (){
			if(tableSalesReportsCurrentRowPos >= 0){
				tableSalesReportsSelectedRowPos = tableSalesReportsCurrentRowPos;
				obj.$keysalesReports.fnBlur();
				var salesReportId = obj.$keyTableSalesReportsApi._('tr', {"filter":"applied"})[tableSalesReportsSelectedRowPos][0];
				var selectedRow = (obj.$keyTableSalesReportsApi.$('tr', {"filter":"applied"})[tableSalesReportsSelectedRowPos]);
				NProgress.start();
				obj.checkIfSalesReportExist(salesReportId).always(function (salesReportExist){
					if(salesReportExist >=1){
						obj.getDefaultPath().always(function (defaulthPath){
							obj.getAppId().always(function (appId){
								obj.getMainId().always(function (mainId){
									var transaction = "EXPORT_SALES_REPORT";
									var extension = ".json";
									var fileName = (transaction +"_"+ salesReportId +"_"+ mainId +"_"+ appId);
									var filePath = (defaulthPath + fileName + extension);
									obj.generateSalesReportData(filePath, salesReportId).always(function (getAllReceipts){
										if(getAllReceipts >=1){
											obj.$keyTableSalesReportsApi.fnUpdate(obj.spanSuccess, selectedRow, 2, false);
											obj.showAlert("File Export Success", "info", "Your file" + fileName + " has been successfully created.");
											NProgress.done();
										}
										else{
											obj.$keyTableSalesReportsApi.fnUpdate(obj.spanFailed, selectedRow, 2, false);
											obj.showAlert("File Export Failed", "info", "Your file" + fileName + " has not been created. Please check your directory settings.");
											NProgress.done();
										}
									});
								});
							});
						});
					}
					else{
						obj.showAlert("Sales Report Does Not Exist", "info","Sales report ID "+ salesReportId + " has already been deleted in another session.");
						NProgress.done();
					}
				})
			}
		});
		// ***
		// receipts
		// ***
		obj.$btnRefreshReceiptsReport.click(function (){
			obj.refreshReceiptsReportTable();
		});

		obj.$btnViewItemsFromReceipts.click(function (){
			if(tableReceiptsCurrentRowPos >= 0){
				tableReceiptsSelectedRowPos = tableReceiptsCurrentRowPos;
				obj.$keyReceipts.fnBlur();
				var receiptId = obj.$keyTableReceiptsApi._('tr', {"filter":"applied"})[tableReceiptsSelectedRowPos][0];
				NProgress.start();
				obj.checkIfReceiptsExist(receiptId).always(function (receiptExists){
					if(receiptExists >=1){
						obj.getAllItemsFromThisReceipts(receiptId).always(function (getAllItemsFromReceipts){
							obj.$tableViewItemsFromReceipts.clear().draw(false);
							var getAllItemsFromReceipts = JSON.parse(getAllItemsFromReceipts);
							for(var i=0; i<getAllItemsFromReceipts.length; i++){
								obj.$tableViewItemsFromReceipts.row.add([
									getAllItemsFromReceipts[i]["item_id"],
									getAllItemsFromReceipts[i]["receipt_id"],
									numeral(getAllItemsFromReceipts[i]["price"]).format("0.00"),
									numeral(getAllItemsFromReceipts[i]["quantity"]).format("0"),
								]);
							}
							obj.$tableViewItemsFromReceipts.draw(false);
							obj.$modalViewItemsFromReceipt.modal("show");
						});
					}
					else{
						obj.showAlert("Receipt Does Not Exist", "info", "Receipt ID "+ receiptId + " has already been deleted in another session.");
						NProgress.done();
					}
				}).always(function (){
					NProgress.done();
				});
			}
		});
		// ***
		// returns
		// ***
		obj.$btnRefreshReturnsReport.click(function (){
			obj.refreshReturnsReportTable();
		});

		obj.$btnGenerateReturnFile.click(function (){
			if(tableReturnsCurrentRowPos >=0){
				tableReturnsSelectedRowPos = tableReturnsCurrentRowPos;
				obj.$keyReturns.fnBlur();
				var returnId = obj.$keyTableReturnsApi._('tr', {"filter":"applied"})[tableReturnsSelectedRowPos][0];
				var selectedRow = (obj.$keyTableReturnsApi.$('tr', {"filter":"applied"})[tableReturnsSelectedRowPos]);
				NProgress.start();
				obj.checkIfReturnExist(returnId).always(function (returnExist){
					if(returnExist >=1){
						obj.getDefaultPath().always(function (defaulthPath){
							obj.getAppId().always(function (appId){
								obj.getMainId().always(function (mainId){
									var transaction = "RETURN_ITEMS";
									var extension = ".json";
									var fileName = (transaction +"_"+ returnId +"_"+ mainId +"_"+ appId);
									var filePath = (defaulthPath + fileName + extension);
									obj.generateFileReturns(returnId, filePath).always(function (returnedData){
										if(returnedData >=1){
											obj.$keyTableReturnsApi.fnUpdate(obj.spanSuccess, selectedRow, 2, false);
											obj.showAlert("File Export Success", "info", "Your file " + fileName + " has been successfully created.");
											NProgress.done();
										}
										else{
											obj.$keyTableReturnsApi.fnUpdate(obj.spanFailed, selectedRow, 2, false);
											obj.showAlert("File Export Failed", "info", "Your file " + fileName + " has not been created. Please check your directory settings.");
											NProgress.done();
										}
									});
								});
							});
						});
					}
					else{
						obj.showAlert("Return Does Not Exist", "info","Return ID "+ returnId + " has already been deleted in another session.");
						NProgress.done();
					}
				}).always(function (){
					NProgress.done();
				});
			}
		})

		obj.$btnViewItemsFromReturns.click(function (){
			if(tableReturnsCurrentRowPos >= 0){
				tableReturnsSelectedRowPos = tableReturnsCurrentRowPos;
				obj.$keyReturns.fnBlur();
				var returnId = obj.$keyTableReturnsApi._('tr', {"filter":"applied"})[tableReturnsSelectedRowPos][0];
				NProgress.start();
				obj.checkIfReturnExist(returnId).always(function (returnExist){
					if(returnExist >=1){
						obj.getAllItemsFromReturn(returnId).always(function (allItemsFromReturn){
							obj.$tableViewItemsFromReturns.clear().draw(false);
							var allItemsFromReturn = JSON.parse(allItemsFromReturn);
							for(var i=0; i<allItemsFromReturn.length; i++){
								obj.$tableViewItemsFromReturns.row.add([
									allItemsFromReturn[i]["item_id"],
									allItemsFromReturn[i]["description"],
									numeral(allItemsFromReturn[i]["quantity"]).format("0")
								]);
							}
							obj.$tableViewItemsFromReturns.draw(false);
							obj.$modalViewItemsFromReturn.modal("show");
						});
					}
					else{
						obj.showAlert("Return Does Not Exist", "info","Return ID " + returnId + " has already been deleted in another session.");
						NProgress.done();
					}
				}).always(function (){
					NProgress.done();
				});
			}
		});
		// ***
		// Deliveries
		// ***
		obj.$btnRefreshDeliveriesReport.click(function (){
			obj.refreshDeliveriesReportTable();
		});

		obj.$btnViewItemsFromDeliveries.click(function (){
			if(tableDeliveriesCurrentRowPos >= 0){
				tableDeliveriesSelectedRowPos = tableDeliveriesCurrentRowPos;
				obj.$keyReturns.fnBlur();
				var deliveryId = obj.$keyTableDeliveriesApi._('tr', {"filter":"applied"})[tableDeliveriesSelectedRowPos][0];
				NProgress.start();
				obj.checkIfDeliveryExist(deliveryId).always(function (deliveryExist){
					if(deliveryExist >=1){
						obj.getAllItemsFromDelivery(deliveryId).always(function (itemsFromDelivery){
							obj.$tableViewItemsFromDeliveries.clear().draw(false);
							var itemsFromDelivery = JSON.parse(itemsFromDelivery);
							for(var i=0; i<itemsFromDelivery.length; i++){
								obj.$tableViewItemsFromDeliveries.row.add([
									itemsFromDelivery[i]["item_id"],
									itemsFromDelivery[i]["description"],
									numeral(itemsFromDelivery[i]["quantity"]).format("0")
								]);
							}
							obj.$tableViewItemsFromDeliveries.draw(false);
							obj.$modalViewItemsFromDeliveries.modal("show");
						});
					}
					else{
						obj.showAlert("Delivery Does Not Exist", "info","Delivery ID " +deliveryId + " has already been deleted in another session.");
						NProgress.done();
					}
				}).always(function(){
					NProgress.done();
				});
			}
		});
	};
	return {
		run: function () {
			initialize();
			bindEvents();
		}
	};
})();