<?php
include_once('../config.php');
//http://world.mkgalaxy.com/api/login.php
//post values username and password
try {
	$users = new world_Users();
	if (empty($_REQUEST['username'])) throw new Exception('Missing username', 1001);
	if (empty($_REQUEST['password'])) throw new Exception('Missing password', 1002);
	$content = $users->login($_REQUEST['username'], $_REQUEST['password']);
	if ($content == 0) throw new Exception('Invalid username and password', 1003);
	if ($content == 2) throw new Exception('Inactive User', 1004);
	$r = array('success' => 1, 'data' => $content);
} catch (Exception $e) {
	$r = array('success' => 0, 'error' => 1, 'msg' => $e->getMessage(), 'code' => $e->getCode());
}
echo json_encode($r);
?>