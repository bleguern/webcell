<?php
	include_once(dirname(__FILE__).'/../../util/sql.php');
	include_once(dirname(__FILE__).'/../../util/util.php');
	include_once(dirname(__FILE__).'/../../util/pdf.php');

function print_to_produce_time() {

	if ($_SESSION['site_id'] == 100) {
		$header = array(array('Site', 15, "data"), 
					    array('Cellule', 15, "data"), 
					    array('Machine', 15, "data"),
					    array('Reference', 20, "data"),
					    array('Designation', 45, "data"),
					    array('Stock CDC', 15, "number"),
					    array('Cdes CDC', 15, "number"),
					    array('Manque CDC', 20, "number"),
					    array('Stock BU', 15, "number"),
					    array('Cdes BU', 15, "number"),
					    array('Manque BU', 20, "number"),
					    array('Manque', 15, "number"),
					    array('Valo', 15, "number"),
					    array('Charge', 15, "number"),
					    array('STT', 15, "center"));
	} else {
		$header = array(array('Cellule', 15, "data"), 
				  		array('Machine', 15, "data"),
				  		array('Reference', 25, "data"),
				  		array('Designation', 45, "data"),
				  		array('Stock CDC', 20, "number"),
				  		array('Cdes CDC', 20, "number"),
				  		array('Manque CDC', 20, "number"),
				  		array('Stock BU', 20, "number"),
				  		array('Cdes BU', 20, "number"),
				  		array('Manque BU', 20, "number"),
				  		array('Manque', 15, "number"),
				  		array('Valo', 15, "number"),
				  		array('Charge', 15, "number"),
					    array('STT', 15, "center"));
	}
					
	$data = array();
	$sub_query_site_filter = '';
	
	if ($_SESSION['site_id'] != 100) {
		$sub_query_site_filter .= 'custords_product.site_id = '.mysql_format_to_number($_SESSION['site_id']).' AND ';
	}
	
	$sql = 'SELECT DISTINCT product.id, ' .
		   'product.reference, ' .
		   'product.name, ' .
		   'cell.id, ' .
		   'cell.name, ' .
		   'machine.id, ' .
		   'machine.name, ' .
		   '(SELECT SUM(quantity) FROM stock WHERE stock.product_id = product.id AND (stock.warehouse_id NOT IN (SELECT warehouse_id FROM site WHERE id = '.mysql_format_to_number($_SESSION['site_id']).' AND warehouse_id IS NOT NULL) OR stock.warehouse_id IS NULL)), ' .
		   '(SELECT SUM(quantity) FROM stock WHERE stock.product_id = product.id AND stock.warehouse_id IN (SELECT warehouse_id FROM site WHERE id = '.mysql_format_to_number($_SESSION['site_id']).' AND warehouse_id IS NOT NULL)), ' .
		   '(SELECT SUM(custords_product.quantity) FROM custords_product WHERE '.$sub_query_site_filter.'custords_product.direct_forwarding = 0 AND custords_product.product_id = product.id AND custords_product.date <= DATE_ADD(NOW(), INTERVAL '.mysql_format_to_number($_SESSION['horizon']).' DAY)), ' .
		   '(SELECT SUM(custords_product.quantity) FROM custords_product WHERE '.$sub_query_site_filter.'custords_product.direct_forwarding = 1 AND custords_product.product_id = product.id AND custords_product.date <= DATE_ADD(NOW(), INTERVAL '.mysql_format_to_number($_SESSION['horizon_bu']).' DAY)), ' .
		   '(SELECT SUM(custords_product.quantity) FROM custords_product WHERE '.$sub_query_site_filter.'custords_product.direct_forwarding = 0 AND custords_product.product_id = product.id AND custords_product.date <= DATE_ADD(NOW(), INTERVAL '.mysql_format_to_number($_SESSION['horizon_sub']).' DAY)), ' .
		   '(SELECT SUM(custords_product.quantity) FROM custords_product WHERE '.$sub_query_site_filter.'custords_product.direct_forwarding = 1 AND custords_product.product_id = product.id AND custords_product.date <= DATE_ADD(NOW(), INTERVAL '.mysql_format_to_number($_SESSION['horizon_sub_bu']).' DAY)), ' .
		   'product.price, ' .
		   'product.machine_time, ' .
		   'site.trigram, ' .
		   'machine.main_machine_id ' .
		   'FROM product ' .
		   'INNER JOIN custords_product ON custords_product.product_id = product.id ' .
		   'LEFT OUTER JOIN custords ON custords_product.custords_id = custords.id ' .
		   'LEFT OUTER JOIN machine ON product.machine_id = machine.id ' .
		   'LEFT OUTER JOIN cell ON machine.cell_id = cell.id ' .
		   'LEFT OUTER JOIN site ON product.site_id = site.id ' .
		   'WHERE '.$sub_query_site_filter.'1 ';
		  
	if ($_SESSION['site_id'] != 100) {
		$sql .= 'AND product.site_id = '.mysql_format_to_number($_SESSION['site_id']).' ';
	}
		   
	$sql .= 'GROUP BY site.trigram, cell.name, machine.name, product.reference';
	
	$result = mysql_select_query($sql);
	
	if ($result) {

		$i = 0;

		while ($row = mysql_fetch_array($result, MYSQL_NUM)) {

			$product_database_data[$i][0] = $row[0];                // ID
			$product_database_data[$i][1] = $row[1];                // Product reference
			$product_database_data[$i][2] = $row[2];                // Product name
			$product_database_data[$i][3] = $row[3];                // Cell id
			$product_database_data[$i][4] = $row[4];                // Cell name
			$product_database_data[$i][5] = $row[5];                // Machine id
			$product_database_data[$i][6] = $row[6];                // Machine name
			$product_database_data[$i][7] = $row[7];                // Stock CDC
			$product_database_data[$i][8] = $row[8];                // Stock BU
			$product_database_data[$i][9] = $row[9];                // Quantity CDC
			$product_database_data[$i][10] = $row[10];              // Quantity BU
			$product_database_data[$i][11] = $row[11];              // Quantity SUB
			$product_database_data[$i][12] = $row[12];              // Quantity SUB BU
			$product_database_data[$i][13] = $row[13];              // Price
			$product_database_data[$i][14] = $row[14];              // Machine time
			$product_database_data[$i][15] = $row[15];              // Site name
			$product_database_data[$i][16] = $row[16];              // Main machine (SUB)

			$i++;
		}
	}
	
	$number = 0;
	
	if (isset($product_database_data)) {
		$number = count($product_database_data);
	}

	if ($number > 0) {
		
		$count = 0;
		
		for ($i = 0; $i < $number; $i++) {

			$to_produce_cdc = $product_database_data[$i][9] - $product_database_data[$i][7];
			$to_produce_bu = $product_database_data[$i][10] - $product_database_data[$i][8];
			$to_produce_sub_cdc = $product_database_data[$i][11] - $product_database_data[$i][7];
			$to_produce_sub_bu = $product_database_data[$i][12] - $product_database_data[$i][8];
			
			$stt = 'NON';
			
			if ($to_produce_cdc < 0) {
				$to_produce_cdc = 0;
			}
			
			if ($to_produce_bu < 0) {
				$to_produce_bu = 0;
			}
			
			if ($to_produce_sub_cdc < 0) {
				$to_produce_sub_cdc = 0;
			}
			
			if ($to_produce_sub_bu < 0) {
				$to_produce_sub_bu = 0;
			}
			
			if ($product_database_data[$i][16] == '')
			{
				$quantity_cdc = $product_database_data[$i][9];
				$quantity_bu = $product_database_data[$i][10];
				$to_produce = $to_produce_cdc + $to_produce_bu;
			}
			else
			{
				$quantity_cdc = $product_database_data[$i][11];
				$quantity_bu = $product_database_data[$i][12];
				$to_produce = $to_produce_sub_cdc + $to_produce_sub_bu;
				$to_produce_cdc = $to_produce_sub_cdc;
				$to_produce_bu = $to_produce_sub_bu;
				$stt = 'OUI';
			}
			
			if ($to_produce > 0) {
			
				$valo = ($to_produce * $product_database_data[$i][13]);
				$time = ($to_produce * $product_database_data[$i][14]);
				
				if ($_SESSION['site_id'] == 100) {
					$data[$count] = array($product_database_data[$i][15], 
								  	  	  $product_database_data[$i][4], 
								  	  	  $product_database_data[$i][6],
								      	  $product_database_data[$i][1],
								      	  $product_database_data[$i][2],
								      	  format_to_number($product_database_data[$i][7]),
								      	  format_to_number($quantity_cdc),
								      	  format_to_number($to_produce_cdc),
								      	  format_to_number($product_database_data[$i][8]),
								      	  format_to_number($quantity_bu),
								      	  format_to_number($to_produce_bu),
									  	  format_to_number($to_produce),
									  	  format_to_currency($valo, ''),
									  	  format_to_time($time),
										  $stt);
				} else {
					$data[$count] = array($product_database_data[$i][4], 
								  	  	  $product_database_data[$i][6],
								      	  $product_database_data[$i][1],
								      	  $product_database_data[$i][2],
								      	  format_to_number($product_database_data[$i][7]),
								      	  format_to_number($quantity_cdc),
								      	  format_to_number($to_produce_cdc),
								      	  format_to_number($product_database_data[$i][8]),
								      	  format_to_number($quantity_bu),
								      	  format_to_number($to_produce_bu),
									  	  format_to_number($to_produce),
									  	  format_to_currency($valo, ''),
									  	  format_to_time($time),
										  $stt);
				}
			
				$count++;
			 }
		}
	}
	
	setlocale(LC_TIME, 'fr_FR');

	$bottom = 'Horizon CDC : '.htmlentities(strftime("%d/%I/%Y", mktime(0, 0, 0, date("m"), (date("d") + $_SESSION['horizon']), date("Y")))).' / ' .
			   'Horizon BU : '.htmlentities(strftime("%d/%I/%Y", mktime(0, 0, 0, date("m"), (date("d") + $_SESSION['horizon_bu']), date("Y"))));
	
	print_pdf('Manque à produire : '.$_SESSION['site_name'], $bottom, $_SERVER['REMOTE_ADDR'], $header, $data);
}

session_cache_limiter("must-revalidate");
session_start();

print_to_produce_time();

exit();
?>
