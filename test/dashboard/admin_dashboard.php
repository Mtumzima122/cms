<?php
session_start();

if (!isset($_SESSION['admin_id'])) {
    header("Location: /cms/test/login/login.php");
    exit();
}

include 'db.php'; // Update the path if needed

$admin_id = $_SESSION['admin_id'];

// Fetch admin role and department from the `admins` table
$sql = "SELECT role, department_id FROM admins WHERE admin_id = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $admin_id);
$stmt->execute();
$result = $stmt->get_result();
$admin = $result->fetch_assoc();

$role = $admin['role'];
$department_id = $admin['department_id'];

// Fetch counts based on role
if ($role === 'super Admin') {
    $total_complaints = $conn->query("SELECT COUNT(*) AS total FROM complaints")->fetch_assoc()['total'];
    $total_suggestions = $conn->query("SELECT COUNT(*) AS total FROM suggestions")->fetch_assoc()['total'];
    $total_evidences = $conn->query("SELECT COUNT(*) AS total FROM evidences")->fetch_assoc()['total'];
} else {
    // HOD sees only their department data
    $stmt1 = $conn->prepare("SELECT COUNT(*) AS total FROM complaints WHERE department_id = ?");
    $stmt1->bind_param("i", $department_id);
    $stmt1->execute();
    $total_complaints = $stmt1->get_result()->fetch_assoc()['total'];

    $stmt2 = $conn->prepare("SELECT COUNT(*) AS total FROM suggestions WHERE department_id = ?");
    $stmt2->bind_param("i", $department_id);
    $stmt2->execute();
    $total_suggestions = $stmt2->get_result()->fetch_assoc()['total'];

    // $stmt3 = $conn->prepare("SELECT COUNT(*) AS total FROM evidences WHERE department_id = ?");
    // $stmt3->bind_param("i", $department_id);
    // $stmt3->execute();
    // $total_evidences = $stmt3->get_result()->fetch_assoc()['total'];
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
  <title>Admin Dashboard</title>
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css">
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
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
<div class="sidebar">
    <h4 class="text-center">Admin Panel</h4>
    <a href="#dashboard"><i class="fas fa-chart-line"></i> Dashboard</a>
    <a href="manage_complaints.php"><i class="fas fa-tasks"></i> Manage Complaints</a>
    <a href="manage_suggestion.php"><i class="fas fa-lightbulb"></i> Manage Suggestions</a>
    <a href="#manage-users"><i class="fas fa-users"></i> Manage Admins</a>
    <a href="new_admin.php"><i class="fas fa-user-plus"></i> Add New Admin</a>
    <a href="#add-department"><i class="fas fa-building"></i> Add New Department</a>
    <a href="/cms/test/login/logout.php"><i class="fas fa-sign-out-alt"></i> Logout</a>
</div>

<div class="content">
    <section id="dashboard">
        <h2>Dashboard</h2>
        <div class="row">
            <div class="col-md-4">
                <div class="card text-white bg-primary mb-3">
                    <div class="card-body">
                        <h5 class="card-title">Total Complaints</h5>
                        <h3><?= $total_complaints ?></h3>
                    </div>
                </div>
            </div>
            <div class="col-md-4">
                <div class="card text-white bg-success mb-3">
                    <div class="card-body">
                        <h5 class="card-title">Total Suggestions</h5>
                        <h3><?= $total_suggestions ?></h3>
                    </div>
                </div>
            </div>
        </div>
    </section>
</div>
</body>
</html>
