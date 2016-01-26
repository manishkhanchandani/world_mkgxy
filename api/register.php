<?php
include_once('../config.php');
//http://world.mkgalaxy.com/api/register.php
//post values username, password, email, phone, name
try {
	$users = new world_Users();
	if (empty($_REQUEST['username'])) throw new Exception('Missing username', 1001);
	if (empty($_REQUEST['password'])) throw new Exception('Missing password', 1002);
	if (empty($_REQUEST['email'])) throw new Exception('Missing username', 1001);
	if (empty($_REQUEST['phone'])) throw new Exception('Missing email', 1008);
	if (empty($_REQUEST['email'])) throw new Exception('Missing username', 1009);
	if (empty($_REQUEST['name'])) throw new Exception('Missing name', 1010);
	$content = $users->register($_REQUEST['username'], $_REQUEST['password'], $_REQUEST['email'], $_REQUEST['phone'], $_REQUEST['name']);
	if ($content == 0) throw new Exception('Username Already Exists', 1005);
	if ($content == 1) throw new Exception('Email Already Exists', 1006);
	if ($content == 2) throw new Exception('Phone Already Exists', 1007);
	if ($content == 3) throw new Exception('Please Try Again', 1010);
	$r = array('success' => 1, 'data' => $content);
} catch (Exception $e) {
	$r = array('success' => 0, 'error' => 1, 'msg' => $e->getMessage(), 'code' => $e->getCode());
}
echo json_encode($r);
?>