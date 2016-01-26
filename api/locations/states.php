<?php
include_once('../../config.php');
//http://world.mkgalaxy.com/api/states.php?id=223
try {
$g = new world_Geography();
$country_id = !empty($_GET['id']) ? $_GET['id']: 0;
$r = $g->stateList($country_id);
if (empty($r)) {
	throw new Exception('Empty State List', 2002);
}
} catch (Exception $e) {
	$r = array('success' => 0, 'error' => 1, 'msg' => $e->getMessage(), 'code' => $e->getCode());
}
//echo json_encode($r);
$content = json_encode($r);
if (!empty($_GET['jsoncallback'])) {
	echo $_GET["jsoncallback"]."(".$content.")";
} else {
	echo $content;
}
?>