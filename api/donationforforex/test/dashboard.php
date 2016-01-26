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

mysql_select_db($database_connMain, $connMain);
$query_rsTotalPoints = "SELECT donationforforex_points.uid, sum(donationforforex_points.points) as points FROM donationforforex_points LEFT JOIN donationforforex_accounts ON donationforforex_accounts.`uid` = donationforforex_points.`uid` WHERE MONTH(CURDATE()) = MONTH(donationforforex_points.pt_date) AND YEAR(CURDATE()) = YEAR(donationforforex_points.pt_date) AND donationforforex_accounts.status = 1 GROUP BY donationforforex_points.uid";
$rsTotalPoints = mysql_query($query_rsTotalPoints, $connMain) or die(mysql_error());
$row_rsTotalPoints = mysql_fetch_assoc($rsTotalPoints);
$totalRows_rsTotalPoints = mysql_num_rows($rsTotalPoints);

mysql_select_db($database_connMain, $connMain);
$query_rsEarnings = "SELECT * FROM donationforforex_earnings LEFT JOIN donationforforex_accounts ON donationforforex_accounts.`uid` = donationforforex_earnings.`uid` WHERE MONTH(CURDATE()) = MONTH(donationforforex_earnings.earning_date) AND YEAR(CURDATE()) = YEAR(donationforforex_earnings.earning_date) AND donationforforex_accounts.status = 1 AND donationforforex_earnings.workflow_status = 'Completed'";
$rsEarnings = mysql_query($query_rsEarnings, $connMain) or die(mysql_error());
$row_rsEarnings = mysql_fetch_assoc($rsEarnings);
$totalRows_rsEarnings = mysql_num_rows($rsEarnings);

mysql_select_db($database_connMain, $connMain);
$query_rsAccepted = "SELECT * FROM donationforforex_earnings LEFT JOIN donationforforex_accounts ON donationforforex_accounts.`uid` = donationforforex_earnings.`uid` WHERE MONTH(CURDATE()) = MONTH(donationforforex_earnings.earning_date) AND YEAR(CURDATE()) = YEAR(donationforforex_earnings.earning_date) AND donationforforex_accounts.status = 1 AND donationforforex_earnings.workflow_status = 'Accepted'";
$rsAccepted = mysql_query($query_rsAccepted, $connMain) or die(mysql_error());
$row_rsAccepted = mysql_fetch_assoc($rsAccepted);
$totalRows_rsAccepted = mysql_num_rows($rsAccepted);

mysql_select_db($database_connMain, $connMain);
$query_rsPending = "SELECT * FROM donationforforex_earnings LEFT JOIN donationforforex_accounts ON donationforforex_accounts.`uid` = donationforforex_earnings.`uid` WHERE MONTH(CURDATE()) = MONTH(donationforforex_earnings.earning_date) AND YEAR(CURDATE()) = YEAR(donationforforex_earnings.earning_date) AND donationforforex_accounts.status = 1 AND donationforforex_earnings.workflow_status = 'Pending'";
$rsPending = mysql_query($query_rsPending, $connMain) or die(mysql_error());
$row_rsPending = mysql_fetch_assoc($rsPending);
$totalRows_rsPending = mysql_num_rows($rsPending);
?>
<!doctype html>
<html>
<head>
<meta charset="UTF-8">
<title>DashBoard</title>
</head>

<body>
<h1>DashBoard
</h1>
<p><a href="index.php">Back</a></p>
<h3>Total Points For Current Month</h3>
<table border="1" cellpadding="5" cellspacing="0">
  <tr>
    <td>uid</td>
    <td>points</td>
  </tr>
  <?php do { ?>
    <tr>
      <td><?php echo $row_rsTotalPoints['uid']; ?></td>
      <td><?php echo $row_rsTotalPoints['points']; ?></td>
    </tr>
    <?php } while ($row_rsTotalPoints = mysql_fetch_assoc($rsTotalPoints)); ?>
</table>
<h3>Total Completed Amount Earned This Month</h3>
<?php if ($totalRows_rsEarnings > 0) { // Show if recordset not empty ?>
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
      <td>account_id</td>
      <td>uid</td>
      <td>amount_earned</td>
      <td>amount_withdrawn</td>
      <td>act_creation_dt</td>
      <td>last_login_dt</td>
      <td>status</td>
    </tr>
    <?php do { ?>
      <tr>
        <td><?php echo $row_rsEarnings['earning_id']; ?></td>
        <td><?php echo $row_rsEarnings['uid']; ?></td>
        <td><?php echo $row_rsEarnings['earning_date']; ?></td>
        <td><?php echo $row_rsEarnings['earning_amount']; ?></td>
        <td><?php echo $row_rsEarnings['admin_fees']; ?></td>
        <td><?php echo $row_rsEarnings['workflow_status']; ?></td>
        <td><?php echo $row_rsEarnings['net_amount']; ?></td>
        <td><?php echo $row_rsEarnings['earning_comments']; ?></td>
        <td><?php echo $row_rsEarnings['earning_updated_date']; ?></td>
        <td><?php echo $row_rsEarnings['account_id']; ?></td>
        <td><?php echo $row_rsEarnings['uid']; ?></td>
        <td><?php echo $row_rsEarnings['amount_earned']; ?></td>
        <td><?php echo $row_rsEarnings['amount_withdrawn']; ?></td>
        <td><?php echo $row_rsEarnings['act_creation_dt']; ?></td>
        <td><?php echo $row_rsEarnings['last_login_dt']; ?></td>
        <td><?php echo $row_rsEarnings['status']; ?></td>
      </tr>
      <?php } while ($row_rsEarnings = mysql_fetch_assoc($rsEarnings)); ?>
  </table>
  <?php } // Show if recordset not empty ?>
<h3>Total Accepted Amount Earned This Month</h3>
<?php if ($totalRows_rsAccepted > 0) { // Show if recordset not empty ?>
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
      <td>account_id</td>
      <td>uid</td>
      <td>amount_earned</td>
      <td>amount_withdrawn</td>
      <td>act_creation_dt</td>
      <td>last_login_dt</td>
      <td>status</td>
    </tr>
    <?php do { ?>
      <tr>
        <td><?php echo $row_rsAccepted['earning_id']; ?></td>
        <td><?php echo $row_rsAccepted['uid']; ?></td>
        <td><?php echo $row_rsAccepted['earning_date']; ?></td>
        <td><?php echo $row_rsAccepted['earning_amount']; ?></td>
        <td><?php echo $row_rsAccepted['admin_fees']; ?></td>
        <td><?php echo $row_rsAccepted['workflow_status']; ?></td>
        <td><?php echo $row_rsAccepted['net_amount']; ?></td>
        <td><?php echo $row_rsAccepted['earning_comments']; ?></td>
        <td><?php echo $row_rsAccepted['earning_updated_date']; ?></td>
        <td><?php echo $row_rsAccepted['account_id']; ?></td>
        <td><?php echo $row_rsAccepted['uid']; ?></td>
        <td><?php echo $row_rsAccepted['amount_earned']; ?></td>
        <td><?php echo $row_rsAccepted['amount_withdrawn']; ?></td>
        <td><?php echo $row_rsAccepted['act_creation_dt']; ?></td>
        <td><?php echo $row_rsAccepted['last_login_dt']; ?></td>
        <td><?php echo $row_rsAccepted['status']; ?></td>
      </tr>
      <?php } while ($row_rsAccepted = mysql_fetch_assoc($rsAccepted)); ?>
  </table>
  <?php } // Show if recordset not empty ?>
<h3>Total Pending Amount Earned This Month</h3>
<?php if ($totalRows_rsPending > 0) { // Show if recordset not empty ?>
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
      <td>account_id</td>
      <td>uid</td>
      <td>amount_earned</td>
      <td>amount_withdrawn</td>
      <td>act_creation_dt</td>
      <td>last_login_dt</td>
      <td>status</td>
    </tr>
    <?php do { ?>
      <tr>
        <td><?php echo $row_rsPending['earning_id']; ?></td>
        <td><?php echo $row_rsPending['uid']; ?></td>
        <td><?php echo $row_rsPending['earning_date']; ?></td>
        <td><?php echo $row_rsPending['earning_amount']; ?></td>
        <td><?php echo $row_rsPending['admin_fees']; ?></td>
        <td><?php echo $row_rsPending['workflow_status']; ?></td>
        <td><?php echo $row_rsPending['net_amount']; ?></td>
        <td><?php echo $row_rsPending['earning_comments']; ?></td>
        <td><?php echo $row_rsPending['earning_updated_date']; ?></td>
        <td><?php echo $row_rsPending['account_id']; ?></td>
        <td><?php echo $row_rsPending['uid']; ?></td>
        <td><?php echo $row_rsPending['amount_earned']; ?></td>
        <td><?php echo $row_rsPending['amount_withdrawn']; ?></td>
        <td><?php echo $row_rsPending['act_creation_dt']; ?></td>
        <td><?php echo $row_rsPending['last_login_dt']; ?></td>
        <td><?php echo $row_rsPending['status']; ?></td>
      </tr>
      <?php } while ($row_rsPending = mysql_fetch_assoc($rsPending)); ?>
  </table>
  <?php } // Show if recordset not empty ?>
<p>&nbsp;</p>
<p>&nbsp;</p>
<p>&nbsp;</p>
</body>
</html>
<?php
mysql_free_result($rsTotalPoints);

mysql_free_result($rsEarnings);

mysql_free_result($rsAccepted);

mysql_free_result($rsPending);
?>
