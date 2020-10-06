<?php
	session_start();
	include_once(dirname(__FILE__).'/../../util/util.php');
	include_once(dirname(__FILE__).'/../../util/auth.php');
	include_once(dirname(__FILE__).'/../../util/sql.php');
	
	if (!is_allowed('admin_access')) {
		header('Location: ../not_allowed.php');
		exit();
	}

	if ($_REQUEST['id'] == '') {
		header('Location: index.php');
		exit();
	}
	
	if (isset($_POST['update']) || isset($_POST['update_x'])) {
		update_access();
	} else if (isset($_POST['add_role']) || isset($_POST['add_role_x'])) {
		add_role();
	}

function get_role_list() {

	$sql = 'SELECT id, name ' .
		   'FROM role ' .
		   'WHERE id IN (SELECT role_id FROM role_access WHERE access_id = '.mysql_format_to_number($_REQUEST['id']).') ' .
		   'ORDER BY name';

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
				 <TD class="main_bottom_text">Liste des roles ('.$count.')</TD>
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
				  <TD align="center"><A HREF=\'_remove_role.php?role_id='.$row['id'].'&access_id='.$_REQUEST['id'].'\' TARGET=\'_self\'><img src="../../image/delete_little.jpg" ALT="Retirer"></A></TD>
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
				 <TD class="main_bottom_text">Liste des roles (0)</TD>
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

function get_unmapped_role_list() {
	$sql = 'SELECT id, name FROM role WHERE id NOT IN (SELECT role_id FROM role_access WHERE access_id = '.mysql_format_to_number($_REQUEST['id']).') ORDER BY name';
	mysql_print_select_option($sql);
}

function update_access() {
	if ($_REQUEST['url'] == '') {
		$_REQUEST['message'] = 'Attention : des champs sont manquants!';
	} else {

		$sql_query = 'UPDATE access ' .
					 'SET url = '.mysql_format_to_string($_REQUEST['url']).', ' .
					 'description = '.mysql_format_to_string($_REQUEST['description']).' ' .
					 'WHERE id = '.mysql_format_to_number($_REQUEST['id']).' LIMIT 1';

		if (mysql_update_query($sql_query)) {
			mysql_save_log('UPDATE_ACCESS', 'ID : '.$_REQUEST['id']);
			header('Location: index.php');
		} else {
			mysql_save_sql_error(get_php_self(), 'Modifier un acc&egrave;s', $sql_query, mysql_errno(), mysql_error());
			$_REQUEST['message'] = 'Erreur : acc&egrave;s non modifi&eacute;e!';
		}
	}
}

function add_role() {

	if ($_REQUEST['role'] == '') {
		$_REQUEST['message'] = 'Attention : le champ suivant est manquant : role!';
	} else {
		$sql_query = 'INSERT INTO role_access (role_id, access_id) VALUES ('.mysql_format_to_number($_REQUEST['role']).', '.mysql_format_to_number($_REQUEST['id']).')';

		if (!mysql_insert_query($sql_query)) {
			mysql_save_sql_error(get_php_self(), 'Ajout de role a un acc&egrave;s', $sql_query, mysql_errno(), mysql_error());
			$_REQUEST['message'] = 'Erreur : role non ajout&eacute;!';
		}
	}
}

	$sql_query = 'SELECT url, description FROM access WHERE id = '.mysql_format_to_number($_REQUEST['id']).' LIMIT 1';

	$result = mysql_select_query($sql_query);

	if($result) {
		$access = mysql_fetch_array($result, MYSQL_ASSOC);
	} else {
		mysql_save_sql_error(get_php_self(), 'Chargement de la page modifier un acc&egrave;s', $sql_query, mysql_errno(), mysql_error());
		$_REQUEST['message'] = 'Erreur : chargement de la page &eacute;chou&eacute;!';
	}
?>
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN" "http://www.w3.org/TR/html4/loose.dtd">
<html>
<head>
	<title>ELBA - Appli Cellules - Modifier un acc&egrave;s</title>
	<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
	<link href="../../css/style.css" rel="stylesheet" type="text/css">
	<SCRIPT type='text/javascript' src='../../scripts/main.js'></SCRIPT>
</HEAD>
<BODY class="main_body">
<FORM NAME='updateAccessForm' ACTION='update.php' METHOD='POST'>
<TABLE class="normal">
	<TR>
		<TD class="main_title_text">Modifier un acc&egrave;s</TD>
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
					<TD class="main_sub_section_text">- <A HREF="index.php?history=<?php echo get_php_self(); ?>" TARGET="_self">Liste des acc&egrave;s</A> -</TD>
					<TD class="main_sub_section_text">- <A HREF="add.php?history=<?php echo get_php_self(); ?>" TARGET="_self">Ajouter un acc&egrave;s</A> -</TD>
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
					<TD class="field_headling">url * :</TD>
					<TD class="field_separator"></TD>
					<TD class="field_value">
						<INPUT TYPE='text' NAME='url' VALUE='<?php echo $access['url']; ?>' STYLE='width:200px' MAXLENGTH='100'>
					</TD>
				</TR>
				<TR>
					<TD class="separator"></TD>
				</TR>
				<TR>
					<TD class="field_headling">description :</TD>
					<TD class="field_separator"></TD>
					<TD class="field_value">
						<INPUT TYPE='text' NAME='description' VALUE='<?php echo $access['description']; ?>' STYLE='width:300px' MAXLENGTH='200'>
					</TD>
				</TR>
				<TR>
					<TD class="separator"></TD>
				</TR>
			</TABLE>
			<TABLE class="normal">
				<TR align="center">
					<TD>
						<INPUT NAME='id' TYPE='hidden' VALUE='<?php echo $_REQUEST['id']; ?>'>
						<INPUT TYPE='submit' NAME='update' VALUE='modifier' ALT='Modifier un acc&egrave;s'>
					</TD>
				</TR>
			</TABLE>
			<TABLE class="normal">
				<TR>
					<TD class="separator"></TD>
				</TR>
<?php

get_role_list();

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
					<TD class="main_bottom_text">Ajouter un role</TD>
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
					<TD class="field_headling">role * :</TD>
					<TD class="field_separator"></TD>
					<TD>
						<SELECT NAME='role_select' onChange='javascript:updateAccessFormOnRoleChange()'>
<?php

get_unmapped_role_list();

?>
						</SELECT>
					</TD>
					<TD class="field_separator"></TD>
					<TD>
						<INPUT NAME='role' TYPE='hidden'>
						<INPUT TYPE='submit' NAME='add_role' VALUE='ajouter' ALT='Ajouter un role'>
					</TD>
				</TR>
				<TR>
					<TD class="separator"></TD>
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