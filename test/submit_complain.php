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

    // Insert data into the database without evidence
    $query = $conn->prepare("INSERT INTO complaints (department_id, category_id, subject, deatails) VALUES (?, ?, ?, ?)");
    $query->bind_param("iiss", $department_id, $category_id, $title, $complaint);

    if ($query->execute()) {
        echo "<script>alert('Complaint submitted successfully!'); window.location.href='test.php';</script>";
    } else {
        echo "Error: " . $conn->error;
    }

    $query->close();
}

// Close database connection
$conn->close();
?>
