<?php
define('ROOTDIR', dirname(dirname(__FILE__)));
define('SITEDIR', dirname(__FILE__));
define('HTTPPATH', dirname($_SERVER['PHP_SELF']));
define('ROOTHTTPPATH', dirname(dirname($_SERVER['PHP_SELF'])));
define('APIDIR', '/api');
define('APIHTTPPATH', 'http://'.$_SERVER['HTTP_HOST'].APIDIR);
//include_once(ROOTDIR.'/Connections/connMain.php');
include_once('functions.php');
$defaultPage = 'home';
$page = $defaultPage;
if (!empty($_GET['p'])) {
  $page = $_GET['p'];
}
$page .= '.php';
$pageTitle = 'World Cities';
ob_start();
if (!empty($_GET['locationFind']) && isset($_GET['q'])) {
  $tmp = explode('/', $_GET['q']);
  $tmp = array_filter($tmp);
  if (empty($tmp)) {
    include('modules/index.php');
  } else {
    $path = implode('/', $tmp);
    if (file_exists('modules/'.$path.'.php')) {
      include('modules/'.$path.'.php');
    } else {
      include('modules/index.php');
    }
  }
} else {
  if (file_exists($page)) {
    include($page);
  } else {
    include($defaultPage.'.php');
  }
}

$contentForTemplate = ob_get_clean();
include('template.php');

?>