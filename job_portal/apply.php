<?php
session_start();
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

include 'php/db.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    if (!isset($_POST['job_id']) || empty($_POST['job_id'])) {
        die("Error: Job ID is missing!");
    }

    $job_id = intval($_POST['job_id']);
    $user_id = $_SESSION['user_id'];

    // Check if user has already applied for this job
    $stmt = $conn->prepare("SELECT * FROM applications WHERE user_id = ? AND job_id = ?");
    $stmt->bind_param("ii", $user_id, $job_id);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        die("You have already applied for this job!");
    }

    // Insert application into database
    $stmt = $conn->prepare("INSERT INTO applications (user_id, job_id, created_at) VALUES (?, ?, NOW())");
    $stmt->bind_param("ii", $user_id, $job_id);
    
    if ($stmt->execute()) {
        echo "Application submitted successfully!";
        header("Location: dashboard.php");
        exit();
    } else {
        die("Error: Could not apply for job.");
    }
}
?>
