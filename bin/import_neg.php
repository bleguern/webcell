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

// Nom du dossier des fichiers de Troyes
$file['tre_folder'] = '/var/www/html/webcell/up/tre/';

// Nom du dossier des fichiers de La Monnerie
$file['lam_folder'] = '/var/www/html/webcell/up/lam/';

// Nom du dossier des fichiers de Moult
$file['mlt_folder'] = '/var/www/html/webcell/up/mlt/';

// Nom du dossier des fichiers de Negoce
$file['neg_folder'] = '/var/www/html/webcell/up/neg/';

// Nom des fichiers
$file['dsjour_name']    = 'DSJOUR.DAT';
$file['onhand_name']    = 'ONHAND.DAT';
$file['custords_name']  = 'CUSTORDS.DAT';
$file['receipts_name']  = 'RECEIPTS.DAT';
$file['priorites_name'] = 'PRIORITES.TXT';
	
	
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

function update_flow($site) {

	global $global;
	
	if ($global['echo']) {
		echo '#'.date("G:i:s").'###FLOW_UPDATE_STARTING...<BR>';
	}
	
	// Requetes
	$insert_queries = '';
	$update_queries = '';
	$delete_queries = '';
	
	if ($global['echo']) {
		echo '#'.date("G:i:s").'##FLOW_LOADING...';
	}
	
	$i = 0;
		
	$sql = 'SELECT flow.id, ' .
		   'flow.product_id, ' .
		   'flow.stock_dc, ' .
		   'flow.stock_bu, ' .
		   'flow.quantity_dc, ' .
		   'flow.quantity_bu, ' .
		   'UNIX_TIMESTAMP(flow.date_dc), ' .
		   'UNIX_TIMESTAMP(flow.date_bu) ' .
		   'FROM flow ' .
		   'LEFT OUTER JOIN product ON flow.product_id = product.id ' .
		   'LEFT OUTER JOIN site ON product.site_id = site.id ' .
		   'WHERE site.trigram = '.mysql_format_to_string($site);

	$result = mysql_select_query($sql);
	
	if ($result) {

		while ($row = mysql_fetch_array($result, MYSQL_NUM)) {

			$flow_database_data[$i][0] = $row[0];                // ID
			$flow_database_data[$i][1] = $row[1];                // Product id
			$flow_database_data[$i][2] = $row[2];                // Stock DC
			$flow_database_data[$i][3] = $row[3];                // Stock BU
			$flow_database_data[$i][4] = $row[4];                // Quantity DC
			$flow_database_data[$i][5] = $row[5];                // Quantity BU
			$flow_database_data[$i][6] = $row[6];                // Date DC
			$flow_database_data[$i][7] = $row[7];                // Date BU

			$i++;
		}
	}
	
	if ($global['echo']) {
		echo $i.'_REFERENCES<BR>#'.date("G:i:s").'##REFERENCES_AND_CUSTOMER_ORDERS_LOADING...';
	}
	
	$i = 0;
		
	$sql = 'SELECT product.id, ' . 
		   '(SELECT SUM(stock.quantity) FROM stock WHERE stock.product_id = product.id AND stock.warehouse_id NOT IN (SELECT warehouse_id FROM site WHERE id = site.id AND warehouse_id IS NOT NULL)), ' .
		   '(SELECT SUM(stock.quantity) FROM stock WHERE stock.product_id = product.id AND stock.warehouse_id IN (SELECT warehouse_id FROM site WHERE id = site.id AND warehouse_id IS NOT NULL)), ' .
		   'custords_product.quantity, ' .
		   'UNIX_TIMESTAMP(custords_product.date), ' .
		   'custords_product.direct_forwarding ' .
		   'FROM product ' .
		   'LEFT OUTER JOIN custords_product ON custords_product.product_id = product.id ' .
		   'LEFT OUTER JOIN site ON product.site_id = site.id ' .
		   'WHERE site.trigram = '.mysql_format_to_string($site).' ' .
		   'ORDER BY product.id, custords_product.date';
	
	$result = mysql_select_query($sql);
	
	if ($result) {

		while ($row = mysql_fetch_array($result, MYSQL_NUM)) {

			$custords_database_data[$i][0] = $row[0];                // Product ID
			$custords_database_data[$i][1] = $row[1];                // Stock DC
			$custords_database_data[$i][2] = $row[2];                // Stock BU
			$custords_database_data[$i][3] = $row[3];                // Quantity
			$custords_database_data[$i][4] = $row[4];                // Date
			$custords_database_data[$i][5] = $row[5];                // Direct forwarding

			$i++;
		}
	}
	
	if ($global['echo']) {
		echo $i.'_REFERENCES_OR_ORDERS<BR>#'.date("G:i:s").'##FLOW_COMPUTING...';
	}
	
	$count = 0;
	
	if (isset($custords_database_data)) {
		for ($i = 0; $i < count($custords_database_data); $i++) {
		
			$is_in_flow = false;
			
			if (isset($flow_data)) {
				for ($j = 0; $j < count($flow_data); $j++) {
				
					if ($custords_database_data[$i][0] == $flow_data[$j][0]) {
						
						if ($custords_database_data[$i][5] == 0) {
							if ($custords_database_data[$i][3] != null) {
								$flow_data[$j][3] += $custords_database_data[$i][3];
							}
							
							if ($flow_data[$j][5] == null) {
								if ($flow_data[$j][1] < $flow_data[$j][3]) {
									$flow_data[$j][5] = $custords_database_data[$i][4];
								}
							}
						} else if ($custords_database_data[$i][5] == 1) {
							
							if ($custords_database_data[$i][3] != null) {
								$flow_data[$j][4] += $custords_database_data[$i][3];
							}
							
							if ($flow_data[$j][6] == null) {
								if ($flow_data[$j][2] < $flow_data[$j][4]) {
									$flow_data[$j][6] = $custords_database_data[$i][4];
								}
							}
						}
						
						$is_in_flow = true;
						break;
					}
				}
			}
			
			if (!$is_in_flow) {
				$flow_data[$count][0] = $custords_database_data[$i][0];
				$flow_data[$count][1] = $custords_database_data[$i][1];
				$flow_data[$count][2] = $custords_database_data[$i][2];
				
				if ($flow_data[$count][1] == null) {
					$flow_data[$count][1] = 0;
				}
				
				if ($flow_data[$count][2] == null) {
					$flow_data[$count][2] = 0;
				}
				
				if ($custords_database_data[$i][5] == 0) {
					if ($custords_database_data[$i][3] == null) {
						$flow_data[$count][3] = 0;
					} else {
						$flow_data[$count][3] = $custords_database_data[$i][3];
					}
					
					$flow_data[$count][4] = 0;
					
					if ($flow_data[$count][1] >= $flow_data[$count][3]) {
						$flow_data[$count][5] = null;
					} else {
						$flow_data[$count][5] = $custords_database_data[$i][4];
					}
					
					$flow_data[$count][6] = null;
				} else if ($custords_database_data[$i][5] == 1) {
					$flow_data[$count][3] = 0;
					
					if ($custords_database_data[$i][3] == null) {
						$flow_data[$count][4] = 0;
					} else {
						$flow_data[$count][4] = $custords_database_data[$i][3];
					}
					
					$flow_data[$count][5] = null;
					
					if ($flow_data[$count][2] >= $flow_data[$count][4]) {
						$flow_data[$count][6] = null;
					} else {
						$flow_data[$count][6] = $custords_database_data[$i][4];
					}
				} else {
					$flow_data[$count][3] = 0;
					$flow_data[$count][4] = 0;
					$flow_data[$count][5] = null;
					$flow_data[$count][6] = null;
				}
				
				$count++;
			}
		}
	}
	
	if ($global['echo']) {
		echo 'OK<BR>#'.date("G:i:s").'##REFERENCES_TO_ADD_AND_TO_UPDATE_COMPUTING...';
	}
	
	$to_add = 0;
	$to_update = 0;
	
	if (isset($flow_data)) {
		for ($i = 0; $i < count($flow_data); $i++) {
		
			$is_in_database = false;
			
			if (isset($flow_database_data)) {
				for ($j = 0; $j < count($flow_database_data); $j++) {
				
					if ($flow_database_data[$j][1] == $flow_data[$i][0]) {
						
						if (($flow_database_data[$j][2] != $flow_data[$i][1]) ||
							($flow_database_data[$j][3] != $flow_data[$i][2]) ||
							($flow_database_data[$j][4] != $flow_data[$i][3]) ||
							($flow_database_data[$j][5] != $flow_data[$i][4]) ||
							($flow_database_data[$j][6] != $flow_data[$i][5]) ||
							($flow_database_data[$j][7] != $flow_data[$i][6])) {
							
							$sql_query = 'UPDATE flow ' .
										 'SET stock_dc = '.mysql_format_to_number($flow_data[$i][1]).', ' .
										 'stock_bu = '.mysql_format_to_number($flow_data[$i][2]).', ' .
										 'quantity_dc = '.mysql_format_to_number($flow_data[$i][3]).', ' .
										 'quantity_bu = '.mysql_format_to_number($flow_data[$i][4]).', ' .
										 'date_dc = '.mysql_format_unixtime_to_date($flow_data[$i][5]).', ' .
										 'date_bu = '.mysql_format_unixtime_to_date($flow_data[$i][6]).' ' .
										 'WHERE id = '.mysql_format_to_number($flow_database_data[$j][0]).' LIMIT 1;';
							
							$update_queries .= $sql_query;
							$to_update++;
						}
						
						$is_in_database = true;
						break;
					}
				}
			}
			
			if (!$is_in_database) {
				
				$sql_query = 'INSERT INTO flow (id, product_id, stock_dc, stock_bu, quantity_dc, quantity_bu, date_dc, date_bu) ' .
							 'VALUES ' .
							 '(NULL, ' .
							 ''.mysql_format_to_number($flow_data[$i][0]).', ' .
							 ''.mysql_format_to_number($flow_data[$i][1]).', ' .
							 ''.mysql_format_to_number($flow_data[$i][2]).', ' .
							 ''.mysql_format_to_number($flow_data[$i][3]).', ' .
							 ''.mysql_format_to_number($flow_data[$i][4]).', ' .
							 ''.mysql_format_unixtime_to_date($flow_data[$i][5]).', ' .
							 ''.mysql_format_unixtime_to_date($flow_data[$i][6]).');';
				
				$insert_queries .= $sql_query;
				$to_add++;

			}
		}
	}
	
	if ($global['echo']) {
		echo $to_add.'_TO_ADD_'.$to_update.'_TO_UPDATE<BR>';
	}
	
	if ($insert_queries != '') {
		if ($global['echo']) {
			echo '#'.date("G:i:s").'##ADDING_REFERENCES...<BR>';
		}
		
		echo str_replace(';', '<BR>', $insert_queries);
		mysql_insert_queries($insert_queries);
		$insert_queries = '';
		
		if ($global['echo']) {
			echo 'OK<BR>';
		}
	}

	if ($update_queries != '') {
		if ($global['echo']) {
			echo '#'.date("G:i:s").'##UPDATING_REFERENCES...<BR>';
		}
		
		echo str_replace(';', '<BR>', $update_queries);
		mysql_update_queries($update_queries);
		$update_queries = '';
		
		if ($global['echo']) {
			echo 'OK<BR>';
		}
	}
	
	if ($global['echo']) {
		echo '#'.date("G:i:s").'###FLOW_UPDATE_ENDS!<BR><BR>';
	}
	
	return true;
}

//////////////////////////////////////////////////////////////////////////////////
//////////////////////////////////////////////////////////////////////////////////
//////////////////////////////////////////////////////////////////////////////////




function import_neg() {

	global $file;
	global $global;
	
	$error = false;
	$same = false;
	
	$neg_folder = $file['neg_folder'];
	
	if ($global['echo']) {
		echo '<HTML>#'.date("G:i:s").'###NEGOCE_IMPORT_STARTS<BR><BR>';
	}
	
	$date = exec('ls -l --full-time '.$neg_folder.$file['dsjour_name'].' | awk \'{print $6,$7}\'');
	$date = substr($date, 0, strpos($date, '.'));
	
	$import = mysql_simple_select_query('SELECT message_value FROM value WHERE name = \'IMPORT_NEG\' LIMIT 1');
	
	if ($import == '')
	{
		$sql_query = 'UPDATE value ' .
					 'SET message_value = \'YES\' ' .
					 'WHERE name = \'IMPORT_NEG\' LIMIT 1;';
		
		mysql_update_query($sql_query);
			
		$result = import_dsjour($neg_folder, 'NEG');
		
		if ($result > -1) {
			if ($result == 1) {
				$same = true;
			}
			
			$result = import_custords($neg_folder, 'NEG');
			
			if ($result > -1) {
				if ($result == 0) {
					$same = false;
				}
				
				$result = import_onhand($neg_folder, 'NEG');
				
				if ($result > -1) {
					if ($result == 0) {
						$same = false;
					}
					
						$result = import_receipts($neg_folder, 'NEG');
						
						if ($result > -1) {
							if (!$same) {
								$error = (!update_flow('NEG'));
							}
						} else {
							$error = true;
						}
				} else {
					$error = true;
				}
			} else {
				$error = true;
			}
		} else {
			$error = true;
		}
		
		if (!$error)
		{
			if (!$same) {
				$sql_query = 'UPDATE date_value ' .
							 'SET date_value.date = STR_TO_DATE(\''.$date.'\', \'%Y-%m-%d %H:%i:%s\') ' .
						  	 'WHERE date_value.name = \'IMPORT_DATE_NEG\' LIMIT 1;';
			
				mysql_update_query($sql_query);
			}
			
			$sql_query = 'UPDATE value ' .
						 'SET message_value = NULL ' .
						 'WHERE name = \'IMPORT_MESSAGE_NEG\' LIMIT 1;';
			
			mysql_update_query($sql_query);
		}
		else
		{
			$sql_query = 'UPDATE value ' .
						 'SET message_value = \'ERREUR\' ' .
						 'WHERE name = \'IMPORT_MESSAGE_NEG\' LIMIT 1;';
			
			mysql_update_query($sql_query);
		}
		
		$sql_query = 'UPDATE value ' .
					 'SET message_value = NULL ' .
					 'WHERE name = \'IMPORT_NEG\' LIMIT 1;';
		
		mysql_update_query($sql_query);
	} else {
		if ($global['echo']) {
			echo '#'.date("G:i:s").'###ALREADY_LOADING...<BR><BR>';
		}
	}
	
	if ($global['echo']) {
		echo '#'.date("G:i:s").'###NEGOCE_IMPORT_END!</HTML>';
	}
}

function import_mlt() {

	global $file;
	global $global;
	
	$error = false;
	$same = false;
	
	$mlt_folder = $file['mlt_folder'];
	
	if ($global['echo']) {
		echo '<HTML>#'.date("G:i:s").'###MLT_IMPORT_STARTS<BR><BR>';
	}
	
	$date = exec('ls -l --full-time '.$mlt_folder.$file['dsjour_name'].' | awk \'{print $6,$7}\'');
	$date = substr($date, 0, strpos($date, '.'));
	
	$import = mysql_simple_select_query('SELECT message_value FROM value WHERE name = \'IMPORT_MLT\' LIMIT 1');
	
	if ($import == '')
	{
		$sql_query = 'UPDATE value ' .
					 'SET message_value = \'YES\' ' .
					 'WHERE name = \'IMPORT_MLT\' LIMIT 1;';
		
		mysql_update_query($sql_query);
	
		$result = import_dsjour($mlt_folder, 'MLT');
		
		if ($result > -1) {
			if ($result == 1) {
				$same = true;
			}
			
			$result = import_custords($mlt_folder, 'MLT');
			
			if ($result > -1) {
				if ($result == 0) {
					$same = false;
				}
				
				$result = import_onhand($mlt_folder, 'MLT');
				
				if ($result > -1) {
					if ($result == 0) {
						$same = false;
					}
					
						$result = import_receipts($mlt_folder, 'MLT');
						
						if ($result > -1) {
							if (!$same) {
								$error = (!update_flow('MLT'));
							}
						} else {
							$error = true;
						}
				} else {
					$error = true;
				}
			} else {
				$error = true;
			}
		} else {
			$error = true;
		}
		
		if (!$error)
		{
			if (!$same) {
				$sql_query = 'UPDATE date_value ' .
							 'SET date_value.date = STR_TO_DATE(\''.$date.'\', \'%Y-%m-%d %H:%i:%s\') ' .
							 'WHERE date_value.name = \'IMPORT_DATE_MLT\' LIMIT 1;';
			
				mysql_update_query($sql_query);
			}
						
			$sql_query = 'UPDATE value ' .
						 'SET message_value = NULL ' .
						 'WHERE name = \'IMPORT_MESSAGE_MLT\' LIMIT 1;';
			
			mysql_update_query($sql_query);
		}
		else
		{
			$sql_query = 'UPDATE value ' .
						 'SET message_value = \'Erreur!\' ' .
						 'WHERE name = \'IMPORT_MESSAGE_MLT\' LIMIT 1;';
			
			mysql_update_query($sql_query);
		}
		
		$sql_query = 'UPDATE value ' .
					 'SET message_value = NULL ' .
					 'WHERE name = \'IMPORT_MLT\' LIMIT 1;';
		
		mysql_update_query($sql_query);
	} else {
		if ($global['echo']) {
			echo '#'.date("G:i:s").'###ALREADY_LOADING...<BR><BR>';
		}
	}
	
	if ($global['echo']) {
		echo '#'.date("G:i:s").'###MLT_IMPORT_ENDS!</HTML>';
	}
}

function import_tre() {

	global $file;
	global $global;
	
	$error = false;
	$same = false;
	
	$tre_folder = $file['tre_folder'];
	
	if ($global['echo']) {
		echo '<HTML>#'.date("G:i:s").'###TRE_IMPORT_STARTS<BR><BR>';
	}
	
	$date = exec('ls -l --full-time '.$tre_folder.$file['dsjour_name'].' | awk \'{print $6,$7}\'');
	$date = substr($date, 0, strpos($date, '.'));
	
	$import = mysql_simple_select_query('SELECT message_value FROM value WHERE name = \'IMPORT_TRE\' LIMIT 1');
	
	if ($import == '')
	{
		$sql_query = 'UPDATE value ' .
					 'SET message_value = \'YES\' ' .
					 'WHERE name = \'IMPORT_TRE\' LIMIT 1;';
		
		mysql_update_query($sql_query);
		
		$result = import_dsjour($tre_folder, 'TRE');
		
		if ($result > -1) {
			if ($result == 1) {
				$same = true;
			}
			
			$result = import_custords($tre_folder, 'TRE');
			
			if ($result > -1) {
				if ($result == 0) {
					$same = false;
				}
				
				$result = import_onhand($tre_folder, 'TRE');
			
				if ($result > -1) {
					if ($result == 0) {
						$same = false;
					}
					
						$result = import_receipts($tre_folder, 'TRE');
						
						if ($result > -1) {
							if (!$same) {
								$error = (!update_flow('TRE'));
							}
						} else {
							$error = true;
						}
				} else {
					$error = true;
				}
			} else {
				$error = true;
			}
		} else {
			$error = true;
		}
		
		if (!$error)
		{
			if (!$same) {
				$sql_query = 'UPDATE date_value ' .
							 'SET date_value.date = STR_TO_DATE(\''.$date.'\', \'%Y-%m-%d %H:%i:%s\') ' .
							 'WHERE date_value.name = \'IMPORT_DATE_TRE\' LIMIT 1;';
			
				mysql_update_query($sql_query);
			}
						
			$sql_query = 'UPDATE value ' .
						 'SET message_value = NULL ' .
						 'WHERE name = \'IMPORT_MESSAGE_TRE\' LIMIT 1;';
			
			mysql_update_query($sql_query);
		}
		else
		{
			$sql_query = 'UPDATE value ' .
						 'SET message_value = \'Erreur!\' ' .
						 'WHERE name = \'IMPORT_MESSAGE_TRE\' LIMIT 1;';
			
			mysql_update_query($sql_query);
		}
		
		$sql_query = 'UPDATE value ' .
					 'SET message_value = NULL ' .
					 'WHERE name = \'IMPORT_TRE\' LIMIT 1;';
		
		mysql_update_query($sql_query);
	} else {
		if ($global['echo']) {
			echo '#'.date("G:i:s").'###ALREADY_LOADING...<BR><BR>';
		}
	}
	
	if ($global['echo']) {
		echo '#'.date("G:i:s").'###TRE_IMPORT_ENDS!</HTML>';
	}
}


function import_lam() {

	global $file;
	global $global;
	
	$error = false;
	$same = false;
	
	$lam_folder = $file['lam_folder'];
	
	if ($global['echo']) {
		echo '<HTML>#'.date("G:i:s").'###LAM_IMPORT_STARTS<BR><BR>';
	}
	
	$date = exec('ls -l --full-time '.$lam_folder.$file['dsjour_name'].' | awk \'{print $6,$7}\'');
	$date = substr($date, 0, strpos($date, '.'));
	
	$import = mysql_simple_select_query('SELECT message_value FROM value WHERE name = \'IMPORT_LAM\' LIMIT 1');
	
	if ($import == '')
	{
		$sql_query = 'UPDATE value ' .
					 'SET message_value = \'YES\' ' .
					 'WHERE name = \'IMPORT_LAM\' LIMIT 1;';
		
		mysql_update_query($sql_query);
	
		$result = import_dsjour($lam_folder, 'LAM');
		
		if ($result > -1) {
			if ($result == 1) {
				$same = true;
			}
			
			$result = import_custords($lam_folder, 'LAM');
			
			if ($result > -1) {
				if ($result == 0) {
					$same = false;
				}
				
				$result = import_onhand($lam_folder, 'LAM');
				
				if ($result > -1) {
					if ($result == 0) {
						$same = false;
					}
					
						$result = import_receipts($lam_folder, 'LAM');
						
						if ($result > -1) {
							if (!$same) {
								$error = (!update_flow('LAM'));
							}
						} else {
							$error = true;
						}
				} else {
					$error = true;
				}
			} else {
				$error = true;
			}
		} else {
			$error = true;
		}
		
		if (!$error)
		{
			if (!$same) {
				$sql_query = 'UPDATE date_value ' .
							 'SET date_value.date = STR_TO_DATE(\''.$date.'\', \'%Y-%m-%d %H:%i:%s\') ' .
							 'WHERE date_value.name = \'IMPORT_DATE_LAM\' LIMIT 1;';
			
				mysql_update_query($sql_query);
			}
						
			$sql_query = 'UPDATE value ' .
						 'SET message_value = NULL ' .
						 'WHERE name = \'IMPORT_MESSAGE_LAM\' LIMIT 1;';
			
			mysql_update_query($sql_query);
		}
		else
		{
			$sql_query = 'UPDATE value ' .
						 'SET message_value = \'Erreur!\' ' .
						 'WHERE name = \'IMPORT_MESSAGE_LAM\' LIMIT 1;';
			
			mysql_update_query($sql_query);
		}
		
		$sql_query = 'UPDATE value ' .
					 'SET message_value = NULL ' .
					 'WHERE name = \'IMPORT_LAM\' LIMIT 1;';
		
		mysql_update_query($sql_query);
	} else {
		if ($global['echo']) {
			echo '#'.date("G:i:s").'###ALREADY_LOADING...<BR><BR>';
		}
	}
		
	if ($global['echo']) {
		echo '#'.date("G:i:s").'###LAM_IMPORT_ENDS!</HTML>';
	}
}

function import_dsjour($folder, $site)
{
	global $file;
	global $global;
	
	if ($global['echo']) {
		echo '#'.date("G:i:s").'###DSJOUR.DAT_FILE_IMPORT_STARTS<BR>';
	}
	
	// DSJOUR.DAT file format
	$file_format = array(array(17, 'string'),         // 0 : Reference
						 array(3, 'string'),          // 1 : Location
						 array(25, 'string'),         // 2 : Name
						 array(10, 'decimal'),        // 3 : Cost
						 array(10, 'decimal'),        // 4 : Price
						 array(8, 'string'),          // 5 : Machine
						 array(8, 'string'),          // 6 : Cell
						 array(8, 'string'),          // 7 : Class
						 array(8, 'number'));         // 8 : MTD
	
	$size = exec('ls -l '.$folder.$file['dsjour_name'].' | awk \'{print $5}\'');

	if ((!is_numeric($size)) || ($size == '0'))
	{
		echo '#'.date("G:i:s").'###ERROR_INCORRECT_FILE : '.$folder.$file['dsjour_name'].'<BR><BR>';
		return -1;
	}
	
	exec('cmp -s '.$folder.$file['dsjour_name'].' '.$folder.$file['dsjour_name'].'.BAK', $cmp_result, $cmp);

	if ($cmp == 0)
	{
		echo '#'.date("G:i:s").'###SAME_FILE...<BR><BR>';
		return 1;
	}
	else
	{
		exec('rm -f '.$folder.$file['dsjour_name'].'.BAK');
		exec('cp '.$folder.$file['dsjour_name'].' '.$folder.$file['dsjour_name'].'.BAK');
	}
	
	if ($global['echo']) {
		echo '#'.date("G:i:s").'##LOADING_REFERENCES_FROM_FILE...';
	}

	$product_file_data = null;
	$i = 0;
	
	// Fill product file data table
	$fs = fopen($folder.$file['dsjour_name'], 'r');
	
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
							$product_file_data[$i][$j] = intval($str);
						} else if ($file_format[$j][1] == 'decimal') {
							$product_file_data[$i][$j] = floatval(str_replace(',', '.', $str));
						} else {
							$product_file_data[$i][$j] = $str;
						}
						
						$len += $file_format[$j][0];
					}
				}
				
				$i++;
			}
	   	}
	   	
		fclose($fs);
	}
	
	if ($i == 0)
	{
		echo '#'.date("G:i:s").'###ERROR_INCORRECT_FILE : '.$folder.$file['dsjour_name'].'<BR><BR>';
		return -1;
	}
	
	if ($global['echo']) {
		echo $i.'_REFERENCES<BR>#'.date("G:i:s").'##LOADING_REFERENCES_FROM_DATABASE...';
	}
	
	$product_database_data = null;
	$i = 0;
	
	//Fill product database data table
	$sql_query = 'SELECT product.id, ' .
				 'product.reference, ' .
				 'product.active, ' .
				 'site.trigram, ' .
				 'machine.name, ' .
				 'product.name, ' .
				 'product.cost, ' .
				 'product.price, ' .
				 'product.mtd_sales, ' .
				 'product.area ' .
				 'FROM product ' .
				 'LEFT OUTER JOIN site ON product.site_id = site.id ' .
				 'LEFT OUTER JOIN machine ON product.machine_id = machine.id ' .
				 'ORDER BY product.reference';
	
	$result = mysql_select_query($sql_query);

	if ($result) {

		$i = 0;

		while ($row = mysql_fetch_array($result, MYSQL_NUM)) {

			$product_database_data[$i][0] = $row[0];                // ID
			$product_database_data[$i][1] = $row[1];                // Reference
			$product_database_data[$i][2] = $row[2];                // Active
			$product_database_data[$i][3] = $row[3];                // Site trigram
			$product_database_data[$i][4] = $row[4];                // Machine
			$product_database_data[$i][5] = $row[5];                // Name
			$product_database_data[$i][6] = $row[6];                // Cost
			$product_database_data[$i][7] = $row[7];                // Price
			$product_database_data[$i][8] = $row[8];                // MTD sales
			$product_database_data[$i][9] = $row[9];                // Class

			$i++;
		}
	}
	
	if ($global['echo']) {
		echo $i.'_REFERENCES<BR>#'.date("G:i:s").'##COMPUTING_REFERENCES_TO_ADD_AND_TO_UPDATE...';
	}
	
	$machine_to_add = 0;
	$to_add = 0;
	$to_update = 0;

	$insert_machine_queries = '';
	$insert_queries = '';
	$update_queries = '';

	/* Test if a reference already exists :          */
	/* If : update                                   */
	/* Else : add                                    */
	/* If a machine doesn't exists : add it          */
	if ($product_file_data != null) {
		for ($i = 0; $i < count($product_file_data); $i++) {
		
			$is_in_database = false;
			
			if ($product_database_data != null) {
				for ($j = 0; $j < count($product_database_data); $j++) {
					
					if ($product_file_data[$i][0] == $product_database_data[$j][1]) {
						
						if (($site != $product_database_data[$j][3]) ||
							(0 == $product_database_data[$j][2]) ||
							($product_file_data[$i][2] != $product_database_data[$j][5]) ||
							($product_file_data[$i][5] != $product_database_data[$j][4]) ||
							($product_file_data[$i][3] != $product_database_data[$j][6]) ||
							($product_file_data[$i][4] != $product_database_data[$j][7]) ||
							($product_file_data[$i][8] != $product_database_data[$j][8]) ||
							($product_file_data[$i][7] != $product_database_data[$j][9])) {
							
							if ($product_file_data[$i][5] != '') {
								$machine_id = mysql_simple_select_query('SELECT id ' . 
																		'FROM machine ' .
																		'WHERE name = '.mysql_format_to_string($product_file_data[$i][5]).' AND ' .
																		'site_id = (SELECT id FROM site WHERE trigram = '.mysql_format_to_string($site).')');
								
								if ($machine_id == null) {
									$sql_query = 'INSERT INTO machine (id, active, site_id, cell_id, name) ' .
												 'VALUES ' . 
												 '(NULL, ' . 
												 '1, ' . 
												 '(SELECT id FROM site WHERE trigram = '.mysql_format_to_string($site).'), ' . 
												 '(SELECT id FROM cell WHERE name = '.mysql_format_to_string($product_file_data[$i][6]).' AND site_id = (SELECT id FROM site WHERE trigram = '.mysql_format_to_string($site).')), ' . 
												 ''.mysql_format_to_string($product_file_data[$i][5]).');';
									
									$insert_machine_queries .= $sql_query;
									$machine_to_add++;
								}
							}
							
							$sql_query = 'UPDATE product ' .
										 'SET active = 1, ' .
										 'site_id = (SELECT id FROM site WHERE trigram = '.mysql_format_to_string($site).'), ' .
										 'machine_id = (SELECT id FROM machine WHERE name = '.mysql_format_to_string($product_file_data[$i][5]).' AND site_id = (SELECT id FROM site WHERE trigram = '.mysql_format_to_string($site).')), ' .
										 'name = '.mysql_format_to_string($product_file_data[$i][2]).', ' .
										 'cost = '.mysql_format_to_number($product_file_data[$i][3]).', ' .
										 'price = '.mysql_format_to_number($product_file_data[$i][4]).', ' . 
										 'mtd_sales = '.mysql_format_to_number($product_file_data[$i][8]).', ' .
										 'area = '.mysql_format_to_string($product_file_data[$i][7]).' ' .
										 'WHERE id = '.mysql_format_to_number($product_database_data[$j][0]).' LIMIT 1;';
							
							$update_queries .= $sql_query;
							$to_update++;
						}
						
						$is_in_database = true;
						break;
					}
				}
			}
			
			if (!$is_in_database) {
			
				if ($product_file_data[$i][5] != '') {
					$machine_id = mysql_simple_select_query('SELECT id ' . 
															'FROM machine ' .
															'WHERE name = '.mysql_format_to_string($product_file_data[$i][5]).' AND ' .
															'site_id = (SELECT id FROM site WHERE trigram = '.mysql_format_to_string($site).')');
					
					if ($machine_id == null) {
						$sql_query = 'INSERT INTO machine (id, active, site_id, cell_id, name) ' .
									 'VALUES ' . 
									 '(NULL, ' . 
									 '1, ' . 
									 '(SELECT id FROM site WHERE trigram = '.mysql_format_to_string($site).'), ' . 
									 '(SELECT id FROM cell WHERE name = '.mysql_format_to_string($product_file_data[$i][6]).' AND site_id = (SELECT id FROM site WHERE trigram = '.mysql_format_to_string($site).')), ' . 
									 ''.mysql_format_to_string($product_file_data[$i][5]).');';
						
						$insert_machine_queries .= $sql_query;
						$machine_to_add++;
					}
				}
						
				$sql_query = 'INSERT INTO product (id, reference, active, site_id, machine_id, name, cost, price, mtd_sales, area) ' . 
							 'VALUES ' . 
							 '(NULL, ' . 
							 ''.mysql_format_to_string($product_file_data[$i][0]).', ' .
							 '1, ' .
							 '(SELECT id FROM site WHERE trigram = '.mysql_format_to_string($site).'), ' .
							 '(SELECT id FROM machine WHERE name = '.mysql_format_to_string($product_file_data[$i][5]).' AND site_id = (SELECT id FROM site WHERE trigram = '.mysql_format_to_string($site).')), ' .
							 ''.mysql_format_to_string($product_file_data[$i][2]).', ' .
							 ''.mysql_format_to_number($product_file_data[$i][3]).', ' .
							 ''.mysql_format_to_number($product_file_data[$i][4]).', ' .
							 ''.mysql_format_to_number($product_file_data[$i][8]).', ' .
							 ''.mysql_format_to_string($product_file_data[$i][7]).');';
				
				
				$insert_queries .= $sql_query;
				$to_add++;
			}
		}
	}
	
	if ($global['echo']) {
		echo $machine_to_add.'_MACHINES_TO_ADD_'.$to_add.'_REFERENCES_TO_ADD_'.$to_update.'_TO_UPDATE<BR>';
	}
	
	if ($insert_machine_queries != '') {
		if ($global['echo']) {
			echo '#'.date("G:i:s").'##ADDING_MACHINES...<BR>';
		}
		
		echo str_replace(';', '<BR>', $insert_machine_queries);
		mysql_insert_queries($insert_machine_queries);
		$insert_machine_queries = '';
		
		if ($global['echo']) {
			echo 'OK<BR>';
		}
	}
	
	if ($insert_queries != '') {
		if ($global['echo']) {
			echo '#'.date("G:i:s").'##ADDING_REFERENCES...<BR>';
		}
		
		echo str_replace(';', '<BR>', $insert_queries);
		mysql_insert_queries($insert_queries);
		$insert_queries = '';

		
		if ($global['echo']) {
			echo 'OK<BR>';
		}
	}

	if ($update_queries != '') {
		if ($global['echo']) {
			echo '#'.date("G:i:s").'##UPDATING_REFRENCES...<BR>';
		}
		
		echo str_replace(';', '<BR>', $update_queries);
		mysql_update_queries($update_queries);
		$update_queries = '';
		
		if ($global['echo']) {
			echo 'OK<BR>';
		}
	}
	
	if ($global['echo']) {
		echo '#'.date("G:i:s").'##COMPUTING_REFERENCES_TO_DESACTIVATE...';
	}
	
	$to_desactivate = 0;
	$update_queries = '';
	
	if ($product_database_data != null) {
		for ($i = 0; $i < count($product_database_data); $i++) {
		
			if (($product_database_data[$i][3] == $site) && ($product_database_data[$i][2] == 1)) {
				$is_in_file = false;
				
				if ($product_file_data != null) {
					for ($j = 0; $j < count($product_file_data); $j++) {
						if ($product_file_data[$j][0] == $product_database_data[$i][1]) {
							$is_in_file = true;
							break;
						}
					}
				}
				
				if (!$is_in_file) {
					$sql_query = 'UPDATE product ' .
								 'SET active = 0 ' .
								 'WHERE id = '.mysql_format_to_number($product_database_data[$i][0]).' LIMIT 1;';
					
					$update_queries .= $sql_query;
					$to_desactivate++;
				}
			}
		}
	}
	
	if ($global['echo']) {
		echo $to_desactivate.'_REFERENCES_TO_DESACTIVATE<BR>';
	}
	
	if ($update_queries != '') {
		if ($global['echo']) {
			echo '#'.date("G:i:s").'##DESACTIVATING_REFERENCES...<BR>';
		}
		
		echo str_replace(';', '<BR>', $update_queries);
		mysql_update_queries($update_queries);
		$update_queries = '';
		
		if ($global['echo']) {
			echo 'OK<BR>';
		}
	}
	
	if ($global['echo']) {
		echo '#'.date("G:i:s").'###DSJOUR.DAT_FILE_IMPORT_ENDS!<BR><BR>';
	}
	
	return 0;
}

function import_custords($folder, $site)
{
	global $file;
	global $global;
	
	if ($global['echo']) {
		echo '#'.date("G:i:s").'###CUSTORDS.DAT_FILE_IMPORT_STARTS<BR>';
	}

	// CUSTORDS.DAT file format
	$file_format = array(array(17, 'string'),         // 0  : Reference
						 array(3, 'string'),          // 1  : Location
						 array(8, 'number'),          // 2  : Quantity
						 array(1, 'string'),          // 3  : Filler
						 array(13, 'string'),         // 4  : Order number
						 array(8, 'number'),          // 5  : Customer number
						 array(1, 'string'),          // 6  : Direct forwarding
						 array(1, 'string'),          // 7  : Blocked
						 array(3, 'string'),          // 8  : Warehouse
						 array(3, 'string'),          // 9  : Sales administration
						 array(1, 'string'),          // 10 : Partial
						 array(21, 'string'),         // 11 : Customer name
						 array(4, 'string'),          // 12 : Status
						 array(6, 'string'),          // 13 : Delivery date
						 array(1, 'string'));         // 14 : Special
	
	$size = exec('ls -l '.$folder.$file['custords_name'].' | awk \'{print $5}\'');
	
	if ((!is_numeric($size)) || ($size == '0'))
	{
		echo '#'.date("G:i:s").'###ERROR_INCORRECT_FILE : '.$folder.$file['custords_name'].'<BR><BR>';
		return -1;
	}
	
	exec('cmp -s '.$folder.$file['custords_name'].' '.$folder.$file['custords_name'].'.BAK', $cmp_result, $cmp);
	
	if ($cmp == 0)
	{
		echo '#'.date("G:i:s").'###SAME_FILE...<BR><BR>';
		return 1;
	}
	else
	{
		exec('rm -f '.$folder.$file['custords_name'].'.BAK');
		exec('cp '.$folder.$file['custords_name'].' '.$folder.$file['custords_name'].'.BAK');
	}
	
	if ($global['echo']) {
		echo '#'.date("G:i:s").'##LOADING_ORDERS_FROM_FILE...';
	}
	
	$order_file_data = null;
	$i = 0;
	
	// Fill custords file data table
	$fs = fopen($folder.$file['custords_name'], 'r');
	
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
							$order_file_data[$i][$j] = intval($str);
						} else if ($file_format[$j][1] == 'decimal') {
							$order_file_data[$i][$j] = floatval(str_replace(',', '.', $str));
						} else {
							$order_file_data[$i][$j] = $str;
						}
						
						$len += $file_format[$j][0];
					}
				}
				
				$i++;
			}
	   	}
	   	
		fclose($fs);
	}
	
	if ($i == 0) {
		echo '#'.date("G:i:s").'###ERROR_INCORRECT_FILE : '.$folder.$file['custords_name'].'<BR><BR>';
		return -1;
	}
	
	if ($global['echo']) {
		echo $i.'_LINES<BR>#'.date("G:i:s").'##LOADING_SALES_ADMIN_FROM_DATABASE...';
	}
	
	$sales_admin_database_data = null;
	$i = 0;
	
	$sql_query = 'SELECT DISTINCT trigram FROM sales_admin ORDER BY trigram';
	
	$result = mysql_select_query($sql_query);

	if ($result) {

		$i = 0;

		while ($row = mysql_fetch_array($result, MYSQL_NUM)) {

			$sales_admin_database_data[$i] = $row[0];            // Number
			$i++;
		}
	}
	
	if ($global['echo']) {
		echo $i.'_SALES_ADMIN<BR>#'.date("G:i:s").'##LOADING_CUSTOMERS_FROM_DATABASE...';
	}
	
	$customer_database_data = null;
	$i = 0;
	
	$sql_query = 'SELECT DISTINCT number FROM customer ORDER BY number';
	
	$result = mysql_select_query($sql_query);

	if ($result) {

		$i = 0;

		while ($row = mysql_fetch_array($result, MYSQL_NUM)) {

			$customer_database_data[$i] = $row[0];            // Number
			$i++;
		}
	}
	
	if ($global['echo']) {
		echo $i.'_CUSTOMERS<BR>#'.date("G:i:s").'##LOADING_ORDERS_FROM_DATABASE...';
	}
	
	$custords_database_data = null;
	
	$sql_query = 'SELECT DISTINCT number FROM custords ORDER BY number';
				 
	$result = mysql_select_query($sql_query);

	if ($result) {

		$i = 0;

		while ($row = mysql_fetch_array($result, MYSQL_NUM)) {

			$custords_database_data[$i] = $row[0];                // Number
			$i++;
		}
	}
	
	if ($global['echo']) {
		echo $i.'_ORDERS<BR>#'.date("G:i:s").'##LOADING_ORDERS_FROM_DATABASE...';
	}
	
	$custords_product_database_data = null;
	
	$sql_query = 'SELECT custords_product.custords_id, ' .
				 'custords_product.product_id, ' .
				 'custords.number, ' .
				 'product.reference, ' .
				 'custords_product.direct_forwarding, ' .
				 'custords_product.blocked, ' .
				 'custords_product.quantity, ' .
				 'DATE_FORMAT(custords_product.date, \'%y%m%d\'), ' .
				 'warehouse.trigram ' .
				 'FROM custords_product ' .
				 'LEFT OUTER JOIN custords ON custords_product.custords_id = custords.id ' .
				 'LEFT OUTER JOIN product ON custords_product.product_id = product.id ' .
				 'LEFT OUTER JOIN warehouse ON custords_product.warehouse_id = warehouse.id ' .
				 'LEFT OUTER JOIN site ON product.site_id = site.id ' .
				 'WHERE site.trigram = '.mysql_format_to_string($site).' ' .
				 'ORDER BY custords.number, product.reference';
	
	$result = mysql_select_query($sql_query);

	if ($result) {

		$i = 0;

		while ($row = mysql_fetch_array($result, MYSQL_NUM)) {

			$custords_product_database_data[$i][0] = $row[0];                // Custords id
			$custords_product_database_data[$i][1] = $row[1];                // Product id
			$custords_product_database_data[$i][2] = $row[2];                // Custords number
			$custords_product_database_data[$i][3] = $row[3];                // Product reference
			$custords_product_database_data[$i][4] = $row[4];                // Direct forwarding
			$custords_product_database_data[$i][5] = $row[5];                // Blocked
			$custords_product_database_data[$i][6] = $row[6];                // Quantity
			$custords_product_database_data[$i][7] = $row[7];                // Date
			$custords_product_database_data[$i][8] = $row[8];                // Warehouse

			$i++;
		}
	}
	
	if ($global['echo']) {
		echo $i.'_LINES<BR>#'.date("G:i:s").'##DATA_TO_UPDATE_COMPUTING...';
	}
	
	$sales_admin_to_add = 0;
	$customer_to_add = 0;
	$custords_to_add = 0;
	
	$custords_product_to_add = 0;
	$custords_product_to_update = 0;
	
	$insert_sales_admin_queries = '';
	$insert_customer_queries = '';
	$insert_custords_queries = '';
	
	$insert_queries = '';
	$update_queries = '';
	$delete_queries = '';
	
	$sales_admin_number = count($sales_admin_database_data);
	$customer_number = count($customer_database_data);
	$custords_number = count($custords_database_data);
	$custords_product_number = count($custords_product_database_data);
	$order_number = count($order_file_data);
	
	
	/* Test if a customer already exists :           */
	/* If : update                                   */
	/* Else : add                                    */
	if ($order_number > 0) {
		for ($i = 0; $i < $order_number; $i++) {
			if ($order_file_data[$i][9] != '') {
				
				if (($sales_admin_number == 0) || (!in_array($order_file_data[$i][9], $sales_admin_database_data))) {
					$sql_query = 'INSERT INTO sales_admin (id, active, trigram) ' . 
								 'VALUES ' . 
								 '(NULL, ' . 
								 '1, ' . 
								 ''.mysql_format_to_string($order_file_data[$i][9]).');';
					
					$insert_sales_admin_queries .= $sql_query;
					$sales_admin_to_add++;
					
					$sales_admin_database_data[$sales_admin_number] = $order_file_data[$i][9];
					$sales_admin_number++;
				}
			}
			
			if ($order_file_data[$i][5] != '') {
				
				if (($customer_number == 0) || (!in_array($order_file_data[$i][5], $customer_database_data))) {
					$sql_query = 'INSERT INTO customer (id, number, name) ' . 
								 'VALUES ' . 
								 '(NULL, ' . 
								 ''.mysql_format_to_number($order_file_data[$i][5]).', ' .
								 ''.mysql_format_to_string($order_file_data[$i][11]).');';
					
					$insert_customer_queries .= $sql_query;
					$customer_to_add++;
					
					$customer_database_data[$customer_number] = $order_file_data[$i][5];
					$customer_number++;
				}
			}
			
			if ($order_file_data[$i][4] != '') {
				
				if (($custords_number == 0) || (!in_array($order_file_data[$i][4], $custords_database_data))) {
					$sql_query = 'INSERT INTO custords (id, number, customer_id, sales_admin_id) ' . 
								 'VALUES ' . 
								 '(NULL, ' . 
								 ''.mysql_format_to_string($order_file_data[$i][4]).', ' . 
								 '(SELECT id FROM customer WHERE number = '.mysql_format_to_string($order_file_data[$i][5]).'), ' . 
								 '(SELECT id FROM sales_admin WHERE trigram = '.mysql_format_to_string($order_file_data[$i][9]).'));';
				
					
					$insert_custords_queries .= $sql_query;
					$custords_to_add++;
					
					$custords_database_data[$custords_number] = $order_file_data[$i][4];
					$custords_number++;
				
				}
			}
	
			$is_custords_product_in_database = false;
		
			if ($custords_product_number > 0) {
				for ($j = 0; $j < $custords_product_number; $j++) {
					
					if (($order_file_data[$i][4] == $custords_product_database_data[$j][2]) && 
						($order_file_data[$i][0] == $custords_product_database_data[$j][3]) && 
						($order_file_data[$i][13] == $custords_product_database_data[$j][7])) {
						
						$direct_forwarding = 0;
			
						if ($order_file_data[$i][6] == 'Y') {
							$direct_forwarding = 1;
						}
						
						$blocked = 0;
						
						if ($order_file_data[$i][7] == 'Y') {
							$blocked = 1;
						}
			
						if (($direct_forwarding != $custords_product_database_data[$j][4]) ||
							($blocked != $custords_product_database_data[$j][5]) ||
							($order_file_data[$i][2] != $custords_product_database_data[$j][6]) ||
							($order_file_data[$i][8] != $custords_product_database_data[$j][8])) {
							
							$sql_query = 'UPDATE custords_product ' .
										 'SET direct_forwarding = '.mysql_format_to_number($direct_forwarding).', ' .
										 'blocked = '.mysql_format_to_number($blocked).', ' .
										 'quantity = '.mysql_format_to_number($order_file_data[$i][2]).', ' .
										 'warehouse_id = (SELECT id FROM warehouse WHERE trigram = '.mysql_format_to_string($order_file_data[$i][8]).'), ' .
										 'site_id = (SELECT site_id FROM product WHERE id = '.mysql_format_to_string($custords_product_database_data[$j][1]).') ' .
										 'WHERE custords_id = '.mysql_format_to_number($custords_product_database_data[$j][0]).' AND ' .
										 'product_id = '.mysql_format_to_number($custords_product_database_data[$j][1]).' AND ' .
										 'date = '.mysql_format_value_to_date($custords_product_database_data[$j][7], '%y%m%d').' LIMIT 1;';
							
							$update_queries .= $sql_query;

							$custords_product_to_update++;
						}
						
						$is_custords_product_in_database = true;
						break;
					}
				}
			}
			
			if (!$is_custords_product_in_database) {
			
				$direct_forwarding = 0;
			
				if ($order_file_data[$i][6] == 'Y') {
					$direct_forwarding = 1;
				}
				
				$blocked = 0;
				
				if ($order_file_data[$i][7] == 'Y') {
					$blocked = 1;
				}
						
				$sql_query = 'INSERT INTO custords_product (custords_id, product_id, site_id, direct_forwarding, blocked, quantity, date, warehouse_id) ' . 
							 'VALUES ' . 
							 '((SELECT id FROM custords WHERE number = '.mysql_format_to_string($order_file_data[$i][4]).'), ' . 
							 '(SELECT id FROM product WHERE reference = '.mysql_format_to_string($order_file_data[$i][0]).'), ' . 
							 '(SELECT site_id FROM product WHERE reference = '.mysql_format_to_string($order_file_data[$i][0]).'), ' . 
							 ''.mysql_format_to_number($direct_forwarding).', ' . 
							 ''.mysql_format_to_number($blocked).', ' . 
							 ''.mysql_format_to_number($order_file_data[$i][2]).', ' . 
							 ''.mysql_format_value_to_date($order_file_data[$i][13], '%y%m%d').', ' .
							 '(SELECT id FROM warehouse WHERE trigram = '.mysql_format_to_string($order_file_data[$i][8]).'));';
				
				$insert_queries .= $sql_query;
				$custords_product_to_add++;
			}
		}
	}
	
	if ($global['echo']) {
		echo $sales_admin_to_add.'_SALES_ADMIN_TO_ADD_' .
			 $customer_to_add.'_CUSTOMERS_TO_ADD_'.$custords_to_add.'_ORDERS_TO_ADD_' .
			 $custords_product_to_add.'_LINES_TO_ADD_'.$custords_product_to_update.'_LINES_TO_UPDATE<BR>';
	}
	
	if ($insert_sales_admin_queries != '') {
		if ($global['echo']) {
			echo '#'.date("G:i:s").'##ADDING_SALES_ADMIN...<BR>';
		}
		
		echo str_replace(';', '<BR>', $insert_sales_admin_queries);
		mysql_insert_queries($insert_sales_admin_queries);
		$insert_sales_admin_queries = '';
		
		if ($global['echo']) {
			echo 'OK<BR>';
		}
	}
	
	if ($insert_customer_queries != '') {
		if ($global['echo']) {
			echo '#'.date("G:i:s").'##ADDING_CUSTOMERS...<BR>';
		}
		
		echo str_replace(';', '<BR>', $insert_customer_queries);
		mysql_insert_queries($insert_customer_queries);
		$insert_customer_queries = '';
		
		if ($global['echo']) {
			echo 'OK<BR>';
		}
	}
	
	if ($insert_custords_queries != '') {
		if ($global['echo']) {
			echo '#'.date("G:i:s").'##ADDING_ORDERS...<BR>';
		}
		
		echo str_replace(';', '<BR>', $insert_custords_queries);
		mysql_insert_queries($insert_custords_queries);
		$insert_custords_queries = '';
		
		if ($global['echo']) {
			echo 'OK<BR>';
		}
	}
	
	if ($insert_queries != '') {
		if ($global['echo']) {
			echo '#'.date("G:i:s").'##ADDING_LINES...<BR>';
		}
		
		echo str_replace(';', '<BR>', $insert_queries);
		mysql_insert_queries($insert_queries);
		$insert_queries = '';
		
		if ($global['echo']) {
			echo 'OK<BR>';
		}
	}
	
	if ($update_queries != '') {
		if ($global['echo']) {
			echo '#'.date("G:i:s").'##UPDATING_LINES...<BR>';
		}
		
		echo str_replace(';', '<BR>', $update_queries);
		mysql_update_queries($update_queries);
		$update_queries = '';
		
		if ($global['echo']) {
			echo 'OK<BR>';
		}
	}
	
	if ($global['echo']) {
		echo '#'.date("G:i:s").'##COMPUTING_LINES_TO_DELETE...';
	}
	
	$custords_product_to_delete = 0;
	
	if ($custords_product_number > 0) {
		for ($i = 0; $i < $custords_product_number; $i++) {
		
			$is_custords_product_in_file = false;
			
			if ($order_number > 0) {
				for ($j = 0; $j < $order_number; $j++) {
				
					if (($order_file_data[$j][4] == $custords_product_database_data[$i][2]) && 
						($order_file_data[$j][0] == $custords_product_database_data[$i][3]) && 
						($order_file_data[$j][13] == $custords_product_database_data[$i][7])) {
						$is_custords_product_in_file = true;
						break;
					}
				}
			}
			
			if (!$is_custords_product_in_file) {
				
				$sql_query = 'DELETE FROM custords_product ' .
							 'WHERE custords_id = '.mysql_format_to_number($custords_product_database_data[$i][0]).' AND ' .
							 'product_id = '.mysql_format_to_number($custords_product_database_data[$i][1]).' AND ' .
							 'date = '.mysql_format_value_to_date($custords_product_database_data[$i][7], '%y%m%d').' LIMIT 1;';
				
				$delete_queries .= $sql_query;
				$custords_product_to_delete++;
			}
		}
	}
	
	if ($global['echo']) {
		echo $custords_product_to_delete.'_LINES_TO_DELETE<BR>';
	}
	
	$sql_query = 'DELETE FROM custords ' .
				 'WHERE id NOT IN (SELECT DISTINCT custords_id FROM custords_product);';
	
	$delete_queries .= $sql_query;
	
	if ($delete_queries != '') {
		if ($global['echo']) {
			echo '#'.date("G:i:s").'##DELETING_LINES...<BR>';
		}
		
		echo str_replace(';', '<BR>', $delete_queries);
		mysql_delete_queries($delete_queries);
		
		if ($global['echo']) {
			echo 'OK<BR>';
		}
	}
	
	if ($global['echo']) {
		echo '#'.date("G:i:s").'###CUSTORDS.DAT_FILE_IMPORT_ENDS!<BR><BR>';
	}
	
	return 0;
}

function import_onhand($folder, $site)
{
	global $file;

	global $global;
	
	if ($global['echo']) {
		echo '#'.date("G:i:s").'###ONHAND.DAT_FILE_IMPORT_STARTS<BR>';
	}

	// ONHAND.DAT file format
	$file_format = array(array(17, 'string'),         // 0 : Reference
						 array(3, 'string'),          // 1 : Location
						 array(8, 'number'),          // 2 : Stock quantity
						 array(12, 'string'),         // 3 : Weight
						 array(12, 'string'),         // 4 : Cube
						 array(8, 'number'),          // 5 : MTD
						 array(8, 'number'),          // 6 : Minimum order
						 array(8, 'number'),          // 7 : Multiple
						 array(3, 'string'),          // 8 : Safety time
						 array(1, 'string'),          // 9 : Non order
						 array(3, 'string'),          // 10 : Lead time
						 array(3, 'string'),          // 11 : warehouse
						 array(13, 'string'),         // 12 : Remark
						 array(1, 'string'));         // 13 : Remark indicator
	
	$size = exec('ls -l '.$folder.$file['onhand_name'].' | awk \'{print $5}\'');
	
	if ((!is_numeric($size)) || ($size == '0'))
	{
		echo '#'.date("G:i:s").'###ERROR_INCORRECT_FILE : '.$folder.$file['onhand_name'].'<BR><BR>';
		return -1;
	}
	
	exec('cmp -s '.$folder.$file['onhand_name'].' '.$folder.$file['onhand_name'].'.BAK', $cmp_result, $cmp);
	
	if ($cmp == 0)
	{
		echo '#'.date("G:i:s").'###SAME_FILE...<BR><BR>';
		return 1;
	}
	else
	{
		exec('rm -f '.$folder.$file['onhand_name'].'.BAK');
		exec('cp '.$folder.$file['onhand_name'].' '.$folder.$file['onhand_name'].'.BAK');
	}
	
	if ($global['echo']) {
		echo '#'.date("G:i:s").'##LOADING_STOCK_FROM_FILE...';
	}
	
	$product_file_data = null;
	$i = 0;
	
	// Fill product file data table
	$fs = fopen($folder.$file['onhand_name'], 'r');
	
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
							$product_file_data[$i][$j] = intval($str);
						} else if ($file_format[$j][1] == 'decimal') {
							$product_file_data[$i][$j] = floatval(str_replace(',', '.', $str));
						} else {
							$product_file_data[$i][$j] = $str;
						}
						
						$len += $file_format[$j][0];
					}
				}
				
				$i++;
			}
	   	}
	   	
		fclose($fs);
	}
	
	if ($i == 0) {
		echo '#'.date("G:i:s").'###ERROR_INCORRECT_FILE : '.$folder.$file['onhand_name'].'<BR><BR>';
		return -1;
	}
	
	if ($global['echo']) {
		echo $i.'_LINES<BR>#'.date("G:i:s").'##LOADING_REFERENCES_FROM_DATABASE...';
	}
	
	$product_database_data = null;
	$i = 0;
	
	$sql_query = 'SELECT id, ' .
				 'reference, ' .
				 'minimum_order, ' .
				 'multiple ' .
				 'FROM product ' .
				 'ORDER BY reference';
	
	$result = mysql_select_query($sql_query);

	if ($result) {
		$i = 0;

		while ($row = mysql_fetch_array($result, MYSQL_NUM)) {
			$product_database_data[$i][0] = $row[0];                // ID
			$product_database_data[$i][1] = $row[1];                // Reference
			$product_database_data[$i][2] = $row[2];                // Minimum order
			$product_database_data[$i][3] = $row[3];                // Multiple

			$i++;
		}
	}
	
	if ($global['echo']) {
		echo $i.'_REFERENCES<BR>#'.date("G:i:s").'##COMPUTING_REFERENCE_TO_UPDATE...';
	}
	
	$update_queries = '';
	$to_update = 0;
	
	/* Test if a product already exists :            */
	/* If : update                                   */
	if ($product_file_data != null) {
		for ($i = 0; $i < count($product_file_data); $i++) {
		
			if (isset($product_database_data)) {
				for ($j = 0; $j < count($product_database_data); $j++) {
					
					if ($product_file_data[$i][0] == $product_database_data[$j][1]) {
						
						if (($product_file_data[$i][6] != $product_database_data[$j][2]) ||
							($product_file_data[$i][7] != $product_database_data[$j][3])) {
							
							$sql_query = 'UPDATE product ' .
										 'SET minimum_order = '.mysql_format_to_string($product_file_data[$i][6]).', ' .
										 'multiple = '.mysql_format_to_string($product_file_data[$i][7]).' ' .
										 'WHERE id = '.mysql_format_to_number($product_database_data[$j][0]).' LIMIT 1;';
							
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
		echo $to_update.'_REFERENCES_TO_UPDATE<BR>';
	}
	
	if ($update_queries != '') {
		if ($global['echo']) {
			echo '#'.date("G:i:s").'##UPDATING_REFERENCES...<BR>';
		}
		
		echo str_replace(';', '<BR>', $update_queries);
		mysql_update_queries($update_queries);
		
		if ($global['echo']) {
			echo 'OK<BR>';
		}
	}
	
	if ($global['echo']) {
		echo '#'.date("G:i:s").'##LOADING_STOCK_FROM_DATABASE...';
	}
	
	$stock_database_data = null;
	
	$sql_query = 'SELECT stock.id, ' .
				 'product.reference, ' .
				 'warehouse.trigram, ' .
				 'stock.quantity ' .
				 'FROM stock ' .
				 'LEFT OUTER JOIN product ON stock.product_id = product.id ' .
				 'LEFT OUTER JOIN warehouse ON stock.warehouse_id = warehouse.id ' .
				 'WHERE product.site_id = (SELECT id FROM site WHERE trigram = '.mysql_format_to_string($site).') ' .
			     'ORDER BY product.reference';
	
	$result = mysql_select_query($sql_query);

	if ($result) {
		$i = 0;

		while ($row = mysql_fetch_array($result, MYSQL_NUM)) {
			$stock_database_data[$i][0] = $row[0];                // ID
			$stock_database_data[$i][1] = $row[1];                // Product
			$stock_database_data[$i][2] = $row[2];                // warehouse
			$stock_database_data[$i][3] = $row[3];                // Quantity

			$i++;
		}
	}
	
	if ($global['echo']) {
		echo $i.'_REFERENCES<BR>#'.date("G:i:s").'##COMPUTING_STOCK_TO_UPDATE...';
	}
	
	$to_add = 0;
	$to_update = 0;
	
	$insert_queries = '';
	$update_queries = '';
	
	/* Test if a product already exists :            */
	/* If : update                                   */
	/* Else : add                                    */
	if ($product_file_data != null) {
		for ($i = 0; $i < count($product_file_data); $i++) {
		
			$is_in_database = false;
			
			if ($stock_database_data != null) {
				for ($j = 0; $j < count($stock_database_data); $j++) {
					
					if (($product_file_data[$i][0] == $stock_database_data[$j][1])
					 && ($product_file_data[$i][11] == $stock_database_data[$j][2])) {
						if ($product_file_data[$i][2] != $stock_database_data[$j][3]) {
							
							$sql_query = 'UPDATE stock ' .
										 'SET quantity = '.mysql_format_to_number($product_file_data[$i][2]).' ' .
										 'WHERE id = '.mysql_format_to_number($stock_database_data[$j][0]).' LIMIT 1;';
							
							$update_queries .= $sql_query;
							$to_update++;
						}
						
						$is_in_database = true;
						break;
					}
				}
			}
			
			if (!$is_in_database) {
				$sql_query = 'INSERT INTO stock (id, product_id, warehouse_id, quantity) ' . 
							 'VALUES ' . 
							 '(NULL, ' . 
							 '(SELECT id FROM product WHERE reference = '.mysql_format_to_string($product_file_data[$i][0]).'), ' .
							 '(SELECT id FROM warehouse WHERE trigram = '.mysql_format_to_string($product_file_data[$i][11]).'), ' .
							 ''.mysql_format_to_number($product_file_data[$i][2]).');';
				
				$insert_queries .= $sql_query;
				$to_add++;
			}
		}
	}
	
	if ($global['echo']) {
		echo $to_add.'_REFERENCES_TO_ADD_'.$to_update.'_TO_UPDATE<BR>';
	}
	
	if ($insert_queries != '') {
		if ($global['echo']) {
			echo '#'.date("G:i:s").'##ADDING_REFERENCES...<BR>';
		}
		
		echo str_replace(';', '<BR>', $insert_queries);
		mysql_insert_queries($insert_queries);
		$insert_queries = '';
		
		if ($global['echo']) {
			echo 'OK<BR>';
		}
	}
	
	if ($update_queries != '') {
		if ($global['echo']) {
			echo '#'.date("G:i:s").'##UPDATING_REFERENCES...<BR>';
		}
		
		echo str_replace(';', '<BR>', $update_queries);
		mysql_update_queries($update_queries);
		$update_queries = '';
		
		if ($global['echo']) {
			echo 'OK<BR>';
		}
	}
	
	if ($global['echo']) {
		echo '#'.date("G:i:s").'##COMPUTING_STOCK_TO_DELETE...';
	}
	
	$to_delete = 0;
	
	$delete_queries = '';
	
	if ($stock_database_data != null) {
		for ($i = 0; $i < count($stock_database_data); $i++) {
		
			$is_in_file = false;
			
			if ($product_file_data != null) {
				for ($j = 0; $j < count($product_file_data); $j++) {
				
					if (($product_file_data[$j][0] == $stock_database_data[$i][1])
					 && ($product_file_data[$j][11] == $stock_database_data[$i][2])) {
						$is_in_file = true;
					}
				}
			}
			
			if (!$is_in_file) {
				$sql_query = 'DELETE FROM stock ' .
							 'WHERE id = '.mysql_format_to_number($stock_database_data[$i][0]).' LIMIT 1;';
				
				$delete_queries .= $sql_query;
				$to_delete++;
			}
		}
	}
	
	if ($global['echo']) {
		echo $to_delete.'_REFERENCES_TO_DELETE<BR>';
	}
	
	$sql_query = 'DELETE FROM stock ' .
				 'WHERE quantity = 0;';
	
	$delete_queries .= $sql_query;
	
	if ($delete_queries != '') {
		if ($global['echo']) {
			echo '#'.date("G:i:s").'##DELETING_REFERENCES...<BR>';
		}
		
		echo str_replace(';', '<BR>', $delete_queries);
		mysql_delete_queries($delete_queries);
		
		if ($global['echo']) {
			echo 'OK<BR>';
		}
	}
	
	if ($global['echo']) {
		echo '#'.date("G:i:s").'###ONHAND.DAT_FILE_IMPORT_ENDS!<BR><BR>';
	}
	
	return 0;
}


function import_receipts($folder, $site)
{
	global $file;
	global $global;
	
	if ($global['echo']) {
		echo '#'.date("G:i:s").'###RECEIPTS.DAT_FILE_IMPORT_STARTS<BR>';
	}
	
	// RECEIPTS.DAT file format
	$file_format = array(array(17, 'string'),         // 0 : Reference
						 array(3, 'string'),          // 1 : Location
						 array(8, 'number'),          // 2 : Quantity
						 array(1, 'string'),          // 3 : Filler
						 array(9, 'string'),          // 4 : PO number
						 array(51, 'string'),         // 5 : Remark
						 array(6, 'string'));         // 8 : Date
	
	$size = exec('ls -l '.$folder.$file['receipts_name'].' | awk \'{print $5}\'');
	
	if (!is_numeric($size))
	{
		echo '#'.date("G:i:s").'###ERROR_INCORRECT_FILE : '.$folder.$file['receipts_name'].'<BR><BR>';
		return -1;
	}
	
	exec('cmp -s '.$folder.$file['receipts_name'].' '.$folder.$file['receipts_name'].'.BAK', $cmp_result, $cmp);
	
	if ($cmp == 0)
	{
		echo '#'.date("G:i:s").'###SAME_FILE...<BR><BR>';
		return 1;
	}
	else
	{
		exec('rm -f '.$folder.$file['receipts_name'].'.BAK');
		exec('cp '.$folder.$file['receipts_name'].' '.$folder.$file['receipts_name'].'.BAK');
	}
	
	
	if ($global['echo']) {
		echo '#'.date("G:i:s").'##LOADING_WO_FROM_FILE... ';
	}
	
	$receipts_file_data = null;
	$i = 0;
	
	// Fill product file data table
	$fs = fopen($folder.$file['receipts_name'], 'r');
	
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
							$receipts_file_data[$i][$j] = intval($str);
						} else if ($file_format[$j][1] == 'decimal') {
							$receipts_file_data[$i][$j] = floatval(str_replace(',', '.', $str));
						} else {
							$receipts_file_data[$i][$j] = $str;
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
		echo $i.'_LINES<BR>#'.date("G:i:s").'##LOADING_WO_FROM_DATABASE... ';
	}
	
	$receipts_database_data = null;
	$i = 0;
	
	$sql_query = 'SELECT receipts.id, ' .
				 'receipts.number, ' .
				 'product.reference, ' .
				 'receipts.quantity, ' .
				 'DATE_FORMAT(receipts.date, \'%y%m%d\') ' .
				 'FROM receipts ' .
				 'LEFT OUTER JOIN product ON receipts.product_id = product.id ' .
				 'WHERE product.site_id = (SELECT id FROM site WHERE trigram = '.mysql_format_to_string($site).') ' .
			     'ORDER BY receipts.number';
	
	$result = mysql_select_query($sql_query);

	if ($result) {
		$i = 0;

		while ($row = mysql_fetch_array($result, MYSQL_NUM)) {
			$receipts_database_data[$i][0] = $row[0];                // ID
			$receipts_database_data[$i][1] = $row[1];                // Number
			$receipts_database_data[$i][2] = $row[2];                // Reference
			$receipts_database_data[$i][3] = $row[3];                // Quantity
			$receipts_database_data[$i][4] = $row[4];                // Date

			$i++;
		}
	}
	
	if ($global['echo']) {
		echo $i.'_WO<BR>#'.date("G:i:s").'##COMPUTING_WO_TO_UPDATE... ';
	}
	
	$to_add = 0;
	$to_update = 0;
	
	$insert_queries = '';
	$update_queries = '';
	
	/* Test if an PO already exists :            */
	/* If : update                                   */
	if ($receipts_file_data != null) {
		for ($i = 0; $i < count($receipts_file_data); $i++) {
		
			$is_in_database = false;
			
			if (isset($receipts_database_data)) {
				for ($j = 0; $j < count($receipts_database_data); $j++) {
					
					if (($receipts_file_data[$i][4] == $receipts_database_data[$j][1]) &&
						($receipts_file_data[$i][0] == $receipts_database_data[$j][2])) {
						
						if (($receipts_file_data[$i][2] != $receipts_database_data[$j][3]) ||
							($receipts_file_data[$i][6] != $receipts_database_data[$j][4])) {
							
							$sql_query = 'UPDATE receipts ' .
										 'SET quantity = '.mysql_format_to_number($receipts_file_data[$i][2]).', ' .
										 'date = '.mysql_format_value_to_date($receipts_file_data[$i][6], '%y%m%d').' ' .


										 'WHERE id = '.mysql_format_to_number($receipts_database_data[$j][0]).' LIMIT 1;';
							
							$update_queries .= $sql_query;
							$to_update++;
						}
						
						$is_in_database = true;
						break;
					}
				}
			}
			
			if (!$is_in_database) {
				$sql_query = 'INSERT INTO receipts (id, product_id, number, quantity, date) ' . 
							 'VALUES ' . 
							 '(NULL, ' . 
							 '(SELECT id FROM product WHERE reference = '.mysql_format_to_string($receipts_file_data[$i][0]).'), ' .
							 ''.mysql_format_to_string($receipts_file_data[$i][4]).', ' .
							 ''.mysql_format_to_number($receipts_file_data[$i][2]).', ' .
							 ''.mysql_format_value_to_date($receipts_file_data[$i][6], '%y%m%d').');';
				
				$insert_queries .= $sql_query;
				$to_add++;
			}
		}
	}
	
	if ($global['echo']) {
		echo $to_add.'_WO_TO_ADD_'.$to_update.'_TO_UPDATE<BR>';
	}
	
	if ($insert_queries != '') {
		if ($global['echo']) {
			echo '#'.date("G:i:s").'##ADDING_WO...<BR>';
		}
		
		echo str_replace(';', '<BR>', $insert_queries);
		mysql_insert_queries($insert_queries);
		$insert_queries = '';
		
		if ($global['echo']) {
			echo 'OK<BR>';
		}
	}
	
	if ($update_queries != '') {
		if ($global['echo']) {
			echo '#'.date("G:i:s").'##UPDATING_WO...<BR>';
		}
		
		echo str_replace(';', '<BR>', $update_queries);
		mysql_update_queries($update_queries);
		$update_queries = '';
		
		if ($global['echo']) {
			echo 'OK<BR>';
		}
	}
	
	if ($global['echo']) {
		echo '#'.date("G:i:s").'##COMPUTING_WO_TO_DELETE... ';
	}
	
	$to_delete = 0;
	$delete_queries = '';
	

	if ($receipts_database_data != null) {
		for ($i = 0; $i < count($receipts_database_data); $i++) {
		
			$is_in_file = false;
			
			if ($receipts_file_data != null) {
				for ($j = 0; $j < count($receipts_file_data); $j++) {
				
					if (($receipts_file_data[$j][4] == $receipts_database_data[$i][1]) &&
						($receipts_file_data[$j][0] == $receipts_database_data[$i][2])) {
						$is_in_file = true;
					}
				}
			}
			
			if (!$is_in_file) {
				$sql_query = 'DELETE FROM receipts ' .
							 'WHERE id = '.mysql_format_to_number($receipts_database_data[$i][0]).' LIMIT 1;';
				
				$delete_queries .= $sql_query;
				$to_delete++;
			}
		}
	}
	
	if ($global['echo']) {
		echo $to_delete.'_WO_TO_DELETE<BR>';
	}
	
	if ($delete_queries != '') {
		if ($global['echo']) {
			echo '#'.date("G:i:s").'##DELETING_WO...<BR>';
		}
		
		echo str_replace(';', '<BR>', $delete_queries);
		mysql_delete_queries($delete_queries);
		
		if ($global['echo']) {
			echo 'OK<BR>';
		}
	}
	
	if ($global['echo']) {
		echo '#'.date("G:i:s").'###RECEIPTS.DAT_FILE_IMPORT_ENDS!<BR><BR>';
	}
	
	return 0;
}

/////////////////////////////////////////////////////////////////////////////////////
//////////////////////// IMPORT /////////////////////////////////////////////////////
/////////////////////////////////////////////////////////////////////////////////////



import_neg();





?>