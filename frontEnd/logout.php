<?php
session_start();
session_unset();     // ลบค่า session ทั้งหมด
session_destroy();   // ทำลาย session
header("Location: login.php"); // กลับไปหน้า login
exit;
