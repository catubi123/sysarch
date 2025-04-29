-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Apr 29, 2025 at 12:58 PM
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
(0, 'Mark', 'April 09, 2025 12:47', 'ok na gyud ang history ug feedback\r\nang genereate report nalang\r\n'),
(0, 'Mark', 'April 09, 2025 03:20', 'HAPIT NA HOMAN\r\n');

-- --------------------------------------------------------

--
-- Table structure for table `feedback`
--

CREATE TABLE `feedback` (
  `id` int(11) NOT NULL,
  `sit_id` int(11) DEFAULT NULL,
  `user_id` varchar(50) DEFAULT NULL,
  `rating` int(11) DEFAULT NULL,
  `message` text DEFAULT NULL,
  `created_at` datetime DEFAULT current_timestamp(),
  `status` varchar(20) DEFAULT 'pending'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `feedback`
--

INSERT INTO `feedback` (`id`, `sit_id`, `user_id`, `rating`, `message`, `created_at`, `status`) VALUES
(14, 92, 'user1', 5, 'may ta ok na\n', '2025-04-09 00:43:34', 'pending'),
(16, 94, 'user1', 5, 'ok na siguro ni hahahah', '2025-04-09 16:07:48', 'pending');

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
(1, 46541, 'PHP', '544', '10:51:32', '06:47:33', '2025-03-17', 'Completed'),
(2, 55564, 'ASP.net', '542', '10:56:03', '06:47:33', '2025-03-17', 'Completed'),
(3, 55564, 'C#', '526', '10:56:43', '06:47:33', '2025-03-17', 'Completed'),
(4, 46541, 'C#', '530', '11:07:35', '06:47:33', '2025-03-17', 'Completed'),
(5, 46541, 'PHP', '530', '10:29:53', '06:47:33', '2025-03-18', 'Completed'),
(6, 46541, 'Java', '544', '10:41:01', '06:47:33', '2025-03-18', 'Completed'),
(7, 789946, 'C#', '542', '10:54:08', '06:47:33', '2025-03-18', 'Completed'),
(8, 789946, 'PHP', '542', '11:13:04', '06:47:33', '2025-03-18', 'Completed'),
(9, 789946, 'PHP', '524', '11:25:00', '06:47:33', '2025-03-18', 'Completed'),
(10, 789946, 'PHP', '530', '11:29:26', '06:47:33', '2025-03-18', 'Completed'),
(11, 22222, 'PHP', '544', '11:32:08', '06:47:33', '2025-03-18', 'Completed'),
(12, 789946, 'Python', '530', '11:59:49', '06:47:33', '2025-03-18', 'Completed'),
(13, 22222, 'Others', '542', '12:12:34', '06:47:33', '2025-03-18', 'Completed'),
(14, 22222, 'ASP.net', '544', '14:20:02', '06:47:33', '2025-03-18', 'Completed'),
(15, 123456, 'PHP', '528', '14:24:05', '06:47:33', '2025-03-18', 'Completed'),
(16, 789946, 'ASP.net', '542', '03:01:47', '06:47:33', '2025-03-19', 'Completed'),
(17, 123456, 'Python', '544', '03:31:42', '06:47:33', '2025-03-19', 'Completed'),
(18, 789946, 'Others', '542', '03:32:51', '06:47:33', '2025-03-19', 'Completed'),
(19, 789946, 'Others', '530', '04:26:00', '06:47:33', '2025-03-19', 'Completed'),
(20, 789946, 'Python', '530', '04:26:10', '06:47:33', '2025-03-19', 'Completed'),
(21, 22222, 'Python', '530', '04:29:20', '06:47:33', '2025-03-19', 'Completed'),
(22, 22222, 'Python', '542', '04:29:32', '06:47:33', '2025-03-19', 'Completed'),
(23, 22222, 'Python', '528', '04:29:54', '06:47:33', '2025-03-19', 'Completed'),
(24, 22222, 'Python', '542', '04:30:06', '06:47:33', '2025-03-19', 'Completed'),
(25, 789946, 'Python', '530', '04:31:07', '06:47:33', '2025-03-19', 'Completed'),
(26, 789946, 'Python', '528', '04:33:51', '06:47:33', '2025-03-19', 'Completed'),
(27, 789946, 'Python', '528', '04:34:03', '06:47:33', '2025-03-19', 'Completed'),
(28, 789946, 'ASP.net', '530', '04:37:45', '06:47:33', '2025-03-19', 'Completed'),
(29, 789946, 'ASP.net', '528', '04:38:22', '06:47:33', '2025-03-19', 'Completed'),
(30, 789946, 'Python', '526', '04:47:46', '06:47:33', '2025-03-19', 'Completed'),
(31, 789946, 'ASP.net', '542', '09:20:36', '06:47:33', '2025-03-19', 'Completed'),
(32, 789946, 'Python', '544', '09:24:50', '06:47:33', '2025-03-19', 'Completed'),
(33, 789946, 'ASP.net', '526', '11:16:58', '06:47:33', '2025-03-19', 'Completed'),
(34, 789946, 'ASP.net', '526', '13:46:50', '06:47:33', '2025-03-19', 'Completed'),
(35, 789946, 'Python', '542', '13:47:20', '06:47:33', '2025-03-19', 'Completed'),
(36, 789946, 'ASP.net', '524', '14:02:06', '06:47:33', '2025-03-19', 'Completed'),
(37, 789946, 'PHP Programming', '528', '14:46:56', '06:47:33', '2025-03-19', 'Completed'),
(38, 123456, 'ASP.net Programming', '544', '15:40:58', '06:47:33', '2025-03-19', 'Completed'),
(39, 123456, 'Java ', '526', '15:44:46', '06:47:33', '2025-03-19', 'Completed'),
(40, 789946, 'ASP.net ', '524', '15:59:47', '06:47:33', '2025-03-19', 'Completed'),
(41, 789946, 'ASP.net ', '524', '16:00:00', '06:47:33', '2025-03-19', 'Completed'),
(42, 789946, 'ASP.net ', '530', '16:00:17', '06:47:33', '2025-03-19', 'Completed'),
(43, 789946, 'Python ', '544', '16:00:30', '06:47:33', '2025-03-19', 'Completed'),
(44, 789946, 'C# ', '526', '16:00:44', '06:47:33', '2025-03-19', 'Completed'),
(45, 123456, 'Python ', '530', '16:16:54', '06:47:33', '2025-03-19', 'Completed'),
(46, 22677116, 'Python ', '526', '16:35:17', '06:47:33', '2025-03-19', 'Completed'),
(47, 22677116, 'ASP.net ', '542', '16:39:46', '06:47:33', '2025-03-19', 'Completed'),
(48, 789946, 'Python ', '528', '08:29:33', '06:47:33', '2025-03-20', 'Completed'),
(49, 22677116, 'Java ', '526', '10:34:52', '06:47:33', '2025-03-21', 'Completed'),
(50, 2147483647, 'PHP ', '526', '06:43:39', '06:47:33', '2025-03-22', 'Completed'),
(51, 22677116, 'ASP.net ', '542', '08:19:42', '06:47:33', '2025-03-30', 'Completed'),
(52, 2147483647, 'ASP.net ', '526', '08:19:58', '06:47:33', '2025-03-30', 'Completed'),
(53, 226771156, 'Python ', '530', '15:27:45', '06:47:33', '2025-04-04', 'Completed'),
(54, 226771156, 'ASP.net ', '528', '15:51:12', '06:47:33', '2025-04-04', 'Completed'),
(55, 226771156, 'PHP ', '524', '15:54:18', '06:47:33', '2025-04-04', 'Completed'),
(56, 226771156, 'Python ', '528', '15:58:30', '06:47:33', '2025-04-04', 'Completed'),
(57, 2222, 'PHP ', '526', '16:15:56', '06:47:33', '2025-04-04', 'Completed'),
(58, 226771156, 'PHP ', '528', '18:17:05', '06:47:33', '2025-04-05', 'Completed'),
(59, 226771156, 'ASP.net ', '526', '18:26:22', '06:47:33', '2025-04-05', 'Completed'),
(60, 226771156, 'ASP.net ', '530', '18:38:13', '06:47:33', '2025-04-05', 'Completed'),
(61, 226771156, 'PHP ', '528', '18:46:43', '06:47:33', '2025-04-05', 'Completed'),
(62, 226771156, 'ASP.net ', '528', '19:02:14', '06:47:33', '2025-04-05', 'Completed'),
(63, 226771156, 'Python ', '524', '19:03:10', '06:47:33', '2025-04-05', 'Completed'),
(64, 226771156, 'C# ', '544', '19:10:05', '06:47:33', '2025-04-05', 'Completed'),
(65, 226771156, 'ASP.net ', '530', '19:15:58', '06:47:33', '2025-04-05', 'Completed'),
(66, 2222, 'C#', '528', '19:40:21', '06:47:33', '2025-04-05', 'Completed'),
(67, 226771156, 'PHP ', '526', '19:44:46', '06:47:33', '2025-04-05', 'Completed'),
(68, 226771156, 'Python ', '528', '19:53:36', '06:47:33', '2025-04-05', 'Completed'),
(69, 226771156, 'PHP ', '528', '19:56:39', '06:47:33', '2025-04-05', 'Completed'),
(70, 226771156, 'Java ', '526', '20:03:29', '06:47:33', '2025-04-05', 'Completed'),
(71, 226771156, 'PHP ', '526', '20:09:53', '06:47:33', '2025-04-05', 'Completed'),
(72, 226771156, 'PHP ', '542', '04:32:25', '06:47:33', '2025-04-06', 'Completed'),
(73, 226771156, 'ASP.net ', '528', '05:16:19', '06:47:33', '2025-04-06', 'Completed'),
(74, 226771156, 'ASP.net ', '544', '05:17:01', '06:47:33', '2025-04-06', 'Completed'),
(75, 226771156, 'C# ', '528', '05:20:29', '06:47:33', '2025-04-06', 'Completed'),
(76, 226771156, 'Java ', '530', '05:24:10', '06:47:33', '2025-04-06', 'Completed'),
(77, 226771156, 'Java ', '528', '05:41:52', '06:47:33', '2025-04-06', 'Completed'),
(78, 226771156, 'ASP.net ', '530', '06:02:26', '06:47:33', '2025-04-06', 'Completed'),
(79, 226771156, 'ASP.net ', '530', '06:33:17', '06:47:33', '2025-04-06', 'Completed'),
(80, 226771156, 'ASP.net ', '528', '06:38:18', '06:47:33', '2025-04-06', 'Completed'),
(81, 226771156, 'ASP.net ', '526', '06:47:30', '06:47:33', '2025-04-06', 'Completed'),
(82, 226771156, 'ASP.net ', '528', '06:54:20', '06:54:22', '2025-04-06', 'Completed'),
(83, 226771156, 'ASP.net ', '526', '17:28:05', '17:28:07', '2025-04-08', 'Completed'),
(84, 226771156, 'PHP ', '528', '17:43:44', '17:43:45', '2025-04-08', 'Completed'),
(85, 226771156, 'C# ', '530', '17:56:53', '17:56:55', '2025-04-08', 'Completed'),
(86, 226771156, 'Java ', '530', '17:59:31', '17:59:32', '2025-04-08', 'Completed'),
(87, 226771156, 'ASP.net ', '542', '18:05:56', '18:05:58', '2025-04-08', 'Completed'),
(88, 226771156, 'Python ', '528', '18:12:53', '18:12:55', '2025-04-08', 'Completed'),
(89, 226771156, 'PHP ', '542', '18:16:38', '18:16:40', '2025-04-08', 'Completed'),
(90, 226771156, 'PHP ', '530', '18:22:44', '18:22:46', '2025-04-08', 'Completed'),
(91, 226771156, 'PHP ', '528', '18:36:32', '18:36:34', '2025-04-08', 'Completed'),
(92, 226771156, 'PHP ', '526', '18:43:09', '18:43:11', '2025-04-08', 'Completed'),
(93, 226771156, 'ASP.net ', '528', '09:58:44', '09:58:45', '2025-04-09', 'Completed'),
(94, 226771156, 'Python ', '542', '10:06:33', '10:06:34', '2025-04-09', 'Completed');

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
  `remaining_session` int(11) NOT NULL DEFAULT 30,
  `points` int(11) DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `user`
--

INSERT INTO `user` (`id`, `lname`, `fname`, `MName`, `Course`, `Level`, `username`, `password`, `image`, `email`, `address`, `role`, `remaining_session`, `points`) VALUES
(1, 'CATUBIG', 'Super', NULL, NULL, NULL, 'admin', 'admin', NULL, 'admin@example.com', 'HQ Address', 'admin', 0, 0),
(22677116, 'Catubig', 'Mark Dave', 'Cabingatan', 'BSIT', '3', 'mark', '$2y$10$zchvWcHidQPT0Ac7k0B8jub.KnCFlWdbnDeIJnoG8zhLCaRvB5OKC', 'uploads/320738403_1115980445778336_3190117843245493251_n.jpg', 'catubigmarkdave0@gmail.com', 'Dam View Deck, Buhisan', 'user', 30, 0),
(226771156, 'Soberano', 'Elizabeth', '', 'BSCRIM', '3', 'user1', '$2y$10$yr4fm5tbvBsk5CufQvvgF.lhDHrP4GH0vBrgpk2udHF2n/1MnmZCm', 'uploads/jordan1.jpg', 'm@gmail.com', '', 'user', 30, 0),
(2147483647, 'Racuma', 'Denise', 'Cabingatan', 'BSCA', '1', 'hahaha', '$2y$10$se1cwLGKhBJCmUE5OMCADOcnAY1TZnhJt46nf7kbeb9BPtlnSVdGi', NULL, '', NULL, 'user', 30, 0);

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
-- Indexes for table `student_sit_in`
--
ALTER TABLE `student_sit_in`
  ADD PRIMARY KEY (`sit_id`);

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
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=17;

--
-- AUTO_INCREMENT for table `student_sit_in`
--
ALTER TABLE `student_sit_in`
  MODIFY `sit_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=95;

--
-- AUTO_INCREMENT for table `user`
--
ALTER TABLE `user`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2147483648;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
