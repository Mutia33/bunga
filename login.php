<<<<<<< HEAD
<?php include 'koneksi.php'; ?>
=======
<?php
session_start();
include 'koneksi.php';

$error = "";

if (isset($_POST['login'])) {
    $email    = mysqli_real_escape_string($koneksi, $_POST['email']);
    $password = $_POST['password'];

    $query  = "SELECT * FROM users WHERE email = '$email'";
    $result = mysqli_query($koneksi, $query);

    if (mysqli_num_rows($result) === 1) {
        $row = mysqli_fetch_assoc($result);

        if ($password === $row['password']) {
            $_SESSION['login']    = true;
            $_SESSION['id_user']  = $row['id_user'];
            $_SESSION['nama']     = $row['nama'];
            $_SESSION['role']     = $row['role'];

            if ($row['role'] === 'admin') {
                header("Location: admin/index.php");
                exit();
            } else {
                if (isset($_GET['redirect']) && $_GET['redirect'] === 'checkout') {
                    header("Location: checkout.php");
                } else {
                    header("Location: index.php");
                }
                exit();
            }
        } else {
            $error = "Password yang kamu masukkan salah!";
        }
    } else {
        $error = "Email tidak terdaftar!";
    }
}
?>

>>>>>>> 61b8eeb069bf336b62367e1ca039f4c121d4558d
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
<<<<<<< HEAD
    <title>Jenis-Jenis Bunga - Bloom & Co.</title>
=======
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login - Bloom & Co.</title>
    <!-- Sesuaikan lokasi file style.css kamu di bawah ini -->
>>>>>>> 61b8eeb069bf336b62367e1ca039f4c121d4558d
    <link rel="stylesheet" href="style.css">
</head>
<body>

<<<<<<< HEAD
<header>
    <h1>⊹₊˚‧︵‿₊୨RYACREAFT୧₊‿︵‧˚₊⊹</h1>
    <nav>
        <a href="index.php">Home</a>
        <a href="galeri.php">Galeri Buket</a>
        <a href="keranjang.php">Keranjang</a>
        <a href="login.php">Login/Account</a>
    </nav>
</header>
<footer>
    <p>&copy; 2026 ⊹₊˚‧︵‿₊୨RYACREAFT୧₊‿︵‧˚₊⊹ Made with 🌷 and Pastel Colors.</p>
</footer>
=======
<div class="login-wrapper">
    <div class="login-box">
        <h2>🌸 Bloom & Co. 🌸</h2>

        <?php if ($error != ""): ?>
            <div class="error-msg"><?php echo $error; ?></div>
        <?php endif; ?>

        <form action="" method="POST">
            <div class="form-group">
                <label>Email</label>
                <input type="email" name="email" placeholder="Masukkan email kamu..." required>
            </div>

            <div class="form-group">
                <label>Password</label>
                <input type="password" name="password" placeholder="Masukkan password..." required>
            </div>

            <button type="submit" name="login" class="btn-login-submit">Masuk Sekarang</button>
        </form>

        <div class="footer-text">
            Belum punya akun? <a href="register.php">Daftar di sini</a>
        </div>
         <a href="index.php" class="btn-back">Kembali ke Beranda</a>
        <div class="clear"></div>
    </div>
</div>

>>>>>>> 61b8eeb069bf336b62367e1ca039f4c121d4558d
</body>
</html>