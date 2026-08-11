-- phpMyAdmin SQL Dump
-- version 5.2.0
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Jun 22, 2026 at 07:46 AM
-- Server version: 10.4.25-MariaDB
-- PHP Version: 8.1.10

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `pollution`
--

-- --------------------------------------------------------

--
-- Table structure for table `admin`
--

CREATE TABLE `admin` (
  `id` int(11) NOT NULL,
  `username` varchar(50) NOT NULL,
  `email` varchar(100) NOT NULL,
  `password` varchar(255) NOT NULL,
  `full_name` varchar(100) NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `last_login` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dumping data for table `admin`
--

INSERT INTO `admin` (`id`, `username`, `email`, `password`, `full_name`, `created_at`, `last_login`) VALUES
(1, 'admin', 'admin@kku.edu.sa', '$2y$10$Hk30H2PN/fR3TMM52amuyeexjhF1L8gFlPgOwD8iiOYEheE986VXe', 'System Administrator', '2025-09-26 20:56:23', '2026-04-17 04:50:27');

-- --------------------------------------------------------

--
-- Table structure for table `pollution_data`
--

CREATE TABLE `pollution_data` (
  `id` int(11) NOT NULL,
  `location` varchar(100) NOT NULL,
  `air_quality_index` decimal(5,2) DEFAULT NULL,
  `water_quality_index` decimal(5,2) DEFAULT NULL,
  `visual_pollution_score` decimal(5,2) DEFAULT NULL,
  `tourist_density` decimal(5,2) DEFAULT NULL,
  `date_recorded` date NOT NULL,
  `season` varchar(20) NOT NULL,
  `notes` text DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dumping data for table `pollution_data`
--

INSERT INTO `pollution_data` (`id`, `location`, `air_quality_index`, `water_quality_index`, `visual_pollution_score`, `tourist_density`, `date_recorded`, `season`, `notes`, `created_at`) VALUES
(1, 'Abha', '75.50', '82.30', '68.70', '85.20', '2024-01-15', 'Winter', 'Good air quality during winter season', '2025-09-26 20:56:23'),
(2, 'Khamis Mushait', '68.20', '79.10', '72.40', '78.90', '2024-01-20', 'Winter', 'Moderate pollution levels', '2025-09-26 20:56:23'),
(3, 'Al-Namas', '71.80', '85.60', '65.30', '72.10', '2024-01-25', 'Winter', 'Excellent water quality', '2025-09-26 20:56:23'),
(4, 'Abha', '45.30', '67.80', '52.10', '95.70', '2024-07-10', 'Summer', 'High tourist density affecting air quality', '2025-09-26 20:56:23'),
(5, 'Khamis Mushait', '48.70', '71.20', '58.90', '89.30', '2024-07-15', 'Summer', 'Increased pollution due to summer tourism', '2025-09-26 20:56:23'),
(6, 'Al-Namas', '42.10', '69.50', '48.70', '92.40', '2024-07-20', 'Summer', 'Significant impact from seasonal tourism', '2025-09-26 20:56:23'),
(7, 'Abha', '72.30', '84.10', '66.20', '82.50', '2024-01-05', 'Winter', 'Data from Pollution_data.ipynb - Aseer Region', '2026-02-12 06:17:07'),
(8, 'Khamis Mushait', '68.10', '78.90', '71.80', '76.20', '2024-01-05', 'Winter', 'Data from Pollution_data.ipynb - Aseer Region', '2026-02-12 06:17:07'),
(9, 'Al-Namas', '74.50', '86.20', '64.10', '70.30', '2024-01-05', 'Winter', 'Data from Pollution_data.ipynb - Aseer Region', '2026-02-12 06:17:07'),
(10, 'Bisha', '70.20', '81.50', '69.40', '65.00', '2024-01-05', 'Winter', 'Data from Pollution_data.ipynb - Aseer Region', '2026-02-12 06:17:07'),
(11, 'Muhayil', '69.80', '80.30', '67.90', '72.10', '2024-01-05', 'Winter', 'Data from Pollution_data.ipynb - Aseer Region', '2026-02-12 06:17:07'),
(12, 'Abha', '71.00', '83.50', '67.00', '80.00', '2024-01-15', 'Winter', 'Data from Pollution_data.ipynb - Aseer Region', '2026-02-12 06:17:07'),
(13, 'Khamis Mushait', '67.50', '78.20', '72.50', '75.00', '2024-01-15', 'Winter', 'Data from Pollution_data.ipynb - Aseer Region', '2026-02-12 06:17:07'),
(14, 'Al-Namas', '73.80', '85.80', '65.00', '69.00', '2024-01-15', 'Winter', 'Data from Pollution_data.ipynb - Aseer Region', '2026-02-12 06:17:07'),
(15, 'Bisha', '69.50', '81.00', '70.00', '64.00', '2024-01-15', 'Winter', 'Data from Pollution_data.ipynb - Aseer Region', '2026-02-12 06:17:07'),
(16, 'Muhayil', '69.00', '79.80', '68.50', '71.00', '2024-01-15', 'Winter', 'Data from Pollution_data.ipynb - Aseer Region', '2026-02-12 06:17:07'),
(17, 'Abha', '45.20', '67.50', '51.80', '94.20', '2024-07-10', 'Summer', 'Data from Pollution_data.ipynb - Aseer Region', '2026-02-12 06:17:07'),
(18, 'Khamis Mushait', '48.50', '70.80', '58.50', '88.50', '2024-07-10', 'Summer', 'Data from Pollution_data.ipynb - Aseer Region', '2026-02-12 06:17:07'),
(19, 'Al-Namas', '42.80', '68.90', '48.20', '91.00', '2024-07-10', 'Summer', 'Data from Pollution_data.ipynb - Aseer Region', '2026-02-12 06:17:07'),
(20, 'Bisha', '50.10', '72.10', '55.20', '85.30', '2024-07-10', 'Summer', 'Data from Pollution_data.ipynb - Aseer Region', '2026-02-12 06:17:07'),
(21, 'Muhayil', '47.30', '69.50', '52.60', '89.10', '2024-07-10', 'Summer', 'Data from Pollution_data.ipynb - Aseer Region', '2026-02-12 06:17:07'),
(22, 'Abha', '44.00', '66.20', '53.00', '95.00', '2024-07-20', 'Summer', 'Data from Pollution_data.ipynb - Aseer Region', '2026-02-12 06:17:07'),
(23, 'Khamis Mushait', '47.80', '70.20', '59.00', '87.00', '2024-07-20', 'Summer', 'Data from Pollution_data.ipynb - Aseer Region', '2026-02-12 06:17:07'),
(24, 'Al-Namas', '41.50', '68.00', '49.00', '92.00', '2024-07-20', 'Summer', 'Data from Pollution_data.ipynb - Aseer Region', '2026-02-12 06:17:07'),
(25, 'Bisha', '49.50', '71.50', '56.00', '84.00', '2024-07-20', 'Summer', 'Data from Pollution_data.ipynb - Aseer Region', '2026-02-12 06:17:07'),
(26, 'Muhayil', '46.80', '69.00', '53.20', '88.50', '2024-07-20', 'Summer', 'Data from Pollution_data.ipynb - Aseer Region', '2026-02-12 06:17:07'),
(27, 'Abha', '65.00', '80.20', '62.00', '55.00', '2024-03-15', 'Spring', 'Data from Pollution_data.ipynb - Aseer Region', '2026-02-12 06:17:07'),
(28, 'Khamis Mushait', '62.50', '76.50', '68.00', '52.00', '2024-03-15', 'Spring', 'Data from Pollution_data.ipynb - Aseer Region', '2026-02-12 06:17:07'),
(29, 'Al-Namas', '68.20', '82.80', '60.50', '48.00', '2024-03-15', 'Spring', 'Data from Pollution_data.ipynb - Aseer Region', '2026-02-12 06:17:07'),
(30, 'Bisha', '63.80', '78.90', '66.20', '50.00', '2024-03-15', 'Spring', 'Data from Pollution_data.ipynb - Aseer Region', '2026-02-12 06:17:07'),
(31, 'Muhayil', '64.20', '79.50', '64.80', '53.00', '2024-03-15', 'Spring', 'Data from Pollution_data.ipynb - Aseer Region', '2026-02-12 06:17:07'),
(32, 'Abha', '58.00', '75.00', '58.00', '72.00', '2024-10-10', 'Autumn', 'Data from Pollution_data.ipynb - Aseer Region', '2026-02-12 06:17:07'),
(33, 'Khamis Mushait', '55.20', '72.80', '62.50', '68.00', '2024-10-10', 'Autumn', 'Data from Pollution_data.ipynb - Aseer Region', '2026-02-12 06:17:07'),
(34, 'Al-Namas', '60.50', '77.50', '55.20', '65.00', '2024-10-10', 'Autumn', 'Data from Pollution_data.ipynb - Aseer Region', '2026-02-12 06:17:07'),
(35, 'Bisha', '56.80', '74.20', '60.00', '70.00', '2024-10-10', 'Autumn', 'Data from Pollution_data.ipynb - Aseer Region', '2026-02-12 06:17:07'),
(36, 'Muhayil', '57.20', '73.50', '59.20', '69.00', '2024-10-10', 'Autumn', 'Data from Pollution_data.ipynb - Aseer Region', '2026-02-12 06:17:07'),
(37, 'Abha', '73.50', '84.80', '65.00', '88.00', '2024-02-01', 'Winter', 'Data from Pollution_data.ipynb - Aseer Region', '2026-02-12 06:17:07'),
(38, 'Khamis Mushait', '69.20', '79.50', '70.50', '82.00', '2024-02-01', 'Winter', 'Data from Pollution_data.ipynb - Aseer Region', '2026-02-12 06:17:07'),
(39, 'Al-Namas', '75.00', '86.50', '63.00', '75.00', '2024-02-01', 'Winter', 'Data from Pollution_data.ipynb - Aseer Region', '2026-02-12 06:17:07'),
(40, 'Abha', '46.50', '68.00', '52.50', '93.00', '2024-08-05', 'Summer', 'Data from Pollution_data.ipynb - Aseer Region', '2026-02-12 06:17:07'),
(41, 'Khamis Mushait', '49.20', '71.00', '57.50', '86.00', '2024-08-05', 'Summer', 'Data from Pollution_data.ipynb - Aseer Region', '2026-02-12 06:17:07'),
(42, 'Al-Namas', '43.00', '69.20', '48.50', '90.00', '2024-08-05', 'Summer', 'Data from Pollution_data.ipynb - Aseer Region', '2026-02-12 06:17:07'),
(43, 'Abha', '66.20', '81.00', '63.50', '58.00', '2024-04-10', 'Spring', 'Data from Pollution_data.ipynb - Aseer Region', '2026-02-12 06:17:07'),
(44, 'Al-Namas', '69.00', '83.20', '61.00', '50.00', '2024-04-10', 'Spring', 'Data from Pollution_data.ipynb - Aseer Region', '2026-02-12 06:17:07'),
(45, 'Abha', '59.50', '76.20', '57.00', '70.00', '2024-11-01', 'Autumn', 'Data from Pollution_data.ipynb - Aseer Region', '2026-02-12 06:17:07'),
(46, 'Khamis Mushait', '56.80', '73.50', '61.80', '66.00', '2024-11-01', 'Autumn', 'Data from Pollution_data.ipynb - Aseer Region', '2026-02-12 06:17:07'),
(47, 'Abha', '74.00', '85.00', '64.50', '84.00', '2024-12-15', 'Winter', 'Data from Pollution_data.ipynb - Aseer Region', '2026-02-12 06:17:07'),
(48, 'Khamis Mushait', '70.00', '80.00', '69.80', '78.00', '2024-12-15', 'Winter', 'Data from Pollution_data.ipynb - Aseer Region', '2026-02-12 06:17:07'),
(49, 'Al-Namas', '76.20', '87.00', '62.50', '72.00', '2024-12-15', 'Winter', 'Data from Pollution_data.ipynb - Aseer Region', '2026-02-12 06:17:07'),
(50, 'Bisha', '71.00', '82.00', '68.00', '62.00', '2024-12-15', 'Winter', 'Data from Pollution_data.ipynb - Aseer Region', '2026-02-12 06:17:07'),
(51, 'Muhayil', '70.50', '80.80', '66.50', '70.00', '2024-12-15', 'Winter', 'Data from Pollution_data.ipynb - Aseer Region', '2026-02-12 06:17:07'),
(52, 'Abha', '43.50', '66.80', '50.50', '96.00', '2024-09-01', 'Summer', 'Data from Pollution_data.ipynb - Aseer Region', '2026-02-12 06:17:07'),
(53, 'Khamis Mushait', '47.00', '70.50', '58.00', '87.50', '2024-09-01', 'Summer', 'Data from Pollution_data.ipynb - Aseer Region', '2026-02-12 06:17:07'),
(54, 'Al-Namas', '42.00', '68.50', '47.80', '91.50', '2024-09-01', 'Summer', 'Data from Pollution_data.ipynb - Aseer Region', '2026-02-12 06:17:07'),
(55, 'Bisha', '50.50', '72.50', '54.50', '84.50', '2024-09-01', 'Summer', 'Data from Pollution_data.ipynb - Aseer Region', '2026-02-12 06:17:07'),
(56, 'Muhayil', '46.00', '69.20', '52.00', '89.50', '2024-09-01', 'Summer', 'Data from Pollution_data.ipynb - Aseer Region', '2026-02-12 06:17:07'),
(57, 'Abha', '64.00', '79.50', '61.50', '56.00', '2024-05-15', 'Spring', 'Data from Pollution_data.ipynb - Aseer Region', '2026-02-12 06:17:07'),
(58, 'Khamis Mushait', '61.00', '75.80', '67.20', '54.00', '2024-05-15', 'Spring', 'Data from Pollution_data.ipynb - Aseer Region', '2026-02-12 06:17:07'),
(59, 'Al-Namas', '67.50', '82.00', '59.80', '49.00', '2024-05-15', 'Spring', 'Data from Pollution_data.ipynb - Aseer Region', '2026-02-12 06:17:07'),
(60, 'Abha', '57.50', '74.80', '56.50', '71.00', '2024-10-20', 'Autumn', 'Data from Pollution_data.ipynb - Aseer Region', '2026-02-12 06:17:07'),
(61, 'Al-Namas', '59.80', '76.80', '54.80', '64.00', '2024-10-20', 'Autumn', 'Data from Pollution_data.ipynb - Aseer Region', '2026-02-12 06:17:07');

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `id` int(11) NOT NULL,
  `username` varchar(50) NOT NULL,
  `email` varchar(100) NOT NULL,
  `password` varchar(255) NOT NULL,
  `full_name` varchar(100) NOT NULL,
  `phone` varchar(20) DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `last_login` timestamp NULL DEFAULT NULL,
  `is_active` tinyint(1) DEFAULT 1
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`id`, `username`, `email`, `password`, `full_name`, `phone`, `created_at`, `last_login`, `is_active`) VALUES
(2, 'user', 'user@gmail.com', '$2y$10$/KH9eRqp5j9LXwxSg2ic3uA.aJBkYldNTpQLwkUOR4xR2OzdWu/wW', 'user', '99775565', '2025-12-04 06:30:34', '2025-12-04 08:08:41', 0),
(3, 'ahmad123', 'ahmad@gmail.com', '$2y$10$97ArDscKJClzEX/2pLKtZu35wFyKRih98FtTo.aIz1G6fhgepcN9q', 'ahmad', '99775565', '2026-01-22 17:20:43', '2026-03-12 05:54:25', 1),
(5, 'Layan', 'Layan@gmail.com', '$2y$10$JPFgvbKMk1R2AQBdRSVvO.pWrajyTKSyN1cipeH1rlR1mEmONPWQi', 'Layan Mansour', '56765434567', '2026-03-12 05:59:19', '2026-04-17 05:13:36', 1),
(6, 'mona123', 'mona@gmail.com', '$2y$10$WSpCBJezhrXx8Ty8B97W8.eyllYp0T6WWikjVYRW5jpfPta1PRTdu', 'mona', '5544565456', '2026-04-17 05:11:54', NULL, 1);

--
-- Indexes for dumped tables
--

--
-- Indexes for table `admin`
--
ALTER TABLE `admin`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `username` (`username`),
  ADD UNIQUE KEY `email` (`email`);

--
-- Indexes for table `pollution_data`
--
ALTER TABLE `pollution_data`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `username` (`username`),
  ADD UNIQUE KEY `email` (`email`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `admin`
--
ALTER TABLE `admin`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `pollution_data`
--
ALTER TABLE `pollution_data`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=62;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
