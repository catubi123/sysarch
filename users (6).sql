-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Mar 19, 2025 at 04:41 AM
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
(0, 'Mark', '2025-03-19', 'haha'),
(0, 'Mark', '2025-03-19', 'haha'),
(0, 'Mark', '2025-03-19', 'haha'),
(0, 'Mark', '2025-03-19', 'haha'),
(0, 'Mark', '2025-03-19', 'haha'),
(0, 'Mark', '2025-03-19', 'haha'),
(0, 'Mark', '2025-03-19', 'haha'),
(0, 'Mark', '2025-03-19', 'haha'),
(0, 'Admin', '2025-03-19 11:41:14', 'Reset all student sessions to 30');

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
(0, 46541, 'PHP', '544', '10:51:32', '04:38:40', '2025-03-17', 'Completed'),
(0, 55564, 'ASP.net', '542', '10:56:03', '04:38:40', '2025-03-17', 'Completed'),
(0, 55564, 'C#', '526', '10:56:43', '04:38:40', '2025-03-17', 'Completed'),
(0, 46541, 'C#', '530', '11:07:35', '04:38:40', '2025-03-17', 'Completed'),
(0, 46541, 'PHP', '530', '10:29:53', '04:38:40', '2025-03-18', 'Completed'),
(0, 46541, 'Java', '544', '10:41:01', '04:38:40', '2025-03-18', 'Completed'),
(0, 789946, 'C#', '542', '10:54:08', '04:38:40', '2025-03-18', 'Completed'),
(0, 789946, 'PHP', '542', '11:13:04', '04:38:40', '2025-03-18', 'Completed'),
(0, 789946, 'PHP', '524', '11:25:00', '04:38:40', '2025-03-18', 'Completed'),
(0, 789946, 'PHP', '530', '11:29:26', '04:38:40', '2025-03-18', 'Completed'),
(0, 22222, 'PHP', '544', '11:32:08', '04:38:40', '2025-03-18', 'Completed'),
(0, 789946, 'Python', '530', '11:59:49', '04:38:40', '2025-03-18', 'Completed'),
(0, 22222, 'Others', '542', '12:12:34', '04:38:40', '2025-03-18', 'Completed'),
(0, 22222, 'ASP.net', '544', '14:20:02', '04:38:40', '2025-03-18', 'Completed'),
(0, 123456, 'PHP', '528', '14:24:05', '04:38:40', '2025-03-18', 'Completed'),
(0, 789946, 'ASP.net', '542', '03:01:47', '04:38:40', '2025-03-19', 'Completed'),
(0, 123456, 'Python', '544', '03:31:42', '04:38:40', '2025-03-19', 'Completed'),
(0, 789946, 'Others', '542', '03:32:51', '04:38:40', '2025-03-19', 'Completed'),
(0, 789946, 'Others', '530', '04:26:00', '04:38:40', '2025-03-19', 'Completed'),
(0, 789946, 'Python', '530', '04:26:10', '04:38:40', '2025-03-19', 'Completed'),
(0, 22222, 'Python', '530', '04:29:20', '04:38:40', '2025-03-19', 'Completed'),
(0, 22222, 'Python', '542', '04:29:32', '04:38:40', '2025-03-19', 'Completed'),
(0, 22222, 'Python', '528', '04:29:54', '04:38:40', '2025-03-19', 'Completed'),
(0, 22222, 'Python', '542', '04:30:06', '04:38:40', '2025-03-19', 'Completed'),
(0, 789946, 'Python', '530', '04:31:07', '04:38:40', '2025-03-19', 'Completed'),
(0, 789946, 'Python', '528', '04:33:51', '04:38:40', '2025-03-19', 'Completed'),
(0, 789946, 'Python', '528', '04:34:03', '04:38:40', '2025-03-19', 'Completed'),
(0, 789946, 'ASP.net', '530', '04:37:45', '04:38:40', '2025-03-19', 'Completed'),
(0, 789946, 'ASP.net', '528', '04:38:22', '04:38:40', '2025-03-19', 'Completed');

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
(22222, 'Mejeliano', 'Shein Michell', 'Cabingatan', 'BSCPE', '4', NULL, '$2y$10$adjXh/zuWKumMCwudjFIteoUUjjthnUHg.AWQCWveRE1nZsBj8hUS', NULL, '', NULL, 'user', 30),
(55564, 'Catubig', 'Mark', 'Dave', 'BSBS', '2', 'amaw', '$2y$10$jfWcbnwqqQt/9TtYQl/BOOWlBQeIbf8zZPjSJMdpydu1T3pnIPxje', NULL, '', NULL, 'user', 30),
(123456, 'Catubig', 'Mark', 'Dave', 'BSCPE', '2', 'user1 ', '$2y$10$ly85IuNmJDNsgwDjeA7e2eC2V6V3SmgkOGn8AbiPhcwGKveoZpGPy', NULL, '', NULL, 'user', 30),
(789946, 'hahah', 'ahaka', 'agaga', 'BSIT', '3', 'rj', '$2y$10$Br8ZIrJIfgn6hZpY.SMILeBGMASJh5Mv9II8K8Xt6eaxUgwdNdbgu', 'uploads/b84dd6df-78f6-488d-b950-9bd095f1b261.jpg', 'rj@gmail.com', 'Dam View Deck, Buhisan', 'user', 30);

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
