<?php
include 'connect.php';
header('Content-Type: text/csv; charset=utf-8');
header('Content-Disposition: attachment; filename=requests.csv');

$out = fopen('php://output', 'w');
fputcsv($out, ['request_id','department','position','quantity','request_type','status','requested_by','created_at']);

$sql = "SELECT request_id, department, position, quantity, request_type, status, requested_by, created_at
        FROM request
        ORDER BY created_at DESC";
$rs = mysqli_query($connect, $sql);
while ($r = mysqli_fetch_assoc($rs)) {
  fputcsv($out, $r);
}
fclose($out);
exit;
