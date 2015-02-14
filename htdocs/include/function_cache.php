<?php

/*if (!defined('AXE'))
	exit;*/

function generate_navigation_cache()
{
	global $db,$db_name,$tpl_header;
	
	$db->select_db($db_name);
	$error=false;
	$result = $db->query('SELECT * FROM pages ORDER BY position DESC, orderby ASC') or ($error='Unable to write cache. This is a SQL problem ('.mysql_error().')');
	$num_groups = $db->num_rows($result);
	
	$output='<?php'."\n";
	while ($data=$db->fetch_assoc($result))
	{
		
		if ($data['description']=='')
			$wowhead='';
		else
			$wowhead='onmouseover="$WowheadPower.showTooltip(event, &#39;'.$data['description'].'&#39;)" onmousemove="$WowheadPower.moveTooltip(event)" onmouseout="$WowheadPower.hideTooltip();"';
		
		$title_link=explode('[|]',$data['title']);
		
		if  ($data['position']=='1')						
			$output.='$tpl_header->gotoNext("link_custom");	
$tpl_header->setVar("link_custom", array("wowhead"=>\''.$wowhead.'\',"title"=>"'.$title_link[0].'","linkpath"=>"'.$data['link'].'"));'."\n";
		else
			$output.='$tpl_header->gotoNext("link_custom2");	
$tpl_header->setVar("link_custom2", array("wowhead"=>\''.$wowhead.'\',"title"=>"'.$title_link[0].'","linkpath"=>"'.$data['link'].'"));'."\n";
	}
	$output.="\n".'?>';
	
	$fh = @fopen('./cache/cache_navigation.php', 'wb');
	
	fwrite($fh, $output);

	fclose($fh);
	
	echo $error;
	
}
