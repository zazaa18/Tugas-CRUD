-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Sep 29, 2026 at 05:18 PM
-- Server version: 10.4.32-MariaDB
-- PHP Version: 8.0.30

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `toko_kue`
--

-- --------------------------------------------------------

--
-- Table structure for table `admin`
--

CREATE TABLE `admin` (
  `id` int(11) NOT NULL,
  `nama` varchar(100) NOT NULL,
  `username` varchar(50) NOT NULL,
  `password` varchar(255) NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `admin`
--

INSERT INTO `admin` (`id`, `nama`, `username`, `password`, `created_at`) VALUES
(1, 'Administrator Toko', 'admin', '$2y$10$A.8sX9iW39eE3mta7W2ghOXw1zWVdpUl0nRnn7I1n5gSsnhLNy6Eu', '2026-09-28 10:31:20');

-- --------------------------------------------------------

--
-- Table structure for table `produk`
--

CREATE TABLE `produk` (
  `id` int(11) NOT NULL,
  `nama` varchar(150) NOT NULL,
  `kategori` varchar(50) NOT NULL,
  `harga` int(11) NOT NULL,
  `deskripsi` text NOT NULL,
  `gambar` varchar(255) DEFAULT 'default.jpg',
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `produk`
--

INSERT INTO `produk` (`id`, `nama`, `kategori`, `harga`, `deskripsi`, `gambar`, `created_at`) VALUES
(1, 'Choco Lava Cake', 'Cake', 25000, 'Kue bolu cokelat panggang dengan lelehan cokelat hangat yang lumer di dalamnya.', 'choco-lava.jpg', '2026-09-28 08:24:36'),
(2, 'Cheesecake Oreo', 'Dessert', 35000, 'Perpaduan biskuit Oreo renyah dengan krim manis yang creamy dan taburan Oreo di atasnya.', 'cheesecake-oreo.jpg', '2026-09-28 08:24:36'),
(3, 'Pancake Strawberry', 'Cake', 20000, 'Pancake lembut dengan madu manis dan potongan stroberi segar yang menggugah selera.', 'pancake-strawberi.jpg', '2026-09-28 08:24:36'),
(4, 'Donat Tiramisu', 'Bread', 12000, 'Donat empuk dengan balutan krim tiramisu manis beraroma kopi lembut.', 'donat-tiramisu.jpg', '2026-09-28 08:24:36'),
(5, 'Bolu Pisang Keju', 'Bread', 30000, 'Bolu panggang pisang asli yang legit dan wangi dengan taburan keju parut renyah.', 'bolu-pisang.jpg', '2026-09-28 08:24:36'),
(6, 'Muffin Chocochip', 'Cake', 15000, 'Kue muffin cokelat dan empuk dengan taburan butiran chocochip melimpah. yummy', 'muffin-chocochip.jpg', '2026-09-28 08:24:36');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `admin`
--
ALTER TABLE `admin`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `username` (`username`);

--
-- Indexes for table `produk`
--
ALTER TABLE `produk`
  ADD PRIMARY KEY (`id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `admin`
--
ALTER TABLE `admin`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `produk`
--
ALTER TABLE `produk`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=13;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
