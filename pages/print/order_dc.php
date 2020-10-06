<?php
	include_once(dirname(__FILE__).'/../../util/sql.php');
	include_once(dirname(__FILE__).'/../../util/util.php');
	include_once(dirname(__FILE__).'/../../util/pdf.php');

function print_order_dc() {
	
	if ($_SESSION['site_id'] == 100) {
		$header = array(array('Date', 20, "center"), 
				  array('Client', 20, "data"),
				  array('Nom', 45, "data"),
				  array('Priorite', 15, "center"),
				  array('Commande', 20, "center"),
				  array('ADV', 10, "center"),
				  array('Site', 15, "center"),
				  array('Cellule', 15, "data"),
				  array('Machine', 20, "data"),
			      array('Reference', 25, "data"),
			      array('Designation', 50, "data"),
				  array('Quantite', 20, "number"));
	} else {
		$header = array(array('Date', 20, "center"), 
				  array('Client', 20, "data"),
				  array('Nom', 45, "data"),
				  array('Priorite', 15, "center"),
				  array('Commande', 25, "center"),
				  array('ADV', 10, "center"),
				  array('Cellule', 15, "data"),
				  array('Machine', 20, "data"),
			      array('Reference', 30, "data"),
			      array('Designation', 50, "data"),
				  array('Quantite', 20, "number"));
	}
					
	$data = array();
	$count = 0;

	$sql = 'SELECT UNIX_TIMESTAMP(custords_product.date) as custords_product_date, ' .
		   'customer.number as customer_number, ' .
		   'customer.name as customer_name, ' .
		   'customer.priority as customer_priority, ' .
		   'custords.number as custords_number, ' .
		   'site.trigram as site_name, ' .
		   'cell.name as cell_name, ' .
		   'machine.name as machine_name, ' .
		   'product.reference as product_reference, ' .
		   'product.name as product_name, ' .
		   'custords_product.quantity, ' .
		   'custords_product.blocked, ' .
		   'sales_admin.trigram as sales_admin_trigram ' .
		   'FROM custords_product ' .
		   'LEFT OUTER JOIN product ON custords_product.product_id = product.id ' .
		   'LEFT OUTER JOIN custords ON custords_product.custords_id = custords.id ' .
		   'LEFT OUTER JOIN customer ON custords.customer_id = customer.id ' .
		   'LEFT OUTER JOIN machine ON product.machine_id = machine.id ' .
		   'LEFT OUTER JOIN cell ON machine.cell_id = cell.id ' .
		   'LEFT OUTER JOIN site ON product.site_id = site.id ' .
		   'LEFT OUTER JOIN sales_admin ON custords.sales_admin_id = sales_admin.id ' .
		   'WHERE custords_product.direct_forwarding = 0 ';
			   
	if ($_SESSION['site_id'] != 100) {
		$sql .= 'AND product.site_id = '.mysql_format_to_number($_SESSION['site_id']).' ';
	}
		   
	$sql .= 'AND custords_product.quantity <> 0 ' .
		    'ORDER BY custords_product.date, customer_number, product_reference, custords_number';

	$result = mysql_select_query($sql);

	if($result) {

		while ($row = mysql_fetch_array($result, MYSQL_ASSOC)) {
			
			if ($_SESSION['site_id'] == 100) {
				$data[$count] = array(format_to_date($row['custords_product_date']), 
								      $row['customer_number'],
								      $row['customer_name'],
								      $row['customer_priority'],
								      $row['custords_number'],
								      $row['sales_admin_trigram'],
								      $row['site_name'],
								      $row['cell_name'],
								      $row['machine_name'],
								      $row['product_reference'],
								      $row['product_name'],
								      format_to_number($row['quantity']));
			} else {
				$data[$count] = array(format_to_date($row['custords_product_date']), 
								      $row['customer_number'],
								      $row['customer_name'],
								      $row['customer_priority'],
								      $row['custords_number'],
								      $row['sales_admin_trigram'],
								      $row['cell_name'],
								      $row['machine_name'],
								      $row['product_reference'],
								      $row['product_name'],
								      format_to_number($row['quantity']));
			}
			
			$count++;
		}
	}

	print_pdf('Commandes CDC : '.$_SESSION['site_name'], '', $_SERVER['REMOTE_ADDR'], $header, $data);
}


session_cache_limiter("must-revalidate");
session_start();


if (!isset($_SESSION['site_id']) || ($_SESSION['site_id'] == '')) {
	header('Location: ../main.php');
	exit();
} else {
	print_order_dc();
	exit();
}

?>
