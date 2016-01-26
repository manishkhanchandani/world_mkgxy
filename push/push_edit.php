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
$colname_rsView = "-1";
if (isset($_GET['push_id'])) {
  $colname_rsView = $_GET['push_id'];
}
mysql_select_db($database_connMain, $connMain);
$query_rsView = sprintf("SELECT * FROM push WHERE push_id = %s", GetSQLValueString($colname_rsView, "int"));
$rsView = mysql_query($query_rsView, $connMain) or die(mysql_error());
$row_rsView = mysql_fetch_assoc($rsView);
$totalRows_rsView = mysql_num_rows($rsView);

$editFormAction = $_SERVER['PHP_SELF'];
if (isset($_SERVER['QUERY_STRING'])) {
  $editFormAction .= "?" . htmlentities($_SERVER['QUERY_STRING']);
}

if ((isset($_REQUEST["MM_insert"])) && ($_REQUEST["MM_insert"] == "form1")) {
  $_REQUEST['modified_dt'] = date('Y-m-d H:i:s');
  if (!isset($_REQUEST['status'])) $_REQUEST['status'] = $row_rsView['status'];
  if (!isset($_REQUEST['deleted'])) $_REQUEST['deleted'] = $row_rsView['deleted'];
  if (is_array($_REQUEST['push_value'])) {
    $_REQUEST['push_value'] = json_encode($_REQUEST['push_value']);
  }
  $updateSQL = sprintf("UPDATE push SET `uid`=%s, `module`=%s, push_value=%s, modified_dt=%s, lat=%s, lon=%s, status=%s, deleted=%s WHERE push_id=%s",
                       GetSQLValueString($_REQUEST['uid'], "text"),
                       GetSQLValueString($_REQUEST['module'], "int"),
                       GetSQLValueString($_REQUEST['push_value'], "text"),
                       GetSQLValueString($_REQUEST['modified_dt'], "date"),
                       GetSQLValueString($_REQUEST['lat'], "double"),
                       GetSQLValueString($_REQUEST['lon'], "double"),
                       GetSQLValueString($_REQUEST['status'], "int"),
                       GetSQLValueString($_REQUEST['deleted'], "int"),
                       GetSQLValueString($_REQUEST['push_id'], "int"));

  mysql_select_db($database_connMain, $connMain);
  $Result1 = @mysql_query($updateSQL, $connMain);
  if (!$Result1)
    throw new Exception(mysql_error());

  if (!empty($_REQUEST['push_details'])) {
    $id = $_REQUEST['push_id'];
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
  $return = array('success' => 1, 'msg' => '', 'push_id' => $_REQUEST['push_id']);
}

} catch (Exception $e) {
  $return = array('success' => 0, 'msg' => $e->getMessage());
}

$return['get'] = $_GET;
$return['post'] = $_POST;

echo json_encode($return);

mysql_free_result($rsView);
?>