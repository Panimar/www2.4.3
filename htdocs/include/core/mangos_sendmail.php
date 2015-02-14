<?php
/***********************************************************
*	Sendmail function for MaNGOS
*	by AXE
*   this file is required for all cores
************************************************************/

/***********************************************************
* 		 GLOBAL FUNCTIONS (required for all cores)
************************************************************/
function sendmail($playername,$playerguid, $subject, $text, $item, $shopid=0,  $money=0, $realmid='1') //returns, IMPORTANT: do not remove <!-- success --> if success
{
	global $server,$ra_user,$ra_pass,$db,$a_user,$se_c,$realm;
	$playername = clean_string($playername);
    $subject = preg_replace( "/[^A-Za-z0-9]/", "", clean_string($subject)); //no whitespaces
	$item = preg_replace( "/[^0-9]/", "", $item); //item id
	$realmid = preg_replace( "/[^0-9]/", "", $realmid); //item id
	if ($item<>'') $item = " ".$item;
    $text = clean_string($text);
	$money= preg_replace( "/[^0-9]/", "", $money);
	
	$telnet = fsockopen($server, $realm[$realmid]['port_ra'], $error, $error_str, 3);
	if($telnet)
	{
		fgets($telnet,1024); // Motd
		fputs($telnet, $ra_user."\n");
		
		sleep(3);
		
		//fgets($telnet,1024); // PASS
		fputs($telnet, $ra_pass."\n");
		
		sleep(3);
		
		$remote_login = fgets($telnet,1024);
		if(strstr($remote_login, "Logged in."))
		{
			if ($item<>'' && $item<>'0')//send item
			{
				//sendmail to RA console
				fputs($telnet, ".send items ".$playername." \"".$subject."\" \"".$text."\"".$item."\n");
				$easf=time();
				$mailtext="Mail with item sent! No money was sent.";
			}
			elseif ($money>'0' && $money<>'')//send money
			{
				fputs($telnet, ".send money ".$playername." \"".$subject."\" \"".$text."\" ".$money."\n");
				$moneytext="Mail with money sent! No item was sent.";
			}
			else //send letter
			{
				fputs($telnet, ".send mail ".$playername." \"".$subject."\" \"".$text."\"\n");
				$moneytext="Normal Mail sent!";
			}
			//check database if actuall item is there
			//WebsiteVoteShopREFXXXXXXX ->this is unique
			$check=$db->query("SELECT * FROM mail WHERE receiver = '".$playerguid."' AND subject ='".$subject."' LIMIT 1")or die(mysql_error());
			if(mysql_num_rows($check)=='0')
				$status="Recheck script (just to make sure mail is actually sent):<br><br><center><iframe style='width:96%;  height:100px' src='./include/core/mangos_iframe_mailcheck.php?shopid=".$shopid."&reciver=".$playerguid."&subject=".$subject."&realmid=".$realmid."&shash=".sha1($a_user['id'].$playerguid.$subject.$se_c.$shopid)."'><a href='./include/core/mangos_iframe_mailcheck.php?shopid=".$shopid."&reciver=".$playerguid."&subject=".$subject."&realmid=".$realmid."&shash=".sha1($a_user['id'].$playerguid.$subject.$se_c.$shopid)."'>Check here if your mail is here.</a></iframe></center>";
				
			return  "<!-- success --><span class=\"colorgood\">".$mailtext.$moneytext."<br></span><br>".$status;
		}
		else
			return  "<span class=\"colorbad\">Remote Login Problem: ".$remote_login."</span><br>Used login: ".$ra_user;
		
		
		fclose($telnet);
	}
	else
		return  "<span class=\"colorbad\">MaNGOS server is offline, you must do this when server is online.</span>";
}

/*************************************************************
* 		NON GLOBAL FUNCTIONS (not required for other cores)
**************************************************************/
function clean_string($string) //returns
{
    return str_replace(array("\n", "\""), "", $string);
}

function test_ra_connection() //used in htdocs/telnet-test.php, echoes
{
	global $server,$ra_user,$ra_pass,$realm;
	$telnet = fsockopen($server, $realm['1']['port_ra'], $error, $error_str, 3);
	if($telnet)
	{
		fgets($telnet,1024); // Motd
		fputs($telnet, $ra_user."\n");
		
		sleep(3);
		
		//fgets($telnet,1024); // PASS
		fputs($telnet, $ra_pass."\n");
		
		sleep(3);
		
		$remote_login = fgets($telnet,1024);
		if(strstr($remote_login, "Logged in."))
		{
			//sendmail to RA console
			echo "<font color=\"lime\">Remote connection to MaNGOS console on RealmID:1 is successful</font><br>";
		}
		else
			echo  "<font color=\"red\">Remote Login Problem: <i>".$remote_login."</i></font><br>";
			
		fclose($telnet);
	}
	else
		echo  "<font color=\"red\">Telnet Connection Problem: <i>".$error_str."</i></font><br>";
}
function sendmail_confirm($receiver,$subject,$realmid)//returns
{	
	global $db,$realm;
	
	$i=1;
	while ($i<=count($realm))
	{
		if ($realmid==$i)
		{
			$db->select_db($realm[$i]['db']);
		}
	
		$i++;
	}
	
	$check=$db->query("SELECT id FROM mail WHERE receiver = '".$receiver."' AND subject ='".$subject."' LIMIT 1")or die(mysql_error());
	if(mysql_num_rows($check)=='0')
	{
		return "Checking for mail in DB... <font color='orange'>Not found!</font>";//you can change this text
	}
	else
		return "Checking for mail in DB... <font color='lime'>Item is successfully sent!</font>";//do not change text or 'recheck' script will not work
}