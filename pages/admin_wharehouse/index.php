<?php
	session_start();
	include_once(dirname(__FILE__).'/../../util/auth.php');
	include_once(dirname(__FILE__).'/../../util/sql.php');
	
	if (!is_allowed('admin_wharehouse')) {
		header('Location: ../not_allowed.php');
		exit();
	}

function get_wharehouse_list() {

	$sql = 'SELECT wharehouse.id, wharehouse.trigram, wharehouse.name ' .
		   'FROM wharehouse ' .
		   'ORDER BY wharehouse.name';

	$result = mysql_select_query($sql);

	if($result) {
		$count = mysql_num_rows($result);

		echo '<TABLE class="data">
				  <TR class="main_title">
					  <TD>Liste des entrepots ('.$count.')</TD>
				  </TR>
				  <TR>
					  <TD class="separator"></TD>
				  </TR>
				  <TR class="link">
					  <TD><A HREF=\'add.php\' TARGET=\'_self\'>Ajouter un entrepot</A></TD>
				  </TR>
				  <TR>
					  <TD class="separator"></TD>
				  </TR>
				  <TR>
					  <TD>
						  <TABLE class="data" border=\'0\' cellpadding=\'3\' cellspacing=\'1\'>
						  	<thead>
							    <tr>
							      <th><a href=\'\' onclick=\'this.blur(); return sortTable("wharehouseTable", 0, false);\' title=\'Trigram de l\'entrepot\'>Trigram</a></th>
								  <th><a href=\'\' onclick=\'this.blur(); return sortTable("wharehouseTable", 1, false);\' title=\'Nom de l\' entrepot\'>Nom</a></th>
								  <th></th> 
								  <th></th> 
							    </tr>
						    </thead>
							<tbody id=\'wharehouseTable\'>';

		$alternate = false;

		while ($row = mysql_fetch_array($result, MYSQL_ASSOC)) {

			if ($alternate) {
				echo '<TR class="alternate_row">';
			} else {
				echo '<TR class="row">';
			}
			
			echo '<TD>'.$row['trigram'].'</TD>
				  <TD>'.$row['name'].'</TD>
				  <TD class="button"><A HREF=\'update.php?id='.$row['id'].'\' TARGET=\'_self\'><img src="../../image/edit_little.jpg" ALT="Modifier"></A></TD>
				  <TD class="button"><A HREF=\'delete.php?id='.$row['id'].'\' TARGET=\'_self\'><img src="../../image/delete_little.jpg" ALT="Supprimer"></A></TD>
			   </TR>';

			$alternate = !$alternate;
		}

		echo '</TBODY>
		   </TABLE>
		  </TD>
  		</TR>
	</TABLE>';
	} else {
		echo '<TABLE class="data">
				  <TR class="main_title">
					  <TD>Liste des entrepots (0)</TD>
				  </TR>
				  <TR>
					  <TD class="separator"></TD>
				  </TR>
				  <TR class="link">
					  <TD><A HREF=\'add.php\' TARGET=\'_self\'>Ajouter un entrepot</A></TD>
				  </TR>
				  <TR>
					  <TD class="separator"></TD>
				  </TR>
			  </TABLE>';
	}
}


?>
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN" "http://www.w3.org/TR/html4/loose.dtd">
<html>
<head>
	<title>Web Appli Cellules - Liste des entrepots</title>
	<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
	<link href="../../css/style.css" rel="stylesheet" type="text/css">
	<SCRIPT type='text/javascript' src='../../scripts/table.js.php'></SCRIPT>
</HEAD>
<BODY class="main_body">
<?php

get_wharehouse_list();

?>
</BODY>
</HTML>