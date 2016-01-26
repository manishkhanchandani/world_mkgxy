<?php
include_once('../../config.php');
//http://world.mkgalaxy.com/api/nearby.php?lat=37.338475&lon=-121.885794&radius=50&limit=100
try {
	$g = new world_Geography();
	$lat = !empty($_GET['lat']) ? $_GET['lat']: null;
	$lon = !empty($_GET['lon']) ? $_GET['lon']: null;
	$radius = !empty($_GET['radius']) ? $_GET['radius']: 30;
	$order = !empty($_GET['order']) ? $_GET['order']: 'distance';
	$limit = !empty($_GET['limit']) ? $_GET['limit']: 30;
	if (empty($lat) || empty($lon)) {
		throw new Exception('Empty lat or lon', 2002);
	}
	$r = $g->get_nearby_cities($lat, $lon, $radius, $order, $limit);
	if (empty($r)) {
		throw new Exception('Empty List', 2001);
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
