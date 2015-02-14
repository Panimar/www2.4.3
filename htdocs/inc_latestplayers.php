<?php
if (!defined('AXE'))
	exit;
$box_simple_short = new Template("styles/".$style."/box_simple_short.php");
$box_simple_short->setVar("imagepath", 'styles/'.$style.'/images/');
//select right db
$db->select_db($acc_db) or error('Unable to select the logon database, maybe it does not exist or the config.php variable $acc_db is empty. <br><br></strong>MySQL reported:<strong> '.mysql_error(), __FILE__, __LINE__); 
$thea1 = $db->query("SELECT ".$db_translation['login']." FROM ".$db_translation['accounts']." ORDER BY ".$db_translation['acct']." DESC LIMIT 3") or error('Something is wrong with the database. <br><br></strong>MySQL reported:<strong> '.mysql_error(), __FILE__, __LINE__); 
if (mysql_num_rows($thea1)=='0')
{
$cont= 'none';
}
else
{	
	$cont= "<center><i>Latest players:</i> &middot; ";
	while ($the2 = mysql_fetch_assoc($thea1))
	{
		$cont.= $the2[$db_translation['login']]." &middot; ";	  
	}
}
$theb1 = $db->query("SELECT COUNT(".$db_translation['acct'].") AS brojj FROM ".$db_translation['accounts']."") or error('Something is wrong with the database. <br><br></strong>MySQL reported:<strong> '.mysql_error(), __FILE__, __LINE__);
$theb2 = mysql_fetch_assoc($theb1);
$cont.= "<br><i>Total accounts:</i> ".$theb2['brojj'];
$db->select_db($realm[1]['db']) or error('Unable to select the characters database. <br><br></strong>MySQL reported:<strong> '.mysql_error().'.<br><br></strong>Suggestion:<strong> Please open your "config.php" file, find the variable '.$realm[1]['db'].', and enter the correct database name.', __FILE__, __LINE__);
$thec1 = $db->query("SELECT COUNT(".$db_translation['characters_guid'].") AS bro FROM ".$db_translation['characters']) or error('Something is wrong with the database. <br><br></strong>MySQL reported:<strong> '.mysql_error(), __FILE__, __LINE__);
$thec2 = mysql_fetch_assoc($thec1);
$cont.="&nbsp;&nbsp;<i>Total characters:</i> ".$thec2['bro'].'</center>';
//select forum
$db->select_db($db_name) or die(mysql_error());
$box_simple_short->setVar("content", $cont);
//print $box_simple_short->toString();
$inc_latestplayers = $box_simple_short->toString();