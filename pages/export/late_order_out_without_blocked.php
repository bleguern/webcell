<?php
	include_once(dirname(__FILE__).'/../../util/sql.php');
	include_once(dirname(__FILE__).'/../../util/util.php');

function export_late_order_to_excel() {
	
	$xls_output = '<TABLE class="data" border=\'0\' name=\'late_order\' cellpadding=\'3\' cellspacing=\'1\' style=\'background-color:#d9d9d9\'>
						<TR>';
									
	if ($_SESSION['site_id'] == 100) {
		$xls_output .= '<TD><b>Site</b></TD>';
	}
							
	$xls_output .= '<TD><b>Cellule</b></TD>
					<TD><b>Machine</b></TD>
					<TD><b>Reference</b></TD>
					<TD><b>Description</b></TD>
					<TD><b>Quantite</b></TD>
					<TD><b>Client</b></TD>
					<TD><b>Nom client</b></TD>
					<TD><b>Priorite</b></TD>
					<TD><b>Date</b></TD>
					<TD><b>XP dir.</b></TD>
					<TD><b>Commande</b></TD>
					<TD><b>ADV</b></TD>
					<TD><b>Valorisation</b></TD>
				</TR>';
									  
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
		   'sales_admin.trigram as sales_admin_trigram, ' .
		   'product.price, ' .
		   '(SELECT SUM(quantity) FROM stock WHERE stock.product_id = product.id AND stock.warehouse_id = custords_product.warehouse_id) as stock_dc, ' . 
		   '(SELECT SUM(quantity) FROM custords_product cust_prod WHERE cust_prod.warehouse_id = custords_product.warehouse_id AND cust_prod.product_id = product.id AND cust_prod.date < NOW()) as quantity_dc ' .
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
			'ORDER BY site_name, cell_name, machine_name, custords_product.date, product_reference';
	
	$result = mysql_select_query($sql);

	if($result) {

		while ($row = mysql_fetch_array($result, MYSQL_ASSOC)) {
			
			$valo = 0;
			
			if ($row['stock_dc'] < $row['quantity_dc']) {
				$direct_forwarding = 'Non';
				
				if ($row['direct_forwarding'] == 1) {
					$direct_forwarding = 'Oui';
				}
				
				$valo = $row['quantity'] * $row['price'];
									  
				$xls_output .= '<TR style=\'background-color:#FFFFFF\' valign=\'center\'>';
										
				if ($_SESSION['site_id'] == 100) {
					$xls_output .= '<TD class="data">'.$row['site_name'].'</TD>';
				}
										
				$xls_output .= '<TD class="data">'.$row['cell_name'].'</TD>
								 <TD class="data">'.$row['machine_name'].'</TD>
								 <TD class="data">'.format_to_reference($row['product_reference']).'</TD>
								 <TD class="data">'.$row['product_description'].'</TD>
								 <TD class="number"><B>'.format_to_number($row['quantity']).'</B></TD>
							     <TD class="data">'.$row['customer_number'].'</TD>
								 <TD class="data">'.$row['customer_name'].'</TD>
								 <TD class="center"><B>'.$row['customer_priority'].'</B></TD>
							     <TD class="data">'.format_to_date($row['custords_product_date']).'</TD>
								 <TD class="center">'.$direct_forwarding.'</TD>
								 <TD class="data">'.$row['custords_number'].'</TD>
								 <TD class="data">'.$row['sales_admin_trigram'].'</TD>
								 <TD class="number"><B>'.format_to_decimal($valo, 2).'</B></TD>
							   </TR>';
			}
		}
	}

	$xls_output .= '</TABLE>';

	return $xls_output;
}


session_cache_limiter("must-revalidate");
header("Content-type: application/vnd.ms-excel");
header("Content-disposition: attachment; filename=commandes_en_retard_en_rupture_non_bloquees_".date("Ymd").".xls");

session_start();

if (!isset($_SESSION['site_id']) || ($_SESSION['site_id'] == '')) {
	header('Location: ../main.php');
	exit();
} else {
	print export_late_order_to_excel();
	exit();
}

?>