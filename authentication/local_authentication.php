<?php
include_once(dirname(__FILE__).'/../util/sql.php');

function local_logon($user_name, $user_password) {

	$result = mysql_simple_select_query('SELECT password FROM user WHERE login=\''.$user_name.'\' LIMIT 1');

	if($result) {
		$salt = substr($result, 0, 2);

        if (crypt($user_password, $salt) == $result) {
        	//echo 'Good job!';
        	return 0;
        } else {
     		return 1;
        }
	} else {
		return 2;
	}

	return 3;
}
?>
