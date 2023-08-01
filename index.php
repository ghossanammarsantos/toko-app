<?php
session_start();
?>

<!DOCTYPE html>
<html>
<head>
    <title>Toko Diskon</title>
</head>
<body>
    <h1>Selamat Datang di Toko Diskon!</h1>
    <?php
    if (isset($_SESSION['username'])) {
        echo "<p>Halo, {$_SESSION['username']}! Selamat berbelanja.</p>";
    } else {
        echo "<p>Silakan <a href='login.php'>login</a> atau <a href='register.php'>daftar</a> untuk berbelanja.</p>";
    }

    // Proses logout
    if (isset($_POST['logout'])) {
        // Hapus semua data sesi
        session_unset();
        session_destroy();

        // Arahkan kembali ke halaman login atau halaman lain sesuai kebutuhan
        header("Location: login.php");
        exit; // Pastikan untuk melakukan exit setelah redirect
    }
    ?>
    <p>Fitur-fitur:</p>
    <ul>
        <li><a href="products.php">Lihat Produk</a></li>
        <li><a href="cart.php">Keranjang Belanja</a></li>
        <li><a href="checkout.php">Checkout</a></li>
    </ul>

    <form method="post">
        <input type="submit" name="logout" value="Logout">
    </form>
</body>
</html>
