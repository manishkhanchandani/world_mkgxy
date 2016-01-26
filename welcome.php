<?php
include_once('config.php');
if (!empty($_GET['p'])) {
	$page = $_GET['p'];
}
ob_start();
if (!file_exists($page.'.php')) $page = 'home';
include_once($page.'.php');
$content_for_layout = ob_get_clean();
echo $content_for_layout;
?>