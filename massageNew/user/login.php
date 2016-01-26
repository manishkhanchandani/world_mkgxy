<?php
$dir = dirname(dirname(dirname(__FILE__)));
require_once 'configs.php';
require_once $dir.'/api/googleauth/src/Google_Client.php'; // include the required calss files for google login
require_once $dir.'/api/googleauth/src/contrib/Google_PlusService.php';
require_once $dir.'/api/googleauth/src/contrib/Google_Oauth2Service.php';
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
	include($dir.'/api/googleauth/save.php');
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
<!doctype html>
<html>
<head>
<meta charset="UTF-8">
<title>Login</title>
</head>

<body>
<h2>Login</h2>
<span class="byline">Using Google Account</span>
<?php 
if(isset($authUrl)) {
	echo "<a class='login' href='$authUrl'><img src=\"../../api/googleauth/google-login-button-asif18.png\" alt=\"Google login\" title=\"login with google\" /></a>";
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
</body>
</html>