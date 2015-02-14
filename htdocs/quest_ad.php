<?php
require_once("header.php"); 
?>
<td id="page2">
<?php
if ($a_user['is_guest']) 
{
	print "You are not logged in."; include "footer.php"; exit;
}
if ($a_user[$db_translation['gm']]<>$db_translation['az'])
{
	print "You don't have access to this page."; include "footer.php"; exit;
}
if ( $_GET['name'] ) {
			//check for file itself
			$name = preg_replace( "/[^A-Za-z0-9_!()]/", "", $_GET['name'] ); //only letters and numbers
			if (!@file_exists('./modules/m_admincp/'.$name.'.php'))
				print " Error: The page you've entered was not found. Please excuse us for any inconvenience caused.";
			else
				include "./modules/m_admincp/".$name.".php";
} 
else
{ print "<center><br>Error: The page you've entered was not found. Please excuse us for any inconvenience caused.</center>";}
?>
</td>
	

<?php
$tpl_footer = new Template("styles/".$style."/footer.php");
$tpl_footer->setVar("imagepath", 'styles/'.$style.'/images/');
print $tpl_footer->toString();
?>
	