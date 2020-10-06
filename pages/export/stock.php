<?php
	include_once(dirname(__FILE__).'/../../util/sql.php');
	include_once(dirname(__FILE__).'/../../util/util.php');

function export_stock_to_excel() {

	$xls_output = '<TABLE class="data" border=\'0\' name=\'stock\' cellpadding=\'3\' cellspacing=\'1\' style=\'background-color:#d9d9d9\'>
						<TR>';

	$xls_output .= '<TD><b>Reference</b></TD>
					<TD><b>Description</b></TD>
					<TD><b>Quantite</b></TD>
					<TD><b>Entrep&ocirc;t</b></TD>
					<TD><b>Machine</b></TD>
					<TD><b>Cellule</b></TD>';
				
	if ($_SESSION['site_id'] == 100) {
		$xls_output .= '<TD><b>Site</b></TD>';
	}
	
	$xls_output .= '<TD><b>Edit_flag</b></TD>
					<TD><b>MOTD</b></TD>
					<TD><b>Multiple</b></TD>
				</TR>';

	$sql = 'SELECT product.reference, stock.quantity, warehouse.name as warehouse_name, product.name as description, machine.name as machine, site.name as site, cell.name as cell, machine.active as edit_flag, product.mtd_sales as motd, product.multiple ' .
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

			$xls_output .= '<TR style=\'background-color:#FFFFFF\' valign=\'center\'>';
			
			$edit_flag = 'FAUX';
			
			if ($row['edit_flag'] == 1) {
				$edit_flag = 'VRAI';
			}
			
			$xls_output .= '<TD class="data">'.format_to_reference($row['reference']).'</TD>
							 <TD class="data">'.$row['description'].'</TD>
							 <TD class="number">'.format_to_number($row['quantity']).'</TD>
							 <TD class="data">'.$row['warehouse_name'].'</TD>
							 <TD class="data">'.$row['machine'].'</TD>
							 <TD class="data">'.$row['cell'].'</TD>';
			
			if ($_SESSION['site_id'] == 100) {
				$xls_output .= '<TD class="data">'.$row['site'].'</TD>';
			}
			
			$xls_output .= '<TD class="data">'.$edit_flag.'</TD>
							<TD class="number">'.format_to_number($row['motd']).'</TD>
							<TD class="number">'.format_to_number($row['multiple']).'</TD></TR>';
		}
	}

	$xls_output .= '</TABLE>';
	return $xls_output;
}

session_cache_limiter("must-revalidate");
header("Content-type: application/vnd.ms-excel");
header("Content-disposition: attachment; filename=stock_".date("Ymd").".xls");

session_start();

if (!isset($_SESSION['site_id']) || ($_SESSION['site_id'] == '')) {
	header('Location: ../main.php');
	exit();
} else {
	print export_stock_to_excel();
	exit();
}
?>
