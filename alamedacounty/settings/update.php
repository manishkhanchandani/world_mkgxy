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
//http://world.mkgalaxy.com/alamedacounty/settings/update.php?uid=&gender=&birthmonth=&birthday=&birthyear=&birthhour=&birthminute=&birthcity=&geonameId=&&marital_status=&education=&profession=
if (!empty($_REQUEST["uid"])) {
  $updateSQL = sprintf("REPLACE ac_settings SET gender=%s, birthday=%s, birthmonth=%s, birthyear=%s, birthhour=%s, birthminute=%s, birthlocation=%s, marital_status=%s, education=%s, profession=%s, `uid`=%s",
                       GetSQLValueString($_REQUEST['gender'], "text"),
                       GetSQLValueString($_REQUEST['birthday'], "int"),
                       GetSQLValueString($_REQUEST['birthmonth'], "int"),
                       GetSQLValueString($_REQUEST['birthyear'], "int"),
                       GetSQLValueString($_REQUEST['birthhour'], "int"),
                       GetSQLValueString($_REQUEST['birthminute'], "int"),
                       GetSQLValueString($_REQUEST['birthlocation'], "text"),
                       GetSQLValueString($_REQUEST['marital_status'], "text"),
                       GetSQLValueString($_REQUEST['education'], "text"),
                       GetSQLValueString($_REQUEST['profession'], "text"),
                       GetSQLValueString($_REQUEST['uid'], "text"));

  mysql_select_db($database_connMain, $connMain);
  $Result1 = mysql_query($updateSQL, $connMain) or die(mysql_error());
} else throw new Exception('uid missing');
} catch (Exception $e) {
	$return = array('success' => 0, 'msg' => $e->getMessage());
}
echo json_encode($return);
?>