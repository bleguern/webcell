<?php
	session_start();
	include_once(dirname(__FILE__).'/../../util/util.php');
	include_once(dirname(__FILE__).'/../../util/auth.php');
	include_once(dirname(__FILE__).'/../../util/sql.php');
	
	if (!isset($_SESSION['site_id']) || ($_SESSION['site_id'] == '')) {
		header('Location: ../index.php');
		exit();
	}
	
	if (!is_allowed('admin_product')) {
		header('Location: ../not_allowed.php');
		exit();
	}
	
	if (isset($_POST['import']) || isset($_POST['import_x'])) {
		import_minimum_stock();
	}
	
function import_minimum_stock() {
	
	$fs = fopen($_FILES["stock_minimum"]["tmp_name"], 'r');
	
	$update_queries = '';
	
	if ($fs) {
		$i = 0;
		
		while (!feof($fs)) {
		
			$sql_query = '';
			
			$buffer = fgets($fs);
			$buffer = explode(';', $buffer);
			
			if (count($buffer) >= 2) {
			
				$reference = strtoupper(trim($buffer[0]));
				$minimum_stock = trim($buffer[1]);
				
				if (is_numeric($minimum_stock)) {
					$sql_query = 'UPDATE product '.
								 'SET minimum_stock = '.mysql_format_to_number($minimum_stock).' ' .
								 'WHERE UPPER(reference) = '.mysql_format_to_string($reference).' '.
								 'LIMIT 1;';
				
					$update_queries .= $sql_query;
				}
			}
		}
	}
	
	if ($update_queries != '') {
		mysql_update_queries($update_queries);
	}
}
?>
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN" "http://www.w3.org/TR/html4/loose.dtd">
<html>
<head>
	<title>ELBA - Appli Cellules - Importation des stocks minimum</title>
	<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
	<link href="../../css/style.css" rel="stylesheet" type="text/css">
</HEAD>
<BODY class="main_body">
<FORM NAME='importMinimumStockForm' ACTION='import_minimum_stock.php' METHOD='POST' enctype="multipart/form-data">
<TABLE class="normal">
	<TR>
		<TD class="main_title_text">Importation des stocks minimum</TD>
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
			<TABLE class="normal">
				<TR>
					<TD class="main_sub_section_text">- <A HREF="index.php?history=<?php echo get_php_self(); ?>" TARGET="_self">Liste des produits</A> -</TD>
					<TD class="main_sub_section_text">- <A HREF="import_machine_time.php?history=<?php echo get_php_self(); ?>" TARGET="_self">Importation des temps machine</A> -</TD>
					<TD class="main_sub_section_text">- <A HREF="import_product_comment.php?history=<?php echo get_php_self(); ?>" TARGET="_self">Importation des commentaires produit</A> -</TD>
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
		<TD class="separator"></TD>
	</TR>
	<TR>
		<TD align="center">
			<TABLE>
				<TR>
					<TD>
						<INPUT TYPE='file' NAME='stock_minimum'>
					</TD>
				</TR>
				<TR>
					<TD class="max_separator"></TD>
				</TR>
			</TABLE>
			<TABLE>
				<TR>
					<TD>
						<INPUT TYPE='submit' NAME='import' VALUE='importation' ALT='Importation des stocks minimum'>
					</TD>
				</TR>
				<TR>
					<TD class="max_separator"></TD>
				</TR>
			</TABLE>
			<TABLE>
				<TR align="center">
					<TD class="message">
<?php

if(isset($_REQUEST['message'])) {
	echo $_REQUEST['message'];
}

?>
					</TD>
				</TR>
			</TABLE>
		</TD>
	</TR>
</TABLE>
</FORM>
</BODY>
</HTML>