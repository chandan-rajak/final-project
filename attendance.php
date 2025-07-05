<?php
session_start();
require_once('db/config.php');

// ✅ Only students can access
if (!isset($_SESSION['user']) || $_SESSION['user']['role'] !== 'student') {
    header("Location: login.php");
    exit();
}

$userId = $_SESSION['user']['id'];
$today = date('Y-m-d');

// ✅ Save attendance if form submitted
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $breakfast = isset($_POST['breakfast']) ? 1 : 0;
    $lunch = isset($_POST['lunch']) ? 1 : 0;
    $dinner = isset($_POST['dinner']) ? 1 : 0;

    // Check if already marked
    $check = mysqli_query($conn, "SELECT * FROM attendance WHERE user_id = $userId AND date = '$today'");

    if (mysqli_num_rows($check) > 0) {
        // Update existing
        $query = "UPDATE attendance SET breakfast = $breakfast, lunch = $lunch, dinner = $dinner WHERE user_id = $userId AND date = '$today'";
    } else {
        // Insert new
        $query = "INSERT INTO attendance (user_id, date, breakfast, lunch, dinner) 
                  VALUES ($userId, '$today', $breakfast, $lunch, $dinner)";
    }

    if (mysqli_query($conn, $query)) {
        $success = "✅ Attendance submitted successfully.";
    } else {
        $error = "❌ Error: " . mysqli_error($conn);
    }
}

// ✅ Get today's record if exists
$todayData = mysqli_fetch_assoc(mysqli_query($conn, "SELECT * FROM attendance WHERE user_id = $userId AND date = '$today'"));
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Attendance - Mess Management</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
</head>
<body style="font-family: Arial, sans-serif; background: #f0f0f0;">

    <div style="max-width: 500px; margin: 60px auto; background: white; padding: 30px; border-radius: 10px; box-shadow: 0 0 10px rgba(0,0,0,0.1);">

        <h2 style="text-align:center; color: #28a745;">🍽 Mark Your Attendance</h2>
        <p style="text-align:center;">Date: <strong><?php echo $today; ?></strong></p>

        <?php if (isset($success)) echo "<p style='color: green; text-align:center;'>$success</p>"; ?>
        <?php if (isset($error)) echo "<p style='color: red; text-align:center;'>$error</p>"; ?>

        <form method="POST" style="margin-top: 20px;">
            <label><input type="checkbox" name="breakfast" <?php if ($todayData && $todayData['breakfast']) echo 'checked'; ?>> Breakfast</label><br><br>
            <label><input type="checkbox" name="lunch" <?php if ($todayData && $todayData['lunch']) echo 'checked'; ?>> Lunch</label><br><br>
            <label><input type="checkbox" name="dinner" <?php if ($todayData && $todayData['dinner']) echo 'checked'; ?>> Dinner</label><br><br>

            <button type="submit" style="width: 100%; background-color: #28a745; color: white; padding: 12px; border: none; font-size: 16px; border-radius: 5px;">
                Submit Attendance
            </button>
        </form>

        <a href="dashboard.php" style="display:block; text-align:center; margin-top:20px; color:#007BFF;">← Back to Dashboard</a>
    </div>

</body>
</html>
