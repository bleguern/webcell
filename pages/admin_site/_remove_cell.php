<?php
	session_start();
	include_once(dirname(__FILE__).'/../../util/auth.php');
	include_once(dirname(__FILE__).'/../../util/sql.php');
	
	if (!is_allowed('admin_site')) {
		header('Location: ../not_allowed.php');
		exit();
	}

	if (($_REQUEST['id'] == '') || ($_REQUEST['site_id'] == '')) {
		header('Location: index.php');
		exit();
	}

	$sql_query = 'UPDATE cell SET site_id = NULL WHERE id = '.mysql_format_to_number($_REQUEST['id']).' LIMIT 1';

	if (mysql_update_query($sql_query)) {
		header('Location: update.php?id='.$_REQUEST['site_id']);
	} else {
		mysql_save_sql_error(get_php_self(), 'Retirer une cellule a un site', $sql_query, mysql_errno(), mysql_error());
		header('Location: update.php?id='.$_REQUEST['site_id'].'&message=Erreur : cellule non retirée!');
	}
?>