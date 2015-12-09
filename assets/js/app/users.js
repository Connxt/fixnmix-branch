var Users = (function() {
	var obj = {};

	var initialize = function () {
		obj ={
			$tableUsers: $("#users_table").DataTable({
				"columns": [
					{ "sClass": "none","sWidth": "0%" },
					{ "sClass": "text-left font-bold", "sWidth": "15%" },
					{ "sClass": "text-left font-bold", "sWidth": "50%" },
					{ "sClass": "text-left font-bold", "sWidth": "15%" },
				],
				"columnDefs": [{ "targets": [0], "visible": false, "searchable": false }],
				"bLengthChange": false,
				responsive: false,
				"bPaginate" : true, 
		        "bAutoWidth": false
			}),

			$tableAddUsersList: $("#add_users_list_table").DataTable({
				"columns": [
					{ "sClass": "text-left font-bold", "sWidth": "15%" },
					{ "sClass": "text-left font-bold", "sWidth": "50%" },
				],
				 "columnDefs": [{ "targets": [0], "visible": false, "searchable": false }],
				"bLengthChange": false,
				responsive: true,
				"bPaginate" : true, 
		        "bAutoWidth": false,
		        "bFilter": false
			}),

			$btnAddUser: $("#btn_add_user"),
			$btnconfirmUser: $("#btn_confirm_users"),
			$bntCancelAddUser: $("#btn_cancel_add_user"),
			$btnRefreshUser: $("#btn_refresh_user"),
			$modalAddUser: $("#add_users_modal"),
			$txtAddUserDate: $("#add_user_date"),
			$inputAddUserFileDialogLoader: $("#input_open_add_users_dialog"),
			$addUserFile: "",

			confirmImportUser: function (usersData) {
				return $.post(BASE_URL + CURRENT_CONTROLLER + "/import_users",{usersData: usersData,},function (data) {});
			},
			getAllUsers: function () {
				return $.post(BASE_URL + CURRENT_CONTROLLER + "/get_all_users",{},function (data) {});
			},
			
			decryptUsersData: function (usersData) {
				return $.post(BASE_URL + CURRENT_CONTROLLER + "/decrypt_users_data",{usersData: usersData,},function (data) {});
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

			refreshUser: function () {
				obj.getAllUsers().always( function (getAllUsers) {
					NProgress.start();
					obj.$tableUsers.clear().draw();
					var getAllUsers = JSON.parse(getAllUsers);
					for(var i=0; i < getAllUsers.length; i++){
						var name = getAllUsers[i]['last_name'] + ", " + getAllUsers[i]['first_name'] + " " +  getAllUsers[i]['middle_name'];
						obj.$tableUsers.row.add([
							getAllUsers[i]['id'],
							getAllUsers[i]['username'],
							name,
							getAllUsers[i]['user_level']
						]).draw();
					}
				}).always( function () {
					NProgress.done();
				});
			},
		
			receiveUser: function (e) {
				var receiveUserData = e.target.result;
				if(receiveUserData != ""){
					try{
						obj.decryptUsersData(receiveUserData).always( function (decryptUsersData) {
							try{
								var userData = JSON.parse(decryptUsersData);
								var transaction = userData.transaction;
								var mainId = userData.main_id;
								var branchId = userData.branch_id;
								obj.$tableAddUsersList.clear().draw();
								obj.$txtAddUserDate.text(moment().format("MMM/DD/YYYY"));
								for(var i=0;i<userData.users.length; i++){
									var name = userData.users[i].last_name + ", " + userData.users[i].first_name + " " + userData.users[i].middle_name;
									obj.$tableAddUsersList.row.add([
										userData.users[i].username,
										name
									]).draw(false);
								}
								obj.$modalAddUser.modal("show");
							}catch (err){
								obj.showAlert("Stop", "warning", "File is invalid.");
							}
						}).always( function () {
							NProgress.done();
						});
					}catch (error){
						obj.showAlert("Stop", "warning", "File is invalid.");
					}
				}else{
					obj.showAlert("Stop", "warning", "File is invalid.");
				}
			},
		};
	};

	var bindEvents = function() {
		obj.refreshUser();
		obj.$btnAddUser.click( function () {	
			obj.$inputAddUserFileDialogLoader.click();
		});

		obj.$inputAddUserFileDialogLoader.change( function () {
			var filename = this.files[0];
			var fileReader = new FileReader();
			obj.$addUserFile = filename;
			fileReader.onload = obj.receiveUser;
			fileReader.readAsText(filename);
			this.value = null;
		});

		obj.$btnconfirmUser.click( function () {
			var filename = obj.$addUserFile;
			var fileReader = new FileReader();
			fileReader.onload = function (e) {
				var receiveUserData = e.target.result;
				try{
					obj.decryptUsersData(receiveUserData).always( function (decryptUsersData) {
						var userData = JSON.parse(decryptUsersData);
						obj.confirmImportUser(userData).always( function (importUser) {
							switch(importUser){
								case '-1':
									obj.showAlert("Stop", "warning", "Main store is invalid.");
								break;
								case '0':
									obj.showAlert("Stop", "warning", "Branch store is invalid.");
									break;
								case '1':
									obj.showAlert("Stop", "warning", "Transaction is already finished.");
									break;
								case '2':
									obj.showAlert("Users Added.", "success", "Users has been successfully added.");
									obj.refreshUser();
									obj.$modalAddUser.modal("hide");
									break;
								default:
									obj.showAlert("Stop", "warning", "File is invalid.");
									break;
								}
						});
					});
				}catch (err){
					obj.showAlert("Stop", "warning", "Please inform your administrator.");
				}
			}
			fileReader.readAsText(filename);
		});
		
		obj.$bntCancelAddUser.click( function () {
			obj.showConfirm("Are you sure you want to cancel?", "warning", "Yes", "No", function (isConfirm){
				if(isConfirm){
					obj.$modalAddUser.modal("hide");
				}
			});
		});
		obj.$btnRefreshUser.click( function () {
			obj.refreshUser();
		});
	};

	return {
		run: function () {
			initialize();
			bindEvents();
		}
	};
})();