<?php
session_start();
require_once('db/config.php');

// Redirect non-students
if (!isset($_SESSION['user']) || $_SESSION['user']['role'] !== 'student') {
    header("Location: login.php");
    exit();
}

$userId = $_SESSION['user']['id'];
$success = $error = "";

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $message = mysqli_real_escape_string($conn, $_POST['message']);

    if (!empty($message)) {
        $query = "INSERT INTO feedback (user_id, message) VALUES ($userId, '$message')";
        if (mysqli_query($conn, $query)) {
            $success = "✅ Feedback submitted successfully!";
        } else {
            $error = "❌ Error: " . mysqli_error($conn);
        }
    } else {
        $error = "❌ Please enter your feedback.";
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Feedback</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <style>
        body {
            margin: 0;
            font-family: Arial, sans-serif;
            background: #f4f4f4;
        }

        .container {
            max-width: 600px;
            margin: 60px auto;
            background: white;
            padding: 30px;
            border-radius: 10px;
            box-shadow: 0 0 10px rgba(0,0,0,0.1);
        }

        h2 {
            text-align: center;
            color: #007BFF;
        }

        textarea {
            width: 100%;
            padding: 12px;
            font-size: 16px;
            border: 1px solid #ccc;
            border-radius: 6px;
            resize: vertical;
            min-height: 120px;
            margin-top: 20px;
        }

        button {
            width: 100%;
            background-color: #007BFF;
            color: white;
            padding: 12px;
            font-size: 16px;
            margin-top: 20px;
            border: none;
            border-radius: 6px;
            cursor: pointer;
        }

        button:hover {
            background-color: #0056b3;
        }

        .message {
            text-align: center;
            font-weight: bold;
            margin-top: 20px;
        }

        .success {
            color: green;
        }

        .error {
            color: red;
        }

        a.back {
            display: block;
            margin-top: 25px;
            text-align: center;
            text-decoration: none;
            color: #007BFF;
        }

        @media (max-width: 600px) {
            .container {
                margin: 20px;
                padding: 20px;
            }

            textarea, button {
                font-size: 15px;
            }
        }
    </style>
</head>
<body>

    <div class="container">
        <h2>📝 Submit Your Feedback</h2>

        <?php if (!empty($success)): ?>
            <div class="message success"><?php echo $success; ?></div>
        <?php elseif (!empty($error)): ?>
            <div class="message error"><?php echo $error; ?></div>
        <?php endif; ?>

        <form method="POST">
            <textarea name="message" placeholder="Write your feedback..." required></textarea>
            <button type="submit">Submit Feedback</button>
        </form>

        <a href="dashboard.php" class="back">← Back to Dashboard</a>
    </div>

</body>
</html>
