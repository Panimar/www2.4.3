<?php
if (!defined('AXE'))
	exit;

$sl = new Template("styles/".$style."/nostyle_box.php");
		  
$content='
<table width="100%" border="0" cellpadding="0">
  <tr><td>
   <div class="slider">
       <div id="cu3er-container">
       <a href="http://www.adobe.com/go/getflashplayer">
        <img style="border:none;" src="http://www.adobe.com/images/shared/download_buttons/get_flash_player.gif" alt="." />
       </a>
       </div></div>
  </td></tr>
</table>';

$sl->setVar("content", $content);
$sl->setVar("imagepath", 'styles/'.$style.'/images_new/');
$slider=$sl->toString();

?>