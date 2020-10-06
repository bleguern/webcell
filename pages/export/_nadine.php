<?php
	include_once(dirname(__FILE__).'/../../util/sql.php');
	include_once(dirname(__FILE__).'/../../util/util.php');

function export_order_to_excel() {
	
	$xls_output = '<TABLE class="data" border=\'0\' name=\'order_bu\' cellpadding=\'3\' cellspacing=\'1\' =\'background-color:#d9d9d9\'>
						<TR>';
									
	$xls_output .= '<TD><b>Reference</b></TD>
					<TD><b>Client</b></TD>
					<TD><b>Nom</b></TD>
					<TD><b>Commande</b></TD>
					<TD><b>Quantite</b></TD>
					<TD><b>Date</b></TD>
					<TD><b>Bloquee</b></TD>
				</TR>';

	$sql = 'SELECT product.reference as reference, customer.number as client, customer.name as nom_client, custords.number as commande, custords_product.quantity, UNIX_TIMESTAMP(custords_product.date) as custords_product_date, custords_product.blocked as bloquee FROM custords_product LEFT OUTER JOIN custords ON custords_product.custords_id = custords.id LEFT OUTER JOIN product ON custords_product.product_id = product.id LEFT OUTER JOIN customer ON customer.id = custords.customer_id WHERE product.reference IN (\'99004001-07\', \'990041-20\', \'999816-20\', \'999916-20\', \'999818-20\', \'999918-20\', \'999826-20\', \'999926-20\', \'459006-20\', \'999840-20\', \'999940-20\', \'999844-20\', \'999944-20\', \'999853-20\', \'999953-20\', \'571916-20\', \'999857-20\', \'999957-20\', \'537617-20\', \'999875-20\', \'999975-20\', \'999889-20\', \'999989-20\')';
			  
	$result = mysql_select_query($sql);

	if($result) {

		while ($row = mysql_fetch_array($result, MYSQL_ASSOC)) {

			$xls_output .= '<TR =\'background-color:#ffffff\' valign=\'center\'>';
			
			$xls_output .= '<TD class="data">'.format_to_reference($row['reference']).'</TD>
							 <TD class="data">'.$row['client'].'</TD>
							 <TD class="data">'.$row['nom_client'].'</TD>
							 <TD class="data">'.$row['commande'].'</TD>
							 <TD class="number">'.format_to_number($row['quantity']).'</TD>
							 <TD class="data">'.$row['custords_product_date'].'</TD>
							 <TD class="data">'.$row['bloquee'].'</TD>
						   </TR>';
		}
	}

	$xls_output .= '</TABLE>';

	return $xls_output;
}


session_cache_limiter("must-revalidate");
header("Content-type: application/vnd.ms-excel");
header("Content-disposition: attachment; filename=commandes_".date("Ymd").".xls");

session_start();

print export_order_to_excel();

exit();

?>
