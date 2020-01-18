<?php

define("DB_SERVER", "localhost");
define("DB_USER", "itbs");
define("DB_PASS", "itbs123");
define("DB_NAME", "85152_lokasi");

class MySQLDB{

  function MySQLDB(){
      $this->connection = mysqli_connect(DB_SERVER, DB_USER, DB_PASS,DB_NAME) or die(mysqli_connect_error());
      //mysql_select_db(DB_NAME, $this->connection) or die(mysql_error());
   }
};
$database = new MySQLDB;
?>