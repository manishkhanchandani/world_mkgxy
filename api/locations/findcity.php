<?php
include_once('../../config.php');
//http://world.mkgalaxy.com/api/locations/findcity.php?q=sunnyvale
try {
	$g = new world_Geography();
	$q = !empty($_GET['q']) ? $_GET['q']: '';
	$r = $g->findcity($q);
	if (empty($r)) {
		throw new Exception('Empty City List', 2001);
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