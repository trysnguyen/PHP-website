-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: localhost
-- Generation Time: Dec 07, 2025 at 05:38 AM
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
-- Database: `qltv`
--

-- --------------------------------------------------------

--
-- Table structure for table `books`
--

CREATE TABLE `books` (
  `BookID` bigint(20) UNSIGNED NOT NULL,
  `Bookname` varchar(200) NOT NULL,
  `Author` varchar(100) DEFAULT NULL,
  `Category` varchar(50) DEFAULT NULL,
  `Quantity` int(11) NOT NULL DEFAULT 0,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `books`
--

INSERT INTO `books` (`BookID`, `Bookname`, `Author`, `Category`, `Quantity`, `created_at`, `updated_at`) VALUES
(1, 'Introduction to Programming', 'John Smith', 'Computer Science', 10, '2025-12-05 05:41:24', '2025-12-06 21:20:42'),
(2, 'Database Systems', 'Maria Garcia', 'Computer Science', 5, '2025-12-05 05:41:24', '2025-12-06 21:22:53'),
(3, 'Web Development', 'David Chen', 'IT', 8, '2025-12-05 05:41:24', '2025-12-05 05:41:24'),
(4, 'The First Computer Scientist', 'Ada Lovelace', 'Computer Science', 15, '2025-12-06 10:28:35', '2025-12-06 10:28:35');

-- --------------------------------------------------------

--
-- Table structure for table `cache`
--

CREATE TABLE `cache` (
  `key` varchar(255) NOT NULL,
  `value` mediumtext NOT NULL,
  `expiration` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `cache_locks`
--

CREATE TABLE `cache_locks` (
  `key` varchar(255) NOT NULL,
  `owner` varchar(255) NOT NULL,
  `expiration` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `migrations`
--

CREATE TABLE `migrations` (
  `id` int(10) UNSIGNED NOT NULL,
  `migration` varchar(255) NOT NULL,
  `batch` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `migrations`
--

INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES
(1, '2025_12_05_112853_create_students_table', 1),
(2, '2025_12_05_112949_create_books_table', 1),
(3, '2025_12_05_113019_create_order_books_table', 1),
(4, '2025_12_06_092503_create_cache_table', 2);

-- --------------------------------------------------------

--
-- Table structure for table `order_books`
--

CREATE TABLE `order_books` (
  `OrderID` bigint(20) UNSIGNED NOT NULL,
  `username` varchar(50) NOT NULL,
  `Studentname` varchar(100) NOT NULL,
  `StudentID` varchar(20) NOT NULL,
  `Bookname` varchar(200) NOT NULL,
  `Status` enum('Pending','Accept','Refuse','Returned') NOT NULL DEFAULT 'Pending',
  `OrderedDate` datetime DEFAULT NULL,
  `ReturnedDate` datetime DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `order_books`
--

INSERT INTO `order_books` (`OrderID`, `username`, `Studentname`, `StudentID`, `Bookname`, `Status`, `OrderedDate`, `ReturnedDate`, `created_at`, `updated_at`) VALUES
(1, 'nguyendinhtri', 'Nguyen Dinh Bao Tri', '24AI061', 'Introduction to Programming', 'Returned', '2025-12-07 04:20:16', '2025-12-07 04:20:42', '2025-12-06 08:50:17', '2025-12-06 21:20:42'),
(2, 'tri', 'nguyen dinh bao tri', '24AI069', 'Database Systems', 'Refuse', NULL, NULL, '2025-12-06 21:21:40', '2025-12-06 21:21:59'),
(3, 'nguyendinhtri', 'Nguyen Dinh Bao Tri', '24AI061', 'Database Systems', 'Returned', '2025-12-07 04:22:41', '2025-12-07 04:22:53', '2025-12-06 21:22:22', '2025-12-06 21:22:53');

-- --------------------------------------------------------

--
-- Table structure for table `students`
--

CREATE TABLE `students` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `username` varchar(255) NOT NULL,
  `password` varchar(255) NOT NULL,
  `Studentname` varchar(255) NOT NULL,
  `StudentID` varchar(255) NOT NULL,
  `Class` varchar(255) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `students`
--

INSERT INTO `students` (`id`, `username`, `password`, `Studentname`, `StudentID`, `Class`, `created_at`, `updated_at`) VALUES
(1, 'nguyendinhtri', '$2y$12$mf0qAQY0NVIno5GaC5CyZOqNqRGG7d31HE2tI6.BBbQvP7TNrswnS', 'Nguyen Dinh Bao Tri', '24AI061', '24AI', '2025-12-05 06:55:46', '2025-12-06 04:01:02'),
(4, 'dsa', '$2y$12$GJ2EDVgUcgMJ10MyL4TBKOsSzJiyzKp0HVpNe.TMWfkk/Y2C0Zx2a', 'fgsfas', 'r31r2f', '3213ef', '2025-12-06 10:23:53', '2025-12-06 10:23:53'),
(5, 'tri', '$2y$12$NE53KSvouoIH0O3kmVJxFesm1E60CX2mKKPyPsGvHFUSYralSPIGq', 'nguyen dinh bao tri', '24AI069', '24AI', '2025-12-06 10:25:04', '2025-12-06 10:25:04');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `books`
--
ALTER TABLE `books`
  ADD PRIMARY KEY (`BookID`);

--
-- Indexes for table `cache`
--
ALTER TABLE `cache`
  ADD PRIMARY KEY (`key`),
  ADD KEY `cache_expiration_index` (`expiration`);

--
-- Indexes for table `cache_locks`
--
ALTER TABLE `cache_locks`
  ADD PRIMARY KEY (`key`),
  ADD KEY `cache_locks_expiration_index` (`expiration`);

--
-- Indexes for table `migrations`
--
ALTER TABLE `migrations`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `order_books`
--
ALTER TABLE `order_books`
  ADD PRIMARY KEY (`OrderID`),
  ADD KEY `order_books_status_index` (`Status`);

--
-- Indexes for table `students`
--
ALTER TABLE `students`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `students_username_unique` (`username`),
  ADD UNIQUE KEY `students_studentid_unique` (`StudentID`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `books`
--
ALTER TABLE `books`
  MODIFY `BookID` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT for table `migrations`
--
ALTER TABLE `migrations`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT for table `order_books`
--
ALTER TABLE `order_books`
  MODIFY `OrderID` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `students`
--
ALTER TABLE `students`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
