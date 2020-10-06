<?php
include_once(dirname(__FILE__).'/../config/global.php');
include_once(dirname(__FILE__).'/sql.php');

function crypt_password($password) {

	$characters = 'abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789';

	$first = $characters[rand(0, strlen($characters)-1)];
	$second = $characters[rand(0, strlen($characters)-1)];

	return crypt($password, $first.$second);
}

function get_site() {
	
	if (!isset($_SESSION['site_id']) || $_SESSION['site_id'] == '' || 
		!isset($_SESSION['site_name']) || $_SESSION['site_name'] == '' || 
		!isset($_SESSION['site_trigram']) || $_SESSION['site_trigram'] == '') {
	
		$_SESSION['site_id'] = null;
		$_SESSION['site_trigram'] = null;
		$_SESSION['site_name'] = null;
		
		$sql_query = 'SELECT id, name, ip_address, trigram FROM site ORDER BY name';
		$sql_result = mysql_select_query($sql_query);
		
		if ($sql_result)
		{	
			while ($row = mysql_fetch_array($sql_result, MYSQL_NUM)) {
			
				$sites = split(';', $row[2]);
			
				if (count($sites) > 0)
				{
					foreach ($sites as $site)
					{
						if (strripos($_SERVER['REMOTE_ADDR'], $site) !== false)
						{
							$_SESSION['site_id'] = $row[0];
							$_SESSION['site_name'] = $row[1];
							$_SESSION['site_trigram'] = $row[3];
							return;
						}
					}
				}
			}
		}
	}
}

function get_horizon() {
	if (!isset($_SESSION['horizon'])) {
		$_SESSION['horizon'] = 1;
	}
	
	if (!isset($_SESSION['horizon_bu'])) {
		$_SESSION['horizon_bu'] = 0;
	}
	
	if (!isset($_SESSION['horizon_sub'])) {
		$_SESSION['horizon_sub'] = 1;
	}
	
	if (!isset($_SESSION['horizon_sub_bu'])) {
		$_SESSION['horizon_sub_bu'] = 0;
	}
	
	if ((date("N") == 5) && ($_SESSION['horizon'] < 3)) {
		$_SESSION['horizon'] = 3;
		$_SESSION['horizon_bu'] = 0;
	}
	
	if ((date("N") == 5) && ($_SESSION['horizon_sub'] < 3)) {
		$_SESSION['horizon_sub'] = 3;
		$_SESSION['horizon_sub_bu'] = 0;
	}
}

function add_reference($array, $reference)
{
	$count = 0;
	
	if (isset($array))
	{
		$count = count($array);
	}
	
	if (!in_array($reference, $array))
	{
		$array[$count] = $reference;
	}
	
	//asort($array);
	
	return $array;
}





/**************************************************

					FORMAT

***************************************************/


function format_to_time($str) {

	$value = 'N/A';
	
	if (!is_null($str) && ($str  != ''))
	{
		$str = trim($str);
		
		if (is_numeric($str)) {
		
			$hour = 0;
			$minute = 0;
			$seconde = 0;
			
			$hour = floor($str);
			$minute = round(($str - $hour) * 60);
			
			$value = str_pad($hour, 2, "0", STR_PAD_LEFT).'H'.str_pad($minute, 2, "0", STR_PAD_LEFT);
		}
	} else {
		$value = '';
	}
	
	return $value;
}

function format_to_date($str) {

	$value = 'N/A';
	
	if (!is_null($str) && ($str  != ''))
	{
		$str = trim($str);
		
		if (is_numeric($str)) {
			
			$value = date("d/m/y", $str);
		}
	} else {
		$value = '';
	}
	
	return $value;
}

function format_to_boolean($str) {

	$value = 'N/A';
	
	if (!is_null($str) && ($str  != ''))
	{
		$str = trim($str);
		
		if (is_numeric($str)) {
			
			if ($str == '1') {
				$value =  'oui';
			} else if ($str == '0') {
				$value =  'non';
			}
		}
	} else {
		$value = '';
	}
	
	return $value;
}

function format_to_cross($str) {

	$value = '!';
	
	if (!is_null($str) && ($str  != ''))
	{
		$str = trim($str);
		
		if (is_numeric($str)) {
			
			if ($str == '1') {
				$value =  'X';
			} else if ($str == '0') {
				$value =  '';
			}
		}
	}
	
	return $value;
}


function format_to_currency($str, $currency) {

	return format_to_currency_with_decimal($str, 0, $currency);
}

function format_to_currency_with_decimal($str, $decimal, $currency) {

	$value = 'N/A';
	
	if (!is_null($str) && ($str  != ''))
	{
		$str = trim($str);
		
		if (is_numeric($str)) {
		
			$value = number_format($str, $decimal, ',', ' ');
			
			if (!is_null($currency) && ($currency  != ''))
			{
				$value .= ' '.$currency;
			}
		}
	} else {
		$value = '';
	}
	
	return $value;
}

function format_to_decimal($str, $decimal) {

	$value = 'N/A';
	
	if (!is_null($str) && ($str  != ''))
	{
		$str = trim($str);
		
		if (is_numeric($str)) {
		
			$value = number_format($str, $decimal, ',', ' ');
		}
	} else {
		$value = '0';
	}
	
	return $value;
}


function format_to_number($str) {

	return format_to_decimal($str, 0);
}

function format_to_string($str) {

	return $str;
}

function get_php_self() {

	$value = '';
	$post = '';
	
	foreach ($_REQUEST as $key => $value) {
		if ($key == 'id') {
			$post .= $key.'='.$value.'&';
		}
	}
	
	$value = $_SERVER['PHP_SELF'];
	$value = str_replace('_result', '', $value);
	
	if ($post != '')
	{
		$post = substr($post, 0, strlen($post) - 1);
		$value .= '?'.$post;
	}

	return $value;
}


function start_loading()
{
	echo '<CENTER>
			<div id="loader" class="loader">
				<table border="0" cellpadding="0" cellspacing="0" width="100%">
					<tr>
						<td class="separator"></td>
					</tr>
					<tr>
						<td>
							<TABLE>
								<TR>
									<TD><img title="Chargement en cours.." width="32" height="32" src="../../image/menu/loading.gif"></TD>
									<TD class="field_separator"></TD>
									<TD><B>Veuillez patienter.<BR>Chargement en cours...</B></TD>
								</TR>
							</TABLE>
						</td>
					</tr>
					<tr>
						<td class="separator"></td>
					</tr>
				</table>
			</div>
		</CENTER>
	<script type="text/javascript">start_loading();
	'.str_pad('', 4096).'
	</script>';
	
	flush();
}



function stop_loading()
{
	echo '<script type="text/javascript">stop_loading();</script>';
}


function format_to_reference($str) {

	return '="'.$str.'"';
}

?>
