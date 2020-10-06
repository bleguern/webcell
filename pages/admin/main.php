<?php
	session_start();
	include_once(dirname(__FILE__).'/../../util/auth.php');
	include_once(dirname(__FILE__).'/../../util/sql.php');
	
	if (!is_allowed('admin')) {
		header('Location: ../not_allowed.php');
		exit();
	}
?>	
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN" "http://www.w3.org/TR/html4/loose.dtd">
<HTML>
<HEAD>
	<TITLE>ELBA - Appli Cellules - Administration</TITLE>
	<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
	<link href="../../css/style.css" rel="stylesheet" type="text/css">
</HEAD>
<BODY class="main_body">
<TABLE class="normal">
	<TR>
		<TD class="main_title_text">Appli Cellules - Administration</TD>
	</TR>
	<tr>
		<td class="main_title"><img src="../../image/menu/separator.jpg" border="0"></td>
	</tr>
	<TR>
		<TD class="separator"></TD>
	</TR>
	<tr>
    	<td><B>Site :</B> 
<?php

if (isset($_SESSION['site_id'])) {
	echo $_SESSION['site_name'];
} else {
	echo 'Erreur!';
}
?> <br>
<B>Connexion :</B>
<?php
	if (isset($_SESSION['authenticated']))
	{
		echo $_SESSION['login'].' (IP : '.$_SERVER['REMOTE_ADDR'].')';
	}
	else
	{
		echo 'anonyme (IP : '.$_SERVER['REMOTE_ADDR'].')';
	}
?>
		</td>
  	</tr>
</TABLE>
</BODY>
</HTML>
