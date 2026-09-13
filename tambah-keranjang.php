<?php
session_start();
include 'koneksi.php';

// Cek apakah user sudah login (sesuaikan session login di aplikasimu)
if (!isset($_SESSION['id_user'])) {
    echo json_encode(['status' => 'unauthorized']);
    exit();
}

if (isset($_POST['nama_produk']) && isset($_POST['harga']) && isset($_POST['qty'])) {
    $id_user = $_SESSION['id_user'];
    $nama_produk = mysqli_real_escape_string($koneksi, $_POST['nama_produk']);
    $harga = (int)$_POST['harga'];
    $qty = (int)$_POST['qty'];

    // Cek apakah produk yang sama sudah ada di keranjang user
    $cek = mysqli_query($koneksi, "SELECT * FROM keranjang WHERE id_user = '$id_user' AND nama_produk = '$nama_produk'");
    
    if (mysqli_num_rows($cek) > 0) {
        // Jika sudah ada, update jumlahnya (qty) saja
        mysqli_query($koneksi, "UPDATE keranjang SET qty = qty + $qty WHERE id_user = '$id_user' AND nama_produk = '$nama_produk'");
    } else {
        // Jika belum ada, masukkan sebagai data baru
        mysqli_query($koneksi, "INSERT INTO keranjang (id_user, nama_produk, harga, qty) VALUES ('$id_user', '$nama_produk', '$harga', '$qty')");
    }

    echo json_encode(['status' => 'success']);
}
?>