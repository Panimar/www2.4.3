<?php
if (!defined('AXE'))
	exit;
//common include
$box_simple_wide = new Template("styles/".$style."/box_simple_wide.php");
$box_wide = new Template("styles/".$style."/box_wide.php");
$box_wide->setVar("imagepath", 'styles/'.$style.'/images/');
$box_simple_wide->setVar("imagepath", 'styles/'.$style.'/images/');
//end common include
$i=1;
while ($i<=count($realm))
{
	$cont2.=
	$realm[$i]['name']." - 
	<div style='width:200px; height:20px;'> <span id='server".$i."'></span>
	<script type='text/javascript'>ajax_loadContent('server".$i."','./dynamic/status.php?id=".$i."');</script> </div><br>";
	$i++;	
					
}				
$box_wide->setVar("content_title", "Realm Status");	
$box_wide->setVar("content", $cont2);					
print $box_wide->toString();	