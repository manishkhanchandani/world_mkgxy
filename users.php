<?php require_once('Connections/conn.php'); ?>
<?php
$_POST['user_id'] = guid();
$_POST['status'] = guid();
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
if (isset($_POST[$MM_flag]) && $_POST[$MM_flag] == 'form2') {
  $MM_dupKeyRedirect="/users";
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
}

$MM_insert = '';
if (isset($_POST['MM_insert'])) {
	$MM_insert = $_POST['MM_insert'];
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
  $password=$_POST['password'];
  $MM_fldUserAuthorization = "access_level";
  $MM_redirectLoginSuccess = "/";
  $MM_redirectLoginFailed = "/loginfailed";
  $MM_redirecttoReferrer = true;
  mysql_select_db($database_conn, $conn);
  	
  $LoginRS__query=sprintf("SELECT username, password, access_level FROM users WHERE username=%s AND password=%s",
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
<link rel="stylesheet" type="text/css" href="css/style.css" />
<link href="SpryAssets/SpryValidationTextField.css" rel="stylesheet" type="text/css">
<link href="SpryAssets/SpryValidationPassword.css" rel="stylesheet" type="text/css">
<link href="SpryAssets/SpryValidationConfirm.css" rel="stylesheet" type="text/css">
<script src="<?php echo HTTP_PATH; ?>/js/cufon-yui.js" type="text/javascript"></script>
<script src="<?php echo HTTP_PATH; ?>/js/ChunkFive_400.font.js" type="text/javascript"></script>
<script src="SpryAssets/SpryValidationTextField.js" type="text/javascript"></script>
<script src="SpryAssets/SpryValidationPassword.js" type="text/javascript"></script>
<script src="SpryAssets/SpryValidationConfirm.js" type="text/javascript"></script>
<script type="text/javascript">
	Cufon.replace('h1',{ textShadow: '1px 1px #fff'});
	Cufon.replace('h2',{ textShadow: '1px 1px #fff'});
	Cufon.replace('h3',{ textShadow: '1px 1px #000'});
	Cufon.replace('.back');
</script>
<div class="wrapper">
	<div class="content">
		<div id="form_wrapper" class="form_wrapper">
			<form action="<?php echo $editFormAction; ?>" class="register <?php if (empty($MM_insert) || $MM_insert == 'form1') echo 'active'; ?>" name="form1" method="POST">
				<h3>Register</h3>
				<div class="column">
					<div>
						<label>Username:</label>
						<span id="spryusername">
						<input type="text" name="username" id="username">
						<span class="textfieldRequiredMsg leftmargin">A value is required.</span></span> </div>
					<div>
						<label> Name:</label>
						<span id="spryname">
						<input type="text" name="name" id="name">
					<span class="textfieldRequiredMsg leftmargin">A value is required.</span></span>					</div>
					<div>
						<label>Phone:</label>
						<span id="spryphone">
						<input type="text" name="phone" id="phone">
					<span class="textfieldRequiredMsg leftmargin">A value is required.</span></span> </div>
				</div>
				<div class="column">
					<div>
						<label>Email:</label>
						<span id="spryemail">
						<input type="text" name="email" id="email">
					<span class="textfieldRequiredMsg leftmargin">A value is required.</span><span class="textfieldInvalidFormatMsg">Invalid format.</span></span> </div>
					<div>
						<label>Password:</label>
						<span id="sprypassword1">
						<input type="password" name="password" id="password">
					<span class="passwordRequiredMsg leftmargin">A value is required.</span></span> </div>
					<div>
						<label>Confirm Password:</label>
						<span id="spryconfirm1">
						<input type="password" name="password1" id="password1">
					<span class="confirmRequiredMsg leftmargin">A value is required.</span><span class="confirmInvalidMsg">The values don't match.</span></span> </div>
				</div>
				<div class="bottom">
					<div class="remember">
						<input type="checkbox" />
						<span>Send me updates</span>
					</div>
					<input type="submit" value="Register" />
					<a href="index.html" rel="login" class="linkform">You have an account already? Log in here</a>
					<div class="clear"></div>
				</div>
				<input type="hidden" name="MM_insert" value="form1" />
				<input type="hidden" name="status" value="1" />
				<input type="hidden" name="created" value="" />
				<input type="hidden" name="user_id" value="" />
				<input type="hidden" name="access_level" value="Member" />
			</form>
			<form ACTION="<?php echo $loginFormAction; ?>" class="login <?php if ($MM_insert == 'form2') echo 'active'; ?>" name="form2" method="POST">
				<h3>Login</h3>
				<div>
					<label>Username:</label>
					<input type="text" name="username" />
					<span class="error">This is an error</span>
				</div>
				<div>
					<label>Password: <a href="#" rel="forgot_password" class="forgot linkform">Forgot your password?</a></label>
					<input type="password" name="password" />
					<span class="error">This is an error</span>
				</div>
				<div class="bottom">
					<div class="remember"><input type="checkbox" /><span>Keep me logged in</span></div>
					<input type="submit" value="Login"></input>
					<a href="" rel="register" class="linkform">You don't have an account yet? Register here</a>
					<div class="clear"></div>
				</div>
				<input type="hidden" name="MM_insert" value="form2" />
			</form>
			<form class="forgot_password <?php if ($MM_insert == 'form3') echo 'active'; ?>" name="form3" method="post">
				<h3>Forgot Password</h3>
				<div>
					<label>Username or Email:</label>
					<input type="text" />
					<span class="error">This is an error</span>
				</div>
				<div class="bottom">
					<input type="submit" value="Send reminder"></input>
					<a href="#" rel="login" class="linkform">Suddenly remebered? Log in here</a>
					<a href="#" rel="register" class="linkform">You don't have an account? Register here</a>
					<div class="clear"></div>
				</div>
				<input type="hidden" name="MM_insert" value="form3" />
			</form>
		</div>
		<div class="clear"></div>
	</div>
</div>


		<!-- The JavaScript -->
<script type="text/javascript">
$(function() {
					//the form wrapper (includes all forms)
				var $form_wrapper	= $('#form_wrapper'),
					//the current form is the one with class active
					$currentForm	= $form_wrapper.children('form.active'),
					//the change form links
					$linkform		= $form_wrapper.find('.linkform');
						
				//get width and height of each form and store them for later						
				$form_wrapper.children('form').each(function(i){
					var $theForm	= $(this);
					//solve the inline display none problem when using fadeIn fadeOut
					if(!$theForm.hasClass('active'))
						$theForm.hide();
					$theForm.data({
						width	: $theForm.width(),
						height	: $theForm.height()
					});
				});
				
				//set width and height of wrapper (same of current form)
				setWrapperWidth();
				
				/*
				clicking a link (change form event) in the form
				makes the current form hide.
				The wrapper animates its width and height to the 
				width and height of the new current form.
				After the animation, the new form is shown
				*/
				$linkform.bind('click',function(e){
					var $link	= $(this);
					var target	= $link.attr('rel');
					$currentForm.fadeOut(400,function(){
						//remove class active from current form
						$currentForm.removeClass('active');
						//new current form
						$currentForm= $form_wrapper.children('form.'+target);
						//animate the wrapper
						$form_wrapper.stop()
									 .animate({
										width	: $currentForm.data('width') + 'px',
										height	: $currentForm.data('height') + 'px'
									 },500,function(){
										//new form gets class active
										$currentForm.addClass('active');
										//show the new form
										$currentForm.fadeIn(400);
									 });
					});
					e.preventDefault();
				});
				
				function setWrapperWidth(){
					$form_wrapper.css({
						width	: $currentForm.data('width') + 'px',
						height	: $currentForm.data('height') + 'px'
					});
				}
				
				/*
				for the demo we disabled the submit buttons
				if you submit the form, you need to check the 
				which form was submited, and give the class active 
				to the form you want to show
				*/
				$form_wrapper.find('input[type="submit"]')
							 .click(function(e){
								//e.preventDefault();
							 });	
			});
var sprytextfield1 = new Spry.Widget.ValidationTextField("spryusername", "none", {hint:"Enter Username"});
var sprytextfield2 = new Spry.Widget.ValidationTextField("spryname", "none", {validateOn:["blur"]});
var sprytextfield3 = new Spry.Widget.ValidationTextField("spryphone", "none", {validateOn:["blur"]});
var sprytextfield4 = new Spry.Widget.ValidationTextField("spryemail", "email", {validateOn:["blur"], hint:"Enter Email"});
var sprypassword1 = new Spry.Widget.ValidationPassword("sprypassword1");
var spryconfirm1 = new Spry.Widget.ValidationConfirm("spryconfirm1", "password