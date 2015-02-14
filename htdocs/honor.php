<?php
require_once("header.php"); 
$box_simple_wide = new Template("styles/".$style."/box_simple_wide.php");
$box_wide = new Template("styles/".$style."/box_wide.php");
$box_simple_wide->setVar("imagepath", 'styles/'.$style.'/images/');
$box_wide->setVar("imagepath", 'styles/'.$style.'/images/');
?><td id="page2">
<?php
$cont1='<center>';

$realmid = preg_replace( "/[^0-9]/", "", $_GET['realm'] ); //only numbers
if ($realmid=='')
	$realmid='1';
$i=1;
while ($i<=count($realm))
{
	$cont1.=  '&nbsp;&nbsp;<a href="./hk.php?realm='.$i.'">'.$realm[$i]['name'].'</a>&nbsp;&nbsp;';
	if ($realmid==$i)
	{
    	$db->select_db($realm[$i]['db']);$rname=$realm[$i]['name'];
	}

	$i++;
}

$cont1.='</center>';
$box_simple_wide->setVar("content", $cont1);
print $box_simple_wide->toString();


    //(c) AXE
	 $cont2_title='<center>Honor Statistics for '.$rname.' Realm</center>';
	 
	$cont2.= "<font size='2' face='Arial, Helvetica, sans-serif' color='white'>";
	$cont2.= "<table width='100%' border='0' cellspacing='0' cellpadding='0'>";
	$cont2.= "<tr height='30' valign='top'><td></td><td>Honor</td><td><u><a href='./hk.php?realm=".$realmid."'>HK's</a></u></td><td>Character</td><td>Level</td><td></td><td></td></tr>";


	$a=0;
	if ($server_core=='trinity' or $server_core=='trinity_ra')
	{
	
			$SQLawow ="SELECT * from ".$db_translation['characters']." where ".$db_translation['characters_honorPoints']."<>'0' order by ".$db_translation['characters_honorPoints']." DESC"; 
			
			// no banned and no 0 kills
			$char=mysql_query($SQLawow) or error('Something is wrong with "characters" table in logon database. <br><br></strong>MySQL reported:<strong> '.mysql_error().'.<br><br></strong>Suggestion:<strong> Please open "config.php" file, find variable $realm['.$realmid.'], and enter correct database name', __FILE__, __LINE__); 
			while ($char2=mysql_fetch_array($char) ){
			 if ($a<=19)
					 {     
					 		mysql_select_db($acc_db);
							$SQLwow='SELECT gmlevel, locked, ac.id, username FROM account ac LEFT JOIN account_access aa ON aa.id = ac.id LEFT JOIN account_banned ab ON ab.id = ac.id WHERE ac.id="'.$char2[$db_translation['characters_acct']].'" GROUP BY username';
//                          die($SQLwow);
                            $SQLwow2=mysql_query($SQLwow) or error('Something is wrong with "accounts" table in logon database. <br><br></strong>MySQL reported:<strong> '.mysql_error().'<br><br>'.$SQLwow, __FILE__, __LINE__);
							$SQLwow3=mysql_fetch_array($SQLwow2);
							
							if (($SQLwow3[0]=="0" or $SQLwow3[0]=="") && ($SQLwow3[1]=="0" or $SQLwow3[1]=="")) { //no gm's and banned accs
							$a=$a+1;
							if ($char2[race]=="1" || $char2[race]=="3" || $char2[race]=="4" || $char2[race]=="7" || $char2[race]=="11")
	                        { $side="0"; } else {$side="1";}
							if ($char2[$db_translation['characters_online']]=="1") {$onl="lime";} else {$onl="white";}
					        $cont2.= "<tr><td align='center'>$a. ";
							$cont2.= "<td align='center' style='font-size:14px'><strong>".$char2[$db_translation['characters_honorPoints']]."</strong> </td>";
							$cont2.= "<td align='center' >".$char2[$db_translation['characters_killsLifeTime']]." </td>";
							$cont2.= "<td align='center'><font color='$onl'>".$char2[$db_translation['characters_name']]."</font></td>";
							$cont2.= "<td align='center'>lvl <strong>".$char2[$db_translation['characters_level']]."</strong></td>";
							$cont2.= "<td align='center'><img src='images/icon/class/".$char2[$db_translation['characters_class']].".gif' title='Class' />&nbsp;<img src='images/icon/race/".$char2[$db_translation['characters_race']]."-".$char2[$db_translation['characters_gender']].".gif'  title='Race' /></td>";
							if ($SQLwow3[3]=='')
							{
								$SQLwow3[3]= '<span clas="colorbad"><i>bugged</i></span>';
							}
							$cont2.= "<td></td>";
							$cont2.= "</tr>";
					 }}}
	
	}
	else
	{	$SQLawow ="SELECT * from ".$db_translation['characters']." where ".$hk_where." ".$db_translation['characters_honorPoints']."<>'0' order by ".$db_translation['characters_killsLifeTime']." DESC"; 
			
			// no banned and no 0 kills
			$char=mysql_query($SQLawow) or error('Something is wrong with "characters" table in logon database. <br><br></strong>MySQL reported:<strong> '.mysql_error().'.<br><br></strong>Suggestion:<strong> Please open "config.php" file, find variable $realm['.$realmid.'], and enter correct database name', __FILE__, __LINE__); 
			while ($char2=mysql_fetch_array($char) ){
			 if ($a<=19)
					 {     
					 		mysql_select_db($acc_db);
					        $SQLwow ="SELECT ".$db_translation['gm'].",".$db_translation['banned'].",".$db_translation['acct'].",".$db_translation['login']." from ".$db_translation['accounts']." where ".$db_translation['acct']."='".$char2[$db_translation['characters_acct']]."'";
	                        $SQLwow2=mysql_query($SQLwow) or error('Something is wrong with "accounts" table in logon database. <br><br></strong>MySQL reported:<strong> '.mysql_error().'.<br><br></strong>Suggestion:<strong> Please open "config.php" file, find variable $acc_db, and enter correct database name', __FILE__, __LINE__); 
							$SQLwow3=mysql_fetch_array($SQLwow2);
							
							if (($SQLwow3[0]=="0" or $SQLwow3[0]=="") && ($SQLwow3[1]=="0" or $SQLwow3[1]=="")) { //no gm's and banned accs
							$a=$a+1;
							if ($char2[race]=="1" || $char2[race]=="3" || $char2[race]=="4" || $char2[race]=="7" || $char2[race]=="11")
	                        { $side="0"; } else {$side="1";}
							if ($char2[$db_translation['characters_online']]=="1") {$onl="lime";} else {$onl="white";}
					        $cont2.= "<tr><td align='center'>$a. ";
							$cont2.= "<td align='center' >".$char2[$db_translation['characters_honorPoints']]." </td>";
							$cont2.= "<td align='center' style='font-size:14px'><strong>".$char2[$db_translation['characters_killsLifeTime']]."</strong> </td>";
							$cont2.= "<td align='center'><font color='$onl'>".$char2[$db_translation['characters_name']]."</font></td>";
							$cont2.= "<td align='center'>lvl <strong>".$char2[$db_translation['characters_level']]."</strong></td>";
							$cont2.= "<td align='center'><img src='images/icon/class/".$char2[$db_translation['characters_class']].".gif' title='Class' />&nbsp;<img src='images/icon/race/".$char2[$db_translation['characters_race']]."-".$char2[$db_translation['characters_gender']].".gif'  title='Race' /></td>";
							if ($SQLwow3[3]=='')
							{
								$SQLwow3[3]= '<span clas="colorbad"><i>bugged</i></span>';
							}
							$cont2.= "<td></td>";
							$cont2.= "</tr>";
					 }}}
	}
	
	$cont2.= "</table>";
	$cont2.= "</center>";


$box_wide->setVar("content_title", $cont2_title); 
$box_wide->setVar("content", $cont2);
print $box_wide->toString();
	?></td><?php
	
$tpl_footer = new Template("styles/".$style."/footer.php");
$tpl_footer->setVar("imagepath", 'styles/'.$style.'/images/');
print $tpl_footer->toString();
?>