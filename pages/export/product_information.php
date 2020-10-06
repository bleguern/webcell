<?php
	include_once(dirname(__FILE__).'/../../util/sql.php');
	include_once(dirname(__FILE__).'/../../util/util.php');
	
	if (!isset($_REQUEST['id']) || ($_REQUEST['id'] == '')) {
		header('Location: ../show/product_information.php');
		exit();
	}
	
function export_product_information_to_excel() {

	$xls_output = '<TABLE class="data" border=\'0\' name=\'information_produit\' cellpadding=\'3\' cellspacing=\'1\' style=\'background-color:#d9d9d9\'>
						<TR>
							<TD><b>Commande</b></TD>
							<TD><b>Client</b></TD>
							<TD><b>Nom</b></TD>
							<TD><b>Priorite</b></TD>
							<TD><b>Date</b></TD>
							<TD><b>Quantite</b></TD>
							<TD><b>Entrep&ocirc;t</b></TD>
							<TD><b>XP dir.</b></TD>
							<TD><b>Bloquee</b></TD>
							<TD><b>Rupture</b></TD>
						</TR>';
		
	$sql = 'SELECT custords.number as custords_number, ' .
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
		   'WHERE product.id = '.mysql_format_to_number($_REQUEST['id']).' ' .
		   'AND custords_product.quantity <> 0 ' .
		   'ORDER BY custords_product.date, customer_number';

	$result = mysql_select_query($sql);

	if($result) {

		$i = 0;
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
			
			$xls_output .= '<TR style=\'background-color:#FFFFFF\' valign=\'center\'>
							 <TD class="data">'.$row['custords_number'].'</TD>
							 <TD class="data">'.$row['customer_number'].'</TD>
							 <TD class="data">'.$row['customer_name'].'</TD>
							 <TD class="center"><B>'.$row['customer_priority'].'</B></TD>
							 <TD class="data">'.format_to_date($row['custords_product_date']).'</TD>
							 <TD class="number">'.format_to_number($row['quantity']).'</TD>
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
header("Content-disposition: attachment; filename=information_produit_".date("Ymd").".xls");

session_start();

print export_product_information_to_excel();
exit();
?>
