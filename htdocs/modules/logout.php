<?php
if (!defined('AXE'))
	exit;

//common include
$box_simple_wide = new Template("styles/".$style."/box_simple_wide.php");
$box_wide = new Template("styles/".$style."/box_wide.php");
$box_wide->setVar("imagepath", 'styles/'.$style.'/images/');
$box_simple_wide->setVar("imagepath", 'styles/'.$style.'/images/');
//end common include
if (!$a_user['is_guest']) 
{
	//session_destroy();
	$cont2= "Logging out failed, Please delete all cookies and sessions or try go to index page and then click on 'Logout'.";
}
else
{
	
	$cont2= "You are now logged out.<meta http-equiv='refresh' content='0;url=./'/>";
}
$box_wide->setVar("content_title", "Logout");	
$box_wide->setVar("content", $cont2);					
print $box_wide->toString();

?>