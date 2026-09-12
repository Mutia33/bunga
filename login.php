<?php
session_start();
include 'koneksi.php';

$error = "";

if (isset($_POST['login'])) {
    $username = mysqli_real_escape_string($koneksi, $_POST['username']);
    $password = $_POST['password'];

    // Cari ke kolom email atau nama sesuai database
    $query = mysqli_query($koneksi, "SELECT * FROM users WHERE email = '$username' OR nama = '$username'");
    
    if ($query && mysqli_num_rows($query) === 1) {
        $row = mysqli_fetch_assoc($query);
        
        // Verifikasi password (aman untuk hash ataupun teks biasa)
        if (password_verify($password, $row['password']) || $password === $row['password']) {
            $_SESSION['login'] = true;
            $_SESSION['id_user'] = $row['id_user'];
            $_SESSION['nama'] = $row['nama'];
            $_SESSION['role'] = $row['role'];

            // Simpan penanda ke localStorage lewat JavaScript untuk sinkronisasi cart/checkout
            echo "<script>
                localStorage.setItem('isLoggedIn', 'true');
            </script>";

            if ($row['role'] === 'admin') {
                echo "<script>window.location.href = 'admin/index.php';</script>";
            } else {
                echo "<script>window.location.href = 'index.php';</script>";
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

    <div class="login-wrapper">
        <div class="login-box">
            <h2>🌸 Bloom & Co. 🌸</h2>

            <?php if (!empty($error)): ?>
                <div class="error-msg">
                    <?php echo $error; ?>
                </div>
            <?php endif; ?>

            <form action="login.php" method="POST">
                <div class="form-group">
                    <label>Username / Email</label>
                    <input type="text" name="username" required placeholder="Masukkan username atau email">
                </div>

                <div class="form-group">
                    <label>Password</label>
                    <input type="password" name="password" required placeholder="Masukkan password">
                </div>

                <button type="submit" name="login" class="btn-login-submit">Masuk</button>
            </form>

            <div class="footer-text">
                Belum punya akun? <a href="register.php">Daftar di sini</a>
            </div>
            
            <a href="index.php" class="btn-back">&larr; Kembali ke Beranda</a>
        </div>
    </div>

</body>
</html>