CREATE DATABASE arsip_surat;
USE arsip_surat;

-- Tabel Users
CREATE TABLE users (
    id INT AUTO_INCREMENT PRIMARY KEY,
    username VARCHAR(50),
    password VARCHAR(255)
);

-- Tambahkan user admin
INSERT INTO users (username, password) VALUES ('admin', MD5('admin123'));

-- Tabel Surat (dengan kolom departemen)
CREATE TABLE surat (
    id INT AUTO_INCREMENT PRIMARY KEY,
    nomor VARCHAR(50),
    tanggal DATE,
    pengirim VARCHAR(100),
    perihal VARCHAR(255),
    jenis ENUM('Masuk', 'Keluar'),
    departemen VARCHAR(50) NOT NULL,
    file VARCHAR(255)
);
