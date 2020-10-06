<?php
	session_start();
	include_once(dirname(__FILE__).'/../../util/util.php');
	include_once(dirname(__FILE__).'/../../util/sql.php');
	
	if (!isset($_REQUEST['history']) || ($_REQUEST['history'] == '')) {
		$_REQUEST['history'] = '/webcell/pages/main.php';
	}

?>
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN" "http://www.w3.org/TR/html4/loose.dtd">
<html>
<head>
	<title>ELBA - Appli Cellules - Liste des utilisateurs</title>
	<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
	<link href="../../css/style.css" rel="stylesheet" type="text/css">
	<SCRIPT type='text/javascript' src='../../scripts/main.js'></SCRIPT>
</HEAD>
<BODY class="main_body">
<FORM NAME="mainForm" ACTION='index.php' METHOD='POST'>
<TABLE>
	<TR>
		<TD class="main_title_text" valign="top">Liste des utilisateurs <input type="text" name="count" value="..." class="count"></TD>
	</TR>
	<tr>
		<td class="main_title"><img src="../../image/menu/separator.jpg" border="0"></td>
	</tr>
	<TR>
		<TD class="min_separator"></TD>
	</TR>
	<TR>
		<TD>
			<TABLE>
				<TR>
					<TD><?php   
$columns = array(array('Nom', 150, 'data', 'left', '', '', 0, '', false, false, 'Nom d\'utilisateur'),
				 array('Site', 130, 'data', 'left', '', '', 0, '', false, false, 'Site de production'),
				 array('Role', 200, 'data', 'left', '', '', 0, '', false, false, 'Role utilisateur'),
				 array('Email', 200, 'mail', 'left', '', '', 0, '', false, false, 'Email'),
				 array('T&eacute;l&eacute;phone', 100, 'data', 'center', '', '', 0, '', false, false, 'T&eacute;l&eacute;phone'));
				  
get_table_head($columns);
?></TD>
				</TR>
				<TR>
					<TD><iframe src="result.php<?php 

echo '?history='.get_php_self();

if (isset($_REQUEST['order']))
{
	echo '&order='.$_REQUEST['order'];
	
	if (isset($_REQUEST['sort']))
	{
		echo '&sort='.$_REQUEST['sort'];
	}
}
?>" width="790" height="390" name="result" frameborder="0" marginwidth="0" marginheight="0"></iframe></TD>
				</TR>
			</TABLE>
		</TD>
	</TR>
	<TR>
		<TD class="min_separator"></TD>
	</TR>
	<tr>
		<td class="main_bottom"><img src="../../image/menu/min_separator.jpg" border="0"></td>
	</tr>
	<TR>
		<TD class="min_separator"></TD>
	</TR>
	<TR>
		<TD class="main_bottom_text"><A HREF="<?php echo $_REQUEST['history']; ?>" target="_self">Retour</A></TD>
	</TR>
	<TR>
		<TD class="min_separator"></TD>
	</TR>
	<tr>
		<td class="main_bottom"><img src="../../image/menu/min_separator.jpg" border="0"></td>
	</tr>
</TABLE>
</FORM>
</BODY>
</HTML>