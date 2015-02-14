<?php
/***********************************************************************
	Copyright (C) AXE
************************************************************************/
// Make sure no one attempts to run this script "directly"
if (!defined('AXE'))
	exit;
// Load the appropriate DB layer class
require PATHROOT.'include/dblayer/mysql.php';
// Create the database adapter object (and open/connect to/select db)
$db = new DBLayer($db_host, $db_user, $db_pass, $db_name, $db_prefix, $p_connect);