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
// *** Redirect if username exists
$MM_flag="MM_insert";
if (isset($_REQUEST['uid']) && isset($_REQUEST['paypal_email_address']) && isset($_REQUEST['event_id'])) {
  $loginUID = $_REQUEST['uid'];
  $loginPaypalEmailAddress = $_REQUEST['paypal_email_address'];
  $LoginRS__query = sprintf("SELECT * FROM donationforforex_users WHERE event_id=%s AND (`uid`=%s OR paypal_email_address=%s)", GetSQLValueString($_REQUEST['event_id'], "int"), GetSQLValueString($loginUID, "text"), GetSQLValueString($loginPaypalEmailAddress, "text"));
  mysql_select_db($database_connMain, $connMain);
  $LoginRS=mysql_query($LoginRS__query, $connMain) or die(mysql_error());
  $loginFoundUser = mysql_num_rows($LoginRS);
  $rec = mysql_fetch_array($LoginRS);
  //if there is a row in the database, the username was found - can not add the requested username
  if($rec['uid'] == $loginUID){
    throw new Exception('User already registered with current event');
  }
  else if($rec['paypal_email_address'] == $loginPaypalEmailAddress){
    throw new Exception('Paypal Email Address already registered with current event');
  }
} else {
  throw new Exception('empty data');
}

$editFormAction = $_SERVER['PHP_SELF'];
if (isset($_SERVER['QUERY_STRING'])) {
  $editFormAction .= "?" . htmlentities($_SERVER['QUERY_STRING']);
}

if ((isset($_REQUEST["paypal_email_address"])) && (isset($_REQUEST["event_id"])) && (isset($_REQUEST["uid"]))) {
  $date = date('Y-m-d H:i:s');
  $insertSQL = sprintf("INSERT INTO donationforforex_users (event_id, `uid`, register_date, paypal_email_address) VALUES (%s, %s, %s, %s)",
                       GetSQLValueString($_REQUEST['event_id'], "int"),
                       GetSQLValueString($_REQUEST['uid'], "text"),
                       GetSQLValueString($date, "date"),
                       GetSQLValueString($_REQUEST['paypal_email_address'], "text"));

  mysql_select_db($database_connMain, $connMain);
  $Result1 = mysql_query($insertSQL, $connMain) or die(mysql_error());
}
$return = array('success' => 1, 'msg' => '', 'data' => $_REQUEST);
} catch (Exception $e) {
  $return = array('success' => 0, 'msg' => $e->getMessage(), 'data' => array());
}

$content = json_encode($return);
if (!empty($_GET['jsoncallback'])) {
	echo $_GET["jsoncallback"]."(".$content.")";
} else {
	echo $content;
}
?>