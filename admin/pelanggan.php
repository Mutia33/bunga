<?php
session_start();
include '../koneksi.php';

// Cek Login Admin
if (!isset($_SESSION['login']) || $_SESSION['role'] !== 'admin') {
    header("Location: ../login.php");
    exit();
}

// Proses Hapus Pelanggan
if (isset($_GET['hapus'])) {
    $id = $_GET['hapus'];
    // Hanya hapus jika rolenya 'user' (bukan admin)
    mysqli_query($koneksi, "DELETE FROM users WHERE id_user = '$id' AND role != 'admin'");
    header("Location: pelanggan.php");
    exit();
}

// Ambil Data Pelanggan (hanya yang role-nya 'user')
// Mengambil seluruh data dari tabel users secara berurutan dari yang terbaru
// Ambil Data Pelanggan (hanya mengecualikan admin / menampilkan yang rolenya 'user')
$query_pelanggan = mysqli_query($koneksi, "SELECT * FROM users WHERE role != 'admin' ORDER BY id_user DESC");
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Data Pelanggan - Admin Bloom</title>
    <link rel="stylesheet" href="../style.css">
</head>
<body class="admin-body">

    <!-- SIDEBAR KIRI -->
    <div class="sidebar">
        <h2>🌸 Admin Bloom</h2>
        <a href="index.php">🎀 Dashboard</a>
        <a href="produk.php">🌷 Kelola Produk</a>
        <a href="pesanan.php">📦 Kelola Pesanan</a>
        <a href="pelanggan.php" class="active">👥 Data Pelanggan</a>
        <a href="../logout.php" class="logout">🚪 Logout</a>
    </div>

    <!-- KONTEN UTAMA KANAN -->
    <div class="main-content">
        
        <div class="banner">
            <h1>Data Pelanggan Setia 👥</h1>
            <p>Daftar akun pelanggan yang telah mendaftar dan berbelanja di toko RYACREAFT.</p>
        </div>

        <!-- TABEL DATA PELANGGAN -->
        <div class="card-box">
            <h3>📋 Daftar Akun Pelanggan</h3>
            <table class="table-produk">
                <thead>
                    <tr>
                        <th>No</th>
                        <th>ID</th>
                        <th>Nama</th>
                        <th>Email</th>
                        <th>No. HP</th>
                        <th>Alamat</th>
                        <th>Bergabung</th>
                        <th>Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    <?php 
                    $no = 1;
                    if ($query_pelanggan && mysqli_num_rows($query_pelanggan) > 0):
                        while ($row = mysqli_fetch_assoc($query_pelanggan)): 
                    ?>
                        <tr>
                            <td><?php echo $no++; ?></td>
                            <td><b>#<?php echo $row['id_user']; ?></b></td>
                            <td><?php echo $row['nama']; ?></td>
                            <td><?php echo $row['email']; ?></td>
                            <td><?php echo !empty($row['no_hp']) ? $row['no_hp'] : '-'; ?></td>
                            <td><?php echo !empty($row['alamat']) ? $row['alamat'] : '-'; ?></td>
                            <td><?php echo isset($row['created_at']) ? $row['created_at'] : '-'; ?></td>
                            <td>
                                <a href="pelanggan.php?hapus=<?php echo $row['id_user']; ?>" onclick="return confirm('Yakin ingin menghapus akun pelanggan ini?')" class="btn-hapus" style="color:#e63946; text-decoration:none; font-weight:600;">🗑️ Hapus</a>
                            </td>
                        </tr>
                    <?php 
                        endwhile;
                    else:
                    ?>
                        <tr>
                            <td colspan="8" style="text-align: center; padding: 25px; color: #777;">
                                Belum ada pelanggan lain yang mendaftar saat ini. ✨
                            </td>
                        </tr>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>

    </div>

</body>
</html>