<?php
	session_start();
	include_once(dirname(__FILE__).'/../../util/auth.php');
	include_once(dirname(__FILE__).'/../../util/sql.php');
	
	if (!is_allowed('admin_access')) {
		header('Location: ../not_allowed.php');
		exit();
	}

	if (($_REQUEST['role_id'] == '') || ($_REQUEST['access_id'] == '')) {
		header('Location: index.php');
		exit();
	}

	$sql_query = 'DELETE FROM role_access WHERE role_id = '.mysql_format_to_number($_REQUEST['role_id']).' AND access_id = '.mysql_format_to_number($_REQUEST['access_id']).' LIMIT 1';

	if (mysql_delete_query($sql_query)) {
		header('Location: update.php?id='.$_REQUEST['access_id']);
		exit();
	} else {
		mysql_save_sql_error(get_php_self(), 'Retirer un role a un acces', $sql_query, mysql_errno(), mysql_error());
		header('Location: update.php?id='.$_REQUEST['access_id'].'&message=Erreur : role non retiré!');
		exit();
	}
?>