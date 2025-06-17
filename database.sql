-- Costonomy Database Schema
-- Buat database jika belum ada
CREATE DATABASE IF NOT EXISTS costonomy_db;
USE costonomy_db;

-- Tabel users: menyimpan data user aplikasi
CREATE TABLE IF NOT EXISTS users (
    id INT AUTO_INCREMENT PRIMARY KEY, -- Primary key user
    email VARCHAR(255) NOT NULL UNIQUE, -- Email unik user
    password VARCHAR(255) NOT NULL, -- Password user (hash)
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP, -- Waktu registrasi
    name VARCHAR(100), -- Nama user
    monthly_income DECIMAL(12,2) DEFAULT 0 -- Pemasukan bulanan user
);

-- Tabel budgets: menyimpan anggaran per kategori per user
CREATE TABLE IF NOT EXISTS budgets (
    id INT AUTO_INCREMENT PRIMARY KEY, -- Primary key budget
    user_id INT NOT NULL, -- Relasi ke user
    category VARCHAR(100) NOT NULL, -- Nama kategori
    amount DECIMAL(10,2) NOT NULL, -- Jumlah anggaran
    enabled BOOLEAN DEFAULT TRUE, -- Status aktif/tidak kategori
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP, -- Waktu input
    chart_data JSON, -- Data diagram (warna, persentase, dsb)
    FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE -- Relasi ke users
);

-- Tabel categories: daftar kategori per user
CREATE TABLE IF NOT EXISTS categories (
    id INT AUTO_INCREMENT PRIMARY KEY, -- Primary key kategori
    user_id INT NOT NULL, -- Relasi ke user
    name VARCHAR(100) NOT NULL, -- Nama kategori
    enabled BOOLEAN DEFAULT TRUE, -- Status aktif/tidak kategori
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP, -- Waktu input
    FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE -- Relasi ke users
); 