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

$admin_factor = 0.5;

$editFormAction = $_SERVER['PHP_SELF'];
if (isset($_SERVER['QUERY_STRING'])) {
  $editFormAction .= "?" . htmlentities($_SERVER['QUERY_STRING']);
}

mysql_select_db($database_connMain, $connMain);
$query_rsUsers = "SELECT donationforforex_points.uid, sum(donationforforex_points.points) as points FROM donationforforex_points LEFT JOIN donationforforex_accounts ON donationforforex_accounts.`uid` = donationforforex_points.`uid` WHERE MONTH(CURDATE()) = MONTH(donationforforex_points.pt_date) AND YEAR(CURDATE()) = YEAR(donationforforex_points.pt_date) AND donationforforex_accounts.status = 1 GROUP BY donationforforex_points.uid";
$rsUsers = mysql_query($query_rsUsers, $connMain) or die(mysql_error());
$row_rsUsers = mysql_fetch_assoc($rsUsers);
$totalRows_rsUsers = mysql_num_rows($rsUsers);

mysql_select_db($database_connMain, $connMain);
$query_rsTotalPoints = "SELECT SUM(donationforforex_points.points) as pts FROM donationforforex_points LEFT JOIN donationforforex_accounts ON donationforforex_accounts.`uid` = donationforforex_points.`uid` WHERE MONTH(CURDATE()) = MONTH(donationforforex_points.pt_date) AND YEAR(CURDATE()) = YEAR(donationforforex_points.pt_date) AND donationforforex_accounts.status = 1";
$rsTotalPoints = mysql_query($query_rsTotalPoints, $connMain) or die(mysql_error());
$row_rsTotalPoints = mysql_fetch_assoc($rsTotalPoints);
$totalRows_rsTotalPoints = mysql_num_rows($rsTotalPoints);

$totalPoints = $row_rsTotalPoints['pts'];

if ((isset($_POST["MM_insert"])) && ($_POST["MM_insert"] == "form1")) {
  if ($totalRows_rsUsers > 0) { // Show if recordset not empty
    do {
      $amountPerPoint = $_POST['amount'] / $totalPoints;
      $earning_amount = $amountPerPoint * $row_rsUsers['points'];
      $admin_fees = $earning_amount * $admin_factor;
      $net_amount = $earning_amount - $admin_fees;
      $insertSQL = sprintf("INSERT INTO donationforforex_earnings (`uid`, earning_date, earning_amount, admin_fees, workflow_status, net_amount, earning_comments, earning_updated_date) VALUES (%s, %s, %s, %s, %s, %s, %s, %s)",
                           GetSQLValueString($row_rsUsers['uid'], "text"),
                           GetSQLValueString(date('Y-m-d H:i:s'), "date"),
                           GetSQLValueString($earning_amount, "double"),
                           GetSQLValueString($admin_fees, "double"),
                           GetSQLValueString('Pending', "text"),
                           GetSQLValueString($net_amount, "double"),
                           GetSQLValueString('', "text"),
                           GetSQLValueString(date('Y-m-d H:i:s'), "date"));
    echo $insertSQL;
    echo '<br>';
      mysql_select_db($database_connMain, $connMain);
      $Result1 = mysql_query($insertSQL, $connMain) or die(mysql_error());
    } while ($row_rsUsers = mysql_fetch_assoc($rsUsers));
  }
}
?>
<!doctype html>
<html>
<head>
<meta charset="UTF-8">
<title>Earnings Entry</title>
<script src="//code.jquery.com/jquery-1.11.0.min.js"></script>
<script src="//code.jquery.com/jquery-migrate-1.2.1.min.js"></script>
<script language="javascript">
function calc(amt, p) {
  amt = parseFloat(amt);
  p = parseInt(p);
  var amountPerPoint = amt / p;
  $('#total').html(amountPerPoint);
}
</script>
</head>

<body>
<h1>Add Earnings Entry</h1>
<p><a href="index.php">Back</a></p>
<form id="form2" name="form2" method="post">
  <p>
    <label for="amount">Total Amount:</label>
    <input type="text" name="amount" id="amount" onBlur="calc(this.value, '<?php echo $totalPoints; ?>')">
  </p>
  <p>
    <input type="submit" name="submit" id="submit" value="Submit">
  <input type="hidden" name="MM_insert" value="form1">
  </p>
  <p>Note: Total Users: <?php echo $totalRows_rsUsers; ?> And Total Points: <?php echo $totalPoints; ?>, Amount Per point = <span id="total"></span></p>
  <p>&nbsp;</p>
</form>
<p>&nbsp;</p>
</body>
</html>
<?php
mysql_free_result($rsUsers);

mysql_free_result($rsTotalPoints);
?>
