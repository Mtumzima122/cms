<?php
session_start();

if (!isset($_SESSION['admin_id'])) {
    header("Location: /cms/test/login/login.php");
    exit();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
  <title>Reset Password</title>
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css">
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
  <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css" rel="stylesheet">

  <style>
    body {
        background-color: #f8f9fa;
    }
    .sidebar {
        width: 250px;
        height: 100vh;
        position: fixed;
        background: #004080;
        padding-top: 20px;
        color: white;
    }
    .sidebar a {
        color: white;
        padding: 15px;
        display: block;
        text-decoration: none;
    }
    .sidebar a:hover {
        background: #0056b3;
    }
    .content {
        margin-left: 260px;
        padding: 20px;
    }
  </style>
</head>
<body>

<!-- <div class="sidebar">
    <h4 class="text-center">Admin Panel</h4>
    <a href="admin_dashboard.php"><i class="fas fa-chart-line"></i> Dashboard</a>
    <a href="manage_complaints.php"><i class="fas fa-tasks"></i> Manage Complaints</a>
    <a href="manage_suggestion.php"><i class="fas fa-lightbulb"></i> Manage Suggestions</a>
    <a href="manage_admins.php"><i class="fas fa-users"></i> Manage Admins</a>
    <a href="new_admin.php"><i class="fas fa-user-plus"></i> Add New Admin</a>
    <a href="reset_password.php"><i class="fas fa-key"></i> Reset Password</a>
    <a href="add_department.php"><i class="fas fa-building"></i> Add New Department</a>
    <a href="/cms/test/login/logout.php"><i class="fas fa-sign-out-alt"></i> Logout</a>
</div> -->
<div class="sidebar">
  <h4 class="text-center">Admin Panel</h4>
  <a href="admin_dashboard.php"><i class="bi bi-speedometer2"></i> Dashboard</a>
  <a href="manage_complaints.php"><i class="bi bi-exclamation-triangle"></i> Manage Complaints</a>
  <a href="manage_suggestion.php"><i class="bi bi-lightbulb"></i> Manage Suggestions</a>
  <a href="new_admin.php"><i class="bi bi-person-plus"></i> Add New Admin</a>
  <a href="reset_password.php"><i class="bi bi-key"></i> Reset Password</a>
  <a href="/cms/test/login/logout.php"><i class="bi bi-box-arrow-right"></i> Logout</a>
</div>

<div class="content">
    <h2>Reset Password</h2>
    <form action="process_reset_password.php" method="POST" class="mt-4">
        <div class="mb-3">
            <label for="current_password" class="form-label">Current Password</label>
            <input type="password" class="form-control" id="current_password" name="current_password" required>
        </div>
        <div class="mb-3">
            <label for="new_password" class="form-label">New Password</label>
            <input type="password" class="form-control" id="new_password" name="new_password" required>
        </div>
        <div class="mb-3">
            <label for="confirm_password" class="form-label">Confirm New Password</label>
            <input type="password" class="form-control" id="confirm_password" name="confirm_password" required>
        </div>
        <button type="submit" class="btn btn-primary">Reset Password</button>
    </form>
</div>

</body>
</html>
