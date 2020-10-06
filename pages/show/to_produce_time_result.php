<?php
	session_start();
	include_once(dirname(__FILE__).'/../../util/auth.php');
	include_once(dirname(__FILE__).'/../../util/sql.php');
	include_once(dirname(__FILE__).'/../../util/util.php');
	
	if (!isset($_SESSION['site_id']) || ($_SESSION['site_id'] == '')) {
		header('Location: ../main.php');
		exit();
	}

function get_to_produce_list() {

	$count = array();
	$sub_query_site_filter = '';
	
	
	if (isset($_REQUEST["site"]) && ($_REQUEST["site"] != '')) {
		$sub_query_site_filter .= 'custords_product.site_id = '.mysql_format_to_number($_REQUEST['site']).' AND ';
	}
	else {
		if ($_SESSION['site_id'] != 100) {
			$sub_query_site_filter .= 'custords_product.site_id = '.mysql_format_to_number($_SESSION['site_id']).' AND ';
		}
	}
	
	
	
	if ($_SESSION['site_id'] == 100) {
		$columns = array(array('Site', 50, 'data', 'left', '', '', 0, '', false, true, 'Site de production'),
						 array('Cellule', 80, 'link', 'left', '', 'cell_information.php', 14, '_parent', false, true, 'Cellule de production'),
						 array('Machine', 80, 'link', 'left', '', 'machine_information.php', 15, '_parent', false, true, 'Machine de production'),
						 array('Ref.', 70, 'link', 'left', '', 'product_information.php', 16, '_parent', false, false, 'R&eacute;f&eacute;rence produit'),
						 array('D&eacute;signation', 129, 'data', 'left', '', '', 0, '', false, false, 'D&eacute;signation produit'),
						 array('Stock', 40, 'number', 'right', '', '', 0, '', false, false, 'Stock CDC'),
						 array('Cdes', 40, 'number', 'right', '', '', 0, '', false, false, 'Quantit&eacute; command&eacute;e CDC'),
						 array('Manq.', 40, 'number', 'right', 'bold', '', 0, '', false, false, 'Manque &agrave; produire CDC'),
						 array('Stock', 40, 'number', 'right', '', '', 0, '', false, false, 'Stock BU'),
						 array('Cdes', 40, 'number', 'right', '', '', 0, '', false, false, 'Quantit&eacute; command&eacute;e BU'),
						 array('Manq.', 40, 'number', 'right', 'bold', '', 0, '', false, false, 'Manque &agrave; produire BU'),
						 array('Manq.', 40, 'number', 'right', 'bold', '', 0, '', true, false, 'Manque &agrave; produire total'),
						 array('Valo.', 40, 'currency', 'right', '', '', 0, '', true, false, 'Valorisation'),
						 array('Tmps.', 40, 'time', 'right', '', '', 0, '', true, false, 'Temps de production'));
	} else {
		$columns = array(array('Cellule', 80, 'link', 'left', '', 'cell_information.php', 13, '_parent', false, true, 'Cellule de production'),
						 array('Machine', 80, 'link', 'left', '', 'machine_information.php', 14, '_parent', false, true, 'Machine de production'),
						 array('Ref.', 70, 'link', 'left', '', 'product_information.php', 15, '_parent', false, false, 'R&eacute;f&eacute;rence produit'),
						 array('D&eacute;signation', 159, 'data', 'left', '', '', 0, '', false, false, 'D&eacute;signation produit'),
						 array('Stock', 40, 'number', 'right', '', '', 0, '', false, false, 'Stock CDC'),
						 array('Cdes', 40, 'number', 'right', '', '', 0, '', false, false, 'Quantit&eacute; command&eacute;e CDC'),
						 array('Manq.', 40, 'number', 'right', 'bold', '', 0, '', false, false, 'Manque &agrave; produire CDC'),
						 array('Stock', 40, 'number', 'right', '', '', 0, '', false, false, 'Stock BU'),
						 array('Cdes', 40, 'number', 'right', '', '', 0, '', false, false, 'Quantit&eacute; command&eacute;e BU'),
						 array('Manq.', 40, 'number', 'right', 'bold', '', 0, '', false, false, 'Manque &agrave; produire BU'),
						 array('Manq.', 45, 'number', 'right', 'bold', '', 0, '', true, false, 'Manque &agrave; produire total'),
						 array('Valo.', 45, 'currency', 'right', '', '', 0, '', true, false, 'Valorisation'),
						 array('Tmps.', 45, 'time', 'right', '', '', 0, '', true, false, 'Temps de production'));
	}
	
	$sql_query = 'SELECT DISTINCT product.id, ' .
		         'product.reference, ' .
		         'product.name, ' .
		         'cell.id, ' .
	         	 'cell.name, ' .
		         'machine.id, ' .
		         'machine.name, ' .
		         '(SELECT SUM(quantity) FROM stock WHERE stock.product_id = product.id AND (stock.warehouse_id NOT IN (SELECT warehouse_id FROM site WHERE id = '.mysql_format_to_number($_SESSION['site_id']).' AND warehouse_id IS NOT NULL) OR stock.warehouse_id IS NULL)), ' .
		         '(SELECT SUM(quantity) FROM stock WHERE stock.product_id = product.id AND stock.warehouse_id IN (SELECT warehouse_id FROM site WHERE id = '.mysql_format_to_number($_SESSION['site_id']).' AND warehouse_id IS NOT NULL)), ' .
		         '(SELECT SUM(custords_product.quantity) FROM custords_product WHERE '.$sub_query_site_filter.'custords_product.direct_forwarding = 0 AND custords_product.product_id = product.id AND custords_product.date <= DATE_ADD(NOW(), INTERVAL '.mysql_format_to_number($_SESSION['horizon']).' DAY)), ' .
		         '(SELECT SUM(custords_product.quantity) FROM custords_product WHERE '.$sub_query_site_filter.'custords_product.direct_forwarding = 1 AND custords_product.product_id = product.id AND custords_product.date <= DATE_ADD(NOW(), INTERVAL '.mysql_format_to_number($_SESSION['horizon_bu']).' DAY)), ' .
		         '(SELECT SUM(custords_product.quantity) FROM custords_product WHERE '.$sub_query_site_filter.'custords_product.direct_forwarding = 0 AND custords_product.product_id = product.id AND custords_product.date <= DATE_ADD(NOW(), INTERVAL '.mysql_format_to_number($_SESSION['horizon_sub']).' DAY)), ' .
		         '(SELECT SUM(custords_product.quantity) FROM custords_product WHERE '.$sub_query_site_filter.'custords_product.direct_forwarding = 1 AND custords_product.product_id = product.id AND custords_product.date <= DATE_ADD(NOW(), INTERVAL '.mysql_format_to_number($_SESSION['horizon_sub_bu']).' DAY)), ' .
		         'product.price, ' .
		         'product.machine_time, ' .
		         'site.trigram, ' .
		         'machine.main_machine_id ' .
		         'FROM product ' .
		         'INNER JOIN custords_product ON custords_product.product_id = product.id ' .
		         'LEFT OUTER JOIN custords ON custords_product.custords_id = custords.id ' .
		         'LEFT OUTER JOIN machine ON product.machine_id = machine.id ' .
		         'LEFT OUTER JOIN cell ON machine.cell_id = cell.id ' .
		         'LEFT OUTER JOIN site ON product.site_id = site.id ' .
		         'WHERE '.$sub_query_site_filter.'1 ';
	
	if (isset($_REQUEST["site"]) && ($_REQUEST["site"] != '')) {
		$sql_query .= 'AND product.site_id = '.mysql_format_to_number($_REQUEST['site']).' ';
	}
	else {
		if ($_SESSION['site_id'] != 100) {
			$sql_query .= 'AND product.site_id = '.mysql_format_to_number($_SESSION['site_id']).' ';
		}
	}
	
	if (isset($_REQUEST["cell"]) && ($_REQUEST["cell"] != '')) {
		$sql_query .= 'AND cell.id = '.mysql_format_to_number($_REQUEST['cell']).' ';
	}
	
	if (isset($_REQUEST["machine"]) && ($_REQUEST["machine"] != '')) {
		$sql_query .= 'AND machine.id = '.mysql_format_to_number($_REQUEST['machine']).' ';
	}
		   
	$sql_query .= 'GROUP BY site.trigram, cell.name, machine.name, product.reference';
	
	
	//echo $sql_query;

	$i = 0;
	$result = mysql_select_query($sql_query);
	
	if ($result) {

		while ($row = mysql_fetch_array($result, MYSQL_NUM)) {

			$product_database_data[$i][0] = $row[0];                // ID
			$product_database_data[$i][1] = $row[1];                // Product reference
			$product_database_data[$i][2] = $row[2];                // Product name
			$product_database_data[$i][3] = $row[3];                // Cell id
			$product_database_data[$i][4] = $row[4];                // Cell name
			$product_database_data[$i][5] = $row[5];                // Machine id
			$product_database_data[$i][6] = $row[6];                // Machine name
			$product_database_data[$i][7] = $row[7];                // Stock CDC
			$product_database_data[$i][8] = $row[8];                // Stock BU
			$product_database_data[$i][9] = $row[9];                // Quantity CDC
			$product_database_data[$i][10] = $row[10];              // Quantity BU
			$product_database_data[$i][11] = $row[11];              // Quantity SUB CDC
			$product_database_data[$i][12] = $row[12];              // Quantity SUB BU
			$product_database_data[$i][13] = $row[13];              // Price
			$product_database_data[$i][14] = $row[14];              // Machine time
			$product_database_data[$i][15] = $row[15];              // Site name
			$product_database_data[$i][16] = $row[16];              // Main machine (SUB)

			$i++;
		}
	}
	
	$j = 0;
	$alternate = false;
	$result_table = array();
	
	if ($i > 0) {
	
		for ($i = 0; $i < count($product_database_data); $i++) {
				
			$to_produce_cdc = $product_database_data[$i][9] - $product_database_data[$i][7];
			$to_produce_bu = $product_database_data[$i][10] - $product_database_data[$i][8];
			$to_produce_sub_cdc = $product_database_data[$i][11] - $product_database_data[$i][7];
			$to_produce_sub_bu = $product_database_data[$i][12] - $product_database_data[$i][8];
			
			if ($to_produce_cdc < 0) {
				$to_produce_cdc = 0;
			}
			
			if ($to_produce_bu < 0) {
				$to_produce_bu = 0;
			}
			
			if ($to_produce_sub_cdc < 0) {
				$to_produce_sub_cdc = 0;
			}
			
			if ($to_produce_sub_bu < 0) {
				$to_produce_sub_bu = 0;
			}
			
			if ($product_database_data[$i][16] == '')
			{
				$quantity_cdc = $product_database_data[$i][9];
				$quantity_bu = $product_database_data[$i][10];
				$to_produce = $to_produce_cdc + $to_produce_bu;
			}
			else
			{
				$quantity_cdc = $product_database_data[$i][11];
				$quantity_bu = $product_database_data[$i][12];
				$to_produce = $to_produce_sub_cdc + $to_produce_sub_bu;
				$to_produce_cdc = $to_produce_sub_cdc;
				$to_produce_bu = $to_produce_sub_bu;
				
			}
			
			if ($to_produce > 0) {
			
				$style = '';
				
				$valo = ($to_produce * $product_database_data[$i][13]);
				$time = ($to_produce * $product_database_data[$i][14]);
				
				if ($product_database_data[$i][16] != '')
				{
					if ($alternate) {
						$style = 'sub_contract_alternate_row';
					} else {
						$style = 'sub_contract_row';
					}
				}

				if ($_SESSION['site_id'] == 100) {
					$result_table[$j][0] = $style;
					$result_table[$j][1] = $product_database_data[$i][15];
					$result_table[$j][2] = $product_database_data[$i][4];
					$result_table[$j][3] = $product_database_data[$i][6];
					$result_table[$j][4] = $product_database_data[$i][1];
					$result_table[$j][5] = $product_database_data[$i][2];
					$result_table[$j][6] = $product_database_data[$i][7];
					$result_table[$j][7] = $quantity_cdc;
					$result_table[$j][8] = $to_produce_cdc;
					$result_table[$j][9] = $product_database_data[$i][8];
					$result_table[$j][10] = $quantity_bu;
					$result_table[$j][11] = $to_produce_bu;
					$result_table[$j][12] = $to_produce;
					$result_table[$j][13] = $valo;
					$result_table[$j][14] = $time;
					$result_table[$j][15] = $product_database_data[$i][3];
					$result_table[$j][16] = $product_database_data[$i][5];
					$result_table[$j][17] = $product_database_data[$i][0];
				} else {
					$result_table[$j][0] = $style;
					$result_table[$j][1] = $product_database_data[$i][4];
					$result_table[$j][2] = $product_database_data[$i][6];
					$result_table[$j][3] = $product_database_data[$i][1];
					$result_table[$j][4] = $product_database_data[$i][2];
					$result_table[$j][5] = $product_database_data[$i][7];
					$result_table[$j][6] = $quantity_cdc;
					$result_table[$j][7] = $to_produce_cdc;
					$result_table[$j][8] = $product_database_data[$i][8];
					$result_table[$j][9] = $quantity_bu;
					$result_table[$j][10] = $to_produce_bu;
					$result_table[$j][11] = $to_produce;
					$result_table[$j][12] = $valo;
					$result_table[$j][13] = $time;
					$result_table[$j][14] = $product_database_data[$i][3];
					$result_table[$j][15] = $product_database_data[$i][5];
					$result_table[$j][16] = $product_database_data[$i][0];
				}
	
				$alternate = !$alternate;
				$j++;
			}
		}
	}
	
	$count = get_table_result_with_table_with_total($result_table, $columns);
	
	return $count;
}
?>
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN" "http://www.w3.org/TR/html4/loose.dtd">
<html>
<head>
	<title>ELBA - Appli Cellules - Liste des manques &agrave; produire</title>
	<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
	<link href="../../css/style.css" rel="stylesheet" type="text/css">
	<SCRIPT type='text/javascript' src='../../scripts/main.js'></SCRIPT>
</HEAD>
<BODY class="main_body">
<FORM NAME="resultForm" ACTION="to_produce_time_result.php" METHOD="POST">
<?php
start_loading();
$count = get_to_produce_list();
stop_loading();
?>
<INPUT TYPE="hidden" NAME="count" VALUE="<?php echo $count; ?>">
</BODY>
<SCRIPT type="text/javascript">
	updateCount()
</SCRIPT>
</FORM>
</HTML>