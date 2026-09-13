<?php
session_start();
include 'koneksi.php';

// Cek apakah user sudah login
if (!isset($_SESSION['id_user'])) {
    header("Location: login.php");
    exit();
}

$id_user = $_SESSION['id_user'];

// Proses Update Profil jika tombol edit diklik
if (isset($_POST['update_profil'])) {
    $new_nama   = mysqli_real_escape_string($koneksi, $_POST['nama']);
    $new_nohp   = mysqli_real_escape_string($koneksi, $_POST['no_hp']);
    $new_alamat = mysqli_real_escape_string($koneksi, $_POST['alamat']);

    $update_query = mysqli_query($koneksi, "UPDATE users SET nama = '$new_nama', no_hp = '$new_nohp', alamat = '$new_alamat' WHERE id_user = '$id_user'");
    
    if ($update_query) {
        $_SESSION['nama'] = $new_nama; // Update nama di session juga
        echo "<script>alert('Informasi akun berhasil diperbarui!'); window.location.href='profil.php';</script>";
        exit();
    } else {
        echo "<script>alert('Gagal memperbarui informasi.');</script>";
    }
}

// 1. Ambil data profil user yang sedang login
$query_user = mysqli_query($koneksi, "SELECT * FROM users WHERE id_user = '$id_user'");
$user = mysqli_fetch_assoc($query_user);

// 2. Ambil riwayat pesanan milik user ini berdasarkan emailnya
$email_user = $user['email'];
$query_pesanan = mysqli_query($koneksi, "SELECT * FROM pesanan WHERE email = '$email_user' ORDER BY id_pesanan DESC");
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Profil Saya - RYACREAFT</title>
    <link rel="stylesheet" href="style.css">
    <style>
        .btn-edit {
            background-color: #d81b60;
            color: white;
            border: none;
            padding: 8px 15px;
            border-radius: 4px;
            font-weight: bold;
            cursor: pointer;
        }
        .btn-edit:hover {
            background-color: #b0236c;
        }
        .form-edit-container {
            background: #fff;
            padding: 20px;
            border-radius: 8px;
            margin-bottom: 25px;
            box-shadow: 0 2px 5px rgba(0,0,0,0.1);
        }
        .form-group-profil {
            margin-bottom: 15px;
        }
        .form-group-profil label {
            display: block;
            font-weight: bold;
            font-size: 14px;
            margin-bottom: 5px;
            color: #333;
        }
        .form-group-profil input, .form-group-profil textarea {
            width: 100%;
            padding: 8px;
            border: 1px solid #ccc;
            border-radius: 4px;
            font-size: 14px;
        }
    </style>
</head>
<body style="background-color: #fdf6f0; font-family: sans-serif; margin: 0; padding: 0;">

    <!-- NAVBAR ATAS -->
    <header>
        <h1>⊹₊˚‧︵‿₊୨RYACREAFT୧₊‿︵‧˚₊⊹</h1>
        <nav>
            <a href="index.php">Home</a>
            <a href="galeri.php">Galeri Buket</a>
            <a href="keranjang.php">Keranjang</a>
            <?php if (isset($_SESSION['login']) && $_SESSION['login'] === true): ?>
                <a href="profil.php">Profil Saya</a>
                <a href="logout.php" onclick="return confirm('Yakin ingin keluar dari akun?')">Logout</a>
            <?php else: ?>
                <a href="login.php">Login/Account</a>
            <?php endif; ?>
        </nav>
    </header>

    <!-- KONTEN UTAMA -->
    <div class="container" style="max-width: 900px; margin: 30px auto; padding: 20px;">
        <div class="section-box" style="margin-bottom: 20px;">
            <h2>⋆⊱༻Selamat Datang, <?php echo htmlspecialchars($user['nama']); ?>!༺⊰⋆</h2>
            <p>Berikut adalah informasi akun dan riwayat pesanan bunga kamu di RYACREAFT.</p>
        </div>

        <!-- Kotak Informasi Profil -->
        <div style="background: #fff; padding: 20px; border-radius: 8px; margin-bottom: 25px; box-shadow: 0 2px 5px rgba(0,0,0,0.1);">
            <h3 style="margin-top: 0; color: #333;">👤 Informasi Akun Saya</h3>
            <p><b>Nama:</b> <?php echo htmlspecialchars($user['nama']); ?></p>
            <p><b>Email:</b> <?php echo htmlspecialchars($user['email']); ?></p>
            <p><b>No. HP:</b> <?php echo !empty($user['no_hp']) ? htmlspecialchars($user['no_hp']) : '-'; ?></p>
            <p><b>Alamat Pengiriman:</b> <?php echo !empty($user['alamat']) ? htmlspecialchars($user['alamat']) : '-'; ?></p>
        </div>

        <!-- Form Edit Informasi Akun (Alamat, No HP, Nama) -->
        <div class="form-edit-container">
            <h3 style="margin-top: 0; color: #333;">✏️ Ubah Informasi Akun / Alamat</h3>
            <form action="" method="POST">
                <div class="form-group-profil">
                    <label>Nama:</label>
                    <input type="text" name="nama" value="<?php echo htmlspecialchars($user['nama']); ?>" required>
                </div>
                <div class="form-group-profil">
                    <label>No. HP / WhatsApp:</label>
                    <input type="text" name="no_hp" value="<?php echo htmlspecialchars($user['no_hp']); ?>" required>
                </div>
                <div class="form-group-profil">
                    <label>Alamat Lengkap:</label>
                    <textarea name="alamat" rows="3" required><?php echo htmlspecialchars($user['alamat']); ?></textarea>
                </div>
                <button type="submit" name="update_profil" class="btn-edit">
                    Simpan Perubahan
                </button>
            </form>
        </div>

        <!-- Kotak Riwayat Pesanan & Status -->
        <div style="background: #fff; padding: 20px; border-radius: 8px; box-shadow: 0 2px 5px rgba(0,0,0,0.1);">
            <h3 style="margin-top: 0; color: #333;">📦 Riwayat & Status Pesanan</h3>
            <table width="100%" border="1" cellspacing="0" cellpadding="10" style="border-collapse: collapse; margin-top: 15px; border-color: #ddd;">
                <thead>
                    <tr style="background: #fdf2f8; color: #d63384;">
                        <th>No</th>
                        <th>ID Pesanan</th>
                        <th>Nama Produk (Buket)</th>
                        <th>Total Harga</th>
                        <th>Tanggal</th>
                        <th>Status Pesanan</th>
                    </tr>
                </thead>
                <tbody>
                    <?php 
                    $no = 1;
                    if ($query_pesanan && mysqli_num_rows($query_pesanan) > 0):
                        while ($row = mysqli_fetch_assoc($query_pesanan)): 
                            $status = $row['status'];
                            $badge_color = "#ffc107"; // Kuning (Menunggu)
                            if ($status == 'Di Jalan' || $status == 'Dikirim' || $status == 'Diproses') {
                                $badge_color = "#17a2b8"; // Biru
                            } elseif ($status == 'Selesai') {
                                $badge_color = "#28a745"; // Hijau
                            } elseif ($status == 'Dibatalkan') {
                                $badge_color = "#dc3545"; // Merah
                            }
                    ?>
                        <tr>
                            <td align="center"><?php echo $no++; ?></td>
                            <td align="center"><b>#<?php echo $row['id_pesanan']; ?></b></td>
                            <td><?php echo !empty($row['detail_produk']) ? htmlspecialchars($row['detail_produk']) : 'Buket Custom'; ?></td>
                            <td>Rp <?php echo number_format((float)$row['total_harga'], 0, ',', '.'); ?></td>
                            <td><?php echo $row['tanggal_pesan'] ?? 'Baru saja'; ?></td>
                            <td align="center">
                                <span style="background: <?php echo $badge_color; ?>; color: #fff; padding: 5px 10px; border-radius: 4px; font-size: 12px; font-weight: bold;">
                                    <?php echo $status; ?>
                                </span>
                            </td>
                        </tr>
                    <?php 
                        endwhile;
                    else:
                    ?>
                        <tr>
                            <td colspan="6" style="text-align: center; padding: 20px; color: #777;">
                                Kamu belum memiliki riwayat pesanan. Yuk, mulai belanja buket bunga favoritmu! 🌷
                            </td>
                        </tr>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
    </div>

    <footer>
        <p>&copy; 2026 ⊹₊˚‧︵‿₊୨RYACREAFT୧₊‿︵‧˚₊⊹ Made with 🌷 and Pastel Colors.</p>
    </footer>
</body>
</html>