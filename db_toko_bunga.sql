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