<?php

$dir = dirname(__FILE__);
$host = $_SERVER['HTTP_HOST'];
$page = $_SERVER['PHP_SELF'];
$queryString = $_SERVER['QUERY_STRING'];
$dirpath = dirname($page);
include_once($dir.'/services.php');
?>