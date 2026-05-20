-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1:3307
-- Generation Time: Apr 13, 2026 at 05:18 AM
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
-- Database: `kas_kelas_net`
--

-- --------------------------------------------------------

--
-- Table structure for table `murid`
--

CREATE TABLE `murid` (
  `id_murid` int(11) NOT NULL,
  `nama` varchar(100) NOT NULL,
  `kelas` varchar(20) NOT NULL,
  `nisn` int(50) NOT NULL,
  `status` enum('aktif','nonaktif','','') NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `murid`
--

INSERT INTO `murid` (`id_murid`, `nama`, `kelas`, `nisn`, `status`) VALUES
(7, 'Adha Putra P', 'XI PPLG1', 0, 'aktif'),
(8, 'Akram Ziyad', 'XI PPLG1', 0, 'aktif'),
(9, 'Andika Esda S', 'XI PPLG1', 0, 'aktif'),
(10, 'Andika Rizky F', 'XI PPLG1', 0, 'aktif'),
(11, 'Andira Rizky M', 'XI PPLG1', 0, 'aktif'),
(12, 'Daffa Aditya W', 'XI PPLG1', 0, 'aktif'),
(13, 'Dewa Putra H', 'XI PPLG1', 0, 'aktif'),
(14, 'Faiz Aprianda', 'XI PPLG1', 0, 'aktif'),
(15, 'Fardan Rabbani', 'XI PPLG1', 0, 'aktif'),
(16, 'Grace Aviolyn F', 'XI PPLG1', 0, 'aktif'),
(17, 'Islamezra Ramadhan', 'XI PPLG1', 0, 'aktif'),
(18, 'Jayden Dwi N', 'XI PPLG1', 0, 'aktif'),
(19, 'Kenziy Triesha Abner T', 'XI PPLG1', 0, 'aktif'),
(20, 'Luis Saputra Pratama', 'XI PPLG1', 0, 'aktif'),
(21, 'Lutfi Zaki E', 'XI PPLG1', 0, 'aktif'),
(22, 'Maulid Ahmad M', 'XI PPLG1', 0, 'aktif'),
(23, 'Muhammad Fardan', 'XI PPLG1', 0, 'aktif'),
(24, 'Muhammad Fawwaz A', 'XI PPLG1', 0, 'aktif'),
(25, 'Muhammad Gilang P', 'XI PPLG1', 0, 'aktif'),
(26, 'Muhammad Rafi', 'XI PPLG1', 0, 'aktif'),
(27, 'Muhammad Rhofiq F', 'XI PPLG1', 0, 'aktif'),
(28, 'Muhammad Yasin', 'XI PPLG1', 0, 'aktif'),
(29, 'Muhammad Zaky M', 'XI PPLG1', 0, 'aktif'),
(30, 'Nialah Fakhiran H', 'XI PPLG1', 0, 'aktif'),
(31, 'Raisatunnisa', 'XI PPLG1', 0, 'aktif'),
(32, 'Rizky Nafis F', 'XI PPLG1', 0, 'aktif'),
(33, 'Satria Rakha D', 'XI PPLG1', 0, 'aktif'),
(34, 'Shah Kunti M', 'XI PPLG1', 0, 'aktif'),
(35, 'Tazkia Alya P', 'XI PPLG1', 0, 'aktif'),
(36, 'Vilant Arnhezky J', 'XI PPLG1', 0, 'aktif'),
(37, 'Yusuf Ahmad R', 'XI PPLG1', 0, 'aktif'),
(38, 'Zahnaya Adinda S', 'XI PPLG1', 0, 'aktif');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `murid`
--
ALTER TABLE `murid`
  ADD PRIMARY KEY (`id_murid`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `murid`
--
ALTER TABLE `murid`
  MODIFY `id_murid` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=39;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
