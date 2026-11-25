-- =====================================================================
-- SCHOOLCARE - SISTEM PENGADUAN SARANA DAN PRASARANA SEKOLAH
-- Skrip Database MySQL: db_schoolcare
-- Standar Uji Kompetensi Keahlian (UKK) Rekayasa Perangkat Lunak
-- Framework: Laravel 12 & PHP 8.2
-- =====================================================================

CREATE DATABASE IF NOT EXISTS `db_schoolcare` CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
USE `db_schoolcare`;

SET FOREIGN_KEY_CHECKS = 0;

-- ---------------------------------------------------------------------
-- 1. Tabel users
-- ---------------------------------------------------------------------
DROP TABLE IF EXISTS `users`;
CREATE TABLE `users` (
  `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT,
  `name` varchar(255) NOT NULL,
  `email` varchar(255) NOT NULL,
  `role` varchar(50) NOT NULL DEFAULT 'siswa',
  `nisn` varchar(50) DEFAULT NULL,
  `kelas` varchar(50) DEFAULT NULL,
  `phone` varchar(50) DEFAULT NULL,
  `avatar` varchar(255) DEFAULT NULL,
  `email_verified_at` timestamp NULL DEFAULT NULL,
  `password` varchar(255) NOT NULL,
  `remember_token` varchar(100) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `users_email_unique` (`email`),
  UNIQUE KEY `users_nisn_unique` (`nisn`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ---------------------------------------------------------------------
-- 2. Tabel categories
-- ---------------------------------------------------------------------
DROP TABLE IF EXISTS `categories`;
CREATE TABLE `categories` (
  `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT,
  `name` varchar(255) NOT NULL,
  `slug` varchar(255) NOT NULL,
  `description` text DEFAULT NULL,
  `icon` varchar(255) DEFAULT NULL,
  `is_active` tinyint(1) NOT NULL DEFAULT 1,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `categories_name_unique` (`name`),
  UNIQUE KEY `categories_slug_unique` (`slug`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ---------------------------------------------------------------------
-- 3. Tabel aspirations
-- ---------------------------------------------------------------------
DROP TABLE IF EXISTS `aspirations`;
CREATE TABLE `aspirations` (
  `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT,
  `ticket_number` varchar(255) NOT NULL,
  `user_id` bigint(20) UNSIGNED NOT NULL,
  `category_id` bigint(20) UNSIGNED NOT NULL,
  `title` varchar(255) NOT NULL,
  `location` varchar(255) NOT NULL,
  `description` text NOT NULL,
  `photo` varchar(255) DEFAULT NULL,
  `status` enum('Menunggu','Diproses','Selesai','Ditolak') NOT NULL DEFAULT 'Menunggu',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `aspirations_ticket_number_unique` (`ticket_number`),
  KEY `aspirations_user_id_foreign` (`user_id`),
  KEY `aspirations_category_id_foreign` (`category_id`),
  CONSTRAINT `aspirations_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE,
  CONSTRAINT `aspirations_category_id_foreign` FOREIGN KEY (`category_id`) REFERENCES `categories` (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ---------------------------------------------------------------------
-- 4. Tabel feedback
-- ---------------------------------------------------------------------
DROP TABLE IF EXISTS `feedback`;
CREATE TABLE `feedback` (
  `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT,
  `aspiration_id` bigint(20) UNSIGNED NOT NULL,
  `user_id` bigint(20) UNSIGNED NOT NULL,
  `response` text NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `feedback_aspiration_id_foreign` (`aspiration_id`),
  KEY `feedback_user_id_foreign` (`user_id`),
  CONSTRAINT `feedback_aspiration_id_foreign` FOREIGN KEY (`aspiration_id`) REFERENCES `aspirations` (`id`) ON DELETE CASCADE,
  CONSTRAINT `feedback_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ---------------------------------------------------------------------
-- 5. Tabel aspiration_status_histories
-- ---------------------------------------------------------------------
DROP TABLE IF EXISTS `aspiration_status_histories`;
CREATE TABLE `aspiration_status_histories` (
  `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT,
  `aspiration_id` bigint(20) UNSIGNED NOT NULL,
  `user_id` bigint(20) UNSIGNED DEFAULT NULL,
  `previous_status` varchar(255) DEFAULT NULL,
  `new_status` varchar(255) NOT NULL,
  `notes` text DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `histories_aspiration_id_foreign` (`aspiration_id`),
  KEY `histories_user_id_foreign` (`user_id`),
  CONSTRAINT `histories_aspiration_id_foreign` FOREIGN KEY (`aspiration_id`) REFERENCES `aspirations` (`id`) ON DELETE CASCADE,
  CONSTRAINT `histories_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE SET NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ---------------------------------------------------------------------
-- 6. Tabel sessions & cache (Laravel default)
-- ---------------------------------------------------------------------
DROP TABLE IF EXISTS `sessions`;
CREATE TABLE `sessions` (
  `id` varchar(255) NOT NULL,
  `user_id` bigint(20) UNSIGNED DEFAULT NULL,
  `ip_address` varchar(45) DEFAULT NULL,
  `user_agent` text DEFAULT NULL,
  `payload` longtext NOT NULL,
  `last_activity` int(11) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `sessions_user_id_index` (`user_id`),
  KEY `sessions_last_activity_index` (`last_activity`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ---------------------------------------------------------------------
-- Data Seeder Default (Bcrypt Password: 'password')
-- ---------------------------------------------------------------------

-- Users
INSERT INTO `users` (`id`, `name`, `email`, `role`, `nisn`, `kelas`, `phone`, `password`, `created_at`, `updated_at`) VALUES
(1, 'Administrator Sarpras', 'admin@schoolcare.sch.id', 'admin', NULL, NULL, '081234567890', '$2y$12$K8/gB4yWqMv0HqjA9e3P4OslzB4qX7Yw7pD2j8s5hL.tX8pM1q7i.', NOW(), NOW()),
(2, 'Ahmad Fauzi', 'ahmad@siswa.schoolcare.sch.id', 'siswa', '20241001', 'XII RPL 1', '089876543210', '$2y$12$K8/gB4yWqMv0HqjA9e3P4OslzB4qX7Yw7pD2j8s5hL.tX8pM1q7i.', NOW(), NOW()),
(3, 'Siti Nurhaliza', 'siti@siswa.schoolcare.sch.id', 'siswa', '20241002', 'XI TKJ 2', '087712345678', '$2y$12$K8/gB4yWqMv0HqjA9e3P4OslzB4qX7Yw7pD2j8s5hL.tX8pM1q7i.', NOW(), NOW()),
(4, 'Budi Pratama', 'budi@siswa.schoolcare.sch.id', 'siswa', '20241003', 'XII MM 1', '085612345678', '$2y$12$K8/gB4yWqMv0HqjA9e3P4OslzB4qX7Yw7pD2j8s5hL.tX8pM1q7i.', NOW(), NOW());

-- Categories
INSERT INTO `categories` (`id`, `name`, `slug`, `description`, `icon`, `is_active`, `created_at`, `updated_at`) VALUES
(1, 'Fasilitas Kelas', 'fasilitas-kelas', 'Kerusakan sarana meja, kursi, papan tulis, proyektor, AC, kipas angin, dan lampu ruang kelas.', 'bi-door-closed', 1, NOW(), NOW()),
(2, 'Kebersihan', 'kebersihan', 'Masalah kebersihan lingkungan, tempat sampah rusak/penuh, dan tumpukan sampah.', 'bi-trash', 1, NOW(), NOW()),
(3, 'Toilet', 'toilet', 'Kerusakan kran air, kunci pintu, wastafel bocor, closet, dan saluran pembuangan toilet.', 'bi-droplet-half', 1, NOW(), NOW()),
(4, 'Laboratorium', 'laboratorium', 'Kendala komputer lab, peralatan praktikum sains/komputer, dan instalasi kelistrikan lab.', 'bi-pc-display', 1, NOW(), NOW()),
(5, 'Perpustakaan', 'perpustakaan', 'Kondisi ruang baca perpustakaan, rak buku, pendingin ruangan, atau sarana literasi.', 'bi-book', 1, NOW(), NOW()),
(6, 'Lapangan', 'lapangan', 'Kerusakan lapangan olahraga, tiang gawang futsal, ring basket, net voli, dan fasilitas outdoor.', 'bi-dribbble', 1, NOW(), NOW()),
(7, 'Internet', 'internet', 'Akses jaringan Wi-Fi sekolah lambat, hotspot tidak dapat diakses, atau kabel LAN terputus.', 'bi-wifi', 1, NOW(), NOW()),
(8, 'Lainnya', 'lainnya', 'Pengaduan sarana dan prasarana umum sekolah lainnya yang belum tercakup di kategori utama.', 'bi-grid', 1, NOW(), NOW());

-- Aspirations
INSERT INTO `aspirations` (`id`, `ticket_number`, `user_id`, `category_id`, `title`, `location`, `description`, `photo`, `status`, `created_at`, `updated_at`) VALUES
(1, 'SC-20261101-1001', 2, 1, 'Proyektor Ruang Kelas XII RPL 1 Tampilan Berkedip dan Mati Sendiri', 'Gedung B Lantai 2, Ruang Kelas XII RPL 1', 'Proyektor sering mati mendadak saat jam pelajaran berlangsung dan gambar berkedip warna hijau. Sangat mengganggu jalannya KBM.', NULL, 'Diproses', NOW() - INTERVAL 3 DAY, NOW() - INTERVAL 1 DAY),
(2, 'SC-20261102-1002', 2, 3, 'Kran Air Wastafel Toilet Pria Rusak dan Terus Mengalir', 'Toilet Siswa Pria Lantai 1 Dekat Kantin', 'Gagang kran air wastafel patah sehingga air bersih terbuang terus menerus. Mohon segera diganti agar tidak boros air.', NULL, 'Selesai', NOW() - INTERVAL 5 DAY, NOW() - INTERVAL 2 DAY),
(3, 'SC-20261105-1003', 3, 7, 'Wi-Fi Perpustakaan Tidak Terkoneksi ke Internet', 'Perpustakaan Sekolah Lantai 1 (Area Sudut Baca)', 'Sinyal Wi-Fi HOTSPOT-PERPUS muncul tetapi berstatus No Internet Access sejak pagi hari.', NULL, 'Menunggu', NOW() - INTERVAL 6 HOUR, NOW() - INTERVAL 6 HOUR),
(4, 'SC-20261106-1004', 4, 6, 'Papan Akrilik Ring Basket Timur Retak dan Goyang', 'Lapangan Basket Outdoor Bagian Timur', 'Papan pantul ring basket retak cukup lebar dan baut penyangga tiang mulai goyang. Berbahaya saat dipakai bermain basket.', NULL, 'Diproses', NOW() - INTERVAL 2 DAY, NOW() - INTERVAL 12 HOUR),
(5, 'SC-20261107-1005', 3, 8, 'Usulan Pemasangan Speaker Aktif Tambahan di Kantin', 'Area Kantin Sehat Sekolah', 'Mengusulkan pengadaan speaker aktif bluetooth di area kantin untuk hiburan musik pada saat jam istirahat.', NULL, 'Ditolak', NOW() - INTERVAL 4 DAY, NOW() - INTERVAL 3 DAY);

-- Feedback
INSERT INTO `feedback` (`id`, `aspiration_id`, `user_id`, `response`, `created_at`, `updated_at`) VALUES
(1, 1, 1, 'Terima kasih atas laporannya. Teknisi telah dihubungi dan akan melakukan pengecekan kabel power dan lampu proyektor siang ini pukul 13.00.', NOW() - INTERVAL 1 DAY, NOW() - INTERVAL 1 DAY),
(2, 2, 1, 'Perbaikan kran wastafel telah selesai dilakukan oleh tim kebersihan & sarpras pagi hari ini. Kran berfungsi normal kembali.', NOW() - INTERVAL 2 DAY, NOW() - INTERVAL 2 DAY),
(3, 4, 1, 'Area ring basket timur telah dipasangi tanda pengaman sementara. Papan akrilik pengganti sedang dalam proses pengadaan oleh pihak sekolah.', NOW() - INTERVAL 12 HOUR, NOW() - INTERVAL 12 HOUR),
(4, 5, 1, 'Terima kasih atas usulannya. Namun pengadaan sarana musik di kantin belum dapat disetujui demi menjaga ketertiban lingkungan sekolah.', NOW() - INTERVAL 3 DAY, NOW() - INTERVAL 3 DAY);

-- Aspiration Status Histories
INSERT INTO `aspiration_status_histories` (`id`, `aspiration_id`, `user_id`, `previous_status`, `new_status`, `notes`, `created_at`, `updated_at`) VALUES
(1, 1, 2, NULL, 'Menunggu', 'Pengaduan sarana diajukan oleh siswa.', NOW() - INTERVAL 3 DAY, NOW() - INTERVAL 3 DAY),
(2, 1, 1, 'Menunggu', 'Diproses', 'Laporan diverifikasi. Penjadwalan inspeksi teknisi.', NOW() - INTERVAL 1 DAY, NOW() - INTERVAL 1 DAY),
(3, 2, 2, NULL, 'Menunggu', 'Pengaduan fasilitas toilet diajukan.', NOW() - INTERVAL 5 DAY, NOW() - INTERVAL 5 DAY),
(4, 2, 1, 'Menunggu', 'Diproses', 'Pembelian sparepart kran air pengganti.', NOW() - INTERVAL 4 DAY, NOW() - INTERVAL 4 DAY),
(5, 2, 1, 'Diproses', 'Selesai', 'Pemasangan kran baru selesai diuji dan normal.', NOW() - INTERVAL 2 DAY, NOW() - INTERVAL 2 DAY),
(6, 3, 3, NULL, 'Menunggu', 'Pengaduan sarana internet diajukan oleh siswa.', NOW() - INTERVAL 6 HOUR, NOW() - INTERVAL 6 HOUR),
(7, 4, 4, NULL, 'Menunggu', 'Pengaduan fasilitas olahraga lapangan diajukan.', NOW() - INTERVAL 2 DAY, NOW() - INTERVAL 2 DAY),
(8, 4, 1, 'Menunggu', 'Diproses', 'Pemasangan rambu pembatas dan proses pengadaan papan.', NOW() - INTERVAL 12 HOUR, NOW() - INTERVAL 12 HOUR),
(9, 5, 3, NULL, 'Menunggu', 'Pengaduan sarana diajukan oleh siswa.', NOW() - INTERVAL 4 DAY, NOW() - INTERVAL 4 DAY),
(10, 5, 1, 'Menunggu', 'Ditolak', 'Tidak sesuai kriteria sarana prioritas sekolah.', NOW() - INTERVAL 3 DAY, NOW() - INTERVAL 3 DAY);

SET FOREIGN_KEY_CHECKS = 1;

