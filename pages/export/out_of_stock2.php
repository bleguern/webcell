<?php
	include_once(dirname(__FILE__).'/../../util/sql.php');
	include_once(dirname(__FILE__).'/../../util/util.php');

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

function export_out_of_stock_to_excel() {

	$xls_output = '<TABLE class="data" border=\'0\' name=\'stock\' cellpadding=\'3\' cellspacing=\'1\' style=\'background-color:#d9d9d9\'>
						<TR>
							<TD><b>Site</b></TD>
							<TD><b>Reference</b></TD>
							<TD><b>Description</b></TD>
							<TD><b>Entrep&ocirc;t</b></TD>
							<TD><b>Stock</b></TD>
							<TD><b>Commandes non bloquee</b></TD>
							<TD><b>Commandes bloquee</b></TD>
							<TD><b>Besoin J + 1</b></TD>
							<TD><b>Besoin J + '.$_SESSION['horizon'].'</b></TD>
							<TD><b>Besoin total</b></TD>
							<TD><b>Stock J + 1</b></TD>
							<TD><b>Stock J + '.$_SESSION['horizon'].'</b></TD>
							<TD><b>Stock total</b></TD>
							<TD><b>Stock disponible</b></TD>
							<TD><b>PCB</b></TD>
							<TD><b>Prevision</b></TD>
						</TR>';
	
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

	if($result) {
	
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
			$display = true;
			$current = null;
			
			for ($i = 0; $i < $number; $i++) {
				
				if ($i == 0) {
					$current = $product_database_data[$i][1];
				} else {
					if ($product_database_data[$i][1] != $current) {
						$current = $product_database_data[$i][1];
					}
				}
				
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
					
					$xls_output .= '<TR style=\'background-color:#FFFFFF\' valign=\'center\'>
										<TD class="data">'.$product_database_data[$i][0].'</TD>
										<TD class="data">'.format_to_reference($product_database_data[$i][2]).'</TD>
										<TD class="data">'.$product_database_data[$i][3].'</TD>
										<TD class="data">'.$product_database_data[$i][5].'</TD>
										<TD class="number"><B>'.format_to_number($product_database_data[$i][10]).'</B></TD>
										<TD class="number">'.format_to_number($product_database_data[$i][6]).'</TD>
										<TD class="number">'.format_to_number($product_database_data[$i][7]).'</TD>
										<TD class="number">'.format_to_number($to_produce_d).'</TD>
										<TD class="number">'.format_to_number($to_produce_h).'</TD>
										<TD class="number">'.format_to_number($to_produce_t).'</TD>
										<TD class="number">'.format_to_number($quantity_d).'</TD>
										<TD class="number">'.format_to_number($quantity_h).'</TD>
										<TD class="number">'.format_to_number($quantity_t).'</TD>
										<TD class="number"><B>'.format_to_number($available).'</B></TD>
										<TD class="number">'.format_to_number($product_database_data[$i][12]).'</TD>
										<TD class="number">'.format_to_number($forecast).'</TD>
								   </TR>';
				}
			}
		}
	}

	$xls_output .= '</TABLE>';
	return $xls_output;
}

session_cache_limiter("must-revalidate");
header("Content-type: application/vnd.ms-excel");
header("Content-disposition: attachment; filename=transferts_".date("Ymd").".xls");

session_start();

print export_out_of_stock_to_excel();
exit();
?>

	
	