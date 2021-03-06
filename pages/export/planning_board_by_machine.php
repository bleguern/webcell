<?php
	include_once(dirname(__FILE__).'/../../util/sql.php');
	include_once(dirname(__FILE__).'/../../util/util.php');
	
	if (!isset($_REQUEST['id']) || ($_REQUEST['id'] == '')) {
		header('Location: ../show/planning_board_by_machine.php');
		exit();
	}


function export_planning_board_machine_to_excel() {
	
	$xls_output = '<TABLE class="data" border=\'0\' name=\'planning_board_machine\' cellpadding=\'3\' cellspacing=\'1\' style=\'background-color:#d9d9d9\'>
						<TR>
							<TD><b>Reference</b></TD>
							<TD><b>Designation</b></TD>
							<TD><b>Stock CDC</b></TD>
							<TD><b>Cdes CDC</b></TD>
							<TD><b>Rupture CDC</b></TD>
							<TD><b>Stock BU</b></TD>
							<TD><b>Cdes BU</b></TD>
							<TD><b>Rupture BU</b></TD>
							<TD><b>Rupture</b></TD>
							<TD><b>Stock mini</b></TD>
							<TD><b>A produire</b></TD>
							<TD><b>Temps</b></TD>
							<TD><b>Comment.</b></TD>
							<TD><b>STT</b></TD>
						</TR>';
	
	$sql = 'SELECT product.id as product_id, ' .
		   'product.reference as product_reference, ' .
		   'product.name as product_name, ' .
		   'flow.stock_dc as flow_stock_dc, ' .
		   'flow.quantity_dc as flow_quantity_dc, ' .
		   'DATE_FORMAT(flow.date_dc, \'%d/%m/%Y\') as flow_date_dc, ' .
		   'flow.stock_bu as flow_stock_bu, ' .
		   'flow.quantity_bu as flow_quantity_bu, ' .
		   'DATE_FORMAT(flow.date_bu, \'%d/%m/%Y\') as flow_date_bu, ' .
		   'product.minimum_stock as product_minimum_stock, ' .
		   'product.machine_time as product_machine_time, ' .
		   'product.description as product_description, ' .
		   'machine.main_machine_id ' .
		   'FROM product ' .
		   'LEFT OUTER JOIN flow ON flow.product_id = product.id ' .
		   'LEFT OUTER JOIN machine ON product.machine_id = machine.id ' .
		   'WHERE product.active = 1 AND (product.description IS NULL OR product.description NOT LIKE \'%#ALI#%\') ' .
		   'AND product.site_id = '.mysql_format_to_number($_SESSION['site_id']).' ' .
		   'AND product.id IN (SELECT DISTINCT id FROM product WHERE machine_id = '.mysql_format_to_number($_REQUEST['id']).' OR machine_id IN (SELECT id FROM machine WHERE main_machine_id = '.mysql_format_to_number($_REQUEST['id']).')) ' .
		   'ORDER BY main_machine_id, product_reference';
			   
	$result = mysql_select_query($sql);
	
	if ($result) {

		$i = 0;

		while ($row = mysql_fetch_array($result, MYSQL_NUM)) {

			$product_database_data[$i][0] = $row[0];                // Product ID
			$product_database_data[$i][1] = $row[1];                // Reference
			$product_database_data[$i][2] = $row[2];                // Name
			$product_database_data[$i][3] = $row[3];                // Stock DC
			$product_database_data[$i][4] = $row[4];                // Quantity DC
			$product_database_data[$i][5] = $row[5];                // Date DC
			$product_database_data[$i][6] = $row[6];                // Stock BU
			$product_database_data[$i][7] = $row[7];                // Quantity BU
			$product_database_data[$i][8] = $row[8];                // Date BU
			$product_database_data[$i][9] = $row[9];                // Minimum stock
			$product_database_data[$i][10] = $row[10];              // Machine time
			$product_database_data[$i][11] = $row[11];              // Description
			$product_database_data[$i][12] = $row[12];              // STT

			$i++;
		}
	}
	
	$count = 0;
	
	if (isset($product_database_data)) {
		$count = count($product_database_data);
	}
	
	if ($count > 0) {
	
		for ($i = 0; $i < $count; $i++) {
	
			$to_produce_str = '';
			$minimum_stock_str = '';
			$quantity_str = '';
			
			$stt = 'NON';
			
			$quantity = ($product_database_data[$i][3] + $product_database_data[$i][6]) - ($product_database_data[$i][4] + $product_database_data[$i][7]);
			$to_produce = $product_database_data[$i][9] - $quantity;
			
			if ($to_produce > 0) {
				$to_produce_str = format_to_number($to_produce);
			} else {
				$to_produce = 0;
			}
			
			if ($product_database_data[$i][9] != null) {
				$minimum_stock_str = format_to_number($product_database_data[$i][9]);
			}
			
			if ($quantity < 0) {
				$quantity_str = format_to_number($quantity);
			}
			
			if ($product_database_data[$i][12] != '') {
				$stt = 'OUI';
			}
			
			$time = ($to_produce * $product_database_data[$i][10]);
			
			
			$xls_output .= '<TR style=\'background-color:#FFFFFF\' valign=\'center\'>
							 <TD class="data">'.format_to_reference($product_database_data[$i][1]).'</TD>
							 <TD class="data">'.$product_database_data[$i][2].'</TD>
							 <TD class="number">'.format_to_number($product_database_data[$i][3]).'</TD>
							 <TD class="number">'.format_to_number($product_database_data[$i][4]).'</TD>
							 <TD class="data">'.$product_database_data[$i][5].'</TD>
							 <TD class="number">'.format_to_number($product_database_data[$i][6]).'</TD>
							 <TD class="number">'.format_to_number($product_database_data[$i][7]).'</TD>
							 <TD class="data">'.$product_database_data[$i][8].'</TD>
							 <TD class="number">'.$quantity_str.'</TD>
							 <TD class="number">'.$minimum_stock_str.'</TD>
							 <TD class="number">'.$to_produce_str.'</TD>
							 <TD class="number">'.format_to_time($time).'</TD>
							 <TD class="data">'.format_to_string($product_database_data[$i][11]).'</TD>
							 <TD class="data">'.$stt.'</TD>
						   </TR>';
		}
	}

	$xls_output .= '</TABLE>';
	
	return $xls_output;
}
	
session_cache_limiter("must-revalidate");
header("Content-type: application/vnd.ms-excel");
header("Content-disposition: attachment; filename=planning_board_machine_".date("Ymd").".xls");

session_start();

print export_planning_board_machine_to_excel();

exit();