<?php

if (!function_exists('checkLogin')) {
function checkLogin()
{
    if (empty($_SESSION['user'])) {
      $currentUrl = 'http://'.$_SERVER['HTTP_HOST'].$_SERVER['PHP_SELF'].'?'.$_SERVER['QUERY_STRING'];
      $_SESSION['redirectUrl'] = $currentUrl;
      header("Location: login.php");
      exit;
    }
}
}

if (!function_exists('pr')) {
  function pr($d) { echo '<pre>'; print_r($d); echo '</pre>'; }
}
?>