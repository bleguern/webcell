<?php
	session_start();
	include_once(dirname(__FILE__).'/../../util/auth.php');
	include_once(dirname(__FILE__).'/../../util/sql.php');
	
	if (!is_allowed('admin_wharehouse')) {
		header('Location: ../not_allowed.php');
		exit();
	}

	if ($_REQUEST['id'] == '') {
		header('Location: index.php');
		exit();
	}
	
	if (isset($_POST['update']) || isset($_POST['update_x'])) {
		update_wharehouse();
	}

function update_wharehouse() {
	if (($_REQUEST['trigram'] == '') ||
		($_REQUEST['name'] == '')) {
		echo '<SCRIPT type=\'text/javascript\'>
			  alert(\'Attention : des champs sont manquants!\')
			  </SCRIPT>';
	} else {

		$sql_query = 'UPDATE wharehouse ' .
					 'SET trigram = '.mysql_format_to_string($_REQUEST['trigram']).', ' .
					 'name = '.mysql_format_to_string($_REQUEST['name']).' ' .
					 'WHERE id = '.mysql_format_to_number($_REQUEST['id']).' LIMIT 1';

		if (mysql_update_query($sql_query)) {
			mysql_save_log('UPDATE_WHAREHOUSE', 'ID : '.$_REQUEST['id']);
			header('Location: index.php');
		} else {
			mysql_save_sql_error($_SERVER['PHP_SELF'], 'Modifier un entrepot', $sql_query, mysql_errno(), mysql_error());
			$_REQUEST['message'] = 'Erreur : entrepot non modifi&eacute;!';
		}
	}
}

	$sql_query = 'SELECT trigram, name FROM wharehouse WHERE id = '.mysql_format_to_number($_REQUEST['id']).' LIMIT 1';

	$result = mysql_select_query($sql_query);

	if($result) {
		$wharehouse = mysql_fetch_array($result, MYSQL_ASSOC);
	} else {
		mysql_save_sql_error($_SERVER['PHP_SELF'], 'Chargement de la page modifier un entrepot', $sql_query, mysql_errno(), mysql_error());
		$_REQUEST['message'] = 'Erreur : chargement de la page &eacute;chou&eacute;!';
	}
?>
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN" "http://www.w3.org/TR/html4/loose.dtd">
<html>
<head>
	<title>Web Appli Cellules - Modifier un entrepot</title>
	<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
	<link href="../../css/style.css" rel="stylesheet" type="text/css">
	<SCRIPT type='text/javascript' src='../../scripts/table.js.php'></SCRIPT>
</HEAD>
<BODY class="main_body">
<FORM NAME='updateWharehouseForm' ACTION='update.php' METHOD='POST'>
<TABLE class="data">
	<TR class="main_title">
		<TD>Modifier un entrepot</TD>
	</TR>
	<TR>
		<TD class="separator"></TD>
	</TR>
	<TR class="link">
		<TD><A HREF='index.php' TARGET='_self'>Liste des entrepots</A> / <A HREF='add.php' TARGET='_self'>Ajouter un entrepot</A></TD>
	</TR>
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
						<INPUT TYPE='text' NAME='trigram' VALUE='<?php echo $wharehouse['trigram']; ?>' STYLE='width:50px' MAXLENGTH='3'>
					</TD>
				</TR>
				<TR>
					<TD class="separator"></TD>
				</TR>
				<TR>
					<TD>nom * :</TD>
					<TD class="field_separator"></TD>
					<TD>
						<INPUT TYPE='text' NAME='name' VALUE='<?php echo $wharehouse['name']; ?>' STYLE='width:150px' MAXLENGTH='20'>
					</TD>
				</TR>
				<TR>
					<TD class="max_separator"></TD>
				</TR>
				<TR>
					<TD>
						<INPUT NAME='id' TYPE='hidden' VALUE='<?php echo $_REQUEST['id']; ?>'>
						<INPUT TYPE='submit' NAME='update' VALUE='modifier' ALT='Modifier un entrepot'>
					</TD>
				</TR>
				<TR>
					<TD class="max_separator"></TD>
				</TR>
			</TABLE>
			<TABLE class="data">
				<TR>
					<TD>* : champs obligatoires</TD>
				</TR>
				<TR>
					<TD class="separator"></TD>
				</TR>
			</TABLE>
			<TABLE class="data">
				<TR>
					<TD class="main_message">
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