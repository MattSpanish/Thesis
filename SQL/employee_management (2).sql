-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Dec 30, 2024 at 05:57 AM
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
-- Database: `employee_management`
--

-- --------------------------------------------------------

--
-- Table structure for table `employees`
--

CREATE TABLE `employees` (
  `id` int(6) NOT NULL,
  `name` varchar(50) NOT NULL,
  `email` varchar(50) NOT NULL,
  `department` varchar(50) NOT NULL,
  `status` varchar(50) NOT NULL,
  `subjects` varchar(255) DEFAULT NULL,
  `gender` enum('Male','Female','Other') DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `employees`
--

INSERT INTO `employees` (`id`, `name`, `email`, `department`, `status`, `subjects`, `gender`) VALUES
(22, 'John Mathew Rabino Español', 'matthewrules08@gmail.com', 'ABM', 'FULL TIME', 'GENERAL MATHEMATICS', 'Male'),
(23, 'alysa', 'alysa@gmail.com', 'ABM', 'FULL TIME', 'KOMUNIKASYON AT PANANALIKSIK SA WIKA AT KULTURANG PILIPINO', 'Female'),
(25, 'kevin michael vargas manuel', 'Kevin.michaelmanuel24@gmail.com', 'ABM', 'FULL TIME', 'ORGANIZATION AND MANAGEMENT', 'Male'),
(26, 'mark', 'mark@gmail.com', 'ABM', 'FULL TIME', 'ORAL COMMUNICATION', 'Male');

-- --------------------------------------------------------

--
-- Table structure for table `time_tracking`
--

CREATE TABLE `time_tracking` (
  `id` int(11) NOT NULL,
  `employee_id` int(11) NOT NULL,
  `regular` int(11) DEFAULT 0,
  `overtime` int(11) DEFAULT 0,
  `sick_leave` int(11) DEFAULT 0,
  `pto` int(11) DEFAULT 0,
  `paid_holiday` int(11) DEFAULT 0,
  `date_added` datetime NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `time_tracking`
--

INSERT INTO `time_tracking` (`id`, `employee_id`, `regular`, `overtime`, `sick_leave`, `pto`, `paid_holiday`, `date_added`) VALUES
(14, 22, 0, 0, 0, 0, 0, '2024-10-31 13:00:07'),
(15, 23, 0, 0, 0, 0, 0, '2024-11-18 17:26:06'),
(17, 25, 0, 0, 0, 0, 0, '2024-11-25 16:07:20'),
(18, 26, 0, 0, 0, 0, 0, '2024-11-25 17:59:40');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `employees`
--
ALTER TABLE `employees`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `time_tracking`
--
ALTER TABLE `time_tracking`
  ADD PRIMARY KEY (`id`),
  ADD KEY `time_tracking_ibfk_1` (`employee_id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `employees`
--
ALTER TABLE `employees`
  MODIFY `id` int(6) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=27;

--
-- AUTO_INCREMENT for table `time_tracking`
--
ALTER TABLE `time_tracking`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=19;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `time_tracking`
--
ALTER TABLE `time_tracking`
  ADD CONSTRAINT `time_tracking_ibfk_1` FOREIGN KEY (`employee_id`) REFERENCES `employees` (`id`) ON DELETE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
