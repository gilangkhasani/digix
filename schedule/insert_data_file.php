<?php 
    $root_page = '/var/www/html/boopati/';
    require_once($root_page.'function/fungsi-sql.php');
	require_once $root_page.'includes/database.php';
	
	$q = "SELECT * FROM boopati_file_import WHERE read_status IS NULL";
	$data = db_query2list($q);
	foreach($data as $result){
		$file_import = $result->file_import;
		$id_file_import = $result->id_file_import;
		$flag = $result->flag;
		$table_name = $result->table_name;
		$id_region = $result->id_region;
		$file = $root_page."file_upload/".$file_import;
		if (file_exists($file)) {
            $fh = fopen($file,'r');
			$counter = 0;
			$count_insert_uploaded = 0;
			$count_insert_claimed = 0;
			while ($data = fgets($fh)) {
				$res = explode("\t", $data);
				$region = $res[0];
				$msisdn = $res[1];
				$region_lacci = $res[2];
				$cluster = $res[3];
				$kab = $res[4];
				$kec = trim(preg_replace('/\s\s+/', ' ', $res[5]));
				$q_claim = "
					SELECT * 
					FROM boopati_whitelist_claim 
					WHERE msisdn = '".$msisdn."'
					AND flag = '".$flag."'
				";
				$count_check_claim = db_num_rows($q_claim);
				
				$query_insert = "";
				if($count_check_claim == 0){
					$query_insert = "
						INSERT INTO ".$table_name." (region, msisdn, region_lacci, cluster_lacci, kabupaten, kecamatan, date_upload)
						VALUES('".$region."', '".$msisdn."', '".$region_lacci."', '".$cluster."', '".$kab."', '".$kec."',CURDATE())
					";
					$count_insert_uploaded++;
				} else {
					$query_insert = "
						INSERT INTO boopati_whitelist_claimed (region, msisdn, region_lacci, cluster_lacci, kabupaten, kecamatan, date_upload, table_name, flag, id_file_import)
						VALUES('".$region."', '".$msisdn."', '".$region_lacci."', '".$cluster."', '".$kab."', '".$kec."',NOW(), '".$table_name."', '".$flag."', '".$id_file_import."')
					";
					$count_insert_claimed++;
				}
				db_query_insert($query_insert);
				
				$counter++;
			}
			$query_insert = "
				INSERT INTO boopati_file_import_summary (id_file_import, claimed, uploaded, date_upload)
				VALUES('".$id_file_import."', '".$count_insert_claimed."', '".$count_insert_uploaded."',CURDATE())
			";
			db_query_insert($query_insert);
			$q_update = "
				UPDATE boopati_file_import
				SET read_status = 1
				WHERE id_file_import = '".$id_file_import."'
			";
			db_query_insert($q_update);
			//unlink($file);
			
			fclose($fh);
        }
	}
?>