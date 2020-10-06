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


function get_product_list() {

	if (((!isset($_REQUEST['id'])) || ($_REQUEST['id'] == '')) && 
		(((isset($_REQUEST['product_reference'])) && ($_REQUEST['product_reference'] != '')) ||
		((isset($_REQUEST['product_description'])) && ($_REQUEST['product_description'] != '')))) {

?>
			<TABLE class="normal">
				<TR>
					<TD class="main_title_text" valign="top">Produits trouv&eacute;s <input type="text" name="count" value="..." class="count"></TD>
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
																
$columns = array(array('R&eacute;f&eacute;rence', 200, "link", "left", "", "product_information.php?id=", 4, "_parent", false, false, 'R&eacute;f&eacute;rence produit'),
				 array('D&eacute;signation', 370, "data", "left", "", "", 0, "", false, false, 'D&eacute;signation produit'),
				 array('Machine', 100, "link", "left", "", "machine_information.php?id=", 5, "_parent", false, false, 'Machine de production'),
				 array('Cellule', 100, "link", "left", "", "cell_information.php?id=", 6, "_parent", false, false, 'Cellule de production'));		  

get_table_head($columns);
							  ?></TD>
				</TR>
				<TR>
					<TD><iframe src="product_information_result.php<?php 

echo '?history='.get_php_self();

if (isset($_REQUEST['product_description']) && ($_REQUEST['product_description'] != ''))
{
	echo '&product_description='.$_REQUEST['product_description'];
}

if (isset($_REQUEST['product_reference']) && ($_REQUEST['product_reference'] != ''))
{
	echo '&product_reference='.$_REQUEST['product_reference'];
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
?>" width="790" height="255" name="result" frameborder="0" marginwidth="0" marginheight="0"></iframe></TD>
				</TR>
			</TABLE>
			
<?php

	}
}


function get_order_list() {

	if ((isset($_REQUEST['id'])) && 
		($_REQUEST['id'] != '')) {
		
		$product_reference = '';
		$product_description = '';
		$cell_id = '';
		$cell_name = '';
		$machine_id = '';
		$machine_name = '';
		$product_cost = '';
		$product_price = '';
		$product_multiple = '';
		$product_sale_dest = '';
		$product_uc_per_hour = '';
		$product_minimum_stock = '';
		$stock =  '';
		$product_motd = '';
		$stock_prev = '';
		$order_cdc_total = 0;
		$order_bu_total = 0;
		$product_order_total = 0;
	
		$sql_query = 'SELECT product.reference as product_reference, ' .
					 'product.name as product_description, ' .
					 'cell.id as cell_id, ' .
					 'cell.name as cell_name, ' .
					 'machine.id as machine_id, ' .
					 'machine.name as machine_name, ' .
					 'product.cost as product_cost, ' .
					 'product.price as product_price, ' .
					 'product.multiple as product_multiple, ' .
					 'product.area as product_sale_dest, ' .
					 'product.mtd_sales as product_motd, ' .
					 'site.warehouse_id as warehouse_id, ' .
					 'product.machine_time as product_machine_time, ' .
					 'product.minimum_stock as product_minimum_stock, ' .
					 '(SELECT SUM(quantity) FROM custords_product WHERE custords_product.product_id = product.id) as product_order_total ' .
					 'FROM product ' .
					 'LEFT OUTER JOIN machine ON machine.id = product.machine_id ' .
					 'LEFT OUTER JOIN cell ON cell.id = machine.cell_id ' .
					 'LEFT OUTER JOIN site ON site.id = product.site_id ' .
					 'WHERE product.id = '.mysql_format_to_number($_REQUEST['id']).' ' .
					 'GROUP BY product_reference ' .
					 'LIMIT 1';
	
		$result = mysql_select_query($sql_query);
	
		if($result) {
			$product = mysql_fetch_array($result, MYSQL_ASSOC);
			
			$product_reference = $product['product_reference'];
			$product_description = $product['product_description'];
			$cell_id = $product['cell_id'];
			$cell_name = $product['cell_name'];
			$machine_id = $product['machine_id'];
			$machine_name = $product['machine_name'];
			$product_cost = $product['product_cost'];
			$product_price = $product['product_price'];
			$product_multiple = $product['product_multiple'];
			$product_sale_dest = $product['product_sale_dest'];
			$product_motd = $product['product_motd'];
			$warehouse_id = $product['warehouse_id'];
			
			if (isset($product['product_machine_time']) && 
				($product['product_machine_time'] != '') &&
				($product['product_machine_time'] != 0))
			{
				$product_uc_per_hour = (1 / $product['product_machine_time']);
			}
			
			$product_minimum_stock = $product['product_minimum_stock'];
			$product_order_total = $product['product_order_total'];
			
			
			if ($product_sale_dest == '')
			{
				$product_sale_dest = 'N/A';
			}
			
			if ($product_uc_per_hour == '')
			{
				$product_uc_per_hour = 'N/A';
			}
			
			if ($product_minimum_stock == '')
			{
				$product_minimum_stock = 'N/A';
			}
			
			$sql_query = 'SELECT warehouse.name as warehouse_name, ' .
						 '(SELECT SUM(stock.quantity) FROM stock where stock.warehouse_id = warehouse.id AND stock.product_id = '.mysql_format_to_number($_REQUEST['id']).') as stock, ' .
						 '(SELECT SUM(custords_product.quantity) FROM custords_product WHERE custords_product.warehouse_id = warehouse.id AND custords_product.product_id = '.mysql_format_to_number($_REQUEST['id']).') as quantity ' .
						 'FROM warehouse ' .
						 'ORDER BY warehouse_name';
			
			$stock_result = mysql_select_query($sql_query);
		} else {
			mysql_save_sql_error(get_php_self(), 'Chargement de la page information article', $sql_query, mysql_errno(), mysql_error());
			$_REQUEST['message'] = 'Erreur : chargement de la page &eacute;chou&eacute;!';
		}
?>
			<TABLE class="left">
				<TR valign="top">
					<TD width="470">
						<TABLE>
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
											<TD class="field_headling" width="80">produit :</TD>
											<TD class="field_separator"></TD>
											<TD class="field_value" width="385"><FONT SIZE="2"><B><?php echo $product_reference.' : '.$product_description; ?></B></FONT></TD>
										</TR>
									</TABLE>
								</TD>
							</TR>
							<TR>
								<TD class="separator"></TD>
							</TR>
							<TR>
								<TD>
									<TABLE>
										<TR>
											<TD class="field_headling" width="80">cellule :</TD>
											<TD class="field_separator"></TD>
											<TD class="field_value" width="150"><A HREF="cell_information.php?id=<?php echo $cell_id; ?>" TARGET="_self"><B><?php echo $cell_name; ?></B></A></TD>
											<TD class="field_separator"></TD>
											<TD class="field_headling" width="80">machine :</TD>
											<TD class="field_separator"></TD>
											<TD class="field_value" width="150"><A HREF="machine_information.php?id=<?php echo $machine_id; ?>" TARGET="_self"><B><?php echo $machine_name; ?></B></A></TD>
										</TR>
										<TR>
											<TD class="field_headling" width="80">co&ucirc;t :</TD>
											<TD class="field_separator"></TD>
											<TD class="field_value" width="150"><B><?php echo format_to_currency_with_decimal($product_cost, 2, '&euro;'); ?></B></TD>
											<TD class="field_separator"></TD>
											<TD class="field_headling" width="80">prix :</TD>
											<TD class="field_separator"></TD>
											<TD class="field_value" width="150"><B><?php echo format_to_currency_with_decimal($product_price, 2, '&euro;'); ?></B></TD>
										</TR>
										<TR>
											<TD class="field_headling" width="80">multiple :</TD>
											<TD class="field_separator"></TD>
											<TD class="field_value" width="150"><B><?php echo format_to_number($product_multiple); ?></B></TD>
											<TD class="field_separator"></TD>
											<TD class="field_headling" width="80">dest. vente :</TD>
											<TD class="field_separator"></TD>
											<TD class="field_value" width="150"><B><?php echo $product_sale_dest; ?></B></TD>
										</TR>
										<TR>
											<TD class="field_headling" width="80">MOTD :</TD>
											<TD class="field_separator"></TD>
											<TD class="field_value" width="150"><B><?php echo format_to_number($product_motd); ?></B></TD>
											<TD class="field_separator"></TD>
											<TD class="field_headling" width="80">total en cde :</TD>
											<TD class="field_separator"></TD>
											<TD class="field_value" width="150"><B><?php echo format_to_number($product_order_total); ?></B></TD>
										</TR>
										<TR>
											<TD class="field_headling" width="80">UC / h :</TD>
											<TD class="field_separator"></TD>
											<TD class="field_value" width="150"><B><?php echo format_to_number($product_uc_per_hour); ?></B></TD>
											<TD class="field_separator"></TD>
											<TD class="field_headling" width="80">stock mini :</TD>
											<TD class="field_separator"></TD>
											<TD class="field_value" width="150"><B><?php echo format_to_number($product_minimum_stock); ?></B></TD>
										</TR>
									</TABLE>
								</TD>
							</TR>
							<TR>
								<TD class="separator"></TD>
							</TR>
						</TABLE>
					</TD>
					<TD width="5"></TD>
					<TD width="300">
						<TABLE>
							<TR>
								<TD class="main_title_text" valign="top">Liste des stocks</TD>
							</TR>
							<tr>
								<td class="main_title"><img src="../../image/menu/min_separator.jpg" border="0"></td>
							</tr>
							<TR>
								<TD class="min_separator"></TD>
							</TR>
							<TR>
								<TD>
									<TABLE class="left">
										<TR>
											<TD class="header" width="120">Entrep&ocirc;t</TD>
											<TD class="header" width="80">Quantit&eacute;</TD>
											<TD class="header" width="100">Stock projet&eacute;</TD>
										</TR>
<?php

$stock_total = 0;

if ($stock_result) {
	
	$alternate = false;
	
	while ($stock = mysql_fetch_array($stock_result, MYSQL_ASSOC)) {

		if ($stock['stock'] != '' || $stock['quantity'] != '') {
			if ($stock['stock'] < $stock['quantity']) {
				echo '<TR class="out_row">';
			} else {
				if ($alternate) {
					echo '<TR class="alternate_row">';
				} else {
					echo '<TR class="row">';
				}
			}
	
			echo '<TD class="data" width="120">'.$stock['warehouse_name'].'</TD>
				  <TD class="number" align="right" width="80"><B>'.format_to_number($stock['stock']).'</B></TD>
				  <TD class="number" align="right" width="100"><B>'.format_to_number($stock['stock'] - $stock['quantity']).'</B></TD>
			   </TR>';
			
			$stock_total += $stock['stock'];
			$alternate = !$alternate;
		}
	}
}

	if ($stock_total < $product_order_total) {
		echo '<TR class="out_row">';
	} else {
		echo '<TR class="total_row">';
	}
?>
												<TD class="data" width="120"><B>TOTAL :</B></TD>
												<TD class="number" align="right" width="80"><B><?php echo format_to_number($stock_total); ?></B></TD>
												<TD class="number" align="right" width="100"><B><?php echo format_to_number($stock_total - $product_order_total); ?></B></TD>
											</TR>
										</TBODY>
								   	</TABLE>
								 </TD>
							</TR>
						</TABLE>
					</TD>
				</TR>
			</TABLE>
			<TABLE class="normal">
				<TR>
					<TD align="center">
						<TABLE class="normal">
							<TR>
								<TD class="main_image"><A HREF="../print/product_information.php?id=<?php echo $_REQUEST['id']; ?>" TARGET="_self"><img src="../../image/printer_little.jpg" ALT="Impression des informations produit"></A></TD>
								<TD class="low_separator">&nbsp;</TD>
								<TD class="main_image"><A HREF="../export/product_information.php?id=<?php echo $_REQUEST['id']; ?>" TARGET="_self"><img src="../../image/excel_little.jpg" alt="Exportation Excel des informations produit"></A></TD>
								<TD class="low_separator">&nbsp;</TD>
								<TD class="main_title_text">Liste des commandes <input type="text" name="count" value="..." class="count"></TD>
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
								
$columns = array(array('Cde.', 60, 'link', 'center', '', 'custords_information.php', 9, '_parent', false, false, 'Num&eacute;ro de commande'),
				 array('ADV', 40, 'link', 'center', '', 'sales_admin_information.php', 10, '_parent', false, false, 'ADV'),
				 array('Client', 60, 'link', 'center', '', 'customer_information.php', 11, '_parent', false, false, 'Num&eacute;ro de client'),
				 array('Nom', 200, 'data', 'left', '', '', 0, '', false, false, 'Nom de client'),
				 array('Priorit&eacute;', 80, 'number', 'center', 'bold', '', 0, '', false, false, 'Priorit&eacute; client'),
				 array('Date', 70, 'date', 'center', '', '', 0, '', false, false, 'Date de commande'),
				 array('Quantit&eacute;', 60, 'number', 'right', 'bold', '', 0, '', true, false, 'Quantit&eacute; command&eacute;e'),
				 array('Entrep&ocirc;t', 120, 'data', 'center', '', '', 0, '', false, false, 'Site d\'exp&eacute;dition'),
				 array('XP dir.', 50, 'boolean', 'center', '', '', 0, '', false, false, 'Exp&eacute;dition directe'));

get_table_head($columns);
							  ?></TD>
				</TR>
				<TR>
					<TD><iframe src="product_information_result.php<?php 

echo '?history='.get_php_self().'&id='.$_REQUEST['id'];

if (isset($_REQUEST['order']))
{
	echo '&order='.$_REQUEST['order'];
}
else
{
	echo '&order=5';
}

if (isset($_REQUEST['sort']))
{
	echo '&sort='.$_REQUEST['sort'];
}
else
{
	echo '&sort=asc';
}
?>" width="790" height="190" name="result" frameborder="0" marginwidth="0" marginheight="0"></iframe></TD>
				</TR>
			</TABLE>
<?php
	}
}
?>
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN" "http://www.w3.org/TR/html4/loose.dtd">
<html>
<head>
	<title>ELBA - Appli Cellules - Information produit</title>
	<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
	<link href="../../css/style.css" rel="stylesheet" type="text/css">
	<SCRIPT type='text/javascript' src='../../scripts/main.js'></SCRIPT>
</HEAD>
<BODY class="main_body">
<FORM NAME='mainForm' ACTION='product_information.php' METHOD='POST'>
<TABLE class="normal">
	<TR>
		<TD class="main_title_text">Information produit<?php 

if (isset($_REQUEST['id']) && $_REQUEST['id'] != '') {
	$sql_query = 'SELECT CONCAT(reference, \' : \', name) ' .
				 'FROM product ' .
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
					<TD class="field_header">r&eacute;f&eacute;rence :</TD>
					<TD class="field_separator"></TD>
					<TD class="field_value">
						<INPUT TYPE='text' NAME='product_reference' VALUE='<?php 
if (isset($_REQUEST['product_reference'])) {
	echo $_REQUEST['product_reference']; 
}
?>' STYLE='width:100px' MAXLENGTH='20'>
					</TD>
					<TD class="field_separator"></TD>
					<TD class="field_header">d&eacute;signation :</TD>
					<TD class="field_separator"></TD>
					<TD class="field_value">
						<INPUT TYPE='text' NAME='product_description' VALUE='<?php 
if (isset($_REQUEST['product_description'])) {
	echo $_REQUEST['product_description']; 
}
?>' STYLE='width:150px' MAXLENGTH='20'>
					</TD>
					<TD class="field_separator"></TD>
					<TD class="field_value">
						<INPUT TYPE='submit' NAME='search' VALUE='rechercher' ALT='Rechercher un produit'>
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

if ((((isset($_REQUEST['product_reference'])) && ($_REQUEST['product_reference'] != '')) || 
	((isset($_REQUEST['product_description'])) && ($_REQUEST['product_description'] != ''))) && 
	((!isset($_REQUEST['id'])) || ($_REQUEST['id'] == ''))) {
	$count = get_product_list();
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