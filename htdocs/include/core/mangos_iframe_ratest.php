<?php
/*******************************************
* ra_test.php is part of WebWoW CMS
* by AXE
********************************************/
define('PATHROOT', '../../');
include PATHROOT."include/common.php";
?><html><head><title>Testing...</title></head><body style="background-color:#333333; color:#FFFFFF; font-family:Arial, Helvetica, sans-serif; font-size:12px"><?php

if ($a_user[$db_translation['gm']]==$db_translation['az'])
{
	echo "<font color='gray'><span style='float:right'><strong>File:</strong> root/include/core/mangos_iframe_ratest.php</span>You are admin, access to this test file granted.</font><br>";
	patch_include("sendmail",false);

	
	function test_serv($port){
	global $server;
	$s = @fsockopen($server, $port, $ERROR_NO, $ERROR_STR,(float)0.5);
	if($s){@fclose($s);return true;} else return false;
	}
	if (test_serv($realm['1']['port_ra']))//first server
	{
		 echo ("<br><font color='#00CC00'>Ra.Port ".$realm['1']['port_ra']." on '".$server."' is open</font><br>");
		 test_ra_connection();
	} else 
	{
		echo("<br><font color='#FF6262'>Ra.Port ".$realm['1']['port_ra']." on '".$server."' is closed. Possible reasons:<br><blockquote>
		<li>MaNGOS server is offline</li>
		<li>Remote Access (RA) is disabled (look above)</li></blockquote></font>");
	}
	
	
	echo "<font color='gray'>Done!</font>";
}
else
{
	echo "File protected";
}
?></body></html>