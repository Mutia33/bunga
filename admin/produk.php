<?php
session_start();
include '../koneksi.php';

// Cek Login Admin
if (!isset($_SESSION['login']) || $_SESSION['role'] !== 'admin') {
    header("Location: ../login.php");
    exit();
}

$pesan = "";

// 1. PROSES TAMBAH PRODUK
if (isset($_POST['tambah'])) {
    $nama_produk = mysqli_real_escape_string($koneksi, $_POST['nama_produk']);
    $kategori    = mysqli_real_escape_string($koneksi, $_POST['kategori']);
    $harga       = mysqli_real_escape_string($koneksi, $_POST['harga']); // Simpan apa adanya tanpa format titik/koma

    $foto_name = $_FILES['foto']['name'];
    $foto_tmp  = $_FILES['foto']['tmp_name'];
    $foto_baru = time() . '_' . $foto_name;
    $path      = "../img/" . $foto_baru;

    if (move_uploaded_file($foto_tmp, $path)) {
        $query = "INSERT INTO produk (nama_produk, kategori, harga, foto) VALUES ('$nama_produk', '$kategori', '$harga', '$foto_baru')";
        if (mysqli_query($koneksi, $query)) {
            $pesan = "🌸 Produk berhasil ditambahkan!";
        } else {
            $pesan = "Gagal simpan ke database: " . mysqli_error($koneksi);
        }
    } else {
        $pesan = "Gagal mengunggah foto ke folder img!";
    }
}

// 2. PROSES HAPUS PRODUK
if (isset($_GET['hapus'])) {
    $id = $_GET['hapus'];
    $get_foto = mysqli_query($koneksi, "SELECT foto FROM produk WHERE id_produk = '$id'");
    if ($get_foto && mysqli_num_rows($get_foto) > 0) {
        $data_foto = mysqli_fetch_assoc($get_foto);
        if (file_exists("../img/" . $data_foto['foto'])) {
            unlink("../img/" . $data_foto['foto']);
        }
    }
    mysqli_query($koneksi, "DELETE FROM produk WHERE id_produk = '$id'");
    header("Location: produk.php");
    exit();
}

// 3. AMBIL DATA PRODUK
$query_produk = mysqli_query($koneksi, "SELECT * FROM produk ORDER BY id_produk DESC");
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Kelola Produk - Admin Bloom</title>
    <link rel="stylesheet" href="../style.css">
</head>
<body class="admin-body">

    <!-- SIDEBAR KIRI (Disamakan persis dengan Dashboard) -->
    <div class="sidebar">
        <h2>🌸 Admin Bloom</h2>
        <a href="index.php">🎀 Dashboard</a>
        <a href="produk.php" class="active">🌷 Kelola Produk</a>
        <a href="pesanan.php">📦 Kelola Pesanan</a>
        <a href="pelanggan.php">👥 Data Pelanggan</a>
        <a href="../logout.php" class="logout">🚪 Logout</a>
    </div>

    <!-- KONTEN UTAMA KANAN -->
    <div class="main-content">
        
        <!-- BANNER SAMBUTAN (Disamakan dengan model dashboard) -->
        <div class="banner">
            <h1>Kelola Buket & Bunga 🌷</h1>
            <p>Atur katalog bunga, ubah harga, dan tambah produk baru di sini dengan mudah.</p>
        </div>

        <?php if (!empty($pesan)): ?>
            <div class="alert-success">
                <?php echo $pesan; ?>
            </div>
        <?php endif; ?>

        <!-- DAFTAR TABEL PRODUK -->
        <div class="card-box">
            <h3>📋 Daftar Produk Bunga</h3>
            <table class="table-produk">
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
                    if ($query_produk && mysqli_num_rows($query_produk) > 0):
                        while ($row = mysqli_fetch_assoc($query_produk)): 
                    ?>
                        <tr>
                            <td><?php echo $no++; ?></td>
                            <td>
                                <img src="../img/<?php echo $row['foto']; ?>" class="img-thumb">
                            </td>
                            <td><b><?php echo $row['nama_produk']; ?></b></td>
                            <td><span class="badge-kategori"><?php echo $row['kategori']; ?></span></td>
                            <td>
                                <?php 
                                 if (is_numeric($row['harga'])) {
                                    echo "Rp " . number_format($row['harga'], 0, ',', '.');
                                  } 
                                else {
                                     echo $row['harga']; 
                                  }
                                 ?>
                            </td>
                            <td>
                                <a href="edit_produk.php?id=<?php echo $row['id_produk']; ?>" class="btn-edit">✏️ Edit</a>
                                <a href="produk.php?hapus=<?php echo $row['id_produk']; ?>" onclick="return confirm('Yakin ingin menghapus produk ini?')" class="btn-hapus">🗑️ Hapus</a>
                            </td>
                        </tr>
                    <?>
                    <?php 
                        endwhile;
                    else:
                    ?>
                        <tr>
                            <td colspan="6" class="text-empty">
                                Belum ada produk bunga ditambahkan. Yuk tambah di bawah! 👇
                            </td>
                        </tr>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>

        <!-- FORM TAMBAH PRODUK BARU -->
        <div class="card-box">
            <h3>✨ Tambah Produk Baru</h3>
            <form action="produk.php" method="POST" enctype="multipart/form-data">
                <div class="form-group">
                    <label>Nama Bunga / Buket</label>
                    <input type="text" name="nama_produk" required placeholder="Contoh: Pastel Pink Rose Bouquet">
                </div>

                <div class="form-group">
                    <label>Kategori</label>
                    <select name="kategori" required>
                        <option value="Mawar">Mawar</option>
                        <option value="Daisy">Daisy</option>
                        <option value="Lily">Lily</option>
                        <option value="Sakura">Sakura</option>
                        <option value="Lavender">Lavender</option>
                        <option value="Tulip">Tulip</option>
                        <option value="Bouquet">Bouquet / Campuran</option>
                    </select>
                </div>

                <div class="form-group">
                    <label>Harga (Angka saja, misal: 7000)</label>
                    <input type="text" name="harga" required placeholder="Contoh: 7000">
                </div>

                <div class="form-group">
                    <label>Foto Buket</label>
                    <input type="file" name="foto" accept="image/*" required>
                </div>

                <button type="submit" name="tambah" class="btn-simpan">💾 Simpan Produk</button>
            </form>
        </div>

    </div>

<footer>
    <p>&copy; 2026 ⊹₊˚‧︵‿₊୨RYACREAFT୧₊‿︵‧˚₊⊹ Made with 🌷 and Pastel Colors.</p>
</footer>

</body>
</html>