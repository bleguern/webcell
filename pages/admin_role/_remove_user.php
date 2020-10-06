<?php
	session_start();
	include_once(dirname(__FILE__).'/../../util/auth.php');
	include_once(dirname(__FILE__).'/../../util/sql.php');
	
	if (!is_allowed('admin_role')) {
		header('Location: ../not_allowed.php');
		exit();
	}

	if (($_REQUEST['id'] == '') || ($_REQUEST['role_id'] == '')) {
		header('Location: index.php');
		exit();
	}

	$sql_query = 'UPDATE user SET role_id = NULL WHERE id = '.mysql_format_to_number($_REQUEST['id']).' LIMIT 1';

	if (mysql_update_query($sql_query)) {
		header('Location: update.php?id='.$_REQUEST['role_id']);
		exit();
	} else {
		mysql_save_sql_error(get_php_self(), 'Retirer un utilisateur a un role', $sql_query, mysql_errno(), mysql_error());
		header('Location: update.php?id='.$_REQUEST['role_id'].'&message=Erreur : utilisateur non retiré!');
		exit();
	}
?>