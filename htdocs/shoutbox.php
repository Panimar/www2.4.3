<?php

/************************
	CONSTANTS
/************************/
include "config.php";
define("HOST", "$db_host");
define("USER", "$db_user");
define("PASSWORD", "$db_pass");
define("DB", "$db_name");

/************************
	FUNCTIONS
/************************/
function connect($db, $user, $password){
	$link = @mysql_connect($db, $user, $password);
	if (!$link)
	    die("Could not connect: ".mysql_error());
	else{
		$db = mysql_select_db(DB);
		if(!$db)
			die("Could not select database: ".mysql_error());
		else return $link;
	}
}
function getContent($link, $num){
	$res = @mysql_query("SELECT date, user, message FROM shoutbox ORDER BY date DESC LIMIT ".$num, $link);
	if(!$res)
		die("Error: ".mysql_error());
	else
		return $res;
}
function insertMessage($user, $message)
{
	$ip=$_SERVER['REMOTE_ADDR'];
	$query = sprintf("INSERT INTO shoutbox(user, message, ip) VALUES('%s', '%s', '$ip');", mysql_real_escape_string(strip_tags($user)), mysql_real_escape_string(strip_tags($message)));
	$res = @mysql_query($query);
	if(!$res)
		die("Error: ".mysql_error());
	else
		return $res;
}

/******************************
	MANAGE REQUESTS
/******************************/
if(!$_POST['action']){
	//We are redirecting people to our shoutbox page if they try to enter in our shoutbox.php
	header ("Location: index.php"); 
}
else{
	$link = connect(HOST, USER, PASSWORD);
	switch($_POST['action']){
		case "update":
			$res = getContent($link, 11);
			while($row = mysql_fetch_array($res)){
			
							$test3= @mysql_query("SELECT * FROM ".$acc_db.".account WHERE username='".$row['user']."'") or die(mysql_error());
			$test4=mysql_fetch_assoc($test3);
			$test1= @mysql_query("SELECT * FROM ".$acc_db.".account_access WHERE RealmID='-1' AND id='".$test4['id']."'") or die(mysql_error());
			$test2=mysql_fetch_assoc($test1);

			if ($test2['gmlevel']==''){
			
			$result .= '<div class="sb_message"><div class="sb_comme"><strong>'
			.$row['user'].'</strong><div class="sb_m_date">'
			.$row['date'].'</div></div><div class="sb_te_comme">'
			.$row['message'].'</div></div>
			';
			
			}

			else{
						$result .= '<div class="sb_message"><div class="sb_comme"><strong><img src="brg.gif" />&nbsp;'
			.$row['user'].'</strong><div class="sb_m_date">'
			.$row['date'].'</div></div><div class="sb_te_comme"><font color="#00FFFF">'
			.$row['message'].'</font></div></div>
			';
			}
			
			}
			echo $result;
			break;
		case "insert":
			echo insertMessage($_POST['nick'], $_POST['message']);
			break;
	}
	mysql_close($link);
}


?>