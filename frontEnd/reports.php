<?php
// reports.php
session_start();
include 'connect.php'; // ต้องนิยาม $connect = mysqli_connect(...)

// ---------- สรุปพนักงาน ----------
$emp_total = 0; $emp_active = 0; $emp_leave = 0;

$rs = mysqli_query($connect, "SELECT COUNT(*) AS c FROM ademp");
if ($rs) { $emp_total = (int)mysqli_fetch_assoc($rs)['c']; mysqli_free_result($rs); }

$rs = mysqli_query($connect, "SELECT COUNT(*) AS c FROM ademp WHERE user_status='active'");
if ($rs) { $emp_active = (int)mysqli_fetch_assoc($rs)['c']; mysqli_free_result($rs); }

$rs = mysqli_query($connect, "SELECT COUNT(*) AS c FROM ademp WHERE user_status='leave'");
if ($rs) { $emp_leave = (int)mysqli_fetch_assoc($rs)['c']; mysqli_free_result($rs); }

// ---------- สรุปคำขอ ----------
$req_total = 0; $req_pending = 0; $req_approved = 0; $req_rejected = 0;

$rs = mysqli_query($connect, "SELECT COUNT(*) AS c FROM request");
if ($rs) { $req_total = (int)mysqli_fetch_assoc($rs)['c']; mysqli_free_result($rs); }

$rs = mysqli_query($connect, "SELECT COUNT(*) AS c FROM request WHERE status='pending'");
if ($rs) { $req_pending = (int)mysqli_fetch_assoc($rs)['c']; mysqli_free_result($rs); }

$rs = mysqli_query($connect, "SELECT COUNT(*) AS c FROM request WHERE status='approved'");
if ($rs) { $req_approved = (int)mysqli_fetch_assoc($rs)['c']; mysqli_free_result($rs); }

$rs = mysqli_query($connect, "SELECT COUNT(*) AS c FROM request WHERE status='rejected'");
if ($rs) { $req_rejected = (int)mysqli_fetch_assoc($rs)['c']; mysqli_free_result($rs); }

// ---------- คำขอล่าสุด ----------
$recent = [];
$sql = "SELECT request_id, department, position, quantity, status, requested_by, created_at
        FROM request
        ORDER BY created_at DESC, id DESC
        LIMIT 10";
$rs = mysqli_query($connect, $sql);
if ($rs) {
  while ($r = mysqli_fetch_assoc($rs)) { $recent[] = $r; }
  mysqli_free_result($rs);
}
?>
<!doctype html>
<html lang="th">
<head>
<meta charset="utf-8">
<title>Reports - Manpower</title>
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="bg-light">
<nav class="navbar navbar-expand-lg navbar-dark bg-primary mb-4">
  <div class="container-fluid">
    <a class="navbar-brand" href="index.php">Manpower</a>
    <ul class="navbar-nav">
      <li class="nav-item"><a class="nav-link" href="index.php">Dashboard</a></li>
      <li class="nav-item"><a class="nav-link" href="employees.php">Employees</a></li>
      <li class="nav-item"><a class="nav-link" href="requests.php">Requests</a></li>
      <li class="nav-item"><a class="nav-link" href="approvals.php">Approvals</a></li>
      <li class="nav-item"><a class="nav-link active" href="reports.php">Reports</a></li>
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
  <div class="d-flex flex-wrap justify-content-between align-items-center">
    <h4 class="m-0">Reports</h4>
    <div class="d-flex gap-2">
      <a class="btn btn-outline-primary" href="export_employees.php">Export Employees CSV</a>
      <a class="btn btn-outline-secondary" href="export_requests.php">Export Requests CSV</a>
    </div>
  </div>

  <!-- Summary Cards -->
  <div class="row text-center mt-3">
    <div class="col-md-3 mb-3">
      <div class="card p-3 shadow-sm">
        <h6 class="text-muted">Employees (Total)</h6>
        <h2 class="m-0"><?php echo $emp_total; ?></h2>
        <div class="small mt-2">
          <span class="badge bg-success">Active: <?php echo $emp_active; ?></span>
          <span class="badge bg-secondary">Leave: <?php echo $emp_leave; ?></span>
        </div>
      </div>
    </div>
    <div class="col-md-3 mb-3">
      <div class="card p-3 shadow-sm">
        <h6 class="text-muted">Requests (Total)</h6>
        <h2 class="m-0"><?php echo $req_total; ?></h2>
      </div>
    </div>
    <div class="col-md-3 mb-3">
      <div class="card p-3 shadow-sm">
        <h6 class="text-muted">Open (Pending)</h6>
        <h2 class="m-0"><?php echo $req_pending; ?></h2>
      </div>
    </div>
    <div class="col-md-3 mb-3">
      <div class="card p-3 shadow-sm">
        <h6 class="text-muted">Approved</h6>
        <h2 class="m-0"><?php echo $req_approved; ?></h2>
      </div>
    </div>
  </div>

  <!-- Employees Breakdown -->
  <div class="card mt-3 p-3 shadow-sm">
    <h5 class="mb-3">Employees Status Breakdown</h5>
    <table class="table table-bordered align-middle m-0">
      <thead class="table-light">
        <tr>
          <th>Status</th>
          <th>Count</th>
        </tr>
      </thead>
      <tbody>
        <tr>
          <td><span class="badge bg-success">Active</span></td>
          <td><?php echo $emp_active; ?></td>
        </tr>
        <tr>
          <td><span class="badge bg-secondary">Leave</span></td>
          <td><?php echo $emp_leave; ?></td>
        </tr>
        <tr class="table-primary">
          <td><strong>Total</strong></td>
          <td><strong><?php echo $emp_total; ?></strong></td>
        </tr>
      </tbody>
    </table>
  </div>

  <!-- Requests Breakdown -->
  <div class="card mt-3 p-3 shadow-sm">
    <h5 class="mb-3">Requests Breakdown</h5>
    <table class="table table-bordered align-middle m-0">
      <thead class="table-light">
        <tr>
          <th>Status</th>
          <th>Count</th>
        </tr>
      </thead>
      <tbody>
        <tr>
          <td><span class="badge bg-warning text-dark">Pending</span></td>
          <td><?php echo $req_pending; ?></td>
        </tr>
        <tr>
          <td><span class="badge bg-success">Approved</span></td>
          <td><?php echo $req_approved; ?></td>
        </tr>
        <tr>
          <td><span class="badge bg-danger">Rejected</span></td>
          <td><?php echo $req_rejected; ?></td>
        </tr>
        <tr class="table-primary">
          <td><strong>Total</strong></td>
          <td><strong><?php echo $req_total; ?></strong></td>
        </tr>
      </tbody>
    </table>
  </div>

  <!-- Recent Requests -->
  <div class="card mt-3 p-3 shadow-sm">
    <h5 class="mb-3">Recent Requests</h5>
    <div class="table-responsive">
      <table class="table table-striped table-bordered align-middle m-0">
        <thead class="table-primary">
          <tr>
            <th>Request ID</th>
            <th>Dept</th>
            <th>Position</th>
            <th>Qty</th>
            <th>Status</th>
            <th>Requested By</th>
            <th>Created At</th>
          </tr>
        </thead>
        <tbody>
          <?php if (count($recent) > 0): ?>
            <?php foreach ($recent as $r): ?>
              <?php
                $statusClass = 'bg-secondary';
                if ($r['status'] === 'pending')  $statusClass = 'bg-warning text-dark';
                if ($r['status'] === 'approved') $statusClass = 'bg-success';
                if ($r['status'] === 'rejected') $statusClass = 'bg-danger';
                if ($r['status'] === 'processing') $statusClass = 'bg-info text-dark';
              ?>
              <tr>
                <td><?php echo htmlspecialchars($r['request_id']); ?></td>
                <td><?php echo htmlspecialchars($r['department']); ?></td>
                <td><?php echo htmlspecialchars($r['position']); ?></td>
                <td><?php echo htmlspecialchars($r['quantity']); ?></td>
                <td><span class="badge <?php echo $statusClass; ?>"><?php echo htmlspecialchars($r['status']); ?></span></td>
                <td><?php echo htmlspecialchars($r['requested_by']); ?></td>
                <td><?php echo htmlspecialchars($r['created_at']); ?></td>
              </tr>
            <?php endforeach; ?>
          <?php else: ?>
            <tr><td colspan="7" class="text-center text-muted">ยังไม่มีคำขอล่าสุด</td></tr>
          <?php endif; ?>
        </tbody>
      </table>
    </div>
  </div>

</div>
</body>
</html>
