<?php
    $db_host = 'localhost';
    $db_name   = 'g19bcsy3a';
    $db_user = 'root';
    $db_pass = '';
    $db_port = 3306;

    $db = new mysqli($db_host, $db_user, $db_pass, $db_name);
    if($db->connect_error){
        echo $db-> connect_error;
        die();
    }
?>