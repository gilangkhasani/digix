<?php
	function import_data_msisdn($file_name,$title){
		//tempat file csv berada
		define('CSV_PATH','file/import/');

		// nama file csv
		$csv_file = CSV_PATH . $file_name; 
		$created_by = $_SESSION['sangkuriang']->username;
			
		if (($getfile = fopen($csv_file, "r")) !== FALSE && ! feof($getfile)) {
			$n = 0;
			while (($data = fgetcsv($getfile, 1000, ",")) !== FALSE) {
				
				$explode_tanggal = explode("/", $data[0]);
				
				if(strlen($explode_tanggal[0]) == 1){
					$month = "0".$explode_tanggal[0];
				} else {
					$month = $explode_tanggal[0];
				}
				
				if(count($explode_tanggal) > 1){
					$tanggal = $explode_tanggal[2]."-".$month."-".$explode_tanggal[1];
				} else {
					$tanggal = $data[0];
				}
				
				$msisdn = $data[1];
				$branch = $data[2];
				$cluster = $data[3];
				$department = $data[4];
				$nama_event = $data[5];
				$insert = '
					INSERT INTO msisdn 
					(tanggal, msisdn, branch, cluster, department, nama_event,  created_date, created_by)
					VALUES("'.$tanggal.'", "'.$msisdn.'", "'.$branch.'", "'.$cluster.'", "'.$department.'", "'.$nama_event.'", NOW(), "'.$created_by.'")
				';
				mysql_query($insert);
				$n++;
			}
		}
	}
?>