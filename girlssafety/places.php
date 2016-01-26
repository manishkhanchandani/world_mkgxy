<?php
header('Content-type: application/json');
$input = '';
if (isset($_GET['input'])) {
	$input = md5($_GET['input']);
	if (file_exists('cache/'.$input.'.txt')) {
		$content = file_get_contents('cache/'.$input.'.txt');
	}
}
if (empty($content)) {
	$url = 'https://maps.googleapis.com/maps/api/place/autocomplete/json?'.$_SERVER['QUERY_STRING'];
	$content = file_get_contents($url);
	file_put_contents('cache/'.$input.'.txt', $content);
}
if (!empty($_GET['jsoncallback'])) {
	echo $_GET["jsoncallback"]."(".$content.")";
} else {
	echo $content;
}