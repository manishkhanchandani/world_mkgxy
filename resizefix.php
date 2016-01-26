<?php
if (!isset($_GET['img'])) {
	echo 'img is missing';
	exit;
}
if (!isset($_GET['percent'])) {
	echo 'percent is missing';
	exit;
}
// The file
$filename = $_GET['img'];
$percent = $_GET['percent']; //0.5; // percentage of resize


// Get new dimensions
$size = getimagesize($filename);
$ratio = $size[0]/$size[1]; // width/height
$width = $size[0];
$height = $size[1];
if( $ratio > 1) {
    $new_width = $percent;
    $new_height = $percent/$ratio;
}
else {
    $new_width = $percent*$ratio;
    $new_height = $percent;
}
// Resample
$image_p = imagecreatetruecolor($new_width, $new_height);
$image = imagecreatefromjpeg($filename);
imagecopyresampled($image_p, $image, 0, 0, 0, 0, $new_width, $new_height, $width, $height);

// Output
// Content type
header('Content-type: image/jpeg');
imagejpeg($image_p, null, 100);
?>