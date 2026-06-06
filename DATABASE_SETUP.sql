-- ========================================
-- SQL Query untuk membuat tabel record_aktivitas
-- Jalankan query ini di MySQL/MariaDB localhost
-- ========================================

-- Buat tabel record_aktivitas
CREATE TABLE IF NOT EXISTS `record_aktivitas` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `id_user` bigint unsigned DEFAULT NULL COMMENT 'Reference ke tabel users',
  `action` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL COMMENT 'Tipe aksi: create_transaksi, edit_transaksi, delete_transaksi, etc',
  `description` longtext COLLATE utf8mb4_unicode_ci COMMENT 'Deskripsi detail aktivitas',
  `model_type` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL COMMENT 'Jenis model: Transaksi, Barang, etc',
  `model_id` bigint unsigned DEFAULT NULL COMMENT 'ID dari model yang diakses',
  `old_values` json DEFAULT NULL COMMENT 'Nilai lama dalam format JSON',
  `new_values` json DEFAULT NULL COMMENT 'Nilai baru dalam format JSON',
  `ip_address` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL COMMENT 'IP address user',
  `user_agent` longtext COLLATE utf8mb4_unicode_ci COMMENT 'User agent dari browser',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `record_aktivitas_id_user_created_at_index` (`id_user`, `created_at`),
  KEY `record_aktivitas_action_created_at_index` (`action`, `created_at`),
  KEY `record_aktivitas_model_type_model_id_index` (`model_type`, `model_id`),
  CONSTRAINT `record_aktivitas_id_user_foreign` FOREIGN KEY (`id_user`) REFERENCES `users` (`id`) ON DELETE SET NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ========================================
-- Jika sudah ada, hapus tabel lama dulu:
-- ========================================
-- DROP TABLE IF EXISTS `activity_logs`;

-- ========================================
-- Untuk memverifikasi tabel:
-- ========================================
-- DESCRIBE record_aktivitas;
-- SHOW INDEXES FROM record_aktivitas;
