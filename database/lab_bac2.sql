-- ============================================================
-- LAB BAC #2 - Broken Access Control (Tugas Kuliah)
-- Nama: kikikokok
-- Database: lab_bac2
-- CATATAN: khusus praktikum belajar. BUKAN untuk produksi.
-- ============================================================

DROP DATABASE IF EXISTS lab_bac2;
CREATE DATABASE lab_bac2 CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
USE lab_bac2;

CREATE TABLE users (
  id INT AUTO_INCREMENT PRIMARY KEY,
  username VARCHAR(50) NOT NULL UNIQUE,
  password VARCHAR(255) NOT NULL,
  nama VARCHAR(100) NOT NULL,
  role ENUM('admin','staff','pelanggan') NOT NULL DEFAULT 'pelanggan',
  telepon VARCHAR(20),
  alamat VARCHAR(200)
) ENGINE=InnoDB;

CREATE TABLE faktur (
  id INT AUTO_INCREMENT PRIMARY KEY,
  user_id INT NOT NULL,
  kode VARCHAR(20) NOT NULL,
  total INT NOT NULL,
  alamat_kirim VARCHAR(200),
  status ENUM('belum','kirim','lunas') NOT NULL DEFAULT 'belum',
  FOREIGN KEY (user_id) REFERENCES users(id)
) ENGINE=InnoDB;

INSERT INTO users (id, username, password, nama, role, telepon, alamat) VALUES
(1,'admin','admin123','Rizky Admin','admin','081200000001','Jl. Merdeka 1'),
(2,'staff','staff123','Sari Staf','staff','081200000002','Jl. Melati 8'),
(3,'budi','budi123','Budi Santoso','pelanggan','081200000003','Jl. Mawar 3'),
(4,'sari','sari123','Sari Melati','pelanggan','081200000004','Jl. Kenanga 7'),
(5,'riko','riko123','Riko Pratama','pelanggan','081200000005','Jl. Anggrek 12');

INSERT INTO faktur (id, user_id, kode, total, alamat_kirim, status) VALUES
(1,3,'FK-1001',178000,'Jl. Mawar 3','lunas'),
(2,4,'FK-1002',45000, 'Jl. Kenanga 7','kirim'),
(3,3,'FK-1003',92000, 'Jl. Mawar 3','belum'),
(4,5,'FK-1004',120000,'Jl. Anggrek 12','belum'),
(5,5,'FK-1005',64000, 'Jl. Anggrek 12','kirim');