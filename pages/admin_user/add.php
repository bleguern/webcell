<?php
	session_start();
	include_once(dirname(__FILE__).'/../../util/auth.php');
	include_once(dirname(__FILE__).'/../../util/sql.php');
	include_once(dirname(__FILE__).'/../../util/util.php');
	
	if (!is_allowed('admin_user')) {
		header('Location: ../not_allowed.php');
		exit();
	}

	if (isset($_POST['add']) || isset($_POST['add_x'])) {
		add_user();
	}

function add_user() {
	if (($_REQUEST['active'] == '') ||
		($_REQUEST['login'] == '') ||
		($_REQUEST['role'] == '') ||
		($_REQUEST['site'] == '') ||
		($_REQUEST['windows_account'] == '') ||
		($_REQUEST['email'] == '')) {
		
		$_REQUEST['message'] = 'Attention : des champs sont manquants!';
	} else {

		$password = '';

		if($_REQUEST['password'] != '') {

			$password = crypt_password($_REQUEST['password']);
		}
		
		$sql_query = 'INSERT INTO user (id, role_id, site_id, active, login, password, email, windows_account, first_name, last_name, telephone) ' .
					 'VALUES ' .
					 '(NULL, ' .
					 ''.mysql_format_to_number($_REQUEST['role']).', ' .
					 ''.mysql_format_to_number($_REQUEST['site']).', ' .
					 ''.mysql_format_to_number($_REQUEST['active']).', ' .
					 ''.mysql_format_to_string(strtolower($_REQUEST['login'])).', ' .
					 ''.mysql_format_to_string($password).', ' .
					 ''.mysql_format_to_string(strtolower($_REQUEST['email'])).', ' .
					 ''.mysql_format_to_string(strtolower($_REQUEST['windows_account'])).', ' .
					 ''.mysql_format_to_string(ucwords(strtolower($_REQUEST['first_name']))).', ' .
					 ''.mysql_format_to_string(strtoupper($_REQUEST['last_name'])).', ' .
					 ''.mysql_format_to_string($_REQUEST['telephone']).')';

		if (mysql_insert_query($sql_query)) {
			mysql_save_log('ADD_USER', 'ID : '.mysql_insert_id());
			header('Location: index.php');
		} else {
			mysql_save_sql_error(get_php_self(), 'Ajouter un utilisateur', $sql_query, mysql_errno(), mysql_error());
			$_REQUEST['message'] = 'Erreur : utilisateur non ajout&eacute;!';
		}
	}
}

?>
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN" "http://www.w3.org/TR/html4/loose.dtd">
<html>
<head>
	<title>ELBA - Appli Cellules - Ajouter un utilisateur</title>
	<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
	<link href="../../css/style.css" rel="stylesheet" type="text/css">
	<SCRIPT type='text/javascript' src='../../scripts/main.js'></SCRIPT>
	<?php mysql_build_javascript_table('SELECT id, name FROM role ORDER BY name;
	SELECT id, name FROM site ORDER BY name',
	'role;
	site'); ?>
	<SCRIPT TYPE='text/javascript'>
function validate() {

	if ((addUserForm.password.value != '') &&
		(addUserForm.password2.value == '')) {

		alert('Veuillez remplir le champ mot de passe (2)!')

		return false;
	}

	if (addUserForm.password.value != addUserForm.password2.value) {

		alert('Les deux champs mot de passes sont diff&eacute;rents!')

		return false;
	}

	if (!checkEmail(addUserForm.email.value)) {

		alert('Email non valide!')

		return false;
	}

	return true;
}

</SCRIPT>
</HEAD>
<BODY class="main_body">
<FORM NAME='addUserForm' ACTION='add.php' METHOD='POST' ONSUBMIT='return validate();'>
<TABLE class="normal">
	<TR>
		<TD class="main_title_text">Ajouter un utilisateur</TD>
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
		<TD class="main_sub_section_text">- <A HREF="index.php?history=<?php echo get_php_self(); ?>" TARGET="_self">Liste des utilisateurs</A> -</TD>
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
						<INPUT TYPE='text' NAME='email' VALUE='@modling.com' STYLE='width:250px' MAXLENGTH='100'>
					</TD>
				</TR>
				<TR>
					<TD class="max_separator"></TD>
				</TR>
				<TR>
					<TD>nom d'utilisateur * :</TD>
					<TD class="field_separator"></TD>
					<TD>
						<INPUT TYPE='text' NAME='login' VALUE='' STYLE='width:150px' MAXLENGTH='20'>
					</TD>
				</TR>
				<TR>
					<TD class="separator"></TD>
				</TR>
				<TR>
					<TD>mot de passe :</TD>
					<TD class="field_separator"></TD>
					<TD>
						<INPUT TYPE='password' NAME='password' VALUE='' STYLE='width:150px' MAXLENGTH='20'>
					</TD>
				</TR>
				<TR>
					<TD class="separator"></TD>
				</TR>
				<TR>
					<TD>mot de passe (2) :</TD>
					<TD class="field_separator"></TD>
					<TD>
						<INPUT TYPE='password' NAME='password2' VALUE='' STYLE='width:150px' MAXLENGTH='20'>
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
							<OPTION VALUE='1' SELECTED>Oui</OPTION>
							<OPTION VALUE='0'>Non</OPTION>
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
						<SELECT NAME='role_select' onChange='javascript:addUserFormOnRoleChange()'></SELECT>
					</TD>
				</TR>
				<TR>
					<TD class="separator"></TD>
				</TR>
				<TR>
					<TD>site * :</TD>
					<TD class="field_separator"></TD>
					<TD>
						<SELECT NAME='site_select' onChange='javascript:addUserFormOnSiteChange()'></SELECT>
					</TD>
				</TR>
				<TR>
					<TD class="max_separator"></TD>
				</TR>
				<TR>
					<TD>compte windows * :</TD>
					<TD class="field_separator"></TD>
					<TD>
						<INPUT TYPE='text' NAME='windows_account' VALUE='' STYLE='width:200px' MAXLENGTH='50'>
					</TD>
				</TR>
				<TR>
					<TD class="max_separator"></TD>
				</TR>
				<TR>
					<TD>nom :</TD>
					<TD class="field_separator"></TD>
					<TD>
						<INPUT TYPE='text' NAME='last_name' VALUE='' STYLE='width:200px' MAXLENGTH='50'>
					</TD>
				</TR>
				<TR>
					<TD class="separator"></TD>
				</TR>
				<TR>
					<TD>pr&eacute;nom :</TD>
					<TD class="field_separator"></TD>
					<TD>
						<INPUT TYPE='text' NAME='first_name' VALUE='' STYLE='width:200px' MAXLENGTH='50'>
					</TD>
				</TR>
				<TR>
					<TD class="max_separator"></TD>
				</TR>
				<TR>
					<TD>t&eacute;l&eacute;phone :</TD>
					<TD class="field_separator"></TD>
					<TD>
						<INPUT TYPE='text' NAME='telephone' VALUE='' STYLE='width:150px' MAXLENGTH='20'>
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
						<INPUT NAME='site' TYPE='hidden'>
						<INPUT NAME='role' TYPE='hidden'>
						<INPUT TYPE='submit' NAME='add' VALUE='ajouter' ALT='Ajouter un utilisateur'>
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
	addUserFormFill()
</SCRIPT>
</FORM>
</BODY>
</HTML>