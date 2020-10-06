<?php
	include_once(dirname(__FILE__).'/../../util/auth.php');
	include_once(dirname(__FILE__).'/../../util/sql.php');
	include_once(dirname(__FILE__).'/../../util/util.php');
	include_once(dirname(__FILE__).'/../../util/pdf.php');
	
	if (!isset($_REQUEST['id']) || ($_REQUEST['id'] == '')) {
		header('Location: ../show/planning_board_by_machine.php');
		exit();
	}

function print_planning_board_by_cell() {
	
	if ((isset($_SESSION['site_id'])) && 
		($_SESSION['site_id'] != '')) {
		$machine = mysql_simple_select_query('SELECT name FROM machine WHERE id = '.mysql_format_to_number($_REQUEST['id']));
									  
		$header = array(array('Reference', 25, "data"),
						array('Designation', 45, "data"),
						array('Stock CDC', 15, "number"),
						array('Cdes CDC', 15, "number"),
						array('Rupture CDC', 20, "center"),
						array('Stock BU', 15, "number"),
						array('Cdes BU', 15, "number"),
						array('Rupture BU', 20, "center"),
						array('Rupture', 20, "number"),
						array('Stock mini', 20, "number"),
						array('A produire', 20, "number"),
						array('Temps', 15, "number"),
						array('Comment.', 20, "data"),
						array('STT', 10, "center"));
						
		$data = array();
		$count = 0;
	
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
	
		if($result) {
	
			while ($row = mysql_fetch_array($result, MYSQL_ASSOC)) {
	
				$to_produce_str = '';
				$minimum_stock_str = '';
				$quantity_str = '';
				
				$stt = 'NON';
				
				$quantity = ($row['flow_stock_dc'] + $row['flow_stock_bu']) - ($row['flow_quantity_dc'] + $row['flow_quantity_bu']);
				$to_produce = $row['product_minimum_stock'] - $quantity;
				
				if ($to_produce > 0) {
					$to_produce_str = format_to_number($to_produce);
				} else {
					$to_produce = 0;
				}
				
				if ($row['product_minimum_stock'] != null) {
					$minimum_stock_str = format_to_number($row['product_minimum_stock']);
				}
				
				if ($quantity < 0) {
					$quantity_str = format_to_number($quantity);
				}
				
				if ($row['main_machine_id'] != '') {
					$stt = 'OUI';
				}
				
				$time = ($to_produce * $row['product_machine_time']);
				
				$data[$count] = array($row['product_reference'],
									  $row['product_name'],
									  format_to_number($row['flow_stock_dc']),
									  format_to_number($row['flow_quantity_dc']),
									  $row['flow_date_dc'],
									  format_to_number($row['flow_stock_bu']),
									  format_to_number($row['flow_quantity_bu']),
									  $row['flow_date_bu'],
									  $quantity_str,
									  $minimum_stock_str,
									  $to_produce_str,
									  format_to_time($time),
									  format_to_string($row['product_description']),
									  $stt);
				
				$count++;
			}
		}
	
		setlocale(LC_TIME, 'fr_FR');

		$horizon = 'Horizon CDC : '.htmlentities(strftime("%d/%I/%Y", mktime(0, 0, 0, date("m"), (date("d") + $_SESSION['horizon']), date("Y")))).' / ' .
				   'Horizon BU : '.htmlentities(strftime("%d/%I/%Y", mktime(0, 0, 0, date("m"), (date("d") + $_SESSION['horizon_bu']), date("Y"))));
		
	
		print_pdf('Planning board : '.$_SESSION['site_name'].' : machine '.$machine, $horizon, $_SERVER['REMOTE_ADDR'], $header, $data);
	}
}


session_cache_limiter("must-revalidate");
session_start();

print_planning_board_by_cell();

exit();
?>