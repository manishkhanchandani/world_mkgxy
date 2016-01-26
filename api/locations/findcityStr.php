<?php
include_once('../../config.php');
//http://world.mkgalaxy.com/api/findcityStr.php?q=sunnyvale
try {
	$g = new world_Geography();
	$q = !empty($_GET['q']) ? $_GET['q']: '';
	$r = $g->findcity($q);
	if (empty($r)) {
		throw new Exception('Empty City List', 2001);
	}
  foreach ($r as $k => $v) {
    $city = $v['city'].', '.$v['statename'].', '.$v['countryname'];
    echo $v['id']."|".$city."\n";
  }
} catch (Exception $e) {
  echo $e->getMessage();
	exit;
}