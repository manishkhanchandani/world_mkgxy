<?php
$lat = isset($_GET['lat']) ? $_GET['lat'] : -33;
$lon = isset($_GET['lon']) ? $_GET['lon'] : 151;
?>
<!doctype html>
<html>
<head>
<meta name="viewport" content="width=device-width, initial-scale=1"> 
<meta charset="UTF-8">
<title><?php echo $lat; ?>,<?php echo $lon; ?></title>
<script src="https://maps.googleapis.com/maps/api/js?v=3.exp&key=AIzaSyBvXqWIcqyTVRgjXsVjDbdORcNaXHVjtOw&sensor=true"></script>
<style>
      html, body, #map-canvas {
        height: 100%;
        margin: 0px;
        padding: 0px
      }
    </style>
<script language="javascript">
function initialize() {
	var myLatlng = new google.maps.LatLng(<?php echo $lat; ?>,<?php echo $lon; ?>);
  var mapOptions = {
    zoom: 14,
    center: myLatlng,
    panControl: true,
    zoomControl: true,
    scaleControl: true
  }
  var map = new google.maps.Map(document.getElementById('map-canvas'),
                                mapOptions);
	var marker = new google.maps.Marker({
      position: myLatlng,
      map: map,
	  draggable:true,
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