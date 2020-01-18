<?php
ini_set('memory_limit', '512M');
ini_set('max_execution_time',6000);
$file_a = 'output_jabo.txt';
$table_name = 'boopati_HVC_nonregistration';
$file_a=$argv[1];
$table_name=$argv[2];
//print_r($argv);
//echo $file_a."-".$table_name;

//open connection to mysql
//MySQL connection
$server_address = "10.3.5.225";
$username = "itbs";
$password = "itbs123";
$db_name = "mcd_jabar";
// connect to mysql server
$link = mysql_connect($server_address, $username, $password)
        or die("Could not connect : " . mysql_error());
mysql_select_db($db_name) or die("Could not select database");
if (!$link) {
    die('Could not connect: ' . mysql_error());
}

$buka_file_a=fopen("output_jabo.txt","r+");
$fp_buka_a=fread($buka_file_a,filesize($file_a)); 


$fp_ex = explode("\n",$fp_buka_a);
$total =sizeof($fp_ex);
for  ($m=0; $m< sizeof($fp_ex)-1;$m++)
        {

        $ex_field = explode("|",$fp_ex[$m]);
        $query_insert="INSERT IGNORE ".$table_name." (MSISDN,FLAG,CEK_BRAND,REGION) VALUES ('".
        trim($ex_field[0])."','".
        trim($ex_field[1])."','".
        trim($ex_field[2])."','".
        trim($ex_field[3]).""
        .");";
        $persen = round(($m/$total*100),2);
        if ($da = mysql_query($query_insert)) {
                echo $m." of ".$total." (".$persen."%)\r";
        }else{
                echo mysql_error();
        }
}
?>