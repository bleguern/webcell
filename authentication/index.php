<?php

include_once(dirname(__FILE__).'/ldap_authentication.php');
include_once(dirname(__FILE__).'/local_authentication.php');
include_once(dirname(__FILE__).'/../util/sql.php');

/*
 * Valeur de retour :
 * 0 : OK
 * 1 : Mauvais nom d'utilisateur ou mot de passe
 * 2 : Nom d'utilisateur inexistant
 * 3 : Erreur
 */

function logon($username, $password, $type = 'local') {

	$logged = 3;

	$sql_query = 'SELECT DISTINCT LOWER(url) FROM access WHERE id IN (SELECT DISTINCT access_id FROM role_access WHERE role_id = (SELECT role_id FROM user WHERE ';
	$sql_query_user = 'SELECT user.id, site.id, site.name, site.trigram, user.role_id, CONCAT(user.first_name, \' \', user.last_name) FROM user LEFT OUTER JOIN site ON user.site_id = site.id WHERE ';

	if ($type == 'ldap') {

		$logged = ldap_logon($username, $password);
		$sql_query = $sql_query.'UPPER(windows_account) = \''.strtoupper($username).'\'))';
		$sql_query_user = $sql_query_user.'UPPER(user.windows_account) = \''.strtoupper($username).'\'';

	} else if ($type == 'local') {

		$logged = local_logon($username, $password);
		$sql_query = $sql_query.'UPPER(login) = \''.strtoupper($username).'\'))';
		$sql_query_user = $sql_query_user.'UPPER(user.login) = \''.strtoupper($username).'\'';
	}

	if ($logged == 0) {
		$result = mysql_select_query($sql_query);
		$result_user = mysql_select_query($sql_query_user);

		if($result_user) {
			$user = mysql_fetch_array($result_user, MYSQL_NUM);
			
			$_SESSION['authenticated'] = 1;
			$_SESSION['login'] = ucwords(strtolower($username));
			$_SESSION['auth_type'] = $type;
			
			$_SESSION['user_id'] = $user[0];
			$_SESSION['site_id'] = $user[1];
			$_SESSION['site_name'] = $user[2];
			$_SESSION['site_trigram'] = $user[3];
			$_SESSION['role_id'] = $user[4];
			$_SESSION['user_name'] = $user[5];
			
			if ($result) {
				$i = 0;
				
				while ($row = mysql_fetch_array($result, MYSQL_NUM)) {
	
					$_SESSION['right'][$i] = $row[0];
					$i++;
				}
			}

			mysql_save_log('LOGGED_IN', '');
		} else {
			$logged = 2;
		}
	}

	return $logged;
}
?>
