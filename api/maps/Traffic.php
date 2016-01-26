<?php
$latlngA = isset($_GET['latlngA']) ? $_GET['latlngA'] : '';
if (!empty($latlngA)) {
	$a = explode(',', $latlngA);
	$lata = $a[0];
	$lona = $a[1];
}
?>
<!doctype html>
<html>
<head>
<meta name="viewport" content="width=device-width, initial-scale=1"> 
<meta charset="UTF-8">
<title><?php echo $latlngA; ?></title>
<script src="https://maps.googleapis.com/maps/api/js?v=3.exp&key=AIzaSyBvXqWIcqyTVRgjXsVjDbdORcNaXHVjtOw&sensor=true"></script>
<style>
      html, body, #map-canvas {
        height: 100%;
        margin: 0px;
        padding: 0px
      }
    </style>
<script language="javascript">
	var map;
var infowindow;

function initialize() {
var myLatlng = new google.maps.LatLng(<?php echo $lata; ?>,<?php echo $lona; ?>);
  var mapOptions = {
    zoom: 17,
    center: myLatlng
  }

  var map = new google.maps.Map(document.getElementById('map-canvas'), mapOptions);

  var trafficLayer = new google.maps.TrafficLayer();
  trafficLayer.setMap(map);
}

google.maps.event.addDomListener(window, 'load', initialize);
</script>
</head>

<body>
<div id="map-canvas"></div>
</body>
</html>