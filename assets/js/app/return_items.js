var ReturnItems = (function() {
	var obj = {};
	var tableReturnItemsSelectedRowPos = -1;
	var tableReturnItemsCurrentRowPos = -1;
	var initialize = function () {
		obj = {
			$tableReturnItems: $("#return_items_table").DataTable({
				"columns": [
					{ "sClass": "text-left font-bold", "sWidth": "10%" },
					{ "sClass": "text-left font-bold", "sWidth": "50%" },
					{ "sClass": "text-left font-bold quantity editable", "sWidth": "10%" },
				],
				"bLengthChange": false,
				responsive: true,
		        scrollCollapse: false,
		        "bAutoWidth": false,
		        "bFilter": false,
		        "rowCallback": function (nRow, aData, iDisplayIndex, iDisplayIndexFull) {
					obj.makeColumnQuantityEditable();
				}
			}),
			$tableSelectItems: $("#select_items_table").DataTable({
				"columns": [
					{ "sClass": "text-left font-bold", "sWidth": "15%"},
					{ "sClass": "text-left font-bold", "sWidth": "50%"},
				],
				"bLengthChange": false,
				responsive: true,
				scrollCollapse: false,
				"bPaginate" : true, 
		        "bAutoWidth": false,
		        "bFilter": false
			}),
			$tableReturnFileExportStatus: $("#return_file_status_table").DataTable({
				"columns": [
					{ "sClass": "text-left font-bold", "sWidth": "40%"},
					{ "sClass": "text-left font-bold", "sWidth": "10%"},
				],
				"bLengthChange": false,
				responsive: true,
				scrollCollapse: false,
				"bPaginate" : true, 
		        "bAutoWidth": false,
		        "bFilter": false,
		        "bPaginate": false
			}),
			$btnSelectReturnItems: $("#btn_select_return_items"),
			$modalSelectItems: $("#select_items_modal"),
			$toggleSelectItems: $("#select_items_table tbody"),
			$btnAddItemsToList: $("#btn_add_items_to_list"),
			$btnReturnItems: $("#btn_return_items"),
			$btnDeleteItemFromList: $("#btn_delete_item"),
			$modalReturnFileExportStatus: $("#return_file_status_modal"),
			$keyReturnItems: new $.fn.dataTable.KeyTable($("#return_items_table").dataTable()),
			$keyTableReturnItemsApi: $("#return_items_table").dataTable(),
			$returnStatusMessage: $("#return_status_message"),
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
			getAllItems: function () {
				return $.post(BASE_URL + CURRENT_CONTROLLER + "/get_all_items",{},function (data){});
			},
			getItemInfo: function (itemId){
				return $.post(BASE_URL + CURRENT_CONTROLLER + "/get_item_info",{itemId:itemId},function (data){});
			},
			getItemThatDoNotExist: function (itemIds){
				return $.post(BASE_URL + CURRENT_CONTROLLER + "/get_items_that_do_not_exist",{itemIds:itemIds},function (data){});
			},
			getItemsWithNotEnoughQuantity: function(items){
				return $.post(BASE_URL + CURRENT_CONTROLLER + "/get_items_with_insufficient_quantity",{items:items},function (data){});
			},
			newReturn: function(items){
				return $.post(BASE_URL + CURRENT_CONTROLLER + "/new_return",{items:items},function (data){});
			},
			getDefaultPath: function (){
				return $.post(BASE_URL + "settings_controller/get_default_save_path",{},function (data){});
			},
			writeReturnDataToFile: function (filePath, returnData, returnId){
				return $.post(BASE_URL + CURRENT_CONTROLLER + "/write_return_data_to_file",{
							filePath:filePath,
							returnData:returnData,
							returnId:returnId
						},function (data){});
			},
			makeColumnQuantityEditable: function(){
				$("#return_items_table tbody td.editable").each(function () {
					obj.$keyReturnItems.event.remove.action(this);
					obj.$keyReturnItems.event.action(this, function (nCell) {
						tableReturnItemsSelectedRowPos = tableReturnItemsCurrentRowPos;
						obj.$keyReturnItems.block = true;
						$(nCell).editable(function (sVal) {
							NProgress.start();
							obj.$keyReturnItems.block = false;
							sVal = numeral(sVal).format("0");
							if($(this).hasClass("quantity")){
		        				var selectedRow = (obj.$keyTableReturnItemsApi.$('tr', {"filter":"applied"})[tableReturnItemsSelectedRowPos]);
								obj.$keyTableReturnItemsApi.fnUpdate(sVal, selectedRow, 2, false);	
							}
							NProgress.done();
							$(nCell).editable("destroy");
							return sVal;
						}, {
							"onblur": "submit",
							cssclass : 'form-class',
							height:($("span#edit").height() + 25) + "px",
							"onreset": function () {
								setTimeout(function () {
									obj.$keyReturnItems.block = false;
								}, 0);
							}
						});
						setTimeout(function () {
							$(nCell).click();
						}, 0);
					});
				});
			},
		};
	};

	var bindEvents = function() {

		obj.$toggleSelectItems.on("click", "tr", function () {
        	$(this).toggleClass("success");
    	});

		obj.$keyReturnItems.event.focus(null, null, function (node, x, y){
			tableReturnItemsCurrentRowPos = y;
			obj.$btnDeleteItemFromList.removeAttr("disabled");
		});

		obj.$keyReturnItems.event.blur(null, null, function (node, x, y){
			tableReturnItemsCurrentRowPos = -1;
			obj.$btnDeleteItemFromList.attr("disabled", "disabled");
		});

		obj.$btnAddItemsToList.click(function(){
			var validItemsSelected = [],
				tableReturnItems = (obj.$tableReturnItems.rows().data()),
				tableSelectItems = (obj.$tableSelectItems.rows(".success").data()),
				itemsSelected = $.map(tableReturnItems, function (renderedItems){ return (renderedItems[0]) }),
				itemsToBeSelected = $.map(tableSelectItems, function (selectedItems){ return (selectedItems[0]) });
			for(var i=0; i<itemsToBeSelected.length; i++){
				var isEqual = false;
				for(var j=0; j<itemsSelected.length; j++){
					if(itemsSelected[j] == itemsToBeSelected[i]){
						isEqual = true;
					}
				}
				if(!isEqual){
					validItemsSelected.push(itemsToBeSelected[i])
				}
			}
			if (validItemsSelected.length == 0){
				obj.$modalSelectItems.modal("hide");
			}
			else{
				NProgress.start();	
				for(var i=0; i<validItemsSelected.length; i++){
					obj.getItemInfo(validItemsSelected[i]).always(function (getItemInfo){
						var getItemInfo = JSON.parse(getItemInfo);
						$.each(getItemInfo, function (){
							obj.$tableReturnItems.row.add([
								this.id,
								this.description,
								0,
							]);
						});
						obj.$tableReturnItems.draw(false);
						obj.$modalSelectItems.modal("hide");
					});
				}
				NProgress.done();
			}
		});
	
		obj.$btnReturnItems.click(function(){
			var	itemsToReturn = (obj.$tableReturnItems.rows().data().toArray()),
				itemsThatHasLessQuantity = [],
				items = [],
				finalItems = [],
				itemIds = [];
			if(itemsToReturn.length >= 1){
				NProgress.start();
				for(var i=0; i<itemsToReturn.length; i++){
					itemIds.push(itemsToReturn[i][0]);
				}
				obj.getItemThatDoNotExist(itemIds).always(function (itemsThatDoNotExist){
					var itemsThatDoNotExist = JSON.parse(itemsThatDoNotExist);
					if(itemsThatDoNotExist.length <=0){
						for(var i=0; i<itemsToReturn.length; i++){
							items.push({
								itemId: itemsToReturn[i][0],
								quantity: itemsToReturn[i][2]
							});
						}
						obj.getItemsWithNotEnoughQuantity(items).always(function (itemsWithNotEnoughQuantity){
							var itemsWithNotEnoughQuantity = JSON.parse(itemsWithNotEnoughQuantity);
							if(itemsWithNotEnoughQuantity.length <=0){
								obj.showConfirm("Are you sure you want to return items?", "warning", "Yes, return it!", "No, cancel please!", function (isConfirm){
									if(isConfirm){
										obj.$btnReturnItems.attr("disabled","disabled");
										for(var i=0; i<itemsToReturn.length; i++){
											finalItems.push({
												itemId:itemsToReturn[i][0],
												quantity: itemsToReturn[i][2]
											});
										}
										obj.newReturn(finalItems).always(function (itemsDataReturned){
											obj.getDefaultPath().always(function (defaultPath){
												var itemReturnData = JSON.parse(itemsDataReturned),
													branchId = (itemReturnData.branch_id),
													transactionType = (itemReturnData.transaction),
													mainBranchId = (itemReturnData.main_id),
													return_id = (itemReturnData.id),
													fileName = (transactionType.toUpperCase() + "_" + return_id +"_"+ mainBranchId + "_" +  branchId),
													extension = ".json",
													filePath = (defaultPath + fileName + extension);
												obj.writeReturnDataToFile(filePath, itemsDataReturned, return_id).always(function (fileDataReturned){
													if(fileDataReturned >=1){
														obj.$tableReturnFileExportStatus.row.add([
															mainBranchId,
															obj.spanSuccess
														]);
														obj.$tableReturnFileExportStatus.draw(false);
														obj.$returnStatusMessage.empty().append("<h5 style='color:#3C8DBC;'>File export succes. Your file has been successfully created. You can still generate the file again in the reports page.</h5>");
													}
													else{
														obj.$tableReturnFileExportStatus.row.add([
															mainBranchId,
															obj.spanFailed
														]);
														obj.$tableReturnFileExportStatus.draw(false);
														obj.$returnStatusMessage.empty().append("<h5 style='color:red;'>You have a failed export file. Please check your directory settings. You can still generate the file again in the reports page.</h5>");
													}
													obj.$modalReturnFileExportStatus.modal("show");
													obj.$tableReturnItems.clear().draw();
													NProgress.done();
												});
											});
										});
										obj.$tableReturnFileExportStatus.clear().draw();
									}
									else{
										NProgress.done();
									}
								});
							}
							else{
								for(var i=0; i<itemsWithNotEnoughQuantity.length; i++){
									itemsThatHasLessQuantity.push(
										"<li style='text-align:left;'><h4>" 
											+ "Item id: " + "<strong>" + itemsWithNotEnoughQuantity[i]['id'] + ".</strong>" 
											+ "  Requested Qty: " + "<strong>" + itemsWithNotEnoughQuantity[i]['requested_quantity'] + ".</strong>" 
											+ "  Available Qty: " + "<strong>" + itemsWithNotEnoughQuantity[i]['available_quantity'] + "</strong>"
										+"</h4></li>"
									);
								}
								obj.showAlert("<h4><b>The following items cannot be returned. Requested quantity is greater than the saved quantity in the inventory.</b></h4>", 
									"warning", "<ul style='display:table; margin: 0 auto;list-style:none; padding:0;'>"+itemsThatHasLessQuantity.join("")+"</ul>" 
								);
								NProgress.done();
							}
						})
					}
					else{
						obj.showAlert("<b>Item Does Not Exist</b>", "warning", "<span>Items ID "+ itemsThatDoNotExist +" has already been deleted in another session. Please delete items in the list<span>");
						NProgress.done();
					}
				})
			}
			else{
				obj.showAlert("<b>Item List is Empty</b>", "info", "<span>Please select items to be returned.<span>");
				NProgress.done();
			}
		});

		obj.$btnDeleteItemFromList.click(function (){
			if(tableReturnItemsCurrentRowPos >= 0){
				NProgress.start();
				tableReturnItemsSelectedRowPos = tableReturnItemsCurrentRowPos;
				obj.$keyReturnItems.fnBlur();
				obj.$keyTableReturnItemsApi.fnDeleteRow(obj.$keyTableReturnItemsApi.$('tr', {"filter":"applied"})[tableReturnItemsSelectedRowPos]);
				NProgress.done();
			}
		});

		obj.$btnSelectReturnItems.click( function () {
			obj.getAllItems().always( function (getAllItems) {
				NProgress.start();
				var getAllItems = JSON.parse(getAllItems);
				obj.$tableSelectItems.clear().draw();
				for(var i=0; i < getAllItems.length; i++){
					obj.$tableSelectItems.row.add([
						getAllItems[i]['id'],
						getAllItems[i]['description']
					]);
				}
				obj.$tableSelectItems.draw(false);
				obj.$modalSelectItems.modal("show");
			}).always( function () {
				NProgress.done();
			});
		});
	};

	return {
		run: function () {
			initialize();
			bindEvents();
		}
	};
})();