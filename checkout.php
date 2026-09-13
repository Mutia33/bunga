<?php
session_start();
include 'koneksi.php';

// Cek apakah user sudah login
if (!isset($_SESSION['id_user'])) {
    echo "<script>alert('Silakan login terlebih dahulu!'); window.location.href = 'login.php';</script>";
    exit();
}

$id_user = $_SESSION['id_user'];
$nama_user = "";
$email_user = "";
$no_hp_user = "";
$alamat_user = "";

// Ambil data user yang sedang login
$query_cek = mysqli_query($koneksi, "SELECT * FROM users WHERE id_user = '$id_user'");
if ($query_cek && mysqli_num_rows($query_cek) > 0) {
    $data_lgn = mysqli_fetch_assoc($query_cek);
    $nama_user = $data_lgn['nama'];
    $email_user = $data_lgn['email'];
    $no_hp_user = $data_lgn['no_hp'];
    $alamat_user = $data_lgn['alamat'];
}

// Ambil data item keranjang yang dicentang dari halaman keranjang.php
if (!isset($_POST['pilih_item']) || empty($_POST['pilih_item'])) {
    echo "<script>alert('Pilih minimal 1 produk di keranjang untuk checkout!'); window.location.href = 'keranjang.php';</script>";
    exit();
}

$selected_ids = $_POST['pilih_item']; // Berupa array id_keranjang
$total_harga_semua = 0;
$detail_pesanan_arr = array();
$item_details_html = "";

foreach ($selected_ids as $id_keranjang) {
    $id_keranjang = mysqli_real_escape_string($koneksi, $id_keranjang);
    $query_k = mysqli_query($koneksi, "SELECT * FROM keranjang WHERE id_keranjang = '$id_keranjang' AND id_user = '$id_user'");
    
    if ($row_k = mysqli_fetch_assoc($query_k)) {
        $subtotal = $row_k['harga'] * $row_k['qty'];
        $total_harga_semua += $subtotal;
        
        $nama_p = $row_k['nama_produk'];
        $qty_p = $row_k['qty'];
        $harga_p = $row_k['harga'];

        $detail_pesanan_arr[] = "$nama_p (Jumlah: $qty_p)";
        $item_details_html .= "<li>$nama_p (Jumlah: $qty_p) - Rp " . number_format($subtotal, 0, ',', '.') . "</li>";
    }
}

$detail_produk_str = implode(", ", $detail_pesanan_arr);
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Checkout - RYACREAFT</title>
    <link rel="stylesheet" href="style.css">
    <style>
        .container-checkout {
            max-width: 800px;
            margin: 40px auto;
            background: white;
            padding: 30px;
            border-radius: 10px;
            box-shadow: 0 4px 8px rgba(0,0,0,0.1);
        }
        .form-group {
            margin-bottom: 20px;
        }
        .form-group label {
            display: block;
            margin-bottom: 8px;
            font-weight: bold;
            color: #333;
        }
        .form-group input, .form-group textarea {
            width: 100%;
            padding: 10px;
            border: 1px solid #ccc;
            border-radius: 5px;
            font-size: 14px;
        }
        .form-group textarea {
            resize: vertical;
            height: 80px;
        }
        .ringkasan-belanja {
            background: #fdf2f8;
            padding: 15px;
            border-radius: 5px;
            margin-bottom: 25px;
            border: 1px solid #f8bbd0;
        }
        .ringkasan-belanja h3 {
            margin-top: 0;
            color: #d63384;
            font-size: 18px;
        }
        .btn-konfirmasi {
            background-color: #d63384;
            color: white;
            border: none;
            padding: 12px 20px;
            font-size: 16px;
            border-radius: 5px;
            cursor: pointer;
            font-weight: bold;
            width: 100%;
        }
        .btn-konfirmasi:hover {
            background-color: #b0236c;
        }
        .total-harga-box {
            font-size: 16px;
            font-weight: bold;
            color: #d63384;
            margin-top: 10px;
        }
    </style>
</head>
<body>

<header>
    <h1>⊹₊˚‧︵‿₊୨RYACREAFT୧₊‿︵‧˚₊⊹</h1>
    <nav>
        <a href="index.php">Home</a>
        <a href="galeri.php">Galeri Buket</a>
        <a href="keranjang.php">Keranjang</a>
        <a href="profil.php">Profil Saya</a>
        <a href="logout.php" style="color: #ff4d4d;">Logout</a>
    </nav>
</header>

<div class="container-checkout">
    <h2>📋 Formulir Pengiriman & Checkout</h2>
    <p style="color: #666; margin-bottom: 20px;">Silakan lengkapi data di bawah ini untuk memproses pesanan buket cantikmu!</p>

    <!-- Ringkasan Produk yang dibeli dari Database -->
    <div class="ringkasan-belanja">
        <h3>🛍️ Produk yang Dipilih:</h3>
        <ul style="margin: 0; padding-left: 20px; color: #444;">
            <?php echo $item_details_html; ?>
        </ul>
        <div class="total-harga-box">
            Total Pembayaran: Rp <?php echo number_format($total_harga_semua, 0, ',', '.'); ?>
        </div>
    </div>

    <!-- Form Pengiriman diarahkan ke proses-checkout.php -->
    <form action="proses-checkout.php" method="POST">
        
        <!-- Kirim data tersembunyi ke proses-checkout.php -->
        <input type="hidden" name="detail_produk" value="<?php echo htmlspecialchars($detail_produk_str); ?>">
        <input type="hidden" name="total_harga" value="<?php echo $total_harga_semua; ?>">
        
        <!-- Kirim ulang id_keranjang yang dicentang agar bisa dihapus setelah checkout berhasil -->
        <?php foreach ($selected_ids as $id_k): ?>
            <input type="hidden" name="pilih_item[]" value="<?php echo $id_k; ?>">
        <?php endforeach; ?>

        <div class="form-group">
            <label>Nama Pemesan</label>
            <input type="text" name="nama_pemesan" value="<?php echo htmlspecialchars($nama_user); ?>" required>
        </div>

        <div class="form-group">
            <label>No. HP / WhatsApp</label>
            <input type="text" name="no_hp" value="<?php echo htmlspecialchars($no_hp_user); ?>" required>
        </div>

        <div class="form-group">
            <label>Email</label>
            <input type="email" name="email" value="<?php echo htmlspecialchars($email_user); ?>" required>
        </div>

        <div class="form-group">
            <label>Alamat Lengkap</label>
            <textarea name="alamat" required><?php echo htmlspecialchars($alamat_user); ?></textarea>
        </div>

        <div class="form-group">
            <label>Catatan Pesanan (Opsional)</label>
            <textarea name="catatan"></textarea>
        </div>

        <button type="submit" name="checkout_btn" class="btn-konfirmasi">
            Konfirmasi Pesanan
        </button>
    </form>
</div>

<footer>
    <p>&copy; 2026 ⊹₊˚‧︵‿₊୨RYACREAFT୧₊‿︵‧˚₊⊹ Made with 🌷 and Pastel Colors.</p>
</footer>

</body>
</html>