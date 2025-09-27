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
<li class="nav-item"><a class="nav-link active" href="index.php">Dashboard</a></li>
<li class="nav-item"><a class="nav-link" href="employees.php">Employees</a></li>
<li class="nav-item"><a class="nav-link" href="requests.php">Requests</a></li>
<li class="nav-item"><a class="nav-link" href="approvals.php">Approvals</a></li>
<li class="nav-item"><a class="nav-link" href="reports.php">Reports</a></li>
</ul>
</div>
</nav>
<div class="container">
<div class="row text-center">
<div class="col-md-4 mb-3"><div class="card p-3"><h5>Total Employees</h5><h2>15</h2></div></div>
<div class="col-md-4 mb-3"><div class="card p-3"><h5>Open Requests</h5><h2>3</h2></div></div>
<div class="col-md-4 mb-3"><div class="card p-3"><h5>Approved</h5><h2>7</h2></div></div>
</div>
<div class="card mt-3 p-3">
<h5>Recent Requests</h5>
<table class="table table-sm">
<thead><tr><th>#</th><th>Dept</th><th>Position</th><th>Qty</th><th>Status</th></tr></thead>
<tbody>
<tr><td>1</td><td>Sales</td><td>Sales Rep</td><td>2</td><td>Pending</td></tr>
<tr><td>2</td><td>HR</td><td>Officer</td><td>1</td><td>Approved</td></tr>
</tbody>
</table>
</div>
</div>
</body>
</html>