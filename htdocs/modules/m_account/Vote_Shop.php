<?php

if (!defined('AXE'))
	exit;

require 'config_voteshop.php';

//common include
$box_simple_wide = new Template("styles/".$style."/box_simple_wide.php");
$box_wide = new Template("styles/".$style."/box_wide.php");
$box_wide->setVar("imagepath", 'styles/'.$style.'/images/');
$box_simple_wide->setVar("imagepath", 'styles/'.$style.'/images/');
//end common include
patch_include("sendmail",false);
if (!isset($_SESSION['user'])) 
{
	print "You are not logged in."; $tpl_footer = new Template("styles/".$style."/footer.php");
	$tpl_footer->setVar("imagepath", 'styles/'.$style.'/images/');
	print $tpl_footer->toString();
	exit;
}
if (isset($_POST['realm']))
 {
 
 $_SESSION['realm']= $_POST['id'];
 
 }
if (!isset($_SESSION['realm'])) 
{
                         
						 $cont2.="<center><div class='new_vote_searchdiv' align='center'>Choose a realm:<table cellspan='0' rowspan='0'>";
						 
						 		$i=0;$j=1;
							while ($j<=count($realm))
							{
						 
						 $cont2.="<td><form method='POST' action='./quest_ac.php?name=Vote_Shop'><input type='hidden' value='".$j."' name='id'><div id='log-b2'><input type='submit' value='".$realm[$j]['name']."' name='realm' /></div></form></td>";
						 	
								$j++;					
							}	
						 $cont2.="</table></div>";
						$box_wide->setVar("content_title", "Vote Shop");	
                        $box_wide->setVar("content", $cont2);					
                        print $box_wide->toString();
						$tpl_footer = new Template("styles/".$style."/footer.php");
	$tpl_footer->setVar("imagepath", 'styles/'.$style.'/images/');
	print $tpl_footer->toString();
	exit;
}


//now reduce points
$db->select_db($db_name) or die(mysql_error());

//send item to character
if (isset($_POST['action'])) 
{
	//we get char id
	if ($_POST['character']=='none')
	{
		box ('Fail','You don\'t have any characters. Mail can\'t be sent.'); 
		$tpl_footer = new Template("styles/".$style."/footer.php");
		$tpl_footer->setVar("imagepath", 'styles/'.$style.'/images/');
		print $tpl_footer->toString();
		exit;
	}
	$pieces = explode("-", $_POST['character']);
	$char = $pieces[0];  //char guid
	$realm_data123 = $pieces[1]; //realm
	
	
	
	if ($_POST['itemsgrup']=='')
	{
		box ('Fail','No item selected.');
		$tpl_footer = new Template("styles/".$style."/footer.php");
		$tpl_footer->setVar("imagepath", 'styles/'.$style.'/images/');
		print $tpl_footer->toString();
		exit;
	}
	
	$itemsgrup = $_POST['itemsgrup']; //this is shop ID
	//now we get all required data for this shop ID
	$checkshopid = $db->query("SELECT * FROM vote_items WHERE entry='".$itemsgrup."' LIMIT 1") or die(mysql_error());
		if (mysql_num_rows($checkshopid)=='0')
			{box ('EPIC Fail','<font color="red"><blink>Hack attempt!</blink></font><br><strong>WebScript:</strong> What the fuck are you doing?<br><strong>WebScript:</strong> <a href="http://www.webwow.totalh.com" target="_blank">AXE</a> will punish you becouse you doing this to me!<br><strong>WebScript:</strong> In matter of fact ill report your ass to admins right now!<br><strong>WebScript:</strong> I know who you are <strong>'.$a_user[$db_translation['login']].'</strong> and your IP is '.$_SERVER['REMOTE_ADDR'].'...<br><strong>WebScript:</strong> <i>[Grunting] (That will teach you...)</i><br><br><strong>WebScript:</strong> Tell me one good reason, one! Why i don\'t ban you right now at spot, ha...<br><strong>WebScript:</strong> Wtf did u doing SQL injecting like that? Stupid humans...'); $tpl_footer = new Template("styles/".$style."/footer.php");
	$tpl_footer->setVar("imagepath", 'styles/'.$style.'/images/');
	print $tpl_footer->toString();
	exit;}
	
	$checkshopid2=mysql_fetch_assoc($checkshopid);
	
	$vote_costs2 = $db->query("SELECT * FROM vote_costs WHERE start_itemlevel <= ".$checkshopid2["ItemLevel"]." AND end_itemlevel >= ".$checkshopid2["ItemLevel"]." LIMIT 1") or die (mysql_error());
    $row2 = $db->fetch_assoc($vote_costs2);
	
	if (!$row2)
     $costpoints = '100';
    else
     $costpoints = $row2["points"];
	 
	$cost = $costpoints;
	
	$itemid = $checkshopid2['entry'];
	$item_stack = '1';

		//reduce points
		if ($a_user['vp']>=$cost)
		{
		}
		else
		{
			box ('Fail','You don\'t have enough points to buy that item.<br>You have '.$a_user['vp'].' points and item costs '.$cost.' points.');
			$tpl_footer = new Template("styles/".$style."/footer.php");
			$tpl_footer->setVar("imagepath", 'styles/'.$style.'/images/');
			print $tpl_footer->toString();
			exit;
		}

		//check if realm db is availavable and select db
		$i=1;
		while ($i<=count($realm))
		{
			if ($pieces[1]==$i)
			{
				if ($realm[$i]['db']=='')
				{box ('Fail','Realm '.$pieces[1].' does not exist!');$tpl_footer = new Template("styles/".$style."/footer.php");
				$tpl_footer->setVar("imagepath", 'styles/'.$style.'/images/');
				print $tpl_footer->toString();
				exit;}
				$db->select_db($realm[$i]['db']);
			}
			$i++;
		}
		
		
		//now we check if this is truly char witch belongs to your account
		$checkchar = $db->query("SELECT ".$db_translation['characters_name'].",".$db_translation['characters_guid']." FROM ".$db_translation['characters']." WHERE ".$db_translation['characters_guid']."='".$char."' AND ".$db_translation['characters_acct']."='".$a_user[$db_translation['acct']]."' LIMIT 1") or die(mysql_error());
		if (mysql_num_rows($checkchar)=='0')
			{box ('EPIC Fail','<font color="red"><blink>Hack attempt!</blink></font><br><strong>WebScript:</strong> What the fuck are you doing?<br><strong>WebScript:</strong> <a href="http://www.webwow.totalh.com" target="_blank">AXE</a> will punish you becouse you doing this to me!<br><strong>WebScript:</strong> In matter of fact ill report your ass to admins right now!<br><strong>WebScript:</strong> I know who you are <strong>'.$db_translation['login'].'</strong> and your IP is '.$_SERVER['REMOTE_ADDR'].'...<br><strong>WebScript:</strong> <i>[Grunting] (That will teach you...)</i><br><br><strong>WebScript:</strong> Tell me one good reason, one! Why i don\'t ban you right now at spot, ha...<br><strong>WebScript:</strong> Wtf did u doing SQL injecting like that? You CAN\'T SEND ITEMS TO CHARACTERS THAT AREN\'T ON YOUR ACCOUNT. Stupid humans...'); $tpl_footer = new Template("styles/".$style."/footer.php");
	$tpl_footer->setVar("imagepath", 'styles/'.$style.'/images/');
	print $tpl_footer->toString();
	exit;}
		
		$charname=$db->fetch_array($checkchar);
		//add mail here
		$time = date("m-d-Y, h:i");
		$refnum=date("jnGis");
		$subject = 'WebsiteVoteShopREF'.$refnum.'';//do not remove $refnum
		$body = 'Enjoy your new reward! Item costed '.$cost.' points. [Time sent: '.$time.'] [Item ID:'.$itemid.']';

		//refrence-> sendmail($playername,$playerguid, $subject, $text, $item, $shopid, $money=0,$realmid=false) //returns
		$sendingmail=sendmail($charname[0],$charname[1], $subject, $body, $itemid,'0','0',$pieces[1]);	
		//SQL
		if (substr($sendingmail, 0, 16)=="<!-- success -->")
		{
			$newpoints=$a_user['vp']-$cost;
			$db->select_db($db_name);
			$delpoints = $db->query("UPDATE accounts_more SET vp='".$newpoints."' WHERE acc_login='".$a_user[$db_translation['login']]."'") or die(mysql_error());
			$sendingmail.="<br>Points are taken.";
		}
		//end SQL
		
		box ('Report',$sendingmail);
		$tpl_footer = new Template("styles/".$style."/footer.php");
	$tpl_footer->setVar("imagepath", 'styles/'.$style.'/images/');
	print $tpl_footer->toString();
	exit;
	}

//
//select web database
//
$db->select_db($db_name);

//
//	Display shop:
//

$name = $_GET['q']; 

$cont2.='<center><div class="voteshop1">
';

					 $cont2.="<table cellspan='0' rowspan='0'>";
						 
						 		$i=0;$j=1;
							while ($j<=count($realm))
							{
						 if ($j==$_SESSION['realm']){$cont2.="<td><div id='log-b22'><input type='submit' value='".$realm[$j]['name']."' name='realm' disabled='disabled'></td>";} else{
						 $cont2.="<td><form method='POST' action='./quest_ac.php?name=Vote_Shop'><input type='hidden' value='".$j."' name='id'><div id='log-b2'><input type='submit' value='".$realm[$j]['name']."' name='realm' /></div></form></td>";
						 	}
								$j++;					
							}	
						 $cont2.="</table>";
$cont2.='
		     <div align="left"><div class="small_box_1"> 
		     You have <font color="#7d8585">'. $a_user['vp'].'</font> Vote Points. 
		     </div></div> <br/>
		<center>
		<div class="new_vote_searchdiv" align="center">
		 <form action="" method="get">
		 <input type="hidden" name="name" value="'.$_GET['name'].'">
         <div class="searc-inp"><input type="text" name="q" value="'.$name.'"></div>
		 <div id="log-b3"><input type="submit" name="search" value="Search"></div>
		 </form>
		</div>
		</center>
		<form method="post" action="">
		<table border="0" width="680px" align="center" cellpadding="0" cellspacing="0">';
						
					if(isset($_GET['search'])){ 
                      
					  $cont2.= '<br/>
							   <tr id="itng" width="680px">
								<td id="in1"><div>Item Name</div></td>
								<td id="ic1"><div>Cost</div></td>
								<td id="ib1"><div>Buy?</div></td>
							   </div></tr>';
							  	  
						 if(preg_match("/^[  a-zA-Z0-9#()]+$/", $_GET['q'])){
     
							  $query = $db->query("SELECT * FROM vote_items WHERE name LIKE '%" . $name .  "%' AND `show` = 'yes' AND realm = '".$_SESSION['realm']."' OR name LIKE '%" . $name .  "%' AND `show` = 'yes' AND realm = '0' ORDER BY name ASC LIMIT ".$voteshop_config['results_limit']) or die (mysql_error());
							  $num = $db->num_rows($query);

							  while ($items = $db->fetch_assoc($query))
							  {		
							  	$vote_costs = $db->query("SELECT * FROM vote_costs WHERE start_itemlevel <= ".$items["ItemLevel"]." AND end_itemlevel >= ".$items["ItemLevel"]." LIMIT 1") or die (mysql_error());
                                $row = $db->fetch_assoc($vote_costs);
	
                                 if (!$row)
                                  $cost = '100';
                                 else
								 if ($items["custom"]=="1"){                                  $cost = $row["points"];

										$cont2.= '<tr onmouseover="this.style.backgroundImage = \'url(./res/images/transp-green.png)\';" onmouseout="this.style.backgroundImage = \'none\';" onclick="document.getElementById(\'radio_'.$items['entry'].'\').checked = \'checked\';">';
										$cont2.= "<td id='s7233s'>";
										$cont2.= '<span class="q'.$items['Quality'].'" href="#" onmouseover="$WowheadPower.showTooltip(event, \'This is a custom item.\')" onmousemove="$WowheadPower.moveTooltip(event)" onmouseout="$WowheadPower.hideTooltip();">'.$items['name'].'</span></td>';									
										$cont2.= "<td id='s7233s'>".$cost."</td>";
										$cont2.= '<td id="s7233s"><input type="radio" name="itemsgrup" value="'.$items['entry'].'" id="radio_'.$items['entry'].'" />';									
										$cont2.='</td></tr>';}
								 else{
                                  $cost = $row["points"];

										$cont2.= '<tr onmouseover="this.style.backgroundImage = \'url(./res/images/transp-green.png)\';" onmouseout="this.style.backgroundImage = \'none\';" onclick="document.getElementById(\'radio_'.$items['entry'].'\').checked = \'checked\';">';
										$cont2.= "<td id='s7233s'>";
										$cont2.= "<a class='q".$items['Quality']."' href='http://www.wowhead.com/?item=".$items['entry']."'>".$items['name']."</a></td>";									
										$cont2.= "<td id='s7233s'>".$cost."</td>";
										$cont2.= '<td id="s7233s"><input type="radio" name="itemsgrup" value="'.$items['entry'].'" id="radio_'.$items['entry'].'" />';									
										$cont2.='</td></tr>';
									
							  }}
							  
						   } else {
					 $cont2 .= '<tr><td colspan="0" align="center">
							 	<center><h3>Try again!</h3></center></td></tr>';
					       }
					} else {
					 $cont2 .= '<tr><td colspan="0" align="center">
							 	<center><h3>Please enter a search query.</h3> </br>
								<h3>For example: "<font color="#FF9900"><strong>Token of Title</strong></font>"</h3>
								</center></td></tr>';
					}
							  $cont2.='</table><br/>
							  <div class="new_vote_searchdiv" align="center">
							  <font color="#5d6161">Select Chracter:</font> <select name="character">';
							
							//#########################################CHAR START
								$i=0;$j=$_SESSION['realm'];
							
								
									$db->select_db($realm[$j]['db'])or error('Unable to select realm database. Probabley you misspelled database name');
									$result = $db->query("SELECT * FROM ".$db_translation['characters']." WHERE ".$db_translation['characters_acct']."='".$a_user[$db_translation['acct']]."'") or die (mysql_error());
									
									while ($char = $db->fetch_assoc($result))
									{
										$cont2.= "<option value='".$char[$db_translation['characters_guid']]."-".$j."'>".$realm[$j]['name']." - ".$char[$db_translation['characters_name']]." level ".$char[$db_translation['characters_level']]." </option>";
										
										$i++;
									}
									
								
												
							
							
								if ($i=='0')
								{
									$cont2.=  "<option value='none'>You do not have any characters</option>";
								}
							//go back to default db selection
							$db->select_db($db_name);
								
                             	
								$cont2.=  "</select> ";
							//#########################################CHAR END
                           		$cont2.= '<div id="log-b3"> <input name="action" type="submit" value="Purchase!"/> </div></form>	
<br/><br/>
<font color="#2f3333">Upon purchasing, website might load more than 10 seconds.<br/> Please be patient and wait for website to load.</font>
							 </div>
							<br><br></div></center>
							';
						$box_wide->setVar("content_title", "Vote Shop");	
                        $box_wide->setVar("content", $cont2);					
                        print $box_wide->toString();
