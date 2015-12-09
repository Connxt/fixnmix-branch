var Items = (function() {
	var obj = {};

	var initialize = function () {
		obj = {
			$tableItemsList: $("#items_list_table").DataTable({
				"columns": [
					{ "sClass": "text-left font-bold", "sWidth": "15%"},
					{ "sClass": "text-left font-bold", "sWidth": "50%"},
					{ "sClass": "text-left font-bold", "sWidth": "10%"},
					{ "sClass": "text-left font-bold", "sWidth": "10%"},
				],
				"bLengthChange": false,
				responsive: true,
				"bPaginate" : true, 
		        "bAutoWidth": false,
			}),

			$tableReceiveItems: $("#receive_items_table").DataTable({
				"columns": [
					{ "sClass": "text-left font-bold", "sWidth": "15%"},
					{ "sClass": "text-left font-bold", "sWidth": "50%"},
					{ "sClass": "text-left font-bold", "sWidth": "10%"},
					{ "sClass": "text-left font-bold", "sWidth": "10%"},
				],
				"bLengthChange": false,
				responsive: true,
				"bPaginate" : true, 
		        "bAutoWidth": false,
		        "bFilter": false
		    }),
			$keyTableItemsList: new $.fn.dataTable.KeyTable($("#items_list_table").dataTable()),
			$btnRefreshItems: $("#btn_refresh_items"),
			$btnReceiveItems: $("#btn_receive_items"),
			$btnConfirmReceiveItems: $("#btn_confirm_receive_items"),
			$txtReceiveItemId: $("#receive_items_id"),
			$txtReceiveDate: $("#receive_items_date"),
			$txtReceiveFromMainStoreId: $("#receive_from_main_store_id"),
			$inputReceiveFileDialogLoader: $("#input_open_receive_items_file_dialog"),
			$modalReceiveItems: $("#recieve_items_modal"),
			$receieveItemFile: "",

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
			getDeliveredItem: function (deliveredItemId){
				return $.post(BASE_URL + CURRENT_CONTROLLER + "/get_delivered_item",{deliveredItemId: deliveredItemId},function (data) {});
			},

			isTransactionValid: function (mainId, branchId, deliveryIdFromMain, transaction) {
				return $.post(BASE_URL + CURRENT_CONTROLLER + "/is_transaction_valid",{mainId, branchId, deliveryIdFromMain, transaction: mainId, branchId, deliveryIdFromMain, transaction,},function (data) {});
			},
			confirmReceiveItems: function (deliveryData) {
				return $.post(BASE_URL + CURRENT_CONTROLLER + "/receive_items",{deliveryData: deliveryData,},function (data) {});
			},

			decryptDeliveredData: function (deliveryData) {
				return $.post(BASE_URL + CURRENT_CONTROLLER + "/decrypt_delivery_data",{deliveryData: deliveryData,},function (data) {});
			},

			refreshItems: function (){
				obj.getAllItems().always(function (getAllItems){
					obj.$tableItemsList.clear().draw();
					NProgress.start();
					var getAllItems = JSON.parse(getAllItems);
					for(var i=0; i<getAllItems.length; i++){
						obj.$tableItemsList.row.add([
							getAllItems[i]["id"],
							getAllItems[i]["description"],
							numeral(getAllItems[i]["price"]).format("0.00"),
							numeral(getAllItems[i]["quantity"]).format("0")
						]).draw(false);
					}
				}).always(function (){
					NProgress.done();
				});
			},

			receiveItems: function (e) {
				var receiveItemsData = e.target.result;
				if(receiveItemsData != ""){
					obj.decryptDeliveredData(receiveItemsData).always( function (receiveData) {
							try{
								NProgress.start();
								obj.$tableReceiveItems.clear().draw();
								var receiveData = JSON.parse(receiveData);
								var mainId = receiveData.main_id;
								var branchId = receiveData.branch_id;
								var deliveryIdFromMain = receiveData.id;
								var transaction = receiveData.transaction;

								obj.isTransactionValid(mainId, branchId, deliveryIdFromMain, transaction).always(function (isTransactionValid) {
									try{
										switch(isTransactionValid){
											case '-2':
												obj.showAlert("Stop", "warning", "Main store is invalid.");
												break;
											case '-1':
												obj.showAlert("Stop", "warning", "Branch store is invalid.");
												break;
											case '0':
												obj.showAlert("Stop", "warning", "Transaction already finished.");
												break;
											case '1':
												obj.showAlert("Stop", "warning", "Transaction type is invalid.");
												break;
											case '2':
												obj.$txtReceiveItemId.text(deliveryIdFromMain);
												obj.$txtReceiveFromMainStoreId.text(mainId);
												obj.$txtReceiveDate.text(moment().format("MMM DD, YYYY"));
												for(var i=0; i<receiveData.items.length; i++){
													obj.$tableReceiveItems.row.add([
														receiveData.items[i].item_id,
														receiveData.items[i].description,
														numeral(receiveData.items[i].price).format("0.00"),
														numeral(receiveData.items[i].quantity).format("0")
													 ]).draw(false);
												}

												obj.$modalReceiveItems.modal("show");
												break;
											default:
												obj.showAlert("Stop", "warning", "File content is invalid.");
												break;
										}
									}catch(err){
										obj.showAlert("Stop", "warning", "Please inform your administrator.");
									}	
								});
							}catch( err){
								obj.showAlert("Stop", "warning", "File content is invalid.");
							}
						}).always(function () {
							NProgress.done();
						});
					}else{
					obj.showAlert("Stop", "warning", "File is empty.");
				}
			}	
		};
	};

	var bindEvents = function() {
		obj.getAllItems().always(function (getAllItems){
			NProgress.start();
			var getAllItems = JSON.parse(getAllItems);
			for(var i=0; i<getAllItems.length; i++){
				obj.$tableItemsList.row.add([
					getAllItems[i]["id"],
					getAllItems[i]["description"],
					numeral(getAllItems[i]["price"]).format("0.00"),
					numeral(getAllItems[i]["quantity"]).format("0")
				]).draw(false);
			}
		}).always(function (){
			NProgress.done();
		});

		obj.$btnReceiveItems.click(function () {
			obj.$inputReceiveFileDialogLoader.click();
		});

		obj.$inputReceiveFileDialogLoader.change(function () {
			var filename = this.files[0];
			var reader = new FileReader();
			obj.$receieveItemFile = filename;
			reader.onload = obj.receiveItems;
			reader.readAsText(filename);
			this.value = null;
		});

		obj.$btnConfirmReceiveItems.click( function () {
			var filename = obj.$receieveItemFile;
			var fileReader = new FileReader();
			fileReader.onload = function (e) {
				var receiveItemsData = e.target.result;
				if(receiveItemsData != ""){
					try{
						obj.decryptDeliveredData(receiveItemsData).always( function (deliveryData) {
							var deliveryData = JSON.parse(deliveryData);
							obj.confirmReceiveItems(deliveryData).always(function ( receiveItems) {
								try{
									switch(receiveItems) {
										case '-2':
											obj.showAlert("Stop", "warning", "Main store is invalid.");
											break;
										case '-1':
											obj.showAlert("Stop", "warning", "Branch store is invalid.");
											break;
										case '0':
											obj.showAlert("Stop", "warning", "Transaction already finished.");
											break;
										case '1':
											obj.showAlert("Stop", "warning", "Transaction type is invalid.");
											break;
										case '2':
											obj.showAlert("Items Added", "success", "Items has been successfully added.");
											obj.$modalReceiveItems.modal("hide");
											obj.refreshItems();
											break;
										default:
											obj.contactDeveloperMessage();
											obj.$modalReceiveItems.modal("hide");
										break;
									}
								}catch (err) {
									obj.showAlert("Stop", "warning", "File content is invalid.");
								}
							});
						}).always(function () {
							NProgress.done();
						});
				} catch (err){
						obj.showAlert("Stop", "warning", "File content is invalid.");
				}
			} else {
				obj.showAlert("Stop", "warning", "File is empty.");
			}
		}

		fileReader.readAsText(filename);
	});

	obj.$btnRefreshItems.click(function (){
		obj.refreshItems();
		});
	};

	return {
		run: function () {
			initialize();
			bindEvents();
		}
	};
})();