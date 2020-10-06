<?php
	session_start();
	include_once(dirname(__FILE__).'/../../util/util.php');
	include_once(dirname(__FILE__).'/../../util/auth.php');
	include_once(dirname(__FILE__).'/../../util/sql.php');
	
	if (!is_allowed('admin_machine')) {
		header('Location: ../not_allowed.php');
		exit();
	}

	if (isset($_POST['add']) || isset($_POST['add_x'])) {
		add_machine();
	}

function add_machine() {
	if (($_REQUEST['site'] == '') ||
		($_REQUEST['cell'] == '') ||
		($_REQUEST['active'] == '') ||
		($_REQUEST['name'] == '')) {
		$_REQUEST['message'] = 'Attention : des champs sont manquants!';
	} else {

		$sql_query = 'INSERT INTO machine (id, site_id, cell_id, main_machine_id, active, name, description) ' .
					 'VALUES ' .
					 '(NULL, ' .
					 ''.mysql_format_to_number($_REQUEST['site']).', ' .
					 ''.mysql_format_to_number($_REQUEST['cell']).', ' .
					 ''.mysql_format_to_number($_REQUEST['main_machine']).', ' .
					 ''.mysql_format_to_string($_REQUEST['active']).', ' .
					 ''.mysql_format_to_string($_REQUEST['name']).', ' .
					 ''.mysql_format_to_string($_REQUEST['description']).')';

		if (mysql_insert_query($sql_query)) {
			mysql_save_log('ADD_MACHINE', 'ID : '.mysql_insert_id());
			header('Location: index.php');
		} else {
			mysql_save_sql_error(get_php_self(), 'Ajouter une machine', $sql_query, mysql_errno(), mysql_error());
			$_REQUEST['message'] = 'Erreur : machine non ajout&eacute;e!';
		}
	}
}
?>
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN" "http://www.w3.org/TR/html4/loose.dtd">
<html>
<head>
	<title>ELBA - Appli Cellules - Ajouter une machine</title>
	<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
	<link href="../../css/style.css" rel="stylesheet" type="text/css">
	<SCRIPT type='text/javascript' src='../../scripts/main.js'></SCRIPT>
	<?php mysql_build_javascript_table('SELECT id, name FROM site ORDER BY name;
SELECT cell.id, cell.name, site.id FROM cell LEFT OUTER JOIN site ON cell.site_id = site.id ORDER BY cell.name;
SELECT id, name FROM machine WHERE main_machine_id IS NULL AND active = 1 ORDER BY name',
'site;
cell;
mainMachine'); ?>
</HEAD>
<BODY class="main_body">
<FORM NAME='addMachineForm' ACTION='add.php' METHOD='POST'>
<TABLE class="normal">
	<TR>
		<TD class="main_title_text">Ajouter une machine</TD>
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
		<TD class="main_sub_section_text">- <A HREF="index.php?history=<?php echo get_php_self(); ?>" TARGET="_self">Liste des machines</A> -</TD>
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
					<TD>site * :</TD>
					<TD width="field_separator"><INPUT TYPE="hidden" NAME="site" VALUE="<?php

if (isset($_SESSION['role_id']) && $_SESSION['role_id'] == 0) {
	if (isset($_REQUEST['site'])) {
		echo $_REQUEST['site'];
	}
} else {
	echo $_SESSION['site_id'];
}
?>"></TD>
					<TD class="data">
<?php

if (isset($_SESSION['role_id']) && $_SESSION['role_id'] == 0) {

?>
<SELECT NAME='site_select' onChange='javascript:addMachineFormOnSiteChange()'></SELECT>
<?php

} else {

	echo $_SESSION['site_name']; 

}

?>
</TD>
				</TR>
				<TR>
					<TD class="separator"></TD>
				</TR>
				<TR>
					<TD>cellule * :</TD>
					<TD class="field_separator"></TD>
					<TD>
						<SELECT NAME='cell_select' onChange='javascript:addMachineFormOnCellChange()'></SELECT>
					</TD>
				</TR>
				<TR>
					<TD class="max_separator"></TD>
				</TR>
				<TR>
					<TD>nom * :</TD>
					<TD class="field_separator"></TD>
					<TD>
						<INPUT TYPE='text' NAME='name' VALUE='' STYLE='width:150px' MAXLENGTH='20'>
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
							<OPTION VALUE='1' SELECTED>Oui</OPTION>
							<OPTION VALUE='0'>Non</OPTION>
						</SELECT>
					</TD>
				</TR>
				<TR>
					<TD class="separator"></TD>
				</TR>
				<TR>
					<TD>description :</TD>
					<TD class="field_separator"></TD>
					<TD>
						<INPUT TYPE='text' NAME='description' VALUE='' STYLE='width:250px' MAXLENGTH='200'>
					</TD>
				</TR>
				<TR>
					<TD class="max_separator"></TD>
				</TR>
				<TR>
					<TD>machine principale :</TD>
					<TD class="field_separator"></TD>
					<TD>
						<SELECT NAME='main_machine_select' onChange='javascript:addMachineFormOnMainMachineChange()'></SELECT>
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
						<INPUT NAME='cell' TYPE='hidden'>
						<INPUT NAME='main_machine' TYPE='hidden'>
						<INPUT TYPE='submit' NAME='add' VALUE='ajouter' ALT='Ajouter une machine'>
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
	addMachineFormFill()
</SCRIPT>
</FORM>
</BODY>
</HTML>