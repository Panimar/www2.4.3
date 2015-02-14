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
		"az"						=> "4",
		"a"							=> "3",
		"gm_normalplayer"			=> "0", //gm premission for normal player
		//
		// Expansion data in sql
		//
		"expansion_normal"			=> "0",
		"expansion_tbc"				=> "1",
		"expansion_wotlk"			=> "2",
		"expansion_cata"			=> "0",
		//
		// Accounts Table
		//
		"accounts" 					=> "account",	 	//table 'accounts'
			"acct"					=> "id",
			"login"    				=> "username",   		
			"password" 				=> "",	 	//leave blank if doesnt exits
														//if exists  website will try update raw pass here 
			"encrypted_password"	=> "sha_pass_hash",
			"gm"					=> "gmlevel",
			"banned"				=> "locked",
			"lastip"				=> "last_ip",
			"lastlogin"				=> "last_login",
			"flags"					=> "expansion",
			"email"					=> "email", //for gimmepass.php
		//
		// Characters Table
		//
		"characters"				=> "characters",  //table 'characters'
			"characters_acct"		=> "account",
			"characters_guid"		=> "guid",
			"characters_name"		=> "name",
			"characters_honorPoints"=> "totalHonorPoints",
			"characters_killsLifeTime"=>"totalKills",
			"characters_online"		=> "online",
			"characters_level"		=> "level",
			"characters_class"		=> "class",
			"characters_race"		=> "race",
			"characters_gender"		=> "gender",
			"characters_gold"		=> "money",
		//
		// Item Table
		//
		"items"						=> "item_template",
			"items_name1"			=> "name",
			"items_quality"			=> "Quality",
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

        //fetching character homebind
        $query_select = 'SELECT * FROM character_homebind WHERE guid = "'.$guid.'" LIMIT 1';
        $sql1=$db->query($query_select)or die($report = 'Error1: '.mysql_error());
		$sql2=$db->fetch_assoc($sql1);


	$query_update ='UPDATE characters SET position_x="'.$sql2['position_x'].'", position_y="'.$sql2['position_y'].'", position_z="'.$sql2['position_z'].'", map="'.$sql2['map'].'", zone="'.$sql2['zone'].'" WHERE guid="'.$sql2['guid'].'" LIMIT 1';
        $db->query($query_update) or ($fail=mysql_error());
		
        return $fail;
}

function teleport($guid,$x,$y,$z,$map,$newGold)//returns
{
	global $db,$db_translation;
	$fail=false;
	$query_update ='UPDATE '.$db_translation['characters'].' SET position_x="'.$x.'", position_y="'.$y.'", position_z="'.$z.'", map="'.$map.'", money="'.$newGold.'" WHERE guid="'.$guid.'"';
    $db->query($query_update) or ($fail=mysql_error());
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
	$db->query("INSERT INTO ".$db_translation['accounts']." (".$db_translation['login'].",".$db_translation['encrypted_password'].",".$db_translation['banned'].",".$db_translation['email'].",".$db_translation['flags'].") VALUES ('".$user."','".$pass_enc."','0','".$db->escape($email)."','2')") or ($report = 'Error: '.mysql_error());
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
	
	
	$db->query("UPDATE ".$db_translation['accounts']." SET ".$db_translation['encrypted_password']." = '".$newpass_enc."',sessionkey='',v='',s='' WHERE ".$db_translation['acct']."='".$sql2[$db_translation['acct']]."' LIMIT 1") or ($report = 'Error: '.mysql_error());
	
	if (!$report)
		return "Password is changed.";
	else
		return $report;
}
//
//execute this when user wants to login and user/pass variables are $_post-ed thru
//
function special_core_exec_onlogin($username)
{	
	return false;
}

//
// Unique function for trinity (exists only here for trinity)
//
function trinity_premissions()
{
	global $db,$acc_db,$a_user,$db_translation,$db_name;
	$end = false;
	//check in account's more
	$s1 = $db->query("SELECT * FROM ".$db_name.".accounts_more WHERE UPPER(acc_login)='".$db->escape(strtoupper($a_user[$db_translation['login']]))."'")or die(mysql_error());
	if (mysql_num_rows($s1)=='1')
	{
		$s2=$db->fetch_assoc($s1);
		if ($s2['gmlevel']<>'')
		{
			return $s2['gmlevel'];
			$end=true;
		}
		
	}
	
	if ($end==false)
	{
		//select premission  from account_access
		$sql1 = $db->query("SELECT * FROM ".$acc_db.".account_access WHERE RealmID='1' AND id='".$a_user[$db_translation['acct']]."'")or die(mysql_error());
		if (mysql_num_rows($sql1)=='1')
		{
			$sql2=$db->fetch_assoc($sql1);
			return $sql2['gmlevel'];
		}
		else
		{
			return $db_translation['gm_normalplayer'];
		}
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
	global $db_translation,$a_user,$ra_user,$ra_pass,$db,$acc_db,$db_name;
	//=======================================
	// 	ADD gmlevel field to accounts_more
	//=======================================
	$db->query("ALTER TABLE ".$db_name.".accounts_more ADD gmlevel int(11) default NULL");
	//=======================================
	// 	        HOW PREMISSIONS WORK
	//=======================================
	$output = "
	<big>How Website premissions for Trinity Core work:</big><br><br>
	Script will check <strong>accounts_more</strong> table for user premissions for this website, if there is no data, script will check for 1st realm (Realm ID 1 in trinity config) and will give website premissions as in Realm ID 1.<br><br><i>You can edit user premissions trough <u><a href='./quest.php?name=admincp3'>User Manager</a></u>.</i><br><br><br><big>External mail patch and SQL (required for vote/donation shops):</big><br><br><u><a href='./include/core/trinity_external_mail/external_patch.txt'>You can find patch here (Mail.cpp)</a><br><a href='./include/core/trinity_external_mail/sql.txt'>SQL is here (execute to character's DB's)</a></u>";
	return $output;
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