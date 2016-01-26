<?php
$box = !empty($_GET['bbox']) ? $_GET['bbox'] : '';
header('Content-type: application/json');
$url = 'http://sanfrancisco.crimespotting.org/crime-data?format=json&count=200&bbox='.$box;
$content = file_get_contents($url);

if (!empty($_GET['jsoncallback'])) {
	echo $_GET["jsoncallback"]."(".$content.")";
} else {
	echo $content;
}