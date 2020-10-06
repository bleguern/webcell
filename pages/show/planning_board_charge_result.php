<?php
	session_start();
	include_once(dirname(__FILE__).'/../../util/auth.php');
	include_once(dirname(__FILE__).'/../../util/sql.php');
	include_once(dirname(__FILE__).'/../../util/util.php');
	
	if (!isset($_SESSION['site_id']) || ($_SESSION['site_id'] == '')) {
		header('Location: ../main.php');
		exit();
	}

function get_planning_board_charge_list() {

	$columns = array(array('Cellule', 125, 'data', 'left', '', '', 0, '', false, true, 'Cellule de production'),
					 array('Machine', 125, 'data', 'left', '', '', 0, '', false, false, 'Machine de production'),
					 array('Charge', 250, 'time', 'right', '', '', 0, '', true, false, 'Charge machine'),
					 array('Charge sans stock mini', 250, 'time', 'right', '', '', 0, '', true, false, 'Charge machine sans stock minimum'));
			   
	$sql_query = 'SELECT cell.name as cell_name, ' .
				 'machine.name as machine_name, ' .
				 'flow.stock_dc as flow_stock_dc, ' .
				 'flow.quantity_dc as flow_quantity_dc, ' .
				 'DATE_FORMAT(flow.date_dc, \'%d/%m/%Y\') as flow_date_dc, ' .
				 'flow.stock_bu as flow_stock_bu, ' .
				 'flow.quantity_bu as flow_quantity_bu, ' .
				 'DATE_FORMAT(flow.date_bu, \'%d/%m/%Y\') as flow_date_bu, ' .
				 'product.minimum_stock as product_minimum_stock, ' .
				 'product.machine_time as product_machine_time, ' .
			     'cell.id as cell_id, ' .
				 'machine.id as machine_id ' .
				 'FROM product ' .
			     'LEFT OUTER JOIN flow ON flow.product_id = product.id ' .
			     'LEFT OUTER JOIN machine ON product.machine_id = machine.id ' .
			     'LEFT OUTER JOIN cell ON machine.cell_id = cell.id ' .
			     'WHERE product.active = 1 ';
			  
	if ($_SESSION['site_id'] != 100) {
		$sql_query .= 'AND product.site_id = '.mysql_format_to_number($_SESSION['site_id']).' ';
	}
		   
	$sql_query .= 'AND machine.id IS NOT NULL AND cell.id IS NOT NULL ' .
		          'ORDER BY cell_name, machine_name';

	$result = mysql_select_query($sql_query);
	$result_table = array();

	if($result) {

		$i = 0;
		$j = 0;
		$style = '';
		$cell_total = 0;
		$machine_total = 0;
		$total = 0;
		$cell_total_without_min_stock = 0;
		$machine_total_without_min_stock = 0;
		$total_without_min_stock = 0;
		$cell = null;
		$machine = null;
		
		while ($row = mysql_fetch_array($result, MYSQL_ASSOC)) {
	
			$quantity = ($row['flow_stock_dc'] + $row['flow_stock_bu']) - ($row['flow_quantity_dc'] + $row['flow_quantity_bu']);
			
			$to_produce = $row['product_minimum_stock'] - $quantity;
			$to_produce_without_min_stock = - $quantity;
			
			if ($to_produce < 0)
			{
				$to_produce = 0;
			}
			
			if ($to_produce_without_min_stock < 0)
			{
				$to_produce_without_min_stock = 0;
			}
			
			if ($to_produce > 0 || $to_produce_without_min_stock > 0) {
			
				if ($i == 0) {
					$machine = $row['machine_name'];
					$cell = $row['cell_name'];
				} else {
					if ($machine != $row['machine_name']) {
						$result_table[$j][0] = $style;
						$result_table[$j][1] = $cell;
						$result_table[$j][2] = $machine;
						$result_table[$j][3] = $machine_total;
						$result_table[$j][4] = $machine_total_without_min_stock;
						
						$j++;
						
						$machine_total = 0;
						$machine_total_without_min_stock = 0;
						$machine = $row['machine_name'];
						$cell = $row['cell_name'];
					}
				}
				
				$time = ($to_produce * $row['product_machine_time']);
				$time_without_min_stock = ($to_produce_without_min_stock * $row['product_machine_time']);
				
			 	$machine_total += $time;
				$machine_total_without_min_stock += $time_without_min_stock;
				
				$i++;
			}
		}
	}
	
	get_table_result_with_table_with_total($result_table, $columns);
}
?>
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN" "http://www.w3.org/TR/html4/loose.dtd">
<html>
<head>
	<title>ELBA - Appli Cellules - Planning board : r&eacute;capitulatif de charge</title>
	<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
	<link href="../../css/style.css" rel="stylesheet" type="text/css">
	<SCRIPT type='text/javascript' src='../../scripts/main.js'></SCRIPT>
</HEAD>
<BODY class="main_body">
<FORM NAME="resultForm" ACTION="planning_board_charge_result.php" METHOD="POST">
<?php
start_loading();
get_planning_board_charge_list();
stop_loading();
?>
<INPUT TYPE="hidden" NAME="count" VALUE="<?php echo $count; ?>">
</BODY>
<SCRIPT type="text/javascript">
	updateCount()
</SCRIPT>
</FORM>
</HTML>