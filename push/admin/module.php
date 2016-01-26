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
  $insertSQL = sprintf("INSERT INTO push_modules (app_id, module_name) VALUES (%s, %s)",
                       GetSQLValueString($_POST['app_id'], "int"),
                       GetSQLValueString($_POST['module_name'], "text"));

  mysql_select_db($database_connMain, $connMain);
  $Result1 = mysql_query($insertSQL, $connMain) or die(mysql_error());
}

$colname_rsApp = "-1";
if (isset($_GET['app_id'])) {
  $colname_rsApp = $_GET['app_id'];
}
mysql_select_db($database_connMain, $connMain);
$query_rsApp = sprintf("SELECT * FROM push_app WHERE app_id = %s", GetSQLValueString($colname_rsApp, "int"));
$rsApp = mysql_query($query_rsApp, $connMain) or die(mysql_error());
$row_rsApp = mysql_fetch_assoc($rsApp);
$totalRows_rsApp = mysql_num_rows($rsApp);

$colname_rsModule = "-1";
if (isset($_GET['app_id'])) {
  $colname_rsModule = $_GET['app_id'];
}
mysql_select_db($database_connMain, $connMain);
$query_rsModule = sprintf("SELECT * FROM push_modules WHERE app_id = %s", GetSQLValueString($colname_rsModule, "int"));
$rsModule = mysql_query($query_rsModule, $connMain) or die(mysql_error());
$row_rsModule = mysql_fetch_assoc($rsModule);
$totalRows_rsModule = mysql_num_rows($rsModule);
?>
<!DOCTYPE HTML>
<html>
<head>
<meta charset="utf-8">
<title>Modules</title>
</head>

<body>
<h1>Modules For App <?php echo $row_rsApp['app_name']; ?></h1>
<form method="post" name="form1" action="<?php echo $editFormAction; ?>">
	<table>
		<tr valign="baseline">
			<td nowrap align="right">Module Name:</td>
			<td><input type="text" name="module_name" value="" size="32"></td>
		</tr>
		<tr valign="baseline">
			<td nowrap align="right">&nbsp;</td>
			<td><input type="submit" value="Insert record"></td>
		</tr>
	</table>
	<input type="hidden" name="app_id" value="<?php echo $row_rsApp['app_id']; ?>">
	<input type="hidden" name="MM_insert" value="form1">
</form>
<?php if ($totalRows_rsModule > 0) { // Show if recordset not empty ?>
	<h3>Modules</h3>
	<table border="1" cellpadding="5" cellspacing="0">
		<tr>
			<td><strong>module_id</strong></td>
			<td><strong>app_id</strong></td>
			<td><strong>module_name</strong></td>
		</tr>
		<?php do { ?>
			<tr>
				<td><?php echo $row_rsModule['module_id']; ?></td>
				<td><?php echo $row_rsModule['app_id']; ?></td>
				<td><a href="data.php?app_id=<?php echo $row_rsModule['app_id']; ?>&module_id=<?php echo $row_rsModule['module_id']; ?>"><?php echo $row_rsModule['module_name']; ?></a></td>
			</tr>
			<?php } while ($row_rsModule = mysql_fetch_assoc($rsModule)); ?>
	</table>
	<?php } // Show if recordset not empty ?>
</body>
</html>
<?php
mysql_free_result($rsApp);

mysql_free_result($rsModule);
?>
