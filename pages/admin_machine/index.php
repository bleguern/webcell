<?php
	session_start();
	include_once(dirname(__FILE__).'/../../util/util.php');
	include_once(dirname(__FILE__).'/../../util/auth.php');
	include_once(dirname(__FILE__).'/../../util/sql.php');
	
	if (!is_allowed('admin_machine')) {
		header('Location: ../not_allowed.php');
		exit();
	}

function get_machine_list() {

	$sql = 'SELECT machine.id, site.name as site, cell.name as cell, machine.active, machine.name, machine.description, main_machine.name as main_machine ' .
		   'FROM machine ' .
		   'LEFT OUTER JOIN cell ON machine.cell_id = cell.id ' .
		   'LEFT OUTER JOIN site ON machine.site_id = site.id ' .
		   'LEFT OUTER JOIN machine as main_machine ON machine.main_machine_id = main_machine.id ';
	
	if (isset($_SESSION['role_id']) && $_SESSION['role_id'] != 0) {
		$sql .= 'WHERE site.id = '.mysql_format_to_number($_SESSION['site_id']).' ';
	}
	
	$sql .= 'ORDER BY machine.active DESC, site.name, cell.name, machine.name';
	
	$result = mysql_select_query($sql);

	if($result) {
		$count = mysql_num_rows($result);

		echo '<TABLE class="normal">
				  <TR>
					  <TD class="main_title_text">Liste des machines ('.$count.')</TD>
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
					  <TD class="main_sub_section_text">- <A HREF="add.php?history='.get_php_self().'" TARGET="_self">Ajouter une machine</A> -</TD>
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
								<TD class="header">Cellule</TD>
								<TD class="header">Site</TD>
								<TD class="header">Description</TD>
								<TD class="header">Machine principale</TD>
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
				echo '<TD align="center"><A HREF=\'_unactivate.php?id='.$row['id'].'\' TARGET=\'_self\'>Oui</A></TD>';
			} else {
				echo '<TD align="center"><A HREF=\'_activate.php?id='.$row['id'].'\' TARGET=\'_self\'>Non</A></TD>';
			}
		
			echo '<TD>'.$row['cell'].'</TD>
				  <TD>'.$row['site'].'</TD>
				  <TD>'.$row['description'].'</TD>
				  <TD>'.$row['main_machine'].'</TD>
				  <TD align="center"><A HREF=\'update.php?id='.$row['id'].'\' TARGET=\'_self\'><img src="../../image/edit_little.jpg" ALT="Modifier"></A></TD>
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
					  <TD class="main_title_text">Liste des machines (0)</TD>
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
					  <TD class="main_sub_section_text">- <A HREF="add.php?history='.get_php_self().'" TARGET="_self">Ajouter une machine</A> -</TD>
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
	<title>ELBA - Appli Cellules - Liste des machines</title>
	<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
	<link href="../../css/style.css" rel="stylesheet" type="text/css">
</HEAD>
<BODY class="main_body">
<?php

get_machine_list();

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