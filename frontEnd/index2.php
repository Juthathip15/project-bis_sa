<?php
session_start();
include 'connect.php'; // ไฟล์เชื่อมต่อ DB

// --- สรุปตัวเลข ---
$totalEmp = 0;
$openReq  = 0;
$approved = 0;

// จำนวนพนักงานทั้งหมด
$sql = "SELECT COUNT(*) AS cnt FROM ademp WHERE user_status='active'";
$rs  = mysqli_query($connect, $sql);
if ($rs) {
    $row = mysqli_fetch_assoc($rs);
    $totalEmp = $row['cnt'];
    mysqli_free_result($rs);
}

// จำนวนคำขอที่ยัง pending
$sql = "SELECT COUNT(*) AS cnt FROM request WHERE status='pending'";
$rs  = mysqli_query($connect, $sql);
if ($rs) {
    $row = mysqli_fetch_assoc($rs);
    $openReq = $row['cnt'];
    mysqli_free_result($rs);
}

// จำนวนคำขอที่ approved
$sql = "SELECT COUNT(*) AS cnt FROM request WHERE status='approved'";
$rs  = mysqli_query($connect, $sql);
if ($rs) {
    $row = mysqli_fetch_assoc($rs);
    $approved = $row['cnt'];
    mysqli_free_result($rs);
}

// --- ดึง recent requests (ล่าสุด 5 รายการ) ---
$recent = [];
$sql = "SELECT request_id, department, position, quantity, status
        FROM request
        ORDER BY created_at DESC
        LIMIT 5";
$rs = mysqli_query($connect, $sql);
if ($rs) {
    while ($r = mysqli_fetch_assoc($rs)) {
        $recent[] = $r;
    }
    mysqli_free_result($rs);
}
?>
<!doctype html>
<html lang="th">
<head>
<meta charset="utf-8">
<title>Dashboard</title>
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
<nav class="navbar navbar-expand-lg navbar-dark bg-primary mb-4">
  <div class="container-fluid">
    <a class="navbar-brand" href="index.php">Manpower</a>
    <ul class="navbar-nav">
     <!-- <li class="nav-item"><a class="nav-link active" href="index.php">Dashboard</a></li>
      <li class="nav-item"><a class="nav-link" href="employees.php">Employees</a></li> -->
      <li class="nav-item"><a class="nav-link" href="requests.php">Requests</a></li>
      <!-- <li class="nav-item"><a class="nav-link" href="approvals.php">Approvals</a></li>
      <li class="nav-item"><a class="nav-link" href="reports.php">Reports</a></li> -->
    </ul>
  </div>
</nav>

<div class="container">
  <div class="row text-center">
    <div class="col-md-4 mb-3">
      <div class="card p-3 shadow">
        <h5>Total Employees</h5>
        <h2><?php echo $totalEmp; ?></h2>
      </div>
    </div>
    <div class="col-md-4 mb-3">
      <div class="card p-3 shadow">
        <h5>Open Requests</h5>
        <h2><?php echo $openReq; ?></h2>
      </div>
    </div>
    <div class="col-md-4 mb-3">
      <div class="card p-3 shadow">
        <h5>Approved</h5>
        <h2><?php echo $approved; ?></h2>
      </div>
    </div>
  </div>

  <div class="card mt-3 p-3 shadow">
    <h5>Recent Requests</h5>
    <table class="table table-sm table-bordered align-middle">
      <thead class="table-light">
        <tr>
          <th>Request ID</th>
          <th>Dept</th>
          <th>Position</th>
          <th>Qty</th>
          <th>Status</th>
        </tr>
      </thead>
      <tbody>
        <?php if (count($recent) > 0): ?>
          <?php foreach ($recent as $i => $r): ?>
            <?php
              $statusClass = 'bg-secondary';
              if ($r['status'] === 'pending')  $statusClass = 'bg-warning text-dark';
              if ($r['status'] === 'approved') $statusClass = 'bg-success';
              if ($r['status'] === 'rejected') $statusClass = 'bg-danger';
            ?>
            <tr>
              <td><?php echo htmlspecialchars($r['request_id']); ?></td>
              <td><?php echo htmlspecialchars($r['department']); ?></td>
              <td><?php echo htmlspecialchars($r['position']); ?></td>
              <td><?php echo htmlspecialchars($r['quantity']); ?></td>
              <td><span class="badge <?php echo $statusClass; ?>"><?php echo htmlspecialchars($r['status']); ?></span></td>
            </tr>
          <?php endforeach; ?>
        <?php else: ?>
          <tr><td colspan="5" class="text-center text-muted">ยังไม่มีคำขอ</td></tr>
        <?php endif; ?>
      </tbody>
    </table>
  </div>
</div>
</body>
</html>
