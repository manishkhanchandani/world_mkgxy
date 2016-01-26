<?php
include_once('../../config.php');
//http://world.mkgalaxy.com/api/nearbycities.php?lat=37.338475&lon=-121.885794&radius=50&limit=100
try {
	$g = new world_Geography();
	$lat = !empty($_GET['lat']) ? $_GET['lat']: 0;
	$lon = !empty($_GET['lon']) ? $_GET['lon']: 0;
	$radius = !empty($_GET['radius']) ? $_GET['radius']: 30;
	$limit = !empty($_GET['limit']) ? $_GET['limit']: 30;
  if (empty($lat) || empty($lon)) {
    throw new Exception('empty lat or lon');
  }
	$r = $g->get_nearby_cities($lat, $lon, $radius, $order='distance', $limit);
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