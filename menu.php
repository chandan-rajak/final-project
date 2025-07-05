<?php
session_start();
require_once('db/config.php');

if (!isset($_SESSION['user']) || $_SESSION['user']['role'] !== 'student') {
    header("Location: login.php");
    exit();
}

$menuResult = mysqli_query($conn, "
    SELECT * FROM menu 
    ORDER BY FIELD(day, 'Monday', 'Tuesday', 'Wednesday', 'Thursday', 'Friday', 'Saturday', 'Sunday')
");
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Weekly Menu</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <style>
        body {
            margin: 0;
            font-family: Arial, sans-serif;
            background: #f2f2f2;
        }

        .container {
            max-width: 960px;
            margin: 30px auto;
            background: white;
            padding: 25px;
            border-radius: 10px;
            box-shadow: 0 2px 10px rgba(0,0,0,0.1);
        }

        h2 {
            text-align: center;
            color: #007BFF;
            margin-bottom: 25px;
        }

        .table-responsive {
            overflow-x: auto;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            min-width: 600px;
        }

        th, td {
            padding: 12px;
            text-align: center;
            border: 1px solid #ccc;
        }

        th {
            background-color: #007BFF;
            color: white;
        }

        a.back {
            display: block;
            text-align: center;
            margin-top: 30px;
            color: #007BFF;
            text-decoration: none;
            font-weight: bold;
        }

        @media (max-width: 600px) {
            th, td {
                padding: 10px 8px;
                font-size: 14px;
            }

            .container {
                margin: 15px;
                padding: 20px;
            }
        }
    </style>
</head>
<body>

    <div class="container">
        <h2>🍽 Weekly Mess Menu</h2>

        <div class="table-responsive">
            <?php if (mysqli_num_rows($menuResult) > 0): ?>
                <table>
                    <thead>
                        <tr>
                            <th>Day</th>
                            <th>Breakfast</th>
                            <th>Lunch</th>
                            <th>Dinner</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php while ($row = mysqli_fetch_assoc($menuResult)): ?>
                        <tr>
                            <td><?php echo htmlspecialchars($row['day']); ?></td>
                            <td><?php echo htmlspecialchars($row['breakfast']); ?></td>
                            <td><?php echo htmlspecialchars($row['lunch']); ?></td>
                            <td><?php echo htmlspecialchars($row['dinner']); ?></td>
                        </tr>
                        <?php endwhile; ?>
                    </tbody>
                </table>
            <?php else: ?>
                <p style="text-align:center;">No menu available.</p>
            <?php endif; ?>
        </div>

        <a href="dashboard.php" class="back">← Back to Dashboard</a>
    </div>

</body>
</html>
