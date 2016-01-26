<?php require_once('../Connections/connMain.php'); ?>
<?php
if (!function_exists("GetSQLValueString")) {
function GetSQLValueString($theValue, $theType, $theDefinedValue = "", $theNotDefinedValue = "") 
{
  if (PHP_VERSION < 6) {
    $theValue = get_magic_quotes_gpc() ? stripslashes($theValue) : $theValue;
  }

  $theValue = function_exists("mysql_real_escape_string") ? mysql_real_escape_string($theValue) : mysql_escape_string($theValue);

  switch ($theType) {
    case "text":
      $theValue = ($theValue != "") ? "'" . $theValue . "'" : "NULL";
      break;    
    case "long":
    case "int":
      $theValue = ($theValue != "") ? intval($theValue) : "NULL";
      break;
    case "double":
      $theValue = ($theValue != "") ? doubleval($theValue) : "NULL";
      break;
    case "date":
      $theValue = ($theValue != "") ? "'" . $theValue . "'" : "NULL";
      break;
    case "defined":
      $theValue = ($theValue != "") ? $theDefinedValue : $theNotDefinedValue;
      break;
  }
  return $theValue;
}
}

$lat = !empty($_GET['lat']) ? $_GET['lat'] : 37.671197;
$lon = !empty($_GET['lon']) ? $_GET['lon'] : -122.086137;


$maxRows_rsView = 100;
$pageNum_rsView = 0;
if (isset($_GET['pageNum_rsView'])) {
  $pageNum_rsView = $_GET['pageNum_rsView'];
}
$startRow_rsView = $pageNum_rsView * $maxRows_rsView;

mysql_select_db($database_connMain, $connMain);
$query_rsView = "SELECT * FROM ac_current_location LEFT JOIN google_auth ON google_auth.uid = ac_current_location.uid";
$query_limit_rsView = sprintf("%s LIMIT %d, %d", $query_rsView, $startRow_rsView, $maxRows_rsView);
$rsView = mysql_query($query_limit_rsView, $connMain) or die(mysql_error());
$row_rsView = mysql_fetch_assoc($rsView);

if (isset($_GET['totalRows_rsView'])) {
  $totalRows_rsView = $_GET['totalRows_rsView'];
} else {
  $all_rsView = mysql_query($query_rsView);
  $totalRows_rsView = mysql_num_rows($all_rsView);
}
$totalPages_rsView = ceil($totalRows_rsView/$maxRows_rsView)-1;

$counter = 0;
?>
<!DOCTYPE html>
<html>
  <head>
    <meta charset="utf-8">
    <title>Overlays within Street View</title>
    <style>
      html, body, #map-canvas {
        height: 100%;
        margin: 0px;
        padding: 0px
      }
      #panel {
        position: absolute;
        top: 5px;
        left: 50%;
        margin-left: -180px;
        z-index: 5;
        background-color: #fff;
        padding: 5px;
        border: 1px solid #999;
      }
    </style>
    <script src="https://maps.googleapis.com/maps/api/js?v=3.exp&sensor=false"></script>
    <script>
var map;
var panorama;
var places = [];
var markers = [];
var infowindow = null;

var center = new google.maps.LatLng(<?php echo $lat; ?>, <?php echo $lon; ?>);
function initialize() {

  // Set up the map
  var mapOptions = {
    center: center,
    zoom: 17,
    panControl: true,
    zoomControl: true,
    scaleControl: true,
    streetViewControl: true
  };
  map = new google.maps.Map(document.getElementById('map-canvas'),
      mapOptions);

	<?php if ($totalRows_rsView > 0) { // Show if recordset not empty ?>
		<?php do { ?>
		places[<?php echo $counter; ?>] = new google.maps.LatLng(<?php echo $row_rsView['latitude']; ?>, <?php echo $row_rsView['longitude']; ?>);
		var marker = new google.maps.Marker({
      position: places[<?php echo $counter; ?>],
      map: map,
      icon: 'http://world.mkgalaxy.com/resizefix.php?percent=100&img=<?php echo $row_rsView['picture']; ?>'
  });
  		content = "<h3><?php echo $row_rsView['latitude']; ?>, <?php echo $row_rsView['longitude']; ?></h3>";
						content = content + "<b>Name: </b> <?php echo $row_rsView['name']; ?>";
						content = content + "<br>";
						content = content + "<b>Gender: </b> <?php echo $row_rsView['gender']; ?>";
			bindInfoWindow(marker, map, infowindow, content);
			markers.push(marker)
		<?php $counter++; ?>
			<?php } while ($row_rsView = mysql_fetch_assoc($rsView)); ?>
	<?php } // Show if recordset not empty ?>
	for (var i = 0; i < markers.length; i++) {
		markers[i].setMap(map);
	  }
  // We get the map's default panorama and set up some defaults.
  // Note that we don't yet set it visible.
  panorama = map.getStreetView();
  panorama.setPosition(center);
  panorama.setPov(/** @type {google.maps.StreetViewPov} */({
    heading: 265,
    pitch: 0
  }));
  toggleStreetView();
}

function toggleStreetView() {
  var toggle = panorama.getVisible();
  if (toggle == false) {
    panorama.setVisible(true);
  } else {
    panorama.setVisible(false);
  }
}
function bindInfoWindow(marker, map, infowindow, strDescription) {
    google.maps.event.addListener(marker, 'click', function() {
        infowindow.setContent(strDescription);
        infowindow.open(map, marker);
    });
}
google.maps.event.addDomListener(window, 'load', initialize);

    </script>
  </head>
  <body>
    <!--<div id="panel" style="margin-left:-100px">
      <input type="button" value="Toggle Street View" onclick="toggleStreetView();"></input>
    </div>-->
  <div id="map-canvas"></div>
  </body>
</html>
<?php
mysql_free_result($rsView);
?>
