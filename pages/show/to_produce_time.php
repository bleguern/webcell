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
	<title>ELBA - Appli Cellules - Manque &agrave; produire</title>
	<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
	<link href="../../css/style.css" rel="stylesheet" type="text/css">
	<SCRIPT type='text/javascript' src='../../scripts/main.js'></SCRIPT>
	<?php mysql_build_javascript_table('SELECT id, trigram FROM site WHERE id <> 100 ORDER BY trigram;
SELECT id, name, site_id FROM cell ORDER BY name;
SELECT id, name, site_id, cell_id FROM machine WHERE machine.active = 1 ORDER BY name',
'site;
cell;
machine'); ?>
</HEAD>
<BODY class="main_body">
<FORM NAME='toProduceForm' ACTION='to_produce_time.php' METHOD='POST'>
<TABLE class="normal">
	<TR>
		<TD align="center">
			<TABLE class="normal">
				<TR>
					<TD class="main_image"><A HREF="../print/to_produce_time.php" TARGET="_self"><img src="../../image/printer_little.jpg" ALT="Impression du manque a produire"></A></TD>
					<TD class="field_separator"></TD>
				    <TD class="main_image"><A HREF="../export/to_produce_time.php" TARGET="_self"><img src="../../image/excel_little.jpg" alt="Exportation Excel du manque a produire"></A></TD>
					<TD class="field_separator"></TD>
				    <TD class="main_title_text">Manque &agrave; produire <input type="text" name="count" value="..." class="count"></TD>
					<TD class="field_separator"></TD>
					<TD class="main_bottom_text" width="150"><A HREF="to_produce_time.php" target="_self">Mettre &agrave; jour</A></TD>
				</TR>
			</TABLE>
		</TD>
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
			<TABLE>
				<TR>
					<TD>
						<TABLE>
<?php

$script = '<TR><INPUT NAME=\'site\' TYPE=\'hidden\' VALUE=\'';
					  
if (isset($_REQUEST["site"])) {
	$script .= $_REQUEST["site"];
} else {
	$script .= $_SESSION['site_id'];
}

$script .= '\'>';
	
if ($_SESSION['site_id'] == 100) {
	$script .= '<TD class="header" width="50"><SELECT NAME="site_select" STYLE="width:50" onChange="javascript:toProduceFormOnSiteChange()"></SELECT></TD>';
}
		
$script .= '<TD class="header" width="80"><INPUT NAME=\'cell\' TYPE=\'hidden\' VALUE=\'';

if (isset($_REQUEST["cell"])) {
	$script .= $_REQUEST["cell"];
}

$script .= '\'><SELECT NAME="cell_select" STYLE="width:80" onChange="javascript:toProduceFormOnCellChange()"></SELECT></TD>
			<TD class="header" width="80"><INPUT NAME=\'machine\' TYPE=\'hidden\' VALUE=\'';

if (isset($_REQUEST["machine"])) {
	$script .= $_REQUEST["machine"];
}

if ($_SESSION['site_id'] == 100) {

$script .= '\'><SELECT NAME="machine_select" STYLE="width:80" onChange="javascript:toProduceFormOnMachineChange()"></SELECT></TD>
	  <TD class="header" colspan="2" width="199"></TD>
	  <TD class="header" colspan="3" width="120">CDC</TD>
	  <TD class="header" colspan="3" width="120">BU</TD>
	  <TD class="header" colspan="3" width="120">TOTAL</TD>
	</TR>';
	
} else {

$script .= '\'><SELECT NAME="machine_select" STYLE="width:80" onChange="javascript:toProduceFormOnMachineChange()"></SELECT></TD>
	  <TD class="header" colspan="2" width="229"></TD>
	  <TD class="header" colspan="3" width="120">CDC</TD>
	  <TD class="header" colspan="3" width="120">BU</TD>
	  <TD class="header" colspan="3" width="135">TOTAL</TD>
	</TR>';

}

echo $script;
?>
						</table>
					</TD>
				</TR>
				<TR>
					<TD><?php
					
if ($_SESSION['site_id'] == 100) {
	$columns = array(array('Site', 50, 'data', 'left', '', '', 0, '', false, true, 'Site de production'),
					 array('Cellule', 80, 'link', 'left', '', 'cell_information.php', 14, '_parent', false, true, 'Cellule de production'),
					 array('Machine', 80, 'link', 'left', '', 'machine_information.php', 15, '_parent', false, true, 'Machine de production'),
					 array('Ref.', 70, 'link', 'left', '', 'product_information.php', 16, '_parent', false, false, 'R&eacute;f&eacute;rence produit'),
					 array('D&eacute;signation', 129, 'data', 'left', '', '', 0, '', false, false, 'D&eacute;signation produit'),
					 array('Stock', 40, 'number', 'right', '', '', 0, '', false, false, 'Stock CDC'),
					 array('Cdes', 40, 'number', 'right', '', '', 0, '', false, false, 'Quantit&eacute; command&eacute;e CDC'),
					 array('Manq.', 40, 'number', 'right', 'bold', '', 0, '', false, false, 'Manque &agrave; produire CDC'),
					 array('Stock', 40, 'number', 'right', '', '', 0, '', false, false, 'Stock BU'),
					 array('Cdes', 40, 'number', 'right', '', '', 0, '', false, false, 'Quantit&eacute; command&eacute;e BU'),
					 array('Manq.', 40, 'number', 'right', 'bold', '', 0, '', false, false, 'Manque &agrave; produire BU'),
					 array('Manq.', 40, 'number', 'right', 'bold', '', 0, '', true, false, 'Manque &agrave; produire total'),
					 array('Valo.', 40, 'currency', 'right', '', '', 0, '', true, false, 'Valorisation'),
					 array('Tmps.', 40, 'time', 'right', '', '', 0, '', true, false, 'Temps de production'));
} else {
	$columns = array(array('Cellule', 80, 'link', 'left', '', 'cell_information.php', 13, '_parent', false, true, 'Cellule de production'),
					 array('Machine', 80, 'link', 'left', '', 'machine_information.php', 14, '_parent', false, true, 'Machine de production'),
					 array('Ref.', 70, 'link', 'left', '', 'product_information.php', 15, '_parent', false, false, 'R&eacute;f&eacute;rence produit'),
					 array('D&eacute;signation', 159, 'data', 'left', '', '', 0, '', false, false, 'D&eacute;signation produit'),
					 array('Stock', 40, 'number', 'right', '', '', 0, '', false, false, 'Stock CDC'),
					 array('Cdes', 40, 'number', 'right', '', '', 0, '', false, false, 'Quantit&eacute; command&eacute;e CDC'),
					 array('Manq.', 40, 'number', 'right', 'bold', '', 0, '', false, false, 'Manque &agrave; produire CDC'),
					 array('Stock', 40, 'number', 'right', '', '', 0, '', false, false, 'Stock BU'),
					 array('Cdes', 40, 'number', 'right', '', '', 0, '', false, false, 'Quantit&eacute; command&eacute;e BU'),
					 array('Manq.', 40, 'number', 'right', 'bold', '', 0, '', false, false, 'Manque &agrave; produire BU'),
					 array('Manq.', 45, 'number', 'right', 'bold', '', 0, '', true, false, 'Manque &agrave; produire total'),
					 array('Valo.', 45, 'currency', 'right', '', '', 0, '', true, false, 'Valorisation'),
					 array('Tmps.', 45, 'time', 'right', '', '', 0, '', true, false, 'Temps de production'));
}
			   
get_table_head($columns);
						  ?></TD>
			</TR>
</TABLE>
<TABLE>
	<TR>
		<TD><iframe src="to_produce_time_result.php<?php 

echo '?history='.get_php_self();

if (isset($_REQUEST['site']) && ($_REQUEST['site'] != ''))
{
	echo '&site='.$_REQUEST['site'];
}

if (isset($_REQUEST['cell']) && ($_REQUEST['cell'] != ''))
{
	echo '&cell='.$_REQUEST['cell'];
}

if (isset($_REQUEST['machine']) && ($_REQUEST['machine'] != ''))
{
	echo '&machine='.$_REQUEST['machine'];
}
		
if (isset($_REQUEST['order']))
{
	echo '&order='.$_REQUEST['order'];
}

if (isset($_REQUEST['sort']))
{
	echo '&sort='.$_REQUEST['sort'];
}

?>" width="790" height="335" name="result" frameborder="0" marginwidth="0" marginheight="0"></iframe></TD>
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
<SCRIPT type="text/javascript">
	toProduceFormFill()
</SCRIPT>
</FORM>
</BODY>
</HTML>