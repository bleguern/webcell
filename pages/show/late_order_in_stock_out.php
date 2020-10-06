<?php
	session_start();
	include_once(dirname(__FILE__).'/../../util/auth.php');
	include_once(dirname(__FILE__).'/../../util/sql.php');
	include_once(dirname(__FILE__).'/../../util/util.php');
	
	if (!isset($_SESSION['site_id']) || ($_SESSION['site_id'] == '')) {
		header('Location: ../main.php');
		exit();
	}

function get_late_order_list() {

	if (isset($_SESSION['site_id']) && ($_SESSION['site_id'] != '')) {
		$sql = 'SELECT product.id as product_id, (SELECT SUM(quantity) FROM stock WHERE stock.product_id = product.id) as stock, (SELECT SUM(quantity) FROM custords_product WHERE custords_product.product_id = product.id) as quantity ' .
			   'FROM custords_product ' .
			   'LEFT OUTER JOIN product ON custords_product.product_id = product.id ' .
			   'WHERE product.site_id = '.mysql_format_to_number($_SESSION['site_id']).' ' .
			   'AND custords_product.date < NOW() ' .
			   'AND custords_product.quantity <> 0 ' .
			   'ORDER BY product_id';
	
		$result = mysql_select_query($sql);
		
		$product_in_out = null;
		
		if ($result) {

			$i = 0;
	
			while ($row = mysql_fetch_array($result, MYSQL_NUM)) {
	
				$product_in_out[$i][0] = $row[0];                // Product id
				$product_in_out[$i][1] = $row[1];                // Stock
				$product_in_out[$i][2] = $row[2];                // Quantity
	
				$i++;
			}
		}
		
		$sql = 'SELECT cell.id as cell_id, cell.name as cell_name, machine.id as machine_id, machine.name as machine_name, product.id as product_id, product.reference as product_reference, product.name as product_description, (SELECT SUM(quantity) FROM stock WHERE stock.product_id = product.id) as stock, custords_product.quantity, customer.id as customer_id, customer.number as customer_number, customer.name as customer_name, customer.priority, DATE_FORMAT(custords_product.date, \'%d/%m/%Y\') as custords_product_date, custords_product.direct_forwarding, custords.id as custords_id, custords.number as custords_number, (product.price * custords_product.quantity) as valo, custords_product.blocked ' .
			   'FROM custords_product ' .
			   'LEFT OUTER JOIN product ON custords_product.product_id = product.id ' .
			   'LEFT OUTER JOIN custords ON custords_product.custords_id = custords.id ' .
			   'LEFT OUTER JOIN customer ON custords.customer_id = customer.id ' .
			   'LEFT OUTER JOIN machine ON product.machine_id = machine.id ' .
			   'LEFT OUTER JOIN cell ON machine.cell_id = cell.id ' .
			   'WHERE product.site_id = '.mysql_format_to_number($_SESSION['site_id']).' ' .
			   'AND custords_product.date < NOW() ' .
			   'AND custords_product.quantity <> 0 ' .
			   'ORDER BY cell_name, machine_name, custords_product.date, product_reference';
		
		$result = mysql_select_query($sql);
	
		if($result) {
			$count = mysql_num_rows($result);
	
			echo '<TABLE class="data">
					  <TR>
					  	  <TD>
							  <TABLE class="data">
							      <TR>
								  	<TD class="main_title">Liste des commandes en retard ('.$count.')</TD>
									<TD class="main_bottom"><A HREF="late_order_without_blocked.php" TARGET="_self">Afficher les commandes non bloqu&eacute;es</A></TD>
								  	<TD class="main_bottom"><A HREF="javascript:history.back()" TARGET="_self">Retour</A></TD>
								  </TR>
							  </TABLE>
						  </TD>
					  </TR>
					  <TR>
						  <TD class="separator"></TD>
					  </TR>
					  <TR>
						  <TD>
							  <TABLE class="data" border=\'0\' cellpadding=\'3\' cellspacing=\'1\'>
								<thead>
									<tr>
									  <th>Cellule</th>
									  <th>Machine</th> 
									  <th>Ref.</th>
									  <th>Desc.</th>
									  <th>Qte</th> 
									  <th>Client</th>
									  <th>Nom</th>
									  <th>Date</th>
									  <th>XP dir.</th>
									  <th>Cde.</th>
									  <th>Valo.</th>
									</tr>
								</thead>
								<tbody id=\'lateOrderTable\'>';
	
			$alternate = false;
			$i = 0;
			$cell_total = 0;
			$total = 0;
			$cell = '';
	
			while ($row = mysql_fetch_array($result, MYSQL_ASSOC)) {
	
				if ($i == 0) {
					$cell = $row['cell_name'];
				 } else {
					if ($cell != $row['cell_name']) {
						echo '<TR class="sub_total">
								<TD colspan="10" align="right">Total '.$cell.' :</TD>
								<TD class="number">'.format_to_currency($cell_total).'</TD>
							  </TR>';
						
						$total += $cell_total;
						$cell_total = 0;
						$cell = $row['cell_name'];
						$alternate = false;
					}
				}
				
				$is_out = false;
				
				if (isset($product_in_out)) {
					for ($i = 0; $i < count($product_in_out); $i++) {
						
						if ($product_in_out[$i][0] == $row['product_id']) {
							if ($product_in_out[$i][1] < $product_in_out[$i][2]) {
								$is_out = true;
							}
						}
					}
				}
				
				if ($row['blocked'] == 1) {
					echo '<TR class=\'blockedRow\'>';
				} else {
					if ($is_out) {
						echo '<TR class=\'outRow\'>';
					} else {
						if ($alternate) {
							echo '<TR class=\'alternateRow\'>';
						} else {
							echo '<TR>';
						}
					}
				}
				
				$direct_forwarding = 'Non';
				
				if ($row['direct_forwarding'] == 1) {
					$direct_forwarding = 'Oui';
				}
				
				
				echo '<TD class="data"><A HREF=\'cell_information.php?cell='.$row['cell_id'].'\' TARGET=\'_self\'>'.$row['cell_name'].'</A></TD>
					  <TD class="data"><A HREF=\'machine_information.php?machine='.$row['machine_id'].'\' TARGET=\'_self\'>'.$row['machine_name'].'</A></TD>
					  <TD><A HREF=\'product_information.php?product='.$row['product_id'].'\' TARGET=\'_self\'>'.$row['product_reference'].'</A></TD>
					  <TD>'.$row['product_description'].'</TD>
					  <TD class="number"><B>'.format_to_number($row['quantity']).'</B></TD>
					  <TD><A HREF=\'customer_information.php?customer='.$row['customer_id'].'\' TARGET=\'_self\'>'.$row['customer_number'].'</A></TD>
					  <TD>'.$row['customer_name'].'</TD>
					  <TD class="date">'.$row['custords_product_date'].'</TD>
					  <TD align="center">'.$direct_forwarding.'</TD>
					  <TD><A HREF=\'custords_information.php?custords='.$row['custords_id'].'\' TARGET=\'_self\'>'.$row['custords_number'].'</A></TD>
					  <TD class="number">'.format_to_currency($row['valo']).'</TD>
				   </TR>';
	
				$alternate = !$alternate;
				$i++;
				$cell_total += $row['valo'];
			}
			
			echo '<TR class="sub_total">
					<TD colspan="10" align="right">Total '.$cell.' :</TD>
					<TD class="number">'.format_to_currency($cell_total).'</TD>
				  </TR>';
				
			$total += $cell_total;
			
			echo '<TR>
					<TD class="separator"></TD>
				  </TR>
				  <TR class="total">
				  	<TD colspan="10" align="right">TOTAL :</TD>
					<TD class="number">'.format_to_currency($total).'</TD>
				  </TR>';
	
			echo '</TBODY>
			   </TABLE>
			  </TD>
			</TR>
		</TABLE>';
		} else {
			echo '<TABLE class="data">
					  <TR>
					  	  <TD>
							  <TABLE class="data">
							      <TR>
								  	<TD class="main_title">Liste des commandes en retard ('.$count.')</TD>
									<TD class="main_bottom"><A HREF="late_order_without_blocked.php" TARGET="_self">Afficher les commandes non bloqu&eacute;es</A></TD>
								  	<TD class="main_bottom"><A HREF="javascript:history.back()" TARGET="_self">Retour</A></TD>
								  </TR>
							  </TABLE>
						  </TD>
					  </TR>
					  <TR>
						  <TD class="separator"></TD>
					  </TR>
				  </TABLE>';
		}
	}
}


?>
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN" "http://www.w3.org/TR/html4/loose.dtd">
<html>
<head>
	<title>Web Appli Cellules - Liste des commandes en retard</title>
	<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
	<link href="../../css/style.css" rel="stylesheet" type="text/css">
	<SCRIPT type='text/javascript' src='../../scripts/table.js.php'></SCRIPT>
</HEAD>
<BODY class="main_body">
<?php

get_late_order_list();

?>
<TABLE class="data">
  <TR>
	  <TD class="max_separator"></TD>
  </TR>
  <TR class="main_bottom">
	  <TD><CENTER><A HREF="javascript:history.back()" TARGET="_self">Retour</A></TD>
  </TR>
</TABLE>
</BODY>
</HTML>