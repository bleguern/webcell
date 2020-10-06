<?php
	session_start();
	include_once(dirname(__FILE__).'/../../util/util.php');
	include_once(dirname(__FILE__).'/../../util/auth.php');
	include_once(dirname(__FILE__).'/../../util/sql.php');
	
	if (!is_allowed('admin_site')) {
		header('Location: ../not_allowed.php');
		exit();
	}

	if ($_REQUEST['id'] == '') {
		header('Location: index.php');
		exit();
	}
	
	if (isset($_POST['update']) || isset($_POST['update_x'])) {
		update_site();
	} else if (isset($_POST['add_cell']) || isset($_POST['add_cell_x'])) {
		add_cell();
	}

function get_cell_list() {

	$sql = 'SELECT id, name, description FROM cell WHERE site_id = '.mysql_format_to_number($_REQUEST['id']).' ORDER BY name';

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
				 <TD class="main_bottom_text">Liste des cellules ('.$count.')</TD>
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

			echo '<TD>'.$row['name'].'</TD>
			 	  <TD>'.$row['description'].'</TD>
			 	  <TD align="center"><A HREF=\'_remove_cell.php?id='.$row['id'].'&site_id='.$_REQUEST['id'].'\' TARGET=\'_self\'><img src="../../image/delete_little.jpg" ALT="Retirer"></A></TD>
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
				 <TD class="main_bottom_text">Liste des cellules (0)</TD>
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

function get_unmapped_cell_list() {
	$sql = 'SELECT id, name FROM cell WHERE site_id IS NULL ORDER BY name';
	mysql_print_select_option($sql);
}

function update_site() {
	if (($_REQUEST['trigram'] == '') ||
		($_REQUEST['name'] == '') ||
		($_REQUEST['ip_address'] == '')) {
		$_REQUEST['message'] = 'Attention : des champs sont manquants!';
	} else {

		$sql_query = 'UPDATE site ' .
					 'SET trigram = '.mysql_format_to_string($_REQUEST['trigram']).', ' .
					 'name = '.mysql_format_to_string($_REQUEST['name']).', ' .
					 'ip_address = '.mysql_format_to_string($_REQUEST['ip_address']).', ' .
					 'warehouse_id = '.mysql_format_to_string($_REQUEST['warehouse']).' ' .
					 'WHERE id = '.mysql_format_to_number($_REQUEST['id']).' LIMIT 1';

		if (mysql_update_query($sql_query)) {
			mysql_save_log('UPDATE_SITE', 'ID : '.$_REQUEST['id']);
			header('Location: index.php');
		} else {
			mysql_save_sql_error(get_php_self(), 'Modifier un site', $sql_query, mysql_errno(), mysql_error());
			$_REQUEST['message'] = 'Erreur : site non modifi&eacute;!';
		}
	}
}

function add_cell() {

	if ($_REQUEST['cell'] == '') {
		$_REQUEST['message'] = 'Attention : le champ suivant est manquant : cellule!';
	} else {
		$sql_query = 'UPDATE cell SET site_id = '.mysql_format_to_number($_REQUEST['id']).' WHERE id = '.mysql_format_to_number($_REQUEST['cell']).' LIMIT 1';

		if (!mysql_update_query($sql_query)) {
			mysql_save_sql_error(get_php_self(), 'Ajout de cellule a un site', $sql_query, mysql_errno(), mysql_error());
			$_REQUEST['message'] = 'Erreur : cellule non ajout&eacute;e!';
		}
	}
}

	$sql_query = 'SELECT trigram, name, ip_address, warehouse_id FROM site WHERE id = '.mysql_format_to_number($_REQUEST['id']).' LIMIT 1';

	$result = mysql_select_query($sql_query);

	if($result) {
		$site = mysql_fetch_array($result, MYSQL_ASSOC);
	} else {
		mysql_save_sql_error(get_php_self(), 'Chargement de la page modifier un site', $sql_query, mysql_errno(), mysql_error());
		$_REQUEST['message'] = 'Erreur : chargement de la page &eacute;chou&eacute;!';
	}
?>
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN" "http://www.w3.org/TR/html4/loose.dtd">
<html>
<head>
	<title>ELBA - Appli Cellules - Modifier un site</title>
	<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
	<link href="../../css/style.css" rel="stylesheet" type="text/css">
	<SCRIPT type='text/javascript' src='../../scripts/main.js'></SCRIPT>
	<?php mysql_build_javascript_table('SELECT id, name FROM warehouse ORDER BY name', 'warehouse'); ?>
</HEAD>
<BODY class="main_body">
<FORM NAME='updateSiteForm' ACTION='update.php' METHOD='POST'>
<TABLE class="normal">
	<TR>
		<TD class="main_title_text">Modifier un site</TD>
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
					<TD class="main_sub_section_text">- <A HREF="index.php?history=<?php echo get_php_self(); ?>" TARGET="_self">Liste des sites</A> -</TD>
					<TD class="main_sub_section_text">- <A HREF="add.php?history=<?php echo get_php_self(); ?>" TARGET="_self">Ajouter un site</A> -</TD>
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
					<TD>trigram * :</TD>
					<TD class="field_separator"></TD>
					<TD>
						<INPUT TYPE='text' NAME='trigram' VALUE='<?php echo $site['trigram']; ?>' STYLE='width:50px' MAXLENGTH='3'>
					</TD>
				</TR>
				<TR>
					<TD class="separator"></TD>
				</TR>
				<TR>
					<TD>nom * :</TD>
					<TD class="field_separator"></TD>
					<TD>
						<INPUT TYPE='text' NAME='name' VALUE='<?php echo $site['name']; ?>' STYLE='width:150px' MAXLENGTH='50'>
					</TD>
				</TR>
				<TR>
					<TD class="separator"></TD>
				</TR>
				<TR>
					<TD>ip * :</TD>
					<TD class="field_separator"></TD>
					<TD>
						<INPUT TYPE='text' NAME='ip_address' VALUE='<?php echo $site['ip_address']; ?>' STYLE='width:250px' MAXLENGTH='100'>
					</TD>
				</TR>
				<TR>
					<TD class="separator"></TD>
				</TR>
				<TR>
					<TD>entrep&ocirc;t :</TD>
					<TD class="field_separator"></TD>
					<TD>
						<SELECT NAME='warehouse_select' onChange='javascript:updateSiteFormOnwarehouseChange()'></SELECT>
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
						<INPUT NAME='warehouse' TYPE='hidden' VALUE='<?php echo $site['warehouse_id']; ?>'>
						<INPUT TYPE='submit' NAME='update' VALUE='modifier' ALT='Modifier un site'>
					</TD>
				</TR>
			</TABLE>
			<TABLE class="normal">
				<TR>
					<TD class="max_separator"></TD>
				</TR>
<?php

get_cell_list();

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
					<TD class="main_bottom_text">Ajouter une cellule</TD>
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
					<TD>cellule * :</TD>
					<TD class="field_separator"></TD>
					<TD>
						<SELECT NAME='cell_select' onChange='javascript:updateSiteFormOnCellChange()'>
<?php

get_unmapped_cell_list();

?>
						</SELECT>
					</TD>
					<TD class="field_separator"></TD>
					<TD>
						<INPUT NAME='cell' TYPE='hidden'>
						<INPUT TYPE='submit' NAME='add_cell' VALUE='ajouter' ALT='Ajouter une cellule'>
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
	updateSiteFormFill()
</SCRIPT>
</FORM>
</BODY>
</HTML>