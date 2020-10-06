<?php
	session_start();
	include_once(dirname(__FILE__).'/../../util/auth.php');
	include_once(dirname(__FILE__).'/../../util/sql.php');
	include_once(dirname(__FILE__).'/../../util/util.php');
	
	if (!is_allowed('password')) {
		header('Location: ../not_allowed.php');
		exit();
	}

	if (isset($_POST['update']) || isset($_POST['update_x'])) {
		update_password();
	}
	
	if (!isset($_REQUEST['history']) || ($_REQUEST['history'] == '')) {
		$_REQUEST['history'] = '/webcell/pages/main.php';
	}

function update_password() {
	if (($_REQUEST['password'] == '') ||
		($_REQUEST['password2'] == '')) {
		$_REQUEST['message'] = 'Attention : des champs sont manquants!';
	} else {

		if ($_REQUEST['password'] != $_REQUEST['password2']) {
			$_REQUEST['message'] = 'Attention : les deux mots de passe sont diff&eacute;rents!';
		} else {
			$password = '';
	
		if($_REQUEST['password'] != '') {
	
				$password = crypt_password($_REQUEST['password']);
			}
			
			$sql_query = 'UPDATE user ' .
						 'SET password = '.mysql_format_to_string($password).' ' .
						 'WHERE id = '.mysql_format_to_number($_SESSION['user_id']).' LIMIT 1';
	
			if (mysql_update_query($sql_query)) {
				mysql_save_log('UPDATE_PASSWORD', 'ID : '.mysql_insert_id());
				
				echo '<SCRIPT type=\'text/javascript\'>' .
					 'window.parent.location.reload()' .
					 '</SCRIPT>';
			} else {
				mysql_save_sql_error(get_php_self(), 'Modifier son mot de passe', $sql_query, mysql_errno(), mysql_error());
				$_REQUEST['message'] = 'Erreur : mot de passe non modifi&eacute;!';
			}
		}
	}
}

?>
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN" "http://www.w3.org/TR/html4/loose.dtd">
<html>
<head>
	<title>ELBA - Appli Cellules - Mot de passe</title>
	<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
	<link href="../../css/style.css" rel="stylesheet" type="text/css">
	<SCRIPT type='text/javascript' src='../../scripts/main.js'></SCRIPT>
</HEAD>
<BODY class="main_body">
<FORM NAME='updatePasswordForm' ACTION='index.php' METHOD='POST'>
<CENTER>
<TABLE>
	<TR>
		<TD class="main_title_text">Mot de passe</TD>
	</TR>
	<tr>
		<td class="main_title"><img src="../../image/menu/separator.jpg" border="0"></td>
	</tr>
	<TR>
		<TD class="separator"></TD>
	</TR>
	<TR>
		<TD align="center">
			<TABLE class="center">
				<TR>
					<TD align="center">
						<TABLE class="center">
							<TR>
								<TD class="field_header">nouveau mot de passe * :</TD>
								<TD class="field_separator"></TD>
								<TD class="field_value">
									<INPUT TYPE='password' NAME='password' STYLE='width:150px' MAXLENGTH='20'>
								</TD>
							</TR>
							<TR>
								<TD class="min_separator"></TD>
							</TR>
							<TR>
								<TD class="field_header">nouveau mot de passe (2) * :</TD>
								<TD class="field_separator"></TD>
								<TD class="field_value">
									<INPUT TYPE='password' NAME='password2' STYLE='width:150px' MAXLENGTH='20'>
								</TD>
							</TR>
						</TABLE>
					</TD>
				</TR>
				<TR>
					<TD class="max_separator"></TD>
				</TR>
				<TR>
					<TD>
						<INPUT TYPE='submit' NAME='update' VALUE='modifier' ALT='Modifier son mot de passe'>
					</TD>
				</TR>
			 </TABLE>
		</TD>
	</TR>
	<TR>
		<TD class="separator"></TD>
	</TR>
	<TR>
		<TD>* : champs obligatoires</TD>
	</TR>
	<TR>
		<TD class="separator"></TD>
	</TR>
	<TR>
		<TD class="message">
<?php

if(isset($_REQUEST['message'])) {
echo $_REQUEST['message'];
}

?>
		</TD>
	</TR>
	<TR>
		<TD class="separator"></TD>
	</TR>
	<tr>
		<td class="main_bottom"><img src="../../image/menu/menu_separator.jpg" border="0"></td>
	</tr>
	<TR>
		<TD class="min_separator"></TD>
	</TR>
	<TR>
		<TD class="main_bottom_text"><A HREF="<?php echo $_REQUEST['history']; ?>" target="_self">Retour</A></TD>
	</TR>
	<TR>
		<TD class="min_separator"></TD>
	</TR>
	<tr>
		<td class="main_bottom"><img src="../../image/menu/menu_separator.jpg" border="0"></td>
	</tr>
</TABLE>
</CENTER>
</FORM>
</BODY>
</HTML>