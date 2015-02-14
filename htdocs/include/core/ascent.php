<?php

/***********************************************
* DB query structure for Ascent/ArcEmu, by AXE
* 3.2.2010. american date
* All functions here must always exist
************************************************/


$db_translation = array(
		//
		// GM premisions translation (all that are used by this cms)
		//
		"az"						=> "az",
		"a"							=> "a",
		"gm_normalplayer"			=> "0", //gm premission for normal player
		//
		// Expansion data in sql
		//
		"expansion_normal"			=> "0",
		"expansion_tbc"				=> "8",
		"expansion_wotlk"			=> "24",
		"expansion_cata"			=> "0",
		//
		// Accounts Table
		//
		"accounts" 					=> "accounts",	 	//table 'accounts'
			"acct"					=> "acct",
			"login"    				=> "login",   		
			"password" 				=> "password",	 	//leave blank if doesnt exits
														//if exists  website will try update raw pass here 
			"encrypted_password"	=> "encrypted_password",
			"gm"					=> "gm",
			"banned"				=> "banned",
			"lastip"				=> "lastip",
			"lastlogin"				=> "lastlogin",
			"flags"					=> "flags",
			"email"					=> "email", //for gimmepass.php
		//
		// Characters Table
		//
		"characters"				=> "characters",  //table 'characters'
			"characters_acct"		=> "acct",
			"characters_guid"		=> "guid",
			"characters_name"		=> "name",
			"characters_honorPoints"=> "honorPoints",
			"characters_killsLifeTime"=>"killsLifeTime",
			"characters_online"		=> "online",
			"characters_level"		=> "level",
			"characters_class"		=> "class",
			"characters_race"		=> "race",
			"characters_gender"		=> "gender",
			"characters_gold"		=> "gold",
		//
		// Item Table
		//
		"items"						=> "items",
			"items_name1"			=> "name1",
			"items_quality"			=> "quality",
			"items_entry"			=> "entry",
		//
		// Tickets
		//
		"gm_tickets"				=> "gm_tickets",
			"gm_tickets_guid"		=> "guid",
			"gm_tickets_playerGuid"	=> "playerGuid",
			"gm_tickets_timestamp" 	=> "timestamp",
			"gm_tickets_message" 	=> "message",
		
);


/***************************************
* 	 	  CHARACTER FUNCTIONS
***************************************/

function unstuck($guid)//returns
{
	global $db,$db_translation;
	$fail=false;
	$db->query( "UPDATE ".$db_translation['characters']." SET positionX = bindpositionX, positionY = bindpositionY, positionZ = bindpositionZ, mapId = bindmapId, zoneId = bindzoneId, deathstate = 0 WHERE guid = '".$guid."' LIMIT 1" ) or ($fail=mysql_error());
	return $fail;
}

function teleport($guid,$x,$y,$z,$map,$newGold)//returns
{
	global $db,$db_translation;
	$fail=false;
	$db->query( "UPDATE ".$db_translation['characters']." SET positionX = ".$x.", positionY = ".$y.", positionZ = ".$z.", mapid = ".$map.", ".$db_translation['characters_gold']." = ".$newGold." WHERE guid = '".$guid."'" ) or ($fail=mysql_error());
	return $fail;
}


/***************************************
* 	 ACCOUNT MANAGEMENT FUNCTIONS
***************************************/

function create_account($user,$pass,$email)//gotta be logged in for this, returns
{
	global $db_translation,$db,$a_user;
	$pass = preg_replace( "/[^A-Za-z0-9]/", "", $pass ); //only letters and numbers
	$user = preg_replace( "/[^A-Za-z0-9]/", "", $user ); //only letters and numbers
	
	$pass_enc=sha1(strtoupper($user).':'.strtoupper($pass));
	$db->query("INSERT INTO ".$db_translation['accounts']." (".$db_translation['login'].",".$db_translation['password'].",".$db_translation['encrypted_password'].",".$db_translation['gm'].",".$db_translation['banned'].",".$db_translation['email'].",".$db_translation['flags'].") VALUES ('".$user."','".$pass."','".$pass_enc."','0','0','".$db->escape($email)."','24')") or ($report = 'Error: '.mysql_error());
	if (!$report)
		return false;
	else
		return $report;
}

function passchange($newpass,$userid=false)//gotta be logged in for this, returns
{
	global $db_translation,$db,$a_user;
	$newpass = preg_replace( "/[^A-Za-z0-9]/", "", $newpass ); //only letters and numbers
	if ($userid==false)
	{
		$userid=$a_user[$db_translation['acct']];
	}
	//get user info
	$sql1=$db->query("SELECT * FROM  ".$db_translation['accounts']." WHERE ".$db_translation['acct']." = '".$userid."' LIMIT 1")or die($report = 'Error: '.mysql_error());
	$sql2=$db->fetch_assoc($sql1);
	
	$newpass_enc=sha1(strtoupper($sql2[$db_translation['login']]).':'.strtoupper($newpass));
	
	$db->query("UPDATE ".$db_translation['accounts']." SET ".$db_translation['password']."='".$newpass."',".$db_translation['encrypted_password']." = '".$newpass_enc."' WHERE ".$db_translation['acct']."='".$sql2[$db_translation['acct']]."' LIMIT 1") or ($report = 'Error: '.mysql_error());
	if (!$report)
		return false;
	else
		return $report;
}
//
//execute this when user wants to login and user/pass variables are $_post-ed thru
//
function special_core_exec_onlogin($username)
{	
	global $db,$acc_db;
	$db->select_db($acc_db);
	//For Ascent: we will check if user has password aleady encrypted, if not we will do this here and now
	$a = $db->query("SELECT encrypted_password,password FROM accounts WHERE login='".$db->escape($username)."' LIMIT 1") or die(mysql_error());
	if (mysql_num_rows($a)=='1')
	{
		$a1=$db->fetch_assoc($a);
		//nevermind if user already has encrypted passwod, we will reupdate it
		$db->query("UPDATE accounts SET encrypted_password='".sha1(strtoupper($username).':'.strtoupper($a1['password']))."' WHERE login='".$db->escape($username)."' LIMIT 1") or die(mysql_error());
	}
}

/***************************************
* 	    FILE SPECIFIC VARIABLES
***************************************/
//
//   /hk.php and /honor.php
//
$hk_where="banned='0' or banned='' and";



/***************************************
* 	    SERVER PATCHES
***************************************/
function patch_notice()//will be shown in admin panel
{
	return false; //none for ascent
}
function patch_include($patchname,$output=false)
{
	global $server_core;
	//inside /htdocs/include/core/ folder
	//include files named $server_core_$patchname.php
	if (file_exists(PATHROOT.'include/core/'.$server_core.'_'.$patchname.'.php'))
	{
		if ($output)
			echo "File ".'<b>'.PATHROOT.'include/core/'.$server_core.'_'.$patchname.'.php</b>'. " is loaded.";
		require_once(PATHROOT.'include/core/'.$server_core.'_'.$patchname.'.php');
	}
	else 
	{
		if ($output)
			echo "File ".'<b>'.PATHROOT.'include/core/'.$server_core.'_'.$patchname.'.php</b>'. " is not loaded.";
		return false;
	}
}