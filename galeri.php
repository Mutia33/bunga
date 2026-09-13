<?php
// 1. Hubungkan ke database
include 'koneksi.php';
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// 2. Ambil data produk dari database
$query = mysqli_query($koneksi, "SELECT * FROM produk ORDER BY id_produk DESC");
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Galeri Buket - RYACREAFT</title>
    <link rel="stylesheet" href="style.css">
    <style>
        .qty-container {
            display: none;
            align-items: center;
            justify-content: center;
            gap: 10px;
            margin: 10px 0;
            font-size: 14px;
            color: #555;
        }
        .qty-container input {
            width: 60px;
            text-align: center;
            padding: 5px;
            border: 1px solid #ccc;
            border-radius: 5px;
        }
        /* Notifikasi ngambang ala Shopee di tengah atas */
        #toast-notif {
            position: fixed;
            top: 20px;
            left: 50%;
            transform: translateX(-50%);
            background-color: rgba(0, 0, 0, 0.8);
            color: white;
            padding: 12px 24px;
            border-radius: 5px;
            font-size: 14px;
            z-index: 9999;
            display: none;
            box-shadow: 0 4px 6px rgba(0,0,0,0.2);
        }
    </style>
    <script>
        function tampilkanInput(idContainer, idBtnAwal) {
            document.getElementById(idContainer).style.display = "flex";
            document.getElementById(idBtnAwal).style.display = "none";
        }

        function masukkanKeranjang(namaProduk, hargaProduk, idInput, idContainer, idBtnAwal) {
            let jumlah = parseInt(document.getElementById(idInput).value);
            
            if (jumlah > 0) {
                // Kirim data ke database via AJAX (fetch)
                let formData = new URLSearchParams();
                formData.append('nama_produk', namaProduk);
                formData.append('harga', hargaProduk);
                formData.append('qty', jumlah);

                fetch('tambah-keranjang.php', {
                    method: 'POST',
                    body: formData
                })
                .then(response => response.json())
                .then(data => {
                    if (data.status === 'unauthorized') {
                        alert("Kamu harus login terlebih dahulu untuk menambahkan produk ke keranjang!");
                        window.location.href = 'login.php';
                    } else if (data.status === 'success') {
                        showToast("Produk telah ditambahkan ke keranjang belanja");
                        
                        // Kembalikan tombol ke kondisi semula
                        document.getElementById(idContainer).style.display = "none";
                        document.getElementById(idBtnAwal).style.display = "inline-block";
                        document.getElementById(idInput).value = 1;
                    } else {
                        alert("Gagal menambahkan produk ke keranjang.");
                    }
                })
                .catch(error => {
                    console.error('Error:', error);
                    alert_terjadi_kesalahan;
                });

            } else {
                alert("Masukkan jumlah pesanan yang valid!");
            }
        }

        function showToast(pesan) {
            let toast = document.getElementById('toast-notif');
            toast.innerText = pesan;
            toast.style.display = 'block';
            setTimeout(function() {
                toast.style.display = 'none';
            }, 3000); // Hilang otomatis setelah 3 detik
        }
    </script>
</head>
<body>

<!-- Notifikasi mengambang -->
<div id="toast-notif"></div>

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
    <div class="section-box">
        <h2>⋆⊱༻Custom Bouquet Gallery༺⊰⋆</h2>
        <p>Ini adalah beberapa contoh buket kustom yang pernah kami buat dengan penuh cinta. Kamu juga bisa bebas request pilihan warnanya sesuai keinginanmu (tergantung ketersediaan stok), lho!</p>
    </div>
    
    <div class="product-grid">
        <?php 
        if ($query && mysqli_num_rows($query) > 0):
            while ($row = mysqli_fetch_assoc($query)): 
                $id = $row['id_produk'];
                $nama_esc = addslashes($row['nama_produk']);
                $harga_asli = $row['harga'];
        ?>
            <!-- Produk Dinamis dari Database -->
            <div class="product-card">
                <img src="img/<?php echo $row['foto']; ?>" alt="<?php echo $row['nama_produk']; ?>">
                <h4><?php echo $row['nama_produk']; ?></h4>
                <p>
                    <?php 
                     if (is_numeric($harga_asli)) {
                        echo "Rp " . number_format($harga_asli, 0, ',', '.');
                        } else {
                            echo $harga_asli; 
                        }
                    ?>
                </p>
                
                <button id="btnAwal<?php echo $id; ?>" onclick="tampilkanInput('qtyBox<?php echo $id; ?>', 'btnAwal<?php echo $id; ?>')" class="btn">🛒 Tambah ke Keranjang</button>
                
                <div id="qtyBox<?php echo $id; ?>" class="qty-container">
                    <label>Jumlah:</label>
                    <input type="number" id="qty<?php echo $id; ?>" value="1" min="1">
                    <button onclick="masukkanKeranjang('<?php echo $nama_esc; ?>', '<?php echo $harga_asli; ?>', 'qty<?php echo $id; ?>', 'qtyBox<?php echo $id; ?>', 'btnAwal<?php echo $id; ?>')" class="btn">Masukkan Keranjang</button>
                </div>
            </div>
        <?php 
            endwhile;
        else:
        ?>
            <p style="grid-column: 1/-1; text-align: center; color: #777;">Belum ada buket bunga yang ditambahkan.</p>
        <?php endif; ?>
    </div>
</div>

<footer>
    <p>&copy; 2026 ⊹₊˚‧︵‿₊୨RYACREAFT୧₊‿︵‧˚₊⊹ Made with 🌷 and Pastel Colors.</p>
</footer>

</body>
</html>