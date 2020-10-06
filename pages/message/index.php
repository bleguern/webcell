<?php
	session_start();
	include_once(dirname(__FILE__).'/../../util/sql.php');
	include_once(dirname(__FILE__).'/../../util/auth.php');
?>
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN" "http://www.w3.org/TR/html4/loose.dtd">
<HTML>
<HEAD>
	<TITLE>ELBA - Appli Cellules - Messages</TITLE>
	<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
	<link href="../../css/style.css" rel="stylesheet" type="text/css">
</HEAD>
<BODY class="main_body">
<TABLE>
<?php

$sql = 'SELECT DATE_FORMAT(message.message_date, \'%d/%m/%Y %H:%i\') as message_date, message.name, message.description, CONCAT(user.first_name, \' \', user.last_name) AS owner, user.email ' .
	   'FROM message ' .
	   'LEFT OUTER JOIN user ON user.id = message.user_id ' .
	   'WHERE message.site_id IS NULL OR message.site_id = '.mysql_format_to_number($_SESSION['site_id']).' ' .
	   'ORDER BY message.message_date DESC ' .
	   'LIMIT 3';

$result = mysql_select_query($sql);

if($result) {

	while ($row = mysql_fetch_array($result, MYSQL_ASSOC)) {

		echo '<TR>
				<TD width="400">'.$row['message_date'].' - <B>'.$row['name'].'</B></TD>
			  </TR>
			  <TR>
				<TD width="400" height="5"></TD>
			  </TR>
			  <TR>
				<TD width="400">'.str_replace("\n", "<BR>", $row['description']).'</TD>
			  </TR>
			  <TR>
				<TD width="400" height="5"></TD>
			  </TR>
			  <TR>
				<TD width="400">Par : <A HREF=\'mailto:'.$row['email'].'\' TARGET=\'_blank\'>'.$row['owner'].'</A></TD>
			  </TR>
			  <TR>
				<TD width="400" height="10"></TD>
			  </TR>
			  <TR>
				<TD width="400" align="center"><img src="../../image/menu/menu_separator.jpg" border="0"></TD>
			  </TR>
			  <TR>
			  	<TD width="400" height="10"></TD>
			  </TR>';
	}
}
?>
</TABLE>
</BODY>
</HTML>