<?php
	session_start();
	include_once(dirname(__FILE__).'/../../util/auth.php');
	include_once(dirname(__FILE__).'/../../util/sql.php');
	include_once(dirname(__FILE__).'/../../util/util.php');
	
	if (!isset($_SESSION['site_id']) || ($_SESSION['site_id'] == '')) {
		header('Location: ../main.php');
		exit();
	}
	
	if (!isset($_REQUEST['history']) || ($_REQUEST['history'] == '')) {
		$_REQUEST['history'] = '/webcell/pages/main.php';
	}

?>
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN" "http://www.w3.org/TR/html4/loose.dtd">
<html>
<head>
	<title>ELBA - Appli Cellules - Planning board : r&eacute;capitulatif de charge</title>
	<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
	<link href="../../css/style.css" rel="stylesheet" type="text/css">
	<SCRIPT type='text/javascript' src='../../scripts/main.js'></SCRIPT>
</HEAD>
<BODY class="main_body">
<FORM NAME='mainForm' ACTION='planning_board_charge.php' METHOD='POST'>
<TABLE class="normal">
	<TR>
		<TD>
			<TABLE class="normal">
				<TR>
					<TD class="main_image"><A HREF="javascript:print()" TARGET="_self"><img src="../../image/printer_little.jpg" ALT="Imprimer le recapitulatif de charge"></A></TD>
					<TD class="main_title_text">Planning board : r&eacute;capitulatif de charge <?php echo ' : '.$_SESSION['site_name']; ?></TD>
				</TR>
			</TABLE>
		</TD>
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
					<TD><?php
					
$columns = array(array('Cellule', 125, 'data', 'left', '', '', 0, '', false, true, 'Cellule de production'),
				 array('Machine', 125, 'data', 'left', '', '', 0, '', false, false, 'Machine de production'),
				 array('Charge', 250, 'time', 'right', '', '', 0, '', true, false, 'Charge machine'),
				 array('Charge sans stock mini', 250, 'time', 'right', '', '', 0, '', true, false, 'Charge machine sans stock minimum'));
			   
get_table_head($columns);

?></TD>
			</TR>
</TABLE>
<TABLE class="normal">
	<TR>
		<TD><iframe src="planning_board_charge_result.php<?php 

echo '?history='.get_php_self();

if (isset($_REQUEST['order']))
{
	echo '&order='.$_REQUEST['order'];
	
	if (isset($_REQUEST['sort']))
	{
		echo '&sort='.$_REQUEST['sort'];
	}
}
?>" width="790" height="365" name="result" frameborder="0" marginwidth="0" marginheight="0"></iframe></TD>
	</TR>
</TABLE>
<TABLE class="normal">
	<TR>
		<TD class="min_separator"></TD>
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
</FORM>
</BODY>
</HTML>