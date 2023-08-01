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
    $email = $_POST['email'];

    $sql = "INSERT INTO customers (username, password, email, tanggal_registrasi) VALUES ('$username', '$password', '$email', NOW())";

    if ($conn->query($sql) === TRUE) {
        header('Location: login.php');
        exit();
    } else {
        echo "Error: " . $sql . "<br>" . $conn->error;
    }
}

$conn->close();
?>

<!DOCTYPE html>
<html>
<head>
    <title>Pendaftaran</title>
</head>
<body>
    <h1>Silakan daftar</h1>
    <form method="post">
        <label for="username">Username:</label>
        <input type="text" name="username" required><br>
        <label for="password">Password:</label>
        <input type="password" name="password" required><br>
        <label for="email">Email:</label>
        <input type="email" name="email" required><br>
        <input type="submit" name="submit" value="Daftar">
    </form>
</body>
</html>
