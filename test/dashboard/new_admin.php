
<?php

session_start();
if (!isset($_SESSION['admin_id'])) {
    header("Location: /cms/test/login/login.php");
    exit();
}
?>
<?php
include 'db.php'; // Database connection

// Handle form submission
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $full_name = $_POST['full_name'];
    $email = $_POST['email'];
    $password = $_POST['password'];
    $role = $_POST['role'];
    $department_id = $_POST['department_id'];

    // Check if email already exists
    $check_query = "SELECT * FROM admins WHERE email = '$email'";
    $result = mysqli_query($conn, $check_query);
    if (mysqli_num_rows($result) > 0) {
        $error_message = "Email already exists!";
    } else {
        // Insert new admin
        $insert_query = "INSERT INTO admins (full_name, email, password, role, department_id) VALUES ('$full_name', '$email', '$password', '$role', '$department_id')";
        if (mysqli_query($conn, $insert_query)) {
            $success_message = "Admin added successfully!";
        } else {
            $error_message = "Error adding admin.";
        }
    }
}

// Fetch departments
$departments_query = "SELECT * FROM departments";
$departments_result = mysqli_query($conn, $departments_query);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Add New Admin</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css" rel="stylesheet">

    <style>
        body { background-color: #f8f9fa; }
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
        .sidebar a:hover { background: #0056b3; }
        .content { margin-left: 260px; padding: 20px; }
    </style>
</head>
<body>
    <!-- <div class="sidebar">
        <h4 class="text-center">Admin Panel</h4>
        <a href="admin_dashboard.php"><i class="fas fa-chart-line"></i> Dashboard</a>
        <a href="manage_complaints.php"><i class="fas fa-tasks"></i> Manage Complaints</a>
        <a href="manage_suggestion.php"><i class="fas fa-lightbulb"></i> Manage Suggestions</a>
        <a href="manage_admin.php"><i class="fas fa-users"></i> Manage Admins</a>
        <a href="add_admin.php"><i class="fas fa-user-plus"></i> Add New Admin</a>
        <a href="add_department.php"><i class="fas fa-building"></i> Add New Department</a>
        <a href="logout.php"><i class="fas fa-sign-out-alt"></i> Logout</a>
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
        <h2>Add New Admin</h2>
        <?php if (isset($error_message)) echo "<div class='alert alert-danger'>$error_message</div>"; ?>
        <?php if (isset($success_message)) echo "<div class='alert alert-success'>$success_message</div>"; ?>
        <form method="POST" action="">
            <div class="mb-3">
                <label class="form-label">Full Name</label>
                <input type="text" class="form-control" name="full_name" required>
            </div>
            <div class="mb-3">
                <label class="form-label">Email</label>
                <input type="email" class="form-control" name="email" required>
            </div>
            <div class="mb-3">
                <label class="form-label">Password</label>
                <input type="password" class="form-control" name="password" required>
            </div>
            <div class="mb-3">
                <label class="form-label">Role</label>
                <select class="form-control" name="role">
                    <option value="super Admin">Super Admin</option>
                    <option value="hod">HOD</option>
                </select>
            </div>
            <div class="mb-3">
                <label class="form-label">Department</label>
                <select class="form-control" name="department_id">
                    <?php while ($row = mysqli_fetch_assoc($departments_result)) { ?>
                        <option value="<?php echo $row['department_id']; ?>">
                            <?php echo $row['department_name']; ?>
                        </option>
                    <?php } ?>
                </select>
            </div>
            <button type="submit" class="btn btn-primary">Add Admin</button>
        </form>
    </div>
</body>
</html>
