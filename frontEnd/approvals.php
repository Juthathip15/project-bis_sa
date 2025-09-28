<?php
session_start();

// เชื่อมต่อฐานข้อมูล
include 'connect.php';

// ฟังก์ชันสำหรับอัปเดตสถานะของคำขอ
if (isset($_GET['action']) && isset($_GET['id'])) {
    $action = $_GET['action'];
    $id = $_GET['id'];
    
    if ($action === 'approve') {
        $status = 'approved';
    } elseif ($action === 'reject') {
        $status = 'rejected';
    } else {
        $status = 'pending'; // Default
    }

    // อัปเดตสถานะคำขอ
    $sql = "UPDATE request SET status = ? WHERE id = ?";
    $stmt = mysqli_prepare($connect, $sql);
    mysqli_stmt_bind_param($stmt, "si", $status, $id);
    if (mysqli_stmt_execute($stmt)) {
        $_SESSION['success'] = "คำขอได้รับการอัปเดตเป็น $status";
    } else {
        $_SESSION['error'] = 'เกิดข้อผิดพลาดในการอัปเดตคำขอ';
    }
    header('Location: approvals.php');
    exit;
}

// ดึงคำขอที่สถานะเป็น 'pending'
$sql = "SELECT id, request_id, department, position, quantity, requested_by, status, created_at
        FROM request WHERE status = 'pending'
        ORDER BY created_at DESC";
$list_rs = mysqli_query($connect, $sql);
?>

<!doctype html>
<html lang="th">
<head>
    <meta charset="utf-8">
    <title>Approvals - Manpower</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
<nav class="navbar navbar-expand-lg navbar-dark bg-primary mb-4">
    <div class="container-fluid">
        <a class="navbar-brand" href="index.php">Manpower</a>
        <ul class="navbar-nav">
            <li class="nav-item"><a class="nav-link" href="index.php">Dashboard</a></li>
            <li class="nav-item"><a class="nav-link" href="employees.php">Employees</a></li>
            <li class="nav-item"><a class="nav-link" href="requests.php">Requests</a></li>
            <li class="nav-item"><a class="nav-link active" href="approvals.php">Approvals</a></li>
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
    <h4>Approvals</h4>

    <?php if (!empty($_SESSION['success'])) { ?>
        <div class="alert alert-success"><?php echo $_SESSION['success']; unset($_SESSION['success']); ?></div>
    <?php } ?>
    <?php if (!empty($_SESSION['error'])) { ?>
        <div class="alert alert-danger"><?php echo $_SESSION['error']; unset($_SESSION['error']); ?></div>
    <?php } ?>

    <table class="table table-striped table-bordered">
        <thead class="table-primary">
            <tr>
                <th>Request ID</th>
                <th>Dept</th>
                <th>Position</th>
                <th>Qty</th>
                <th>Requested By</th>
                <th>Status</th>
                <th>Created At</th>
                <th>Actions</th>
            </tr>
        </thead>
        <tbody>
        <?php if ($list_rs && mysqli_num_rows($list_rs) > 0) { ?>
            <?php while ($r = mysqli_fetch_assoc($list_rs)) { ?>
                <tr>
                    <td><?php echo htmlspecialchars($r['request_id']); ?></td>
                    <td><?php echo htmlspecialchars($r['department']); ?></td>
                    <td><?php echo htmlspecialchars($r['position']); ?></td>
                    <td><?php echo htmlspecialchars($r['quantity']); ?></td>
                    <td><?php echo htmlspecialchars($r['requested_by']); ?></td>
                    <td>
                        <span class="badge bg-warning"><?php echo htmlspecialchars($r['status']); ?></span>
                    </td>
                    <td><?php echo htmlspecialchars($r['created_at']); ?></td>
                    <td>
                        <a href="approvals.php?action=approve&id=<?php echo $r['id']; ?>" class="btn btn-sm btn-success">Approve</a>
                        <a href="approvals.php?action=reject&id=<?php echo $r['id']; ?>" class="btn btn-sm btn-danger">Reject</a>
                    </td>
                </tr>
            <?php } ?>
        <?php } else { ?>
            <tr>
                <td colspan="9" class="text-center text-muted">ยังไม่มีคำขอรออนุมัติ</td>
            </tr>
        <?php } ?>
        </tbody>
    </table>
</div>
</body>
</html>
