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
	<title>ELBA - Appli Cellules - Commandes en retard non bloqu&eacute;es</title>
	<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
	<link href="../../css/style.css" rel="stylesheet" type="text/css">
</HEAD>
<BODY class="main_body">
<FORM NAME='mainForm' ACTION='late_order_without_blocked.php' METHOD='POST'>
<TABLE class="normal">
	<TR>
		<TD align="center">
			<TABLE class="normal">
				<TR>
					<TD class="main_image"><A HREF="../print/late_order_without_blocked.php" TARGET="_self"><img src="../../image/printer_little.jpg" ALT="Impression des commandes en retard non bloqu&eacute;es"></A></TD>
					<TD class="field_separator"></TD>
				    <TD class="main_image"><A HREF="../export/late_order_without_blocked.php" TARGET="_self"><img src="../../image/excel_little.jpg" alt="Exportation Excel des commandes en retard non bloqu&eacute;es"></A></TD>
					<TD class="field_separator"></TD>
				    <TD class="main_title_text">Commandes en retard non bloqu&eacute;es : lignes <input type="text" name="count" value="..." class="count"> / references <input type="text" name="count2" value="..." class="count"></TD>
					<TD class="field_separator"></TD>
					<TD class="main_bottom_text" width="150"><A HREF="late_order_without_blocked.php" target="_self">Mettre &agrave; jour</A></TD>
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
			<TABLE class="normal">
				<TR>
					<TD class="main_sub_section_text">- <A HREF="late_order.php?history=<?php echo get_php_self(); ?>" TARGET="_self">Commandes en retard</A> -</TD>
					<TD class="main_sub_section_text">- <A HREF="late_order_out.php?history=<?php echo get_php_self(); ?>" TARGET="_self">Commandes en rupture</A> -</TD>
					<TD class="main_sub_section_text">- <A HREF="late_order_out_without_blocked.php?history=<?php echo get_php_self(); ?>" TARGET="_self">Commandes non bloqu&eacute;es et en rupture</A> -</TD>
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
	$columns = array(array('Site', 25, 'data', 'left', '', '', 0, '', false, false, 'Site de production'),
					 array('Cellule', 55, 'link', 'left', '', 'cell_information.php', 13, '_parent', false, true, 'Cellule de production'),
					 array('Machine', 55, 'link', 'left', '', 'machine_information.php', 14, '_parent', false, false, 'Machine de production'),
					 array('R&eacute;f&eacute;rence', 75, 'link', 'left', '', 'product_information.php', 15, '_parent', false, false, 'R&eacute;f&eacute;rence produit'),
					 array('D&eacute;signation', 165, 'data', 'left', '', '', 0, '', false, false, 'D&eacute;signation produit'),
					 array('Qt&eacute;.', 30, 'number', 'right', 'bold', '', 0, '', true, false, 'Quantit&eacute; command&eacute;e'),
					 array('Client', 55, 'link', 'center', '', 'customer_information.php', 16, '_parent', false, false, 'Num&eacute;ro de client'),
					 array('Nom', 150, 'data', 'left', '', '', 0, '', false, false, 'Nom de client'),
					 array('P', 10, 'data', 'center', 'bold', '', 0, '', false, false, 'Priorit&eacute; client'),
					 array('Date', 50, 'date', 'center', '', '', 0, '', false, false, 'Date de commande'),
					 array('X', 10, 'cross', 'center', '', '', 0, '', false, false, 'Exp&eacute;dition directe'),
					 array('Cde.', 40, 'link', 'center', '', 'custords_information.php', 17, '_parent', false, false, 'Num&eacute;ro de commande'),
					 array('ADV', 40, 'link', 'center', '', 'sales_admin_information.php', 18, '_parent', false, false, 'ADV'));
} else {
	$columns = array(array('Cellule', 55, 'link', 'left', '', 'cell_information.php', 12, '_parent', false, true, 'Cellule de production'),
					 array('Machine', 55, 'link', 'left', '', 'machine_information.php', 13, '_parent', false, false, 'Machine de production'),
					 array('R&eacute;f&eacute;rence', 85, 'link', 'left', '', 'product_information.php', 14, '_parent', false, false, 'R&eacute;f&eacute;rence produit'),
					 array('D&eacute;signation', 165, 'data', 'left', '', '', 0, '', false, false, 'D&eacute;signation produit'),
					 array('Qt&eacute;.', 35, 'number', 'right', 'bold', '', 0, '', true, false, 'Quantit&eacute; command&eacute;e'),
					 array('Client', 55, 'link', 'center', '', 'customer_information.php', 15, '_parent', false, false, 'Num&eacute;ro de client'),
					 array('Nom', 150, 'data', 'left', '', '', 0, '', false, false, 'Nom de client'),
					 array('P', 10, 'data', 'center', 'bold', '', 0, '', false, false, 'Priorit&eacute; client'),
					 array('Date', 50, 'date', 'center', '', '', 0, '', false, false, 'Date de commande'),
					 array('X', 10, 'cross', 'center', '', '', 0, '', false, false, 'Exp&eacute;dition directe'),
					 array('Cde.', 45, 'link', 'center', '', 'custords_information.php', 16, '_parent', false, false, 'Num&eacute;ro de commande'),
					 array('ADV', 40, 'link', 'center', '', 'sales_admin_information.php', 17, '_parent', false, false, 'ADV'));
}
			   
get_table_head($columns);
						  ?></TD>
			</TR>
</TABLE>
<TABLE class="normal">
	<TR>
		<TD><iframe src="late_order_without_blocked_result.php<?php 
		
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