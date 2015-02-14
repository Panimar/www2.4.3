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
		$item="";
	if ($money=='')
		$money=0;
	
	
	$query_string="INSERT INTO mailbox_insert_queue(sender_guid, receiver_guid, subject, body, stationary, money, item_id, item_stack) VALUES ('".$playerguid."', '".$playerguid."', '".$db->escape($subject)."', '".$db->escape($text)."', '61', '".$money."', '".$item."', '1')";
	$sendmail=false;
	$db->query($query_string) or ($sendmail=mysql_error());
	if($sendmail==false)
		return  "<!-- success --><span class=\"colorgood\">Mail is sent! <br>All done!</span>";
	else
		return  "<span class=\"colorbad\">Mail is not sent! Error returned: ".$sendmail."<br>"."INSERT INTO mailbox_insert_queue(sender_guid, receiver_guid, subject, body, stationary, money, item_id, item_stack) VALUES ('".$playerguid."', '".$playerguid."', '".$db->escape($subject)."', '".$db->escape($text)."', '61', '".$money."', '".$item."', '1')"."</span>";
	

}

/*************************************************************
* 		NON GLOBAL FUNCTIONS (not required for other cores)
**************************************************************/
