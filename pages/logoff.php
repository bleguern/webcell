<?php
	session_start();
	include_once(dirname(__FILE__).'/../util/sql.php');
	
	mysql_save_log('LOGGED_OUT', '');
	
	session_destroy();
	unset($_SESSION);
	
	header('Location: index.php');
	exit();
?>