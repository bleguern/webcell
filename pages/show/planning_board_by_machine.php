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

function get_planning_board_machine_list() {
	
	if ((isset($_REQUEST['id'])) && 
		($_REQUEST['id'] != '')) {

?>
			<TABLE class="normal">
				<TR>
					<TD align="center">
						<TABLE class="normal">
							<TR>
								<TD class="main_image"><A HREF="../print/planning_board_by_machine.php?id=<?php echo $_REQUEST['id']; ?>" TARGET="_self"><img src="../../image/printer_little.jpg" ALT="Impression du planning board : machine <?php echo $_REQUEST['machine_name']; ?>"></A></TD>
								<TD class="low_separator">&nbsp;</TD>
								<TD class="main_image"><A HREF="../export/planning_board_by_machine.php?id=<?php echo $_REQUEST['id']; ?>" TARGET="_self"><img src="../../image/excel_little.jpg" ALT="Exportation Excel du planning board : machine <?php echo $_REQUEST['machine_name']; ?>"></A></TD>
								<TD class="low_separator">&nbsp;</TD>
								<TD class="main_title_text">Planning board : machine <?php echo $_REQUEST['machine_name']; ?></TD>
							</TR>
						</TABLE>
					</TD>
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
										<TR>
											<TD class="header" width="230"></TD>
											<TD class="header" width="145">CDC</TD>
											<TD class="header" width="145">BU</TD>
											<TD class="header" width="190">TOTAL</TD>
											<TD class="header" width="50"></TD>
											<TD width="20"></TD>
										</TR>
									</table>
								</TD>
							</TR>
							<TR>
								<TD><?php
		
$columns = array(array('Ref.', 70, 'link', 'left', '', 'product_information.php', 13, '_parent', false, false, 'R&eacute;f&eacute;rence produit'),
				 array('D&eacute;signation', 160, 'data', 'left', '', '', 0, '', false, false, 'D&eacute;signation produit'),
				 array('Stk.', 45, 'number', 'right', '', '', 0, '', false, false, 'Quantit&eacute; en stock CDC'),
				 array('Cdes', 45, 'number', 'right', '', '', 0, '', false, false, 'Quantit&eacute; command&eacute;e CDC'),
				 array('Rupt.', 55, 'date', 'center', '', '', 0, '', false, false, 'Quantit&eacute; en rupture CDC'),
				 array('Stk.', 45, 'number', 'right', '', '', 0, '', false, false, 'Quantit&eacute; en stock BU'),
				 array('Cdes', 45, 'number', 'right', '', '', 0, '', false, false, 'Quantit&eacute; command&eacute;e BU'),
				 array('Rupt.', 55, 'date', 'center', '', '', 0, '', false, false, 'Quantit&eacute; en rupture BU'),
				 array('Rupt.', 45, 'number', 'right', '', '', 0, '', false, false, 'Quantit&eacute; totale en rupture'),
				 array('Mini', 45, 'number', 'right', '', '', 0, '', false, false, 'Stock minimum'),
				 array('Prod.', 50, 'number', 'right', 'bold', '', 0, '', true, false, 'Quantit&eacute; &agrave; produire'),
				 array('Tps.', 50, 'time', 'right', '', '', 0, '', true, false, 'Temps machine'),
				 array('Cmts.', 50, 'data', 'left', '', '', 0, '', false, false, 'Commentaires'));
		 
get_table_head($columns);
		
								?></TD>
							</TR>
							<TR>
								<TD><iframe src="planning_board_by_machine_result.php<?php 

echo '?history='.get_php_self().'&id='.$_REQUEST['id'];

if (isset($_REQUEST['order']))
{
	echo '&order='.$_REQUEST['order'];
	
	if (isset($_REQUEST['sort']))
	{
		echo '&sort='.$_REQUEST['sort'];
	}
}
?>" width="790" height="280" name="result" frameborder="0" marginwidth="0" marginheight="0"></iframe></TD>
				</TR>
			</TABLE>
<?php
	}
}

	if (isset($_REQUEST['id']) && ($_REQUEST['id'] != '')) {
		$_REQUEST['machine_name'] = mysql_simple_select_query('SELECT name FROM machine WHERE id = '.mysql_format_to_number($_REQUEST['id']));
	}
?>
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN" "http://www.w3.org/TR/html4/loose.dtd">
<html>
<head>
	<title>ELBA - Appli Cellules - Planning board par machine</title>
	<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
	<link href="../../css/style.css" rel="stylesheet" type="text/css">
	<SCRIPT type='text/javascript' src='../../scripts/main.js'></SCRIPT>
	<?php mysql_build_javascript_table('SELECT id, name FROM cell WHERE cell.site_id = '.mysql_format_to_number($_SESSION['site_id']).' ORDER BY name;
		  SELECT id, name, cell_id FROM machine WHERE machine.site_id = '.mysql_format_to_number($_SESSION['site_id']).' AND machine.main_machine_id IS NULL AND machine.active = 1 ORDER BY name',
		  'cell;
		  machine'); ?>
</HEAD>
<BODY class="main_body">
<FORM NAME='planningBoardMachineForm' ACTION='planning_board_by_machine.php' METHOD='POST'>
<TABLE class="normal">
	<TR>
		<TD class="main_title_text">Planning board par machine</TD>
	</TR>
	<tr>
		<td class="main_title"><img src="../../image/menu/separator.jpg" border="0"></td>
	</tr>
	<TR>
		<TD class="separator"></TD>
	</TR>
	<TR>
		<TD align="center">
			<TABLE class="center">
				<TR>
					<TD class="field_headling">cellule :</TD>
					<TD class="field_separator"></TD>
					<TD class="field_value">
						<INPUT NAME='cell' TYPE='hidden' VALUE='<?php if (isset($_REQUEST['cell'])) { echo $_REQUEST['cell']; } ?>'>
						<SELECT NAME='cell_select' onChange='javascript:planningBoardMachineFormOnCellChange()'></SELECT>
					</TD>
					<TD class="field_separator"></TD>
					<TD class="field_headling">machine :</TD>
					<TD class="field_separator"></TD>
					<TD class="field_value">
						<INPUT NAME='id' TYPE='hidden' VALUE='<?php if (isset($_REQUEST['id'])) { echo $_REQUEST['id']; } ?>'>
						<INPUT NAME='machine_name' TYPE='hidden' VALUE='<?php if (isset($_REQUEST['machine_name'])) { echo $_REQUEST['machine_name']; } ?>'>
						<SELECT NAME='machine_select' onChange='javascript:planningBoardMachineFormOnMachineChange()'></SELECT>
					</TD>
				</TR>
			</TABLE>
		</TD>
	</TR>
	<TR>
		<TD class="separator"></TD>
	</TR>
</TABLE>
<?php

if ((isset($_REQUEST['id'])) && ($_REQUEST['id'] != '')) {
	get_planning_board_machine_list();
}
?>
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
	planningBoardMachineFormFill()
</SCRIPT>
</FORM>
</BODY>
</HTML>