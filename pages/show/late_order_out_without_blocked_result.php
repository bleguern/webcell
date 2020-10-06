<?php
	session_start();
	include_once(dirname(__FILE__).'/../../util/auth.php');
	include_once(dirname(__FILE__).'/../../util/sql.php');
	include_once(dirname(__FILE__).'/../../util/util.php');
	
	if (!isset($_SESSION['site_id']) || ($_SESSION['site_id'] == '')) {
		header('Location: ../main.php');
		exit();
	}

function get_late_order_out_without_blocked_list() {

	$count = array();
	
	$count[0] = 0;
	$count[1] = 0;
	
	if ($_SESSION['site_id'] == 100) {
		$columns = array(array('Site', 25, 'data', 'left', '', '', 0, '', false, false, 'Site de production'),
						 array('Cellule', 55, 'link', 'left', '', 'cell_information.php', 13, '_parent', false, true, 'Cellule de production'),
						 array('Machine', 55, 'link', 'left', '', 'machine_information.php', 14, '_parent', false, false, 'Machine de production'),
						 array('R&eacute;f&eacute;rence', 75, 'link', 'left', '', 'product_information.php', 15, '_parent', false, false, 'R&eacute;f&eacute;rence produit'),
						 array('D&eacute;signation', 165, 'data', 'left', '', '', 0, '', false, false, 'D&eacute;signation produit'),
						 array('Qt&eacute;.', 30, 'number', 'right', 'bold', '', 0, '', true, false, 'Quantit&eacute; command&eacute;e'),
						 array('Client', 55, 'link', 'center', '', 'customer_information.php', 16, '_parent', false, false, 'Num&eacute;ro de client'),
						 array('Nom', 150, 'data', 'left', '', '', 0, '', false, false, 'Nom de client'),
						 array('P', 10, 'data', 'center', 'bold', '', 0, '', false, false, 'Priorit&eacute; client'),
						 array('Date', 50, 'date', 'center', '', '', 0, '', false, false, 'Date de commande'),
						 array('X', 10, 'cross', 'center', '', '', 0, '', false, false, 'Exp&eacute;dition directe'),
						 array('Cde.', 40, 'link', 'center', '', 'custords_information.php', 17, '_parent', false, false, 'Num&eacute;ro de commande'),
						 array('ADV', 40, 'link', 'center', '', 'sales_admin_information.php', 18, '_parent', false, false, 'ADV'));
	} else {
		$columns = array(array('Cellule', 55, 'link', 'left', '', 'cell_information.php', 12, '_parent', false, true, 'Cellule de production'),
						 array('Machine', 55, 'link', 'left', '', 'machine_information.php', 13, '_parent', false, false, 'Machine de production'),
						 array('R&eacute;f&eacute;rence', 85, 'link', 'left', '', 'product_information.php', 14, '_parent', false, false, 'R&eacute;f&eacute;rence produit'),
						 array('D&eacute;signation', 165, 'data', 'left', '', '', 0, '', false, false, 'D&eacute;signation produit'),
						 array('Qt&eacute;.', 35, 'number', 'right', 'bold', '', 0, '', true, false, 'Quantit&eacute; command&eacute;e'),
						 array('Client', 55, 'link', 'center', '', 'customer_information.php', 15, '_parent', false, false, 'Num&eacute;ro de client'),
						 array('Nom', 150, 'data', 'left', '', '', 0, '', false, false, 'Nom de client'),
						 array('P', 10, 'data', 'center', 'bold', '', 0, '', false, false, 'Priorit&eacute; client'),
						 array('Date', 50, 'date', 'center', '', '', 0, '', false, false, 'Date de commande'),
						 array('X', 10, 'cross', 'center', '', '', 0, '', false, false, 'Exp&eacute;dition directe'),
						 array('Cde.', 45, 'link', 'center', '', 'custords_information.php', 16, '_parent', false, false, 'Num&eacute;ro de commande'),
						 array('ADV', 40, 'link', 'center', '', 'sales_admin_information.php', 17, '_parent', false, false, 'ADV'));
	}
	
	$sql = 'SELECT site.trigram as site_trigram, ' .
		   'cell.name as cell_name, ' .
		   'machine.name as machine_name, ' .
		   'product.reference as product_reference, ' .
		   'product.name as product_description, ' .
		   'custords_product.quantity, ' .
		   'customer.number as customer_number, ' .
		   'customer.name as customer_name, ' .
		   'customer.priority as customer_priority, ' .
		   'UNIX_TIMESTAMP(custords_product.date) as custords_product_date, ' .
		   'custords_product.direct_forwarding, ' .
		   'custords.number as custords_number, ' .
		   'sales_admin.trigram as sales_admin_trigram, ' .
		   '(SELECT SUM(quantity) FROM stock WHERE stock.product_id = product.id AND stock.warehouse_id = custords_product.warehouse_id) as stock_dc, ' . 
		   '(SELECT SUM(quantity) FROM custords_product cust_prod WHERE cust_prod.warehouse_id = custords_product.warehouse_id AND cust_prod.product_id = product.id AND cust_prod.date < NOW()) as quantity_dc, ' .
		   'cell.id as cell_id, ' .
		   'machine.id as machine_id, ' .
		   'product.id as product_id, ' .
		   'customer.id as customer_id, ' .
		   'custords.id as custords_id, ' .
		   'sales_admin.id as sales_admin_id ' .
		   'FROM custords_product ' .
		   'LEFT OUTER JOIN product ON custords_product.product_id = product.id ' .
		   'LEFT OUTER JOIN custords ON custords_product.custords_id = custords.id ' .
		   'LEFT OUTER JOIN customer ON custords.customer_id = customer.id ' .
		   'LEFT OUTER JOIN machine ON product.machine_id = machine.id ' .
		   'LEFT OUTER JOIN cell ON machine.cell_id = cell.id ' .
		   'LEFT OUTER JOIN site ON product.site_id = site.id ' .
		   'LEFT OUTER JOIN sales_admin ON custords.sales_admin_id = sales_admin.id ' .
		   'WHERE 1 ';
		  
	if ($_SESSION['site_id'] != 100) {
		$sql .= 'AND product.site_id = '.mysql_format_to_number($_SESSION['site_id']).' ';
	}
		   
	$sql .= 'AND custords_product.date < NOW() ' .
			'AND custords_product.quantity <> 0 ' .
			'AND custords_product.blocked = 0 ' .
			'ORDER BY site_trigram, cell_name, machine_name, custords_product.date, product_reference';
	
	
	$result = mysql_select_query($sql);
	$result_table = array();
	$references = array();
	
	if($result) {
	
		$i = 0;
		
		while ($row = mysql_fetch_array($result, MYSQL_ASSOC)) {

			if ($row['stock_dc'] < $row['quantity_dc']) {
			
				$style = 'out_row';
				
				if ($_SESSION['site_id'] == 100) {
					$result_table[$i][0] = $style;
					$result_table[$i][1] = $row['site_trigram'];
					$result_table[$i][2] = $row['cell_name'];
					$result_table[$i][3] = $row['machine_name'];
					$result_table[$i][4] = $row['product_reference'];
					$result_table[$i][5] = $row['product_description'];
					$result_table[$i][6] = $row['quantity'];
					$result_table[$i][7] = $row['customer_number'];
					$result_table[$i][8] = $row['customer_name'];
					$result_table[$i][9] = $row['customer_priority'];
					$result_table[$i][10] = $row['custords_product_date'];
					$result_table[$i][11] = $row['direct_forwarding'];
					$result_table[$i][12] = $row['custords_number'];
					$result_table[$i][13] = $row['sales_admin_trigram'];
					$result_table[$i][14] = $row['cell_id'];
					$result_table[$i][15] = $row['machine_id'];
					$result_table[$i][16] = $row['product_id'];
					$result_table[$i][17] = $row['customer_id'];
					$result_table[$i][18] = $row['custords_id'];
					$result_table[$i][19] = $row['sales_admin_id'];
				} else {
					$result_table[$i][0] = $style;
					$result_table[$i][1] = $row['cell_name'];
					$result_table[$i][2] = $row['machine_name'];
					$result_table[$i][3] = $row['product_reference'];
					$result_table[$i][4] = $row['product_description'];
					$result_table[$i][5] = $row['quantity'];
					$result_table[$i][6] = $row['customer_number'];
					$result_table[$i][7] = $row['customer_name'];
					$result_table[$i][8] = $row['customer_priority'];
					$result_table[$i][9] = $row['custords_product_date'];
					$result_table[$i][10] = $row['direct_forwarding'];
					$result_table[$i][11] = $row['custords_number'];
					$result_table[$i][12] = $row['sales_admin_trigram'];
					$result_table[$i][13] = $row['cell_id'];
					$result_table[$i][14] = $row['machine_id'];
					$result_table[$i][15] = $row['product_id'];
					$result_table[$i][16] = $row['customer_id'];
					$result_table[$i][17] = $row['custords_id'];
					$result_table[$i][18] = $row['sales_admin_id'];
				}
				
				$references = add_reference($references, $row['product_reference']);
			}
			
			$i++;
		}
	}
	
	$count[0] = get_table_result_with_table_with_total($result_table, $columns);
	$count[1] = count($references);
	
	return $count;
}
?>
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN" "http://www.w3.org/TR/html4/loose.dtd">
<html>
<head>
	<title>ELBA - Appli Cellules - Commandes en retard non bloqu&eacute;es et en rupture</title>
	<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
	<link href="../../css/style.css" rel="stylesheet" type="text/css">
	<SCRIPT type='text/javascript' src='../../scripts/main.js'></SCRIPT>
</HEAD>
<BODY class="main_body">
<FORM NAME="resultForm" ACTION="late_order_out_without_blocked_result.php" METHOD="POST">
<?php
start_loading();
$count = get_late_order_out_without_blocked_list();
stop_loading();
?>
<INPUT TYPE="hidden" NAME="count" VALUE="<?php echo $count[0]; ?>">
<INPUT TYPE="hidden" NAME="count2" VALUE="<?php echo $count[1]; ?>">
</BODY>
<SCRIPT type="text/javascript">
	updateCount()
</SCRIPT>
</FORM>
</HTML>