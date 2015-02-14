<?php
if (!defined('AXE'))
	exit;

if (!isset($_SESSION['user'])) 
{
	print "You are not logged in.";
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


//

$box_simple_wide->setVar("content", $cont1);
print $box_simple_wide->toString(); ?>


							<?php 
							$cont2='<table cellpadding="1" cellspacing="1"> <tr> <td valign="top">
							<div class="acc-info-box">Hello,  <strong>'. $a_user[$db_translation['login']].'</strong>!<br /><br />
							ID аккаунта: <strong><font color="#CCC">'. $a_user[$db_translation['acct']].'</font></strong><br />
							Последний вход:: <strong><font color="#CCC">'. $a_user[$db_translation['lastlogin']].'</font></strong><br />
							Последний IP: <strong><font color="#CCC">'. $a_user[$db_translation['lastip']].'</font></strong><br />
							Ваш IP: <strong><font color="#CCC">'. get_remote_address().'</font></strong><br />';
							if ($a_user[$db_translation['flags']]==$db_translation['expansion_tbc'])
								$cont2.= 'Версия клиента: <strong> The Burning Crusaide</strong>';
							elseif ($a_user[$db_translation['flags']]==$db_translation['expansion_wotlk'])
								$cont2.= 'Версия клиента: <strong> Wrath of the Lich King </strong>';
							elseif ($a_user[$db_translation['flags']]==$db_translation['expansion_cata'])
								$cont2.= 'Версия клиента: <strong> Cataclysm </strong>';
							else
								$cont2.= 'You do not have an expantion set.';
								$cont2.='<br />
							Статус: '; if ($a_user[$db_translation['banned']]=='0' or $a_user[$db_translation['banned']]=='') { 
							$cont2.= "<span class='colorgood'><strong>Активен.</strong></color>";} else {$cont2.= "<font class='colorbad'><strong>Заблокирован.</strong></font>";};
							$cont2.='</div> <br/><br /><br />
                                 
								<div class="acc-b-m"><table cellpadding="0" cellspacing="0"><tr>
                                <td valign="top"><img width="60" height="50" border="0" src="./styles/'.$style.'/images/icon_lock.png"></td>
                                <td valign="top">
                                <a href="./quest.php?name=passchange">Изменить пароль</a> <br/>
                                <div class="acc_b_de">Узменяет пароль вашего аккаунта</div>
                                </td>
                                </tr></table></div>
								

';
							  if (file_exists('./modules/expansion.php')) {
							  $cont2.='
							  
							    <div class="acc-b-m"><table cellpadding="0" cellspacing="0"><tr>
                                <td valign="top"><img width="60" height="50" border="0" src="./styles/'.$style.'/images/wowstatus.png"></td>
                                <td valign="top">
                                <a href="./quest.php?name=expansion"> Enable/Disable Expansions </a> <br/>
                                <div class="acc_b_de">This tool can change what expansion packs are enabled on your account.</div>
                                </td>
                                </tr></table></div>
								
';
							 
							  }
							  if (file_exists('./modules/teleporter.php')) {
							  $cont2.='
							 
                                <div class="acc-b-m"><table cellpadding="0" cellspacing="0"><tr>
                                <td valign="top"><img width="60" height="50" border="0" src="./styles/'.$style.'/images/movecharact.png"></td>
                                <td valign="top">
                                <a href="./quest.php?name=teleporter">Телепортер</a> <br/>
                                <div class="acc_b_de">Телепортирует вашего персанажа в выбранную точку.</div>
                                </td>
                                </tr></table></div>
								
';
							  }
							  if (file_exists('./modules/char.php')) {
							  $cont2.='
							  
							    <div class="acc-b-m"><table cellpadding="0" cellspacing="0"><tr>
                                <td valign="top"><img width="60" height="50" border="0" src="./styles/'.$style.'/images/chars.png"></td>
                                <td valign="top">
                                <a href="./quest.php?name=char">Исправление ошибок</a> <br/>
                                <div class="acc_b_de">Исправляет ошибки вашего прсанажа</div>
                                </td>
                                </tr></table></div>
								
';
							 
							  }
							  if (file_exists('./modules/transfer.php')) {
							  $cont2.='
							  
							    <div class="acc-b-m"><table cellpadding="0" cellspacing="0"><tr>
                                <td valign="top"><img width="60" height="50" border="0" src="./styles/'.$style.'/images/movecharact.png"></td>
                                <td valign="top">
                                <a href="./quest.php?name=transfer"> Character Transfer </a> <br/>
                                <div class="acc_b_de">Migrate your character to another account.</div>
                                </td>
                                </tr></table></div>
';
                              
							  }
							  $cont2.='</td><td valign="top"> 
							  <div class="donate_banner_button">
							   <ul>
							     <li><a href="quest_ac.php?name=Donation_Shop"></a></li>
							   </ul>
							  </div>
							  ';
							 	//INCLUDE PLUGINS FROM m_admincp DIR
							  	//********************************************
								$folder = "modules/m_account/";
								$handle = opendir($folder);
								# Making an array containing the files in the current directory:
								while ($file = readdir($handle))
								{
									$files[] = $file;
								}
								closedir($handle);
								
								#echo the files
								foreach ($files as $file) 
								{
									
									if (strstr($file, ".php"))
									{
									
										$file2=substr($file, 0,-4); //without .php
										$file3=str_replace('_',' ',$file2); //replace "_" with " "
										//description start
										$homepage = file_get_contents('modules/m_account/'.$file2.'.txt');
										$descr=explode("|",$homepage);
										if ($descr[0]=='') $descr[0]='modules.png';
										//description end
										$cont2.= '
								<div class="acc-b-m"><table cellpadding="0" cellspacing="0"><tr>
                                <td valign="top"><img width="60" height="50" border="0" src="./styles/'.$style.'/images/'.$descr[0].'"></td>
                                <td valign="top">
                                <a href="./quest_ac.php?name='.$file2.'"> '.$file3.' </a> <br/>
                                <div class="acc_b_de">'.$descr[1].'</div>
                                </td>
                                </tr></table></div>
								
								';
									} 
								}
								//********************************************
							 $cont2.='</td></tr></table>'; 
?>
                           
<?php
$box_wide->setVar("content_title", "Account Panel");	
$box_wide->setVar("content", $cont2);					
print $box_wide->toString();							
?>				