<?php
session_start();
require_once('db/config.php');

$error = "";

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $email = $_POST['email'];
    $password = $_POST['password'];

    // Query the users table
    $query = "SELECT * FROM users WHERE email = '$email' AND password = '$password'";
    $result = mysqli_query($conn, $query);

    if (mysqli_num_rows($result) === 1) {
        $row = mysqli_fetch_assoc($result);

        // ✅ Store user info in session
        $_SESSION['user'] = [
            'id' => $row['id'],
            'name' => $row['name'],
            'role' => $row['role']
        ];

        // ✅ Redirect based on role
        if ($row['role'] === 'admin') {
            header("Location: admin.php");
        } else {
            header("Location: dashboard.php");
        }
        exit();
    } else {
        $error = "❌ Invalid email or password.";
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Login - Mess Management System</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
</head>
<body style="font-family: Arial, sans-serif; background: #f4f4f4;">

    <div style="max-width: 400px; margin: 60px auto; padding: 30px; background-color: #fff; border-radius: 8px; box-shadow: 0 8px 16px rgba(0,0,0,0.1);">
        <h2 style="text-align: center; color: #007BFF;">User Login</h2>

        <?php if (!empty($error)): ?>
            <p style="color: red; text-align: center;"><?php echo $error; ?></p>
        <?php endif; ?>

        <form method="POST">
            <input type="email" name="email" placeholder="Email Address" required style="width: 100%; padding: 12px; margin-bottom: 10px;">
            <input type="password" name="password" placeholder="Password" required style="width: 100%; padding: 12px; margin-bottom: 20px;">
            <button type="submit" style="width: 100%; background-color: #007BFF; color: white; padding: 12px; border: none; font-size: 16px;">Login</button>
        </form>

        <a href="index.html" style="display: block; margin-top: 15px; text-align: center; color: #007BFF;">← Back to Home</a>
    </div>

</body>
</html>
