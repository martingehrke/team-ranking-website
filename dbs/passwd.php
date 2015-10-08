<?php
$config=array(
	'password'=>array(
                        'min'=>6, // minumum password length allowed
                        'salt'=>'asdfliaske(*@#*&#lKJSDLKAMNL@@oiq23l4k2nq2)(*#2,m3j5' // random characters for salting passwords & sessions
                ));


$password = $argv[2];
$name = $argv[1];

if(strlen($password)<$config['password']['min'])
	echo ("Password must be at least ".$config['password']['min']." characters");

else{
$password=md5(sha1($name).$config['password']['salt'].sha1($password));

echo $password;
}
?>

