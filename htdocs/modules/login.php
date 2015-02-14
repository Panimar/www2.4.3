<?php
if (!defined('AXE'))
	exit;
//if session set, then we shoudlnt be here
if (isset($_SESSION['user'])) 
{
	print "You are already logged in."; $tpl_footer = new Template("styles/".$style."/footer.php");
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
if (isset($_POST['action'])) 
{
	if (pun_htmlspecialchars($_POST['username'])=='')
		{
			$cont2= "Your user name needs to be filled in. <meta http-equiv='refresh' content='2;url=./quest.php?name=login'/>";
		}
		elseif (pun_htmlspecialchars($_POST['username'])=='')
		{
			$cont2= "Your password needs to be filled in. <meta http-equiv='refresh' content='2;url=./quest.php?name=login'/>";
		}
	//lets select acc db
	mysql_select_db($acc_db);
	special_core_exec_onlogin(pun_htmlspecialchars($_POST['username']));
	
	$a  = mysql_query("SELECT ".$db_translation['encrypted_password']." FROM ".$db_translation['accounts']." WHERE ".$db_translation['login']." = '".$db->escape(pun_htmlspecialchars($_POST['username']))."'") or die (mysql_error());
	$a2 = mysql_fetch_array($a);
	if ($a2[0]==sha1(strtoupper(pun_htmlspecialchars($_POST['username'])).':'.strtoupper(pun_htmlspecialchars($_POST['password'])))) 
	{
		if (pun_htmlspecialchars($_POST['username'])=='')
		{
			$cont2= "Your user name needs to be filled in. <meta http-equiv='refresh' content='0;url=./quest.php?name=login'/>";
		}
		elseif (pun_htmlspecialchars($_POST['password'])=='')
		{
			$cont2= "Your password needs to be filled in. <meta http-equiv='refresh' content='0;url=./quest.php?name=login'/>";
		}
		else
		{
			$_SESSION['user']=pun_htmlspecialchars($_POST['username']);
			$cont2= "You are now logged in! <meta http-equiv='refresh' content='0;url=./'/>";
		}
		$box_wide->setVar("content_title", "Login");	
        $box_wide->setVar("content", $cont2);					
        print $box_wide->toString();
		$tpl_footer = new Template("styles/".$style."/footer.php");
	    $tpl_footer->setVar("imagepath", 'styles/'.$style.'/images/');
	    print $tpl_footer->toString();
	    exit;
	    }
	    else
	    {
		$warn = "<font color='red'>(!)</font>";
		$warn2 = "<strong>Неправильное имя пользователя или пароль!</strong>";
	}
} 


$cont2='
         <center>
         <div class="sub-box1" align="left">
	     <form action="" method="post">
		 &nbsp;Логин:<br/>
		 <input type="text" id="username" maxlength="20" name="username" /><br/> '. $warn.'								
         &nbsp;Пароль:  <br/>                               	       
         <input type="password" id="password" maxlength="20" name="password" /><br/> '. $warn .'	
'. $warn2.'
		 &nbsp;*Попробуйте ещё раз ввести ваш логин и пароль!<br/>
		 <div class="line2"></div>
		 <table cellpadding="0" cellspacing="0"><tr>
		 <td valign="top">
		 <div id="log-b2"><input type="submit" name="action" value="Вход" class="button doit" /></div>
		 </td>
		 <td valign="top" style="padding: 3px 0px 0px 5px;">
		 <a href="./quest.php?name=gimmepass">Восстановить пароль?</a> <br/>
		 <a href="quest.php?name=register">Создать аккаунт</a>
		 </td>
		 </tr></table>
		 
		 </form>
		 </div>
         </center>
';
		 
$box_wide->setVar("content_title", "Вход");	
$box_wide->setVar("content", $cont2);					
print $box_wide->toString();
					