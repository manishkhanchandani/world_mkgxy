<?php
/*
 * Copyright 2011 Google Inc.
 *
 * Licensed under the Apache License, Version 2.0 (the "License");
 * you may not use this file except in compliance with the License.
 * You may obtain a copy of the License at
 *
 *     http://www.apache.org/licenses/LICENSE-2.0
 *
 * Unless required by applicable law or agreed to in writing, software
 * distributed under the License is distributed on an "AS IS" BASIS,
 * WITHOUT WARRANTIES OR CONDITIONS OF ANY KIND, either express or implied.
 * See the License for the specific language governing permissions and
 * limitations under the License.
 */
require_once 'configs.php';
require_once 'src/Google_Client.php'; // include the required calss files for google login
require_once 'src/contrib/Google_PlusService.php';
require_once 'src/contrib/Google_Oauth2Service.php';
session_start();
$title = 'Title';
$client = new Google_Client();
$client->setApplicationName("Google Authentication"); // Set your applicatio name
$client->setScopes(array('https://www.googleapis.com/auth/userinfo.email', 'https://www.googleapis.com/auth/plus.me')); // set scope during user login
$client->setClientId(CLIENTID); // paste the client id which you get from google API Console
$client->setClientSecret(CLIENTSECRET); // set the client secret
$client->setDeveloperKey(DEVELOPERKEY); // Developer key
$client->setRedirectUri('http://world.mkgalaxy.com/api/googleauth/oauth2callback.php'); // paste the redirect URI where you given in APi Console. You will get the Access Token here during login success
$plus 		= new Google_PlusService($client);
$oauth2 	= new Google_Oauth2Service($client); // Call the OAuth2 class for get email address


$title = 'Title';

if(isset($_GET['logout'])) {
  unset($_SESSION['access_token']);
  unset($_SESSION['gplusuer']);
  session_destroy();
  header('Location: http://' . $_SERVER['HTTP_HOST'] . $_SERVER['PHP_SELF']); // it will simply destroy the current seesion which you started before
  #header('Location: https://www.google.com/accounts/Logout?continue=https://appengine.google.com/_ah/logout?continue=http://' . $_SERVER['HTTP_HOST'] . $_SERVER['PHP_SELF']);
  
  /*NOTE: for logout and clear all the session direct goole jus un comment the above line an comment the first header function */
}
if(isset($_GET['code'])) {
	$client->authenticate(); // Authenticate
	$_SESSION['access_token'] = $client->getAccessToken(); // get the access token here
	header('Location: http://' . $_SERVER['HTTP_HOST'] . $_SERVER['PHP_SELF']);
}

if(isset($_SESSION['access_token'])) {
	$client->setAccessToken($_SESSION['access_token']);
}

if ($client->getAccessToken()) {
  $user 		= $oauth2->userinfo->get();
	include('save.php');
	save($user);
  $me 			= $plus->people->get('me');
  $optParams 	= array('maxResults' => 100);
  $activities 	= $plus->activities->listActivities('me', 'public',$optParams);
  $title = json_encode($user);
  // The access token may have been updated lazily.
  $_SESSION['access_token'] 		= $client->getAccessToken();
  $email 							= filter_var($user['email'], FILTER_SANITIZE_EMAIL); // get the USER EMAIL ADDRESS using OAuth2
} else {
	$authUrl = $client->createAuthUrl();
}

if(isset($me)){ 
	$_SESSION['gplusuer'] = $me; // start the session
}
if(isset($user)){ 
	$_SESSION['userDetails'] = $user; // start the session
}
?>
<!doctype html>
<html>
<head>
<meta charset="UTF-8">
<title><?php echo $title; ?></title>
</head>

<body>
<!--<div class="wrapper">
<h1>Signin with Google Account</h1>

</div>-->
<?php 
if(isset($authUrl)) {
	//echo "<a class='login' href='$authUrl'><img src=\"google-login-button-asif18.png\" alt=\"Google login\" title=\"login with google\" /></a>";
	} else {
	?>
		<img src="http://sierrafire.cr.usgs.gov/images/loading.gif" width="300" height="300" />
	<!--<h3><?php //echo $user['name']; ?></h3>
	<p><strong>Gender: </strong><?php //echo $user['gender']; ?></p>
	<p><img src="<?php //echo $user['picture']; ?>" width="100" height="100" /></p>
	<p><a class='logout' href='index.php?logout'>Logout</a></p>-->
	<?php
}
?>
</body>
</html>