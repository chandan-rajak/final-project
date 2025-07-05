<?php
$conn = mysqli_connect('localhost', 'root', '', 'mess_management');
if (!$conn) {
    die("Connection failed: " . mysqli_connect_error());
}
?>