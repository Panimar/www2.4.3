<?php
if (!defined('AXE'))
	exit;
//common include
$box_simple_wide = new Template("styles/".$style."/box_simple_wide.php");
$box_wide = new Template("styles/".$style."/box_wide.php");
$box_wide->setVar("imagepath", 'styles/'.$style.'/images/');
$box_simple_wide->setVar("imagepath", 'styles/'.$style.'/images/');
//end common include

//if session set, then we shoudlnt be here
if (!$a_user['is_guest'] && $a_user[$db_translation['gm']]<>$db_translation['gm_normalplayer'] && $a_user[$db_translation['gm']]<>'') 
{
	if ($a_user[$db_translation['gm']]==$db_translation['az']) 
$box_simple_wide->setVar("content", $cont1);
print $box_simple_wide->toString();
		

$cont2.='<br /><center>
 <div id="log-b4"><a href="./quest_ad.php?name=Shoutbox_Manager&all">Prune all shouts</a></div>
 <br /><table width="400px">';
$db->select_db($db_name);
		$getshout = "SELECT date, user, message, id FROM shoutbox ORDER BY date DESC limit 20";
			
$getshout2=mysql_query($getshout) or error('Something is wrong with the database. <br><br></strong>MySQL reported:<strong> '.mysql_error().'.<br><br></strong>Suggestion:<strong> Please open the "config.php" file, find the variable $realm['.$realmid.'], and enter the correct database name', __FILE__, __LINE__); 
			while ($getit=mysql_fetch_array($getshout2) ){
			
				$cont2.=  ' <tr style="Background-color:#000000;"><td>
'.$getit['user'].'  '.$getit['date'].'</td><td>

<a href="./quest_ad.php?name=Shoutbox_Manager&id='.$getit['id'].'">[X]</a></td></tr><td><div class="post">'.$getit['message'].'</div></td>';
			} $cont2.="</table>";
			
$cont2_title="Shoutbox Control Panel";
if (isset($_GET['id'])) 
{
$id = $_GET['id'];
mysql_query("DELETE FROM shoutbox WHERE id=$id");
echo'<meta http-equiv="refresh" content="2;url=./quest_ad.php?name=Shoutbox_Manager">';
}
if (isset($_GET['all'])) 
{
mysql_query("DELETE FROM shoutbox");
echo'<meta http-equiv="refresh" content="2;url=./quest_ad.php?name=Shoutbox_Manager">';
}
//
$box_wide->setVar("content_title", $cont2_title);	
$box_wide->setVar("content", $cont2);					
print $box_wide->toString();
//
		
}
else
{
	print "You are not logged in or you do not have access to this page.";
}
?>