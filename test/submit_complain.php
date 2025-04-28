<?php
// Start session immediately
session_start();

// Error reporting for debugging
error_reporting(E_ALL);
ini_set('display_errors', 1);

// Database connection
$conn = new mysqli('localhost', 'root', '', 'cms');
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Check if form is submitted
if (isset($_POST['submit'])) {
    // Collect form data safely
    $department_id = $_POST['department'] ?? null;
    $category_id = !empty($_POST['category']) ? $_POST['category'] : null;
    $title = trim($_POST['title'] ?? '');
    $complaint = trim($_POST['complaint'] ?? '');

    // Validate required fields
    if (empty($department_id) || empty($title) || empty($complaint)) {
        echo "<script>alert('Please fill in all required fields.'); window.history.back();</script>";
        exit;
    }

    // Insert complaint into the database
    $query = $conn->prepare("INSERT INTO complaints (department_id, category_id, subject, deatails) VALUES (?, ?, ?, ?)");
    $query->bind_param("iiss", $department_id, $category_id, $title, $complaint);

    if ($query->execute()) {
        // Insert notification
        $notif_msg = "New complaint submitted.";

        // Check if session variables are set
        if (isset($_SESSION['admin_id']) && isset($_SESSION['role'])) {
            $admin_id = $_SESSION['admin_id'];
            $admin_role = $_SESSION['role'];

            // Notification logic based on role
            if (strtolower($admin_role) === 'super admin') {
                $notif_stmt = $conn->prepare("INSERT INTO notifications (type, message) VALUES ('complaint', ?)");
                $notif_stmt->bind_param("s", $notif_msg);
            } else {
                $notif_stmt = $conn->prepare("INSERT INTO notifications (type, message, department_id) VALUES ('complaint', ?, ?)");
                $notif_stmt->bind_param("si", $notif_msg, $department_id);
            }

            $notif_stmt->execute();
            $notif_stmt->close();
        }

        // Success message and redirect
        echo "<script>
                alert('Complaint submitted successfully!');
                window.location.href='index.html';
              </script>";
        exit;
    } else {
        // If error during query execution
        echo "<script>alert('Error submitting complaint: " . $conn->error . "'); window.history.back();</script>";
        exit;
    }

    $query->close();
}

// Close connection
$conn->close();
?>
