-- phpMyAdmin SQL Dump
-- version 5.2.0
-- https://www.phpmyadmin.net/
--
-- Host: localhost:3306
-- Generation Time: Dec 25, 2024 at 09:28 AM
-- Server version: 8.0.30
-- PHP Version: 8.1.10

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `sertifikasi`
--

-- --------------------------------------------------------

--
-- Table structure for table `failed_jobs`
--

CREATE TABLE `failed_jobs` (
  `id` bigint UNSIGNED NOT NULL,
  `uuid` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `connection` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `queue` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `payload` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `exception` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `failed_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `migrations`
--

CREATE TABLE `migrations` (
  `id` int UNSIGNED NOT NULL,
  `migration` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `batch` int NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `migrations`
--

INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES
(1, '2014_10_12_000000_create_users_table', 1),
(2, '2014_10_12_100000_create_password_reset_tokens_table', 1),
(3, '2019_08_19_000000_create_failed_jobs_table', 1),
(4, '2019_12_14_000001_create_personal_access_tokens_table', 1),
(5, '2024_11_10_184518_create_m_level', 1),
(6, '2024_11_10_184526_create_m_bidang', 1),
(7, '2024_11_10_184559_create_m_vendor', 1),
(8, '2024_11_10_193115_create_m_user', 1),
(9, '2024_11_10_193120_create_m_mata_kuliah', 1),
(10, '2024_11_10_194120_create_m_jenis', 1),
(11, '2024_11_10_194130_create_m_periode', 1),
(12, '2024_11_10_194235_create_m_sertifikasi', 1),
(13, '2024_11_10_194246_create_m_pelatihan', 1),
(14, '2024_11_18_132122_create_t_dosen', 1),
(15, '2024_12_08_125258_create_upload_sertifikasi', 1),
(16, '2024_12_08_125304_create_upload_pelatihan', 1),
(17, '2024_12_08_142633_create_peserta_sertifikasi', 1),
(18, '2024_12_08_142645_create_peserta_pelatihan', 1),
(19, '2024_12_08_142654_create_surat_tugas', 1),
(20, '2024_12_09_141908_add_column_deskripsi_to_m_sertifikasi_and_m_pelatihan', 2),
(21, '2024_12_10_190902_add_column_jenis_to_m_bidang', 3),
(22, '2024_12_11_191609_delete_collumn_m_pelatihan', 4),
(23, '2024_12_11_192045_delete_collumn_m_sertifikasi', 4),
(24, '2024_12_12_102914_create_notification_table', 5),
(25, '2024_12_12_103954_create_notification_table', 6),
(26, '2024_12_13_071238_change_avatar_nullable', 7),
(27, '2024_12_14_051407_update_surat_tugas_table', 8),
(28, '2024_12_14_061449_create_surat_tugas_table', 9),
(29, '2024_12_14_063042_update_surat_tugas_nullable_columns', 10),
(30, '2024_12_14_103319_add_bidang_matkul_id_to_m_user', 11),
(31, '2024_12_14_115331_modify_bidang_and_mk_nullable_in_dosen_table', 12),
(32, '2024_12_14_120924_change_user_id_to_dosen_id_in_peserta_pelatihan_table', 13),
(33, '2024_12_14_130128_update_peserta_sertifikasi_dosen_id', 14),
(34, '2024_12_16_220831_add_column_file_surat_to_surat_tugas', 15),
(35, '2024_12_17_153903_t_dosen', 16);

-- --------------------------------------------------------

--
-- Table structure for table `m_bidang`
--

CREATE TABLE `m_bidang` (
  `bidang_id` bigint UNSIGNED NOT NULL,
  `bidang_kode` varchar(10) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `bidang_nama` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `jenis_id` bigint UNSIGNED NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `m_jenis`
--

CREATE TABLE `m_jenis` (
  `jenis_id` bigint UNSIGNED NOT NULL,
  `jenis_kode` varchar(10) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `jenis_nama` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `m_level`
--

CREATE TABLE `m_level` (
  `level_id` bigint UNSIGNED NOT NULL,
  `level_kode` varchar(10) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `level_nama` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `m_level`
--

INSERT INTO `m_level` (`level_id`, `level_kode`, `level_nama`, `created_at`, `updated_at`) VALUES
(1, 'ADM', 'Admin', NULL, NULL),
(2, 'PMN', 'Pimpinan ', NULL, NULL),
(3, 'DSN', 'Dosen', NULL, NULL);

-- --------------------------------------------------------

--
-- Table structure for table `m_mata_kuliah`
--

CREATE TABLE `m_mata_kuliah` (
  `mk_id` bigint UNSIGNED NOT NULL,
  `mk_kode` varchar(10) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `mk_nama` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `m_mata_kuliah`
--

INSERT INTO `m_mata_kuliah` (`mk_id`, `mk_kode`, `mk_nama`, `created_at`, `updated_at`) VALUES
(1, 'MK001', 'Pemrograman Dasar', NULL, NULL),
(2, 'MK002', 'Algoritma dan Basis Data', NULL, NULL),
(3, 'MK003', 'Analisis dan Perancangan Sistem', NULL, NULL),
(4, 'MK004', 'Jaringan Komputer', NULL, NULL),
(5, 'MK005', 'Kecerdasan Buatan', NULL, NULL),
(6, 'MK006', 'Matematika Diskrit', NULL, NULL),
(7, 'MK007', 'Pemrograman Mobile', NULL, NULL),
(8, 'MK008', 'Rekayasa Perangkat Lunak', NULL, NULL),
(9, 'MK009', 'Machine Learning', NULL, NULL),
(10, 'MK010', 'Data Mining', NULL, NULL);

-- --------------------------------------------------------

--
-- Table structure for table `m_pelatihan`
--

CREATE TABLE `m_pelatihan` (
  `pelatihan_id` bigint UNSIGNED NOT NULL,
  `nama_pelatihan` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `deskripsi` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `tanggal` date NOT NULL,
  `kuota` bigint UNSIGNED NOT NULL,
  `lokasi` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `biaya` bigint UNSIGNED NOT NULL,
  `level_pelatihan` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `vendor_id` bigint UNSIGNED NOT NULL,
  `jenis_id` bigint UNSIGNED NOT NULL,
  `mk_id` bigint UNSIGNED NOT NULL,
  `periode_id` bigint UNSIGNED NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `m_pelatihan`
--

INSERT INTO `m_pelatihan` (`pelatihan_id`, `nama_pelatihan`, `deskripsi`, `tanggal`, `kuota`, `lokasi`, `biaya`, `level_pelatihan`, `vendor_id`, `jenis_id`, `mk_id`, `periode_id`, `created_at`, `updated_at`) VALUES
(1, 'Using BigQuery Omni', 'In this lab, you will learn how to use BigQuery Omni with AWS. BigQuery Omni lets you run BigQuery analytics on data stored in AWS S3.', '2025-01-12', 5, 'Auditorium Gedung Teknik Sipil LT 8', 250000, 'Nasional', 26, 3, 10, 12, '2024-12-16 08:51:40', '2024-12-16 08:51:40'),
(2, 'Flutter Dev Academy', 'tes', '2024-12-16', 3, 'tes lokasi', 250000, 'Nasional', 27, 7, 1, 12, '2024-12-18 01:02:54', '2024-12-18 01:02:54');

-- --------------------------------------------------------

--
-- Table structure for table `m_periode`
--

CREATE TABLE `m_periode` (
  `periode_id` bigint UNSIGNED NOT NULL,
  `periode_tahun` smallint UNSIGNED NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `m_periode`
--

INSERT INTO `m_periode` (`periode_id`, `periode_tahun`, `created_at`, `updated_at`) VALUES
(7, 1920, '2024-12-09 18:06:00', '2024-12-09 18:06:00'),
(8, 2021, '2024-12-09 18:06:00', '2024-12-09 18:06:00'),
(9, 2122, '2024-12-09 18:06:00', '2024-12-09 18:06:00'),
(10, 2223, '2024-12-09 18:06:00', '2024-12-09 18:06:00'),
(11, 2324, '2024-12-09 18:06:00', '2024-12-09 18:06:00'),
(12, 2425, '2024-12-09 18:06:00', '2024-12-09 18:06:00'),
(13, 2526, '2024-12-10 15:42:07', '2024-12-10 15:42:07'),
(14, 2627, '2024-12-10 15:43:47', '2024-12-10 15:43:47');

-- --------------------------------------------------------

--
-- Table structure for table `m_sertifikasi`
--

CREATE TABLE `m_sertifikasi` (
  `sertifikasi_id` bigint UNSIGNED NOT NULL,
  `nama_sertifikasi` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `deskripsi` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `tanggal` date NOT NULL,
  `kuota` bigint UNSIGNED NOT NULL,
  `level_sertifikasi` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `vendor_id` bigint UNSIGNED NOT NULL,
  `jenis_id` bigint UNSIGNED NOT NULL,
  `mk_id` bigint UNSIGNED NOT NULL,
  `periode_id` bigint UNSIGNED NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `m_sertifikasi`
--

INSERT INTO `m_sertifikasi` (`sertifikasi_id`, `nama_sertifikasi`, `deskripsi`, `tanggal`, `kuota`, `level_sertifikasi`, `vendor_id`, `jenis_id`, `mk_id`, `periode_id`, `created_at`, `updated_at`) VALUES
(1, 'Machine Learning Pipeline', 'This course explores how to the use of the iterative machine learning (ML) process pipeline to solve a real business problem in a project-based learning environment.', '2024-12-30', 3, 'Profesi', 26, 9, 9, 12, '2024-12-16 08:55:11', '2024-12-16 08:55:11');

-- --------------------------------------------------------

--
-- Table structure for table `m_user`
--

CREATE TABLE `m_user` (
  `user_id` bigint UNSIGNED NOT NULL,
  `level_id` bigint UNSIGNED NOT NULL,
  `bidang_id` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci,
  `mk_id` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci,
  `nip` varchar(20) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `nama` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `username` varchar(20) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `email` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `password` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `avatar` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `m_user`
--

INSERT INTO `m_user` (`user_id`, `level_id`, `bidang_id`, `mk_id`, `nip`, `nama`, `username`, `email`, `password`, `avatar`, `created_at`, `updated_at`) VALUES
(1, 1, NULL, NULL, '12345678', 'Administrator', 'admin', 'admin@example.com', '$2y$12$rdJNMj8CXpFFNluAgApJC.7NC/mmsuZPjdAddmQi90Fl3qWY0Zt9W', 'cNUuGtN8x8e4p1WK7CUWR8Lz4BHxO8xCsl7eG1mS.jpg.jpg', NULL, '2024-12-16 08:57:01'),
(2, 2, NULL, NULL, '09876543', 'Pimpinan', 'pimpinan', 'pimpinan@example.com', '$2y$12$2ggbiZGE5Z/20N3dRYswoO22bz0f7jaszohAKGrJyo0nqwFmdOXOu', '0rwD3JxLocinm3F627qOVashIrHHAWT7tBR0RU6K.png.png', NULL, '2024-12-16 16:08:51'),
(3, 3, '3', '7,9', '2241760128', 'Iyazuz Zidan', 'iyzidann', 'iyazuzzidan@gmail.com', '$2y$12$bLpQZV3KzIVdxOif7rLhgewub3csHtOX1ivF3/OfwotGm7IoGw5KG', '1735052182.jpg', NULL, '2024-12-24 08:07:52');

-- --------------------------------------------------------

--
-- Table structure for table `m_vendor`
--

CREATE TABLE `m_vendor` (
  `vendor_id` bigint UNSIGNED NOT NULL,
  `vendor_nama` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `alamat` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `kota` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `no_telp` varchar(20) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `alamat_web` varchar(200) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `m_vendor`
--

INSERT INTO `m_vendor` (`vendor_id`, `vendor_nama`, `alamat`, `kota`, `no_telp`, `alamat_web`, `created_at`, `updated_at`) VALUES
(1, 'PT. Jaya', 'Jl. Raya Jaya No. 123', 'Jakarta', '021-1234567', 'www.ptjaya.com', '2024-12-09 17:11:59', '2024-12-09 17:11:59'),
(2, 'PT. Sukamaju', 'Jl. Sukamaju No. 456', 'Bandung', '022-7654321', 'www.ptsukamaju.com', '2024-12-09 17:11:59', '2024-12-09 17:11:59'),
(23, 'Microsoft Learning', 'Jl. Jaya Baya', 'Surabaya', '0895-4882-8080', 'learn.microsoft.com', NULL, NULL),
(24, 'Oracle University', 'Jl. Keraton', 'Yogyakarta', '0857-0506-7000', 'education.oracle.com', NULL, NULL),
(25, 'Cisco Learning', 'Jl. Singa', 'Malang', '0881-8526-4000', 'learning.cisco.com', NULL, NULL),
(26, 'AWS Training', 'Jl. Merdeka No. 7', 'Tangerang', '0896-7266-1000', 'aws.training', NULL, NULL),
(27, 'Google Cloud Training', 'Jl. Kuala Lumpur', 'Sidoarjo', '0882-2253-0000', 'cloud.google.com/training', NULL, NULL),
(28, 'Red Hat Training', 'Jl. Adara No. 55', 'Bogor', '0881-9754-3700', 'redhat.com/training', NULL, NULL),
(29, 'IBM Training', 'Jl. Sewu No. 2', 'Semarang', '0882-4499-1900', 'ibm.com/training', NULL, NULL),
(30, 'VMware Education', 'Jl. Grigi', 'Gresik', '0877-3486-9273', 'vmware.com/education', NULL, NULL),
(31, 'Salesforce Training', 'Jl. Abidari', 'Cirebon', '0857-0901-7000', 'trailhead.salesforce.com', NULL, NULL),
(32, 'SAP Training', 'Jl. Bidadara', 'Ngoro', '0896-7661-1000', 'training.sap.com', NULL, NULL);

-- --------------------------------------------------------

--
-- Table structure for table `notification`
--

CREATE TABLE `notification` (
  `notif_id` bigint UNSIGNED NOT NULL,
  `user_id` bigint UNSIGNED NOT NULL,
  `title` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `message` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `type` varchar(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `reference_id` bigint UNSIGNED NOT NULL,
  `is_read` tinyint(1) NOT NULL DEFAULT '0',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `password_reset_tokens`
--

CREATE TABLE `password_reset_tokens` (
  `email` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `token` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `personal_access_tokens`
--

CREATE TABLE `personal_access_tokens` (
  `id` bigint UNSIGNED NOT NULL,
  `tokenable_type` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `tokenable_id` bigint UNSIGNED NOT NULL,
  `name` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `token` varchar(64) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `abilities` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci,
  `last_used_at` timestamp NULL DEFAULT NULL,
  `expires_at` timestamp NULL DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `peserta_pelatihan`
--

CREATE TABLE `peserta_pelatihan` (
  `peserta_pelatihan_id` bigint UNSIGNED NOT NULL,
  `user_id` bigint UNSIGNED NOT NULL,
  `pelatihan_id` bigint UNSIGNED NOT NULL,
  `status` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `peserta_sertifikasi`
--

CREATE TABLE `peserta_sertifikasi` (
  `peserta_sertifikasi_id` bigint UNSIGNED NOT NULL,
  `user_id` bigint UNSIGNED NOT NULL,
  `sertifikasi_id` bigint UNSIGNED NOT NULL,
  `status` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `surat_tugas`
--

CREATE TABLE `surat_tugas` (
  `surat_tugas_id` bigint UNSIGNED NOT NULL,
  `peserta_sertifikasi_id` bigint UNSIGNED DEFAULT NULL,
  `peserta_pelatihan_id` bigint UNSIGNED DEFAULT NULL,
  `user_id` bigint UNSIGNED NOT NULL,
  `file_surat` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `t_dosen`
--

CREATE TABLE `t_dosen` (
  `dosen_id` bigint UNSIGNED NOT NULL,
  `user_id` bigint UNSIGNED NOT NULL,
  `bidang_id` bigint UNSIGNED NOT NULL,
  `mk_id` bigint UNSIGNED NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `upload_pelatihan`
--

CREATE TABLE `upload_pelatihan` (
  `upload_id` bigint UNSIGNED NOT NULL,
  `user_id` bigint UNSIGNED NOT NULL,
  `nama_sertif` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `no_sertif` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `tanggal` date NOT NULL,
  `masa_berlaku` date NOT NULL,
  `jenis_id` bigint UNSIGNED NOT NULL,
  `nama_vendor` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `bukti` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `upload_sertifikasi`
--

CREATE TABLE `upload_sertifikasi` (
  `upload_id` bigint UNSIGNED NOT NULL,
  `user_id` bigint UNSIGNED NOT NULL,
  `nama_sertif` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `no_sertif` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `tanggal` date NOT NULL,
  `masa_berlaku` date NOT NULL,
  `jenis_id` bigint UNSIGNED NOT NULL,
  `nama_vendor` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `bukti` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `id` bigint UNSIGNED NOT NULL,
  `name` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `email` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `email_verified_at` timestamp NULL DEFAULT NULL,
  `password` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `remember_token` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Indexes for dumped tables
--

--
-- Indexes for table `failed_jobs`
--
ALTER TABLE `failed_jobs`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `failed_jobs_uuid_unique` (`uuid`);

--
-- Indexes for table `migrations`
--
ALTER TABLE `migrations`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `m_bidang`
--
ALTER TABLE `m_bidang`
  ADD PRIMARY KEY (`bidang_id`),
  ADD UNIQUE KEY `m_bidang_bidang_kode_unique` (`bidang_kode`),
  ADD KEY `m_bidang_jenis_id_foreign` (`jenis_id`);

--
-- Indexes for table `m_jenis`
--
ALTER TABLE `m_jenis`
  ADD PRIMARY KEY (`jenis_id`),
  ADD UNIQUE KEY `m_jenis_jenis_kode_unique` (`jenis_kode`);

--
-- Indexes for table `m_level`
--
ALTER TABLE `m_level`
  ADD PRIMARY KEY (`level_id`),
  ADD UNIQUE KEY `m_level_level_kode_unique` (`level_kode`);

--
-- Indexes for table `m_mata_kuliah`
--
ALTER TABLE `m_mata_kuliah`
  ADD PRIMARY KEY (`mk_id`);

--
-- Indexes for table `m_pelatihan`
--
ALTER TABLE `m_pelatihan`
  ADD PRIMARY KEY (`pelatihan_id`),
  ADD KEY `m_pelatihan_vendor_id_foreign` (`vendor_id`),
  ADD KEY `m_pelatihan_jenis_id_foreign` (`jenis_id`),
  ADD KEY `m_pelatihan_mk_id_foreign` (`mk_id`),
  ADD KEY `m_pelatihan_periode_id_foreign` (`periode_id`);

--
-- Indexes for table `m_periode`
--
ALTER TABLE `m_periode`
  ADD PRIMARY KEY (`periode_id`);

--
-- Indexes for table `m_sertifikasi`
--
ALTER TABLE `m_sertifikasi`
  ADD PRIMARY KEY (`sertifikasi_id`),
  ADD KEY `m_sertifikasi_vendor_id_foreign` (`vendor_id`),
  ADD KEY `m_sertifikasi_jenis_id_foreign` (`jenis_id`),
  ADD KEY `m_sertifikasi_mk_id_foreign` (`mk_id`),
  ADD KEY `m_sertifikasi_periode_id_foreign` (`periode_id`);

--
-- Indexes for table `m_user`
--
ALTER TABLE `m_user`
  ADD PRIMARY KEY (`user_id`),
  ADD UNIQUE KEY `m_user_nip_unique` (`nip`),
  ADD UNIQUE KEY `m_user_username_unique` (`username`),
  ADD KEY `m_user_level_id_foreign` (`level_id`);

--
-- Indexes for table `m_vendor`
--
ALTER TABLE `m_vendor`
  ADD PRIMARY KEY (`vendor_id`),
  ADD UNIQUE KEY `m_vendor_vendor_nama_unique` (`vendor_nama`);

--
-- Indexes for table `notification`
--
ALTER TABLE `notification`
  ADD PRIMARY KEY (`notif_id`),
  ADD KEY `notification_user_id_foreign` (`user_id`);

--
-- Indexes for table `password_reset_tokens`
--
ALTER TABLE `password_reset_tokens`
  ADD PRIMARY KEY (`email`);

--
-- Indexes for table `personal_access_tokens`
--
ALTER TABLE `personal_access_tokens`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `personal_access_tokens_token_unique` (`token`),
  ADD KEY `personal_access_tokens_tokenable_type_tokenable_id_index` (`tokenable_type`,`tokenable_id`);

--
-- Indexes for table `peserta_pelatihan`
--
ALTER TABLE `peserta_pelatihan`
  ADD PRIMARY KEY (`peserta_pelatihan_id`),
  ADD KEY `peserta_pelatihan_pelatihan_id_foreign` (`pelatihan_id`),
  ADD KEY `peserta_pelatihan_user_id_foreign` (`user_id`);

--
-- Indexes for table `peserta_sertifikasi`
--
ALTER TABLE `peserta_sertifikasi`
  ADD PRIMARY KEY (`peserta_sertifikasi_id`),
  ADD KEY `peserta_sertifikasi_sertifikasi_id_foreign` (`sertifikasi_id`),
  ADD KEY `peserta_sertifikasi_user_id_foreign` (`user_id`);

--
-- Indexes for table `surat_tugas`
--
ALTER TABLE `surat_tugas`
  ADD PRIMARY KEY (`surat_tugas_id`),
  ADD KEY `surat_tugas_peserta_sertifikasi_id_foreign` (`peserta_sertifikasi_id`),
  ADD KEY `surat_tugas_peserta_pelatihan_id_foreign` (`peserta_pelatihan_id`),
  ADD KEY `surat_tugas_user_id_foreign` (`user_id`);

--
-- Indexes for table `t_dosen`
--
ALTER TABLE `t_dosen`
  ADD PRIMARY KEY (`dosen_id`),
  ADD KEY `t_dosen_user_id_foreign` (`user_id`),
  ADD KEY `t_dosen_bidang_id_foreign` (`bidang_id`),
  ADD KEY `t_dosen_mk_id_foreign` (`mk_id`);

--
-- Indexes for table `upload_pelatihan`
--
ALTER TABLE `upload_pelatihan`
  ADD PRIMARY KEY (`upload_id`),
  ADD UNIQUE KEY `upload_pelatihan_no_sertif_unique` (`no_sertif`),
  ADD KEY `upload_pelatihan_user_id_foreign` (`user_id`),
  ADD KEY `upload_pelatihan_jenis_id_foreign` (`jenis_id`);

--
-- Indexes for table `upload_sertifikasi`
--
ALTER TABLE `upload_sertifikasi`
  ADD PRIMARY KEY (`upload_id`),
  ADD UNIQUE KEY `upload_sertifikasi_no_sertif_unique` (`no_sertif`),
  ADD KEY `upload_sertifikasi_user_id_foreign` (`user_id`),
  ADD KEY `upload_sertifikasi_jenis_id_foreign` (`jenis_id`);

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `users_email_unique` (`email`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `failed_jobs`
--
ALTER TABLE `failed_jobs`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `migrations`
--
ALTER TABLE `migrations`
  MODIFY `id` int UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=36;

--
-- AUTO_INCREMENT for table `m_bidang`
--
ALTER TABLE `m_bidang`
  MODIFY `bidang_id` bigint UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `m_jenis`
--
ALTER TABLE `m_jenis`
  MODIFY `jenis_id` bigint UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `m_level`
--
ALTER TABLE `m_level`
  MODIFY `level_id` bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `m_mata_kuliah`
--
ALTER TABLE `m_mata_kuliah`
  MODIFY `mk_id` bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=11;

--
-- AUTO_INCREMENT for table `m_pelatihan`
--
ALTER TABLE `m_pelatihan`
  MODIFY `pelatihan_id` bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `m_periode`
--
ALTER TABLE `m_periode`
  MODIFY `periode_id` bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=15;

--
-- AUTO_INCREMENT for table `m_sertifikasi`
--
ALTER TABLE `m_sertifikasi`
  MODIFY `sertifikasi_id` bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `m_user`
--
ALTER TABLE `m_user`
  MODIFY `user_id` bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- AUTO_INCREMENT for table `m_vendor`
--
ALTER TABLE `m_vendor`
  MODIFY `vendor_id` bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=33;

--
-- AUTO_INCREMENT for table `notification`
--
ALTER TABLE `notification`
  MODIFY `notif_id` bigint UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `personal_access_tokens`
--
ALTER TABLE `personal_access_tokens`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `peserta_pelatihan`
--
ALTER TABLE `peserta_pelatihan`
  MODIFY `peserta_pelatihan_id` bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=24;

--
-- AUTO_INCREMENT for table `peserta_sertifikasi`
--
ALTER TABLE `peserta_sertifikasi`
  MODIFY `peserta_sertifikasi_id` bigint UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `surat_tugas`
--
ALTER TABLE `surat_tugas`
  MODIFY `surat_tugas_id` bigint UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `t_dosen`
--
ALTER TABLE `t_dosen`
  MODIFY `dosen_id` bigint UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `upload_pelatihan`
--
ALTER TABLE `upload_pelatihan`
  MODIFY `upload_id` bigint UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `upload_sertifikasi`
--
ALTER TABLE `upload_sertifikasi`
  MODIFY `upload_id` bigint UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `m_bidang`
--
ALTER TABLE `m_bidang`
  ADD CONSTRAINT `m_bidang_jenis_id_foreign` FOREIGN KEY (`jenis_id`) REFERENCES `m_jenis` (`jenis_id`);

--
-- Constraints for table `m_pelatihan`
--
ALTER TABLE `m_pelatihan`
  ADD CONSTRAINT `m_pelatihan_jenis_id_foreign` FOREIGN KEY (`jenis_id`) REFERENCES `m_jenis` (`jenis_id`),
  ADD CONSTRAINT `m_pelatihan_mk_id_foreign` FOREIGN KEY (`mk_id`) REFERENCES `m_mata_kuliah` (`mk_id`),
  ADD CONSTRAINT `m_pelatihan_periode_id_foreign` FOREIGN KEY (`periode_id`) REFERENCES `m_periode` (`periode_id`),
  ADD CONSTRAINT `m_pelatihan_vendor_id_foreign` FOREIGN KEY (`vendor_id`) REFERENCES `m_vendor` (`vendor_id`);

--
-- Constraints for table `m_sertifikasi`
--
ALTER TABLE `m_sertifikasi`
  ADD CONSTRAINT `m_sertifikasi_jenis_id_foreign` FOREIGN KEY (`jenis_id`) REFERENCES `m_jenis` (`jenis_id`),
  ADD CONSTRAINT `m_sertifikasi_mk_id_foreign` FOREIGN KEY (`mk_id`) REFERENCES `m_mata_kuliah` (`mk_id`),
  ADD CONSTRAINT `m_sertifikasi_periode_id_foreign` FOREIGN KEY (`periode_id`) REFERENCES `m_periode` (`periode_id`),
  ADD CONSTRAINT `m_sertifikasi_vendor_id_foreign` FOREIGN KEY (`vendor_id`) REFERENCES `m_vendor` (`vendor_id`);

--
-- Constraints for table `m_user`
--
ALTER TABLE `m_user`
  ADD CONSTRAINT `m_user_level_id_foreign` FOREIGN KEY (`level_id`) REFERENCES `m_level` (`level_id`);

--
-- Constraints for table `notification`
--
ALTER TABLE `notification`
  ADD CONSTRAINT `notification_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `m_user` (`user_id`);

--
-- Constraints for table `peserta_pelatihan`
--
ALTER TABLE `peserta_pelatihan`
  ADD CONSTRAINT `peserta_pelatihan_pelatihan_id_foreign` FOREIGN KEY (`pelatihan_id`) REFERENCES `m_pelatihan` (`pelatihan_id`),
  ADD CONSTRAINT `peserta_pelatihan_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `m_user` (`user_id`) ON DELETE CASCADE;

--
-- Constraints for table `peserta_sertifikasi`
--
ALTER TABLE `peserta_sertifikasi`
  ADD CONSTRAINT `peserta_sertifikasi_sertifikasi_id_foreign` FOREIGN KEY (`sertifikasi_id`) REFERENCES `m_sertifikasi` (`sertifikasi_id`),
  ADD CONSTRAINT `peserta_sertifikasi_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `m_user` (`user_id`) ON DELETE CASCADE;

--
-- Constraints for table `surat_tugas`
--
ALTER TABLE `surat_tugas`
  ADD CONSTRAINT `surat_tugas_peserta_pelatihan_id_foreign` FOREIGN KEY (`peserta_pelatihan_id`) REFERENCES `peserta_pelatihan` (`peserta_pelatihan_id`),
  ADD CONSTRAINT `surat_tugas_peserta_sertifikasi_id_foreign` FOREIGN KEY (`peserta_sertifikasi_id`) REFERENCES `peserta_sertifikasi` (`peserta_sertifikasi_id`),
  ADD CONSTRAINT `surat_tugas_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `m_user` (`user_id`);

--
-- Constraints for table `t_dosen`
--
ALTER TABLE `t_dosen`
  ADD CONSTRAINT `t_dosen_bidang_id_foreign` FOREIGN KEY (`bidang_id`) REFERENCES `m_bidang` (`bidang_id`),
  ADD CONSTRAINT `t_dosen_mk_id_foreign` FOREIGN KEY (`mk_id`) REFERENCES `m_mata_kuliah` (`mk_id`),
  ADD CONSTRAINT `t_dosen_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `m_user` (`user_id`);

--
-- Constraints for table `upload_pelatihan`
--
ALTER TABLE `upload_pelatihan`
  ADD CONSTRAINT `upload_pelatihan_jenis_id_foreign` FOREIGN KEY (`jenis_id`) REFERENCES `m_jenis` (`jenis_id`),
  ADD CONSTRAINT `upload_pelatihan_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `m_user` (`user_id`);

--
-- Constraints for table `upload_sertifikasi`
--
ALTER TABLE `upload_sertifikasi`
  ADD CONSTRAINT `upload_sertifikasi_jenis_id_foreign` FOREIGN KEY (`jenis_id`) REFERENCES `m_jenis` (`jenis_id`),
  ADD CONSTRAINT `upload_sertifikasi_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `m_user` (`user_id`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
