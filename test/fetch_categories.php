<?php
// Database connection
$conn = new mysqli('localhost', 'root', '', 'cms');
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Get selected department ID
if (isset($_POST['department_id'])) {
    $department_id = $_POST['department_id'];

    // Fetch categories related to this department
    $query = $conn->prepare("SELECT * FROM categories WHERE department_id = ?");
    $query->bind_param("i", $department_id);
    $query->execute();
    $result = $query->get_result();

    // Output category options
    echo '<option value="">-- Select Category --</option>';
    while ($row = $result->fetch_assoc()) {
        echo '<option value="'.$row['category_id'].'">'.$row['category_name'].'</option>';
    }
}
?>