<?php
	session_start();
	include_once(dirname(__FILE__).'/../../util/util.php');
	include_once(dirname(__FILE__).'/../../util/auth.php');
	include_once(dirname(__FILE__).'/../../util/sql.php');
	
	if (!is_allowed('admin_site')) {
		header('Location: ../not_allowed.php');
		exit();
	}

	if (isset($_POST['add']) || isset($_POST['add_x'])) {
		add_site();
	}

function add_site() {
	if (($_REQUEST['name'] == '') ||
		($_REQUEST['trigram'] == '') ||
		($_REQUEST['ip_address'] == '')) {
		$_REQUEST['message'] = 'Attention : des champs sont manquants!';
	} else {

		$sql_query = 'INSERT INTO site (id, trigram, name, ip_address ) ' .
					 'VALUES ' .
					 '(NULL, ' .
					 ''.mysql_format_to_string($_REQUEST['trigram']).', ' .
					 ''.mysql_format_to_string($_REQUEST['name']).', ' .
					 ''.mysql_format_to_string($_REQUEST['ip_address']).')';

		if (mysql_insert_query($sql_query)) {
			mysql_save_log('ADD_SITE', 'ID : '.mysql_insert_id());
			header('Location: index.php');
		} else {
			mysql_save_sql_error(get_php_self(), 'Ajouter un site', $sql_query, mysql_errno(), mysql_error());
			$_REQUEST['message'] = 'Erreur : site non ajout&eacute;!';
		}
	}
}
?>
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN" "http://www.w3.org/TR/html4/loose.dtd">
<html>
<head>
	<title>ELBA - Appli Cellules - Ajouter un site</title>
	<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
	<link href="../../css/style.css" rel="stylesheet" type="text/css">
	<SCRIPT type='text/javascript' src='../../scripts/main.js'></SCRIPT>
	<?php mysql_build_javascript_table('SELECT id, name FROM warehouse WHERE id NOT IN (SELECT DISTINCT warehouse_id FROM site WHERE warehouse_id IS NOT NULL) ORDER BY name', 'warehouse'); ?>
</HEAD>
<BODY class="main_body">
<FORM NAME='addSiteForm' ACTION='add.php' METHOD='POST'>
<TABLE class="normal">
	<TR>
		<TD class="main_title_text">Ajouter un site</TD>
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
		<TD class="main_sub_section_text">- <A HREF="index.php?history=<?php echo get_php_self(); ?>" TARGET="_self">Liste des sites</A></A> -</TD>
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
						<INPUT TYPE='text' NAME='name' VALUE='' STYLE='width:150px' MAXLENGTH='50'>
					</TD>
				</TR>
				<TR>
					<TD class="separator"></TD>
				</TR>
				<TR>
					<TD>ip * :</TD>
					<TD class="field_separator"></TD>
					<TD>
						<INPUT TYPE='text' NAME='ip_address' VALUE='' STYLE='width:250px' MAXLENGTH='100'>
					</TD>
				</TR>
				<TR>
					<TD class="separator"></TD>
				</TR>
				<TR>
					<TD>entrep&ocirc;t :</TD>
					<TD class="field_separator"></TD>
					<TD>
						<SELECT NAME='warehouse_select' onChange='javascript:addSiteFormOnwarehouseChange()'></SELECT>
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
						<INPUT NAME='warehouse' TYPE='hidden'>
						<INPUT TYPE='submit' NAME='add' VALUE='ajouter' ALT='Ajouter un site'>
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
<SCRIPT type="text/javascript">
	addSiteFormFill()
</SCRIPT>
</FORM>
</BODY>
</HTML>