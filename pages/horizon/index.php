<?php
	session_start();
	include_once(dirname(__FILE__).'/../../util/sql.php');
	include_once(dirname(__FILE__).'/../../util/auth.php');
	
	if (isset($_POST['horizon']) || isset($_POST['horizon_x'])) {
		select_horizon();
	}
	
	if (!isset($_REQUEST['history']) || ($_REQUEST['history'] == '')) {
		$_REQUEST['history'] = '/webcell/pages/main.php';
	}


function select_horizon() {
	if (($_REQUEST['horizon_value'] == '') || ($_REQUEST['horizon_sub_value'] == '')) {
		$_REQUEST['message'] = 'Attention : veuillez s&eacute;lectionner un horizon!';
	} else {
		$_SESSION['horizon'] = $_REQUEST['horizon_value'];
		$_SESSION['horizon_bu'] = $_SESSION['horizon'] - 1;
		$_SESSION['horizon_sub'] = $_REQUEST['horizon_sub_value'];
		$_SESSION['horizon_sub_bu'] = $_SESSION['horizon_sub'] - 1;
		
		if ((date("N") == 5) && ($_SESSION['horizon'] < 3)) {
			$_SESSION['horizon'] = 3;
			$_SESSION['horizon_bu'] = 0;
			$_SESSION['horizon_sub'] = 3;
			$_SESSION['horizon_sub_bu'] = 0;
		}
		
		echo '<SCRIPT type=\'text/javascript\'>' .
			 'window.parent.location.reload()' .
			 '</SCRIPT>';
	}
}
?>
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN" "http://www.w3.org/TR/html4/loose.dtd">
<HTML>
<HEAD>
	<TITLE>ELBA - Appli Cellules - Horizon</TITLE>
	<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
	<link href="../../css/style.css" rel="stylesheet" type="text/css">
	<SCRIPT type='text/javascript' src='../../scripts/main.js'></SCRIPT>
</HEAD>
<BODY class="main_body">
<FORM NAME="horizonForm" ACTION='index.php' METHOD='POST'>
<CENTER>
<TABLE>
	<TR>
		<TD class="main_title_text">Horizon</TD>
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
					<TD align="center">
						<TABLE class="center">
							<TR>
								<TD class="field_header">horizon :</TD>
								<TD class="field_separator"></TD>
								<TD class="field_value"><SELECT NAME='horizon_value'>
			<?php
			
			$horizon  = '';
			$script = '';
			
			if (isset($_SESSION['horizon']) && $_SESSION['horizon'] != '') {
			$horizon = $_SESSION['horizon'];
			}
			
			if ($horizon == '') {
			$script .= '<OPTION VALUE=\'\' SELECTED></OPTION>';
			} else {
			$script .= '<OPTION VALUE=\'\'></OPTION>';
			}
			
			for ($i = 1; $i < 366; $i++) {
			
			$selected = '';
			
			if ($horizon == $i) {
			$selected = ' SELECTED';
			}
			
			if ($i == 1) {
			$script .= '<OPTION VALUE=\''.$i.'\''.$selected.'>1 jour</OPTION>';
			} else if ($i > 1 && $i < 7) {
			$script .= '<OPTION VALUE=\''.$i.'\''.$selected.'>'.$i.' jours</OPTION>';
			} else if ($i == 7) {
			$script .= '<OPTION VALUE=\''.$i.'\''.$selected.'>1 semaine</OPTION>';
			} else if ($i > 6 && $i < 30) {
			if (($i % 7) == 0) {
			$script .= '<OPTION VALUE=\''.$i.'\''.$selected.'>'.($i/7).' semaines</OPTION>';
			}
			} else if ($i == 31) {
			$script .= '<OPTION VALUE=\''.$i.'\''.$selected.'>1 mois</OPTION>';
			} else if ($i > 31 && $i < 365) {
			if (($i % 31) == 0) {
			$script .= '<OPTION VALUE=\''.$i.'\''.$selected.'>'.($i/31).' mois</OPTION>';
			}
			} else if ($i == 365) {
			$script .= '<OPTION VALUE=\''.$i.'\''.$selected.'>1 an</OPTION>';
			}
			}
			
			echo $script;
			
			?>
					
					
						</SELECT></TD>
							</TR>
							<TR>
								<TD class="separator"></TD>
							</TR>
							<TR>
								<TD class="field_header">horizon sous-traitance :</TD>
								<TD class="field_separator"></TD>
								<TD class="field_value"><SELECT NAME='horizon_sub_value'>
			<?php
			
			$horizon_sub  = '';
			$script = '';
			
			if (isset($_SESSION['horizon_sub']) && $_SESSION['horizon_sub'] != '') {
				$horizon_sub = $_SESSION['horizon_sub'];
			}
			
			if ($horizon_sub == '') {
				$script .= '<OPTION VALUE=\'\' SELECTED></OPTION>';
			} else {
				$script .= '<OPTION VALUE=\'\'></OPTION>';
			}
			
			for ($i = 1; $i < 366; $i++) {
			
				$selected = '';
				
				if ($horizon_sub == $i) {
					$selected = ' SELECTED';
				}
				
				if ($i == 1) {
					$script .= '<OPTION VALUE=\''.$i.'\''.$selected.'>1 jour</OPTION>';
				} else if ($i > 1 && $i < 7) {
					$script .= '<OPTION VALUE=\''.$i.'\''.$selected.'>'.$i.' jours</OPTION>';
				} else if ($i == 7) {
					$script .= '<OPTION VALUE=\''.$i.'\''.$selected.'>1 semaine</OPTION>';
				} else if ($i > 6 && $i < 30) {
				
					if (($i % 7) == 0) {
						$script .= '<OPTION VALUE=\''.$i.'\''.$selected.'>'.($i/7).' semaines</OPTION>';
					}
				} else if ($i == 31) {
						$script .= '<OPTION VALUE=\''.$i.'\''.$selected.'>1 mois</OPTION>';
				} else if ($i > 31 && $i < 365) {
					if (($i % 31) == 0) {
						$script .= '<OPTION VALUE=\''.$i.'\''.$selected.'>'.($i/31).' mois</OPTION>';
					}
				} else if ($i == 365) {
					$script .= '<OPTION VALUE=\''.$i.'\''.$selected.'>1 an</OPTION>';
				}
			}
			
			echo $script;
			
			?>
					
					
					</SELECT></TD>
							</TR>
						</TABLE>
					</TD>
				</TR>
				<TR>
					<TD class="max_separator"></TD>
				</TR>
				<TR>
				  	<TD><INPUT TYPE='submit' NAME='horizon' VALUE='valider' ALT='Horizon'></TD>
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
</CENTER>
</FORM>
</BODY>
</HTML>