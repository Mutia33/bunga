<?php
session_start();
include '../koneksi.php';

// Proteksi Halaman: Hanya Role Admin yang Bisa Masuk
if (!isset($_SESSION['login']) || $_SESSION['role'] !== 'admin') {
    header("Location: ../login.php");
    exit();
}

// Mengambil Data Ringkasan untuk Dashboard
$query_user = mysqli_query($koneksi, "SELECT COUNT(*) AS total_user FROM users WHERE role = 'user'");
$data_user  = mysqli_fetch_assoc($query_user);

// Kamu bisa tambah query lain di sini nanti (misal: total produk atau pesanan)
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard Admin - Bloom & Co.</title>
    <link rel="stylesheet" href="../style.css">
</head>
<body class="admin-body">

    <!-- Sidebar Admin -->
    <div class="sidebar">
        <h2>🌸 Admin Bloom</h2>
        <ul>
            <li><a href="index.php" class="active">Dashboard</a></li>
            <li><a href="produk.php">Kelola Produk</a></li>
            <li><a href="pesanan.php">Kelola Pesanan</a></li>
            <li><a href="users.php">Data Pelanggan</a></li>
            <li><a href="../logout.php" class="btn-logout">Logout</a></li>
        </ul>
    </div>

    <!-- Konten Utama Dashboard -->
    <div class="main-content">
        <header>
            <h1>Selamat Datang, <?php echo htmlspecialchars($_SESSION['nama']); ?>! 👋</h1>
            <p>Halaman pusat kendali toko bunga Bloom & Co.</p>
        </header>

        <!-- Kartu Ringkasan (Statistik) -->
        <div class="card-container">
            <div class="card">
                <h3>Total Pelanggan</h3>
                <p class="card-count"><?php echo $data_user['total_user']; ?></p>
            </div>
            <div class="card">
                <h3>Total Produk</h3>
                <p class="card-count">0</p>
            </div>
            <div class="card">
                <h3>Pesanan Baru</h3>
                <p class="card-count">0</p>
            </div>
        </div>

        <div class="quick-info">
            <h3>Aksi Cepat</h3>
            <p>Pilih menu di sebelah kiri untuk mulai mengelola produk atau melihat pesanan masuk.</p>
        </div>
    </div>

</body>
</html>