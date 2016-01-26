<?php


if (!function_exists('curlget')) {
	function curlget($url, $post=0, $POSTFIELDS='') {
		$https = 0;
		if (substr($url, 0, 5) === 'https') {
			$https = 1;
		}

		$ch = curl_init();
		curl_setopt($ch, CURLOPT_URL, $url);  
		if (!empty($post)) {
			curl_setopt($ch, CURLOPT_POST, 1); 
			curl_setopt($ch, CURLOPT_POSTFIELDS,$POSTFIELDS);
		}

		curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
		curl_setopt($ch, CURLOPT_FOLLOWLOCATION, 1);
		curl_setopt($ch, CURLOPT_COOKIEFILE, COOKIE_FILE_PATH);
		curl_setopt($ch, CURLOPT_COOKIEJAR,COOKIE_FILE_PATH);
		if (!empty($https)) {
			curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
			curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 0);
		}

		$result = curl_exec($ch); 
		curl_close($ch);
		return $result;
	}
}


if (!function_exists('pr')) {
function pr($d){
	echo '<pre>';
	print_r($d);
	echo '</pre>';
}
}




function url_name_v2($name='')
{
	if (empty($name)) {
		return $name;
	}

	$patterns = array();
	$patterns[0] = "/\s+/";
	$patterns[1] = '/[^A-Za-z0-9]+/';
	$replacements = array();
	$replacements[0] = "-";
	$replacements[1] = '-';
	ksort($patterns);
	ksort($replacements);
	$output = preg_replace($patterns, $replacements, $name);
	$output = strtolower($output);
	return $output;
}//end list_name_url()


function findCity($city_id)
{
  $url = APIHTTPPATH.'/citydetail.php?nearby=1&id='.$city_id ;
  $cityList = curlget($url);
  $cityList = json_decode($cityList, 1);
  
  if (empty($cityList[0])) {
    header("Locations: ".HTTPPATH."/locations/country");
    exit;
  }
  $cityDetails = $cityList[0];
  $cityDetails['url'] = HTTPPATH.'/city-'.url_name_v2($cityDetails['city']).'-'.$cityDetails['id'];
  $cityDetails['pageTitle'] = $cityDetails['city'].', '.$cityDetails['statename'].', '.$cityDetails['countryname'];
  return $cityDetails;
}

function makecityurl($city_id, $city)
{
  return HTTPPATH.'/city-'.url_name_v2($city).'-'.$city_id;
}