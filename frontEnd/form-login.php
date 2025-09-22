<?php
session_start();
$open_connect = 1;
require('connect.php');

if(isset($_POST['email_account']) && isset($_POST['password_account'])){
    $email_account = mysqli_real_escape_string($connect, $_POST['email_account']);
    $password_account = mysqli_real_escape_string($connect, $_POST['password_account']);

    $query = "SELECT * FROM account WHERE email_account='$email_account' AND password_account='$password_account'";
    $result = mysqli_query($connect, $query);

    if(mysqli_num_rows($result) == 1){
        $user = mysqli_fetch_assoc($result);
        $_SESSION['username'] = $user['username_account'];
        $_SESSION['role'] = $user['role_account'];
        $_SESSION['email'] = $user['email_account'];

        header("Location: form-information.php");
        exit();
    } else {
        echo "<p style='color:red;'>อีเมลหรือรหัสผ่านไม่ถูกต้อง</p>";
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>เข้าสู่ระบบ</title>
</head>
<body>
    <h1>เข้าสู่ระบบ</h1>
    <form action="" method="POST">
        <div>
            <input name="email_account" type="email" placeholder="อีเมล" required>
        </div>
        <div>
            <input name="password_account" type="password" placeholder="รหัสผ่าน" required>
        </div>
        <button>เข้าสู่ระบบ</button>
    </form>
</body>
</html>