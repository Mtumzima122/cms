<?php

ini_set('upload_max_filesize', '100M');
ini_set('post_max_size', '120M');
ini_set('max_execution_time', '300');
ini_set('max_input_time', '300');

// Database connection
$conn = new mysqli('localhost', 'root', '', 'cms');
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Fetch all departments
$departments = $conn->query("SELECT * FROM departments");
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Submit Complaint</title>
    <link rel="stylesheet" href="../css/bootstrap/css/bootstrap.min.css">
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <style>
        /* Styling */
        body {
            background-color: #003366;
            color: white;
            font-family: 'Arial', sans-serif;
        }
        .form-container {
            background: #004080;
            padding: 30px;
            border-radius: 8px;
            box-shadow: 0px 4px 8px rgba(0, 0, 0, 0.2);
            max-width: 500px;
            margin: auto;
            margin-top: 50px;
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
        .btn-success {
            background-color: #28a745;
            border: none;
            font-weight: bold;
        }
        .btn-success:hover {
            background-color: #218838;
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="form-container">
            <h2>Submit Your Complaint</h2>

            <form method="POST" action="submit_complain.php">
                <!-- Department Dropdown -->
                <div class="mb-3">
                    <label class="form-label">Choose Department:</label>
                    <select name="department" id="department" class="form-control" required>
                        <option value="">-- Select Department --</option>
                        <?php while ($row = $departments->fetch_assoc()): ?>
                            <option value="<?= $row['department_id']; ?>"><?= $row['department_name']; ?></option>
                        <?php endwhile; ?>
                    </select>
                </div>

                <!-- Category Dropdown (Dynamically Updated) -->
                <div class="mb-3">
                    <label class="form-label">Choose Category:</label>
                    <select name="category" id="category" class="form-control" required>
                        <option value="">-- Select Category --</option>
                    </select>
                </div>

                <!-- Title -->
                <div class="mb-3">
                    <label class="form-label">Title:</label>
                    <input type="text" name="title" class="form-control" required>
                </div>

                <!-- Complaint Description -->
                <div class="mb-3">
                    <label class="form-label">Your Complaint:</label>
                    <textarea name="complaint" class="form-control" rows="4" required></textarea>
                </div>

                <!-- Submit Button -->
                <button type="submit" name="submit" class="btn btn-success w-100">Submit Complaint</button>
            </form>
        </div>
    </div>

    <!-- jQuery AJAX for Category Selection -->
    <script>
        $(document).ready(function(){
            $("#department").change(function(){
                var department_id = $(this).val();
                $.ajax({
                    url: "fetch_categories.php",
                    method: "POST",
                    data: { department_id: department_id },
                    success: function(data) {
                        $("#category").html(data);
                    }
                });
            });
        });
    </script>
</body>
</html>
