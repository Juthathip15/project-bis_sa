<?php

$open_connect = 1;
require('connect.php');

if(isset($_POST['first_name']) && 
   isset($_POST['last_name']) &&
   isset($_POST['email_account']) &&
   isset($_POST['phone_number']) &&
   isset($_POST['password_account1']) &&
   isset($_POST['password_account2'])) {

    $first_name = htmlspecialchars(mysqli_real_escape_string($connect, $_POST['first_name']));
    $last_name = htmlspecialchars(mysqli_real_escape_string($connect, $_POST['last_name']));
    $email_account = htmlspecialchars(mysqli_real_escape_string($connect, $_POST['email_account']));
    $phone_number = htmlspecialchars(mysqli_real_escape_string($connect, $_POST['phone_number']));
    $password_account1 = htmlspecialchars(mysqli_real_escape_string($connect, $_POST['password_account1']));
    $password_account2 = htmlspecialchars(mysqli_real_escape_string($connect, $_POST['password_account2']));

    if(empty($first_name)){
        die(header('Location: form-information.php'));
    }else  if(empty($last_name)){
        die(header('Location: form-information.php'));
    }else  if(empty($email_account)){
        die(header('Location: form-information.php'));
    }else  if(empty($phone_number)){
        die(header('Location: form-information.php'));
    }else  if(empty($password_account1)){
        die(header('Location: form-information.php'));
    }else  if(empty($password_account2)){
        die(header('Location: form-information.php'));
    }else {
        echo 'ไม่มีค่าว่าง';
    }

} else {
    die(header('Location: form-information.php'));
}
?>