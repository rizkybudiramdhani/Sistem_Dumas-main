-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1:4306
-- Generation Time: Nov 09, 2025 at 03:38 PM
-- Server version: 10.4.32-MariaDB
-- PHP Version: 8.2.12

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `ditresnarkoba`
--

-- --------------------------------------------------------

--
-- Table structure for table `dumas_manual`
--

CREATE TABLE `dumas_manual` (
  `id` int(11) NOT NULL,
  `sumber` enum('Whatsapp','Surat','Telepone','Langsung','Email','Lainnya') NOT NULL,
  `tanggal_lapor` datetime NOT NULL,
  `nama_pelapor` varchar(100) NOT NULL,
  `kontak_pelapor` varchar(100) NOT NULL,
  `ringkasan` text NOT NULL,
  `tindaklanjut` text NOT NULL,
  `lampiran` varchar(200) DEFAULT NULL,
  `created_by` varchar(200) NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `dumas_manual`
--

INSERT INTO `dumas_manual` (`id`, `sumber`, `tanggal_lapor`, `nama_pelapor`, `kontak_pelapor`, `ringkasan`, `tindaklanjut`, `lampiran`, `created_by`, `created_at`) VALUES
(1, '', '2025-10-01 19:33:00', 'akbp dimas', '0895789312', 'keren kali kau', 'gassss', NULL, 'Akbp aziz', '2025-10-22 12:37:06');

-- --------------------------------------------------------

--
-- Table structure for table `kegiatan_ditbinmas`
--

CREATE TABLE `kegiatan_ditbinmas` (
  `id` int(11) NOT NULL,
  `tanggal` varchar(100) NOT NULL,
  `no_surat` varchar(100) NOT NULL,
  `kegiatan` varchar(255) NOT NULL,
  `lokasi` varchar(255) NOT NULL,
  `materi` varchar(100) NOT NULL,
  `file_laporan` varchar(100) NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `kegiatan_ditbinmas`
--

INSERT INTO `kegiatan_ditbinmas` (`id`, `tanggal`, `no_surat`, `kegiatan`, `lokasi`, `materi`, `file_laporan`, `created_at`) VALUES
(1, '2025-10-23', '2121', 'Melakukan pembicaraan kepada masayarakat', 'appwpaw', 'sdfsdfs', '11.jpg', '2025-10-23 10:43:53');

-- --------------------------------------------------------

--
-- Table structure for table `laporan_resnarkoba`
--

CREATE TABLE `laporan_resnarkoba` (
  `id_laporan` int(11) NOT NULL,
  `tanggal_operasi` datetime NOT NULL,
  `nama_tim` varchar(255) NOT NULL,
  `ketua_tim` varchar(255) NOT NULL,
  `jenis_operasi` varchar(100) NOT NULL,
  `lokasi` varchar(500) NOT NULL,
  `kronologi` text NOT NULL,
  `jumlah_tersangka` int(11) NOT NULL DEFAULT 0,
  `identitas_tersangka` text DEFAULT NULL,
  `barang_bukti` text NOT NULL,
  `file_bukti` varchar(255) DEFAULT NULL,
  `tindak_lanjut` text DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NULL DEFAULT NULL ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `laporan_resnarkoba`
--

INSERT INTO `laporan_resnarkoba` (`id_laporan`, `tanggal_operasi`, `nama_tim`, `ketua_tim`, `jenis_operasi`, `lokasi`, `kronologi`, `jumlah_tersangka`, `identitas_tersangka`, `barang_bukti`, `file_bukti`, `tindak_lanjut`, `created_at`, `updated_at`) VALUES
(1, '2024-11-01 10:00:00', 'Tim Opsnal Ditresnarkoba', 'AKBP Ahmad Yani', 'Penangkapan', 'Jl. Gatot Subroto No. 123, Medan', 'Tim melakukan pengintaian sejak pukul 08.00 WIB terhadap tersangka yang diduga sebagai pengedar narkoba. Setelah dilakukan pengamatan, tim melakukan penangkapan pada pukul 10.00 WIB dengan barang bukti yang ditemukan.', 2, '1. Ahmad Sulaiman, L, 35 tahun, Jl. Permata No. 5, Medan\n2. Budi Santoso, L, 28 tahun, Jl. Mangga Raya No. 10, Medan', '100 gram sabu-sabu, 50 butir ekstasi, 1 unit HP Samsung, Rp 5.000.000', NULL, 'Tersangka akan dilakukan pemeriksaan lebih lanjut dan diproses sesuai hukum yang berlaku.', '2025-11-07 13:13:28', NULL),
(2, '2024-11-03 14:30:00', 'Tim Reskrim Narkoba', 'Kompol Bambang S.', 'Penyidikan', 'Jl. Imam Bonjol No. 45, Medan', 'Berdasarkan informasi dari masyarakat, tim melakukan penyidikan terhadap rumah yang diduga sebagai tempat peredaran narkoba. Setelah dilakukan penggerebekan, ditemukan barang bukti narkoba jenis ganja kering.', 1, '1. Siti Nurhaliza, P, 32 tahun, Jl. Melati No. 7, Medan', '500 gram ganja kering, 1 unit timbangan digital, Plastik klip 100 buah', NULL, 'Barang bukti akan dilakukan lab test dan tersangka akan diproses lebih lanjut.', '2025-11-07 13:13:28', NULL),
(3, '2024-11-05 09:15:00', 'Tim Gabungan Narkoba', 'AKBP Hendra Kurniawan', 'Razia', 'Kawasan Pantai Cermin, Deli Serdang', 'Tim melakukan razia gabungan di kawasan wisata. Ditemukan beberapa pengunjung yang membawa narkoba jenis pil ekstasi.', 3, '1. Doni Pratama, L, 25 tahun, Medan\n2. Eka Putra, L, 27 tahun, Medan\n3. Fahmi Rizal, L, 24 tahun, Binjai', '75 butir pil ekstasi, 20 gram sabu-sabu, 3 unit HP', NULL, 'Tersangka akan diinterogasi dan diproses sesuai prosedur hukum.', '2025-11-07 13:13:28', NULL);

-- --------------------------------------------------------

--
-- Table structure for table `laporan_samapta`
--

CREATE TABLE `laporan_samapta` (
  `id_laporan` int(11) NOT NULL,
  `tanggal_lapor` datetime NOT NULL,
  `nama_petugas` varchar(255) NOT NULL,
  `nrp_petugas` varchar(255) NOT NULL,
  `pangkat_petugas` varchar(255) NOT NULL,
  `jenis_kegiatan` varchar(255) NOT NULL,
  `kronologi` text NOT NULL,
  `lokasi` varchar(255) NOT NULL,
  `jumlah_tersangka` int(11) NOT NULL,
  `rincian_barang_bukti` text NOT NULL,
  `file_bukti` text NOT NULL,
  `status_verifikasi` enum('baru','diproses','ditindaklanjuti','selesai') NOT NULL,
  `tanggapan_resnarkoba` text NOT NULL,
  `tanggal_tangapan` datetime NOT NULL,
  `asal_laporan` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `laporan_samapta`
--

INSERT INTO `laporan_samapta` (`id_laporan`, `tanggal_lapor`, `nama_petugas`, `nrp_petugas`, `pangkat_petugas`, `jenis_kegiatan`, `kronologi`, `lokasi`, `jumlah_tersangka`, `rincian_barang_bukti`, `file_bukti`, `status_verifikasi`, `tanggapan_resnarkoba`, `tanggal_tangapan`, `asal_laporan`) VALUES
(1, '2025-10-22 20:37:00', 'Aiptu Rahmat Sulaiman', '85010123', 'Aiptu', 'Pengawalan', 'No. Sprint: sprin 200\n\naman terkendali', 'tanjung morawa dusun 1 jln.sei blumai', 5, '20 gram sabu sabu', 'bukti_68f924d745f63_1761158359.png', 'baru', '', '0000-00-00 00:00:00', 'Ditsamapta Internal'),
(2, '2025-10-22 20:37:00', 'Aiptu Rahmat Sulaiman', '85010123', 'Aiptu', 'Pengawalan', 'No. Sprint: sprin 200\n\naman terkendali', 'tanjung morawa dusun 1 jln.sei blumai', 5, '20 gram sabu sabu', 'bukti_68f9258767fe6_1761158535.png', 'baru', '', '0000-00-00 00:00:00', 'Ditsamapta Internal'),
(3, '2025-10-22 20:37:00', 'Aiptu Rahmat Sulaiman', '85010123', 'Aiptu', 'Pengawalan', 'No. Sprint: sprin 200\n\naman terkendali', 'tanjung morawa dusun 1 jln.sei blumai', 5, '20 gram sabu sabu', 'bukti_68f925e3b5e52_1761158627.png', 'baru', '', '0000-00-00 00:00:00', 'Ditsamapta Internal');

-- --------------------------------------------------------

--
-- Table structure for table `tabel_berita`
--

CREATE TABLE `tabel_berita` (
  `id_berita` int(11) NOT NULL,
  `judul_berita` varchar(500) NOT NULL,
  `link_berita` varchar(500) NOT NULL,
  `deskripsi_berita` text NOT NULL,
  `gambar_berita` varchar(200) NOT NULL,
  `tanggal_upload` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `tabel_berita`
--

INSERT INTO `tabel_berita` (`id_berita`, `judul_berita`, `link_berita`, `deskripsi_berita`, `gambar_berita`, `tanggal_upload`) VALUES
(2, 'Komdigi Blokir Zangi yang Sempat Disebut-sebut di Kasus Narkoba Ammar Zoni', 'https://news.detik.com/berita/d-8175766/3-fakta-dakwaan-ungkap-ammar-zoni-jualan-narkoba-di-penjara', 'Ammar zoni terjerat kasus narkoba kembali dan menjadi perbincangan yang hangat pasalnya Ammar zoni akan dikirim ke nusakambangan', '1762625050_amar.jpeg', '2025-11-08 18:04:10');

-- --------------------------------------------------------

--
-- Table structure for table `tabel_laporan`
--

CREATE TABLE `tabel_laporan` (
  `id_laporan` int(11) NOT NULL,
  `id_user` int(11) DEFAULT NULL,
  `id_tim` int(11) DEFAULT NULL,
  `laporan` text NOT NULL,
  `lokasi` varchar(255) NOT NULL,
  `gambar` varchar(255) NOT NULL,
  `video` varchar(255) NOT NULL,
  `tanggal_lapor` datetime NOT NULL DEFAULT current_timestamp(),
  `status_laporan` enum('baru','diproses','selesai','') NOT NULL DEFAULT 'baru',
  `judul_laporan` varchar(200) NOT NULL,
  `tanggapan_admin` text NOT NULL,
  `tanggal_tanggapan` datetime NOT NULL,
  `nama` varchar(200) NOT NULL,
  `no_hp` int(20) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `tabel_laporan`
--

INSERT INTO `tabel_laporan` (`id_laporan`, `id_user`, `id_tim`, `laporan`, `lokasi`, `gambar`, `video`, `tanggal_lapor`, `status_laporan`, `judul_laporan`, `tanggapan_admin`, `tanggal_tanggapan`, `nama`, `no_hp`) VALUES
(5, 2, NULL, 'awaw', 'jermal 5 mendan', '', '', '2025-10-21 15:29:55', 'diproses', 'peredaran narkoba di jermal', 'baik kami akan proses laporan yang anda berikan mohon nanti menerima hubungan dari kami', '2025-10-22 22:59:20', '', 0),
(6, 2, NULL, 'disebuah daerah terjadi peredaran narkoba dan obat obatan terlarang', 'tanjung morawa dusun 1 jln.sei blumai', '', '', '2025-10-22 18:40:47', 'diproses', 'peredaran obat terlarang', 'baik kami akan tidak lanjuti', '2025-10-26 20:30:47', '', 0),
(7, 2, NULL, 'disebuah daerah terjadi peredaran narkoba dan obat obatan terlarang', 'tanjung morawa dusun 1 jln.sei blumai', '', '', '2025-10-22 18:44:23', '', 'peredaran obat terlarang', 'sudah kami tangani', '2025-11-02 22:20:02', '', 0),
(11, NULL, NULL, 'banyak yg makek', 'jermal 5 mendan', '[]', '', '2025-10-26 21:56:22', 'baru', 'peredaran obat terlarang', '', '0000-00-00 00:00:00', 'adli fadhlurrahman aziz', 896434113),
(12, NULL, NULL, 'dawdawdddddddddddddddddddddddddddddddddddddddddddddddddddddddddddddddddddddddddddddddddddddddddddddddddddddddddddddddddddddddda', 'tanjung morawa dusun 1 jln.sei blumai', 'uploads/pengaduan/gambar_69105fc478bc9_1762680772.png', '', '2025-11-09 16:32:52', 'baru', 'peredaran narkoba di jermal', '', '0000-00-00 00:00:00', 'adli fadhlurrahman aziz', 896434113),
(13, NULL, NULL, 'adwandiansdwd', 'tanjung morawa dusun 1 jln.sei blumai', 'bukti_691061cd07246_1762681293.png', '', '2025-11-09 16:41:33', 'baru', 'narkoba', '', '0000-00-00 00:00:00', 'adli fadhlurrahman aziz', 2147483647),
(14, NULL, NULL, 'wadsdwadw', 'tanjung morawa dusun 1', 'bukti_691061ece5120_1762681324.png', '', '2025-11-09 16:42:04', 'baru', 'narkoba', '', '0000-00-00 00:00:00', 'adli fadhlurrahman aziz', 2147483647),
(15, NULL, NULL, 'dawda', 'tanjung morawa dusun 1 jln.sei blumai', 'uploads/lapor_1762695432_691099085fefb.jpg', '', '2025-11-09 20:37:12', 'baru', 'narkoba', '', '0000-00-00 00:00:00', 'adli fadhlurrahman aziz', 2147483647);

-- --------------------------------------------------------

--
-- Table structure for table `tabel_pengungkapan`
--

CREATE TABLE `tabel_pengungkapan` (
  `id` int(11) NOT NULL,
  `jumlah_laporan` int(50) NOT NULL,
  `tersangka_penyidikan` int(50) NOT NULL,
  `tersangka_rehabilitasi` int(20) NOT NULL,
  `bb_sabu` decimal(10,2) NOT NULL,
  `bb_ekstasi` decimal(10,3) NOT NULL,
  `bb_ganja` decimal(10,3) NOT NULL,
  `bb_pohon_ganja` decimal(10,3) NOT NULL,
  `bb_kokain` decimal(10,3) NOT NULL,
  `bb_heroin` decimal(10,3) NOT NULL,
  `bb_happy_five` int(20) NOT NULL,
  `bb_pil_alprazolam` int(20) NOT NULL,
  `bb_ketamin` int(20) NOT NULL,
  `bb_liquid_vape` int(20) NOT NULL,
  `last_updated` datetime NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `tabel_pengungkapan`
--

INSERT INTO `tabel_pengungkapan` (`id`, `jumlah_laporan`, `tersangka_penyidikan`, `tersangka_rehabilitasi`, `bb_sabu`, `bb_ekstasi`, `bb_ganja`, `bb_pohon_ganja`, `bb_kokain`, `bb_heroin`, `bb_happy_five`, `bb_pil_alprazolam`, `bb_ketamin`, `bb_liquid_vape`, `last_updated`) VALUES
(1, 200, 200, 200, 10.00, 5.000, 20.000, 5.000, 0.080, 0.180, 5, 5, 5, 5, '2025-11-09 21:33:40');

-- --------------------------------------------------------

--
-- Table structure for table `tabel_statistik`
--

CREATE TABLE `tabel_statistik` (
  `id` int(11) NOT NULL,
  `jumlah_laporan` int(11) NOT NULL,
  `jumlah_penangkapan` int(11) NOT NULL,
  `jumlah_barang_bukti` int(11) NOT NULL,
  `jumlah_tim` int(11) NOT NULL,
  `last_updated` datetime NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `tabel_statistik`
--

INSERT INTO `tabel_statistik` (`id`, `jumlah_laporan`, `jumlah_penangkapan`, `jumlah_barang_bukti`, `jumlah_tim`, `last_updated`) VALUES
(1, 0, 0, 0, 3, '0000-00-00 00:00:00'),
(2, 200, 200, 200, 3, '2025-10-22 14:11:16');

-- --------------------------------------------------------

--
-- Table structure for table `tabel_tim`
--

CREATE TABLE `tabel_tim` (
  `id_tim` int(11) NOT NULL,
  `nama` varchar(100) NOT NULL,
  `username` varchar(50) NOT NULL,
  `password` varchar(255) NOT NULL,
  `role` enum('ditresnarkoba','ditbinmas','ditsamapta','') NOT NULL,
  `last_login` datetime NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `tabel_tim`
--

INSERT INTO `tabel_tim` (`id_tim`, `nama`, `username`, `password`, `role`, `last_login`) VALUES
(2, 'AKBP TONI', 'toni', '$2y$10$E.V4/csydyhLqooaKKl/8.mBrked01YJjiYiPMQVzfC9jiLkbKPaa', 'ditresnarkoba', '2025-10-19 15:02:57'),
(3, 'AKBP rizal', 'rizal78', '$2y$10$E.V4/csydyhLqooaKKl/8.mBrked01YJjiYiPMQVzfC9jiLkbKPaa', 'ditsamapta', '2025-10-22 20:45:15'),
(4, 'Beni syahreza', 'benisyah', '$2y$10$E.V4/csydyhLqooaKKl/8.mBrked01YJjiYiPMQVzfC9jiLkbKPaa', 'ditbinmas', '2025-10-23 04:12:24');

-- --------------------------------------------------------

--
-- Table structure for table `tabel_users`
--

CREATE TABLE `tabel_users` (
  `id_users` int(11) NOT NULL,
  `nama` varchar(100) NOT NULL,
  `username` varchar(50) NOT NULL,
  `email` varchar(100) NOT NULL,
  `telepon` varchar(20) NOT NULL,
  `password` varchar(255) NOT NULL,
  `tanggal_daftar` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  `status` enum('masyarakat') NOT NULL DEFAULT 'masyarakat'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `tabel_users`
--

INSERT INTO `tabel_users` (`id_users`, `nama`, `username`, `email`, `telepon`, `password`, `tanggal_daftar`, `status`) VALUES
(1, 'adli fadhlurrahman aziz', 'adli', 'adli@gmail.com', '0891 7829 7291', '$2y$10$bbhN5hF0A8VZkB8KxMZ0oeb.jDUvXqH6ZpY4nM3qkO6/8PHqkOaVa', '2025-10-17 14:30:12', 'masyarakat'),
(2, 'adli fadhlurrahman aziz', 'aziz', 'dodidoyo199@gmail.com', '0897222223', '$2y$10$E.V4/csydyhLqooaKKl/8.mBrked01YJjiYiPMQVzfC9jiLkbKPaa', '2025-10-21 15:37:18', ''),
(4, 'rizal', 'rizal', 'nabila17@gmail.com', '0897222223', '$2y$10$JF60.NKZYL6uHZkBBow8ledRV7SCrBQ/Fa1L5E8ST1r4UBipYAbSy', '2025-10-22 18:47:04', ''),
(5, 'Beni syahreza', 'beni', 'beniysyah99@gmail.com', '0898976389', '$2y$10$q0JW11SsP3YT.nmS/MqbKOwcdVnV.OraSMyBy7mEa3mfE46cKS8pa', '2025-10-23 02:12:00', ''),
(6, 'Rizky Budi Ramdhani', 'Budi', 'rizkymedan04@gmail.com', '085837633968', '$2y$10$iEd66kg8KR0sSzlqFx/vdOqKRC5/Bj1TLVXRTTvHzrlDU6ehKGv3u', '2025-10-23 10:50:31', ''),
(7, 'budi', 'budi', 'budi@gmail.com', '085183223968', '$2y$10$hR4I9j0p6df/PxCsRAgXt.oFzmTb69QxjKK41TDn1OJjyRug.40OS', '2025-10-23 11:00:13', 'masyarakat'),
(8, 'adli aziz', 'adli', 'adliaziz17022004@gmail.com', '0898976389', '$2y$10$i25Wl0/XlF26aLgmIfSBTu.HGzk2GLAwduOfQDRth/WLJtW2/64mi', '2025-11-09 14:08:00', '');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `dumas_manual`
--
ALTER TABLE `dumas_manual`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `kegiatan_ditbinmas`
--
ALTER TABLE `kegiatan_ditbinmas`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `laporan_resnarkoba`
--
ALTER TABLE `laporan_resnarkoba`
  ADD PRIMARY KEY (`id_laporan`),
  ADD KEY `idx_tanggal` (`tanggal_operasi`),
  ADD KEY `idx_jenis` (`jenis_operasi`),
  ADD KEY `idx_created` (`created_at`);

--
-- Indexes for table `laporan_samapta`
--
ALTER TABLE `laporan_samapta`
  ADD PRIMARY KEY (`id_laporan`);

--
-- Indexes for table `tabel_berita`
--
ALTER TABLE `tabel_berita`
  ADD PRIMARY KEY (`id_berita`);

--
-- Indexes for table `tabel_laporan`
--
ALTER TABLE `tabel_laporan`
  ADD PRIMARY KEY (`id_laporan`),
  ADD KEY `id_user` (`id_user`),
  ADD KEY `id_tim` (`id_tim`);

--
-- Indexes for table `tabel_pengungkapan`
--
ALTER TABLE `tabel_pengungkapan`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `tabel_statistik`
--
ALTER TABLE `tabel_statistik`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `tabel_tim`
--
ALTER TABLE `tabel_tim`
  ADD PRIMARY KEY (`id_tim`);

--
-- Indexes for table `tabel_users`
--
ALTER TABLE `tabel_users`
  ADD PRIMARY KEY (`id_users`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `dumas_manual`
--
ALTER TABLE `dumas_manual`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `kegiatan_ditbinmas`
--
ALTER TABLE `kegiatan_ditbinmas`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `laporan_resnarkoba`
--
ALTER TABLE `laporan_resnarkoba`
  MODIFY `id_laporan` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `laporan_samapta`
--
ALTER TABLE `laporan_samapta`
  MODIFY `id_laporan` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `tabel_berita`
--
ALTER TABLE `tabel_berita`
  MODIFY `id_berita` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `tabel_laporan`
--
ALTER TABLE `tabel_laporan`
  MODIFY `id_laporan` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=17;

--
-- AUTO_INCREMENT for table `tabel_pengungkapan`
--
ALTER TABLE `tabel_pengungkapan`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `tabel_statistik`
--
ALTER TABLE `tabel_statistik`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `tabel_tim`
--
ALTER TABLE `tabel_tim`
  MODIFY `id_tim` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT for table `tabel_users`
--
ALTER TABLE `tabel_users`
  MODIFY `id_users` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=9;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `tabel_laporan`
--
ALTER TABLE `tabel_laporan`
  ADD CONSTRAINT `tabel_laporan_ibfk_1` FOREIGN KEY (`id_user`) REFERENCES `tabel_users` (`id_users`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `tabel_laporan_ibfk_2` FOREIGN KEY (`id_tim`) REFERENCES `tabel_tim` (`id_tim`) ON DELETE SET NULL ON UPDATE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
