-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: May 05, 2025 at 04:53 AM
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
(0, 'Mark', 'April 09, 2025 03:20', 'HAPIT NA HOMAN\r\n'),
(0, 'Mark', 'April 29, 2025 07:50', 'hapit na deadline guys HAHHAHA\r\n'),
(0, 'Mark', 'May 04, 2025 07:19:3', 'pak you  ka');

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
(16, 94, 'user1', 5, 'ok na siguro ni hahahah', '2025-04-09 16:07:48', 'pending'),
(18, 108, 'user1', 5, 'csaca', '2025-05-04 10:15:34', 'pending'),
(19, 109, 'user1', 5, '', '2025-05-04 13:33:19', 'pending'),
(20, 110, 'user1', 5, 'hxbcsldbs\n\n', '2025-05-04 13:36:33', 'pending');

-- --------------------------------------------------------

--
-- Table structure for table `notification`
--

CREATE TABLE `notification` (
  `notification_id` int(11) NOT NULL,
  `id_number` int(11) NOT NULL,
  `message` varchar(100) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `notification`
--

INSERT INTO `notification` (`notification_id`, `id_number`, `message`) VALUES
(0, 22677116, 'Your reservation for Lab 526 on 2025-05-05 at 10:00 has been approved.'),
(0, 22677116, 'Your reservation for Lab 526 on 2025-05-05 at 10:00 has been rejected.'),
(0, 22677116, 'Your reservation for Lab 526 on 2025-05-05 at 10:00 has been approved.'),
(0, 22677116, 'Your reservation for Lab 526 on 2025-05-05 at 10:00 has been approved.'),
(0, 22677116, 'Your reservation for Lab 526 on 2025-05-05 at 10:00 has been approved.'),
(0, 22677116, 'Your reservation for Lab 526 on 2025-05-05 at 10:00 has been approved.'),
(0, 22677116, 'Your reservation for Lab 526 on 2025-05-05 at 10:00 has been approved.'),
(0, 226771156, 'Your reservation for Lab 528 has been approved. You can now use PC #3.'),
(0, 226771156, 'Your reservation for Lab 526 has been approved. You can now use PC #0.'),
(0, 226771156, 'Your reservation for Lab 526 has been approved. You can now use PC #0.'),
(0, 226771156, 'Your reservation for Lab 526 has been approved. You can now use PC #0.'),
(0, 226771156, 'Your reservation for Lab 526 has been approved. You can now use PC #0.'),
(0, 226771156, 'Your reservation for Lab 526 on  at  has been approved.'),
(0, 226771156, 'Your reservation for Lab 524 on  at  has been rejected.'),
(0, 226771156, 'Your reservation for Lab 526 on 2025-05-16 at 20:20 has been approved.'),
(0, 226771156, 'Your reservation for Lab 526 on 2025-05-04 at 20:20 has been approved.'),
(0, 226771156, 'Your reservation for Lab 528 on 2025-05-04 at 20:24 has been approved.'),
(0, 226771156, 'Your reservation for Lab 528 on 2025-05-04 at 20:27 has been approved.'),
(0, 226771156, 'Your reservation for Lab 528 on 2025-05-20 at 20:40 has been approved.'),
(0, 226771156, 'Your reservation for Lab 542 on 2025-05-09 at 20:45 has been approved. Your sit-in has been automati'),
(0, 226771156, 'Your reservation for Lab 528 on 2025-05-24 at 09:11 has been rejected.'),
(0, 22677116, 'Your reservation for Lab 526 on 2025-05-14 at 09:10 has been approved. Your sit-in has been automati'),
(0, 226771156, 'Your reservation for Lab 528 on 2025-05-15 at 09:13 has been approved. Your sit-in has been automati'),
(0, 226771156, 'Your reservation for Lab 526 on 2025-05-10 at 21:01 has been approved. Your sit-in has been automati'),
(0, 226771156, 'Your reservation for Lab 524 on 2025-05-10 at 09:42 has been approved. Your sit-in has been automati'),
(0, 226771156, 'Your reservation for Lab 530 on 2025-05-15 at 10:37 has been approved. Your sit-in has been automati'),
(0, 226771156, 'Your reservation for Lab 524 on 2025-05-09 at 10:49 has been approved. Your sit-in has been automati');

-- --------------------------------------------------------

--
-- Table structure for table `pc_numbers`
--

CREATE TABLE `pc_numbers` (
  `lab_number` varchar(10) NOT NULL,
  `pc_number` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `pc_numbers`
--

INSERT INTO `pc_numbers` (`lab_number`, `pc_number`) VALUES
('524', 1),
('524', 2),
('524', 3),
('524', 4),
('524', 5),
('524', 6),
('524', 7),
('524', 8),
('524', 9),
('524', 10),
('524', 11),
('524', 12),
('524', 13),
('524', 14),
('524', 15),
('524', 16),
('524', 17),
('524', 18),
('524', 19),
('524', 20),
('524', 21),
('524', 22),
('524', 23),
('524', 24),
('524', 25),
('526', 1),
('526', 2),
('526', 3),
('526', 4),
('526', 5),
('526', 6),
('526', 7),
('526', 8),
('526', 9),
('526', 10),
('526', 11),
('526', 12),
('526', 13),
('526', 14),
('526', 15),
('526', 16),
('526', 17),
('526', 18),
('526', 19),
('526', 20),
('526', 21),
('526', 22),
('526', 23),
('526', 24),
('526', 25),
('528', 1),
('528', 2),
('528', 3),
('528', 4),
('528', 5),
('528', 6),
('528', 7),
('528', 8),
('528', 9),
('528', 10),
('528', 11),
('528', 12),
('528', 13),
('528', 14),
('528', 15),
('528', 16),
('528', 17),
('528', 18),
('528', 19),
('528', 20),
('528', 21),
('528', 22),
('528', 23),
('528', 24),
('528', 25),
('530', 1),
('530', 2),
('530', 3),
('530', 4),
('530', 5),
('530', 6),
('530', 7),
('530', 8),
('530', 9),
('530', 10),
('530', 11),
('530', 12),
('530', 13),
('530', 14),
('530', 15),
('530', 16),
('530', 17),
('530', 18),
('530', 19),
('530', 20),
('530', 21),
('530', 22),
('530', 23),
('530', 24),
('530', 25),
('542', 1),
('542', 2),
('542', 3),
('542', 4),
('542', 5),
('542', 6),
('542', 7),
('542', 8),
('542', 9),
('542', 10),
('542', 11),
('542', 12),
('542', 13),
('542', 14),
('542', 15),
('542', 16),
('542', 17),
('542', 18),
('542', 19),
('542', 20),
('542', 21),
('542', 22),
('542', 23),
('542', 24),
('542', 25),
('544', 1),
('544', 2),
('544', 3),
('544', 4),
('544', 5),
('544', 6),
('544', 7),
('544', 8),
('544', 9),
('544', 10),
('544', 11),
('544', 12),
('544', 13),
('544', 14),
('544', 15),
('544', 16),
('544', 17),
('544', 18),
('544', 19),
('544', 20),
('544', 21),
('544', 22),
('544', 23),
('544', 24),
('544', 25);

-- --------------------------------------------------------

--
-- Table structure for table `pc_status`
--

CREATE TABLE `pc_status` (
  `lab_number` varchar(10) NOT NULL,
  `pc_number` int(11) NOT NULL,
  `is_active` tinyint(1) DEFAULT 1
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `pc_status`
--

INSERT INTO `pc_status` (`lab_number`, `pc_number`, `is_active`) VALUES
('524', 1, 1),
('524', 2, 1),
('524', 3, 1),
('524', 4, 1),
('524', 5, 1),
('524', 8, 1),
('526', 1, 1),
('526', 2, 0),
('526', 3, 1),
('526', 5, 1),
('526', 10, 1);

-- --------------------------------------------------------

--
-- Table structure for table `reservation`
--

CREATE TABLE `reservation` (
  `reservation_id` int(11) NOT NULL,
  `id_number` int(11) NOT NULL,
  `lab` varchar(11) NOT NULL,
  `pc_number` int(11) NOT NULL,
  `purpose` varchar(50) NOT NULL,
  `reservation_date` varchar(10) NOT NULL,
  `reservation_time` varchar(10) NOT NULL,
  `status` varchar(10) NOT NULL DEFAULT 'pending',
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `reservation`
--

INSERT INTO `reservation` (`reservation_id`, `id_number`, `lab`, `pc_number`, `purpose`, `reservation_date`, `reservation_time`, `status`, `created_at`) VALUES
(1, 226771156, '528', 3, 'DGILOG', '2025-05-04', '13:23', 'approved', '2025-05-04 05:23:52'),
(2, 226771156, '526', 0, 'Python', '2025-05-04', '13:34', 'approved', '2025-05-04 05:34:59'),
(3, 226771156, '526', 0, 'Python', '2025-05-04', '13:36', 'approved', '2025-05-04 05:36:59'),
(4, 226771156, '526', 0, 'Python', '2025-05-04', '19:22', 'approved', '2025-05-04 11:22:49'),
(5, 226771156, '526', 0, 'Python', '2025-05-04', '19:28', 'approved', '2025-05-04 11:28:57'),
(8, 226771156, '526', 33, 'System', '2025-05-04', '20:20', 'approved', '2025-05-04 12:20:28'),
(9, 226771156, '526', 25, 'Python', '2025-05-16', '20:20', 'approved', '2025-05-04 12:20:52'),
(10, 226771156, '528', 2, 'System', '2025-05-04', '20:24', 'approved', '2025-05-04 12:24:10'),
(11, 226771156, '528', 1, 'System', '2025-05-04', '20:27', 'approved', '2025-05-04 12:27:19'),
(12, 226771156, '528', 0, 'System', '2025-05-20', '20:40', 'approved', '2025-05-04 12:40:11'),
(13, 226771156, '542', 2, 'Python', '2025-05-09', '20:45', 'approved', '2025-05-04 12:45:50'),
(14, 22677116, '526', 0, 'Others', '2025-05-14', '09:10', 'approved', '2025-05-04 13:10:55'),
(15, 226771156, '528', 0, 'C#', '2025-05-24', '09:11', 'rejected', '2025-05-04 13:11:39'),
(16, 226771156, '528', 0, 'Python', '2025-05-15', '09:13', 'approved', '2025-05-04 13:13:18'),
(22, 226771156, '526', 0, 'Python', '2025-05-10', '21:01', 'approved', '2025-05-05 01:01:13'),
(23, 226771156, '524', 0, 'System', '2025-05-10', '09:42', 'approved', '2025-05-05 01:42:50'),
(24, 226771156, '530', 0, 'Python', '2025-05-15', '10:37', 'approved', '2025-05-05 02:37:13'),
(25, 226771156, '524', 0, 'Web', '2025-05-09', '10:49', 'approved', '2025-05-05 02:49:46');

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
  `pc_number` int(11) NOT NULL DEFAULT 0,
  `time_in` varchar(15) NOT NULL,
  `time_out` varchar(15) NOT NULL,
  `sit_date` varchar(10) NOT NULL,
  `status` varchar(20) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `student_sit_in`
--

INSERT INTO `student_sit_in` (`sit_id`, `id_number`, `sit_purpose`, `sit_lab`, `pc_number`, `time_in`, `time_out`, `sit_date`, `status`) VALUES
(1, 46541, 'PHP', '544', 0, '10:51:32', '06:47:33', '2025-03-17', 'Completed'),
(2, 55564, 'ASP.net', '542', 0, '10:56:03', '06:47:33', '2025-03-17', 'Completed'),
(3, 55564, 'C#', '526', 0, '10:56:43', '06:47:33', '2025-03-17', 'Completed'),
(4, 46541, 'C#', '530', 0, '11:07:35', '06:47:33', '2025-03-17', 'Completed'),
(5, 46541, 'PHP', '530', 0, '10:29:53', '06:47:33', '2025-03-18', 'Completed'),
(6, 46541, 'Java', '544', 0, '10:41:01', '06:47:33', '2025-03-18', 'Completed'),
(7, 789946, 'C#', '542', 0, '10:54:08', '06:47:33', '2025-03-18', 'Completed'),
(8, 789946, 'PHP', '542', 0, '11:13:04', '06:47:33', '2025-03-18', 'Completed'),
(9, 789946, 'PHP', '524', 0, '11:25:00', '06:47:33', '2025-03-18', 'Completed'),
(10, 789946, 'PHP', '530', 0, '11:29:26', '06:47:33', '2025-03-18', 'Completed'),
(11, 22222, 'PHP', '544', 0, '11:32:08', '06:47:33', '2025-03-18', 'Completed'),
(12, 789946, 'Python', '530', 0, '11:59:49', '06:47:33', '2025-03-18', 'Completed'),
(13, 22222, 'Others', '542', 0, '12:12:34', '06:47:33', '2025-03-18', 'Completed'),
(14, 22222, 'ASP.net', '544', 0, '14:20:02', '06:47:33', '2025-03-18', 'Completed'),
(15, 123456, 'PHP', '528', 0, '14:24:05', '06:47:33', '2025-03-18', 'Completed'),
(16, 789946, 'ASP.net', '542', 0, '03:01:47', '06:47:33', '2025-03-19', 'Completed'),
(17, 123456, 'Python', '544', 0, '03:31:42', '06:47:33', '2025-03-19', 'Completed'),
(18, 789946, 'Others', '542', 0, '03:32:51', '06:47:33', '2025-03-19', 'Completed'),
(19, 789946, 'Others', '530', 0, '04:26:00', '06:47:33', '2025-03-19', 'Completed'),
(20, 789946, 'Python', '530', 0, '04:26:10', '06:47:33', '2025-03-19', 'Completed'),
(21, 22222, 'Python', '530', 0, '04:29:20', '06:47:33', '2025-03-19', 'Completed'),
(22, 22222, 'Python', '542', 0, '04:29:32', '06:47:33', '2025-03-19', 'Completed'),
(23, 22222, 'Python', '528', 0, '04:29:54', '06:47:33', '2025-03-19', 'Completed'),
(24, 22222, 'Python', '542', 0, '04:30:06', '06:47:33', '2025-03-19', 'Completed'),
(25, 789946, 'Python', '530', 0, '04:31:07', '06:47:33', '2025-03-19', 'Completed'),
(26, 789946, 'Python', '528', 0, '04:33:51', '06:47:33', '2025-03-19', 'Completed'),
(27, 789946, 'Python', '528', 0, '04:34:03', '06:47:33', '2025-03-19', 'Completed'),
(28, 789946, 'ASP.net', '530', 0, '04:37:45', '06:47:33', '2025-03-19', 'Completed'),
(29, 789946, 'ASP.net', '528', 0, '04:38:22', '06:47:33', '2025-03-19', 'Completed'),
(30, 789946, 'Python', '526', 0, '04:47:46', '06:47:33', '2025-03-19', 'Completed'),
(31, 789946, 'ASP.net', '542', 0, '09:20:36', '06:47:33', '2025-03-19', 'Completed'),
(32, 789946, 'Python', '544', 0, '09:24:50', '06:47:33', '2025-03-19', 'Completed'),
(33, 789946, 'ASP.net', '526', 0, '11:16:58', '06:47:33', '2025-03-19', 'Completed'),
(34, 789946, 'ASP.net', '526', 0, '13:46:50', '06:47:33', '2025-03-19', 'Completed'),
(35, 789946, 'Python', '542', 0, '13:47:20', '06:47:33', '2025-03-19', 'Completed'),
(36, 789946, 'ASP.net', '524', 0, '14:02:06', '06:47:33', '2025-03-19', 'Completed'),
(37, 789946, 'PHP Programming', '528', 0, '14:46:56', '06:47:33', '2025-03-19', 'Completed'),
(38, 123456, 'ASP.net Programming', '544', 0, '15:40:58', '06:47:33', '2025-03-19', 'Completed'),
(39, 123456, 'Java ', '526', 0, '15:44:46', '06:47:33', '2025-03-19', 'Completed'),
(40, 789946, 'ASP.net ', '524', 0, '15:59:47', '06:47:33', '2025-03-19', 'Completed'),
(41, 789946, 'ASP.net ', '524', 0, '16:00:00', '06:47:33', '2025-03-19', 'Completed'),
(42, 789946, 'ASP.net ', '530', 0, '16:00:17', '06:47:33', '2025-03-19', 'Completed'),
(43, 789946, 'Python ', '544', 0, '16:00:30', '06:47:33', '2025-03-19', 'Completed'),
(44, 789946, 'C# ', '526', 0, '16:00:44', '06:47:33', '2025-03-19', 'Completed'),
(45, 123456, 'Python ', '530', 0, '16:16:54', '06:47:33', '2025-03-19', 'Completed'),
(46, 22677116, 'Python ', '526', 0, '16:35:17', '06:47:33', '2025-03-19', 'Completed'),
(47, 22677116, 'ASP.net ', '542', 0, '16:39:46', '06:47:33', '2025-03-19', 'Completed'),
(48, 789946, 'Python ', '528', 0, '08:29:33', '06:47:33', '2025-03-20', 'Completed'),
(49, 22677116, 'Java ', '526', 0, '10:34:52', '06:47:33', '2025-03-21', 'Completed'),
(50, 2147483647, 'PHP ', '526', 0, '06:43:39', '06:47:33', '2025-03-22', 'Completed'),
(51, 22677116, 'ASP.net ', '542', 0, '08:19:42', '06:47:33', '2025-03-30', 'Completed'),
(52, 2147483647, 'ASP.net ', '526', 0, '08:19:58', '06:47:33', '2025-03-30', 'Completed'),
(53, 226771156, 'Python ', '530', 0, '15:27:45', '06:47:33', '2025-04-04', 'Completed'),
(54, 226771156, 'ASP.net ', '528', 0, '15:51:12', '06:47:33', '2025-04-04', 'Completed'),
(55, 226771156, 'PHP ', '524', 0, '15:54:18', '06:47:33', '2025-04-04', 'Completed'),
(56, 226771156, 'Python ', '528', 0, '15:58:30', '06:47:33', '2025-04-04', 'Completed'),
(57, 2222, 'PHP ', '526', 0, '16:15:56', '06:47:33', '2025-04-04', 'Completed'),
(58, 226771156, 'PHP ', '528', 0, '18:17:05', '06:47:33', '2025-04-05', 'Completed'),
(59, 226771156, 'ASP.net ', '526', 0, '18:26:22', '06:47:33', '2025-04-05', 'Completed'),
(60, 226771156, 'ASP.net ', '530', 0, '18:38:13', '06:47:33', '2025-04-05', 'Completed'),
(61, 226771156, 'PHP ', '528', 0, '18:46:43', '06:47:33', '2025-04-05', 'Completed'),
(62, 226771156, 'ASP.net ', '528', 0, '19:02:14', '06:47:33', '2025-04-05', 'Completed'),
(63, 226771156, 'Python ', '524', 0, '19:03:10', '06:47:33', '2025-04-05', 'Completed'),
(64, 226771156, 'C# ', '544', 0, '19:10:05', '06:47:33', '2025-04-05', 'Completed'),
(65, 226771156, 'ASP.net ', '530', 0, '19:15:58', '06:47:33', '2025-04-05', 'Completed'),
(66, 2222, 'C#', '528', 0, '19:40:21', '06:47:33', '2025-04-05', 'Completed'),
(67, 226771156, 'PHP ', '526', 0, '19:44:46', '06:47:33', '2025-04-05', 'Completed'),
(68, 226771156, 'Python ', '528', 0, '19:53:36', '06:47:33', '2025-04-05', 'Completed'),
(69, 226771156, 'PHP ', '528', 0, '19:56:39', '06:47:33', '2025-04-05', 'Completed'),
(70, 226771156, 'Java ', '526', 0, '20:03:29', '06:47:33', '2025-04-05', 'Completed'),
(71, 226771156, 'PHP ', '526', 0, '20:09:53', '06:47:33', '2025-04-05', 'Completed'),
(72, 226771156, 'PHP ', '542', 0, '04:32:25', '06:47:33', '2025-04-06', 'Completed'),
(73, 226771156, 'ASP.net ', '528', 0, '05:16:19', '06:47:33', '2025-04-06', 'Completed'),
(74, 226771156, 'ASP.net ', '544', 0, '05:17:01', '06:47:33', '2025-04-06', 'Completed'),
(75, 226771156, 'C# ', '528', 0, '05:20:29', '06:47:33', '2025-04-06', 'Completed'),
(76, 226771156, 'Java ', '530', 0, '05:24:10', '06:47:33', '2025-04-06', 'Completed'),
(77, 226771156, 'Java ', '528', 0, '05:41:52', '06:47:33', '2025-04-06', 'Completed'),
(78, 226771156, 'ASP.net ', '530', 0, '06:02:26', '06:47:33', '2025-04-06', 'Completed'),
(79, 226771156, 'ASP.net ', '530', 0, '06:33:17', '06:47:33', '2025-04-06', 'Completed'),
(80, 226771156, 'ASP.net ', '528', 0, '06:38:18', '06:47:33', '2025-04-06', 'Completed'),
(81, 226771156, 'ASP.net ', '526', 0, '06:47:30', '06:47:33', '2025-04-06', 'Completed'),
(82, 226771156, 'ASP.net ', '528', 0, '06:54:20', '06:54:22', '2025-04-06', 'Completed'),
(83, 226771156, 'ASP.net ', '526', 0, '17:28:05', '17:28:07', '2025-04-08', 'Completed'),
(84, 226771156, 'PHP ', '528', 0, '17:43:44', '17:43:45', '2025-04-08', 'Completed'),
(85, 226771156, 'C# ', '530', 0, '17:56:53', '17:56:55', '2025-04-08', 'Completed'),
(86, 226771156, 'Java ', '530', 0, '17:59:31', '17:59:32', '2025-04-08', 'Completed'),
(87, 226771156, 'ASP.net ', '542', 0, '18:05:56', '18:05:58', '2025-04-08', 'Completed'),
(88, 226771156, 'Python ', '528', 0, '18:12:53', '18:12:55', '2025-04-08', 'Completed'),
(89, 226771156, 'PHP ', '542', 0, '18:16:38', '18:16:40', '2025-04-08', 'Completed'),
(90, 226771156, 'PHP ', '530', 0, '18:22:44', '18:22:46', '2025-04-08', 'Completed'),
(91, 226771156, 'PHP ', '528', 0, '18:36:32', '18:36:34', '2025-04-08', 'Completed'),
(92, 226771156, 'PHP ', '526', 0, '18:43:09', '18:43:11', '2025-04-08', 'Completed'),
(93, 226771156, 'ASP.net ', '528', 0, '09:58:44', '09:58:45', '2025-04-09', 'Completed'),
(94, 226771156, 'Python ', '542', 0, '10:06:33', '10:06:34', '2025-04-09', 'Completed'),
(95, 226771156, 'PHP', '542', 0, '13:04:45', '2025-04-29 19:0', '2025-04-29', 'Completed'),
(96, 226771156, 'Embedded', '542', 0, '13:15:10', '2025-04-29 19:1', '2025-04-29', 'Completed'),
(97, 226771156, 'Python', '542', 0, '13:22:51', '2025-04-29 19:2', '2025-04-29', 'Completed'),
(98, 226771156, 'Python', '528', 0, '13:23:13', '2025-04-29 19:2', '2025-04-29', 'Completed'),
(99, 226771156, 'Others', '530', 0, '13:25:08', '2025-04-29 19:2', '2025-04-29', 'Completed'),
(100, 226771156, 'PHP', '542', 0, '13:25:18', '2025-04-29 19:2', '2025-04-29', 'Completed'),
(101, 226771156, 'PHP', '530', 0, '13:25:28', '2025-04-29 19:2', '2025-04-29', 'Completed'),
(102, 226771156, 'Python', '528', 0, '13:26:31', '2025-04-29 19:2', '2025-04-29', 'Completed'),
(103, 226771156, 'System', '530', 0, '13:26:43', '2025-04-29 19:2', '2025-04-29', 'Completed'),
(104, 226771156, 'Web', '544', 0, '13:26:54', '2025-04-29 19:2', '2025-04-29', 'Completed'),
(105, 2147483647, 'Others', '542', 0, '13:27:25', '2025-04-29 19:2', '2025-04-29', 'Completed'),
(106, 2147483647, 'Database', '524', 0, '13:27:38', '2025-04-29 19:2', '2025-04-29', 'Completed'),
(107, 2147483647, 'PHP', '542', 0, '13:27:49', '2025-04-29 19:2', '2025-04-29', 'Completed'),
(108, 226771156, 'Java', '528', 0, '09:28:18', '2025-05-02 15:2', '2025-05-02', 'Completed'),
(109, 226771156, 'DGILOG', '528', 3, '07:32:09', '2025-05-04 13:3', '2025-05-04', 'Completed'),
(110, 226771156, 'Python', '526', 0, '07:35:50', '2025-05-04 13:3', '2025-05-04', 'Completed'),
(111, 226771156, 'Python', '526', 0, '12:39:21', '2025-05-04 18:4', '2025-05-04', 'Completed'),
(112, 226771156, 'Python', '526', 0, '13:27:48', '2025-05-04 19:2', '2025-05-04', 'Completed'),
(113, 226771156, 'Python', '526', 0, '13:29:48', '2025-05-04 20:1', '2025-05-04', 'Completed'),
(114, 226771156, 'Python', '542', 0, '20:45', '2025-05-04 20:4', '2025-05-09', 'Completed'),
(115, 226771156, 'DGILOG', '530', 0, '15:05:30', '2025-05-04 21:0', '2025-05-04', 'Completed'),
(116, 22677116, 'Others', '526', 0, '09:10', '2025-05-04 21:1', '2025-05-14', 'Completed'),
(117, 226771156, 'Python', '528', 0, '09:13', '15:14:16', '2025-05-15', 'Completed'),
(118, 226771156, 'Python', '526', 0, '21:01', '2025-05-05 09:0', '2025-05-10', 'Completed'),
(119, 226771156, 'System', '524', 0, '09:42', '2025-05-05 10:3', '2025-05-10', 'Completed'),
(120, 226771156, 'Python', '530', 0, '10:37', '2025-05-05 10:3', '2025-05-15', 'Completed'),
(121, 226771156, 'Web', '524', 0, '10:49', '04:51:44', '2025-05-09', 'Completed');

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
(22677116, 'Catubig', 'Mark Dave', 'Cabingatan', 'BSIT', '3', 'mark', '$2y$10$zchvWcHidQPT0Ac7k0B8jub.KnCFlWdbnDeIJnoG8zhLCaRvB5OKC', 'uploads/320738403_1115980445778336_3190117843245493251_n.jpg', 'catubigmarkdave0@gmail.com', 'Dam View Deck, Buhisan', 'user', 30, 1),
(226771156, 'Soberano', 'Elizabeth', '', 'BSCRIM', '3', 'user1', '$2y$10$yr4fm5tbvBsk5CufQvvgF.lhDHrP4GH0vBrgpk2udHF2n/1MnmZCm', 'uploads/jordan1.jpg', 'm@gmail.com', '', 'user', 31, 14),
(2147483647, 'Racuma', 'Denise', 'Cabingatan', 'BSCA', '1', 'hahaha', '$2y$10$se1cwLGKhBJCmUE5OMCADOcnAY1TZnhJt46nf7kbeb9BPtlnSVdGi', NULL, '', NULL, 'user', 28, 3);

--
-- Indexes for dumped tables
--

--
-- Indexes for table `feedback`
--
ALTER TABLE `feedback`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `pc_numbers`
--
ALTER TABLE `pc_numbers`
  ADD PRIMARY KEY (`lab_number`,`pc_number`);

--
-- Indexes for table `pc_status`
--
ALTER TABLE `pc_status`
  ADD PRIMARY KEY (`lab_number`,`pc_number`);

--
-- Indexes for table `reservation`
--
ALTER TABLE `reservation`
  ADD PRIMARY KEY (`reservation_id`),
  ADD KEY `id_number` (`id_number`);

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
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=21;

--
-- AUTO_INCREMENT for table `reservation`
--
ALTER TABLE `reservation`
  MODIFY `reservation_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=26;

--
-- AUTO_INCREMENT for table `student_sit_in`
--
ALTER TABLE `student_sit_in`
  MODIFY `sit_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=122;

--
-- AUTO_INCREMENT for table `user`
--
ALTER TABLE `user`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2147483648;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `reservation`
--
ALTER TABLE `reservation`
  ADD CONSTRAINT `reservation_ibfk_1` FOREIGN KEY (`id_number`) REFERENCES `user` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
