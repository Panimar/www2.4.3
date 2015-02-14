<?php
require "include/common.php";
if (!defined('AXE'))
	exit;
//if session set, then we shoudlnt be here
if (!$a_user['is_guest'] && $a_user[$db_translation['gm']]==$db_translation['az']) 
{ 
	?>
	<html><head><title>Character GUID look-up</title>
	<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
    <link href="styles/default/style-popup.css" rel="stylesheet" type="text/css">
    </head><body>
	<div align="center">
    <div class="pop-inside-box" align="left">
	<form action="" method="post">
	Select realm:<br/>
    <select name="realm">
	<?php
	$i=1;
	while ($i<=count($realm))
	{
		print '<option value="'.$i.'">'.$realm[$i]['name'].'</option>';
		$i++;
	}
	
	?></select>
	<br>Character name:<br>
	<input name="name" type="text">
	<div id="log-b3"><input name="action" type="submit" value="Search"></div>
	</form>
	<br>
	<?php
	if (isset($_POST['action']))
	{
		$exist=1;
		$realm2=preg_replace( "/[^0-9]/", "", $_POST['realm'] );

		$db->select_db($realm[$realm2]['db']) or $exist=0;
			
		
		if ($exist=='0')
		{
			print 'Something went wrong when selecting realm character database.</body></html>'; exit;
		}
		$name=preg_replace( "/[^a-zA-Z0-9'.!?:]/", "", $_POST['name'] );
		$a=mysql_query("SELECT * FROM ".$db_translation['characters']." WHERE ".$db_translation['characters_name']."='".$db->escape($name)."' LIMIT 1")or die(mysql_error());
		if (mysql_num_rows($a)=='0')
		{
			print "There is no characters with name '".$name."'.";
		}
		else
		{
			$a1=mysql_fetch_assoc($a);
			print 'Name: <strong>'.$a1[$db_translation['characters_name']].'</strong>  GUID: <strong>'.$a1[$db_translation['characters_guid']].'</strong>';
		}
	}
}
?>
</div>
</div>
</body>
</html>