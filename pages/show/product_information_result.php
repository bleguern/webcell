<?php
	session_start();
	include_once(dirname(__FILE__).'/../../util/auth.php');
	include_once(dirname(__FILE__).'/../../util/sql.php');
	include_once(dirname(__FILE__).'/../../util/util.php');
	
	if (!isset($_SESSION['site_id']) || ($_SESSION['site_id'] == '')) {
		header('Location: ../main.php');
		exit();
	}

function get_product_list() {

	$count = 0;
	
	if (((!isset($_REQUEST['id'])) || ($_REQUEST['id'] == '')) && 
		(((isset($_REQUEST['product_reference'])) && ($_REQUEST['product_reference'] != '')) ||
		((isset($_REQUEST['product_description'])) && ($_REQUEST['product_description'] != '')))) {
		
		
		$sql_query = 'SELECT product.reference as product_reference, ' .
					 'product.name as product_description, ' .
					 'machine.name as machine_name, ' .
					 'cell.name as cell_name, ' .
					 'product.id as product_id, ' .
					 'machine.id as machine_id, ' .
					 'cell.id as cell_id ' .
					 'FROM product ' .
				     'LEFT OUTER JOIN machine ON product.machine_id = machine.id ' .
			         'LEFT OUTER JOIN cell ON machine.cell_id = cell.id ' .
				     'WHERE product.active = 1 ';
			  
		if ($_SESSION['site_id'] != 100) {
			$sql_query .= 'AND product.site_id = '.mysql_format_to_number($_SESSION['site_id']).' ';
		}
			   
		if (isset($_REQUEST['product_reference']) && $_REQUEST['product_reference'] != '') {
			$sql_query .= 'AND UPPER(product.reference) LIKE \'%'.strtoupper(str_replace("'", "\'", $_REQUEST['product_reference'])).'%\' '; 
		}
		
		if (isset($_REQUEST['product_description']) && $_REQUEST['product_description'] != '') {
			$sql_query .= 'AND UPPER(product.name) LIKE \'%'.strtoupper(str_replace("'", "\'", $_REQUEST['product_description'])).'%\' '; 
		}
		
		$sql_query .= 'ORDER BY product_reference';
		
		
		$columns = array(array('R&eacute;f&eacute;rence', 200, 'link', 'left', '', 'product_information.php', 4, '_parent', false, false, 'R&eacute;f&eacute;rence produit'),
						 array('D&eacute;signation', 370, 'data', 'left', '', '', 0, '', false, false, 'D&eacute;signation produit'),
						 array('Machine', 100, 'link', 'left', '', 'machine_information.php', 5, '_parent', false, false, 'Machine de production'),
						 array('Cellule', 100, 'link', 'left', '', 'cell_information.php', 6, '_parent', false, false, 'Cellule de production'));

		$count = get_table_result_with_query($sql_query, $columns);
	}
	
	return $count;
}

function get_order_list() {

	if ((isset($_REQUEST['id'])) && 
		($_REQUEST['id'] != '')) {

		$columns = array(array('Cde.', 60, 'link', 'center', '', 'custords_information.php', 9, '_parent', false, false, 'Num&eacute;ro de commande'),
						 array('ADV', 40, 'link', 'center', '', 'sales_admin_information.php', 10, '_parent', false, false, 'ADV'),
						 array('Client', 60, 'link', 'center', '', 'customer_information.php', 11, '_parent', false, false, 'Num&eacute;ro de client'),
						 array('Nom', 200, 'data', 'left', '', '', 0, '', false, false, 'Nom de client'),
						 array('Priorit&eacute;', 80, 'number', 'center', 'bold', '', 0, '', false, false, 'Priorit&eacute; client'),
						 array('Date', 70, 'date', 'center', '', '', 0, '', false, false, 'Date de commande'),
						 array('Quantit&eacute;', 60, 'number', 'right', 'bold', '', 0, '', true, false, 'Quantit&eacute; command&eacute;e'),
						 array('Entrep&ocirc;t', 120, 'data', 'center', '', '', 0, '', false, false, 'Site d\'exp&eacute;dition'),
						 array('XP dir.', 50, 'boolean', 'center', '', '', 0, '', false, false, 'Exp&eacute;dition directe'));

		$sql_query = 'SELECT custords.number as custords_number, ' .
					 'sales_admin.trigram as sales_admin_trigram, ' .
				     'customer.number as customer_number, ' .
				     'customer.name as customer_name, ' .
					 'customer.priority as customer_priority, ' .
					 'UNIX_TIMESTAMP(custords_product.date) as custords_product_date, ' .
					 'custords_product.quantity, ' .
					 'warehouse.name as warehouse_name, ' .
					 'custords_product.direct_forwarding, ' .
					 'custords_product.blocked, ' .
					 '(SELECT SUM(quantity) FROM stock WHERE stock.product_id = product.id AND stock.warehouse_id = custords_product.warehouse_id) as stock_dc, ' . 
					 '(SELECT SUM(quantity) FROM custords_product cust_prod WHERE cust_prod.warehouse_id = custords_product.warehouse_id AND cust_prod.product_id = product.id AND cust_prod.date < NOW()) as quantity_dc, ' .
					 'custords.id as custords_id, ' .
					 'sales_admin.id as sales_admin_id, ' .
					 'customer.id as customer_id ' .
					 'FROM custords_product ' .
					 'LEFT OUTER JOIN product ON custords_product.product_id = product.id ' .
					 'LEFT OUTER JOIN custords ON custords_product.custords_id = custords.id ' .
					 'LEFT OUTER JOIN customer ON custords.customer_id = customer.id ' .
					 'LEFT OUTER JOIN warehouse ON custords_product.warehouse_id = warehouse.id ' .
					 'LEFT OUTER JOIN sales_admin ON custords.sales_admin_id = sales_admin.id ' .
					 'WHERE product.id = '.mysql_format_to_number($_REQUEST['id']).' ' .
					 'AND custords_product.quantity <> 0 ' .
					 'ORDER BY custords_product.date, customer_priority, customer_number';
			   
		$result = mysql_select_query($sql_query);
		$result_table = array();
	
		if($result) {
			$count = mysql_num_rows($result);
			setlocale(LC_TIME, "fr_FR");
			$now = time();
	
			$i = 0;
			
			while ($row = mysql_fetch_array($result, MYSQL_ASSOC)) {
	
				$style = '';
				
				$is_out = false;
				$date = $row['custords_product_date'];
				
				if ($row['stock_dc'] < $row['quantity_dc']) {
					$is_out = true;
				}
				
				if ($row['blocked'] == 1) {
					if ($is_out && ($date < $now)) {
						$style = 'out_blocked_row';
					} else {
						$style = 'blocked_row';
					}
				} else {
					if ($is_out && ($date < $now)) {
						$style = 'out_row';
					}
				}
				
				$result_table[$i][0] = $style;
				$result_table[$i][1] = $row['custords_number'];
				$result_table[$i][2] = $row['sales_admin_trigram'];
				$result_table[$i][3] = $row['customer_number'];
				$result_table[$i][4] = $row['customer_name'];
				$result_table[$i][5] = $row['customer_priority'];
				$result_table[$i][6] = $row['custords_product_date'];
				$result_table[$i][7] = $row['quantity'];
				$result_table[$i][8] = $row['warehouse_name'];
				$result_table[$i][9] = $row['direct_forwarding'];
				$result_table[$i][10] = $row['custords_id'];
				$result_table[$i][11] = $row['sales_admin_id'];
				$result_table[$i][12] = $row['customer_id'];
				
				$i++;
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
	<title>ELBA - Appli Cellules - Information produit</title>
	<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
	<link href="../../css/style.css" rel="stylesheet" type="text/css">
	<SCRIPT type='text/javascript' src='../../scripts/main.js'></SCRIPT>
</HEAD>
<BODY class="main_body">
<FORM NAME="resultForm" ACTION="product_information_result.php" METHOD="POST">
<?php
start_loading();
if (((!isset($_REQUEST['id'])) || ($_REQUEST['id'] == '')) && 
	(((isset($_REQUEST['product_description'])) && ($_REQUEST['product_description'] != '')) ||
	((isset($_REQUEST['product_reference'])) && ($_REQUEST['product_reference'] != '')))) {
	$count = get_product_list();
}
		
if ((isset($_REQUEST['id'])) && ($_REQUEST['id'] != '')) {
	$count = get_order_list();
}
stop_loading();
?>
<INPUT TYPE="hidden" NAME="count" VALUE="<?php echo $count; ?>">
</BODY>
<SCRIPT type="text/javascript">
	updateCount()
</SCRIPT>
</FORM>
</HTML>