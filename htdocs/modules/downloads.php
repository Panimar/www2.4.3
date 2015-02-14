<?php


if (!defined('AXE'))
	exit;
	//common include
$box_simple_wide = new Template("styles/".$style."/box_simple_wide.php");
$box_wide = new Template("styles/".$style."/box_wide.php");
$box_wide->setVar("imagepath", 'styles/'.$style.'/images/');
$box_simple_wide->setVar("imagepath", 'styles/'.$style.'/images/');
//end common include

$cont2='<strong>Clients</strong><br />';
$folder4 = "downloads/Clients/";
$handle4 = opendir($folder4);

# Making an array containing the files in the current directory:
while ($file4 = readdir($handle4))
{
    $files4[] = $file4;
}
closedir($handle4);

#echo the files
foreach ($files4 as $file4) {
    
    if ($file4=='..' || $file4=='.')
    {}
    else
    {
      $filename4= str_replace("-", " ", $file4);
      $filenamea4= str_replace(".rar", "", $filename4);
      $filenameaa4= str_replace(".zip", "", $filenamea4);
      $filenameaaa4= str_replace(".exe", "", $filenameaa4);
      $cont2.= "<a href='./downloads/Clients/".$file4."'>- ".$filenameaaa4."</a><br />";}
} 
$cont2.='
<strong>Patches</strong><br />
';
$folder = "downloads/Patches/";
$handle = opendir($folder);

# Making an array containing the files in the current directory:
while ($file = readdir($handle))
{
    $files[] = $file;
}
closedir($handle);

#echo the files
foreach ($files as $file) {
    
    if ($file=='..' || $file=='.')
    {}
    else
    {
      $filename= str_replace("-", " ", $file);
      $filenamea= str_replace(".exe", "", $filename);
      $cont2.= "<a href='./downloads/Patches/".$file."'>- ".$filenamea."</a><br />";}
} 
$cont2.='
<strong>Addons</strong><br />
';
$folder2 = "downloads/addons/";
$handle2 = opendir($folder2);

# Making an array containing the files in the current directory:
while ($file2 = readdir($handle2))
{
    $files2[] = $file2;
}
closedir($handle2);

#echo the files
foreach ($files2 as $file2) {
    
    if ($file2=='..' || $file2=='.')
    {}
    else
    {
      $filename2= str_replace("-", " ", $file2);
      $filenamea2= str_replace(".rar", "", $filename2);
      $filenameaa2= str_replace(".zip", "", $filenamea2);
      $cont2.= "<a href='./downloads/addons/".$file2."'>- ".$filenameaa2."</a><br />";}
} 
$cont2.='
<strong>Tools</strong><br />
';
$folder3 = "downloads/Tools/";
$handle3 = opendir($folder3);

# Making an array containing the files in the current directory:
while ($file3 = readdir($handle3))
{
    $files3[] = $file3;
}
closedir($handle3);

#echo the files
foreach ($files3 as $file3) {
    
    if ($file3=='..' || $file3=='.')
    {}
    else
    {
      $filename3= str_replace("-", " ", $file3);
      $filenamea3= str_replace(".rar", "", $filename3);
      $filenameaa3= str_replace(".zip", "", $filenamea3);
      $cont2.= "<a href='./downloads/Tools/".$file3."'>- ".$filenameaa3."</a><br />";}
} 
if (!$a_user['is_guest'] && $a_user[$db_translation['gm']]<>'0')
{
$cont2.='
<strong>GM Tools</strong><br />
';
$folder5 = "downloads/GM-Tools/";
$handle5 = opendir($folder5);

# Making an array containing the files in the current directory:
while ($file5 = readdir($handle5))
{
    $files5[] = $file5;
}
closedir($handle5);

#echo the files
foreach ($files5 as $file5) {
    
    if ($file5=='..' || $file5=='.')
    {}
    else
    {
      $filename5= str_replace("-", " ", $file5);
      $filenamea5= str_replace(".rar", "", $filename5);
      $filenameaa5= str_replace(".zip", "", $filenamea5);
      $filenameaaa5= str_replace(".exe", "", $filenameaa5);
      $cont2.= "<a href='./downloads/GM-Tools/".$file5."'>- ".$filenameaaa5."</a><br />";}
} 
}
$box_wide->setVar("content_title", "Downloads");	
$box_wide->setVar("content", $cont2);					
print $box_wide->toString();