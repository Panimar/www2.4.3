<?php
if (!defined('AXE'))
	exit;
if (!isset($_SESSION['user'])) 
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
patch_include("sendmail",false);

//if session set, then we shoudlnt be here
if (!$a_user['is_guest'] && $a_user[$db_translation['gm']]==$db_translation['az']) 
{ 
	if (isset($_POST['action']))
	{

	$realmid = preg_replace( "/[^0-9]/", "", $_GET['realm'] ); //only numbers
	if ($realmid=='')
		$realmid='1';
	$i=1;
	while ($i<=count($realm))
	{	
		if ($realmid==$i)
		{
			$db->select_db($realm[$i]['db']);$rname=$realm[$i]['name'];
		}
	
		$i++;
	}

		
		$receiver_guid=preg_replace( "/[^0-9]/", "", $_POST['receiver_guid'] );
		$subject=preg_replace( "/[^a-zA-Z0-9'.!?:]/", "", $_POST['subject'] );
		$body=preg_replace( "/[^a-zA-Z0-9'.!?: ]/", "", $_POST['body'] );	
		$what=preg_replace( "/[^a-z]/", "", $_POST['what'] );
		$whatvalue=preg_replace( "/[^0-9]/", "", $_POST['whatvalue'] );
		
		//select playername by id
		$playersql=$db->query("SELECT ".$db_translation['characters_name']." FROM ". $db_translation['characters']." WHERE ".$db_translation['characters_guid']."=".$receiver_guid) or die(mysql_error());
		if (mysql_num_rows($playersql)=='0')
		{
			box('Fail', "Player not found.");
			$tpl_footer = new Template("styles/".$style."/footer.php");
			$tpl_footer->setVar("imagepath", 'styles/'.$style.'/images/');
			print $tpl_footer->toString();
			exit;
		}
		else
		{
			$playersql2=$db->fetch_array($playersql);
			//refrence-> sendmail($playername,$playerguid, $subject, $text, $item,$shopid=0, $money=0) //returns
			if ($whatvalue>='1' && $what=='money')
				$a=sendmail($playersql2[0],$receiver_guid, $subject, $body, '','0', $whatvalue);
			elseif ($whatvalue>='1' && $what=='item')
				$a=sendmail($playersql2[0],$receiver_guid, $subject, $body, $whatvalue,'0', '0');
			elseif ($whatvalue=='0' or $whatvalue=='')
				$a=sendmail($playersql2[0],$receiver_guid, $subject, $body, '', '0', '0');

			box('Success', $a);
			$tpl_footer = new Template("styles/".$style."/footer.php");
			$tpl_footer->setVar("imagepath", 'styles/'.$style.'/images/');
			print $tpl_footer->toString();
			exit;
		}
		 
		box('Success', $string1."<br>Mail was sent.");
		$tpl_footer = new Template("styles/".$style."/footer.php");
		$tpl_footer->setVar("imagepath", 'styles/'.$style.'/images/');
		print $tpl_footer->toString();
		exit;
	}
	
	$cont2.='
	<center>
    <div class="sub-box1" align="left">
	<form action="./quest.php?name=ingamemail" method="post">
	Select server:<br /><select name="realm">';
	
	
	$i=1;
	while ($i<=count($realm))
	{

		$cont2.= '<option value="'.$i.'">'.$realm[$i]['name'].'</option>';
	
		$i++;
	}
	
	
	$cont2.='
	</select>
	<br /><br /><br />
	Receiver character GUID: <a href=\'#\' onClick=\'window.open("./pop-charlookup.php","char","width=350,height=400,screenX=50,left=250,screenY=50,top=200,scrollbars=yes,status=no,menubar=no");return false\'><strong>[Search char GUID]</strong></a><br />
	<input name="receiver_guid" type="text" value="" /><br />
	Subject:<BR /><input name="subject" type="text" value="" /><br />
	Body:<BR /><textarea name="body" cols="55" rows="5"></textarea><br />
	
	<select name="what">
	<option value="item">An item (ID)</option>
	<option value="money">Gold (In copper.)</option>
	</select>: 
	<input name="whatvalue" type="text" value="0" />
	 <a href=\'#\' onClick=\'window.open("./pop-itemlookup.php","item","width=450,height=400,screenX=50,left=250,screenY=50,top=200,scrollbars=yes,status=no,menubar=no");return false\'><strong>[Search for an item ID]</strong></a><br />
	<br />
	<div id="log-b2"><input name="action" type="submit" value="Send" /></div>
	<br/> (This might take some time. Please wait untill your browser finishes loading.)
	</form>
	</div>
    </center>';
$box_wide->setVar("content_title", "Send in-game mail");	
$box_wide->setVar("content", $cont2);					
print $box_wide->toString();	
}
?>