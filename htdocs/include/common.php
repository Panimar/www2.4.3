<?php
/***********************************************************************

  Modified PunBB file

************************************************************************/
//start session
session_start();
if (!defined('PATHROOT')) {
    define('PATHROOT', './');
}
// Load the functions script
require_once PATHROOT.'include/functions.php';
require_once PATHROOT.'include/function_template.php';




// Reverse the effect of register_globals
unregister_globals();


@include_once PATHROOT.'config.php';
if ($server_core=='')
	$server_core='ascent';
require_once PATHROOT.'include/core/'.$server_core.'.php';

//include cache functions
require_once PATHROOT.'include/function_cache.php';

// If AXE isn't defined, config.php is missing or corrupt
if (!defined('AXE'))
	exit('The file \'config.php\' does not exist or is corrupt.');



// Make sure PHP reports all errors except E_NOTICE. PunBB supports E_ALL, but a lot of scripts it may interact with, do not.
error_reporting(E_ALL ^ E_NOTICE);
//error_reporting(E_ALL);
require_once PATHROOT.'include/parser.php';


// Strip slashes from GET/POST/COOKIE (if magic_quotes_gpc is enabled)
if (get_magic_quotes_gpc())
{
	function stripslashes_array($array)
	{
		return is_array($array) ? array_map('stripslashes_array', $array) : stripslashes($array);
	}

	$_GET = stripslashes_array($_GET);
	$_POST = stripslashes_array($_POST);
	$_COOKIE = stripslashes_array($_COOKIE);
}

// Seed the random number generator (PHP <4.2.0 only)
if (version_compare(PHP_VERSION, '4.2.0', '<'))
	mt_srand((double)microtime()*1000000);

//check terms cookie if no cookie redirect to terms of service page else go index
$namet=$cookie_name."_terms";
$valuet = "agree";

if ($_GET['terms']=='agree')
{	
	$tex =  time() +  172800;//expire in 2 days
	setcookie($namet, $valuet, $tex, $cookie_path, $cookie_domain, $cookie_secure, true);
}
if ($usetos=='1')
{
	if ((!isset($_COOKIE[$namet]) || $_COOKIE[$namet] != $valuet) && !isset($_GET['terms']))
	{
		
		print '<meta http-equiv="refresh" content="0;url=./tos.php"/>';
		exit;
	}
}

// Load DB abstraction layer and connect
require PATHROOT.'include/dblayer/common_db.php';
$upozorenje="";

//required in PHP 5.3, set default timezone for date() function
if(function_exists("date_default_timezone_set") and function_exists("date_default_timezone_get"))
@date_default_timezone_set(@date_default_timezone_get());


//logout:
if ($_GET['hash'])
{
	$hash=explode('.',$_GET['hash']);
	//check if user and pass match
	
	$db->select_db($acc_db);
	
	$resultz = $db->query("SELECT ".$db_translation['encrypted_password']." FROM ".$db_translation['accounts']." WHERE ".$db_translation['login']."='".$db->escape($hash[0])."'") or error('Unable to select the password from the accounts table. <br><br></strong>MySQL reported:<strong> '.mysql_error(), __FILE__, __LINE__);
	$z_user = $db->fetch_array($resultz);
	

	if ($z_user[0]==$hash[1])
	{
		$_SESSION['user']=pun_htmlspecialchars($cookie[0]);	
		$expire =  time() - 3600 ;
		//delete cookie
		setacookie($hash[0], $hash[1], $expire);
		//delete session
		session_destroy();
		//set is guest parameter
		$a_user['is_guest']=TRUE;
		//done
	}//else nothing, can't logout ya
}
else
{
	if (isset($_COOKIE[$cookie_name]) && !isset($_SESSION['user']))
	{	
		$cookie = explode ("-",$_COOKIE[$cookie_name]);
		
		//print "[checking cookie]-[".$cookie[0]."]-[".$cookie[1]."]";
		//-do cookie login values
		$db->select_db($acc_db);
		$result = $db->query("SELECT ".$db_translation['encrypted_password']." FROM ".$db_translation['accounts']." WHERE UPPER(".$db_translation['login'].")='".$db->escape(strtoupper($cookie[0]))."'") or error('Unable to select the password from the accounts table. <br><br></strong>MySQL reported:<strong> '.mysql_error(), __FILE__, __LINE__);
		$c_user = $db->fetch_array($result);
		if ($c_user[0]==$cookie[1])
		{
			$_SESSION['user']=pun_htmlspecialchars($cookie[0]);	
		}
	} 
	elseif (isset($_SESSION['user']) && !isset($_COOKIE[$cookie_name]))
	{
		
		//-cookie stuff
		$expire =  date("U") + (12*30*24*60*60) ; //1 year
		$db->select_db($acc_db);
		$result = $db->query("SELECT ".$db_translation['encrypted_password']." FROM ".$db_translation['accounts']." WHERE UPPER(".$db_translation['login'].")='".strtoupper($_SESSION['user'])."'") or error('Unable to select an account. <br><br></strong>MySQL reported:<strong> '.mysql_error().'.<br><br></strong>Possible fix:<strong> Open your config.php file and find the $acc_db variable. Recheck if you have entered the correct database name.', __FILE__, __LINE__);
		$h_user = $db->fetch_assoc($result);
		setacookie(strtoupper($_SESSION['user']), $h_user[$db_translation['encrypted_password']], $expire);
		//-cookie written
	}

	
	
	if (isset($_SESSION['user'])) 
	{
		$db->select_db($acc_db);
		$result = $db->query("SELECT * FROM ".$db_translation['accounts']." WHERE ".$db_translation['login']."='".$_SESSION['user']."'") or error('Unable to select an account. <br><br></strong>MySQL reported:<strong> '.mysql_error().'.<br><br></strong>Possible fix:<strong> Open  your config.php file and find the $acc_db variable. Recheck if you have entered the correct database name.', __FILE__, __LINE__);
		$a_user = $db->fetch_assoc($result);
		//
		// GM premissions for TRINITY
		// SET the $a_user[$db_translation['gm']]
		//
		if (function_exists('trinity_premissions')) $a_user[$db_translation['gm']]=trinity_premissions();
		//echo trinity_premissions();
		//
		// END TRINITY
		//
		$a_user['is_guest']=FALSE;
		$db->select_db($db_name);
		$result2 = $db->query("SELECT * FROM accounts_more WHERE UPPER(acc_login)='".strtoupper($a_user[$db_translation['login']])."'") or error('Unable to select an account. <br><br></strong>MySQL reported:<strong> '.mysql_error().'.<br><br></strong>Possible fix:<strong> Open your config.php file and find the $acc_db variable. Recheck if you have entered the correct database name.', __FILE__, __LINE__);
		//if there is no additional data we need to create
		if ($db->num_rows($result2)=='0')
		{
			//print "Creating new data...";
			$random=rand(10, 99);
			$result5 = $db->query("INSERT INTO accounts_more (acc_login, question_id, answer) VALUES ('".strtoupper($a_user[$db_translation['login']])."','1','loremaster".$random."')") or error('Unable to update the aditional website-related account data. <br><br></strong>MySQL reported:<strong> '.mysql_error().'.<br><br></strong>Possible fix:<strong> Re-importing the sql database from the root of the website download will probably fix the problem.', __FILE__, __LINE__);
			
			$upozorenje="<div style='border:dashed 1px green; padding:4px'><font size='2' color='lightgreen'><strong>IMPORTANT:</strong></font><br>This is your first login, your points has been set to 0. You do not have a security question set yet, so we will pick one for you:<br><br>Security Question: Your middle name?<br>Security Answer: loremaster".$random."<br><br>Memorize this name, after you reload this page, this message will go away.</div>";
			$a_user['vp'] = '0';
			$a_user['dp'] = '0';
		}
		elseif ($db->num_rows($result2)>='2')
		{	
			$result6 = $db->query("DELETE FROM accounts_more WHERE UPPER(acc_login) = '".strtoupper($a_user[$db_translation['login']])."'") or error('Unable to delete the aditional website-related account data. <br><br></strong>MySQL reported:<strong> '.mysql_error().'.<br><br></strong>Possible fix:<strong> Re-importing the sql database from the root of the website download will probably fix the problem.', __FILE__, __LINE__);
			$random=rand(10, 99);
			$result5 = $db->query("INSERT INTO accounts_more (acc_login, question_id, answer) VALUES ('".strtoupper($a_user[$db_translation['login']])."','1','loremaster".$random."')")or error('Unable to add aditional website-related account data. <br><br></strong>MySQL reported:<strong> '.mysql_error().'.<br><br></strong>Possible fix:<strong> Re-importing the sql database from the root of the website download will probably fix the problem.', __FILE__, __LINE__);
			$upozorenje="<div style='border:dashed 1px green; padding:4px'><font size='2' color='lightgreen'><strong>IMPORTANT:</strong></font><br>Your account has more than 1 row of data with your name in it. This script will delete all data and allow you to insert new data. If this happend to you, it is probably the server administrators fault. If you lose any points, please talk to admininstrator.<br><br>Your points have been set to 0. You do not have security question set yet, so we will pick one for you:<br><br>Security Question: Your middle name?<br>Security Answer: loremaster".$random."<br><br>Memorize this answer. After you reload this page, this message will go away.</div>";
		}
		else
		{
			$b_user = $db->fetch_assoc($result2);
			//set additional strings
			$a_user['vp'] = $b_user['vp'];
			$a_user['dp'] = $b_user['dp'];
			$a_user['question_id'] = $b_user['question_id'];
			$a_user['answer'] = $b_user['answer'];
		}
		
	}
	else
	{
		//else is guest
		$a_user['is_guest']=TRUE;
	}
}
?>