<?php
if (!defined('AXE'))
	exit;
//if session set, then we shoudlnt be here
if (!isset($_SESSION['user'])) 
{
	print "You are not logged in or you do not have access to this page."; $tpl_footer = new Template("styles/".$style."/footer.php");
	$tpl_footer->setVar("imagepath", 'styles/'.$style.'/images/');
	print $tpl_footer->toString();
	exit;
}
if ($a_user['is_guest']) 
{
	print "You are not logged in."; $tpl_footer = new Template("styles/".$style."/footer.php");
	$tpl_footer->setVar("imagepath", 'styles/'.$style.'/images/');
	print $tpl_footer->toString();
	exit;
}
if ($a_user[$db_translation['gm']]<>$db_translation['az'])
{
	print "You do not have access to this page."; $tpl_footer = new Template("styles/".$style."/footer.php");
	$tpl_footer->setVar("imagepath", 'styles/'.$style.'/images/');
	print $tpl_footer->toString();
	exit;
}
//common include
$box_wide = new Template("styles/".$style."/box_wide.php");
$box_wide->setVar("imagepath", 'styles/'.$style.'/images/');
//end common include
if (isset($_POST['action'])) 
{
	//$points=pun_htmlspecialchars($_GET['points']);
	//$delid=pun_htmlspecialchars($_GET['delid']);
	//$login = preg_replace( "/[^A-Za-z0-9]/", "", $_POST['username'] ); //only letters and numbers
	$acclogin = $_POST['login'];
	$acctid = preg_replace( "/[^0-9]/", "", $_GET['id'] ); //only numbers  
	$psw = preg_replace( "/[^A-Za-z0-9]/", "", $_POST['psw'] ); //only letters and numbers
	$gm = preg_replace( "/[^0-9-]/", "", $_POST['gm'] ); //only numbers  0 or 1
	$gm_realm = preg_replace( "/[^0-9-]/", "", $_POST['gm_realm'] ); //only numbers  0 or 1
	$gm2 = preg_replace( "/[^A-Za-z0-9-]/", "", $_POST['gm'] );
	$banned = preg_replace( "/[^01]/", "", $_POST['banned'] ); //only numbers  0 or 1
	if ($banned=='') {$banned='0';}
	$flags = preg_replace( "/[^0-9]/", "", $_POST['flags'] ); //only numbers  
	if ($flags=='') {$flags='8';}
	//$banreason = preg_replace( "/[^A-Za-z0-9]/", "", $_POST['banreason'] );
	//check pass:
	$db->select_db($acc_db);
	if ($_POST['psw']<>'')
	{
		
		$db->query("UPDATE ".$db_translation['accounts']." SET ".$db_translation['banned']."='".$banned."',".$db_translation['flags']."='".$flags."' WHERE ".$db_translation['acct']."='".$db->escape($_POST['acct'])."'") or die (mysql_error());
		if ($server_core=='trinity' or $server_core=='trinity_ra')
		{
			if ($gm<>'')
			{
				//update gm status
				$gmstatus1=$db->query("SELECT * FROM account_access WHERE RealmID='".$gm_realm."' AND id='".$acctid."'") or die(mysql_error());
				
				if (mysql_num_rows($gmstatus1)=='0')
				{
					//create new row
					$db->query("INSERT INTO account_access (id,gmlevel,RealmID) VALUES ('".$acctid."','".$gm."','".$gm_realm."')") or die(mysql_error());
				
				}
				elseif(mysql_num_rows($gmstatus1)=='1')
				{
					//update old one
					$db->query("UPDATE account_access SET ".$db_translation['gm']."='".$gm."' WHERE id='".$db->escape($_POST['acct'])."' AND RealmID = '".$gm_realm."'") or die ("error1: ".mysql_error());
					
				}
			}
		}
		
		
		$text1 = passchange($psw,$acctid);
		
		$text1 .= "<br>The account info was updated.<br>";
		//box ( "Updated",$text1 );
	}
	else
	{
		//query without pass
		$db->query("UPDATE ".$db_translation['accounts']." SET ".$db_translation['banned']."='".$banned."',".$db_translation['flags']."='".$flags."' WHERE ".$db_translation['acct']."='".$db->escape($_POST['acct'])."'") or die (mysql_error());
		if ($server_core=='trinity' or $server_core=='trinity_ra')
		{
			if ($gm<>'')
			{
				//update gm status
				$gmstatus1=$db->query("SELECT * FROM account_access WHERE RealmID='".$gm_realm."' AND id='".$acctid."'") or die(mysql_error());
				
				if (mysql_num_rows($gmstatus1)=='0')
				{
					//create new row
					$db->query("INSERT INTO account_access (id,gmlevel,RealmID) VALUES ('".$acctid."','".$gm."','".$gm_realm."')") or die(mysql_error());
				
				}
				elseif(mysql_num_rows($gmstatus1)=='1')
				{
					//update old one
					$db->query("UPDATE account_access SET ".$db_translation['gm']."='".$gm."' WHERE id='".$db->escape($_POST['acct'])."' AND RealmID = '".$gm_realm."'") or die ("error1: ".mysql_error());
					
				}
			}
		}
		$text1 = "The account info was updated, the password was not changed.<br>";
		//box ( "Updated","Server account info updated! Password not changed." );
	}
	$db->select_db($db_name);
	//check additional data
	if($_POST['additional']=='1')
	{
		
		$vp = preg_replace( "/[^0-9]/", "", $_POST['vp'] ); //only numbers
		if ($vp=='') {$vp='0';}
		$dp = preg_replace( "/[^0-9]/", "", $_POST['dp'] ); //only numbers
		if ($dp=='') {$dp='0';}
		$question_id = preg_replace( "/[^1234]/", "", $_POST['question_id'] ); //only numbers 1234
		if ($question_id=='') {$question_id='1';}
		$answer = preg_replace( "/[^A-Za-z0-9]/", "", $_POST['answer'] ); //only letters and numbers
		if ($answer=='') {$answer='blackhell';}
		//additional data query
		$db->query("UPDATE accounts_more SET vp='".$vp."',question_id='".$question_id."',answer='".$answer."',dp='".$dp."' WHERE UPPER(acc_login)='".strtoupper($db->escape($acclogin))."'") or die (mysql_error());
		
		$text2 = "Additional data was updated.";
	}
	$cont2.= "<center><strong>Updated!</strong><br><br>";
	$cont2.= $text1.$text2."<br><br><u><a href='quest.php?name=admincp3&id=".$_GET['id']."'>Go back to user</a></u></center>";
	$box_wide->setVar("content_title", "User Manager");	
	$box_wide->setVar("content", $cont2);					
	print $box_wide->toString();
	
	$tpl_footer = new Template("styles/".$style."/footer.php");
	$tpl_footer->setVar("imagepath", 'styles/'.$style.'/images/');
	print $tpl_footer->toString();
	exit;
}
 $cont2.='
<center>
<div class="sub-box1" align="left">
<form action="./quest.php?name=admincp3&p=0" method="post">
User name : <br/>
<input name="login" type="text" />
<div id="log-b2"><input name="search" type="submit" value="Search"/></div>
</form>
</div></center>';
if (isset($_POST['search'])) 
{
	$db->select_db($acc_db);
	$start=$_GET['p'];
	if ($start==''){$start='0';}
	$a= $db->query("SELECT * from ".$db_translation['accounts']." WHERE ".$db_translation['login']." LIKE '%".$db->escape($_POST['login'])."%' ORDER BY ".$db_translation['login']." ASC LIMIT 30");
	
	$b= $db->num_rows($a);
	 $cont2.='<br/><center><div class="sub-box1" align="left">';
	 $cont2.= "<i>".$b." Results for '".$_POST['login']."' (displaying 30 max)</i><br><br>";
	while ($c= $db->fetch_assoc($a))
	{
		if ($c[$db_translation['gm']]<>'0' && $c[$db_translation['gm']]<>'' &&  $c[$db_translation['gm']]<>$db_translation['gm_normalplayer'])
		{
			$gm="&lt;gm&gt;";
		}
		else
		{
			$gm='';
		}
		 $cont2.= " - ".$gm."<a href='./quest.php?name=admincp3&id=".$c[$db_translation['acct']]."'>".$c[$db_translation['login']]."</a><br>";
	 
	}
	$cont2.='</div></center>';
	
	//print "<br>".paginate($b,$start,'quest.php?name=admincp3');
}
if (isset($_GET['id'])) 
{   $db->select_db($acc_db);
	$ac= $db->query("SELECT * from ".$db_translation['accounts']." WHERE ".$db_translation['acct']." = '".$db->escape(pun_htmlspecialchars($_GET['id']))."' LIMIT 1") or die (mysql_error());
	$acc= $db->fetch_assoc($ac);
	$cont2.='<br/><center><div class="sub-box1" align="left">';
	$cont2.= "<i>Editing user '".$acc[$db_translation['Login']]."':</i><br><br>";
	$cont2.= '<form action="./quest.php?name=admincp3&id='.$acc[$db_translation['acct']].'" method="post">
	<input name="" type="text" disabled="disabled" value="'.$acc[$db_translation['login']].'" /> - Username<br>
	<input name="acct" type="hidden" value="'.$acc[$db_translation['acct']].'" />
	<input name="login" type="hidden" value="'.$acc[$db_translation['login']].'" />
	<input name="psw" type="text" value="" /> - Passw. (Leave blank to not change.)<br>';
	$cont2.='<input name="banned" type="text" value="'.$acc[$db_translation['banned']].'" /> - Banned (Value usually 1 or 0.)<br>
	<input name="flags" type="text" value="'.$acc[$db_translation['flags']].'" /> - Flags ('.$db_translation['expansion_normal'].' - WoW, '.$db_translation['expansion_tbc'].' - TBC, '.$db_translation['expansion_wotlk'].' - WOTLK)<br>
	';
	$db->select_db($db_name);
	$ad= $db->query("SELECT * from accounts_more WHERE UPPER(acc_login) = '".strtoupper($db->escape($acc[$db_translation['login']]))."' LIMIT 1") or die (mysql_error());
	$bd= $db->num_rows($ad);
	
	if ($bd=='0')
	{
		$cont2.= "<br>No additional data for this user.<br><br><input name='additional' type='hidden' value='0' />";
		
	}
	else
	{
		$acd= $db->fetch_assoc($ad);
		$cont2.= '<input name="additional" type="hidden" value="1" />
		<input name="vp" type="text"  value="'.$acd['vp'].'" /> - Vote Points<br>
		<input name="dp" type="text"  value="'.$acd['dp'].'" /> - Donation Points<br>
		<input name="question_id" type="text"  value="'.$acd['question_id'].'" /> - Security Question ID<br>
		&nbsp;1 - Your middle name?<br>
		&nbsp;2 - Your birth town?<br>
		&nbsp;3 - Your pet\'s name?<br>
		&nbsp;4 - Your mother maiden name?<br><br>
		<input name="answer" type="text" value="'.$acd['answer'].'" /> - Security Answer<br>
		
		';
		
	}
	$cont2.= '
	<div id="log-b2"><input name="action" type="submit" value="Edit User"/></div>
	</form>
	<br><strong>Note:</strong> With passwords, only a-z, A-Z letters and numbers are allowed.
	</div></center>';
}						
$db->select_db($db_name);

$box_wide->setVar("content_title", "User Manager");	
$box_wide->setVar("content", $cont2);					
print $box_wide->toString();
					