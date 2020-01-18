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
	
	
	$query = "SELECT * FROM tbl_kelas WHERE id_sekolah = '".$_GET['id_sekolah']."'";
	
	$sql = mysqli_query($con, $query);
	
	$count = mysqli_num_rows($sql);
	
	$output = array();
	
	$counter = 0;
	while($result = mysqli_fetch_object($sql)){
		$output['result'][] = $result;
		$counter++;
	}
	
	echo json_encode($output);
?>