<?php include 'koneksi.php'; ?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Bloom & Co. - Toko Bunga Pastel</title>
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
    <div class="hero">
        <h2>🌸 The Story Behind Bloom & Co. 🌸</h2>
        <h3>"Where every petal tells a story, and every bloom holds a memory."</h3>
        <p>Bloom & Co. lahir dari kecintaan kami pada keindahan bunga yang tak pernah gagal membawa senyuman.</p>
        <p>Kata "Bloom" melambangkan proses mekar—sebuah filosofi tentang keindahan, harapan baru, dan momen-momen manis yang tumbuh dalam hidup. Kami percaya bahwa setiap bunga yang mekar memiliki bahasanya sendiri untuk menyampaikan kebahagiaan, rasa terima kasih, hingga cinta yang tulus.</p>
        <p>Sedangkan "& Co." (Company & Companions) mencerminkan komitmen kami untuk menjadi teman setia dalam setiap momen berhargamu. Kami bukan sekadar toko bunga, melainkan tempat di mana cerita, perasaan, dan kenangan indah dirangkai menjadi satu bentuk apresiasi yang tak terlupakan.</p>
        <a href="katalog.php" class="btn">Lihat Katalog Bunga</a>
    </div>

    <h3>✨ Produk Pilihan Terbaik</h3>
    <div class="product-grid">
        <?php
        $result = mysqli_query($koneksi, "SELECT * FROM produk LIMIT 3");
        while ($row = mysqli_fetch_assoc($result)) {
        ?>
            <div class="product-card">
                <img src="<?php echo $row['foto']; ?>" alt="Bunga">
                <h4><?php echo $row['nama_produk']; ?></h4>
                <p>Rp <?php echo number_format($row['harga'], 0, ',', '.'); ?></p>
                <a href="keranjang.php?id=<?php echo $row['id']; ?>" class="btn">Beli Sekarang</a>
            </div>
        <?php } ?>
    </div>
</div>

<footer>
    <p>&copy; 2026 Bloom & Co. Made with 🌷 and Pastel Colors.</p>
</footer>

</body>
</html>