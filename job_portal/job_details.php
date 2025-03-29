
<?php
session_start();
include 'php/db.php';
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}



$job_id = $_GET['id'];

$sql = "SELECT * FROM jobs WHERE id = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $job_id);
$stmt->execute();
$result = $stmt->get_result();
$job = $result->fetch_assoc();

if (!$job) {
    echo "Job not found!";
    exit();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <title><?php echo htmlspecialchars($job['title']); ?></title>
</head>
<body>
    <h2><?php echo htmlspecialchars($job['title']); ?></h2>
    <p><strong>Company:</strong> <?php echo htmlspecialchars($job['company']); ?></p>
    <p><strong>Location:</strong> <?php echo htmlspecialchars($job['location']); ?></p>
    <p><strong>Salary:</strong> <?php echo htmlspecialchars($job['salary']); ?></p>
    <p><strong>Description:</strong></p>
    <p><?php echo nl2br(htmlspecialchars($job['description'])); ?></p>
    <a href="dashboard.php">Back to Dashboard</a>
</body>
</html>
