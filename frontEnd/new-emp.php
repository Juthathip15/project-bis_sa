<?php
session_start();
include 'connect.php'; // เชื่อมต่อฐานข้อมูล

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // รับค่าจากฟอร์ม
    $user_id = trim($_POST['user_id']);
    $user_Fname = trim($_POST['user_Fname']);
    $user_Lname = trim($_POST['user_Lname']);
    $user_age = trim($_POST['user_age']);
    $user_born = trim($_POST['user_born']);
    $user_department = trim($_POST['user_department']);
    $user_position = trim($_POST['user_position']);
    $user_tel = trim($_POST['user_tel']);
    $user_email = trim($_POST['user_email']);

    // ตรวจสอบว่าอีเมลไม่ซ้ำในระบบ
    $sql = "SELECT * FROM ademp WHERE user_email = ?";
    $stmt = $connect->prepare($sql); // ตรวจสอบว่า $conn เป็นการเชื่อมต่อที่ถูกต้องหรือไม่
    $stmt->bind_param("s", $user_email); // เปลี่ยนจาก $email เป็น $user_email
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        $error = "อีเมลนี้มีอยู่ในระบบแล้ว";
    } else {
        // ถ้าไม่มีข้อมูลซ้ำก็ทำการบันทึก
        $sql_insert = "INSERT INTO ademp (user_id, user_Fname, user_Lname, user_age, user_born, user_department, user_position, user_tel, user_email) 
                       VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?)";
        $stmt_insert = $connect->prepare($sql_insert);
        $stmt_insert->bind_param("sssisssss", $user_id, $user_Fname, $user_Lname, $user_age, $user_born, $user_department, $user_position, $user_tel, $user_email);

        if ($stmt_insert->execute()) {
            $_SESSION['success'] = "ข้อมูลพนักงานถูกบันทึกเรียบร้อยแล้ว";
            header("Location: employees.php");
            exit;
        } else {
            $error = "เกิดข้อผิดพลาดในการบันทึกข้อมูล";
        }
    }
}
?>

<!DOCTYPE html>
<html lang="th">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Add Employee - Manpower</title>
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
      <h3 class="mb-4">เพิ่มข้อมูลพนักงาน (Add Employee)</h3>

      <?php if (isset($error)) { ?>
        <div class="alert alert-danger">
          <?php echo $error; ?>
        </div>
      <?php } ?>

      <form action="new-emp.php" method="POST">
        <div class="row mb-3">
          <div class="col-md-4">
            <label for="emp_id" class="form-label">รหัสพนักงาน</label>
            <input type="text" id="user_id" name="user_id" class="form-control" required>
          </div>
          <div class="col-md-4">
            <label for="name" class="form-label">ชื่อ</label>
            <input type="text" id="user_Fname" name="user_Fname" class="form-control" required>
          </div>
          <div class="col-md-4">
            <label for="lastname" class="form-label">นามสกุล</label>
            <input type="text" id="user_Lname" name="user_Lname" class="form-control" required>
          </div>
        </div>

        <div class="row mb-3">
          <div class="col-md-2">
            <label for="age" class="form-label">อายุ</label>
            <input type="number" id="user_age" name="user_age" class="form-control" min="18" max="65" required>
          </div>
          <div class="col-md-4">
            <label for="dob" class="form-label">วัน/เดือน/ปีเกิด</label>
            <input type="date" id="user_born" name="user_born" class="form-control" min="1965-01-01" max="2010-12-31" required>
          </div>
          <div class="col-md-6">
            <label for="department" class="form-label">แผนก (Department)</label>
            <select id="user_department" name="user_department" class="form-select" required>
              <option value="">-- Select Department --</option>
              <option value="Production">ฝ่ายผลิต (Production)</option>
              <option value="QC">ควบคุมคุณภาพ (QC)</option>
              <option value="Maintenance">ซ่อมบำรุง (Maintenance)</option>
              <option value="Warehouse">คลังสินค้า & จัดส่ง (Warehouse)</option>
              <option value="Sales">ฝ่ายขาย (Sales)</option>
              <option value="HR">ทรัพยากรบุคคล (HR)</option>
              <option value="Finance">การเงินและบัญชี (Finance)</option>
              <option value="Safety">ความปลอดภัย (Safety)</option>
              <option value="IT">ไอที (IT)</option>
            </select>
          </div>
        </div>

        <div class="row mb-3">
          <div class="col-md-6">
            <label for="position" class="form-label">ตำแหน่ง</label>
            <input type="text" id="user_position" name="user_position" class="form-control" required>
          </div>
          <div class="col-md-6">
            <label for="phone" class="form-label">เบอร์โทรศัพท์</label>
            <input type="tel" id="user_tel" name="user_tel" class="form-control" 
                   pattern="[0-9]{10}" placeholder="เช่น 0812345678">
          </div>
        </div>

        <div class="mb-3">
          <label for="email" class="form-label">อีเมล</label>
          <input type="email" id="user_email" name="user_email" class="form-control">
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
