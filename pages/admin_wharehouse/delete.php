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
	
	if (isset($_POST['delete']) || isset($_POST['delete_x'])) {
		delete_wharehouse();
	}

function delete_wharehouse() {
	$sql_query = 'DELETE FROM wharehouse ' .
				 'WHERE id = '.mysql_format_to_number($_REQUEST['id']).' LIMIT 1';

	if (mysql_delete_query($sql_query, 1)) {
		mysql_save_log('DELETE_WHAREHOUSE', 'ID : '.$_REQUEST['id']);
		header('Location: index.php');
	} else {
		if (mysql_errno() == 1451) {
			$_REQUEST['message'] = 'Erreur : vous ne pouvez pas supprimer un entrepot attribu&eacute; a un site! Veuillez retirer l\'entrepot d\'abord.';
		} else {
			mysql_save_sql_error($_SERVER['PHP_SELF'], 'Supprimer un entrepot', $sql_query, mysql_errno(), mysql_error());
			$_REQUEST['message'] = 'Erreur : entrepot non supprim&eacute;!';
		}
	}
}
	$sql_query = 'SELECT name FROM wharehouse WHERE id = '.mysql_format_to_number($_REQUEST['id']).' LIMIT 1';

	$result = mysql_select_query($sql_query);

	if($result) {
		$wharehouse = mysql_fetch_array($result, MYSQL_ASSOC);
	} else {
		mysql_save_sql_error($_SERVER['PHP_SELF'], 'Chargement de la page supprimer un entrepot', $sql_query, mysql_errno(), mysql_error());
		$_REQUEST['message'] = 'Erreur : chargement de la page &eacute;chou&eacute;!';
	}
?>
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN" "http://www.w3.org/TR/html4/loose.dtd">
<html>
<head>
	<title>Web Appli Cellules - Supprimer un entrepot</title>
	<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
	<link href="../../css/style.css" rel="stylesheet" type="text/css">
</HEAD>
<BODY class="main_body">
<FORM NAME='deleteWharehouseForm' ACTION='delete.php' METHOD='POST'>
<TABLE class="data">
	<TR class="main_title">
		<TD>Supprimer un entrepot</TD>
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
			<TABLE class="data">
				<TR class="main_message">
					<TD>Etes vous s&ucirc;r de vouloir supprimer l'entrepot : <?php echo $wharehouse['name']; ?> ?</TD>
				</TR>
				<TR>
					<TD class="separator"></TD>
				</TR>
				<TR>
					<TD>
						<INPUT NAME='id' TYPE='hidden' VALUE='<?php echo $_REQUEST['id']; ?>'>
						<INPUT TYPE='submit' NAME='delete' VALUE='Supprimer' ALT='Supprimer un entrepot'>
					</TD>
				</TR>
				<TR>
					<TD class="separator"></TD>
				</TR>
			</TABLE>
			<TABLE class="data">
				<TR class="main_message">
					<TD>
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