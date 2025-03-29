<?php
session_start();
if (!isset($_SESSION['user_id']) || $_SESSION['user_role'] !== 'employer') {
    header("Location: login.php");
    exit();
}

include 'php/db.php';

if (isset($_GET['id'])) {
    $job_id = $_GET['id'];

    // Delete the job
    $sql = "DELETE FROM jobs WHERE id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $job_id);

    if ($stmt->execute()) {
        $_SESSION['success'] = "Job deleted successfully.";
    } else {
        $_SESSION['error'] = "Error deleting job.";
    }

    $stmt->close();
    $conn->close();
}

header("Location: manage_jobs.php");
exit();
?>
