<?php
session_start();
include '../koneksi.php';

// Proteksi Halaman Admin
if (!isset($_SESSION['login']) || $_SESSION['role'] !== 'admin') {
    header("Location: ../login.php");
    exit();
}

$pesan = "";

// PROSES TAMBAH PRODUK (UPLOAD FOTO)
if (isset($_POST['tambah'])) {
    $nama      = mysqli_real_escape_string($koneksi, $_POST['nama_produk']);
    $kategori  = mysqli_real_escape_string($koneksi, $_POST['kategori']);
    $harga     = (float)$_POST['harga'];
    $deskripsi = mysqli_real_escape_string($koneksi, $_POST['deskripsi']);

    // Ambil data file foto yang diupload
    $nama_foto   = $_FILES['foto']['name'];
    $tmp_foto    = $_FILES['foto']['tmp_name'];

    // Rename file biar namanya unik (pake timestamp)
    $nama_foto_baru = date('YmdHis') . '_' . $nama_foto;
    $folder_tujuan  = "../assets/img/" . $nama_foto_baru;

    // Buat folder assets/img otomatis jika belum ada
    if (!file_exists('../assets/img')) {
        mkdir('../assets/img', 0777, true);
    }

    // Pindahkan foto dari temporary folder ke folder assets/img
    if (move_uploaded_file($tmp_foto, $folder_tujuan)) {
        $query = "INSERT INTO produk (nama_produk, kategori, harga, foto, deskripsi) 
                  VALUES ('$nama', '$kategori', '$harga', '$nama_foto_baru', '$deskripsi')";
        
        if (mysqli_query($koneksi, $query)) {
            $pesan = "<div class='success-msg'>Bunga berhasil ditambahkan dengan foto! 🌸</div>";
        } else {
            $pesan = "<div class='error-msg'>Gagal menyimpan ke database!</div>";
        }
    } else {
        $pesan = "<div class='error-msg'>Gagal mengunggah foto! Pastikan kamu sudah memilih gambar.</div>";
    }
}

// PROSES EDIT PRODUK
if (isset($_POST['edit'])) {
    $id_edit   = (int)$_POST['id'];
    $nama      = mysqli_real_escape_string($koneksi, $_POST['nama_produk']);
    $kategori  = mysqli_real_escape_string($koneksi, $_POST['kategori']);
    $harga     = (float)$_POST['harga'];
    $deskripsi = mysqli_real_escape_string($koneksi, $_POST['deskripsi']);
    $foto_lama = $_POST['foto_lama'];

    $nama_foto = $_FILES['foto']['name'];
    
    // Cek apakah admin mengupload foto baru atau pakai foto lama
    if (!empty($nama_foto)) {
        $tmp_foto       = $_FILES['foto']['tmp_name'];
        $nama_foto_baru = date('YmdHis') . '_' . $nama_foto;
        $folder_tujuan  = "../assets/img/" . $nama_foto_baru;

        if (move_uploaded_file($tmp_foto, $folder_tujuan)) {
            // Hapus foto lama jika ada di folder
            if (file_exists("../assets/img/" . $foto_lama) && !empty($foto_lama)) {
                unlink("../assets/img/" . $foto_lama);
            }
            $foto_simpan = $nama_foto_baru;
        } else {
            $foto_simpan = $foto_lama;
        }
    } else {
        $foto_simpan = $foto_lama;
    }

    $query = "UPDATE produk SET 
                nama_produk = '$nama', 
                kategori = '$kategori', 
                harga = '$harga', 
                foto = '$foto_simpan', 
                deskripsi = '$deskripsi' 
              WHERE id = $id_edit";
              
    if (mysqli_query($koneksi, $query)) {
        $pesan = "<div class='success-msg'>Data bunga berhasil diperbarui! 🌷</div>";
    }
}

// PROSES HAPUS PRODUK
if (isset($_GET['hapus'])) {
    $id_hapus = (int)$_GET['hapus'];
    
    // Hapus file foto dari folder assets/img
    $ambil_foto = mysqli_query($koneksi, "SELECT foto FROM produk WHERE id = $id_hapus");
    $data_foto  = mysqli_fetch_assoc($ambil_foto);
    if ($data_foto && file_exists("../assets/img/" . $data_foto['foto'])) {
        unlink("../assets/img/" . $data_foto['foto']);
    }

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
            <p>Atur katalog bunga dan tambah produk baru di sini.</p>
        </header>

        <?php echo $pesan; ?>

        <!-- 1. TABEL DAFTAR PRODUK (POSISI DI ATAS) -->
        <div class="quick-info">
            <h3>Daftar Produk Bunga</h3>
            <table class="admin-table">
                <thead>
                    <tr>
                        <th style="width: 50px;">No</th>
                        <th style="width: 80px;">Foto</th>
                        <th>Nama Bunga</th>
                        <th>Kategori</th>
                        <th>Harga</th>
                        <th style="width: 150px;">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    <?php 
                    $no = 1;
                    if (mysqli_num_rows($query_produk) > 0):
                        while ($row = mysqli_fetch_assoc($query_produk)): 
                            // Cek apakah foto berupa URL luar atau nama file lokal
                            $src_foto = (strpos($row['foto'], 'http') === 0) ? $row['foto'] : "../assets/img/" . $row['foto'];
                    ?>
                        <tr>
                            <td><?php echo $no++; ?></td>
                            <td>
                                <img src="<?php echo htmlspecialchars($src_foto); ?>" alt="Bunga" class="img-thumb" onerror="this.onerror=null; this.src='https://via.placeholder.com/60/fce4ec/d86b89?text=No+Img';">
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

        <!-- 2. FORM TAMBAH / EDIT PRODUK (POSISI DI BAWAH) -->
        <div class="quick-info" style="margin-top: 30px;">
            <h3><?php echo $data_edit ? '✏️ Edit Produk Bunga' : '+ Tambah Produk Baru'; ?></h3>
            <form action="produk.php" method="POST" enctype="multipart/form-data" class="admin-form">
                <?php if ($data_edit): ?>
                    <input type="hidden" name="id" value="<?php echo $data_edit['id']; ?>">
                    <input type="hidden" name="foto_lama" value="<?php echo htmlspecialchars($data_edit['foto']); ?>">
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
                        <label>Upload Foto Gambar</label>
                        <input type="file" name="foto" accept="image/*" <?php echo $data_edit ? '' : 'required'; ?>>
                        <?php if ($data_edit): ?>
                            <small style="color: #888; font-size: 11px;">*Biarkan kosong jika tidak ingin mengubah foto</small>
                        <?php endif; ?>
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

    </div>

</body>
</html>