-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Mar 19, 2025 at 02:37 PM
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
(0, 'Mark', 'March 19, 2025 09:20', 'amawa nimo\r\n'),
(0, 'Mark', 'March 19, 2025 09:21', 'lage'),
(0, 'Mark', 'March 19, 2025 09:21', 'c chds'),
(0, 'Admin', '2025-03-19 21:34:34', 'Reset all student sessions to 30');

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
(0, 46541, 'PHP', '544', '10:51:32', '14:03:17', '2025-03-17', 'Completed'),
(0, 55564, 'ASP.net', '542', '10:56:03', '14:03:17', '2025-03-17', 'Completed'),
(0, 55564, 'C#', '526', '10:56:43', '14:03:17', '2025-03-17', 'Completed'),
(0, 46541, 'C#', '530', '11:07:35', '14:03:17', '2025-03-17', 'Completed'),
(0, 46541, 'PHP', '530', '10:29:53', '14:03:17', '2025-03-18', 'Completed'),
(0, 46541, 'Java', '544', '10:41:01', '14:03:17', '2025-03-18', 'Completed'),
(0, 789946, 'C#', '542', '10:54:08', '14:03:17', '2025-03-18', 'Completed'),
(0, 789946, 'PHP', '542', '11:13:04', '14:03:17', '2025-03-18', 'Completed'),
(0, 789946, 'PHP', '524', '11:25:00', '14:03:17', '2025-03-18', 'Completed'),
(0, 789946, 'PHP', '530', '11:29:26', '14:03:17', '2025-03-18', 'Completed'),
(0, 22222, 'PHP', '544', '11:32:08', '14:03:17', '2025-03-18', 'Completed'),
(0, 789946, 'Python', '530', '11:59:49', '14:03:17', '2025-03-18', 'Completed'),
(0, 22222, 'Others', '542', '12:12:34', '14:03:17', '2025-03-18', 'Completed'),
(0, 22222, 'ASP.net', '544', '14:20:02', '14:03:17', '2025-03-18', 'Completed'),
(0, 123456, 'PHP', '528', '14:24:05', '14:03:17', '2025-03-18', 'Completed'),
(0, 789946, 'ASP.net', '542', '03:01:47', '14:03:17', '2025-03-19', 'Completed'),
(0, 123456, 'Python', '544', '03:31:42', '14:03:17', '2025-03-19', 'Completed'),
(0, 789946, 'Others', '542', '03:32:51', '14:03:17', '2025-03-19', 'Completed'),
(0, 789946, 'Others', '530', '04:26:00', '14:03:17', '2025-03-19', 'Completed'),
(0, 789946, 'Python', '530', '04:26:10', '14:03:17', '2025-03-19', 'Completed'),
(0, 22222, 'Python', '530', '04:29:20', '14:03:17', '2025-03-19', 'Completed'),
(0, 22222, 'Python', '542', '04:29:32', '14:03:17', '2025-03-19', 'Completed'),
(0, 22222, 'Python', '528', '04:29:54', '14:03:17', '2025-03-19', 'Completed'),
(0, 22222, 'Python', '542', '04:30:06', '14:03:17', '2025-03-19', 'Completed'),
(0, 789946, 'Python', '530', '04:31:07', '14:03:17', '2025-03-19', 'Completed'),
(0, 789946, 'Python', '528', '04:33:51', '14:03:17', '2025-03-19', 'Completed'),
(0, 789946, 'Python', '528', '04:34:03', '14:03:17', '2025-03-19', 'Completed'),
(0, 789946, 'ASP.net', '530', '04:37:45', '14:03:17', '2025-03-19', 'Completed'),
(0, 789946, 'ASP.net', '528', '04:38:22', '14:03:17', '2025-03-19', 'Completed'),
(0, 789946, 'Python', '526', '04:47:46', '14:03:17', '2025-03-19', 'Completed'),
(0, 789946, 'ASP.net', '542', '09:20:36', '14:03:17', '2025-03-19', 'Completed'),
(0, 789946, 'Python', '544', '09:24:50', '14:03:17', '2025-03-19', 'Completed'),
(0, 789946, 'ASP.net', '526', '11:16:58', '14:03:17', '2025-03-19', 'Completed'),
(0, 789946, 'ASP.net', '526', '13:46:50', '14:03:17', '2025-03-19', 'Completed'),
(0, 789946, 'Python', '542', '13:47:20', '14:03:17', '2025-03-19', 'Completed'),
(0, 789946, 'ASP.net', '524', '14:02:06', '14:03:17', '2025-03-19', 'Completed');

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
(123456, 'Catubig', 'Mark', 'Dave', 'BSCPE', '2', 'user1 ', '$2y$10$ly85IuNmJDNsgwDjeA7e2eC2V6V3SmgkOGn8AbiPhcwGKveoZpGPy', 'uploads/320738403_1115980445778336_3190117843245493251_n.jpg', 'catubigmarkdave0@gmail.com', 'Dam View Deck, Buhisan', 'user', 30),
(789946, 'hahah', 'ahaka', 'agaga', 'BSIT', '1', 'rj', '$2y$10$Br8ZIrJIfgn6hZpY.SMILeBGMASJh5Mv9II8K8Xt6eaxUgwdNdbgu', 'uploads/b84dd6df-78f6-488d-b950-9bd095f1b261.jpg', 'rj@gmail.com', 'Dam View Deck, Buhisan', 'user', 30);

--
-- Indexes for dumped tables
--

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
-- AUTO_INCREMENT for table `user`
--
ALTER TABLE `user`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=22677117;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
