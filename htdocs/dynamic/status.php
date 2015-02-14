<?php
include "../config.php";
$id = preg_replace( "/[^0-9]/", "", $_GET['id'] ); //only numbers
function test_serv($port){
	global $server;
	$s = @fsockopen($server, $port, $ERROR_NO, $ERROR_STR,(float)0.5);
	if($s){@fclose($s);return true;} else return false;
	}
	
if (test_serv($realm[$id]['port']))
echo "<div class='online-s'></div>";
else
echo "<div class='offline-s'></div>";


