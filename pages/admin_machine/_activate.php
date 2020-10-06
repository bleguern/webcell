<?php
	session_start();
	include_once(dirname(__FILE__).'/../../util/auth.php');
	include_once(dirname(__FILE__).'/../../util/sql.php');
	
	if (!is_allowed('admin_machine')) {
		header('Location: ../not_allowed.php');
		exit();
	}

	if ($_REQUEST['id'] == '') {
		header('Location: index.php');
		exit();
	}

	$sql_query = 'UPDATE machine SET active = 1 WHERE id = '.mysql_format_to_number($_REQUEST['id']).' LIMIT 1';

	if (mysql_update_query($sql_query)) {
		header('Location: index.php');
	} else {
		mysql_save_sql_error(get_php_self(), 'Activer une machine', $sql_query, mysql_errno(), mysql_error());
		header('Location: index.php?message=Erreur : machine non activée!');
	}
?>