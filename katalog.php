<?php include 'koneksi.php'; ?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Jenis-Jenis Bunga - Bloom & Co.</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>

<header>
    <h1>🌸 Bloom & Co. 🌸</h1>
    <nav>
        <a href="index.php">Home</a>
        <a href="katalog.php">Jenis Bunga</a>
        <a href="galeri.php">Galeri Buket</a>
        <a href="keranjang.php">Keranjang</a>
    </nav>
</header>

<div class="container">
    <!-- Judul dibungkus dengan section-box -->
    <div class="section-box">
        <h2>Tulip, Mawar, & Lily Koleksi Kami</h2>
    </div>

    <div class="product-grid">
        <?php
        $result = mysqli_query($koneksi, "SELECT * FROM produk");
        while ($row = mysqli_fetch_assoc($result)) {
        ?>
            <div class="product-card">
                <img src="<?php echo $row['foto']; ?>" alt="Bunga">
                <h4><?php echo $row['nama_produk']; ?></h4>
                <p style="font-size: 13px; color: #888;">Kategori: <?php echo $row['kategori']; ?></p>
                <p><strong>Rp <?php echo number_format($row['harga'], 0, ',', '.'); ?></strong></p>
                <p style="font-size: 14px;"><?php echo $row['deskripsi']; ?></p>
                <a href="#" class="btn">Tambah ke Keranjang</a>
            </div>
        <?php } ?>
    </div>
</div>

<footer>
    <p>&copy; 2026 Bloom & Co.</p>
</footer>

</body>
</html>