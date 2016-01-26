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
$colstatus_rsEvents = "-1";
if (isset($_GET['event_id'])) {
  $colstatus_rsEvents = $_GET['event_id'];
}
mysql_select_db($database_connMain, $connMain);
$query_rsEvents = sprintf("SELECT * FROM donationforforex_events WHERE status = 1 AND event_id = %s", GetSQLValueString($colstatus_rsEvents, "int"));
$rsEvents = mysql_query($query_rsEvents, $connMain) or die(mysql_error());
$row_rsEvents = mysql_fetch_assoc($rsEvents);
$totalRows_rsEvents = mysql_num_rows($rsEvents);

$colname_rsUsers = "-1";
if (isset($_GET['event_id'])) {
  $colname_rsUsers = $_GET['event_id'];
}
mysql_select_db($database_connMain, $connMain);
$query_rsUsers = sprintf("SELECT * FROM donationforforex_users LEFT JOIN google_auth ON donationforforex_users.uid = google_auth.uid WHERE donationforforex_users.event_id = %s", GetSQLValueString($colname_rsUsers, "int"));
$rsUsers = mysql_query($query_rsUsers, $connMain) or die(mysql_error());
$row_rsUsers = mysql_fetch_assoc($rsUsers);
$totalRows_rsUsers = mysql_num_rows($rsUsers);

$data = array();
$users = array();
if ($totalRows_rsEvents > 0) {
    $row_rsEvents['fd'] = date('D j S M, Y', strtotime($row_rsEvents['from_date']));
    $row_rsEvents['td'] = date('D j S M, Y', strtotime($row_rsEvents['to_date']));
    $data = $row_rsEvents;
  if ($totalRows_rsUsers > 0) {
    do {
      $users[] = $row_rsUsers;
    } while ($row_rsUsers = mysql_fetch_assoc($rsUsers));
  }
} else {
  throw new Exception('No Event Found.');
}
$data['users'] = $users;
$return = array('success' => 1, 'msg' => '', 'data' => $data);
} catch (Exception $e) {
  $return = array('success' => 0, 'msg' => $e->getMessage(), 'data' => '');
}

$content = json_encode($return);
if (!empty($_GET['jsoncallback'])) {
	echo $_GET["jsoncallback"]."(".$content.")";
} else {
	echo $content;
}
mysql_free_result($rsEvents);

mysql_free_result($rsUsers);
?>
