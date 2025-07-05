<?php
session_start();
require_once('db/config.php');

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $email = $_POST['email'];
    $password = $_POST['password'];

    // Check credentials
    $query = "SELECT * FROM users WHERE email = '$email' AND password = '$password' AND role = 'admin'";
    $result = mysqli_query($conn, $query);

    if (mysqli_num_rows($result) === 1) {
        $user = mysqli_fetch_assoc($result);
        $_SESSION['user'] = $user;
        header("Location: admin.php");
        exit();
    } else {
        $error = "Invalid credentials or not an admin.";
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Admin Login</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
</head>
<body style="font-family: Arial, sans-serif; background: #f1f1f1;">

    <div style="max-width: 400px; margin: 60px auto; padding: 30px; background-color: #fff; border-radius: 8px; box-shadow: 0 8px 16px rgba(0,0,0,0.1);">
        <h2 style="text-align: center; color: #28a745;">Admin Login</h2>

        <?php if (isset($error)): ?>
            <p style="color: red; text-align: center;"><?php echo $error; ?></p>
        <?php endif; ?>

        <form method="POST">
            <input type="email" name="email" placeholder="Admin Email" required style="width: 100%; padding: 12px; margin-bottom: 10px;">
            <input type="password" name="password" placeholder="Password" required style="width: 100%; padding: 12px; margin-bottom: 20px;">
            <button type="submit" style="width: 100%; background-color: #28a745; color: white; padding: 12px; border: none; font-size: 16px;">Login</button>
        </form>

        <a href="index.html" style="display: block; margin-top: 15px; text-align: center; color: #007BFF;">← Back to Home</a>
    </div>

</body>
</html>
