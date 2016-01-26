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

$currentPage = $_SERVER["PHP_SELF"];
$table = 'push_ref_'.$_REQUEST['module_id'];
if ((isset($_POST["MM_insert"])) && ($_POST["MM_insert"] == "form1")) {
  if (empty($_POST['push_value']) && !empty($_POST['push_key']) && !empty($_POST['reference'])) {
    $arr = array();
    foreach ($_POST['push_key'] as $key => $value) {
			if (empty($value)) {
				continue;
			}
			$push_key = $value;
			$reference = $_POST['reference'][$key];
      $arr[$push_key] = $reference;
		}
    $_POST['push_value'] = json_encode($arr);
  }
}

$editFormAction = $_SERVER['PHP_SELF'];
if (isset($_SERVER['QUERY_STRING'])) {
  $editFormAction .= "?" . htmlentities($_SERVER['QUERY_STRING']);
}

if ((isset($_POST["MM_insert"])) && ($_POST["MM_insert"] == "form1")) {
  $insertSQL = sprintf("INSERT INTO push (`uid`, `module`, push_value, created_dt, modified_dt, status, deleted, lat, lon) VALUES (%s, %s, %s, %s, %s, %s, %s, %s, %s)",
                       GetSQLValueString($_POST['uid'], "text"),
                       GetSQLValueString($_POST['module'], "int"),
                       GetSQLValueString($_POST['push_value'], "text"),
                       GetSQLValueString($_POST['created_dt'], "date"),
                       GetSQLValueString($_POST['modified_dt'], "date"),
                       GetSQLValueString($_POST['status'], "int"),
                       GetSQLValueString($_POST['deleted'], "int"),
                       GetSQLValueString($_POST['lat'], "double"),
                       GetSQLValueString($_POST['lon'], "double"));

  mysql_select_db($database_connMain, $connMain);
  $Result1 = mysql_query($insertSQL, $connMain) or die(mysql_error());
}
if ((isset($_POST["MM_insert"])) && ($_POST["MM_insert"] == "form1")) {
	$push_id = mysql_insert_id();
	if (!empty($_POST['push_key'])) {
    $tmp = array();
    $insertSQL = sprintf("INSERT INTO $table SET push_id = %s, ", GetSQLValueString($push_id, "int"));
		foreach ($_POST['push_key'] as $key => $value) {
			if (empty($value)) {
				continue;
			}
			$push_key = $value;
			$reference = $_POST['reference'][$key];
      $tmp[] = $push_key." = ".GetSQLValueString($reference, "text");
		}
    $insertSQL .= implode(', ', $tmp);
    mysql_select_db($database_connMain, $connMain);
		$Result1 = mysql_query($insertSQL, $connMain) or die(mysql_error());
	}
}

if ((isset($_GET['delete_id'])) && ($_GET['delete_id'] != "")) {
  $deleteSQL = sprintf("DELETE FROM $table WHERE push_id=%s",
                       GetSQLValueString($_GET['delete_id'], "int"));

  mysql_select_db($database_connMain, $connMain);
  $Result1 = mysql_query($deleteSQL, $connMain) or die(mysql_error());
  $deleteSQL = sprintf("DELETE FROM push WHERE push_id=%s",
                       GetSQLValueString($_GET['delete_id'], "int"));

  mysql_select_db($database_connMain, $connMain);
  $Result1 = mysql_query($deleteSQL, $connMain) or die(mysql_error());
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
if (isset($_GET['module_id'])) {
  $colname_rsModule = $_GET['module_id'];
}
mysql_select_db($database_connMain, $connMain);
$query_rsModule = sprintf("SELECT * FROM push_modules WHERE module_id = %s", GetSQLValueString($colname_rsModule, "int"));
$rsModule = mysql_query($query_rsModule, $connMain) or die(mysql_error());
$row_rsModule = mysql_fetch_assoc($rsModule);
$totalRows_rsModule = mysql_num_rows($rsModule);

$maxRows_rsData = 100;
$pageNum_rsData = 0;
if (isset($_GET['pageNum_rsData'])) {
  $pageNum_rsData = $_GET['pageNum_rsData'];
}
$startRow_rsData = $pageNum_rsData * $maxRows_rsData;

$colname_rsData = "-1";
if (isset($_GET['module_id'])) {
  $colname_rsData = $_GET['module_id'];
}
mysql_select_db($database_connMain, $connMain);
$query_rsData = sprintf("SELECT * FROM push WHERE `module` = %s", GetSQLValueString($colname_rsData, "int"));
$query_limit_rsData = sprintf("%s LIMIT %d, %d", $query_rsData, $startRow_rsData, $maxRows_rsData);
$rsData = mysql_query($query_limit_rsData, $connMain) or die(mysql_error());
$row_rsData = mysql_fetch_assoc($rsData);

if (isset($_GET['totalRows_rsData'])) {
  $totalRows_rsData = $_GET['totalRows_rsData'];
} else {
  $all_rsData = mysql_query($query_rsData);
  $totalRows_rsData = mysql_num_rows($all_rsData);
}
$totalPages_rsData = ceil($totalRows_rsData/$maxRows_rsData)-1;

$queryString_rsData = "";
if (!empty($_SERVER['QUERY_STRING'])) {
  $params = explode("&", $_SERVER['QUERY_STRING']);
  $newParams = array();
  foreach ($params as $param) {
    if (stristr($param, "pageNum_rsData") == false && 
        stristr($param, "totalRows_rsData") == false) {
      array_push($newParams, $param);
    }
  }
  if (count($newParams) != 0) {
    $queryString_rsData = "&" . htmlentities(implode("&", $newParams));
  }
}
$queryString_rsData = sprintf("&totalRows_rsData=%d%s", $totalRows_rsData, $queryString_rsData);
$query = sprintf("SHOW COLUMNS FROM $table");
$rs = mysql_query($query, $connMain) or die(mysql_error());

?>
<!DOCTYPE HTML>
<html>
<head>
<meta charset="utf-8">
<title>Data</title>
<style type="text/css">
body {
	font-family: Verdana, Geneva, sans-serif;
	font-size: 11px;
}
</style>
</head>

<body>
<h1>Data For App <?php echo $row_rsApp['app_name']; ?> And Module <?php echo $row_rsModule['module_name']; ?></h1>
<p><a href="module.php?app_id=<?php echo $_GET['app_id']; ?>">Back</a></p>
<form method="post" name="form1" action="<?php echo $editFormAction; ?>">
	<table>
		<tr valign="baseline">
			<td nowrap align="right"><strong>Uid:</strong></td>
			<td><input type="text" name="uid" value="112913147917981568678" size="32"></td>
		</tr>
		<tr valign="baseline">
			<td nowrap align="right"><strong>Push Value:</strong></td>
			<td><input type="text" name="push_value" value="" size="32"></td>
		</tr>
		<tr valign="baseline">
			<td nowrap align="right"><strong>Created Dt:</strong></td>
			<td><input type="text" name="created_dt" value="<?php echo date('Y-m-d H:i:s'); ?>" size="32"></td>
		</tr>
		<tr valign="baseline">
			<td nowrap align="right"><strong>Modified Dt:</strong></td>
			<td><input type="text" name="modified_dt" value="<?php echo date('Y-m-d H:i:s'); ?>" size="32"></td>
		</tr>
		<tr valign="baseline">
			<td nowrap align="right"><strong>Status:</strong></td>
			<td><input type="text" name="status" value="1" size="32"></td>
		</tr>
		<tr valign="baseline">
			<td nowrap align="right"><strong>Deleted:</strong></td>
			<td><input type="text" name="deleted" value="0" size="32"></td>
		</tr>
		<tr valign="baseline">
			<td nowrap align="right"><strong>Lat:</strong></td>
			<td><input type="text" name="lat" value="" size="32"></td>
		</tr>
		<tr valign="baseline">
			<td nowrap align="right"><strong>Lon:</strong></td>
			<td><input type="text" name="lon" value="" size="32"></td>
		</tr>
		<?php while($rec = mysql_fetch_array($rs)) { 
      if ($rec['Field'] === 'push_id') continue;
    ?>
		<tr valign="baseline">
			<td nowrap align="right">
			<?php echo $rec['Field']; ?><input type="hidden" name="push_key[]" id="push_key_<?php echo $i; ?>" value="<?php echo $rec['Field']; ?>"></td>
			<td>
			<input type="text" name="reference[]" id="reference_<?php echo $i; ?>" size="45"></td>
		</tr>
		<?php } ?>
		<tr valign="baseline">
			<td nowrap align="right">&nbsp;</td>
			<td><input type="hidden" name="push_id" id="push_id" value="<?php echo (!empty($_GET['push_id'])) ? $_GET['push_id']: ''; ?>">
      <input type="submit" value="Save"></td>
		</tr>
	</table>
	<input type="hidden" name="module" value="<?php echo $row_rsModule['module_id']; ?>">
	<input type="hidden" name="MM_insert" value="form1">
</form>
<?php if ($totalRows_rsData > 0) { // Show if recordset not empty ?>
	<h3>Push Data</h3>
	<p> Records <?php echo ($startRow_rsData + 1) ?> to <?php echo min($startRow_rsData + $maxRows_rsData, $totalRows_rsData) ?> of <?php echo $totalRows_rsData ?></p>
	<table border="0">
		<tr>
			<td><?php if ($pageNum_rsData > 0) { // Show if not first page ?>
				<a href="<?php printf("%s?pageNum_rsData=%d%s", $currentPage, 0, $queryString_rsData); ?>">First</a>
			<?php } // Show if not first page ?></td>
			<td><?php if ($pageNum_rsData > 0) { // Show if not first page ?>
				<a href="<?php printf("%s?pageNum_rsData=%d%s", $currentPage, max(0, $pageNum_rsData - 1), $queryString_rsData); ?>">Previous</a>
			<?php } // Show if not first page ?></td>
			<td><?php if ($pageNum_rsData < $totalPages_rsData) { // Show if not last page ?>
				<a href="<?php printf("%s?pageNum_rsData=%d%s", $currentPage, min($totalPages_rsData, $pageNum_rsData + 1), $queryString_rsData); ?>">Next</a>
			<?php } // Show if not last page ?></td>
			<td><?php if ($pageNum_rsData < $totalPages_rsData) { // Show if not last page ?>
				<a href="<?php printf("%s?pageNum_rsData=%d%s", $currentPage, $totalPages_rsData, $queryString_rsData); ?>">Last</a>
			<?php } // Show if not last page ?></td>
		</tr>
	</table>
	<br>
	<table border="1" cellpadding="5" cellspacing="0">
		<tr>
			<td><strong>Edit</strong></td>
			<td><strong>Delete</strong></td>
			<td><strong>View Api</strong></td>
			<td><strong>push_id</strong></td>
			<td><strong>uid</strong></td>
			<td><strong>module</strong></td>
			<td><strong>push_value</strong></td>
			<td><strong>created_dt</strong></td>
			<td><strong>modified_dt</strong></td>
			<td><strong>status</strong></td>
			<td><strong>deleted</strong></td>
			<td><strong>lat</strong></td>
			<td><strong>lon</strong></td>
		</tr>
		<?php do { ?>
			<tr>
				<td>Edit</td>
				<td><a href="data.php?delete_id=<?php echo $row_rsData['push_id']; ?>&app_id=<?php echo $row_rsApp['app_id']; ?>&module_id=<?php echo $row_rsData['module']; ?>">Delete</a></td>
				<td><a href="../push_search.php?module=<?php echo $row_rsData['module']; ?>&k=&v=" target="_blank">View Api</a></td>
				<td><?php echo $row_rsData['push_id']; ?></td>
				<td><?php echo $row_rsData['uid']; ?></td>
				<td><?php echo $row_rsData['module']; ?></td>
				<td><?php echo $row_rsData['push_value']; ?></td>
				<td><?php echo $row_rsData['created_dt']; ?></td>
				<td><?php echo $row_rsData['modified_dt']; ?></td>
				<td><?php echo $row_rsData['status']; ?></td>
				<td><?php echo $row_rsData['deleted']; ?></td>
				<td><?php echo $row_rsData['lat']; ?></td>
				<td><?php echo $row_rsData['lon']; ?></td>
			</tr>
			<?php } while ($row_rsData = mysql_fetch_assoc($rsData)); ?>
	</table>
	<?php } // Show if recordset not empty ?>
</body>
</html>
<?php
mysql_free_result($rsApp);

mysql_free_result($rsModule);

mysql_free_result($rsData);
?>
