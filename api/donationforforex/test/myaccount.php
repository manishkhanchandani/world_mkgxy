<?php require_once('../../../Connections/connMain.php'); ?>
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

if ((isset($_GET['earning_id'])) && (isset($_GET['wfs'])) && (isset($_GET['uid'])) && in_array($_GET['wfs'], array('Accepted'))) {
  $deleteSQL = sprintf("UPDATE donationforforex_earnings SET workflow_status = %s WHERE earning_id=%s",
                       GetSQLValueString($_GET['wfs'], "text"),
                       GetSQLValueString($_GET['earning_id'], "int"));

  mysql_select_db($database_connMain, $connMain);
  $Result1 = mysql_query($deleteSQL, $connMain) or die(mysql_error());
  
  header("Location: ".$_SERVER['PHP_SELF'].'?uid='.$_GET['uid']);
  exit;
}

$colname_rsEarning = "-1";
if (isset($_GET['earning_id'])) {
  $colname_rsEarning = $_GET['earning_id'];
}
mysql_select_db($database_connMain, $connMain);
$query_rsEarning = sprintf("SELECT * FROM donationforforex_earnings WHERE earning_id = %s", GetSQLValueString($colname_rsEarning, "int"));
$rsEarning = mysql_query($query_rsEarning, $connMain) or die(mysql_error());
$row_rsEarning = mysql_fetch_assoc($rsEarning);
$totalRows_rsEarning = mysql_num_rows($rsEarning);

if ((isset($_GET['earning_id'])) && (isset($_GET['wfs'])) && (isset($_GET['uid'])) && in_array($_GET['wfs'], array('Completed'))) {
  $deleteSQL = sprintf("UPDATE donationforforex_earnings SET workflow_status = %s WHERE earning_id=%s",
                       GetSQLValueString($_GET['wfs'], "text"),
                       GetSQLValueString($_GET['earning_id'], "int"));

  mysql_select_db($database_connMain, $connMain);
  $Result1 = mysql_query($deleteSQL, $connMain) or die(mysql_error());

  $deleteSQL = sprintf("UPDATE donationforforex_accounts SET amount_earned = amount_earned + %s WHERE uid=%s",
                       GetSQLValueString($row_rsEarning['earning_amount'], "double"),
                       GetSQLValueString($_GET['uid'], "text"));

  mysql_select_db($database_connMain, $connMain);
  $Result1 = mysql_query($deleteSQL, $connMain) or die(mysql_error());
  header("Location: ".$_SERVER['PHP_SELF'].'?uid='.$_GET['uid']);
  exit;
}



$editFormAction = $_SERVER['PHP_SELF'];
if (isset($_SERVER['QUERY_STRING'])) {
  $editFormAction .= "?" . htmlentities($_SERVER['QUERY_STRING']);
}

$colname_rsView = "-1";
if (isset($_GET['uid'])) {
  $colname_rsView = $_GET['uid'];
}
mysql_select_db($database_connMain, $connMain);
$query_rsView = sprintf("SELECT * FROM donationforforex_accounts WHERE `uid` = %s", GetSQLValueString($colname_rsView, "text"));
$rsView = mysql_query($query_rsView, $connMain) or die(mysql_error());
$row_rsView = mysql_fetch_assoc($rsView);
$totalRows_rsView = mysql_num_rows($rsView);

$colname_rsPendingEarnings = "-1";
if (isset($_GET['uid'])) {
  $colname_rsPendingEarnings = $_GET['uid'];
}
mysql_select_db($database_connMain, $connMain);
$query_rsPendingEarnings = sprintf("SELECT * FROM donationforforex_earnings WHERE `uid` = %s AND workflow_status = 'Pending'", GetSQLValueString($colname_rsPendingEarnings, "text"));
$rsPendingEarnings = mysql_query($query_rsPendingEarnings, $connMain) or die(mysql_error());
$row_rsPendingEarnings = mysql_fetch_assoc($rsPendingEarnings);
$totalRows_rsPendingEarnings = mysql_num_rows($rsPendingEarnings);

$colname_rsAcceptedEarnings = "-1";
if (isset($_GET['uid'])) {
  $colname_rsAcceptedEarnings = $_GET['uid'];
}
mysql_select_db($database_connMain, $connMain);
$query_rsAcceptedEarnings = sprintf("SELECT * FROM donationforforex_earnings WHERE `uid` = %s  AND workflow_status = 'Accepted'", GetSQLValueString($colname_rsAcceptedEarnings, "text"));
$rsAcceptedEarnings = mysql_query($query_rsAcceptedEarnings, $connMain) or die(mysql_error());
$row_rsAcceptedEarnings = mysql_fetch_assoc($rsAcceptedEarnings);
$totalRows_rsAcceptedEarnings = mysql_num_rows($rsAcceptedEarnings);

$colname_rsCompleted = "-1";
if (isset($_GET['uid'])) {
  $colname_rsCompleted = $_GET['uid'];
}
mysql_select_db($database_connMain, $connMain);
$query_rsCompleted = sprintf("SELECT * FROM donationforforex_earnings WHERE `uid` = %s AND workflow_status = 'Completed'", GetSQLValueString($colname_rsCompleted, "text"));
$rsCompleted = mysql_query($query_rsCompleted, $connMain) or die(mysql_error());
$row_rsCompleted = mysql_fetch_assoc($rsCompleted);
$totalRows_rsCompleted = mysql_num_rows($rsCompleted);

$colname_rsPoints = "-1";
if (isset($_GET['uid'])) {
  $colname_rsPoints = $_GET['uid'];
}
mysql_select_db($database_connMain, $connMain);
$query_rsPoints = sprintf("SELECT SUM(donationforforex_points.points) as pts FROM donationforforex_points LEFT JOIN donationforforex_accounts ON donationforforex_accounts.`uid` = donationforforex_points.`uid`  WHERE donationforforex_points.`uid` = %s AND MONTH(CURDATE()) = MONTH(donationforforex_points.pt_date) AND YEAR(CURDATE()) = YEAR(donationforforex_points.pt_date) AND donationforforex_accounts.status = 1", GetSQLValueString($colname_rsPoints, "text"));
$rsPoints = mysql_query($query_rsPoints, $connMain) or die(mysql_error());
$row_rsPoints = mysql_fetch_assoc($rsPoints);
$totalRows_rsPoints = mysql_num_rows($rsPoints);



if (empty($totalRows_rsView)) {
  $insertSQL = sprintf("INSERT INTO donationforforex_accounts (`uid`, amount_earned, amount_withdrawn) VALUES (%s, %s, %s)",
                       GetSQLValueString($_GET['uid'], "text"),
                       GetSQLValueString(0, "double"),
                       GetSQLValueString(0, "double"));

  mysql_select_db($database_connMain, $connMain);
  $Result1 = mysql_query($insertSQL, $connMain) or die(mysql_error());
  header("Location: ".$_SERVER['PHP_SELF'].'?uid='.$_GET['uid']);
  exit;
}
?>
<!doctype html>
<html>
<head>
<meta charset="UTF-8">
<title>My Account</title>
</head>

<body>
<h1>My Account</h1>
<p><a href="index.php">Back</a></p>
<p>UID: <?php echo $row_rsView['uid']; ?></p>
<p>Amount Earned: <?php echo $row_rsView['amount_earned']; ?></p>
<p>Amount Withdrawn: <?php echo $row_rsView['amount_withdrawn']; ?></p>
<p>Points: <?php echo $row_rsPoints['pts']; ?></p>
<p>Last Login Date: <?php echo $row_rsView['last_login_date']; ?></p>
<p>&nbsp;</p>
<h3>Pending Earnings</h3>
<?php if ($totalRows_rsPendingEarnings > 0) { // Show if recordset not empty ?>
  <table border="1" cellpadding="5" cellspacing="0">
    <tr>
      <td>earning_id</td>
      <td>uid</td>
      <td>earning_date</td>
      <td>earning_amount</td>
      <td>admin_fees</td>
      <td>workflow_status</td>
      <td>net_amount</td>
      <td>earning_comments</td>
      <td>earning_updated_date</td>
      <td>Accept This Order</td>
    </tr>
    <?php do { ?>
      <tr>
        <td><?php echo $row_rsPendingEarnings['earning_id']; ?></td>
        <td><?php echo $row_rsPendingEarnings['uid']; ?></td>
        <td><?php echo $row_rsPendingEarnings['earning_date']; ?></td>
        <td><?php echo $row_rsPendingEarnings['earning_amount']; ?></td>
        <td><?php echo $row_rsPendingEarnings['admin_fees']; ?></td>
        <td><?php echo $row_rsPendingEarnings['workflow_status']; ?></td>
        <td><?php echo $row_rsPendingEarnings['net_amount']; ?></td>
        <td><?php echo $row_rsPendingEarnings['earning_comments']; ?></td>
        <td><?php echo $row_rsPendingEarnings['earning_updated_date']; ?></td>
        <td><a href="myaccount.php?wfs=Accepted&uid=<?php echo $row_rsPendingEarnings['uid']; ?>&earning_id=<?php echo $row_rsPendingEarnings['earning_id']; ?>">Accept This Order</a></td>
      </tr>
      <?php } while ($row_rsPendingEarnings = mysql_fetch_assoc($rsPendingEarnings)); ?>
  </table>
  <?php } // Show if recordset not empty ?>
<h3>Accepted Earnings</h3>
<?php if ($totalRows_rsAcceptedEarnings > 0) { // Show if recordset not empty ?>
  <table border="1" cellpadding="5" cellspacing="0">
    <tr>
      <td>earning_id</td>
      <td>uid</td>
      <td>earning_date</td>
      <td>earning_amount</td>
      <td>admin_fees</td>
      <td>workflow_status</td>
      <td>net_amount</td>
      <td>earning_comments</td>
      <td>earning_updated_date</td>
      <td>Complete This Order</td>
    </tr>
    <?php do { ?>
      <tr>
        <td><?php echo $row_rsAcceptedEarnings['earning_id']; ?></td>
        <td><?php echo $row_rsAcceptedEarnings['uid']; ?></td>
        <td><?php echo $row_rsAcceptedEarnings['earning_date']; ?></td>
        <td><?php echo $row_rsAcceptedEarnings['earning_amount']; ?></td>
        <td><?php echo $row_rsAcceptedEarnings['admin_fees']; ?></td>
        <td><?php echo $row_rsAcceptedEarnings['workflow_status']; ?></td>
        <td><?php echo $row_rsAcceptedEarnings['net_amount']; ?></td>
        <td><?php echo $row_rsAcceptedEarnings['earning_comments']; ?></td>
        <td><?php echo $row_rsAcceptedEarnings['earning_updated_date']; ?></td>
        <td><a href="myaccount.php?wfs=Completed&uid=<?php echo $row_rsAcceptedEarnings['uid']; ?>&earning_id=<?php echo $row_rsAcceptedEarnings['earning_id']; ?>">Complete This Order</a></td>
      </tr>
      <?php } while ($row_rsAcceptedEarnings = mysql_fetch_assoc($rsAcceptedEarnings)); ?>
  </table>
  <?php } // Show if recordset not empty ?>
<h3>Completed Earnings</h3>
<?php if ($totalRows_rsCompleted > 0) { // Show if recordset not empty ?>
  <table border="1" cellpadding="5" cellspacing="0">
    <tr>
      <td>earning_id</td>
      <td>uid</td>
      <td>earning_date</td>
      <td>earning_amount</td>
      <td>admin_fees</td>
      <td>workflow_status</td>
      <td>net_amount</td>
      <td>earning_comments</td>
      <td>earning_updated_date</td>
    </tr>
    <?php do { ?>
      <tr>
        <td><?php echo $row_rsCompleted['earning_id']; ?></td>
        <td><?php echo $row_rsCompleted['uid']; ?></td>
        <td><?php echo $row_rsCompleted['earning_date']; ?></td>
        <td><?php echo $row_rsCompleted['earning_amount']; ?></td>
        <td><?php echo $row_rsCompleted['admin_fees']; ?></td>
        <td><?php echo $row_rsCompleted['workflow_status']; ?></td>
        <td><?php echo $row_rsCompleted['net_amount']; ?></td>
        <td><?php echo $row_rsCompleted['earning_comments']; ?></td>
        <td><?php echo $row_rsCompleted['earning_updated_date']; ?></td>
      </tr>
      <?php } while ($row_rsCompleted = mysql_fetch_assoc($rsCompleted)); ?>
  </table>
  <?php } // Show if recordset not empty ?>
</body>
</html>
<?php
mysql_free_result($rsView);

mysql_free_result($rsPendingEarnings);

mysql_free_result($rsAcceptedEarnings);

mysql_free_result($rsCompleted);

mysql_free_result($rsPoints);

mysql_free_result($rsEarning);
?>
