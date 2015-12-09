var Cashiering = (function() {
	var obj = {};
		tableSoldItemsSelectedRowPos = -1;
		tableSoldItemsCurrentRowPos = -1;
		tableSearchItemsSelectedRowPos = -1;
		tableSearchItemsCurrentRowPos = -1;
		transactionStatus = 0;
	var initialize = function () {
		obj = {
			$tableSoldItems: $("#sold_items_table").DataTable({
				"columns": [
					{ "sClass": "text-left font-bold", "sWidth": "15%","bSortable": false },
					{ "sClass": "text-left font-bold", "sWidth": "50%","bSortable": false },
					{ "sClass": "text-left font-bold quantity editable", "sWidth": "10%","bSortable": false },
					{ "sClass": "text-left font-bold", "sWidth": "10%","bSortable": false },
					{ "sClass": "text-left font-bold", "sWidth": "10%","bSortable": false },
				],
				// "sScrollY": "465",
        		"bScrollCollapse": false,
				"bLengthChange": false,
				responsive: true,
				"bPaginate" : true, 
		        "autoWidth": true,
		        "bFilter":false,
		        "rowCallback": function (nRow, aData, iDisplayIndex, iDisplayIndexFull) {
		        	obj.makeColumnQuantityEditable();
				}
			}),
			$tableSearchItems: $("#search_item_table").DataTable({
				"columns": [
					{ "sClass": "text-left font-bold", "sWidth": "15%"},
					{ "sClass": "text-left font-bold", "sWidth": "50%"},
					{ "sClass": "text-left font-bold", "sWidth": "10%"},
					{ "sClass": "text-left font-bold", "sWidth": "10%"}
				],
        		"bScrollCollapse": false,
				"bLengthChange": false,
				responsive: true,
				"bPaginate" : true, 
		        "autoWidth": true
			}),
			$textCashieringItemId: $("#cashiering_item_id"),
			$textCashieringDescription: $("#cashiering_description"),
			$textCashieringQuantity: $("#cashiering_quantity"),
			$textCashieringGrandTotal: $("#cashiering_grand_total"),
			$textCashieringAmountRendered: $("#cashiering_amount_rendered"),
			$textCashieringChange: $("#cashiering_change"),
			$btnCashieringAddItem: $("#btn_cashiering_add_item"),
			$btnCashieringSearchItem : $("#btn_cashiering_search_item"),
			$btnCashieringCompleteTransaction :$("#btn_cashiering_transact"),
			$btnCashieringNewTransaction: $("#btn_new_transaction"),
			$btnCashieringShowModalHelp: $("#btn_show_modal_help"),
			$btnCashieringCancelTransaction: $("#btn_cancel_transaction"),
			$btnCashieringDeleteItemInTheList: $("#btn_delete_items_in_the_list"),
			$keyTableSoldItems: new $.fn.dataTable.KeyTable($("#sold_items_table").dataTable()),
			$keyTableSearchItems: new $.fn.dataTable.KeyTable($("#search_item_table").dataTable()),
			$keyTableSoldItemsApi: $("#sold_items_table").dataTable(),
			$keyTableSearhItemsApi: $("#search_item_table").dataTable(),
			$modalSearchItem: $("#search_item_modal"),
			$modalViewShortCuts: $("#view_help_controls_modal"),
			// function
			getAllItems: function (){
				return $.post(BASE_URL + CURRENT_CONTROLLER + "/get_all_items",{},function (data){});
			},
			getItemInfo: function (itemId){
				return $.post(BASE_URL + CURRENT_CONTROLLER + "/get_item_info",{itemId: itemId},function (data){});
			},
			checkIfItemExist: function (itemId){
				return $.post(BASE_URL + CURRENT_CONTROLLER + "/item_exists",{itemId:itemId},function (data){});
			},
			checkIfItemQuantityIsEnough: function (itemId, requestedQuantity){
				return $.post(BASE_URL + CURRENT_CONTROLLER + "/is_item_quantity_enough",{
							itemId: itemId,
							requestedQuantity: requestedQuantity
						},function (data){});
			},
			checkIfItemsOnTheListExist: function (itemIds){
				return $.post(BASE_URL + CURRENT_CONTROLLER + "/get_items_that_do_not_exist",{
							itemIds: itemIds
						},function (data){});
			},
			checkIfItemsOnTheListHasEnoughQuantity: function (items){
				return $.post(BASE_URL + CURRENT_CONTROLLER + "/get_items_with_insufficient_quantity",{items: items},function (data){});
			},
			saveTransaction: function (items){
				return $.post(BASE_URL + CURRENT_CONTROLLER + "/save_transaction",{items: items},function (data){});
			},
			showConfirm: function (text, type, confirmButtonText, cancelButtonText, callback){
				obj.removeShortCuts();
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
				obj.removeShortCuts();
				swal({
					html:true,
					title: title,
				  	text: text,
				  	type: type,
				  	confirmButtonClass: "btn-primary",
				  	confirmButtonColor: "#3c8dbc",
				  	closeOnConfirm:true
				},function (isConfirm){
					if(isConfirm){
						obj.addShortCuts();
					}
				});	
			},
			showAlertWithCallBack: function(title, type, text, callback){
				obj.removeShortCuts();
				swal({
					html:true,
					title: title,
				  	text: text,
				  	type: type,
				  	confirmButtonClass: "btn-primary",
				  	confirmButtonColor: "#3c8dbc",
				  	closeOnConfirm:true
				}, callback );	
			},
			makeColumnQuantityEditable: function(){
				$("#sold_items_table tbody td.editable").each(function () {
					obj.$keyTableSoldItems.event.remove.action(this);
					obj.$keyTableSoldItems.event.action(this, function (nCell) {
						obj.$keyTableSoldItems.event.remove.focus(nCell);
						if(obj.transactionExist()){
							tableSoldItemsSelectedRowPos = tableSoldItemsCurrentRowPos;
							obj.$keyTableSoldItems.block = true;
							$(nCell).editable(function (sVal) {
								obj.$keyTableSoldItems.block = false;
								sVal = numeral(sVal).format("0");
								if($(this).hasClass("quantity")){
									var rowInTheList = obj.$tableSoldItems.row(tableSoldItemsSelectedRowPos);
									var itemId = rowInTheList.data()[0];
									var itemDescription = rowInTheList.data()[1];
									var itemQuantity = rowInTheList.data()[2];
									var itemPrice = rowInTheList.data()[3];
									rowInTheList.data([
										itemId,
										itemDescription,
										numeral(sVal).format("0"),
										numeral(itemPrice).format("0.00"),
										numeral(parseFloat(itemPrice) * parseFloat(sVal)).format("0.00")
									]).draw(false);
									obj.$textCashieringGrandTotal.val(obj.getGrandTotal());
								}
								$(nCell).editable("destroy");
								return sVal;
							}, {
								"onblur": "submit",
								cssclass : 'form-class',
								height:($("span#edit").height() + 25) + "px",
								"onreset": function () {
									setTimeout(function () {
										obj.$keyTableSoldItems.block = false;
									}, 0);
								}
							});
							setTimeout(function () {
								$(nCell).click();
							}, 0);
						}
					});
				});
			},
			newTransaction: function (){
				obj.removeShortCuts();
				NProgress.start();
				obj.showConfirm("Are you sure you want to start a new transaction?", "info", "Yes, Please!", "No, Please!", function (isConfirm){
					if (isConfirm) {
						obj.addShortCuts();
						transactionStatus = 1;
						obj.enableOnNewTransaction();
						NProgress.done();
						setTimeout(function(){
							obj.$textCashieringItemId.focus() 
						},1);
					}
					else{
						NProgress.done();
						obj.addShortCuts();
					}
				});
			},
			addItem: function(elementId){
				if(obj.transactionExist()){
					var itemId = obj.$textCashieringItemId.val();
					var	requestedQuantity = (elementId == "cashiering_item_id") ? 0 : parseInt(obj.$textCashieringQuantity.val());
					var initialCondition = (elementId == "cashiering_item_id") ? (itemId != "") : (itemId != "" && requestedQuantity > 0);
					if(initialCondition){
						obj.checkIfItemExist(itemId).always(function (checkIfItemExist){
							if(checkIfItemExist >=1){
								obj.getItemInfo(itemId).always(function (getItemInfo){
									var getItemInfo = JSON.parse(getItemInfo);
									if(elementId == "cashiering_item_id"){
										$.each(getItemInfo, function(){
											obj.$textCashieringDescription.val(this.description);
											obj.$textCashieringQuantity.focus();
										});
									}
									else{
										obj.checkIfItemQuantityIsEnough(itemId, requestedQuantity).always(function (checkItemQuantity){
											var getItemQuantityData = JSON.parse(checkItemQuantity);
											if(checkItemQuantity >=1){
												$.each(getItemInfo, function(){
													var itemExistInTheList = false;
													var rowInTheList;
													var numOfRows = obj.$tableSoldItems.rows().data().length;
													for(var i=0; i<numOfRows; i++){
														if(obj.$tableSoldItems.row(i).data()[0] == this.id){
															rowInTheList = obj.$tableSoldItems.row(i);
															itemExistInTheList = true;
															break;
														}
													}
													if(itemExistInTheList){
														requestedQuantity = parseInt(requestedQuantity) + parseInt(rowInTheList.data()[2]);
														rowInTheList.data([
															this.id,
															this.description,
															numeral(requestedQuantity).format("0"),
															numeral(this.price).format("0.00"),
															numeral(this.price * requestedQuantity).format("0.00")
														]).draw(false);
													}
													else{
														obj.$tableSoldItems.row.add([
															this.id,
															this.description,
															numeral(requestedQuantity).format("0"),
															numeral(this.price).format("0.00"),
															numeral(this.price * requestedQuantity).format("0.00")
														]);
														obj.$tableSoldItems.draw(false);
													}
													obj.$textCashieringQuantity.val("");
													obj.$textCashieringDescription.val("");
													obj.$textCashieringItemId.val("");
													obj.$textCashieringGrandTotal.val(obj.getGrandTotal());
													obj.$textCashieringItemId.focus();
												});
											}
											else{
												obj.$textCashieringQuantity.focus();
												obj.showAlert("<b>Quantity is Not Enough</b>", "info",
													"<span>Item "+ itemId +" cannot be sold. Requested quantity is greater than the saved quantity in the inventory.<span>" + "<br><br>"
													+ "<ul style ='list-style:none; padding:0px;'>"
											  	  	+"<li><h4>Requested Quantity: <strong>"+ getItemQuantityData.requested_quantity +"</strong></h4></li>"
											  	  	+ "<li><h4>Available Quantity: <strong>"+ getItemQuantityData.available_quantity +"</strong></h4></li>"
											  		+ "</ul>"
												);
											}
										});
									}
								});
							}
							else{
								obj.$textCashieringItemId.focus();
								obj.showAlert("<h3>Item ID Does Not Exist</h3>", "info", "<span>Item ID "+ itemId +" was not found in the inventory.<span>");
							}
						});
					}
					else if(itemId == ""){
						obj.$textCashieringItemId.focus();
						obj.showAlert("", "info", "Please enter a valid item id number.");
					}
					else if(requestedQuantity <= 0 || isNaN(requestedQuantity)) {
						obj.$textCashieringQuantity.focus();
						obj.showAlert("", "info", "Please enter a valid quantity.");
					}
				}
			},
			addItemThroughSearch: function(){
				if(tableSearchItemsCurrentRowPos >=0){
					tableSearchItemsSelectedRowPos = tableSearchItemsCurrentRowPos;
					obj.$keyTableSearchItems.fnBlur();
					var itemId = obj.$keyTableSearhItemsApi._('tr', {"filter":"applied"})[tableSearchItemsSelectedRowPos][0];
					obj.checkIfItemExist(itemId).always(function (checkIfItemExist){
						if(checkIfItemExist >=1){
							obj.getItemInfo(itemId).always(function (getItemInfo){
								var getItemInfo = JSON.parse(getItemInfo);
								$.each(getItemInfo, function(){
									obj.$textCashieringDescription.val(this.description);
									obj.$textCashieringItemId.val(this.id);
									obj.$textCashieringQuantity.val("");
									obj.$modalSearchItem.modal("hide");
									obj.$textCashieringQuantity.focus();
								});
							});
						}
						else{
							obj.$modalSearchItem.modal("hide");
							obj.showAlertWithCallBack("<h3>Item ID Does Not Exist</h3>", "info", "<span>Item ID "+ itemId +" was not found in the inventory.<span>",function (isConfirm){
								if(isConfirm){
									obj.addShortCuts();
									obj.$modalSearchItem.modal("show");
									obj.$keyTableSearhItemsApi.fnDeleteRow(obj.$keyTableSearhItemsApi.$('tr', {"filter":"applied"})[tableSearchItemsSelectedRowPos]);
								}
							});						
						}
					});		
				}
			},
			completeTransaction: function(){
				if(obj.transactionExist()){
					var itemsToBeSold = obj.$tableSoldItems.rows().data().toArray();
					var itemIds = [];
					var items = [];
					var finalItems = [];
					var itemsThatHasLessQuantity = [];
					for(var i=0; i<itemsToBeSold.length; i++){
						itemIds.push(itemsToBeSold[i][0]);
					}
					obj.checkIfItemsOnTheListExist(itemIds).always(function (checkIfItemsOnTheListExist){
						var itemIdsThatDoNotExist = JSON.parse(checkIfItemsOnTheListExist);
						if(itemIdsThatDoNotExist.length <= 0){
							for(var i=0; i<itemsToBeSold.length; i++){
								items.push({
									itemId:itemsToBeSold[i][0],
									quantity:itemsToBeSold[i][2]
								});
							}
							obj.checkIfItemsOnTheListHasEnoughQuantity(items).always(function (checkIfItemsOnTheListHasEnoughQuantity){
								var itemsWithNotEnoughQuantity = JSON.parse(checkIfItemsOnTheListHasEnoughQuantity);
								if(itemsWithNotEnoughQuantity.length <=0){
									obj.showConfirm("Are you sure you want to complete this transaction?", "info", "Yes, Please!", "No, Please!", function (isConfirm){
										if(isConfirm){
											obj.addShortCuts();
											for(var i=0; i<itemsToBeSold.length; i++){
												finalItems.push({
													itemId:itemsToBeSold[i][0],
													quantity:itemsToBeSold[i][2],
													price: itemsToBeSold[i][3]
												});
											}
											obj.saveTransaction(finalItems).always(function (saveTransaction){
												obj.closeTransaction(true);
												transactionStatus = 0;
												obj.$textCashieringChange.val(numeral(obj.$textCashieringAmountRendered.val() - obj.$textCashieringGrandTotal.val()).format("0.00"));
												obj.$textCashieringAmountRendered.val(numeral(obj.$textCashieringAmountRendered.val()).format("0.00"));
											});
										}
										else{
											obj.addShortCuts();
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
									obj.showAlert("<h4><b>The following items cannot be deliver. Requested quantity is greater than the saved quantity in the inventory.</b></h4>", 
										"warning", "<ul style='display:table; margin: 0 auto;list-style:none; padding:0;'>"+itemsThatHasLessQuantity.join("")+"</ul>"
									);
								}
							});
						}
						else{
							obj.showAlert("<b>Items Does Not Exist</b>", "warning", 
								"<span>Items ID "+ itemIdsThatDoNotExist +" has already been deleted by another session. Please remove the following items to continue this transaction.<span>"
							);
						}
					});
				}
			},
			checkIfAmountsIsValid: function(){
				if(obj.transactionExist()){
					if(obj.$tableSoldItems.rows().data().length <=0){
						obj.showAlertWithCallBack("", "info", "There is currently no items to be sold. Please select items to complete the transaction",function (isConfirm){
							if(isConfirm){
								obj.addShortCuts();
								setTimeout(function(){
									obj.$textCashieringItemId.focus();
								},0);
								return false;
							}
						});
					}
					else if(obj.$textCashieringAmountRendered.val() == "" || isNaN(obj.$textCashieringAmountRendered.val()) || parseFloat(obj.$textCashieringAmountRendered.val()) < 0){
						obj.showAlert("", "info", "Please input a valid amount to be rendered.");
						return false;
					}
					else if(numeral(obj.$textCashieringAmountRendered.val()) < numeral(obj.$textCashieringGrandTotal.val())){
						obj.showAlert("", "info", "Amount rendered should not be lessthan than the grand total. Please adjust your amounts to continue this transaction.");
					}
					else{
						return true;
					}
				}
			},
			deleteItemInTheList: function(){
				if(obj.transactionExist()){
					if(tableSoldItemsCurrentRowPos >= 0 ){
						tableSoldItemsSelectedRowPos = tableSoldItemsCurrentRowPos;
						obj.$keyTableSoldItems.fnBlur();
						var itemId = obj.$keyTableSoldItemsApi.$('tr', {"filter":"applied"})[tableSoldItemsSelectedRowPos];
						obj.$keyTableSoldItemsApi.fnDeleteRow(obj.$keyTableSoldItemsApi.$('tr',{"filter":"applied"})[tableSoldItemsSelectedRowPos]);

					}
				}
			},
			getGrandTotal: function(){
				var grandTotal = 0;
				var numOfRows = obj.$tableSoldItems.rows().data();
				for(var i=0; i<numOfRows.length; i++){
					grandTotal = parseFloat(numeral(grandTotal).format("0.00")) + parseFloat(numeral(obj.$tableSoldItems.row(i).data()[4]).format("0.00"));
				}
				return numeral(grandTotal).format("0.00");
			},
			transactionExist: function(){
				if(transactionStatus >= 1){
					return true;
				}	
				else{
					obj.$keyTableSoldItems.fnBlur();
					obj.showAlert("", "info", "There is currently no transaction. Please press Ctrl+Alt+0 to start a new transaction.");
				}
			},
			openSearchItem: function (){
				if(obj.transactionExist()){
					obj.getAllItems().always(function(allItems){
						obj.$tableSearchItems.clear().draw(false);
						var allItemsData = JSON.parse(allItems);
						for(var i=0; i<allItemsData.length; i++){
							obj.$tableSearchItems.row.add([
								allItemsData[i]['id'],
								allItemsData[i]['description'],
								allItemsData[i]['quantity'],
								allItemsData[i]['price']
							]);
							obj.$tableSearchItems.draw(false);
						}
						obj.$modalSearchItem.modal({});
					});
				}
			},
			enableOnNewTransaction: function (){
				obj.$textCashieringQuantity.removeAttr("disabled");
				obj.$textCashieringItemId.removeAttr("disabled");
				obj.$textCashieringAmountRendered.removeAttr("disabled");
				obj.$tableSoldItems.clear().draw();
				obj.$textCashieringAmountRendered.val("");
				obj.$textCashieringDescription.val("");
				obj.$textCashieringChange.val("");
				obj.$textCashieringQuantity.val("");
				obj.$textCashieringGrandTotal.val("");
				obj.$textCashieringItemId.val("");
				obj.$textCashieringItemId.focus();
			},
			disableOnCloseTransaction: function(){
				obj.$textCashieringItemId.attr("disabled","disabled");
				obj.$textCashieringQuantity.attr("disabled","disabled");
				obj.$textCashieringAmountRendered.attr("disabled", "disabled");
				obj.$tableSoldItems.clear().draw();
				obj.$textCashieringAmountRendered.val("");
				obj.$textCashieringDescription.val("");
				obj.$textCashieringChange.val("");
				obj.$textCashieringQuantity.val("");
				obj.$textCashieringGrandTotal.val("");
				obj.$textCashieringItemId.val("");
			},
			closeTransaction: function (transaction_finish){
				if(transactionStatus == 0){
					obj.disableOnCloseTransaction();
				}
				else if(transaction_finish){
					obj.$textCashieringItemId.attr("disabled","disabled");
					obj.$textCashieringQuantity.attr("disabled","disabled");
					obj.$textCashieringAmountRendered.attr("disabled", "disabled");
				}
				else if(transactionStatus == 1){
					obj.showConfirm("Are you sure you want to cancel this transaction?", "info", "Yes, Please!", "No, Please!",function (isConfirm){
						if(isConfirm){
							obj.addShortCuts();
							transactionStatus = 0;
							obj.disableOnCloseTransaction();
						}
						else{
							obj.addShortCuts();
						}
					});
				}
			},
			addShortCuts: function (){
				shortcut.add("Ctrl+Alt+H", function () {
					obj.$modalViewShortCuts.modal("show");
				});
				shortcut.add("Ctrl+I", function () {
					obj.$textCashieringItemId.focus();
				});
				shortcut.add("Ctrl+Alt+D", function () {
					obj.deleteItemInTheList();
				});
				shortcut.add("Ctrl+Alt+T", function () {
					obj.$keyTableSoldItems.fnSetPosition(0, 0);
				});
				shortcut.add("Ctrl+Alt+F", function () {
					obj.openSearchItem();
				});
				shortcut.add("Ctrl+Alt+0", function () {
					obj.newTransaction();
				});
				shortcut.add("Ctrl+Alt+Enter", function () {
					obj.$keyTableSoldItems.fnBlur();
					obj.$textCashieringAmountRendered.focus();
				});
				shortcut.add("Ctrl+Alt+Backspace", function () {
					obj.closeTransaction();
				});
			},
			removeShortCuts: function(){
				var combos = [
					"Ctrl+Alt+H",
					"Ctrl+Alt+I",
					"Ctrl+Alt+D",
					"Ctrl+Alt+T",
					"Ctrl+Alt+F",
					"Ctrl+Alt+0",
					"Ctrl+Alt+Enter",
					"Ctrl+Alt+Backspace",
				];
				for(var i = 0; i < combos.length; i++) {
					shortcut.remove(combos[i]);
				}
			},
		};
	};

	var bindEvents = function() {
		obj.$textCashieringAmountRendered.numeric();

		obj.$textCashieringQuantity.numeric(false, function() {});

		obj.closeTransaction();

		obj.addShortCuts();

		obj.$btnCashieringNewTransaction.click(function (){
			obj.newTransaction();
		});

		obj.$btnCashieringSearchItem.click(function (){
			obj.openSearchItem();
		});

		obj.$btnCashieringCancelTransaction.click(function (){
			obj.closeTransaction();
		});

		obj.$btnCashieringAddItem.click(function(){
			obj.addItem($(this).attr("id"));
		});

		obj.$btnCashieringDeleteItemInTheList.click(function (){
			obj.deleteItemInTheList();
		});

		obj.$btnCashieringShowModalHelp.click(function (){
			obj.$modalViewShortCuts.modal("show");
		});
		// modal search items
		obj.$modalSearchItem.on("shown.bs.modal", function () {
			obj.removeShortCuts();
		});
		obj.$modalSearchItem.on("hidden.bs.modal", function () {
			obj.addShortCuts();
		});
		// modal view help
		obj.$modalViewShortCuts.on("shown.bs.modal", function () {
			obj.removeShortCuts();
		});
		obj.$modalViewShortCuts.on("hidden.bs.modal", function () {
			obj.addShortCuts();
		});

		obj.$modalSearchItem.on("shown.bs.modal", function (){
			$("#search_item_table_filter input[type='search']").focus();
			obj.$keyTableSearchItems.fnSetPosition(0, 0);
		});

		obj.$textCashieringItemId.keypress(function (e){
			if(e.keyCode == 13) {
				obj.addItem($(this).attr("id"));
			}
		});

		obj.$textCashieringQuantity.keypress(function (e){
			if(e.keyCode == 13) {
				obj.addItem($(this).attr("id"));
			}
		});

		obj.$textCashieringAmountRendered.keypress(function (e){
			if(e.keyCode == 13){
				if(obj.checkIfAmountsIsValid()){
					obj.completeTransaction();
				}
			}
		});

		obj.$btnCashieringCompleteTransaction.click(function (){
			if(obj.checkIfAmountsIsValid()){
				obj.completeTransaction();
			}
		});

		//key table for sold items
		obj.$keyTableSoldItems.event.focus(null, null, function (node, x, y){
			tableSoldItemsCurrentRowPos = y;
		});
		obj.$keyTableSoldItems.event.blur(null, null, function (node, x, y){
			tableSoldItemsCurrentRowPos = -1;
		});
		// key table for search items
		obj.$keyTableSearchItems.event.focus(null, null, function (node, x, y){
			tableSearchItemsCurrentRowPos = y;
		});
		obj.$keyTableSearchItems.event.blur(null, null, function (node, x, y){
			tableSearchItemsCurrentRowPos = -1;
		});
		obj.$keyTableSearchItems.event.action(null, null, function (node, x, y){
			obj.addItemThroughSearch();
		});
	};
	return {
		run: function () {
			initialize();
			bindEvents();
		}
	};
})();