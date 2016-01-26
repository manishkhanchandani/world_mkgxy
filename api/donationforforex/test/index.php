<?php
if (isset($_POST['uid'])) {
  setcookie('uid', $_POST['uid'], 0, '/');
  $_COOKIE['uid'] = $_POST['uid'];
}
?>
<!doctype html>
<html>
<head>
<meta charset="UTF-8">
<title>Untitled Document</title>
</head>

<body>
<p>Test Area</p>
<p>Login</p>
<form id="form1" name="form1" method="post">
  <label for="uid">UID:</label>
  <input name="uid" type="text" id="uid" value="117652198097778721736" size="50">
  <input type="submit" name="login" id="login" value="Login">
</form>
<?php if (isset($_COOKIE['uid'])) { ?>
<h1>Welcome User <?php echo $_COOKIE['uid']; ?>, </h1>
<p><strong>Admin Based</strong></p>
<p><a href="dashboard.php">DashBoard</a></p>
<p><a href="earnings.php">Earning Entry</a></p>
<p><strong>User Based</strong></p>
<p><a href="myaccount.php?uid=<?php echo $_COOKIE['uid']; ?>">My Account</a></p>
<p>Withdraw Request</p>
<p>Notification</p>
<?php } ?>
<p>&nbsp;</p>
</body>
</html>