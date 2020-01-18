<?php
function detectDelimiter($csvFile)
{
    $delimiters = array(
        ';' => 0,
        ',' => 0,
        "\t" => 0,
        "|" => 0
    );

    $handle = fopen($csvFile, "r");
    $firstLine = fgets($handle);
    fclose($handle); 
    foreach ($delimiters as $delimiter => &$count) {
        $count = count(str_getcsv($firstLine, $delimiter));
    }

    return array_search(max($delimiters), $delimiters);
}

function format_date($waktu){
		$explode = explode(" ",$waktu);
		$exop = explode("-",$explode[0]);
		if(count($explode)>1){
			$tgl = $exop[2]."/".$exop[1]."/".$exop[0]." ".$explode[1];
		}else{
			$tgl = $alarmtime;
		}
	return $tgl;
}
function format_date_sub($waktu){
	$wkt = explode("|",$waktu);
	if(count($wkt)>2){
		$alarmtime = str_replace("_"," ",$wkt[2]).":00";
		$explode = explode(" ",$alarmtime);
		$exop = explode("-",$explode[0]);
		if(count($explode)>1){
			$tgl = $exop[2]."/".$exop[1]."/".$exop[0]." ".$explode[1];
		}else{
			$tgl = $alarmtime;
		}
	}else{
		$tgl = "0000-00-00 00:00:00";
	}
	return $tgl;
}
function convert_hari($hari){
	switch($hari){
		case 'Sunday' :
			$output = 'Minggu';
			break;
		case 'Monday':
			$output = 'Senin';
			break;
		case 'Tuesday':
			$output = 'Selasa';
			break;
		case 'Wednesday':
			$output = 'Rabu';
			break;
		case 'Thursday':
			$output = 'Kamis';
			break;
		case 'Friday':
			$output = 'Jumat';
			break;
		case 'Saturday':
			$output = 'Sabtu';
			break;
		default:
			$output = 'Unknown';
			break;
	}
	return $output;
}
function convert_bulan($bulan){
	switch ($bulan) {
		case '1':
			$output = 'Januari';
			break;
		case '2':
			$output = 'Februari';
			break;
		case '3':
			$output = 'Maret';
			break;
		case '4':
			$output = 'April';
			break;
		case '5':
			$output = 'Mei';
			break;
		case '6':
			$output = 'Juni';
			break;
		case '7':
			$output = 'Juli';
			break;
		case '8':
			$output = 'Agustus';
			break;
		case '9':
			$output = 'September';
			break;
		case '10':
			$output = 'Oktober';
			break;
		case '11':
			$output = 'November';
			break;
		case '12':
			$output = 'Desember';
			break;
		
		default:
			$output = 'Unknown';
			break;
	}
	return $output;
}
function get_tanggal_akhir($thn,$bln){
	$bulan[1]=31;
	$bulan[2]=28;
	$bulan[3]=31;
	$bulan[4]=30;
	$bulan[5]=31;
	$bulan[6]=30;
	$bulan[7]=31;
	$bulan[8]=31;
	$bulan[9]=30;
	$bulan[10]=31;
	$bulan[11]=30;
	$bulan[12]=31;

	if ($thn%4==0){
		$bulan[2]=29;
	}
	return $bulan[$bln];
}
function format_angka($angka){
    if($angka == 0 ){
        return "-";
    }else{
        return $angka;
    }
}

function array2csv($array, $filename)
{
   if (count($array) == 0) {
     return null;
   }
   ob_start();
	$fp = fopen('file_doc/'.$filename, 'w');
	foreach ($array as $fields) {
		fputcsv($fp, $fields);
	}
	fclose($fp);
   return ob_get_clean();
}

function download_send_headers($filename) {	
    header('Content-Type: application/csv;');
    header('Content-Disposition: attachment; filename="'.$filename.'";');
	readfile("file_doc/".$filename);
    //header("Location: ".url("file_doc/".$filename));
}

function pagination($query,$per_page=0,$page=1,$url='?'){   
    $query = "SELECT COUNT(*) as `num` FROM {$query}";
    $row = mysql_fetch_array(mysql_query($query));
    $total = $row['num'];
    $adjacents = "2"; 
      
    $prevlabel = " Prev";
    $nextlabel = "Next ";
    $lastlabel = "Last &rsaquo;&rsaquo;";
    if($per_page==0) $per_page = 20;
    $page = ($page == 0 ? 1 : $page);  
    $start = ($page - 1) * $per_page;                               
      
    $prev = $page - 1;                          
    $next = $page + 1;
      
    $lastpage = ceil($total/$per_page);
      
    $lpm1 = $lastpage - 1; // //last page minus 1
      
    $pagination = "";
    if($lastpage > 1){   
       // $pagination .= "<ul class='pagination'>";
        $pagination .= "<a href='#'' class='btn icn-only'>Page {$page} of {$lastpage}</a>";
              
        if ($page > 1) $pagination.= "<a class='btn btn-success' href='{$url}page={$prev}'><i class='m-icon-swapleft m-icon-white'></i>{$prevlabel}</a>";
              
        if ($lastpage < 7 + ($adjacents * 2)){   
            for ($counter = 1; $counter <= $lastpage; $counter++){
                if ($counter == $page)
                    $pagination.= "<a class='btn btn-danger'>{$counter}</a>";
                else
                    $pagination.= "<a class='btn btn-default' href='{$url}page={$counter}&limit={$per_page}'>{$counter}</a>";                    
            }
          
        } elseif($lastpage > 5 + ($adjacents * 2)){
              
            if($page < 1 + ($adjacents * 2)) {
                  
                for ($counter = 1; $counter < 4 + ($adjacents * 2); $counter++){
                    if ($counter == $page)
                        $pagination.= "<a class='btn btn-danger'>{$counter}</a>";
                    else
                        $pagination.= "<a class='btn btn-default' href='{$url}page={$counter}&limit={$per_page}'>{$counter}</a>";                    
                }
                $pagination.= " ... ";
                $pagination.= "<a class='btn btn-default' href='{$url}page={$lpm1}&limit={$per_page}'>{$lpm1}</a>";
                $pagination.= "<a class='btn btn-default' href='{$url}page={$lastpage}&limit={$per_page}'>{$lastpage}</a>";  
                      
            } elseif($lastpage - ($adjacents * 2) > $page && $page > ($adjacents * 2)) {
                  
                $pagination.= "<a class='btn btn-default' href='{$url}page=1&limit={$per_page}'>1</a></li>";
                $pagination.= "<a class='btn btn-default' href='{$url}page=2&limit={$per_page}'>2</a></li>";
               	$pagination.= " ... ";
                for ($counter = $page - $adjacents; $counter <= $page + $adjacents; $counter++) {
                    if ($counter == $page)
                        $pagination.= "<a class='btn btn-danger'>{$counter}</a>";
                    else
                        $pagination.= "<a class='btn btn-default' href='{$url}page={$counter}&limit={$per_page}'>{$counter}</a>";                    
                }
                $pagination.= " ... ";
                $pagination.= "<a class='btn btn-default' href='{$url}page={$lpm1}&limit={$per_page}'>{$lpm1}</a>";
                $pagination.= "<a class='btn btn-default'href='{$url}page={$lastpage}&limit={$per_page}'>{$lastpage}</a>";      
                  
            } else {
                  
                $pagination.= "<a class='btn btn-default' href='{$url}page=1&limit={$per_page}'>1</a>";
                $pagination.= "<a class='btn btn-default' href='{$url}page=2&limit={$per_page}'>2</a>";
                $pagination.= " ... ";
                for ($counter = $lastpage - (2 + ($adjacents * 2)); $counter <= $lastpage; $counter++) {
                    if ($counter == $page)
                        $pagination.= "<a class='btn btn-danger'>{$counter}</a>";
                    else
                        $pagination.= "<a class='btn btn-default' href='{$url}page={$counter}&limit={$per_page}'>{$counter}</a>";                    
                }
            }
        }
          
            if ($page < $counter - 1) {
                $pagination.= "<a class='btn btn-success' href='{$url}page={$next}&limit={$per_page}'>{$nextlabel}<i class='m-icon-swapright m-icon-white'></i></a>";
                $pagination.= "<a class='btn blue' href='{$url}page=$lastpage&limit={$per_page}'>{$lastlabel}</a>";
            }
        //$pagination.= "</ul>";        
    }else{
        $pagination .= "<a href='#'' class='btn icn-only'>Page {$page} of {$lastpage}</a>";
        $pagination.= "<a class='btn btn-danger'>1</a>";
    }
      
    return $pagination;
}

function send_mail($sendto,$subject_mail,$isi_pesan){
	$to      = $sendto;
	$subject = $subject_mail;
	$message = $isi_pesan;
	$headers = 'From: cs@quickcorp.co.id' . "\r\n" .
	    'Reply-To: cs@quickcorp.co.id' . "\r\n" .
	    'X-Mailer: PHP/' . phpversion();

	mail($to, $subject, $message, $headers);
}


function send_sms($notujuan,$IDguest,$namatamu,$nomerhp,$company){
	
	require_once ("function/smppclass.php");
	
	$user_ldap = "16010754";
	$pass_ldap = "Auto#2017";
	
	$smpphost = "10.2.224.156";
	$smppport = 28108;
	$systemid = "ARMAN4725";
	$password = "ARMAN123";
	$system_type = "TCP";
	$from = "3999"; //display pengirim
	//$from = "siktest"; //display pengirim
	
	
		$telephonenumber = "62".$notujuan; 
		
		$smpp = new SMPPClass();
		$smpp->SetSender($from);
		$smpp->Start($smpphost, $smppport, $systemid, $password, $system_type);
		
		$msg = "";
		$msg .= "Karywn Yth , tamu a.n. ".$namatamu."/".$nomerhp." dari ".$company." sdg di lobby ".$_SESSION['guestbook_building']->building_name." \n";
		//$msg .= "waiting for your confirmation \n";
		$msg .= "Konfirm via sms ke 3999 format : TAMU(spasi)$IDguest#yes/no#info \n";
		$msg .= "atau \n";
		$msg .= "call ke : ".$_SESSION['guestbook_building']->tlp_gedung." (ext) ".$_SESSION['guestbook_building']->extension;
		$smpp->Send($telephonenumber,$msg);
		
	$smpp->End();
}

function checkout_sms($notujuan,$namatamu,$checkout){
	
	require_once ("function/smppclass.php");
	
	$user_ldap = "16010754";
	$pass_ldap = "Auto#2017";
	
	$smpphost = "10.2.224.156";
	$smppport = 28108;
	$systemid = "ARMAN4725";
	$password = "ARMAN123";
	$system_type = "TCP";
	$from = "3999"; //display pengirim
	//$from = "siktest"; //display pengirim
	
	
		$telephonenumber = "62".$notujuan; 
		
		$smpp = new SMPPClass();
		$smpp->SetSender($from);
		$smpp->Start($smpphost, $smppport, $systemid, $password, $system_type);
		
		$msg = "";
		$msg .= "Rekan Yth ,\n";
		$msg .= "Tamu a.n. ".$namatamu." tlh mninggalkan ".$_SESSION['guestbook_building']->building_name." \n";
		$msg .= "Jam ".$checkout."\n";
		$msg .= "\n~TerimaKasih";
		//$msg .= "-- Resepsionis Gedung : ".$_SESSION['guestbook_building']->tlp;
		$smpp->Send($telephonenumber,$msg);
		
	$smpp->End();
}

function send_telegram($chat_id,$isi_pesan){
	ini_set('max_execution_time', 1200);
	$bot_token = '142893211:AAHZb7R-8KtAJOk8pkV5kBJGDLiCExu_hHM'; //alert_activity_bot
	$website = 'https://api.telegram.org/bot'.$bot_token;
	
	
	//echo $comma_separated = implode("", $arrText);
	//ini_set('default_socket_timeout', 500); 
	ini_set('max_execution_time', 500); //300 seconds = 5 minutes
	file_get_contents($website.'/sendMessage?chat_id='.$chat_id.'&text='.$isi_pesan.' by quick');
}


function getLdapManager($ldapuser,$ldappass,$ldapsearch){
	$adServer = "ldap://telkomsel.co.id";
	$ldap = ldap_connect($adServer);
	$ldaprdn = "telkomsel\\$ldapuser";
	$ldap_dn = 'dc=telkomsel,dc=co,dc=id';
	$pass = $ldappass;
 
	ldap_set_option($ldap, LDAP_OPT_PROTOCOL_VERSION, 3);
	ldap_set_option($ldap, LDAP_OPT_REFERRALS, 0);
	$bind = @ldap_bind($ldap, $ldaprdn, $pass);
	if($bind){
		//$filter="(S=$samaccountname)";
		$ldap_user = $ldapsearch;
		$keyword = "Resources Performance Assurance Jawa Barat Department;Organik";
		$keywordtitle = "description";
		//$result = ldap_search($ldap, $ldap_dn, "(".$keywordtitle."=".$keyword.")");
		$result = ldap_search($ldap, $ldap_dn, "(cn=".$ldap_user.")");
		//Nah ini untuk narik nilai atributnya.
		$entries = ldap_get_entries($ldap, $result);
		//Ini untuk munculin, tinggal dipilih-dipilih value mana yang mau diambil
		foreach ( $entries as $key => $value){
			$ldap_att = new stdClass;
			$ldap_att->title = $value['title'][0];
			$ldap_att->description = $value['description'][0];
			$ldap_att->physicaldeliveryofficename = $value['physicaldeliveryofficename'][0];
			$ldap_att->telephonenumber = $value['telephonenumber'][0];
			$ldap_att->displayname = $value['displayname'][0];
			$ldap_att->department = $value['department'][0];
			$ldap_att->company = $value['company'][0];
			$ldap_att->samaccountname = $value['samaccountname'][0];
			$ldap_att->directorate = $value['extensionattribute13'][0];
			$ldap_att->group = $value['extensionattribute14'][0];
			$ldap_att->division = $value['extensionattribute15'][0];
			$ldap_att->mail = $value['mail'][0];
			$getmanager = explode(",",$value['manager'][0]);
			$ldap_att->manager = substr($getmanager[0],3,strlen($getmanager[0])-3);
			$ldap_att->mobile = $value['mobile'][0];
			$ldap_att->nik = $value['extensionattribute1'][0];
			$ldap_att->first_name = $value['givenname'][0];
			$ldap_att->last_name = $value['sn'][0];
			$ldap_att->full_name = $value['givenname'][0].' '.$value['sn'][0];
		}
		ldap_close($ldap);
		return $ldap_att;
	}
}

function getLdapByFilter($ldapuser, $ldappass, $ldapsearch, $filter){
	$adServer = "ldap://telkomsel.co.id";
	$ldap = ldap_connect($adServer);
	$ldaprdn = "telkomsel\\$ldapuser";
	$ldap_dn = 'dc=telkomsel,dc=co,dc=id';
	$pass = $ldappass;
 
	ldap_set_option($ldap, LDAP_OPT_PROTOCOL_VERSION, 3);
	ldap_set_option($ldap, LDAP_OPT_REFERRALS, 0);
	$bind = @ldap_bind($ldap, $ldaprdn, $pass);
	if($bind){
		//$filter="(S=$samaccountname)";
		$ldap_user = $ldapsearch;
		$keyword = "Resources Performance Assurance Jawa Barat Department;Organik";
		$keywordtitle = "description";
		//$result = ldap_search($ldap, $ldap_dn, "(".$keywordtitle."=".$keyword.")");
		$result = ldap_search($ldap, $ldap_dn, "(".$filter."=".$ldap_user.")");
		//Nah ini untuk narik nilai atributnya.
		$entries = ldap_get_entries($ldap, $result);
		//Ini untuk munculin, tinggal dipilih-dipilih value mana yang mau diambil
		foreach ( $entries as $key => $value){
			$ldap_att = new stdClass;
			$ldap_att->title = $value['title'][0];
			$ldap_att->description = $value['description'][0];
			$ldap_att->physicaldeliveryofficename = $value['physicaldeliveryofficename'][0];
			$ldap_att->telephonenumber = $value['telephonenumber'][0];
			$ldap_att->displayname = $value['displayname'][0];
			$ldap_att->department = $value['department'][0];
			$ldap_att->company = $value['company'][0];
			$ldap_att->samaccountname = $value['samaccountname'][0];
			$ldap_att->directorate = $value['extensionattribute13'][0];
			$ldap_att->group = $value['extensionattribute14'][0];
			$ldap_att->division = $value['extensionattribute15'][0];
			$ldap_att->mail = $value['mail'][0];
			$getmanager = explode(",",$value['manager'][0]);
			$ldap_att->manager = substr($getmanager[0],3,strlen($getmanager[0])-3);
			$ldap_att->mobile = $value['mobile'][0];
			$ldap_att->nik = $value['extensionattribute1'][0];
			$ldap_att->first_name = $value['givenname'][0];
			$ldap_att->last_name = $value['sn'][0];
			$ldap_att->full_name = $value['givenname'][0].' '.$value['sn'][0];
		}
		ldap_close($ldap);
		return $ldap_att;
	}
}
function alertflash($type,$alertheader,$alertmessage){
    switch($type){
        case 'danger':
            $style_alert = 'alert-danger';
            $style_icon = 'fa-ban';
            break;
        case 'info':
            $style_alert = 'alert-info';
            $style_icon = 'fa-info';
            break;
        case 'warning':
            $style_alert = 'alert-warning';
            $style_icon = 'fa-warning';
            break;
        case 'success':
            $style_alert = 'alert-success';
            $style_icon = 'fa-check';
            break;
        default:
            $style_alert = 'alert-success';
            $style_icon = 'fa-check';
            break;
    }
    $txt =  '<div class="alert '.$style_alert.' alert-dismissible">';
    $txt .= '    <button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>';
    $txt .= '    <h4><i class="icon fa '.$style_icon.'"></i> '.$alertheader.'</h4>';
    $txt .= '    '.$alertmessage.'';
    $txt .= '  </div>';
    return $txt;
}

function getnamafromid_users($id_users){
	$server = "103.15.226.142";
	$user = "itbs";
	$password = "itbs123";
	$db_name = "88077_obc_tdc";
	$con = mysqli_connect("$server","$user","$password","$db_name");
	// Check connection
	if (mysqli_connect_errno()){
		echo "Failed to connect to MySQL: " . mysqli_connect_error();
	}
    $query = "
		SELECT username from users_tdc where id_users='".$id_users."'
	";
	$q = mysqli_query($con, $query);
	$r = mysqli_fetch_object($q);
	if(empty($r)){
		return 1;
	} else {
		return $r->username;
	}
}

function get_region_id_users($id_users){
	$server = "103.15.226.142";
	$user = "itbs";
	$password = "itbs123";
	$db_name = "88077_obc_tdc";
	$con = mysqli_connect("$server","$user","$password","$db_name");
	// Check connection
	if (mysqli_connect_errno()){
		echo "Failed to connect to MySQL: " . mysqli_connect_error();
	}
    $query = "
		SELECT regional from users_tdc where id_users='".$id_users."'
	";
	$q = mysqli_query($con, $query);
	$r = mysqli_fetch_object($q);
	if(empty($r)){
		return 1;
	} else {
		return $r->regional;
	}
}

function get_table_name($id_users){
	$server = "103.15.226.142";
	$user = "itbs";
	$password = "itbs123";
	$db_name = "88077_obc_tdc";
	$con = mysqli_connect("$server","$user","$password","$db_name");
	// Check connection
	if (mysqli_connect_errno()){
		echo "Failed to connect to MySQL: " . mysqli_connect_error();
	}
    $query = "
		SELECT *
		FROM users_tdc a 
		WHERE a.id_users = '".$id_users."'
	";
	$q = mysqli_query($con, $query);
	$r = mysqli_fetch_object($q);
	if(empty($r)){
		return 1;
	} else {
		return "boopati_obc_msisdn_region".$r->id_region."_20180925";
	}
}

function get_table_name_family_plan($id_users){
	$server = "103.15.226.142";
	$user = "itbs";
	$password = "itbs123";
	$db_name = "88077_obc_tdc";
	$con = mysqli_connect("$server","$user","$password","$db_name");
	// Check connection
	if (mysqli_connect_errno()){
		echo "Failed to connect to MySQL: " . mysqli_connect_error();
	}
    $query = "
		SELECT *
		FROM users_tdc a 
		WHERE a.id_users = '".$id_users."'
	";
	$q = mysqli_query($con, $query);
	$r = mysqli_fetch_object($q);
	if(empty($r)){
		return 1;
	} else {
		return "boopati_familyplan_region".$r->id_region;
	}
}

function get_table_name_usim($id_users){
	$server = "103.15.226.142";
	$user = "itbs";
	$password = "itbs123";
	$db_name = "88077_obc_tdc";
	$con = mysqli_connect("$server","$user","$password","$db_name");
	// Check connection
	if (mysqli_connect_errno()){
		echo "Failed to connect to MySQL: " . mysqli_connect_error();
	}
    $query = "
		SELECT *
		FROM users_tdc a 
		WHERE a.id_users = '".$id_users."'
	";
	$q = mysqli_query($con, $query);
	$r = mysqli_fetch_object($q);
	if(empty($r)){
		return 1;
	} else {
		return "boopati_usim_migration_region".$r->id_region;
	}
}

function get_table_name_setting4g($id_users){
	$server = "103.15.226.142";
	$user = "itbs";
	$password = "itbs123";
	$db_name = "88077_obc_tdc";
	$con = mysqli_connect("$server","$user","$password","$db_name");
	// Check connection
	if (mysqli_connect_errno()){
		echo "Failed to connect to MySQL: " . mysqli_connect_error();
	}
    $query = "
		SELECT *
		FROM users_tdc a 
		WHERE a.id_users = '".$id_users."'
	";
	$q = mysqli_query($con, $query);
	$r = mysqli_fetch_object($q);
	if(empty($r)){
		return 1;
	} else {
		return "boopati_setting4g_region".$r->id_region."_20181004";
	}
}
?>

