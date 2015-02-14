<?php
if (!defined('AXE'))
	exit;

require_once "Mail.php";


if ($to<>'')
{	
	$headers = array ('From' => $from,
	  'To' => $to,
	  'Subject' => $subject);
	$smtp = Mail::factory('smtp',
	  array ('host' => $smtp_h,
		'auth' => true,
		'username' => $smtp_u,
		'password' => $smtp_p));
	
	$mail = $smtp->send($to, $headers, $body);
	
	if (PEAR::isError($mail)) {
	  echo("<p>" . $mail->getMessage() . "</p>");
	  $smtpme="<font color='red'>Error: " . $mail->getMessage() . "</font><br><br><strong>The mail was not sent. To receive your password please contact an Administrator.</strong><br><br>";
	 } else {
	  $smtpme="The message was successfully sent. Please check your e-mail.<br>";
	 }
}
?>