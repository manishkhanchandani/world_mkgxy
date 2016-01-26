<?php
include_once('../../config.php');
//http://world.mkgalaxy.com/api/countries.php
try {
$g = new world_Geography();
$r = $g->countryList();
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