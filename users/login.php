<?php require_once('Connections/conn.php'); ?>
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
?>
<?php
// *** Validate request to login to this site.
if (!isset($_SESSION)) {
  session_start();
}

$loginFormAction = $_SERVER['PHP_SELF'];
if (isset($_GET['accesscheck'])) {
  $_SESSION['PrevUrl'] = $_GET['accesscheck'];
}

if (isset($_POST['username'])) {
  $loginUsername=$_POST['username'];
  $password=$_POST['password1'];
  $MM_fldUserAuthorization = "access_level";
  $MM_redirectLoginSuccess = "success";
  $MM_redirectLoginFailed = "failure";
  $MM_redirecttoReferrer = true;
  mysql_select_db($database_conn, $conn);
  	
  $LoginRS__query=sprintf("SELECT username, username, access_level FROM users WHERE username=%s AND username=%s",
  GetSQLValueString($loginUsername, "text"), GetSQLValueString($password, "text")); 
   
  $LoginRS = mysql_query($LoginRS__query, $conn) or die(mysql_error());
  $loginFoundUser = mysql_num_rows($LoginRS);
  if ($loginFoundUser) {
    
    $loginStrGroup  = mysql_result($LoginRS,0,'access_level');
    
	if (PHP_VERSION >= 5.1) {session_regenerate_id(true);} else {session_regenerate_id();}
    //declare two session variables and assign them
    $_SESSION['MM_Username'] = $loginUsername;
    $_SESSION['MM_UserGroup'] = $loginStrGroup;	      

    if (isset($_SESSION['PrevUrl']) && true) {
      $MM_redirectLoginSuccess = $_SESSION['PrevUrl'];	
    }
    header("Location: " . $MM_redirectLoginSuccess );
  }
  else {
    header("Location: ". $MM_redirectLoginFailed );
  }
}
?>
<script src="<?php echo HTTP_PATH; ?>/SpryAssets/SpryValidationTextField.js" type="text/javascript"></script>
<link href="<?php echo HTTP_PATH; ?>/SpryAssets/SpryValidationTextField.css" rel="stylesheet" type="text/css">
<link href="<?php echo HTTP_PATH; ?>/SpryAssets/SpryValidationPassword.css" rel="stylesheet" type="text/css">
<script src="<?php echo HTTP_PATH; ?>/SpryAssets/SpryValidationPassword.js" type="text/javascript"></script>
<form ACTION="" id="form1" name="form1" method="POST">
	<h2>Login</h2>
	<table border="0" cellspacing="0" cellpadding="5">
		<tr>
			<td>Username:</td>
			<td><span id="spryusername">
				<input type="text" name="username" id="username">
			<span class="textfieldRequiredMsg">A value is required.</span></span></td>
		</tr>
		<tr>
			<td>Password:</td>
			<td><span id="sprypassword1">
				<input type="password" name="password1" id="password1">
			<span class="passwordRequiredMsg">A value is required.</span></span></td>
		</tr>
		<tr>
			<td>&nbsp;</td>
			<td><input type="submit" name="submit" id="submit" value="Submit"></td>
		</tr>
	</table>
</form>
<script type="text/javascript">
var sprytextfield1 = new Spry.Widget.ValidationTextField("spryusername", "none", {hint:"Enter Username", validateOn:["blur"]});
var sprypassword1 = new Spry.Widget.ValidationPassword("sprypassword1", {validateOn:["blur"]});
</script>
