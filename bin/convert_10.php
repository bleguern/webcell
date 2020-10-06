<?php

// Nom du dossier des fichiers de Troyes
$file['tre_folder'] = '/var/www/html/webcell/up/tre/';
$file['export_tre_folder'] = '/var/www/html/webcell/up/tre/10/';

// Nom du dossier des fichiers de La Monnerie
$file['lam_folder'] = '/var/www/html/webcell/up/lam/';
$file['export_lam_folder'] = '/var/www/html/webcell/up/lam/10/';

// Nom du dossier des fichiers de Moult
$file['mlt_folder'] = '/var/www/html/webcell/up/mlt/';
$file['export_mlt_folder'] = '/var/www/html/webcell/up/mlt/10/';

// Nom du dossier des fichiers de Negoce
$file['neg_folder'] = '/var/www/html/webcell/up/neg/';
$file['export_neg_folder'] = '/var/www/html/webcell/up/neg/10/';

// Nom des fichiers
$file['dsmois_name']    = 'DSMOIS.DAT';
$file['dsjour_name']    = 'DSJOUR.DAT';
$file['onhand_name']    = 'ONHAND.DAT';
$file['custords_name']  = 'CUSTORDS.DAT';
$file['receipts_name']  = 'RECEIPTS.DAT';


function export($site)
{
	global $file;
	
	$site = strtolower($site);
	
	$folder = $file[$site.'_folder'];
	$export_folder = $file['export_'.$site.'_folder'];
	
	if (!is_dir($export_folder)) {
		echo exec('mkdir '.$export_folder);
	} else {
		if (file_exists($export_folder.$file['dsmois_name'])) {
			exec('cp '.$export_folder.$file['dsmois_name'].' '.$export_folder.$file['dsmois_name'].'.BAK');
		}
		
		if (file_exists($export_folder.$file['dsjour_name'])) {
			exec('cp '.$export_folder.$file['dsjour_name'].' '.$export_folder.$file['dsjour_name'].'.BAK');
		}
		
		if (file_exists($export_folder.$file['onhand_name'])) {
			exec('cp '.$export_folder.$file['onhand_name'].' '.$export_folder.$file['onhand_name'].'.BAK');
		}
		
		if (file_exists($export_folder.$file['custords_name'])) {
			exec('cp '.$export_folder.$file['custords_name'].' '.$export_folder.$file['custords_name'].'.BAK');
		}
		
		if (file_exists($export_folder.$file['receipts_name'])) {
			exec('cp '.$export_folder.$file['receipts_name'].' '.$export_folder.$file['receipts_name'].'.BAK');
		}
	}
	
	// DSMOIS.DAT
	$fs = fopen($folder.$file['dsmois_name'], 'r');
	$export_fs = fopen($export_folder.$file['dsmois_name'], 'w');
	
	if ($fs && $export_fs) {
		while (!feof($fs)) {
			$buffer = fgets($fs);
			
			if (strlen($buffer) > 97) {
			
				$str1 = substr($buffer, 0, 20);
				$str1 = str_pad($str1, 78);
				$str2 = substr($buffer, 20, 77);
				
				$export_buffer = $str1.$str2.'
';
				
				fputs($export_fs, $export_buffer);
			}
	   	}
	   	
		fclose($fs);
		fclose($export_fs);
	}
	
	// DSJOUR.DAT
	$fs = fopen($folder.$file['dsjour_name'], 'r');
	$export_fs = fopen($export_folder.$file['dsjour_name'], 'w');
	
	if ($fs && $export_fs) {
		while (!feof($fs)) {
			$buffer = fgets($fs);
			
			if (strlen($buffer) > 97) {
			
				$str1 = substr($buffer, 0, 20);
				$str1 = str_pad($str1, 78);
				$str2 = substr($buffer, 20, 77);
				
				$export_buffer = $str1.$str2.'
';
				
				fputs($export_fs, $export_buffer);
			}
	   	}
	   	
		fclose($fs);
		fclose($export_fs);
	}
	
	// ONHAND.DAT
	$fs = fopen($folder.$file['onhand_name'], 'r');
	$export_fs = fopen($export_folder.$file['onhand_name'], 'w');
	
	if ($fs && $export_fs) {
		while (!feof($fs)) {
			$buffer = fgets($fs);
			
			if (strlen($buffer) > 86) {
			
				$str1 = substr($buffer, 0, 20);
				$str1 = str_pad($str1, 78);
				$str2 = substr($buffer, 20, 66);
				
				$export_buffer = $str1.$str2.'
';
				
				fputs($export_fs, $export_buffer);
			}
	   	}
	   	
		fclose($fs);
		fclose($export_fs);
	}
	
	// CUSTORDS.DAT
	$fs = fopen($folder.$file['custords_name'], 'r');
	$export_fs = fopen($export_folder.$file['custords_name'], 'w');
	
	if ($fs && $export_fs) {
		while (!feof($fs)) {
			$buffer = fgets($fs);
			
			if (strlen($buffer) > 90) {
			
				$str1 = substr($buffer, 0, 20);
				$str1 = str_pad($str1, 78);
				$str2 = substr($buffer, 20, 64);
				$str3 = substr($buffer, 84, 6);
				$str3 = '20'.$str3;
				
				$export_buffer = $str1.$str2.$str3.'
';
				
				fputs($export_fs, $export_buffer);
			}
	   	}
	   	
		fclose($fs);
		fclose($export_fs);
	}
	
	// RECEIPTS.DAT
	$fs = fopen($folder.$file['receipts_name'], 'r');
	$export_fs = fopen($export_folder.$file['receipts_name'], 'w');
	
	if ($fs && $export_fs) {
		while (!feof($fs)) {
			$buffer = fgets($fs);
			
			if (strlen($buffer) > 95) {
			
				$str1 = substr($buffer, 0, 20);
				$str1 = str_pad($str1, 78);
				$str2 = substr($buffer, 20, 69);
				$str3 = substr($buffer, 89, 6);
				$str3 = '20'.$str3;
				
				$export_buffer = $str1.$str2.$str3.'
';
				
				fputs($export_fs, $export_buffer);
			}
	   	}
	   	
		fclose($fs);
		fclose($export_fs);
	}
}

export('MLT');
export('NEG');
export('TRE');
export('LAM');

?>