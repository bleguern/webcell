<?php
	include_once(dirname(__FILE__).'/../../util/sql.php');
	include_once(dirname(__FILE__).'/../../util/util.php');

function export_reference_to_excel() {
	
	$output = '<TABLE class="data" border=\'0\' cellpadding=\'3\' cellspacing=\'1\'>
				<thead>
					<tr>
					  <TH>Reference</TH>
					  <TH>Designation</TH>
					  <TH>Machine</TH>
					  <TH>Cellule</TH>
					</TR>
				</thead>
				<tbody id=\'referenceTable\'>';

	$sql = 'SELECT site.name, product.reference as reference, product.name as name, machine.name as machine, cell.name as cell ' .
		   'FROM product ' .
		   'LEFT OUTER JOIN site ON product.site_id = site.id ' .
		   'LEFT OUTER JOIN machine ON product.machine_id = machine.id ' .
		   'LEFT OUTER JOIN cell ON machine.cell_id = cell.id ' .
		   'WHERE product.active = 1 AND site.trigram = \'MLT\' ' .
		   'ORDER BY cell, machine, reference';

	$result = mysql_select_query($sql);

	if($result) {

		while ($row = mysql_fetch_array($result, MYSQL_ASSOC)) {

			$output .= '<TR =\'background-color:#ffffff\' valign=\'center\'>
							 <TD class="data">'.$row['reference'].'</TD>
							 <TD class="data">'.$row['name'].'</TD>
							 <TD class="data">'.$row['machine'].'</TD>
							 <TD class="data">'.$row['cell'].'</TD>
						   </TR>';
		}
	}

	$output .= '</TBODY></TABLE>';

	echo $output;
}

?>
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN" "http://www.w3.org/TR/html4/loose.dtd">
<html>
<head>
	<title>ELBA - Appli Cellules - Liste des r&eacute;f&eacute;rences de Moult</title>
	<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
	<link href="../../css/_1024x768.css" rel="stylesheet" type="text/css">
</HEAD>
<BODY class="main_body">
<FORM NAME='exportReference' ACTION='chantal.php' METHOD='POST'>
<TABLE class="normal">
	<TR>
		<TD class="main_title_text">References Moult</TD>
	</TR>
	<TR>
		<TD class="separator"></TD>
	</TR>
	<TR>
		<TD>
<?php

export_reference_to_excel();

?>
		</TD>
	</TR>
</TABLE>
</FORM>
</BODY>
</HTML>