<?php require_once('user.php'); $user->require_login(); ?>
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
<title>Ranking</title>
<script type="text/javascript" src="./js/jquery-1.3.2.min.js"></script>
<script type="text/javascript" src="./js/jquery-ui-1.7.1.custom.min.js"></script>
<script type="text/javascript" src="./js/countdown.js"></script>

<link rel='stylesheet' href='./css/styles.css' type='text/css' media='all' />
<script type="text/javascript">
  // When the document is ready set up our sortable with it's inherant function(s)
  $(document).ready(function() {
    $("#test-list, #test-list2").sortable({
		placeholder: 'shadow',
		connectWith: '.connectedSortable',
		update : function () { 
			if ($("#test-list li").length > 20){
	                	$("#test-list2 li:last").after($("#test-list li:last"));
                        }

		        var order = $('#test-list').sortable('serialize'); 
	                $("#info").load("process-sortable.php?"+order); 
		    } 
		
	});

	$("#update_order").click(function () {
		var order = $('#test-list').sortable('serialize');
		alert(order);
		$("#info").load(order);
		//$("#info").load("process-sortable.php?"+order);
	});
	
});


</script>
</head>
	<?php include('side.php'); ?>	
<body>
<?php

	$db = new PDO('sqlite:'.dirname(dirname(__FILE__)).'/rank/dbs/users.db');
        $db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

	$stmt = $db->prepare("SELECT week, year FROM polls WHERE datetime('now', 'localtime') between start and view_end");
        $stmt->execute();
        $TDATA = $stmt->fetch();


	print "<h2>Your Poll: Week $TDATA[0], $TDATA[1]</h2>";

?>
<pre>
<div id="info"></div>
</pre>
<table >
<tr><th>#</th><th>Team</th><th>Not Ranked</th></tr>
<tr><td valign=top>
<ol id="ranks">
<?php
	for ($i = 1; $i <= 20; $i ++){
		echo "<li>$i</li>";
	}
?>
</ol>
</td><td valign=top>
<ul id="test-list" class="connectedSortable" height=100%>
<?php 


	$stmt = $db->prepare("select poll_id from polls where datetime('now', 'localtime') between start and end");
	$stmt->execute();
	
	$CURRENT_POLL = $stmt->fetchColumn(0);
	if ($CURRENT_POLL == null)
		print "There is no poll open for voting";

	else {
	$USER_ID = $_SESSION['user']['id'];

	$stmt = $db->prepare("SELECT t.team_id, t.name  FROM teams t INNER JOIN rankings r ON t.team_id = r.team_id WHERE poll_id = ? AND user_id = ? ORDER BY r.rank");
	$stmt->execute(array($CURRENT_POLL, $USER_ID));
	$RANKED = $stmt->fetchAll();

	if(count($RANKED) == 0){
		$stmt = $db->prepare("SELECT team_id, name  FROM teams t ORDER BY name");
	        $stmt->execute();
        	$TEAMS = $stmt->fetchAll();
		$RANKED = array_slice($TEAMS,0,20);
		$NOT_RANKED = array_slice($TEAMS,20);
	}

	else { 
		$stmt = $db->prepare("SELECT team_id, name FROM teams WHERE team_id NOT IN (SELECT team_id FROM rankings WHERE poll_id = ? AND user_id = ? ORDER BY name)");
        	$stmt->execute(array($CURRENT_POLL, $USER_ID));

		$NOT_RANKED = $stmt->fetchAll();
	}

	foreach ($RANKED as $TEMP)
		echo  "<li id=\"listItem_$TEMP[0]\"><img class=\"oars\" src=\"./images/arrow.png\" alt=move width=10 height=10  />$TEMP[1]</li>\r";
	
	
?>

</ul>
</td>
<td>
<ol id="test-list2" class="connectedSortable">

<?php
	foreach ($NOT_RANKED as $TEMP)
        	echo  "<li id=\"listItem_$TEMP[0]\"><img class=\"oars\" src=\"./images/arrow.png\" alt=move width=10 height=10  />$TEMP[1]</li>\r";

	}
?>

</ul>
</td>
</tr></table>
<?php

        $stmt = $db->prepare("SELECT strftime(\"%Y,%m,%d,%H,%M\", end), end FROM polls WHERE datetime('now', 'localtime') between start and end");
        $stmt->execute();

        $END = $stmt->fetch();

        $JS_DATE_STR = substr($END[0],2);

        print "<script language=\"javascript\">countdown_clock($JS_DATE_STR,1)</script> at $END[1]<br/>";

?>
<!--<input type=submit name="update-order" value="Submit Rankings">-->
</form>
</body>
</html>
