<?php

///////////////////////////////////////////////

// Mode débogage
$global['debug'] = true;     // false

// Mode débogage
$global['echo'] = true;     // false

///////////////////////////////////////////////

// Serveur MySQL
$mysql['host'] = 'localhost';

// Nom de la base MySQL
$mysql['database'] = 'web_cell';

// Nom d'utilisateur
$mysql['user_name'] = 'web_cell';
// Mot de passe utilisateur
$mysql['user_password'] = 'Oi9u7str55y';

///////////////////////////////////////////////

// Nom du dossier du fichier de priorites
$file['priorities_folder'] = '/var/www/html/webcell/up/';

// Nom des fichiers
$file['priorites_name'] = 'PRIORITES.DAT';
	
	
//////////////////////////////////////////////////////////////////////////////////
///////// FONCTION SQL ///////////////////////////////////////////////////////////
//////////////////////////////////////////////////////////////////////////////////

function mysql_simple_select_query($sql_query) {

	global $mysql;
	global $global;

	if(mysql_connect($mysql['host'], $mysql['user_name'], $mysql['user_password'], true)) {

	    if(mysql_select_db($mysql['database'])) {
			$result = mysql_query($sql_query);
			$result = mysql_fetch_row($result);

			if($result) {
				return $result[0];
			} else {
				if ((mysql_errno() != 0)  && $global['debug']) {
					echo '###ERROR_MYSQL : '.mysql_errno().' : '.mysql_error().' : '.$sql_query.'<BR>';
				}
			}
		} else {
			if ($global['debug']) {
				echo '###ERROR_MYSQL : '.mysql_errno().' : '.mysql_error().'<BR>';
			}
		}
	} else {
		if ($global['debug']) {
			echo '###ERROR_MYSQL : '.mysql_errno().' : '.mysql_error().'<BR>';
		}
	}

	return NULL;
}

function mysql_select_query($sql_query) {

	global $mysql;
	global $global;

	if(mysql_connect($mysql['host'], $mysql['user_name'], $mysql['user_password'], true)) {

	    if(mysql_select_db($mysql['database'])) {
			$result = mysql_query($sql_query);

			if ($result) {
				if (mysql_num_rows($result) > 0) {
					return $result;
				}
			} else {
				if ((mysql_errno() != 0)  && $global['debug']) {
					echo '###ERROR_MYSQL : '.mysql_errno().' : '.mysql_error().' : '.$sql_query.'<BR>';
				}
			}
		} else {
			if ($global['debug']) {
				echo '###ERROR_MYSQL : '.mysql_errno().' : '.mysql_error().'<BR>';
			}
		}
	} else {
		if ($global['debug']) {
			echo '###ERROR_MYSQL : '.mysql_errno().' : '.mysql_error().'<BR>';
		}
	}

	return NULL;
}


function mysql_insert_query($sql_query) {

	global $mysql;
	global $global;
	
	$success = false;

	if(mysql_connect($mysql['host'], $mysql['user_name'], $mysql['user_password'], true)) {

	    if(mysql_select_db($mysql['database'])) {

			if(mysql_query($sql_query) && (mysql_affected_rows() >= 1)) {
				$success = true;
			} else {
				if ((mysql_errno() != 0)  && $global['debug']) {
					echo '###ERROR_MYSQL : '.mysql_errno().' : '.mysql_error().' : '.$sql_query.'<BR>';
				}
			}
		} else {
			if ($global['debug']) {
				echo '###ERROR_MYSQL : '.mysql_errno().' : '.mysql_error().'<BR>';
			}
		}
	} else {
		if ($global['debug']) {
			echo '###ERROR_MYSQL : '.mysql_errno().' : '.mysql_error().'<BR>';
		}
	}

	return $success;
}

function mysql_insert_queries($sql_queries) {

	global $mysql;
	global $global;
	
	$sql_queries = split(';', $sql_queries);

	if(mysql_connect($mysql['host'], $mysql['user_name'], $mysql['user_password'], true)) {
	    if(mysql_select_db($mysql['database'])) {
			foreach ($sql_queries as $sql_query)
			{
				$sql_query = trim($sql_query);
				
				if ($sql_query != '')
				{
					if(!mysql_query($sql_query) || (mysql_affected_rows() == 0)) {
						if ((mysql_errno() != 0)  && $global['debug']) {
							echo '###ERROR_MYSQL : '.mysql_errno().' : '.mysql_error().' : '.$sql_query.'<BR>';
						}
					}
				}
			}
		} else {
			if ($global['debug']) {
				echo '###ERROR_MYSQL : '.mysql_errno().' : '.mysql_error().'<BR>';
			}
		}
	} else {
		if ($global['debug']) {
			echo '###ERROR_MYSQL : '.mysql_errno().' : '.mysql_error().'<BR>';
		}
	}
}

function mysql_update_query($sql_query) {

	global $mysql;
	global $global;

	if(mysql_connect($mysql['host'], $mysql['user_name'], $mysql['user_password'], true)) {
	    if(mysql_select_db($mysql['database'])) {
			if (mysql_query($sql_query)) {
				return true;
			} else {
				if ((mysql_errno() != 0)  && $global['debug']) {
					echo '###ERROR_MYSQL : '.mysql_errno().' : '.mysql_error().' : '.$sql_query.'<BR>';
				}
			}
		} else {
			if ($global['debug']) {
				echo '###ERROR_MYSQL : '.mysql_errno().' : '.mysql_error().'<BR>';
			}
		}
	} else {
		if ($global['debug']) {
			echo '###ERROR_MYSQL : '.mysql_errno().' : '.mysql_error().'<BR>';
		}
	}

	return false;
}

function mysql_update_queries($sql_queries) {

	global $mysql;
	global $global;

	$sql_queries = split(';', $sql_queries);
	
	if(mysql_connect($mysql['host'], $mysql['user_name'], $mysql['user_password'], true)) {
	    if(mysql_select_db($mysql['database'])) {
			
			foreach ($sql_queries as $sql_query)
			{
				$sql_query = trim($sql_query);
				
				if ($sql_query != '') {
					if(!mysql_query($sql_query)) {
						if ((mysql_errno() != 0)  && $global['debug']) {
							echo '###ERROR_MYSQL : '.mysql_errno().' : '.mysql_error().' : '.$sql_query.'<BR>';
						}
					}
				}
			}
		} else {
			if ($global['debug']) {
				echo '###ERROR_MYSQL : '.mysql_errno().' : '.mysql_error().'<BR>';
			}
		}
	} else {
		if ($global['debug']) {
			echo '###ERROR_MYSQL : '.mysql_errno().' : '.mysql_error().'<BR>';
		}
	}
}

function mysql_delete_query($sql_query, $must_be_deleted = 0) {

	global $mysql;
	global $global;

	if(mysql_connect($mysql['host'], $mysql['user_name'], $mysql['user_password'], true)) {

	    if(mysql_select_db($mysql['database'])) {

			if(mysql_query($sql_query)) {

				if ($must_be_deleted && (mysql_affected_rows() == 0)) {
					return FALSE;
				}

				return TRUE;
			} else {
				if ((mysql_errno() != 0)  && $global['debug']) {
					echo '###ERROR_MYSQL : '.mysql_errno().' : '.mysql_error().' : '.$sql_query.'<BR>';
				}
			}
		} else {
			if ($global['debug']) {
				echo '###ERROR_MYSQL : '.mysql_errno().' : '.mysql_error().'<BR>';
			}
		}
	} else {
		if ($global['debug']) {
			echo '###ERROR_MYSQL : '.mysql_errno().' : '.mysql_error().'<BR>';
		}
	}

	return FALSE;
}

function mysql_delete_queries($sql_queries) {

	global $mysql;
	global $global;

	$sql_queries = split(';', $sql_queries);
	
	if(mysql_connect($mysql['host'], $mysql['user_name'], $mysql['user_password'], true)) {
	    if(mysql_select_db($mysql['database'])) {
			
			foreach ($sql_queries as $sql_query)
			{
				$sql_query = trim($sql_query);
				
				if ($sql_query != '') {
					if(!mysql_query($sql_query)) {
						if ((mysql_errno() != 0)  && $global['debug']) {
							echo '###ERROR_MYSQL : '.mysql_errno().' : '.mysql_error().' : '.$sql_query.'<BR>';
						}
					}
				}
			}
		} else {
			if ($global['debug']) {
				echo '###ERROR_MYSQL : '.mysql_errno().' : '.mysql_error().'<BR>';
			}
		}
	} else {
		if ($global['debug']) {
			echo '###ERROR_MYSQL : '.mysql_errno().' : '.mysql_error().'<BR>';
		}
	}
}

function mysql_count_query($sql_query) {

	global $mysql;
	global $global;
	$count = 0;

	if(mysql_connect($mysql['host'], $mysql['user_name'], $mysql['user_password'], true)) {
	    if(mysql_select_db($mysql['database'])) {




			$result = mysql_query($sql_query);

			if ($result) {
				$count = mysql_num_rows($result);
			} else {
				if ((mysql_errno() != 0)  && $global['debug']) {
					echo '###ERROR_MYSQL : '.mysql_errno().' : '.mysql_error().' : '.$sql_query.'<BR>';
				}
			}
		} else {
			if ($global['debug']) {
				echo '###ERROR_MYSQL : '.mysql_errno().' : '.mysql_error().'<BR>';
			}
		}
	} else {
		if ($global['debug']) {
			echo '###ERROR_MYSQL : '.mysql_errno().' : '.mysql_error().'<BR>';
		}
	}

	return $count;
}

function mysql_format_to_number($value) {
	$value = trim($value);
	
	if (($value == '') || (!is_numeric($value))) {
		return 'NULL';
	} else {
		return $value;
	}
}

function mysql_format_to_string($value) {
	$value = trim($value);
	
	if ($value == '') {
		return 'NULL';
	} else {
		return '\''.mysql_escape_string($value).'\'';
	}
}

function mysql_format_to_date($value) {
	$value = trim($value);
	
	if ($value == '') {
		return 'NULL';
	} else {
		return 'STR_TO_DATE(\''.$value.'\', \'%d/%m/%Y\')';
	}
}

function mysql_format_value_to_date($value, $format) {
	$value = trim($value);
	
	if ($value == '') {
		return 'NULL';
	} else {
		return 'STR_TO_DATE(\''.$value.'\', \''.$format.'\')';
	}
}

function mysql_format_unixtime_to_date($value) {
	$value = trim($value);
	
	if ($value == '') {
		return 'NULL';
	} else {
		return 'FROM_UNIXTIME('.$value.')';
	}
}

function mysql_format($value) {
	return mysql_escape_string($value);
}


//////////////////////////////////////////////////////////////////////////////
//////////////////////////////////////////////////////////////////////////////


function import_priorities()
{
	global $file;
	global $global;
	
	if ($global['echo']) {
		echo '<HTML>#'.date("G:i:s").'###PRIORITES.DAT_FILE_IMPORT_STARTS<BR>';
	}
	
	// PRIORITIES.DAT file format
	$file_format = array(array(3, 'string'),         // 0 : Network
						 array(8, 'number'),         // 1 : Customer code
						 array(2, 'string'),         // 2 : Filliale
						 array(1, 'string'),         // 3 : Mode
						 array(1, 'string'));        // 4 : Priority
	
	$size = exec('ls -l '.$file['priorities_folder'].$file['priorites_name'].' | awk \'{print $5}\'');
	
	if (!is_numeric($size))
	{
		echo '#'.date("G:i:s").'###ERROR_INCORRECT_FILE : '.$file['priorities_folder'].$file['priorites_name'].'<BR><BR>';
		return -1;
	}
	
	exec('cmp -s '.$file['priorities_folder'].$file['priorites_name'].' '.$file['priorities_folder'].$file['priorites_name'].'.BAK', $cmp_result, $cmp);
	
	if ($cmp == 0)
	{
		echo '#'.date("G:i:s").'###SAME_FILE...<BR><BR>';
		return 1;
	}
	else
	{
		exec('rm -f '.$file['priorities_folder'].$file['priorites_name'].'.BAK');
		exec('cp '.$file['priorities_folder'].$file['priorites_name'].' '.$file['priorities_folder'].$file['priorites_name'].'.BAK');
	}
	
	
	if ($global['echo']) {
		echo '#'.date("G:i:s").'##LOADING_CUSTOMERS_FROM_FILE... ';
	}
	
	$customer_file_data = null;
	$i = 0;
	
	// Fill product file data table
	$fs = fopen($file['priorities_folder'].$file['priorites_name'], 'r');
	
	if ($fs) {
		while (!feof($fs)) {
		
			$buffer = fgets($fs);
			
			$j = 0;
			$len = 0;
			
			if (strlen($buffer) > $file_format[0][0])
			{
				for($j = 0; $j < count($file_format); $j++) {
				
					$str = substr($buffer, $len, $file_format[$j][0]);
					$str = trim($str);
					
					if ($j == 0 && $str == '') {
						$i--;
						break;
					} else {
						if ($file_format[$j][1] == 'number') {
							$customer_file_data[$i][$j] = intval($str);
						} else if ($file_format[$j][1] == 'decimal') {
							$customer_file_data[$i][$j] = floatval(str_replace(',', '.', $str));
						} else {
							$customer_file_data[$i][$j] = $str;
						}
						
						$len += $file_format[$j][0];
					}
				}
				
				$i++;
			}
	   	}
	   	
		fclose($fs);
	}
	
	if ($global['echo']) {
		echo $i.'_LINES<BR>#'.date("G:i:s").'##LOADING_CUSTOMERS_FROM_DATABASE... ';
	}
	
	$customer_database_data = null;
	$i = 0;
	
	$sql_query = 'SELECT id, ' .
				 'number, ' .
				 'priority ' .
				 'FROM customer ' .
				 'ORDER BY number';
	
	$result = mysql_select_query($sql_query);

	if ($result) {
		$i = 0;

		while ($row = mysql_fetch_array($result, MYSQL_NUM)) {
			$customer_database_data[$i][0] = $row[0];                                      // ID
			$customer_database_data[$i][1] = $row[1];                                      // Number
			$customer_database_data[$i][2] = $row[2];                                      // Priority

			$i++;
		}
	}
	
	if ($global['echo']) {
		echo $i.'_CUSTOMERS<BR>#'.date("G:i:s").'##COMPUTING_CUSTOMERS_TO_UPDATE... ';
	}
	
	foreach ($customer_file_data as $key => $row) {
		$number[$key]  = $row[1];
	}
	
	array_multisort($number, SORT_ASC, $customer_file_data);
	
	$new_customer_file_data = null;
	$count = 0;
	
	$last_customer = '';
	$last_priority = '';
	
	if ($customer_file_data != null) {
		for ($i = 0; $i < count($customer_file_data); $i++) {
			
			if ($i == 0)
			{
				$last_customer = $customer_file_data[0][1];
				$last_priority = $customer_file_data[0][4];
				
				$new_customer_file_data[0] = $customer_file_data[0];
				$count++;
			}
			else
			{
				if ($last_customer == $customer_file_data[$i][1])
				{
					if ($customer_file_data[$i][4] < $last_priority)
					{
						$new_customer_file_data[$count - 1][4] = $customer_file_data[$i][4];
					}
				}
				else
				{
					$new_customer_file_data[$count] = $customer_file_data[$i];
					$count++;
				}
				
				$last_customer = $customer_file_data[$i][1];
				$last_priority = $customer_file_data[$i][4];
			}
		}
	}
	
	
	$customer_file_data = $new_customer_file_data;
	
	$to_update = 0;
	$update_queries = '';
	
	/* Test if an customer already exists :            */
	/* If : update                                   */
	if ($customer_file_data != null) {
		for ($i = 0; $i < count($customer_file_data); $i++) {
			if (isset($customer_database_data)) {
				for ($j = 0; $j < count($customer_database_data); $j++) {
					
					if ($customer_file_data[$i][1] == $customer_database_data[$j][1]) {
						if ($customer_file_data[$i][4] != $customer_database_data[$j][2]) {
							
							$sql_query = 'UPDATE customer ' .
										 'SET priority = '.mysql_format_to_number($customer_file_data[$i][4]).' ' .
										 'WHERE id = '.mysql_format_to_number($customer_database_data[$j][0]).' LIMIT 1;';
							
							$update_queries .= $sql_query;
							$to_update++;
						}
						
						break;
					}
				}
			}
		}
	}
	
	if ($global['echo']) {
		echo $to_update.'_CUSTOMERS_TO_UPDATE<BR>';
	}
	
	if ($update_queries != '') {
		if ($global['echo']) {
			echo '#'.date("G:i:s").'##UPDATING_CUSTOMERS...<BR>';
		}
		
		echo str_replace(';', '<BR>', $update_queries);
		mysql_update_queries($update_queries);
		$update_queries = '';
		
		if ($global['echo']) {
			echo 'OK<BR>';
		}
	}
	
	if ($global['echo']) {
		echo '#'.date("G:i:s").'###PRIORITIES.DAT_FILE_IMPORT_ENDS!<BR><BR></HTML>';
	}
	
	return 0;
}


import_priorities();

?>