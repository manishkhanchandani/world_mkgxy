<?php require_once('Connections/conn.php'); ?>
<?php
$_POST['user_id'] = guid();
$_POST['status'] = 1;
$_POST['created'] = tstobts(time());
?>
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

// *** Redirect if username exists
$MM_flag="MM_insert";
if (isset($_POST[$MM_flag])) {
  $MM_dupKeyRedirect="rfailure";
  $loginUsername = $_POST['username'];
  $LoginRS__query = sprintf("SELECT username FROM users WHERE username=%s", GetSQLValueString($loginUsername, "text"));
  mysql_select_db($database_conn, $conn);
  $LoginRS=mysql_query($LoginRS__query, $conn) or die(mysql_error());
  $loginFoundUser = mysql_num_rows($LoginRS);

  //if there is a row in the database, the username was found - can not add the requested username
  if($loginFoundUser){
    $MM_qsChar = "?";
    //append the username to the redirect page
    if (substr_count($MM_dupKeyRedirect,"?") >=1) $MM_qsChar = "&";
    $MM_dupKeyRedirect = $MM_dupKeyRedirect . $MM_qsChar ."requsername=".$loginUsername;
    header ("Location: $MM_dupKeyRedirect");
    exit;
  }
}

$editFormAction = $_SERVER['PHP_SELF'];
if (isset($_SERVER['QUERY_STRING'])) {
  $editFormAction .= "?" . htmlentities($_SERVER['QUERY_STRING']);
}

if ((isset($_POST["MM_insert"])) && ($_POST["MM_insert"] == "form1")) {
  $insertSQL = sprintf("INSERT INTO users (user_id, username, name, email, password, phone, status, created, access_level) VALUES (%s, %s, %s, %s, %s, %s, %s, %s, %s)",
                       GetSQLValueString($_POST['user_id'], "text"),
                       GetSQLValueString($_POST['username'], "text"),
                       GetSQLValueString($_POST['name'], "text"),
                       GetSQLValueString($_POST['email'], "text"),
                       GetSQLValueString($_POST['password'], "text"),
                       GetSQLValueString($_POST['phone'], "text"),
                       GetSQLValueString($_POST['status'], "int"),
                       GetSQLValueString($_POST['created'], "int"),
                       GetSQLValueString($_POST['access_level'], "text"));

  mysql_select_db($database_conn, $conn);
  $Result1 = mysql_query($insertSQL, $conn) or die(mysql_error());

  $insertGoTo = "rsuccess";
  if (isset($_SERVER['QUERY_STRING'])) {
    $insertGoTo .= (strpos($insertGoTo, '?')) ? "&" : "?";
    $insertGoTo .= $_SERVER['QUERY_STRING'];
  }
  header(sprintf("Location: %s", $insertGoTo));
}
?>
<script src="<?php echo HTTP_PATH; ?>/SpryAssets/SpryValidationTextField.js" type="text/javascript"></script>
<link href="<?php echo HTTP_PATH; ?>/SpryAssets/SpryValidationTextField.css" rel="stylesheet" type="text/css">
<link href="<?php echo HTTP_PATH; ?>/SpryAssets/SpryValidationPassword.css" rel="stylesheet" type="text/css">
<link href="<?php echo HTTP_PATH; ?>/SpryAssets/SpryValidationConfirm.css" rel="stylesheet" type="text/css">
<script src="<?php echo HTTP_PATH; ?>/SpryAssets/SpryValidationPassword.js" type="text/javascript"></script>
<script src="<?php echo HTTP_PATH; ?>/SpryAssets/SpryValidationConfirm.js" type="text/javascript"></script>
<form name="form1" method="POST" action="<?php echo $editFormAction; ?>">
	<table border="0" cellspacing="0" cellpadding="5">
		<tr>
			<td>Username:</td>
			<td><span id="spryusername">
				<input type="text" name="username" id="username">
			<span class="textfieldRequiredMsg">A value is required.</span></span></td>
		</tr>
		<tr>
			<td>Email:</td>
			<td><span id="spryemail">
			<input type="text" name="email" id="email">
			<span class="textfieldRequiredMsg">A value is required.</span><span class="textfieldInvalidFormatMsg">Invalid format.</span></span></td>
		</tr>
		<tr>
			<td>Name:</td>
			<td><span id="spryname">
				<input type="text" name="name" id="name">
			<span class="textfieldRequiredMsg">A value is required.</span></span></td>
		</tr>
		<tr>
			<td>Phone:</td>
			<td><span id="spryphone">
				<input type="text" name="phone" id="phone">
			<span class="textfieldRequiredMsg">A value is required.</span></span></td>
		</tr>
		<tr>
			<td>Password:</td>
			<td><span id="sprypassword1">
				<input type="password" name="password" id="password">
			<span class="passwordRequiredMsg">A value is required.</span></span></td>
		</tr>
		<tr>
			<td>Confirm Password:</td>
			<td><span id="spryconfirm1">
				<input type="password" name="confirm_password" id="confirm_password">
			<span class="confirmRequiredMsg">A value is required.</span><span class="confirmInvalidMsg">The values don't match.</span></span></td>
		</tr>
		<tr>
			<td>&nbsp;</td>
			<td><input type="submit" name="Register" id="Register" value="Submit"></td>
		</tr>
	</table>
				<input type="hidden" name="status" value="1" />
				<input type="hidden" name="created" value="" />
				<input type="hidden" name="user_id" value="" />
				<input type="hidden" name="access_level" value="Member" />
				<input type="hidden" name="MM_insert" value="form1">
</form>
<script type="text/javascript">
var sprytextfield1 = new Spry.Widget.ValidationTextField("spryusername", "none", {validateOn:["blur"]});
var sprytextfield2 = new Spry.Widget.ValidationTextField("spryemail", "email", {validateOn:["blur"]});
var sprytextfield3 = new Spry.Widget.ValidationTextField("spryname", "none", {validateOn:["blur"]});
var sprytextfield4 = new Spry.Widget.ValidationTextField("spryphone", "none", {validateOn:["blur"]});
var sprypassword1 = new Spry.Widget.ValidationPassword("sprypassword1", {validateOn:["blur"]});
var spryconfirm1 = new Spry.Widget.ValidationConfirm("spryconfirm1", "password");
</script>
