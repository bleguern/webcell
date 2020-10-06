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

function get_custords_list() {

	if (((isset($_REQUEST['custords_number'])) && ($_REQUEST['custords_number'] != '')) &&
		((!isset($_REQUEST['id'])) || ($_REQUEST['id'] == ''))) {
		
?>
			<TABLE class="normal">
				<TR>
					<TD class="main_title_text" valign="top">Commandes trouv&eacute;es <input type="text" name="count" value="..." class="count"></TD>
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
$columns = array(array('Commande', 170, 'link', 'left', '', 'custords_information.php', 5, '_parent', false, false, 'Num&eacute;ro de commande'),
				 array('ADV', 100, 'link', 'left', '', 'sales_admin_information.php', 6, '_parent', false, false, 'ADV'),
				 array('Client', 190, 'link', 'left', '', 'customer_information.php', 7, '_parent', false, false, 'Num&eacute;ro de client'),
				 array('Nom', 200, 'data', 'left', '', '', 0, '', false, false, 'Nom de client'),
				 array('Priorit&eacute;', 100, 'data', 'center', 'bold', '', 0, '', false, false, 'Priorit&eacute; client'));
		  
get_table_head($columns);
							  ?></TD>
				</TR>
				<TR>
					<TD><iframe src="custords_information_result.php<?php 

echo '?history='.get_php_self();

if (isset($_REQUEST['custords_number']) && ($_REQUEST['custords_number'] != ''))
{
	echo '&custords_number='.$_REQUEST['custords_number'];
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
		
		$date = '';
		$custords_number = '';
		$customer_id = '';
		$customer_number = '';
		$customer_name = '';
		$sales_admin_id = '';
		$sales_admin_trigram = '';
		$sales_admin_name = '';
		$sales_admin_telephone = '';
		
		$sql_query = 'SELECT custords.number as custords_number, ' .
					 'customer.id as customer_id, ' .
					 'customer.number as customer_number, ' .
					 'customer.name as customer_name, ' .
					 'customer.priority as customer_priority, ' . 
					 'sales_admin.id as sales_admin_id, ' .
					 'sales_admin.trigram as sales_admin_trigram, ' .
					 'CONCAT(user.first_name, \' \', user.last_name) as sales_admin_name, ' .
					 'user.telephone as sales_admin_telephone ' .
					 'FROM custords ' .
					 'LEFT OUTER JOIN customer ON customer.id = custords.customer_id ' .
					 'LEFT OUTER JOIN sales_admin ON sales_admin.id = custords.sales_admin_id ' .
					 'LEFT OUTER JOIN user ON user.id = sales_admin.user_id ' .
					 'WHERE custords.id = '.mysql_format_to_number($_REQUEST['id']).' LIMIT 1';
	
		$result = mysql_select_query($sql_query);
	
		if($result) {
			$custords = mysql_fetch_array($result, MYSQL_ASSOC);
			
			$custords_number = $custords['custords_number'];
			$customer_id = $custords['customer_id'];
			$customer_number = $custords['customer_number'];
			$customer_name = $custords['customer_name'];
			$customer_priority = $custords['customer_priority'];
			$sales_admin_id = $custords['sales_admin_id'];
			$sales_admin_trigram = $custords['sales_admin_trigram'];
			$sales_admin_name = $custords['sales_admin_name'];
			$sales_admin_telephone = $custords['sales_admin_telephone'];
		} else {
			mysql_save_sql_error(get_php_self(), 'Chargement de la page information commande', $sql_query, mysql_errno(), mysql_error());
			$_REQUEST['message'] = 'Erreur : chargement de la page &eacute;chou&eacute;!';
		}
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
								<TD class="field_headling" width="80">Client :</TD>
								<TD class="field_separator"></TD>
								<TD class="field_value"><B>
<?php

echo '<A HREF=\'customer_information.php?history='.get_php_self().'&id='.$customer_id.'\' TARGET=\'_self\'>'.$customer_number.'</A> - '.$customer_name.' (Priorit&eacute; : '.$customer_priority.')';

?></B></TD>
							</TR>
							<TR>
								<TD class="min_separator"></TD>
							</TR>
							<TR>
								<TD class="field_headling" width="80">ADV :</TD>
								<TD class="field_separator"></TD>
								<TD class="field_value"><B>
<?php

echo '<A HREF=\'sales_admin_information.php?history='.get_php_self().'&id='.$sales_admin_id.'\' TARGET=\'_self\'>'.$sales_admin_trigram.'</A> - '.$sales_admin_name.' (Tel. : '.$sales_admin_telephone.')';

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
					<TD class="main_title_text" valign="top">Liste des lignes de commande <input type="text" name="count" value="..." class="count"></TD>
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
	$columns = array(array('Site', 50, 'data', 'left', '', '', 0, '', false, false, 'Site de production'),
					 array('Cellule', 70, 'link', 'left', '', 'cell_information.php', 10, '_parent', false, false, 'Cellule de production'),
					 array('Machine', 70, 'link', 'left', '', 'machine_information.php', 11, '_parent', false, false, 'Machine de production'),
					 array('R&eacute;f&eacute;rence', 80, 'link', 'left', '', 'product_information.php', 12, '_parent', false, false, 'R&eacute;f&eacute;rence produit'),
					 array('D&eacute;signation', 180, 'data', 'left', '', '', 0, '', false, false, 'D&eacute;signation produit'),
					 array('Date', 70, 'date', 'center', '', '', 0, '', false, false, 'Date de commande'),
					 array('Qt&eacute;.', 50, 'number', 'right', 'bold', '', 0, '', true, false, 'Quantit&eacute; command&eacute;e'),
					 array('Entrep&ocirc;t', 120, 'data', 'center', '', '', 0, '', false, false, 'Site d\'exp&eacute;dition'),
					 array('XP dir.', 35, 'boolean', 'center', '', '', 0, '', false, false, 'Exp&eacute;dition directe'));
} else {
	$columns = array(array('Cellule', 70, 'link', 'left', '', 'cell_information.php', 9, '_parent', false, false, 'Cellule de production'),
					 array('Machine', 70, 'link', 'left', '', 'machine_information.php', 10, '_parent', false, false, 'Machine de production'),
					 array('R&eacute;f&eacute;rence', 80, 'link', 'left', '', 'product_information.php', 11, '_parent', false, false, 'R&eacute;f&eacute;rence produit'),
					 array('D&eacute;signation', 180, 'data', 'left', '', '', 0, '', false, false, 'D&eacute;signation produit'),
					 array('Date', 70, 'date', 'center', '', '', 0, '', false, false, 'Date de commande'),
					 array('Qt&eacute;.', 50, 'number', 'right', 'bold', '', 0, '', true, false, 'Quantit&eacute; command&eacute;e'),
					 array('Entrep&ocirc;t', 120, 'data', 'center', '', '', 0, '', false, false, 'Site d\'exp&eacute;dition'),
					 array('XP dir.', 35, 'boolean', 'center', '', '', 0, '', false, false, 'Exp&eacute;dition directe'));
}

get_table_head($columns);
							  ?></TD>
				</TR>
				<TR>
					<TD><iframe src="custords_information_result.php<?php 

echo '?history='.get_php_self().'&id='.$_REQUEST['id'];

if (isset($_REQUEST['order']))
{
	echo '&order='.$_REQUEST['order'];
}
else
{
	if ($_SESSION['site_id'] == 100) {
		echo '&order=5';
	}
	else
	{
		echo '&order=4';
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


?>" width="790" height="255" name="result" frameborder="0" marginwidth="0" marginheight="0"></iframe></TD>
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
<FORM NAME='mainForm' ACTION='custords_information.php' METHOD='POST'>
<TABLE class="normal">
	<TR>
		<TD class="main_title_text">Information commande<?php 

if (isset($_REQUEST['id']) && $_REQUEST['id'] != '') {
	$sql_query = 'SELECT number ' .
				 'FROM custords ' .
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
						<INPUT TYPE='text' NAME='custords_number' VALUE='<?php 
if (isset($_REQUEST['custords_number'])) {
	echo $_REQUEST['custords_number']; 
}
?>' STYLE='width:100px' MAXLENGTH='20'>
					</TD>
					<TD class="field_separator"></TD>
					<TD class="field_value">
						<INPUT TYPE='submit' NAME='search' VALUE='rechercher' ALT='Rechercher une commande'>
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

if (((isset($_REQUEST['custords_number'])) && ($_REQUEST['custords_number'] != '')) || 
	((!isset($_REQUEST['id'])) || ($_REQUEST['id'] == ''))) {
	$count = get_custords_list();
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