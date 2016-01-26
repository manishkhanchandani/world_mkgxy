<?php
$latlngA = isset($_GET['latlngA']) ? $_GET['latlngA'] : '';
if (!empty($latlngA)) {
	$a = explode(',', $latlngA);
	$lata = $a[0];
	$lona = $a[1];
}
$latlngB = isset($_GET['latlngB']) ? $_GET['latlngB'] : '';
if (!empty($latlngB)) {
	$b = explode(',', $latlngB);
	$latb = $b[0];
	$lonb = $b[1];
}
?>
<!doctype html>
<html>
<head>
<meta name="viewport" content="width=device-width, initial-scale=1"> 
<meta charset="UTF-8">
<title><?php echo $latlngA; ?>|<?php echo $latlngB; ?></title>
<script src="https://maps.googleapis.com/maps/api/js?v=3.exp&key=AIzaSyBvXqWIcqyTVRgjXsVjDbdORcNaXHVjtOw&sensor=true"></script>
<style>
      html, body, #map-canvas {
        height: 100%;
        margin: 0px;
        padding: 0px
      }
    </style>
<script language="javascript">
	var directionsDisplay;
	var directionsService = new google.maps.DirectionsService();
function initialize() {
	directionsDisplay = new google.maps.DirectionsRenderer();

	var myLatlnga = new google.maps.LatLng(<?php echo $lata; ?>,<?php echo $lona; ?>);
	var myLatlngb = new google.maps.LatLng(<?php echo $latb; ?>,<?php echo $lonb; ?>);
  var mapOptions = {
    zoom: 14,
    center: myLatlnga,
    panControl: true,
    zoomControl: true,
    scaleControl: true
  }
  
  var imagea = '../images/map-icon-a.png';
  var imageb = '../images/map-icon-b.png';
  var map = new google.maps.Map(document.getElementById('map-canvas'),
                                mapOptions);
	directionsDisplay.setMap(map);
  calcRoute(myLatlnga, myLatlngb);
}
function calcRoute(start, end) {
  var request = {
      origin:start,
      destination:end,
      travelMode: google.maps.TravelMode.WALKING
  };
  directionsService.route(request, function(response, status) {
    if (status == google.maps.DirectionsStatus.OK) {
      directionsDisplay.setDirections(response);
    }
  });
}
google.maps.event.addDomListener(window, 'load', initialize);
</script>
</head>

<body>
<div id="map-canvas"></div>
</body>
</html>