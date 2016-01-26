<?php
# FileName="Connection_php_mysql.htm"
# Type="MYSQL"
# HTTP="true"
$hostname_conn = "remote-mysql3.servage.net";
$database_conn = "nov2013";
$username_conn = "nov2013";
$password_conn = "passwords123";
$conn = mysql_connect($hostname_conn, $username_conn, $password_conn) or trigger_error(mysql_error(),E_USER_ERROR);
mysql_select_db($database_conn, $conn);

//adodb try
$site_path = SITE_DIR;//'/home135/sub004/sc29722-KLXJ';
include($site_path.'/adodb/adodb.inc.php');
$ADODB_CACHE_DIR = $site_path.'/ADODB_cache/world';
$connAdodb = ADONewConnection('mysql');
$connAdodb->Connect($hostname_conn, $username_conn, $password_conn, $database_conn);
//$connAdodb->LogSQL();
?>