<?php
	session_start();
	include_once(dirname(__FILE__).'/../../util/util.php');
	include_once(dirname(__FILE__).'/../../util/auth.php');
	include_once(dirname(__FILE__).'/../../util/sql.php');
	
	if (!is_allowed('admin_access')) {
		header('Location: ../not_allowed.php');
		exit();
	}

	if ($_REQUEST['id'] == '') {
		header('Location: index.php');
		exit();
	}
	
	if (isset($_POST['delete']) || isset($_POST['delete_x'])) {
		delete_access();
	}

function delete_access() {
	$sql_query = 'DELETE FROM access ' .
				 'WHERE id = '.mysql_format_to_number($_REQUEST['id']).' LIMIT 1';

	if (mysql_delete_query($sql_query, 1)) {
		mysql_save_log('DELETE_ACCESS', 'ID : '.$_REQUEST['id']);
		header('Location: index.php');
	} else {
		if (mysql_errno() == 1451) {
			$_REQUEST['message'] = 'Erreur : vous ne pouvez pas supprimer un acc&egrave;s qui poss&egrave;de des roles! Veuillez retirer les roles d\'abord.';
		} else {
			mysql_save_sql_error(get_php_self(), 'Supprimer un acces', $sql_query, mysql_errno(), mysql_error());
			$_REQUEST['message'] = 'Erreur : acc&egrave;s non supprim&eacute;!';
		}
	}
}
	$sql_query = 'SELECT url FROM access WHERE id = '.mysql_format_to_number($_REQUEST['id']).' LIMIT 1';

	$result = mysql_select_query($sql_query);

	if($result) {
		$access = mysql_fetch_array($result, MYSQL_ASSOC);
	} else {
		mysql_save_sql_error(get_php_self(), 'Chargement de la page supprimer un acces', $sql_query, mysql_errno(), mysql_error());
		$_REQUEST['message'] = 'Erreur : chargement de la page &eacute;chou&eacute;!';
	}
?>
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN" "http://www.w3.org/TR/html4/loose.dtd">
<html>
<head>
	<title>ELBA - Appli Cellules - Supprimer un acc&egrave;s</title>
	<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
	<link href="../../css/style.css" rel="stylesheet" type="text/css">
</HEAD>
<BODY class="main_body">
<FORM NAME='deleteAccessForm' ACTION='delete.php' METHOD='POST'>
<TABLE class="normal">
	<TR>
		<TD class="main_title_text">Supprimer un acc&egrave;s</TD>
	</TR>
	<TR>
		<TD class="min_separator"></TD>
	</TR>
	<tr>
		<td class="main_title"><img src="../../image/menu/separator.jpg" border="0"></td>
	</tr>
	<TR>
		<TD class="min_separator"></TD>
	</TR>
	<TR>
		<TD align="center">
			<TABLE class="normal">
				<TR>
					<TD class="main_sub_section_text">- <A HREF="index.php?history=<?php echo get_php_self(); ?>" TARGET="_self">Liste des acc&egrave;s</A> -</TD>
					<TD class="main_sub_section_text">- <A HREF="add.php?history=<?php echo get_php_self(); ?>" TARGET="_self">Ajouter un acc&egrave;s</A> -</TD>
				</TR>
			</TABLE>
		</TD>
	</TR>
	<TR>
		<TD class="min_separator"></TD>
	</TR>
	<tr>
		<td class="main_title"><img src="../../image/menu/separator.jpg" border="0"></td>
	</tr>
	<TR>
		<TD class="separator"></TD>
	</TR>
	<TR>
		<TD>
			<TABLE class="normal">
				<TR align="center">
					<TD class="message">Etes vous s&ucirc;r de vouloir supprimer l'acc&egrave;s : <?php echo $access['url']; ?> ?</TD>
				</TR>
				<TR>
					<TD class="separator"></TD>
				</TR>
			</TABLE>
			<TABLE class="normal">
				<TR align="center">
					<TD>
						<INPUT NAME='id' TYPE='hidden' VALUE='<?php echo $_REQUEST['id']; ?>'>
						<INPUT TYPE='submit' NAME='delete' VALUE='Supprimer' ALT='Supprimer un acc&egrave;s'>
					</TD>
				</TR>
				<TR>
					<TD class="separator"></TD>
				</TR>
				<TR align="center">
					<TD class="message">
<?php

if(isset($_REQUEST['message'])) {
	echo $_REQUEST['message'];
}

?>
					</TD>
				</TR>
			</TABLE>
		</TD>
	</TR>
</TABLE>
</FORM>
</BODY>
</HTML>