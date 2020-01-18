<?php
if(!empty($_GET['q'])){
		// pengecekan q di loader ada atau tidak
    $query_loader = "select * from tbl_menu_loader where nama_loader='".strtolower($_GET['q'])."'";
    $count = db_num_rows($query_loader);
    if($count > 0){
    	 // check permission access to loader
        $row_loader = db_query($query_loader);
        $permission = array_filter(explode(";",$row_loader->permission));
        if(in_array($_SESSION[$sessionname]->{$row_loader->session_loader},(array) $permission)== TRUE){
            $loader['konten'] = $row_loader->filepage;
            $loader['title'] = $row_loader->title;
        }else{
            $loader['konten'] = './page/notification/notauthorized.php';
            $loader['title'] = 'No Page';
        }
    }else{
        $loader['konten'] = './page/notification/404page.php';
        $loader['title'] = 'No Page';
    }
}
?>