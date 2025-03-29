<?php
// Start session only if not already started
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// Redirect to login if not logged in
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

include 'php/db.php';

$user_id = $_SESSION['user_id'];
$user_name = $_SESSION['user_name'];
$user_role = $_SESSION['user_role'];

// Fetch all job listings
$sql = "SELECT * FROM jobs ORDER BY created_at DESC";
$result = $conn->query($sql);
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard - Job Portal</title>
    <link rel="stylesheet" href="public/style.css"> <!-- Link to CSS -->
</head>
<body>
    <header>
        <h1>Welcome, <?php echo htmlspecialchars($user_name); ?>!</h1>
        <p>Your role: <strong><?php echo htmlspecialchars($user_role); ?></strong></p>
        <nav>
            <?php if ($user_role == 'employer'): ?>
                <a href="manage_jobs.php">Manage Jobs</a>
            <?php endif; ?>
            <a href="view_applications.php">My Applications</a>
            <a href="profile.php">Profile</a>
            <a href="logout.php">Logout</a>
        </nav>
    </header>

    <main>
    <h3>Search Job Listings</h3>
<form method="GET" action="" style="margin-bottom: 30px;">
    <input type="text" name="search" placeholder="Search by title or company">
    <button type="submit">Search</button>
</form>

<h3>Available Job Listings</h3>

      
        <table border="1">
            <tr>
                <th>Title</th>
                <th>Company</th>
                <th>Location</th>
                <th>Salary</th>
                <th>Description</th>
                <?php if ($user_role == 'job_seeker'): ?>
                    <th>Action</th>
                <?php endif; ?>
            </tr>
            <?php
            $search_query = isset($_GET['search']) ? trim($_GET['search']) : '';
            $sql = "SELECT * FROM jobs WHERE title LIKE ? OR company LIKE ? ORDER BY created_at DESC";
            $stmt = $conn->prepare($sql);
            $like_search = '%' . $search_query . '%';
            $stmt->bind_param("ss", $like_search, $like_search);
            $stmt->execute();
            $result = $stmt->get_result();
            while ($row = $result->fetch_assoc()): ?>
                <tr>
                    <td><?php echo htmlspecialchars($row['title']); ?></td>
                    <td><?php echo htmlspecialchars($row['company']); ?></td>
                    <td><?php echo htmlspecialchars($row['location']); ?></td>
                    <td><?php echo htmlspecialchars($row['salary']); ?></td>
                    <td><?php echo htmlspecialchars($row['description']); ?></td>
                    <?php if ($user_role == 'job_seeker'): ?>
                        <td>
                            <form method="POST" action="apply.php">
                                <input type="hidden" name="job_id" value="<?php echo htmlspecialchars($row['id']); ?>">
                                <button type="submit">Apply</button>
                            </form>
                        </td>
                    <?php endif; ?>
                </tr>
            <?php endwhile; ?>
        </table>
    </main>

    <footer>
        <p>&copy; <?php echo date("Y"); ?> Job Portal. All rights reserved.</p>
    </footer>
</body>
</html>
