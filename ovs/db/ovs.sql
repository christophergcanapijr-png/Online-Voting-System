-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: May 22, 2025 at 04:05 PM
-- Server version: 10.4.32-MariaDB
-- PHP Version: 8.0.30

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `ovs`
--

-- --------------------------------------------------------

--
-- Table structure for table `candidate`
--

CREATE TABLE `candidate` (
  `CandidateID` int(11) NOT NULL,
  `FirstName` varchar(200) NOT NULL,
  `LastName` varchar(200) NOT NULL,
  `MiddleName` varchar(100) NOT NULL,
  `Position` varchar(200) NOT NULL,
  `Party` varchar(100) NOT NULL,
  `Gender` varchar(6) NOT NULL,
  `Year` varchar(100) NOT NULL,
  `Photo` varchar(200) NOT NULL,
  `Platform` text DEFAULT NULL,
  `academic_year` varchar(20) NOT NULL DEFAULT '2024-2025'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `candidate`
--

INSERT INTO `candidate` (`CandidateID`, `FirstName`, `LastName`, `MiddleName`, `Position`, `Party`, `Gender`, `Year`, `Photo`, `Platform`, `academic_year`) VALUES
(1, 'davey', 'langiy', 'da', 'Governor', 'dsa', 'Male', '1st year', 'images/1746796136_WIN_20250504_00_03_37_Pro.jpg', 'dsa', '2024-2025'),
(2, 'karem', 'hanzo', 'jackie', '2nd Year Representative', 'davet', 'FeMale', '2nd year', 'images/1746797053_2024_Predator_option_01_3840x2400.jpg', 'ds', '2024-2025'),
(4, 'stewie', 'twin', 'dsa', '3rd Year Representative', 'dsa', 'Male', '3rd year', 'images/1746798322_2024_Predator_option_02_3840x2400.jpg', 'dsa', '2024-2025'),
(5, 'karem', 'abdul', 'dave', 'Governor', 'dsa', 'Male', '1st year', 'images/1746871680_2024_Predator_option_01_3840x2400.jpg', 'dsa', '2026-2027'),
(6, 'garry', 'espinosa', 'G', '1st Year Representative', 'Tindig', 'Male', '1st year', 'images/1747050538_Screenshot (2).png', 'hanzo', '2024-2025'),
(7, 'jerry', 'smith', '', '1st Year Representative', 'h', 'Male', '1st year', 'images/1747050564_Screenshot (5).png', 'jac', '2024-2025'),
(8, 'ha', 'hsh', 'gsa', 'Governor', 'gsa', 'Male', '1st year', 'images/1747051182_Screenshot (2).png', 'gsa', '2024-2025'),
(9, 'saga', 'gsaga', 'sa', 'Governor', 'hsah', 'Male', '1st year', 'images/1747051193_Screenshot (4).png', 'sa', '2024-2025'),
(11, 'fsa', 'gsa', 'gsa', '4th Year Representative', 'dsa', 'Male', '4th year', 'images/1747054695_2024_Predator_option_02_3840x2400.jpg', 'dsa', '2024-2025'),
(13, 'jhon', 'raki', 'ha', '4th Year Representative', '4', 'Male', '1st year', 'images/1747369739_Add a little bit of body text.jpg', 'ds', '2024-2025'),
(14, 'hanzo', 'ger', 'dsaf', 'Vice-Governor', 'fer', 'Male', '1st year', 'images/1747369771_496509156_1434199197579431_4998270256963238929_n.jpg', 'das', '2024-2025'),
(15, 'Kyle ', 'Hunter', 'G', 'Governor', 'H', 'Male', '2nd year', 'images/1747581292_Screenshot (1).png', 'da', '2025-2026'),
(16, 'Clarice ', 'Maxwell', 'G', 'Governor', 'H', 'FeMale', '3rd year', 'images/1747581317_Screenshot (2).png', 'hanzo', '2025-2026'),
(17, 'Lula ', 'Love', 'davey', '2nd Year Representative', 'Hda', 'Male', '2nd year', 'images/1747582238_Screenshot (9).png', 'da', '2025-2026'),
(18, 'Chang ', 'Benitez', 'd', 'Vice-Governor', 'hamz', 'Male', '4th year', 'images/1747582259_Screenshot (10).png', 'da', '2025-2026'),
(19, 'Amparo ', 'Becker', 'da', 'Vice-Governor', 'da', 'Male', '3rd year', 'images/1747582285_Screenshot 2025-05-18 225119.png', 'ds', '2025-2026'),
(20, 'Beverly', 'Stewart', 'd', '1st Year Representative', 'd', 'Male', '1st year', 'images/1747582433_Screenshot (9).png', 'd', '2025-2026'),
(21, 'Jordyn ', 'Howe', 'G', 'Governor', 'A', 'Male', '1st year', 'images/1747710364_pexels-italo-melo-881954-2379005.jpg', 'B', '2025-2026'),
(22, 'Madalyn ', 'Meyers', '', 'Vice-Governor', 'a', 'Male', '1st year', 'images/1747710383_pexels-pixabay-415829.jpg', 'a', '2025-2026'),
(23, 'Hallie ', 'Cuevas', 'G', 'Governor', 'G', 'FeMale', '3rd year', 'images/1747710401_pexels-heitorverdifotos-2169434.jpg', 'j', '2025-2026'),
(24, 'Malayah', 'Joseph', '', '2nd Year Representative', 'a', 'Male', '1st year', 'images/1747710420_pexels-justin-shaifer-501272-1222271.jpg', 'a', '2025-2026'),
(25, 'Adalee ', 'Vincent', 'A', 'Governor', 'A', 'Male', '1st year', 'images/1747710435_pexels-tarzine-jackson-254126-773371.jpg', 'a', '2025-2026'),
(26, 'Madalynn ', 'Cummings', 'G', 'Governor', 'G', 'Male', '3rd year', 'images/1747710451_pexels-marcelodias-2104252.jpg', 'G', '2025-2026'),
(27, 'Rylan ', 'Allen', 'A', '3rd Year Representative', 'A', 'Male', '1st year', 'images/1747710473_pexels-marcelodias-2104252.jpg', 'A', '2025-2026'),
(28, 'Saoirse ', 'Terry', 'dave', '4th Year Representative', 'd', 'Male', '4th year', 'images/1747710498_pexels-olly-3799786.jpg', 'd', '2025-2026');

-- --------------------------------------------------------

--
-- Table structure for table `candidates_history`
--

CREATE TABLE `candidates_history` (
  `id` int(11) NOT NULL,
  `candidate_id` int(11) DEFAULT NULL,
  `first_name` varchar(100) DEFAULT NULL,
  `last_name` varchar(100) DEFAULT NULL,
  `position` varchar(100) DEFAULT NULL,
  `academic_year` varchar(20) NOT NULL,
  `archived_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `history`
--

CREATE TABLE `history` (
  `history_id` int(11) NOT NULL,
  `data` text NOT NULL,
  `action` varchar(50) NOT NULL,
  `date` datetime NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  `user_id` int(11) NOT NULL,
  `academic_year` varchar(20) NOT NULL DEFAULT '2024-2025'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `history`
--

INSERT INTO `history` (`history_id`, `data`, `action`, `date`, `user_id`, `academic_year`) VALUES
(144, 'Admin logged out', 'Logout', '2025-05-13 17:58:37', 1, '2024-2025'),
(145, 'Updated user: admin1', 'Edit User', '2025-05-13 17:58:49', 1, '2024-2025'),
(146, 'Admin logged out', 'Logout', '2025-05-13 17:59:04', 1, '2024-2025'),
(147, '606 - bayot a', 'Deleted voter', '2025-05-13 17:59:16', 1, '2024-2025'),
(148, 'Admin logged out', 'Logout', '2025-05-13 17:59:41', 1, '2024-2025'),
(149, 'asa hhsa - Vice-Governor', 'Deleted candidate', '2025-05-13 18:00:46', 1, '2024-2025'),
(150, 'Admin logged out', 'Logout', '2025-05-13 18:00:54', 1, '2024-2025'),
(151, 'Admin logged out', 'Logout', '2025-05-13 18:05:25', 1, '2024-2025'),
(152, 'jeremy inner - Vice-Governor', 'Added candidate', '2025-05-13 19:12:33', 1, '2024-2025'),
(153, 'Admin logged out', 'Logout', '2025-05-13 23:29:51', 1, '2024-2025'),
(162, '214 - hey g', 'Deleted voter', '2025-05-13 23:53:18', 1, '2024-2025'),
(225, 'tite inner - Vice-Governor', 'Deleted candidate', '2025-05-14 13:20:06', 1, '2024-2025'),
(226, 'Admin logged out', 'Logout', '2025-05-14 13:23:10', 1, '2024-2025'),
(227, '214412 - Danik Xander', 'Deleted voter', '2025-05-16 12:25:15', 1, '2025-2026'),
(228, 'Admin logged out', 'Logout', '2025-05-16 12:26:49', 1, '2025-2026'),
(229, 'jhon raki - 4th Year Representative', 'Added candidate', '2025-05-16 12:28:59', 1, '2024-2025'),
(230, 'hanzo ger - Vice-Governor', 'Added candidate', '2025-05-16 12:29:31', 1, '2024-2025'),
(231, 'da dsa - 1st Year Representative', 'Deleted candidate', '2025-05-16 12:43:38', 1, '2024-2025'),
(236, 'Admin logged out', 'Logout', '2025-05-17 15:44:18', 1, '2024-2025'),
(237, 'Admin logged out', 'Logout', '2025-05-17 15:49:57', 1, '2024-2025'),
(238, 'Admin logged out', 'Logout', '2025-05-17 16:03:23', 1, '2024-2025'),
(239, 'Admin logged out', 'Logout', '2025-05-17 16:05:08', 1, '2024-2025'),
(240, 'Admin logged out', 'Logout', '2025-05-17 16:05:31', 1, '2024-2025'),
(241, 'Admin logged out', 'Logout', '2025-05-17 19:35:42', 1, '2024-2025'),
(242, 'Admin logged out', 'Logout', '2025-05-18 17:39:27', 1, '2024-2025'),
(243, '2025001 - Juan Dela Cruz', 'Imported voter', '2025-05-18 18:27:47', 1, '2025-2026'),
(244, '214412 - Danik Xander', 'Imported voter', '2025-05-18 18:27:47', 1, '2025-2026'),
(245, '42155122 - seci duwar', 'Imported voter', '2025-05-18 18:27:47', 1, '2025-2026'),
(246, '606 - bayot a', 'Imported voter', '2025-05-18 18:27:47', 1, '2025-2026'),
(247, '75722 - at d', 'Imported voter', '2025-05-18 18:27:47', 1, '2025-2026'),
(248, '42424 - chris d', 'Imported voter', '2025-05-18 18:27:47', 1, '2025-2026'),
(249, 'Kyle  Hunter - Governor', 'Added candidate', '2025-05-18 23:14:52', 1, '2025-2026'),
(250, 'Clarice  Maxwell - Governor', 'Added candidate', '2025-05-18 23:15:17', 1, '2025-2026'),
(251, 'Lula  Love - 2nd Year Representative', 'Added candidate', '2025-05-18 23:30:38', 1, '2025-2026'),
(252, 'Chang  Benitez - Vice-Governor', 'Added candidate', '2025-05-18 23:30:59', 1, '2025-2026'),
(253, 'Amparo  Becker - Vice-Governor', 'Added candidate', '2025-05-18 23:31:25', 1, '2025-2026'),
(254, 'Beverly Stewart - 1st Year Representative', 'Added candidate', '2025-05-18 23:33:53', 1, '2025-2026'),
(255, 'Admin logged out', 'Logout', '2025-05-20 10:46:26', 1, '2025-2026'),
(256, 'Admin logged out', 'Logout', '2025-05-20 10:50:37', 1, '2025-2026'),
(257, 'Jordyn  Howe - Governor', 'Added candidate', '2025-05-20 11:06:05', 1, '2025-2026'),
(258, 'Madalyn  Meyers - Vice-Governor', 'Added candidate', '2025-05-20 11:06:23', 1, '2025-2026'),
(259, 'Hallie  Cuevas - Governor', 'Added candidate', '2025-05-20 11:06:41', 1, '2025-2026'),
(260, 'Malayah Joseph - 2nd Year Representative', 'Added candidate', '2025-05-20 11:07:00', 1, '2025-2026'),
(261, 'Adalee  Vincent - Governor', 'Added candidate', '2025-05-20 11:07:15', 1, '2025-2026'),
(262, 'Madalynn  Cummings - Governor', 'Added candidate', '2025-05-20 11:07:31', 1, '2025-2026'),
(263, 'Rylan  Allen - 3rd Year Representative', 'Added candidate', '2025-05-20 11:07:53', 1, '2025-2026'),
(264, 'Saoirse  Terry - 4th Year Representative', 'Added candidate', '2025-05-20 11:08:18', 1, '2025-2026'),
(265, 'Updated own profile', 'Update Profile', '2025-05-21 10:38:29', 1, '2025-2026'),
(266, 'Updated user: admin1', 'Edit User', '2025-05-21 10:38:41', 1, '2025-2026'),
(267, 'Deleted user: admin1', 'Delete User', '2025-05-21 10:38:50', 1, '2025-2026'),
(268, 'Admin logged out', 'Logout', '2025-05-21 11:09:07', 1, '2025-2026'),
(269, '606 - bayot a', 'Imported voter', '2025-05-22 14:44:40', 1, '2024-2025'),
(270, '214 - hey g', 'Imported voter', '2025-05-22 14:44:40', 1, '2024-2025');

-- --------------------------------------------------------

--
-- Table structure for table `settings`
--

CREATE TABLE `settings` (
  `id` int(11) NOT NULL,
  `academic_year` varchar(20) NOT NULL,
  `is_current` tinyint(1) DEFAULT 0,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `settings`
--

INSERT INTO `settings` (`id`, `academic_year`, `is_current`, `created_at`) VALUES
(1, '2024-2025', 1, '2025-05-06 14:23:11'),
(2, '2025-2026', 0, '2025-05-08 11:31:14'),
(4, '2026-2027', 0, '2025-05-10 09:42:46'),
(6, '2027-2028', 0, '2025-05-22 07:15:59');

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `User_id` int(11) NOT NULL,
  `FirstName` varchar(100) NOT NULL,
  `LastName` varchar(100) NOT NULL,
  `UserName` varchar(100) NOT NULL,
  `Password` varchar(100) NOT NULL,
  `User_Type` varchar(50) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`User_id`, `FirstName`, `LastName`, `UserName`, `Password`, `User_Type`) VALUES
(1, 'Admin', 'User', 'admin', '123', 'Admin');

-- --------------------------------------------------------

--
-- Table structure for table `voters`
--

CREATE TABLE `voters` (
  `StudentID` varchar(255) NOT NULL,
  `FirstName` varchar(150) NOT NULL,
  `LastName` varchar(150) NOT NULL,
  `MiddleName` varchar(100) NOT NULL,
  `Username` varchar(100) NOT NULL,
  `Password` varchar(100) NOT NULL,
  `Year` varchar(100) NOT NULL,
  `Status` varchar(20) NOT NULL,
  `enrollment` enum('enrolled','unenrolled') NOT NULL DEFAULT 'enrolled',
  `academic_year` varchar(20) NOT NULL DEFAULT '2024-2025'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `voters`
--

INSERT INTO `voters` (`StudentID`, `FirstName`, `LastName`, `MiddleName`, `Username`, `Password`, `Year`, `Status`, `enrollment`, `academic_year`) VALUES
('12422', 'who', 'g', 'g', '12422', 'who', '2nd Year', 'UnVoted', 'enrolled', '2024-2025'),
('12422', 'who', 'g', 'g', '12422', 'who', '2nd Year', 'UnVoted', 'enrolled', '2025-2026'),
('12422', 'who', 'g', 'g', '12422', 'who', '2nd Year', 'UnVoted', 'enrolled', '2026-2027'),
('2025001', 'Juan', 'Dela Cruz', 'Santos', '2025001', 'Juan', '4th Year', 'UnVoted', 'enrolled', '2024-2025'),
('2025001', 'Juan', 'Dela Cruz', 'Santos', '2025001', 'Juan', '4th Year', 'UnVoted', 'enrolled', '2025-2026'),
('2025001', 'Juan', 'Dela Cruz', 'Santos', '2025001', 'Juan', '4th Year', 'UnVoted', 'enrolled', '2026-2027'),
('214', 'hey', 'g', 'g', '214', 'hey', '2nd Year', 'UnVoted', 'enrolled', '2024-2025'),
('214', 'hey', 'g', 'g', '214', 'hey', '2nd Year', 'UnVoted', 'enrolled', '2025-2026'),
('214', 'hey', 'g', 'g', '214', 'hey', '2nd Year', 'UnVoted', 'enrolled', '2026-2027'),
('214412', 'Danik', 'Xander', 'G', '214412', 'Danik', '1st Year', 'Voted', 'enrolled', '2024-2025'),
('214412', 'Danik', 'Xander', 'G', '214412', 'Danik', '1st Year', 'UnVoted', 'enrolled', '2025-2026'),
('214412', 'Danik', 'Xander', 'G', '214412', 'Danik', '1st Year', 'UnVoted', 'enrolled', '2026-2027'),
('22012242', 'mario', 'hesuyam', 'david', '22012242', 'mario', '3rd Year', 'UnVoted', 'enrolled', '2024-2025'),
('22012242', 'mario', 'hesuyam', 'david', '22012242', 'mario', '3rd Year', 'UnVoted', 'enrolled', '2025-2026'),
('22012242', 'mario', 'hesuyam', 'david', '22012242', 'mario', '3rd Year', 'UnVoted', 'enrolled', '2026-2027'),
('42155122', 'seci', 'duwar', 'E', '42155122', 'seci', '2nd Year', 'UnVoted', 'enrolled', '2024-2025'),
('42155122', 'seci', 'duwar', 'E', '42155122', 'seci', '2nd Year', 'UnVoted', 'enrolled', '2025-2026'),
('42155122', 'seci', 'duwar', 'E', '42155122', 'seci', '2nd Year', 'UnVoted', 'enrolled', '2026-2027'),
('42424', 'chris', 'd', 'david', '42424', 'chris', '4th Year', 'UnVoted', 'enrolled', '2024-2025'),
('42424', 'chris', 'd', 'david', '42424', 'chris', '4th Year', 'UnVoted', 'enrolled', '2025-2026'),
('42424', 'chris', 'd', 'david', '42424', 'chris', '4th Year', 'UnVoted', 'enrolled', '2026-2027'),
('4244', 'haha', 'g', 'g', '4244', 'haha', '1st Year', 'Voted', 'enrolled', '2024-2025'),
('4244', 'haha', 'g', 'g', '4244', 'haha', '1st Year', 'Voted', 'enrolled', '2025-2026'),
('4244', 'haha', 'g', 'g', '4244', 'haha', '1st Year', 'UnVoted', 'enrolled', '2026-2027'),
('606', 'bayot', 'a', 'b', '606', 'bayot', '1st Year', 'UnVoted', 'enrolled', '2024-2025'),
('606', 'bayot', 'a', 'b', '606', 'bayot', '1st Year', 'Voted', 'enrolled', '2025-2026'),
('606', 'bayot', 'a', 'b', '606', 'bayot', '1st Year', 'Voted', 'enrolled', '2026-2027'),
('75722', 'at', 'd', 'david', '75722', 'at', '3rd Year', 'UnVoted', 'enrolled', '2024-2025'),
('75722', 'at', 'd', 'david', '75722', 'at', '3rd Year', 'UnVoted', 'enrolled', '2025-2026'),
('75722', 'at', 'd', 'david', '75722', 'at', '3rd Year', 'UnVoted', 'enrolled', '2026-2027');

-- --------------------------------------------------------

--
-- Table structure for table `voters_history`
--

CREATE TABLE `voters_history` (
  `id` int(11) NOT NULL,
  `voter_id` int(11) DEFAULT NULL,
  `academic_year` varchar(20) DEFAULT NULL,
  `data` text DEFAULT NULL,
  `deleted_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `votes`
--

CREATE TABLE `votes` (
  `ID` int(11) NOT NULL,
  `CandidateID` int(11) NOT NULL,
  `voter_id` varchar(255) NOT NULL,
  `academic_year` varchar(20) NOT NULL,
  `date` datetime NOT NULL,
  `timestamp` datetime DEFAULT current_timestamp(),
  `date_voted` datetime DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `votes`
--

INSERT INTO `votes` (`ID`, `CandidateID`, `voter_id`, `academic_year`, `date`, `timestamp`, `date_voted`) VALUES
(5, 1, '214412', '2024-2025', '2025-05-09 15:29:43', '2025-05-13 22:03:14', '2025-05-13 22:07:43'),
(6, 3, '214412', '2024-2025', '2025-05-09 15:29:43', '2025-05-13 22:03:14', '2025-05-13 22:07:43'),
(12, 5, '606', '2026-2027', '2025-05-12 12:53:00', '2025-05-13 22:03:14', '2025-05-13 22:07:43'),
(17, 8, '4244', '2024-2025', '2025-05-12 14:08:36', '2025-05-13 22:03:14', '2025-05-13 22:07:43'),
(18, 10, '4244', '2024-2025', '2025-05-12 14:08:36', '2025-05-13 22:03:14', '2025-05-13 22:07:43'),
(19, 6, '4244', '2024-2025', '2025-05-12 14:08:36', '2025-05-13 22:03:14', '2025-05-13 22:07:43'),
(20, 8, '42155122', '2024-2025', '2025-05-12 14:36:38', '2025-05-13 22:03:14', '2025-05-13 22:07:43'),
(21, 10, '42155122', '2024-2025', '2025-05-12 14:36:38', '2025-05-13 22:03:14', '2025-05-13 22:07:43'),
(22, 2, '42155122', '2024-2025', '2025-05-12 14:36:38', '2025-05-13 22:03:14', '2025-05-13 22:07:43'),
(23, 1, '42424', '2024-2025', '2025-05-12 14:58:25', '2025-05-13 22:03:14', '2025-05-13 22:07:43'),
(24, 10, '42424', '2024-2025', '2025-05-12 14:58:25', '2025-05-13 22:03:14', '2025-05-13 22:07:43'),
(25, 11, '42424', '2024-2025', '2025-05-12 14:58:25', '2025-05-13 22:03:14', '2025-05-13 22:07:43'),
(26, 1, '22012242', '2024-2025', '2025-05-13 13:13:10', '2025-05-13 22:03:14', '2025-05-13 22:07:43'),
(27, 12, '22012242', '2024-2025', '2025-05-13 13:13:10', '2025-05-13 22:03:14', '2025-05-13 22:07:43'),
(28, 4, '22012242', '2024-2025', '2025-05-13 13:13:10', '2025-05-13 22:03:14', '2025-05-13 22:07:43'),
(29, 1, '75722', '2024-2025', '2025-05-13 16:04:15', '2025-05-13 22:04:15', '2025-05-13 22:07:43'),
(30, 12, '75722', '2024-2025', '2025-05-13 16:04:15', '2025-05-13 22:04:15', '2025-05-13 22:07:43'),
(31, 4, '75722', '2024-2025', '2025-05-13 16:04:15', '2025-05-13 22:04:15', '2025-05-13 22:07:43'),
(35, 1, '2025001', '2024-2025', '2025-05-16 06:37:20', '2025-05-16 12:37:20', '2025-05-16 12:37:20'),
(36, 14, '2025001', '2024-2025', '2025-05-16 06:37:20', '2025-05-16 12:37:20', '2025-05-16 12:37:20'),
(37, 11, '2025001', '2024-2025', '2025-05-16 06:37:20', '2025-05-16 12:37:20', '2025-05-16 12:37:20'),
(38, 16, '4244', '2025-2026', '2025-05-20 16:50:53', '2025-05-20 22:50:53', '2025-05-20 22:50:53'),
(39, 18, '4244', '2025-2026', '2025-05-20 16:50:53', '2025-05-20 22:50:53', '2025-05-20 22:50:53'),
(40, 20, '4244', '2025-2026', '2025-05-20 16:50:53', '2025-05-20 22:50:53', '2025-05-20 22:50:53'),
(41, 16, '12422', '2025-2026', '2025-05-20 17:11:22', '2025-05-20 23:11:22', '2025-05-20 23:11:22'),
(42, 19, '12422', '2025-2026', '2025-05-20 17:11:22', '2025-05-20 23:11:22', '2025-05-20 23:11:22'),
(43, 24, '12422', '2025-2026', '2025-05-20 17:11:22', '2025-05-20 23:11:22', '2025-05-20 23:11:22'),
(44, 26, '606', '2025-2026', '2025-05-21 13:21:03', '2025-05-21 19:21:03', '2025-05-21 19:21:03'),
(45, 18, '606', '2025-2026', '2025-05-21 13:21:03', '2025-05-21 19:21:03', '2025-05-21 19:21:03'),
(46, 20, '606', '2025-2026', '2025-05-21 13:21:03', '2025-05-21 19:21:03', '2025-05-21 19:21:03'),
(47, 16, '75722', '2025-2026', '2025-05-21 13:41:08', '2025-05-21 19:41:08', '2025-05-21 19:41:08'),
(48, 18, '75722', '2025-2026', '2025-05-21 13:41:08', '2025-05-21 19:41:08', '2025-05-21 19:41:08'),
(49, 27, '75722', '2025-2026', '2025-05-21 13:41:08', '2025-05-21 19:41:08', '2025-05-21 19:41:08'),
(50, 9, '214', '2024-2025', '2025-05-22 09:20:08', '2025-05-22 15:20:08', '2025-05-22 15:20:08'),
(51, 14, '214', '2024-2025', '2025-05-22 09:20:08', '2025-05-22 15:20:08', '2025-05-22 15:20:08'),
(52, 2, '214', '2024-2025', '2025-05-22 09:20:08', '2025-05-22 15:20:08', '2025-05-22 15:20:08');

-- --------------------------------------------------------

--
-- Table structure for table `votes_history`
--

CREATE TABLE `votes_history` (
  `id` int(11) NOT NULL,
  `vote_id` int(11) DEFAULT NULL,
  `voter_id` int(11) DEFAULT NULL,
  `candidate_id` int(11) DEFAULT NULL,
  `academic_year` varchar(20) NOT NULL,
  `archived_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Indexes for dumped tables
--

--
-- Indexes for table `candidate`
--
ALTER TABLE `candidate`
  ADD PRIMARY KEY (`CandidateID`),
  ADD KEY `idx_academic_year` (`academic_year`);

--
-- Indexes for table `candidates_history`
--
ALTER TABLE `candidates_history`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `history`
--
ALTER TABLE `history`
  ADD PRIMARY KEY (`history_id`),
  ADD KEY `user_id` (`user_id`),
  ADD KEY `idx_academic_year_history` (`academic_year`);

--
-- Indexes for table `settings`
--
ALTER TABLE `settings`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `unique_academic_year` (`academic_year`);

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`User_id`),
  ADD UNIQUE KEY `idx_username` (`UserName`);

--
-- Indexes for table `voters`
--
ALTER TABLE `voters`
  ADD PRIMARY KEY (`StudentID`,`academic_year`),
  ADD KEY `idx_academic_year_voters` (`academic_year`);

--
-- Indexes for table `voters_history`
--
ALTER TABLE `voters_history`
  ADD PRIMARY KEY (`id`),
  ADD KEY `academic_year` (`academic_year`);

--
-- Indexes for table `votes`
--
ALTER TABLE `votes`
  ADD PRIMARY KEY (`ID`),
  ADD KEY `CandidateID` (`CandidateID`),
  ADD KEY `idx_academic_year_votes` (`academic_year`),
  ADD KEY `fk_votes_voter` (`voter_id`,`academic_year`);

--
-- Indexes for table `votes_history`
--
ALTER TABLE `votes_history`
  ADD PRIMARY KEY (`id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `candidate`
--
ALTER TABLE `candidate`
  MODIFY `CandidateID` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=29;

--
-- AUTO_INCREMENT for table `candidates_history`
--
ALTER TABLE `candidates_history`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `history`
--
ALTER TABLE `history`
  MODIFY `history_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=271;

--
-- AUTO_INCREMENT for table `settings`
--
ALTER TABLE `settings`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `User_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `voters_history`
--
ALTER TABLE `voters_history`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `votes`
--
ALTER TABLE `votes`
  MODIFY `ID` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=53;

--
-- AUTO_INCREMENT for table `votes_history`
--
ALTER TABLE `votes_history`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `candidate`
--
ALTER TABLE `candidate`
  ADD CONSTRAINT `candidate_ibfk_1` FOREIGN KEY (`academic_year`) REFERENCES `settings` (`academic_year`) ON UPDATE CASCADE;

--
-- Constraints for table `history`
--
ALTER TABLE `history`
  ADD CONSTRAINT `history_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`User_id`) ON DELETE CASCADE,
  ADD CONSTRAINT `history_ibfk_2` FOREIGN KEY (`academic_year`) REFERENCES `settings` (`academic_year`) ON UPDATE CASCADE;

--
-- Constraints for table `voters`
--
ALTER TABLE `voters`
  ADD CONSTRAINT `voters_ibfk_1` FOREIGN KEY (`academic_year`) REFERENCES `settings` (`academic_year`) ON UPDATE CASCADE;

--
-- Constraints for table `voters_history`
--
ALTER TABLE `voters_history`
  ADD CONSTRAINT `voters_history_ibfk_1` FOREIGN KEY (`academic_year`) REFERENCES `settings` (`academic_year`);

--
-- Constraints for table `votes`
--
ALTER TABLE `votes`
  ADD CONSTRAINT `fk_votes_voter` FOREIGN KEY (`voter_id`,`academic_year`) REFERENCES `voters` (`StudentID`, `academic_year`) ON DELETE CASCADE,
  ADD CONSTRAINT `votes_ibfk_3` FOREIGN KEY (`academic_year`) REFERENCES `settings` (`academic_year`) ON UPDATE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
