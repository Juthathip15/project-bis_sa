<?php
include 'connect.php';
header('Content-Type: text/csv; charset=utf-8');
header('Content-Disposition: attachment; filename=employees.csv');

$out = fopen('php://output', 'w');
fputcsv($out, ['user_id','user_Fname','user_Lname','user_department','user_position','user_status']);

$sql = "SELECT user_id, user_Fname, user_Lname, user_department, user_position, user_status FROM ademp ORDER BY user_id";
$rs = mysqli_query($connect, $sql);
while ($r = mysqli_fetch_assoc($rs)) {
  fputcsv($out, $r);
}
fclose($out);
exit;
