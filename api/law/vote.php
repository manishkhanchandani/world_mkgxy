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
if (empty($_POST)) {
	throw new Exception('empty post values');
}

if ((isset($_POST["MM_insert"])) && ($_POST["MM_insert"] == "form1")) {
  $insertSQL = sprintf("INSERT INTO law_ratings (id, `uid`, up, down, rating_date, rating_comments) VALUES (%s, %s, %s, %s, %s, %s)",
                       GetSQLValueString($_POST['id'], "int"),
                       GetSQLValueString($_POST['uid'], "int"),
                       GetSQLValueString($_POST['up'], "int"),
                       GetSQLValueString($_POST['down'], "int"),
                       GetSQLValueString($_POST['rating_date'], "date"),
                       GetSQLValueString($_POST['rating_comments'], "text"));

  mysql_select_db($database_connMain, $connMain);
  $Result1 = @mysql_query($insertSQL, $connMain);
  if (empty($Result1)) 
  	throw new Exception(mysql_error());
}

$result = array('success' => 1, 'msg' => 'Vote Added.');
} catch (Exception $e) {
	$result = array('success' => 0, 'msg' => $e->getMessage());
}
echo json_encode($result);
?>