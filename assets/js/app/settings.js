var Settings = (function() {
	var obj = {};
	var initialize = function () {
		obj = {
			$btnChangeFilePath: $("#btn_change_file_path"),
			setDefaultPath: function (path){
				return $.post(BASE_URL + "settings_controller/set_default_save_path",{path: path},function (data){});
			},
			getDefaultPath: function (){
				return $.post(BASE_URL + "settings_controller/get_default_save_path",{},function (data){});
			}
		};
	};

	var bindEvents = function() {
		obj.$btnChangeFilePath.click(function (){
			obj.getDefaultPath().always(function (defaultPath){
				swal({
					html: true,
					title: "<h3 style='text-align:left;'>Enter file directory to save the file.</h3>",
					text: "<h4 style='text-align:left;'>This is the directory that your file will be save:</h4>",
					type: "input",
					closeOnConfirm: true,
					confirmButtonClass: "btn-primary",
					confirmButtonColor: "#3c8dbc",
					animation: "slide-from-top",
					inputValue: defaultPath
				},
				function (setDefaultPathValue){
					NProgress.start();
					if (setDefaultPathValue){
					  	obj.setDefaultPath(setDefaultPathValue).always(function (){
					  		swal({html: true,
								title: "<b>File Directory Changed</b>",
								type: "success",
								text: "<span>Your file directory has been successfully updated.<span>",
								confirmButtonClass: "btn-primary",
								confirmButtonColor: "#3c8dbc"
							});
					  	}).always(function (){
					  		NProgress.done();
					  	});
					}
					else{
						NProgress.done();
					}
				});
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