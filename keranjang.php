<?php
session_start();
include 'koneksi.php';

if (!isset($_SESSION['id_user'])) {
    header("Location: login.php");
    exit();
}

$id_user = $_SESSION['id_user'];
$query_keranjang = mysqli_query($koneksi, "SELECT * FROM keranjang WHERE id_user = '$id_user'");
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Keranjang Belanja - RYACREAFT</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>
    <header>
    <h1>⊹₊˚‧︵‿₊୨RYACREAFT୧₊‿︵‧˚₊⊹</h1>
    <nav>
    <a href="index.php">Home</a>
    <a href="galeri.php">Galeri Buket</a>
    <a href="keranjang.php">Keranjang</a>
    
    <?php if (isset($_SESSION['login']) && $_SESSION['login'] === true): ?>
        <!-- Jika sudah login / selesai checkout: Tampilkan Profil & Logout -->
        <a href="profil.php">Profil Saya</a>
        <a href="logout.php" onclick="return confirm('Yakin ingin keluar dari akun?')">Logout</a>
    <?php else: ?>
        <!-- Jika belum login: Tampilkan Login/Account saja -->
        <a href="login.php">Login/Account</a>
    <?php endif; ?>
</nav>
</header>
    <div class="container-keranjang" style="max-width: 900px; margin: 40px auto; background: white; padding: 20px; border-radius: 10px;">
        <h2>🛒 Keranjang Belanja Kamu</h2>
        <form action="checkout.php" method="POST">
            <table width="100%" border="1" cellspacing="0" cellpadding="10" style="border-collapse: collapse; margin-top: 20px;">
                <thead>
                    <tr style="background: #fdf2f8; color: #d63384;">
                        <th>Pilih</th>
                        <th>No</th>
                        <th>Nama Produk</th>
                        <th>Harga Satuan</th>
                        <th>Jumlah (Qty)</th>
                        <th>Subtotal</th>
                    </tr>
                </thead>
                <tbody>
                    <?php 
                    $no = 1;
                    $total_semua = 0;
                    if ($query_keranjang && mysqli_num_rows($query_keranjang) > 0):
                        while ($row = mysqli_fetch_assoc($query_keranjang)):
                            $subtotal = $row['harga'] * $row['qty'];
                            $total_semua += $subtotal;
                    ?>
                        <tr>
                            <td align="center"><input type="checkbox" name="pilih_item[]" value="<?php echo $row['id_keranjang']; ?>" checked></td>
                            <td align="center"><?php echo $no++; ?></td>
                            <td><?php echo htmlspecialchars($row['nama_produk']); ?></td>
                            <td>Rp <?php echo number_format($row['harga'], 0, ',', '.'); ?></td>
                            <td align="center"><?php echo $row['qty']; ?></td>
                            <td>Rp <?php echo number_format($subtotal, 0, ',', '.'); ?></td>
                        </tr>
                    <?php 
                        endwhile;
                    else:
                    ?>
                        <tr>
                            <td colspan="6" style="text-align: center; padding: 20px; color: #777;">Keranjang kamu masih kosong.</td>
                        </tr>
                    <?php endif; ?>
                </tbody>
            </table>
            <div style="margin-top: 20px; text-align: right;">
                <button type="submit" name="lanjut_checkout" style="background-color: #d63384; color: white; border: none; padding: 10px 20px; font-weight: bold; border-radius: 5px; cursor: pointer;">🛍️ Lanjut ke Checkout</button>
            </div>
        </form>
    </div>
</body>
</html>