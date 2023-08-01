<?php
session_start();

if (isset($_SESSION['username'])) {
    header('Location: index.php');
    exit();
}

// Database connection configuration (contoh sederhana)
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "db_toko2";

$conn = new mysqli($servername, $username, $password, $dbname);

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

if (isset($_POST['submit'])) {
    $username = $_POST['username'];
    $password = $_POST['password'];

    $sql = "SELECT * FROM customers WHERE username = '$username' AND password = '$password'";
    $result = $conn->query($sql);

    if ($result->num_rows == 1) {
        $_SESSION['username'] = $username;
        header('Location: index.php');
        exit();
    } else {
        echo "Username atau password salah.";
    }
}

$conn->close();
?>

<!DOCTYPE html>
<html>
<head>
    <title>Login</title>
</head>
<body>
    <h1>Silakan login</h1>
    <form method="post">
        <label for="username">Username:</label>
        <input type="text" name="username" required><br>
        <label for="password">Password:</label>
        <input type="password" name="password" required><br>
        <input type="submit" name="submit" value="Login">
    </form>

<br>
<p>belum punya akun???</p>
<button><a href="register.php">Daftar Akun</a></button>

</body>

</html>
