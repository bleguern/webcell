<?php
	session_start();
	include_once(dirname(__FILE__).'/../authentication/index.php');
	
	if (isset($_POST['connect']) || isset($_POST['connect_x'])) {
		connect();
	}
	
function connect() {
	if(($_REQUEST['login'] == '') || 
	   ($_REQUEST['password'] == '')) {
		
		$_REQUEST['message'] = 'Veuillez renseigner les champs : nom d\'utilisateur et mot de passe!';
	
	} else {
	
		$value = logon($_REQUEST['login'], $_REQUEST['password'], $_REQUEST['type']);
	
		if($value == 0) {
			echo '<SCRIPT type=\'text/javascript\'>
				  window.parent.location.reload()
				  </SCRIPT>';
		} else {
			if ($value == 1) {
				$_REQUEST['message'] = 'Nom d\'utilisateur ou mot de passe incorrect!';
			} else if ($value == 2) {
				$_REQUEST['message'] = 'Nom d\'utilisateur incorrect!';
			} else {
				$_REQUEST['message'] = 'Erreur serveur, veuillez contacter l\'administrateur : <A HREF="mailto:benoit.leguern@modling.com" TARGET="_blank">benoit.leguern@modling.com</A>.';
			}
		}
	}
}
	
	
?>
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN" "http://www.w3.org/TR/html4/loose.dtd">
<HTML>
<HEAD>
	<TITLE>ELBA - Appli Cellules - Connexion</TITLE>
	<link rel="stylesheet" href="../css/style.css" type="text/css" />
</HEAD>
<BODY class="main_body">
<FORM ACTION='logon.php' METHOD='POST'>
<CENTER>
<TABLE>
	<TR>
		<TD class="main_title_text">Connexion</TD>
	</TR>
	<tr>
		<td class="main_title"><img src="../image/menu/separator.jpg" border="0"></td>
	</tr>
	<TR>
		<TD class="separator"></TD>
	</TR>
	<TR>
		<TD align="center">
			<TABLE class="center">
				<TR>
					<TD class="field_header">mode :</TD>
					<TD class="field_separator"></TD>
					<TD class="field_value"><SELECT NAME='type'>
										<OPTION VALUE='ldap' SELECTED>Windows (LDAP)</OPTION>
										<OPTION VALUE='local'>Application (local)</OPTION>
									</SELECT>
					</TD>
				</TR>
				<TR>
					<TD class="max_separator"></TD>
				</TR>
				<TR>
					<TD class="field_header">nom d'utilisateur :</TD>
					<TD class="field_separator"></TD>
					<TD class="field_value"><INPUT TYPE='text' NAME='login' VALUE=''></TD>
				</TR>
				<TR>
					<TD class="separator"></TD>
				</TR>
				<TR>
					<TD class="field_header">mot de passe :</TD>
					<TD class="field_separator"></TD>
					<TD class="field_value"><INPUT TYPE='password' NAME='password' VALUE=''></TD>
				</TR>
				<TR>
					<TD class="max_separator"></TD>
				</TR>
			</TABLE>
			<TABLE class="validate">
				<TR>
				  <TD><INPUT TYPE='submit' NAME='connect' VALUE='connexion' ALT='Connexion'></TD>
				</TR>
		  </TABLE>
		</TD>
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
		<td class="main_bottom"><img src="../image/menu/menu_separator.jpg" border="0"></td>
	</tr>
	<TR>
		<TD class="min_separator"></TD>
	</TR>
	<TR>
		<TD class="main_bottom_text"><A HREF="main.php" target="_self">Retour</A></TD>
	</TR>
	<TR>
		<TD class="min_separator"></TD>
	</TR>
	<tr>
		<td class="main_bottom"><img src="../image/menu/menu_separator.jpg" border="0"></td>
	</tr>
</TABLE>
</CENTER>
</FORM>
</BODY>
</HTML>