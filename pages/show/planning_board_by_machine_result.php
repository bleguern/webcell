<?php
	session_start();
	include_once(dirname(__FILE__).'/../../util/auth.php');
	include_once(dirname(__FILE__).'/../../util/sql.php');
	include_once(dirname(__FILE__).'/../../util/util.php');
	
	if (!isset($_SESSION['site_id']) || ($_SESSION['site_id'] == '')) {
		header('Location: ../main.php');
		exit();
	}

function get_planning_board_machine_list() {

	if ((isset($_REQUEST['id'])) && 
		($_REQUEST['id'] != '')) {
		
		$columns = array(array('Ref.', 70, 'link', 'left', '', 'product_information.php', 13, '_parent', false, false, 'R&eacute;f&eacute;rence produit'),
						 array('D&eacute;signation', 160, 'data', 'left', '', '', 0, '', false, false, 'D&eacute;signation produit'),
						 array('Stk.', 45, 'number', 'right', '', '', 0, '', false, false, 'Quantit&eacute; en stock CDC'),
						 array('Cdes', 45, 'number', 'right', '', '', 0, '', false, false, 'Quantit&eacute; command&eacute;e CDC'),
						 array('Rupt.', 55, 'date', 'center', '', '', 0, '', false, false, 'Quantit&eacute; en rupture CDC'),
						 array('Stk.', 45, 'number', 'right', '', '', 0, '', false, false, 'Quantit&eacute; en stock BU'),
						 array('Cdes', 45, 'number', 'right', '', '', 0, '', false, false, 'Quantit&eacute; command&eacute;e BU'),
						 array('Rupt.', 55, 'date', 'center', '', '', 0, '', false, false, 'Quantit&eacute; en rupture BU'),
						 array('Rupt.', 45, 'number', 'right', '', '', 0, '', false, false, 'Quantit&eacute; totale en rupture'),
						 array('Mini', 45, 'number', 'right', '', '', 0, '', false, false, 'Stock minimum'),
						 array('Prod.', 50, 'number', 'right', 'bold', '', 0, '', true, false, 'Quantit&eacute; &agrave; produire'),
						 array('Tps.', 50, 'time', 'right', '', '', 0, '', true, false, 'Temps machine'),
						 array('Cmts.', 50, 'data', 'left', '', '', 0, '', false, false, 'Commentaires'));
				 
				 
		$sql_query = 'SELECT product.reference as product_reference, ' .
					 'product.name as product_name, ' .
					 'flow.stock_dc as flow_stock_dc, ' .
					 'flow.quantity_dc as flow_quantity_dc, ' .
					 'UNIX_TIMESTAMP(flow.date_dc) as flow_date_dc, ' .
					 'flow.stock_bu as flow_stock_bu, ' .
					 'flow.quantity_bu as flow_quantity_bu, ' .
					 'UNIX_TIMESTAMP(flow.date_bu) as flow_date_bu, ' .
					 'product.minimum_stock as product_minimum_stock, ' .
					 'product.machine_time as product_machine_time, ' .
					 'product.description as product_description, ' .
					 'product.id as product_id, ' .
					 'machine.main_machine_id ' .
					 'FROM product ' .
					 'LEFT OUTER JOIN flow ON flow.product_id = product.id ' .
					 'LEFT OUTER JOIN machine ON product.machine_id = machine.id ' .
					 'WHERE product.active = 1 AND (product.description IS NULL OR product.description NOT LIKE \'%#ALI#%\') ';
			  
		if ($_SESSION['site_id'] != 100) {
			$sql_query .= 'AND product.site_id = '.mysql_format_to_number($_SESSION['site_id']).' ';
		}
			   
		$sql_query .= 'AND product.id IN (SELECT DISTINCT id FROM product WHERE machine_id = '.mysql_format_to_number($_REQUEST['id']).' OR machine_id IN (SELECT id FROM machine WHERE main_machine_id = '.mysql_format_to_number($_REQUEST['id']).')) ' .
			          'ORDER BY main_machine_id, product_reference';
		
		$result = mysql_select_query($sql_query);
		$result_table = array();
	
		if($result) {
		
			$i = 0;
			$alternate = false;
			
			while ($row = mysql_fetch_array($result, MYSQL_ASSOC)) {
	
				$style = '';
				
				$to_produce_str = '';
				$minimum_stock_str = '';
				$quantity_str = '';
				
				$quantity = ($row['flow_stock_dc'] + $row['flow_stock_bu']) - ($row['flow_quantity_dc'] + $row['flow_quantity_bu']);
				$to_produce = $row['product_minimum_stock'] - $quantity;
				
				if ($row['main_machine_id'] != '')
				{
					if ($alternate) {
						$style = 'sub_contract_alternate_row';
					} else {
						$style = 'sub_contract_row';
					}
				}
				
				if ($to_produce < 0) {
					$to_produce = 0;
				}
				
				if ($quantity > 0) {
					$quantity = 0;
				}
		
				$time = ($to_produce * $row['product_machine_time']);
				
				
				$result_table[$i][0] = $style;
				$result_table[$i][1] = $row['product_reference'];
				$result_table[$i][2] = $row['product_name'];
				$result_table[$i][3] = $row['flow_stock_dc'];
				$result_table[$i][4] = $row['flow_quantity_dc'];
				$result_table[$i][5] = $row['flow_date_dc'];
				$result_table[$i][6] = $row['flow_stock_bu'];
				$result_table[$i][7] = $row['flow_quantity_bu'];
				$result_table[$i][8] = $row['flow_date_bu'];
				$result_table[$i][9] = $quantity;
				$result_table[$i][10] = $row['product_minimum_stock'];
				$result_table[$i][11] = $to_produce;
				$result_table[$i][12] = $time;
				$result_table[$i][13] = $row['product_description'];
				$result_table[$i][14] = $row['product_id'];
	
				$i++;
				$alternate != $alternate;
			}
		}
		
		$count = get_table_result_with_table_with_total($result_table, $columns);
	}
	
	return $count;
}
?>
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN" "http://www.w3.org/TR/html4/loose.dtd">
<html>
<head>
	<title>ELBA - Appli Cellules - Planning board par machine</title>
	<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
	<link href="../../css/style.css" rel="stylesheet" type="text/css">
	<SCRIPT type='text/javascript' src='../../scripts/main.js'></SCRIPT>
</HEAD>
<BODY class="main_body">
<FORM NAME="resultForm" ACTION="planning_board_by_machine_result.php" METHOD="POST">
<?php
start_loading();
if ((isset($_REQUEST['id'])) && ($_REQUEST['id'] != '')) {
	$count = get_planning_board_machine_list();
}
stop_loading();
?>
<INPUT TYPE="hidden" NAME="count" VALUE="<?php echo $count; ?>">
</BODY>
</FORM>
</HTML>