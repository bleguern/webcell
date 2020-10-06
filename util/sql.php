<?php
	include_once(dirname(__FILE__).'/../config/global.php');
	include_once(dirname(__FILE__).'/../config/sql.php');

function mysql_simple_select_query($sql_query) {

	global $mysql;
	global $global;
	
	$value = NULL;

	if(mysql_connect($mysql['host'], $mysql['user_name'], $mysql['user_password'], true)) {

	    if(mysql_select_db($mysql['database'])) {
			$result = mysql_query($sql_query);
			
			if($result) {
				$value = mysql_fetch_row($result);
				$value = $value[0];
			} else {
				if ((mysql_errno() != 0)  && $global['debug']) {
					echo '### ERREUR MYSQL : '.mysql_errno().' : '.mysql_error().' : '.$sql_query.'<BR>';
				}
			}
			
			mysql_free_result($result);
		} else {
			if ($global['debug']) {
				echo '### ERREUR MYSQL  : '.mysql_errno().' : '.mysql_error().'<BR>';
			}
		}
	} else {
		if ($global['debug']) {
			echo '### ERREUR MYSQL  : '.mysql_errno().' : '.mysql_error().'<BR>';
		}
	}

	return $value;
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
					echo '### ERREUR MYSQL : '.mysql_errno().' : '.mysql_error().' : '.$sql_query.'<BR>';
				}
			}
		} else {
			if ($global['debug']) {
				echo '### ERREUR MYSQL  : '.mysql_errno().' : '.mysql_error().'<BR>';
			}
		}
	} else {
		if ($global['debug']) {
			echo '### ERREUR MYSQL  : '.mysql_errno().' : '.mysql_error().'<BR>';
		}
	}

	return NULL;
}


function mysql_insert_query($sql_query) {

	global $mysql;
	global $global;
	
	$success = false;

	$link = mysql_connect($mysql['host'], $mysql['user_name'], $mysql['user_password'], true);
	
	if($link) {

	    if(mysql_select_db($mysql['database'])) {

			if(mysql_query($sql_query) && (mysql_affected_rows() >= 1)) {
				$success = true;
			} else {
				if ((mysql_errno() != 0)  && $global['debug']) {
					echo '### ERREUR MYSQL : '.mysql_errno().' : '.mysql_error().' : '.$sql_query.'<BR>';
				}
			}
		} else {
			if ($global['debug']) {
				echo '### ERREUR MYSQL  : '.mysql_errno().' : '.mysql_error().'<BR>';
			}
		}
		
		mysql_close($link);
	} else {
		if ($global['debug']) {
			echo '### ERREUR MYSQL  : '.mysql_errno().' : '.mysql_error().'<BR>';
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
							echo '### ERREUR MYSQL : '.mysql_errno().' : '.mysql_error().' : '.$sql_query.'<BR>';
						}
					}
				}
			}
		} else {
			if ($global['debug']) {
				echo '### ERREUR MYSQL  : '.mysql_errno().' : '.mysql_error().'<BR>';
			}
		}
	} else {
		if ($global['debug']) {
			echo '### ERREUR MYSQL  : '.mysql_errno().' : '.mysql_error().'<BR>';
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
					echo '### ERREUR MYSQL : '.mysql_errno().' : '.mysql_error().' : '.$sql_query.'<BR>';
				}
			}
		} else {
			if ($global['debug']) {
				echo '### ERREUR MYSQL  : '.mysql_errno().' : '.mysql_error().'<BR>';
			}
		}
	} else {
		if ($global['debug']) {
			echo '### ERREUR MYSQL  : '.mysql_errno().' : '.mysql_error().'<BR>';
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
							echo '### ERREUR MYSQL : '.mysql_errno().' : '.mysql_error().' : '.$sql_query.'<BR>';
						}
					}
				}
			}
		} else {
			if ($global['debug']) {
				echo '### ERREUR MYSQL  : '.mysql_errno().' : '.mysql_error().'<BR>';
			}
		}
	} else {
		if ($global['debug']) {
			echo '### ERREUR MYSQL  : '.mysql_errno().' : '.mysql_error().'<BR>';
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
					echo '### ERREUR MYSQL : '.mysql_errno().' : '.mysql_error().' : '.$sql_query.'<BR>';
				}
			}
		} else {
			if ($global['debug']) {
				echo '### ERREUR MYSQL  : '.mysql_errno().' : '.mysql_error().'<BR>';
			}
		}
	} else {
		if ($global['debug']) {
			echo '### ERREUR MYSQL  : '.mysql_errno().' : '.mysql_error().'<BR>';
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
							echo '### ERREUR MYSQL : '.mysql_errno().' : '.mysql_error().' : '.$sql_query.'<BR>';
						}
					}
				}
			}
		} else {
			if ($global['debug']) {
				echo '### ERREUR MYSQL  : '.mysql_errno().' : '.mysql_error().'<BR>';
			}
		}
	} else {
		if ($global['debug']) {
			echo '### ERREUR MYSQL  : '.mysql_errno().' : '.mysql_error().'<BR>';
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
					echo '### ERREUR MYSQL : '.mysql_errno().' : '.mysql_error().' : '.$sql_query.'<BR>';
				}
			}
		} else {
			if ($global['debug']) {
				echo '### ERREUR MYSQL  : '.mysql_errno().' : '.mysql_error().'<BR>';
			}
		}
	} else {
		if ($global['debug']) {
			echo '### ERREUR MYSQL  : '.mysql_errno().' : '.mysql_error().'<BR>';
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

function mysql_print_select_option($sql_query) {
	global $mysql;
	global $global;
	
	$script = '<OPTION VALUE=\'\' SELECTED></OPTION>';

	if(mysql_connect($mysql['host'], $mysql['user_name'], $mysql['user_password'], true)) {

	    if(mysql_select_db($mysql['database'])) {
			$result = mysql_query($sql_query);

			if($result) {
				while ($row = mysql_fetch_array($result, MYSQL_NUM)) {
					$script .= '<OPTION VALUE=\''.$row[0].'\'>'.$row[1].'</OPTION>';
				}
			} else {
				if ((mysql_errno() != 0)  && $global['debug']) {
					echo '### ERREUR MYSQL : '.mysql_errno().' : '.mysql_error().' : '.$sql_query.'<BR>';
				}
			}
		} else {
			if ($global['debug']) {
				echo '### ERREUR MYSQL  : '.mysql_errno().' : '.mysql_error().'<BR>';
			}
		}
	} else {
		if ($global['debug']) {
			echo '### ERREUR MYSQL  : '.mysql_errno().' : '.mysql_error().'<BR>';
		}
	}
	
	echo $script;
}

function mysql_build_javascript_table($sql_queries, $table_names) {

	global $mysql;
	global $global;
	
	$sql_query = explode(';', $sql_queries);
	$table_name = explode(';', $table_names);

	$script = '<SCRIPT TYPE=\'text/javascript\'>';

	if(count($sql_query) == count($table_name)) {

		if(mysql_connect($mysql['host'], $mysql['user_name'], $mysql['user_password'], true)) {

		    if(mysql_select_db($mysql['database'])) {

				$i = 0;

				for ($i = 0; $i < count($sql_query); $i++) {

					$result = mysql_query($sql_query[$i]);

					if($result) {

						$script .= 'var '.$table_name[$i].'Table = new Array(';
						
						$k = 0;
						$num_rows = mysql_num_rows($result);

						while ($row = mysql_fetch_array($result, MYSQL_NUM)) {

							$col_count = count($row);

							$script .= 'new Array(';

							for ($j = 0; $j < $col_count; $j++) {

								if ($j != ($col_count - 1)) {
									$script .= '\''.mysql_format($row[$j]).'\',';
								} else {
									$script .= '\''.mysql_format($row[$j]).'\'';
								}
							}

							if ($k != ($num_rows - 1)) {
								$script .= '),';
							} else {
								$script .= ')';
							}

							$k++;
						}
						
						$script .= ');';

					} else {
						if ((mysql_errno() != 0)  && $global['debug']) {
							echo '### ERREUR MYSQL : '.mysql_errno().' : '.mysql_error().' : '.$sql_query.'<BR>';
						}
					}
				}
			} else {
				if ($global['debug']) {
					echo '### ERREUR MYSQL  : '.mysql_errno().' : '.mysql_error().'<BR>';
				}
			}
		} else {
			if ($global['debug']) {
				echo '### ERREUR MYSQL  : '.mysql_errno().' : '.mysql_error().'<BR>';
			}
		}
	}

	$script .= '</SCRIPT>';
	
	echo $script;
}



function mysql_save_error($url, $name, $description) {

	global $mysql;
	global $global;

	$sql_query = 'INSERT INTO error (id, error_date, ip_address, url, name, description) ' .
			     'VALUES ' .
			     '(NULL, ' .
			     'NOW(), ' .
			     ''.mysql_format_to_string($_SERVER['REMOTE_ADDR']).', ' .
			     ''.mysql_format_to_string($url).', ' .
			     ''.mysql_format_to_string($name).', ' .
			     ''.mysql_format_to_string($description).')';

	if(mysql_connect($mysql['host'], $mysql['user_name'], $mysql['user_password'])) {

	    if(mysql_select_db($mysql['database'])) {

			if(mysql_query($sql_query) && (mysql_affected_rows() == 1)) {
				return true;
			} else {
				if ((mysql_errno() != 0)  && $global['debug']) {
					echo '### ERREUR MYSQL : '.mysql_errno().' : '.mysql_error().' : '.$sql_query.'<BR>';
				}
			}
		} else {
			if ($global['debug']) {
				echo '### ERREUR MYSQL  : '.mysql_errno().' : '.mysql_error().'<BR>';
			}
		}
	} else {
		if ($global['debug']) {
			echo '### ERREUR MYSQL  : '.mysql_errno().' : '.mysql_error().'<BR>';
		}
	}

	return false;
}

function mysql_save_sql_error($url, $name, $sql_query, $sql_error_no, $sql_error) {
	return mysql_save_error($url, $name, 'SQL : '.$sql_query.'\nERROR : '.$sql_error_no.' : '.$sql_error);
}

function mysql_save_log($name, $description) {

	global $mysql;
	global $global;
	
	$login = 'Anonymous';
	
	if (isset($_SESSION['login']) && ($_SESSION['login'] != '')) {
		$login = $_SESSION['login'];
	}

	$sql_query = 'INSERT INTO log (id, log_date, login, ip_address, name, description) ' .
			     'VALUES ' .
			     '(NULL, ' .
			     'NOW(), ' .
			     ''.mysql_format_to_string($login).', ' .
			     ''.mysql_format_to_string($_SERVER['REMOTE_ADDR']).', ' .
			     ''.mysql_format_to_string($name).', ' .
			     ''.mysql_format_to_string($description).')';

	if(mysql_connect($mysql['host'], $mysql['user_name'], $mysql['user_password'])) {

	    if(mysql_select_db($mysql['database'])) {

			if(mysql_query($sql_query) && (mysql_affected_rows() == 1)) {
				return true;
			} else {
				if ((mysql_errno() != 0)  && $global['debug']) {
					echo '### ERREUR MYSQL : '.mysql_errno().' : '.mysql_error().' : '.$sql_query.'<BR>';
				}
			}
		} else {
			if ($global['debug']) {
				echo '### ERREUR MYSQL  : '.mysql_errno().' : '.mysql_error().'<BR>';
			}
		}
	} else {
		if ($global['debug']) {
			echo '### ERREUR MYSQL  : '.mysql_errno().' : '.mysql_error().'<BR>';
		}
	}

	return false;
}

/*

0 : Name
1 : Size
2 : Type ("link", "string", "number", "decimal", "date", "currency")
3 : Position ("center", "left", "right")
4 : Bold ("bold", "")
5 : Link url : string
6 : Link value index : integer
7 : Link target : string
8 : Is total : boolean
9 : Is aggregate : boolean
10 : Title (description)

*/


function get_table_head($columns) {

	if (isset($_REQUEST['order'])) {
		$order = $_REQUEST['order'];
	} else {
		$order = 1;
	} 
	
	if (isset($_REQUEST['sort'])) {
		if($_REQUEST['sort'] == 'asc')
		{
			$direction = SORT_ASC;
		} else if($_REQUEST['sort'] == 'desc') {
				$direction = SORT_DESC;
		}
	} else {
		$direction = SORT_ASC;
	}

	$script = '<TABLE class="left"><TR>';
					
	for($i = 0; $i < count($columns); $i++)
	{
		$sort = 'asc';
		$post = '?';
		$title = '';
		
		if (isset($_REQUEST['order']))
		{
			if ($_REQUEST['order'] == $i)
			{
				if (isset($_REQUEST['sort']))
				{
					if($_REQUEST['sort'] == 'desc')
					{
						$sort = 'asc';
					}
					else
					{
						$sort = 'desc';
					}
				}
				else
				{
					$sort = 'desc';
				}
			}
		}
		
		foreach ($_POST as $key => $value) {
			$post .= $key.'='.$value.'&';
	   	}
		
		foreach ($_REQUEST as $key => $value) {
			if (($key != 'PHPSESSID') && ($key != 'users_resolution') && ($key != 'order') && ($key != 'sort')) {
				$post .= $key.'='.$value.'&';
			}
	   	}
		
		if (isset($columns[$i][10]) && ($columns[$i][1] != ''))
		{
			$title = $columns[$i][10];
		}

		$script .= '<TD class="header" width="'.$columns[$i][1].'"><a href="'.$post.'order='.$i.'&sort='.$sort.'" TARGET="_self" TITLE="'.$title.'">'.$columns[$i][0].'</a></TD>';
	}
	
	
	$script .= '<TD width="20"></TD></TR></TABLE>';
	
	
	echo $script;
}



/*

0 : Name
1 : Size
2 : Type ("link", "string", "number", "decimal", "date", "currency")
3 : Position ("center", "left", "right")
4 : Bold ("bold", "")
5 : Link url : string
6 : Link value index : integer
7 : Link target : string
8 : Is total : boolean
9 : Is aggregate : boolean
10 : Title (description)

*/



function get_table_result_with_query($sql_query, $columns) {

	$count = 0;
	
	if (isset($_REQUEST['order'])) {
		$order = $_REQUEST['order'];
	} else {
		$order = 1;
	} 
	
	if (isset($_REQUEST['sort'])) {
		if($_REQUEST['sort'] == 'asc')
		{
			$direction = SORT_ASC;
		} else if($_REQUEST['sort'] == 'desc') {
				$direction = SORT_DESC;
		}
	} else {
		$direction = SORT_ASC;
	} 

	$script = '';
	
	$result_table = array();
	$result = mysql_select_query($sql_query);

	if ($result) {

		$i = 0;

		while ($row = mysql_fetch_array($result, MYSQL_NUM)) {

			for ($j = 0; $j < count($row); $j++)
			{
				$result_table[$i][$j] = $row[$j];
			}
			
			$i++;
		}
	}
	
	$count = count($result_table);
	
	if ($count > 0)
	{
		foreach ($result_table as $key => $row) {
			$sort[$key]  = $row[$order];
		}
		
		array_multisort($sort, $direction, $result_table);

		$script .= '<TABLE class="left">';
		
		$alternate_row = false;
		
		for ($i = 0; $i < count($result_table); $i++)
		{
			if ($alternate_row) {
				$script .= '<TR class="alternate_row">';
			} else {
				$script .= '<TR class="row">';
			}
			
			for ($j = 0; $j < count($columns); $j++)
			{
				$script .= '<TD width="'.$columns[$j][1].'" class="'.$columns[$j][2].'" align="'.$columns[$j][3].'">';
				
				if ($columns[$j][4] == 'bold')
				{
					$script .= '<B>';
				}
				
				if ($columns[$j][2] == 'link')
				{
					$script .= '<A HREF="'.$columns[$j][5].'?history='.get_php_self().'&id='.$result_table[$i][$columns[$j][6]].'" TARGET="'.$columns[$j][7].'">';
				}
				
				if ($columns[$j][2] == 'mail')
				{
					$script .= '<A HREF="mailto:'.$result_table[$i][$j].'" TARGET="'.$columns[$j][7].'">';
				}
				
				if ($columns[$j][2] == 'number') {
					$script .= format_to_number($result_table[$i][$j]);
				} else if ($columns[$j][2] == 'decimal') {
					$script .= format_to_decimal($result_table[$i][$j]);
				} else if ($columns[$j][2] == 'boolean') {
					$script .= format_to_boolean($result_table[$i][$j]);
				} else if ($columns[$j][2] == 'cross') {
					$script .= format_to_cross($result_table[$i][$j]);
				} else if ($columns[$j][2] == 'date') {
					$script .= format_to_date($result_table[$i][$j]);
				} else if ($columns[$j][2] == 'time') {
					$script .= format_to_time($result_table[$i][$j]);
				} else if ($columns[$j][2] == 'currency') {
					$script .= format_to_currency($result_table[$i][$j], '&euro;');
				} else {
					$script .= $result_table[$i][$j];
				}
				
				if (($columns[$j][2] == 'link') || ($columns[$j][2] == 'mail'))
				{
					$script .= '</A>';
				}
				
				if ($columns[$j][4] == 'bold')
				{
					$script .= '</B>';
				}
				
				$script .= '</TD>';
			}
			
			$script .= '</TR>';
			
			$alternate_row = !$alternate_row;
		}
		
		$script .= '</TABLE>';
	}
	else
	{
		$count = 0;
	}
	
	echo $script;
	
	return $count;
}


function get_table_result_with_table($table, $columns) {

	$count = 0;
	
	if (isset($_REQUEST['order'])) {
		$order = $_REQUEST['order'] + 1;
	} else {
		$order = 2;
	} 
	
	if (isset($_REQUEST['sort'])) {
		if($_REQUEST['sort'] == 'asc')
		{
			$direction = SORT_ASC;
		} else if($_REQUEST['sort'] == 'desc') {
				$direction = SORT_DESC;
		}
	} else {
		$direction = SORT_ASC;
	} 

	$script = '';
	
	$count = count($table);
	
	if ($count > 0)
	{
		foreach ($table as $key => $row) {
			$sort[$key]  = $row[$order];
		}
		
		array_multisort($sort, $direction, $table);
	
		$script .= '<TABLE class="left">';
		
		$alternate_row = false;
		
		for ($i = 0; $i < count($table); $i++)
		{
			if ($table[$i][0] == '')
			{
				if ($alternate_row) {
					$script .= '<TR class="alternate_row">';
				} else {
					$script .= '<TR class="row">';
				}
			} else {
			
				$script .= '<TR class="'.$table[$i][0].'">';
			}
			
			for ($j = 0; $j < count($columns); $j++)
			{
				$script .= '<TD width="'.$columns[$j][1].'" class="'.$columns[$j][2].'" align="'.$columns[$j][3].'">';
				
				if ($columns[$j][4] == 'bold')
				{
					$script .= '<B>';
				}
				
				if ($columns[$j][2] == 'link')
				{
					$script .= '<A HREF="'.$columns[$j][5].'?history='.get_php_self().'&id='.$table[$i][$columns[$j][6] + 1].'" TARGET="'.$columns[$j][7].'">';
				}
				
				if ($columns[$j][2] == 'mail')
				{
					$script .= '<A HREF="mailto:'.$table[$i][$j + 1].'" TARGET="'.$columns[$j][7].'">';
				}
				
				if ($columns[$j][2] == 'number') {
					$script .= format_to_number($table[$i][$j + 1]);
				} else if ($columns[$j][2] == 'decimal') {
					$script .= format_to_decimal($table[$i][$j + 1]);
				} else if ($columns[$j][2] == 'boolean') {
					$script .= format_to_boolean($table[$i][$j + 1]);
				} else if ($columns[$j][2] == 'cross') {
					$script .= format_to_cross($table[$i][$j + 1]);
				} else if ($columns[$j][2] == 'date') {
					$script .= format_to_date($table[$i][$j + 1]);
				} else if ($columns[$j][2] == 'time') {
					$script .= format_to_time($table[$i][$j + 1]);
				} else if ($columns[$j][2] == 'currency') {
					$script .= format_to_currency($table[$i][$j + 1], '&euro;');
				} else {
					$script .= $table[$i][$j + 1];
				}
				
				if (($columns[$j][2] == 'link') || ($columns[$j][2] == 'mail'))
				{
					$script .= '</A>';
				}
				
				if ($columns[$j][4] == 'bold')
				{
					$script .= '</B>';
				}
				
				$script .= '</TD>';
			}
			
			$script .= '</TR>';
			
			$alternate_row = !$alternate_row;
		}
	
		$script .= '</TABLE>';
	}
	else
	{
		$count = 0;
	}
	
	
	echo $script;
	
	return $count;
}

/*

0 : Name
1 : Size
2 : Type ("link", "string", "number", "decimal", "date", "currency", "mail")
3 : Position ("center", "left", "right")
4 : Bold ("bold", "")
5 : Link url : string
6 : Link value index : integer
7 : Link target : string
8 : Is total : boolean
9 : Is aggregate : boolean
10 : Title (description)

*/

function get_table_result_with_table_with_total($table, $columns) {

	$count = 0;
	$show_sub = true;
	$show_aggregate = false;
	$total = array();
	$aggregate = array();
	$aggregate_total = array();
	$current = '';
	
	if (isset($_REQUEST['order'])) {
		$order = $_REQUEST['order'] + 1;
	} else {
		$order = 1;
	}
	
	if ((isset($_REQUEST['order']) && ($_REQUEST['order'] > 1)) || isset($_REQUEST['sort'])) {
		$show_sub = false;
	}
	
	if (isset($_REQUEST['sort'])) {
		if($_REQUEST['sort'] == 'asc')
		{
			$direction = SORT_ASC;
		} else if($_REQUEST['sort'] == 'desc') {
			$direction = SORT_DESC;
		}
	} else {
		$direction = SORT_ASC;
	} 

	$script = '';
	
	$count = count($table);
	
	if ($count > 0)
	{
		foreach ($table as $key => $row) {
			$sort[$key]  = $row[$order];
		}
		
		array_multisort($sort, $direction, $table);
	
		$script .= '<TABLE class="left">';
		
		$alternate_row = false;
		$show_sub_total = false;
		
		for ($i = 0; $i < count($table); $i++)
		{
			$show_sub_total = false;
			
			if ($i == 0) {
				for ($j = 0; $j < count($columns); $j++)
				{
					$total[$j] = 0;
					$aggregate[$j] = $table[0][$j + 1];
					$aggregate_total[$j] = 0;
					
					if ($columns[$j][9]) {
						$current = $table[0][$j + 1];
					}
				}
			} else {
				for ($j = 0; $j < count($columns); $j++)
				{
					if ($columns[$j][9]) {
						if ($aggregate[$j] != $table[$i][$j + 1]) {
							$show_sub_total = true;
							$aggregate[$j] = $table[$i][$j + 1];
						}
					}
				}
			}
			
			if ($show_sub_total && $show_sub) {
			
				$script .= '<TR class="sub_total_row">';
				$span = -1;
							
				for ($j = 0; $j < count($columns); $j++)
				{
					if ($columns[$j][8]) {
						if ($span == -1) {
							$span = 0;
						}
						
						if ($span == 0) {
							$script .= '<TD colspan="'.$j.'" align="right"><B>Total '.$current.' :</B></TD>';
							
							if ($i == 0)
							{
								$script .= '<TD width="'.$columns[$j][1].'" align="'.$columns[$j][3].'"><B>';
							}
							else
							{
								$script .= '<TD align="'.$columns[$j][3].'"><B>';
							}
						
							if ($columns[$j][2] == 'number') {
								$script .= format_to_number($aggregate_total[$j]);
							} else if ($columns[$j][2] == 'decimal') {
								$script .= format_to_decimal($aggregate_total[$j]);
							} else if ($columns[$j][2] == 'time') {
								$script .= format_to_time($aggregate_total[$j]);
							} else if ($columns[$j][2] == 'currency') {
								$script .= format_to_currency($aggregate_total[$j], '&euro;');
							}
							
							$script .= '</B></TD>';
							$span = 1;
						 } else {
						 	if ($i == 0)
							{
								$script .= '<TD width="'.$columns[$j][1].'" align="'.$columns[$j][3].'">';
							}
							else
							{
								$script .= '<TD align="'.$columns[$j][3].'">';
							}
						
							if ($columns[$j][2] == 'number') {
								$script .= format_to_number($aggregate_total[$j]);
							} else if ($columns[$j][2] == 'decimal') {
								$script .= format_to_decimal($aggregate_total[$j]);
							} else if ($columns[$j][2] == 'time') {
								$script .= format_to_time($aggregate_total[$j]);
							} else if ($columns[$j][2] == 'currency') {
								$script .= format_to_currency($aggregate_total[$j], '&euro;');
							}
							
							$script .= '</B></TD>';
						}
									
						if (is_numeric($table[$i][$j + 1])) {
							$total[$j] += $table[$i][$j + 1];
						}
						
						if (is_numeric($table[$i][$j + 1])) {
							$aggregate_total[$j] = $table[$i][$j + 1];
						}
					} else {
						if ($span == 1) {
							if ($i == 0)
							{
								$script .= '<TD width="'.$columns[$j][1].'"></TD>';
							}
							else
							{
								$script .= '<TD>';
							}
						}
					}
				}
				
				$script .= '</TR>';
				$show_sub_total = false;
				
				for ($j = 0; $j < count($columns); $j++)
				{
					if ($columns[$j][9]) {
						$current = $table[$i][$j + 1];
					}
				}
			} else {
				for ($j = 0; $j < count($columns); $j++)
				{
					if ($columns[$j][8]) {
					
						if (is_numeric($table[$i][$j + 1])) {
							$total[$j] += $table[$i][$j + 1];
						}
						
						if (is_numeric($table[$i][$j + 1])) {
							$aggregate_total[$j] += $table[$i][$j + 1];
						}
					}
				}
			}
			
			if ($table[$i][0] == '')
			{
				if ($alternate_row) {
					$script .= '<TR class="alternate_row">';
				} else {
					$script .= '<TR class="row">';
				}
			} else {
				$script .= '<TR class="'.$table[$i][0].'">';
			}
			
			for ($j = 0; $j < count($columns); $j++)
			{
				if ($i == 0)
				{
					$script .= '<TD width="'.$columns[$j][1].'" align="'.$columns[$j][3].'">';
				}
				else
				{
					$script .= '<TD align="'.$columns[$j][3].'">';
				}
				
				if ($columns[$j][4] == 'bold')
				{
					$script .= '<B>';
				}
				
				if ($columns[$j][2] == 'link')
				{
					$script .= '<A HREF="javascript:goto(this, \''.$columns[$j][5].'\', '.$table[$i][$columns[$j][6] + 1].')">';
				}
				
				if ($columns[$j][2] == 'mail')
				{
					$script .= '<A HREF="mailto:'.$table[$i][$j + 1].'" TARGET="'.$columns[$j][7].'">';
				}
				
				if ($columns[$j][2] == 'number') {
					$script .= format_to_number($table[$i][$j + 1]);
				} else if ($columns[$j][2] == 'decimal') {
					$script .= format_to_decimal($table[$i][$j + 1]);
				} else if ($columns[$j][2] == 'boolean') {
					$script .= format_to_boolean($table[$i][$j + 1]);
				} else if ($columns[$j][2] == 'cross') {
					$script .= format_to_cross($table[$i][$j + 1]);
				} else if ($columns[$j][2] == 'date') {
					$script .= format_to_date($table[$i][$j + 1]);
				} else if ($columns[$j][2] == 'time') {
					$script .= format_to_time($table[$i][$j + 1]);
				} else if ($columns[$j][2] == 'currency') {
					$script .= format_to_currency($table[$i][$j + 1], '&euro;');
				} else {
					$script .= $table[$i][$j + 1];
				}
				
				if (($columns[$j][2] == 'link') || ($columns[$j][2] == 'mail'))
				{
					$script .= '</A>';
				}
				
				if ($columns[$j][4] == 'bold')
				{
					$script .= '</B>';
				}
				
				$script .= '</TD>';
			}
			
			$script .= '</TR>';
			
			$alternate_row = !$alternate_row;
		}
		
		$show_sub_total = false;
		$script_sub_total = '<TR class="sub_total_row">';
		$span = -1;
		
		for ($j = 0; $j < count($columns); $j++)
		{
			if ($columns[$j][9] == true) {
				$show_aggregate = true;
			}
			
			if ($columns[$j][8] == true) {
				$show_sub_total = true;
				
				if ($span == -1) {
					$span = 0;
				}
				
				if ($span == 0) {
					$script_sub_total .= '<TD colspan="'.$j.'" align="right"><B>Total '.$current.' :</B></TD><TD align="'.$columns[$j][3].'"><B>';
					
					if ($columns[$j][2] == 'number') {
						$script_sub_total .= format_to_number($aggregate_total[$j]);
					} else if ($columns[$j][2] == 'decimal') {
						$script_sub_total .= format_to_decimal($aggregate_total[$j]);
					} else if ($columns[$j][2] == 'time') {
						$script_sub_total .= format_to_time($aggregate_total[$j]);
					} else if ($columns[$j][2] == 'currency') {
						$script_sub_total .= format_to_currency($aggregate_total[$j], '&euro;');
					}
					
					$script_sub_total .= '</B></TD>';
								
					$span = 1;
				 } else {
					$script_sub_total .= '<TD align="'.$columns[$j][3].'"><B>';
					
					if ($columns[$j][2] == 'number') {
						$script_sub_total .= format_to_number($aggregate_total[$j]);
					} else if ($columns[$j][2] == 'decimal') {
						$script_sub_total .= format_to_decimal($aggregate_total[$j]);
					} else if ($columns[$j][2] == 'time') {
						$script_sub_total .= format_to_time($aggregate_total[$j]);
					} else if ($columns[$j][2] == 'currency') {
						$script_sub_total .= format_to_currency($aggregate_total[$j], '&euro;');
					}
					
					$script_sub_total .= '</B></TD>';
				}
			} else {
				if ($span == 1) {
					$script_sub_total .= '<TD></TD>';
				}
			}
		}
		
		$script_sub_total .= '</TR>';
						
		if ($show_sub_total && $show_sub && $show_aggregate) {
			$script .= $script_sub_total;
		}
		
		
		$show_total = false;
		$script_total = '<TR class="total_row">';
		$span = -1;
		
		for ($j = 0; $j < count($columns); $j++)
		{
			if ($columns[$j][8] == true) {
				$show_total = true;
				
				if ($span == -1) {
					$span = 0;
				}
				
				if ($span == 0) {
					$script_total .= '<TD colspan="'.$j.'" align="right"><B>TOTAL :</B></TD><TD align="'.$columns[$j][3].'"><B>';
					
					if ($columns[$j][2] == 'number') {
						$script_total .= format_to_number($total[$j]);
					} else if ($columns[$j][2] == 'decimal') {
						$script_total .= format_to_decimal($total[$j]);
					} else if ($columns[$j][2] == 'time') {
						$script_total .= format_to_time($total[$j]);
					} else if ($columns[$j][2] == 'currency') {
						$script_total .= format_to_currency($total[$j], '&euro;');
					}
					
					$script_total .= '</B></TD>
								';
					$span = 1;
				} else {
					$script_total .= '<TD align="'.$columns[$j][3].'"><B>';
					
					if ($columns[$j][2] == 'number') {
						$script_total .= format_to_number($total[$j]);
					} else if ($columns[$j][2] == 'decimal') {
						$script_total .= format_to_decimal($total[$j]);
					} else if ($columns[$j][2] == 'time') {
						$script_total .= format_to_time($total[$j]);
					} else if ($columns[$j][2] == 'currency') {
						$script_total .= format_to_currency($total[$j], '&euro;');
					}
					
					$script_total .= '</B></TD>';
				}
			} else {
				if ($span == 1) {
					$script_total .= '<TD></TD>';
				}
			}
		}
		
		$script_total .= '</TR>';
						
		if ($show_total) {
			$script .= $script_total;
		}
	
		$script .= '</TABLE>';
	}
	else
	{
		$count = 0;
	}
	
	
	echo $script;
	
	return $count;
}


?>