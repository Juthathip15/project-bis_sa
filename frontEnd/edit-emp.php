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
      </ul>
    </div>
  </nav>

  <div class="container">
    <div class="card shadow p-4">
      <h3 class="mb-4">แก้ไขข้อมูลพนักงาน</h3>

      <form action="employees.php" method="POST">
        <div class="row mb-3">
          <div class="col-md-6">
            <label for="name" class="form-label">ชื่อ</label>
            <input type="text" id="name" name="name" class="form-control" required>
          </div>
          <div class="col-md-6">
            <label for="lastname" class="form-label">นามสกุล</label>
            <input type="text" id="lastname" name="lastname" class="form-control" required>
          </div>
        </div>

        <div class="row mb-3">
          <div class="col-md-6">
            <label for="phone" class="form-label">เบอร์โทรศัพท์</label>
            <input type="tel" id="phone" name="phone" class="form-control" 
                   pattern="[0-9]{10}" placeholder="เช่น 0812345678">
          </div>
          <div class="col-md-6">
            <label for="email" class="form-label">อีเมล</label>
            <input type="email" id="email" name="email" class="form-control">
          </div>
        </div>

        <div class="d-flex justify-content-between">
   <a href="employees.php" class="btn btn-secondary">ย้อนกลับ</a> <div> <button type="reset" class="btn btn-warning">ล้างข้อมูล</button> <button type="submit" class="btn btn-primary">บันทึกข้อมูล</button>
          <div>
            <button type="reset" class
