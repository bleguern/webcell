<?php
	include_once(dirname(__FILE__).'/../../util/sql.php');
	include_once(dirname(__FILE__).'/../../util/util.php');

function export_order_dc_to_excel() {
	
	$xls_output = '<TABLE class="data" border=\'0\' name=\'order_bu\' cellpadding=\'3\' cellspacing=\'1\' style=\'background-color:#d9d9d9\'>
						<TR>';
									
	if ($_SESSION['site_id'] == 100) {
		$xls_output .= '<TD><b>Site</b></TD>';
	}
							
	$xls_output .= '<TD><b>Cellule</b></TD>
					<TD><b>Machine</b></TD>
					<TD><b>Reference</b></TD>
					<TD><b>Description</b></TD>
					<TD><b>Commande</b></TD>
					<TD><b>ADV</b></TD>
					<TD><b>Numero client</b></TD>
					<TD><b>Nom client</b></TD>
					<TD><b>Priorite client</b></TD>
					<TD><b>Quantite</b></TD>
					<TD><b>Date</b></TD>
					<TD><b>Entrepot</b></TD>
					<TD><b>Expedition directe</b></TD>
					<TD><b>Bloquee</b></TD>
					<TD><b>Rupture</b></TD>
				</TR>';

	$sql = 'SELECT site.name as site_name, ' .
		   'cell.name as cell_name, ' .
		   'machine.name as machine_name, ' .
		   'product.reference as product_reference, ' .
		   'product.name as product_description, ' .
		   'custords.number as custords_number, ' .
		   'sales_admin.trigram as sales_admin_trigram, ' .
		   'customer.number as customer_number, ' .
		   'customer.name as customer_name, ' .
		   'customer.priority as customer_priority, ' .
		   'custords_product.quantity as custords_product_quantity, ' .
		   'UNIX_TIMESTAMP(custords_product.date) as custords_product_date, ' .
		   'custords_product.direct_forwarding as direct_forwarding, ' .
		   'custords_product.blocked as blocked, ' .
		   'warehouse.name as warehouse_name, ' .
		   '(SELECT SUM(quantity) FROM stock WHERE stock.product_id = product.id AND stock.warehouse_id = custords_product.warehouse_id) as stock_dc, ' . 
		   '(SELECT SUM(quantity) FROM custords_product cust_prod WHERE cust_prod.warehouse_id = custords_product.warehouse_id AND cust_prod.product_id = product.id AND cust_prod.date < NOW()) as quantity_dc ' .
		   'FROM custords_product ' .
		   'LEFT OUTER JOIN custords ON custords_product.custords_id = custords.id ' .
		   'LEFT OUTER JOIN product ON custords_product.product_id = product.id ' .
		   'LEFT OUTER JOIN machine ON product.machine_id = machine.id ' .
		   'LEFT OUTER JOIN cell ON machine.cell_id = cell.id ' .
		   'LEFT OUTER JOIN site ON product.site_id = site.id ' .
		   'LEFT OUTER JOIN customer ON custords.customer_id = customer.id ' .
		   'LEFT OUTER JOIN sales_admin ON custords.sales_admin_id = sales_admin.id ' .
		   'LEFT OUTER JOIN warehouse ON custords_product.warehouse_id = warehouse.id ' .
		   'WHERE custords_product.quantity <> 0 ' .
		   'AND custords_product.direct_forwarding = 0 ';
			  
	if ($_SESSION['site_id'] != 100) {
		$sql .= 'AND product.site_id = '.mysql_format_to_number($_SESSION['site_id']).' ';
	}
		   
	$sql .= 'ORDER BY site_name, cell_name, machine_name, product_reference, custords_product_date, customer_priority';

	$result = mysql_select_query($sql);

	if($result) {

		$now = time();
		
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
			
			$xls_output .= '<TR style=\'background-color:#FFFFFF\' valign=\'center\'>';
			
			if ($_SESSION['site_id'] == 100) {
				$xls_output .= '<TD class="data">'.$row['site_name'].'</TD>';
			}
			
			$xls_output .= '<TD class="data">'.$row['cell_name'].'</TD>
							 <TD class="data">'.$row['machine_name'].'</TD>
							 <TD class="data">'.format_to_reference($row['product_reference']).'</TD>
							 <TD class="data">'.$row['product_description'].'</TD>
							 <TD class="data">'.$row['custords_number'].'</TD>
							 <TD class="data">'.$row['sales_admin_trigram'].'</TD>
							 <TD class="data">'.$row['customer_number'].'</TD>
							 <TD class="data">'.$row['customer_name'].'</TD>
							 <TD class="data">'.$row['customer_priority'].'</TD>
							 <TD class="number">'.format_to_number($row['custords_product_quantity']).'</TD>
							 <TD class="number">'.format_to_date($row['custords_product_date']).'</TD>
							 <TD class="data">'.$row['warehouse_name'].'</TD>
							 <TD class="center">'.$direct_forwarding.'</TD>
							 <TD class="center">'.$blocked.'</TD>
							 <TD class="center">'.$is_out.'</TD>
						   </TR>';
		}
	}

	$xls_output .= '</TABLE>';

	return $xls_output;
}


session_cache_limiter("must-revalidate");
header("Content-type: application/vnd.ms-excel");
header("Content-disposition: attachment; filename=commandes_cdc_".date("Ymd").".xls");

session_start();

print export_order_dc_to_excel();

exit();

?>
