<?php
# FileName="Connection_php_mysql.htm"
# Type="MYSQL"
# HTTP="true"
$hostname_connMain = "remote-mysql3.servage.net";
$database_connMain = "mkgxy_main";
$username_connMain = "mkgxy_main";
$password_connMain = "manishkk74";
$connMain = mysql_pconnect($hostname_connMain, $username_connMain, $password_connMain) or trigger_error(mysql_error(),E_USER_ERROR);
mysql_select_db($database_connMain, $connMain) or die('could not select db');

//adodb try
define('SITE_DIR', dirname(dirname(dirname(dirname(dirname(__FILE__))))));
$site_path = SITE_DIR;//'/home135/sub004/sc29722-KLXJ';
include($site_path.'/adodb/adodb.inc.php');
$ADODB_CACHE_DIR = $site_path.'/ADODB_cache/world';
$connMainAdodb = ADONewConnection('mysql');
$connMainAdodb->Connect($hostname_connMain, $username_connMain, $password_connMain, $database_connMain);
//$connAdodb->LogSQL();
?>