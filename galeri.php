<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Galeri Buket - Bloom & Co.</title>
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

        function masukkanKeranjang(namaProduk, idInput) {
            let jumlah = parseInt(document.getElementById(idInput).value);
            if (jumlah > 0) {
                // Ambil data keranjang yang sudah ada di localStorage browser
                let keranjang = JSON.parse(localStorage.getItem('keranjang_shoope')) || {};

                // Jika produk sudah ada, tambahkan jumlahnya. Jika belum, buat baru.
                if (keranjang[namaProduk]) {
                    keranjang[namaProduk] += jumlah;
                } else {
                    keranjang[namaProduk] = jumlah;
                }

                // Simpan kembali ke localStorage
                localStorage.setItem('keranjang_shoope', JSON.stringify(keranjang));

                // Munculkan notifikasi ngambang ala Shopee (tanpa pindah halaman)
                showToast("Produk telah ditambahkan ke keranjang belanja");

                // Reset tampilan input kembali seperti semula di card tersebut
                document.getElementById(idInput).value = 1;
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
        <a href="login.php">Login/Account</a>
    </nav>
</header>

<div class="container">
    <div class="section-box">
        <h2>⋆⊱༻Custom Bouquet Gallery༺⊰⋆</h2>
        <p>Ini adalah beberapa contoh buket kustom yang pernah kami buat dengan penuh cinta. Kamu juga bisa bebas request pilihan warnanya sesuai keinginanmu (tergantung ketersediaan stok), lho!</p>
    </div>
    
    <div class="product-grid">
        <!-- Produk 1 -->
        <div class="product-card">
            <img src="img/1.jpeg" alt="Galeri 1">
            <h4>Single Daisy Bloom</h4>
            <p>Rp.5.000</p>
            <button id="btnAwal1" onclick="tampilkanInput('qtyBox1', 'btnAwal1')" class="btn">🛒 Tambah ke Keranjang</button>
            <div id="qtyBox1" class="qty-container">
                <label>Jumlah:</label>
                <input type="number" id="qty1" value="1" min="1">
                <button onclick="masukkanKeranjang('Single Daisy Bloom', 'qty1')" class="btn">Masukkan Keranjang</button>
            </div>
        </div>

        <!-- Produk 2 -->
        <div class="product-card">
            <img src="img/2.jpeg" alt="Galeri 2">
            <h4>Single Lavender Bloom</h4>
            <p>Rp.7.000</p>
            <button id="btnAwal2" onclick="tampilkanInput('qtyBox2', 'btnAwal2')" class="btn">🛒 Tambah ke Keranjang</button>
            <div id="qtyBox2" class="qty-container">
                <label>Jumlah:</label>
                <input type="number" id="qty2" value="1" min="1">
                <button onclick="masukkanKeranjang('Single Lavender Bloom', 'qty2')" class="btn">Masukkan Keranjang</button>
            </div>
        </div>

        <!-- Produk 3 -->
        <div class="product-card">
            <img src="img/3.jpeg" alt="Galeri 3">
            <h4>Single Lily Bloom</h4>
            <p>Rp.15.000</p>
            <button id="btnAwal3" onclick="tampilkanInput('qtyBox3', 'btnAwal3')" class="btn">🛒 Tambah ke Keranjang</button>
            <div id="qtyBox3" class="qty-container">
                <label>Jumlah:</label>
                <input type="number" id="qty3" value="1" min="1">
                <button onclick="masukkanKeranjang('Single Lily Bloom', 'qty3')" class="btn">Masukkan Keranjang</button>
            </div>
        </div>

        <!-- Produk 4 -->
        <div class="product-card">
            <img src="img/4.jpeg" alt="Galeri 4">
            <h4>Single Sakura Bloom</h4>
            <p>Rp.5.000</p>
            <button id="btnAwal4" onclick="tampilkanInput('qtyBox4', 'btnAwal4')" class="btn">🛒 Tambah ke Keranjang</button>
            <div id="qtyBox4" class="qty-container">
                <label>Jumlah:</label>
                <input type="number" id="qty4" value="1" min="1">
                <button onclick="masukkanKeranjang('Single Sakura Bloom', 'qty4')" class="btn">Masukkan Keranjang</button>
            </div>
        </div>

        <!-- Produk 5 -->
        <div class="product-card">
            <img src="img/5.jpeg" alt="Galeri 5">
            <h4>Single Tulip Bloom</h4>
            <p>Rp.12.000</p>
            <button id="btnAwal5" onclick="tampilkanInput('qtyBox5', 'btnAwal5')" class="btn">🛒 Tambah ke Keranjang</button>
            <div id="qtyBox5" class="qty-container">
                <label>Jumlah:</label>
                <input type="number" id="qty5" value="1" min="1">
                <button onclick="masukkanKeranjang('Single Tulip Bloom', 'qty5')" class="btn">Masukkan Keranjang</button>
            </div>
        </div>

        <!-- Produk 6 -->
        <div class="product-card">
            <img src="img/6.jpeg" alt="Galeri 6">
            <h4>Lavender & Mini Bloom Stem</h4>
            <p>Rp.5.000</p>
            <button id="btnAwal6" onclick="tampilkanInput('qtyBox6', 'btnAwal6')" class="btn">🛒 Tambah ke Keranjang</button>
            <div id="qtyBox6" class="qty-container">
                <label>Jumlah:</label>
                <input type="number" id="qty6" value="1" min="1">
                <button onclick="masukkanKeranjang('Lavender & Mini Bloom Stem', 'qty6')" class="btn">Masukkan Keranjang</button>
            </div>
        </div>

        <!-- Produk 7 -->
        <div class="product-card">
            <img src="img/7.jpeg" alt="Galeri 7">
            <h4>Lily Heart Bouquet</h4>
            <p>Kustom Buket Sesuai Keinginanmu!
Komposisi bunga, jumlah tangkai, dan warna pada buket ini bersifat kustom. Harga akhir akan disesuaikan dengan total request pesananmu.</p>
            <button id="btnAwal7" onclick="tampilkanInput('qtyBox7', 'btnAwal7')" class="btn">🛒 Tambah ke Keranjang</button>
            <div id="qtyBox7" class="qty-container">
                <label>Jumlah:</label>
                <input type="number" id="qty7" value="1" min="1">
                <button onclick="masukkanKeranjang('Lily Heart Bouquet', 'qty7')" class="btn">Masukkan Keranjang</button>
            </div>
        </div>

        <!-- Produk 8 -->
        <div class="product-card">
            <img src="img/8.jpeg" alt="Galeri 8">
            <h4>Playful Spiral Daisy Bouquet</h4>
            <p>Rp.20.000</p>
            <button id="btnAwal8" onclick="tampilkanInput('qtyBox8', 'btnAwal8')" class="btn">🛒 Tambah ke Keranjang</button>
            <div id="qtyBox8" class="qty-container">
                <label>Jumlah:</label>
                <input type="number" id="qty8" value="1" min="1">
                <button onclick="masukkanKeranjang('Playful Spiral Daisy Bouquet', 'qty8')" class="btn">Masukkan Keranjang</button>
            </div>
        </div>
    </div>
</div>

<footer>
    <p>&copy; 2026 ⊹₊˚‧︵‿₊୨RYACREAFT୧₊‿︵‧˚₊⊹ Made with 🌷 and Pastel Colors.</p>
</footer>

</body>
</html>