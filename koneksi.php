<?php
$host = "localhost";
$user = "root";
$pass = "";
$db   = "db_toko_bunga";

$koneksi = mysqli_connect($host, $user, $pass, $db);

// Menghitung total pelanggan (mengabaikan admin, atau menangani role yang kosong)
$sql_users = mysqli_query($koneksi, "SELECT COUNT(*) as total FROM users WHERE role != 'admin' OR role IS NULL");
$data_users = mysqli_fetch_assoc($sql_users);
$total_users = $data_users['total'];

// 2. Hitung Total Produk
$sql_produk = mysqli_query($koneksi, "SELECT COUNT(*) as total FROM produk");
$data_produk = mysqli_fetch_assoc($sql_produk);
$total_produk = $data_produk['total'];

// 3. Hitung Pesanan Baru (berdasarkan status)
$sql_pesanan = mysqli_query($koneksi, "SELECT COUNT(*) as total FROM pesanan WHERE status = 'Menunggu'");
$data_pesanan = mysqli_fetch_assoc($sql_pesanan);
$total_pesanan = $data_pesanan['total'];

if (!$koneksi) {
    die("Koneksi database gagal: " . mysqli_connect_error());
}
?>