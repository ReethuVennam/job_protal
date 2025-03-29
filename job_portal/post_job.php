<?php
session_start();
if (!isset($_SESSION['user_id']) || $_SESSION['user_role'] !== 'employer') {
    header("Location: login.php");
    exit();
}

include 'php/db.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $title = $_POST['title'];
    $company = $_POST['company'];
    $location = $_POST['location'];
    $salary = $_POST['salary'];
    $description = $_POST['description'];
    $employer_id = $_SESSION['user_id']; // Correct employer ID

    $sql = "INSERT INTO jobs (title, company, location, salary, description, employer_id) 
            VALUES (?, ?, ?, ?, ?, ?)";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("sssssi", $title, $company, $location, $salary, $description, $employer_id);

    if ($stmt->execute()) {
        echo "✅ Job posted successfully!";
    } else {
        echo "❌ Error: " . $stmt->error;
    }
}
?>
