<?php
// Only call this ONCE at the top of the file
session_start();

if (!isset($_SESSION['admin_id'])) {
    header("Location: /cms/test/login/login.php");
    exit();
}
?>
<?php

if (!isset($_SESSION['admin_id'])) {
    header("Location: /cms/test/login/login.php");
    exit();
}
?>
<?php
// Connect to your database
$conn = new mysqli("localhost", "root", "", "cms"); // Update with your DB credentials

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Handle delete action
if (isset($_GET['delete'])) {
    $adminId = $_GET['delete'];
    $conn->query("DELETE FROM admins WHERE admin_id = $adminId");
    header("Location: manage_admins.php");
    exit();
}

// Fetch admins
$sql = "SELECT a.admin_id, a.full_name, a.email, a.role, d.department_name 
        FROM admins a 
        LEFT JOIN departments d ON a.department_id = d.department_id";
$result = $conn->query($sql);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Manage Admins</title>
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

<div class="sidebar">
    <h4 class="text-center">Admin Panel</h4>
    <a href="admin_dashboard.php"><i class="fas fa-chart-line"></i> Dashboard</a>
    <a href="manage_complaints.php"><i class="fas fa-tasks"></i> Manage Complaints</a>
    <a href="manage_suggestions.php"><i class="fas fa-lightbulb"></i> Manage Suggestions</a>
    <a href="manage_admins.php"><i class="fas fa-users"></i> Manage Admins</a>
    <a href="add_admin.php"><i class="fas fa-user-plus"></i> Add New Admin</a>
    <a href="add_department.php"><i class="fas fa-building"></i> Add New Department</a>
    <a href="/cms/test/login/logout.php"><i class="fas fa-sign-out-alt"></i> Logout</a>

</div>

<div class="content">
    <h2>Manage Admins</h2>
    <div class="card">
        <div class="card-header bg-primary text-white">
            Admin List
        </div>
        <div class="card-body">
            <table class="table table-bordered table-striped">
                <thead class="table-dark">
                    <tr>
                        <th>ID</th>
                        <th>Full Name</th>
                        <th>Email</th>
                        <th>Role</th>
                        <th>Department</th>
                        <th>Action</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if ($result->num_rows > 0): ?>
                        <?php while($row = $result->fetch_assoc()): ?>
                            <tr>
                                <td><?= $row['admin_id'] ?></td>
                                <td><?= $row['full_name'] ?></td>
                                <td><?= $row['email'] ?></td>
                                <td><?= $row['role'] ?></td>
                                <td><?= $row['department_name'] ?? 'N/A' ?></td>
                                <td>
                                    <a href="?delete=<?= $row['admin_id'] ?>" class="btn btn-danger btn-sm"
                                       onclick="return confirm('Are you sure you want to delete this admin?')">
                                       Delete
                                    </a>
                                </td>
                            </tr>
                        <?php endwhile; ?>
                    <?php else: ?>
                        <tr><td colspan="6" class="text-center">No admins found.</td></tr>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
    </div>
</div>

</body>
</html>

<?php $conn->close(); ?>
