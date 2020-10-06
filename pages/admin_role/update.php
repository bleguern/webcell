<?php
	session_start();
	include_once(dirname(__FILE__).'/../../util/util.php');
	include_once(dirname(__FILE__).'/../../util/auth.php');
	include_once(dirname(__FILE__).'/../../util/sql.php');
	
	if (!is_allowed('admin_role')) {
		header('Location: ../not_allowed.php');
		exit();
	}

	if ($_REQUEST['id'] == '') {
		header('Location: index.php');
		exit();
	}
	
	if (isset($_POST['update']) || isset($_POST['update_x'])) {
		update_role();
	} else if (isset($_POST['add_user']) || isset($_POST['add_user_x'])) {
		add_user();
	}

function get_user_list() {

	$sql = 'SELECT user.id, user.active, site.name as site, user.login, user.email, user.windows_account, user.last_name, user.first_name  ' .
		   'FROM user ' .
		   'LEFT OUTER JOIN site ON site.id = user.site_id ' .
		   'WHERE user.role_id = '.mysql_format_to_number($_REQUEST['id']).' ' .
		   'ORDER BY site.name, user.last_name, user.first_name';

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
				 <TD class="main_bottom_text">Liste des utilisateurs ('.$count.')</TD>
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
							<TD class="header">Prenom</TD>
							<TD class="header">Actif</TD>
							<TD class="header">Site</TD>
							<TD class="header">Email</TD>
							<TD class="header">Compte</TD>
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
			
			if ($row['active'] == '1')
			{
				$active = 'Oui';
			}
			
			echo '<TD>'.$row['last_name'].'</TD>
				  <TD>'.$row['first_name'].'</TD>
				  <TD align="center">'.$active.'</TD>
				  <TD>'.$row['site'].'</TD>
			 	  <TD><A HREF=\'mailto:'.$row['email'].'\' TARGET=\'_blank\'>'.$row['email'].'</A></TD>
			 	  <TD>'.$row['windows_account'].'</TD>
			 	  <TD align="center"><A HREF=\'_remove_user.php?id='.$row['id'].'&role_id='.$_REQUEST['id'].'\' TARGET=\'_self\'><img src="../../image/delete_little.jpg" ALT="Retirer"></A></TD>
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
				 <TD class="main_bottom_text">Liste des utilisateurs (0)</TD>
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

function get_unmapped_user_list() {
	$sql = 'SELECT user.id, CONCAT(user.first_name, \' \', user.last_name, \' : \', site.name) as name FROM user LEFT OUTER JOIN site ON site.id = user.site_id WHERE role_id IS NULL ORDER BY name';
	mysql_print_select_option($sql);
}

function update_role() {
	if ($_REQUEST['name'] == '') {
		$_REQUEST['message'] = 'Attention : des champs sont manquants!';
	} else {

		$sql_query = 'UPDATE role ' .
					 'SET name = '.mysql_format_to_string($_REQUEST['name']).' ' .
					 'WHERE id = '.mysql_format_to_number($_REQUEST['id']).' LIMIT 1';

		if (mysql_update_query($sql_query)) {
			mysql_save_log('UPDATE_ROLE', 'ID : '.$_REQUEST['id']);
			header('Location: index.php');
		} else {
			mysql_save_sql_error(get_php_self(), 'Modifier un role', $sql_query, mysql_errno(), mysql_error());
			$_REQUEST['message'] = 'Erreur : role non modifi&eacute;e!';
		}
	}
}

function add_user() {

	if ($_REQUEST['user'] == '') {
		$_REQUEST['message'] = 'Attention : le champ suivant est manquant : utilisateur!';
	} else {
		$sql_query = 'UPDATE user SET role_id = '.mysql_format_to_number($_REQUEST['id']).' WHERE id = '.mysql_format_to_number($_REQUEST['user']).' LIMIT 1';

		if (!mysql_update_query($sql_query)) {
			mysql_save_sql_error(get_php_self(), 'Ajout d utilisateur a un role', $sql_query, mysql_errno(), mysql_error());
			$_REQUEST['message'] = 'Erreur : utilisateur non ajout&eacute;!';
		}
	}
}

	$sql_query = 'SELECT name FROM role WHERE id = '.mysql_format_to_number($_REQUEST['id']).' LIMIT 1';

	$result = mysql_select_query($sql_query);

	if($result) {
		$role = mysql_fetch_array($result, MYSQL_ASSOC);
	} else {
		mysql_save_sql_error(get_php_self(), 'Chargement de la page modifier un role', $sql_query, mysql_errno(), mysql_error());
		$_REQUEST['message'] = 'Erreur : chargement de la page &eacute;chou&eacute;!';
	}
?>
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN" "http://www.w3.org/TR/html4/loose.dtd">
<html>
<head>
	<title>ELBA - Appli Cellules - Modifier un role</title>
	<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
	<link href="../../css/style.css" rel="stylesheet" type="text/css">
	<SCRIPT type='text/javascript' src='../../scripts/main.js'></SCRIPT>
</HEAD>
<BODY class="main_body">
<FORM NAME='updateRoleForm' ACTION='update.php' METHOD='POST'>
<TABLE class="normal">
	<TR>
		<TD class="main_title_text">Modifier un role</TD>
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
					<TD class="main_sub_section_text">- <A HREF="index.php?history=<?php echo get_php_self(); ?>" TARGET="_self">Liste des roles</A> -</TD>
					<TD class="main_sub_section_text">- <A HREF="add.php?history=<?php echo get_php_self(); ?>" TARGET="_self">Ajouter un role</A> -</TD>
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
					<TD>nom * :</TD>
					<TD class="field_separator"></TD>
					<TD>
						<INPUT TYPE='text' NAME='name' VALUE='<?php echo $role['name']; ?>' STYLE='width:200px' MAXLENGTH='50'>
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
						<INPUT TYPE='submit' NAME='update' VALUE='modifier' ALT='Modifier un role'>
					</TD>
				</TR>
			</TABLE>
			<TABLE class="normal">
				<TR>
					<TD class="max_separator"></TD>
				</TR>
<?php

get_user_list();

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
					<TD class="main_bottom_text">Ajouter un utilisateur</TD>
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
					<TD>utilisateur * :</TD>
					<TD class="field_separator"></TD>
					<TD>
						<SELECT NAME='user_select' onChange='javascript:updateRoleFormOnUserChange()'>
<?php

get_unmapped_user_list();

?>
						</SELECT>
					</TD>
					<TD class="field_separator"></TD>
					<TD>
						<INPUT NAME='user' TYPE='hidden'>
						<INPUT TYPE='submit' NAME='add_user' VALUE='ajouter' ALT='Ajouter un utilisateur'>
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
</FORM>
</BODY>
</HTML>