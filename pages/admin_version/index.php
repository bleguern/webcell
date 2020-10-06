<?php
	session_start();
	include_once(dirname(__FILE__).'/../../util/util.php');
	include_once(dirname(__FILE__).'/../../util/auth.php');
	include_once(dirname(__FILE__).'/../../util/sql.php');

	if (!is_allowed('admin_version')) {

		header('Location: ../not_allowed.php');
		exit();
	}

function get_version_list() {

	$sql = 'SELECT version.id, DATE_FORMAT(version.version_date, \'%d/%m/%Y %H:%i:%s\') as version_date, version.number, version.name, CONCAT(user.first_name, \' \', user.last_name) AS owner ' .
		   'FROM version ' .
		   'LEFT OUTER JOIN user ON user.id = version.user_id ' .
		   'ORDER BY version.version_date DESC';

	$result = mysql_select_query($sql);

	if($result) {

		$count = mysql_num_rows($result);

		echo '<TABLE class="normal">
				  <TR>
					  <TD class="main_title_text">Liste des versions ('.$count.')</TD>
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
					  <TD class="main_sub_section_text">- <A HREF="add.php?history='.get_php_self().'" TARGET="_self">Ajouter une version</A> -</TD>
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
								<TD class="header">Num&eacute;ro</TD>
								<TD class="header">Date</TD>
								<TD class="header">Nom</TD>
								<TD class="header">Propri&eacute;taire</TD>
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

			echo '<TD>'.$row['number'].'</TD>
				  <TD>'.$row['version_date'].'</TD>
			   	  <TD>'.$row['name'].'</TD>
			      <TD>'.$row['owner'].'</TD>
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
					  <TD class="main_title_text">Liste des versions (0)</TD>
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
					  <TD class="main_sub_section_text">- <A HREF="add.php?history='.get_php_self().'" TARGET="_self">Ajouter une version</A> -</TD>
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
<HTML>
<HEAD>
	<title>ELBA - Appli Cellules - Liste des versions</title>
	<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
	<link href="../../css/style.css" rel="stylesheet" type="text/css">
</HEAD>
<BODY class="main_body">
<?php

get_version_list();

?>
</BODY>
</HTML>