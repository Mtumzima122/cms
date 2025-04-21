<?php
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
    $department_id = $_POST['department'];
    $category_id = !empty($_POST['category']) ? $_POST['category'] : NULL;
    $title = $_POST['title'];
    $complaint = $_POST['complaint'];

    // Insert complaint into the database
    $query = $conn->prepare("INSERT INTO complaints (department_id, category_id, subject, deatails) VALUES (?, ?, ?, ?)");
    $query->bind_param("iiss", $department_id, $category_id, $title, $complaint);

    if ($query->execute()) {
        // Insert notification for the respective department
        $notif_msg = "New complaint submitted.";
        
        // Check if the logged-in admin is super admin or HOD
        session_start();
        $admin_id = $_SESSION['admin_id']; // Assuming admin_id is saved in session
        $admin_role = $_SESSION['role']; // Assuming role is saved in session

        // If admin is super admin, notify all departments
        if ($admin_role == 'super Admin') {
            $notif_stmt = $conn->prepare("INSERT INTO notifications (type, message) VALUES ('complaint', ?)");
            $notif_stmt->bind_param("s", $notif_msg);
            $notif_stmt->execute();
            $notif_stmt->close();
        } else {
            // If admin is HOD, notify only their department
            $notif_stmt = $conn->prepare("INSERT INTO notifications (type, message, department_id) VALUES ('complaint', ?, ?)");
            $notif_stmt->bind_param("si", $notif_msg, $department_id);
            $notif_stmt->execute();
            $notif_stmt->close();
        }

        echo "<script>alert('Complaint submitted successfully!'); window.location.href='index.html';</script>";
    } else {
        echo "Error: " . $conn->error;
    }

    $query->close();
}

// Close database connection
$conn->close();
?>
