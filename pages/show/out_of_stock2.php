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
	<title>ELBA - Appli Cellules - Transferts inter-d&eacute;p&ocirc;t</title>
	<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
	<link href="../../css/style.css" rel="stylesheet" type="text/css">
	<SCRIPT type='text/javascript' src='../../scripts/main.js'></SCRIPT>
</HEAD>
<BODY class="main_body">
<FORM NAME='mainForm' ACTION='out_of_stock2.php' METHOD='POST'>
<TABLE class="normal">
	<TR>
		<TD align="center">
			<TABLE class="normal">
				<TR>
					<TD class="main_image"><A HREF="../export/out_of_stock2.php" TARGET="_self"><img src="../../image/excel_little.jpg" alt="Exportation Excel des transferts inter-d&eacute;p&ocirc;t"></A></TD>
					<TD class="field_separator"></TD>
				    <TD class="main_title_text">Transferts inter-d&eacute;p&ocirc;t <input type="text" name="count" value="..." class="count"></TD>
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
		<TD align="center">
			<TABLE>
				<TR>
					<TD class="main_sub_section_text">- <A HREF='out_of_stock.php' TARGET='_self'>Ancienne version</A> -</TD>
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
</TABLE>
<TABLE class="normal">
	<TR>
		<TD>
			<TABLE>
				<TR>
					<TD class="header" width="380"></TD>
					<TD class="header" width="40"></TD>
					<TD class="header" width="70">Cdes.</TD>
					<TD class="header" width="95">Besoin</TD>
					<TD class="header" width="130">Stock</TD>
					<TD class="header" width="30"></TD>
					<TD class="header" width="35"></TD>
					<TD width="20"></TD>
				</TR>
			</table>
		</TD>
	</TR>
	<TR>
		<TD>
		<?php

$columns = array(array('Site', 35, 'data', 'center', '', '', 0, '', false, false, 'Site de production'),
				 array('R&eacute;f.', 65, 'link', 'left', '', 'product_information.php', 16, '_parent', false, false, 'R&eacute;f&eacute;rence produit'),
				 array('D&eacute;signation', 160, 'data', 'left', '', '', 0, '', false, false, 'D&eacute;signation produit'),
				 array('Entrep&ocirc;t', 120, 'data', 'center', '', '', 0, '', false, false, 'Entrep&ocirc;t de stockage'),
				 array('Stock', 40, 'number', 'right', 'bold', '', 0, '', false, false, 'Quantit&eacute; en stock'),
				 array('N.B.', 35, 'number', 'right', '', '', 0, '', false, false, 'Quantit&eacute; command&eacute;e non bloqu&eacute;e'),
				 array('B.', 35, 'number', 'right', '', '', 0, '', false, false, 'Quantit&eacute; command&eacute;e bloqu&eacute;e'),
				 array('J+1', 30, 'number', 'right', '', '', 0, '', false, false, 'Quantit&eacute; n&eacute;cessaire &agrave; J + 1'),
				 array('J+'.$_SESSION['horizon'], 30, 'number', 'right', '', '', 0, '', false, false, 'Quantit&eacute; n&eacute;cessaire &agrave; J + '.$_SESSION['horizon']),
				 array('Tot.', 35, 'number', 'right', '', '', 0, '', false, false, 'Quantit&eacute; totale n&eacute;cessaire'),
				 array('J+1', 30, 'number', 'right', '', '', 0, '', false, false, 'Quantit&eacute; en stock &agrave; J + 1'),
				 array('J+'.$_SESSION['horizon'], 30, 'number', 'right', '', '', 0, '', false, false, 'Quantit&eacute; en stock &agrave; J + '.$_SESSION['horizon']),
				 array('Tot.', 35, 'number', 'right', '', '', 0, '', false, false, 'Quantit&eacute; en stock totale'),
				 array('Disp.', 35, 'number', 'right', 'bold', '', 0, '', false, false, 'Quantit&eacute; disponible'),
				 array('PCB', 30, 'number', 'right', '', '', 0, '', false, false, 'PCB'),
				 array('Pr&eacute;v.', 35, 'number', 'right', '', '', 0, '', false, false, 'Pr&eacute;visions de vente'));
			   						
get_table_head($columns);

		?></TD>
	</TR>
</TABLE>
<TABLE class="normal">
	<TR>
		<TD><iframe src="out_of_stock2_result.php<?php 
		
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