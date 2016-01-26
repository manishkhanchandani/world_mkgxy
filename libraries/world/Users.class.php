<?php
class world_Users
{
	public function login($username, $password)
	{
		global $connAdodb;
		$sql = "select * from users where username = ".$connAdodb->qstr($username)." AND password = ".$connAdodb->qstr($password);
		$rs = $connAdodb->Execute($sql);
		if ($rs->RecordCount() === 0) {
			return 0;
		}
		if ($rs->fields['status'] != 1) return 2;
		$return = $rs->fields;
		unset($return['password']);
		return $return;
	}
	
	public function register($username, $password, $email, $phone, $name)
	{
		global $connAdodb;
		$sql = "select * from users where (username = ".$connAdodb->qstr($username)." OR phone = ".$connAdodb->qstr($phone)." OR email = ".$connAdodb->qstr($email).")";
		$rs = $connAdodb->Execute($sql);
		if ($rs->RecordCount() === 0) {
			//insert here
			$return['username'] = $username;
			$return['password'] = $password;
			$return['email'] = $email;
			$return['phone'] = $phone;
			$return['name'] = $name;
			$return['user_id'] = guid();
			$return['status'] = 1;
			$return['created'] = tstobts(time());
			$connAdodb->AutoExecute('users', $return, 'INSERT');
			unset($return['password']);
			return $return;
		}
		if ($rs->fields['username'] == $username) return 0;
		if ($rs->fields['email'] == $email) return 1;
		if ($rs->fields['phone'] == $phone) return 2;
		return 3;
	}
	
	public function forgot($username)
	{
		global $connAdodb;
		$sql = "select * from users where username = ".$connAdodb->qstr($username);
		$rs = $connAdodb->Execute($sql);
		if ($rs->RecordCount() === 0) {
			return 0;
		}
		if ($rs->fields['status'] != 1) return 1;
		$return = $rs->fields;
		unset($return['password']);
		return $return;
	}
}
?>