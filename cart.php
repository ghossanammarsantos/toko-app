<?php
session_start();

// Database connection configuration (contoh sederhana)
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "db_toko2";

$conn = new mysqli($servername, $username, $password, $dbname);

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

if (isset($_SESSION['username'])) {
    // Dapatkan ID customer dari database (contoh sederhana)
    $username = $_SESSION['username'];
    $customerQuery = "SELECT id FROM customers WHERE username = '$username'";
    $customerResult = $conn->query($customerQuery);
    if ($customerResult->num_rows == 1) {
        $customerRow = $customerResult->fetch_assoc();
        $customerId = $customerRow['id'];

        // Dapatkan data keranjang customer dari database
        $cartQuery = "SELECT products.id, products.nama_produk, products.harga
                      FROM products
                      INNER JOIN carts ON products.id = carts.product_id
                      WHERE carts.customer_id = '$customerId'";
        $cartResult = $conn->query($cartQuery);
    }
}

// Hitung total belanjaan
$totalBelanja = 0;
if (isset($cartResult) && $cartResult->num_rows > 0) {
    while ($row = $cartResult->fetch_assoc()) {
        $totalBelanja += $row['harga'];
    }

    // Berikan voucher jika total belanja mencapai 2 juta
    if ($totalBelanja >= 2000000) {
        // Pastikan customer belum pernah mendapatkan voucher sebelumnya
        $voucherQuery = "SELECT * FROM vouchers WHERE customer_id = '$customerId'";
        $voucherResult = $conn->query($voucherQuery);

        if ($voucherResult->num_rows == 0) {
            // Generate kode voucher unik
            $kodeVoucher = uniqid();
            // Hitung tanggal kedaluwarsa (3 bulan dari saat ini)
            $tanggalKedaluwarsa = date('Y-m-d', strtotime('+3 months'));

            // Simpan voucher ke database
            $insertVoucherQuery = "INSERT INTO vouchers (customer_id, kode_voucher, tanggal_kedaluwarsa)
                                  VALUES ('$customerId', '$kodeVoucher', '$tanggalKedaluwarsa')";
            $conn->query($insertVoucherQuery);
        }
    }
}

// Proses penghapusan produk dari keranjang
if (isset($_POST['hapus_produk'])) {
    $productId = $_POST['hapus_produk'];

    // Hapus produk dari keranjang berdasarkan product_id dan customer_id
    $hapusQuery = "DELETE FROM carts WHERE product_id = '$productId' AND customer_id = '$customerId'";
    $conn->query($hapusQuery);
}

$conn->close();
?>

<!DOCTYPE html>
<html>
<head>
    <title>Keranjang Belanja</title>
</head>
<body>
    <h1>Keranjang Belanja</h1>
    <?php
    if (isset($cartResult) && $cartResult->num_rows > 0) {
        echo "<ul>";
        while ($row = $cartResult->fetch_assoc()) {
            echo "<li>";
            echo "{$row['nama_produk']} - Rp. {$row['harga']}";

            echo "</li>";
        }
        echo "</ul>";

        // Tampilkan total belanja
        echo "<p>Total Belanja: Rp. $totalBelanja</p>";

        // Tombol "Checkout"
        echo "<form action='checkout.php' method='post'>";
        echo "  <input type='submit' name='checkout' value='Checkout'>";
        echo "</form>";
    } else {
        echo "Keranjang belanja Anda kosong.";
    }
    ?>
<br>
    <!-- Tombol untuk kembali ke home -->
    <button><a href="index.php">Kembali ke Home</a></button>
</body>
</html>
