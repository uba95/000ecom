<?php 

$dns = 'mysql:host=localhost;dbname=shop';
$user = 'root';
$pass = '';
$options = array(

    PDO::MYSQL_ATTR_INIT_COMMAND => 'SET NAMES utf8',
    PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION
);

try {
    
    $connect = new PDO($dns, $user, $pass, $options);
    
    
} catch (PDOException $e) {

    echo 'no'.$e->getMessage();
}