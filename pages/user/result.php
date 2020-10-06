<?php
	session_start();
	include_once(dirname(__FILE__).'/../../util/util.php');
	include_once(dirname(__FILE__).'/../../util/sql.php');
?>
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN" "http://www.w3.org/TR/html4/loose.dtd">
<html>
<head>
	<title>ELBA - Appli Cellules - Liste des utilisateurs</title>
	<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
	<link href="../../css/style.css" rel="stylesheet" type="text/css">
	<SCRIPT type="text/javascript" src="../../scripts/main.js"></SCRIPT>
</HEAD>
<BODY class="main_body">
<FORM NAME="resultForm" ACTION="result.php" METHOD="POST">
<?php
start_loading();
$sql_query = 'SELECT CONCAT(user.first_name, \' \', user.last_name) as name, site.name as site, role.name as role, user.email, user.telephone ' .
				 'FROM user ' .
				 'LEFT OUTER JOIN site ON user.site_id = site.id ' .
				 'LEFT OUTER JOIN role ON user.role_id = role.id ' .
				 'WHERE user.active = 1 ' .
				 'ORDER BY site, role, name';
		   
$columns = array(array('Nom', 150, 'data', 'left', '', '', 0, '', false, false, 'Nom d\'utilisateur'),
				 array('Site', 130, 'data', 'left', '', '', 0, '', false, false, 'Site de production'),
				 array('Role', 200, 'data', 'left', '', '', 0, '', false, false, 'Role utilisateur'),
				 array('Email', 200, 'mail', 'left', '', '', 0, '', false, false, 'Email'),
				 array('T&eacute;l&eacute;phone', 100, 'data', 'center', '', '', 0, '', false, false, 'T&eacute;l&eacute;phone'));

$count = get_table_result_with_query($sql_query, $columns);
stop_loading();
?>
<INPUT TYPE="hidden" NAME="count" VALUE="<?php echo $count; ?>">
</BODY>
<SCRIPT type="text/javascript">
	updateCount()
</SCRIPT>
</FORM>
</HTML>