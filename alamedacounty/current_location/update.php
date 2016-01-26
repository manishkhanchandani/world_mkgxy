<?php require_once('../../Connections/connMain.php'); ?>
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
try {
$editFormAction = $_SERVER['PHP_SELF'];
if (isset($_SERVER['QUERY_STRING'])) {
  $editFormAction .= "?" . htmlentities($_SERVER['QUERY_STRING']);
}
$return = array('success' => 1, 'msg' => '');
if (isset($_GET['latitude']) && isset($_GET['longitude']) && isset($_GET['uid'])) {
	$accuracy = isset($_GET['accuracy']) ? $_GET['accuracy'] : '';
	$altitude = isset($_GET['altitude']) ? $_GET['altitude'] : '';
	$address = isset($_GET['address']) ? $_GET['address'] : '';
  $insertSQL = sprintf("REPLACE ac_current_location set `uid`=%s, latitude=%s, longitude=%s, accuracy=%s, altitude=%s, address=%s",
                       GetSQLValueString($_GET['uid'], "text"),
                       GetSQLValueString($_GET['latitude'], "double"),
                       GetSQLValueString($_GET['longitude'], "double"),
                       GetSQLValueString($_GET['accuracy'], "text"),
                       GetSQLValueString($_GET['altitude'], "text"),
                       GetSQLValueString($_GET['address'], "text"));

  mysql_select_db($database_connMain, $connMain);
  $Result1 = @mysql_query($insertSQL, $connMain);
  
} else throw new Exception('lat, lon or uid missing');
} catch (Exception $e) {
	$return = array('success' => 0, 'msg' => $e->getMessage());
}
echo json_encode($return);