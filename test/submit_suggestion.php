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

    // Insert suggestion
    $query = $conn->prepare("INSERT INTO suggestions (department_id, subject, details) VALUES (?, ?, ?)");
    $query->bind_param("iss", $department_id, $subject, $details);

    if ($query->execute()) {
        // Insert notification for the respective department
        $notif_msg = "New suggestion submitted.";
        $notif_stmt = $conn->prepare("INSERT INTO notifications (type, message, department_id) VALUES ('suggestion', ?, ?)");
        $notif_stmt->bind_param("si", $notif_msg, $department_id);
        $notif_stmt->execute();
        $notif_stmt->close();

        echo "<script>alert('Suggestion submitted successfully!'); window.location.href='index.html';</script>";
    } else {
        echo "<script>alert('Error submitting suggestion.'); window.history.back();</script>";
    }

    $query->close();
}

// Close database connection
$conn->close();
?>
