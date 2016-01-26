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
	throw new Exception('post value is empty');
}

if ((isset($_POST["MM_insert"])) && ($_POST["MM_insert"] == "form1")) {
  $insertSQL = sprintf("Replace google_auth set email = %s, gender = %s, name = %s, `uid` = %s, link = %s, picture = %s",
                       GetSQLValueString($_POST['email'], "text"),
                       GetSQLValueString($_POST['gender'], "text"),
                       GetSQLValueString($_POST['name'], "text"),
                       GetSQLValueString($_POST['uid'], "int"),
                       GetSQLValueString($_POST['link'], "text"),
                       GetSQLValueString($_POST['picture'], "text"));

  mysql_select_db($database_connMain, $connMain);
  $Result1 = @mysql_query($insertSQL, $connMain);
  if (empty($Result1))
  	throw new Exception(mysql_error());
}

if ((isset($_POST["MM_insert"])) && ($_POST["MM_insert"] == "form1")) {
  $insertSQL = sprintf("INSERT INTO law_content (`uid`, law, created, modified) VALUES (%s, %s, %s, %s)",
                       GetSQLValueString($_POST['uid'], "int"),
                       GetSQLValueString($_POST['law'], "text"),
                       GetSQLValueString(date('Y-m-d H:i:s'), "date"),
                       GetSQLValueString(date('Y-m-d H:i:s'), "date"));

  mysql_select_db($database_connMain, $connMain);
  $Result1 = @mysql_query($insertSQL, $connMain);
  if (empty($Result1))
  	throw new Exception(mysql_error());
}
$result = array('success' => 1, 'msg' => 'Record Added Successfully');
} catch (Exception $e) {
	$result = array('success' => 0, 'msg' => $e->getMessage());
}
echo json_encode($result);
?>