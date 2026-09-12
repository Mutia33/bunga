<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Checkout - Bloom & Co.</title>
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
    </style>
</head>
<body>

<header>
    <h1>⊹₊˚‧︵‿₊୨RYACREAFT୧₊‿︵‧˚₊⊹</h1>
    <nav>
        <a href="index.php">Home</a>
        <a href="galeri.php">Galeri Buket</a>
        <a href="keranjang.php">Keranjang</a>
        <a href="login.php">Login/Account</a>
    </nav>
</header>

<div class="container-checkout">
    <h2>📋 Formulir Pengiriman & Checkout</h2>
    <p style="color: #666; margin-bottom: 20px;">Silakan lengkapi data di bawah ini untuk memproses pesanan buket cantikmu!</p>

    <!-- Ringkasan Produk yang dibeli -->
    <div class="ringkasan-belanja">
        <h3>🛍️ Produk yang Dipilih:</h3>
        <ul id="list-ringkasan" style="margin: 0; padding-left: 20px; color: #444;">
            <!-- Dimuat otomatis via JavaScript -->
        </ul>
    </div>

    <!-- Form Data Pengiriman -->
    <form onsubmit="selesaiCheckout(event)">
        <div class="form-group">
            <label for="nama">Nama Lengkap Penerima:</label>
            <input type="text" id="nama" required placeholder="Masukkan nama lengkap kamu">
        </div>

        <div class="form-group">
            <label for="hp">Nomor HP / WhatsApp:</label>
            <input type="tel" id="hp" required placeholder="Contoh: 081234567890">
        </div>

        <div class="form-group">
            <label for="email">Email:</label>
            <input type="email" id="email" required placeholder="Contoh: emailkamu@gmail.com">
        </div>

        <div class="form-group">
            <label for="alamat">Alamat Lengkap Pengiriman:</label>
            <textarea id="alamat" required placeholder="Nama jalan, nomor rumah, RT/RW, kecamatan, kota..."></textarea>
        </div>

        <div class="form-group">
            <label for="catatan">Catatan Tambahan (Opsional):</label>
            <textarea id="catatan" placeholder="Contoh: Kartu ucapan: 'Happy Graduation Rya!' atau warna pita pink"></textarea>
        </div>

        <button type="submit" class="btn-konfirmasi">✨ Konfirmasi Pesanan Sekarang</button>
    </form>
</div>

<footer>
    <p>&copy; 2026 ⊹₊˚‧︵‿₊୨RYACREAFT୧₊‿︵‧˚₊⊹ Made with 🌷 and Pastel Colors.</p>
</footer>

<script>
    window.onload = function() {
        muatRingkasan();
    };

    function muatRingkasan() {
        let checkoutItemData = localStorage.getItem('checkout_item');
        let listRingkasan = document.getElementById('list-ringkasan');

        if (!checkoutItemData) {
            listRingkasan.innerHTML = "<li>Tidak ada produk yang dipilih.</li>";
            return;
        }

        let itemDipilih = JSON.parse(checkoutItemData);
        listRingkasan.innerHTML = "";

        for (let produk in itemDipilih) {
            let qty = itemDipilih[produk];
            let li = document.createElement('li');
            li.textContent = `${produk} (Jumlah: ${qty})`;
            listRingkasan.appendChild(li);
        }
    }

    function selesaiCheckout(event) {
        event.preventDefault(); // Mencegah form reload halaman

        let nama = document.getElementById('nama').value;
        let hp = document.getElementById('hp').value;
        let alamat = document.getElementById('alamat').value;

        alert(`Terima kasih ${nama}! Pesanan buket kamu berhasil dibuat dan akan segera dikirim ke alamat: ${alamat}. Kami akan menghubungi nomor ${hp} untuk konfirmasi pembayaran.`);

        // Bersihkan keranjang belanja setelah checkout sukses
        localStorage.removeItem('keranjang_shoope');
        localStorage.removeItem('checkout_item');

        // Kembalikan ke halaman utama
        window.location.href = "index.php";
    }
</script>

</body>
</html>