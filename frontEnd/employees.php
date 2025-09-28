<?php
// employees.php
session_start();
include 'connect.php'; // ต้องมี $connect = mysqli_connect(...)

$rows  = [];
$error = null;

// ----- รับค่า filter สถานะจาก GET (all / active / leave) -----
$allowed = ['all', 'active', 'leave'];
$status  = isset($_GET['status']) ? strtolower(trim($_GET['status'])) : 'all';
if (!in_array($status, $allowed, true)) {
    $status = 'all';
}


if ($status === 'all') {
    $sql = "SELECT user_id, user_Fname, user_Lname, user_department, user_position, user_status 
            FROM ademp ORDER BY id DESC";  // <-- ใช้ DESC
    $result = mysqli_query($connect, $sql);
} else {
    $sql = "SELECT user_id, user_Fname, user_Lname, user_department, user_position, user_status 
            FROM ademp WHERE user_status = ? ORDER BY user_id DESC"; // <-- ใช้ DESC
    $stmt = mysqli_prepare($connect, $sql);
    mysqli_stmt_bind_param($stmt, "s", $status);
    mysqli_stmt_execute($stmt);
    $result = mysqli_stmt_get_result($stmt);
}


// รวบรวมผลลัพธ์
if ($result) {
    while ($r = mysqli_fetch_assoc($result)) {
        $rows[] = $r;
    }
    if (isset($stmt)) mysqli_stmt_close($stmt);
    if (is_object($result)) mysqli_free_result($result);
} else {
    $error = "ดึงข้อมูลไม่สำเร็จ: " . mysqli_error($connect);
}
?>
<!doctype html>
<html lang="th">
<head>
<meta charset="utf-8">
<title>Employees - Manpower</title>
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
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
  <div class="d-flex flex-wrap justify-content-between align-items-center mb-3">
    <h4 class="m-0">Employee Management</h4>
    <div class="d-flex gap-2">
      <!-- ฟิลเตอร์สถานะ -->
      <div class="btn-group" role="group" aria-label="Status filter">
        <a href="employees.php?status=all"    class="btn btn-outline-primary<?php echo $status==='all'?' active':''; ?>">All</a>
        <a href="employees.php?status=active" class="btn btn-outline-success<?php echo $status==='active'?' active':''; ?>">Active</a>
        <a href="employees.php?status=leave"  class="btn btn-outline-secondary<?php echo $status==='leave'?' active':''; ?>">Leave</a>
      </div>
      <a href="new-emp.php" class="btn btn-success btn-sm">+ New</a>
    </div>
  </div>

  <?php if (isset($_SESSION['success'])): ?>
    <div class="alert alert-success"><?php echo htmlspecialchars($_SESSION['success']); unset($_SESSION['success']); ?></div>
  <?php endif; ?>

  <?php if (isset($_SESSION['error'])): ?>
    <div class="alert alert-danger"><?php echo htmlspecialchars($_SESSION['error']); unset($_SESSION['error']); ?></div>
  <?php endif; ?>

  <?php if ($error): ?>
    <div class="alert alert-danger"><?php echo htmlspecialchars($error); ?></div>
  <?php endif; ?>

  <table class="table mt-3 table-striped table-bordered">
    <thead class="table-primary">
      <tr>
        <th style="width: 10%">ID</th>
        <th style="width: 20%">Name</th>
        <th style="width: 20%">Dept</th>
        <th style="width: 20%">Position</th>
        <th style="width: 10%">Status</th>
        <th style="width: 20%">Actions</th>
      </tr>
    </thead>
    <tbody>
      <?php if (count($rows) === 0): ?>
        <tr><td colspan="6" class="text-center text-muted">ไม่มีข้อมูลพนักงาน</td></tr>
      <?php else: ?>
        <?php foreach ($rows as $row): ?>
          <tr>
            <td><?php echo htmlspecialchars($row['user_id']); ?></td>
            <td><?php echo htmlspecialchars($row['user_Fname'] . ' ' . $row['user_Lname']); ?></td>
            <td><?php echo htmlspecialchars($row['user_department']); ?></td>
            <td><?php echo htmlspecialchars($row['user_position']); ?></td>
            <td>
              <?php if ($row['user_status'] === 'active'): ?>
                <span class="badge bg-success">Active</span>
              <?php else: ?>
                <span class="badge bg-secondary">Leave</span>
              <?php endif; ?>
            </td>
            <td>
              <a href="edit-emp.php?id=<?php echo urlencode($row['user_id']); ?>" class="btn btn-sm btn-primary">Edit</a>
              <?php if ($row['user_status'] === 'active'): ?>
                <!-- ปุ่ม Hide: เปลี่ยนสถานะเป็น leave -->
                <a href="hide-emp.php?id=<?php echo urlencode($row['user_id']); ?>"
                   class="btn btn-sm btn-warning"
                   onclick="return confirm('ยืนยันการซ่อนพนักงานรหัส <?php echo htmlspecialchars($row['user_id']); ?> ?');">
                  Hide
                </a>
              <?php endif; ?>
            </td>
          </tr>
        <?php endforeach; ?>
      <?php endif; ?>
    </tbody>
  </table>
</div>
</body>
</html>
