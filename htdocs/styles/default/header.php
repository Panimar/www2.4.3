<?php exit; ?><!DOCTYPE HTML PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml"><head>
<meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
<link rel="shortcut icon" href="./favicon.ico">
<title>{title}</title>
<script type="text/javascript" src="res/javascript.js"></script>
<script type="text/javascript" src="res/power/power.js"></script>
<script type="text/javascript" src="shoutbox.js"></script>
<link href="res/power/power.css" rel="stylesheet" type="text/css">
{stylesheet}
<!--not used variable: [sitetitle] -->
<!--[if IE]>
<link href="res/style_ie.css" rel="stylesheet" type="text/css" />
<link href="res/power/power_ie.css" rel="stylesheet" type="text/css">
<![endif]-->
<script type="text/javascript" src="js/swfobject/swfobject.js"></script>
<script type="text/javascript">
  var flashvars = {};
  flashvars.xml = "config.xml";
  flashvars.font = "font.swf";
  var attributes = {};
  attributes.wmode = "transparent";
  attributes.id = "slider";
  swfobject.embedSWF("cu3er.swf", "cu3er-container", "523", "217", "9", "expressInstall.swf", flashvars, attributes);
</script>
<script type="text/javascript">

function getInternetExplorerVersion()
// Returns the version of Internet Explorer or a -1
// (indicating the use of another browser).
{
  var rv = -1; // Return value assumes failure.
  if (navigator.appName == 'Microsoft Internet Explorer')
  {
    var ua = navigator.userAgent;
    var re  = new RegExp("MSIE ([0-9]{1,}[\.0-9]{0,})");
    if (re.exec(ua) != null)
      rv = parseFloat( RegExp.$1 );
  }
  return rv;
}

function readCookie(name) {
	var nameEQ = name + "=";
	var ca = document.cookie.split(';');
	for(var i=0;i < ca.length;i++) {
		var c = ca[i];
		while (c.charAt(0)==' ') c = c.substring(1,c.length);
		if (c.indexOf(nameEQ) == 0) return c.substring(nameEQ.length,c.length);
	}
	return null;
}

function createCookie(name,value,min) {
	if (min) {
		var date = new Date();
		date.setTime(date.getTime()+(min*60*1000));
		var expires = "; expires="+date.toGMTString();
	}
	else var expires = "";
	document.cookie = name+"="+value+expires+"; path=/";
}


window.onload = function vote_popup() {

	// w00t w00t HAX!
	var ver = getInternetExplorerVersion();
	if(navigator.appName == 'Microsoft Internet Explorer' && ver < 7.0)
	{
	return;
	}

	voted = readCookie('voted');

	if (voted == null) {
		document.getElementById('vote_popup').style.display = "block";
	}

}

function hide_vote_popup() {
	createCookie('voted','yes','10');
	document.getElementById('vote_popup').style.display = "none";
	document.getElementById('vote_popup').innerHTML = "";
};
</script>


</head><body>

<div align="center">
<div id="container">

<div id="header">
 <table cellpadding="0" cellspacing="0">
  <tr>
     <td valign="top">
      <div class="logo"></div>
     </td>
     <td valign="top" >
      <div class="menu" align="right">
       <ul>
          <li>
          <a href="./">Главная</a>
          </li>
					  
		  <!-- if(link_guest) -->
          <li>
		  <a href="{linkpath}">{title}</a>
          </li>
		  <!-- else(link_guest) -->	
		  <!-- endif(link_guest) -->
				  
		  <!-- if(link_loggedin) -->
          <li>
		  <a href="{linkpath2}" >{title2}</a>
          </li>
		  <!-- else(link_loggedin) -->
		  <!-- endif(link_loggedin) -->
					  
		  <!-- if(link_custom) -->
          <li>
		  <a href="{linkpath}" >{title}</a>
          </li>
		  <!-- else(link_custom) -->
		  <!-- endif(link_custom) -->
			
		  <!-- if(link_custom2) -->
          <li>
		  <a href="{linkpath}" >{title}</a>
          </li>
		  <!-- else(link_custom2) -->
		  <!-- endif(link_custom2) -->
         <ul>
       </div>
     </td>
  </tr>
 </table>
</div>
<div style="height:192px;"></div>
<div id="content">
  <table class="content" cellpadding="0" cellspacing="0">
	<tbody><tr>
       <!--<td valign="top">
       <div class="slider">
       <div id="cu3er-container">
       <a href="http://www.adobe.com/go/getflashplayer">
        <img style="border:none;" src="http://www.adobe.com/images/shared/download_buttons/get_flash_player.gif" alt="." />
       </a>
       </div></div>
	   </td></tr>-->
    <tr>	