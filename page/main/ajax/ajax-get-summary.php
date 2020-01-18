<?php
	$server = "localhost";
	$user = "itbs";
	$password = "itbs123";
	$db_name = "85152_lokasi";
	$con = mysqli_connect("$server","$user","$password","$db_name");
	// Check connection
	if (mysqli_connect_errno()){
		echo "Failed to connect to MySQL: " . mysqli_connect_error();
	}
	date_default_timezone_set("Asia/Jakarta");
	
	$kondisi = "";
	$auth = $_GET['auth'];
	
	$query_db = "
		SELECT *
		FROM tbl_user a 
		LEFT JOIN tbl_person b ON (a.id_person = b.id_person)
		LEFT JOIN tbl_sekolah c ON (b.id_sekolah = c.id_sekolah)
		WHERE password = '".$auth."'
		AND level != 'murid'
		AND status = 'web'
	";
	
	$sql = mysqli_query($con, $query_db);
	
	$count = mysqli_num_rows($sql);
	$row = mysqli_fetch_object($sql);
	$kondisi = "AND id_sekolah = '".$_GET['id_sekolah']."'";
	if($row->level == 'su'){
		$kondisi = "";
	}
	(!empty($_GET['tanggal'])) ? $tanggal = $_GET['tanggal'] : $tanggal = date('Y-m-d');

	$query = "
		SELECT a.nama_kelas, a.nama_sekolah,
		COUNT(*) AS total_absen,
		COUNT(CASE WHEN checkin_kesimpulan = 'Tepat Waktu' THEN 1 ELSE NULL END) AS total_tepat_waktu,
		COUNT(CASE WHEN checkin_kesimpulan = 'Telat' THEN 1 ELSE NULL END) AS total_telat
		FROM (
			SELECT a.*, c.id_sekolah, c.nama_sekolah, b.nama_kelas, b.jam_masuk, b.jam_selesai,
			CASE 
				WHEN TIME(a.checkin_time) <= b.jam_masuk THEN 'Tepat Waktu'
				ELSE 'Telat'
			END AS checkin_kesimpulan
			FROM tbl_absen a 
			JOIN tbl_kelas b ON (a.id_kelas = b.id_kelas)
			JOIN tbl_sekolah c ON (b.id_sekolah = c.id_sekolah)
		) a
		WHERE a.mydate = '".$tanggal."'
		$kondisi
		GROUP BY a.id_kelas
	";
	
	$sql = mysqli_query($con, $query);
	
	$count = mysqli_num_rows($sql);
	
	$output = array();
	
	$counter = 0;
	if($count > 0){
		$output['count'] = $count;
		$output['status'] = TRUE;
		while($result = mysqli_fetch_object($sql)){
			$output['result'][] = $result;
			$output['categories'][] = $result->nama_kelas;
			$output['series'][0]['name'] = "Tepat Waktu";
			$output['series'][0]['data'][] = (int)$result->total_tepat_waktu;
			$output['series'][1]['name'] = "Telat";
			$output['series'][1]['data'][] = (int)$result->total_telat;
			$counter++;
		}
	} else {
		$output['count'] = $count;
		$output['status'] = FALSE;
	}
	
	echo json_encode($output);
?>