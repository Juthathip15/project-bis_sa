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
</ul>
</div>
</nav>
<div class="container">
<h4>Employee Management</h4>
<table class="table mt-3">
 <a href="new-emp.php" class="btn btn-success btn-sm">New</a>
<thead><tr><th>ID</th><th>Name</th><th>Dept</th><th>Position</th><th>Status</th><th>Actions</th></tr></thead>
<tbody>
  <tr>
    <td>1</td>
    <td>Somchai</td>
    <td>Sales</td>
    <td>Sales Rep</td>
    <td>Active</td>
    <td>
      <a href="edit-emp.php?id=1" class="btn btn-sm btn-primary">Edit</a>
      <button class="btn btn-sm btn-danger">Delete</button>
    </td>
  </tr>
  <tr>
    <td>2</td>
    <td>Suda</td>
    <td>HR</td>
    <td>Officer</td>
    <td>Active</td>
    <td>
      <a href="edit-emp.php?id=2" class="btn btn-sm btn-primary">Edit</a>
      <button class="btn btn-sm btn-danger">Delete</button>
    </td>
  </tr>
</tbody>

</table>
</div>
</body>
</html>