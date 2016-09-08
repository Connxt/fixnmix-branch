var Login = (function() {
	var obj = {};
	var initialize = function () {
		obj = {
			$login: $("#login"),
			$frmLogin: $("#frm_login"),
			$errorMessage: $("#error_message"),
			LOGIN_CASHIER: 2,
			LOGIN_ADMIN: 1,
			LOGIN_FAILED: 0
		};
	};
	var bindEvents = function() {
		obj.$login.on("click", function () {
			var valitorUserLogin = obj.$frmLogin.validate({
				errorElement: "div",
				errorPlacement: function (error, element){
				error.appendTo("div#" + element.attr("name") + "_error")
				},
				rules:{
					username: {
						required: true,
					},
					password: {
						required: true,
					}
				},
				messages: {
					username: {
						required: "Enter your username.",
					},
					password: {
						required: "Enter your password.",
					}
				},
				submitHandler: function(form){
					NProgress.start();
					$.post(BASE_URL + CURRENT_CONTROLLER + "/login", {
						username: $("#username").val(),
						password: $("#password").val()
					}, function (data) {
						if(data != obj.LOGIN_FAILED){
							var userLevelId;
							var usersData = JSON.parse(data);
							$.each(usersData,function(){
								userLevelId = (this.user_level_id);
							});
							if(userLevelId == obj.LOGIN_ADMIN){
								window.location.href = BASE_URL + "/cashiering";
							}
							else if(userLevelId == obj.LOGIN_CASHIER){
								window.location.href = BASE_URL + "/cashiering";
							}
						}
						else{
							obj.$errorMessage.empty().append("<p><i class='icon fa fa-ban'></i> Username and password did not match.</p>").fadeIn(1000);
						}
					}).always(function (){
						NProgress.done();
					});
				}
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
