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
try {

$editFormAction = $_SERVER['PHP_SELF'];
if (isset($_SERVER['QUERY_STRING'])) {
  $editFormAction .= "?" . htmlentities($_SERVER['QUERY_STRING']);
}

if ((isset($_REQUEST["MM_insert"])) && ($_REQUEST["MM_insert"] == "form1")) {
  $insertSQL = sprintf("INSERT INTO push_ref (push_id, push_key, reference) VALUES (%s, %s, %s)",
                       GetSQLValueString($_REQUEST['push_id'], "int"),
                       GetSQLValueString($_REQUEST['push_key'], "text"),
                       GetSQLValueString($_REQUEST['reference'], "text"));

  mysql_select_db($database_connMain, $connMain);
  $Result1 = mysql_query($insertSQL, $connMain);
  if (!$Result1)
    throw new Exception(mysql_error());

  $return = array('success' => 1, 'msg' => '');
} else {
  throw new Exception('Missing Post Data');
}
} catch (Exception $e) {
  $return = array('success' => 0, 'msg' => $e->getMessage());
}

$return['get'] = $_GET;
$return['post'] = $_POST;

echo json_encode($return);
?>