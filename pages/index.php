<?php
	session_start();
	include_once(dirname(__FILE__).'/../util/util.php');
	include_once(dirname(__FILE__).'/../util/auth.php');
	include_once(dirname(__FILE__).'/../util/sql.php');
	
	get_site();
	get_horizon();
	
	mysql_save_log('CONNECTED', '');
?>	
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN" "http://www.w3.org/TR/html4/loose.dtd">
<html>
<head>
	<title>ELBA - Appli Cellules</title>
	<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
	<link href="../css/style.css" rel="stylesheet" type="text/css">
	<SCRIPT type='text/javascript' src='../scripts/main.js'></SCRIPT>
</HEAD>
<body class="main_body">
<CENTER>
<TABLE class="center">
	<tr>
		<td width="950">
			<table>
				<tr>
					<td width="200" height="70" nowrap><img src="../image/menu/top_menu.jpg"></td>
					<td width="750" background="../image/menu/top_menu_back.jpg" class="title">ELBA - Appli Cellules</td>
				</tr>
			</table>
		</td>
	</tr>
	<tr>
		<td width="950">
			<TABLE>
				<tr>
					<td>
						<TABLE>
							<tr>
								<td align="left" width="610"><iframe src="update.php" width="610" height="15" name="update" frameborder="0" marginwidth="0"></iframe></td>
								<td align="right" width="340"><FONT SIZE="1">
		<?php
		
		if (isset($_SESSION['authenticated']) && ($_SESSION['authenticated'] == 1)) {
		echo 'Connect&eacute;(e) en tant que '.$_SESSION['user_name'].', <A HREF="logoff.php?history=/webcell/pages/main.php" TARGET="_self">se d&eacute;connecter...</A>';
		} else {
		echo '<A HREF="logon.php?history=/webcell/pages/main.php" TARGET="main">se connecter...</A>';
		}
		
		?>
									</FONT>
								</td>
							</tr>
						</table>
					</td>
				</tr>
				<tr>
					<td height="5"><input type="hidden" name="site_trigram" value="<?php echo strtolower($_SESSION['site_trigram']); ?>"></td>
				</tr>
				<tr height="100%">
					<TD width="100%" height="100%">
						<TABLE>
							<tr height="100%">
								<td width="149" height="100%" valign="top" align="center">
									<table width="100%" height="100%"  border="0" cellpadding="0" cellspacing="0">
										<tr>
											<td class="separator"></td>
										</tr>
										<TR>
											<TD class="menu_title_text">- <a href="main.php" target="main">Ecran principal</a> -</TD>
										</TR>
										<tr>
											<td class="separator"></td>
										</tr>
										<tr>
											<td class="menu"><img src="../image/menu/menu_separator.jpg" border="0"></td>
										</tr>
										<tr>
											<td class="separator"></td>
										</tr>

<?php

if (is_allowed('admin')) {

?>
										<TR>
											<TD class="menu_section_text">- <A HREF="admin/index.php?history=/webcell/pages/index.php" TARGET="_self">Administration</A> -</TD>
										</TR>
										<tr>
											<td class="separator"></td>
										</tr>
										<tr>
											<td class="menu"><img src="../image/menu/menu_separator.jpg" border="0"></td>
										</tr>
										<tr>
											<td class="separator"></td>
										</tr>
<?php

}

?>
										
										
										<TR>
											<TD class="menu_section_text">- <A HREF="horizon/index.php?history=/webcell/pages/main.php" TARGET="main">Horizon</A> -</TD>
										</TR>
										<tr>
											<td class="min_separator"></td>
										</tr>
										<TR>
											<TD class="menu_value">
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
										<tr>
											<td class="separator"></td>
										</tr>
										<tr>
											<td class="menu"><img src="../image/menu/menu_separator.jpg" border="0"></td>
										</tr>
										<tr>
											<td class="separator"></td>
										</tr>
										<TR>
											<TD class="menu_section_text">
<?php

if (is_allowed('site')) {
	echo '- <A HREF=\'site/index.php?site_id='.$_SESSION['site_id'].'&history=/webcell/pages/main.php\' TARGET=\'main\'>Site</A> -';
} else {
	echo 'Site';
}

?>
											</TD>
										</TR>
										<tr>
											<td class="min_separator"></td>
										</tr>
										<TR>
											<TD class="menu_value">
<?php

if (isset($_SESSION['site_id'])) {
	echo $_SESSION['site_name'];
} else {
	echo 'Erreur!';
}

?>
											</TD>
										</TR>
<?php

if (is_allowed('password')) {

?>
										<tr>
											<td class="separator"></td>
										</tr>
										<tr>
											<td class="menu"><img src="../image/menu/menu_separator.jpg" border="0"></td>
										</tr>
										<tr>
											<td class="separator"></td>
										</tr>
										<TR>
											<TD class="menu_section_text">- <A HREF='password/index.php?history=/webcell/pages/main.php' TARGET='main'>Mot de passe</A> -</TD>
										</TR>
<?php

}

?>
										<tr>
											<td class="separator"></td>
										</tr>
										<tr>
											<td class="menu"><img src="../image/menu/menu_separator.jpg" border="0"></td>
										</tr>
										<tr>
											<td class="separator"></td>
										</tr>
										<TR>
											<TD class="menu_section_text">- <A HREF='user/index.php?history=/webcell/pages/main.php' TARGET='main'>Utilisateurs</A> -</TD>
										</TR>
										<tr>
											<td class="separator"></td>
										</tr>
										<tr>
											<td class="menu"><img src="../image/menu/menu_separator.jpg" border="0"></td>
										</tr>
										<tr>
											<td class="separator"></td>
										</tr>
										<TR>
											<TD class="menu_title_text">- <a href="javascript:self.close();" TARGET="_parent">Quitter</a> -</TD>
										</TR>
										<tr>
											<td class="separator"></td>
										</tr>
										<tr>
											<td class="menu"><img src="../image/menu/menu_separator.jpg" border="0"></td>
										</tr>
										<tr>
											<td class="max_separator"></td>
										</tr>
										<TR>
											<TD class="legend" width="100%">
												<TABLE>
													<TR>
														<TD class="menu_section_text" align="center">- <A HREF="legend/index.php?history=/webcell/pages/main.php" TARGET="main">Legende</A> -</TD>
													</TR>
													<TR>
														<TD class="min_separator"></TD>
													</TR>
													<TR>
														<TD>
															<TABLE>
																<TR>
																	<TD class="menu_legend_header"><A HREF="legend/index.php?history=/webcell/pages/main.php" TARGET="main"><img src="../image/printer_little.jpg" alt="Impression"></A></TD>
																	<TD class="field_separator"></TD>
																	<TD class="menu_legend_value"><A HREF="legend/index.php?history=/webcell/pages/main.php" TARGET="main"> : Impression</A></TD>
																</TR>
																<TR>
																	<TD class="min_separator"></TD>
																</TR>
																<TR>
																	<TD class="menu_legend_header"><A HREF="legend/index.php?history=/webcell/pages/main.php" TARGET="main"><img src="../image/excel_little.jpg" alt="Exportation Excel"></A></TD>
																	<TD class="field_separator"></TD>
																	<TD class="menu_legend_value"><A HREF="legend/index.php?history=/webcell/pages/main.php" TARGET="main"> : Exportation Excel</A></TD>
																</TR>
																<TR>
																	<TD class="min_separator"></TD>
																</TR>
																<TR>
																	<TD class="menu_legend_header"><A HREF="legend/index.php?history=/webcell/pages/main.php" TARGET="main"><img src="../image/view_little.jpg" alt="Affichage"></A></TD>
																	<TD class="field_separator"></TD>
																	<TD class="menu_legend_value"><A HREF="legend/index.php?history=/webcell/pages/main.php" TARGET="main"> : Affichage</A></TD>
																</TR>
																<TR>
																	<TD class="min_separator"></TD>
																</TR>
																<TR height="18">
																	<TD class="menu_legend_header"><A HREF="legend/index.php?history=/webcell/pages/main.php" TARGET="main"><img src="../image/menu/blocked.jpg" alt="Bloqu&eacute;e"></A></TD>
																	<TD class="field_separator"></TD>
																	<TD class="menu_legend_value"><A HREF="legend/index.php?history=/webcell/pages/main.php" TARGET="main"> : Bloqu&eacute;e</A></TD>
																</TR>
																<TR>
																	<TD class="min_separator"></TD>
																</TR>
																<TR height="18">
																	<TD class="menu_legend_header"><A HREF="legend/index.php?history=/webcell/pages/main.php" TARGET="main"><img src="../image/menu/out.jpg" alt="Rupture"></A></TD>
																	<TD class="field_separator"></TD>
																	<TD class="menu_legend_value"><A HREF="legend/index.php?history=/webcell/pages/main.php" TARGET="main"> : Rupture</A></TD>
																</TR>
																<TR>
																	<TD class="min_separator"></TD>
																</TR>
																<TR height="18">
																	<TD class="menu_legend_header"><A HREF="legend/index.php?history=/webcell/pages/main.php" TARGET="main"><img src="../image/menu/blocked_out.jpg" alt="Bloqu&eacute;e, en rupture"></A></TD>
																	<TD class="field_separator"></TD>
																	<TD class="menu_legend_value"><A HREF="legend/index.php?history=/webcell/pages/main.php" TARGET="main"> : Bloqu&eacute;e, en rupture</A></TD>
																</TR>
																<TR>
																	<TD class="min_separator"></TD>
																</TR>
																<TR height="18">
																	<TD class="sub" width="18"><A HREF="legend/index.php?history=/webcell/pages/main.php" TARGET="main"><img src="../image/menu/sub.jpg" alt="Sous-traitance"></A></TD>
																	<TD class="field_separator"></TD>
																	<TD class="menu_legend_value"><A HREF="legend/index.php?history=/webcell/pages/main.php" TARGET="main"> : Sous-traitance</A></TD>
																</TR>
															</TABLE>
														</TD>
													</TR>
												</TABLE>
											</TD>
										</TR>
									</table>
								</td>
								<TD width="10">
									<TABLE class="center" cellpadding="0" cellspacing="0">
										<TR>
											<TD width="1" height="25"><img src="../image/menu/main_section_top_center.jpg"></TD>
										</TR>
										<TR>
											<TD width="1" height="400" background="../image/menu/pix.jpg" ></TD>
										</TR>
										<TR>
											<TD width="1" height="25"><img src="../image/menu/main_section_bottom_center.jpg"></TD>
										</TR>
									</TABLE>
								</TD>
								<TD width="800" height="470" align="left">
									<iframe src="main.php" width="800" height="470" name="main" frameborder="0" marginwidth="0"></iframe>
								</TD>
							</tr>
						</table>
					</TD>
				</TR>
				<tr>
					<td height="5"></td>
				</tr>
			</table>
		</td>
	</tr>
	<tr>
		<td width="950">
			<TABLE>
				<tr>
					<td width="200" height="40" background="../image/menu/bottom_menu.jpg">&nbsp;</td>
					<td width="715" height="40" background="../image/menu/bottom_menu_back.jpg" class="title">&nbsp;</td>
					<td width="5">&nbsp;</td>
					<td width="30" height="40" align="right"><FONT SIZE="1">
<?php

$value = mysql_simple_select_query('SELECT UPPER(number) FROM version ORDER BY version_date DESC LIMIT 1');

if ($value) {
echo $value;
} else {
echo 'Erreur!';
}

?>
							</FONT>
					</td>
				</tr>
			</table>
		</td>
	</tr>
</table>
</CENTER>
</body>
</html>