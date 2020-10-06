<?php
	session_start();
	include_once(dirname(__FILE__).'/../../util/auth.php');
	include_once(dirname(__FILE__).'/../../util/sql.php');
	
	if (!is_allowed('log')) {
		header('Location: ../not_allowed.php');
		exit();
	}
	
	if (isset($_POST['delete_all']) || isset($_POST['delete_all_x'])) {
		delete_all_logs();
	}
	
function delete_all_logs() {

	$sql_query = 'DELETE FROM log';
	mysql_delete_query($sql_query);
}

function get_log_list() {

	$sql = 'SELECT id, DATE_FORMAT(log_date, \'%d/%m/%Y : %H:%i:%s\') as date, login, ip_address, name, description ' .
		   'FROM log ' .
		   'ORDER BY log_date desc, name';

	$result = mysql_select_query($sql);

	if($result) {
		$count = mysql_num_rows($result);

		echo '<TABLE class="normal">
				  <TR>
					  <TD class="main_title_text">Liste des journaux ('.$count.')</TD>
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
								<TD class="header">Date</TD>
								<TD class="header">Login</TD>
								<TD class="header">IP</TD>
								<TD class="header">Nom</TD>
								<TD class="header">Description</TD>
							</TR>';

		$alternate = false;

		while ($row = mysql_fetch_array($result, MYSQL_ASSOC)) {

			if ($alternate) {
				echo '<TR class="alternate_row">';
			} else {
				echo '<TR class="row">';
			}
			
			echo '<TD>'.$row['date'].'</TD>
				  <TD>'.$row['login'].'</TD>
				  <TD>'.$row['ip_address'].'</TD>
				  <TD>'.$row['name'].'</TD>
				  <TD>'.$row['description'].'</TD>
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
					  <TD class="main_title_text">Liste des journaux (0)</TD>
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
	<title>ELBA - Appli Cellules - Liste des journaux</title>
	<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
	<link href="../../css/style.css" rel="stylesheet" type="text/css">
</HEAD>
<BODY class="main_body">
<FORM NAME='logForm' ACTION='index.php' METHOD='POST'>
<TABLE class="normal">
	<TR>
		<TD>
<?php

get_log_list();

?>
		</TD>
	</TR>
	<TR>
		<TD class="max_separator"></TD>
	</TR>
	<TR>
		<TD align="center"><INPUT TYPE='submit' NAME='delete_all' VALUE='Supprimer tout' ALT='Supprimer tous les journaux'></TD>
	</TR>
</TABLE>
</FORM>
</BODY>
</HTML>