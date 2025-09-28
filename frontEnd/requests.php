<?php
session_start();

include 'connect.php';

if (!isset($_SESSION['role'])) {
  header('Location: login.php');
  exit;
}

if (!function_exists('random_int')) {
    function random_int($min, $max) { return mt_rand($min, $max); }
}

function make_request_id($db) {
    $base = 'REQ-' . date('Ymd') . '-';
    do {
        $id = $base . str_pad((string)random_int(0, 9999), 4, '0', STR_PAD_LEFT);
        $q  = mysqli_prepare($db, "SELECT 1 FROM request WHERE request_id = ? LIMIT 1");
        mysqli_stmt_bind_param($q, "s", $id);
        mysqli_stmt_execute($q);
        mysqli_stmt_store_result($q);
        $exists = mysqli_stmt_num_rows($q) > 0;
        mysqli_stmt_close($q);
    } while ($exists);
    return $id;
}

// ----- บันทึกฟอร์ม -----
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // รับค่า (แทนที่ ?? ด้วย isset)
    $department   = isset($_POST['department'])   ? trim($_POST['department'])   : '';
    $position     = isset($_POST['position'])     ? trim($_POST['position'])     : '';
    $quantity     = isset($_POST['quantity'])     ? (int)$_POST['quantity']      : 0;
    $request_type = isset($_POST['request_type']) ? trim($_POST['request_type']) : '';
    $requested_by = isset($_POST['requested_by']) ? trim($_POST['requested_by']) : '';
    $reason       = isset($_POST['reason'])       ? trim($_POST['reason'])       : '';

    // whitelist ตัวเลือก
    $allowDept  = array('Production','QC','Maintenance','Warehouse','Sales','HR','Finance','Safety','IT');
    $allowType  = array('monthly','daily','office','intern');
    $allowReqBy = array('HR','Manager');

    $errors = array();
    if (!in_array($department, $allowDept, true))   $errors[] = 'กรุณาเลือกแผนกให้ถูกต้อง';
    if ($position === '')                            $errors[] = 'กรุณากรอกตำแหน่ง';
    if ($quantity <= 0)                              $errors[] = 'จำนวนต้องมากกว่า 0';
    if (!in_array($request_type, $allowType, true))  $errors[] = 'กรุณาเลือกประเภทให้ถูกต้อง';
    if (!in_array($requested_by, $allowReqBy, true)) $errors[] = 'กรุณาเลือกผู้ร้องขอให้ถูกต้อง';

    if (!empty($errors)) {
        $_SESSION['error'] = implode('<br>', $errors);
        header('Location: requests.php');
        exit;
    }

    // กันรหัสซ้ำด้วย retry เมื่อชน UNIQUE (errno 1062)
    $maxRetry = 5;
    for ($i = 0; $i < $maxRetry; $i++) {
        $request_id = make_request_id($connect);
        $status     = 'pending';

        $sql = "INSERT INTO request
                (request_id, department, position, request_type, quantity, reason, status, requested_by)
                VALUES (?, ?, ?, ?, ?, ?, ?, ?)";
        $stmt = mysqli_prepare($connect, $sql);
        mysqli_stmt_bind_param(
            $stmt, "ssssisss",
            $request_id, $department, $position, $request_type, $quantity, $reason, $status, $requested_by
        );

        if (mysqli_stmt_execute($stmt)) {
            mysqli_stmt_close($stmt);
            $_SESSION['success'] = "บันทึกคำขอเลขที่ <strong>" . $request_id . "</strong> สำเร็จ";
            header('Location: requests.php');
            exit;
        }

        $errno = mysqli_errno($connect);
        mysqli_stmt_close($stmt);

        if ($errno === 1062) {
            // duplicate entry -> ลองใหม่
            continue;
        } else {
            error_log('INSERT request error: [' . $errno . '] ' . mysqli_error($connect));
            $_SESSION['error'] = 'บันทึกไม่สำเร็จ กรุณาลองอีกครั้ง';
            header('Location: requests.php');
            exit;
        }
    }

    $_SESSION['error'] = 'มีการใช้งานพร้อมกันจำนวนมาก กรุณาลองใหม่อีกครั้ง';
    header('Location: requests.php');
    exit;
}

// ----- ดึงรายการทั้งหมด -----
$list_sql = "SELECT id, request_id, department, position, quantity, request_type, status, requested_by, created_at
             FROM request
             ORDER BY created_at DESC, id DESC";
$list_rs  = mysqli_query($connect, $list_sql);
?>
<!doctype html>
<html lang="th">
<head>
<meta charset="utf-8">
<title>Requests - Manpower</title>
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
<nav class="navbar navbar-expand-lg navbar-dark bg-primary mb-4">
  <div class="container-fluid">
    <a class="navbar-brand" href="index.php">Manpower</a>
    <ul class="navbar-nav">

      <?php if ($_SESSION['role'] === 'admin'): ?>
        <li class="nav-item"><a class="nav-link active" href="index.php">Dashboard</a></li>
        <li class="nav-item"><a class="nav-link" href="employees.php">Employees</a></li>
        <li class="nav-item"><a class="nav-link" href="requests.php">Requests</a></li>
        <li class="nav-item"><a class="nav-link" href="approvals.php">Approvals</a></li>
        <li class="nav-item"><a class="nav-link" href="reports.php">Reports</a></li>
      <?php else: ?>
        <!-- ถ้าเป็น user -->
        <li class="nav-item"><a class="nav-link active" href="requests.php">Requests</a></li>
      <?php endif; ?>
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

  <?php if (!empty($_SESSION['success'])) { ?>
    <div class="alert alert-success"><?php echo $_SESSION['success']; unset($_SESSION['success']); ?></div>
  <?php } ?>
  <?php if (!empty($_SESSION['error'])) { ?>
    <div class="alert alert-danger"><?php echo $_SESSION['error']; unset($_SESSION['error']); ?></div>
  <?php } ?>

  <h4>New Manpower Request</h4>

  <form class="mt-3" method="POST" action="requests.php" autocomplete="off">
    <div class="row g-2">

      <div class="col-md-3">
        <label class="form-label" for="department">Department</label>
        <select id="department" name="department" class="form-select" required>
          <option value="">-- Select Department --</option>
          <option value="Production">Production</option>
          <option value="QC">QC</option>
          <option value="Maintenance">Maintenance</option>
          <option value="Warehouse">Warehouse</option>
          <option value="Sales">Sales</option>
          <option value="HR">HR</option>
          <option value="Finance">Finance</option>
          <option value="Safety">Safety</option>
          <option value="IT">IT</option>
        </select>
      </div>

      <div class="col-md-3">
        <label class="form-label" for="position">Position</label>
        <input id="position" name="position" class="form-control" placeholder="Position" required>
      </div>

      <div class="col-md-2">
        <label class="form-label" for="quantity">Qty</label>
        <input id="quantity" name="quantity" type="number" min="1" class="form-control" placeholder="Qty" required>
      </div>

      <div class="col-md-3">
        <label class="form-label" for="request_type">Type</label>
        <select id="request_type" name="request_type" class="form-select" required>
          <option value="">-- Select Type --</option>
          <option value="monthly">รายเดือน</option>
          <option value="daily">รายวัน</option>
          <option value="office">ออฟฟิศ (8:00-17:00)</option>
          <option value="intern">ฝึกงาน</option>
        </select>
      </div>

      <div class="col-md-3">
        <label class="form-label" for="requested_by">Requested by</label>
        <select id="requested_by" name="requested_by" class="form-select" required>
          <option value="">-- Request by --</option>
          <option value="HR">HR</option>
          <option value="Manager">Manager</option>
        </select>
      </div>

      <div class="col-12">
        <label class="form-label" for="reason">Reason/Notes</label>
        <input id="reason" name="reason" class="form-control" placeholder="Reason/Notes">
      </div>

      <div class="col-12">
        <button class="btn btn-primary mt-2" type="submit">Submit</button>
      </div>

    </div>
  </form>

  <?php if ($_SESSION['role'] === 'admin'): ?>
    <hr>
    <h5>All Requests</h5>
    <div class="table-responsive">
      <table class="table table-striped table-bordered align-middle">
        <thead class="table-primary">
          <tr>
            <th>Request ID</th>
            <th>Dept</th>
            <th>Position</th>
            <th>Qty</th>
            <th>Type</th>
            <th>Status</th>
            <th>Requested By</th>
            <th>Created At</th>
          </tr>
        </thead>
        <tbody>
        <?php if ($list_rs && mysqli_num_rows($list_rs) > 0) { ?>
          <?php while($r = mysqli_fetch_assoc($list_rs)) { ?>
            <tr>
              <td><?php echo htmlspecialchars($r['request_id']); ?></td>
              <td><?php echo htmlspecialchars($r['department']); ?></td>
              <td><?php echo htmlspecialchars($r['position']); ?></td>
              <td><?php echo htmlspecialchars($r['quantity']); ?></td>
              <td><?php echo htmlspecialchars($r['request_type']); ?></td>
              <td>
                <?php
                  $statusClass = 'bg-secondary';
                  switch ($r['status']) {
                      case 'pending':    $statusClass = 'bg-warning text-dark'; break;
                      case 'approved':   $statusClass = 'bg-success'; break;
                      case 'rejected':   $statusClass = 'bg-danger'; break;
                      case 'processing': $statusClass = 'bg-info text-dark'; break;
                  }
                ?>
                <span class="badge <?php echo $statusClass; ?>">
                  <?php echo htmlspecialchars($r['status']); ?>
                </span>
              </td>
              <td><?php echo htmlspecialchars($r['requested_by']); ?></td>
              <td><?php echo htmlspecialchars($r['created_at']); ?></td>
            </tr>
          <?php } ?>
        <?php } else { ?>
            <tr><td colspan="9" class="text-center text-muted">ยังไม่มีข้อมูล</td></tr>
        <?php } ?>
        </tbody>
      </table>
    </div>
  <?php endif; ?>
        
</div>
</body>
</html>
