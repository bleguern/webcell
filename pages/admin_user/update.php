<?php
	session_start();
	include_once(dirname(__FILE__).'/../../util/util.php');
	include_once(dirname(__FILE__).'/../../util/auth.php');
	include_once(dirname(__FILE__).'/../../util/sql.php');
	
	if (!is_allowed('admin_user')) {
		header('Location: ../not_allowed.php');
		exit();
	}

	if ($_REQUEST['id'] == '') {
		header('Location: index.php');
		exit();
	}
	
	if (isset($_POST['update']) || isset($_POST['update_x'])) {
		update_user();
	}
	
function update_user() {
	if (($_REQUEST['active'] == '') ||
		($_REQUEST['login'] == '') ||
		($_REQUEST['role'] == '') ||
		($_REQUEST['site'] == '') ||
		($_REQUEST['windows_account'] == '') ||
		($_REQUEST['email'] == '')) {
		$_REQUEST['message'] = 'Attention : des champs sont manquants!';
	} else {

		$sql_query = 'UPDATE user ' .
					 'SET role_id = '.mysql_format_to_number($_REQUEST['role']).', ' .
					 'site_id = '.mysql_format_to_number($_REQUEST['site']).', ' .
					 'active = '.mysql_format_to_number($_REQUEST['active']).', ' .
					 'login = '.mysql_format_to_string(strtolower($_REQUEST['login'])).', ' .
					 'email = '.mysql_format_to_string(strtolower($_REQUEST['email'])).', ' .
					 'windows_account = '.mysql_format_to_string(strtolower($_REQUEST['windows_account'])).', ' .
					 'first_name = '.mysql_format_to_string(ucwords(strtolower($_REQUEST['first_name']))).', ' .
					 'last_name = '.mysql_format_to_string(strtoupper($_REQUEST['last_name'])).', ' .
					 'telephone = '.mysql_format_to_string($_REQUEST['telephone']).' ' .
					 'WHERE id = '.mysql_format_to_number($_REQUEST['id']).' LIMIT 1';

		if (mysql_update_query($sql_query)) {
			mysql_save_log('UPDATE_USER', 'ID : '.$_REQUEST['id']);
			header('Location: index.php');
		} else {
			mysql_save_sql_error(get_php_self(), 'Modifier un utilisateur', $sql_query, mysql_errno(), mysql_error());
			$_REQUEST['message'] = 'Erreur : utilisateur non modifi&eacute;!';
		}
	}
}

	$sql_query = 'SELECT role_id as role, site_id as site, active, login, email, windows_account, first_name, last_name, telephone FROM user WHERE id = '.mysql_format_to_number($_REQUEST['id']).' LIMIT 1';

	$result = mysql_select_query($sql_query);

	if($result) {
		$user = mysql_fetch_array($result, MYSQL_ASSOC);
	} else {
		mysql_save_sql_error(get_php_self(), 'Chargement de la page modifier un utilisateur', $sql_query, mysql_errno(), mysql_error());
		$_REQUEST['message'] = 'Erreur : chargement de la page &eacute;chou&eacute;!';
	}

?>
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN" "http://www.w3.org/TR/html4/loose.dtd">
<html>
<head>
	<title>ELBA - Appli Cellules - Modifier un utilisateur</title>
	<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
	<link href="../../css/style.css" rel="stylesheet" type="text/css">
	<SCRIPT type='text/javascript' src='../../scripts/main.js'></SCRIPT>
	<?php mysql_build_javascript_table('SELECT id, name FROM role ORDER BY name;
SELECT id, name FROM site ORDER BY name',
'role;
site'); ?>
	<SCRIPT TYPE='text/javascript'>
function validate() {

	if (!checkEmail(updateUserForm.email.value)) {

		alert('Email non valide!')

		return false;
	}

	return true;
}
</SCRIPT>
</HEAD>
<BODY class="main_body">
<FORM NAME='updateUserForm' ACTION='update.php' METHOD='POST' ONSUBMIT='return validate();'>
<TABLE class="normal">
	<TR>
		<TD class="main_title_text">Modifier un utilisateur</TD>
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
					<TD class="main_sub_section_text">- <A HREF="index.php?history=<?php echo get_php_self(); ?>" TARGET="_self">Liste des utilisateurs</A> -</TD>
					<TD class="main_sub_section_text">- <A HREF="add.php?history=<?php echo get_php_self(); ?>" TARGET="_self">Ajouter un utilisateur</A> -</TD>
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
					<TD>email * :</TD>
					<TD class="field_separator"></TD>
					<TD>
						<INPUT TYPE='text' NAME='email' VALUE='<?php echo $user['email']; ?>' STYLE='width:250px' MAXLENGTH='100'>
					</TD>
				</TR>
				<TR>
					<TD class="max_separator"></TD>
				</TR>
				<TR>
					<TD>nom d'utilisateur * :</TD>
					<TD class="field_separator"></TD>
					<TD>
						<INPUT TYPE='text' NAME='login' VALUE='<?php echo $user['login']; ?>' STYLE='width:150px' MAXLENGTH='20'>
					</TD>
				</TR>
				<TR>
					<TD class="max_separator"></TD>
				</TR>
				<TR>
					<TD>actif * :</TD>
					<TD class="field_separator"></TD>
					<TD>
						<SELECT NAME='active'>
<?php

if ($user['active'] == '1') {
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
					<TD>role * :</TD>
					<TD class="field_separator"></TD>
					<TD>
						<SELECT NAME='role_select' onChange='javascript:updateUserFormOnRoleChange()'></SELECT>
					</TD>
				</TR>
				<TR>
					<TD class="separator"></TD>
				</TR>
				<TR>
					<TD>site * :</TD>
					<TD class="field_separator"></TD>
					<TD>
						<SELECT NAME='site_select' onChange='javascript:updateUserFormOnSiteChange()'></SELECT>
					</TD>
				</TR>
				<TR>
					<TD class="max_separator"></TD>
				</TR>
				<TR>
					<TD>compte windows * :</TD>
					<TD class="field_separator"></TD>
					<TD>
						<INPUT TYPE='text' NAME='windows_account' VALUE='<?php echo $user['windows_account']; ?>' STYLE='width:200px' MAXLENGTH='50'>
					</TD>
				</TR>
				<TR>
					<TD class="max_separator"></TD>
				</TR>
				<TR>
					<TD>nom :</TD>
					<TD class="field_separator"></TD>
					<TD>
						<INPUT TYPE='text' NAME='last_name' VALUE='<?php echo $user['last_name']; ?>' STYLE='width:200px' MAXLENGTH='50'>
					</TD>
				</TR>
				<TR>
					<TD class="separator"></TD>
				</TR>
				<TR>
					<TD>pr&eacute;nom :</TD>
					<TD class="field_separator"></TD>
					<TD>
						<INPUT TYPE='text' NAME='first_name' VALUE='<?php echo $user['first_name']; ?>' STYLE='width:200px' MAXLENGTH='50'>
					</TD>
				</TR>
				<TR>
					<TD class="max_separator"></TD>
				</TR>
				<TR>
					<TD>t&eacute;l&eacute;phone :</TD>
					<TD class="field_separator"></TD>
					<TD>
						<INPUT TYPE='text' NAME='telephone' VALUE='<?php echo $user['telephone']; ?>' STYLE='width:150px' MAXLENGTH='20'>
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
						<INPUT NAME='site' TYPE='hidden' VALUE='<?php echo $user['site']; ?>'>
						<INPUT NAME='role' TYPE='hidden' VALUE='<?php echo $user['role']; ?>'>
						<INPUT TYPE='submit' NAME='update' VALUE='modifier' ALT='Modifier un utilisateur'>
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
	updateUserFormFill()
</SCRIPT>
</FORM>
</BODY>
</HTML>