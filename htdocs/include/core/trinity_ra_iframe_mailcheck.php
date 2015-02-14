<?php
/*******************************************
* ra_test.php is part of WebWoW CMS
* by AXE
********************************************/
define('PATHROOT', '../../');
include PATHROOT."include/common.php";
?><html><head><title>Checking...</title></head><body style="background-color:#333333; color:#FFFFFF; font-family:Arial, Helvetica, sans-serif; font-size:12px"><?php

if ($a_user['is_guest']==false)
{
	if (isset($_GET['error']))
	{
	echo "<font color='red'>The item was not sent.</font> Maybe the Trinity console is currently busy or the server is offline. A report has been sent to an Administrator, however your points are not restored. An Administrator will have to investigate the matter and refund your points or item(s). Sorry for any inconvenience caused.<br><br>Please wait at least 1 minute until making a next purchase.</body></html>";exit;}
	
	$numbers=array(
1 => 'c4ca4238a0b923820dcc509a6f75849b',
2 => 'c81e728d9d4c2f636f067f89cc14862c',
3 => 'eccbc87e4b5ce2fe28308fd9f2a7baf3',
4 => 'a87ff679a2f3e71d9181a67b7542122c',
5 => 'e4da3b7fbbce2345d7772b0674a318d5',
6 => '1679091c5a880faf6fb5e6087eb1b2dc',
7 => '8f14e45fceea167a5a36dedd4bea2543',
8 => 'c9f0f895fb98ab9159f51fd0297e236d',
9 => '45c48cce2e2d7fbdea1afc51c7c6ad26',
10 => 'd3d9446802a44259755d38e6d163e820');

	patch_include("sendmail",false);
	$rec = preg_replace( "/[^0-9]/", "", $_GET['reciver'] ); //only letters and numbers
	$realmid = preg_replace( "/[^0-9]/", "", $_GET['realmid'] ); //only letters and numbers
	$shopid = preg_replace( "/[^0-9]/", "", $_GET['shopid'] ); //only letters and numbers
	$subj = preg_replace( "/[^A-Za-z0-9]/", "", $_GET['subject'] ); //only letters and numbers
	
	//check for hacks
	if (sha1($a_user['id'].$rec.$subj.$se_c.$shopid)<>$_GET['shash'])
	{
	 	echo "Hack attempt detected!";exit;
	}
	//end hacks
	
	$a=sendmail_confirm($rec,$subj,$realmid);
	if ($a=="Checking for mail in DB... <font color='lime'>Item is successfully sent!</font>")//DO NOT CHANGE THIS STRING
	{
		echo $a;
	}
	else
	{
		if (!$_GET['checktime']) $_GET['checktime']="c4ca4238a0b923820dcc509a6f75849b";
		echo $a;
		$checktime=$_GET['checktime'];//md5
		//
		$i=0;
		while ($i<=10)
		{
			
			if ($checktime==$numbers[$i])
			{
				$checktime2=$i; 
			}
			$i++;
		}
		
		
		//
		if ($checktime2>=10)
		{
			//add to trinity_ra_iframe_mailcheck_log.php
			//
			//
			$myFile = "./trinity_ra_iframe_mailcheck_log/".$a_user['id'].'_'.date("U").".txt";
			$fh = fopen($myFile, 'w') or die("Writing to the log file has failed.");
			//characterguid|shopid
			$stringData = $rec."|".$shopid;
			fwrite($fh, $stringData);
			fclose($fh);
			
			echo '<meta http-equiv="refresh" content="0;url=./trinity_ra_iframe_mailcheck.php?error" />';
			
	
			exit;
			//
			//
			//
		}
		
	?><br>
<?php echo $checktime2;?>/10 Rechecking in 3 seconds...<meta http-equiv="refresh" content="3;url=./trinity_ra_iframe_mailcheck.php?shopid=<?php echo $shopid;?>&reciver=<?php echo $rec; ?>&subject=<?php echo $subj; ?>&realmid=<?php echo $realmid; ?>&checktime=<?php echo md5($checktime2+1); ?>&shash=<?php echo $_GET['shash'];?>" />
	
	<?php
	}
}
else
{
	echo "This file is protected.";
}
?></body></html>