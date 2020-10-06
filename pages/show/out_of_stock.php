<?php
	session_start();
	include_once(dirname(__FILE__).'/../../util/auth.php');
	include_once(dirname(__FILE__).'/../../util/sql.php');
	include_once(dirname(__FILE__).'/../../util/util.php');
	
	if (!isset($_SESSION['site_id']) || ($_SESSION['site_id'] == '')) {
		header('Location: ../main.php');
		exit();
	}
	
	if (!isset($_REQUEST['history']) || ($_REQUEST['history'] == '')) {
		$_REQUEST['history'] = '/webcell/pages/main.php';
	}
	
	if (isset($_REQUEST['warehouse']) && ($_REQUEST['warehouse'] != '')) {
		$_REQUEST['warehouse_name'] = mysql_simple_select_query('SELECT name FROM warehouse WHERE id = '.mysql_format_to_number($_REQUEST['warehouse']));
	}

?>
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN" "http://www.w3.org/TR/html4/loose.dtd">
<html>
<head>
	<title>ELBA - Appli Cellules - Transferts inter-d&eacute;p&ocirc;t</title>
	<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
	<link href="../../css/style.css" rel="stylesheet" type="text/css">
	<SCRIPT type='text/javascript' src='../../scripts/main.js'></SCRIPT>
	<?php mysql_build_javascript_table('SELECT warehouse.id, CONCAT(warehouse.trigram, \' - \', warehouse.name) ' .
			 'FROM warehouse ' .
			 'ORDER BY warehouse.name',
			 'warehouse'); ?>
</HEAD>
<BODY class="main_body">
<FORM NAME='outOfStockForm' ACTION='out_of_stock.php' METHOD='POST'>
<TABLE class="normal">
	<TR>
		<TD class="main_title_text">Transferts inter-d&eacute;p&ocirc;t<?php 

if (isset($_REQUEST['warehouse_name']) && $_REQUEST['warehouse_name'] != '') {
	echo ' : '.$_REQUEST['warehouse_name'].' <input type="text" name="count" value="..." class="count">';
}

?></TD>
	</TR>
	<tr>
		<td class="main_title"><img src="../../image/menu/separator.jpg" border="0"></td>
	</tr>
	<TR>
		<TD class="min_separator"></TD>
	</TR>
	<TR>
		<TD align="center">
			<TABLE>
				<TR>
					<TD class="main_sub_section_text">- <A HREF='out_of_stock2.php' TARGET='_self'>Nouvelle version</A> -</TD>
				</TR>
			</TABLE>
		</TD>
	</TR>
	<TR>
		<TD class="min_separator"></TD>
	</TR>
	<tr>
		<td class="main_title"><img src="../../image/menu/separator.jpg" border="0"></td>
	</tr>
	<TR>
		<TD class="min_separator"></TD>
	</TR>
	<TR>
		<TD align="center">
			<TABLE class="center">
				<TR>
					<TD class="field_headling">Entrep&ocirc;t :</TD>
					<TD class="field_separator"></TD>
					<TD class="field_value">
						<INPUT NAME='warehouse' TYPE='hidden' VALUE='<?php if (isset($_REQUEST['warehouse'])) { echo $_REQUEST['warehouse']; } ?>'>
						<INPUT NAME='warehouse_name' TYPE='hidden' VALUE='<?php if (isset($_REQUEST['warehouse_name'])) { echo $_REQUEST['warehouse_name']; } ?>'>
						<SELECT NAME='warehouse_select' onChange='javascript:outOfStockFormOnWarehouseChange()'></SELECT>
					</TD>
				</TR>
			</TABLE>
		</TD>
	<TR>
		<TD class="min_separator"></TD>
	</TR>
	<tr>
		<td class="main_title"><img src="../../image/menu/separator.jpg" border="0"></td>
	</tr>
	<TR>
		<TD class="min_separator"></TD>
	</TR>
</TABLE>
<?php

if ((isset($_REQUEST['warehouse']) && $_REQUEST['warehouse'] != '') && 
	(isset($_REQUEST['warehouse_name']) && $_REQUEST['warehouse_name'] != '')){

?>
<TABLE class="normal">
	<TR>
		<TD>
			<TABLE>
				<TR>
					<TD><?php

$columns = array(array('Site', 50, 'data', 'center', '', '', 0, '', false, false, 'Site de production'),
				 array('R&eacute;f&eacute;rence', 90, 'link', 'left', '', 'product_information.php', 8, '_parent', false, false, 'R&eacute;f&eacute;rence produit'),
				 array('D&eacute;signation', 160, 'data', 'left', '', '', 0, '', false, false, 'D&eacute;signation produit'),
				 array('Qt&eacute;. Cd&eacute;e.', 70, 'number', 'right', '', '', 0, '', false, false, 'Quantit&eacute; Command&eacute;e'),
				 array('Stock '.$_REQUEST['warehouse_name'], 160, 'number', 'right', '', '', 0, '', false, false, 'Quantit&eacute; en stock sur '.$_REQUEST['warehouse_name']),
				 array('Stock total', 70, 'number', 'right', '', '', 0, '', false, false, 'Quantit&eacute; en stock total'),
				 array('Stock', 70, 'number', 'right', '', '', 0, '', false, false, 'Quantit&eacute; en stock'),
				 array('Entrep&ocirc;t', 100, 'data', 'center', '', '', 0, '', false, false, 'Entrep&ocirc;t de stockage'));
	
get_table_head($columns);

						  ?></TD>
			</TR>
</TABLE>
<TABLE class="normal">
	<TR>
		<TD><iframe src="out_of_stock_result.php<?php 

echo '?history='.get_php_self().'&warehouse='.$_REQUEST['warehouse'].'&warehouse_name='.$_REQUEST['warehouse_name'];

if (isset($_REQUEST['order']))
{
	echo '&order='.$_REQUEST['order'];
	
	if (isset($_REQUEST['sort']))
	{
		echo '&sort='.$_REQUEST['sort'];
	}
}
?>" width="790" height="300" name="result" frameborder="0" marginwidth="0" marginheight="0"></iframe></TD>
	</TR>
</TABLE>

<?php

}

?>
<TABLE class="normal">
  	<TR>
		<TD class="min_separator"></TD>
	</TR>
	<tr>
		<td class="main_bottom"><img src="../../image/menu/menu_separator.jpg" border="0"></td>
	</tr>
	<TR>
		<TD class="min_separator"></TD>
	</TR>
	<TR>
		<TD class="main_bottom_text"><A HREF="<?php echo $_REQUEST['history']; ?>" target="_self">Retour</A></TD>
	</TR>
	<TR>
		<TD class="min_separator"></TD>
	</TR>
	<tr>
		<td class="main_bottom"><img src="../../image/menu/menu_separator.jpg" border="0"></td>
	</tr>
</TABLE>
<SCRIPT type="text/javascript">
	outOfStockFormFill()
</SCRIPT>
</FORM>
</BODY>
</HTML>