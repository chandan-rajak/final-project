<?php
session_start();
require_once('db/config.php');

// Redirect if not student
if (!isset($_SESSION['user']) || $_SESSION['user']['role'] !== 'student') {
    header("Location: login.php");
    exit();
}

$user = $_SESSION['user'];
$today = date('l');

// Fetch today's menu
$query = "SELECT * FROM menu WHERE day = '$today'";
$result = mysqli_query($conn, $query);
$menu = mysqli_fetch_assoc($result);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Student Dashboard</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <style>
        body {
            margin: 0;
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            background-color: #f4f4f4;
        }

        header {
            background-color: #007BFF;
            color: white;
            padding: 20px;
            text-align: center;
        }

        main {
            max-width: 900px;
            margin: 30px auto;
            background: white;
            padding: 30px;
            border-radius: 10px;
            box-shadow: 0 0 10px rgba(0,0,0,0.1);
        }

        h2 {
            color: #007BFF;
            margin-bottom: 20px;
        }

        ul {
            line-height: 2;
            font-size: 16px;
        }

        .buttons {
            display: flex;
            flex-wrap: wrap;
            gap: 15px;
            justify-content: center;
            margin-top: 30px;
        }

        .buttons a {
            padding: 12px 24px;
            background-color: #007BFF;
            color: white;
            text-decoration: none;
            border-radius: 6px;
            font-size: 16px;
            transition: background 0.3s ease;
        }

        .buttons a:hover {
            background-color: #0056b3;
        }

        .logout {
            background-color: #dc3545 !important;
        }

        footer {
            text-align: center;
            color: #777;
            padding: 20px;
            margin-top: 30px;
        }

        @media (max-width: 600px) {
            ul {
                font-size: 15px;
            }

            .buttons a {
                width: 100%;
                text-align: center;
                font-size: 15px;
            }
        }
    </style>
</head>
<body>

    <header>
        <h1>Welcome, <?php echo htmlspecialchars($user['name']); ?> 👋</h1>
        <p>Today is <strong><?php echo $today; ?></strong></p>
    </header>

    <main>
        <h2>🍽 Today's Menu</h2>
        <?php if ($menu): ?>
            <ul>
                <li><strong>Breakfast:</strong> <?php echo $menu['breakfast']; ?></li>
                <li><strong>Lunch:</strong> <?php echo $menu['lunch']; ?></li>
                <li><strong>Dinner:</strong> <?php echo $menu['dinner']; ?></li>
            </ul>
        <?php else: ?>
            <p>No menu available for today.</p>
        <?php endif; ?>

        <div class="buttons">
            <a href="feedback.php">📝 Give Feedback</a>
            <a href="attendance.php">✅ Mark Attendance</a>
            <a href="menu.php">📋 View Full Menu</a>
            <a href="logout.php" class="logout">🚪 Logout</a>
        </div>
    </main>

    <footer>
        &copy; <?php echo date('Y'); ?> Mess Management System – Student Dashboard
    </footer>

</body>
</html>
