<?php
	session_start();
	include_once(dirname(__FILE__).'/../../util/util.php');
	include_once(dirname(__FILE__).'/../../util/auth.php');
	include_once(dirname(__FILE__).'/../../util/sql.php');

	if (!is_allowed('admin_version')) {

		header('Location: ../not_allowed.php');
		exit();
	}

	if (isset($_POST['add']) || isset($_POST['add_x'])) {
		add_version();
	}

function add_version() {
	if (($_REQUEST['number'] == '') ||
		($_REQUEST['name'] == '')) {
		$_REQUEST['message'] = 'Attention : des champs sont manquants!';
	} else {

		$sql_query = 'INSERT INTO version (id, user_id, version_date, number, name, description) ' .
					 'VALUES ' .
					 '(NULL, ' .
					 ''.mysql_format_to_number($_SESSION['user_id']).', ' .
					 'NOW(), ' .
					 ''.mysql_format_to_string($_REQUEST['number']).', ' .
					 ''.mysql_format_to_string($_REQUEST['name']).', ' .
					 ''.mysql_format_to_string($_REQUEST['description']).')';

		if (mysql_insert_query($sql_query)) {
			mysql_save_log('ADD_VERSION', 'ID : '.mysql_insert_id());
			header('Location: index.php');
		} else {
			mysql_save_sql_error(get_php_self(), 'Ajouter une version', $sql_query, mysql_errno(), mysql_error());
			$_REQUEST['message'] = 'Erreur : version non ajout&eacute;e!';
		}
	}
}


?>
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN" "http://www.w3.org/TR/html4/loose.dtd">
<html>
<head>
	<title>ELBA - Appli Cellules - Ajouter une version</title>
	<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
	<link href="../../css/style.css" rel="stylesheet" type="text/css">
</HEAD>
<BODY class="main_body">
<FORM NAME='addVersionForm' ACTION='add.php' METHOD='POST'>
<TABLE class="normal">
	<TR>
		<TD class="main_title_text">Ajouter une version</TD>
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
		<TD class="main_sub_section_text">- <A HREF="index.php?history=<?php echo get_php_self(); ?>" TARGET="_self">Liste des versions</A> -</TD>
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
			<TABLE>
				<TR>
					<TD>num&eacute;ro * :</TD>
					<TD class="field_separator"></TD>
					<TD>
						<INPUT TYPE='text' NAME='number' VALUE='' STYLE='width:100px' MAXLENGTH='10'>
					</TD>
				</TR>
				<TR>
					<TD class="separator"></TD>
				</TR>
				<TR>
					<TD>titre * :</TD>
					<TD class="field_separator"></TD>
					<TD>
						<INPUT TYPE='text' NAME='name' VALUE='' STYLE='width:200px' MAXLENGTH='50'>
					</TD>
				</TR>
				<TR>
					<TD class="separator"></TD>
				</TR>
				<TR>
					<TD>description :</TD>
					<TD class="field_separator"></TD>
					<TD>
						<TEXTAREA NAME="description" COLS=40 ROWS=6></TEXTAREA>
					</TD>
				</TR>
				<TR>
					<TD class="max_separator"></TD>
				</TR>
				<TR>
					<TD>* : champs obligatoires</TD>
				</TR>
				<TR>
					<TD class="max_separator"></TD>
				</TR>
			</TABLE>
			<TABLE class="normal">
				<TR align="center">
					<TD>
						<INPUT TYPE='submit' NAME='add' VALUE='ajouter' ALT='Ajouter une version'>
					</TD>
				</TR>
				<TR>
					<TD class="max_separator"></TD>
				</TR>
			</TABLE>
			<TABLE class="normal">
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