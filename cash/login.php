<?php
require_once 'configs.php';
require_once '../api/googleauth/src/Google_Client.php'; // include the required calss files for google login
require_once '../api/googleauth/src/contrib/Google_PlusService.php';
require_once '../api/googleauth/src/contrib/Google_Oauth2Service.php';
session_start();
$client = new Google_Client();
$client->setApplicationName("Google Authentication"); // Set your applicatio name
$client->setScopes(array('https://www.googleapis.com/auth/userinfo.email', 'https://www.googleapis.com/auth/plus.me')); // set scope during user login
$client->setClientId(CLIENTID); // paste the client id which you get from google API Console
$client->setClientSecret(CLIENTSECRET); // set the client secret
$client->setDeveloperKey(DEVELOPERKEY); // Developer key
$url = 'http://' . $_SERVER['HTTP_HOST'] . $_SERVER['PHP_SELF'];
$client->setRedirectUri($url); // paste the redirect URI where you given in APi Console. You will get the Access Token here during login success
$plus 		= new Google_PlusService($client);
$oauth2 	= new Google_Oauth2Service($client); // Call the OAuth2 class for get email address
if(isset($_GET['logout'])) {
  unset($_SESSION['access_token']);
  unset($_SESSION['gplusuer']);
  session_destroy();
  header('Location: http://' . $_SERVER['HTTP_HOST'] . $_SERVER['PHP_SELF']);
  exit;
}
if(isset($_GET['code'])) {
	$client->authenticate(); // Authenticate
	$_SESSION['access_token'] = $client->getAccessToken(); // get the access token here
	header('Location: http://' . $_SERVER['HTTP_HOST'] . $_SERVER['PHP_SELF']);
  exit;
}

if(isset($_SESSION['access_token'])) {
	$client->setAccessToken($_SESSION['access_token']);
}

$title = 'Title';
if ($client->getAccessToken()) {
  $user 		= $oauth2->userinfo->get();
	include('../api/googleauth/save.php');
	save($user);
  $_SESSION['user'] = $user;
  $me 			= $plus->people->get('me');
  $optParams 	= array('maxResults' => 100);
  $activities 	= $plus->activities->listActivities('me', 'public',$optParams);
  // The access token may have been updated lazily.
  $_SESSION['access_token'] 		= $client->getAccessToken();
  $email 							= filter_var($user['email'], FILTER_SANITIZE_EMAIL); // get the USER EMAIL ADDRESS using OAuth2
  if (isset($_SESSION['redirectUrl'])) {
    $url = $_SESSION['redirectUrl'];
    unset($_SESSION['redirectUrl']);
    header("Location: ".$url);
    exit;
  }
} else {
	$authUrl = $client->createAuthUrl();
}

if(isset($me)){ 
	$_SESSION['gplusuer'] = $me; // start the session
}

?>
<!DOCTYPE HTML>
<html><!-- InstanceBegin template="/Templates/no-sidebar.dwt.php" codeOutsideHTMLIsLocked="false" -->
	<head>
  <meta charset="UTF-8">
	<!-- InstanceBeginEditable name="doctitle" -->
	<title>Login</title>
	<!-- InstanceEndEditable -->
	<meta http-equiv="content-type" content="text/html; charset=utf-8" />
		<meta name="description" content="" />
		<meta name="keywords" content="" />
		<link href='http://fonts.googleapis.com/css?family=Roboto:400,100,300,700,500,900' rel='stylesheet' type='text/css'>
		<script src="//code.jquery.com/jquery-1.11.0.min.js"></script>
    <script src="//code.jquery.com/jquery-migrate-1.2.1.min.js"></script>
		<script src="themes/templated-linear/js/skel.min.js"></script>
		<script src="themes/templated-linear/js/skel-panels.min.js"></script>
		<script src="themes/templated-linear/js/init.js"></script>
		<!-- InstanceBeginEditable name="head" -->
		<!-- InstanceEndEditable -->
			<link rel="stylesheet" href="themes/templated-linear/css/skel-noscript.css" />
			<link rel="stylesheet" href="themes/templated-linear/css/style.css" />
			<link rel="stylesheet" href="themes/templated-linear/css/style-desktop.css" />
	</head>
	<body>

	<!-- Header -->
		<div id="header">
			<div id="nav-wrapper"> 
				<!-- Nav -->
				<nav id="nav">
					<?php include('menu.php'); ?>
				</nav>
			</div>
			<div class="container"> 
				
				<!-- Logo -->
				<div id="logo">
					<?php include('submenu.php'); ?>
				</div>
			</div>
		</div>
	<!-- Header --> 

	<!-- Main -->
		<div id="main">
			<div id="content" class="container">
				<section>
          <!-- InstanceBeginEditable name="EditRegion3" -->
					<header>
						<h2>Login</h2>
						<span class="byline">Using Google Account</span>
					</header>
					<?php 
if(isset($authUrl)) {
	echo "<a class='login' href='$authUrl'><img src=\"../api/googleauth/google-login-button-asif18.png\" alt=\"Google login\" title=\"login with google\" /></a>";
	} else {
		?>
		<p>You are already logged in. [<a class='logout' href='login.php?logout'>Logout</a>]</p>
    <h3>Login Details</h3>
<p><b>ID: </b><?php echo $_SESSION['user']['id']; ?><br>
<b>Name: </b><?php echo $_SESSION['user']['name']; ?><br>
<b>Gender: </b><?php echo $_SESSION['user']['gender']; ?><br>
<img src="<?php echo $_SESSION['user']['picture']; ?>" />
</p>
		<?php
}
?>
        <!-- InstanceEndEditable -->
				</section>
			</div>
		</div>
	<!-- /Main -->

	<!-- Tweet -->
		<div id="tweet">
			<div class="container">
				<section>
					<blockquote>&ldquo;In posuere eleifend odio. Quisque semper augue mattis wisi. Maecenas ligula. Pellentesque viverra vulputate enim. Aliquam erat volutpat.&rdquo;</blockquote>
				</section>
			</div>
		</div>
	<!-- /Tweet -->

	<!-- Footer -->
		<div id="footer">
			<div class="container">
				<section>
					<header>
						<h2>Get in touch</h2>
						<span class="byline">Integer sit amet pede vel arcu aliquet pretium</span>
					</header>
					<ul class="contact">
						<li><a href="#" class="fa fa-twitter"><span>Twitter</span></a></li>
						<li class="active"><a href="#" class="fa fa-facebook"><span>Facebook</span></a></li>
						<li><a href="#" class="fa fa-dribbble"><span>Pinterest</span></a></li>
						<li><a href="#" class="fa fa-tumblr"><span>Google+</span></a></li>
					</ul>
				</section>
			</div>
		</div>
	<!-- /Footer -->

	<!-- Copyright -->
		<div id="copyright">
			<div class="container">
				Design: <a href="http://templated.co">TEMPLATED</a> Images: <a href="http://unsplash.com">Unsplash</a> (<a href="http://unsplash.com/cc0">CC0</a>)
			</div>
		</div>


	</body>
<!-- InstanceEnd --></html>