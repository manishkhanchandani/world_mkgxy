<?php
if (empty($_GET['city_id']) && !empty($_GET['city'])) {
  header("Location: ".HTTPPATH."/locations/searchResult?q=".$_GET['city']);
  exit;
}
if (empty($_GET['city_id'])) {
  header("Location: ".HTTPPATH."/locations/country");
  exit;
}
$city_id = $_GET['city_id'];
$url = APIHTTPPATH.'/locations/citydetail.php?id='.$city_id ;
$cityList = curlget($url);
$cityList = json_decode($cityList, 1);
if (empty($cityList[0])) {
  header("Location: ".HTTPPATH."/locations/country");
  exit;
}

$url = HTTPPATH.'/city-'.url_name_v2($cityList[0]['city']).'-'.$cityList[0]['id'];
header("Location: ".$url);
exit;