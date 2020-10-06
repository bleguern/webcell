<?php
	session_start();
	include_once(dirname(__FILE__).'/../../util/auth.php');
	include_once(dirname(__FILE__).'/../../util/sql.php');
	include_once(dirname(__FILE__).'/../../util/util.php');
	
	if (!isset($_SESSION['site_id']) || ($_SESSION['site_id'] == '')) {
		header('Location: ../main.php');
		exit();
	}

function get_stock_list() {

	$count = array();
	
	$count[0] = 0;
	$count[1] = 0;
	
	if (isset($_SESSION['site_id']) && ($_SESSION['site_id'] != '')) {

		if ($_SESSION['site_id'] == 100) {
			$columns = array(array('R&eacute;f&eacute;rence', 100, "link", "left", "", "product_information.php", 9, "_parent", false, false, 'R&eacute;f&eacute;rence produit'),
							 array('D&eacute;signation', 160, "data", "left", "", "", 0, "", false, false, 'D&eacute;signation produit'),
							 array('Site', 60, "data", "left", "", "", 0, "", false, false, 'Site de production'),
							 array('Cellule', 60, "link", "left", "", "cell_information.php", 10, "_parent", false, false, 'Cellule de production'),
							 array('Machine', 60, "link", "left", "", "machine_information.php", 11, "_parent", false, false, 'Machine de production'),
							 array('MOTD', 60, "number", "right", "", "", 0, "", false, false, 'Quantit&eacute; vendue depuis le d&eacute;but du mois'),
							 array('Multiple', 60, "number", "right", "", "", 0, "", false, false, 'Multiple de commande'),
							 array('Entrep&ocirc;t', 140, "data", "center", "", "", 0, "", false, false, 'Entrep&ocirc;t de stockage'),
							 array('Quantit&eacute;', 60, "number", "right", "bold", "", 0, "", false, false, 'Quantit&eacute; en stock'));
		} else {
			$columns = array(array('R&eacute;f&eacute;rence', 110, "link", "left", "", "product_information.php", 8, "_parent", false, false, 'R&eacute;f&eacute;rence produit'),
							 array('D&eacute;signation', 190, "data", "left", "", "", 0, "", false, false, 'D&eacute;signation produit'),
							 array('Cellule', 60, "link", "left", "", "cell_information.php", 9, "_parent", false, false, 'Cellule de production'),
							 array('Machine', 60, "link", "left", "", "machine_information.php", 10, "_parent", false, false, 'Machine de production'),
							 array('MOTD', 60, "number", "right", "", "", 0, "", false, false, 'Quantit&eacute; vendue depuis le d&eacute;but du mois'),
							 array('Multiple', 60, "number", "right", "", "", 0, "", false, false, 'Multiple de commande'),
							 array('Entrep&ocirc;t', 140, "data", "center", "", "", 0, "", false, false, 'Entrep&ocirc;t de stockage'),
							 array('Quantit&eacute;', 60, "number", "right", "bold", "", 0, "", false, false, 'Quantit&eacute; en stock'));
		}
		
		$sql_query = 'SELECT product.reference as product_reference, ' .
					 'product.name as product_description, ' .
					 'site.trigram as site_trigram, ' .
					 'cell.name as cell_name, ' .
					 'machine.name as machine_name, ' .
					 'product.mtd_sales as product_motd, ' .
					 'product.multiple as product_multiple, ' .
				     'warehouse.name as warehouse_name, ' .
					 'stock.quantity, ' .
					 'product.id as product_id, ' .
					 'cell.id as cell_id, ' .
					 'machine.id as machine_id ' .
					 'FROM stock ' .
				     'LEFT OUTER JOIN warehouse ON stock.warehouse_id = warehouse.id ' .
				     'LEFT OUTER JOIN product ON stock.product_id = product.id ' .
				     'LEFT OUTER JOIN machine ON product.machine_id = machine.id ' .
				     'LEFT OUTER JOIN cell ON machine.cell_id = cell.id ' .
				     'LEFT OUTER JOIN site ON product.site_id = site.id ' .
				     'WHERE 1 ';
			  
		if ($_SESSION['site_id'] != 100) {
			$sql_query .= 'AND product.site_id = '.mysql_format_to_number($_SESSION['site_id']).' ';
		}
			   
		$sql_query .= 'ORDER BY site_trigram, product_reference, warehouse_name';
		
		
		$result = mysql_select_query($sql_query);
		$result_table = array();
		$references = array();
		
		if($result) {
		
			$i = 0;
			
			while ($row = mysql_fetch_array($result, MYSQL_ASSOC)) {
			
				$style = '';
					 
				if ($_SESSION['site_id'] == 100) {
					$result_table[$i][0] = $style;
					$result_table[$i][1] = $row['product_reference'];
					$result_table[$i][2] = $row['product_description'];
					$result_table[$i][3] = $row['site_trigram'];
					$result_table[$i][4] = $row['cell_name'];
					$result_table[$i][5] = $row['machine_name'];
					$result_table[$i][6] = $row['product_motd'];
					$result_table[$i][7] = $row['product_multiple'];
					$result_table[$i][8] = $row['warehouse_name'];
					$result_table[$i][9] = $row['quantity'];
					$result_table[$i][10] = $row['product_id'];
					$result_table[$i][11] = $row['cell_id'];
					$result_table[$i][12] = $row['machine_id'];
				} else {
					$result_table[$i][0] = $style;
					$result_table[$i][1] = $row['product_reference'];
					$result_table[$i][2] = $row['product_description'];
					$result_table[$i][3] = $row['cell_name'];
					$result_table[$i][4] = $row['machine_name'];
					$result_table[$i][5] = $row['product_motd'];
					$result_table[$i][6] = $row['product_multiple'];
					$result_table[$i][7] = $row['warehouse_name'];
					$result_table[$i][8] = $row['quantity'];
					$result_table[$i][9] = $row['product_id'];
					$result_table[$i][10] = $row['cell_id'];
					$result_table[$i][11] = $row['machine_id'];
				}
				
				$references = add_reference($references, $row['product_reference']);
				$i++;
			}
		}
		
		$count[0] = get_table_result_with_table($result_table, $columns);
		$count[1] = count($references);
	}
	
	return $count;
}
?>
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN" "http://www.w3.org/TR/html4/loose.dtd">
<html>
<head>
	<title>ELBA - Appli Cellules - Liste des stocks</title>
	<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
	<link href="../../css/style.css" rel="stylesheet" type="text/css">
	<SCRIPT type='text/javascript' src='../../scripts/main.js'></SCRIPT>
</HEAD>
<BODY class="main_body">
<FORM NAME="resultForm" ACTION="stock_result.php" METHOD="POST">
<?php
start_loading();
$count = get_stock_list();
stop_loading();
?>
<INPUT TYPE="hidden" NAME="count" VALUE="<?php echo $count[0]; ?>">
<INPUT TYPE="hidden" NAME="count2" VALUE="<?php echo $count[1]; ?>">
</BODY>
<SCRIPT type="text/javascript">
	updateCount()
</SCRIPT>
</FORM>
</HTML>