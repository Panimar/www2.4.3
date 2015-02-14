<?php
//include "./include/timer-header.php";
require "./include/common.php"; 
$tpl_header = new Template("styles/".$style."/header.php");
$tpl_header->setVar("title", $title);
$tpl_header->setVar("stylesheet", '<script src="./res/javascript.js" type="text/javascript"></script><link href="styles/'.$style.'/style.css" rel="stylesheet" type="text/css">');
$tpl_header->setVar("imagepath", 'styles/'.$style.'/images/');
$tpl_header->setVar("sitetitle", $title);

	//generate links
	if (!$a_user['is_guest']) 
	{
		$u=$a_user[$db_translation['login']];
		$p=$a_user[$db_translation['encrypted_password']];
		$tpl_header->setVar("navigation", '<a href="./quest.php?name=logout&hash='.$u.'.'.$p.'">Выйти</a>');
	}
	else
	{
		$tpl_header->setVar("navigation", '<a href="./quest.php?name=login" onmouseover="$WowheadPower.showTooltip(event, \'In order to go to your Account Panel you must login.\')" onmousemove="$WowheadPower.moveTooltip(event)" onmouseout="$WowheadPower.hideTooltip();">Login</a>');
	}
	//end

if ($a_user['is_guest'])
{
	//registration link:
	if ($smtp_h<>'') $smtp_descr= "Register a new account. A mail with your password will be sent to your e-mail address."; else $smtp_descr='Register a new account .';
	$a = array("descr" 		=> $smtp_descr,
               "title"		=> "Создать аккаунт",
			   "linkpath" 	=>"quest.php?name=register");
    $tpl_header->gotoNext("link_guest");
    $tpl_header->setVar("link_guest", $a);
	//login link:
	$a = array("descr" 		=> "Login with your username and password. After you login, you can enter your Account Panel where you can modify your info, unstuck your character and more.",
               "title"		=> "Войти",
			   "linkpath" 	=>"quest.php?name=login");
    $tpl_header->gotoNext("link_guest");
    $tpl_header->setVar("link_guest", $a);
	if ($smtp_h<>'')
	{
	//pass retrieve link:
	$a = array("descr" 		=> "Forgot your password? Enter your e-mail and security answer to retrieve your password.",
               "title"		=> "Password Retrieval",
			   "linkpath" 	=>"quest.php?name=gimmepass");
    $tpl_header->gotoNext("link_guest");
    $tpl_header->setVar("link_guest", $a);
	}
}
else
{
  if ($a_user[$db_translation['gm']]<>'0' && $a_user[$db_translation['gm']]<>$db_translation['az'] && $a_user[$db_translation['gm']]<>'' && $a_user[$db_translation['gm']]<>$db_translation['gm_normalplayer']) 
	{
		//check if file ticket manager is installed
		if (file_exists('./modules/admincp4.php')) {
			//tickets link:
			$b = array("descr2" 		=> "View GM tickets.",
					   "title2"		=> "Tickets",
					   "linkpath2" 	=>"quest.php?name=admincp4");
			$tpl_header->gotoNext("link_loggedin");
			$tpl_header->setVar("link_loggedin", $b);
		}
	}
	if ($a_user[$db_translation['gm']]==$db_translation['az'])
	{
		$b = array("descr2" 		=> "Only \'".$db_translation['az']."\' accounts can enter the Admin Panel.",
				   "title2"		=> "Админ панель",
				   "linkpath2" 	=>"quest.php?name=admincp");
		$tpl_header->gotoNext("link_loggedin");
		$tpl_header->setVar("link_loggedin", $b);
	}
	$b = array("descr2" 		=> "Change your password, unstuck your character and more.",
				   "title2"		=> "Личный Кабинет",
				   "linkpath2" 	=>"quest.php?name=account");
	$tpl_header->gotoNext("link_loggedin");
	$tpl_header->setVar("link_loggedin", $b);
	$b = array("descr2" 		=> "Logout here.",
				   "title2"		=> "Выйти",
				   "linkpath2" 	=>'quest.php?name=logout&hash='.$u.'.'.$p);
	$tpl_header->gotoNext("link_loggedin");
	$tpl_header->setVar("link_loggedin", $b);
}

					//
					//check for navigation cache file
					//
					if (file_exists('./cache/cache_navigation.php'))
					{
						include_once './cache/cache_navigation.php';
					}
					else
					{
						mysql_select_db($db_name);
						$linkovi  = mysql_query("SELECT title,link,description FROM pages WHERE position = '1' ORDER BY orderby ASC") or error('Something is wrong with table "pages" in website database, look below. <br><br></strong>MySQL reported:<strong> '.mysql_error(), __FILE__, __LINE__);
						while ($linkovi2=mysql_fetch_assoc($linkovi))
						{
							$title_link=explode('[|]',$linkovi2['title']);
							if (($title_link[1]=='1' && $a_user['is_guest']) or $title_link[1]=='' or ($title_link[1]=='2' && !$a_user['is_guest']) or ($title_link[1]=='3' && ($a_user[$db_translation['gm']]=='0' or $a_user[$db_translation['gm']]=='' or $a_user[$db_translation['gm']]==$db_translation['gm_normalplayer'])) or ($title_link[1]=='5' && ($a_user[$db_translation['gm']]==$db_translation['az'] or $a_user[$db_translation['gm']]==$db_translation['a'])) or ($title_link[1]=='4' && $a_user[$db_translation['gm']]==$db_translation['az']))
							{
								if ($linkovi2['description']=='')
									$wowhead='';
								else
									$wowhead='onmouseover="$WowheadPower.showTooltip(event, \''.$linkovi2['description'].'\')" onmousemove="$WowheadPower.moveTooltip(event)" onmouseout="$WowheadPower.hideTooltip();"';
								$a = array(    "wowhead" 	=> $wowhead,
											   "title"		=> $title_link[0],
											   "linkpath" 	=> $linkovi2['link']);
								$tpl_header->gotoNext("link_custom");
								$tpl_header->setVar("link_custom", $a);
							}
						}
						$linkov  = mysql_query("SELECT title,link,description FROM pages WHERE position = '2' ORDER BY orderby ASC") or error('Something is wrong with table "pages" in website database, look below. <br><br></strong>MySQL reported:<strong> '.mysql_error(), __FILE__, __LINE__);
						while ($linkov2=mysql_fetch_assoc($linkov))
						{
							$title_link=explode('[|]',$linkov2['title']);
							if (($title_link[1]=='1' && $a_user['is_guest']) or $title_link[1]=='' or ($title_link[1]=='2' && !$a_user['is_guest']) or ($title_link[1]=='3' && ($a_user[$db_translation['gm']]=='0' or $a_user[$db_translation['gm']]=='' or $a_user[$db_translation['gm']]==$db_translation['gm_normalplayer'])) or ($title_link[1]=='5' && ($a_user[$db_translation['gm']]==$db_translation['az'] or $a_user[$db_translation['gm']]==$db_translation['a'])) or ($title_link[1]=='4' && $a_user[$db_translation['gm']]==$db_translation['az']))
							{
								if ($linkov2['description']=='')
									$wowhead='';
								else
									$wowhead='onmouseover="$WowheadPower.showTooltip(event, \''.$linkov2['description'].'\')" onmousemove="$WowheadPower.moveTooltip(event)" onmouseout="$WowheadPower.hideTooltip();"';
								$a = array(    "wowhead" 	=> $wowhead,
											   "title"		=> $title_link[0],
											   "linkpath" 	=> $linkov2['link']);
								$tpl_header->gotoNext("link_custom2");
								$tpl_header->setVar("link_custom2", $a);
							}
						}
					}
  print $tpl_header->toString();