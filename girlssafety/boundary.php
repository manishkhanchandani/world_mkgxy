<?php
function bar_get_nearby( $lat, $lng, $distance = 50, $unit = 'mi' ) {
// radius of earth; @note: the earth is not perfectly spherical, but this is considered the 'mean radius'
if( $unit == 'km' ) { $radius = 6371.009; }
elseif ( $unit == 'mi' ) { $radius = 3958.761; }

// latitude boundaries
$maxLat = ( float ) $lat + rad2deg( $distance / $radius );
$minLat = ( float ) $lat - rad2deg( $distance / $radius );

// longitude boundaries (longitude gets smaller when latitude increases)
$maxLng = ( ( float ) $lng + rad2deg( $distance / $radius) ) /  cos( deg2rad( ( float ) $lat ) );
$minLng = ( ( float ) $lng - rad2deg( $distance / $radius) ) /  cos( deg2rad( ( float ) $lat ) );

$max_min_values = array(
'lat' => $lat,
'lng' => $lng,
'distance' => $distance,
'units' => $units,
'max_latitude' => $maxLat,
'min_latitude' => $minLat,
'max_longitude' => $maxLng,
'min_longitude' => $minLng,
'point1' => array('lat' => $minLat, 'lng' => $minLng);,
'point2' => array('lat' => $minLat, 'lng' => $maxLng);,
'point3' => array('lat' => $maxLat, 'lng' => $maxLng);,
'point4' => array('lat' => $maxLat, 'lng' => $minLng);
);

return $max_min_values;
}
$lat = 37.33847;
$lng = -121.885794;
$boundary = bar_get_nearby( $lat, $lng, 10, 'mi' );
echo '<pre>';
print_r($boundary);
echo '</pre>';
?>