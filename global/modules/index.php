<?php
if (empty($_GET['city_id'])) {
  header("Locations: ".HTTPPATH."/locations/country");
  exit;
}
$city_id = $_GET['city_id'];
/*
$url = 'http://world.mkgalaxy.com/api/citydetail.php?nearby=1&id='.$city_id ;
$cityList = curlget($url);
$cityList = json_decode($cityList, 1);

if (empty($cityList[0])) {
  header("Locations: ".HTTPPATH."/locations/country");
  exit;
}
$globalCity = $cityList[0];
*/
$globalCity = findCity($city_id);
$pageTitle = $globalCity['pageTitle'];
$currentURL = $globalCity['url'];
//building navigation item
$str = '';
$str .= '<div>';
$str .= '<b>City: </b>'.$globalCity['city'].'<br />';
$str .= '<b>State: </b>'.$globalCity['statename'].'<br />';
$str .= '<b>Country: </b>'.$globalCity['countryname'].'<br />';
$str .= '<b>Latitude: </b>'.$globalCity['latitude'].'<br />';
$str .= '<b>Longitude: </b>'.$globalCity['longitude'].'<br />';
$str .= '<br /></div>';
$pageDynamicNavigationItem = $str;

//nearby
if (!empty($globalCity['nearby'])) {
$str = '';
foreach ($globalCity['nearby'] as $nearby) {
    $url = HTTPPATH.'/city-'.url_name_v2($nearby['name']).'-'.$nearby['cty_id'];
    $str .= '<a href="'.$url.'">'.$nearby['name'].'</a> ('.$nearby['distance'].' mi)<br />';
}
$pageDynamicNearby = $str;
}
?>
<script type="text/javascript" src="<?php echo HTTPPATH.'/scripts/map.js'; ?>"></script>
<script type="text/javascript" src="https://www.google.com/jsapi?key=ABQIAAAALUsWUxJrv3zXUNCu0Kas1RQFv3AXA4OcITNh-zHKPaxsGpzj0xQrVCwfLY_kBbxK-4-gSU4j3c7huQ"></script>
<script type="text/javascript" src="http://maps.google.com/maps/api/js?sensor=false"></script>
<div style="padding:5px;"><a href="<?php echo $currentURL; ?>?view=road">Road Map</a> | <a href="<?php echo $currentURL; ?>?view=street">Street View</a></div>
		<?php if (!empty($_GET['view']) && $_GET['view'] === 'street') { ?>
		<div id="mapCanvascitydetailstreetview" style="width:100%; height:100%; min-width:300px; min-height:300px"></div>
		<?php } else { ?>
		<div id="mapCanvas" style="width:100%; height:100%; min-width:300px; min-height:300px"></div>
		<?php } ?>
<script type="text/javascript">
// initialize the google Maps
var latitude = '<?php echo $globalCity['latitude']; ?>';
var longitude = '<?php echo $globalCity['longitude']; ?>';
<?php if (!empty($_GET['view']) && $_GET['view'] === 'street') { ?>
initializeGoogleStreetMap('mapCanvascitydetailstreetview', latitude, longitude);
<?php } else { ?>
initializeGoogleMap('mapCanvas');
<?php } ?>
</script>