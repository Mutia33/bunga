<?php 
include 'koneksi.php';
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Bloom & Co. - Toko Bunga Pastel</title>
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

<div class="container">
    <div class="hero">
        <h2>🌸 The Story RYACREAFT 🌸</h2>
        <h3>"Crafting sweet memories into soft, everlasting petals."</h3>
        <p>Nama RyaCraft melambangkan komitmen kami dalam menghadirkan produk handicraft yang tidak hanya indah dipandang, tetapi juga memiliki sentuhan kelembutan dan nilai emosional yang tinggi. Tidak seperti bunga segar pada umumnya, buket dari RyaCraft dirancang untuk bertahan selamanya sebagai pengingat momen-momen manismu.</p>
        <p>Temukan buket bunga impianmu dan sampaikan perasaanmu lewat karya seni buatan tangan yang tak tergerus waktu.</p>
    </div>
</div>

<footer>
    <p>&copy; 2026 ⊹₊˚‧︵‿₊୨RYACREAFT୧₊‿︵‧˚₊⊹ Made with 🌷 and Pastel Colors.</p>
</footer>

</body>
</html>