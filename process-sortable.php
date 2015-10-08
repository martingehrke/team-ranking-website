<?php require_once('user.php'); $user->require_login(); ?>
<?php
/* This is where you would inject your sql into the database 
   but we're just going to format it and send it back
*/
$USER_ID = $_SESSION['user']['id']; 

$db = new PDO('sqlite:'.dirname(dirname(__FILE__)).'/dbs/users.db');
$db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

$stmt = $db->prepare("SELECT poll_id from polls where datetime('now', 'localtime') between start and view_end");
$stmt->execute();

$CURRENT_POLL = $stmt->fetchColumn(0);

if (count($_GET['listItem']) == 20){
	$db->exec("DELETE FROM rankings WHERE poll_id = $CURRENT_POLL AND user_id = $USER_ID");

	$stmt = $db->prepare('INSERT OR REPLACE INTO rankings (rank,team_id,user_id,poll_id) values (?,?,?,?)');

	foreach ($_GET['listItem'] as $position => $item) :
		$position++;
		$stmt->execute(array($position,$item,$USER_ID,$CURRENT_POLL));
	endforeach;
}

?>
