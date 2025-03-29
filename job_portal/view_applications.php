<?php
session_start();
if (!isset($_SESSION['user_id']) || $_SESSION['user_role'] !== 'employer') {
    header("Location: login.php");
    exit();
}

include 'php/db.php';
$employer_id = $_SESSION['user_id'];

// Get applications for the employer’s jobs
$sql = "SELECT applications.id, users.name AS applicant_name, jobs.title AS job_title, applications.created_at 
        FROM applications 
        JOIN jobs ON applications.job_id = jobs.id 
        JOIN users ON applications.user_id = users.id
        WHERE jobs.employer_id = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $employer_id);
$stmt->execute();
$result = $stmt->get_result();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Job Applications - Job Portal</title>
    <link rel="stylesheet" href="public/style.css"> <!-- Link to CSS -->
</head>
<body>
    <header>
        <h1>Job Applications</h1>
        <nav>
            <a href="dashboard.php">Back to Dashboard</a>
            <a href="manage_jobs.php">Manage Jobs</a>
            <a href="profile.php">Profile</a>
            <a href="logout.php">Logout</a>
        </nav>
    </header>

    <main>
        <table border="1">
            <tr>
                <th>Applicant Name</th>
                <th>Job Title</th>
                <th>Applied On</th>
            </tr>
            <?php while ($row = $result->fetch_assoc()): ?>
                <tr>
                    <td><?php echo htmlspecialchars($row['applicant_name']); ?></td>
                    <td><?php echo htmlspecialchars($row['job_title']); ?></td>
                    <td><?php echo htmlspecialchars($row['created_at']); ?></td>
                </tr>
            <?php endwhile; ?>
        </table>
    </main>

    <footer>
        <p>&copy; <?php echo date("Y"); ?> Job Portal. All rights reserved.</p>
    </footer>
</body>
</html>
