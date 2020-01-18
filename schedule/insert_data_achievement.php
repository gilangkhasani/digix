<?php 
	$root_page = '/var/www/html/boopati/';
    require_once($root_page.'function/fungsi-sql.php');
	require_once $root_page.'includes/database.php';
	
	$query = "
	INSERT INTO boopati_achievement
	select b.id_users, b.username, flag, tanggal_claim,
	COUNT(msisdn) as tot_call, 
	count(if((a.status_claim = 'sukses'),1,NULL)) as call_sukses,
	count(if((a.status_claim = 'tidakdiangkat'),1,NULL)) as call_sukses,
	count(if((a.status_claim = 'sudahaktivasi'),1,NULL)) as call_notanswer,
	count(if((a.status_claim = 'menolakaktivasi'),1,NULL)) as call_reject
	from boopati_whitelist_claim a
	join users b ON (a.id_users = b.id_users)
	and tanggal_claim = CURDATE() - 1
	GROUP BY b.id_users, flag";
	
	db_query_insert($query);
?>