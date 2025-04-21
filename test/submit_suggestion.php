<?php
// Database connection
$conn = new mysqli('localhost', 'root', '', 'cms');
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Check if form is submitted
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $department_id = $_POST['department'];
    $subject = $_POST['subject'];
    $details = $_POST['details'];

    // Insert data into the database
    $query = $conn->prepare("INSERT INTO suggestions (department_id, subject, details) VALUES (?, ?, ?)");
    $query->bind_param("iss", $department_id, $subject, $details);

    if ($query->execute()) {
        echo "<script>alert('Suggestion submitted successfully!'); window.location.href='index.php';</script>";
    } else {
        echo "<script>alert('Error submitting suggestion.'); window.history.back();</script>";
    }

    $query->close();
}

// Close database connection
$conn->close();
?>
