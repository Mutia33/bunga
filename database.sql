CREATE DATABASE db_toko_bunga;
USE db_toko_bunga;

CREATE TABLE produk (
    id INT AUTO_INCREMENT PRIMARY KEY,
    nama_produk VARCHAR(100) NOT NULL,
    kategori VARCHAR(50) NOT NULL,
    harga DECIMAL(10,2) NOT NULL,
    foto VARCHAR(255) NOT NULL,
    deskripsi TEXT
);

-- Masukkan contoh data produk bunga
INSERT INTO produk (nama_produk, kategori, harga, foto, deskripsi) VALUES 
('Pastel Pink Rose Bouquet', 'Mawar', 150000, 'https://pin.it/64HAF5MEh', 'Buket mawar pink pastel segar dengan bungkus korean paper soft pink.'),
('Lily''s Dream Bouquet', 'Lily', 185000, 'https://pin.it/6kFERGx1V', 'Kombinasi bunga nuansa pink dan putih yang elegan.'),
('Rainbow Blossom', 'Thumbelina', 210000, 'https://pin.it/1vT8LTPsm', 'Buket dari variasi bunga yang penuh warna.');

-- Tambahan Tabel Users untuk Authentication
CREATE TABLE IF NOT EXISTS users (
    id_user INT AUTO_INCREMENT PRIMARY KEY,
    nama VARCHAR(100) NOT NULL,
    email VARCHAR(100) NOT NULL UNIQUE,
    password VARCHAR(255) NOT NULL,
    no_hp VARCHAR(20),
    alamat TEXT,
    role ENUM('admin', 'user') DEFAULT 'user',
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

INSERT INTO users (nama, email, password, no_hp, role) VALUES
('Admin Toko', 'admin@tokobunga.com', 'admin123', '08123456789', 'admin'),
('Siti Pembeli', 'siti@gmail.com', 'user123', '08987654321', 'user')
ON DUPLICATE KEY UPDATE id_user=id_user;