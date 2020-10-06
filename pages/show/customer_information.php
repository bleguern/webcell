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

function get_customer_list() {

	if (((!isset($_REQUEST['id'])) || ($_REQUEST['id'] == '')) && 
		(((isset($_REQUEST['customer_name'])) && ($_REQUEST['customer_name'] != '')) ||
		((isset($_REQUEST['customer_number'])) && ($_REQUEST['customer_number'] != '')))) {

?>
			<TABLE class="normal">
				<TR>
					<TD class="main_title_text" valign="top">Clients trouv&eacute;s <input type="text" name="count" value="..." class="count"></TD>
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
$columns = array(array('Num&eacute;ro', 300, 'link', 'left', '', 'customer_information.php', 3, '_parent', false, false, 'Num&eacute;ro de client'),
				 array('Nom', 370, 'data', 'left', '', '', 0, '', false, false, 'Nom de client'),
				 array('Priorit&eacute;', 100, 'number', 'center', 'bold', '', 0, '', false, false, 'Priorit&eacute; client'));
		  
get_table_head($columns);
							  ?></TD>
				</TR>
				<TR>
					<TD><iframe src="customer_information_result.php<?php 

echo '?history='.get_php_self();

if (isset($_REQUEST['customer_name']) && ($_REQUEST['customer_name'] != ''))
{
	echo '&customer_name='.$_REQUEST['customer_name'];
}

if (isset($_REQUEST['customer_number']) && ($_REQUEST['customer_number'] != ''))
{
	echo '&customer_number='.$_REQUEST['customer_number'];
}

if (isset($_REQUEST['order']))
{
	echo '&order='.$_REQUEST['order'];
}
else
{
	echo '&order=0';
}

if (isset($_REQUEST['sort']))
{
	echo '&sort='.$_REQUEST['sort'];
}
else
{
	echo '&sort=asc';
}
?>" width="790" height="310" name="result" frameborder="0" marginwidth="0" marginheight="0"></iframe></TD>
				</TR>
			</TABLE>
			
<?php

	}
}


function get_order_list() {

	if ((isset($_REQUEST['id'])) && 
		($_REQUEST['id'] != '')) {
		
?>
			<TABLE class="normal">
				<TR>
					<TD class="main_title_text" valign="top">Liste des commandes <input type="text" name="count" value="..." class="count"></TD>
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
	$columns = array(array('Cde.', 55, 'link', 'center', '', 'custords_information.php', 11, '_parent', false, false, 'Num&eacute;ro de commande'),
					 array('ADV', 40, 'link', 'center', '', 'sales_admin_information.php', 12, '_parent', false, false, 'Trigram ADV'),
					 array('Site', 40, 'data', 'left', '', '', 0, '', false, false, 'Site de production'),
					 array('Cellule', 60, 'link', 'left', '', 'cell_information.php', 13, '_parent', false, false, 'Cellule de production'),
					 array('Machine', 60, 'link', 'left', '', 'machine_information.php', 14, '_parent', false, false, 'Machine de production'),
					 array('R&eacute;f&eacute;rence', 75, 'link', 'left', '', 'product_information.php', 15, '_parent', false, false, 'R&eacute;f&eacute;rence produit'),
					 array('D&eacute;signation', 165, 'data', 'left', '', '', 0, '', false, false, 'D&eacute;signation produit'),
					 array('Date', 70, 'date', 'center', '', '', 0, '', false, false, 'Date de commande'),
					 array('Qt&eacute;.', 50, 'number', 'right', 'bold', '', 0, '', true, false, 'Quantit&eacute; command&eacute;e'),
					 array('Entrep&ocirc;t', 110, 'data', 'center', '', '', 0, '', false, false, 'Site d\'exp&eacute;dition'),
					 array('XP dir.', 35, 'boolean', 'center', '', '', 0, '', false, false, 'Exp&eacute;dition directe'));
} else {
	$columns = array(array('Cde.', 60, 'link', 'center', '', 'custords_information.php', 10, '_parent', false, false, 'Num&eacute;ro de commande'),
					 array('ADV', 40, 'link', 'center', '', 'sales_admin_information.php', 11, '_parent', false, false, 'Trigram ADV'),
					 array('Cellule', 60, 'link', 'left', '', 'cell_information.php', 12, '_parent', false, false, 'Cellule de production'),
					 array('Machine', 60, 'link', 'left', '', 'machine_information.php', 13, '_parent', false, false, 'Machine de production'),
					 array('R&eacute;f&eacute;rence', 85, 'link', 'left', '', 'product_information.php', 14, '_parent', false, false, 'R&eacute;f&eacute;rence produit'),
					 array('D&eacute;signation', 175, 'data', 'left', '', '', 0, '', false, false, 'D&eacute;signation produit'),
					 array('Date', 70, 'date', 'center', '', '', 0, '', false, false, 'Date de commande'),
					 array('Qt&eacute;.', 50, 'number', 'right', 'bold', '', 0, '', true, false, 'Quantit&eacute; command&eacute;e'),
					 array('Entrep&ocirc;t', 110, 'data', 'center', '', '', 0, '', false, false, 'Site d\'exp&eacute;dition'),
					 array('XP dir.', 35, 'boolean', 'center', '', '', 0, '', false, false, 'Exp&eacute;dition directe'));
}

get_table_head($columns);
							  ?></TD>
				</TR>
				<TR>
					<TD><iframe src="customer_information_result.php<?php 

echo '?history='.get_php_self().'&id='.$_REQUEST['id'];

if (isset($_REQUEST['order']))
{
	echo '&order='.$_REQUEST['order'];
}
else
{
	if ($_SESSION['site_id'] == 100) {
		echo '&order=7';
	}
	else
	{
		echo '&order=6';
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


?>" width="790" height="300" name="result" frameborder="0" marginwidth="0" marginheight="0"></iframe></TD>
				</TR>
			</TABLE>
<?php
	}
}
?>
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN" "http://www.w3.org/TR/html4/loose.dtd">
<html>
<head>
	<title>ELBA - Appli Cellules - Information client</title>
	<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
	<link href="../../css/style.css" rel="stylesheet" type="text/css">
	<SCRIPT type='text/javascript' src='../../scripts/main.js'></SCRIPT>
</HEAD>
<BODY class="main_body">
<FORM NAME='mainForm' ACTION='customer_information.php' METHOD='POST'>
<TABLE class="normal">
	<TR>
		<TD class="main_title_text">Information client<?php 

if (isset($_REQUEST['id']) && $_REQUEST['id'] != '') {
	$sql_query = 'SELECT CONCAT(number, \' : \', name, \' (PRIORITE : \', priority, \')\') ' .
				 'FROM customer ' .
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
					<TD class="field_header">num&eacute;ro :</TD>
					<TD class="field_separator"></TD>
					<TD class="field_value">
						<INPUT TYPE='text' NAME='customer_number' VALUE='<?php 
if (isset($_REQUEST['customer_number'])) {
	echo $_REQUEST['customer_number']; 
}
?>' STYLE='width:100px' MAXLENGTH='20'>
					</TD>
					<TD class="field_separator"></TD>
					<TD class="field_header">nom :</TD>
					<TD class="field_separator"></TD>
					<TD class="field_value">
						<INPUT TYPE='text' NAME='customer_name' VALUE='<?php 
if (isset($_REQUEST['customer_name'])) {
	echo $_REQUEST['customer_name']; 
}
?>' STYLE='width:150px' MAXLENGTH='20'>
					</TD>
					<TD class="field_separator"></TD>
					<TD class="field_value">
						<INPUT TYPE='submit' NAME='search' VALUE='rechercher' ALT='Rechercher un client'>
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

if ((((isset($_REQUEST['customer_number'])) && ($_REQUEST['customer_number'] != '')) || 
	((isset($_REQUEST['customer_name'])) && ($_REQUEST['customer_name'] != ''))) && 
	((!isset($_REQUEST['id'])) || ($_REQUEST['id'] == ''))) {
	$count = get_customer_list();
}
		
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
</BODY>
</HTML>