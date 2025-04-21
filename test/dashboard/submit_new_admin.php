<?php
session_start();
include '../config/db_connect.php'; // Database connection file

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $full_name = trim($_POST['full_name']);
    $email = trim($_POST['email']);
    $password = trim($_POST['password']);
    $role = $_POST['role'];
    $department_id = $_POST['department_id'];

    if (empty($full_name) || empty($email) || empty($password) || empty($role) || empty($department_id)) {
        $_SESSION['error'] = "All fields are required!";
        header("Location: add_new_admin.php");
        exit();
    }

    // Check if email already exists
    $stmt = $conn->prepare("SELECT email FROM admins WHERE email = ?");
    $stmt->bind_param("s", $email);
    $stmt->execute();
    $stmt->store_result();
    
    if ($stmt->num_rows > 0) {
        $_SESSION['error'] = "Email already exists!";
        header("Location: add_new_admin.php");
        exit();
    }
    $stmt->close();

    // Insert new admin
    $stmt = $conn->prepare("INSERT INTO admins (full_name, email, password, role, department_id) VALUES (?, ?, ?, ?, ?)");
    $stmt->bind_param("ssssi", $full_name, $email, $password, $role, $department_id);

    if ($stmt->execute()) {
        $_SESSION['success'] = "Admin added successfully!";
        header("Location: add_new_admin.php");
        exit();
    } else {
        $_SESSION['error'] = "Failed to add admin!";
        header("Location: add_new_admin.php");
        exit();
    }
}
?>
