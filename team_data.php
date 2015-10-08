<?php
	function average($array){
	    return array_sum($array)/count($array);
	}

	//The average function can be use independantly but the deviation function uses the average function.

	function deviation ($array){
	    $avg = average($array);
	    foreach ($array as $value)
        	$variance[] = pow($value-$avg, 2);

	    return sqrt(average($variance));
	}

	$db = new PDO('sqlite:'.dirname(dirname(__FILE__)).'/rank/dbs/users.db');
        $db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

	if(array_key_exists('load',$_GET))
		$POLL_ID = $_GET['load'];
	else{
		$stmt = $db->prepare("SELECT poll_id from polls where datetime('now', 'localtime') between start and view_end");
		$stmt->execute();
		$T = $stmt->fetch();
		$POLL_ID = $T['poll_id'];
	}	

	$stmt = $db->prepare("SELECT sum(21-rank) FROM rankings WHERE  poll_id = ? AND team_id = ? GROUP BY team_id");

	$stmt->execute(array($POLL_ID, $_GET['team_id']));
	
	$DATA = $stmt->fetch();

	printf ("Points: %.2f<br/>", $DATA[0]);
	
	$stmt = $db->prepare(" SELECT rank FROM rankings r WHERE poll_id = ? AND team_id = ?");

	$stmt->execute(array($POLL_ID, $_GET['team_id']));

	$DATA_ARRAY = $stmt->fetchAll();
	foreach ($DATA_ARRAY as $P)
		$VALUES[] = $P[0];

	printf ("Std Dev: %.2f<br/>", deviation($VALUES));
	print ("On ".count($VALUES)." ballots");

?>
