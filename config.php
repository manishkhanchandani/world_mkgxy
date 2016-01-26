<?php
define('HTTP_PATH', 'http://world.mkgalaxy.com');
define('ROOT_DIR', dirname(__FILE__));
define('LIBS_DIRECTORY', ROOT_DIR.'/libraries');
define('WORLD_DIRECTORY', LIBS_DIRECTORY.'/world');
define('SITE_DIR', dirname(dirname(dirname(dirname(__FILE__)))));
define('ZEND_LIB_PATH', SITE_DIR.'/library');
include_once(LIBS_DIRECTORY.'/constants.php');
$includePathList = explode(PATH_SEPARATOR, get_include_path());
array_unshift($includePathList, ZEND_LIB_PATH);
array_unshift($includePathList, WORLD_DIRECTORY);
set_include_path(implode(PATH_SEPARATOR, $includePathList));
require_once(WORLD_DIRECTORY.DIRECTORY_SEPARATOR . 'Autoload.class.php');
world_Autoload::register();
require_once('Zend/Loader/Autoloader.php');
if (class_exists('Zend_Loader_Autoloader', false)) {
	Zend_Loader_Autoloader::getInstance();
}
//include_once(ROOT_DIR.'/Connections/conn.php');
include_once(ROOT_DIR.'/Connections/connMain.php');
include_once(LIBS_DIRECTORY.'/functions.php');
?>