<?php
	session_start();
	include_once(dirname(__FILE__).'/../../util/auth.php');
	include_once(dirname(__FILE__).'/../../util/sql.php');
	include_once(dirname(__FILE__).'/../../util/util.php');
	
	if (!isset($_SESSION['site_id']) || ($_SESSION['site_id'] == '')) {
		header('Location: ../main.php');
		exit();
	}
	
	if (!isset($_REQUEST['history']) || ($_REQUEST['history'] == '')) {
		$_REQUEST['history'] = '/webcell/pages/main.php';
	}

?>
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN" "http://www.w3.org/TR/html4/loose.dtd">
<html>
<head>
	<title>ELBA - Appli Cellules - Liste des stocks</title>
	<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
	<link href="../../css/style.css" rel="stylesheet" type="text/css">
</HEAD>
<BODY class="main_body">
<FORM NAME='mainForm' ACTION='order_dc.php' METHOD='POST'>
<TABLE class="normal">
	<TR>
		<TD align="center">
			<TABLE class="normal">
				<TR>
					<TD class="main_image"><A HREF="../print/stock.php" TARGET="_self"><img src="../../image/printer_little.jpg" ALT="Impression des stocks"></A></TD>
					<TD class="low_separator">&nbsp;</TD>
				    <TD class="main_image"><A HREF="../export/stock.php" TARGET="_self"><img src="../../image/excel_little.jpg" alt="Exportation Excel des stocks"></A></TD>
					<TD class="low_separator">&nbsp;</TD>
				    <TD class="main_title_text">Liste des stocks : lignes <input type="text" name="count" value="..." class="count"> / references <input type="text" name="count2" value="..." class="count"></TD>
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
		<TD class="min_separator"></TD>
	</TR>
	<TR>
		<TD>
			<TABLE>
				<TR>
					<TD><?php
									
if ($_SESSION['site_id'] == 100) {
	$columns = array(array('R&eacute;f&eacute;rence', 100, "link", "left", "", "product_information.php", 9, "_parent", false, false, 'R&eacute;f&eacute;rence produit'),
					 array('D&eacute;signation', 160, "data", "left", "", "", 0, "", false, false, 'D&eacute;signation produit'),
					 array('Site', 60, "data", "left", "", "", 0, "", false, false, 'Site de production'),
					 array('Cellule', 60, "link", "left", "", "cell_information.php", 10, "_parent", false, false, 'Cellule de production'),
					 array('Machine', 60, "link", "left", "", "machine_information.php", 11, "_parent", false, false, 'Machine de production'),
					 array('MOTD', 60, "number", "right", "", "", 0, "", false, false, 'Quantit&eacute; vendue depuis le d&eacute;but du mois'),
					 array('Multiple', 60, "number", "right", "", "", 0, "", false, false, 'Multiple de commande'),
					 array('Entrep&ocirc;t', 140, "data", "center", "", "", 0, "", false, false, 'Entrep&ocirc;t de stockage'),
					 array('Quantit&eacute;', 60, "number", "right", "bold", "", 0, "", false, false, 'Quantit&eacute; en stock'));
} else {
	$columns = array(array('R&eacute;f&eacute;rence', 110, "link", "left", "", "product_information.php", 8, "_parent", false, false, 'R&eacute;f&eacute;rence produit'),
					 array('D&eacute;signation', 190, "data", "left", "", "", 0, "", false, false, 'D&eacute;signation produit'),
					 array('Cellule', 60, "link", "left", "", "cell_information.php", 9, "_parent", false, false, 'Cellule de production'),
					 array('Machine', 60, "link", "left", "", "machine_information.php", 10, "_parent", false, false, 'Machine de production'),
					 array('MOTD', 60, "number", "right", "", "", 0, "", false, false, 'Quantit&eacute; vendue depuis le d&eacute;but du mois'),
					 array('Multiple', 60, "number", "right", "", "", 0, "", false, false, 'Multiple de commande'),
					 array('Entrep&ocirc;t', 140, "data", "center", "", "", 0, "", false, false, 'Entrep&ocirc;t de stockage'),
					 array('Quantit&eacute;', 60, "number", "right", "bold", "", 0, "", false, false, 'Quantit&eacute; en stock'));
}
			   
get_table_head($columns);
						  ?></TD>
			</TR>
</TABLE>
<TABLE>
	<TR>
		<TD><iframe src="stock_result.php<?php 
		
echo '?history='.get_php_self();

if (isset($_REQUEST['order']))
{
	echo '&order='.$_REQUEST['order'];
	
	if (isset($_REQUEST['sort']))
	{
		echo '&sort='.$_REQUEST['sort'];
	}
}
?>" width="790" height="335" name="result" frameborder="0" marginwidth="0" marginheight="0"></iframe></TD>
	</TR>
</TABLE>
<TABLE class="normal">
  	<TR>
		<TD class="min_separator"></TD>
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
</FORM>
</BODY>
</HTML>