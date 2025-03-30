-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Mar 30, 2025 at 09:33 AM
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
(0, 'Admin', '2025-03-21 17:35:24', 'Reset all student sessions to 30'),
(0, 'Admin', '2025-03-22 13:52:09', 'Reset all student sessions to 30'),
(0, 'Mark', 'March 23, 2025 12:42', 'kapoya na bitaw\r\n');

-- --------------------------------------------------------

--
-- Table structure for table `feedback`
--

CREATE TABLE `feedback` (
  `id` int(11) NOT NULL,
  `user_id` varchar(50) DEFAULT NULL,
  `message` text DEFAULT NULL,
  `date_submitted` datetime DEFAULT current_timestamp(),
  `status` enum('pending','read') DEFAULT 'pending',
  `stars` int(11) DEFAULT 0,
  `is_read` tinyint(1) DEFAULT 0,
  `rating` int(11) DEFAULT 0,
  `admin_rating` int(11) DEFAULT NULL,
  `read_date` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `feedback`
--

INSERT INTO `feedback` (`id`, `user_id`, `message`, `date_submitted`, `status`, `stars`, `is_read`, `rating`, `admin_rating`, `read_date`) VALUES
(4, 'user1', 'ungaw kapoya na ang ferson  HAHHAHAH\\r\\n', '2025-03-30 15:04:31', 'read', 0, 0, 0, 4, '2025-03-30 15:22:40'),
(5, 'user1', 'guba ang pc nimal', '2025-03-30 15:31:54', 'read', 0, 0, 0, 4, '2025-03-30 15:32:20');

-- --------------------------------------------------------

--
-- Table structure for table `settings`
--

CREATE TABLE `settings` (
  `setting_name` varchar(50) NOT NULL,
  `value` int(11) NOT NULL,
  `description` varchar(255) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `settings`
--

INSERT INTO `settings` (`setting_name`, `value`, `description`) VALUES
('default_sessions', 30, 'Default number of sessions for new users');

-- --------------------------------------------------------

--
-- Table structure for table `student_sit_in`
--

CREATE TABLE `student_sit_in` (
  `sit_id` int(11) NOT NULL,
  `id_number` int(11) NOT NULL,
  `sit_purpose` varchar(50) NOT NULL,
  `sit_lab` varchar(20) NOT NULL,
  `time_in` varchar(15) NOT NULL,
  `time_out` varchar(15) NOT NULL,
  `sit_date` varchar(10) NOT NULL,
  `status` varchar(20) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `student_sit_in`
--

INSERT INTO `student_sit_in` (`sit_id`, `id_number`, `sit_purpose`, `sit_lab`, `time_in`, `time_out`, `sit_date`, `status`) VALUES
(0, 46541, 'PHP', '544', '10:51:32', '08:20:01', '2025-03-17', 'Completed'),
(0, 55564, 'ASP.net', '542', '10:56:03', '08:20:01', '2025-03-17', 'Completed'),
(0, 55564, 'C#', '526', '10:56:43', '08:20:01', '2025-03-17', 'Completed'),
(0, 46541, 'C#', '530', '11:07:35', '08:20:01', '2025-03-17', 'Completed'),
(0, 46541, 'PHP', '530', '10:29:53', '08:20:01', '2025-03-18', 'Completed'),
(0, 46541, 'Java', '544', '10:41:01', '08:20:01', '2025-03-18', 'Completed'),
(0, 789946, 'C#', '542', '10:54:08', '08:20:01', '2025-03-18', 'Completed'),
(0, 789946, 'PHP', '542', '11:13:04', '08:20:01', '2025-03-18', 'Completed'),
(0, 789946, 'PHP', '524', '11:25:00', '08:20:01', '2025-03-18', 'Completed'),
(0, 789946, 'PHP', '530', '11:29:26', '08:20:01', '2025-03-18', 'Completed'),
(0, 22222, 'PHP', '544', '11:32:08', '08:20:01', '2025-03-18', 'Completed'),
(0, 789946, 'Python', '530', '11:59:49', '08:20:01', '2025-03-18', 'Completed'),
(0, 22222, 'Others', '542', '12:12:34', '08:20:01', '2025-03-18', 'Completed'),
(0, 22222, 'ASP.net', '544', '14:20:02', '08:20:01', '2025-03-18', 'Completed'),
(0, 123456, 'PHP', '528', '14:24:05', '08:20:01', '2025-03-18', 'Completed'),
(0, 789946, 'ASP.net', '542', '03:01:47', '08:20:01', '2025-03-19', 'Completed'),
(0, 123456, 'Python', '544', '03:31:42', '08:20:01', '2025-03-19', 'Completed'),
(0, 789946, 'Others', '542', '03:32:51', '08:20:01', '2025-03-19', 'Completed'),
(0, 789946, 'Others', '530', '04:26:00', '08:20:01', '2025-03-19', 'Completed'),
(0, 789946, 'Python', '530', '04:26:10', '08:20:01', '2025-03-19', 'Completed'),
(0, 22222, 'Python', '530', '04:29:20', '08:20:01', '2025-03-19', 'Completed'),
(0, 22222, 'Python', '542', '04:29:32', '08:20:01', '2025-03-19', 'Completed'),
(0, 22222, 'Python', '528', '04:29:54', '08:20:01', '2025-03-19', 'Completed'),
(0, 22222, 'Python', '542', '04:30:06', '08:20:01', '2025-03-19', 'Completed'),
(0, 789946, 'Python', '530', '04:31:07', '08:20:01', '2025-03-19', 'Completed'),
(0, 789946, 'Python', '528', '04:33:51', '08:20:01', '2025-03-19', 'Completed'),
(0, 789946, 'Python', '528', '04:34:03', '08:20:01', '2025-03-19', 'Completed'),
(0, 789946, 'ASP.net', '530', '04:37:45', '08:20:01', '2025-03-19', 'Completed'),
(0, 789946, 'ASP.net', '528', '04:38:22', '08:20:01', '2025-03-19', 'Completed'),
(0, 789946, 'Python', '526', '04:47:46', '08:20:01', '2025-03-19', 'Completed'),
(0, 789946, 'ASP.net', '542', '09:20:36', '08:20:01', '2025-03-19', 'Completed'),
(0, 789946, 'Python', '544', '09:24:50', '08:20:01', '2025-03-19', 'Completed'),
(0, 789946, 'ASP.net', '526', '11:16:58', '08:20:01', '2025-03-19', 'Completed'),
(0, 789946, 'ASP.net', '526', '13:46:50', '08:20:01', '2025-03-19', 'Completed'),
(0, 789946, 'Python', '542', '13:47:20', '08:20:01', '2025-03-19', 'Completed'),
(0, 789946, 'ASP.net', '524', '14:02:06', '08:20:01', '2025-03-19', 'Completed'),
(0, 789946, 'PHP Programming', '528', '14:46:56', '08:20:01', '2025-03-19', 'Completed'),
(0, 123456, 'ASP.net Programming', '544', '15:40:58', '08:20:01', '2025-03-19', 'Completed'),
(0, 123456, 'Java ', '526', '15:44:46', '08:20:01', '2025-03-19', 'Completed'),
(0, 789946, 'ASP.net ', '524', '15:59:47', '08:20:01', '2025-03-19', 'Completed'),
(0, 789946, 'ASP.net ', '524', '16:00:00', '08:20:01', '2025-03-19', 'Completed'),
(0, 789946, 'ASP.net ', '530', '16:00:17', '08:20:01', '2025-03-19', 'Completed'),
(0, 789946, 'Python ', '544', '16:00:30', '08:20:01', '2025-03-19', 'Completed'),
(0, 789946, 'C# ', '526', '16:00:44', '08:20:01', '2025-03-19', 'Completed'),
(0, 123456, 'Python ', '530', '16:16:54', '08:20:01', '2025-03-19', 'Completed'),
(0, 22677116, 'Python ', '526', '16:35:17', '08:20:01', '2025-03-19', 'Completed'),
(0, 22677116, 'ASP.net ', '542', '16:39:46', '08:20:01', '2025-03-19', 'Completed'),
(0, 789946, 'Python ', '528', '08:29:33', '08:20:01', '2025-03-20', 'Completed'),
(0, 22677116, 'Java ', '526', '10:34:52', '08:20:01', '2025-03-21', 'Completed'),
(0, 2147483647, 'PHP ', '526', '06:43:39', '08:20:01', '2025-03-22', 'Completed'),
(0, 22677116, 'ASP.net ', '542', '08:19:42', '08:20:01', '2025-03-30', 'Completed'),
(0, 2147483647, 'ASP.net ', '526', '08:19:58', '08:20:01', '2025-03-30', 'Completed');

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
  `role` enum('admin','user') NOT NULL DEFAULT 'user',
  `remaining_session` int(11) NOT NULL DEFAULT 30
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `user`
--

INSERT INTO `user` (`id`, `lname`, `fname`, `MName`, `Course`, `Level`, `username`, `password`, `image`, `email`, `address`, `role`, `remaining_session`) VALUES
(1, 'CATUBIG', 'Super', NULL, NULL, NULL, 'admin', 'admin', NULL, 'admin@example.com', 'HQ Address', 'admin', 0),
(22677116, 'Catubig', 'Mark Dave', 'Cabingatan', 'BSIT', '3', 'mark', '$2y$10$zchvWcHidQPT0Ac7k0B8jub.KnCFlWdbnDeIJnoG8zhLCaRvB5OKC', 'uploads/320738403_1115980445778336_3190117843245493251_n.jpg', 'catubigmarkdave0@gmail.com', 'Dam View Deck, Buhisan', 'user', 30),
(226771156, 'Soberano', 'Elizabeth', '', 'BSCRIM', '3', 'user1', '$2y$10$yr4fm5tbvBsk5CufQvvgF.lhDHrP4GH0vBrgpk2udHF2n/1MnmZCm', NULL, '', NULL, 'user', 30),
(2147483647, 'Racuma', 'Denise', 'Cabingatan', 'BSCA', '1', 'hahaha', '$2y$10$se1cwLGKhBJCmUE5OMCADOcnAY1TZnhJt46nf7kbeb9BPtlnSVdGi', NULL, '', NULL, 'user', 30);

--
-- Indexes for dumped tables
--

--
-- Indexes for table `feedback`
--
ALTER TABLE `feedback`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `settings`
--
ALTER TABLE `settings`
  ADD PRIMARY KEY (`setting_name`);

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
-- AUTO_INCREMENT for table `feedback`
--
ALTER TABLE `feedback`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT for table `user`
--
ALTER TABLE `user`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2147483648;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
