<?php
session_start();
include 'connect.php';

if (!isset($_GET['id']) || $_GET['id'] === '') {
    $_SESSION['error'] = 'ไม่พบรหัสพนักงาน';
    header('Location: employees.php');
    exit;
}

$user_id = $_GET['id'];

$sql = "UPDATE ademp SET user_status = 'leave' WHERE user_id = ?";
$stmt = mysqli_prepare($connect, $sql);
mysqli_stmt_bind_param($stmt, "s", $user_id);

if (mysqli_stmt_execute($stmt)) {
    $_SESSION['success'] = "พนักงานรหัส $user_id ถูกซ่อนแล้ว (สถานะ Leave)";
} else {
    $_SESSION['error'] = "เกิดข้อผิดพลาด: " . mysqli_error($connect);
}

mysqli_stmt_close($stmt);
header('Location: employees.php');
exit;
?>
