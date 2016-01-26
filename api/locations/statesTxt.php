<?php
include_once('../../config.php');
//http://world.mkgalaxy.com/api/states.php?id=223
try {
$g = new world_Geography();
$country_id = !empty($_GET['dg_key']) ? $_GET['dg_key']: 0;
$r = $g->stateList($country_id);
if (empty($r)) {
	throw new Exception('Empty State List', 2002);
}
	$return = array();
	$fieldSeparater = ' | ';
	foreach ($r as $k => $v) {
		$return[] = $v['state'].';'.$v['id'];
	}
	echo implode($fieldSeparater, $return);
	exit;
} catch (Exception $e) {
	echo $e->getMessage();
}
?>