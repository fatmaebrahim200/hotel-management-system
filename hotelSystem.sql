-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Aug 13, 2026 at 02:33 PM
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
-- Database: `hotelsystem`
--

-- --------------------------------------------------------

--
-- Table structure for table `booking`
--

CREATE TABLE `booking` (
  `id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `room_name` varchar(100) NOT NULL,
  `CHECK_in` date NOT NULL,
  `CHECK_out` date NOT NULL,
  `guests` int(11) NOT NULL,
  `STATUS` varchar(20) DEFAULT 'pending',
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `payment_status` varchar(20) NOT NULL DEFAULT 'Unpaid',
  `payment_method` varchar(20) NOT NULL DEFAULT 'hotel',
  `paid_at` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `booking`
--

INSERT INTO `booking` (`id`, `user_id`, `room_name`, `CHECK_in`, `CHECK_out`, `guests`, `STATUS`, `created_at`, `payment_status`, `payment_method`, `paid_at`) VALUES
(1, 5, 'Deluxe Ocean View', '2026-08-13', '2026-08-29', 2, 'cancelled', '2026-08-11 11:31:55', 'Paid', 'card', '2026-08-11 14:50:41'),
(3, 7, 'Deluxe Room', '2026-08-26', '2026-09-04', 2, 'cancelled', '2026-08-11 11:35:14', 'Paid', 'card', '2026-08-11 15:51:15'),
(4, 5, 'Executive Suite', '2026-08-29', '2026-09-04', 2, 'Pending', '2026-08-11 11:50:21', 'Paid', 'card', '2026-08-11 14:50:36'),
(5, 5, 'Luxury Suite', '2026-08-15', '2026-08-29', 2, 'Pending', '2026-08-11 11:56:22', 'Paid', 'card', '2026-08-11 14:56:38'),
(6, 5, 'Deluxe Room', '2026-08-21', '2026-09-02', 2, 'Pending', '2026-08-11 12:03:25', 'Unpaid', 'hotel', NULL),
(7, 5, 'Deluxe Room', '2026-08-21', '2026-09-02', 2, 'Pending', '2026-08-11 12:03:38', 'Unpaid', 'hotel', NULL),
(8, 5, 'Deluxe Room', '2026-08-24', '2026-09-05', 2, 'Pending', '2026-08-11 12:45:44', 'Unpaid', 'hotel', NULL),
(9, 5, 'Deluxe Room', '2026-08-21', '2026-09-02', 2, 'Pending', '2026-08-11 12:46:28', 'Unpaid', 'hotel', NULL),
(10, 5, 'Deluxe Room', '2026-09-05', '2026-10-07', 2, 'Cancelled', '2026-08-11 12:46:42', 'Unpaid', 'hotel', NULL),
(11, 5, 'Luxury Pool Villa', '2026-08-22', '2026-09-05', 2, 'Pending', '2026-08-11 12:47:18', 'Paid', 'card', '2026-08-11 15:47:33'),
(12, 5, 'Executive Suite', '2026-08-15', '2026-09-05', 2, 'Pending', '2026-08-11 12:49:58', 'Unpaid', 'hotel', NULL),
(13, 5, 'Executive Suite', '2026-08-15', '2026-09-05', 2, 'Pending', '2026-08-11 12:50:05', 'Unpaid', 'hotel', NULL),
(14, 7, 'Luxury Pool Villa', '2026-08-14', '2026-08-29', 2, 'Pending', '2026-08-11 12:51:31', 'Paid', 'card', '2026-08-13 10:22:39'),
(15, 7, 'Deluxe Ocean View', '2026-09-02', '2026-10-10', 2, 'cancelled', '2026-08-11 12:52:25', 'Unpaid', 'hotel', NULL),
(16, 5, 'Executive Suite', '2026-08-29', '2026-09-04', 2, 'Pending', '2026-08-11 12:55:19', 'Unpaid', 'hotel', NULL),
(17, 5, 'Executive Suite', '2026-08-29', '2026-09-04', 2, 'Pending', '2026-08-11 12:57:01', 'Unpaid', 'hotel', NULL),
(18, 5, 'Executive Suite', '2026-08-28', '2026-09-05', 2, 'Pending', '2026-08-11 12:57:10', 'Unpaid', 'hotel', NULL),
(19, 5, 'Executive Suite', '2026-08-29', '2026-09-04', 2, 'Pending', '2026-08-11 12:57:23', 'Unpaid', 'hotel', NULL),
(20, 5, 'Executive Suite', '2026-08-29', '2026-09-04', 2, 'Pending', '2026-08-11 12:57:27', 'Unpaid', 'hotel', NULL),
(21, 5, 'Executive Suite', '2026-08-27', '2026-08-29', 2, 'Pending', '2026-08-11 12:58:25', 'Unpaid', 'hotel', NULL),
(22, 8, 'Executive Suite', '2026-08-15', '2026-08-19', 2, 'cancelled', '2026-08-11 14:32:53', 'Paid', 'card', '2026-08-11 17:33:06'),
(23, 8, 'Deluxe Room', '2026-08-27', '2026-09-05', 2, 'cancelled', '2026-08-11 14:36:20', 'Paid', 'card', '2026-08-11 17:38:56'),
(27, 8, 'Deluxe Ocean View', '2026-08-28', '2026-09-03', 2, 'Pending', '2026-08-12 10:12:47', 'Unpaid', 'hotel', NULL),
(28, 17, 'Deluxe Room', '2026-08-27', '2026-09-01', 2, 'Pending', '2026-08-12 22:24:31', 'Paid', 'card', '2026-08-13 01:24:43'),
(29, 8, 'Executive Suite', '2026-08-28', '2026-09-05', 2, 'Confirmed', '2026-08-13 06:24:15', 'Unpaid', 'hotel', NULL);

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `id` int(11) NOT NULL,
  `name` varchar(100) NOT NULL,
  `email` varchar(100) NOT NULL,
  `PASSWORD` varchar(255) NOT NULL,
  `phone` varchar(20) DEFAULT NULL,
  `nationality` varchar(50) NOT NULL,
  `date_of_birth` date NOT NULL,
  `gender` varchar(20) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`id`, `name`, `email`, `PASSWORD`, `phone`, `nationality`, `date_of_birth`, `gender`) VALUES
(3, 'fatma', 'hana@gmail.com', '$2y$10$LNToTBOTrFm4r6ICCoOfzON1Yap9dB7Jjqh/5IWnGEclVVIpy5Kwa', '01119415669', 'Saudi Arabia', '2011-03-27', 'Male'),
(4, 'fatma', 'hna@gmail.com', '$2y$10$Ab8H4d3fWbW/cy6wJjQwfuGnVp6GPuabbSMXsykaPmSw3sgF11dWm', '01119415669', 'Saudi Arabia', '2011-03-27', 'Female'),
(5, 'fatema', 'fatma@gmail.com', '$2y$10$SQWwAgqyOJ5HpVV96d4f5.Frd3Y.PGmjlT9Ep2pD25edYajOApfhe', '01119412778', 'Egypt', '2021-02-07', 'Female'),
(7, 'say', 'jhhv@gmail.com', '$2y$10$xAjDSOi4VtM5Zyfx.sYMoexxKAjnJ/wtMBUcf3/mDJa981dcr2BIW', '01119415', 'Kuwait', '2026-08-04', 'Female'),
(8, 'say', 'jhv@gmail.com', '$2y$10$B4XAsL4XGlXK7uUGIUXchOkDJV4VCNRSGgZHaf8oTFo5ZmdHTm0q2', '01119415669', 'Kuwait', '2026-08-04', 'Male'),
(9, 'ahmed', 'ahmad@gmail.com', '$2y$10$Qgv2pp3mAJPZt.incA6iGeAA2NXUbNoct.Xszf4vxoL5rLwVOGTVq', '01119415669', 'Egypt', '2021-01-12', 'Male'),
(10, 'hassnaa', 'hasnaa@gmail.com', '$2y$10$RJB696mJLAZomqaN7BY1wei/Snl.4wuEmL95.XXZkbJC.un1QWeMq', '01119415669', 'Kuwait', '2026-08-04', 'Female'),
(11, 'hassnaa', 'has@gmail.com', '$2y$10$pC49hWfrTTu9N4ONRcYIc.if994dsDHNNIPheAd7oGLg.7qlRubje', '01119415669', 'UAE', '2026-08-04', 'Female'),
(12, 'hassnaa', 'hs@gmail.com', '$2y$10$rfxGFWJI51p.uKaJ7mf60em7HtBMX6CQWXE/JT6wyveXoT9BpqONi', '01119415669', 'Saudi Arabia', '2026-08-04', 'Female'),
(13, 'lama', 'lama@gmail.com', '$2y$10$gRkyvZny03WW2nD.6SlVkeCsSTbL.ChRGtPS1Drstgv4vz1KbyAoq', '01119415669', 'Kuwait', '2021-02-12', 'Female'),
(14, 'mohamed', 'm@gmail.com', '$2y$10$6kMu9c.RHbX8JHDKiFquh.Prn.CYaEuxKGxuT1aCUkEQZto7EqFT6', '01119415669', 'Egypt', '2026-09-04', 'Female'),
(15, 'MZAM', 'FAt@gmail.com', '$2y$10$gU3aXb2msH90BOOoPelZN.tJZGej0yLgWKixQi.sZFGOjonLKHGJW', '01119414558', 'Saudi Arabia', '2023-02-21', 'Male'),
(16, 'maram', 'maram@gmail.com', '$2y$10$vpUIKfBOQfFd/cR40B6nFeNcYE1tMvmTxV2syZgUAKcRY6Attvbe2', '01119415669', 'Egypt', '2023-02-12', 'Female'),
(19, 'fatma', 'sej@gmail.com', '$2y$10$QLlcpHtInCQDbpFhvBkN4uNuaN8.rsj3JCSR17DTU10qlKC8XTftm', '01119415669', 'eghption', '2019-02-13', 'Female'),
(21, 'fatma', 'faa@gmail.com', '$2y$10$beBktlUlhu.LtQGKrDNeROh1rr7eRC6F32qIxro6JAWsvem3zQEsG', '01119415669', 'Egypt', '2019-12-31', 'Female');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `booking`
--
ALTER TABLE `booking`
  ADD PRIMARY KEY (`id`);

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
-- AUTO_INCREMENT for table `booking`
--
ALTER TABLE `booking`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=31;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=22;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
