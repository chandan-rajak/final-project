<?php
session_start();
require_once('db/config.php');

// Ensure only admin can access
if (!isset($_SESSION['user']) || $_SESSION['user']['role'] !== 'admin') {
    header("Location: login.php");
    exit();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Admin Dashboard - Mess Management System</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

 <style>
    body {
        font-family: Arial, sans-serif;
        margin: 0;
        background: #f0f2f5;
    }

    header {
        background: #343a40;
        color: white;
        padding: 20px;
        text-align: center;
    }

    nav {
        display: flex;
        flex-wrap: wrap;
        justify-content: center;
        gap: 15px;
        padding: 15px;
        background: white;
    }

    nav a {
        padding: 10px 15px;
        background: #007bff;
        color: white;
        border-radius: 5px;
        text-decoration: none;
        font-size: 16px;
    }

    nav a.logout {
        background: #dc3545;
    }

    main {
        max-width: 1200px;
        margin: 20px auto;
        background: white;
        padding: 25px;
        border-radius: 10px;
        box-shadow: 0 0 10px rgba(0,0,0,0.1);
        overflow-x: auto;
    }

    h2 {
        margin-top: 40px;
        color: #007bff;
    }

    table {
        width: 100%;
        border-collapse: collapse;
        margin-top: 15px;
        min-width: 600px;
    }

    th, td {
        padding: 10px;
        text-align: center;
        border: 1px solid #ccc;
    }

    th {
        background-color: #007BFF;
        color: white;
    }

    td input[type="text"] {
        width: 90%;
        padding: 5px;
    }

    button {
        padding: 8px 15px;
        background: #28a745;
        color: white;
        border: none;
        border-radius: 5px;
        cursor: pointer;
    }

    button:hover {
        background: #218838;
    }

    footer {
        text-align: center;
        padding: 20px;
        color: #888;
    }

    @media (max-width: 768px) {
        nav {
            flex-direction: column;
            align-items: center;
        }

        table {
            font-size: 14px;
        }

        button {
            font-size: 14px;
        }
    }

    @media (max-width: 480px) {
        main {
            padding: 15px;
        }

        nav a {
            width: 100%;
            text-align: center;
        }
    }
 </style>

</head>
<body style="font-family: Arial, sans-serif; background: #f0f2f5; margin: 0;">

    <header style="background-color: #343a40; color: white; padding: 20px; text-align: center;">
        <h1>Admin Dashboard</h1>
        <p>Welcome, <?php echo htmlspecialchars($_SESSION['user']['name']); ?></p>
    </header>

    <nav style="display: flex; justify-content: center; gap: 20px; padding: 15px; background: #ffffff;">
        <a href="admin.php" style="padding: 10px 15px; background: #007bff; color: white; border-radius: 5px; text-decoration: none;">🏠 Dashboard</a>
        <a href="logout.php" style="padding: 10px 15px; background: #dc3545; color: white; border-radius: 5px; text-decoration: none;">🚪 Logout</a>
    </nav>

    <main style="max-width: 1000px; margin: 30px auto; background: white; padding: 25px; border-radius: 10px; box-shadow: 0 0 10px rgba(0,0,0,0.1);">

        <h2>📋 Weekly Menu Management</h2>

        <?php
        // Handle form submission for update
        if ($_SERVER["REQUEST_METHOD"] === "POST" && isset($_POST['update_menu'])) {
            $id = $_POST['id'];
            $breakfast = $_POST['breakfast'];
            $lunch = $_POST['lunch'];
            $dinner = $_POST['dinner'];

            $updateQuery = "UPDATE menu SET 
                breakfast = '$breakfast', 
                lunch = '$lunch', 
                dinner = '$dinner' 
                WHERE id = $id";

            if (mysqli_query($conn, $updateQuery)) {
                echo "<p style='color: green;'>✅ Menu updated successfully for ID $id.</p>";
            } else {
                echo "<p style='color: red;'>❌ Error: " . mysqli_error($conn) . "</p>";
            }
        }

        // Fetch menu records
        $result = mysqli_query($conn, "SELECT * FROM menu");
        ?>

        <table border="1" cellpadding="10" cellspacing="0" style="width:100%; margin-top:20px;">
            <tr style="background:#007BFF; color:white;">
                <th>Day</th>
                <th>Breakfast</th>
                <th>Lunch</th>
                <th>Dinner</th>
                <th>Action</th>
            </tr>

            <?php while ($row = mysqli_fetch_assoc($result)) : ?>
                <tr>
                    <form method="POST" action="">
                        <input type="hidden" name="id" value="<?php echo $row['id']; ?>">
                        <td><?php echo $row['day']; ?></td>
                        <td><input type="text" name="breakfast" value="<?php echo $row['breakfast']; ?>"></td>
                        <td><input type="text" name="lunch" value="<?php echo $row['lunch']; ?>"></td>
                        <td><input type="text" name="dinner" value="<?php echo $row['dinner']; ?>"></td>
                        <td><button type="submit" name="update_menu">Update</button></td>
                    </form>
                </tr>
            <?php endwhile; ?>
        </table>
        <hr style="margin: 40px 0;">
<h2>🗣 Student Feedback</h2>

<?php
// Get all feedback with user names
$query = "
    SELECT f.message, f.created_at, u.name 
    FROM feedback f 
    JOIN users u ON f.user_id = u.id 
    ORDER BY f.created_at DESC
";
$feedbackResult = mysqli_query($conn, $query);

if (!$feedbackResult) {
    echo "<p style='color: red;'>❌ Error fetching feedback: " . mysqli_error($conn) . "</p>";
} elseif (mysqli_num_rows($feedbackResult) > 0) {
    echo '<table border="1" cellpadding="10" cellspacing="0" style="width:100%; margin-top:20px;">
            <tr style="background:#343a40; color:white;">
                <th>Student Name</th>
                <th>Message</th>
                <th>Submitted At</th>
            </tr>';
    while ($row = mysqli_fetch_assoc($feedbackResult)) {
        echo "<tr>
                <td>" . htmlspecialchars($row['name']) . "</td>
                <td>" . nl2br(htmlspecialchars($row['message'])) . "</td>
                <td>" . date('d M Y, h:i A', strtotime($row['created_at'])) . "</td>
              </tr>";
    }
    echo "</table>";
} else {
    echo "<p>No feedback submitted yet.</p>";
}


if (mysqli_num_rows($feedbackResult) > 0):
?>
    <table border="1" cellpadding="10" cellspacing="0" style="width:100%; margin-top:20px;">
        <tr style="background:#343a40; color:white;">
            <th>Student Name</th>
            <th>Message</th>
            <th>Submitted At</th>
        </tr>
        <?php while ($row = mysqli_fetch_assoc($feedbackResult)): ?>
            <tr>
                <td><?php echo htmlspecialchars($row['name']); ?></td>
                <td><?php echo nl2br(htmlspecialchars($row['message'])); ?></td>
                <td><?php echo date('d M Y, h:i A', strtotime($row['created_at'])); ?></td>
            </tr>
        <?php endwhile; ?>
    </table>
    <?php else: ?>
        <p>No feedback submitted yet.</p>
    <?php endif; ?>
    <hr style="margin: 40px 0;">
<h2>📊 Student Attendance Records</h2>

<?php
$attendanceQuery = "
    SELECT a.date, a.breakfast, a.lunch, a.dinner, u.name
    FROM attendance a
    JOIN users u ON a.user_id = u.id
    ORDER BY a.date DESC, u.name
";
$attendanceResult = mysqli_query($conn, $attendanceQuery);

if (!$attendanceResult) {
    echo "<p style='color:red;'>❌ Error loading attendance: " . mysqli_error($conn) . "</p>";
} elseif (mysqli_num_rows($attendanceResult) > 0) {
    echo '<table border="1" cellpadding="10" cellspacing="0" style="width:100%; margin-top:20px;">
            <tr style="background:#343a40; color:white;">
                <th>Date</th>
                <th>Student Name</th>
                <th>Breakfast</th>
                <th>Lunch</th>
                <th>Dinner</th>
            </tr>';
    while ($row = mysqli_fetch_assoc($attendanceResult)) {
        echo "<tr>
                <td>" . htmlspecialchars($row['date']) . "</td>
                <td>" . htmlspecialchars($row['name']) . "</td>
                <td>" . ($row['breakfast'] ? '✅' : '❌') . "</td>
                <td>" . ($row['lunch'] ? '✅' : '❌') . "</td>
                <td>" . ($row['dinner'] ? '✅' : '❌') . "</td>
              </tr>";
    }
    echo "</table>";
} else {
    echo "<p>No attendance records found.</p>";
}
?>


    </main>

    <footer style="text-align: center; padding: 20px; color: #888;">
        &copy; <?php echo date("Y"); ?> Mess Management System – Admin Panel
    </footer>

</body>
</html>
