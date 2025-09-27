<?php
/*
if($open_connect != 1){
    header('Location: form-login.php');
}*/

$hostname = 'localhost';
$username = 'root';
$password = '12345678';
$database = 'bis_sa_db';

$connect = mysqli_connect($hostname, $username, $password, $database);

if(!$connect){
    die("การเชื่อมต่อฐานข้อมูลล้มเหลว : " . mysqli_connect_error());
} else {
    mysqli_set_charset($connect, 'utf8');
}

?>
