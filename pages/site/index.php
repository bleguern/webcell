<?php
	session_start();
	include_once(dirname(__FILE__).'/../../util/sql.php');
	include_once(dirname(__FILE__).'/../../util/auth.php');
	
	if (!is_allowed('site')) {
		header('Location: ../not_allowed.php');
		exit();
	}
	
	if (!isset($_REQUEST['history']) || ($_REQUEST['history'] == '')) {
		$_REQUEST['history'] = '/webcell/pages/main.php';
	}

	
	if (isset($_POST['site']) || isset($_POST['site_x'])) {
		select_site();
	}

function select_site() {
	if ($_REQUEST['site_id'] == '') {
		$_REQUEST['message'] = 'Attention : veuillez s&eacute;lectionner un site!';
	} else {

		$_SESSION['site_id'] = $_REQUEST['site_id'];
		$_SESSION['site_name'] = $_REQUEST['site_name'];
		
		$sql_query = 'SELECT name, trigram FROM site WHERE id = '.mysql_format_to_number($_REQUEST['site_id']);
		$result = mysql_select_query($sql_query);
		
		if ($result)
		{	
			$row = mysql_fetch_array($result, MYSQL_NUM);
			
			$_SESSION['site_id'] = $_REQUEST['site_id'];
			$_SESSION['site_name'] = $row[0];
			$_SESSION['site_trigram'] = $row[1];
		}
		
		echo '<SCRIPT type=\'text/javascript\'>' .
			 'window.parent.location.reload()' .
			 '</SCRIPT>';
	}
}

	if (isset($_SESSION['site_id']) && $_SESSION['site_id'] != '') {
		$_REQUEST['site_id'] = $_SESSION['site_id'];
	}
	
	if (isset($_SESSION['site_name']) && $_SESSION['site_name'] != '') {
		$_REQUEST['site_name'] = $_SESSION['site_name'];
	}

?>

<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN" "http://www.w3.org/TR/html4/loose.dtd">
<HTML>
<HEAD>
	<TITLE>ELBA - Appli Cellules - Site</TITLE>
	<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
	<link href="../../css/style.css" rel="stylesheet" type="text/css">
	<SCRIPT type='text/javascript' src='../../scripts/main.js'></SCRIPT>
	<?php mysql_build_javascript_table('SELECT id, name FROM site ORDER BY name', 'site'); ?>
</HEAD>
<BODY class="main_body">
<FORM NAME="siteForm" ACTION='index.php' METHOD='POST'>
<center>
<TABLE>
	<TR>
		<TD class="main_title_text">Site</TD>
	</TR>
	<tr>
		<td class="main_title"><img src="../../image/menu/separator.jpg" border="0"></td>
	</tr>
	<TR>
		<TD class="separator"></TD>
	</TR>
	<TR>
		<TD align="center">
			<TABLE>
				<TR>
					<TD align="center">
						<TABLE class="center">
							<TR>
								<TD class="field_header">site :</TD>
								<TD class="field_separator"><INPUT TYPE="hidden" NAME="site_id" VALUE="<?php

if(isset($_REQUEST['site_id'])) {
echo $_REQUEST['site_id'];
}

?>"><INPUT TYPE="hidden" NAME="site_name" VALUE="<?php

if(isset($_REQUEST['site_name'])) {
echo $_REQUEST['site_name'];
}

?>"></TD>
								<TD class="field_value"><SELECT NAME='site_select' onChange='javascript:siteFormOnSiteChange()'></SELECT></TD>
							</TR>
						</TABLE>
					</TD>
				</TR>
				<TR>
					<TD class="max_separator"></TD>
				</TR>
				<TR>
				  	<TD align="center"><INPUT TYPE='submit' NAME='site' VALUE='valider' ALT='Site'></TD>
				</TR>
		  	</TABLE>
		</TD>
	</TR>
	<TR>
		<TD class="separator"></TD>
	</TR>
	<TR>
		<TD class="message">
<?php

if(isset($_REQUEST['message'])) {
	echo $_REQUEST['message'];
}

?>
		</TD>
	</TR>
	<TR>
		<TD class="separator"></TD>
	</TR>
	<tr>
		<td class="main_bottom"><img src="../../image/menu/menu_separator.jpg" border="0"></td>
	</tr>
	<TR>
		<TD class="min_separator"></TD>
	</TR>
	<TR>
		<TD class="main_bottom_text"><A HREF="<?php echo $_REQUEST['history']; ?>" target="_self">Retour</A></TD>
	</TR>
	<TR>
		<TD class="min_separator"></TD>
	</TR>
	<tr>
		<td class="main_bottom"><img src="../../image/menu/menu_separator.jpg" border="0"></td>
	</tr>
</TABLE>
</center>
</FORM>
<SCRIPT type="text/javascript">
	siteFormFill()
</SCRIPT>
</BODY>
</HTML>