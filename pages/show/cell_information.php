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

function get_cell_list() {

	if (((isset($_REQUEST['cell_name'])) && ($_REQUEST['cell_name'] != '')) &&
		((!isset($_REQUEST['id'])) || ($_REQUEST['id'] == ''))) {

?>
			<TABLE>
				<TR>
					<TD class="main_title_text" valign="top">Cellules trouv&eacute;es <input type="text" name="count" value="..." class="count"></TD>
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
	$columns = array(array('Nom', 200, 'link', 'left', '', 'cell_information.php', 3, '_parent', false, false, 'Nom de la cellule'),
					 array('D&eacute;signation', 370, 'data', 'left', '', '', 0, '', false, false, 'Description de la cellule'),
					 array('Site', 200, 'data', 'center', '', '', 0, '', false, false, 'Site de production'));
} else {
	$columns = array(array('Nom', 300, 'link', 'left', '', 'cell_information.php', 2, '_parent', false, false, 'Nom de la cellule'),
				 	 array('D&eacute;signation', 470, 'data', 'left', '', '', 0, '', false, false, 'Description de la cellule'));
}
		  
get_table_head($columns);
							  ?></TD>
				</TR>
				<TR>
					<TD><iframe src="cell_information_result.php<?php 

echo '?history='.get_php_self().'&cell_name='.$_REQUEST['cell_name'];

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
			<TABLE>
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
$columns = array(array('Machine', 60, 'link', 'left', '', 'machine_information.php', 11, '_parent', false, false, 'Machine de production'),
				 array('R&eacute;f&eacute;rence', 80, 'link', 'left', '', 'product_information.php', 12, '_parent', false, false, 'R&eacute;f&eacute;rence produit'),
				 array('D&eacute;signation', 160, 'data', 'left', '', '', 0, '', false, false, 'D&eacute;signation produit'),
				 array('Cde.', 55, 'link', 'center', '', 'custords_information.php', 13, '_parent', false, false, 'Num&eacute;ro de commande'),
				 array('ADV', 40, 'link', 'center', '', 'sales_admin_information.php', 14, '_parent', false, false, 'ADV'),
				 array('Client', 50, 'link', 'center', '', 'customer_information.php', 15, '_parent', false, false, 'Num&eacute;ro de client'),
				 array('Nom', 150, 'data', 'left', '', '', 0, '', false, false, 'Nom de client'),
				 array('P', 10, 'number', 'center', 'bold', '', 0, '', false, false, 'Priorit&eacute; client'),
				 array('Date', 70, 'date', 'center', '', '', 0, '', false, false, 'Date de commande'),
				 array('Qt&eacute;.', 50, 'number', 'right', 'bold', '', 0, '', true, false, 'Quantit&eacute; command&eacute;e'),
				 array('XP dir.', 35, 'boolean', 'center', '', '', 0, '', false, false, 'Exp&eacute;dition directe'));
				    
get_table_head($columns);
							  ?></TD>
				</TR>
				<TR>
					<TD><iframe src="cell_information_result.php<?php 

echo '?history='.get_php_self().'&id='.$_REQUEST['id'];

if (isset($_REQUEST['order']))
{
	echo '&order='.$_REQUEST['order'];
}
else
{
	echo '&order=8';
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
	<title>ELBA - Appli Cellules - Information cellule</title>
	<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
	<link href="../../css/style.css" rel="stylesheet" type="text/css">
	<SCRIPT type='text/javascript' src='../../scripts/main.js'></SCRIPT>
</HEAD>
<BODY class="main_body">
<FORM NAME='mainForm' ACTION='cell_information.php' METHOD='POST'>
<TABLE class="normal">
	<TR>
		<TD class="main_title_text">Information cellule<?php 

if (isset($_REQUEST['id']) && $_REQUEST['id'] != '') {
	$sql_query = 'SELECT name ' .
				 'FROM cell ' .
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
					<TD class="field_header">cellule :</TD>
					<TD class="field_separator"></TD>
					<TD class="field_value">
						<INPUT TYPE='text' NAME='cell_name' VALUE='<?php 
if (isset($_REQUEST['cell_name'])) {
echo $_REQUEST['cell_name']; 
}
?>' style='width:100px' MAXLENGTH='20'>
					</TD>
					<TD class="field_separator"></TD>
					<TD class="field_value">
						<INPUT TYPE='submit' NAME='search' VALUE='rechercher' ALT='Rechercher une cellule'>
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

if (((isset($_REQUEST['cell_name'])) && ($_REQUEST['cell_name'] != '')) && ((!isset($_REQUEST['id'])) || ($_REQUEST['id'] == ''))) {
	$count = get_cell_list();
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