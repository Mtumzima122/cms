<?php
session_start();
include 'db.php'; // adjust path if needed

// Ensure user is logged in
if (!isset($_SESSION['admin_id'])) {
    header("Location: /cms/test/login/login.php");
    exit();
}

$admin_id         = $_SESSION['admin_id'];
$current_password = $_POST['current_password'] ?? '';
$new_password     = $_POST['new_password']     ?? '';
$confirm_password = $_POST['confirm_password'] ?? '';

// 1) Check new/confirm match
if ($new_password !== $confirm_password) {
    echo "<script>alert('New passwords do not match!'); window.history.back();</script>";
    exit();
}

// 2) Fetch stored (plain‑text) password
$stmt = $conn->prepare("SELECT password FROM admins WHERE admin_id = ?");
$stmt->bind_param("i", $admin_id);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows !== 1) {
    echo "<script>alert('Admin not found.'); window.history.back();</script>";
    exit();
}

$row = $result->fetch_assoc();
$stored_password = $row['password'];

// 3) Verify old password by direct comparison
if ($current_password !== $stored_password) {
    echo "<script>alert('Incorrect current password.'); window.history.back();</script>";
    exit();
}

// 4) Update to new plain‑text password
$upd = $conn->prepare("UPDATE admins SET password = ? WHERE admin_id = ?");
$upd->bind_param("si", $new_password, $admin_id);

if ($upd->execute()) {
    echo "<script>alert('Password updated successfully.'); window.location.href='admin_dashboard.php';</script>";
} else {
    echo "<script>alert('Something went wrong. Please try again.'); window.history.back();</script>";
}
$upd->close();
$conn->close(); 