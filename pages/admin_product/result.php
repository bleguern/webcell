<?php
	session_start();
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
	
	if (isset($_REQUEST['id']) && ($_REQUEST['id'] != '')) {
		modify();
	}

function modify() {
	$sql_query = 'UPDATE product ' .
				 'SET minimum_stock = '.mysql_format_to_number($_REQUEST['minimum_stock_'.$_REQUEST['id']]).', ' .
				 'machine_time = '.mysql_format_to_number($_REQUEST['machine_time_'.$_REQUEST['id']]).', ' .
				 'description = '.mysql_format_to_string($_REQUEST['description_'.$_REQUEST['id']]).' ' .
				 'WHERE id = '.mysql_format_to_number($_REQUEST['id']).' LIMIT 1';
	
	mysql_update_query($sql_query);
}

function get_product_list() {

	$sql = 'SELECT product.id, product.reference, product.name, cell.name as cell, machine.name as machine, product.minimum_stock, product.machine_time, product.description ' .
		   'FROM product ' .
		   'LEFT OUTER JOIN machine ON product.machine_id = machine.id ' .
		   'LEFT OUTER JOIN cell ON machine.cell_id = cell.id ' .
		   'WHERE product.site_id = '.mysql_format_to_number($_SESSION['site_id']).' ' .
		   'ORDER BY cell.name, machine.name, product.reference';

	$result = mysql_select_query($sql);

	if($result) {
		$count = mysql_num_rows($result);

		echo '<TABLE>
				  <TR class="main_title">
					  <TD>Liste des produits ('.$count.')</TD>
				  </TR>
				  <TR>
					  <TD class="separator"></TD>
				  </TR>
				  <TR class="link">
					  <TD><A HREF=\'import_minimum_stock.php\' TARGET=\'_self\'>Importation des stocks minimum</A> / <A HREF=\'import_machine_time.php\' TARGET=\'_self\'>des temps machine</A> / <A HREF=\'import_product_comment.php\' TARGET=\'_self\'>des commentaires produit</A></TD>
				  </TR>
				  <TR>
					  <TD height=\'10\'><INPUT NAME=\'count\' TYPE=\'hidden\' VALUE=\''.$count.'\'></TD>
				  </TR>
				  <TR>
					  <TD>
						  <TABLE class="data" border=\'0\' cellpadding=\'3\' cellspacing=\'1\'>
						  	<thead>
							    <tr>
								  <th><a href=\'\' onclick=\'this.blur(); return sortTable("productTable", 0, false);\' title=\'Reference produit\'>Reference</a></th> 
							      <th><a href=\'\' onclick=\'this.blur(); return sortTable("productTable", 1, false);\' title=\'Designation produit\'>Designation</a></th> 
							      <th><a href=\'\' onclick=\'this.blur(); return sortTable("productTable", 2, false);\' title=\'Cellule de production\'>Cellule</a></th> 
							      <th><a href=\'\' onclick=\'this.blur(); return sortTable("productTable", 3, false);\' title=\'Machine de production\'>Machine</a></th>
							      <th>Stock mini</th>
							      <th>Temps</th>
								  <th>Desc.</th>
								  <th></th>
							    </tr>
						    </thead>
							<tbody id=\'productTable\'>';

		$alternate = false;

		while ($row = mysql_fetch_array($result, MYSQL_ASSOC)) {

			if ($alternate) {
				echo '<TR class="alternate_row">';
			} else {
				echo '<TR class="row">';
			}
			
			echo '<TD>'.$row['reference'].'</TD>
				  <TD>'.$row['name'].'</TD>
				  <TD>'.$row['cell'].'</TD>
				  <TD>'.$row['machine'].'</TD>
				  <TD><INPUT TYPE=\'text\' NAME=\'minimum_stock_'.$row['id'].'\' VALUE=\''.$row['minimum_stock'].'\' =\'width:60px\' MAXLENGTH=\'20\'></TD>
				  <TD><INPUT TYPE=\'text\' NAME=\'machine_time_'.$row['id'].'\' VALUE=\''.$row['machine_time'].'\' =\'width:60px\' MAXLENGTH=\'20\'></TD>
				  <TD><INPUT TYPE=\'text\' NAME=\'description_'.$row['id'].'\' VALUE=\''.$row['description'].'\' =\'width:80px\' MAXLENGTH=\'100\'></TD>
				  <TD><A HREF=\'javascript:modify('.$row['id'].')\' TARGET=\'_self\'><img src="../../image/edit_little.jpg" ALT="Modifier"></A></TD>
			   </TR>';

			$alternate = !$alternate;
		}

		echo '</TBODY>
		   </TABLE>
		  </TD>
  		</TR>
	</TABLE>';
	} else {
		echo '<TABLE>
				  <TR class="main_title">
					  <TD>Liste des produits (0)</TD>
				  </TR>
				  <TR>
					  <TD class="separator"></TD>
				  </TR>
				  <TR class="link">
					  <TD><A HREF=\'import_minimum_stock.php\' TARGET=\'_self\'>Importation des stocks minimum</A> / <A HREF=\'import_machine_time.php\' TARGET=\'_self\'>des temps machine</A> / <A HREF=\'import_product_comment.php\' TARGET=\'_self\'>des commentaires produit</A></TD>
				  </TR>	  
				  <TR>
					  <TD height=\'10\'><INPUT NAME=\'count\' TYPE=\'hidden\' VALUE=\'0\'></TD>
				  </TR>
			  </TABLE>';
	}
}
?>
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN" "http://www.w3.org/TR/html4/loose.dtd">
<html>
<head>
	<title>ELBA - Appli Cellules - Administration des produits</title>
	<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
	<link href="../../css/style.css" rel="stylesheet" type="text/css">
	<SCRIPT TYPE='text/javascript'>
function modify(product_id) {

	productForm.id.value = product_id
	productForm.submit()
}
	</SCRIPT>
</HEAD>
<BODY class="main_body">
<FORM NAME='productForm' ACTION='index.php' METHOD='POST'>
<TABLE class="normal">
	<TR>
		<TD class="main_title_text">Commandes en retard : lignes <input type="text" name="count" value="..." class="count"> / references <input type="text" name="count2" value="..." class="count"></TD>
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
<?php

get_product_list();

?>
<INPUT NAME='id' TYPE='hidden'>
</FORM>
</BODY>
</HTML>