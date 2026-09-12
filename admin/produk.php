<?php
session_start();
include '../koneksi.php';

// Proteksi Halaman Admin
if (!isset($_SESSION['login']) || $_SESSION['role'] !== 'admin') {
    header("Location: ../login.php");
    exit();
}

$pesan = "";

// Proses Tambah Produk Bunga Baru
if (isset($_POST['tambah'])) {
    $nama      = mysqli_real_escape_string($koneksi, $_POST['nama_produk']);
    $kategori  = mysqli_real_escape_string($koneksi, $_POST['kategori']);
    $harga     = (float)$_POST['harga'];
    $deskripsi = mysqli_real_escape_string($koneksi, $_POST['deskripsi']);
    $foto      = mysqli_real_escape_string($koneksi, $_POST['foto']);

    $query = "INSERT INTO produk (nama_produk, kategori, harga, foto, deskripsi) 
              VALUES ('$nama', '$kategori', '$harga', '$foto', '$deskripsi')";
    
    if (mysqli_query($koneksi, $query)) {
        $pesan = "<div class='success-msg'>Bunga berhasil ditambahkan! 🌸</div>";
    } else {
        $pesan = "<div class='error-msg'>Gagal menyimpan ke database!</div>";
    }
}

// Proses Edit Produk Bunga
if (isset($_POST['edit'])) {
    $id_edit   = (int)$_POST['id'];
    $nama      = mysqli_real_escape_string($koneksi, $_POST['nama_produk']);
    $kategori  = mysqli_real_escape_string($koneksi, $_POST['kategori']);
    $harga     = (float)$_POST['harga'];
    $deskripsi = mysqli_real_escape_string($koneksi, $_POST['deskripsi']);
    $foto      = mysqli_real_escape_string($koneksi, $_POST['foto']);

    $query = "UPDATE produk SET 
                nama_produk = '$nama', 
                kategori = '$kategori', 
                harga = '$harga', 
                foto = '$foto', 
                deskripsi = '$deskripsi' 
              WHERE id = $id_edit";
              
    if (mysqli_query($koneksi, $query)) {
        $pesan = "<div class='success-msg'>Data bunga berhasil diperbarui! 🌷</div>";
    }
}

// Proses Hapus Produk Bunga
if (isset($_GET['hapus'])) {
    $id_hapus = (int)$_GET['hapus'];
    mysqli_query($koneksi, "DELETE FROM produk WHERE id = $id_hapus");
    header("Location: produk.php");
    exit();
}

// Ambil Data Bunga Jika Ada Request Edit
$data_edit = null;
if (isset($_GET['edit_id'])) {
    $id_edit = (int)$_GET['edit_id'];
    $query_edit = mysqli_query($koneksi, "SELECT * FROM produk WHERE id = $id_edit");
    $data_edit = mysqli_fetch_assoc($query_edit);
}

// Ambil Semua Data Produk
$query_produk = mysqli_query($koneksi, "SELECT * FROM produk ORDER BY id DESC");
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Kelola Produk - Bloom & Co.</title>
    <link rel="stylesheet" href="../style.css">
</head>
<body class="admin-body">

    <!-- Sidebar Admin -->
    <div class="sidebar">
        <h2>🌸 Admin Bloom</h2>
        <ul>
            <li><a href="index.php">Dashboard</a></li>
            <li><a href="produk.php" class="active">Kelola Produk</a></li>
            <li><a href="pesanan.php">Kelola Pesanan</a></li>
            <li><a href="users.php">Data Pelanggan</a></li>
            <li><a href="../logout.php" class="btn-logout">Logout</a></li>
        </ul>
    </div>

    <!-- Konten Utama Kelola Produk -->
    <div class="main-content">
        <header>
            <h1>Kelola Buket & Bunga 🌷</h1>
            <p>Tambah dan atur daftar produk bunga custom (made-to-order).</p>
        </header>

        <?php echo $pesan; ?>

        <!-- Form Tambah / Edit Produk -->
        <div class="quick-info">
            <h3><?php echo $data_edit ? '✏️ Edit Produk Bunga' : '+ Tambah Produk Baru'; ?></h3>
            <form action="produk.php" method="POST" class="admin-form">
                <?php if ($data_edit): ?>
                    <input type="hidden" name="id" value="<?php echo $data_edit['id']; ?>">
                <?php endif; ?>

                <div class="form-grid">
                    <div class="form-group">
                        <label>Nama Bunga / Buket</label>
                        <input type="text" name="nama_produk" value="<?php echo $data_edit ? htmlspecialchars($data_edit['nama_produk']) : ''; ?>" placeholder="Contoh: Pastel Pink Rose Bouquet" required>
                    </div>
                    <div class="form-group">
                        <label>Kategori</label>
                        <input type="text" name="kategori" value="<?php echo $data_edit ? htmlspecialchars($data_edit['kategori']) : ''; ?>" placeholder="Contoh: Mawar, Lily, atau Bouquet" required>
                    </div>
                    <div class="form-group">
                        <label>Harga (Rp)</label>
                        <input type="number" name="harga" value="<?php echo $data_edit ? $data_edit['harga'] : ''; ?>" placeholder="Contoh: 150000" required>
                    </div>
                    <div class="form-group">
                        <label>Link Foto / Gambar</label>
                        <input type="text" name="foto" value="<?php echo $data_edit ? htmlspecialchars($data_edit['foto']) : ''; ?>" placeholder="Contoh: https://pin.it/..." required>
                    </div>
                </div>
                <div class="form-group">
                    <label>Deskripsi</label>
                    <textarea name="deskripsi" rows="3" placeholder="Deskripsi singkat mengenai bunga ini..." required><?php echo $data_edit ? htmlspecialchars($data_edit['deskripsi']) : ''; ?></textarea>
                </div>

                <div style="display: flex; gap: 10px;">
                    <button type="submit" name="<?php echo $data_edit ? 'edit' : 'tambah'; ?>" class="btn-login-submit" style="width: auto; padding: 10px 25px;">
                        <?php echo $data_edit ? 'Update Bunga' : 'Simpan Bunga'; ?>
                    </button>
                    <?php if ($data_edit): ?>
                        <a href="produk.php" class="btn-cancel">Batal</a>
                    <?php endif; ?>
                </div>
            </form>
        </div>

        <!-- Tabel Daftar Produk -->
        <div class="quick-info" style="margin-top: 30px;">
            <h3>Daftar Produk Bunga</h3>
            <table class="admin-table">
                <thead>
                    <tr>
                        <th>No</th>
                        <th>Foto</th>
                        <th>Nama Bunga</th>
                        <th>Kategori</th>
                        <th>Harga</th>
                        <th>Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    <?php 
                    $no = 1;
                    if (mysqli_num_rows($query_produk) > 0):
                        while ($row = mysqli_fetch_assoc($query_produk)): 
                    ?>
                        <tr>
                            <td><?php echo $no++; ?></td>
                            <td>
                                <img src="<?php echo $row['foto']; ?>" alt="Bunga" class="img-thumb" onerror="this.src='https://via.placeholder.com/60'">
                            </td>
                            <td><strong><?php echo htmlspecialchars($row['nama_produk']); ?></strong></td>
                            <td><span class="badge-kategori"><?php echo htmlspecialchars($row['kategori']); ?></span></td>
                            <td>Rp <?php echo number_format($row['harga'], 0, ',', '.'); ?></td>
                            <td>
                                <a href="produk.php?edit_id=<?php echo $row['id']; ?>" class="btn-edit">Edit</a>
                                <a href="produk.php?hapus=<?php echo $row['id']; ?>" class="btn-delete" onclick="return confirm('Yakin ingin menghapus bunga ini?')">Hapus</a>
                            </td>
                        </tr>
                    <?php 
                        endwhile;
                    else:
                    ?>
                        <tr>
                            <td colspan="6" style="text-align: center; color: #888;">Belum ada produk bunga ditambahkan.</td>
                        </tr>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
    </div>

</body>
</html>