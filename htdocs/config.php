<?php

######################################################
# Website Title
######################################################
$title="WoWWnet";

######################################################
# Domain url
#  - With http:// and without trailling slash "/" at end
######################################################
$domain_url="http://127.0.0.1";

######################################################
# Server Address
#  - Used for online/offline status
#  - PHP fsockopen() must be enabled on your web server
######################################################
$server = "127.0.0.1";

######################################################
# SQL Connection
######################################################
$db_user = "root";
$db_pass = "ascent" ;
$db_host = "127.0.0.1";

######################################################
# Website Database Name
#  - For this CMS
######################################################
$db_name = "webwow";

######################################################
# World Database Name
#  - Not so important, helps admin to search item IDs.
######################################################
$item_db ="mangos";

######################################################
# Accounts Database Name
#  - Where all accounts are stored
######################################################
$acc_db ="realmd";

######################################################
# Realm Configuration
#  - You can have unlimited amount of realms
#  - Example: (X represents number 1 -> infinity)
#
#  //Server no.X
#  $realm[X] = array(
#  "name" => "Realm Name",
#  "port" => "3306",
#  "port_ra" => "3443",
#  "db"   => "realm_database"
#  );
######################################################
//Realm no.1
$realm[1] = array(
"name" => "WoWWnet X1",
"port" => "8085",
"port_ra" => "3443",
"db" => "cha"
);

//Realm no.2
$realm[2] = array(
"name" => "Paradise x100",
"port" => "8086",
"port_ra" => "3443",
"db" => "characters"
);
######################################################
# Core Settings (ascent,mangos,trinity or trinity_ra)
######################################################
$server_core ="mangos";

######################################################
# SMTP Settings
######################################################
$smtp_h = "";
$smtp_u = "";
$smtp_p = "";

######################################################
# Vote Settings
#  - You can have unlimited amount of vote urls
#  - Images for vote are stored in:
#  - htdocs/styles/<yourstyle>/images/<votenumber>.jpg
#  Example with 3 vote url's:
#
#  $voteurls = array(
#  1 => "http://vote_url_1",
#  2 => "http://vote_url_2",
#  3 => "http://vote_url_3"
#  );"
######################################################
$voteurls = array(
1 => "http://wow.mmotop.ru/vote/434645/",
);

######################################################
# Style
#  - Located in: htdocs/styles/<style>/
######################################################
$style='default';

######################################################
# Use ToS (1 or 0)
######################################################
$usetos = 1;

######################################################
# Other
######################################################
$module_teleporter_gold="0";


/***************************
***   DONT EDIT BELOW    ***
***************************/
$se_c = "baf9abece3482c2236e47291d35ac07e";
$db_type = "mysql";
$db_prefix = "a_";
$p_connect = false;

$cookie_name = "webwowcms_cookie";
$cookie_domain = "";
$cookie_path = "/";
$cookie_secure = 0;

//autogenerated MaNGOS,Trinity RA
$ra_user="koss";//admin account
$ra_pass="10291986";//admin password


define('AXE', 1);
?>