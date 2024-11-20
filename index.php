<?php
session_start();
require 'conn.php'; // Include the database connection

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['login'])) {
    $email = $_POST['email'];
    $password = $_POST['password']; // Store the password as plain text
    $role = $_POST['role'];

    // Prepare and execute SQL query to fetch user details based on role
    $stmt = $conn->prepare("SELECT id, password FROM users WHERE email = ? AND role = ?");
    $stmt->bind_param("ss", $email, $role);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows === 1) {
        $user = $result->fetch_assoc();
        // Directly compare the plain text password
        if ($password === $user['password']) {
            // Set session variables for the logged-in user
            $_SESSION['user_id'] = $user['id'];
            $_SESSION['role'] = $role;
            // Redirect to the respective dashboard
            header("Location: " . ($role === 'admin' ? 'admin_dashboard.php' : 'user_dashboard.php'));
            exit;
        } else {
            $error = "Invalid password!";
        }
    } else {
        $error = "User  with the specified role not found!";
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login</title>
    <link rel="stylesheet" href="styles.css">
</head>
<body>
    <div class="container">
        <h1>Login</h1>
        <form method="POST">
            <div class="form-group">
            <input type="email" name="email" placeholder="Email" required>
            <input type="password" name="password" placeholder="Password" required>
            <select name="role" required>
                <option value="">Select Role</option>
                <option value="user">User</option>
                <option value="admin">Admin</option>
            </select>
            <button type="submit" name="login">Login</button>
            </div>
        </form>
        <?php if (!empty($error)) echo "<p style='color: red;'>$error</p>"; ?>
        <p>Don't have an account? <a href="register.php">Register here</a></p>
    </div>
</body>
</html>
