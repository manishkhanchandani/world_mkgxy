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

$editFormAction = $_SERVER['PHP_SELF'];
if (isset($_SERVER['QUERY_STRING'])) {
  $editFormAction .= "?" . htmlentities($_SERVER['QUERY_STRING']);
}

if ((isset($_POST["MM_insert"])) && ($_POST["MM_insert"] == "form1")) {
  $insertSQL = sprintf("INSERT INTO push_app (app_name) VALUES (%s)",
                       GetSQLValueString($_POST['app_name'], "text"));

  mysql_select_db($database_connMain, $connMain);
  $Result1 = mysql_query($insertSQL, $connMain) or die(mysql_error());
}

mysql_select_db($database_connMain, $connMain);
$query_rsApp = "SELECT * FROM push_app";
$rsApp = mysql_query($query_rsApp, $connMain) or die(mysql_error());
$row_rsApp = mysql_fetch_assoc($rsApp);
$totalRows_rsApp = mysql_num_rows($rsApp);
?>
<!DOCTYPE HTML>
<html>
<head>
<meta charset="utf-8">
<title>App Management</title>
</head>

<body>
<h1>App Management</h1>
<form method="post" name="form1" action="<?php echo $editFormAction; ?>">
	<table>
		<tr valign="baseline">
			<td nowrap align="right">App Name:</td>
			<td><input type="text" name="app_name" value="" size="32"></td>
		</tr>
		<tr valign="baseline">
			<td nowrap align="right">&nbsp;</td>
			<td><input type="submit" value="Insert record"></td>
		</tr>
	</table>
	<input type="hidden" name="MM_insert" value="form1">
</form>
<?php if ($totalRows_rsApp > 0) { // Show if recordset not empty ?>
	<table border="1" cellpadding="5" cellspacing="0">
		<tr>
			<td><strong>app_id</strong></td>
			<td><strong>app_name</strong></td>
		</tr>
		<?php do { ?>
			<tr>
				<td><?php echo $row_rsApp['app_id']; ?></td>
				<td><a href="module.php?app_id=<?php echo $row_rsApp['app_id']; ?>"><?php echo $row_rsApp['app_name']; ?></a></td>
			</tr>
			<?php } while ($row_rsApp = mysql_fetch_assoc($rsApp)); ?>
	</table>
	<?php } // Show if recordset not empty ?>
</body>
</html>
<?php
mysql_free_result($rsApp);
?>
