<?php
/***********************************************************************

  Thiese functions are from PUNBB

************************************************************************/


//
//generate box with message for 'upozorenje' message
//
function upozorenje($messages)
{	
	global $style;
		 $t = new Template("styles/".$style."/box_simple_short.php");
		 $t->setVar("imagepath", "./styles/".$style.'/images/');
		 $t->setVar("content", $messages);
		  return $t->toString();
}
//
//generate box with message wide box only
//
function box($title, $message)
{
	global $style;
	$ta = new Template("styles/".$style."/box_wide.php");
	$ta->setVar("imagepath", "./styles/".$style.'/images/');
	$ta->setVar("content_title", $title);
	$ta->setVar("content", $message.'<br><br><a href="" onclick="history.back(1); return false">&laquo; Go Back</a>');
	print $ta->toString();
}
//
// Set a cookie!
//
function setacookie($user_id, $password_hash, $expire)
{
	global $cookie_name, $cookie_path, $cookie_domain;

	if (version_compare(PHP_VERSION, '5.2.0', '>='))
		setcookie($cookie_name, $user_id."-".$password_hash, $expire, $cookie_path, $cookie_domain, $cookie_secure, true);
	else
		setcookie($cookie_name, $user_id."-".$password_hash, $expire, $cookie_path.'; HttpOnly', $cookie_domain, $cookie_secure);
}
//
// Set a cookie - terms!
//
function setacookiet($value, $expire)
{
	global $cookie_name, $cookie_path, $cookie_domain;
	$cookie_namet=$cookie_name."_terms";
	if (version_compare(PHP_VERSION, '5.2.0', '>='))
		setcookie($cookie_name, $value, $expire, $cookie_path, $cookie_domain, $cookie_secure, true);
	else
		setcookie($cookie_name, $value, $expire, $cookie_path.'; HttpOnly', $cookie_domain, $cookie_secure);
}
//
// Generate a string with numbered links (for multipage scripts)
//
function paginate($num_pages, $cur_page, $link_to)
{
	$pages = array();
	$link_to_all = false;

	// If $cur_page == -1, we link to all pages (used in viewforum.php)
	if ($cur_page == -1)
	{
		$cur_page = 1;
		$link_to_all = true;
	}

	if ($num_pages <= 1)
		$pages = array('<strong>1</strong>');
	else
	{
		if ($cur_page > 3)
		{
			$pages[] = '<a href="'.$link_to.'&amp;p=1">1</a>';

			if ($cur_page != 4)
				$pages[] = '&hellip;';
		}

		// Don't ask me how the following works. It just does, OK? :-)
		for ($current = $cur_page - 2, $stop = $cur_page + 3; $current < $stop; ++$current)
		{
			if ($current < 1 || $current > $num_pages)
				continue;
			else if ($current != $cur_page || $link_to_all)
				$pages[] = '<a href="'.$link_to.'&amp;p='.$current.'">'.$current.'</a>';
			else
				$pages[] = '<strong>'.$current.'</strong>';
		}

		if ($cur_page <= ($num_pages-3))
		{
			if ($cur_page != ($num_pages-3))
				$pages[] = '&hellip;';

			$pages[] = '<a href="'.$link_to.'&amp;p='.$num_pages.'">'.$num_pages.'</a>';
		}
	}

	return implode('&nbsp;', $pages);
}






//
// If we are running pre PHP 4.3.0, we add our own implementation of file_get_contents
//
if (!function_exists('file_get_contents'))
{
	function file_get_contents($filename, $use_include_path = 0)
	{
		$data = '';

		if ($fh = fopen($filename, 'rb', $use_include_path))
		{
			$data = fread($fh, filesize($filename));
			fclose($fh);
		}

		return $data;
	}
}




//
// Generate a random password of length $len
//
function random_pass($len)
{
	$chars = 'ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz0123456789';

	$password = '';
	for ($i = 0; $i < $len; ++$i)
		$password .= substr($chars, (mt_rand() % strlen($chars)), 1);

	return $password;
}


//
// Try to determine the correct remote IP-address
//
function get_remote_address()
{
	return $_SERVER['REMOTE_ADDR'];
}


//
// Equivalent to htmlspecialchars(), but allows &#[0-9]+ (for unicode)
//
function pun_htmlspecialchars($str)
{
	$str = preg_replace('/&(?!#[0-9]+;)/s', '&amp;', $str);
	$str = str_replace(array('<', '>', '"'), array('&lt;', '&gt;', '&quot;'), $str);

	return $str;
}



//
// Equivalent to strlen(), but counts &#[0-9]+ as one character (for unicode)
//
function pun_strlen($str)
{
	return strlen(preg_replace('/&#([0-9]+);/', '!', $str));
}


//
// Convert \r\n and \r and rn to \n
//
function pun_linebreaks($str)
{
	return str_replace("\r", "\n", str_replace("\r\n", "\n", $str));
}
//
// Convert \r\n and \r and rn to <br>
//
function pun_linebreaks2($str)
{
	return str_replace("\r", "\n", str_replace("\r\n", "<br>", $str));
}

//
// A more aggressive version of trim()
//
function pun_trim($str)
{
	global $lang_common;

	if (strpos($lang_common['lang_encoding'], '8859') !== false)
	{
		$fishy_chars = array(chr(0x81), chr(0x8D), chr(0x8F), chr(0x90), chr(0x9D), chr(0xA0));
		return trim(str_replace($fishy_chars, ' ', $str));
	}
	else
		return trim($str);
}

//
// Display a simple error message
//
function error($message, $file, $line, $db_error = false)
{
	// Empty output buffer and stop buffering
	@ob_end_clean();

	// "Restart" output buffering if we are using ob_gzhandler (since the gzip header is already sent)
	if (!empty($pun_config['o_gzip']) && extension_loaded('zlib') && (strpos($_SERVER['HTTP_ACCEPT_ENCODING'], 'gzip') !== false || strpos($_SERVER['HTTP_ACCEPT_ENCODING'], 'deflate') !== false))
		ob_start('ob_gzhandler');

?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" dir="ltr">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
<title>Website Error</title>
</head>
<body>
<div style="BORDER: 1px solid #B84623; font-family:Verdana, Arial, Helvetica, sans-serif; ">
	<h2 style="MARGIN: 0; COLOR: #FFFFFF; BACKGROUND-COLOR: #B84623; FONT-SIZE: 1.1em; PADDING: 5px 4px;font-size:14px">An error was encountered</h2>
	<div style="PADDING: 6px 5px; BACKGROUND-COLOR: #F1F1F1; color:#000000;font-size:11px">
<?php

	if (defined('PUN_DEBUG'))
	{
		echo "\t\t".'<strong>File:</strong> '.$file.'<br />'."\n\t\t".'<strong>Line:</strong> '.$line.'<br /><br />'."\n\t\t".'<strong>The website reported:</strong>: '.$message."\n";

		if ($db_error)
		{
			echo "\t\t".'<br /><br /><strong>The database reported:</strong> '.pun_htmlspecialchars($db_error['error_msg']).(($db_error['error_no']) ? ' (Errno: '.$db_error['error_no'].')' : '')."\n";

			if ($db_error['error_sql'] != '')
				echo "\t\t".'<br /><br /><strong>Failed query:</strong> '.pun_htmlspecialchars($db_error['error_sql'])."\n";
		}
	}
	else
		echo "\t\t".'Error: <strong>'.$message.'.</strong>'."\n";

?>
	</div>
</div>

</body>
</html>
<?php

	// If a database connection was established (before this error) we close it
	if ($db_error)
		$GLOBALS['db']->close();

	exit;
}

// DEBUG FUNCTIONS BELOW

function unregister_globals()
{
	$register_globals = @ini_get('register_globals');
	if ($register_globals === "" || $register_globals === "0" || strtolower($register_globals) === "off")
		return;

	// Prevent script.php?GLOBALS[foo]=bar
	if (isset($_REQUEST['GLOBALS']) || isset($_FILES['GLOBALS']))
		exit('Damn, you are naughty.');
	
	// Variables that shouldn't be unset
	$no_unset = array('GLOBALS', '_GET', '_POST', '_COOKIE', '_REQUEST', '_SERVER', '_ENV', '_FILES');

	// Remove elements in $GLOBALS that are present in any of the superglobals
	$input = array_merge($_GET, $_POST, $_COOKIE, $_SERVER, $_ENV, $_FILES, isset($_SESSION) && is_array($_SESSION) ? $_SESSION : array());
	foreach ($input as $k => $v)
	{
		if (!in_array($k, $no_unset) && isset($GLOBALS[$k]))
		{
			unset($GLOBALS[$k]);
			unset($GLOBALS[$k]);	// Double unset to circumvent the zend_hash_del_key_or_index hole in PHP <4.4.3 and <5.1.4
		}
	}
}




