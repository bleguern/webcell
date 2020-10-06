<?php
	session_start();
	include_once(dirname(__FILE__).'/../../util/util.php');
	include_once(dirname(__FILE__).'/../../util/auth.php');
	include_once(dirname(__FILE__).'/../../util/sql.php');
	
	if (!is_allowed('admin_cell')) {
		header('Location: ../not_allowed.php');
		exit();
	}

function get_cell_list() {

	$sql = 'SELECT cell.id, site.name as site, cell.name, cell.description, cell.effectiveness ' .
		   'FROM cell ' .
		   'LEFT OUTER JOIN site ON cell.site_id = site.id ';
	
	if (isset($_SESSION['role_id']) && $_SESSION['role_id'] != 0) {
		$sql .= 'WHERE site.id = '.mysql_format_to_number($_SESSION['site_id']).' ';
	}
	
	$sql .= 'ORDER BY site.name, cell.name';

	$result = mysql_select_query($sql);

	if($result) {
		$count = mysql_num_rows($result);

		echo '<TABLE class="normal">
				  <TR>
					  <TD class="main_title_text">Liste des cellules ('.$count.')</TD>
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
					  <TD class="main_sub_section_text">- <A HREF="add.php?history='.get_php_self().'" TARGET="_self">Ajouter une cellule</A> -</TD>
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
								<TD class="header">Site</TD>
								<TD class="header">Description</TD>
								<TD class="header">Efficacite</TD>
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
			
			echo '<TD>'.$row['name'].'</TD>
				  <TD>'.$row['site'].'</TD>
				  <TD>'.$row['description'].'</TD>
				  <TD>'.$row['effectiveness'].'</TD>
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
					  <TD class="main_title_text">Liste des cellules (0)</TD>
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
					  <TD class="main_sub_section_text">- <A HREF="add.php?history='.get_php_self().'" TARGET="_self">Ajouter une cellule</A> -</TD>
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
	<title>ELBA - Appli Cellules - Liste des cellules</title>
	<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
	<link href="../../css/style.css" rel="stylesheet" type="text/css">
</HEAD>
<BODY class="main_body">
<?php

get_cell_list();

?>
</BODY>
</HTML>