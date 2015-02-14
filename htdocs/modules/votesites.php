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


//common include
$box_simple_wide = new Template("styles/".$style."/box_simple_wide.php");
$box_wide = new Template("styles/".$style."/box_wide.php");
$box_wide->setVar("imagepath", 'styles/'.$style.'/images/');
$box_simple_wide->setVar("imagepath", 'styles/'.$style.'/images/');
//end common include
//say hi to lazyness ^^

$timenow = date("U");

$s=0;//number of already voted sites
$zzs=0;
function check($site) 
{
	global $a_user,$timenow,$s,$sitepath,$db_translation;
	
	$getvote="SELECT timevoted FROM vote_data WHERE userid='".$a_user[$db_translation['acct']]."' AND siteid='".$site."'";
	$getvote2=mysql_query($getvote) or error('Unable to select vote data. <br><br></strong>MySQL reported:<strong> '.mysql_error().'.<br><br></strong>Suggestion:<strong> Re-importing sql database you got in part 1 of site download will probably fix problem', __FILE__, __LINE__);
	$getvote3=mysql_fetch_array($getvote2);
	if (!$getvote3[0]) {$getvote3[0]="0";}
	if ($getvote3[0]>=$timenow) {$s++;} 
}
function check2($site,$url) 
{
  global $a_user,$timenow,$s,$sitepath,$zzs,$style,$db_translation,$style;
  
  $getvote="SELECT timevoted FROM vote_data WHERE userid='".$a_user[$db_translation['acct']]."' AND siteid='".$site."'";
  $getvote2=mysql_query($getvote) or error('Unable to update vote data. <br><br></strong>MySQL reported:<strong> '.mysql_error().'.<br><br></strong>Suggestion:<strong> Re-importing sql database you got in part 1 of site download will probably fix problem', __FILE__, __LINE__);
  $getvote3=mysql_fetch_array($getvote2);
  if (!$getvote3[0]) {$getvote3[0]="0";}
  
  $url2= str_replace('&',"[i]", $url);
	 if ($getvote3[0]>=$timenow) {return "<a href=\"javascript:alert('You have already voted for this site. You can only vote once per 12 hours.');\" ><img style='margin:2px' src='./styles/".$style."/images/".$site."_g.jpg' width='81px' alt='[Vote here]' title='IMG: styles/".$style."/images/".$site.".jpg'></a>";} 
	 else 
	{
		if ($url=='')
		{
			$zzs=$zzs+1;
			
		}
		else
			return "<a href='./vote.php?vote=".$url2."' target='_blank'><img style='margin:2px' src='./styles/".$style."/images/".$site.".jpg' width='81px' alt='[Vote here]' title='IMG: styles/".$style."/images/".$site.".jpg'></a>";
			
	
	}
}
$s1=0;$s2=0;$s3=0;$s4=0;

$i=1;
while ($i<=count($voteurls) && count($voteurls)<>'0')
{
	check($i);
	$i++;
}
	//voted sites <= 1
//is there any site left?
$siteleft=count($voteurls)-$s;


		  
$cont2="Вы можете голосовать только раз в 24часа!<br /> 
В скором времени сможете приобрести в личном кабинете эксклюзивные Ittems! <br />
Так же голосуя за сервер вы помогаете проекту!<br /><br /><center>";

  $i=1;
  	while ($i<=count($voteurls) && count($voteurls)<>'0')
	{
    	$cont2.=check2($i,$voteurls[$i]);
		$cont2.="&nbsp;";
		if ($i==5 || $i==10 || $i==20 || $i==30){$cont2.="<br />";}
	
		$i++;
	}
	
	$cont2.='
</center>';


$box_wide->setVar("content_title", "Система Голосования");	
$box_wide->setVar("content", $cont2);					
print $box_wide->toString();	