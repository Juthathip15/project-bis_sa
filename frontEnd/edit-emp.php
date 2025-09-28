<?php
// edit-emp.php
session_start();
include 'connect.php'; // ต้องมี $connect = mysqli_connect(...);

// 1) ตรวจสอบและดึง user_id จาก URL
if (!isset($_GET['id']) || $_GET['id'] === '') {
    $_SESSION['error'] = 'ไม่พบรหัสพนักงานที่ต้องการแก้ไข';
    header('Location: employees.php');
    exit;
}
$user_id = $_GET['id']; // string (อาจมีศูนย์นำหน้า)

// 2) กดบันทึก (POST) => อัปเดตข้อมูล
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $user_id_post = trim($_POST['user_id']); // เก็บไว้เป็น hidden field
    $user_Fname   = trim($_POST['name']);
    $user_Lname   = trim($_POST['lastname']);
    $user_tel     = trim($_POST['phone']);
    $user_email   = trim($_POST['email']);

    // ตัวอย่าง: UPDATE เฉพาะ 4 ฟิลด์นี้ (เพิ่ม/ลดได้ตามคอลัมน์จริง)
    $sql_update = "UPDATE ademp
                   SET user_Fname = ?, user_Lname = ?, user_tel = ?, user_email = ?
                   WHERE user_id = ?";
    $stmt_upd = mysqli_prepare($connect, $sql_update);
    mysqli_stmt_bind_param($stmt_upd, "sssss", $user_Fname, $user_Lname, $user_tel, $user_email, $user_id_post);

    if (mysqli_stmt_execute($stmt_upd)) {
        mysqli_stmt_close($stmt_upd);
        $_SESSION['success'] = 'อัปเดตข้อมูลพนักงานเรียบร้อยแล้ว';
        header('Location: employees.php');
        exit;
    } else {
        $error = 'เกิดข้อผิดพลาดในการอัปเดตข้อมูล: ' . mysqli_stmt_error($stmt_upd);
        mysqli_stmt_close($stmt_upd);
    }
}

// 3) โหลดข้อมูลเดิมมาแสดงในฟอร์ม
$sql_sel = "SELECT user_id, user_Fname, user_Lname, user_tel, user_email
            FROM ademp
            WHERE user_id = ?";
$stmt_sel = mysqli_prepare($connect, $sql_sel);
mysqli_stmt_bind_param($stmt_sel, "s", $user_id);
mysqli_stmt_execute($stmt_sel);
$result = mysqli_stmt_get_result($stmt_sel);
$emp = mysqli_fetch_assoc($result);
mysqli_stmt_close($stmt_sel);

if (!$emp) {
    $_SESSION['error'] = 'ไม่พบข้อมูลพนักงานรหัส ' . htmlspecialchars($user_id);
    header('Location: employees.php');
    exit;
}
?>
<!DOCTYPE html>
<html lang="th">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Edit Employee - Manpower</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="bg-light">
  <nav class="navbar navbar-expand-lg navbar-dark bg-primary mb-4">
    <div class="container-fluid">
      <a class="navbar-brand" href="index.html">Manpower</a>
      <ul class="navbar-nav">
        <li class="nav-item"><a class="nav-link" href="index.php">Dashboard</a></li>
        <li class="nav-item"><a class="nav-link active" href="employees.php">Employees</a></li>
        <li class="nav-item"><a class="nav-link" href="requests.php">Requests</a></li>
        <li class="nav-item"><a class="nav-link" href="approvals.php">Approvals</a></li>
        <li class="nav-item"><a class="nav-link" href="reports.php">Reports</a></li>
            <!-- แสดงชื่อผู้ใช้ -->
    <span class="navbar-text text-white me-3">
      👤 <?php echo htmlspecialchars($_SESSION['name']); ?>
    </span>

         <!-- ปุ่ม Logout -->
  <li class="nav-item ms-auto">
    <a class="btn btn-danger btn-sm" href="logout.php"
       onclick="return confirm('คุณต้องการออกจากระบบหรือไม่?');">Logout</a>
  </li>
      </ul>
    </div>
  </nav>

  <div class="container">
    <div class="card shadow p-4">
      <h3 class="mb-4">แก้ไขข้อมูลพนักงาน</h3>

      <?php if (!empty($error)): ?>
        <div class="alert alert-danger"><?php echo htmlspecialchars($error); ?></div>
      <?php endif; ?>

      <form action="edit-emp.php?id=<?php echo urlencode($emp['user_id']); ?>" method="POST">
        <!-- เก็บ user_id แบบ hidden เพื่อรักษา 0 นำหน้า -->
        <input type="hidden" name="user_id" value="<?php echo htmlspecialchars($emp['user_id']); ?>">

        <div class="row mb-3">
          <div class="col-md-6">
            <label for="name" class="form-label">ชื่อ</label>
            <input type="text" id="name" name="name" class="form-control" required
                   value="<?php echo htmlspecialchars($emp['user_Fname']); ?>">
          </div>
          <div class="col-md-6">
            <label for="lastname" class="form-label">นามสกุล</label>
            <input type="text" id="lastname" name="lastname" class="form-control" required
                   value="<?php echo htmlspecialchars($emp['user_Lname']); ?>">
          </div>
        </div>

        <div class="row mb-3">
          <div class="col-md-6">
            <label for="phone" class="form-label">เบอร์โทรศัพท์</label>
            <input type="tel" id="phone" name="phone" class="form-control"
                   pattern="[0-9]{10}" placeholder="เช่น 0812345678"
                   value="<?php echo htmlspecialchars($emp['user_tel']); ?>">
          </div>
          <div class="col-md-6">
            <label for="email" class="form-label">อีเมล</label>
            <input type="email" id="email" name="email" class="form-control"
                   value="<?php echo htmlspecialchars($emp['user_email']); ?>">
          </div>
        </div>

        <div class="d-flex justify-content-between">
          <a href="employees.php" class="btn btn-secondary">ย้อนกลับ</a>
          <div>
            <button type="reset" class="btn btn-warning">ล้างข้อมูล</button>
            <button type="submit" class="btn btn-primary">บันทึกข้อมูล</button>
          </div>
        </div>
      </form>
    </div>
  </div>
</body>
</html>
