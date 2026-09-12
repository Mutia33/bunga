<?php
session_start();
// Cek proteksi halaman admin
if (!isset($_SESSION['login']) || $_SESSION['role'] !== 'admin') {
    header("Location: ../login.php");
    exit();
}
echo "<h1>Halo Admin, " . $_SESSION['nama'] . "!</h1>";
echo "<p>Selamat datang di Dashboard Admin Toko Bunga.</p>";
echo "<a href='../logout.php'>Logout</a>";
?>