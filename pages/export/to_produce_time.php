<?php
	include_once(dirname(__FILE__).'/../../util/sql.php');
	include_once(dirname(__FILE__).'/../../util/util.php');

function export_to_produce_time_to_excel() {
	
	
	$sub_query_site_filter = '';

	if ($_SESSION['site_id'] != 100) {
		$sub_query_site_filter .= 'custords_product.site_id = '.mysql_format_to_number($_SESSION['site_id']).' AND ';
	}
	
	
	$xls_output = '<TABLE class="data" border=\'0\' name=\'to_produce\' cellpadding=\'3\' cellspacing=\'1\' style=\'background-color:#d9d9d9\'>
						<TR>';
									
	if ($_SESSION['site_id'] == 100) {
		$xls_output .= '<TD><b>Site</b></TD>';
	}
							
	$xls_output .= '<TD><b>Cellule</b></TD>
					<TD><b>Machine</b></TD>
					<TD><b>Reference</b></TD>
					<TD><b>Description</b></TD>
					<TD><b>Stock CDC</b></TD>
					<TD><b>Commandes CDC</b></TD>
					<TD><b>Manque CDC</b></TD>
					<TD><b>Stock BU</b></TD>
					<TD><b>Commandes BU</b></TD>
					<TD><b>Manque BU</b></TD>
					<TD><b>Manque total</b></TD>
					<TD><b>Valorisation</b></TD>
					<TD><b>Charge</b></TD>
					<TD><b>STT</b></TD>
				</TR>';

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
	
	$count = 0;
	
	if (isset($product_database_data)) {
		$count = count($product_database_data);
	}

	if ($count > 0) {

		for ($i = 0; $i < $count; $i++) {

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
				
				$xls_output .= '<TR style=\'background-color:#FFFFFF\' valign=\'center\'>';
				
				if ($_SESSION['site_id'] == 100) {
					$xls_output .= '<TD class="data">'.$product_database_data[$i][15].'</TD>';
				}
				
				$xls_output .= '<TD class="data">'.$product_database_data[$i][4].'</TD>
								 <TD class="data">'.$product_database_data[$i][6].'</TD>
								 <TD class="data">'.format_to_reference($product_database_data[$i][1]).'</TD>
								 <TD class="data">'.$product_database_data[$i][2].'</TD>
								 <TD class="number">'.format_to_number($product_database_data[$i][7]).'</TD>
								 <TD class="number">'.format_to_number($quantity_cdc).'</TD>
								 <TD class="number">'.format_to_number($to_produce_cdc).'</TD>
								 <TD class="number">'.format_to_number($product_database_data[$i][8]).'</TD>
								 <TD class="number">'.format_to_number($quantity_bu).'</TD>
								 <TD class="number">'.format_to_number($to_produce_bu).'</TD>
								 <TD class="number">'.format_to_number($to_produce).'</TD>
								 <TD class="number">'.format_to_currency($valo, '').'</TD>
								 <TD class="number">'.$time.'</TD>
								 <TD class="data">'.$stt.'</TD>
							   </TR>';
			 }
		}
	}

	$xls_output .= '</TABLE>';
	
	return $xls_output;
}

session_cache_limiter("must-revalidate");
header("Content-type: application/vnd.ms-excel");
header("Content-disposition: attachment; filename=manquants_".date("Ymd").".xls");

session_start();

print export_to_produce_time_to_excel();

exit();
?>
