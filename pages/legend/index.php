<?php
	session_start();
	
	if (!isset($_REQUEST['history']) || ($_REQUEST['history'] == '')) {
		$_REQUEST['history'] = '/webcell/pages/main.php';
	}

?>
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN" "http://www.w3.org/TR/html4/loose.dtd">
<HTML>
<HEAD>
	<TITLE>ELBA - Appli Cellules - Legende</TITLE>
	<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
	<link href="../../css/style.css" rel="stylesheet" type="text/css">
	<SCRIPT type='text/javascript' src='../../scripts/main.js'></SCRIPT>
</HEAD>
<BODY class="main_body">
<CENTER>
<TABLE>
	<TR>
		<TD class="main_title_text">Legende</TD>
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
					<TD>Voici une explication de la l&eacute;gende :</TD>
				</TR>
				<TR>
					<TD class="separator"></TD>
				</TR>
			</TABLE>
			<TABLE>
				<TR height="18">
					<TD class="menu_legend_header"><img src="../../image/menu/blocked.jpg" alt="Bloqu&eacute;e"></TD>
					<TD class="field_separator"></TD>
					<TD class="field_value"> : Bloqu&eacute;e : cela indique que la ligne de commande est bloqu&eacute;e (en livraison, blocage client, etc.).</TD>
				</TR>
				<TR>
					<TD class="min_separator"></TD>
				</TR>
				<TR height="18">
					<TD class="menu_legend_header"><img src="../../image/menu/out.jpg" alt="Rupture"></TD>
					<TD class="field_separator"></TD>
					<TD class="field_value"> : Rupture : indique que la la ligne de commande est en rupture par rapport &agrave; la date du jour.<BR>
					  Cette ligne est donc en retard et le stock du site d'exp&eacute;dition pour la r&eacute;f&eacute;rence ne couvre pas les besoins de commande.</TD>
				</TR>
				<TR>
					<TD class="min_separator"></TD>
				</TR>
				<TR height="18">
					<TD class="menu_legend_header"><img src="../../image/menu/blocked_out.jpg" alt="Bloqu&eacute;e, en rupture"></TD>
					<TD class="field_separator"></TD>
					<TD class="field_value"> : Bloqu&eacute;e, en rupture : indique que la ligne de commande est non seulement bloqu&eacute;e mais aussi en rupture.</TD>
				</TR>
				<TR>
					<TD class="min_separator"></TD>
				</TR>
				<TR height="18">
					<TD class="menu_legend_header"><img src="../../image/menu/sub.jpg" alt="Sous-traitance"></TD>
					<TD class="field_separator"></TD>
					<TD class="field_value"> : Sous-traitance : cette r&eacute;f&eacute;rence est un produit partiellement ou totalement sous-trait&eacute;.</TD>
				</TR>
			</TABLE>
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
</BODY>
</HTML>