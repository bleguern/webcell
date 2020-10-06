<?php
	session_start();
	include_once(dirname(__FILE__).'/../../util/auth.php');
	include_once(dirname(__FILE__).'/../../util/sql.php');
	include_once(dirname(__FILE__).'/../../util/util.php');
	
	if (!isset($_SESSION['site_id']) || ($_SESSION['site_id'] == '')) {
		header('Location: ../main.php');
		exit();
	}

function get_out_of_dc_list() {

	$script = '';
	
	if (isset($_SESSION['site_id']) && ($_SESSION['site_id'] != '')) {
		$sql = 'SELECT product.id, ' .
			   'product.reference, ' .
			   'product.name, ' .
			   '(SELECT SUM(quantity) FROM stock WHERE stock.product_id = product.id AND stock.warehouse_id = (SELECT id FROM warehouse WHERE trigram = \'MDG\')), ' .
			   '(SELECT SUM(quantity) FROM stock WHERE stock.product_id = product.id AND stock.warehouse_id NOT IN (SELECT DISTINCT id FROM warehouse WHERE trigram = \'MDG\' OR warehouse.trigram = \'EUR\' OR warehouse.trigram = \'EU2\')), ' .
			   'stock.quantity, ' .
			   'warehouse.trigram, ' .
			   'warehouse.name, ' .
			   'site.name, ' .
			   '(SELECT SUM(quantity) FROM custords_product WHERE custords_product.product_id = product.id AND custords_product.direct_forwarding = 0 AND custords_product.date <= DATE_ADD(NOW(), INTERVAL '.mysql_format_to_number($_SESSION['horizon']).' DAY)) ' .
			   'FROM product ' .
			   'LEFT OUTER JOIN site ON product.site_id = site.id ' .
			   'LEFT OUTER JOIN stock ON stock.product_id = product.id ' .
			   'LEFT OUTER JOIN warehouse ON stock.warehouse_id = warehouse.id ' .
			   'WHERE warehouse.trigram <> \'MDG\' AND warehouse.trigram <> \'EUR\' AND warehouse.trigram <> \'EU2\' ' .
			   'ORDER BY site.name, product.id, warehouse.id';
		
		$result = mysql_select_query($sql);
		
		if ($result) {

			$i = 0;
	
			while ($row = mysql_fetch_array($result, MYSQL_NUM)) {
	
				$product_database_data[$i][0] = $row[0];                // ID
				$product_database_data[$i][1] = $row[1];                // Product reference
				$product_database_data[$i][2] = $row[2];                // Product name
				$product_database_data[$i][3] = $row[3];                // Stock CDC
				$product_database_data[$i][4] = $row[4];                // Stock other
				$product_database_data[$i][5] = $row[5];                // Stock
				$product_database_data[$i][6] = $row[6];                // Warehouse trigram
				$product_database_data[$i][7] = $row[7];                // Warehouse name
				$product_database_data[$i][8] = $row[8];                // Site name
				$product_database_data[$i][9] = $row[9];                // Quantity
	
				$i++;
			}
		}
		
		$number = count($product_database_data);
		
		if ($i > 0) {
	
			$count = 0;
			
			$script .= '<TABLE class="normal">
					  <TR>
					  	  <TD>
							  <TABLE class="normal">
							      <TR>
								  	<TD class="main_title">Liste des ruptures CDC (#COUNT#)</TD>
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
									  <th>Site</th>
									  <th>R&eacute;f&eacute;rence</th>
									  <th>Description</th>
									  <th>Quantit&eacute; Command&eacute;e</th>
									  <th>Stock total</th>
									  <th>Stock CDC</th>
									  <th>Stock</th>
									  <th>Entrepot</th>
									</tr>
								</thead>
								<tbody id=\'outOfTable\'>';
	
			$alternate = false;
			$display = true;
			$count = 0;
			$current = null;
			
			for ($i = 0; $i < $number; $i++) {
				
				$stock_total = $product_database_data[$i][3] + $product_database_data[$i][4];
				$to_produce = $product_database_data[$i][9] - $product_database_data[$i][3];
				
				if ($to_produce < 0) {
					$to_produce = 0;
				}
				
				if ($to_produce > 0) {
				
					if ($count == 0) {
						$current = $product_database_data[$i][0];
					} else {
						if ($product_database_data[$i][0] == $current) {
							$display = false;
							$alternate = !$alternate;
						} else {
							$display = true;
							$current = $product_database_data[$i][0];
						}
					}
					
					if ($alternate) {
						$script .= '<TR class="alternate_row">';
					} else {
						$script .= '<TR class="row">';
					}
					
					if ($display) {
						$script .= '<TD class="data">'.$product_database_data[$i][8].'</TD>
								  <TD class="data"><A HREF=\'product_information.php?product='.$product_database_data[$i][0].'\' TARGET=\'_self\'>'.$product_database_data[$i][1].'</A></TD>
								  <TD class="data">'.$product_database_data[$i][2].'</TD>
								  <TD class="number">'.format_to_number($product_database_data[$i][9]).'</TD>
								  <TD class="number">'.format_to_number($stock_total).'</TD>
								  <TD class="number">'.format_to_number($product_database_data[$i][3]).'</TD>
								  <TD class="number">'.format_to_number($product_database_data[$i][5]).'</TD>
								  <TD class="data">'.$product_database_data[$i][7].'</TD>
							   </TR>';
					} else {
						$script .= '<TD colspan="6"></TD>
								  <TD class="number">'.format_to_number($product_database_data[$i][5]).'</TD>
								  <TD class="data">'.$product_database_data[$i][7].'</TD>
							   </TR>';
					}
		
					$alternate = !$alternate;
					$count++;
				}
			}
			
			$script .= '</TBODY>
			   </TABLE>
			  </TD>
			</TR>
		</TABLE>';
		
			$script = str_replace('#COUNT#', $count, $script);
		} else {
			$script .= '<TABLE class="normal">
					  <TR class="main_title">
						  <TD>Liste des ruptures CDC (0)</TD>
					  </TR>
					  <TR>
						  <TD class="separator"></TD>
					  </TR>
				  </TABLE>';
		}
	}
	
	echo $script;
}


?>
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN" "http://www.w3.org/TR/html4/loose.dtd">
<html>
<head>
	<title>Web Appli Cellules - Liste des ruptures CDC</title>
	<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
	<link href="../../css/style.css" rel="stylesheet" type="text/css">
	<SCRIPT type='text/javascript' src='../../scripts/table.js.php'></SCRIPT>
</HEAD>
<BODY class="main_body">
<?php

get_out_of_dc_list();

?>
<TABLE class="normal">
  <TR>
	  <TD class="separator"></TD>
  </TR>
  <TR>
  	<TD>
		<TABLE class="normal">
			<TR>
				<TD class="main_bottom"><A HREF="javascript:history.back()" TARGET="_self">Retour</A></TD>
			</TR>
		</TABLE>
	</TD>
  </TR>
</TABLE>
</BODY>
</HTML>