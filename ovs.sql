-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: May 29, 2026 at 05:12 PM
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
-- Database: `ovs`
--

-- --------------------------------------------------------

--
-- Table structure for table `candidate`
--

CREATE TABLE `candidate` (
  `CandidateID` int(11) NOT NULL,
  `StudentID` varchar(255) NOT NULL,
  `FirstName` varchar(200) NOT NULL,
  `LastName` varchar(200) NOT NULL,
  `MiddleName` varchar(100) NOT NULL,
  `Position` varchar(200) NOT NULL,
  `Party` varchar(100) NOT NULL,
  `Year` varchar(100) NOT NULL,
  `Photo` varchar(200) NOT NULL,
  `Platform` text DEFAULT NULL,
  `academic_year` varchar(20) NOT NULL DEFAULT '2024-2025'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `candidate`
--

INSERT INTO `candidate` (`CandidateID`, `StudentID`, `FirstName`, `LastName`, `MiddleName`, `Position`, `Party`, `Year`, `Photo`, `Platform`, `academic_year`) VALUES
(104, '2024004', 'Jhony', 'Naui', 'G', 'Governor', 'Tindig', '4th Year', 'images/1764831863_1748001902_pexels-italo-melo-881954-2379005.jpg', 'Free wifi and softdrinks', '2029-2030'),
(105, '2024001', 'Dennis', 'Macababbad', 'M', 'Governor', 'Kasapi', '1st Year', 'images/1764831915_1748051857_pexels-david-garrison-1128051-2128807.jpg', 'Free Coffee', '2029-2030'),
(106, '2024008', 'Yurie', 'Delos Santos', 'X', 'Vice-Governor', 'Kasapi', '1st Year', 'images/1764831948_1748058369_gettyimages-156690487-612x612.jpg', 'Free print everyday', '2029-2030'),
(107, '2024013', 'Josh', 'Asparela', 'A', 'Vice-Governor', 'Tindig', '4th Year', 'images/1764832000_1748058204_gettyimages-1418548223-612x612.jpg', 'Free bondpaper', '2029-2030'),
(108, '2024006', 'Samuel', 'Manalili', 'D', '1st Year Representative', 'Tindig', '3rd Year', 'images/1764832191_1748009910_gettyimages-1438185814-612x612.jpg', 'Free snacks', '2029-2030'),
(109, '2024012', 'Romel', 'Tangonan', 'B', '2nd Year Representative', 'Kasapi', '3rd Year', 'images/1764832252_1748043251_gettyimages-1540766473-612x612.jpg', 'Free wifi\\r\\n', '2029-2030'),
(110, '2024010', 'Roi', 'Garma', 'S', '4th Year Representative', 'Tindig', '3rd Year', 'images/1764864148_1748063961_gettyimages-2172873473-612x612.jpg', 'Better community', '2029-2030');

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
(926, 'StudentID: 102361 — mae joy balignasay added as a candidate', 'Added candidate', '2025-11-28 06:34:14', 1, '2025-2026'),
(927, 'StudentID: 42155122 — seci duwar added as a candidate', 'Added candidate', '2025-11-28 06:38:04', 1, '2025-2026'),
(928, 'StudentID: 214 — hey g added as a candidate', 'Added candidate', '2025-11-28 06:47:16', 1, '2025-2026'),
(929, '2024001 - John Doe', 'Imported voter', '2025-11-28 07:36:55', 1, '2025-2026'),
(930, '2024002 - Jane Smith', 'Imported voter', '2025-11-28 07:36:55', 1, '2025-2026'),
(931, '2024003 - Robert Johnson', 'Imported voter', '2025-11-28 07:36:55', 1, '2025-2026'),
(932, '2024003 - enrolled', 'Updated enrollment', '2025-11-28 07:37:11', 1, '2025-2026'),
(933, '2024002 - enrolled', 'Updated enrollment', '2025-11-28 07:37:11', 1, '2025-2026'),
(934, '2024001 - enrolled', 'Updated enrollment', '2025-11-28 07:37:12', 1, '2025-2026'),
(935, '2024001', 'Deleted voter', '2025-11-28 07:39:15', 1, '2025-2026'),
(936, '2024003', 'Deleted voter', '2025-11-28 07:39:19', 1, '2025-2026'),
(937, '2024002', 'Deleted voter', '2025-11-28 07:39:23', 1, '2025-2026'),
(938, '2024001 - John Doe', 'Imported voter', '2025-11-28 07:39:34', 1, '2025-2026'),
(939, '2024002 - Jane Smith', 'Imported voter', '2025-11-28 07:39:34', 1, '2025-2026'),
(940, '2024003 - Robert Johnson', 'Imported voter', '2025-11-28 07:39:34', 1, '2025-2026'),
(941, 'StudentID: 2024003 — Robert Johnson added as a candidate', 'Added candidate', '2025-11-28 07:40:23', 1, '2025-2026'),
(942, 'justin handsome - Governor', 'Deleted candidate', '2025-11-28 07:43:51', 1, '2025-2026'),
(943, 'StudentID: 75722 — at d added as a candidate', 'Added candidate', '2025-11-28 10:05:13', 1, '2025-2026'),
(944, '2024003 - enrolled', 'Updated enrollment', '2025-11-28 10:05:43', 1, '2025-2026'),
(945, 'Deleted user: 123', 'Delete User', '2025-11-28 10:12:07', 1, '2025-2026'),
(946, 'Anthony Davis - Governor', 'Deleted candidate', '2025-11-28 10:33:40', 1, '2025-2026'),
(947, 'Academic year: 2050-2051', 'Added Academic Year', '2025-11-28 11:15:46', 1, '2024-2025'),
(948, 'Academic year: 2043-2044', 'Added Academic Year', '2025-11-28 11:25:40', 1, '2024-2025'),
(949, 'Edited candidate: at d (Vice-Governor)', 'Edit Candidate', '2025-11-28 11:29:43', 1, '2024-2025'),
(950, 'Edited candidate: at d (Governor)', 'Edit Candidate', '2025-11-28 11:29:55', 1, '2024-2025'),
(951, 'Edited candidate: at d (1st Year Representative)', 'Edit Candidate', '2025-11-28 11:31:34', 1, '2024-2025'),
(952, 'Edited candidate: at d (3rd Year Representative)', 'Edit Candidate', '2025-11-28 11:33:20', 1, '2024-2025'),
(953, 'Edited candidate: mario hesuyam (Vice-Governor)', 'Edit Candidate', '2025-11-28 11:36:04', 1, '2024-2025'),
(954, 'Edited candidate: at d (Vice-Governor)', 'Edit Candidate', '2025-11-28 11:36:34', 1, '2024-2025'),
(955, '2024001 - John Doe', 'Imported voter', '2025-11-28 11:37:42', 1, '2040-2041'),
(956, '2024002 - Jane Smith', 'Imported voter', '2025-11-28 11:37:42', 1, '2040-2041'),
(957, '2024003 - Robert Johnson', 'Imported voter', '2025-11-28 11:37:42', 1, '2040-2041'),
(958, 'Edited candidate: at d (4th Year Representative) for 2025-2026', 'Edit Candidate', '2025-11-28 11:38:37', 1, '2024-2025'),
(959, 'Edited candidate: at d (3rd Year Representative) for 2025-2026', 'Edit Candidate', '2025-11-28 11:41:54', 1, '2025-2026'),
(960, 'Danik Xander - Governor', 'Deleted candidate', '2025-11-28 11:43:37', 1, '2025-2026'),
(961, 'Generated password for StudentID: selected', 'Generated password', '2025-11-28 11:47:06', 1, '2025-2026'),
(962, 'Generated password for StudentID: selected', 'Generated password', '2025-11-28 11:47:45', 1, '2025-2026'),
(963, 'Generated password for StudentID: 102361', 'Generated password', '2025-11-28 11:48:56', 1, '2025-2026'),
(964, 'Generated password for StudentID: 12422', 'Generated password', '2025-11-28 11:48:56', 1, '2025-2026'),
(965, 'Generated password for StudentID: 2024001', 'Generated password', '2025-11-28 11:48:56', 1, '2025-2026'),
(966, 'Generated password for StudentID: 2024002', 'Generated password', '2025-11-28 11:48:56', 1, '2025-2026'),
(967, 'Generated password for StudentID: 2024003', 'Generated password', '2025-11-28 11:48:56', 1, '2025-2026'),
(968, 'Generated password for StudentID: 2025001', 'Generated password', '2025-11-28 11:48:56', 1, '2025-2026'),
(969, 'Generated password for StudentID: 214', 'Generated password', '2025-11-28 11:48:56', 1, '2025-2026'),
(970, 'Generated password for StudentID: 214412', 'Generated password', '2025-11-28 11:48:56', 1, '2025-2026'),
(971, 'Generated password for StudentID: 21555251', 'Generated password', '2025-11-28 11:48:56', 1, '2025-2026'),
(972, 'Generated password for StudentID: 22012', 'Generated password', '2025-11-28 11:48:56', 1, '2025-2026'),
(973, 'Generated password for StudentID: 22012242', 'Generated password', '2025-11-28 11:48:56', 1, '2025-2026'),
(974, 'Generated password for StudentID: 42155122', 'Generated password', '2025-11-28 11:48:56', 1, '2025-2026'),
(975, 'Generated password for StudentID: 4244', 'Generated password', '2025-11-28 11:48:56', 1, '2025-2026'),
(976, 'Generated password for StudentID: 75722', 'Generated password', '2025-11-28 11:48:56', 1, '2025-2026'),
(977, 'Generated password for StudentID: 102361', 'Generated password', '2025-11-28 11:51:42', 1, '2025-2026'),
(978, 'Generated password for StudentID: 12422', 'Generated password', '2025-11-28 11:51:42', 1, '2025-2026'),
(979, 'Generated password for StudentID: 2024001', 'Generated password', '2025-11-28 11:51:42', 1, '2025-2026'),
(980, 'Generated password for StudentID: 2024002', 'Generated password', '2025-11-28 11:51:42', 1, '2025-2026'),
(981, 'Generated password for StudentID: 2024003', 'Generated password', '2025-11-28 11:51:42', 1, '2025-2026'),
(982, 'Generated password for StudentID: 2025001', 'Generated password', '2025-11-28 11:51:42', 1, '2025-2026'),
(983, 'Generated password for StudentID: 214', 'Generated password', '2025-11-28 11:51:42', 1, '2025-2026'),
(984, 'Generated password for StudentID: 214412', 'Generated password', '2025-11-28 11:51:42', 1, '2025-2026'),
(985, 'Generated password for StudentID: 21555251', 'Generated password', '2025-11-28 11:51:42', 1, '2025-2026'),
(986, 'Generated password for StudentID: 22012', 'Generated password', '2025-11-28 11:51:42', 1, '2025-2026'),
(987, 'Generated password for StudentID: 22012242', 'Generated password', '2025-11-28 11:51:42', 1, '2025-2026'),
(988, 'Generated password for StudentID: 42155122', 'Generated password', '2025-11-28 11:51:42', 1, '2025-2026'),
(989, 'Generated password for StudentID: 4244', 'Generated password', '2025-11-28 11:51:42', 1, '2025-2026'),
(990, 'Generated password for StudentID: 75722', 'Generated password', '2025-11-28 11:51:42', 1, '2025-2026'),
(991, 'Generated password for StudentID: 102361', 'Generated password', '2025-11-28 12:00:18', 1, '2025-2026'),
(992, 'Generated password for StudentID: 12422', 'Generated password', '2025-11-28 12:00:18', 1, '2025-2026'),
(993, 'Generated password for StudentID: 2024001', 'Generated password', '2025-11-28 12:00:18', 1, '2025-2026'),
(994, 'Generated password for StudentID: 2024002', 'Generated password', '2025-11-28 12:00:18', 1, '2025-2026'),
(995, 'Generated password for StudentID: 2024003', 'Generated password', '2025-11-28 12:00:18', 1, '2025-2026'),
(996, 'Generated password for StudentID: 2025001', 'Generated password', '2025-11-28 12:00:18', 1, '2025-2026'),
(997, 'Generated password for StudentID: 214', 'Generated password', '2025-11-28 12:00:18', 1, '2025-2026'),
(998, 'Generated password for StudentID: 214412', 'Generated password', '2025-11-28 12:00:18', 1, '2025-2026'),
(999, 'Generated password for StudentID: 21555251', 'Generated password', '2025-11-28 12:00:18', 1, '2025-2026'),
(1000, 'Generated password for StudentID: 22012', 'Generated password', '2025-11-28 12:00:18', 1, '2025-2026'),
(1001, 'Generated password for StudentID: 22012242', 'Generated password', '2025-11-28 12:00:18', 1, '2025-2026'),
(1002, 'Generated password for StudentID: 42155122', 'Generated password', '2025-11-28 12:00:18', 1, '2025-2026'),
(1003, 'Generated password for StudentID: 4244', 'Generated password', '2025-11-28 12:00:18', 1, '2025-2026'),
(1004, 'Generated password for StudentID: 75722', 'Generated password', '2025-11-28 12:00:18', 1, '2025-2026'),
(1005, 'Edited candidate: at d (4th Year Representative) for 2025-2026', 'Edit Candidate', '2025-11-28 12:01:47', 1, '2025-2026'),
(1006, '2024001 - unenrolled', 'Updated enrollment', '2025-11-28 21:58:51', 1, '2025-2026'),
(1007, 'Admin logged out', 'Logout', '2025-11-28 22:03:09', 1, '2025-2026'),
(1008, 'Generated password for StudentID: 102361', 'Generated password', '2025-11-29 09:04:24', 1, '2025-2026'),
(1009, 'Generated password for StudentID: 102361', 'Generated password', '2025-11-29 09:06:18', 1, '2025-2026'),
(1010, 'Generated password for StudentID: 21555251', 'Generated password', '2025-11-29 09:06:44', 1, '2025-2026'),
(1011, 'Generated password for StudentID: 102361', 'Generated password', '2025-11-29 09:09:07', 1, '2025-2026'),
(1012, 'StudentID: 12422 — who g added as a candidate', 'Added candidate', '2025-11-29 09:10:08', 1, '2025-2026'),
(1013, 'StudentID: 22012 — davey langit added as a candidate', 'Added candidate', '2025-11-29 09:12:38', 1, '2025-2026'),
(1014, 'Generated password for StudentID: 102361', 'Generated password', '2025-11-29 09:13:23', 1, '2025-2026'),
(1015, 'Generated password for StudentID: 12422', 'Generated password', '2025-11-29 09:13:23', 1, '2025-2026'),
(1016, 'Generated password for StudentID: 2024001', 'Generated password', '2025-11-29 09:13:23', 1, '2025-2026'),
(1017, 'Generated password for StudentID: 2024002', 'Generated password', '2025-11-29 09:13:23', 1, '2025-2026'),
(1018, 'Generated password for StudentID: 2024003', 'Generated password', '2025-11-29 09:13:23', 1, '2025-2026'),
(1019, 'Generated password for StudentID: 2025001', 'Generated password', '2025-11-29 09:13:23', 1, '2025-2026'),
(1020, 'Generated password for StudentID: 214', 'Generated password', '2025-11-29 09:13:23', 1, '2025-2026'),
(1021, 'Generated password for StudentID: 214412', 'Generated password', '2025-11-29 09:13:23', 1, '2025-2026'),
(1022, 'Generated password for StudentID: 21555251', 'Generated password', '2025-11-29 09:13:23', 1, '2025-2026'),
(1023, 'Generated password for StudentID: 22012', 'Generated password', '2025-11-29 09:13:23', 1, '2025-2026'),
(1024, 'Generated password for StudentID: 22012242', 'Generated password', '2025-11-29 09:13:23', 1, '2025-2026'),
(1025, 'Generated password for StudentID: 42155122', 'Generated password', '2025-11-29 09:13:23', 1, '2025-2026'),
(1026, 'Generated password for StudentID: 4244', 'Generated password', '2025-11-29 09:13:23', 1, '2025-2026'),
(1027, 'Generated password for StudentID: 102361', 'Generated password', '2025-11-30 09:58:14', 1, '2025-2026'),
(1028, 'Generated password for StudentID: 102361', 'Generated password', '2025-11-30 09:58:33', 1, '2025-2026'),
(1029, 'Generated password for StudentID: 12422', 'Generated password', '2025-11-30 09:58:33', 1, '2025-2026'),
(1030, 'Generated password for StudentID: 2024001', 'Generated password', '2025-11-30 09:58:33', 1, '2025-2026'),
(1031, 'Generated password for StudentID: 2024002', 'Generated password', '2025-11-30 09:58:33', 1, '2025-2026'),
(1032, 'Generated password for StudentID: 2024003', 'Generated password', '2025-11-30 09:58:33', 1, '2025-2026'),
(1033, 'Generated password for StudentID: 2025001', 'Generated password', '2025-11-30 09:58:33', 1, '2025-2026'),
(1034, 'Generated password for StudentID: 214', 'Generated password', '2025-11-30 09:58:33', 1, '2025-2026'),
(1035, 'Generated password for StudentID: 214412', 'Generated password', '2025-11-30 09:58:33', 1, '2025-2026'),
(1036, 'Generated password for StudentID: 21555251', 'Generated password', '2025-11-30 09:58:33', 1, '2025-2026'),
(1037, 'Generated password for StudentID: 22012', 'Generated password', '2025-11-30 09:58:33', 1, '2025-2026'),
(1038, 'Generated password for StudentID: 22012242', 'Generated password', '2025-11-30 09:58:33', 1, '2025-2026'),
(1039, 'Generated password for StudentID: 42155122', 'Generated password', '2025-11-30 09:58:33', 1, '2025-2026'),
(1040, 'Generated password for StudentID: 4244', 'Generated password', '2025-11-30 09:58:33', 1, '2025-2026'),
(1041, 'Generated password for StudentID: 102361', 'Generated password', '2025-11-30 09:58:54', 1, '2025-2026'),
(1042, '9412924 - bayot garma', 'Added voter', '2025-11-30 10:18:43', 1, '2025-2026'),
(1043, '9412924 - enrolled', 'Updated enrollment', '2025-11-30 10:19:04', 1, '2025-2026'),
(1044, '29487421 - bur bar', 'Added voter', '2025-11-30 10:33:59', 1, '2025-2026'),
(1045, 'Admin logged out', 'Logout', '2025-11-30 10:52:33', 1, '2025-2026'),
(1046, 'Danik Xander - Governor', 'Deleted candidate', '2025-11-30 11:32:54', 1, '2025-2026'),
(1047, 'Generated password for StudentID: 21555251', 'Generated password', '2025-12-02 22:04:25', 1, '2025-2026'),
(1048, 'Admin logged out', 'Logout', '2025-12-02 22:56:15', 1, '2025-2026'),
(1049, 'Generated password for StudentID: 102361', 'Generated password', '2025-12-03 00:59:53', 1, '2025-2026'),
(1050, 'Generated password for StudentID: 12422', 'Generated password', '2025-12-03 00:59:53', 1, '2025-2026'),
(1051, 'Generated password for StudentID: 2024001', 'Generated password', '2025-12-03 00:59:53', 1, '2025-2026'),
(1052, 'Generated password for StudentID: 2024002', 'Generated password', '2025-12-03 00:59:53', 1, '2025-2026'),
(1053, 'Generated password for StudentID: 2024003', 'Generated password', '2025-12-03 00:59:53', 1, '2025-2026'),
(1054, 'Generated password for StudentID: 2025001', 'Generated password', '2025-12-03 00:59:53', 1, '2025-2026'),
(1055, 'Generated password for StudentID: 214', 'Generated password', '2025-12-03 00:59:53', 1, '2025-2026'),
(1056, 'Generated password for StudentID: 214412', 'Generated password', '2025-12-03 00:59:53', 1, '2025-2026'),
(1057, 'Generated password for StudentID: 21555251', 'Generated password', '2025-12-03 00:59:53', 1, '2025-2026'),
(1058, 'Generated password for StudentID: 22012', 'Generated password', '2025-12-03 00:59:53', 1, '2025-2026'),
(1059, 'Generated password for StudentID: 22012242', 'Generated password', '2025-12-03 00:59:53', 1, '2025-2026'),
(1060, 'Generated password for StudentID: 29487421', 'Generated password', '2025-12-03 00:59:53', 1, '2025-2026'),
(1061, 'Generated password for StudentID: 42155122', 'Generated password', '2025-12-03 00:59:54', 1, '2025-2026'),
(1062, 'Generated password for StudentID: 4244', 'Generated password', '2025-12-03 00:59:54', 1, '2025-2026'),
(1063, 'Generated password for StudentID: 9412924', 'Generated password', '2025-12-03 00:59:54', 1, '2025-2026'),
(1064, '3434 - rr ss', 'Added voter', '2025-12-03 01:01:09', 1, '2025-2026'),
(1065, 'StudentID: 3434 — rr ss added as a candidate', 'Added candidate', '2025-12-03 01:03:35', 1, '2025-2026'),
(1066, 'Admin logged out', 'Logout', '2025-12-03 22:37:05', 1, '2025-2026'),
(1067, 'Admin logged out', 'Logout', '2025-12-03 22:37:16', 1, '2025-2026'),
(1068, '2024001 - enrolled', 'Updated enrollment', '2025-12-03 22:38:08', 1, '2025-2026'),
(1069, '2024001 - John Doe', 'Imported voter', '2025-12-03 22:58:36', 1, '2027-2028'),
(1070, '2024002 - Jane Smith', 'Imported voter', '2025-12-03 22:58:36', 1, '2027-2028'),
(1071, '2024003 - Robert Johnson', 'Imported voter', '2025-12-03 22:58:36', 1, '2027-2028'),
(1072, '2024001 - Dennis Macababbad', 'Imported voter', '2025-12-03 23:00:51', 1, '2029-2030'),
(1073, '2024002 - Jane Smith', 'Imported voter', '2025-12-03 23:00:51', 1, '2029-2030'),
(1074, '2024003 - Robert Johnson', 'Imported voter', '2025-12-03 23:00:51', 1, '2029-2030'),
(1075, '2024004 - Jhony Naui', 'Imported voter', '2025-12-03 23:00:51', 1, '2029-2030'),
(1076, '2024005 - Elymar Ilac', 'Imported voter', '2025-12-03 23:00:51', 1, '2029-2030'),
(1077, '2024006 - Samuel Manalili', 'Imported voter', '2025-12-03 23:00:51', 1, '2029-2030'),
(1078, '2024008 - Yurie Delos Santos', 'Imported voter', '2025-12-03 23:00:51', 1, '2029-2030'),
(1079, '2024009 - Lupo Luna', 'Imported voter', '2025-12-03 23:00:51', 1, '2029-2030'),
(1080, '2024010 - Roi Garma', 'Imported voter', '2025-12-03 23:00:51', 1, '2029-2030'),
(1081, '2024011 - Edzel Catuiran', 'Imported voter', '2025-12-03 23:00:51', 1, '2029-2030'),
(1082, '2024012 - Romel Tangonan', 'Imported voter', '2025-12-03 23:00:51', 1, '2029-2030'),
(1083, '2024013 - Josh Asparela', 'Imported voter', '2025-12-03 23:00:51', 1, '2029-2030'),
(1084, '2024014 - Justin Tulio', 'Imported voter', '2025-12-03 23:00:51', 1, '2029-2030'),
(1085, '2024001 - Dennis Macababbad', 'Imported voter', '2025-12-03 23:01:45', 1, '2036-2037'),
(1086, '2024002 - Jane Smith', 'Imported voter', '2025-12-03 23:01:45', 1, '2036-2037'),
(1087, '2024003 - Robert Johnson', 'Imported voter', '2025-12-03 23:01:45', 1, '2036-2037'),
(1088, '2024004 - Jhony Naui', 'Imported voter', '2025-12-03 23:01:45', 1, '2036-2037'),
(1089, '2024005 - Elymar Ilac', 'Imported voter', '2025-12-03 23:01:45', 1, '2036-2037'),
(1090, '2024006 - Samuel Manalili', 'Imported voter', '2025-12-03 23:01:45', 1, '2036-2037'),
(1091, '2024008 - Yurie Delos Santos', 'Imported voter', '2025-12-03 23:01:45', 1, '2036-2037'),
(1092, '2024009 - Lupo Luna', 'Imported voter', '2025-12-03 23:01:45', 1, '2036-2037'),
(1093, '2024010 - Roi Garma', 'Imported voter', '2025-12-03 23:01:45', 1, '2036-2037'),
(1094, '2024011 - Edzel Catuiran', 'Imported voter', '2025-12-03 23:01:45', 1, '2036-2037'),
(1095, '2024012 - Romel Tangonan', 'Imported voter', '2025-12-03 23:01:45', 1, '2036-2037'),
(1096, '2024013 - Josh Asparela', 'Imported voter', '2025-12-03 23:01:45', 1, '2036-2037'),
(1097, '2024014 - Justin Tulio', 'Imported voter', '2025-12-03 23:01:45', 1, '2036-2037'),
(1098, '2024012 - enrolled', 'Updated enrollment', '2025-12-03 23:01:53', 1, '2036-2037'),
(1099, '2024001 - Dennis Macababbad', 'Imported voter', '2025-12-03 23:03:38', 1, '2029-2030'),
(1100, '2024002 - Jane Smith', 'Imported voter', '2025-12-03 23:03:38', 1, '2029-2030'),
(1101, '2024003 - Robert Johnson', 'Imported voter', '2025-12-03 23:03:38', 1, '2029-2030'),
(1102, '2024004 - Jhony Naui', 'Imported voter', '2025-12-03 23:03:38', 1, '2029-2030'),
(1103, '2024005 - Elymar Ilac', 'Imported voter', '2025-12-03 23:03:38', 1, '2029-2030'),
(1104, '2024006 - Samuel Manalili', 'Imported voter', '2025-12-03 23:03:38', 1, '2029-2030'),
(1105, '2024008 - Yurie Delos Santos', 'Imported voter', '2025-12-03 23:03:38', 1, '2029-2030'),
(1106, '2024009 - Lupo Luna', 'Imported voter', '2025-12-03 23:03:38', 1, '2029-2030'),
(1107, '2024010 - Roi Garma', 'Imported voter', '2025-12-03 23:03:38', 1, '2029-2030'),
(1108, '2024011 - Edzel Catuiran', 'Imported voter', '2025-12-03 23:03:38', 1, '2029-2030'),
(1109, '2024012 - Romel Tangonan', 'Imported voter', '2025-12-03 23:03:38', 1, '2029-2030'),
(1110, '2024013 - Josh Asparela', 'Imported voter', '2025-12-03 23:03:38', 1, '2029-2030'),
(1111, '2024014 - Justin Tulio', 'Imported voter', '2025-12-03 23:03:39', 1, '2029-2030'),
(1112, 'StudentID: 2024004 — Jhony Naui added as a candidate', 'Added candidate', '2025-12-03 23:04:23', 1, '2029-2030'),
(1113, 'StudentID: 2024001 — Dennis Macababbad added as a candidate', 'Added candidate', '2025-12-03 23:05:15', 1, '2029-2030'),
(1114, 'StudentID: 2024008 — Yurie Delos Santos added as a candidate', 'Added candidate', '2025-12-03 23:05:48', 1, '2029-2030'),
(1115, 'StudentID: 2024013 — Josh Asparela added as a candidate', 'Added candidate', '2025-12-03 23:06:40', 1, '2029-2030'),
(1116, 'Generated password for StudentID: 2024001', 'Generated password', '2025-12-03 23:06:56', 1, '2029-2030'),
(1117, 'Generated password for StudentID: 2024002', 'Generated password', '2025-12-03 23:06:56', 1, '2029-2030'),
(1118, 'Generated password for StudentID: 2024003', 'Generated password', '2025-12-03 23:06:56', 1, '2029-2030'),
(1119, 'Generated password for StudentID: 2024004', 'Generated password', '2025-12-03 23:06:56', 1, '2029-2030'),
(1120, 'Generated password for StudentID: 2024005', 'Generated password', '2025-12-03 23:06:56', 1, '2029-2030'),
(1121, 'Generated password for StudentID: 2024006', 'Generated password', '2025-12-03 23:06:56', 1, '2029-2030'),
(1122, 'Generated password for StudentID: 2024008', 'Generated password', '2025-12-03 23:06:56', 1, '2029-2030'),
(1123, 'Generated password for StudentID: 2024009', 'Generated password', '2025-12-03 23:06:56', 1, '2029-2030'),
(1124, 'Generated password for StudentID: 2024010', 'Generated password', '2025-12-03 23:06:56', 1, '2029-2030'),
(1125, 'Generated password for StudentID: 2024011', 'Generated password', '2025-12-03 23:06:56', 1, '2029-2030'),
(1126, 'Generated password for StudentID: 2024012', 'Generated password', '2025-12-03 23:06:56', 1, '2029-2030'),
(1127, 'Generated password for StudentID: 2024013', 'Generated password', '2025-12-03 23:06:56', 1, '2029-2030'),
(1128, 'Generated password for StudentID: 2024014', 'Generated password', '2025-12-03 23:06:56', 1, '2029-2030'),
(1129, 'StudentID: 2024006 — Samuel Manalili added as a candidate', 'Added candidate', '2025-12-03 23:09:51', 1, '2029-2030'),
(1130, 'StudentID: 2024012 — Romel Tangonan added as a candidate', 'Added candidate', '2025-12-03 23:10:52', 1, '2029-2030'),
(1131, 'Generated password for StudentID: 2024001', 'Generated password', '2025-12-04 00:22:37', 1, '2029-2030'),
(1132, 'Generated password for StudentID: 2024002', 'Generated password', '2025-12-04 00:22:37', 1, '2029-2030'),
(1133, 'Generated password for StudentID: 2024003', 'Generated password', '2025-12-04 00:22:37', 1, '2029-2030'),
(1134, 'Generated password for StudentID: 2024004', 'Generated password', '2025-12-04 00:22:37', 1, '2029-2030'),
(1135, 'Generated password for StudentID: 2024005', 'Generated password', '2025-12-04 00:22:37', 1, '2029-2030'),
(1136, 'Generated password for StudentID: 2024006', 'Generated password', '2025-12-04 00:22:37', 1, '2029-2030'),
(1137, 'Generated password for StudentID: 2024008', 'Generated password', '2025-12-04 00:22:37', 1, '2029-2030'),
(1138, 'Generated password for StudentID: 2024009', 'Generated password', '2025-12-04 00:22:37', 1, '2029-2030'),
(1139, 'Generated password for StudentID: 2024010', 'Generated password', '2025-12-04 00:22:37', 1, '2029-2030'),
(1140, 'Generated password for StudentID: 2024011', 'Generated password', '2025-12-04 00:22:37', 1, '2029-2030'),
(1141, 'Generated password for StudentID: 2024012', 'Generated password', '2025-12-04 00:22:37', 1, '2029-2030'),
(1142, 'Generated password for StudentID: 2024013', 'Generated password', '2025-12-04 00:22:37', 1, '2029-2030'),
(1143, 'Generated password for StudentID: 2024014', 'Generated password', '2025-12-04 00:22:37', 1, '2029-2030'),
(1144, 'StudentID: 2024010 — Roi Garma added as a candidate', 'Added candidate', '2025-12-04 08:02:28', 1, '2029-2030'),
(1145, 'Edited candidate: Romel Tangonan (2nd Year Representative) for 2029-2030', 'Edit Candidate', '2025-12-04 08:02:58', 1, '2029-2030'),
(1146, 'Edited candidate: Roi Garma (4th Year Representative) for 2029-2030', 'Edit Candidate', '2025-12-04 19:39:04', 1, '2029-2030'),
(1147, 'Generated password for StudentID: 2024001', 'Generated password', '2025-12-04 19:50:58', 1, '2029-2030'),
(1148, 'Generated password for StudentID: 2024002', 'Generated password', '2025-12-04 19:50:58', 1, '2029-2030'),
(1149, 'Generated password for StudentID: 2024003', 'Generated password', '2025-12-04 19:50:58', 1, '2029-2030'),
(1150, 'Generated password for StudentID: 2024004', 'Generated password', '2025-12-04 19:50:58', 1, '2029-2030'),
(1151, 'Generated password for StudentID: 2024005', 'Generated password', '2025-12-04 19:50:58', 1, '2029-2030'),
(1152, 'Generated password for StudentID: 2024006', 'Generated password', '2025-12-04 19:50:58', 1, '2029-2030'),
(1153, 'Generated password for StudentID: 2024008', 'Generated password', '2025-12-04 19:50:58', 1, '2029-2030'),
(1154, 'Generated password for StudentID: 2024009', 'Generated password', '2025-12-04 19:50:58', 1, '2029-2030'),
(1155, 'Generated password for StudentID: 2024010', 'Generated password', '2025-12-04 19:50:58', 1, '2029-2030'),
(1156, 'Generated password for StudentID: 2024011', 'Generated password', '2025-12-04 19:50:58', 1, '2029-2030'),
(1157, 'Generated password for StudentID: 2024012', 'Generated password', '2025-12-04 19:50:58', 1, '2029-2030'),
(1158, 'Generated password for StudentID: 2024013', 'Generated password', '2025-12-04 19:50:58', 1, '2029-2030'),
(1159, 'Generated password for StudentID: 2024014', 'Generated password', '2025-12-04 19:50:58', 1, '2029-2030'),
(1160, 'Generated password for StudentID: 2024013', 'Generated password', '2025-12-04 19:51:27', 1, '2029-2030'),
(1161, 'Admin logged out', 'Logout', '2025-12-19 20:21:42', 1, '2029-2030');

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
(1, '2024-2025', 0, '2025-05-06 14:23:11'),
(2, '2025-2026', 0, '2025-05-08 11:31:14'),
(8, '2027-2028', 0, '2025-05-24 07:58:35'),
(10, '2034-2035', 0, '2025-11-28 19:08:20'),
(11, '2036-2037', 0, '2025-11-28 19:09:13'),
(12, '2029-2030', 1, '2025-11-28 19:12:24'),
(13, '2040-2041', 0, '2025-11-28 19:13:02'),
(14, '2050-2051', 0, '2025-11-28 19:15:46'),
(15, '2043-2044', 0, '2025-11-28 19:25:40');

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
(1, 'Admin', 'User', 'Admin', '123', 'Admin');

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
('2024001', 'Dennis', 'Macababbad', 'M', '2024001', '7TJeq[A*', '1st Year', 'UnVoted', 'enrolled', '2029-2030'),
('2024002', 'Jane', 'Smith', 'A', '2024002', '7D)z8)Z7', '2nd Year', 'Voted', 'enrolled', '2029-2030'),
('2024003', 'Robert', 'Johnson', 'B', '2024003', 'rU5AS*-C', '3rd Year', 'UnVoted', 'enrolled', '2029-2030'),
('2024004', 'Jhony', 'Naui', 'G', '2024004', '7A.AAPAn', '4th Year', 'UnVoted', 'enrolled', '2029-2030'),
('2024005', 'Elymar', 'Ilac', 'D', '2024005', 'Nk.bt,1:', '4th Year', 'Voted', 'enrolled', '2029-2030'),
('2024006', 'Samuel', 'Manalili', 'D', '2024006', 'q9:GR>K<', '3rd Year', 'UnVoted', 'enrolled', '2029-2030'),
('2024008', 'Yurie', 'Delos Santos', 'X', '2024008', '6E5ijg<!', '1st Year', 'Voted', 'enrolled', '2029-2030'),
('2024009', 'Lupo', 'Luna', '', '2024009', '*L6^$9,B', '1st Year', 'UnVoted', 'enrolled', '2029-2030'),
('2024010', 'Roi', 'Garma', 'S', '2024010', 'cj7c,:]C', '3rd Year', 'UnVoted', 'enrolled', '2029-2030'),
('2024011', 'Edzel', 'Catuiran', 'G', '2024011', '=Jn0y;W2', '2nd Year', 'UnVoted', 'enrolled', '2029-2030'),
('2024012', 'Romel', 'Tangonan', 'B', '2024012', '7WvXnmKP', '3rd Year', 'UnVoted', 'enrolled', '2029-2030'),
('2024013', 'Josh', 'Asparela', 'A', '2024013', 'U]D)o7UN', '4th Year', 'Voted', 'enrolled', '2029-2030'),
('2024014', 'Justin', 'Tulio', 'C', '2024014', '9!AMwY5Q', '2nd Year', 'UnVoted', 'enrolled', '2029-2030');

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
(107, 83, '42424', '2025-2026', '2025-06-29 10:42:53', '2025-06-29 16:42:53', '2025-06-29 16:42:53'),
(108, 85, '42424', '2025-2026', '2025-06-29 10:42:53', '2025-06-29 16:42:53', '2025-06-29 16:42:53'),
(109, 87, '42424', '2025-2026', '2025-06-29 10:42:53', '2025-06-29 16:42:53', '2025-06-29 16:42:53'),
(110, 102, '102361', '2025-2026', '2025-11-29 18:17:08', '2025-11-29 09:17:08', '2025-11-29 09:17:08'),
(111, 94, '102361', '2025-2026', '2025-11-29 18:17:08', '2025-11-29 09:17:08', '2025-11-29 09:17:08'),
(112, 100, '102361', '2025-2026', '2025-11-29 18:17:08', '2025-11-29 09:17:08', '2025-11-29 09:17:08'),
(113, 102, '29487421', '2025-2026', '2025-11-30 20:33:28', '2025-11-30 11:33:28', '2025-11-30 11:33:28'),
(114, 94, '29487421', '2025-2026', '2025-11-30 20:33:28', '2025-11-30 11:33:28', '2025-11-30 11:33:28'),
(115, 97, '29487421', '2025-2026', '2025-11-30 20:33:28', '2025-11-30 11:33:28', '2025-11-30 11:33:28'),
(116, 101, '12422', '2025-2026', '2025-12-03 08:36:39', '2025-12-02 23:36:39', '2025-12-02 23:36:39'),
(117, 94, '12422', '2025-2026', '2025-12-03 08:36:39', '2025-12-02 23:36:39', '2025-12-02 23:36:39'),
(118, 96, '12422', '2025-2026', '2025-12-03 08:36:39', '2025-12-02 23:36:39', '2025-12-02 23:36:39'),
(119, 105, '2024008', '2029-2030', '2025-12-04 09:28:11', '2025-12-04 00:28:11', '2025-12-04 00:28:11'),
(120, 107, '2024008', '2029-2030', '2025-12-04 09:28:11', '2025-12-04 00:28:11', '2025-12-04 00:28:11'),
(121, 108, '2024008', '2029-2030', '2025-12-04 09:28:11', '2025-12-04 00:28:11', '2025-12-04 00:28:11'),
(122, 104, '2024002', '2029-2030', '2025-12-04 17:03:40', '2025-12-04 08:03:40', '2025-12-04 08:03:40'),
(123, 107, '2024002', '2029-2030', '2025-12-04 17:03:40', '2025-12-04 08:03:40', '2025-12-04 08:03:40'),
(124, 109, '2024002', '2029-2030', '2025-12-04 17:03:40', '2025-12-04 08:03:40', '2025-12-04 08:03:40'),
(125, 105, '2024005', '2029-2030', '2025-12-05 04:41:09', '2025-12-04 19:41:09', '2025-12-04 19:41:09'),
(126, 106, '2024005', '2029-2030', '2025-12-05 04:41:09', '2025-12-04 19:41:09', '2025-12-04 19:41:09'),
(127, 110, '2024005', '2029-2030', '2025-12-05 04:41:09', '2025-12-04 19:41:09', '2025-12-04 19:41:09'),
(128, 105, '2024013', '2029-2030', '2025-12-05 04:54:09', '2025-12-04 19:54:09', '2025-12-04 19:54:09'),
(129, 106, '2024013', '2029-2030', '2025-12-05 04:54:09', '2025-12-04 19:54:09', '2025-12-04 19:54:09'),
(130, 110, '2024013', '2029-2030', '2025-12-05 04:54:09', '2025-12-04 19:54:09', '2025-12-04 19:54:09');

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
  ADD KEY `idx_academic_year_votes` (`academic_year`);

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
  MODIFY `CandidateID` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=111;

--
-- AUTO_INCREMENT for table `history`
--
ALTER TABLE `history`
  MODIFY `history_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=1162;

--
-- AUTO_INCREMENT for table `settings`
--
ALTER TABLE `settings`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=16;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `User_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=21;

--
-- AUTO_INCREMENT for table `voters_history`
--
ALTER TABLE `voters_history`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `votes`
--
ALTER TABLE `votes`
  MODIFY `ID` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=131;

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
  ADD CONSTRAINT `votes_ibfk_3` FOREIGN KEY (`academic_year`) REFERENCES `settings` (`academic_year`) ON UPDATE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
