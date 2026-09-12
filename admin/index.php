<?php
session_start();
include '../koneksi.php';

// Cek session login & role admin
if (!isset($_SESSION['login']) || $_SESSION['role'] !== 'admin') {
    header("Location: ../login.php");
    exit();
}

// Ambil data statistik dinamis dari database
$query_users = mysqli_query($koneksi, "SELECT COUNT(*) as total FROM users WHERE role = 'user'");
$total_users = mysqli_fetch_assoc($query_users)['total'];

$query_produk = mysqli_query($koneksi, "SELECT COUNT(*) as total FROM produk");
$total_produk = $query_produk ? mysqli_fetch_assoc($query_produk)['total'] : 0;
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

    <div class="admin-container">
        <!-- Sidebar Navigation -->
        <div class="sidebar">
        <h2>🌸 Admin Bloom</h2>
        <a href="index.php" class="active">🎀 Dashboard</a>
        <a href="produk.php">🌷 Kelola Produk</a>
        <a href="pesanan.php">📦 Kelola Pesanan</a>
        <a href="pelanggan.php">👥 Data Pelanggan</a>
        <a href="../logout.php" class="logout">🚪 Logout</a>
    </div>

        <!-- Main Content Area -->
        <main class="admin-content">
            <!-- Welcome Banner -->
            <div class="welcome-banner">
                <div class="banner-text">
                    <h1>Selamat Datang, <?php echo htmlspecialchars($_SESSION['nama'] ?? 'Admin Toko'); ?>! 👋✨</h1>
                    <p>Halaman pusat kendali toko bunga Bloom & Co. Yuk cek performa toko hari ini!</p>
                </div>
                <div class="banner-decoration">💐</div>
            </div>

            <!-- Stats Cards -->
            <div class="stats-grid">
                <div class="stat-card card-pink">
                    <div class="stat-icon">👥</div>
                    <div class="stat-info">
                        <h3>Total Pelanggan</h3>
                        <p class="stat-number"><?php echo $total_users; ?></p>
                    </div>
                </div>

                <div class="stat-card card-green">
                    <div class="stat-icon">🌷</div>
                    <div class="stat-info">
                        <h3>Total Produk</h3>
                        <p class="stat-number"><?php echo $total_produk; ?></p>
                    </div>
                </div>

                <div class="stat-card card-yellow">
                    <div class="stat-icon">📑</div>
                    <div class="stat-info">
                        <h3>Pesanan Baru</h3>
                        <p class="stat-number">0</p>
                    </div>
                </div>
            </div>

            <!-- Quick Action Area -->
            <div class="quick-actions-card">
                <h2>Aksi Cepat ⚡</h2>
                <p>Pilih menu di bawah ini untuk langsung mengelola toko kamu:</p>
                <div class="action-buttons">
                    <a href="produk.php" class="btn-action btn-pastel-pink">
                        ➕ Tambah Produk Bunga
                    </a>
                    <a href="pesanan.php" class="btn-action btn-pastel-green">
                        📦 Cek Pesanan Masuk
                    </a>
                    <a href="../index.php" target="_blank" class="btn-action btn-pastel-yellow">
                        🌐 Lihat Tampilan Toko
                    </a>
                </div>
            </div>
        </main>
    </div>

</body>
</html>