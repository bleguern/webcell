<?php
	session_start();
	include_once(dirname(__FILE__).'/../../util/auth.php');
	include_once(dirname(__FILE__).'/../../util/sql.php');
	include_once(dirname(__FILE__).'/../../util/util.php');
	
	if (!isset($_SESSION['site_id']) || ($_SESSION['site_id'] == '')) {
		header('Location: ../main.php');
		exit();
	}

function get_out_of_stock_list() {

	$count = 0;
	$result_table = array();
	
	$columns = array(array('Site', 50, 'data', 'center', '', '', 0, '', false, false, 'Site de production'),
					 array('R&eacute;f&eacute;rence', 90, 'link', 'left', '', 'product_information.php', 8, '_parent', false, false, 'R&eacute;f&eacute;rence produit'),
					 array('D&eacute;signation', 160, 'data', 'left', '', '', 0, '', false, false, 'D&eacute;signation produit'),
					 array('Qt&eacute;. Cd&eacute;e.', 70, 'number', 'right', '', '', 0, '', false, false, 'Quantit&eacute; Command&eacute;e'),
					 array('Stock '.$_REQUEST['warehouse_name'], 160, 'number', 'right', '', '', 0, '', false, false, 'Quantit&eacute; en stock sur '.$_REQUEST['warehouse_name']),
					 array('Stock total', 70, 'number', 'right', '', '', 0, '', false, false, 'Quantit&eacute; en stock total'),
					 array('Stock', 70, 'number', 'right', '', '', 0, '', false, false, 'Quantit&eacute; en stock'),
					 array('Entrep&ocirc;t', 100, 'data', 'center', '', '', 0, '', false, false, 'Entrep&ocirc;t de stockage'));
	
	if (isset($_REQUEST['warehouse']) && ($_REQUEST['warehouse'] != '')) {
		$sql = 'SELECT product.id, ' .
			   'product.reference, ' .
			   'product.name, ' .
			   '(SELECT SUM(quantity) FROM stock WHERE stock.product_id = product.id AND stock.warehouse_id = '.mysql_format_to_number($_REQUEST['warehouse']).'), ' .
			   '(SELECT SUM(quantity) FROM stock WHERE stock.product_id = product.id AND stock.warehouse_id <> '.mysql_format_to_number($_REQUEST['warehouse']).'), ' .
			   'stock.quantity, ' .
			   'warehouse.trigram, ' .
			   'warehouse.name, ' .
			   'site.trigram, ' .
			   '(SELECT SUM(quantity) FROM custords_product WHERE custords_product.product_id = product.id AND custords_product.blocked = 0 AND custords_product.direct_forwarding = 0 AND custords_product.date <= DATE_ADD(NOW(), INTERVAL '.mysql_format_to_number($_SESSION['horizon']).' DAY) AND custords_product.warehouse_id = '.mysql_format_to_number($_REQUEST['warehouse']).') ' .
			   'FROM product ' .
			   'LEFT OUTER JOIN site ON product.site_id = site.id ' .
			   'LEFT OUTER JOIN stock ON stock.product_id = product.id ' .
			   'LEFT OUTER JOIN warehouse ON stock.warehouse_id = warehouse.id ' .
			   'WHERE warehouse.id <> '.mysql_format_to_number($_REQUEST['warehouse']).' ' .
			   'ORDER BY site.name, product.id, warehouse.id';
		
		$result = mysql_select_query($sql);
		
		if ($result) {

			$i = 0;
	
			while ($row = mysql_fetch_array($result, MYSQL_NUM)) {
	
				$product_database_data[$i][0] = $row[0];                // ID
				$product_database_data[$i][1] = $row[1];                // Product reference
				$product_database_data[$i][2] = $row[2];                // Product name
				$product_database_data[$i][3] = $row[3];                // Stock
				$product_database_data[$i][4] = $row[4];                // Stock other
				$product_database_data[$i][5] = $row[5];                // Stock
				$product_database_data[$i][6] = $row[6];                // Warehouse trigram
				$product_database_data[$i][7] = $row[7];                // Warehouse name
				$product_database_data[$i][8] = $row[8];                // Site name
				$product_database_data[$i][9] = $row[9];                // Quantity
	
				$i++;
			}
		}
		
		if ($i > 0) {
			
			for ($i = 0; $i < count($product_database_data); $i++) {
				
				$stock_total = $product_database_data[$i][3] + $product_database_data[$i][4];
				$to_produce = $product_database_data[$i][9] - $product_database_data[$i][3];
				
				if ($to_produce < 0) {
					$to_produce = 0;
				}
				
				if ($to_produce > 0) {
				
					$style = '';
					
					$result_table[$count][0] = $style;
					$result_table[$count][1] = $product_database_data[$i][8];
					$result_table[$count][2] = $product_database_data[$i][1];
					$result_table[$count][3] = $product_database_data[$i][2];
					$result_table[$count][4] = $product_database_data[$i][9];
					$result_table[$count][5] = $product_database_data[$i][3];
					$result_table[$count][6] = $stock_total;
					$result_table[$count][7] = $product_database_data[$i][5];
					$result_table[$count][8] = $product_database_data[$i][7];
					$result_table[$count][9] = $product_database_data[$i][0];
					
					$count++;
				}
			}
		}
	}

	return get_table_result_with_table_with_total($result_table, $columns);
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
<FORM NAME="resultForm" ACTION="out_of_stock_result.php" METHOD="POST">
<?php
start_loading();
$count = get_out_of_stock_list();
stop_loading();
?>
<INPUT TYPE="hidden" NAME="count" VALUE="<?php echo $count; ?>">
</BODY>
<SCRIPT type="text/javascript">
	updateCount()
</SCRIPT>
</FORM>
</HTML>