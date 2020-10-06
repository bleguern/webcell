<?php
	session_start();
	include_once(dirname(__FILE__).'/../../util/util.php');
	include_once(dirname(__FILE__).'/../../util/auth.php');
	include_once(dirname(__FILE__).'/../../util/sql.php');
	
	if (!is_allowed('admin_user')) {
		header('Location: ../not_allowed.php');
		exit();
	}

function get_user_list() {

	$sql = 'SELECT user.id, site.name as site, role.name as role, CONCAT(user.first_name, \' \', user.last_name) as name, user.active, user.email, user.windows_account, user.telephone ' .
		   'FROM user ' .
		   'LEFT OUTER JOIN site ON user.site_id = site.id ' .
		   'LEFT OUTER JOIN role ON user.role_id = role.id ' .
		   'ORDER BY user.active DESC, role, site, name';

	$result = mysql_select_query($sql);

	if($result) {
		$count = mysql_num_rows($result);

		echo '<TABLE class="normal">
				  <TR>
					  <TD class="main_title_text">Liste des utilisateurs ('.$count.')</TD>
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
					  <TD class="main_sub_section_text">- <A HREF="add.php?history='.get_php_self().'" TARGET="_self">Ajouter un utilisateur</A> -</TD>
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
								<TD class="header">Actif</TD>
								<TD class="header">Role</TD>
								<TD class="header">Site</TD>
								<TD class="header">Email</TD>
								<TD class="header">Compte</TD>
								<TD class="header">Telephone</TD>
								<TD class="header"></TD>
								<TD class="header"></TD>
							</TR>';

		$alternate = false;

		while ($row = mysql_fetch_array($result, MYSQL_ASSOC)) {

			if ($alternate) {
				echo '<TR class="alternate_row">';
			} else {
				echo '<TR class="row">';
			}
			
			echo '<TD>'.$row['name'].'</TD>';
			
			if ($row['active'] == 1) {
				echo '<TD><A HREF=\'_unactivate.php?id='.$row['id'].'\' TARGET=\'_self\'>Oui</A></TD>';
			} else {
				echo '<TD><A HREF=\'_activate.php?id='.$row['id'].'\' TARGET=\'_self\'>Non</A></TD>';
			}
				  
			echo '<TD>'.$row['role'].'</TD>
				  <TD>'.$row['site'].'</TD>
				  <TD><A HREF=\'mailto:'.$row['email'].'\' TARGET=\'_blank\'>'.$row['email'].'</A></TD>
				  <TD>'.$row['windows_account'].'</TD>
				  <TD>'.$row['telephone'].'</TD>
				  <TD align="center"><A HREF=\'update.php?id='.$row['id'].'\' TARGET=\'_self\'><img src="../../image/edit_little.jpg" ALT="Modifier"></A></TD>
				  <TD align="center"><A HREF=\'delete.php?id='.$row['id'].'\' TARGET=\'_self\'><img src="../../image/delete_little.jpg" ALT="Supprimer"></A></TD>
			   </TR>';

			$alternate = !$alternate;
		}

		echo '</TABLE>
		  </TD>
  		</TR>
	</TABLE>';
	} else {
		echo '<TABLE class="normal">
				  <TR>
					  <TD class="main_title_text">Liste des utilisateurs (0)</TD>
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
					  <TD class="main_sub_section_text">- <A HREF="add.php?history='.get_php_self().'" TARGET="_self">Ajouter un utilisateur</A> -</TD>
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
			  </TABLE>';
	}
}


?>
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN" "http://www.w3.org/TR/html4/loose.dtd">
<html>
<head>
	<title>ELBA - Appli Cellules - Liste des utilisateurs</title>
	<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
	<link href="../../css/style.css" rel="stylesheet" type="text/css">
</HEAD>
<BODY class="main_body">
<?php

get_user_list();

?>
<TABLE>
	<TR>
		<TD class="separator"></TD>
	</TR>
	<TR class="message">
		<TD>
<?php

if(isset($_REQUEST['message'])) {
echo $_REQUEST['message'];
}

?>
		</TD>
	</TR>
</TABLE>
</BODY>
</HTML>