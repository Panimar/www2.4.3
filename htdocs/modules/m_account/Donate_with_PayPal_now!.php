<?php if (!defined('AXE'))  exit; require("config_paypal.php"); if ($a_user['is_guest'])  {  print "You will be granted access to donate form only if you login, thank you."; $tpl_footer = new Template("styles/".$style."/footer.php");  $tpl_footer->setVar("imagepath", 'styles/'.$style.'/images/');  print $tpl_footer->toString();  exit; }  /*common include*/ $box_simple_wide = new Template("styles/".$style."/box_simple_wide.php"); $box_wide = new Template("styles/".$style."/box_wide.php"); $box_wide->setVar("imagepath", 'styles/'.$style.'/images/'); $box_simple_wide->setVar("imagepath", 'styles/'.$style.'/images/'); 
/*end common include*/ 
if (!$a_user['is_guest'])  { 

$day= date('d');
$timethen=date('Y-m-d', strtotime('-'.$day.' day +1 day'));
$timewhen=date('Y-m-d', strtotime('+1 month -'.$day.' day '));

$savingsgoal = "250";


$timethen=$timethen=strtotime($timethen);;
$amount="0";
	$query1=mysql_query("SELECT * FROM paypal_data ORDER BY whendon DESC") or die(mysql_error());
	while ($q=mysql_fetch_array($query1) ){
	if ($q['whendon']>=$timethen)
	{$amount= $amount + $q['amount'];}
	}
$savingspct  = round(($amount / $savingsgoal)*100);
if ($amount>=$savingsgoal) {$savingspct="100";}
$cont2.='<center>


      <div class="sub-box1" align="left">
	  
	  
	  <center><b>DONATION GOAL</b></center>
	      <div class="prog-border" ><center>'.$amount.'$ / '.$savingsgoal.'$</center>
   <div class="progress" style="width:'.$savingspct.'%;" align="center">
    
   </div>
 </div><br />
	  
	  
	  
	  
	  
	  <form action="https://'.$paypalurl.'/cgi-bin/webscr" method="post" target="paypal">     
      <input type="hidden" name="cmd" value="_xclick">        
      <input type="hidden" name="business" value="'.$paypalemail.'">        
	  <input type="hidden" name="item_name" value="Donation Points">        
	  <input type="hidden" name="item_number" value="'.$a_user[$db_translation['acct']].'">        
	  <input type="hidden" name="amount" value="1.00">            
	  &nbsp; How many donation points would you like to purchase? <br/>
	  <input type="input" name="quantity" value="1" />       
	  <input type="hidden" name="currency_code" value="USD">     
	  <input type="hidden" name="notify_url" value="'.$domain_url.'/postback.php" />      
	  <div id="log-b2"><input type="submit" name="submit" value="Donate" /></div> 
	  </form><br/>
	  1 point costs 1€. </div> </center>'; 
	  
	  
$box_wide->setVar("content_title", "Donate with PayPal!");  
$box_wide->setVar("content", $cont2);      
print $box_wide->toString();   } else {  print "You are not logged in.";
$tpl_footer = new Template("styles/".$style."/footer.php");  
$tpl_footer->setVar("imagepath", 'styles/'.$style.'/images/');  print $tpl_footer->toString();  exit; }  
?>