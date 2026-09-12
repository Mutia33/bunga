<?php
session_start();
include 'koneksi.php';

$error = "";

if (isset($_POST['login'])) {
    $username = mysqli_real_escape_string($koneksi, $_POST['username']);
    $password = $_POST['password'];

    // Cari ke kolom email atau nama (karena kolom username tidak ada di database)
    $query = mysqli_query($koneksi, "SELECT * FROM users WHERE email = '$username' OR nama = '$username'");
    
    if ($query && mysqli_num_rows($query) === 1) {
        $row = mysqli_fetch_assoc($query);
        
        // Cek password (bisa verifikasi hash atau teks biasa)
        if (password_verify($password, $row['password']) || $password === $row['password']) {
            $_SESSION['login'] = true;
            $_SESSION['id_user'] = $row['id_user']; // Menggunakan id_user sesuai database
            $_SESSION['nama'] = $row['nama'];
            $_SESSION['role'] = $row['role'];

            if ($row['role'] === 'admin') {
                header("Location: admin/index.php");
            } else {
                header("Location: index.php");
            }
            exit();
        } else {
            $error = "Password yang kamu masukkan salah!";
        }
    } else {
        $error = "Email atau Nama tidak ditemukan!";
    }
}
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login - Bloom & Co.</title>
    <link rel="stylesheet" href="style.css">
</head>
<body class="login-body">

    <div class="login-card">
        <h2>Selamat Datang 🌸</h2>

        <?php if (!empty($error)): ?>
            <p class="login-error-msg">
                <?php echo $error; ?>
            </p>
        <?php endif; ?>

        <form action="login.php" method="POST">
            <div class="form-group-login">
                <label>Username / Email</label>
                <input type="text" name="username" required placeholder="Masukkan username atau email">
            </div>

            <div class="form-group-login">
                <label>Password</label>
                <input type="password" name="password" required placeholder="Masukkan password">
            </div>

            <button type="submit" name="login" class="btn-submit-login">Masuk</button>
        </form>

        <div class="register-link">
            Belum punya akun? <a href="register.php">Daftar di sini</a>
            <a href="index.php" class="btn-back-home">
                &larr; Kembali ke Beranda
            </a>
        </div>
    </div>

</body>
</html>