<?php
	session_start();
	include_once(dirname(__FILE__).'/../../util/util.php');
	include_once(dirname(__FILE__).'/../../util/auth.php');
	include_once(dirname(__FILE__).'/../../util/sql.php');

	if (!is_allowed('admin_version')) {
		header('Location: ../not_allowed.php');
		exit();
	}

	if ($_REQUEST['id'] == '') {
		header('Location: index.php');
		exit();
	}

	if (isset($_POST['update']) || isset($_POST['update_x'])) {
		update_version();
	}

function update_version() {
	if (($_REQUEST['number'] == '') ||
		($_REQUEST['name'] == '')) {

		$_REQUEST['message'] = 'Attention : des champs sont manquants!';
	} else {

		$sql_query = 'UPDATE version ' .
					 'SET user_id = '.mysql_format_to_number($_SESSION['user_id']).', ' .
					 'number = '.mysql_format_to_string($_REQUEST['number']).', ' .
			 		 'name = '.mysql_format_to_string($_REQUEST['name']).', ' .
	 		 		 'description = '.mysql_format_to_string($_REQUEST['description']).' ' .
	 		 		 'WHERE id = '.mysql_format_to_number($_REQUEST['id']).' LIMIT 1';

		if (mysql_update_query($sql_query)) {
			mysql_save_log('UPDATE_VERSION', 'ID : '.$_REQUEST['id']);
			header('Location: index.php');
		} else {
			mysql_save_sql_error(get_php_self(), 'Modifier une version', $sql_query, mysql_errno(), mysql_error());
			$_REQUEST['message'] = 'Erreur : version non modifi&eacute;e!';
		}
	}
}

	$sql_query = 'SELECT number, name, description FROM version WHERE id = '.mysql_format_to_number($_REQUEST['id']).' LIMIT 1';

	$result = mysql_select_query($sql_query);

	if($result) {
		$version = mysql_fetch_array($result, MYSQL_ASSOC);
	} else {
		mysql_save_sql_error(get_php_self(), 'Chargement de la page modifier une version', $sql_query, mysql_errno(), mysql_error());
		$_REQUEST['message'] = 'Erreur : chargement de la page &eacute;chou&eacute;!';
	}

?>
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN" "http://www.w3.org/TR/html4/loose.dtd">
<html>
<head>
	<title>ELBA - Appli Cellules - Modifier une version</title>
	<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
	<link href="../../css/style.css" rel="stylesheet" type="text/css">
</HEAD>
<BODY class="main_body">
<FORM NAME='updateVersionForm' ACTION='update.php' METHOD='POST'>
<TABLE class="normal">
	<TR>
		<TD class="main_title_text">Modifier une version</TD>
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
					<TD class="main_sub_section_text">- <A HREF="index.php?history=<?php echo get_php_self(); ?>" TARGET="_self">Liste des versions</A> -</TD>
					<TD class="main_sub_section_text">- <A HREF="add.php?history=<?php echo get_php_self(); ?>" TARGET="_self">Ajouter une version</A> -</TD>
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
			<TABLE>
				<TR>
					<TD>num&eacute;ro * :</TD>
					<TD class="field_separator"></TD>
					<TD>
						<INPUT TYPE='text' NAME='number' VALUE='<?php echo $version['number']; ?>' STYLE='width:100px' MAXLENGTH='10'>
					</TD>
				</TR>
				<TR>
					<TD class="separator"></TD>
				</TR>
				<TR>
					<TD>titre * :</TD>
					<TD class="field_separator"></TD>
					<TD>
						<INPUT TYPE='text' NAME='name' VALUE='<?php echo $version['name']; ?>' STYLE='width:200px' MAXLENGTH='50'>
					</TD>
				</TR>
				<TR>
					<TD class="separator"></TD>
				</TR>
				<TR>
					<TD>description :</TD>
					<TD class="field_separator"></TD>
					<TD>
						<TEXTAREA NAME="description" COLS=40 ROWS=6><?php echo $version['description']; ?></TEXTAREA>
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
						<INPUT NAME='id' TYPE='hidden' VALUE='<?php echo $_REQUEST['id']; ?>'>
						<INPUT TYPE='submit' NAME='update' VALUE='modifier' ALT='Modifier une version'>
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