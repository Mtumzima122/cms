<?php
session_start();
error_reporting(E_ALL);
ini_set('display_errors', 1);

// Connect to the database
$conn = new mysqli('localhost', 'root', '', 'cms');
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Ensure only super admin can access
if (!isset($_SESSION['role']) || $_SESSION['role'] !== 'super Admin') {
    echo "Access denied. Super Admins only.";
    exit;
}

// Handle delete request
if (isset($_GET['delete'])) {
    $delete_id = intval($_GET['delete']);
    if ($delete_id != $_SESSION['admin_id']) { // Prevent deleting yourself
        $delete_stmt = $conn->prepare("DELETE FROM admins WHERE admin_id = ?");
        $delete_stmt->bind_param("i", $delete_id);
        $delete_stmt->execute();
        $delete_stmt->close();
    }
    header("Location: manage_admins.php");
    exit;
}

// Get all admins except the currently logged-in super admin
$current_admin_id = $_SESSION['admin_id'];
$result = $conn->query("SELECT admin_id, full_name, email FROM admins WHERE admin_id != $current_admin_id");
?>

<!DOCTYPE html>
<html>
<head>
    <title>Manage Admins</title>
    <style>
        table {
            width: 70%;
            margin: 20px auto;
            border-collapse: collapse;
        }
        th, td {
            padding: 12px;
            border: 1px solid #444;
            text-align: center;
        }
        th {
            background-color: #333;
            color: white;
        }
        tr:nth-child(even) {
            background-color: #f4f4f4;
        }
        .btn-delete {
            background-color: red;
            color: white;
            padding: 6px 12px;
            text-decoration: none;
            border-radius: 5px;
        }
    </style>
</head>
<body>

<h2 style="text-align:center;">Manage Admins</h2>

<table>
    <tr>
        <th>Full Name</th>
        <th>Email</th>
        <th>Action</th>
    </tr>

    <?php while ($row = $result->fetch_assoc()): ?>
        <tr>
            <td><?= htmlspecialchars($row['full_name']) ?></td>
            <td><?= htmlspecialchars($row['email']) ?></td>
            <td>
                <a href="?delete=<?= $row['admin_id'] ?>" class="btn-delete" onclick="return confirm('Are you sure you want to delete this admin?')">Delete</a>
            </td>
        </tr>
    <?php endwhile; ?>
</table>

</body>
</html>

<?php $conn->close(); ?>
