<?php
require "include/common.php"; 
if (!defined('AXE'))
	exit;
?>
<!DOCTYPE HTML PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml"><head>


<meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
<link rel="shortcut icon" href="./favicon.ico">
<title><?php print "Vote Script - ".$title; ?></title>
<link href="./styles/default/style.css" rel="stylesheet" type="text/css">
<style type="text/css">
body { background:url(images/tos-bg.jpg) no-repeat top #070707; padding:0px; margin:0px;}
#fixx1 {
	padding:10px;
	color:#666;
	background:#1d1d1d;
	border:solid 1px #000;
}
</style>
</head><body>
<?php


if (!$a_user['is_guest'])
{
if(isset($_GET['vote']) && $_GET['vote']<>'')
 {
	$voteurl= str_replace('[i]',"&", $_GET['vote']);
	// this is made like this so there is no exploits... getting ids and retrieving their vote time for user
	
	$i=1;$siteid=false;
	while ($i<=count($voteurls) && count($voteurls)<>'0')
	{
		if($voteurl==$voteurls[$i])
		$siteid=$i;
		$i++;
	}
		   
 } else 
 {
   $voteurl="error.php"; $siteid='0';
 }
 if($siteid==false)
 {
 	$voteurl="error.php"; $siteid='0';
 }
   $getvote="SELECT * from vote_data where userid='".$a_user[$db_translation['acct']]."' and siteid='".$siteid."'";
   $getvote2=mysql_query($getvote)  or error('Unable to select vote data from the website database. <br><br></strong>MySQL reported:<strong> '.mysql_error().'.<br><br></strong>', __FILE__, __LINE__);
   $getvote3=mysql_fetch_array($getvote2);
   $points=$a_user['vp']+1;
   $timenow = date("U");
   $timefuture = date("U")+43200;//12 hrs
   $timeleft = $getvote3['timevoted'];
   $timeleft2 = gmdate("F j, Y G:i:s",$timeleft); //ex: March 23, 2009 18:25:55 - gmdate so its UTF
   $timeleft3 = $getvote3[timevoted]-$timenow;
   if ($siteid=='0') {
          $text="That vote-site was not found in the database.";

	  } else {
			   if ($getvote3['userid']==$a_user[$db_translation['acct']] && $siteid==$getvote3[siteid] && $getvote3[timevoted]>=$timenow)
			   {
			   		$timeaz=gmdate("G:i:s",$timeleft3);
				   $text="Time until you can vote for this site again: $timeaz<br><br><a href='$voteurl'>Click here to vote anyway.</a>";// 
			   } else {
				   
				   //*****************DOING USER UPDATE QUERIES****************
				   $adding="UPDATE accounts_more SET vp='$points' WHERE UPPER(acc_login)='".strtoupper($a_user[$db_translation['login']])."'";
				   mysql_query($adding) or error('Unable to update your vote points. <br><br></strong>MySQL reported:<strong> '.mysql_error().'.<br><br></strong>Advise:<strong> Please notify an Administrator and this issue will be fixed as soon as possible.', __FILE__, __LINE__);
				   
				   //********************ADDING TIME QUERIES******************
				   $ins="INSERT INTO vote_data (userid,siteid,timevoted) values ('".$a_user[$db_translation['acct']]."','".$siteid."','".$timefuture."')";
				   mysql_query($ins) or error('Unable to update your vote data. <br><br></strong>MySQL reported:<strong> '.mysql_error().'.<br><br></strong>Advise:<strong> Please notify an Administrator and this issue will be fixed as soon as possible.', __FILE__, __LINE__);
				   
				   //*******DELETING TIME QUERIES, ALL THAT ARE EXPIRED******* usefull so there is no useless queries and no space waste
				   $del="DELETE FROM vote_data WHERE timevoted < '$timenow'";
				   mysql_query($del) or error('Unable to delete your vote data. <br><br></strong>MySQL reported:<strong> '.mysql_error().'.<br><br></strong>Advise:<strong> Please notify an Administrator and this issue will be fixed as soon as possible.', __FILE__, __LINE__);
				   
				   //*********************************************************
				  
				   $text = 'Redirecting you to the selected vote site.<meta http-equiv="refresh" content="0;'.$voteurl.'"/>
';
			
			   }
		} 
}//end if logged in
else
{
?>
	

	
<?php
}
?>	<div style=" height:175px;"></div>
<br />
<br />
<br />
<br />
<br />
<center>
	<div class="post" id="fixx2">
	<div class="post_body" id="fixx1"align="center">
	<?php
	print $text;
	?>
	</div>
	</div>
</center>
</body></html>