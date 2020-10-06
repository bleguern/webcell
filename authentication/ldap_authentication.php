<?php
include_once(dirname(__FILE__).'/../config/ldap.php');

/*
 * Fonction de connexion via un serveur LDAP :
 * -------------------------------------------
 *
 */
function ldap_logon($user_name, $user_password)
{
	global $ldap;
	$connect = null;
	$result = 3;
	
	for ($i = 0; $i < count($ldap['host']); $i++)
	{
		$connect = ldap_connect($ldap['host'][$i]);
		
		if ($connect)
		{
			ldap_set_option($connect, LDAP_OPT_PROTOCOL_VERSION, 3);
			ldap_set_option($connect, LDAP_OPT_REFERRALS, 0);
		
			break;
		}
	}
	
	if ($connect)
	{
		if (ldap_bind($connect, $user_name, $user_password))
		{
			$result = 0;
		}
		else
		{
			$result = 1;
		}
		
		ldap_close($connect);
	}

	return $result;
}

?>
