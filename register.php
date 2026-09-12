<?php
session_start();
include 'koneksi.php';

$error = "";
$sukses = "";

if (isset($_POST['register'])) {
    $nama     = mysqli_real_escape_string($koneksi, $_POST['nama']);
    $email    = mysqli_real_escape_string($koneksi, $_POST['email']);
    $password = $_POST['password'];
    $no_hp    = mysqli_real_escape_string($koneksi, $_POST['no_hp']);
    $alamat   = mysqli_real_escape_string($koneksi, $_POST['alamat']);

    // Cek apakah email sudah terdaftar sebelumnya
    $cek_email = mysqli_query($koneksi, "SELECT * FROM users WHERE email = '$email'");
    if (mysqli_num_rows($cek_email) > 0) {
        $error = "Email sudah terdaftar, gunakan email lain!";
    } else {
        // Masukkan data pengguna baru ke database (role otomatis 'user')
        $query = "INSERT INTO users (nama, email, password, no_hp, alamat, role) 
                  VALUES ('$nama', '$email', '$password', '$no_hp', '$alamat', 'user')";
        
        if (mysqli_query($koneksi, $query)) {
            $sukses = "Pendaftaran berhasil! Silakan login.";
        } else {
            $error = "Gagal mendaftar, coba lagi nanti!";
        }
    }
}
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Daftar Akun - Bloom & Co.</title>
    <!-- Sesuaikan lokasi file style.css kamu di bawah ini -->
    <link rel="stylesheet" href="style.css">
</head>
<body>

<div class="register-wrapper">
    <div class="register-box">
        <h2>🌸 Daftar Akun 🌸</h2>

        <?php if ($error != ""): ?>
            <div class="error-msg"><?php echo $error; ?></div>
        <?php endif; ?>

        <?php if ($sukses != ""): ?>
            <div class="success-msg"><?php echo $sukses; ?></div>
        <?php endif; ?>

        <form action="" method="POST">
            <div class="form-group">
                <label>Nama Lengkap</label>
                <input type="text" name="nama" placeholder="Masukkan nama lengkap..." required>
            </div>

            <div class="form-group">
                <label>Email</label>
                <input type="email" name="email" placeholder="Masukkan email..." required>
            </div>

            <div class="form-group">
                <label>Password</label>
                <input type="password" name="password" placeholder="Buat password..." required>
            </div>

            <div class="form-group">
                <label>No. HP / WhatsApp</label>
                <input type="text" name="no_hp" placeholder="Contoh: 08123456789" required>
            </div>

            <div class="form-group">
                <label>Alamat Lengkap</label>
                <textarea name="alamat" rows="3" placeholder="Masukkan alamat pengiriman..." required></textarea>
            </div>

            <button type="submit" name="register" class="btn-login-submit">Daftar Sekarang</button>
        </form>

        <div class="footer-text">
            Sudah punya akun? <a href="login.php">Login di sini</a>
        </div>
        <a href="index.php" class="btn-back">Kembali ke Beranda</a>
        <div class="clear"></div>
    </div>
</div>

</body>
</html>