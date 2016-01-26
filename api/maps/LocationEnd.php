<?php
$latlngA = isset($_GET['latlngA']) ? $_GET['latlngA'] : '';
if (!empty($latlngA)) {
	$a = explode(',', $latlngA);
	$lata = $a[0];
	$lonb = $a[1];
}
$lat = isset($_GET['lat']) ? $_GET['lat'] : -33;
$lon = isset($_GET['lon']) ? $_GET['lon'] : 151;
//AIzaSyBvXqWIcqyTVRgjXsVjDbdORcNaXHVjtOw
?>
<!doctype html>
<html>
<head>
<meta name="viewport" content="width=device-width, initial-scale=1"> 
<meta charset="UTF-8">
<title><?php echo $lat; ?>,<?php echo $lon; ?></title>
<script src="https://maps.googleapis.com/maps/api/js?v=3.exp&sensor=true"></script>
<style>
      html, body, #map-canvas {
        height: 100%;
        margin: 0px;
        padding: 0px
      }
    </style>
<script language="javascript">
function initialize() {
	var myLatlnga = new google.maps.LatLng(<?php echo $lata; ?>,<?php echo $lonb; ?>);
	var myLatlng = new google.maps.LatLng(<?php echo $lat; ?>,<?php echo $lon; ?>);
  var mapOptions = {
    zoom: 14,
    center: myLatlnga,
    panControl: true,
    zoomControl: true,
    scaleControl: true
  }
  var imagea = '../images/map-icon-a.png';
  var image = '../images/map-icon-b.png';
  var map = new google.maps.Map(document.getElementById('map-canvas'),
                                mapOptions);
	var markera = new google.maps.Marker({
      position: myLatlnga,
      map: map,
	  icon: imagea
  });
	var marker = new google.maps.Marker({
      position: myLatlng,
      map: map,
	  draggable:true,
	  icon: image
  });
  
  google.maps.event.addListener(marker, 'dragend', function() {
    // 3 seconds after the center of the map has changed, pan back to the
    // marker.
	document.title = marker.getPosition().lat() + "," + marker.getPosition().lng();
  });
}

google.maps.event.addDomListener(window, 'load', initialize);
</script>
</head>

<body>
<div id="map-canvas"></div>
</body>
</html>