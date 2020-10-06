<?php
	session_start();
	include_once(dirname(__FILE__).'/../util/auth.php');
	include_once(dirname(__FILE__).'/../util/util.php');
	
	if (isset($_COOKIE['width_resolution']))
	{
		$ = '_800x600.css';
		
		if ($_COOKIE['width_resolution'] == '1024' || $_COOKIE['width_resolution'] == '1280')
		{
			$ = '_1024x768.css';
		}
		else if ($_COOKIE['width_resolution'] == '640' || $_COOKIE['width_resolution'] == '800')
		{
			$ = '_800x600.css';
		}
		else
		{
			$ = '_800x600.css';
		}
		
		$_SESSION['css'] = $;
	}
	else
	{

?>
<SCRIPT LANGUAGE="javascript">
	var screen_information = "width_resolution=" + screen.width + ";"
	document.cookie = screen_information
	location = '<?php echo $_SERVER['PHP_SELF']; ?>'
</SCRIPT>
<?php

	}
	
	get_site();
	get_horizon();
?>	
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN" "http://www.w3.org/TR/html4/loose.dtd">
<HTML>
<HEAD>
	<TITLE>Web Appli Cellules</TITLE>
	<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
	<link href="../css/style.css" rel="stylesheet" type="text/css">
</HEAD>
<BODY class="menu_body">
	<CENTER>
		<TABLE class="normal">
			<TR>
				<TD class="menu_title"><a href="main.php" target="main">Web<BR>Appli Cellules</a></TD>
			</TR>
			<TR>
				<TD class="max_separator"></TD>
			</TR>
			<TR>
				<TD class="main"><a href="main.php" target="main"><img src="../image/elba_menu.jpg"></a></TD>
			</TR>
			<TR>
				<TD class="max_separator"></TD>
			</TR>
			<TR>
				<TD class="menu_title"><a href="main.php" target="main">Ecran principal</a></TD>
			</TR>
			<TR>
				<TD class="max_separator"></TD>
			</TR>
<?php

if (is_allowed('admin')) {

?>
  <TR>
    <TD class="menu"><A HREF="admin/index.php" TARGET="_parent">- Administration -</A></TD>
  </TR>
  <TR>
		<TD class="max_separator"></TD>
	</TR>
<?php

}

?>
			<TR>
				<TD class="menu"><A HREF="horizon/index.php" TARGET="main">- Horizon -</A><BR>
<?php

if (isset($_SESSION['horizon'])) {
	echo '<FONT SIZE="1">'.$_SESSION['horizon'].' jour(s)</FONT>';
} else {
	echo '<FONT SIZE="1">Erreur!</FONT>';
}

if (isset($_SESSION['horizon_sub'])) {
	echo '<FONT SIZE="1"><BR>STT : '.$_SESSION['horizon_sub'].' jour(s)</FONT>';
} else {
	echo '<FONT SIZE="1"><BR>STT : Erreur!</FONT>';
}

?>	
				</TD>
			</TR>
			<TR>
				<TD class="separator"></TD>
			</TR>
			<TR>
				<TD class="menu">
<?php

if (isset($_SESSION['site_id'])) {
	if (is_allowed('site')) {
		echo '<A HREF=\'site/index.php?site_id='.$_SESSION['site_id'].'\' TARGET=\'main\'>- Site -</A><BR><FONT SIZE="1">'.$_SESSION['site_name'].'</FONT>';
	} else {
		echo '- Site -<BR><FONT SIZE="1">'.$_SESSION['site_name'].'</FONT>';
	}
} else {
	if (is_allowed('site')) {
		echo '<A HREF=\'site/index.php\' TARGET=\'main\'>- Site -</A><BR><FONT SIZE="1">Erreur!</FONT>';
	} else {
		echo '- Site -<BR><FONT SIZE="1">Erreur!</FONT>';
	}
}

?>
				</TD>
			</TR>
			<TR>
				<TD class="separator"></TD>
			</TR>
			<TR>
				<TD class="menu">
<?php
	
	if (isset($_SESSION['authenticated'])) {
		echo '<A HREF=\'logoff.php\' TARGET=\'_parent\'>- Deconnexion -</A><BR><FONT SIZE="1">'.$_SESSION['login'].'<BR>(IP : '.$_SERVER['REMOTE_ADDR'].')</FONT>';
	} else {
		echo '<A HREF=\'logon.php\' TARGET=\'main\'">- Connexion -</A><BR><FONT SIZE="1">anonyme<BR>(IP : '.$_SERVER['REMOTE_ADDR'].')<BR></FONT>';
	}
	
?>
				</TD>
			</TR>
<?php
	
	if (is_allowed('password')) {
	
?>

	<TR>
		<TD class="separator"></TD>
	</TR>
	<TR>
		<TD class="menu"><A HREF='password/index.php' TARGET='main'>- Mot de passe -</A></TD>
	</TR>
<?php

}
	
?>
			<TR>
				<TD class="max_separator"></TD>
			</TR>
			<TR>
				<TD class="menu_title"><a href="#" onClick="javascript:window.parent.close();">Quitter</a></TD>
			</TR>
			<TR>
				<TD class="max_separator"></TD>
			</TR>
			<TR>
				<TD class="max_separator"></TD>
			</TR>
			<TR>
				<TD class="legend">
					<TABLE class="normal">
						<TR>
							<TD class="menu" align="center">- Legende -</TD>
						</TR>
						<TR>
							<TD class="min_separator"></TD>
						</TR>
						<TR>
							<TD>
								<TABLE class="normal">
									<TR>
										<TD><img src="../image/printer_little.jpg" alt="Impression"></TD>
										<TD class="field_separator"></TD>
										<TD> : Impression</TD>
									</TR>
									<TR>
										<TD class="min_separator"></TD>
									</TR>
									<TR>
										<TD><img src="../image/excel_little.jpg" alt="Exportation Excel"></TD>
										<TD class="field_separator"></TD>
										<TD> : Exportation Excel</TD>
									</TR>
									<TR>
										<TD class="min_separator"></TD>
									</TR>
									<TR>
										<TD><img src="../image/view_little.jpg" alt="Affichage"></TD>
										<TD class="field_separator"></TD>
										<TD> : Affichage</TD>
									</TR>
									<TR>
										<TD class="min_separator"></TD>
									</TR>
									<TR height="18">
										<TD class="blocked" width="18"></TD>
										<TD class="field_separator"></TD>
										<TD> : Bloqu&eacute;e</TD>
									</TR>
									<TR>
										<TD class="min_separator"></TD>
									</TR>
									<TR height="18">
										<TD class="out" width="18"></TD>
										<TD class="field_separator"></TD>
										<TD> : Rupture</TD>
									</TR>
									<TR>
										<TD class="min_separator"></TD>
									</TR>
									<TR height="18">
										<TD class="out_blocked" width="18"></TD>
										<TD class="field_separator"></TD>
										<TD> : Bloqu&eacute;e, en rupture</TD>
									</TR>
									<TR>
										<TD class="min_separator"></TD>
									</TR>
									<TR height="18">
										<TD class="sub" width="18"></TD>
										<TD class="field_separator"></TD>
										<TD> : Sous-traitance</TD>
									</TR>
								</TABLE>
							</TD>
						</TR>
					</TABLE>
				</TD>
			</TR>
			<TR>
				<TD class="separator"></TD>
			</TR>
		</TABLE>
	</CENTER>
</BODY>
</HTML>