<?php
if (!defined('AXE'))
	exit;
if (!$a_user['is_guest'])
{

$timenow = date("U");

$s=0;//number of already voted sites
$zzs=0;
function check($site) 
{
	global $a_user,$timenow,$s,$sitepath,$db_translation;
	
	$getvote="SELECT timevoted FROM vote_data WHERE userid='".$a_user[$db_translation['acct']]."' AND siteid='".$site."'";
	$getvote2=mysql_query($getvote) or error('Unable to select your vote data. <br><br></strong>MySQL reported:<strong> '.mysql_error().'.<br><br></strong>Suggestion:<strong> Please contact an Administrator whom will resolve this problem.', __FILE__, __LINE__);
	$getvote3=mysql_fetch_array($getvote2);
	if (!$getvote3[0]) {$getvote3[0]="0";}
	if ($getvote3[0]>=$timenow) {$s++;} 
}
function check2($site,$url) 
{
  global $a_user,$timenow,$s,$sitepath,$zzs,$style,$db_translation,$style;
  
  $getvote="SELECT timevoted FROM vote_data WHERE userid='".$a_user[$db_translation['acct']]."' AND siteid='".$site."'";
  $getvote2=mysql_query($getvote) or error('Unable to update your vote data. <br><br></strong>MySQL reported:<strong> '.mysql_error().'.<br><br></strong>Suggestion:<strong> Please contact an Administrator whom will resolve this problem.', __FILE__, __LINE__);
  $getvote3=mysql_fetch_array($getvote2);
  if (!$getvote3[0]) {$getvote3[0]="0";}
  
  $url2= str_replace('&',"[i]", $url);
	 if ($getvote3[0]>=$timenow) {} 
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
if ($siteleft<>'0') {
 $t = new Template("styles/".$style."/box_simple_short.php");
		  
$content='<center>
<table width="100%" border="0" cellpadding="0">
  <tr><td>';
  
  // read this baby
$content.="<div id='log-b2'><input type='button' value='Голосовать' onclick=";$content.='"location.href =';$content.="'./quest.php?name=votesites';";$content.='"';$content.=" /></div>";
	
	$content.='</td><td width="400px" style=" text-align:right">
	Вы можете голосовать только раз в 24часа<br/> (За голосование вы получаете 1бонус) </td>
  </tr>
</table>
</center>';
$t->setVar("content", $content);
$t->setVar("imagepath", 'styles/'.$style.'/images/');
$vote_links= $t->toString();

}

	?>

	
<?php
}//end if logged in
?>