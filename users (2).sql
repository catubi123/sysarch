-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Mar 17, 2025 at 09:45 AM
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
-- Database: `users`
--

-- --------------------------------------------------------

--
-- Table structure for table `announce`
--

CREATE TABLE `announce` (
  `announce_id` int(11) NOT NULL,
  `admin_name` varchar(20) NOT NULL,
  `date` varchar(20) NOT NULL,
  `message` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `announce`
--

INSERT INTO `announce` (`announce_id`, `admin_name`, `date`, `message`) VALUES
(0, 'Admin', '2025-03-15', 'kapoya'),
(0, 'Admin', '2025-03-16', 'amaawa kapoya bitaw\r\n'),
(0, 'Mark', '2025-03-16', 'yawaaa'),
(0, 'Mark', '2025-03-16', 'Hello GU!YS\r\nthis website is under development\r\nstay turned for more updates'),
(0, 'Mark', '2025-03-16', 'mana gyud sa pag connect sa admin hahahaha\r\n'),
(0, 'Mark', '2025-03-16', 'taya kapoya na ni pero ok ra kay ma pull shark man ta HAHHAHHA\r\n'),
(0, 'Mark', '2025-03-17', 'may lag mahoman ni uyy\r\n'),
(0, 'Mark', '2025-03-17', 'bdsbslkf');

-- --------------------------------------------------------

--
-- Table structure for table `student_sit_in`
--

CREATE TABLE `student_sit_in` (
  `sit_id` int(11) NOT NULL,
  `id_number` int(11) NOT NULL,
  `sit_purpose` varchar(50) NOT NULL,
  `sit_lab` varchar(20) NOT NULL,
  `sit_login` varchar(15) NOT NULL,
  `sit_logout` varchar(15) NOT NULL,
  `sit_date` varchar(10) NOT NULL,
  `status` varchar(20) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `user`
--

CREATE TABLE `user` (
  `id` int(11) NOT NULL,
  `lname` varchar(50) DEFAULT NULL,
  `fname` varchar(50) DEFAULT NULL,
  `MName` varchar(50) DEFAULT NULL,
  `Course` varchar(50) DEFAULT NULL,
  `Level` varchar(10) DEFAULT NULL,
  `username` varchar(50) DEFAULT NULL,
  `password` varchar(255) NOT NULL,
  `image` varchar(255) DEFAULT NULL,
  `email` varchar(100) NOT NULL,
  `address` varchar(255) DEFAULT NULL,
  `role` enum('admin','user') NOT NULL DEFAULT 'user'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `user`
--

INSERT INTO `user` (`id`, `lname`, `fname`, `MName`, `Course`, `Level`, `username`, `password`, `image`, `email`, `address`, `role`) VALUES
(1, 'CATUBIG', 'Super', NULL, NULL, NULL, 'admin', 'admin', NULL, 'admin@example.com', 'HQ Address', 'admin'),
(1000, 'Catubig', 'Mark', 'Dave', 'BSCPE', '3', 'hello', '$2y$10$cpdo7hVxeDc1mcrTrHnKdeO0dA0WOm8.Nge/afoXAdJJwGwzH/e4S', NULL, '', NULL, 'user'),
(4568, 'Catubig', 'MarkARA', 'Dave', 'BSIT', '3', 'DAVE', '$2y$10$bCbRkgfhDYkX4TFRaGuf8OQPWns32/Ky5kSV/piZtdIhviR7b2BN2', 'uploads/download.jpg', 'catubigmarkdave0@gmail.com', 'Dam View Deck, Buhisan', 'user'),
(46541, 'Catubig', 'Mark', '', 'BPED', '3', 'taya', '$2y$10$L9TOdiuQ/m7tS7PBStqxiOQAEn6WLm7I.hI2LN0qMmQ5UR8ySKZni', 'uploads/67bd41154a538_19740.png', 'catubigmarkdave0@gmail.com', 'Dam View Deck, Buhisan', 'user'),
(55564, 'Catubig', 'Mark', 'Dave', 'BSCRIM', '1', 'alpha', '$2y$10$akwk3ZtdoBvwPunPU5cCYeEJBijsmqTzL9UdbalI/XQdWpsEoevrW', NULL, '', NULL, 'user'),
(56432, 'Catubig', 'Mark', 'Dave', 'BSCPE', '4', 'USER1232', '$2y$10$.kjgnub5o4uPJyJ6.ikT.eAjw1ne.LlxFLcGpsihZwRzd6UQsWpKm', NULL, '', NULL, 'user'),
(5555546, 'cabingatan', 'Mark', 'Dave', 'BSIT', '3', 'user1', '$2y$10$WlahTUxBMjmjjz6hhJICQ.STaFvD3iuJ4P4rHl9bHEC7qmFOd0.JW', NULL, '', NULL, 'user');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `user`
--
ALTER TABLE `user`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `username` (`username`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `user`
--
ALTER TABLE `user`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=22677117;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
