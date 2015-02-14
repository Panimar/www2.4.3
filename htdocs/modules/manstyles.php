<?php
if (!defined('AXE'))
	exit;
if ($a_user['is_guest']) 
{
	print "You are not logged in."; $tpl_footer = new Template("styles/".$style."/footer.php");
	$tpl_footer->setVar("imagepath", 'styles/'.$style.'/images/');
	print $tpl_footer->toString();
	exit;
}
if ($a_user[$db_translation['gm']]<>$db_translation['az'])
{
	print "You don't have access to this page."; $tpl_footer = new Template("styles/".$style."/footer.php");
	$tpl_footer->setVar("imagepath", 'styles/'.$style.'/images/');
	print $tpl_footer->toString();
	exit;
}
//common include
$box_simple_wide = new Template("styles/".$style."/box_simple_wide.php");
$box_wide = new Template("styles/".$style."/box_wide.php");
$box_wide->setVar("imagepath", 'styles/'.$style.'/images/');
$box_simple_wide->setVar("imagepath", 'styles/'.$style.'/images/');
//end common include

if ($_POST['submit'])
{
	$cont2='This script will now attempt to rewrite your <strong>config.php</strong> file.<br>Values that will be changed are:<br><br><font face= "Courier New", Courier, monospace><big>$style=\'<span class="colorgood">'.$_GET['style'].'</span>\';</big></font><br><br>';
	

$fail='';
$config_data = file_get_contents('./config.php') or ($fail= 'ERROR: Could not read the "config.php" file.');

$config_data2=str_replace('$style=\''.$_GET['style'].'\';','$style=\''.$_POST['stylename'].'\';',$config_data);

//print config
//$cont2.= nl2br(pun_htmlspecialchars($config_data2));
$myFile = "./config.php";
$fh = fopen($myFile, 'w') or ($fail= "ERROR: Can't open the configuration file");
if ($fail=='')
{
	fwrite($fh, $config_data2);
	fclose($fh);
	$cont2.='<span class="colorgood">Success!</span> <br>';
}
else
{
	$cont2.='Applying the new style has failed. You will have to do it manually. <br>'.$fail.'<br><br>';
}


	
	
	$cont2.='
	
	<center><a href="./quest.php?name=manstyles">Done. Go back and refresh the page.</a></center><br><br><hr><b><big>H</big>ow to do it manually:</b><br>If this does not work, you can always manually open your <strong>config.php</strong> file and find line where it says:<br><br><font face= "Courier New", Courier, monospace><big>$style=\''.$_GET['style'].'\';</big></font><br><br>and change to:<br><br><font face= "Courier New", Courier, monospace><big>$style=\''.$_POST['stylename'].'\';</big></font>';

}
else
{
	$cont2= '
	<center>
    <div class="sub-box1" align="left">
	Select a style from the dropdown menu:<br><br><form method="POST" action="quest.php?name=manstyles&style='.$style.'"><select name="stylename">';
	$folder4 = "styles/";
	$handle4 = opendir($folder4);
	
	# Making an array containing the files in the current directory:
	while ($file4 = readdir($handle4))
	{
		$files4[] = $file4;
	}
	closedir($handle4);
	
	#echo the files
	foreach ($files4 as $file4) {
		
		if ($file4=='..' || $file4=='.' || $file4=='index.html' || $file4=='readme.txt')
		{}
		else
		{
		  $cont2.= "<option>".$file4.'</option>';}
	}
		  
			
			  
			
	$cont2.= '</select> <br/>
	      <div id="log-b2"> <input name="submit" type="submit" value="Change" /></div>
		  </form><br>
		  <br><strong><span class="colorgood">Tip:</span></strong> 
		  All styles are located inside the <strong>/styles/</strong> folder.<br>You can change the whole design from the files found in that folder.<br>No editing of the php source files is needed.<br>(Unless you know what are you doing.)
		  </div>
    </center>';
}
//*********************
$box_wide->setVar("content_title", "Website Template Selecter");	
$box_wide->setVar("content", $cont2);					
print $box_wide->toString();	