<?php
session_start();
if (!isset($_SESSION['user_id']) || $_SESSION['user_role'] !== 'employer') {
    header("Location: login.php");
    exit();
}

include 'php/db.php';

if (!isset($_GET['id'])) {
    die("❌ Invalid job ID.");
}

$job_id = $_GET['id'];
$employer_id = $_SESSION['user_id'];

// Fetch the job details
$sql = "SELECT * FROM jobs WHERE id = ? AND employer_id = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("ii", $job_id, $employer_id);
$stmt->execute();
$result = $stmt->get_result();
$job = $result->fetch_assoc();

if (!$job) {
    die("❌ Job not found or you don't have permission to edit it.");
}

// Update job logic
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $title = $_POST['title'];
    $company = $_POST['company'];
    $location = $_POST['location'];
    $salary = $_POST['salary'];
    $description = $_POST['description'];

    $update_sql = "UPDATE jobs SET title=?, company=?, location=?, salary=?, description=? WHERE id=? AND employer_id=?";
    $update_stmt = $conn->prepare($update_sql);
    $update_stmt->bind_param("ssssssi", $title, $company, $location, $salary, $description, $job_id, $employer_id);

    if ($update_stmt->execute()) {
        echo "✅ Job updated successfully!";
    } else {
        echo "❌ Error updating job: " . $update_stmt->error;
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Job - Job Portal</title>
    <link rel="stylesheet" href="public/style.css"> <!-- Link to CSS -->
</head>
<body>
    <header>
        <h1>Edit Job</h1>
        <nav>
            <a href="manage_jobs.php">Manage Jobs</a>
            <a href="view_applications.php">View Applications</a>
            <a href="profile.php">Profile</a>
            <a href="logout.php">Logout</a>
        </nav>
    </header>

    <main>
        <form method="POST">
            <label for="title">Title:</label>
            <input type="text" name="title" value="<?php echo htmlspecialchars($job['title']); ?>" required>

            <label for="company">Company:</label>
            <input type="text" name="company" value="<?php echo htmlspecialchars($job['company']); ?>" required>

            <label for="location">Location:</label>
            <input type="text" name="location" value="<?php echo htmlspecialchars($job['location']); ?>" required>

            <label for="salary">Salary:</label>
            <input type="text" name="salary" value="<?php echo htmlspecialchars($job['salary']); ?>">

            <label for="description">Description:</label>
            <textarea name="description" required><?php echo htmlspecialchars($job['description']); ?></textarea>

            <button type="submit">Update Job</button>
        </form>
        <p><a href="manage_jobs.php">Back to Manage Jobs</a></p>
    </main>

    <footer>
        <p>&copy; <?php echo date("Y"); ?> Job Portal. All rights reserved.</p>
    </footer>
</body>
</html>
