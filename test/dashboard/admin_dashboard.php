<?php
session_start();
if (!isset($_SESSION['admin_id'])) {
    header("Location: /cms/test/login/login.php");
    exit();
}

include 'db.php';

$admin_id = $_SESSION['admin_id'];

// 1) Fetch admin role & department
$stmt = $conn->prepare("SELECT role, department_id FROM admins WHERE admin_id = ?");
$stmt->bind_param("i", $admin_id);
$stmt->execute();
$admin = $stmt->get_result()->fetch_assoc();
$role = $admin['role'];
$department_id = $admin['department_id'];

// 2) Fetch unread notifications into an array
if ($role === 'super Admin') {
    $res = $conn->query("SELECT id, message, created_at FROM notifications WHERE is_read = 0 ORDER BY created_at DESC");
} else {
    $stmt2 = $conn->prepare("SELECT id, message, created_at FROM notifications WHERE department_id = ? AND is_read = 0 ORDER BY created_at DESC");
    $stmt2->bind_param("i", $department_id);
    $stmt2->execute();
    $res = $stmt2->get_result();
}
$toasts = $res->fetch_all(MYSQLI_ASSOC);

// 3) Mark those notifications as read
if (!empty($toasts)) {
    $ids = array_column($toasts, 'id');
    $in  = implode(',', array_map('intval', $ids));
    $conn->query("UPDATE notifications SET is_read = 1 WHERE id IN ($in)");
}

// 4) Fetch counts (unchanged)
if ($role === 'super Admin') {
    $total_complaints  = $conn->query("SELECT COUNT(*) AS total FROM complaints")->fetch_assoc()['total'];
    $total_suggestions = $conn->query("SELECT COUNT(*) AS total FROM suggestions")->fetch_assoc()['total'];
} else {
    $stmt3 = $conn->prepare("SELECT COUNT(*) AS total FROM complaints WHERE department_id = ?");
    $stmt3->bind_param("i", $department_id);
    $stmt3->execute();
    $total_complaints = $stmt3->get_result()->fetch_assoc()['total'];

    $stmt4 = $conn->prepare("SELECT COUNT(*) AS total FROM suggestions WHERE department_id = ?");
    $stmt4->bind_param("i", $department_id);
    $stmt4->execute();
    $total_suggestions = $stmt4->get_result()->fetch_assoc()['total'];
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
  <title>Admin Dashboard</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
  <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css" rel="stylesheet">

  <style>
    body { background-color: #f8f9fa; }
    .sidebar {
      width: 250px; height: 100vh; position: fixed;
      background: #004080; padding-top: 20px; color: white;
    }
    .sidebar a {
      color: white; padding: 15px; display: block; text-decoration: none;
    }
    .sidebar a:hover { background: #0056b3; }
    .content { margin-left: 260px; padding: 20px; }
    .toast-container { position: fixed; top: 1rem; right: 1rem; z-index: 1055; }
  </style>
</head>
<body>

  <!-- <div class="sidebar">
    <h4 class="text-center">Admin Panel</h4>
    <a href="admin_dashboard.php">Dashboard</a>
    <a href="manage_complaints.php">Manage Complaints</a>
    <a href="manage_suggestion.php">Manage Suggestions</a>
    <a href="manage_admins.php">Manage Admins</a>
    <a href="new_admin.php">Add New Admin</a>
    <a href="reset_password.php">Reset Password</a>
    <a href="add_department.php">Add Department</a>
    <a href="/cms/test/login/logout.php">Logout</a>
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
    <h2>Dashboard</h2>
    <div class="row mt-4">
      <div class="col-md-4">
        <div class="card text-white bg-primary mb-3">
          <div class="card-body">
            <h5>Total Complaints</h5>
            <h3><?= $total_complaints ?></h3>
          </div>
        </div>
      </div>
      <div class="col-md-4">
        <div class="card text-white bg-success mb-3">
          <div class="card-body">
            <h5>Total Suggestions</h5>
            <h3><?= $total_suggestions ?></h3>
          </div>
        </div>
      </div>
    </div>
  </div>

  <!-- Toast Container -->
  <div class="toast-container">
    <?php foreach ($toasts as $note): ?>
      <div class="toast show align-items-center" role="alert" aria-live="assertive" aria-atomic="true">
        <div class="d-flex">
          <div class="toast-body">
            <?= htmlspecialchars($note['message']) ?>
            <div class="small text-muted"><?= date('d M Y, H:i', strtotime($note['created_at'])) ?></div>
          </div>
          <button type="button" class="btn-close btn-close-white me-2 m-auto" data-bs-dismiss="toast"></button>
        </div>
      </div>
    <?php endforeach; ?>
  </div>

  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
