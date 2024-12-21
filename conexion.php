<?php
    $server='localhost';
    $user='root';
    $pass='';
    $db='db_name';

    $conexion = new mysqli($server,$user,$pass,$db);

    if($conexion-> connect_errno){
        die("Error connecting to db");
    }else{
        echo 'connection';
    }
?>