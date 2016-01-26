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
  $_REQUEST['created_dt'] = date('Y-m-d H:i:s');
  $_REQUEST['modified_dt'] = date('Y-m-d H:i:s');
  if (!isset($_REQUEST['status']))
    $_REQUEST['status'] = 1;
  if (!isset($_REQUEST['deleted']))
    $_REQUEST['deleted'] = 0;
  if (is_array($_REQUEST['push_value'])) {
    $_REQUEST['push_value'] = json_encode($_REQUEST['push_value']);
  }
  $insertSQL = sprintf("INSERT INTO push (`uid`, `module`, push_value, created_dt, modified_dt, lat, lon, status, deleted) VALUES (%s, %s, %s, %s, %s, %s, %s, %s, %s)",
                       GetSQLValueString($_REQUEST['uid'], "text"),
                       GetSQLValueString($_REQUEST['module'], "int"),
                       GetSQLValueString($_REQUEST['push_value'], "text"),
                       GetSQLValueString($_REQUEST['created_dt'], "date"),
                       GetSQLValueString($_REQUEST['modified_dt'], "date"),
                       GetSQLValueString($_REQUEST['lat'], "double"),
                       GetSQLValueString($_REQUEST['lon'], "double"),
                       GetSQLValueString($_REQUEST['status'], "int"),
                       GetSQLValueString($_REQUEST['deleted'], "int"));

  mysql_select_db($database_connMain, $connMain);
  $Result1 = @mysql_query($insertSQL, $connMain);
  if (!$Result1)
    throw new Exception(mysql_error());

  $id = mysql_insert_id();
  
  if (!empty($_REQUEST['push_details'])) {
    $table = 'push_ref_'.$_REQUEST['module'];
    $deleteSQL = sprintf("DELETE FROM $table WHERE push_id=%s",
                       GetSQLValueString($id, "int"));

    mysql_select_db($database_connMain, $connMain);
    $Result1 = mysql_query($deleteSQL, $connMain);
    if (!$Result1) {
      throw new Exception(mysql_error());
    }
    $insertSQL = sprintf("INSERT INTO $table SET push_id = %s, ", GetSQLValueString($id, "int"));
    $tmp = array();
    foreach ($_REQUEST['push_details'] as $k => $v) {
      $tmp[] = $k." = ".GetSQLValueString($v, "text");
    }
    $insertSQL .= implode(', ', $tmp);
  
    mysql_select_db($database_connMain, $connMain);
    $Result1 = mysql_query($insertSQL, $connMain);
    if (!$Result1)
      throw new Exception(mysql_error());
  }//end if push details
  $return = array('success' => 1, 'msg' => '', 'push_id' => $id);
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