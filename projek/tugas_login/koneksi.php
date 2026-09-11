<?php
$host = "sql109.infinityfree.com";
$user = "if0_42563162";
$pass = "MASUKKAN_PASSWORD_INFINITYFREE_KAMU_DISINI";
$db   = "if0_42563162_2526_14db";

$koneksi = mysqli_connect($host, $user, $pass, $db);

// Cek koneksi
if (!$koneksi) {
    die("Koneksi database gagal: " . mysqli_connect_error());
}
?>