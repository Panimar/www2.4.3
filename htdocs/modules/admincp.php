<?php
if (!defined('AXE'))
	exit;
if ($a_user['is_guest']) 
{
	print "You are not logged in."; $tpl_footer = new Template("styles/".$style."/footer.php");
	$tpl_footer->setVar("imagepath", 'styles/'.$style.'/images/');
	print $tpl_footer->toString();
	exit;
}
if ($a_user[$db_translation['gm']]<>$db_translation['az'])
{
	print "You do not have access to this page."; $tpl_footer = new Template("styles/".$style."/footer.php");
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
//if session set, then we shoudlnt be here
if (!$a_user['is_guest'] && $a_user[$db_translation['gm']]==$db_translation['az']) 
{
	
	
	
	
						
						$cont2='	<table width="100%" border="0" cellpadding="4">
                              <tr>
                                <td>&nbsp;</td>
                                <td align="left"><a style="font-size:14px" href="./quest.php?name=admincp3">User Manager</a><br />
                                  <i>This tool allows you to ban, unban, add or reduce vote or donation points from a player.</i></td>
                              </tr>';
							 
							  if (file_exists('./modules/admincp4.php')) 
							  { 
							  $cont2.='
                                <tr>
                                  <td>&nbsp;</td>
                                  <td align="left"><a style="font-size:14px" href="./quest.php?name=admincp4">Tickets</a><br />
                                <i>This tool allows you to read or delete in-game GM tickets.</i></td>
                                </tr>
								';
							  }
							  
							  ?>
							  <!--
							  <tr>
							    <td>&nbsp;</td>
							    <td align="left"><a style="font-size:14px" href="./quest.php?name=account&points=1">Add Items to Vote Shop</a><br />
                                <i>Redirects you to vote item shop where you can add items. </i></td>
						      </tr>
							  <tr>
							    <td>&nbsp;</td>
							    <td align="left"><a style="font-size:14px" href="./quest.php?name=account&points=2">Add Items to Donation Shop</a><br />
                                <i>Redirects you to donation item shop where you can add items. </i></td>
						      </tr>
							  -->
							  <?php
							  $cont2.='
							   <tr>
							    <td>&nbsp;</td>
							    <td align="left"><a style="font-size:14px" href="./index.php">Post News</a><br />
                                <i>Redirects you to index where you can post news. </i></td>
						      </tr>
							  <tr>
							    <td>&nbsp;</td>
							    <td align="left"><a style="font-size:14px" href="./quest.php?name=manlink">Manage Links</a><br />
                                <i>You can add or remove links from the top menu.</i></td>
						      </tr>
							  <tr>
							    <td>&nbsp;</td>
							    <td align="left"><a style="font-size:14px" href="./quest.php?name=manstyles">Manage Styles</a><br />
                                <i>You can change your website design with one click.</i></td>
						      </tr>
						      <tr>
							    <td>&nbsp;</td>
							    <td align="left"><a style="font-size:14px" href="./quest.php?name=ingamemail">Send in-game mail</a><br />
                                   <i>You can write and send mails to in-game characters.</i></td>
						      </tr>
							  ';
							 	//INCLUDE PLUGINS FROM m_admincp DIR
							  	//********************************************
								$folder = "modules/m_admincp/";
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
										$file3=str_replace('_',' ',$file2); //without .php
										//description start
										$homepage = file_get_contents('modules/m_admincp/'.$file2.'.txt');
										//description end
										$cont2.= '<tr>
							    <td>&nbsp;</td>
							    <td align="left"><a style="font-size:14px" href="./quest_ad.php?name='.$file2.'">'.$file3.'</a><br><i>'.$homepage.'</i></td>
						      </tr>';
									} 
								}
								//********************************************
							  $cont2.='
                              </table>
							  ';
$box_wide->setVar("content_title", "Administration");	
$box_wide->setVar("content", $cont2);					
print $box_wide->toString();
							

$cont4 ='This website is fully compatible and tested with PHP 5.3.0.<br><br>Your current installed PHP version is: '.phpversion().'<br>';
$cont4.="<br>This website optimized for a <strong>".$server_core.'</strong> database structure.';


$box_wide->setVar("content_title", "Info");	
$box_wide->setVar("content", $cont4);					
print $box_wide->toString();	

	if (patch_notice())
	{
		$cont5 =patch_notice();
		$box_wide->setVar("content_title", "Required Server config");	
		$box_wide->setVar("content", $cont5);					
		print $box_wide->toString();	
	}	
}