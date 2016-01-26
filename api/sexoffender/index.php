<?php require_once('../../Connections/connMain.php'); ?>
<?php
//$sql = sprintf(“select city_id, url, city, state, country, lat, lon, (ROUND(DEGREES(ACOS(SIN(RADIANS(“.GetSQLValueString($lat, ‘double’).”)) * SIN(RADIANS(c.lat)) + COS(RADIANS(“.GetSQLValueString($lat, ‘double’).”)) * COS(RADIANS(c.lat)) * COS(RADIANS(“.GetSQLValueString($lon, ‘double’).” -(c.lon)))))*60*1.1515,2)) as distance from c_cities as c WHERE (ROUND(DEGREES(ACOS(SIN(RADIANS(“.GetSQLValueString($lat, ‘double’).”)) * SIN(RADIANS(c.lat)) + COS(RADIANS(“.GetSQLValueString($lat, ‘double’).”)) * COS(RADIANS(c.lat)) * COS(RADIANS(“.GetSQLValueString($lon, ‘double’).” -(c.lon)))))*60*1.1515,2)) <= “.GetSQLValueString($radius, ‘int’).” ORDER BY “.$order.” LIMIT “.$limit);


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

try {
$lat = isset($_GET['lat']) ? $_GET['lat'] : 0;
$lon = isset($_GET['lon']) ? $_GET['lon'] : 0;
$radius = isset($_GET['r']) ? $_GET['r'] : 2;

mysql_select_db($database_connMain, $connMain);
$query_rsView = sprintf("SELECT *, (ROUND(DEGREES(ACOS(SIN(RADIANS(%s)) * SIN(RADIANS(c.lat)) + COS(RADIANS(%s)) * COS(RADIANS(c.lat)) * COS(RADIANS(%s -(c.lon)))))*60*1.1515,2)) as distance FROM sexoffender as c WHERE (ROUND(DEGREES(ACOS(SIN(RADIANS(%s)) * SIN(RADIANS(c.lat)) + COS(RADIANS(%s)) * COS(RADIANS(c.lat)) * COS(RADIANS(%s -(c.lon)))))*60*1.1515,2)) <= %s", GetSQLValueString($lat, 'double'), GetSQLValueString($lat, 'double'), GetSQLValueString($lon, 'double'), GetSQLValueString($lat, 'double'), GetSQLValueString($lat, 'double'), GetSQLValueString($lon, 'double'), GetSQLValueString($radius, 'int'));
$rsView = mysql_query($query_rsView, $connMain) or die(mysql_error());
$row_rsView = mysql_fetch_assoc($rsView);
$totalRows_rsView = mysql_num_rows($rsView);

$return = array();
 if ($totalRows_rsView > 0) { // Show if recordset not empty 
	do {
		$x = $row_rsView;
		unset($x['raw']);
		foreach ($x as $k => $v) {
			if ($k == 'crime') {
				$v = str_replace('</li>', '</li>. ', $v);
				$x[$k] = strip_tags($v);
			} else {
				$x[$k] = strip_tags($v);
			}
		}
		$return[] = $x;
	} while ($row_rsView = mysql_fetch_assoc($rsView));
} // Show if recordset not empty

mysql_free_result($rsView);
	$return = array('success' => 1, 'msg' => '', 'data' => $return);
} catch (Exception $e) {
	$return = array('success' => 0, 'msg' => $e->getMessage(), 'data' => array());
}
$content = json_encode($return);
if (!empty($_GET['jsoncallback'])) {
	echo $_GET["jsoncallback"]."(".$content.")";
} else {
	echo $content;
}
exit;
?>