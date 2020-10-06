<?php
	session_start();
	include_once(dirname(__FILE__).'/../../util/auth.php');
	include_once(dirname(__FILE__).'/../../util/sql.php');
	include_once(dirname(__FILE__).'/../../util/util.php');
	
	if (!is_allowed('admin_sales_admin')) {
		header('Location: ../not_allowed.php');
		exit();
	}

	if (isset($_POST['add']) || isset($_POST['add_x'])) {
		add_sales_admin();
	}

function add_sales_admin() {
	if (($_REQUEST['active'] == '') ||
		($_REQUEST['trigram'] == '')) {
		
		$_REQUEST['message'] = 'Attention : des champs sont manquants!';
	} else {

		$sql_query = 'INSERT INTO sales_admin (id, active, trigram, user_id) ' .
					 'VALUES ' .
					 '(NULL, ' .
					 ''.mysql_format_to_number($_REQUEST['active']).', ' .
					 ''.mysql_format_to_string(strtoupper($_REQUEST['trigram'])).', ' .
					 ''.mysql_format_to_number($_REQUEST['user']).')';

		if (mysql_insert_query($sql_query)) {
			mysql_save_log('ADD_SALES_ADMIN', 'ID : '.mysql_insert_id());
			header('Location: index.php');
		} else {
			mysql_save_sql_error(get_php_self(), 'Ajouter une ADV', $sql_query, mysql_errno(), mysql_error());
			$_REQUEST['message'] = 'Erreur : ADV non ajout&eacute;e!';
		}
	}
}

?>
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN" "http://www.w3.org/TR/html4/loose.dtd">
<html>
<head>
	<title>ELBA - Appli Cellules - Ajouter une ADV</title>
	<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
	<link href="../../css/style.css" rel="stylesheet" type="text/css">
	<SCRIPT type='text/javascript' src='../../scripts/main.js'></SCRIPT>
	<?php mysql_build_javascript_table('SELECT id, CONCAT(first_name, \' \', last_name) as name FROM user ORDER BY name',
	'user'); ?>
</HEAD>
<BODY class="main_body">
<FORM NAME='addSalesAdminForm' ACTION='add.php' METHOD='POST'>
<TABLE class="normal">
	<TR>
		<TD class="main_title_text">Ajouter une ADV</TD>
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
		<TD class="main_sub_section_text">- <A HREF="index.php?history=<?php echo get_php_self(); ?>" TARGET="_self">Liste des ADVs</A></A> -</TD>
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
					<TD class="max_separator"></TD>
				</TR>
				<TR>
					<TD>active * :</TD>
					<TD class="field_separator"></TD>
					<TD>
						<SELECT NAME='active'>
							<OPTION VALUE='1' SELECTED>Oui</OPTION>
							<OPTION VALUE='0'>Non</OPTION>
						</SELECT>
					</TD>
				</TR>
				<TR>
					<TD class="max_separator"></TD>
				</TR>
				<TR>
					<TD>utilisateur :</TD>
					<TD class="field_separator"></TD>
					<TD>
						<SELECT NAME='user_select' onChange='javascript:addSalesAdminFormOnUserChange()'></SELECT>
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
						<INPUT NAME='user' TYPE='hidden'>
						<INPUT TYPE='submit' NAME='add' VALUE='ajouter' ALT='Ajouter une ADV'>
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
	addSalesAdminFormFill()
</SCRIPT>
</FORM>
</BODY>
</HTML>