<?php
session_start();
include 'php/db.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $email = $_POST['email'];
    $password = $_POST['password'];

    $sql = "SELECT * FROM users WHERE email = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("s", $email);
    $stmt->execute();
    $result = $stmt->get_result();
    $user = $result->fetch_assoc();

    if ($user && password_verify($password, $user['password'])) { // ✅ Proper verification
        $_SESSION['user_id'] = $user['id'];
        $_SESSION['user_name'] = $user['name'];
        $_SESSION['user_role'] = $user['role'];
        header("Location: dashboard.php");
        exit();
    } else {
        echo "Invalid email or password!";
 
    }
    if ($_SERVER["REQUEST_METHOD"] == "POST") {
        $email = trim($_POST['email']);
        $password = trim($_POST['password']);
        
        // Server-side validation
        if (empty($email) || empty($password)) {
            $error = "All fields are required.";
        }
    }
    
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
<script>
document.querySelector('form').addEventListener('submit', function(event) {
    const email = document.querySelector('input[name="email"]').value;
    const password = document.querySelector('input[name="password"]').value;

    if (!email || !password) {
        alert('Please fill in all fields.');
        event.preventDefault(); // Prevent form submission
    }
});
</script>

    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login - Job Portal</title>
    <link rel="stylesheet" href="public/style.css"> <!-- Link to CSS -->
<body>
    <header>
        <h1>Login to Your Account</h1>
    </header>
    
    <main>
        <form action="login.php" method="POST">
            <label for="email">Email:</label>
            <input type="email" name="email" required>
            
            <label for="password">Password:</label>
            <input type="password" name="password" required>
            
            <button type="submit">Login</button>
        </form>
        <p>Don't have an account? <a href="register.php">Register here</a></p>
    </main>
    
    <footer>
        <p>&copy; <?php echo date("Y"); ?> Job Portal. All rights reserved.</p>
    </footer>
</body>
</html>
