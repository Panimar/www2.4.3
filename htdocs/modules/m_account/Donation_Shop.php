<?php /************************************************************************************** * Shop mod by AXE, this one is secure and is not hackable trough POST data modifying. * * Created: 8 April 2009, this mod uses 'shop' table.          * **************************************************************************************/ 
 if (!defined('AXE'))  exit;
 /*common include*/
 $box_simple_wide = new Template("styles/".$style."/box_simple_wide.php");
 $box_wide = new Template("styles/".$style."/box_wide.php"); 
 $box_wide->setVar("imagepath", 'styles/'.$style.'/images/');
 $box_simple_wide->setVar("imagepath", 'styles/'.$style.'/images/');
 /*end common include*/ 
 patch_include("sendmail",false);
 if (!isset($_SESSION['user'])) 
 {  print "You are not logged in.";
 $tpl_footer = new Template("styles/".$style."/footer.php");
 $tpl_footer->setVar("imagepath", 'styles/'.$style.'/images/');
 print $tpl_footer->toString();  exit; } 
 
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
						 
						 $cont2.="<td><form method='POST' action='./quest_ac.php?name=Donation_Shop'><input type='hidden' value='".$j."' name='id'><div id='log-b2'><input type='submit' value='".$realm[$j]['name']."' name='realm' /></div></form></td>";
						 	
								$j++;					
							}	
						 $cont2.="</table></div>";
						$box_wide->setVar("content_title", "Donation shop");	
                        $box_wide->setVar("content", $cont2);					
                        print $box_wide->toString();
						$tpl_footer = new Template("styles/".$style."/footer.php");
	$tpl_footer->setVar("imagepath", 'styles/'.$style.'/images/');
	print $tpl_footer->toString();
	exit;
}
 
 
 /*now reduce points*/ $db->select_db($db_name) or die(mysql_error());
 /*delete shop item, for admins*/ 
 if (isset($_GET['delid']) && $a_user[$db_translation['gm']]==$db_translation['az']) 
 {  
 $points=pun_htmlspecialchars($_GET['points']);  $delid=pun_htmlspecialchars($_GET['delid']); 
 if (isset($_GET['confirm']))
 {   $db->query("DELETE FROM shop WHERE id='".$db->escape($delid)."' LIMIT 1") or die (mysql_error());
 box ( "Delete Item","Item deleted!<br><br><a href='./quest_ac.php?name=Donation_Shop'>Go to Shop</a>" );  
 $tpl_footer = new Template("styles/".$style."/footer.php"); 
 $tpl_footer->setVar("imagepath", 'styles/'.$style.'/images/');
 print $tpl_footer->toString();  exit;  } 
 else  
 {  box ( "Delete Item","<center>Are you sure you want delete this item?<br><br><a href='quest_ac.php?name=Donation_Shop&delid=".$delid."&confirm=YES'>YES</a>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<a href='./quest_ac.php?name=Donation_Shop'>NO</a></center>" );
 $tpl_footer = new Template("styles/".$style."/footer.php");
 $tpl_footer->setVar("imagepath", 'styles/'.$style.'/images/');
 print $tpl_footer->toString();  exit;  }  } 
 /*send item to character*/ if (isset($_POST['action']))
 {  /*we get char id*/  if ($_POST['character']=='none')
 {   box ('Fail','You don\'t have any characters. Mail can\'t be sent.'); 
 $tpl_footer = new Template("styles/".$style."/footer.php"); 
 $tpl_footer->setVar("imagepath", 'styles/'.$style.'/images/'); 
 print $tpl_footer->toString();   exit;  } 
 $pieces = explode("-", $_POST['character']);  $char = $pieces[0]; 
 /*char guid*/  $realm_data123 = $pieces[1]; /*realm*/     
 if ($_POST['itemsgrup']=='')  
 {   box ('Fail','No item selected.');  
 $tpl_footer = new Template("styles/".$style."/footer.php"); 
 $tpl_footer->setVar("imagepath", 'styles/'.$style.'/images/');  
 print $tpl_footer->toString();   exit;  }  
 $itemsgrup = $_POST['itemsgrup']; /*this is shop ID*/ 
 $itemsgrup = preg_replace( "/[^0-9]/", "", $_POST['itemsgrup'] ); 
 /*only  numbers  /*now we get all required data for this shop ID*/  $checkshopid = $db->query("SELECT * FROM shop WHERE id='".$itemsgrup."' AND donateorvote='1' LIMIT 1") or die(mysql_error());   if (mysql_num_rows($checkshopid)=='0')    {box ('EPIC Fail','<font color="red"><blink>Hack attempt!</blink></font><br><strong>WebScript:</strong> What the fuck are you doing?<br><strong>WebScript:</strong> <a href="http://www.webwow.totalh.com" target="_blank">AXE</a> will punish you becouse you doing this to me!<br><strong>WebScript:</strong> In matter of fact ill report your ass to admins right now!<br><strong>WebScript:</strong> I know who you are <strong>'.$a_user[$db_translation['login']].'</strong> and your IP is '.$_SERVER['REMOTE_ADDR'].'...<br><strong>WebScript:</strong> <i>[Grunting] (That will teach you...)</i><br><br><strong>WebScript:</strong> Tell me one good reason, one! Why i don\'t ban you right now at spot, ha...<br><strong>WebScript:</strong> Wtf did u doing SQL injecting like that? Stupid humans...'); $tpl_footer = new Template("styles/".$style."/footer.php");  $tpl_footer->setVar("imagepath", 'styles/'.$style.'/images/');  print $tpl_footer->toString();  exit;}    $checkshopid2=mysql_fetch_assoc($checkshopid);  $cost = $checkshopid2['cost'];  $itemid = $checkshopid2['itemid'];  $item_stack = $checkshopid2['charges'];  if($checkshopid2['realm']!=$_SESSION['realm'] && $checkshopid2['realm']!="0")  {   box ('Fail','This item is not available on that realm.');   $tpl_footer = new Template("styles/".$style."/footer.php");   $tpl_footer->setVar("imagepath", 'styles/'.$style.'/images/');   print $tpl_footer->toString();   exit;  }       /*reduce points*/   if ($a_user['dp']>=$cost)   {       }   else   {    box ('Fail','You don\'t have enough points to buy that item.<br>You have '.$a_user['dp'].' points and item costs '.$cost.' points.');    $tpl_footer = new Template("styles/".$style."/footer.php");    $tpl_footer->setVar("imagepath", 'styles/'.$style.'/images/');    print $tpl_footer->toString();    exit;   }    /*check if realm db is availavable and select db*/   $i=1;   while ($i<=count($realm))   {    if ($pieces[1]==$i)    {     if ($realm[$i]['db']=='')     {box ('Fail','Realm '.$pieces[1].' does not exist!');$tpl_footer = new Template("styles/".$style."/footer.php");     $tpl_footer->setVar("imagepath", 'styles/'.$style.'/images/');     print $tpl_footer->toString();     exit;}     $db->select_db($realm[$i]['db']);    }    $i++;   }      /*now we check if this is truly char witch belongs to your account*/   $checkchar = $db->query("SELECT ".$db_translation['characters_name'].",".$db_translation['characters_guid']." FROM ".$db_translation['characters']." WHERE ".$db_translation['characters_guid']."='".$char."' AND ".$db_translation['characters_acct']."='".$a_user[$db_translation['acct']]."' LIMIT 1") or die(mysql_error());   if (mysql_num_rows($checkchar)=='0')    {box ('EPIC Fail','<font color="red"><blink>Hack attempt!</blink></font><br><strong>WebScript:</strong> What the fuck are you doing?<br><strong>WebScript:</strong> <a href="http://www.web-wow.net" target="_blank">AXE</a> will punish you becouse you doing this to me!<br><strong>WebScript:</strong> In matter of fact ill report your ass to admins right now!<br><strong>WebScript:</strong> I know who you are <strong>'.$db_translation['login'].'</strong> and your IP is '.$_SERVER['REMOTE_ADDR'].'...<br><strong>WebScript:</strong> <i>[Grunting] (That will teach you...)</i><br><br><strong>WebScript:</strong> Tell me one good reason, one! Why i don\'t ban you right now at spot, ha...<br><strong>WebScript:</strong> Wtf did u doing SQL injecting like that? You CAN\'T SEND ITEMS TO CHARACTERS THAT AREN\'T ON YOUR ACCOUNT. Stupid humans...'); $tpl_footer = new Template("styles/".$style."/footer.php");  $tpl_footer->setVar("imagepath", 'styles/'.$style.'/images/');  print $tpl_footer->toString();  exit;}      $charname=$db->fetch_array($checkchar);   /*add mail here*/   $time = date("m-d-Y, h:i");   $refnum=date("jnGis");   $subject = 'WebsiteDonationShopREF'.$refnum.'';/*do not remove $refnum*/   $body = 'Enjoy your new reward! Item costed '.$cost.' points. [Time sent: '.$time.'] [Item ID:'.$itemid.']';    /*refrence-> sendmail($playername,$playerguid, $subject, $text, $item, $shopid, $money=0,$realmid=false) //returns*/   $sendingmail=sendmail($charname[0],$charname[1], $subject, $body, $itemid,$itemsgrup,'0',$pieces[1]);   /*SQL*/      if (substr($sendingmail, 0, 16)=="<!-- success -->")   {    $newpoints=$a_user['dp']-$cost;    $db->select_db($db_name);    $delpoints = $db->query("UPDATE accounts_more SET dp='".$newpoints."' WHERE acc_login='".$a_user[$db_translation['login']]."'") or die(mysql_error());    $sendingmail.="<br>Points are taken.";   }      /*end SQL*/       box ('Report',$sendingmail);   $tpl_footer = new Template("styles/".$style."/footer.php");  $tpl_footer->setVar("imagepath", 'styles/'.$style.'/images/');  print $tpl_footer->toString();  exit;  }    
 $box_simple_wide->setVar("content", $cont1); print $box_simple_wide->toString(); /**/ /*select web database*/ /**/ $db->select_db($db_name);  /**/ /* Something is bought (post data submitted)*/ /**/  if ($a_user[$db_translation['gm']]==$db_translation['az']) { 
 if ($_POST['additem']) 
 {    if ($_POST['sep']=='0') /*is item*/  
 {    if ($_POST['itemid']=='')   
 {     box ('Fail','Make sure you type in item id.');  
 $tpl_footer = new Template("styles/".$style."/footer.php");
 $tpl_footer->setVar("imagepath", 'styles/'.$style.'/images/'); 
 print $tpl_footer->toString();  exit;    }  
 else if ($_POST['name']=='')  
 {     box ('Fail','Make sure you type in item name.');  
 $tpl_footer = new Template("styles/".$style."/footer.php"); 
 $tpl_footer->setVar("imagepath", 'styles/'.$style.'/images/');
 print $tpl_footer->toString();  exit;    } 
 else if ($_POST['description']=='')   
 {     box ('Failure','Make sure you typed in an item description.');  
 $tpl_footer = new Template("styles/".$style."/footer.php"); 
 $tpl_footer->setVar("imagepath", 'styles/'.$style.'/images/'); 
 print $tpl_footer->toString();  exit;    } 
 else if ($_POST['points']=='')    
 {     box ('Fail','Make sure you type in item point cost.'); 
 $tpl_footer = new Template("styles/".$style."/footer.php"); 
 $tpl_footer->setVar("imagepath", 'styles/'.$style.'/images/');
 print $tpl_footer->toString();  exit;    } 
 else if ($_POST['charges']=='')    
 {     box ('Fail','Make sure you type in charges.');  
 $tpl_footer = new Template("styles/".$style."/footer.php"); 
 $tpl_footer->setVar("imagepath", 'styles/'.$style.'/images/'); 
 print $tpl_footer->toString();  exit;    }
 else if ($_POST['cat']=='')    
 {     box ('Fail','Make sure you type in category number for sorting items.'); 
 $tpl_footer = new Template("styles/".$style."/footer.php");
 $tpl_footer->setVar("imagepath", 'styles/'.$style.'/images/');
 print $tpl_footer->toString();  exit;    }
 else if ($_POST['sort']=='')    
 {     box ('Fail','Make sure you type in sort items within same category.');  
 $tpl_footer = new Template("styles/".$style."/footer.php"); 
 $tpl_footer->setVar("imagepath", 'styles/'.$style.'/images/');
 print $tpl_footer->toString();  exit;    }    else /*pass*/   
 {     $result=$db->query("INSERT INTO shop (sep,name,itemid,color,cat,sort,cost,charges,donateorvote,description,custom,realm) VALUES ('0','".$db->escape($_POST['name'])."','".$db->escape($_POST['itemid'])."','".$_POST['color']."','".$db->escape($_POST['cat'])."','".$db->escape($_POST['sort'])."','".$db->escape($_POST['points'])."','".$db->escape($_POST['charges'])."','1','".$db->escape($_POST['description'])."', '".$db->escape($_POST['custom'])."','".$db->escape($_POST['realm1'])."')") or die(mysql_error());       box ('Success','Item is added!');  $tpl_footer = new Template("styles/".$style."/footer.php");  $tpl_footer->setVar("imagepath", 'styles/'.$style.'/images/');  print $tpl_footer->toString();  exit;    }   }   else /*is seperator*/   {    if ($_POST['name']=='')    {     box ('Fail','Make sure you type in item name.');     $tpl_footer = new Template("styles/".$style."/footer.php");  $tpl_footer->setVar("imagepath", 'styles/'.$style.'/images/');  print $tpl_footer->toString();  exit;    }    else if ($_POST['cat']=='')    {     box ('Fail','Make sure you type in category number for sorting items.');     $tpl_footer = new Template("styles/".$style."/footer.php");  $tpl_footer->setVar("imagepath", 'styles/'.$style.'/images/');  print $tpl_footer->toString();  exit;    }    else if ($_POST['sort']=='')    {     box ('Fail','Make sure you type in sort items within same category.');     $tpl_footer = new Template("styles/".$style."/footer.php");  $tpl_footer->setVar("imagepath", 'styles/'.$style.'/images/');  print $tpl_footer->toString();  exit;    }    else /*pass*/    {     $result=$db->query("INSERT INTO shop (sep,name,cat,sort,donateorvote,itemid) VALUES ('1','".$db->escape($_POST['name'])."','".$db->escape($_POST['cat'])."','".$_POST['sort']."','1','0')") or die(mysql_error());          box ('Success','Item is added!');     $tpl_footer = new Template("styles/".$style."/footer.php");  $tpl_footer->setVar("imagepath", 'styles/'.$style.'/images/');  print $tpl_footer->toString();  exit;    }   }  }              } /**/ /* Display shop:*/ /**/ 

 $cont2='<center><div class="voteshop1">';
 
$cont2.="<table cellspan='0' rowspan='0'>";
						 
						 		$i=0;$j=1;
							while ($j<=count($realm))
							{
						 if ($j==$_SESSION['realm']){$cont2.="<td><div id='log-b22'><input type='submit' value='".$realm[$j]['name']."' name='realm' disabled='disabled'></td>";} else{
						 $cont2.="<td><form method='POST' action='./quest_ac.php?name=Donation_Shop'><input type='hidden' value='".$j."' name='id'><div id='log-b2'><input type='submit' value='".$realm[$j]['name']."' name='realm' /></div></form></td>";
						 	}
								$j++;					
							}	
						 $cont2.="</table>";
 $cont2.='		<div align="left"><div class="small_box_1"> 
You have '. $a_user['dp'].' Donation Points.
</div></div> <br/>
 <form method="post" action=""><table border="0" width="650px" align="center" cellpadding="3" cellspacing="0">    
 <tr style=" background-image:url(./res/images/transp-white.png)">    
 <td colspan="2">Item Name</td>         <td>Description</td>     
 <td>Cost</td>   
 <td>Buy?</td>   
 </tr>';          
 $query = $db->query("SELECT * FROM shop WHERE donateorvote='1' AND realm = '".$_SESSION['realm']."' OR donateorvote='1' AND realm = '0' ORDER BY cat, sort ASC") or die (mysql_error());   
 while ($items = $db->fetch_assoc($query))      
 {              /*its seperator*/    
 if ($items['sep']=='1')       
 {           $cont2.= "<tr><td colspan='3'>";    
 if ($a_user[$db_translation['gm']]==$db_translation['az'])     
 {            $cont2.= '<a href="./quest.php?name=account&points=1&delid='.$items['id'].'">[x]</a> ';    
 }           $cont2.= "<strong><u>".$items['name']."</u></strong></td></tr>";       
 }          else /*its item*/          {     
 $cont2.= '<tr onmouseover="this.style.backgroundImage = \'url(./res/images/transp-green.png)\';" onmouseout="this.style.backgroundImage = \'none\'; ">';    
 $cont2.= "<td>";           if ($a_user[$db_translation['gm']]==$db_translation['az'])       
 {            $cont2.= '<a href="./quest_ac.php?name=Donation_Shop&delid='.$items['id'].'">[x]</a>  ';       
 }           if ($items['custom']=='1')        
 {            /*color codes here*/         
 $cil = array (         
 '0'=>'gray',    
 '1'=>'white',  
 '2'=>'#25FF16',  
 '3'=>'#0070AC',   
 '4'=>'#A335EE',  
 '5'=>'#FF8000',   
 );      
 $cont2.= '<span style="color:'.$cil[$items['color']].'" onmouseover="$WowheadPower.showTooltip(event, \'<font color='.$cil[$items['color']].'>'.$items['name'].'</font><br><small>This is a donation token.</small>\')" onmousemove="$WowheadPower.moveTooltip(event)" onmouseout="$WowheadPower.hideTooltip();">['.$items['name'].']</span></td>';           }           else           {           $cont2.= "<a class='q".$items['color']."' href='http://www.wowhead.com/?item=".$items['itemid']."'>[".$items['name']."]</a></td>";           }                      if ($items['charges']=='0' || $items['charges']=='1')           {            $charges='';           }           else           {            $charges='x'.$items['charges'];           }           $cont2.= "<td>".$charges."</td>";           $real_descr=explode("[|]",$items['description']);           $cont2.= "<td>".$real_descr[0]."</td>";                     $cont2.= "<td>".$items['cost']."</td>";           $cont2.= '<td><input type="radio" name="itemsgrup" value="'.$items['id'].'" />';
                       $cont2.='</td> </tr>';          }          }                                       $cont2.='        
  
 <tr><td colspan="4">  <br/>
 <div class="new_vote_searchdiv" align="center">Select Your Chracter: <select name="character">';                
 /*#########################################CHAR START*/         $i=0;        $j=$_SESSION['realm'];       
 $db->select_db($realm[$j]['db'])or error('Unable to select realm database. Probabley you misspelled database name');  
 $result = $db->query("SELECT * FROM ".$db_translation['characters']." WHERE ".$db_translation['characters_acct']."='".$a_user[$db_translation['acct']]."'") or die (mysql_error());  
 while ($char = $db->fetch_assoc($result))      
 {       
 $cont2.= "<option value='".$char[$db_translation['characters_guid']]."-".$j."'>".$realm[$j]['name']." - ".$char[$db_translation['characters_name']]." level ".$char[$db_translation['characters_level']]." </option>";   
 $i++;          }                            $j++;                     if ($i=='0')        {         $cont2.=  "<option value='none'>You do not have any characters</option>";        }        /*go back to default db selection*/        $db->select_db($db_name);                                                 $cont2.=  "</select> ";        /*#########################################CHAR END*/ 
     $cont2.= ' <div id="log-b3"><input name="action" type="submit" value="Purchase" /></div></form>                 
	 <br /> <br /> Upon purchasing the website might take more than 10 seconds to load.<br> Please be patient and wait whilst your purchase is progressed.   </div>
							</tr></td>  </table> </div></center>       ';       
$box_wide->setVar("content_title", "Donation Shop");  
$box_wide->setVar("content", $cont2);      
print $box_wide->toString();      
if ($a_user[$db_translation['gm']]==$db_translation['az'])       
  {       
  $cont2= '
<center>      
<div class="sub-box1" align="left">                  
   <form action="" method="post">      
    <table  border="0" align="center" cellpadding="3"> <tr>       
	 <td>Item?:<br /></td>       
	 <td><select name="sep">         
	 <option value="0" selected="selected">Item</option>         
	 <option value="1">Seperator *</option>                 
	 </select></td>    
	 </tr>        
	 <tr>          
	 <td>Custom item? </td>         
	  <td><select name="custom">         
	  <option value="0" selected="selected">No</option>         
	  <option value="1">Yes</option>        
	  </select></td>   </tr>       
	  <tr>          
	  <td>Available on: </td>          
	  <td> <select name="realm1"> ';        $i=1;        while ($i<=count($realm))        {         
	  $cont2.='
	  <div id="fix66"><option value="'.$i.'" > '.$realm[$i]['name'].'</option>'; 
	  $i++;
	  $cont2.='</div>';          
	  }
	  $cont2.='<option value="0" > All realms</option></select>';
	  $cont2.='</td></tr> <tr>       
	  <td>Item ID:</td>       
	  <td><input name="itemid" type="text" /> 
	  <a href=\'#\' onClick=\'window.open("./pop-itemlookup.php","item","width=450,height=400,screenX=50,left=250,screenY=50,top=200,scrollbars=yes,status=no,menubar=no");return false\'><strong>[Search for item ID]</strong></a></td>        
	  </tr> <tr>       
	  <td>Item name:</td>       
	  <td><input name="name" type="text" /> *</td> 
	  </tr>     
	  <tr>       
	  <td>Item color:</td>       
	  <td><select name="color">         
	  <option value="0">Poor (gray)</option>         
	  <option value="1" selected="selected">Common (white)</option>         
	  <option value="2">Uncommon (green)</option>         
	  <option value="3">Rare (blue)</option>         
	  <option value="4">Epic (purple)</option>         
	  <option value="5">Legendary (orange)</option>        
	  </select>
	  </td>        
	  </tr>         
	  <tr>       
	  <td>Description:</td>       
	  <td><input name="description" type="text" /></td>        
	  </tr>        
	  <tr>       
	  <td>Cost Points:</td>       
	  <td><input name="points" type="text" value="1" /></td>        
	  </tr>        
	  <tr>       
	  <td>Item Stack:</td>       
	  <td><input name="charges" type="text" value="1" /><br />Default is 1 for one item.</td>        
	  </tr>        
	  <tr>       
	  <td>Cat Sort:</td>       
	  <td><input name="cat" type="text" value="0" />        
	  *  &laquo;<strong>X</strong>-x&raquo;</td>        
	  </tr>        
	  <tr>       
	  <td>Sort within Cat:</td>       
	  <td><input name="sort" type="text" value="0" />       
	  * &laquo;x-<strong>X</strong>&raquo;</td>        
	  </tr> </table>      
	  <center><br />      
	  If you select "Seperator" then only fields marked with an"*" are required.<br /><br />      
	  <div id="log-b2"><input name="additem" type="submit" value="Add Item" /></div>      
	  </center>            
	  </form>
</div>
</center>
'; 
$box_wide->setVar("content_title", "Admin tool to add an item:");  
$box_wide->setVar("content", $cont2);      
print $box_wide->toString();           
} /*end admin*/ 