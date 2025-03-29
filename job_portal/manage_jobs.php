<?php
// Start session only if not already started
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// Redirect to login if not logged in
if (!isset($_SESSION['user_id']) || $_SESSION['user_role'] !== 'employer') {
    header("Location: login.php");
    exit();
}

include 'php/db.php';

$user_id = $_SESSION['user_id'];

// Fetch jobs posted by this employer
$sql = "SELECT * FROM jobs WHERE employer_id = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $user_id);
$stmt->execute();
$result = $stmt->get_result();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Manage Jobs - Job Portal</title>
    <link rel="stylesheet" href="public/style.css"> <!-- Link to CSS -->
</head>
<body>
    <header>
        <h1>Manage Your Job Listings</h1>
        <nav>
            <a href="dashboard.php">Back to Dashboard</a>
            <a href="view_applications.php">View Applications</a>
            <a href="profile.php">Profile</a>
            <a href="logout.php">Logout</a>
        </nav>
    </header>

    <main>
        <table border="1">
            <tr>
                <th>Title</th>
                <th>Company</th>
                <th>Location</th>
                <th>Salary</th>
                <th>Description</th>
                <th>Actions</th>
            </tr>
            <?php while ($row = $result->fetch_assoc()): ?>
                <tr>
                    <td><?php echo htmlspecialchars($row['title']); ?></td>
                    <td><?php echo htmlspecialchars($row['company']); ?></td>
                    <td><?php echo htmlspecialchars($row['location']); ?></td>
                    <td><?php echo htmlspecialchars($row['salary']); ?></td>
                    <td><?php echo htmlspecialchars($row['description']); ?></td>
                    <td>
                        <a href="edit_job.php?id=<?php echo $row['id']; ?>">Edit</a> |
                        <a href="delete_job.php?id=<?php echo $row['id']; ?>" onclick="return confirm('Are you sure?')">Delete</a>
                    </td>
                </tr>
            <?php endwhile; ?>
        </table>
    </main>

    <footer>
        <p>&copy; <?php echo date("Y"); ?> Job Portal. All rights reserved.</p>
    </footer>
</body>
</html>
