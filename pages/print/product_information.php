<?php
	include_once(dirname(__FILE__).'/../../util/sql.php');
	include_once(dirname(__FILE__).'/../../util/util.php');
	include_once(dirname(__FILE__).'/../../util/pdf.php');
	
	if (!isset($_REQUEST['id']) || ($_REQUEST['id'] == '')) {
		header('Location: ../show/product_information.php');
		exit();
	}
	
function print_product_information() {

	$header = array(array('Commande', 25, "data"), 
					array('ADV', 15, "center"),
					array('Client', 25, "data"),
					array('Nom', 50, "data"),
					array('Priorite', 15, "center"),
					array('Date', 30, "center"),
					array('Quantite', 25, "number"),
					array('Entrepot', 30, "data"),
					array('XP dir.', 20, "center"),
					array('Bloquee', 20, "center"),
					array('Rupture', 20, "center"));
					
	$data = array();
	$count = 0;
		
	$sql = 'SELECT custords.number as custords_number, ' .
		   'sales_admin.trigram as sales_admin_trigram, ' .
		   'customer.number as customer_number, ' .
		   'customer.name as customer_name, ' .
		   'customer.priority as customer_priority, ' .
		   'UNIX_TIMESTAMP(custords_product.date) as custords_product_date, ' .
		   'custords_product.quantity, ' .
		   'custords_product.direct_forwarding, ' .
		   'custords_product.blocked, ' .
		   'warehouse.name as warehouse_name, ' .
		   '(SELECT SUM(quantity) FROM stock WHERE stock.product_id = product.id AND stock.warehouse_id = custords_product.warehouse_id) as stock_dc, ' . 
		   '(SELECT SUM(quantity) FROM custords_product cust_prod WHERE cust_prod.warehouse_id = custords_product.warehouse_id AND cust_prod.product_id = product.id AND cust_prod.date < NOW()) as quantity_dc ' .
		   'FROM custords_product ' .
		   'LEFT OUTER JOIN product ON custords_product.product_id = product.id ' .
		   'LEFT OUTER JOIN custords ON custords_product.custords_id = custords.id ' .
		   'LEFT OUTER JOIN customer ON custords.customer_id = customer.id ' .
		   'LEFT OUTER JOIN warehouse ON custords_product.warehouse_id = warehouse.id ' .
		   'LEFT OUTER JOIN sales_admin ON custords.sales_admin_id = sales_admin.id ' .
		   'WHERE product.id = '.mysql_format_to_number($_REQUEST['id']).' ' .
		   'AND custords_product.quantity <> 0 ' .
		   'ORDER BY custords_product.date, customer_number';

	$result = mysql_select_query($sql);

	if($result) {

		setlocale(LC_TIME, "fr_FR");
		$now = strtotime(date("d")."-".date("m")."-".date("Y"));
			
		while ($row = mysql_fetch_array($result, MYSQL_ASSOC)) {
	
			$is_out = 'Non';
			$blocked = 'Non';
			$direct_forwarding = 'Non';
			
			$date = strtotime(str_replace("/", "-", $row['custords_product_date']));
				
			if (($row['stock_dc'] < $row['quantity_dc']) && ($date < $now)) {
				$is_out = 'Oui';
			}
			
			if ($row['blocked'] == 1) {
				$blocked = 'Oui';
			}
			
			if ($row['direct_forwarding'] == 1) {
				$direct_forwarding = 'Oui';
			}
			
			$data[$count] = array($row['custords_number'], 
								  $row['sales_admin_trigram'],
								  $row['customer_number'],
								  $row['customer_name'],
								  $row['customer_priority'],
								  format_to_date($row['custords_product_date']),
								  format_to_number($row['quantity']),
								  $row['warehouse_name'],
								  $direct_forwarding,
								  $blocked, 
								  $is_out);
			
			$count++;
		}
	}
	
	$product = mysql_select_query('SELECT reference, name FROM product WHERE id = '.mysql_format_to_number($_REQUEST['id']).' LIMIT 1');
	$product = mysql_fetch_row($product);
				
	$bottom = 'Reference : '.$product[0].' : '.$product[1];
	
	print_pdf('Information produit : '.$product[0].' | '.$_SESSION['site_name'], $bottom, $_SERVER['REMOTE_ADDR'], $header, $data);
}


session_cache_limiter("must-revalidate");
session_start();

print_product_information();

exit();
?>
