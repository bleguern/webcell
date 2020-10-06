<?php
	include_once(dirname(__FILE__).'/../../util/sql.php');
	include_once(dirname(__FILE__).'/../../util/util.php');
	include_once(dirname(__FILE__).'/../../util/pdf.php');

function print_late_order() {
	
	if ($_SESSION['site_id'] == 100) {
		$header = array(array('Site', 15, "data"), 
				  array('Cellule', 15, "data"), 
				  array('Machine', 15, "data"),
				  array('Reference', 20, "data"),
				  array('Designation', 45, "data"),
				  array('Quantite', 15, "number"),
				  array('Client', 17, "data"),
				  array('Nom', 40, "data"),
				  array('Prio.', 10, "center"),
				  array('Date', 20, "center"),
				  array('XP dir.', 15, "center"),
				  array('Cde.', 15, "data"),
				  array('Valo.', 15, "number"),
				  array('Bloquee', 15, "center"));
	} else {
		$header = array(array('Cellule', 15, "data"), 
				  array('Machine', 15, "data"),
				  array('Reference', 20, "data"),
				  array('Designation', 45, "data"),
				  array('Quantite', 15, "number"),
				  array('Client', 17, "data"),
				  array('Nom', 40, "data"),
				  array('Prio.', 10, "center"),
				  array('Date', 20, "center"),
				  array('XP dir.', 15, "center"),
				  array('Cde.', 15, "data"),
				  array('Valo.', 15, "number"),
				  array('Bloquee', 15, "center"));
	}
									  
	$data = array();
	$count = 0;
	
	$sql = 'SELECT site.name as site_name, ' .
		   'cell.id as cell_id, ' .
		   'cell.name as cell_name, ' .
		   'machine.id as machine_id, ' .
		   'machine.name as machine_name, ' .
		   'product.id as product_id, ' .
		   'product.reference as product_reference, ' .
		   'product.name as product_description, ' .
		   'custords_product.quantity, ' .
		   'customer.id as customer_id, ' .
		   'customer.number as customer_number, ' .
		   'customer.name as customer_name, ' .
		   'customer.priority as customer_priority, ' .
		   'UNIX_TIMESTAMP(custords_product.date) as custords_product_date, ' .
		   'custords_product.direct_forwarding, ' .
		   'custords.id as custords_id, ' .
		   'custords.number as custords_number, ' .
		   '(product.price * custords_product.quantity) as valo, ' .
		   'custords_product.blocked, ' .
		   '(SELECT SUM(quantity) FROM stock WHERE stock.product_id = product.id AND stock.warehouse_id = custords_product.warehouse_id) as stock_dc, ' . 
		   '(SELECT SUM(quantity) FROM custords_product cust_prod WHERE cust_prod.warehouse_id = custords_product.warehouse_id AND cust_prod.product_id = product.id AND cust_prod.date < NOW()) as quantity_dc ' .
		   'FROM custords_product ' .
		   'LEFT OUTER JOIN product ON custords_product.product_id = product.id ' .
		   'LEFT OUTER JOIN custords ON custords_product.custords_id = custords.id ' .
		   'LEFT OUTER JOIN customer ON custords.customer_id = customer.id ' .
		   'LEFT OUTER JOIN machine ON product.machine_id = machine.id ' .
		   'LEFT OUTER JOIN cell ON machine.cell_id = cell.id ' .
		   'LEFT OUTER JOIN site ON product.site_id = site.id ' .
		   'WHERE 1 ';
		  
	if ($_SESSION['site_id'] != 100) {
		$sql .= 'AND product.site_id = '.mysql_format_to_number($_SESSION['site_id']).' ';
	}
		   
	$sql .= 'AND custords_product.date < NOW() ' .
			'AND custords_product.quantity <> 0 ' .
			'ORDER BY site_name, cell_name, machine_name, custords_product.date, product_reference';
	
	$result = mysql_select_query($sql);

	if($result) {

		while ($row = mysql_fetch_array($result, MYSQL_ASSOC)) {

			if ($row['stock_dc'] < $row['quantity_dc']) {
			
				$blocked = 'Non';
				$direct_forwarding = 'Non';
					
				if ($row['blocked'] == 1) {
					$blocked = 'Oui';
				}
				
				if ($row['direct_forwarding'] == 1) {
					$direct_forwarding = 'Oui';
				}
				
				if ($_SESSION['site_id'] == 100) {
					$data[$count] = array($row['site_name'], 
										  $row['cell_name'], 
										  $row['machine_name'],
										  $row['product_reference'],
										  $row['product_description'],
										  format_to_number($row['quantity']),
										  $row['customer_number'],
										  $row['customer_name'],
										  $row['customer_priority'],
									      format_to_date($row['custords_product_date']),
										  $direct_forwarding,
										  $row['custords_number'],
										  format_to_currency($row['valo'], ''),
										  $blocked);
				} else {
					$data[$count] = array($row['cell_name'], 
										  $row['machine_name'],
										  $row['product_reference'],
										  $row['product_description'],
										  format_to_number($row['quantity']),
										  $row['customer_number'],
										  $row['customer_name'],
										  $row['customer_priority'],
									      format_to_date($row['custords_product_date']),
										  $direct_forwarding,
										  $row['custords_number'],
										  format_to_currency($row['valo'], ''),
										  $blocked);
				}
				
				$count++;
			}
		}
	}

	print_pdf('Commandes en retard en rupture : '.$_SESSION['site_name'], '', $_SERVER['REMOTE_ADDR'], $header, $data);
}


session_cache_limiter("must-revalidate");
session_start();

print_late_order();

exit();
?>
