<?php
$latlngA = isset($_GET['latlngA']) ? $_GET['latlngA'] : '';
if (!empty($latlngA)) {
	$a = explode(',', $latlngA);
	$lata = $a[0];
	$lona = $a[1];
}
$q = isset($_GET['q']) ? $_GET['q'] : '';
?>
<!doctype html>
<html>
<head>
<meta name="viewport" content="width=device-width, initial-scale=1"> 
<meta charset="UTF-8">
<title><?php echo $latlngA; ?></title>
<script src="https://maps.googleapis.com/maps/api/js?v=3.exp&key=AIzaSyBvXqWIcqyTVRgjXsVjDbdORcNaXHVjtOw&sensor=true&libraries=places"></script>
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
  var pyrmont = new google.maps.LatLng(<?php echo $lata; ?>,<?php echo $lona; ?>);

  map = new google.maps.Map(document.getElementById('map-canvas'), {
    center: pyrmont,
    zoom: 15
  });

  var request = {
    location: pyrmont,
    radius: 500,
    types: ['<?php echo $q; ?>']
  };
  infowindow = new google.maps.InfoWindow();
  var service = new google.maps.places.PlacesService(map);
  service.nearbySearch(request, callback);
}

function callback(results, status) {
  if (status == google.maps.places.PlacesServiceStatus.OK) {
    for (var i = 0; i < results.length; i++) {
      createMarker(results[i]);
    }
  }
}

function createMarker(place) {
	  console.log(place);
  var placeLoc = place.geometry.location;
  var marker = new google.maps.Marker({
    map: map,
    position: place.geometry.location
  });

  google.maps.event.addListener(marker, 'click', function() {
    var address = '';
    if (place.address_components) {
      address = [
        (place.address_components[0] && place.address_components[0].short_name || ''),
        (place.address_components[1] && place.address_components[1].short_name || ''),
        (place.address_components[2] && place.address_components[2].short_name || '')
      ].join(' ');
    }

    infowindow.setContent('<div><strong>' + place.name + '</strong><br>' + place.vicinity);
    infowindow.open(map, this);
  });
}

google.maps.event.addDomListener(window, 'load', initialize);
</script>
</head>

<body>
<div id="map-canvas"></div>
</body>
</html>