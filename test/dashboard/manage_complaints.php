<?php
session_start();
if (!isset($_SESSION['admin_id'])) {
    header("Location: ../login/login.php");
    exit();
}

include 'db.php'; // adjust if needed

$role = $_SESSION['role'];
$department_id = $_SESSION['department_id'];

if ($role === 'super Admin') {
    $sql = "SELECT complaints.*, departments.department_name 
            FROM complaints 
            JOIN departments ON complaints.department_id = departments.department_id";
} else {
    $sql = "SELECT complaints.*, departments.department_name 
            FROM complaints 
            JOIN departments ON complaints.department_id = departments.department_id 
            WHERE complaints.department_id = $department_id";
}

$result = mysqli_query($conn, $sql);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Manage Complaints</title>
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
        <a href="admin_dashboard.php"><i class="fas fa-chart-line"></i> Dashboard</a>
        <a href="manage_complaints.php"><i class="fas fa-tasks"></i> Manage Complaints</a>
        <a href="manage_suggestion.php"><i class="fas fa-lightbulb"></i> Manage Suggestions</a>
        <a href="manage_admins.php"><i class="fas fa-users-cog"></i> Manage Admins</a>
        <a href="add_admin_form.php"><i class="fas fa-user-plus"></i> Add New Admin</a>
        <a href="add_department.php"><i class="fas fa-building"></i> Add New Department</a>
        <a href="../login/logout.php"><i class="fas fa-sign-out-alt"></i> Logout</a>
    </div>

    <div class="content">
        <h2>Manage Complaints</h2>
        <table class="table table-bordered table-striped">
            <thead class="table-primary">
                <tr>
                    <th>ID</th>
                    <th>Department</th>
                    <th>Subject</th>
                    <th>Details</th>
                    <th>Evidence</th>
                </tr>
            </thead>
            <tbody>
                <?php while ($row = mysqli_fetch_assoc($result)): ?>
                    <tr>
                        <td><?= $row['compaint_id'] ?></td>
                        <td><?= $row['department_name'] ?></td>
                        <td><?= $row['subject'] ?></td>
                        <td><?= $row['deatails'] ?></td>
                        <td>
                            <?php if (!empty($row['evidance'])): ?>
                                <a href="../uploads/<?= $row['evidance'] ?>" target="_blank">View</a>
                            <?php else: ?>
                                None
                            <?php endif; ?>
                        </td>
                    </tr>
                <?php endwhile; ?>
            </tbody>
        </table>
    </div>
</body>
</html>
