<?php
	session_start();
	include_once(dirname(__FILE__).'/../../util/util.php');
	include_once(dirname(__FILE__).'/../../util/auth.php');
	include_once(dirname(__FILE__).'/../../util/sql.php');
	
	if (!is_allowed('admin_sales_admin')) {
		header('Location: ../not_allowed.php');
		exit();
	}

	if ($_REQUEST['id'] == '') {
		header('Location: index.php');
		exit();
	}
	
	if (isset($_POST['update']) || isset($_POST['update_x'])) {
		update_sales_admin();
	}
	
function update_sales_admin() {
	if (($_REQUEST['active'] == '') ||
		($_REQUEST['trigram'] == '')) {
		$_REQUEST['message'] = 'Attention : des champs sont manquants!';
	} else {

		$sql_query = 'UPDATE sales_admin ' .
					 'SET active = '.mysql_format_to_number($_REQUEST['active']).', ' .
					 'trigram = '.mysql_format_to_string(strtoupper($_REQUEST['trigram'])).', ' .
					 'user_id = '.mysql_format_to_number($_REQUEST['user']).' ' .
					 'WHERE id = '.mysql_format_to_number($_REQUEST['id']).' LIMIT 1';

		if (mysql_update_query($sql_query)) {
			mysql_save_log('UPDATE_SALES_ADMIN', 'ID : '.$_REQUEST['id']);
			header('Location: index.php');
		} else {
			mysql_save_sql_error(get_php_self(), 'Modifier une ADV', $sql_query, mysql_errno(), mysql_error());
			$_REQUEST['message'] = 'Erreur : ADV non modifi&eacute;e!';
		}
	}
}

	$sql_query = 'SELECT active, trigram, user_id as user FROM sales_admin WHERE id = '.mysql_format_to_number($_REQUEST['id']).' LIMIT 1';

	$result = mysql_select_query($sql_query);

	if($result) {
		$sales_admin = mysql_fetch_array($result, MYSQL_ASSOC);
	} else {
		mysql_save_sql_error(get_php_self(), 'Chargement de la page modifier une ADV', $sql_query, mysql_errno(), mysql_error());
		$_REQUEST['message'] = 'Erreur : chargement de la page &eacute;chou&eacute;!';
	}

?>
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN" "http://www.w3.org/TR/html4/loose.dtd">
<html>
<head>
	<title>ELBA - Appli Cellules - Modifier une ADV</title>
	<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
	<link href="../../css/style.css" rel="stylesheet" type="text/css">
	<SCRIPT type='text/javascript' src='../../scripts/main.js'></SCRIPT>
	<?php mysql_build_javascript_table('SELECT id, CONCAT(first_name, \' \', last_name) as name FROM user ORDER BY name',
'user'); ?>
</HEAD>
<BODY class="main_body">
<FORM NAME='updateSalesAdminForm' ACTION='update.php' METHOD='POST'>
<TABLE class="normal">
	<TR>
		<TD class="main_title_text">Modifier une ADV</TD>
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
					<TD class="main_sub_section_text">- <A HREF="index.php?history=<?php echo get_php_self(); ?>" TARGET="_self">Liste des ADVs</A> -</TD>
					<TD class="main_sub_section_text">- <A HREF="add.php?history=<?php echo get_php_self(); ?>" TARGET="_self">Ajouter une ADV</A> -</TD>
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
					<TD>trigram * :</TD>
					<TD class="field_separator"></TD>
					<TD>
						<INPUT TYPE='text' NAME='trigram' VALUE='<?php echo $sales_admin['trigram']; ?>' STYLE='width:50px' MAXLENGTH='3'>
					</TD>
				</TR>
				<TR>
					<TD class="separator"></TD>
				</TR>
				<TR>
					<TD>active * :</TD>
					<TD class="field_separator"></TD>
					<TD>
						<SELECT NAME='active'>
<?php

if ($sales_admin['active'] == '1') {
	echo '<OPTION VALUE=\'1\' SELECTED>Oui</OPTION>' .
			'<OPTION VALUE=\'0\'>Non</OPTION>';
} else {
	echo '<OPTION VALUE=\'1\'>Oui</OPTION>' .
			'<OPTION VALUE=\'0\' SELECTED>Non</OPTION>';
}

?>
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
						<SELECT NAME='user_select' onChange='javascript:updateSalesAdminFormOnUserChange()'></SELECT>
					</TD>
				</TR>
				<TR>
					<TD class="max_separator"></TD>
				</TR>
			</TABLE>
			<TABLE class="normal">
				<TR align="center">
					<TD>
						<INPUT NAME='id' TYPE='hidden' VALUE='<?php echo $_REQUEST['id']; ?>'>
						<INPUT NAME='user' TYPE='hidden' VALUE='<?php echo $sales_admin['user']; ?>'>
						<INPUT TYPE='submit' NAME='update' VALUE='modifier' ALT='Modifier une ADV'>
					</TD>
				</TR>
				<TR>
					<TD class="max_separator"></TD>
				</TR>
			</TABLE>
			<TABLE>
				<TR>
					<TD>* : champs obligatoires</TD>
				</TR>
				<TR>
					<TD class="separator"></TD>
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
	updateSalesAdminFormFill()
</SCRIPT>
</FORM>
</BODY>
</HTML>