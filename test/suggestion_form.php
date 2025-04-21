<?php
// Database connection
$conn = new mysqli('localhost', 'root', '', 'cms');
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);}

// Fetch all departments
$departments = $conn->query("SELECT * FROM departments");
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Submit Suggestion</title>
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
            <h2>Submit Your Suggestion</h2>

            <form action="submit_suggestion.php" method="POST">
                <!-- Department Dropdown -->
                <div class="mb-3">
                    <label class="form-label">Choose Department:</label>
                    <select name="department" class="form-control" required>
                        <option value="">-- Select Department --</option>
                        <?php while ($row = $departments->fetch_assoc()): ?>
                            <option value="<?= $row['department_id']; ?>"><?= $row['department_name']; ?></option>
                        <?php endwhile; ?>
                    </select>
                </div>

                <!-- Subject -->
                <div class="mb-3">
                    <label class="form-label">Subject:</label>
                    <input type="text" name="subject" class="form-control" required>
                </div>

                <!-- Suggestion Details -->
                <div class="mb-3">
                    <label class="form-label">Your Suggestion:</label>
                    <textarea name="details" class="form-control" rows="4" required></textarea>
                </div>

                <!-- Submit Button -->
                <button type="submit" class="btn btn-success w-100">Submit Suggestion</button>
            </form>
        </div>
    </div>
</body>
</html>
