<?php
	session_start();
	include_once(dirname(__FILE__).'/../../util/auth.php');
	
	if (!is_allowed('admin')) {
		header('Location: ../not_allowed.php');
		exit();
	}
?>	
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN" "http://www.w3.org/TR/html4/loose.dtd">
<HTML>
<HEAD>
	<TITLE>Web Appli Cellules - Administration</TITLE>
	<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
	<link href="../../css/style.css" rel="stylesheet" type="text/css">
</HEAD>
<BODY class="menu_body">
	<CENTER>
	<TABLE class="normal">
		<TR>
			<TD class="menu_title"><a href="main.php" target="main">Administration</A></TD>
		</TR>
		<TR>
			<TD class="max_separator"></TD>
		</TR>
		<TR>
			<TD><a href="main.php" target="main"><img src="../../image/elba_menu.jpg"></a></TD>
		</TR>
		<TR>
			<TD class="max_separator"></TD>
		</TR>		
<?php

if (is_allowed('admin_product') ) {

?>
	<TR>
		<TD class="menu"><a href="../admin_product/index.php" target="main">- Produit -</a></TD>
	</TR>
	<TR>
		<TD class="separator"></TD>
	</TR>
<?php

}

?>
	<TR>
		<TD class="separator"></TD>
	</TR>
<?php

if (is_allowed('admin_machine') ) {

?>
	<TR>
		<TD class="menu"><a href="../admin_machine/index.php" target="main">- Machine -</a></TD>
	</TR>
	<TR>
		<TD class="separator"></TD>
	</TR>
<?php

}

if (is_allowed('admin_cell') ) {

?>
	<TR>
		<TD class="menu"><a href="../admin_cell/index.php" target="main">- Cellule -</a></TD>
	</TR>
	<TR>
		<TD class="separator"></TD>
	</TR>
<?php

}

if (is_allowed('admin_warehouse') ) {

?>
	<TR>
		<TD class="menu"><a href="../admin_warehouse/index.php" target="main">- Entrepot -</a></TD>
	</TR>
	<TR>
		<TD class="separator"></TD>
	</TR>
<?php

}

if (is_allowed('admin_site') ) {

?>
	<TR>
		<TD class="menu"><a href="../admin_site/index.php" target="main">- Site -</a></TD>
	</TR>
	<TR>
		<TD class="separator"></TD>
	</TR>
<?php

}

?>
	<TR>
		<TD class="separator"></TD>
	</TR>
<?php
if (is_allowed('admin_user') ) {

?>
	<TR>
		<TD class="menu"><a href="../admin_user/index.php" target="main">- Utilisateur -</a></TD>
	</TR>
	<TR>
		<TD class="separator"></TD>
	</TR>
<?php

}

if (is_allowed('admin_role') ) {

?>
	<TR>
		<TD class="menu"><a href="../admin_role/index.php" target="main">- Role -</a></TD>
	</TR>
	<TR>
		<TD class="separator"></TD>
	</TR>
<?php

}

if (is_allowed('admin_access') ) {

?>
	<TR>
		<TD class="menu"><a href="../admin_access/index.php" target="main">- Acces -</a></TD>
	</TR>
	<TR>
		<TD class="separator"></TD>
	</TR>
<?php

}

?>
	<TR>
		<TD class="separator"></TD>
	</TR>
<?php

if (is_allowed('error') ) {

?>
	<TR>
		<TD class="menu"><a href="../error/index.php" target="main">- Erreur -</a></TD>
	</TR>
	<TR>
		<TD class="separator"></TD>
	</TR>
<?php

}

if (is_allowed('log') ) {

?>
	<TR>
		<TD class="menu"><a href="../log/index.php" target="main">- Journal -</a></TD>
	</TR>
	<TR>
		<TD class="max_separator"></TD>
	</TR>
<?php

}

if (is_allowed('admin_version') ) {

?>
	<TR>
		<TD class="menu"><a href="../admin_version/index.php" target="main">- Version -</a></TD>
	</TR>
	<TR>
		<TD class="max_separator"></TD>
	</TR>
<?php

}

if (is_allowed('admin_message') ) {

?>
	<TR>
		<TD class="menu"><a href="../admin_message/index.php" target="main">- Message -</a></TD>
	</TR>
	<TR>
		<TD class="max_separator"></TD>
	</TR>
<?php

}

?>
	<TR>
		<TD class="separator"></TD>
	</TR>
	<TR>
		<TD class="menu_title"><a href="../index.php" target="_parent">Retour</a></TD>
	</TR>
	</TABLE>
	</CENTER>
</BODY>
</HTML>
