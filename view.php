<?php require_once('user.php'); ?>
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
<title>Ranking</title>
<script type="text/javascript" src="./js/jquery-1.3.2.min.js"></script>
<script type="text/javascript" src="./js/jquery-ui-1.7.1.custom.min.js"></script>

<link rel='stylesheet' href='./css/styles.css' type='text/css' media='all' />
</head>
	<?php include('side.php'); ?>	
<body>
<?php
	//connect o db
        $db = new PDO('sqlite:'.dirname(dirname(__FILE__)).'/rank/dbs/users.db');
        $db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

	if (array_key_exists('load', $_GET))
		$POLL_ID = $_GET['load'];
	else
		$POLL_ID = $db->query("SELECT poll_id FROM polls WHERE datetime('now', 'localtime') between start and view_end")->fetchColumn();

	if (array_key_exists('user', $_GET)){
		$USER_ID = $_GET['user'];
		$stmt = $db->prepare("SELECT name FROM users WHERE id = ?");
		$stmt->execute(array($USER_ID));
		$USERNAME = $stmt->fetchColumn();
	}
	else
		print "No User ID Specified";

	//header for current poll
        $stmt = $db->prepare("SELECT week, year FROM polls WHERE poll_id = ?");
	$stmt->execute(array($POLL_ID));
	$TDATA = $stmt->fetch();

        print "<h2>$USERNAME: Week $TDATA[0], $TDATA[1]</h2>";

?>

<pre>
<div id="info"></div>
</pre>
<table>
<tr><th>#</th><th>Team</th></tr>
<tr><td valign=top>
<?php 
	//if var passed to script load old poll
	$stmt = $db->prepare("SELECT t.team_id, t.name, sum(21-r.rank) as RRANK FROM teams t INNER JOIN rankings r ON t.team_id = r.team_id WHERE poll_id = ? AND user_id = ? GROUP BY r.team_id ORDER BY RRANK desc LIMIT 20");
	$stmt->execute(array($POLL_ID, $USER_ID));

	//get top 20 ranked teams
	$RANKED = $stmt->fetchAll();

	$stmt = $db->prepare("SELECT t.team_id, sum(21-r.rank) as RRANK FROM teams t INNER JOIN rankings r ON t.team_id = r.team_id WHERE poll_id = ? GROUP BY r.team_id ORDER BY RRANK desc");
	$stmt->execute(array($POLL_ID-1));

        $LAST_POLL = $stmt->fetchAll();

	//create a dictionary for last weeks data: KEYS=>team_id, VALUE=>last weeks rank
	foreach ($LAST_POLL as $KEY => $VALUE)
		$LAST_DICT[$VALUE[0]] = $KEY+1;	
	$LAST_POLL = null;//we no longer need the non-keyed data

	//***********************************************************
	//we need to actually rank the teams 
	//  and figure out ties and properly format them
	//***********************************************************
	$TEAM_ID_DICT = array(); //this weeks rankings dictionary: KEY=>team_id; VALUE=>this weeks rank
	for ($i=0; $i < 20; $i++)
		$TEAM_ID_DICT[$RANKED[$i]['team_id']] = $i+1;

	//output the numbering
	echo "<ol id=\"ranks\">"; 

	for ($i=1; $i <=20; $i++){
		echo "<li>$i</li>";
		
	}

	//output the top 20 teams
	echo "</ol> </td><td valign=top> <ul id=\"test-list\" height=100%>";

	foreach ($RANKED as $TEMP){
		$NAME = $TEMP[1]; //team name
		if ($TEMP[3] != 0) //did they recieve any first place votes
			$NAME = $TEMP[1]." ($TEMP[3])"; 

		//for computing whether team gets up or down arrow
		$LAST_WEEK_RANK = $LAST_DICT[$TEMP[0]];
		$THIS_WEEK_RANK = $TEAM_ID_DICT[$TEMP[0]];

		if(!$LAST_DICT) //this is the first poll of the season
                        $IMG = "";
		elseif($THIS_WEEK_RANK > $LAST_WEEK_RANK) //they dropped in rankings
			$IMG = '<img class="movement" src="./images/red-down-16x16.png">';
		elseif($THIS_WEEK_RANK < $LAST_WEEK_RANK) //they moved up in rankings
			$IMG = '<img class="movement" src="./images/green-up-16x16.png">';
		else //they did not move
			$IMG = "";

		//output list item

		//jtip 
		$JTIP = "./team_data.php?team_id=$TEMP[0]&load=$POLL_ID";

		echo  "<li id=\"listItem_$TEMP[0]\"><img class=\"oars\" src=\"./images/arrow.png\" alt=move width=10 height=10  />$NAME $IMG</li>\r";
	}
	
?>

</ul>
</td>
<td>
<?php
#print_r($TEAM_ID_DICT);
/*
	print "<table border=1><tr><th>name</th><th>avg rank</th><th>1st place votes</th></tr>";
foreach ($RANKED as $TEMP)
	print "<tr><td>$TEMP[1]</td><td>$TEMP[2]</td><td>$TEMP[3]</td></tr>";
	
	print "</table>";
*/
?>
</td></tr></table>
<!--<input type=submit name="update-order" value="Submit Rankings">-->
</form>
</body>
</html>
