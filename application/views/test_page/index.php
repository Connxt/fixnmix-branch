<!DOCTYPE html>
<html>
<head>
	<title>Test Page</title>
	<?php include("/../_shared/css.php"); ?>
</head>
<body>
	<?php include("/../_shared/js.php"); ?>
	<script type="text/javascript" src="<?php echo base_url();?>assets/js/app/<?php echo $current_page; ?>.js"></script>
	<script type="text/javascript">TestPage.run();</script>
</body>
</html>