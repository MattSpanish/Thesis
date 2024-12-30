-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Dec 30, 2024 at 05:59 AM
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
-- Database: `teacher_schedule`
--

-- --------------------------------------------------------

--
-- Table structure for table `conflict_models`
--

CREATE TABLE `conflict_models` (
  `id` int(11) NOT NULL,
  `Faculty Name` varchar(255) NOT NULL,
  `model` blob DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `schedule`
--

CREATE TABLE `schedule` (
  `id` int(11) NOT NULL,
  `Faculty Name` varchar(255) NOT NULL,
  `Subject` varchar(255) DEFAULT NULL,
  `Time` varchar(255) DEFAULT NULL,
  `Day` varchar(50) DEFAULT NULL,
  `Total Hours` int(11) NOT NULL,
  `Conflict` tinyint(1) DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `schedule`
--

INSERT INTO `schedule` (`id`, `Faculty Name`, `Subject`, `Time`, `Day`, `Total Hours`, `Conflict`) VALUES
(1, 'Ariola ( FC1)', 'Gen Chem ', '1:30 - 4:30PM', 'Monday', 0, 0),
(2, 'Unknown', 'Unknown', '4:50 -5:20 PM', 'Monday', 0, 0),
(3, 'Unknown', 'Unknown', '1:30 - 4:30PM', 'Tuesday', 0, 0),
(4, 'Unknown', 'Unknown', '7:00 - 10:00AM', 'Wednesday', 0, 0),
(5, 'Unknown', 'Unknown', '1:30 - 4:30PM', 'Wednesday', 0, 0),
(6, 'Unknown', 'Earth Science', 'Unknown', 'Unknown', 0, 0),
(7, 'Unknown', 'Unknown', '7:00 - 10:00AM', 'Tuesday', 0, 0),
(8, 'Unknown', 'Unknown', '10:20 - 1:20PM', 'Tuesday', 0, 0),
(9, 'Unknown', 'Unknown', '4:50 - 7:50PM', 'Tuesday', 0, 0),
(10, 'Unknown', 'Unknown', '7:00 - 10:00AM', 'Friday ', 0, 0),
(11, 'Unknown', 'Unknown', 'Unknown', 'Unknown', 0, 0),
(12, 'Unknown', 'Unknown', 'Unknown', 'Unknown', 0, 0),
(13, 'Unknown', 'Unknown', 'Unknown', 'Unknown', 0, 0),
(14, 'Unknown', 'BIO 1', 'Unknown', 'Unknown', 0, 0),
(15, 'Unknown', 'Unknown', '7:00-10:00AM', 'Saturday', 0, 0),
(16, 'Unknown', 'Unknown', '10:20 -1:20PM', 'Saturday', 0, 0),
(17, 'Unknown', 'Unknown', '1:30 - 4:30PM', 'Saturday', 0, 0),
(18, 'Unknown', 'Unknown', '4:50 - 7:50PM', 'Saturday', 0, 0),
(19, 'Unknown', 'Unknown', 'Unknown', 'Unknown', 0, 0),
(20, 'De Luna ', 'Earth Science', '7:00 -10:20AM', 'Monday', 0, 0),
(21, 'Unknown', 'Unknown', '10:50 -1:20PM', 'Monday ', 0, 0),
(22, 'Unknown', 'Unknown', '1:30 -4:30PM', 'Monday', 0, 0),
(23, 'Unknown', 'Unknown', '4:50 - 7:50PM', 'Monday', 0, 0),
(24, 'Unknown', 'Unknown', '7:00 -10:20AM', 'Thursday', 0, 0),
(25, 'Unknown', 'Unknown', '10:50 - 1:20PM', 'Thursday', 0, 0),
(26, 'Unknown', 'PHY SCIENCE', 'Unknown', 'Unknown', 0, 0),
(27, 'Unknown', 'Unknown', '7:00 - 10:20AM', 'Tuesday', 0, 0),
(28, 'Unknown', 'Unknown', '10:50 - 1:20PM', 'Tuesday', 0, 0),
(29, 'Unknown', 'Unknown', '1:30 - 4:30PM', 'Tuesday', 0, 0),
(30, 'Unknown', 'Unknown', '10:50 - 1:20AM', 'Friday ', 0, 0),
(31, 'Unknown', 'Unknown', '1:30 - 4:30PM', 'Friday ', 0, 0),
(32, 'Unknown', 'Unknown', '4:50 - 7:50PM', 'Friday ', 0, 0),
(33, 'Unknown', 'BIO 1 ', 'Unknown', 'Unknown', 0, 0),
(34, 'Unknown', 'Unknown', '7:00 - 10:20 AM', 'Wednesday', 0, 0),
(35, 'Unknown', 'Unknown', '1:30 - 4:30PM', 'Wednesday', 0, 0),
(36, 'Unknown', 'Unknown', '7:00 - 10:20AM', 'Friday ', 0, 0),
(37, 'Unknown', 'Unknown', 'Unknown', 'Unknown', 0, 0),
(38, 'Bermillo ', 'Earth Science ', '7:00 - 10:20AM', 'Wednesday', 0, 0),
(39, 'Unknown', 'Unknown', '1:30 - 4:30PM', 'Wednesday', 0, 0),
(40, 'Unknown', 'Unknown', '1:30 - 4:30PM', 'Thursday', 0, 0),
(41, 'Unknown', 'Unknown', '4:50 - 7:50PM', 'Thursday', 0, 0),
(42, 'Unknown', 'Unknown', '1:30 - 4:30PM', 'Saturday', 0, 0),
(43, 'Unknown', 'Unknown', '4:50 - 7:50PM', 'Saturday', 0, 0),
(44, 'Unknown', 'Unknown', 'Unknown', 'Unknown', 0, 0),
(45, 'Unknown', 'PHY SCIENCE', 'Unknown', 'Unknown', 0, 0),
(46, 'Unknown', 'Unknown', '7:00 - 10:20AM', 'Monday ', 0, 0),
(47, 'Unknown', 'Unknown', '10:20 - 1:20PM', 'Monday ', 0, 0),
(48, 'Unknown', 'Unknown', '10:20 - 1:20PM', 'Tuesday', 0, 0),
(49, 'Unknown', 'Unknown', '4:50 - 7:50PM', 'Tuesday', 0, 0),
(50, 'Unknown', 'BIO 1 ', 'Unknown', 'Unknown', 0, 0),
(51, 'Unknown', 'Unknown', '1:30 - 4:30PM', 'Monday ', 0, 0),
(52, 'Unknown', 'Unknown', '7:00 - 10:20AM', 'Friday', 0, 0),
(53, 'Unknown', 'Unknown', '10:20 - 1:20PM', 'Friday', 0, 0),
(54, 'Unknown', 'Unknown', '1:30 - 4:30PM', 'Friday', 0, 0),
(55, 'Unknown', 'Unknown', 'Unknown', 'Unknown', 0, 0),
(56, 'Carias', 'BIO 1 ', '1:30 - 4:30PM', 'Monday', 0, 0),
(57, 'Unknown', 'Unknown', '7:00 - 10:00AM', 'Tuesday ', 0, 0),
(58, 'Unknown', 'Unknown', '10:50 - 1:20PM', 'Tuesday ', 0, 0),
(59, 'Unknown', 'Unknown', '1:30 - 4:30 PM', 'Tuesday ', 0, 0),
(60, 'Unknown', 'Unknown', '4:50 - 7:50PM', 'Tuesday ', 0, 0),
(61, 'Unknown', 'GEN CHEM', 'Unknown', 'Unknown', 0, 0),
(62, 'Unknown', 'Unknown', '7:00 - 10:00AM', 'Monday', 0, 0),
(63, 'Unknown', 'Unknown', '10:20 - 1:20PM', 'Monday', 0, 0),
(64, 'Unknown', 'Unknown', '7:00 - 10:00AM', 'Thursday ', 0, 0),
(65, 'Unknown', 'Unknown', '10:20 - 1:20PM', 'Thursday ', 0, 0),
(66, 'Unknown', 'Unknown', '1:30 - 4:30PM', 'Thursday ', 0, 0),
(67, 'Unknown', 'Unknown', '10:20 - 1:20PM', 'Friday', 0, 0),
(68, 'Unknown', 'EALS', 'Unknown', 'Unknown', 0, 0),
(69, 'Unknown', 'Unknown', '10:20 -1:20PM', 'Wednesday', 0, 0),
(70, 'Unknown', 'Unknown', '4:30 - 7:50PM', 'Wednesday', 0, 0),
(71, 'Unknown', 'Unknown', '7:00 - 10:00AM', 'Friday', 0, 0),
(72, 'Unknown', 'Unknown', '4:50 - 7:50PM', 'Friday', 0, 0),
(73, 'Unknown', 'Unknown', 'Unknown', 'Unknown', 0, 0),
(74, 'Batis', 'BIO 1', '7:00 - 10:00AM', 'Monday ', 0, 0),
(75, 'Unknown', 'Unknown', '10:20 - 1:20PM', 'Monday ', 0, 0),
(76, 'Unknown', 'Unknown', '4:50 - 7:50PM', 'Monday ', 0, 0),
(77, 'Unknown', 'GEN CHEM ', 'Unknown', 'Unknown', 0, 0),
(78, 'Unknown', 'Unknown', '10:50 - 1:20PM', 'Wednesday ', 0, 0),
(79, 'Unknown', 'Unknown', '1:30 - 4:30PM', 'Wednesday ', 0, 0),
(80, 'Unknown', 'Unknown', '4:50 -7:50PM', 'Wednesday ', 0, 0),
(81, 'Unknown', 'Unknown', '7:00 - 10:00AM', 'Saturday', 0, 0),
(82, 'Unknown', 'Unknown', '4:50 - 7:50PM', 'Saturday ', 0, 0),
(83, 'Unknown', 'EALS', 'Unknown', 'Unknown', 0, 0),
(84, 'Unknown', 'Unknown', '7:00 - 10:00AM', 'Wednesday', 0, 0),
(85, 'Unknown', 'Unknown', '7:00 - 10:00AM', 'Friday', 0, 0),
(86, 'Unknown', 'Unknown', '10:20 - 1:20PM', 'Friday ', 0, 0),
(87, 'Unknown', 'Unknown', '1:30 - 4:30PM', 'Friday', 0, 0),
(88, 'Unknown', 'Unknown', '1:30 -4:30PM', 'Saturday', 0, 0);

-- --------------------------------------------------------

--
-- Table structure for table `system_logs`
--

CREATE TABLE `system_logs` (
  `id` int(11) NOT NULL,
  `timestamp` timestamp NOT NULL DEFAULT current_timestamp(),
  `message` text NOT NULL,
  `level` enum('INFO','ERROR','WARNING') NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `weekly_hours_limit`
--

CREATE TABLE `weekly_hours_limit` (
  `id` int(11) NOT NULL,
  `Faculty Name` varchar(255) NOT NULL,
  `Total Hours` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Indexes for dumped tables
--

--
-- Indexes for table `conflict_models`
--
ALTER TABLE `conflict_models`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `schedule`
--
ALTER TABLE `schedule`
  ADD PRIMARY KEY (`id`),
  ADD KEY `idx_faculty_day` (`Faculty Name`,`Day`),
  ADD KEY `idx_faculty_time` (`Faculty Name`,`Time`);

--
-- Indexes for table `system_logs`
--
ALTER TABLE `system_logs`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `weekly_hours_limit`
--
ALTER TABLE `weekly_hours_limit`
  ADD PRIMARY KEY (`id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `conflict_models`
--
ALTER TABLE `conflict_models`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `schedule`
--
ALTER TABLE `schedule`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=89;

--
-- AUTO_INCREMENT for table `system_logs`
--
ALTER TABLE `system_logs`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `weekly_hours_limit`
--
ALTER TABLE `weekly_hours_limit`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
