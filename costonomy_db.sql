-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Jun 17, 2025 at 09:35 AM
-- Server version: 10.4.28-MariaDB
-- PHP Version: 8.2.4

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `costonomy_db`
--

-- --------------------------------------------------------

--
-- Table structure for table `budgets`
--

CREATE TABLE `budgets` (
  `id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `category` varchar(100) NOT NULL,
  `amount` decimal(10,2) NOT NULL,
  `enabled` tinyint(1) DEFAULT 1,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `chart_data` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_bin DEFAULT NULL CHECK (json_valid(`chart_data`))
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

--
-- Dumping data for table `budgets`
--

INSERT INTO `budgets` (`id`, `user_id`, `category`, `amount`, `enabled`, `created_at`, `chart_data`) VALUES
(31, 2, 'Tempat Tinggal', 600000.00, 1, '2025-06-16 17:27:13', NULL),
(32, 2, 'Makanan', 400000.00, 1, '2025-06-16 17:27:13', NULL),
(33, 2, 'Transportasi', 200000.00, 1, '2025-06-16 17:27:13', NULL),
(34, 2, 'Hiburan', 200000.00, 1, '2025-06-16 17:27:13', NULL),
(35, 2, 'Utilitas', 200000.00, 1, '2025-06-16 17:27:13', NULL),
(36, 2, 'Kesehatan', 200000.00, 1, '2025-06-16 17:27:13', NULL),
(187, 3, 'Tempat Tinggal', 600000.00, 1, '2025-06-16 19:43:25', '{\"percentage\":37.5,\"color\":\"#9c4dcc\"}'),
(188, 3, 'Makanan', 400000.00, 1, '2025-06-16 19:43:25', '{\"percentage\":25,\"color\":\"#6a1b9a\"}'),
(189, 3, 'Transportasi', 200000.00, 1, '2025-06-16 19:43:25', '{\"percentage\":12.5,\"color\":\"#38006b\"}'),
(190, 3, 'Hiburan', 200000.00, 1, '2025-06-16 19:43:25', '{\"percentage\":12.5,\"color\":\"#9575cd\"}'),
(191, 3, 'Utilitas', 200000.00, 1, '2025-06-16 19:43:25', '{\"percentage\":12.5,\"color\":\"#7e57c2\"}'),
(192, 3, 'Kesehatan', 200000.00, 0, '2025-06-16 19:43:25', '{\"percentage\":12.5,\"color\":\"#5e35b1\"}'),
(439, 4, 'Tempat Tinggal', 1500000.00, 1, '2025-06-17 07:17:31', NULL),
(440, 4, 'Makanan', 1000000.00, 1, '2025-06-17 07:17:31', NULL),
(441, 4, 'Transportasi', 500000.00, 1, '2025-06-17 07:17:31', NULL),
(442, 4, 'Hiburan', 500000.00, 1, '2025-06-17 07:17:31', NULL),
(443, 4, 'Utilitas', 750000.00, 1, '2025-06-17 07:17:31', NULL),
(444, 4, 'Kesehatan', 750000.00, 1, '2025-06-17 07:17:31', NULL),
(451, 6, 'Tempat Tinggal', 1500000.00, 1, '2025-06-17 07:22:11', NULL),
(452, 6, 'Makanan', 1000000.00, 1, '2025-06-17 07:22:11', NULL),
(453, 6, 'Transportasi', 500000.00, 1, '2025-06-17 07:22:11', NULL),
(454, 6, 'Hiburan', 500000.00, 1, '2025-06-17 07:22:11', NULL),
(455, 6, 'Utilitas', 750000.00, 1, '2025-06-17 07:22:11', NULL),
(456, 6, 'Kesehatan', 750000.00, 1, '2025-06-17 07:22:11', NULL),
(469, 7, 'Tempat Tinggal', 500000.00, 1, '2025-06-17 07:26:08', NULL),
(470, 7, 'Makanan', 400000.00, 1, '2025-06-17 07:26:08', NULL),
(471, 7, 'Transportasi', 200000.00, 1, '2025-06-17 07:26:08', NULL),
(472, 7, 'Hiburan', 200000.00, 1, '2025-06-17 07:26:08', NULL),
(473, 7, 'Utilitas', 300000.00, 1, '2025-06-17 07:26:08', NULL),
(474, 7, 'Kesehatan', 300000.00, 1, '2025-06-17 07:26:08', NULL),
(487, 8, 'Tempat Tinggal', 500000.00, 1, '2025-06-17 07:30:47', NULL),
(488, 8, 'Makanan', 400000.00, 1, '2025-06-17 07:30:47', NULL),
(489, 8, 'Transportasi', 200000.00, 1, '2025-06-17 07:30:47', NULL),
(490, 8, 'Hiburan', 200000.00, 1, '2025-06-17 07:30:47', NULL),
(491, 8, 'Utilitas', 300000.00, 1, '2025-06-17 07:30:47', NULL),
(492, 8, 'Kesehatan', 300000.00, 1, '2025-06-17 07:30:47', NULL);

-- --------------------------------------------------------

--
-- Table structure for table `categories`
--

CREATE TABLE `categories` (
  `id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `name` varchar(100) NOT NULL,
  `enabled` tinyint(1) DEFAULT 1,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

--
-- Dumping data for table `categories`
--

INSERT INTO `categories` (`id`, `user_id`, `name`, `enabled`, `created_at`) VALUES
(1, 2, 'Tempat Tinggal', 1, '2025-06-16 13:06:25'),
(2, 2, 'Makanan', 1, '2025-06-16 13:06:25'),
(3, 2, 'Transportasi', 1, '2025-06-16 13:06:25'),
(4, 2, 'Hiburan', 1, '2025-06-16 13:06:25'),
(5, 2, 'Utilitas', 1, '2025-06-16 13:06:25'),
(6, 2, 'Kesehatan', 1, '2025-06-16 13:06:25'),
(145, 3, 'Tempat Tinggal', 1, '2025-06-16 19:43:25'),
(146, 3, 'Makanan', 1, '2025-06-16 19:43:25'),
(147, 3, 'Transportasi', 1, '2025-06-16 19:43:25'),
(148, 3, 'Hiburan', 1, '2025-06-16 19:43:25'),
(149, 3, 'Utilitas', 1, '2025-06-16 19:43:25'),
(150, 3, 'Kesehatan', 0, '2025-06-16 19:43:25'),
(235, 4, 'Tempat Tinggal', 1, '2025-06-16 20:02:35'),
(236, 4, 'Makanan', 1, '2025-06-16 20:02:35'),
(237, 4, 'Transportasi', 1, '2025-06-16 20:02:35'),
(238, 4, 'Hiburan', 1, '2025-06-16 20:02:35'),
(239, 4, 'Utilitas', 1, '2025-06-16 20:02:35'),
(240, 4, 'Kesehatan', 1, '2025-06-16 20:02:35');

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `id` int(11) NOT NULL,
  `name` varchar(100) DEFAULT NULL,
  `email` varchar(255) NOT NULL,
  `password` varchar(255) NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `monthly_income` decimal(12,2) DEFAULT 0.00
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`id`, `name`, `email`, `password`, `created_at`, `monthly_income`) VALUES
(1, NULL, 'malih@gmail.com', '$2y$10$jNUBWjDIyeFPC6weyXtdOOfoCmnBZVjtChxUoaNhWNYGrH8sSewFu', '2025-06-16 12:50:02', 0.00),
(2, NULL, 'lopyu@gmail.com', '$2y$10$Dik6lKhnEc.ct5mjK4tIye7vqsfFEPX9JKFwcvs3SyT9vh4zACshG', '2025-06-16 13:06:25', 0.00),
(3, 'Sugiono', 'sugiono@gmail.com', '$2y$10$b30dDpzk4Tkvlzi1a70Os.h8F0VugyTQbiYQ4s7JNihWhaTxCmiv2', '2025-06-16 17:55:23', 2000000.00),
(4, 'Bunga', 'bunga@gmail.com', '$2y$10$VdfX9pDFmL3wXIAjPrzlfOjxTxEK41Emrv4Y9eQQ3H32wl0GtACzy', '2025-06-16 19:44:25', 5000000.00),
(5, 'bayu', 'bayu@gmail.com', '$2y$10$.Be9BC3YSp0Scil66N/fNetNpxcD4ieKL7F.YhUAn0X/30SwExlYC', '2025-06-17 06:58:56', 5000000.00),
(6, 'ava', 'ava@gmail.com', '$2y$10$kZsKoDdOKf3E5YacHEnC2uib3blkAJ8JgeCbVCFCcxwdakcmbSKOy', '2025-06-17 07:21:50', 5000000.00),
(7, 'adam', 'adam@gmail.com', '$2y$10$mDgrCwtrjv8V7JnaFqTkSeevPm.7ZOfg8xB5i/DO4Xc5HXEM/9tLa', '2025-06-17 07:25:06', 2000000.00),
(8, 'caca', 'caca@gmail.com', '$2y$10$tZqplUYaU5xGyYuf.4YtyOzO4G1xeOVESUfOZy7xmxP6atfzGsH82', '2025-06-17 07:29:42', 2000000.00);

--
-- Indexes for dumped tables
--

--
-- Indexes for table `budgets`
--
ALTER TABLE `budgets`
  ADD PRIMARY KEY (`id`),
  ADD KEY `user_id` (`user_id`);

--
-- Indexes for table `categories`
--
ALTER TABLE `categories`
  ADD PRIMARY KEY (`id`),
  ADD KEY `user_id` (`user_id`);

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
-- AUTO_INCREMENT for table `budgets`
--
ALTER TABLE `budgets`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=493;

--
-- AUTO_INCREMENT for table `categories`
--
ALTER TABLE `categories`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=241;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=9;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `budgets`
--
ALTER TABLE `budgets`
  ADD CONSTRAINT `budgets_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `categories`
--
ALTER TABLE `categories`
  ADD CONSTRAINT `categories_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
