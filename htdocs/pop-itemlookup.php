<?php
require "include/common.php";
if (!defined('AXE'))
	exit;
//if session set, then we shoudlnt be here
if (!$a_user['is_guest'] && $a_user[$db_translation['gm']]==$db_translation['az'])  
{ 
	?>
	<html><head><title>Item Look-up</title>
	<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
    <link href="styles/default/style-popup.css" rel="stylesheet" type="text/css">
    </head><body>
    
    <div align="center">
    <div class="pop-inside-box" align="left">
	<form action="" method="post">
	Item name:<br/>
	<input name="name" type="text">
	<div id="log-b3"><input name="action" type="submit" value="Search"></div>
	</form>
	<?php
	if (isset($_POST['action']))
	{
		$exist='1';
		$db->select_db($item_db) or $exist=0;
		if ($exist=='0')
		{
			print 'Something went wrong when selecting item database. Recheck value $item_db in your config.php.</body></html>'; exit;
		}
		
		$name=$_POST['name'];
		$a=mysql_query("SELECT * FROM ".$db_translation['items']." WHERE ".$db_translation['items_name1']."='".$db->escape($name)."' LIMIT 1")or die(mysql_error());
		
		if (mysql_num_rows($a)=='0')
		{
			//search similar
			$b=mysql_query("SELECT * FROM ".$db_translation['items']." WHERE ".$db_translation['items_name1']." LIKE '%".$db->escape($name)."%' LIMIT 50")or die(mysql_error());
			print "Item not found, printing similar results <i>(50 max)</i>:<br><br>";
			while ($b1=mysql_fetch_assoc($b))
			{
				if ($b1[$db_translation['items_quality']]=='0')
					$color="gray";
				elseif ($b1[$db_translation['items_quality']]=='1')
					$color="white";
				elseif ($b1[$db_translation['items_quality']]=='2')
					$color="lime";
				elseif ($b1[$db_translation['items_quality']]=='3')
					$color="#7E90FF";
				elseif ($b1[$db_translation['items_quality']]=='4')
					$color="#D584FF";
				elseif ($b1[$db_translation['items_quality']]=='5')
					$color="orange";
				print 'ID: <strong>'.$b1[$db_translation['items_entry']].'</strong> <font color="'.$color.'">['.$b1[$db_translation['items_name1']].']</font><br>';
			}
		}
		else
		{
			$a1=mysql_fetch_assoc($a);
			if ($a1[$db_translation['items_quality']]=='0')
				$color="gray";
			elseif ($a1[$db_translation['items_quality']]=='1')
				$color="white";
			elseif ($a1[$db_translation['items_quality']]=='2')
				$color="lime";
			elseif ($a1[$db_translation['items_quality']]=='3')
				$color="#7E90FF";
			elseif ($a1[$db_translation['items_quality']]=='4')
				$color="#D584FF";
			elseif ($a1[$db_translation['items_quality']]=='5')
				$color="orange";
			else
				$color="gray";
			
			print 'ID: <strong>'.$a1[$db_translation['items_entry']].'</strong> <font color="'.$color.'">['.$a1[$db_translation['items_name1']].']</font><br>';
		}
	}
}
?>

</div>
</div>
</body></html>