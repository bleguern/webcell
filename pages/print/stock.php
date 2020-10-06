<?php
	include_once(dirname(__FILE__).'/../../util/sql.php');
	include_once(dirname(__FILE__).'/../../util/util.php');
	include_once(dirname(__FILE__).'/../../util/pdf.php');
	
function print_stock() {

	if ($_SESSION['site_id'] == 100) {
		$header = array(array('Reference', 30, "data"), 
				  array('Designation', 55, "data"),
				  array('Quantite', 25, "number"),
				  array('Entrepot', 35, "data"),
				  array('Machine', 20, "data"),
				  array('Cellule', 20, "data"),
				  array('Site', 30, "data"),
				  array('Edit_flag', 20, "center"),
				  array('MOTD', 20, "number"),
				  array('Multiple', 20, "number"));
	} else {
		$header = array(array('Reference', 40, "data"), 
				  array('Designation', 55, "data"),
				  array('Quantite', 25, "number"),
				  array('Entrepot', 35, "data"),
				  array('Machine', 30, "data"),
				  array('Cellule', 30, "data"),
				  array('Edit_flag', 20, "center"),
				  array('MOTD', 20, "number"),
				  array('Multiple', 20, "number"));  
	}
					
	$data = array();
	$count = 0;
		
	$sql = 'SELECT product.reference, stock.quantity, warehouse.name as warehouse_name, product.name as description, machine.name as machine, cell.name as cell, site.name as site, machine.active as edit_flag, product.mtd_sales as motd, product.multiple ' .
		   'FROM stock ' .
		   'LEFT OUTER JOIN warehouse ON stock.warehouse_id = warehouse.id ' .
		   'LEFT OUTER JOIN product ON stock.product_id = product.id ' .
		   'LEFT OUTER JOIN machine ON product.machine_id = machine.id ' .
		   'LEFT OUTER JOIN cell ON machine.cell_id = cell.id ' .
		   'LEFT OUTER JOIN site ON product.site_id = site.id ' .
		   'WHERE 1 ';
			  
	if ($_SESSION['site_id'] != 100) {
		$sql .= 'AND product.site_id = '.mysql_format_to_number($_SESSION['site_id']).' ';
	}
		   
	$sql .= 'ORDER BY site.name, product.reference, warehouse.name';

	$result = mysql_select_query($sql);

	if($result) {

		while ($row = mysql_fetch_array($result, MYSQL_ASSOC)) {
	
			$edit_flag = 'FAUX';
			
			if ($row['edit_flag'] == 1) {
				$edit_flag = 'VRAI';
			}
			
			if ($_SESSION['site_id'] == 100) {
				$data[$count] = array($row['reference'], 
									  $row['description'],
									  format_to_number($row['quantity']),
									  $row['warehouse_name'],
									  $row['machine'],
									  $row['cell'],
									  $row['site'],
									  $edit_flag,
									  format_to_number($row['motd']),
									  format_to_number($row['multiple']));
			} else {
				$data[$count] = array($row['reference'], 
									  $row['description'],
									  format_to_number($row['quantity']),
									  $row['warehouse_name'],
									  $row['machine'],
									  $row['cell'],
									  $edit_flag,
									  format_to_number($row['motd']),
									  format_to_number($row['multiple']));
			}
			
			$count++;
		}
	}
	
	print_pdf('Stock références : '.$_SESSION['site_name'], '', $_SERVER['REMOTE_ADDR'], $header, $data);
}


session_cache_limiter("must-revalidate");
session_start();

if (!isset($_SESSION['site_id']) || ($_SESSION['site_id'] == '')) {
	header('Location: ../main.php');
	exit();
} else {
	print_stock();
	exit();
}
?>
