<?php
session_start();
include '../koneksi.php';

// Cek Login Admin
if (!isset($_SESSION['login']) || $_SESSION['role'] !== 'admin') {
    header("Location: ../login.php");
    exit();
}

// Proses Update Status Pesanan (jika tombol ubah status diklik)
if (isset($_POST['update_status'])) {
    $id_pesanan = $_POST['id_pesanan'];
    $status_baru = mysqli_real_escape_string($koneksi, $_POST['status']);
    
    mysqli_query($koneksi, "UPDATE pesanan SET status = '$status_baru' WHERE id_pesanan = '$id_pesanan'");
    header("Location: pesanan.php");
    exit();
}

// Proses Hapus Pesanan
if (isset($_GET['hapus'])) {
    $id = $_GET['hapus'];
    mysqli_query($koneksi, "DELETE FROM pesanan WHERE id_pesanan = '$id'");
    header("Location: pesanan.php");
    exit();
}

// Ambil Data Pesanan dari Database
$query_pesanan = mysqli_query($koneksi, "SELECT * FROM pesanan ORDER BY id_pesanan DESC");
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Kelola Pesanan - Admin Bloom</title>
    <link rel="stylesheet" href="../style.css">
</head>
<body class="admin-body">

    <!-- SIDEBAR KIRI (Konsisten dengan halaman lain) -->
    <div class="sidebar">
        <h2>🌸 Admin Bloom</h2>
        <a href="index.php">🎀 Dashboard</a>
        <a href="produk.php">🌷 Kelola Produk</a>
        <a href="pesanan.php" class="active">📦 Kelola Pesanan</a>
        <a href="pelanggan.php">👥 Data Pelanggan</a>
        <a href="../logout.php" class="logout">🚪 Logout</a>
    </div>

    <!-- KONTEN UTAMA KANAN -->
    <div class="main-content">
        
        <div class="banner">
            <h1>Kelola Pesanan Masuk 📦</h1>
            <p>Pantau pesanan buket bunga dari pelanggan dan perbarui status pengirimannya di sini.</p>
        </div>

        <!-- TABEL DAFTAR PESANAN -->
        <div class="card-box">
            <h3>📋 Daftar Pesanan Pelanggan</h3>
            <table class="table-produk">
                <thead>
                   <tr>
    <th>No</th>
    <th>ID Pesan</th>
    <th>Nama Pemesan</th>
    <th>No. HP</th>
    <th>Email</th>
    <th>Alamat Pengiriman</th>
    <th>Catatan</th>
    <th>Detail Buket</th>
    <th>Total Harga</th>
    <th>Status</th>
    <th>Aksi</th>
</tr>
                </thead>
                <tbody>
                   <?php
$no = 1;
if ($query_pesanan && mysqli_num_rows($query_pesanan) > 0):
    while ($row = mysqli_fetch_assoc($query_pesanan)):
?>
    <tr>
        <td><?php echo $no++; ?></td>
        <td><b>#<?php echo $row['id_pesanan']; ?></b></td>
        <td><?php echo isset($row['nama_pemesan']) ? htmlspecialchars($row['nama_pemesan']) : 'Pelanggan'; ?></td>
        <td><?php echo isset($row['no_hp']) ? htmlspecialchars($row['no_hp']) : '-'; ?></td>         <!-- Menampilkan No HP -->
        <td><?php echo isset($row['email']) ? htmlspecialchars($row['email']) : '-'; ?></td>         <!-- Menampilkan Email -->
        <td><?php echo isset($row['alamat']) ? htmlspecialchars($row['alamat']) : '-'; ?></td>
        <td><?php echo isset($row['detail_produk']) ? htmlspecialchars($row['detail_produk']) : 'Buket Bunga'; ?></td>
        <td><?php echo isset($row['catatan']) && !empty($row['catatan']) ? htmlspecialchars($row['catatan']) : '-'; ?></td> <!-- Menampilkan Catatan -->
        <td>Rp <?php echo is_numeric($row['total_harga']) ? number_format($row['total_harga'], 0, ',', '.') : $row['total_harga']; ?></td>
        <td>
            <!-- Form ganti status langsung di tabel -->
            <form action="" method="POST" style="display:inline;">
                <input type="hidden" name="id_pesanan" value="<?php echo $row['id_pesanan']; ?>">
                <select name="status" onchange="this.form.submit()" style="padding: 5px; border-radius: 6px; border: 1px solid #ccc;">
                    <option value="Menunggu" <?php if($row['status'] == 'Menunggu') echo 'selected'; ?>>Menunggu</option>
                    <option value="Diproses" <?php if($row['status'] == 'Diproses') echo 'selected'; ?>>Diproses</option>
                    <option value="Selesai" <?php if($row['status'] == 'Selesai') echo 'selected'; ?>>Selesai</option>
                    <option value="Dibatalkan" <?php if($row['status'] == 'Dibatalkan') echo 'selected'; ?>>Dibatalkan</option>
                </select>
                <input type="hidden" name="update_status" value="1">
            </form>
        </td>
        <td>
            <a href="pesanan.php?hapus=<?php echo $row['id_pesanan']; ?>" onclick="return confirm('Yakin ingin menghapus data pesanan ini?')">Hapus</a>
        </td>
    </tr>
<?php 
    endwhile;
endif; 
?>
                        <tr>
                            <td colspan="7" style="text-align: center; padding: 25px; color: #777;">
                                Belum ada pesanan masuk dari pelanggan saat ini. ✨
                            </td>
                        </tr>
                </tbody>
            </table>
        </div>

    </div>

<footer>
    <p>&copy; 2026 ⊹₊˚‧︵‿₊୨RYACREAFT୧₊‿︵‧˚₊⊹ Made with 🌷 and Pastel Colors.</p>
</footer>

</body>
</html>