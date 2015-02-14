<?php

/***********************************************
* DB query structure for Ascent/ArcEmu, by AXE
* 3.2.2010. american date
* All functions here must always exist
************************************************/


$db_translation = array(
		//
		// GM premisions translation (all that are used by this cms)
		//
		"az"						=> "3",
		"a"							=> "2",
		"gm_normalplayer"			=> "0", //gm premission for normal player
		//
		// Expansion data in sql
		//
		"expansion_normal"			=> "0",
		"expansion_tbc"				=> "1",
		"expansion_wotlk"			=> "2",
		"expansion_cata"			=> "3",
		//
		// Accounts Table
		//
		"accounts" 					=> "account",	 	//table 'accounts'
			"acct"					=> "id",
			"login"    				=> "username",   		
			"password" 				=> "",	 	//leave blank if doesnt exits
														//if exists  website will try update raw pass here 
			"encrypted_password"	=> "sha_pass_hash",
			"gm"					=> "gmlevel",
			"banned"				=> "locked",
			"lastip"				=> "last_ip",
			"lastlogin"				=> "last_login",
			"flags"					=> "expansion",
			"email"					=> "email", //for gimmepass.php
		//
		// Characters Table
		//
		"characters"				=> "characters",  //table 'characters'
			"characters_acct"		=> "account",
			"characters_guid"		=> "guid",
			"characters_name"		=> "name",
			"characters_honorPoints"=> "totalHonorPoints",
			"characters_killsLifeTime"=>"totalKills",
			"characters_online"		=> "online",
			"characters_level"		=> "level",
			"characters_class"		=> "class",
			"characters_race"		=> "race",
			"characters_gender"		=> "gender",
			"characters_gold"		=> "money",
		//
		// Item Table
		//
		"items"						=> "item_template",
			"items_name1"			=> "name",
			"items_quality"			=> "Quality",
			"items_entry"			=> "entry",
		//
		// Tickets
		//
		"gm_tickets"				=> "gm_tickets",
			"gm_tickets_guid"		=> "guid",
			"gm_tickets_playerGuid"	=> "playerGuid",
			"gm_tickets_timestamp" 	=> "timestamp",
			"gm_tickets_message" 	=> "message",
		
);


/***************************************
* 	 	  CHARACTER FUNCTIONS
***************************************/

function unstuck($guid)//returns
{
	global $db,$db_translation;
	$fail=false;

        //fetching character homebind
        $query_select = 'SELECT * FROM character_homebind WHERE guid = "'.$guid.'" LIMIT 1';
        $sql1=$db->query($query_select)or die($report = 'Error1: '.mysql_error());
		$sql2=$db->fetch_assoc($sql1);


	$query_update ='UPDATE characters SET position_x="'.$sql2['position_x'].'", position_y="'.$sql2['position_y'].'", position_z="'.$sql2['position_z'].'", map="'.$sql2['map'].'", zone="'.$sql2['zone'].'" WHERE guid="'.$sql2['guid'].'" LIMIT 1';
        $db->query($query_update) or ($fail=mysql_error());
		
        return $fail;
}

function teleport($guid,$x,$y,$z,$map,$newGold)//returns
{
	global $db,$db_translation;
	$fail=false;
	$query_update ='UPDATE '.$db_translation['characters'].' SET position_x="'.$x.'", position_y="'.$y.'", position_z="'.$z.'", map="'.$map.'", money="'.$newGold.'" WHERE guid="'.$guid.'"';
    $db->query($query_update) or ($fail=mysql_error());
        return $fail;
}


/***************************************
* 	 ACCOUNT MANAGEMENT FUNCTIONS
***************************************/

function create_account($user,$pass,$email)//gotta be logged in for this, returns
{
	global $db_translation,$db,$a_user;
	$pass = preg_replace( "/[^A-Za-z0-9]/", "", $pass ); //only letters and numbers
	$user = preg_replace( "/[^A-Za-z0-9]/", "", $user ); //only letters and numbers
	
	$pass_enc=sha1(strtoupper($user).':'.strtoupper($pass));
	$db->query("INSERT INTO ".$db_translation['accounts']." (".$db_translation['login'].",".$db_translation['encrypted_password'].",".$db_translation['banned'].",".$db_translation['email'].",".$db_translation['flags'].") VALUES ('".$user."','".$pass_enc."','0','".$db->escape($email)."','2')") or ($report = 'Error: '.mysql_error());
	if (!$report)
		return false;
	else
		return $report;
}

function passchange($newpass,$userid=false)//gotta be logged in for this, returns
{
	global $db_translation,$db,$a_user;
	$newpass = preg_replace( "/[^A-Za-z0-9]/", "", $newpass ); //only letters and numbers
	if ($userid==false)
	{
		$userid=$a_user[$db_translation['acct']];
	}
	//get user info
	$sql1=$db->query("SELECT * FROM  ".$db_translation['accounts']." WHERE ".$db_translation['acct']." = '".$userid."' LIMIT 1")or die($report = 'Error: '.mysql_error());
	$sql2=$db->fetch_assoc($sql1);
	
	$newpass_enc=sha1(strtoupper($sql2[$db_translation['login']]).':'.strtoupper($newpass));
	
	
	$db->query("UPDATE ".$db_translation['accounts']." SET ".$db_translation['encrypted_password']." = '".$newpass_enc."',sessionkey='',v='',s='' WHERE ".$db_translation['acct']."='".$sql2[$db_translation['acct']]."' LIMIT 1") or ($report = 'Error: '.mysql_error());
	
	if (!$report)
		return "Password is changed.";
	else
		return $report;
}
//
//execute this when user wants to login and user/pass variables are $_post-ed thru
//
function special_core_exec_onlogin($username)
{	
	return false;
}

//
// Unique function for trinity (exists only here for trinity)
//
function trinity_premissions()
{
	global $db,$acc_db,$a_user,$db_translation,$db_name;
	$end = false;
	//check in account's more
	$s1 = $db->query("SELECT * FROM ".$db_name.".accounts_more WHERE UPPER(acc_login)='".$db->escape(strtoupper($a_user[$db_translation['login']]))."'")or die(mysql_error());
	if (mysql_num_rows($s1)=='1')
	{
		$s2=$db->fetch_assoc($s1);
		if ($s2['gmlevel']<>'')
		{
			return $s2['gmlevel'];
			$end=true;
		}
		
	}
	
	if ($end==false)
	{
		//select premission  from account_access
		$sql1 = $db->query("SELECT * FROM ".$acc_db.".account_access WHERE RealmID='1' AND id='".$a_user[$db_translation['acct']]."'")or die(mysql_error());
		if (mysql_num_rows($sql1)=='1')
		{
			$sql2=$db->fetch_assoc($sql1);
			return $sql2['gmlevel'];
		}
		else
		{
			return $db_translation['gm_normalplayer'];
		}
	}
	
	
	
		
}

/***************************************
* 	    FILE SPECIFIC VARIABLES
***************************************/
//
//   /hk.php and /honor.php
//
$hk_where="banned='0' or banned='' and";



/***************************************
* 	    SERVER PATCHES
***************************************/
function patch_notice()//will be shown in admin panel
{
	global $db_translation,$a_user,$ra_user,$ra_pass,$db,$acc_db,$db_name;
	
	//=======================================
	// 	        REMOTE ACCESS PANEL
	//=======================================
	$output = "<script type='text/javascript'>function loadiframe(){var text = \"<iframe src='./include/core/trinity_ra_iframe_ratest.php' style='width:99%; height:130px;'>Your browser does not support iframes. <a href='./include/core/trinity_ra_iframe_ratest.php' target='_blank'>Click to test connection</a></iframe>\";
	document.getElementById('raiframe').innerHTML=text;}</script>
	
	
	<fieldset id='remote'><legend>Remote Access:</legend>
	<u><big>1) Enable RA in your Trinity world-server configuration.</big></u><br>
		<blockquote><fieldset style='border:solid 1px'><legend>Edit the worldserver.conf</legend>Ra.Enabled = <span class='colorgood'>1</span><br>
         Ra.IP = 0.0.0.0<br>
         Ra.Port = 3443<br>
         Ra.MinLevel = 3<br>
         Ra.Secure = 1</fieldset></blockquote><br>";
		 
	//add/edit config.php ra_user and ra_pass
	$output .= '<u><big>2) Add an Admin account info for remote access.</big></u><br>The account needs to be an Admin on all realms. RealmID: -1 in account_access';
	if (isset($_POST['mangos1']))
	{	
		//first check if password is valid
		if (sha1(strtoupper($a_user[$db_translation['login']]).':'.strtoupper($_POST['ra_pass']))==$a_user[$db_translation['encrypted_password']])
		{
			/**************READ AND UPDATE config.php***************/
			/*                                                     */
			$fail=false;
			$config_file_content = file_get_contents('./config.php') or ($fail= 'ERROR 1: Could not read file "config.php"');
			
			if ($fail)
			{$output .='<blockquote><fieldset style="border:solid 1px"><legend>Admin Username and Password (GM Level 3):</legend><center><span class="colorbad">'.$fail.'</span></fieldset>
			</blockquote><br>';
			}
			else
			{
				$config_file_content=str_replace('$ra_user="'.$ra_user.'"','$ra_user="'.strtoupper($a_user[$db_translation['login']]).'"',$config_file_content);
				$config_file_content=str_replace('$ra_pass="'.$ra_pass.'"','$ra_pass="'.str_replace("\"","",$_POST['ra_pass']).'"',$config_file_content);
				//$output .= pun_htmlspecialchars($config_file_content);
				//finally writte new config
				$fh = fopen('./config.php', 'w') or ($fail= 'ERROR 2: Could not writte file "config.php"');
				if ($fail)
				{
					$output .='<blockquote><fieldset style="border:solid 1px"><legend>Admin Username and Password (GM Level 3):</legend><center><span class="colorbad">'.$fail.'</span> <a href="./quest.php?name=admincp#remote">Click here to refresh this box.</a></fieldset>
			</blockquote><br>';
				}
				else
				{
					fwrite($fh, $config_file_content);
					fclose($fh);
					$output .='<blockquote><fieldset style="border:solid 1px"><legend>Admin Username and Password (GM Level 3):</legend><center><span class="colorgood">The config.php is now updated.</span> <a href="./quest.php?name=admincp&1#remote">Click here to refresh this box.</a></fieldset>
			</blockquote><br>';
				}
				
			}
			/*                                                     */
			/********************************************************/
		}
		else//pass not valid
		{
			/**************PRINT WRONG PASS*************************/
			/*                                                     */
			$output .='<blockquote><fieldset style="border:solid 1px"><legend>Admin Username and Password (GM Level 3):</legend><center><span class="colorbad">The password for this account is incorrect. <a href="./quest.php?name=admincp&2#remote">Click to try again.</a></span></fieldset>
			</blockquote><br>';
			/*                                                     */
			/********************************************************/
		}
		
	}
	else
	{
		$output .='
		<form action="./quest.php?name=admincp#remote" method="POST">
		<blockquote>
			<fieldset style="border:solid 1px"><legend>Admin Username and Password (GM Level 3):</legend>
		
		<table width="100%" border="0">
	  <tr>
		<td><i><strong>User:</strong></i> '.strtoupper($a_user[$db_translation['login']]).' (CAPS on)</td>
		<td>[stored: \''.$ra_user.'\']</td>
	  </tr>
	  <tr>
		<td><i><strong>Pass:</strong></i> <input name="ra_pass" type="password" value="" /></td>
		<td>[stored: \''.$ra_pass[0];$i=2;while ($i<=strlen($ra_pass)){$output .='*';$i++;}
		
		$output .='\']</td>
	  </tr>
	</table><center>
	<div id="log-b2"><input name="mangos1" type="submit" value="Update" /></div>
	</center>
			</fieldset>
		</blockquote></form><br>';
	}
		 
	$output .= "<u><big>3) Test Connection</big></u><br><noscript><div style='border: solid 1px red; padding: 3px' class='colorbad'>Please enable javascript in order for this test to work.</div></noscript><blockquote><fieldset style='border:solid 1px'><legend><a onclick='javascript:loadiframe(); return false' href='#'>Click here to start the connection test. (Wait a few moments for the site to load.)</a></legend><center><div id='raiframe'></div></center></fieldset></blockquote></fieldset>
		";
		
	//=======================================
	// 	        FAILED MAIL REPORTS
	//=======================================
	
	$output.="<br /><fieldset id='failedmail'><legend>Failed mail logs:</legend>
	
	";
	if (isset($_POST['failedmail']))//return points and delete
	{
		//delete filename and return points
		$db->select_db($db_name);
		//-get user points atm
		
		if($_POST['dorv']=='1')//dp
		{
			mysql_query("UPDATE accounts_more SET dp='".$_POST['numpoints_total_dp']."' WHERE acc_login='".$db->escape($_POST['username'])."' LIMIT 1")or die(mysql_error());
			
		}
		elseif($_POST['dorv']=='0')//vp
		{	
			mysql_query("UPDATE accounts_more SET vp='".$_POST['numpoints_total_vp']."' WHERE acc_login='".$db->escape($_POST['username'])."' LIMIT 1")or die(mysql_error());;
		}
	
		
		unlink("./include/core/trinity_ra_iframe_mailcheck_log/".$_POST['filename']) or ($unlink=false);
		$output.="<br><br><center><a href='./quest.php?name=admincp&ok#failedmail'>The report has been deleted and the points have been returned, click here to refresh this box.</a></center><br><br>";
		
	}
	elseif (isset($_POST['failedmail2']))//just delete report
	{
		
	
		
		unlink("./include/core/trinity_ra_iframe_mailcheck_log/".$_POST['filename']) or ($unlink=false);
		$output.="<br><br><center><a href='./quest.php?name=admincp&ok#failedmail'>The report has been deleted, click here to refresh this box.</a></center><br><br>";
		
	}
	else
	{
		$handle4 = opendir("./include/core/trinity_ra_iframe_mailcheck_log/");
		$output.="Mail sometimes can't be sent due to the server being busy, and console commands via web PHP not being 100% reliable. This script will report errors from both the vote and donation shops. If there is any item and or point loss, it will be listed in this report:<br>";
		# Making an array containing the files in the current directory:
		while ($file4 = readdir($handle4))
		{
			$files4[] = $file4;
		}
		closedir($handle4);
			$output.="<br><table width=\"98%\" border=\"0\" cellspacing='0'>
					  <tr>
						<td><strong>Username</strong><br><br></td>
						<td><strong>Time</strong><br><br></td>
						<td><strong>Action</strong><br><br></td>
					  </tr>";
		#echo the files
		foreach ($files4 as $file4) {
	
			if ($file4<>'..' && $file4<>'.')
			{
				  $f4= str_replace(".txt", "", $file4);
				  //$file4 -> userid_timestamp
				  $f4=explode("_",$f4);
				  $file4_content = file_get_contents("./include/core/trinity_ra_iframe_mailcheck_log/".$f4[0]."_".$f4[1].".txt") or ($report_error='ERROR 1: The log file cannot be read.');
				  $file4_content=explode("|",$file4_content);
				  //get username and other infro from id
				  $db->select_db($acc_db);
				  $fsql=$db->query("SELECT * FROM ".$db_translation['accounts']." WHERE ".$db_translation['acct']."='".$f4[0]."' LIMIT 1")or($report_error=mysql_error());
				  $fsql2=mysql_fetch_assoc($fsql);
				  $output.=$report_error; $report_error=false;
				  //get item info
				  $db->select_db($db_name);
				  $fsql_it=$db->query("SELECT * FROM shop WHERE id='". $file4_content[1]."' LIMIT 1")or die($report_error=mysql_error());
					$output.=$report_error; $report_error=false;
				  $fsql2_it=mysql_fetch_assoc($fsql_it);
				  $output.="[itemid: ".$fsql2_it['itemid']."]";
				  
				  
				  $output.= "<tr>
						<td>".$fsql2[$db_translation['login']]."<hr></td>
						<td>".date("j M'y@g:ia",$f4[1])." ";
					 $output.='<span class="headed" onmouseover="$WowheadPower.showTooltip(event, \''.$fsql2_it['name'].'<br>';
					 if ($fsql2_it['donateorvote']=='1')  $output.="<small>Donation item</small>";
					 else if($fsql2_it['donateorvote']=='0') $output.="<small>Vote item</small>";
					 $output.='<br><small>Points to return: '.$fsql2_it['cost'].'</small>\')" onmousemove="$WowheadPower.moveTooltip(event)" onmouseout="$WowheadPower.hideTooltip();">[itemid: '.$fsql2_it['itemid'].']</span>';	
					 
					 //get accounts_more
					 $fsql_account=$db->query("SELECT dp,vp FROM accounts_more WHERE acc_login='". $fsql2[$db_translation['login']]."' LIMIT 1")or die($report_error=mysql_error());
					 $fsql_account2=mysql_fetch_assoc($fsql_account);
					 
					 
					$output.="<hr></td>
						<td>".'<center><form action="./quest.php?name=admincp#failedmail" method="POST">
						
<input name="dorv" type="hidden" value="'.$fsql2_it['donateorvote'].'" />
<input name="numpoints_total_vp" type="hidden" value="';
if ($fsql2_it['donateorvote']=='0') $output.= ($fsql_account2['vp']+$fsql2_it['cost']);
else	$output.="0";
$output.='" />
<input name="numpoints_total_dp" type="hidden" value="';
if ($fsql2_it['donateorvote']=='1') $output.= ($fsql_account2['dp']+$fsql2_it['cost']);
else	$output.="0";
$output.='" />
<input name="username" type="hidden" value="'.$fsql2[$db_translation['login']].'" />
<input name="filename" type="hidden" value="'.$f4[0].'_'.$f4[1].'.txt" />

<input name="failedmail" type="submit" value="Return pt & del" /> <input name="failedmail2" type="submit" value="Just del" /></form></center></center>'."</td>
					  </tr>";
				  
				 
					  
					  
						
					
	
			}
		
		} 
		$output.="</table>";
	}//end _POST
	
	$output.="</fieldset>";
	
	
	return $output;
}
function patch_include($patchname,$output=false)
{
	global $server_core;
	//inside /htdocs/include/core/ folder
	//include files named $server_core_$patchname.php
	if (file_exists(PATHROOT.'include/core/'.$server_core.'_'.$patchname.'.php'))
	{
		if ($output)
			echo "File ".'<b>'.PATHROOT.'include/core/'.$server_core.'_'.$patchname.'.php</b>'. " is loaded.";
		require_once(PATHROOT.'include/core/'.$server_core.'_'.$patchname.'.php');
	}
	else 
	{
		if ($output)
			echo "File ".'<b>'.PATHROOT.'include/core/'.$server_core.'_'.$patchname.'.php</b>'. " is not loaded.";
		return false;
	}
}