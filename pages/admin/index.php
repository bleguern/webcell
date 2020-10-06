<?php
	session_start();
	include_once(dirname(__FILE__).'/../../util/auth.php');
	include_once(dirname(__FILE__).'/../../util/util.php');
	include_once(dirname(__FILE__).'/../../util/sql.php');
	
	if (!is_allowed('admin')) {
		header('Location: ../not_allowed.php');
		exit();
	}
?>	
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN" "http://www.w3.org/TR/html4/loose.dtd">
<html>
<head>
	<title>ELBA - Appli Cellules - Administration</title>
	<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
	<link href="../../css/style.css" rel="stylesheet" type="text/css">
	<SCRIPT type='text/javascript' src='../../scripts/main.js'></SCRIPT>
</HEAD>
<body class="main_body">
<CENTER>
<TABLE class="center">
	<tr>
		<td width="950">
			<table border="0" cellpadding="0" cellspacing="0">
				<tr>
					<td width="200" height="70" nowrap><img src="../../image/menu/top_menu.jpg"></td>
					<td width="750" background="../../image/menu/top_menu_back.jpg" class="title">Appli Cellules - Administration</td>
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
								<td align="left" width="610"><FONT SIZE="1">
		<?php
		
echo 'MAJ : ';

if (isset($_SESSION['site_id'])) {
	if ($_SESSION['site_id'] != 100) {
		$value = mysql_simple_select_query('SELECT message_value FROM value WHERE name = \'IMPORT_MESSAGE_'.$_SESSION['site_trigram'].'\'');
		
		if ($value) {
			echo $value;
		} else {
			echo mysql_simple_select_query('SELECT DATE_FORMAT(date, \'%d/%m/%Y:%H:%i\') FROM date_value WHERE name = \'IMPORT_DATE_'.$_SESSION['site_trigram'].'\'');
		}
	} else {
		$value_tre = mysql_simple_select_query('SELECT message_value FROM value WHERE name = \'IMPORT_MESSAGE_TRE\'');
		$value_lam = mysql_simple_select_query('SELECT message_value FROM value WHERE name = \'IMPORT_MESSAGE_LAM\'');
		$value_mlt = mysql_simple_select_query('SELECT message_value FROM value WHERE name = \'IMPORT_MESSAGE_MLT\'');
		$value_neg = mysql_simple_select_query('SELECT message_value FROM value WHERE name = \'IMPORT_MESSAGE_NEG\'');
		
		if ($value_tre) {
			echo 'TRE : '.$value_tre;
		} else {
			echo 'TRE : '.mysql_simple_select_query('SELECT DATE_FORMAT(date, \'%d/%m/%Y:%H:%i\') FROM date_value WHERE name = \'IMPORT_DATE_TRE\'');
		}
		
		if ($value_lam) {
			echo ' / LAM : '.$value_lam;
		} else {
			echo ' / LAM : '.mysql_simple_select_query('SELECT DATE_FORMAT(date, \'%d/%m/%Y:%H:%i\') FROM date_value WHERE name = \'IMPORT_DATE_LAM\'');
		}
		
		if ($value_mlt) {
			echo ' / MLT : '.$value_mlt;
		} else {
			echo ' / MLT : '.mysql_simple_select_query('SELECT DATE_FORMAT(date, \'%d/%m/%Y:%H:%i\') FROM date_value WHERE name = \'IMPORT_DATE_MLT\'');
		}
		
		if ($value_neg) {
			echo ' / NEG : '.$value_neg;
		} else {
			echo ' / NEG : '.mysql_simple_select_query('SELECT DATE_FORMAT(date, \'%d/%m/%Y:%H:%i\') FROM date_value WHERE name = \'IMPORT_DATE_NEG\'');
		}
	}
} else {
	echo 'Erreur!';
}

		
		?>
								</FONT>
								</td>
								<td align="right" width="340"><FONT SIZE="1">
		<?php
		
		if (isset($_SESSION['authenticated']) && ($_SESSION['authenticated'] == 1)) {
		echo 'Connect&eacute;(e) en tant que '.$_SESSION['user_name'].', <A HREF="logoff.php" TARGET="_self">se d&eacute;connecter...</A>';
		} else {
		echo '<A HREF="logon.php" TARGET="main">se connecter...</A>';
		}
		
		?>
									</FONT>
								</td>
							</tr>
						</table>
					</td>
				</tr>
				<tr>
					<td height="0"><input type="hidden" name="site_trigram" value="<?php echo strtolower($_SESSION['site_trigram']); ?>"></td>
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
											<TD class="menu_title_text">- <a href="main.php" target="main">Administration</a> -</TD>
										</TR>
										<tr>
											<td class="separator"></td>
										</tr>
										<tr>
											<td class="menu"><img src="../../image/menu/menu_separator.jpg" border="0"></td>
										</tr>
										<tr>
											<td class="separator"></td>
										</tr>

<?php

if (is_allowed('admin_product')) {

?>
										<TR>
											<TD class="menu_section_text">- <A HREF="../admin_product/index.php" TARGET="main">Produit</A> -</TD>
										</TR>
										<tr>
											<td class="separator"></td>
										</tr>
										<tr>
											<td class="menu"><img src="../../image/menu/menu_separator.jpg" border="0"></td>
										</tr>
										<tr>
											<td class="separator"></td>
										</tr>
<?php

}

if (is_allowed('admin_machine') ) {

?>
										<TR>
											<TD class="menu_section_text">- <A HREF="../admin_machine/index.php" TARGET="main">Machine</A> -</TD>
										</TR>
										<tr>
											<td class="separator"></td>
										</tr>
										<tr>
											<td class="menu"><img src="../../image/menu/menu_separator.jpg" border="0"></td>
										</tr>
										<tr>
											<td class="separator"></td>
										</tr>
<?php

}

if (is_allowed('admin_cell') ) {

?>
										<TR>
											<TD class="menu_section_text">- <A HREF="../admin_cell/index.php" TARGET="main">Cellule</A> -</TD>
										</TR>
										<tr>
											<td class="separator"></td>
										</tr>
										<tr>
											<td class="menu"><img src="../../image/menu/menu_separator.jpg" border="0"></td>
										</tr>
										<tr>
											<td class="separator"></td>
										</tr>
<?php

}

if (is_allowed('admin_warehouse') ) {

?>
										<TR>
											<TD class="menu_section_text">- <A HREF="../admin_warehouse/index.php" TARGET="main">Entrep&ocirc;t</A> -</TD>
										</TR>
										<tr>
											<td class="separator"></td>
										</tr>
										<tr>
											<td class="menu"><img src="../../image/menu/menu_separator.jpg" border="0"></td>
										</tr>
										<tr>
											<td class="separator"></td>
										</tr>
<?php

}

if (is_allowed('admin_site') ) {

?>
										<TR>
											<TD class="menu_section_text">- <A HREF="../admin_site/index.php" TARGET="main">Site</A> -</TD>
										</TR>
										<tr>
											<td class="separator"></td>
										</tr>
										<tr>
											<td class="menu"><img src="../../image/menu/menu_separator.jpg" border="0"></td>
										</tr>
										<tr>
											<td class="separator"></td>
										</tr>
<?php

}

if (is_allowed('admin_sales_admin') ) {

?>
										<TR>
											<TD class="menu_section_text">- <A HREF="../admin_sales_admin/index.php" TARGET="main">ADV</A> -</TD>
										</TR>
										<tr>
											<td class="separator"></td>
										</tr>
										<tr>
											<td class="menu"><img src="../../image/menu/menu_separator.jpg" border="0"></td>
										</tr>
										<tr>
											<td class="separator"></td>
										</tr>
<?php

}

if (is_allowed('admin_user') ) {

?>
										<TR>
											<TD class="menu_section_text">- <A HREF="../admin_user/index.php" TARGET="main">Utilisateur</A> -</TD>
										</TR>
										<tr>
											<td class="separator"></td>
										</tr>
										<tr>
											<td class="menu"><img src="../../image/menu/menu_separator.jpg" border="0"></td>
										</tr>
										<tr>
											<td class="separator"></td>
										</tr>
<?php

}

if (is_allowed('admin_role') ) {

?>
										<TR>
											<TD class="menu_section_text">- <A HREF="../admin_role/index.php" TARGET="main">Role</A> -</TD>
										</TR>
										<tr>
											<td class="separator"></td>
										</tr>
										<tr>
											<td class="menu"><img src="../../image/menu/menu_separator.jpg" border="0"></td>
										</tr>
										<tr>
											<td class="separator"></td>
										</tr>
<?php

}

if (is_allowed('admin_access') ) {

?>
										<TR>
											<TD class="menu_section_text">- <A HREF="../admin_access/index.php" TARGET="main">Acc&egrave;s</A> -</TD>
										</TR>
										<tr>
											<td class="separator"></td>
										</tr>
										<tr>
											<td class="menu"><img src="../../image/menu/menu_separator.jpg" border="0"></td>
										</tr>
										<tr>
											<td class="separator"></td>
										</tr>
<?php

}

if (is_allowed('error') ) {

?>
										<TR>
											<TD class="menu_section_text">- <A HREF="../error/index.php" TARGET="main">Erreur</A> -</TD>
										</TR>
										<tr>
											<td class="separator"></td>
										</tr>
										<tr>
											<td class="menu"><img src="../../image/menu/menu_separator.jpg" border="0"></td>
										</tr>
										<tr>
											<td class="separator"></td>
										</tr>
<?php

}

if (is_allowed('log') ) {

?>
										<TR>
											<TD class="menu_section_text">- <A HREF="../log/index.php" TARGET="main">Journal</A> -</TD>
										</TR>
										<tr>
											<td class="separator"></td>
										</tr>
										<tr>
											<td class="menu"><img src="../../image/menu/menu_separator.jpg" border="0"></td>
										</tr>
										<tr>
											<td class="separator"></td>
										</tr>
<?php

}

if (is_allowed('admin_version') ) {

?>
										<TR>
											<TD class="menu_section_text">- <A HREF="../admin_version/index.php" TARGET="main">Version</A> -</TD>
										</TR>
										<tr>
											<td class="separator"></td>
										</tr>
										<tr>
											<td class="menu"><img src="../../image/menu/menu_separator.jpg" border="0"></td>
										</tr>
										<tr>
											<td class="separator"></td>
										</tr>
<?php

}

if (is_allowed('admin_message') ) {

?>
										<TR>
											<TD class="menu_section_text">- <A HREF="../admin_message/index.php" TARGET="main">Message</A> -</TD>
										</TR>
										<tr>
											<td class="separator"></td>
										</tr>
										<tr>
											<td class="menu"><img src="../../image/menu/menu_separator.jpg" border="0"></td>
										</tr>
										<tr>
											<td class="separator"></td>
										</tr>
<?php

}

?>
										<TR>
											<TD class="menu_title_text">- <a href="../index.php" target="_self">Retour</a> -</TD>
										</TR>
										<tr>
											<td class="separator"></td>
										</tr>
									</table>
								</td>
								<TD width="1">
									<TABLE class="center" cellpadding="0" cellspacing="0">
										<TR>
											<TD width="1" height="25"><img src="../../image/menu/main_section_top_center.jpg"></TD>
										</TR>
										<TR>
											<TD width="1" height="400" background="../../image/menu/pix.jpg" ></TD>
										</TR>
										<TR>
											<TD width="1" height="25"><img src="../../image/menu/main_section_bottom_center.jpg"></TD>
										</TR>
									</TABLE>
								</TD>
								<TD width="800" height="470">
									<iframe src="main.php" width="800" height="100%" name="main" frameborder="0"></iframe>
								</TD>
							</tr>
						</table>
					</TD>
				</TR>
				<tr>
					<td height="20"></td>
				</tr>
			</table>
		</td>
	</tr>
	<tr>
		<td width="950">
			<TABLE>
				<tr>
					<td width="200" height="40" background="../../image/menu/bottom_menu.jpg">&nbsp;</td>
					<td width="715" height="40" background="../../image/menu/bottom_menu_back.jpg" class="main_title">&nbsp;</td>
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