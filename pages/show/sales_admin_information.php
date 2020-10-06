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

function get_order_list() {

	if ((isset($_REQUEST['id'])) && 
		($_REQUEST['id'] != '')) {
		
		$sql_query = 'SELECT telephone ' .
					 'FROM user ' .
					 'WHERE id = (SELECT user_id FROM sales_admin WHERE id = '.mysql_format_to_number($_REQUEST['id']).') LIMIT 1';
	
		$telephone = mysql_simple_select_query($sql_query);
?>
			<TABLE class="normal">
				<TR>
					<TD class="main_title_text" valign="top">Description</TD>
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
								<TD class="field_headling" width="80">T&eacute;l&eacute;phone :</TD>
								<TD class="field_separator"></TD>
								<TD class="field_value"><B>
<?php

echo $telephone;

?></B></TD>
							</TR>
						</TABLE>
					</TD>
				</TR>
				<TR>
					<TD class="min_separator"></TD>
				</TR>
			</TABLE>
			<TABLE class="normal">
				<TR>
					<TD align="center">
						<TABLE class="normal">
							<TR>
								<TD class="main_image"><A HREF="../export/sales_admin_information.php?id=<?php echo $_REQUEST['id']; ?>" TARGET="_self"><img src="../../image/excel_little.jpg" alt="Exportation Excel des informations ADV"></A></TD>
								<TD class="low_separator">&nbsp;</TD>
								<TD class="main_title_text">Liste des lignes de commande <input type="text" name="count" value="..." class="count"></TD>
							</TR>
						</TABLE>
					</TD>
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
	$columns = array(array('Cde.', 50, 'link', 'center', '', 'custords_information.php', 11, '_parent', false, false, 'Num&eacute;ro de commande'),
					 array('Client', 50, 'link', 'center', '', 'customer_information.php', 12, '_parent', false, false, 'Num&eacute;ro de client'),
					 array('P', 10, 'data', 'center', '', '', 0, '', false, false, 'Priorit&eacute; client'),
					 array('Site', 45, 'data', 'left', '', '', 0, '', false, false, 'Site de production'),
					 array('Cellule', 55, 'link', 'left', '', 'cell_information.php', 13, '_parent', false, false, 'Cellule de production'),
					 array('Machine', 60, 'link', 'left', '', 'machine_information.php', 14, '_parent', false, false, 'Machine de production'),
					 array('R&eacute;f&eacute;rence', 80, 'link', 'left', '', 'product_information.php', 15, '_parent', false, false, 'R&eacute;f&eacute;rence produit'),
					 array('D&eacute;signation', 160, 'data', 'left', '', '', 0, '', false, false, 'D&eacute;signation produit'),
					 array('Date', 60, 'date', 'center', '', '', 0, '', false, false, 'Date de commande'),
					 array('Qt&eacute;.', 50, 'number', 'right', 'bold', '', 0, '', true, false, 'Quantit&eacute; command&eacute;e'),
					 array('Entrep&ocirc;t', 100, 'data', 'center', '', '', 0, '', false, false, 'Site d\'exp&eacute;dition'),
					 array('XP dir.', 40, 'boolean', 'center', '', '', 0, '', false, false, 'Exp&eacute;dition directe'));
} else {
	$columns = array(array('Cde.', 50, 'link', 'center', '', 'custords_information.php', 10, '_parent', false, false, 'Num&eacute;ro de commande'),
					 array('Client', 50, 'link', 'center', '', 'customer_information.php', 11, '_parent', false, false, 'Num&eacute;ro de client'),
					 array('P', 10, 'data', 'center', '', '', 0, '', false, false, 'Priorit&eacute; client'),
					 array('Cellule', 60, 'link', 'left', '', 'cell_information.php', 12, '_parent', false, false, 'Cellule de production'),
					 array('Machine', 65, 'link', 'left', '', 'machine_information.php', 13, '_parent', false, false, 'Machine de production'),
					 array('R&eacute;f&eacute;rence', 85, 'link', 'left', '', 'product_information.php', 14, '_parent', false, false, 'R&eacute;f&eacute;rence produit'),
					 array('D&eacute;signation', 165, 'data', 'left', '', '', 0, '', false, false, 'D&eacute;signation produit'),
					 array('Date', 60, 'date', 'center', '', '', 0, '', false, false, 'Date de commande'),
					 array('Qt&eacute;.', 50, 'number', 'right', 'bold', '', 0, '', true, false, 'Quantit&eacute; command&eacute;e'),
					 array('Entrep&ocirc;t', 110, 'data', 'center', '', '', 0, '', false, false, 'Site d\'exp&eacute;dition'),
					 array('XP dir.', 40, 'boolean', 'center', '', '', 0, '', false, false, 'Exp&eacute;dition directe'));
}

get_table_head($columns);
							  ?></TD>
				</TR>
				<TR>
					<TD><iframe src="sales_admin_information_result.php<?php 

echo '?history='.get_php_self().'&id='.$_REQUEST['id'];

if (isset($_REQUEST['order']))
{
	echo '&order='.$_REQUEST['order'];
}
else
{
	if ($_SESSION['site_id'] == 100) {
		echo '&order=8';
	}
	else
	{
		echo '&order=7';
	}
}

if (isset($_REQUEST['sort']))
{
	echo '&sort='.$_REQUEST['sort'];
}
else
{
	echo '&sort=asc';
}


?>" width="790" height="275" name="result" frameborder="0" marginwidth="0" marginheight="0"></iframe></TD>
				</TR>
			</TABLE>
<?php
	}
}
?>
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN" "http://www.w3.org/TR/html4/loose.dtd">
<html>
<head>
	<title>ELBA - Appli Cellules - Information ADV</title>
	<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
	<link href="../../css/style.css" rel="stylesheet" type="text/css">
	<SCRIPT type='text/javascript' src='../../scripts/main.js'></SCRIPT>
	<?php mysql_build_javascript_table('SELECT sales_admin.id, CONCAT(sales_admin.trigram, \' - \', user.first_name, \' \', user.last_name) as name FROM sales_admin LEFT OUTER JOIN user ON sales_admin.user_id = user.id ORDER BY sales_admin.trigram',
'salesAdmin'); ?>
</HEAD>
<BODY class="main_body">
<FORM NAME='mainForm' ACTION='sales_admin_information.php' METHOD='POST'>
<TABLE class="normal">
	<TR>
		<TD class="main_title_text">Information ADV<?php 

if (isset($_REQUEST['id']) && $_REQUEST['id'] != '') {
	$sql_query = 'SELECT trigram ' .
				 'FROM sales_admin ' .
				 'WHERE id = '.mysql_format_to_number($_REQUEST['id']).' LIMIT 1';

	echo ' : '.mysql_simple_select_query($sql_query);
}

?></TD>
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
					<TD class="field_header">ADV :</TD>
					<TD class="field_separator"><INPUT NAME='id' TYPE='hidden' VALUE='<?php echo $_REQUEST['id']; ?>'></TD>
					<TD class="field_value">
						<SELECT NAME='sales_admin_select' onChange='javascript:salesAdminInformationFormOnSalesAdminChange()'></SELECT>
					</TD>
				</TR>
			</TABLE>
		</TD>
	</TR>
	<TR>
		<TD class="separator"></TD>
	</TR>
</TABLE>
<?php 

if ((isset($_REQUEST['id'])) && ($_REQUEST['id'] != '')) {
	$count = get_order_list();
}

?>
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
<SCRIPT type="text/javascript">
	salesAdminInformationFormFill()
</SCRIPT>
</BODY>
</HTML>