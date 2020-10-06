<?php
	session_start();
	include_once(dirname(__FILE__).'/../../util/auth.php');
	include_once(dirname(__FILE__).'/../../util/sql.php');
	
	if (!is_allowed('admin_wharehouse')) {
		header('Location: ../not_allowed.php');
		exit();
	}

	if (isset($_POST['add']) || isset($_POST['add_x'])) {
		add_wharehouse();
	}

function add_wharehouse() {
	if (($_REQUEST['name'] == '') ||
		($_REQUEST['trigram'] == '')) {
		echo '<SCRIPT type=\'text/javascript\'>
			  alert(\'Attention : des champs sont manquants!\')
			  </SCRIPT>';
	} else {

		$sql_query = 'INSERT INTO wharehouse (id, trigram, name) ' .
					 'VALUES ' .
					 '(NULL, ' .
					 ''.mysql_format_to_string($_REQUEST['trigram']).', ' .
					 ''.mysql_format_to_string($_REQUEST['name']).')';

		if (mysql_insert_query($sql_query)) {
			mysql_save_log('ADD_WHAREHOUSE', 'ID : '.mysql_insert_id());
			header('Location: index.php');
		} else {
			mysql_save_sql_error($_SERVER['PHP_SELF'], 'Ajouter un entrepot', $sql_query, mysql_errno(), mysql_error());
			$_REQUEST['message'] = 'Erreur : entrepot non ajout&eacute;!';
		}
	}
}
?>
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN" "http://www.w3.org/TR/html4/loose.dtd">
<html>
<head>
	<title>Web Appli Cellules - Ajouter un entrepot</title>
	<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
	<link href="../../css/style.css" rel="stylesheet" type="text/css">
</HEAD>
<BODY class="main_body">
<FORM NAME='addWharehouseForm' ACTION='add.php' METHOD='POST'>
<TABLE class="data">
	<TR class="main_title">
		<TD>Ajouter un entrepot</TD>
	</TR>
	<TR>
		<TD class="separator"></TD>
	</TR>
	<TR class="link">
		<TD><A HREF='index.php' TARGET='_self'>Liste des entrepots</A></TD>
	</TR>
	<TR>
		<TD class="separator"></TD>
	</TR>
	<TR>
		<TD>
			<TABLE>
				<TR>
					<TD>trigram * :</TD>
					<TD class="field_separator"></TD>
					<TD>
						<INPUT TYPE='text' NAME='trigram' VALUE='' STYLE='width:50px' MAXLENGTH='3'>
					</TD>
				</TR>
				<TR>
					<TD class="separator"></TD>
				</TR>
				<TR>
					<TD>nom * :</TD>
					<TD class="field_separator"></TD>
					<TD>
						<INPUT TYPE='text' NAME='name' VALUE='' STYLE='width:150px' MAXLENGTH='20'>
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
			<TABLE class="data">
				<TR>
					<TD>
						<INPUT TYPE='submit' NAME='add' VALUE='ajouter' ALT='Ajouter un entrepot'>
					</TD>
				</TR>
				<TR>
					<TD class="max_separator"></TD>
				</TR>
			</TABLE>
			<TABLE class="data">
				<TR class="main_message">
					<TD>
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