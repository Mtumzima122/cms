<?php
session_start();
include 'db.php';

// Fetch departments from database
$departments = $conn->query("SELECT * FROM departments");

// Handle login
if (isset($_POST['login'])) {
    $name = $_POST['name'];
    $department_id = $_POST['department'];
    $password = $_POST['password'];

    // Check if admin exists
    $query = $conn->prepare("SELECT * FROM admins WHERE full_name = ? AND department_id = ? AND password = ?");
    $query->bind_param("sis", $name, $department_id, $password);
    $query->execute();
    $result = $query->get_result();
    $admin = $result->fetch_assoc();

    if ($admin) {
        $_SESSION['admin_id'] = $admin['admin_id'];
        $_SESSION['full_name'] = $admin['full_name'];
        $_SESSION['role'] = $admin['role'];
        $_SESSION['department_id'] = $admin['department_id'];

        header("Location: ../dashboard/admin_dashboard.php");
        
        exit();
    } else {
        $error = "Invalid credentials!";
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Login</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css">
    <style>
        body {
            background-color: #003366;
            color: white;
            font-family: 'Arial', sans-serif;
        }
        .login-container {
            background: #004080;
            padding: 30px;
            border-radius: 10px;
            box-shadow: 0px 4px 8px rgba(0, 0, 0, 0.2);
            max-width: 400px;
            margin: auto;
            margin-top: 100px;
        }
        .form-label {
            font-weight: bold;
            color: white;
        }
        .form-control {
            background-color: white;
            color: black;
            border-radius: 5px;
            padding: 10px;
        }
        .btn-primary {
            background-color: #28a745;
            border: none;
            font-weight: bold;
        }
        .btn-primary:hover {
            background-color: #218838;
        }
    </style>
</head>
<body>

<div class="container">
    <div class="login-container">
        <h2 class="text-center">Admin Login</h2>
        <?php if (isset($error)): ?>
            <div class="alert alert-danger"><?= $error; ?></div>
        <?php endif; ?>

        <form method="POST">
            <div class="mb-3">
                <label class="form-label">Full Name:</label>
                <input type="text" name="name" class="form-control" required>
            </div>

            <div class="mb-3">
                <label class="form-label">Department:</label>
                <select name="department" class="form-control" required>
                    <option value="">Select Department</option>
                    <?php while ($dept = $departments->fetch_assoc()): ?>
                        <option value="<?= $dept['department_id']; ?>"><?= $dept['department_name']; ?></option>
                    <?php endwhile; ?>
                </select>
            </div>

            <div class="mb-3">
                <label class="form-label">Password:</label>
                <input type="password" name="password" class="form-control" required>
            </div>

            <button type="submit" name="login" class="btn btn-primary w-100">Login</button>
        </form>
    </div>
</div>

</body>
</html>
