<?php
	session_start();
	include_once(dirname(__FILE__).'/../../util/util.php');
	include_once(dirname(__FILE__).'/../../util/auth.php');
	include_once(dirname(__FILE__).'/../../util/sql.php');
	
	if (!is_allowed('admin_cell')) {
		header('Location: ../not_allowed.php');
		exit();
	}

	if ($_REQUEST['id'] == '') {
		header('Location: index.php');
		exit();
	}
	
	if (isset($_POST['update']) || isset($_POST['update_x'])) {
		update_cell();
	} else if (isset($_POST['add_machine']) || isset($_POST['add_machine_x'])) {
		add_machine();
	}

function get_machine_list() {

	$sql = 'SELECT id, active, name, description FROM machine WHERE cell_id = '.mysql_format_to_number($_REQUEST['id']).' ORDER BY name';

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
				 <TD class="main_bottom_text">Liste des machines ('.$count.')</TD>
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
							<TD class="header">Nom</TD>
							<TD class="header">Active</TD>
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
			
			$active = 'Non';
			
			if ($row['active'] == 1) {
				$active = 'Oui';
			}

			echo '<TD>'.$row['name'].'</TD>
				  <TD>'.$active.'</TD>
			 	  <TD>'.$row['description'].'</TD>
			 	  <TD align="center"><A HREF=\'_remove_machine.php?id='.$row['id'].'&cell_id='.$_REQUEST['id'].'\' TARGET=\'_self\'><img src="../../image/delete_little.jpg" ALT="Retirer"></A></TD>
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
				 <TD class="main_bottom_text">Liste des machines (0)</TD>
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

function get_unmapped_machine_list() {
	$sql = 'SELECT id, name FROM machine WHERE cell_id IS NULL ORDER BY name';
	mysql_print_select_option($sql);
}

function update_cell() {
	if (($_REQUEST['site'] == '') ||
		($_REQUEST['name'] == '')) {
		$_REQUEST['message'] = 'Attention : des champs sont manquants!';
	} else {

		$sql_query = 'UPDATE cell ' .
					 'SET site_id = '.mysql_format_to_number($_REQUEST['site']).', ' .
					 'name = '.mysql_format_to_string($_REQUEST['name']).', ' .
					 'description = '.mysql_format_to_string($_REQUEST['description']).', ' .
					 'effectiveness = '.mysql_format_to_number($_REQUEST['effectiveness']).' ' .
					 'WHERE id = '.mysql_format_to_number($_REQUEST['id']).' LIMIT 1';

		if (mysql_update_query($sql_query)) {
			mysql_save_log('UPDATE_CELL', 'ID : '.$_REQUEST['id']);
			header('Location: index.php');
		} else {
			mysql_save_sql_error(get_php_self(), 'Modifier une cellule', $sql_query, mysql_errno(), mysql_error());
			$_REQUEST['message'] = 'Erreur : cellule non modifi&eacute;e!';
		}
	}
}

function add_machine() {

	if ($_REQUEST['machine'] == '') {
		$_REQUEST['message'] = 'Attention : le champ suivant est manquant : machine!';
	} else {
		$sql_query = 'UPDATE machine SET cell_id = '.mysql_format_to_number($_REQUEST['id']).' WHERE id = '.mysql_format_to_number($_REQUEST['machine']).' LIMIT 1';

		if (!mysql_update_query($sql_query)) {
			mysql_save_sql_error(get_php_self(), 'Ajout de machine a une cellule', $sql_query, mysql_errno(), mysql_error());
			$_REQUEST['message'] = 'Erreur : machine non ajout&eacute;e!';
		}
	}
}

	$sql_query = 'SELECT cell.site_id as site_id, site.name as site_name, cell.name, cell.description, cell.effectiveness ' .
				 'FROM cell ' .
				 'LEFT OUTER JOIN site ON cell.site_id = site.id ' .
				 'WHERE cell.id = '.mysql_format_to_number($_REQUEST['id']).' ' .
				 'LIMIT 1';

	$result = mysql_select_query($sql_query);

	if($result) {
		$cell = mysql_fetch_array($result, MYSQL_ASSOC);
	} else {
		mysql_save_sql_error(get_php_self(), 'Chargement de la page modifier une cellule', $sql_query, mysql_errno(), mysql_error());
		$_REQUEST['message'] = 'Erreur : chargement de la page &eacute;chou&eacute;!';
	}
?>
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN" "http://www.w3.org/TR/html4/loose.dtd">
<html>
<head>
	<title>ELBA - Appli Cellules - Modifier une cellule</title>
	<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
	<link href="../../css/style.css" rel="stylesheet" type="text/css">
	<SCRIPT type='text/javascript' src='../../scripts/main.js'></SCRIPT>
	<?php mysql_build_javascript_table('SELECT id, name FROM site ORDER BY name', 'site'); ?>
</HEAD>
<BODY class="main_body">
<FORM NAME='updateCellForm' ACTION='update.php' METHOD='POST'>
<TABLE class="normal">
	<TR>
		<TD class="main_title_text">Modifier une cellule</TD>
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
					<TD class="main_sub_section_text">- <A HREF="index.php?history=<?php echo get_php_self(); ?>" TARGET="_self">Liste des cellules</A> -</TD>
					<TD class="main_sub_section_text">- <A HREF="add.php?history=<?php echo get_php_self(); ?>" TARGET="_self">Ajouter une cellule</A> -</TD>
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
					<TD class="field_headling">site * :</TD>
					<TD width="field_separator"><INPUT TYPE="hidden" NAME="site" VALUE="<?php echo $cell['site_id']; ?>"></TD>
					<TD class="data">
<?php

if (isset($_SESSION['role_id']) && $_SESSION['role_id'] == 0) {

?>
<SELECT NAME='site_select' onChange='javascript:updateCellFormOnSiteChange()'></SELECT>
<?php

} else {

	echo $cell['site_name']; 

}

?>
</TD>
				</TR>
				<TR>
					<TD class="separator"></TD>
				</TR>
				<TR>
					<TD class="field_headling">nom * :</TD>
					<TD class="field_separator"></TD>
					<TD>
						<INPUT TYPE='text' NAME='name' VALUE='<?php echo $cell['name']; ?>' STYLE='width:150px' MAXLENGTH='20'>
					</TD>
				</TR>
				<TR>
					<TD class="separator"></TD>
				</TR>
				<TR>
					<TD class="field_headling">description :</TD>
					<TD class="field_separator"></TD>
					<TD>
						<INPUT TYPE='text' NAME='description' VALUE='<?php echo $cell['description']; ?>' STYLE='width:250px' MAXLENGTH='100'>
					</TD>
				</TR>
				<TR>
					<TD class="separator"></TD>
				</TR>
				<TR>
					<TD class="field_headling">efficacit&eacute; :</TD>
					<TD class="field_separator"></TD>
					<TD>
						<INPUT TYPE='text' NAME='effectiveness' VALUE='<?php echo $cell['effectiveness']; ?>' STYLE='width:50px' MAXLENGTH='20'>
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
						<INPUT TYPE='submit' NAME='update' VALUE='modifier' ALT='Modifier une cellule'>
					</TD>
				</TR>
			</TABLE>
			<TABLE class="normal">
				<TR>
					<TD class="separator"></TD>
				</TR>
<?php

get_machine_list();

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
					<TD class="main_bottom_text">Ajouter une machine</TD>
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
					<TD class="field_headling">machine * :</TD>
					<TD class="field_separator"></TD>
					<TD>
						<SELECT NAME='machine_select' onChange='javascript:updateCellFormOnMachineChange()'>
<?php

get_unmapped_machine_list();

?>
						</SELECT>
					</TD>
					<TD class="field_separator"></TD>
					<TD>
						<INPUT NAME='machine' TYPE='hidden'>
						<INPUT TYPE='submit' NAME='add_machine' VALUE='ajouter' ALT='Ajouter une machine'>
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
	updateCellFormFill()
</SCRIPT>
</FORM>
</BODY>
</HTML>