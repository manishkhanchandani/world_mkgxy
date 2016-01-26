<!doctype html>
<html>
<head>
<meta charset="UTF-8">
<title>Map</title>
<script language="javascript">
var qs = (function(a) {
    if (a == "") return {};
    var b = {};
    for (var i = 0; i < a.length; ++i)
    {
        var p=a[i].split('=');
        if (p.length != 2) continue;
        b[p[0]] = decodeURIComponent(p[1].replace(/\+/g, " "));
    }
    return b;
})(window.location.search.substr(1).split('&'));
var lat = qs["lat"];
var lon = qs["lon"];
</script>
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
	var myLatlng = new google.maps.LatLng(lat,lon);
  var mapOptions = {
    zoom: 14,
    center: myLatlng,
    panControl: true,
    zoomControl: true,
    scaleControl: true
  }
  var image = '../images/map-icon-a.png';
  var map = new google.maps.Map(document.getElementById('map-canvas'),
                                mapOptions);
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