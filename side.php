
<div id="account">

<?php if($user->logged_in()) {
        echo "You are ".$_SESSION['user']['name']."<br/>";
echo <<<EOF
	<hr/><br/>
	<a href="./rank.php">Current Poll</a><br/>
        <a href="./account.php">Manage your account</a><br/>
        <a href="/rank/logout">Log out</a><br/>
	<br/><hr/><br/>
EOF;
	}
?>

	<a href="./">Homepage</a><br/>
	<?php
		if(!$user->logged_in()){
			print '<a href="./login">Login</a><br/>';
			print '<a href="./signup">Signup</a><br/>';
		}
	?>
	
	<a href="./faq.php">Read the FAQ</a><br/>
</div>



