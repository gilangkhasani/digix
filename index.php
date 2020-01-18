<?php 
	$msie = strpos($_SERVER["HTTP_USER_AGENT"], 'MSIE') ? true : false;
	$firefox = strpos($_SERVER["HTTP_USER_AGENT"], 'Firefox') ? true : false;
	$safari = strpos($_SERVER["HTTP_USER_AGENT"], 'Safari') ? true : false;
	$chrome = strpos($_SERVER["HTTP_USER_AGENT"], 'Chrome') ? true : false;
	
	$msg;
	$message;
	
	if ($firefox) {
		//Firefox
		$msg = 'you are using Firefox!';
	} else if ($safari || $chrome) { 
		// Safari or Chrome. Both use the same engine - webkit
		$msg = 'you are using a webkit powered browser';
	} else {
		// IE
		echo $msg = '<br>This Browser is Not Supported, Change to Chrome Or Firefox. Thanks :D <br>';
		die();
	}

    // memulai session
    date_default_timezone_set("Asia/Jakarta");
	$sessionname = 'digix';
	//echo "sa";
    session_start();
    //empty($_SESSION['rushone-administrator']) ? : $_SESSION['rushone-administrator'] = 'anonim';
    if(empty($_SESSION[$sessionname]->roles)){
		$user = new stdClass;
		$user->roles = 'anonim';
        $user->name = 'anonim';
        $user->username = 'anonim';
		$_SESSION[$sessionname]->roles = 'anonim';
        $_SESSION[$sessionname] = $user;
    }
    require_once('function/fungsi-path.php');
    require_once('function/fungsi-sql.php');
    require_once('function/fungsi-base.php');
	//echo "sa";
    //mengambil path ke array
    if(!empty($_GET['q'])){
        $path = explode("/",$_GET['q']);
    }
    try {
        require_once './includes/session.php';
    }
    catch(Exception $error) {
        print $error->getMessage();
		
    }
	
	if($_SESSION[$sessionname]->roles == 'anonim'){
		require_once('themes/template_anonim.php');
    } else {
		require_once('themes/template_user.php');
	}
?>