<?php
	session_start();
	include_once(dirname(__FILE__).'/../../util/auth.php');
	include_once(dirname(__FILE__).'/../../util/sql.php');
	include_once(dirname(__FILE__).'/../../util/util.php');
	
	if (!isset($_SESSION['site_id']) || ($_SESSION['site_id'] == '')) {
		header('Location: ../main.php');
		exit();
	}

function get_custords_list() {

	$count = 0;
	
	if (((!isset($_REQUEST['id'])) || ($_REQUEST['id'] == '')) && 
		((isset($_REQUEST['custords_number'])) && ($_REQUEST['custords_number'] != ''))) {
		
		$sql_query = 'SELECT DISTINCT custords.number as custords_number, ' .
					 'sales_admin.trigram as sales_admin_trigram, ' .
					 'customer.number as customer_number, ' .
					 'customer.name as customer_name, ' .
					 'customer.priority as customer_priority, ' .
					 'custords.id as custords_id, ' .
					 'sales_admin.id as sales_admin_id, ' .
					 'customer.id as customer_id ' .
					 'FROM custords ' .
				     'LEFT OUTER JOIN customer ON custords.customer_id = customer.id ' .
				     'LEFT OUTER JOIN custords_product ON custords_product.custords_id = custords.id ' .
				     'LEFT OUTER JOIN product ON custords_product.product_id = product.id ' .
			         'LEFT OUTER JOIN sales_admin ON custords.sales_admin_id = sales_admin.id ' .
			         'WHERE 1 ';
			  
		if ($_SESSION['site_id'] != 100) {
			$sql_query .= 'AND product.site_id = '.mysql_format_to_number($_SESSION['site_id']).' ';
		}
			   
		$sql_query .= 'AND UPPER(custords.number) LIKE \'%'.strtoupper(str_replace("'", "\'", $_REQUEST['custords_number'])).'%\' ' .
			          'ORDER BY custords.number';
				
				
		$columns = array(array('Commande', 170, 'link', 'left', '', 'custords_information.php', 5, '_parent', false, false, 'Num&eacute;ro de commande'),
						 array('ADV', 100, 'link', 'left', '', 'sales_admin_information.php', 6, '_parent', false, false, 'ADV'),
						 array('Client', 190, 'link', 'left', '', 'customer_information.php', 7, '_parent', false, false, 'Num&eacute;ro de client'),
						 array('Nom', 200, 'data', 'left', '', '', 0, '', false, false, 'Nom de client'),
						 array('Priorit&eacute;', 100, 'data', 'center', 'bold', '', 0, '', false, false, 'Priorit&eacute; client'));
		
		$count = get_table_result_with_query($sql_query, $columns);
	}
	
	return $count;
}

	
function get_order_list() {

	if ((isset($_REQUEST['id'])) && 
		($_REQUEST['id'] != '')) {

		if ($_SESSION['site_id'] == 100) {
			$columns = array(array('Site', 50, 'data', 'left', '', '', 0, '', false, false, 'Site de production'),
							 array('Cellule', 70, 'link', 'left', '', 'cell_information.php', 10, '_parent', false, false, 'Cellule de production'),
							 array('Machine', 70, 'link', 'left', '', 'machine_information.php', 11, '_parent', false, false, 'Machine de production'),
							 array('R&eacute;f&eacute;rence', 80, 'link', 'left', '', 'product_information.php', 12, '_parent', false, false, 'R&eacute;f&eacute;rence produit'),
							 array('D&eacute;signation', 180, 'data', 'left', '', '', 0, '', false, false, 'D&eacute;signation produit'),
							 array('Date', 70, 'date', 'center', '', '', 0, '', false, false, 'Date de commande'),
							 array('Qt&eacute;.', 50, 'number', 'right', 'bold', '', 0, '', true, false, 'Quantit&eacute; command&eacute;e'),
							 array('Entrep&ocirc;t', 120, 'data', 'center', '', '', 0, '', false, false, 'Site d\'exp&eacute;dition'),
							 array('XP dir.', 35, 'boolean', 'center', '', '', 0, '', false, false, 'Exp&eacute;dition directe'));
		} else {
			$columns = array(array('Cellule', 70, 'link', 'left', '', 'cell_information.php', 9, '_parent', false, false, 'Cellule de production'),
							 array('Machine', 70, 'link', 'left', '', 'machine_information.php', 10, '_parent', false, false, 'Machine de production'),
							 array('R&eacute;f&eacute;rence', 80, 'link', 'left', '', 'product_information.php', 11, '_parent', false, false, 'R&eacute;f&eacute;rence produit'),
							 array('D&eacute;signation', 180, 'data', 'left', '', '', 0, '', false, false, 'D&eacute;signation produit'),
							 array('Date', 70, 'date', 'center', '', '', 0, '', false, false, 'Date de commande'),
							 array('Qt&eacute;.', 50, 'number', 'right', 'bold', '', 0, '', true, false, 'Quantit&eacute; command&eacute;e'),
							 array('Entrep&ocirc;t', 120, 'data', 'center', '', '', 0, '', false, false, 'Site d\'exp&eacute;dition'),
							 array('XP dir.', 35, 'boolean', 'center', '', '', 0, '', false, false, 'Exp&eacute;dition directe'));
		}
		
		$sql_query = 'SELECT site.trigram as site_trigram, ' .
					 'cell.name as cell_name, ' .
 					 'machine.name as machine_name, ' .
					 'product.reference as product_reference, ' .
					 'product.name as product_description, ' .
					 'UNIX_TIMESTAMP(custords_product.date) as custords_product_date, ' .
					 'custords_product.quantity, ' .
					 'warehouse.name as warehouse_name, ' .
					 'custords_product.direct_forwarding, ' .
					 'custords_product.blocked, ' .
					 '(SELECT SUM(quantity) FROM stock WHERE stock.product_id = product.id AND stock.warehouse_id = custords_product.warehouse_id) as stock_dc, ' . 
					 '(SELECT SUM(quantity) FROM custords_product cust_prod WHERE cust_prod.warehouse_id = custords_product.warehouse_id AND cust_prod.product_id = product.id AND cust_prod.date < NOW()) as quantity_dc, ' .
					 'custords.id as custords_id, ' .
					 'cell.id as cell_id, ' .
					 'machine.id as machine_id, ' .
					 'product.id as product_id ' .
					 'FROM custords_product ' .
					 'LEFT OUTER JOIN product ON custords_product.product_id = product.id ' .
					 'LEFT OUTER JOIN machine ON product.machine_id = machine.id ' .
					 'LEFT OUTER JOIN cell ON machine.cell_id = cell.id ' .
					 'LEFT OUTER JOIN site ON product.site_id = site.id ' .
					 'LEFT OUTER JOIN custords ON custords_product.custords_id = custords.id ' .
					 'LEFT OUTER JOIN warehouse ON custords_product.warehouse_id = warehouse.id ' .
					 'WHERE custords.id = '.mysql_format_to_number($_REQUEST['id']).' ';
		
		if ($_SESSION['site_id'] != 100) {
			$sql_query .= 'AND product.site_id = '.mysql_format_to_number($_SESSION['site_id']).' ';
		}
			   
		$sql_query .= 'AND custords_product.quantity <> 0 ' .
			    	  'ORDER BY custords_product_date, site_trigram, cell_name, machine_name, product_reference';
				
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
				
				if ($_SESSION['site_id'] == 100) {
					$result_table[$i][0] = $style;
					$result_table[$i][1] = $row['site_trigram'];
					$result_table[$i][2] = $row['cell_name'];
					$result_table[$i][3] = $row['machine_name'];
					$result_table[$i][4] = $row['product_reference'];
					$result_table[$i][5] = $row['product_description'];
					$result_table[$i][6] = $row['custords_product_date'];
					$result_table[$i][7] = $row['quantity'];
					$result_table[$i][8] = $row['warehouse_name'];
					$result_table[$i][9] = $row['direct_forwarding'];
					$result_table[$i][10] = $row['custords_id'];
					$result_table[$i][11] = $row['cell_id'];
					$result_table[$i][12] = $row['machine_id'];
					$result_table[$i][13] = $row['product_id'];
				} else {
					$result_table[$i][0] = $style;
					$result_table[$i][1] = $row['cell_name'];
					$result_table[$i][2] = $row['machine_name'];
					$result_table[$i][3] = $row['product_reference'];
					$result_table[$i][4] = $row['product_description'];
					$result_table[$i][5] = $row['custords_product_date'];
					$result_table[$i][6] = $row['quantity'];
					$result_table[$i][7] = $row['warehouse_name'];
					$result_table[$i][8] = $row['direct_forwarding'];
					$result_table[$i][9] = $row['custords_id'];
					$result_table[$i][10] = $row['cell_id'];
					$result_table[$i][11] = $row['machine_id'];
					$result_table[$i][12] = $row['product_id'];
				}
	
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
	<title>ELBA - Appli Cellules - Information commande</title>
	<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
	<link href="../../css/style.css" rel="stylesheet" type="text/css">
	<SCRIPT type='text/javascript' src='../../scripts/main.js'></SCRIPT>
</HEAD>
<BODY class="main_body">
<FORM NAME="resultForm" ACTION="custords_information_result.php" METHOD="POST">
<?php
start_loading();
if (((!isset($_REQUEST['id'])) || ($_REQUEST['id'] == '')) && 
	((isset($_REQUEST['custords_number'])) && ($_REQUEST['custords_number'] != ''))) {
	$count = get_custords_list();
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