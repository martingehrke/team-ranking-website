<?php require_once('user.php'); ?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN"
	"http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="en" lang="en">

<head>
	<meta http-equiv="Content-Type" content="text/html;charset=utf-8" />
	<meta http-equiv="Content-Language" content="en-us" />
	<title>Signup Page</title>
	<link rel="stylesheet" type="text/css" href="./css/login.css" />
	<script type="text/javascript" src="./js/jquery-1.3.2.min.js"></script>
	<script src="js/jtip.js" type="text/javascript"></script>

	<link rel='stylesheet' href='./css/global.css' type='text/css' media='all' />

</head>

<body>
	<div class="login">
		<p>Signup Page</p>
<?php $user->signup_form(); ?>
	</div>
</body>

</html>
