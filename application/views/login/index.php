<!DOCTYPE html>
<html>
<head>
	<title>Login</title>
	<?php include("/../_shared/css.php"); ?>
</head>
<body class="login-page">
    <div class="login-box">
    	<div class="login-logo">
        	<a href="#"><b>POS</b></a>
      	</div>
      	<div class="login-box-body">
        	<p class="login-box-msg">Sign in to start your session</p>
        	<form id="frm_login" method="post">
	          	<div class="form-group has-feedback">
		            <input id="username" type="text" name="username" class="form-control" placeholder="Username"/>
		            <span class="glyphicon glyphicon-user form-control-feedback"></span>
		            <div id="username_error" class="error-alert"></div>
	          	</div>
	          	<div class="form-group has-feedback">
		            <input id="password" type="password" name="password"class="form-control" placeholder="Password"/>
		            <span class="glyphicon glyphicon-lock form-control-feedback"></span>
		            <div id="password_error" class="error-alert"></div>
	          	</div>
	          	<br>
	          	<div class="row">
		            <div class="col-xs-4">
		              	<button type="submit" id="login" class="btn btn-default btn-block btn-flat">Sign In</button>
		            </div><!-- /.col -->
	          	</div>
        	</form>
      	</div><!-- /.login-box-body -->
      	<br>
      	<div class="alert alert-danger alert-dismissable" style="display:none;" id="error_message">
        	<p><i class="icon fa fa-ban"></i> Username and password did not match.</p>
      	</div>
    </div><!-- /.login-box -->

	<?php include("/../_shared/js.php"); ?>
	<script type="text/javascript" src="<?php echo base_url();?>assets/js/app/<?php echo $current_page; ?>.js"></script>
	<script type="text/javascript">Login.run();</script>
</body>
</html>