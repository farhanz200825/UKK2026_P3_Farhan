-- phpMyAdmin SQL Dump
-- version 5.2.0
-- https://www.phpmyadmin.net/
--
-- Host: localhost:3306
-- Generation Time: Apr 10, 2026 at 10:12 AM
-- Server version: 8.0.30
-- PHP Version: 8.3.29

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `db_sarana_sekolah`
--

-- --------------------------------------------------------

--
-- Table structure for table `aspirasi`
--

CREATE TABLE `aspirasi` (
  `id_aspirasi` int NOT NULL,
  `user_id` int DEFAULT NULL,
  `id_kategori` int DEFAULT NULL,
  `lokasi` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `keterangan` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `foto` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `status` enum('Menunggu','Proses','Selesai') DEFAULT 'Menunggu',
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `id_ruangan` int DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data for table `aspirasi`
--

INSERT INTO `aspirasi` (`id_aspirasi`, `user_id`, `id_kategori`, `lokasi`, `keterangan`, `foto`, `status`, `created_at`, `updated_at`, `id_ruangan`) VALUES
(2, 4, 1, 'kelas 12 rpl', 'meja patah', 'aspirasi_foto/1775657829_555ef5680423bd083f8b4567.jpeg', 'Selesai', '2026-04-08 07:17:09', '2026-04-08 07:38:55', NULL),
(3, 4, 2, 'kelas 12 rpl', 'kursinya udah rusak', 'aspirasi_foto/1775660968_Screenshot_20250115_131355_WhatsApp.jpg', 'Proses', '2026-04-08 08:09:28', '2026-04-08 08:12:08', NULL),
(4, 4, 4, 'kelas 12 rpl', 'pintu rusak', 'aspirasi_foto/1775661066_download1.jpg', 'Menunggu', '2026-04-08 08:11:06', '2026-04-08 08:11:06', NULL),
(5, 4, 1, 'kelas 12 rpl', 'dfghdfcghnfg', 'aspirasi_foto/1775729452_Untitled.png', 'Selesai', '2026-04-09 03:10:52', '2026-04-09 03:12:30', NULL);

-- --------------------------------------------------------

--
-- Table structure for table `guru`
--

CREATE TABLE `guru` (
  `id` int NOT NULL,
  `user_id` int DEFAULT NULL,
  `nip` varchar(20) DEFAULT NULL,
  `nama` varchar(100) DEFAULT NULL,
  `mata_pelajaran` varchar(100) DEFAULT NULL,
  `jabatan` enum('Guru','Kepala Sekolah','Wakil Kepala Sekolah','Wali Kelas','Kepala Jurusan') DEFAULT 'Guru',
  `jenis_kelamin` enum('L','P') DEFAULT NULL,
  `tanggal_lahir` date DEFAULT NULL,
  `alamat` text,
  `no_hp` varchar(15) DEFAULT NULL,
  `foto` varchar(255) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data for table `guru`
--

INSERT INTO `guru` (`id`, `user_id`, `nip`, `nama`, `mata_pelajaran`, `jabatan`, `jenis_kelamin`, `tanggal_lahir`, `alamat`, `no_hp`, `foto`, `created_at`, `updated_at`) VALUES
(1, 3, '2317628317862', 'guru', 'animator', 'Guru', 'L', '2026-04-11', 'padasuka', '9987765447333', NULL, '2026-04-06 23:01:42', '2026-04-10 01:03:32');

-- --------------------------------------------------------

--
-- Table structure for table `history_status`
--

CREATE TABLE `history_status` (
  `id_history` int NOT NULL,
  `id_aspirasi` int DEFAULT NULL,
  `status_lama` enum('Menunggu','Proses','Selesai') DEFAULT NULL,
  `status_baru` enum('Menunggu','Proses','Selesai') DEFAULT NULL,
  `diubah_oleh` int DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data for table `history_status`
--

INSERT INTO `history_status` (`id_history`, `id_aspirasi`, `status_lama`, `status_baru`, `diubah_oleh`, `created_at`) VALUES
(1, 2, 'Menunggu', 'Proses', 3, '2026-04-08 14:19:59'),
(2, 2, 'Proses', 'Selesai', 3, '2026-04-08 14:38:55'),
(3, 3, 'Menunggu', 'Proses', 3, '2026-04-08 15:12:08'),
(4, 5, 'Menunggu', 'Proses', 3, '2026-04-09 10:12:08'),
(5, 5, 'Proses', 'Selesai', 3, '2026-04-09 10:12:30');

-- --------------------------------------------------------

--
-- Table structure for table `jurusan`
--

CREATE TABLE `jurusan` (
  `id_jurusan` int NOT NULL,
  `kode_jurusan` varchar(20) NOT NULL,
  `nama_jurusan` varchar(100) NOT NULL,
  `deskripsi` text,
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data for table `jurusan`
--

INSERT INTO `jurusan` (`id_jurusan`, `kode_jurusan`, `nama_jurusan`, `deskripsi`, `created_at`, `updated_at`) VALUES
(1, 'RPL', 'Rekayasa Perangkat Lunak', 'Jurusan yang mempelajari pengembangan perangkat lunak', '2026-04-10 04:38:14', '2026-04-10 00:26:57'),
(2, 'TKJ', 'Teknik Komputer dan Jaringan', 'Jurusan yang mempelajari komputer dan jaringan', '2026-04-10 04:38:14', '2026-04-10 04:38:14'),
(3, 'MM', 'Multimedia', 'Jurusan yang mempelajari desain grafis dan multimedia', '2026-04-10 04:38:14', '2026-04-10 04:38:14'),
(4, 'DKV', 'Desain Komunikasi Visual', 'Jurusan yang mempelajari desain komunikasi visual', '2026-04-10 04:38:14', '2026-04-10 04:38:14'),
(5, 'OTKP', 'Otomatisasi dan Tata Kelola Perkantoran', 'Jurusan yang mempelajari administrasi perkantoran', '2026-04-10 04:38:14', '2026-04-10 04:38:14'),
(6, 'BDP', 'Bisnis Daring dan Pemasaran', 'Jurusan yang mempelajari bisnis online dan pemasaran', '2026-04-10 04:38:14', '2026-04-10 04:38:14'),
(7, 'AKL', 'Akuntansi dan Keuangan Lembaga', 'Jurusan yang mempelajari akuntansi', '2026-04-10 04:38:14', '2026-04-10 04:38:14');

-- --------------------------------------------------------

--
-- Table structure for table `kategori`
--

CREATE TABLE `kategori` (
  `id_kategori` int NOT NULL,
  `nama_kategori` varchar(50) DEFAULT NULL,
  `deskripsi` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci,
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data for table `kategori`
--

INSERT INTO `kategori` (`id_kategori`, `nama_kategori`, `deskripsi`, `created_at`) VALUES
(1, 'Meja', 'meja siswa', '2026-04-07 10:20:55'),
(2, 'kursi', 'kursi siswa', '2026-04-07 10:49:37'),
(4, 'pintu', 'pintu kelas', '2026-04-08 13:51:37'),
(5, 'papan tulis', 'papan', '2026-04-10 07:33:47');

-- --------------------------------------------------------

--
-- Table structure for table `kelas`
--

CREATE TABLE `kelas` (
  `id_kelas` int NOT NULL,
  `nama_kelas` varchar(20) NOT NULL,
  `tingkat` enum('10','11','12') NOT NULL,
  `id_jurusan` int NOT NULL,
  `kapasitas` int DEFAULT '30',
  `deskripsi` text,
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data for table `kelas`
--

INSERT INTO `kelas` (`id_kelas`, `nama_kelas`, `tingkat`, `id_jurusan`, `kapasitas`, `deskripsi`, `created_at`, `updated_at`) VALUES
(1, '10 RPL', '10', 1, 4, NULL, '2026-04-10 04:38:14', '2026-04-10 00:43:58'),
(2, '11 RPL', '11', 1, 30, NULL, '2026-04-10 04:38:14', '2026-04-10 04:38:14'),
(3, '12 RPL', '12', 1, 30, NULL, '2026-04-10 04:38:14', '2026-04-10 04:38:14'),
(4, '10 TKJ', '10', 2, 30, NULL, '2026-04-10 04:38:14', '2026-04-10 04:38:14'),
(5, '11 TKJ', '11', 2, 30, NULL, '2026-04-10 04:38:14', '2026-04-10 04:38:14'),
(6, '12 TKJ', '12', 2, 30, NULL, '2026-04-10 04:38:14', '2026-04-10 04:38:14'),
(7, '10 MM', '10', 3, 30, NULL, '2026-04-10 04:38:14', '2026-04-10 04:38:14'),
(8, '11 MM', '11', 3, 30, NULL, '2026-04-10 04:38:14', '2026-04-10 04:38:14'),
(9, '12 MM', '12', 3, 30, NULL, '2026-04-10 04:38:14', '2026-04-10 04:38:14');

-- --------------------------------------------------------

--
-- Table structure for table `migrations`
--

CREATE TABLE `migrations` (
  `id` int UNSIGNED NOT NULL,
  `migration` varchar(255) NOT NULL,
  `batch` int NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

-- --------------------------------------------------------

--
-- Table structure for table `petugas`
--

CREATE TABLE `petugas` (
  `id` int NOT NULL,
  `user_id` int NOT NULL,
  `nip` varchar(20) DEFAULT NULL,
  `nama` varchar(100) NOT NULL,
  `jenis_kelamin` enum('L','P') DEFAULT NULL,
  `tanggal_lahir` date DEFAULT NULL,
  `alamat` text,
  `no_hp` varchar(15) DEFAULT NULL,
  `foto` varchar(255) DEFAULT NULL,
  `status` enum('Aktif','Tidak Aktif') DEFAULT 'Aktif',
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data for table `petugas`
--

INSERT INTO `petugas` (`id`, `user_id`, `nip`, `nama`, `jenis_kelamin`, `tanggal_lahir`, `alamat`, `no_hp`, `foto`, `status`, `created_at`, `updated_at`) VALUES
(1, 5, '27398273922', 'petugas', 'L', '2026-04-10', 'padasuka', '0816237522', NULL, 'Aktif', '2026-04-10 02:06:15', '2026-04-10 02:06:15');

-- --------------------------------------------------------

--
-- Table structure for table `progres`
--

CREATE TABLE `progres` (
  `id_progres` int NOT NULL,
  `id_aspirasi` int DEFAULT NULL,
  `user_id` int DEFAULT NULL,
  `keterangan_progres` text,
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data for table `progres`
--

INSERT INTO `progres` (`id_progres`, `id_aspirasi`, `user_id`, `keterangan_progres`, `created_at`) VALUES
(2, 2, 3, 'Feedback: sekolah akan secepatnya mengganti meja', '2026-04-08 14:19:59'),
(3, 2, 3, 'segera', '2026-04-08 14:22:41'),
(4, 3, 3, 'Feedback: kami akan secepatnya mengganti yang baru', '2026-04-08 15:11:36'),
(5, 3, 3, 'kursi sekarang sedang di perbaiki', '2026-04-08 15:12:08'),
(6, 5, 3, 'Feedback: bvhjvghjvgh', '2026-04-09 10:11:45'),
(7, 5, 3, 'asdfsdfsdfsfssfdf', '2026-04-09 10:12:08');

-- --------------------------------------------------------

--
-- Table structure for table `ruangan`
--

CREATE TABLE `ruangan` (
  `id_ruangan` int NOT NULL,
  `kode_ruangan` varchar(20) NOT NULL,
  `nama_ruangan` varchar(100) NOT NULL,
  `jenis_ruangan` enum('Kelas','Laboratorium','Perpustakaan','Kantin','Kamar Mandi','Lapangan','Ruang Guru','Ruang Kepala Sekolah','Ruang UKS','Lainnya') NOT NULL,
  `lokasi` varchar(100) DEFAULT NULL,
  `kapasitas` int DEFAULT NULL,
  `kondisi` enum('Baik','Rusak Ringan','Rusak Berat','Dalam Perbaikan') DEFAULT 'Baik',
  `deskripsi` text,
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data for table `ruangan`
--

INSERT INTO `ruangan` (`id_ruangan`, `kode_ruangan`, `nama_ruangan`, `jenis_ruangan`, `lokasi`, `kapasitas`, `kondisi`, `deskripsi`, `created_at`, `updated_at`) VALUES
(1, 'R-01', 'Ruang Kelas 10 RPL', 'Kelas', 'Lantai 1', 30, 'Baik', NULL, '2026-04-10 04:38:14', '2026-04-09 23:29:45'),
(2, 'R-02', 'Ruang Kelas 11 RPL', 'Kelas', 'Lantai 1', 30, 'Baik', NULL, '2026-04-10 04:38:14', '2026-04-10 04:38:14'),
(3, 'R-03', 'Ruang Kelas 12 RPL', 'Kelas', 'Lantai 1', 30, 'Baik', NULL, '2026-04-10 04:38:14', '2026-04-10 04:38:14'),
(4, 'LAB-01', 'Lab Komputer 1', 'Laboratorium', 'Lantai 2', 40, 'Baik', NULL, '2026-04-10 04:38:14', '2026-04-10 04:38:14'),
(5, 'LAB-02', 'Lab Komputer 2', 'Laboratorium', 'Lantai 2', 40, 'Baik', NULL, '2026-04-10 04:38:14', '2026-04-10 04:38:14'),
(6, 'PUSTAKA', 'Perpustakaan', 'Perpustakaan', 'Lantai 1', 100, 'Baik', NULL, '2026-04-10 04:38:14', '2026-04-10 04:38:14'),
(7, 'KANTIN', 'Kantin Sekolah', 'Kantin', 'Belakang', 200, 'Baik', NULL, '2026-04-10 04:38:14', '2026-04-10 04:38:14');

-- --------------------------------------------------------

--
-- Table structure for table `siswa`
--

CREATE TABLE `siswa` (
  `id` int NOT NULL,
  `user_id` int DEFAULT NULL,
  `nis` varchar(20) DEFAULT NULL,
  `nama` varchar(100) DEFAULT NULL,
  `kelas` varchar(10) DEFAULT NULL,
  `jurusan` varchar(50) DEFAULT NULL,
  `jenis_kelamin` enum('L','P') DEFAULT NULL,
  `tanggal_lahir` date DEFAULT NULL,
  `alamat` text,
  `no_hp` varchar(15) DEFAULT NULL,
  `foto` varchar(255) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `id_kelas` int DEFAULT NULL,
  `id_jurusan` int DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data for table `siswa`
--

INSERT INTO `siswa` (`id`, `user_id`, `nis`, `nama`, `kelas`, `jurusan`, `jenis_kelamin`, `tanggal_lahir`, `alamat`, `no_hp`, `foto`, `created_at`, `updated_at`, `id_kelas`, `id_jurusan`) VALUES
(1, 4, '2324102576', 'siswa', '12', 'rpl', 'L', '2026-04-10', 'cimahi', '081726517251', 'foto_profil/1775631955_download.jpg', '2026-04-06 23:26:35', '2026-04-10 02:20:06', NULL, NULL);

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `id` int NOT NULL,
  `email` varchar(100) DEFAULT NULL,
  `password` varchar(255) DEFAULT NULL,
  `role` enum('siswa','guru','admin','petugas') DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`id`, `email`, `password`, `role`, `created_at`, `updated_at`) VALUES
(2, 'admin@ukk2026.com', '$2y$12$eWoqe0yVBGoLlXUXDv3uhe8iCfrj2x8.nlXqkl1P0Wo.zpnL2SXYW', 'admin', '2026-04-06 22:07:15', '2026-04-07 23:22:34'),
(3, 'guru@ukk2026.com', '$2y$12$7BiJzILxr5sPfqqF5.PvJevaFjhNiDPIW4JcljU8r1qetGiE1gEcG', 'guru', '2026-04-06 23:01:42', '2026-04-10 01:03:32'),
(4, 'siswa@ukk2026.com', '$2y$12$kbN/E4gbNHlprdUqNBcM8uYHRDWfFWBoE1thX5zNnbBXjYKeOH9H2', 'siswa', '2026-04-06 23:26:35', '2026-04-10 02:20:06'),
(5, 'petugas@ukk2026.com', '$2y$12$XOD/RFRYOsVHiGzFo6e5aezvWLEe0XQ8CzCTpY79Mzbd7BKhUr/ke', 'petugas', '2026-04-10 02:06:15', '2026-04-10 02:48:01');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `aspirasi`
--
ALTER TABLE `aspirasi`
  ADD PRIMARY KEY (`id_aspirasi`),
  ADD KEY `user_id` (`user_id`),
  ADD KEY `id_kategori` (`id_kategori`),
  ADD KEY `id_ruangan` (`id_ruangan`);

--
-- Indexes for table `guru`
--
ALTER TABLE `guru`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `nip` (`nip`),
  ADD KEY `user_id` (`user_id`);

--
-- Indexes for table `history_status`
--
ALTER TABLE `history_status`
  ADD PRIMARY KEY (`id_history`),
  ADD KEY `id_aspirasi` (`id_aspirasi`),
  ADD KEY `diubah_oleh` (`diubah_oleh`);

--
-- Indexes for table `jurusan`
--
ALTER TABLE `jurusan`
  ADD PRIMARY KEY (`id_jurusan`),
  ADD UNIQUE KEY `kode_jurusan` (`kode_jurusan`);

--
-- Indexes for table `kategori`
--
ALTER TABLE `kategori`
  ADD PRIMARY KEY (`id_kategori`);

--
-- Indexes for table `kelas`
--
ALTER TABLE `kelas`
  ADD PRIMARY KEY (`id_kelas`),
  ADD UNIQUE KEY `nama_kelas` (`nama_kelas`),
  ADD KEY `id_jurusan` (`id_jurusan`);

--
-- Indexes for table `migrations`
--
ALTER TABLE `migrations`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `petugas`
--
ALTER TABLE `petugas`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `nip` (`nip`),
  ADD KEY `user_id` (`user_id`);

--
-- Indexes for table `progres`
--
ALTER TABLE `progres`
  ADD PRIMARY KEY (`id_progres`),
  ADD KEY `id_aspirasi` (`id_aspirasi`),
  ADD KEY `user_id` (`user_id`);

--
-- Indexes for table `ruangan`
--
ALTER TABLE `ruangan`
  ADD PRIMARY KEY (`id_ruangan`),
  ADD UNIQUE KEY `kode_ruangan` (`kode_ruangan`);

--
-- Indexes for table `siswa`
--
ALTER TABLE `siswa`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `nis` (`nis`),
  ADD KEY `user_id` (`user_id`),
  ADD KEY `id_kelas` (`id_kelas`),
  ADD KEY `id_jurusan` (`id_jurusan`);

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `email` (`email`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `aspirasi`
--
ALTER TABLE `aspirasi`
  MODIFY `id_aspirasi` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT for table `guru`
--
ALTER TABLE `guru`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `history_status`
--
ALTER TABLE `history_status`
  MODIFY `id_history` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT for table `jurusan`
--
ALTER TABLE `jurusan`
  MODIFY `id_jurusan` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;

--
-- AUTO_INCREMENT for table `kategori`
--
ALTER TABLE `kategori`
  MODIFY `id_kategori` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT for table `kelas`
--
ALTER TABLE `kelas`
  MODIFY `id_kelas` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=10;

--
-- AUTO_INCREMENT for table `migrations`
--
ALTER TABLE `migrations`
  MODIFY `id` int UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `petugas`
--
ALTER TABLE `petugas`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `progres`
--
ALTER TABLE `progres`
  MODIFY `id_progres` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;

--
-- AUTO_INCREMENT for table `ruangan`
--
ALTER TABLE `ruangan`
  MODIFY `id_ruangan` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;

--
-- AUTO_INCREMENT for table `siswa`
--
ALTER TABLE `siswa`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `aspirasi`
--
ALTER TABLE `aspirasi`
  ADD CONSTRAINT `aspirasi_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `aspirasi_ibfk_2` FOREIGN KEY (`id_kategori`) REFERENCES `kategori` (`id_kategori`) ON DELETE SET NULL,
  ADD CONSTRAINT `aspirasi_ibfk_3` FOREIGN KEY (`id_ruangan`) REFERENCES `ruangan` (`id_ruangan`) ON DELETE SET NULL;

--
-- Constraints for table `guru`
--
ALTER TABLE `guru`
  ADD CONSTRAINT `guru_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `history_status`
--
ALTER TABLE `history_status`
  ADD CONSTRAINT `history_status_ibfk_1` FOREIGN KEY (`id_aspirasi`) REFERENCES `aspirasi` (`id_aspirasi`) ON DELETE CASCADE,
  ADD CONSTRAINT `history_status_ibfk_2` FOREIGN KEY (`diubah_oleh`) REFERENCES `users` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `kelas`
--
ALTER TABLE `kelas`
  ADD CONSTRAINT `kelas_ibfk_1` FOREIGN KEY (`id_jurusan`) REFERENCES `jurusan` (`id_jurusan`) ON DELETE CASCADE;

--
-- Constraints for table `petugas`
--
ALTER TABLE `petugas`
  ADD CONSTRAINT `petugas_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `progres`
--
ALTER TABLE `progres`
  ADD CONSTRAINT `progres_ibfk_1` FOREIGN KEY (`id_aspirasi`) REFERENCES `aspirasi` (`id_aspirasi`) ON DELETE CASCADE,
  ADD CONSTRAINT `progres_ibfk_2` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `siswa`
--
ALTER TABLE `siswa`
  ADD CONSTRAINT `siswa_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `siswa_ibfk_2` FOREIGN KEY (`id_kelas`) REFERENCES `kelas` (`id_kelas`) ON DELETE SET NULL,
  ADD CONSTRAINT `siswa_ibfk_3` FOREIGN KEY (`id_jurusan`) REFERENCES `jurusan` (`id_jurusan`) ON DELETE SET NULL;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
