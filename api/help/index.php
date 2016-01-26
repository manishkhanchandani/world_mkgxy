<?php
try {
define('RDIR', dirname(__FILE__));

include_once('../../Connections/connMain.php');
//define('SITEROOTPATH', dirname(dirname(dirname(dirname(dirname(dirname(__FILE__)))))));
set_include_path(get_include_path(). PATH_SEPARATOR. SITE_DIR.'/library');
include_once('MkGalaxy/Library/functions.php');

//my autoloader
function myautoload($class_name) {
    $classPath = RDIR.'/MkGalaxy/'.implode('/', explode('_', $class_name));
   if (file_exists($classPath.'.class.php')) {
    include_once $classPath . '.class.php';
   }
}
spl_autoload_register('myautoload', true);
//zend autoloadar
require_once('Zend/Loader/Autoloader.php');
if (class_exists('Zend_Loader_Autoloader', false))
{
  Zend_Loader_Autoloader::getInstance();
}


$defaultPage = 'home';
$page_prefix = 'App_';
$page = $defaultPage;
//http://world.mkgalaxy.com/api/help/users/login
//http://world.mkgalaxy.com/api/help/doctors/add
//http://world.mkgalaxy.com/api/help/messages/add?data[uid]=108014758311611089087&data[to_uid]=117652198097778721736&data[message]=hello
//http://world.mkgalaxy.com/api/help/messages/view?uid=108014758311611089087
//http://world.mkgalaxy.com/api/help/messages/detail?message_id=1&uid=108014758311611089087
if (!empty($_GET['p'])) {
  $page = $_GET['p'];
  if (!file_exists(RDIR.'/MkGalaxy/App/'.$page.'.class.php')) {
    $page = $defaultPage;
  }
  $page = implode('_', explode('/', $page));
}
$pagePath = $page_prefix.$page;
$object = new $pagePath();
$object->execute();
$return = array('success' => 1, 'msg' => '', 'data' => $object->return, 'pagePath' => $pagePath);
} catch (Exception $e) {
$return = array('success' => 0, 'msg' => $e->getMessage(), 'data' => array(), 'pagePath' => $pagePath);
}
//echo json_encode($return);
$content = json_encode($return);
if (!empty($_GET['jsoncallback'])) {
	echo $_GET["jsoncallback"]."(".$content.")";
} else {
	echo $content;
}
exit;