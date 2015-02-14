<?php
if (!defined('AXE'))
	exit;
	
	
if (!$a_user['is_guest'])
{
	box ('Ошибка','Вы уже зарегестрированы,почемы вы бы хотели создать новый аккаунт<br>Пожалуйста, продолжите...');
	$tpl_footer = new Template("styles/".$style."/footer.php");
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
	//do login stuff:
	$login = preg_replace( "/[^A-Za-z0-9]/", "", $_POST['username'] ); //only letters and numbers
	
	if ($login=='')
	{
		$war1="<font color='red'>Enter your username</font>";
	}
	else //pass empty
	{
		$db->select_db($acc_db);
		$result = $db->query("SELECT ".$db_translation['login']." FROM ".$db_translation['accounts']." WHERE ".$db_translation['login']." = '".$db->escape($login)."' LIMIT 1") or die(mysql_error());
		$rows   = $db->num_rows($result);
		if ($rows>=1)
		{
			$war1="<font color='red'>Логин '".$login."'уже занят, выберете другойr.</font>";
			$db->select_db($db_name);
		}
		else //pass username
		{
			if ($smtp_h=='')
			{
				$pass1 = preg_replace( "/[^A-Za-z0-9]/", "", $_POST['password'] ); //only letters and numbers
				$pass2 = preg_replace( "/[^A-Za-z0-9]/", "", $_POST['password2'] ); //only letters and numbers
				if ($pass1=='')
				{
					box ('Fail',"Type in password.");
					$tpl_footer = new Template("styles/".$style."/footer.php");
					$tpl_footer->setVar("imagepath", 'styles/'.$style.'/images/');
					print $tpl_footer->toString();
					exit;
				}
				else //pass empty
				{
					if ($pass1<>$pass2) 
					{
						box ('Ошибка',"Пароли не совпадают.");
						$tpl_footer = new Template("styles/".$style."/footer.php");
						$tpl_footer->setVar("imagepath", 'styles/'.$style.'/images/');
						print $tpl_footer->toString();
						exit;
					}
				}
			}
			$email = pun_htmlspecialchars($_POST['email']);
			if ($email=='')
			{
				$war3="<font color='red'>Type in your e-mail address</font>";
			}
			else //pass empty
			{
				$db->select_db($acc_db);
				$result = $db->query("SELECT ".$db_translation['login']." FROM ".$db_translation['accounts']." WHERE ".$db_translation['email']." = '".$db->escape($email)."' LIMIT 1") or die(mysql_error());
				$rows   = $db->num_rows($result);
				if ($rows>=1)
				{
					$war3="<font color='red'>The e-mail address '".$email."' is already in use!</font><br/>";
					$db->select_db($db_name);
				}
				else //pass
				{
					$question = $_POST['question'];
					$answer = preg_replace( "/[^A-Za-z0-9]/", "", $_POST['answer'] );
					if ($answer=='')
					{
						$war4="<font color='red'>Make sure you type in your answer.</font><br/>";
					}
					else //pass final
					{	
					
						//random pass
						if ($smtp_h<>'' && $smtp_u<>'') //check if there is smtp info
						{
							$pass1=random_pass('6');
						}
						
						$db->select_db($acc_db);
						//create_account($user,$pass,$email,$securityq,$securitya)
						$createacc=create_account($login,$pass1,$db->escape($email));
						if ($createacc)
						{
							box ('Fail',$createacc);
							$tpl_footer = new Template("styles/".$style."/footer.php");
							$tpl_footer->setVar("imagepath", 'styles/'.$style.'/images/');
							print $tpl_footer->toString();
							exit;
						}
						$db->select_db($db_name);
						//add additional data
						$result2 = $db->query("INSERT INTO accounts_more (acc_login, vp, question_id, answer, dp) VALUES ('".strtoupper($login)."','0','".$question."','".$db->escape($answer)."','0')") or die(mysql_error());

						if ($question=='1')
						{
							$questi="Your middle name?";
						}
						elseif ($question=='2')
						{
							$questi="Your birth town?";
						}
						elseif ($question=='3')
						{
							$questi="Your pet's name?";
						}
						elseif ($question=='4')
						{
							$questi="Your mother's maiden name?";
						}
						else
						{
							print "Something was wrong with your security question.<br/>";
						}
						
						//SMTP START
						if ($smtp_h<>'' && $smtp_u<>'') //check if there is smtp info
						{
							$from =trim($email);
							$to = trim($email);
							$subject =  $title." - Account Info";
							$body = "Thank you for creating account, ".$login."!\n\nYour password: ".$pass1."\n\nEnjoy your stay!\n\n".$domain_url;
							require_once "smtp.php";
							
						}
						//SMTP END
						
						$thisboxstring.='<span class="colorgood">Вы успешно зарегестрировались.Теперь вы можете зайти в игру.</span><br><br>'.$smtpme;
						if ($smtp_h=='' && $smtp_u<>'') //check if there is smtp info
						{
							$thisboxstring.=' Your password is <strong>'.$pass1.'</strong>. You can change it from the Account Panel.';
						}
						
						$thisboxstring.='<a href="./quest.php?name=account">Перейти в личный кабинет</a>';
						box ('Регистрация прошла успешно',$thisboxstring);
						//login
						$_SESSION['user']=pun_htmlspecialchars($login);
						
						
						
						$tpl_footer = new Template("styles/".$style."/footer.php");
	$tpl_footer->setVar("imagepath", 'styles/'.$style.'/images/');
	print $tpl_footer->toString();
	exit;
					}
					
				}
			}

		}
		}
	
	}	

$cont2='<center>
        <div class="sub-box1" align="left">
        <form action="" method="post">
		 &nbsp;Логин:<br/>
		 <input type="text" id="username" maxlength="20" name="username" /><br/> '. $war1.'
';
								if ($smtp_h=='') //check if there is smtp info
								{
$cont2.='
		&nbsp;Пароль:<br/>
		<input type="password" id="password" maxlength="20" name="password" /><br/>											
		&nbsp;Повторить пароль:<br/>
		<input type="password" id="password2" maxlength="20" name="password2" /> <br/>'. $war2.'											
';}
$cont2.='
        &nbsp;Email Адрес:<br/>
		<input type="text" id="email" maxlength="40" name="email" /><br/> '. $war3.'										
		&nbsp;Секретный вопрос:<br/>
		<div class="bord1">
          <label><input class="fix1" name="question" type="radio" value="1"  />&nbsp;Ваша фамилия?</label><br />
		  <label><input class="fix1" name="question" type="radio" value="2" checked="checked" />&nbsp; Ваш родной город?</label><br />
		  <label><input class="fix1" name="question" type="radio" value="3" />&nbsp;Кличка домашнего животного?</label><br />
		  <label><input class="fix1" name="question" type="radio" value="4" />&nbsp;Девичь фамилия матери?</label><br />	
		</div>					
        &nbsp;Секретный ответ:<br/>
        <input type="text" id="answer" maxlength="100" name="answer" /><br/> '.$war4.'										
         Может содержать символы английского алфавита (a-z, A-Z) и цифры (0-9).<br/><br/>
		<div id="log-b2"><input type="submit" name="action" value="Зарегистрировать аккаунт" class="button doit" /></div>
		</form>
		</div>
		
		   <br/>
		   
		<div class="sub-box1" align="left">
		<strong>*Подключение</strong> <br/>
     <strong><font color="#464646">1)</font></strong> Открываем C:\Program Files\World of Warcraft\Data\enRU\realmlist.wtf редактируем.<br/>
     <strong><font color="#464646">2)</font></strong> 
	  Удалите всё и добавьте set realmlist <font color="#9a2828">wowwnet.servegame.com</font> and save.<br/>
     <strong><font color="#464646">3)</font></strong>Регестрируем аккаунт выше<br/>
     <strong><font color="#464646">4)</font></strong>Приятной игра на WoWWnet!<br/>
		</div>
</center>
';

$box_wide->setVar("content_title", "Регистрация аккаунта");	
$box_wide->setVar("content", $cont2);					
print $box_wide->toString();	