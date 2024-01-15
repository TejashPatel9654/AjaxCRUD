<?php
    $server = "localhost";
    $user = "root";
    $password = '';
    $database = 'php_crud';

    $conn = new mysqli($server,$user,$password,$database);

    if($conn->connect_error){
        die('Connection Error '. $conn->connect_error);
    }
?>