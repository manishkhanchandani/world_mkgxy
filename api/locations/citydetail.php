<?php
include_once('../../config.php');
//http://world.mkgalaxy.com/api/citydetail.php?id=1&nearby=1
try {
	$g = new world_Geography();
	$id = !empty($_GET['id']) ? $_GET['id']: 0;
  if (empty($id)) {
    throw new Exception('empty city id');
  }
	$r = $g->cityDetail($id);
	if (empty($r)) {
		throw new Exception('Empty City List', 2001);
	}
  if (!empty($_GET['nearby']) && !empty($r[0])) {
    $lat = !empty($r[0]['latitude']) ? $r[0]['latitude']: null;
    $lon = !empty($r[0]['longitude']) ? $r[0]['longitude']: null;
    $radius = 30;
    $order = 'distance';
    $limit = 30;
    if (empty($lat) || empty($lon)) {
      $r[0]['nearby'] = array();
    } else {
      $r[0]['nearby'] = $g->get_nearby_cities($lat, $lon, $radius, $order, $limit);
    }
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