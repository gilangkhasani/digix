<?php
function db_query($query){
	global $database;
	$q = mysqli_query($database->connection,$query);
	$r = mysqli_fetch_object($q);
	return $r;
}
function db_query2list($query){
	global $database;
	$q = mysqli_query($database->connection,$query);
	$data = array();
	if(!$q) echo $query;
	while($r = mysqli_fetch_object($q)){
		$data[] = $r;
	}
	return $data;
}
function db_query2list_fieldedit($query, $field, $type = NULL){
	global $database;
	$q = mysqli_query($database->connection,$query);
	$data = array();
	if($type == 'array'){
		while($r = mysqli_fetch_assoc($q)){
			$data[$r[$field]] = $r;
		}
	}elseif(is_null($type)){
		while($r = mysqli_fetch_object($q)){
			$data[$r->$field] = $r;
		}
	}
	return $data;
}
function db_query2arrayindex($query){
	global $database;
	$q = mysqli_query($database->connection,$query);
	$data = array();
	$no= 0;
	while($r = mysqli_fetch_array($q)){
		$data[] = $r;
		//unset($data[$no]['name_roles']);
		//unset($data[$no]['id_subdept']);
		$no++;
	}
	return $data;
}

function db_num_rows($query){
	global $database;
	$q = mysqli_query($database->connection,$query);
	$output = mysqli_num_rows($q);
	return $output;
}

function db_query_insert($query){
	global $database;
    $q = mysqli_query($database->connection,$query);
	
    if($q==TRUE){
        $output['result'] = TRUE;
        $output['error'] ='';
    }else{
        $output['result'] = FALSE;
        $output['error'] = 'ERROR';
    }
	return $output;
}

function last_insert_id(){
	global $database;
    $id = mysqli_insert_id($database->connection);
	return $id;
}
//////
function insert_pac($id_ttc,$pac_name,$pac_vendor){
	global $database;
	$query = mysqli_query($database->connection,"insert into tbl_dev_ac (id_ttc,panel_name,panel_vendor) values('".$id_ttc."','".$pac_name."','".$pac_vendor."')");
	if($query){
		$alert = "Data berhasil diupdate!";
	}else{
		$alert = "Data gagal diupdate!!".mysqli_error();
	}

	return $alert;
}
function update_pac($id_ac,$pac_name,$pac_vendor){
	global $database;
	$query = mysqli_query($database->connection,"update tbl_dev_ac set panel_name = '".$pac_name."', panel_vendor='".$pac_vendor."' where id_ac='".$id_ac."'");
	if($query){
		$alert = "Data berhasil diupdate!";
	}else{
		$alert = "Data gagal diupdate!!".mysqli_error();
	}

	return $alert;
}
function insert_recti($id_ttc,$recti_name,$recti_vendor,$recti_ip){
	global $database;
	$query = mysqli_query($database->connection,"insert into tbl_dev_rectifier (id_ttc,rectifier_name,rectifier_vendor,rectifier_ipaddress) values('".$id_ttc."','".$recti_name."','".$recti_vendor."','".$recti_ip."')");
	if($query){
		$alert = "Data berhasil diupdate!";
	}else{
		$alert = "Data gagal diupdate!!".mysqli_error();
	}

	return $alert;
}
function update_recti($id_recti,$recti_name,$recti_vendor,$recti_ip){
	global $database;
	$query = mysqli_query($database->connection,"update tbl_dev_rectifier set rectifier_name = '".$recti_name."', rectifier_vendor='".$recti_vendor."', rectifier_ipaddress='".$recti_ip."' where id_recti='".$id_recti."'");
	if($query){
		$alert = "Data berhasil diupdate!";
	}else{
		$alert = "Data gagal diupdate!!".mysqli_error();
	}

	return $alert;
}
function update_user($id,$username,$password,$roles,$block = 0,$fullname,$email,$tlp){
	global $database;
	if($password==''){
		// $cek = db_num_rows("Select * from user where username = '$username'");
		// if($cek>0){
		// 	$alert = "Data user dengan <b>".$username."</b> sudah ada!!";
		// }else{
			$query = mysqli_query($database->connection,"update tbl_user set username = '".$username."',level = '".$roles."',fullname = '".$fullname."',email = '".$email."',phonenum = '".$tlp."' where id = '".$id."'");
			if($query){
				$alert = "Data berhasil diupdate!";
			}else{
				$alert = "Data gagal diupdate!!".mysqli_error();
			}
		// }
	}else{
		$pass = md5($password);
		// $cek = db_num_rows("Select * from user where username = '$username'");
		// if($cek>0){
		// 	$alert = "Data user dengan <b>".$username."</b> sudah ada!";
		// }else{
			$query = mysqli_query($database->connection," update tbl_user set username = '".$username."',password='".$pass."',level = '".$roles."',fullname = '".$fullname."',email = '".$email."',phonenum = '".$tlp."' where id = '".$id."'");
			if($query){
				$alert = "Data berhasil diupdate!";
			}else{
				$alert = "Data gagal diupdatea!".mysqli_error();
			}
		// }
	}
	return $alert;
}
function update_ttc($id,$name,$address,$pic){
	global $database;
	$query = mysqli_query($database->connection,"update tbl_ttc set name = '".$name."',address = '".$address."',pic = '".$pic."' where id_ttc = '".$id."'");
	if($query){
		$alert = "Data berhasil diupdate!";
	}else{
		$alert = "Data gagal diupdate!!".mysqli_error();
	}

	return $alert;
}


function delete_user($id){
	if($id==''){
		$alert = "ID tidak dideklarasi!!";
	}else{
		$query = mysql_query("Delete from user where id_user = '".$id."'");
		if($query){
			$alert = "Data berhasil dihapus!";
		}else{
			$alert = "Data gagal dihapus!".mysql_error();
		}
	}
	return $alert;
}
?>