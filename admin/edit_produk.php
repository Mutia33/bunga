<?php
session_start();
include '../koneksi.php';

if (!isset($_SESSION['login']) || $_SESSION['role'] !== 'admin') {
    header("Location: ../login.php");
    exit();
}

$id = $_GET['id'];
$data = mysqli_query($koneksi, "SELECT * FROM produk WHERE id_produk = '$id'");
$row = mysqli_fetch_assoc($data);

if (isset($_POST['update'])) {
    $nama_produk = mysqli_real_escape_string($koneksi, $_POST['nama_produk']);
    $kategori    = mysqli_real_escape_string($koneksi, $_POST['kategori']);
    $harga       = mysqli_real_escape_string($koneksi, $_POST['harga']);

    // Cek apakah admin ganti foto baru
    if ($_FILES['foto']['name'] != "") {
        $foto_name = $_FILES['foto']['name'];
        $foto_tmp  = $_FILES['foto']['tmp_name'];
        $foto_baru = time() . '_' . $foto_name;
        $path      = "../img/" . $foto_baru;

        if (move_uploaded_file($foto_tmp, $path)) {
            // Hapus foto lama
            if (file_exists("../img/" . $row['foto'])) {
                unlink("../img/" . $row['foto']);
            }
            $query = "UPDATE produk SET nama_produk='$nama_produk', kategori='$kategori', harga='$harga', foto='$foto_baru' WHERE id_produk='$id'";
        }
    } else {
        $query = "UPDATE produk SET nama_produk='$nama_produk', kategori='$kategori', harga='$harga' WHERE id_produk='$id'";
    }

    if (mysqli_query($koneksi, $query)) {
        header("Location: produk.php");
        exit();
    } else {
        $pesan = "Gagal mengupdate produk: " . mysqli_error($koneksi);
    }
}
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Edit Produk - Admin Bloom</title>
    <link rel="stylesheet" href="../style.css">
</head>
<body class="admin-body">

    <div class="sidebar">
        <h2>🌸 Admin Bloom</h2>
        <a href="index.php">🎀 Dashboard</a>
        <a href="produk.php" class="active">🌷 Kelola Produk</a>
        <a href="pesanan.php">📦 Kelola Pesanan</a>
        <a href="pelanggan.php">👥 Data Pelanggan</a>
        <a href="../logout.php" class="logout">🚪 Logout</a>
    </div>

    <div class="main-content">
        <div class="banner">
            <h1>Edit Produk Bunga ✏️</h1>
            <p>Ubah informasi detail buket bunga sesuai kebutuhan.</p>
        </div>

        <div class="card-box">
            <h3>✨ Form Edit Produk</h3>
            <form action="" method="POST" enctype="multipart/form-data">
                <div class="form-group">
                    <label>Nama Bunga / Buket</label>
                    <input type="text" name="nama_produk" value="<?php echo $row['nama_produk']; ?>" required>
                </div>

                <div class="form-group">
                    <label>Kategori</label>
                    <select name="kategori" required>
                        <option value="Mawar" <?php if($row['kategori']=='Mawar') echo 'selected'; ?>>Mawar</option>
                        <option value="Daisy" <?php if($row['kategori']=='Daisy') echo 'selected'; ?>>Daisy</option>
                        <option value="Lily" <?php if($row['kategori']=='Lily') echo 'selected'; ?>>Lily</option>
                        <option value="Sakura" <?php if($row['kategori']=='Sakura') echo 'selected'; ?>>Sakura</option>
                        <option value="Bouquet" <?php if($row['kategori']=='Bouquet') echo 'selected'; ?>>Bouquet / Campuran</option>
                    </select>
                </div>

                <div class="form-group">
                    <label>Harga (Rp)</label>
                    <input type="text" name="harga" value="<?php echo $row['harga']; ?>" required>
                </div>

                <div class="form-group">
                    <label>Foto Saat Ini:</label><br>
                    <img src="../img/<?php echo $row['foto']; ?>" width="80" style="border-radius: 8px; margin-bottom: 10px;"><br>
                    <label>Ganti Foto (Opsional, biarkan kosong jika tidak ingin diganti)</label>
                    <input type="file" name="foto" accept="image/*">
                </div>

                <button type="submit" name="update" class="btn-simpan">💾 Simpan Perubahan</button>
                <a href="produk.php" style="margin-left: 10px; text-decoration: none; color: #666; font-size: 14px;">Batal</a>
            </form>
        </div>
    </div>

<footer>
    <p>&copy; 2026 ⊹₊˚‧︵‿₊୨RYACREAFT୧₊‿︵‧˚₊⊹ Made with 🌷 and Pastel Colors.</p>
</footer>

</body>
</html>