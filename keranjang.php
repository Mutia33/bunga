<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Keranjang Belanja - RYACREAFT</title>
    <link rel="stylesheet" href="style.css">
    <style>
        .container-keranjang {
            max-width: 900px;
            margin: 40px auto;
            background: white;
            padding: 20px;
            border-radius: 10px;
            box-shadow: 0 4px 8px rgba(0,0,0,0.1);
        }
        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 20px;
        }
        table, th, td {
            border: 1px solid #ddd;
        }
        th, td {
            padding: 12px;
            text-align: center;
        }
        th {
            background-color: #f4f4f4;
            color: #333;
        }
        .btn-hapus {
            background-color: #ff4d4d;
            color: white;
            border: none;
            padding: 6px 12px;
            border-radius: 4px;
            cursor: pointer;
        }
        .btn-edit {
            background-color: #ffa500;
            color: white;
            border: none;
            padding: 6px 12px;
            border-radius: 4px;
            cursor: pointer;
            margin-right: 5px;
        }
        .input-qty {
            width: 50px;
            text-align: center;
            padding: 4px;
        }
        .checkout-container {
            margin-top: 20px;
            text-align: right;
        }
        .btn-beli {
            background-color: #d63384;
            color: white;
            border: none;
            padding: 10px 20px;
            font-size: 16px;
            border-radius: 5px;
            cursor: pointer;
            font-weight: bold;
        }
        .btn-beli:hover {
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

<div class="container-keranjang">
    <h2>🛒 Keranjang Belanja Kamu</h2>
    
    <p id="keranjang-kosong" style="text-align: center; color: #777; margin-top: 20px; display: none;">
        Keranjang kamu masih kosong. Yuk pilih buket favoritmu di <a href="galeri.php" style="color: #d63384;">Galeri Buket</a>!
    </p>

    <div id="tabel-container" style="display: none;">
        <table>
            <thead>
                <tr>
                    <th>Pilih</th>
                    <th>No</th>
                    <th>Nama Produk</th>
                    <th>Jumlah (Qty)</th>
                    <th>Aksi</th>
                </tr>
            </thead>
            <tbody id="tabel-keranjang">
                <!-- Data produk dimuat otomatis via JS -->
            </tbody>
        </table>
        
        <div class="checkout-container">
            <button class="btn-beli" onclick="prosesBeli()">🛍️ Beli Sekarang</button>
        </div>
    </div>
</div>

<footer>
    <p>&copy; 2026 ⊹₊˚‧︵‿₊୨RYACREAFT୧₊‿︵‧˚₊⊹ Made with 🌷 and Pastel Colors.</p>
</footer>

<script>
    window.onload = function() {
        muatKeranjang();
    };

    function muatKeranjang() {
        let keranjangData = localStorage.getItem('keranjang_shoope');
        let tbody = document.getElementById('tabel-keranjang');
        let kosongP = document.getElementById('keranjang-kosong');
        let tabelContainer = document.getElementById('tabel-container');

        tbody.innerHTML = "";

        if (!keranjangData || keranjangData === "{}") {
            kosongP.style.display = "block";
            tabelContainer.style.display = "none";
        } else {
            kosongP.style.display = "none";
            tabelContainer.style.display = "block";

            let keranjang = JSON.parse(keranjangData);
            let no = 1;

            for (let produk in keranjang) {
                let qty = keranjang[produk];
                let tr = document.createElement('tr');
                tr.innerHTML = `
                    <td><input type="checkbox" class="pilih-produk" value="${produk}" data-qty="${qty}"></td>
                    <td>${no++}</td>
                    <td><a href="galeri.php" style="color: #d63384; text-decoration: none; font-weight: bold;">${produk}</a></td>
                    <td><input type="number" id="qty_${produk}" class="input-qty" value="${qty}" min="1"></td>
                    <td>
                        <button class="btn-edit" onclick="updateQty('${produk}')">Edit</button>
                        <button class="btn-hapus" onclick="hapusItem('${produk}')">Hapus</button>
                    </td>
                `;
                tbody.appendChild(tr);
            }
        }
    }

    function updateQty(produk) {
        let inputBaru = document.getElementById(`qty_${produk}`);
        let qtyBaru = parseInt(inputBaru.value);

        if (qtyBaru > 0) {
            let keranjang = JSON.parse(localStorage.getItem('keranjang_shoope')) || {};
            keranjang[produk] = qtyBaru;
            localStorage.setItem('keranjang_shoope', JSON.stringify(keranjang));
            alert("Jumlah produk berhasil diubah!");
            muatKeranjang();
        } else {
            alert("Jumlah minimal adalah 1!");
        }
    }

    function hapusItem(produk) {
        if (confirm(`Yakin ingin menghapus ${produk} dari keranjang?`)) {
            let keranjang = JSON.parse(localStorage.getItem('keranjang_shoope')) || {};
            delete keranjang[produk];
            localStorage.setItem('keranjang_shoope', JSON.stringify(keranjang));
            muatKeranjang();
        }
    }

    function prosesBeli() {
        // Ambil semua checkbox yang dicentang
        let checkboxes = document.querySelectorAll('.pilih-produk:checked');
        
        if (checkboxes.length === 0) {
            alert("Pilih dulu produk yang mau dibeli dengan mencentang kotak di sebelah kiri!");
            return;
        }

        let itemDipilih = {};
        checkboxes.forEach((cb) => {
            let namaProduk = cb.value;
            let qty = document.getElementById(`qty_${namaProduk}`).value;
            itemDipilih[namaProduk] = qty;
        });

        // Simpan sementara item yang dipilih untuk checkout
        localStorage.setItem('checkout_item', JSON.stringify(itemDipilih));

        // Cek status login
        let statusLogin = localStorage.getItem('isLoggedIn'); 

        if (statusLogin !== "true") {
            alert("Kamu harus login terlebih dahulu untuk melanjutkan pembelian!");
            window.location.href = "login.php"; 
        } else {
            window.location.href = "checkout.php";
        }
    }
</script>

</body>
</html>