<?php
	session_start();
	include_once(dirname(__FILE__).'/../../util/util.php');
	include_once(dirname(__FILE__).'/../../util/auth.php');
	include_once(dirname(__FILE__).'/../../util/sql.php');
	
	if (!is_allowed('admin_machine')) {
		header('Location: ../not_allowed.php');
		exit();
	}

	if ($_REQUEST['id'] == '') {
		header('Location: index.php');
		exit();
	}
	
	if (isset($_POST['update']) || isset($_POST['update_x'])) {
		update_machine();
	} else if (isset($_POST['add_product']) || isset($_POST['add_product_x'])) {
		add_product();
	}

function get_product_list() {

	$sql = 'SELECT id, reference, name, description FROM product WHERE machine_id = '.mysql_format_to_number($_REQUEST['id']).' ORDER BY reference';

	$result = mysql_select_query($sql);

	if($result) {
		$count = mysql_num_rows($result);

		echo '<TR>
				<TD class="min_separator"></TD>
			  </TR>
		      <tr>
				<td class="main_title"><img src="../../image/menu/separator.jpg" border="0"></td>
			  </tr>
			  <TR>
				<TD class="min_separator"></TD>
		      </TR>
			  <TR>
				 <TD class="main_bottom_text">Liste des produits ('.$count.')</TD>
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
				  <TD>
					<TABLE class="normal">
						<TR>
							<TD class="header">Reference</TD>
							<TD class="header">Nom</TD>
							<TD class="header">Description</TD>
							<TD class="header"></TD>
						</TR>';

		$alternate = false;

		while ($row = mysql_fetch_array($result, MYSQL_ASSOC)) {
			if ($alternate) {
				echo '<TR class="alternate_row">';
			} else {
				echo '<TR class="row">';
			}
			
			echo '<TD>'.$row['reference'].'</TD>
				  <TD>'.$row['name'].'</TD>
			 	  <TD>'.$row['description'].'</TD>
			 	  <TD align="center"><A HREF=\'_remove_product.php?id='.$row['id'].'&machine_id='.$_REQUEST['id'].'\' TARGET=\'_self\'><img src="../../image/delete_little.jpg" ALT="Retirer"></A></TD>
		 	   </TR>';

			$alternate = !$alternate;
		}

		echo '</TABLE>
		  </TD>
  		</TR>';
	} else {
		echo '<TR>
				<TD class="min_separator"></TD>
			  </TR>
		      <tr>
				<td class="main_title"><img src="../../image/menu/separator.jpg" border="0"></td>
			  </tr>
			  <TR>
				<TD class="min_separator"></TD>
		      </TR>
			  <TR>
				 <TD class="main_bottom_text">Liste des produits (0)</TD>
			  </TR>
			  <TR>
				<TD class="min_separator"></TD>
			  </TR>
		      <tr>
				<td class="main_title"><img src="../../image/menu/separator.jpg" border="0"></td>
			  </tr>
			  <TR>
				<TD class="min_separator"></TD>
		      </TR>';
	}
}

function get_unmapped_product_list() {
	$sql = 'SELECT id, CONCAT(reference, \' : \', name) as name FROM product WHERE machine_id IS NULL ORDER BY name';
	mysql_print_select_option($sql);
}

function update_machine() {
	if (($_REQUEST['site'] == '') ||
		($_REQUEST['cell'] == '') ||
		($_REQUEST['active'] == '') ||
		($_REQUEST['name'] == '')) {
		$_REQUEST['message'] = 'Attention : des champs sont manquants!';
	} else {

		$sql_query = 'UPDATE machine ' .
					 'SET site_id = '.mysql_format_to_number($_REQUEST['site']).', ' .
					 'cell_id = '.mysql_format_to_number($_REQUEST['cell']).', ' .
					 'main_machine_id = '.mysql_format_to_number($_REQUEST['main_machine']).', ' .
					 'active = '.mysql_format_to_number($_REQUEST['active']).', ' .
					 'name = '.mysql_format_to_string($_REQUEST['name']).', ' .
					 'description = '.mysql_format_to_string($_REQUEST['description']).' ' .
					 'WHERE id = '.mysql_format_to_number($_REQUEST['id']).' LIMIT 1';

		if (mysql_update_query($sql_query)) {
			mysql_save_log('UPDATE_MACHINE', 'ID : '.$_REQUEST['id']);
			header('Location: index.php');
		} else {
			mysql_save_sql_error(get_php_self(), 'Modifier une machine', $sql_query, mysql_errno(), mysql_error());
			$_REQUEST['message'] = 'Erreur : machine non modifi&eacute;e!';
		}
	}
}

function add_product() {

	if ($_REQUEST['product'] == '') {
		$_REQUEST['message'] = 'Attention : le champ suivant est manquant : produit!';
	} else {
		$sql_query = 'UPDATE product SET machine_id = '.mysql_format_to_number($_REQUEST['id']).' WHERE id = '.mysql_format_to_number($_REQUEST['product']).' LIMIT 1';

		if (!mysql_update_query($sql_query)) {
			mysql_save_sql_error(get_php_self(), 'Ajout de produit a une machine', $sql_query, mysql_errno(), mysql_error());
			$_REQUEST['message'] = 'Erreur : produit non ajout&eacute;!';
		}
	}
}

	$sql_query = 'SELECT machine.site_id as site_id, site.name as site_name, machine.cell_id as cell_id, machine.active, machine.name, machine.description, machine.main_machine_id as main_machine_id ' .
				 'FROM machine ' .
				 'LEFT OUTER JOIN site ON machine.site_id = site.id ' .
				 'WHERE machine.id = '.mysql_format_to_number($_REQUEST['id']).' LIMIT 1';

	$result = mysql_select_query($sql_query);

	if($result) {
		$machine = mysql_fetch_array($result, MYSQL_ASSOC);
	} else {
		mysql_save_sql_error(get_php_self(), 'Chargement de la page modifier une machine', $sql_query, mysql_errno(), mysql_error());
		$_REQUEST['message'] = 'Erreur : chargement de la page &eacute;chou&eacute;!';
	}
?>
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN" "http://www.w3.org/TR/html4/loose.dtd">
<html>
<head>
	<title>ELBA - Appli Cellules - Modifier une machine</title>
	<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
	<link href="../../css/style.css" rel="stylesheet" type="text/css">
	<SCRIPT type='text/javascript' src='../../scripts/main.js'></SCRIPT>
	<?php mysql_build_javascript_table('SELECT id, name FROM site ORDER BY name;
SELECT cell.id, cell.name, site.id FROM cell LEFT OUTER JOIN site ON cell.site_id = site.id ORDER BY cell.name;
SELECT id, name FROM machine WHERE main_machine_id IS NULL AND active = 1 ORDER BY name',
'site;
cell;
mainMachine'); ?>
</HEAD>
<BODY class="main_body">
<FORM NAME='updateMachineForm' ACTION='update.php' METHOD='POST'>
<TABLE class="normal">
	<TR>
		<TD class="main_title_text">Modifier une machine</TD>
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
					<TD class="main_sub_section_text">- <A HREF="index.php?history=<?php echo get_php_self(); ?>" TARGET="_self">Liste des machines</A> -</TD>
					<TD class="main_sub_section_text">- <A HREF="add.php?history=<?php echo get_php_self(); ?>" TARGET="_self">Ajouter une machine</A> -</TD>
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
		<TD>
			<TABLE>
				<TR>
					<TD>site * :</TD>
					<TD width="field_separator"><INPUT TYPE="hidden" NAME="site" VALUE="<?php echo $machine['site_id']; ?>"></TD>
					<TD class="data">
<?php

if (isset($_SESSION['role_id']) && $_SESSION['role_id'] == 0) {

?>
<SELECT NAME='site_select' onChange='javascript:updateMachineFormOnSiteChange()'></SELECT>
<?php

} else {

	echo $machine['site_name']; 

}

?>
</TD>
				</TR>
				<TR>
					<TD class="separator"></TD>
				</TR>
				<TR>
					<TD>cellule * :</TD>
					<TD class="field_separator"></TD>
					<TD>
						<SELECT NAME='cell_select' onChange='javascript:updateMachineFormOnCellChange()'></SELECT>
					</TD>
				</TR>
				<TR>
					<TD class="max_separator"></TD>
				</TR>
				<TR>
					<TD>nom * :</TD>
					<TD class="field_separator"></TD>
					<TD>
						<INPUT TYPE='text' NAME='name' VALUE='<?php echo $machine['name']; ?>' STYLE='width:150px' MAXLENGTH='20'>
					</TD>
				</TR>
				<TR>
					<TD class="separator"></TD>
				</TR>
				<TR>
					<TD>active * :</TD>
					<TD class="field_separator"></TD>
					<TD>
						<SELECT NAME='active'>
<?php

if ($machine['active'] == '1') {
	echo '<OPTION VALUE=\'1\' SELECTED>Oui</OPTION>' .
			'<OPTION VALUE=\'0\'>Non</OPTION>';
} else {
	echo '<OPTION VALUE=\'1\'>Oui</OPTION>' .
			'<OPTION VALUE=\'0\' SELECTED>Non</OPTION>';
}

?>
						</SELECT>
					</TD>
				</TR>
				<TR>
					<TD class="separator"></TD>
				</TR>
				<TR>
					<TD>description :</TD>
					<TD class="field_separator"></TD>
					<TD>
						<INPUT TYPE='text' NAME='description' VALUE='<?php echo $machine['description']; ?>' STYLE='width:250px' MAXLENGTH='200'>
					</TD>
				</TR>
				<TR>
					<TD class="max_separator"></TD>
				</TR>
				<TR>
					<TD>machine principale :</TD>
					<TD class="field_separator"></TD>
					<TD>
						<SELECT NAME='main_machine_select' onChange='javascript:updateMachineFormOnMainMachineChange()'></SELECT>
					</TD>
				</TR>
				<TR>
					<TD class="max_separator"></TD>
				</TR>
			</TABLE>
			<TABLE class="normal">
				<TR align="center">
					<TD>
						<INPUT NAME='id' TYPE='hidden' VALUE='<?php echo $_REQUEST['id']; ?>'>
						<INPUT NAME='cell' TYPE='hidden' VALUE='<?php echo $machine['cell_id']; ?>'>
						<INPUT NAME='main_machine' TYPE='hidden' VALUE='<?php echo $machine['main_machine_id']; ?>'>
						<INPUT TYPE='submit' NAME='update' VALUE='modifier' ALT='Modifier une cellule'>
					</TD>
				</TR>
			</TABLE>
			<TABLE class="normal">
				<TR>
					<TD class="max_separator"></TD>
				</TR>
<?php

get_product_list();

?>
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
					<TD class="main_bottom_text">Ajouter un produit</TD>
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
			</TABLE>
			<TABLE>
				<TR>
					<TD>produit * :</TD>
					<TD class="field_separator"></TD>
					<TD>
						<SELECT NAME='product_select' onChange='javascript:updateMachineFormOnProductChange()'>
<?php

get_unmapped_product_list();

?>
						</SELECT>
					</TD>
					<TD class="field_separator"></TD>
					<TD>
						<INPUT NAME='product' TYPE='hidden'>
						<INPUT TYPE='submit' NAME='add_product' VALUE='ajouter' ALT='Ajouter un produit'>
					</TD>
				</TR>
				<TR>
					<TD class="max_separator"></TD>
				</TR>
			</TABLE>
			<TABLE>
				<TR>
					<TD>* : champs obligatoires</TD>
				</TR>
				<TR>
					<TD class="separator"></TD>
				</TR>
			</TABLE>
			<TABLE class="normal">
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
<SCRIPT type="text/javascript">
	updateMachineFormFill()
</SCRIPT>
</FORM>
</BODY>
</HTML>