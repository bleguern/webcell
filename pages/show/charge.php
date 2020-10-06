<?php
	session_start();
	include_once(dirname(__FILE__).'/../../util/auth.php');
	include_once(dirname(__FILE__).'/../../util/sql.php');
	include_once(dirname(__FILE__).'/../../util/util.php');
	
	if (!isset($_SESSION['site_id']) || ($_SESSION['site_id'] == '')) {
		header('Location: ../main.php');
		exit();
	}

function get_charge_list() {

	if (isset($_SESSION['site_id']) && ($_SESSION['site_id'] != '')) {
		$sql = 'SELECT cell.id as cell_id, cell.name as cell_name, machine.id as machine_id, machine.name as machine_name, product.id as product_id, product.reference as product_reference, product.name as product_name, product.machine_time as machine_time, SUM(custords_product.quantity) as order_quantity, SUM(stock.quantity) as stock_quantity ' .
			   'FROM custords_product ' .
			   'LEFT OUTER JOIN product ON custords_product.product_id = product.id ' .
			   'LEFT OUTER JOIN custords ON custords_product.custords_id = custords.id ' .
			   'LEFT OUTER JOIN machine ON product.machine_id = machine.id ' .
			   'LEFT OUTER JOIN stock ON stock.product_id = product.id ' .
			   'LEFT OUTER JOIN cell ON machine.cell_id = cell.id ' .
			   'WHERE product.site_id = '.mysql_format_to_number($_SESSION['site_id']).' ' .
			   'AND custords_product.quantity <> 0 ' .
			   'GROUP BY cell_name, machine_name, product_reference';
	
		$result = mysql_select_query($sql);
	
		if($result) {
			$count = mysql_num_rows($result);
	
			echo '<TABLE class="normal">
					  <TR class="main_title">
						  <TD>Charge totale</TD>
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
									  <th>R&eacute;f&eacute;rence</th>
									  <th>Designation</th> 
									  <th>Quantit&eacute;</th> 
									  <th>Stock</th> 
									  <th>Reste a produire</th>
									  <th>Temps (H)</th> 
									  <th>Jours</th>
									</tr>
								</thead>
								<tbody id=\'chargeTable\'>';
	
			$alternate = false;
			$j = 0;
			$cell_total = 0;
			$total = 0;
			$cell = '';
	
			while ($row = mysql_fetch_array($result, MYSQL_ASSOC)) {
				
				$to_produce = ($row['order_quantity'] - $row['stock_quantity']);
				
				if ($to_produce < 0) {
					$time = -($to_produce * $row['machine_time']);
					$day = ($time / 23);
					
					if ($j == 0) {
						$cell = $row['cell_name'];
					} else {
						if ($cell != $row['cell_name']) {
							echo '<TR class="sub_total">
									<TD colspan="7" align="right">Total '.$cell.' :</TD>
									<TD class="number">'.format_to_decimal($cell_total, 2).'</TD>
									<TD></TD>
								  </TR>';
							
							$total += $cell_total;
							$cell_total = 0;
							$cell = $row['cell_name'];
						}
					}
					
					if ($alternate) {
						echo '<TR class="alternate_row">';
					} else {
						echo '<TR class="row">';
					}
					
					echo '<TD class="data"><A HREF=\'cell_information.php?cell='.$row['cell_id'].'\' TARGET=\'_self\'>'.$row['cell_name'].'</A></TD>
						  <TD class="data"><A HREF=\'machine_information.php?machine='.$row['machine_id'].'\' TARGET=\'_self\'>'.$row['machine_name'].'</A></TD>
						  <TD><A HREF=\'product_information.php?product='.$row['product_id'].'\' TARGET=\'_self\'>'.$row['product_reference'].'</A></TD>
						  <TD>'.$row['product_name'].'</TD>
						  <TD class="number">'.format_to_number($row['order_quantity']).'</TD>
						  <TD class="number">'.format_to_number($row['stock_quantity']).'</TD>
						  <TD class="number">'.format_to_number($to_produce).'</TD>
						  <TD class="number">'.format_to_decimal($time, 2).'</TD>
						  <TD class="number">'.format_to_decimal($day, 1).'</TD>
					   </TR>';
		
					$alternate = !$alternate;
					$cell_total += $time;
					$j++;
				}
			}
			
			echo '<TR class="sub_total">
					<TD colspan="7" align="right">Total '.$cell.' :</TD>
					<TD class="number">'.format_to_decimal($cell_total, 2).'</TD>
					<TD></TD>
				  </TR>';
				
			$total += $cell_total;
			
			echo '<TR>
					<TD class="separator"></TD>
				  </TR>
				  <TR class="total">
					<TD colspan="7" align="right">TOTAL :</TD>
					<TD class="number">'.format_to_decimal($total, 2).'</TD>
					<TD></TD>
				  </TR>';
	
			echo '</TBODY>
			   </TABLE>
			  </TD>
			</TR>
		</TABLE>';
		} else {
			echo '<TABLE class="normal">
					  <TR class="main_title">
						  <TD>Charge totale</TD>
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
	<title>Web Appli Cellules - R&eacute;capitulatif de charge</title>
	<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
	<link href="../../css/style.css" rel="stylesheet" type="text/css">
	<SCRIPT type='text/javascript' src='../../scripts/table.js.php'></SCRIPT>
</HEAD>
<BODY class="main_body">
<?php

get_charge_list();

?>
<TABLE class="normal">
  <TR>
	  <TD class="max_separator"></TD>
  </TR>
  <TR class="main_bottom">
	  <TD><CENTER><A HREF="javascript:history.back()" TARGET="_self">Retour</A></TD>
  </TR>
</TABLE>
</BODY>
</HTML>