<?php
/*------------------------------
-----@url -> inputan url tempat file berada
-----output -> menampilkan root dan url file
------------------------------*/
function url($url){
	$p='';
	if(!empty($_GET['q'])){
		$path = explode("/",$_GET['q']);
		$no=1;
		foreach($path as $isi_path){
			if($no<>1)
			$p .= "../";
			$no++;
		}
	}else{
		$p = "";
	}
        $path_fix = $p.$url;
	return $path_fix;
}
/*------------------------------
-----Menampilkan seluruh attribute menu
-----output -> variabel $r_site berbentuk object
------------------------------*/
function menu_loader(){
	$q_site = mysql_query("select * from menu");
	$r_site = mysql_fetch_array($q_site);
	return $r_site;
}

/*------------------------------
-----@url -> inputan url yang akan diganti *
-----output -> variabel $b yang isinya link * diganti dengan semua string variable $_GET ke x
------------------------------*/
function b($url){
	if(!empty($_GET['q'])){
		$loader = explode("/",$url);
		$path_get = explode("/",$_GET['q']);
		$jum_loader = sizeof($loader);
		$jum_path = sizeof($path_get);
		if($jum_loader == $jum_path){
			$no = 0;
			foreach($loader as $isi_loader){
				if($isi_loader == '*'){
					$path[] = $path_get[$no];
				}else{
					$path[] = $isi_loader;
				}
				$no++;
			}
			$b = implode("/",$path);
		}else{
			$b = $url;
		}
		return $b;
	}
}

function redirect($url, $delay = NULL){
	is_null($delay)? $delay = '1000' : '';
	$output = "<script language=\"javascript\">";
	$output .= "setTimeout('self.location.href =\"".url($url)."\"',".$delay.")";
	$output .= "</script>";
	return $output;
}

function loader($check, $permission, $konten = NULL, $title = NULL){
	$loader = array();
	if(in_array($check, (array) $permission) == TRUE){
		$loader['konten'] = $konten;
		$loader['title'] = $title;
	}else{
		$loader['konten'] = './page/main/no-page.php';
		$loader['title'] = 'No page';
	}
	return $loader;
}
function status($text){
	$output = "<div style=\"width:200px; padding:30px; margin:150px auto; background-color:#333; border-radius:20px; color:#FFF; font-weight:bold\" align=\"center\">";
	$output .= "<img src=\"".url('./themes/img/loading.GIF')."\"><br>$text";
	$output .= "</div>";
	return $output;
}

function perm($session, $name_perm, $name_access_roles){
	$data_aturan = db_query2arrayindex("select '$name_access_roles' as 'name_roles', pm.id_subdept from perm p inner join perm_member pm on p.id_perm = pm.id_perm where pm.id_subdept = '$session->id_subdept' and p.name_perm = '$name_perm'");
	$output = array();
	$output['user'] = array($session->name_roles, $session->id_subdept);
	$output['aturan'] = $data_aturan;
	return $output;
}
?>