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
$editFormAction = $_SERVER['PHP_SELF'];
if (isset($_SERVER['QUERY_STRING'])) {
  $editFormAction .= "?" . htmlentities($_SERVER['QUERY_STRING']);
}
if (!empty($_GET['type']) && !empty($_GET['item'])&& !empty($_GET['uid'])) {

	$up = $_GET['type'] == 'up' ? 1 : 0;
	$down = $_GET['type'] == 'down' ? 1 : 0;
	$item = $_GET['item'];
	$uid = $_GET['uid'];
	
	try {
		
	$insertSQL = sprintf("INSERT INTO law_ratings (id, `uid`, up, down, rating_date) VALUES (%s, %s, %s, %s, %s)",
                       GetSQLValueString($item, "int"),
                       GetSQLValueString($uid, "int"),
                       GetSQLValueString($up, "int"),
                       GetSQLValueString($down, "int"),
                       GetSQLValueString(date('Y-m-d H:i:s'), "date"));

	  mysql_select_db($database_connMain, $connMain);
	  $Result1 = @mysql_query($insertSQL, $connMain);
	  echo json_encode(array('error' => false));
	} catch(Exception $e) {
		echo json_encode(array('error' => true, 'case' => 2));
	}

} else {
	echo json_encode(array('error' => true, 'case' => 1));
}
?>