<?php
session_start();
if ($_SESSION['role'] !== 'user') {
    header("Location: index.php");
    exit;
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>User Dashboard</title>
</head>
<body>
    <h1>Welcome to the User Dashboard!</h1>
    <a href="logut.php">Logout</a>
</body>
</html>
