<?php
//adodb try
define('ROOT_DIR', dirname(dirname(dirname(dirname(dirname(dirname(dirname(__FILE__))))))));
define('DB_DIR', dirname(__FILE__));
define('SITE_DIR', dirname(dirname(__FILE__)));
$root_path = ROOT_DIR;//'/home135/sub004/sc29722-KLXJ';
$db_path = DB_DIR;
$site_path = SITE_DIR;
include($root_path.'/adodb/adodb.inc.php');
$ADODB_CACHE_DIR = $root_path.'/ADODB_cache';
$connMainAdodb = ADONewConnection('sqlite');
$connMainAdodb->Connect(realpath($db_path.'/help.sqlite'));
?>