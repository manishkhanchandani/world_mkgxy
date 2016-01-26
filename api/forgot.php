<?php
include_once('../config.php');
//http://world.mkgalaxy.com/api/register.php
//post values username, password, email, phone, name
try {
	$users = new world_Users();
	if (empty($_REQUEST['username'])) throw new Exception('Missing username', 1001);
	$content = $users->forgot($_REQUEST['username']);
	if ($content == 0) throw new Exception('Username Does Not Exists', 2003);
	if ($content == 1) throw new Exception('Inactive User', 2004);
	$r = array('success' => 1, 'msg' => 'Password Sent to You.');
} catch (Exception $e) {
	$r = array('success' => 0, 'error' => 1, 'msg' => $e->getMessage(), 'code' => $e->getCode());
}
echo json_encode($r);
?>