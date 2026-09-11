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
<<<<<<< HEAD
INSERT INTO produk (nama_produk, kategori, harga, foto, deskripsi) VALUES 
('Pastel Pink Rose Bouquet', 'Mawar', 150000, 'https://pin.it/64HAF5MEh', 'Buket mawar pink pastel segar dengan bungkus korean paper soft pink.'),
('Lily''s Dream Bouquet', 'Lily', 185000, 'https://pin.it/6kFERGx1V', 'Kombinasi bunga nuansa pink dan putih yang elegan.'),
=======
INSERT INTO produk (nama_produk, kategori, harga, foto, deskripsi) VALUES
('Pastel Pink Rose Bouquet', 'Mawar', 150000, 'https://pin.it/64HAF5MEh', 'Buket mawar pink pastel segar dengan bungkus korean paper soft pink.'),
('Lily's Dream Bouquet', 'Lily', 185000, 'https://pin.it/6kFERGx1V', 'Kombinasi bunga nuansa pink dan putih yang elegan.'),
>>>>>>> fadd5b38d90cbdd84608751f446263b9bdba0d67
('Rainbow Blossom', 'Thumbelina', 210000, 'https://pin.it/1vT8LTPsm', 'Buket dari variasi bunga yang penuh warna.');