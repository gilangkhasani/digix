<?php
$mysqli = new mysqli("localhost", "root", "", "b0413113_omc");

/* check connection */
if (mysqli_connect_errno()) {
    printf("Connect failed: %s\n", mysqli_connect_error());
    exit();
}
?>