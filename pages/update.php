<?php
	session_start();
	include_once(dirname(__FILE__).'/../util/util.php');
	include_once(dirname(__FILE__).'/../util/auth.php');
	include_once(dirname(__FILE__).'/../util/sql.php');
?>	
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN" "http://www.w3.org/TR/html4/loose.dtd">
<html>
<head>
	<title>ELBA - Appli Cellules - Mise &agrave; jour</title>
	<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
	<meta http-equiv="refresh" content="600">
	<link href="../css/style.css" rel="stylesheet" type="text/css">
	<SCRIPT type='text/javascript' src='../scripts/main.js'></SCRIPT>
</HEAD>
<body class="main_body">
<FONT SIZE="1">
<?php
		
echo 'MAJ : ';

if (isset($_SESSION['site_id'])) {
	if ($_SESSION['site_id'] != 100) {
		$value = mysql_simple_select_query('SELECT message_value FROM value WHERE name = \'IMPORT_MESSAGE_'.$_SESSION['site_trigram'].'\'');
		
		if ($value) {
			echo $value;
		} else {
			echo mysql_simple_select_query('SELECT DATE_FORMAT(date, \'%d/%m/%Y:%H:%i\') FROM date_value WHERE name = \'IMPORT_DATE_'.$_SESSION['site_trigram'].'\'');
		}
	} else {
		$value_tre = mysql_simple_select_query('SELECT message_value FROM value WHERE name = \'IMPORT_MESSAGE_TRE\'');
		$value_lam = mysql_simple_select_query('SELECT message_value FROM value WHERE name = \'IMPORT_MESSAGE_LAM\'');
		$value_mlt = mysql_simple_select_query('SELECT message_value FROM value WHERE name = \'IMPORT_MESSAGE_MLT\'');
		$value_neg = mysql_simple_select_query('SELECT message_value FROM value WHERE name = \'IMPORT_MESSAGE_NEG\'');
		
		if ($value_tre) {
			echo 'TRE : '.$value_tre;
		} else {
			echo 'TRE : '.mysql_simple_select_query('SELECT DATE_FORMAT(date, \'%d/%m/%Y:%H:%i\') FROM date_value WHERE name = \'IMPORT_DATE_TRE\'');
		}
		
		if ($value_lam) {
			echo ' / LAM : '.$value_lam;
		} else {
			echo ' / LAM : '.mysql_simple_select_query('SELECT DATE_FORMAT(date, \'%d/%m/%Y:%H:%i\') FROM date_value WHERE name = \'IMPORT_DATE_LAM\'');
		}
		
		if ($value_mlt) {
			echo ' / MLT : '.$value_mlt;
		} else {
			echo ' / MLT : '.mysql_simple_select_query('SELECT DATE_FORMAT(date, \'%d/%m/%Y:%H:%i\') FROM date_value WHERE name = \'IMPORT_DATE_MLT\'');
		}
		
		if ($value_neg) {
			echo ' / NEG : '.$value_neg;
		} else {
			echo ' / NEG : '.mysql_simple_select_query('SELECT DATE_FORMAT(date, \'%d/%m/%Y:%H:%i\') FROM date_value WHERE name = \'IMPORT_DATE_NEG\'');
		}
	}
} else {
	echo 'Erreur!';
}
?></FONT>
</body>
</html>