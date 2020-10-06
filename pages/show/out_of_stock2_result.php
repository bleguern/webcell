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

	
function clean($product_array)
{
	$new_count = 0;
	$new_product_array = array();
		
	if (isset($product_array))
	{
		$count = count($product_array);
		$next = false;
		
		$i = 0;
		
		while (($i + 1) < $count)
		{
			if ($product_array[$i][1] == $product_array[$i + 1][1])
			{
				$next = true;
				$new_product_array[$new_count] = $product_array[$i];
				$new_count++;
			}
			else
			{
				if ($next)
				{
					$next = false;
					$new_product_array[$new_count] = $product_array[$i];
					$new_count++;
				}
			}
			
			$i++;
		}
	}
	
	return $new_product_array;
}

function is_needed($product_array, $id)
{
	$needed = false;
	
	if (isset($product_array))
	{
		$to_produce = 0;
		
		for ($i = 0; $i < count($product_array); $i++)
		{
			if ($product_array[$i][1] == $id)
			{
				$to_produce = ($product_array[$i][6] - $product_array[$i][10]);
		
				if ($to_produce > 0)
				{
					$needed = true;
					break;
				}
			}
		}
	}

	return $needed;
}

function get_out_of_stock_list() {

	$count = 0;
	$result_table = array();
	
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
				 
	$sql = 'SELECT site.trigram, ' .
		   'product.id, ' .
		   'product.reference, ' .
		   'product.name, ' .
		   'warehouse.trigram, ' .
		   'warehouse.name , ' .
		   '(SELECT SUM(quantity) FROM custords_product WHERE custords_product.blocked = 0 AND custords_product.product_id = product.id AND custords_product.warehouse_id = warehouse.id), ' .
		   '(SELECT SUM(quantity) FROM custords_product WHERE custords_product.blocked = 1 AND custords_product.product_id = product.id AND custords_product.warehouse_id = warehouse.id), ' .
		   '(SELECT SUM(quantity) FROM custords_product WHERE custords_product.blocked = 0 AND custords_product.date <= DATE_ADD(NOW(), INTERVAL 1 DAY) AND custords_product.product_id = product.id AND custords_product.warehouse_id = warehouse.id), ' .
		   '(SELECT SUM(quantity) FROM custords_product WHERE custords_product.blocked = 0 AND custords_product.date <= DATE_ADD(NOW(), INTERVAL '.mysql_format_to_number($_SESSION['horizon']).' DAY) AND custords_product.product_id = product.id AND custords_product.warehouse_id = warehouse.id), ' .
		   '(SELECT SUM(quantity) FROM stock WHERE stock.product_id = product.id AND stock.warehouse_id = warehouse.id), ' .
		   '(SELECT SUM(quantity) FROM stock WHERE stock.product_id = product.id), ' .
		   'product.multiple, ' .
		   'product.mtd_sales, ' .
		   'SUM(stock.quantity), ' .
		   'SUM(custords_product.quantity) ' .
		   'FROM product ' .
		   'LEFT OUTER JOIN custords_product ON custords_product.product_id = product.id ' .
		   'LEFT OUTER JOIN site ON product.site_id = site.id ' .
		   'LEFT OUTER JOIN stock ON stock.product_id = product.id ' .
		   'LEFT OUTER JOIN warehouse ON custords_product.warehouse_id = warehouse.id OR stock.warehouse_id = warehouse.id ' .
		   'WHERE warehouse.trigram NOT IN (\'MOU\', \'TR\', \'LM\') ' .
		   'GROUP BY warehouse.name, product.reference ' .
		   'ORDER BY site.trigram, product.reference, warehouse.name';
	
	$result = mysql_select_query($sql);
	
	if ($result) {

		$i = 0;

		while ($row = mysql_fetch_array($result, MYSQL_NUM)) {

			$product_database_data[$i][0] = $row[0];                // Site
			$product_database_data[$i][1] = $row[1];                // Product id
			$product_database_data[$i][2] = $row[2];                // Product reference
			$product_database_data[$i][3] = $row[3];                // Product name
			$product_database_data[$i][4] = $row[4];                // Warehouse trigram
			$product_database_data[$i][5] = $row[5];                // Warehouse name
			$product_database_data[$i][6] = $row[6];                // Custords
			$product_database_data[$i][7] = $row[7];                // Custords blocked
			$product_database_data[$i][8] = $row[8];                // Custords D+1
			$product_database_data[$i][9] = $row[9];                // Custords H
			$product_database_data[$i][10] = $row[10];              // Stock warehouse
			$product_database_data[$i][11] = $row[11];              // Stock
			$product_database_data[$i][12] = $row[12];              // Product multiple
			$product_database_data[$i][13] = $row[13];              // Product MTD sales
			
			$i++;
		}
		
		$product_database_data = clean($product_database_data);
		$number = count($product_database_data);
	
		if ($number > 0) {
			
			for ($i = 0; $i < $number; $i++) {
				$stock = $product_database_data[$i][10];
				
				$to_produce_d = $product_database_data[$i][8] - $stock;
				$to_produce_h = $product_database_data[$i][9] - $stock;
				$to_produce_t = $product_database_data[$i][6] - $stock;
				
				if ($to_produce_d < 0) {
					$to_produce_d = 0;
				}
				
				if ($to_produce_h < 0) {
					$to_produce_h = 0;
				}
				
				if ($to_produce_t < 0) {
					$to_produce_t = 0;
				}
				
				if (is_needed($product_database_data, $product_database_data[$i][1]))
				{
					$style = '';
					
					$quantity_d = $stock - $product_database_data[$i][8];
					$quantity_h = $stock - $product_database_data[$i][9];
					$quantity_t = $stock - $product_database_data[$i][6];
					
					$available = ($quantity_t >= 0)?$quantity_t:0;
		
					$forecast = 0;
					
					if ($product_database_data[$i][4] == 'MDG')
					{
						$mtd_sales = $product_database_data[$i][13];
						$forecast = round(($product_database_data[$i][13] / date("j")) * 30);
						$forecast = $stock - $forecast;
					}
					
					$result_table[$count][0] = $style;
					$result_table[$count][1] = $product_database_data[$i][0];
					$result_table[$count][2] = $product_database_data[$i][2];
					$result_table[$count][3] = $product_database_data[$i][3];
					$result_table[$count][4] = $product_database_data[$i][5];
					$result_table[$count][5] = $product_database_data[$i][10];
					$result_table[$count][6] = $product_database_data[$i][6];
					$result_table[$count][7] = $product_database_data[$i][7];
					$result_table[$count][8] = $to_produce_d;
					$result_table[$count][9] = $to_produce_h;
					$result_table[$count][10] = $to_produce_t;
					$result_table[$count][11] = $quantity_d;
					$result_table[$count][12] = $quantity_h;
					$result_table[$count][13] = $quantity_t;
					$result_table[$count][14] = $available;
					$result_table[$count][15] = $product_database_data[$i][12];
					$result_table[$count][16] = $forecast;
					$result_table[$count][17] = $product_database_data[$i][1];
					
					
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
<FORM NAME='outOfStockForm' ACTION='out_of_stock2_result.php' METHOD='POST'>
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