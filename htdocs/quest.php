<?php
require_once("header.php"); 
?>
<td id="page2">
<?php

if ( $_GET['name'] ) {
			//check for file itself
			$name = preg_replace( "/[^A-Za-z0-9_!()]/", "", $_GET['name'] ); //only letters and numbers
			if (!@file_exists('./modules/'.$name.'.php'))
				print " Error: The page you've entered was not found. Please excuse us for any inconvenience caused.";
			else
			{
				if ($name=='donate_success')
					print " Error: The page you've entered was not found. Please excuse us for any inconvenience caused.";
				else
					include "./modules/".$name.".php";
				
			}
} 
else
{ print "<center><br>Error: The page you've entered was not found. Please excuse us for any inconvenience caused.</center>";}
?>
</td>
<?php
$tpl_footer = new Template("styles/".$style."/footer.php");
$tpl_footer->setVar("imagepath", 'styles/'.$style.'/images/');
print $tpl_footer->toString();
//include "./include/timer-footer.php";	