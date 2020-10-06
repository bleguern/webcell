<?php
	include_once(dirname(__FILE__).'/../../util/sql.php');
	include_once(dirname(__FILE__).'/../../util/util.php');

function export_reference_to_excel() {
	
	$xls_output = '<TABLE class="data" border=\'0\' name=\'reference"\' cellpadding=\'3\' cellspacing=\'1\' =\'background-color:#d9d9d9\'>
						<TR>
							<TD><b>Reference</b></TD>
							<TD><b>Designation</b></TD>
							<TD><b>Machine</b></TD>
							<TD><b>Cellule</b></TD>
						</TR>';

	$sql = 'SELECT site.name, product.reference as reference, product.name as name, machine.name as machine, cell.name as cell ' .
		   'FROM product ' .
		   'LEFT OUTER JOIN site ON product.site_id = site.id ' .
		   'LEFT OUTER JOIN machine ON product.machine_id = machine.id ' .
		   'LEFT OUTER JOIN cell ON machine.cell_id = cell.id ' .
		   'WHERE site.trigram = \'MLT\' ' .
		   'ORDER BY cell, machine, reference';

	echo $sql;
	$result = mysql_select_query($sql);

	if($result) {

		while ($row = mysql_fetch_array($result, MYSQL_ASSOC)) {

			$xls_output .= '<TR =\'background-color:#ffffff\' valign=\'center\'>
							 <TD class="data">'.format_to_reference($row['reference']).'</TD>
							 <TD class="data">'.$row['name'].'</TD>
							 <TD class="data">'.$row['machine'].'</TD>
							 <TD class="data">'.$row['cell'].'</TD>
						   </TR>';
		}
	}

	$xls_output .= '</TABLE>';

	return $xls_output;
}


session_cache_limiter("must-revalidate");
header("Content-type: application/vnd.ms-excel");
header("Content-disposition: attachment; filename=references_moult_".date("Ymd").".xls");

session_start();

print export_reference_to_excel();

exit();

?>
