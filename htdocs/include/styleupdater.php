<?php
set_time_limit(0);
error_reporting(E_ALL ^ E_NOTICE);
include "../config.php";
?><!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
<title>Style fixer tool</title>
</head>

<body style="font-family:Arial, Helvetica, sans-serif">
<p><strong>Manual update instructions:</strong></p>
<p>1) Open your style folder, rename all files inside from *.html to *.php except index.html <br />
  2) Edit each renamed files and add this text to begining of the file: <input type="text" name="textfield" value="&lt;?php exit; ?&gt;" />
    <br />
    <br />
    <br />
  <br />
  <strong>Trying automatic update...:</strong><br />
<br />
  <?php
if (!function_exists('fopen'))
{
	echo "The fopen function is disabled on your web server. The script can not update.";
	exit;
}
if (!function_exists('fread'))
{
	echo "The fread function is disabled on your web server. The script can not update.";
	exit;
}
if (!function_exists('rename'))
{
	echo "The rename function is disabled on your web server. The script can not update.";
	exit;
}
if (!function_exists('fwrite'))
{
	echo "The fwrite function is disabled on your web server. The script can not update.";
	exit;
}
if (!file_exists('../styles/'.$style.'/header.html'))
{
	echo "It appears your style is already updated.";
	exit;
}
echo "<font color=gray>Your style folder is on: ". substr(base_convert(fileperms("../styles/".$style."/"), 10, 8), 3). ' chmod premissions, file inside is on: '. substr(base_convert(fileperms("../styles/".$style."/header.html"), 10, 8), 3) .' chmod premission.</font><br><br>';

function fixfile($filename)
{
	$fd = fopen($filename.'.html', "r") or die("<br><br>Cannot open the ".$filename." file.html. I guess it is already updated.");
	$content = fread($fd, filesize($filename.'.html'));
	$content_fixed="<?php exit; ?>".$content;
	fclose($fd);
	
	$fh = fopen($filename.'.html', 'w') or die("<br><br>Cannot open the ".$filename." file.html. If there is a .php version of this file, please delete this one.");
	fwrite($fh, $content_fixed);
	fclose($fh);
	echo $filename.".html: New content has been written.<br>";
	
	//rename
	rename($filename.'.html', $filename.'.php');
	echo $filename.".html: The file has been renamed to *.php<br>";
	//unlink($filename.'.html') or ($alreadydeleted=true);
	echo "<br><br>";

}
fixfile("../styles/".$style."/header");//without extension
fixfile("../styles/".$style."/box_short");//without extension
fixfile("../styles/".$style."/box_simple_short");//without extension
fixfile("../styles/".$style."/box_simple_wide");//without extension
fixfile("../styles/".$style."/box_wide");//without extension
fixfile("../styles/".$style."/footer");//without extension
fixfile("../styles/".$style."/index_body");//without extension
fixfile("../styles/".$style."/news");//without extension
fixfile("../styles/".$style."/sidebar");//without extension


//create index.html file
$fh = fopen('../styles/'.$style.'/index.html', 'w') or die("Error writing the index.html, not so important, you may discard this error message.");
	fwrite($fh, "<html><head><title>404 - Not found</title><meta http-equiv=\"refresh\" content=\"0;url=../../\" /></head><body></body></html>");
	fclose($fh);
$fh = fopen('../styles/'.$style.'/images/index.html', 'w') or die("Error writting index.html, not so important you can discard this error message.");
	fwrite($fh, "<html><head><title>404 - Not found</title><meta http-equiv=\"refresh\" content=\"0;url=../../\" /></head><body></body></html>");
	fclose($fh);

echo "<font color=green>SUCCESS! ALL DONE!</font>";
?>
  <br />
  <br />
  <a href="../">Click here to go back to the website.</a></p>
</body>
</html>