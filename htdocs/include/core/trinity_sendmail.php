<?php
/***********************************************************
*	Sendmail function for Ascent/Arcemu
*	by AXE
*   this file is required for all cores
************************************************************/

/***********************************************************
* 		 GLOBAL FUNCTIONS (required for all cores)
************************************************************/
function sendmail($playername,$playerguid, $subject, $text, $item, $shopid=0, $money=0, $realmid=false) //returns, IMPORTANT: do not remove <!-- success --> if success
{
	//send normal, item only 
	global $db,$a_user,$db_translation;
    $subject = preg_replace( "/[^A-Za-z0-9]/", "", $subject); //no whitespaces
	$item = preg_replace( "/[^0-9]/", "", $item); //item id
	$playerguid = preg_replace( "/[^0-9]/", "", $playerguid); //item id
	$text = preg_replace( "/[^A-Za-z0-9!-:.? ]/", "", $text); //no whitespaces
	$money= preg_replace( "/[^0-9]/", "", $money);
	


	if ($item=='')//send item
		$item="0";
	if ($money=='')
		$money=0;
	
	
	
        $query_string ='INSERT INTO mail_external(sender, receiver, subject, message, money, stationery, sent) VALUES("'.$playerguid.'","'.$playerguid.'", "'.$subject.'", "'.$text.'", "'.$money.'", 0, 1)';
        $sendmail=false;
	$db->query($query_string) or ($sendmail=mysql_error());
	//find mail id and add item
	$sql1 = $db->query("SELECT id FROM mail_external WHERE subject='".$subject."' AND receiver='".$playerguid."' LIMIT 1")or ($sendmail.=mysql_error());
	$sql2=$db->fetch_array($sql1);
	if($sendmail==false && $sql2[0]<>'')
		$db->query("INSERT INTO mail_external_items (mail_id,item) VALUES ('".$sql2[0]."','".$item."')") or ($sendmail.=mysql_error());
	if($sendmail==false)
		return  "<!-- success --><span class=\"colorgood\">Mail is sent! <br>All done!</span>";
	else
		return  "<span class=\"colorbad\">Mail is not sent! Error returned: ".$sendmail."<br>".$query_string;
	

}
function sendmail_secondpatch($playername,$playerguid, $subject, $text, $item, $shopid=0, $money=0, $realmid=false) //returns, IMPORTANT: do not remove <!-- success --> if success
{
	//send normal, item only 
	global $db,$a_user,$db_translation;
    $subject = preg_replace( "/[^A-Za-z0-9]/", "", $subject); //no whitespaces
	$item = preg_replace( "/[^0-9]/", "", $item); //item id
	$playerguid = preg_replace( "/[^0-9]/", "", $playerguid); //item id
	$text = preg_replace( "/[^A-Za-z0-9!-:.? ]/", "", $text); //no whitespaces
	$money= preg_replace( "/[^0-9]/", "", $money);
	


	if ($item=='')//send item
		$item="0";
	if ($money=='')
		$money=0;
	
	
	$insert_data = array(
            '%reciver%'     => $playerguid,
            '%subject%'     => $db->escape($subject),
            '%message%'     => $db->escape($text),
            '%money%'       => $money,
            '%item%'        => $item,
            '%item_count%'  => 1
        );
        $query_string = strtr('INSERT INTO mail_external(receiver, subject, message, money, item, item_count) VALUES("%reciver%", "%subject%", "%message%", "%money%", %item%, %item_count%)', $insert_data);
        $sendmail=false;
	$db->query($query_string) or ($sendmail=mysql_error());
	if($sendmail==false)
		return  "<!-- success --><span class=\"colorgood\">Mail is sent! <br>All done!</span>";
	else
		return  "<span class=\"colorbad\">Mail is not sent! Error returned: ".$sendmail."<br>".$query_string;
	

}

/*************************************************************
* 		NON GLOBAL FUNCTIONS (not required for other cores)
**************************************************************/
