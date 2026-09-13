<?php
session_start();
include 'koneksi.php';

if (!isset($_SESSION['id_user'])) {
    echo "<script>alert('Silakan login terlebih dahulu!'); window.location.href = 'login.php';</script>";
    exit();
}

if (isset($_POST['checkout_btn'])) {
    $id_user       = $_SESSION['id_user'];
    $nama_pemesan  = mysqli_real_escape_string($koneksi, $_POST['nama_pemesan']);
    $no_hp         = mysqli_real_escape_string($koneksi, $_POST['no_hp']);
    $email         = mysqli_real_escape_string($koneksi, $_POST['email']);
    $alamat        = mysqli_real_escape_string($koneksi, $_POST['alamat']);
    $catatan       = mysqli_real_escape_string($koneksi, $_POST['catatan']);
    $status        = "Menunggu";

    if (!isset($_POST['pilih_item']) || empty($_POST['pilih_item'])) {
        echo "<script>alert('Pilih minimal 1 produk di keranjang untuk checkout!'); window.location.href = 'keranjang.php';</script>";
        exit();
    }

    $selected_ids = $_POST['pilih_item'];
    $total_harga_semua = 0;
    $detail_pesanan_arr = array();

    foreach ($selected_ids as $id_keranjang) {
        $id_keranjang = mysqli_real_escape_string($koneksi, $id_keranjang);
        $query_k = mysqli_query($koneksi, "SELECT * FROM keranjang WHERE id_keranjang = '$id_keranjang' AND id_user = '$id_user'");
        
        if ($row_k = mysqli_fetch_assoc($query_k)) {
            $subtotal = $row_k['harga'] * $row_k['qty'];
            $total_harga_semua += $subtotal;
            $detail_pesanan_arr[] = $row_k['nama_produk'] . " (Jumlah: " . $row_k['qty'] . ")";
        }
    }

    $detail_produk_str = implode(", ", $detail_pesanan_arr);

    $query_pesanan = "INSERT INTO pesanan (id_user, nama_pemesan, no_hp, email, alamat, catatan, detail_produk, total_harga, status) 
                      VALUES ('$id_user', '$nama_pemesan', '$no_hp', '$email', '$alamat', '$catatan', '$detail_produk_str', '$total_harga_semua', '$status')";
    
    $result_pesanan = mysqli_query($koneksi, $query_pesanan);

    if ($result_pesanan) {
        foreach ($selected_ids as $id_keranjang) {
            mysqli_query($koneksi, "DELETE FROM keranjang WHERE id_keranjang = '$id_keranjang'");
        }

        echo "<script>
                localStorage.removeItem('checkout_item');
                localStorage.removeItem('keranjang_shoope');
                alert('Pesanan berhasil dibuat!');
                window.location.href = 'profil.php';
              </script>";
        exit();
    } else {
        $error_pesan = mysqli_error($koneksi);
        echo "<script>
                alert('Gagal memproses pesanan: " . addslashes($error_pesan) . "');
                window.location.href = 'checkout.php';
              </script>";
        exit();
    }
}
?>