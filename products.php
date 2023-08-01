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

$sql = "SELECT * FROM products";
$result = $conn->query($sql);

if ($result->num_rows > 0) {
    $products = array();
    while ($row = $result->fetch_assoc()) {
        $products[] = $row;
    }
}

if (isset($_SESSION['username'])) {
    // Dapatkan ID customer dari database (contoh sederhana)
    $username = $_SESSION['username'];
    $customerQuery = "SELECT id FROM customers WHERE username = '$username'";
    $customerResult = $conn->query($customerQuery);
    if ($customerResult->num_rows == 1) {
        $customerRow = $customerResult->fetch_assoc();
        $customerId = $customerRow['id'];
    }
}

// Proses tambah produk ke keranjang belanja
if (isset($_POST['add_to_cart']) && isset($_SESSION['username'])) {
    $productId = $_POST['product_id'];

    // Periksa apakah produk sudah ada di keranjang
    $cartQuery = "SELECT * FROM carts WHERE customer_id = '$customerId' AND product_id = '$productId'";
    $cartResult = $conn->query($cartQuery);

    if ($cartResult !== false) {
        if ($cartResult->num_rows == 0) {
            // Jika belum ada, tambahkan produk ke keranjang
            $addCartQuery = "INSERT INTO carts (customer_id, product_id) VALUES ('$customerId', '$productId')";
            if ($conn->query($addCartQuery)) {
                echo "<p>Produk berhasil ditambahkan ke keranjang.</p>";
            } else {
                echo "<p>Error saat menambahkan produk ke keranjang: " . $conn->error . "</p>";
            }
        } else {
            echo "<p>Produk sudah ada di keranjang.</p>";
        }
    } else {
        echo "<p>Error saat mengecek keranjang: " . $conn->error . "</p>";
    }
}

$conn->close();
?>

<!DOCTYPE html>
<html>
<head>
    <title>Daftar Produk</title>
</head>
<body>
    <h1>Daftar Produk</h1>
    <?php
    if (isset($products)) {
        echo "<ul>";
        foreach ($products as $product) {
            echo "<li>";
            echo "{$product['nama_produk']} - Rp. {$product['harga']}";

            // Tampilkan tombol Beli untuk setiap produk
            if (isset($_SESSION['username'])) {
                echo "<form method='post'>";
                echo "  <input type='hidden' name='product_id' value='{$product['id']}'>";
                echo "  <input type='submit' name='add_to_cart' value='Beli'>";
                echo "</form>";
            }
            echo "</li>";
        }
        echo "</ul>";
    } else {
        echo "Tidak ada produk yang tersedia.";
    }
    ?>
<br>
<button><a href="index.php">Home</a></button>
</body>
</html>
