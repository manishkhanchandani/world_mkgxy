<?php
include_once('../../config.php');
//http://world.mkgalaxy.com/api/cities.php?id=3469
try {
	$g = new world_Geography();
	$id = !empty($_GET['dg_key']) ? $_GET['dg_key']: 0;
	$r = $g->cityList($id);
	if (empty($r)) {
		throw new Exception('Empty City List', 2001);
	}
	$return = array();
	$fieldSeparater = ' | ';
	foreach ($r as $k => $v) {
		$return[] = $v['city'].';'.$v['id'];
	}
	echo implode($fieldSeparater, $return);
	exit;
} catch (Exception $e) {
	echo $e->getMessage();
}
?>