-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Dec 30, 2024 at 05:58 AM
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
-- Database: `enrollment_db`
--

-- --------------------------------------------------------

--
-- Table structure for table `facultyschedule`
--

CREATE TABLE `facultyschedule` (
  `schedule_id` int(11) NOT NULL,
  `id` int(11) NOT NULL,
  `fullname` varchar(255) NOT NULL,
  `subject` varchar(255) NOT NULL,
  `sections` varchar(50) NOT NULL,
  `time` time NOT NULL,
  `day` varchar(50) NOT NULL,
  `total_students` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `facultyschedule`
--

INSERT INTO `facultyschedule` (`schedule_id`, `id`, `fullname`, `subject`, `sections`, `time`, `day`, `total_students`) VALUES
(1, 1, 'Matthew espanol', 'Math', 'STEM 11-YA-1', '08:00:00', 'Monday, Wednesday, Friday', 35);

-- --------------------------------------------------------

--
-- Table structure for table `faculty_data`
--

CREATE TABLE `faculty_data` (
  `year` int(11) NOT NULL,
  `total_teachers` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `faculty_data`
--

INSERT INTO `faculty_data` (`year`, `total_teachers`) VALUES
(2019, 2),
(2021, 2),
(2022, 11),
(2023, 6),
(2024, 14);

-- --------------------------------------------------------

--
-- Table structure for table `historical_data`
--

CREATE TABLE `historical_data` (
  `id` int(11) NOT NULL,
  `id_no` varchar(50) NOT NULL,
  `last_name` varchar(100) NOT NULL,
  `first_name` varchar(100) NOT NULL,
  `middle_name` varchar(100) DEFAULT NULL,
  `department` varchar(150) NOT NULL,
  `position` varchar(100) NOT NULL,
  `date_hired` date NOT NULL,
  `years_of_service` int(11) NOT NULL,
  `ranking` varchar(50) NOT NULL,
  `status` varchar(50) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `historical_data`
--

INSERT INTO `historical_data` (`id`, `id_no`, `last_name`, `first_name`, `middle_name`, `department`, `position`, `date_hired`, `years_of_service`, `ranking`, `status`) VALUES
(1, '19246-3188', 'F', '0', '1', 'SENIOR HIGH SCHOOL', 'FACULTY MEMBER', '2019-11-11', 4, 'ASSISTANT INSTRUCTOR', 'ACTIVE'),
(2, '06-24690-4043', 'F', '0', '2', 'SENIOR HIGH SCHOOL', 'FACULTY MEMBER', '2024-01-25', 0, 'ASSISTANT INSTRUCTOR', 'ACTIVE'),
(3, '06-24653-177', 'F', '0', '3', 'SENIOR HIGH SCHOOL', 'FACULTY MEMBER', '2024-08-19', 0, 'ASSISTANT INSTRUCTOR', 'ACTIVE'),
(4, '06-21360-6693', 'F', '0', '4', 'SENIOR HIGH SCHOOL', 'FACULTY MEMBER', '2021-06-22', 3, 'ASSISTANT INSTRUCTOR', 'ACTIVE'),
(5, '06-21707-5142', 'F', '0', '5', 'SENIOR HIGH SCHOOL', 'FACULTY MEMBER', '2021-09-06', 3, 'ASSISTANT INSTRUCTOR', 'ACTIVE'),
(6, '06-22226-1141', 'F', '0', '6', 'SENIOR HIGH SCHOOL', 'FACULTY MEMBER', '2022-02-07', 2, 'ASSISTANT INSTRUCTOR', 'ACTIVE'),
(7, '06-22907-4983', 'F', '0', '7', 'SENIOR HIGH SCHOOL', 'FACULTY MEMBER', '2022-08-22', 2, 'ASSISTANT INSTRUCTOR', 'ACTIVE'),
(8, '06-22167-4271', 'F', '0', '8', 'SENIOR HIGH SCHOOL', 'FACULTY MEMBER', '2022-08-22', 2, 'ASSISTANT INSTRUCTOR', 'ACTIVE'),
(9, '06-22411-6713', 'F', '0', '9', 'SENIOR HIGH SCHOOL', 'FACULTY MEMBER', '2022-08-22', 2, 'ASSISTANT INSTRUCTOR', 'ACTIVE'),
(10, '06-22482-4856', 'F', '0', '10', 'SENIOR HIGH SCHOOL', 'FACULTY MEMBER', '2022-08-22', 2, 'ASSISTANT INSTRUCTOR', 'ACTIVE'),
(11, '06-22619-1273', 'F', '0', '11', 'SENIOR HIGH SCHOOL', 'FACULTY MEMBER', '2022-08-22', 2, 'ASSISTANT INSTRUCTOR', 'ACTIVE'),
(12, '06-22773-8462', 'F', '0', '12', 'SENIOR HIGH SCHOOL', 'ASSISTANT PRINCIPAL/FACULTY MEMBER', '2022-08-22', 2, 'ASSISTANT INSTRUCTOR', 'ACTIVE'),
(13, '06-22974-5329', 'F', '0', '13', 'SENIOR HIGH SCHOOL', 'FACULTY MEMBER', '2022-08-22', 2, 'ASSISTANT INSTRUCTOR', 'ACTIVE'),
(14, '06-22754-9850', 'F', '0', '14', 'SENIOR HIGH SCHOOL', 'FACULTY MEMBER', '2022-08-22', 2, 'ASSISTANT INSTRUCTOR', 'ACTIVE'),
(15, '06-22975-8978', 'F', '0', '15', 'SENIOR HIGH SCHOOL', 'FACULTY MEMBER', '2022-08-22', 2, 'ASSISTANT INSTRUCTOR', 'ACTIVE'),
(16, '06-22731-9545', 'F', '0', '16', 'SENIOR HIGH SCHOOL', 'FACULTY MEMBER', '2022-09-20', 2, 'ASSISTANT INSTRUCTOR', 'ACTIVE'),
(17, '06-23805-8086', 'F', '0', '17', 'SENIOR HIGH SCHOOL', 'FACULTY MEMBER', '2023-01-24', 1, 'ASSISTANT INSTRUCTOR', 'ACTIVE'),
(18, '06-23716-1238', 'F', '0', '18', 'SENIOR HIGH SCHOOL', 'FACULTY MEMBER', '2023-08-14', 1, 'ASSISTANT INSTRUCTOR', 'ACTIVE'),
(19, '06-23416-4594', 'F', '0', '19', 'SENIOR HIGH SCHOOL', 'FACULTY MEMBER', '2023-08-14', 1, 'ASSISTANT INSTRUCTOR', 'ACTIVE'),
(20, '06-23737-4875', 'F', '0', '20', 'SENIOR HIGH SCHOOL', 'FACULTY MEMBER', '2023-08-14', 1, 'ASSISTANT INSTRUCTOR', 'ACTIVE'),
(21, '06-23782-2654', 'F', '0', '21', 'SENIOR HIGH SCHOOL', 'FACULTY MEMBER', '2023-08-14', 1, 'ASSISTANT INSTRUCTOR', 'ACTIVE'),
(22, '06-23546-3416', 'F', '0', '22', 'SENIOR HIGH SCHOOL', 'FACULTY MEMBER', '2023-08-22', 1, 'ASSISTANT INSTRUCTOR', 'ACTIVE'),
(23, '06-24511-8583', 'F', '0', '23', 'SENIOR HIGH SCHOOL', 'FACULTY MEMBER', '2024-01-15', 0, 'ASSISTANT INSTRUCTOR', 'ACTIVE'),
(24, '19460-1985', 'F', '0', '24', 'SENIOR HIGH SCHOOL', 'FACULTY MEMBER', '2024-01-15', 0, 'ASSISTANT INSTRUCTOR', 'ACTIVE'),
(25, '19871-2985', 'F', '0', '25', 'SENIOR HIGH SCHOOL', 'FACULTY MEMBER', '2019-04-25', 5, 'ASSISTANT INSTRUCTOR', 'ACTIVE'),
(26, '06-24902-3546', 'F', '0', '26', 'SENIOR HIGH SCHOOL', 'FACULTY MEMBER', '2024-08-05', 0, 'ASSISTANT INSTRUCTOR', 'ACTIVE'),
(27, '06-24543-9427', 'F', '0', '27', 'SENIOR HIGH SCHOOL', 'FACULTY MEMBER', '2024-08-05', 0, 'ASSISTANT INSTRUCTOR', 'ACTIVE'),
(28, '06-24412-8153', 'F', '0', '28', 'SENIOR HIGH SCHOOL', 'FACULTY MEMBER', '2024-08-05', 0, 'ASSISTANT INSTRUCTOR', 'ACTIVE'),
(29, '06-24112-9370', 'F', '0', '29', 'SENIOR HIGH SCHOOL', 'FACULTY MEMBER', '2024-08-05', 0, 'ASSISTANT INSTRUCTOR', 'ACTIVE'),
(30, '06-24719-6812', 'F', '0', '30', 'SENIOR HIGH SCHOOL', 'FACULTY MEMBER', '2024-08-05', 0, 'ASSISTANT INSTRUCTOR', 'ACTIVE'),
(31, '06-24805-3467', 'F', '0', '31', 'SENIOR HIGH SCHOOL', 'FACULTY MEMBER', '2024-08-05', 0, 'ASSISTANT INSTRUCTOR', 'ACTIVE'),
(32, '06-24803-7788', 'F', '0', '32', 'SENIOR HIGH SCHOOL', 'FACULTY MEMBER', '2024-08-05', 0, 'ASSISTANT INSTRUCTOR', 'ACTIVE'),
(33, '06-24292-3135', 'F', '0', '33', 'SENIOR HIGH SCHOOL', 'FACULTY MEMBER', '2024-08-05', 0, 'ASSISTANT INSTRUCTOR', 'ACTIVE'),
(34, '06-24643-3662', 'F', '0', '34', 'SENIOR HIGH SCHOOL', 'FACULTY MEMBER', '2024-08-19', 0, 'ASSISTANT INSTRUCTOR', 'ACTIVE'),
(35, '06-24232-2563', 'F', '0', '35', 'SENIOR HIGH SCHOOL', 'FACULTY MEMBER', '2024-08-10', 0, 'ASSISTANT INSTRUCTOR', 'ACTIVE'),
(36, '0', 'F', '0', '36', 'SENIOR HIGH SCHOOL', 'FACULTY MEMBER', '2022-08-22', 2, 'ASSISTANT INSTRUCTOR', 'INACTIVE'),
(37, '0', 'F', '0', '37', 'SENIOR HIGH SCHOOL', 'FACULTY MEMBER', '2022-08-22', 2, 'ASSISTANT INSTRUCTOR', 'INACTIVE'),
(38, '0', 'F', '0', '38', 'SENIOR HIGH SCHOOL', 'FACULTY MEMBER', '2022-08-22', 2, 'ASSISTANT INSTRUCTOR', 'INACTIVE'),
(39, '0', 'F', '0', '39', 'SENIOR HIGH SCHOOL', 'FACULTY MEMBER', '2022-08-22', 2, 'ASSISTANT INSTRUCTOR', 'INACTIVE'),
(40, '0', 'F', '0', '40', 'SENIOR HIGH SCHOOL', 'FACULTY MEMBER', '2022-08-22', 2, 'ASSISTANT INSTRUCTOR', 'INACTIVE'),
(41, '0', 'F', '0', '41', 'SENIOR HIGH SCHOOL', 'FACULTY MEMBER', '2022-08-22', 2, 'ASSISTANT INSTRUCTOR', 'INACTIVE'),
(42, '0', 'F', '0', '42', 'SENIOR HIGH SCHOOL', 'FACULTY MEMBER', '2023-08-15', 1, 'ASSISTANT INSTRUCTOR', 'INACTIVE');

-- --------------------------------------------------------

--
-- Table structure for table `student_data`
--

CREATE TABLE `student_data` (
  `program` varchar(100) DEFAULT NULL,
  `total` int(11) DEFAULT NULL,
  `year` varchar(20) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `student_data`
--

INSERT INTO `student_data` (`program`, `total`, `year`) VALUES
('SHS-HUMSS', 62, '2021'),
('SHS-HUMSS', 32, '2021'),
('SHS-STEM', 170, '2021'),
('SHS-STEM PLUS', 23, '2021'),
('SHS-ABM', 224, '2021-2022'),
('SHS-HUMSS', 136, '2021-2022'),
('SHS-STEM', 632, '2021-2022'),
('SHS-STEM PLUS', 46, '2021-2022'),
('SHS-ABM', 295, '2022-2023'),
('SHS-ABM', 185, '2022-2023'),
('SHS-STEM', 937, '2022-2023'),
('SHS-STEM PLUS', 45, '2022-2023'),
('SHS-ABM', 311, '2023-2024'),
('SHS-ABM PLUS', 45, '2023-2024'),
('SHS-HUMSS', 270, '2023-2024'),
('SHS-HUMSS PLUS', 43, '2023-2024'),
('SHS-STEM', 1193, '2023-2024'),
('SHS-STEM PLUS', 263, '2023-2024'),
('SHS-ABM', 297, '2024-2025'),
('SHS-ABM PLUS', 105, '2024-2025'),
('SHS-HUMSS', 293, '2024-2025'),
('SHS-HUMSS PLUS', 99, '2024-2025'),
('SHS-STEM', 1151, '2024-2025'),
('SHS-STEM PLUS', 553, '2024-2025'),
('', 0, '');

-- --------------------------------------------------------

--
-- Table structure for table `student_records`
--

CREATE TABLE `student_records` (
  `id` int(11) NOT NULL,
  `year` varchar(10) DEFAULT NULL,
  `program` varchar(50) DEFAULT NULL,
  `section` varchar(50) DEFAULT NULL,
  `total` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `student_records`
--

INSERT INTO `student_records` (`id`, `year`, `program`, `section`, `total`) VALUES
(9, '2021', 'SHS-ABM', '11-YA1-1', 62),
(10, '2021', 'SHS-HUMSS', '11-YA1-1', 32),
(11, '2021', 'SHS-STEM', '11-YA1-1', 54),
(12, '2021', 'SHS-STEM', '11-YA1-2', 48),
(13, '2021', 'SHS-STEM', '11-YA1-3', 50),
(14, '2021', 'SHS-STEM', '11-YA1-4', 18),
(15, '2021', 'SHS-STEMPLUS', '11-Y1-1P', 23),
(19, '2021-2022', 'SHS-ABM', '11-YA1-1', 49),
(20, '2021-2022', 'SHS-ABM', '11-YA1-2', 36),
(21, '2021-2022', 'SHS-ABM', '11-YA1-3', 53),
(23, '2021-2022', 'SHS-ABM', '11-YA1-3', 28),
(24, '2021-2022', 'SHS-ABM', '12-Y1-1', 58),
(25, '2021-2022', 'SHS-HUMSS', '11-YA1-1', 54),
(26, '2021-2022', 'SHS-HUMSS', '11-YA1-2', 54),
(27, '2021-2022', 'SHS-HUMSS', '12-Y1-1', 28),
(28, '2021-2022', 'SHS-STEM', '11-YA1-1', 54),
(29, '2021-2022', 'SHS-STEM', '11-Y1-2', 50),
(30, '2021-2022', 'SHS-STEM', '11-Y1-3', 52),
(31, '2021-2022', 'SHS-STEM', '11-Y1-4', 50),
(32, '2021-2022', 'SHS-STEM', '11-Y1-5', 55),
(33, '2021-2022', 'SHS-STEM', '11-Y1-6', 50),
(34, '2021-2022', 'SHS-STEM', '11-Y1-7', 51),
(35, '2021-2022', 'SHS-STEM', '11-Y1-8', 53),
(36, '2021-2022', 'SHS-STEM', '11-Y1-9', 54),
(37, '2021-2022', 'SHS-STEM', '12-Y1-1', 48),
(38, '2021-2022', 'SHS-STEM', '12-Y1-2', 42),
(39, '2021-2022', 'SHS-STEM', '12-Y1-3', 43),
(40, '2021-2022', 'SHS-STEM', '12-Y1-4', 30),
(41, '2021-2022', 'SHS-STEMPLUS', '11-Y1-1P', 25),
(42, '2021-2022', 'SHS-STEMPLUS', '12-Y1-1P', 46),
(43, '2022-2023', 'SHS-ABM', '11-YA1-1', 48),
(44, '2022-2023', 'SHS-ABM', '11-Y1-2', 49),
(45, '2022-2023', 'SHS-ABM', '11-Y1-3', 47),
(46, '2022-2023', 'SHS-ABM', '12-Y1-1', 49),
(47, '2022-2023', 'SHS-ABM', '12-Y1-2', 34),
(48, '2022-2023', 'SHS-ABM', '12-Y1-3', 54),
(49, '2022-2023', 'SHS-ABM', '12-Y1-4', 14),
(50, '2022-2023', 'SHS-HUMSS', '11-Y1-1', 46),
(51, '2022-2023', 'SHS-HUMSS', '11-Y1-2', 31),
(52, '2022-2023', 'SHS-HUMSS', '11-Y1-3', 19),
(53, '2022-2023', 'SHS-HUMSS', '12-Y1-1', 50),
(54, '2022-2023', 'SHS-HUMSS', '12-Y1-2', 39),
(55, '2022-2023', 'SHS-STEM', '11-Y1-1', 46),
(56, '2022-2023', 'SHS-STEM', '11-Y1-10', 51),
(57, '2022-2023', 'SHS-STEM', '11-Y1-2', 47),
(58, '2022-2023', 'SHS-STEM', '11-Y1-3', 50),
(59, '2022-2023', 'SHS-STEM', '11-Y1-4', 49),
(60, '2022-2023', 'SHS-STEM', '11-Y1-5', 50),
(61, '2022-2023', 'SHS-STEM', '11-Y1-6', 48),
(62, '2022-2023', 'SHS-STEM', '11-Y1-7', 49),
(63, '2022-2023', 'SHS-STEM', '11-Y1-8', 49),
(64, '2022-2023', 'SHS-STEM', '11-Y1-9', 52),
(65, '2022-2023', 'SHS-STEM', '12-Y1-1', 52),
(66, '2022-2023', 'SHS-STEM', '12-Y1-2', 49),
(67, '2022-2023', 'SHS-STEM', '12-Y1-3', 52),
(68, '2022-2023', 'SHS-STEM', '12-Y1-4', 49),
(69, '2022-2023', 'SHS-STEM', '12-Y1-5', 53),
(70, '2022-2023', 'SHS-STEM', '12-Y1-6', 39),
(71, '2022-2023', 'SHS-STEM', '12-Y1-7', 53),
(72, '2022-2023', 'SHS-STEM', '12-Y1-8', 44),
(73, '2022-2023', 'SHS-STEM', '12-Y1-9', 54),
(74, '2022-2023', 'SHS-STEMPLUS', '11-Y1-1', 19),
(75, '2022-2023', 'SHS-STEMPLUS', '12-Y1-1', 26),
(76, '2023-2024', 'SHS-ABM', '11-Y1-1', 47),
(77, '2023-2024', 'SHS-ABM', '11-Y1-2', 50),
(79, '2023-2024', 'SHS-ABM', '11-YA-3', 44),
(80, '2023-2024', 'SHS-ABM', '11-YA-4', 31),
(81, '2023-2024', 'SHS-ABM', '12-YA-1', 52),
(82, '2023-2024', 'SHS-ABM', '12-YA-2', 39),
(83, '2023-2024', 'SHS-ABM', '12-YA-3', 48),
(84, '2023-2024', 'SHS-ABMPLUS', '11-Y1-1P', 45),
(85, '2023-2024', 'SHS-HUMSS', '11-YA-1', 49),
(86, '2023-2024', 'SHS-HUMSS', '11-YA-2', 48),
(87, '2023-2024', 'SHS-HUMSS', '11-YA-3', 50),
(88, '2023-2024', 'SHS-HUMSS', '11-YA-4', 35),
(89, '2023-2024', 'SHS-HUMSS', '12-YA-1', 44),
(90, '2023-2024', 'SHS-HUMSS', '12-YA-2', 44),
(91, '2023-2024', 'SHS-HUMSSPLUS', '11-YA-1P', 43),
(92, '2023-2024', 'SHS-STEM', '11-Y1-1', 50),
(93, '2023-2024', 'SHS-STEM', '11-Y1-2', 48),
(94, '2023-2024', 'SHS-STEM', '11-Y1-3', 46),
(95, '2023-2024', 'SHS-STEM', '11-Y1-4', 49),
(96, '2023-2024', 'SHS-STEM', '11-Y1-5', 49),
(97, '2023-2024', 'SHS-STEM', '11-Y1-6', 50),
(98, '2023-2024', 'SHS-STEM', '11-YA-10', 50),
(99, '2023-2024', 'SHS-STEM', '11-YA-11', 48),
(100, '2023-2024', 'SHS-STEM', '11-YA-12', 50),
(101, '2023-2024', 'SHS-STEM', '11-YA-13', 49),
(102, '2023-2024', 'SHS-STEM', '11-YA-14', 49),
(103, '2023-2024', 'SHS-STEM', '11-YA-15', 46),
(104, '2023-2024', 'SHS-STEM', '11-YA-7', 50),
(105, '2023-2024', 'SHS-STEM', '11-YA-8', 50),
(106, '2023-2024', 'SHS-STEM', '11-YA-9', 50),
(107, '2023-2024', 'SHS-STEM', '12-YA-1', 50),
(108, '2023-2024', 'SHS-STEM', '12-YA-2', 51),
(109, '2023-2024', 'SHS-STEM', '12-YA-3', 51),
(110, '2023-2024', 'SHS-STEM', '12-YA-4', 52),
(111, '2023-2024', 'SHS-STEM', '12-YA-5', 51),
(112, '2023-2024', 'SHS-STEM', '12-YA-6', 51),
(113, '2023-2024', 'SHS-STEM', '12-YA-7', 51),
(114, '2023-2024', 'SHS-STEM', '12-YA-8', 50),
(115, '2023-2024', 'SHS-STEM', '12-YA-9', 52),
(116, '2023-2024', 'SHS-STEMPLUS', '11-Y1-1P', 51),
(117, '2023-2024', 'SHS-STEMPLUS', '11-Y1-2P', 47),
(118, '2023-2024', 'SHS-STEMPLUS', '11-Y1-3P', 50),
(121, '2023-2024', 'SHS-STEMPLUS', '11-YA-4P', 45),
(122, '2023-2024', 'SHS-STEMPLUS', '11-YA-5P', 32),
(123, '2023-2024', 'SHS-STEMPLUS', '12-YA-1', 38),
(124, '2024-2025', 'SHS-ABM', '11-YA-1', 45),
(125, '2024-2025', 'SHS-ABM', '11-YA-2', 40),
(126, '2024-2025', 'SHS-ABM', '11-YA-3', 45),
(127, '2024-2025', 'SHS-ABM', '12-YA-1', 50),
(128, '2024-2025', 'SHS-ABM', '12-YA-2', 43),
(129, '2024-2025', 'SHS-ABM', '12-YA-3', 49),
(130, '2024-2025', 'SHS-ABM', '12-YA-4', 25),
(132, '2024-2025', 'SHS-ABM', '11-YA-1', 1),
(133, '2024-2025', 'SHS-ABMPLUS', '11-YA-1', 40),
(134, '2024-2025', 'SHS-ABMPLUS', '11-YA-2', 24),
(135, '2024-2025', 'SHS-ABMPLUS', '12-YA-1P', 40),
(136, '2024-2025', 'SHS-HUMSS', '11-YA-1', 42),
(137, '2024-2025', 'SHS-HUMSS', '11-YA-2', 41),
(138, '2024-2025', 'SHS-HUMSS', '11-YA-3', 34),
(139, '2024-2025', 'SHS-HUMSS', '12-YA-1', 48),
(140, '2024-2025', 'SHS-HUMSS', '12-YA-2', 49),
(141, '2024-2025', 'SHS-HUMSS', '12-YA-3', 47),
(142, '2024-2025', 'SHS-HUMSS', '12-YA-4', 32),
(143, '2024-2025', 'SHS-HUMSSPLUS', '11-YA-1', 54),
(144, '2024-2025', 'SHS-HUMSSPLUS', '12-YA-1', 45),
(145, '2024-2025', 'SHS-STEM', '11-YA-1', 49),
(146, '2024-2025', 'SHS-STEM', '11-YA-2', 50),
(147, '2024-2025', 'SHS-STEM', '11-YA-3', 48),
(148, '2024-2025', 'SHS-STEM', '11-YA-4', 49),
(149, '2024-2025', 'SHS-STEM', '11-YA-5', 50),
(150, '2024-2025', 'SHS-STEM', '11-YA-6', 53),
(151, '2024-2025', 'SHS-STEM', '11-YA-7', 49),
(152, '2024-2025', 'SHS-STEM', '11-YA-8', 49),
(153, '2024-2025', 'SHS-STEM', '11-YA-9', 49),
(154, '2024-2025', 'SHS-STEM', '12-YA-1', 50),
(155, '2024-2025', 'SHS-STEM', '12-YA-10', 51),
(156, '2024-2025', 'SHS-STEM', '12-YA-11', 50),
(157, '2024-2025', 'SHS-STEM', '12-YA-12', 51),
(158, '2024-2025', 'SHS-STEM', '12-YA-13', 49),
(159, '2024-2025', 'SHS-STEM', '12-YA-14', 51),
(160, '2024-2025', 'SHS-STEM', '12-YA-2', 51),
(161, '2024-2025', 'SHS-STEM', '12-YA-3', 50),
(162, '2024-2025', 'SHS-STEM', '12-YA-4', 51),
(163, '2024-2025', 'SHS-STEM', '12-YA-5', 50),
(164, '2024-2025', 'SHS-STEM', '12-YA-6', 51),
(165, '2024-2025', 'SHS-STEM', '12-YA-7', 49),
(166, '2024-2025', 'SHS-STEM', '12-YA-8', 51),
(167, '2024-2025', 'SHS-STEM', '12-YA-9', 50),
(168, '2024-2025', 'SHS-STEMPLUS', '11-YA-1', 45),
(169, '2024-2025', 'SHS-STEMPLUS', '11-YA-2', 46),
(170, '2024-2025', 'SHS-STEMPLUS', '11-YA-3', 46),
(171, '2024-2025', 'SHS-STEMPLUS', '11-YA-4', 47),
(172, '2024-2025', 'SHS-STEMPLUS', '11-YA-5', 47),
(173, '2024-2025', 'SHS-STEMPLUS', '11-YA-6', 46),
(174, '2024-2025', 'SHS-STEMPLUS', '11-YA-7', 47),
(175, '2024-2025', 'SHS-STEMPLUS', '12-YA-1P', 50),
(176, '2024-2025', 'SHS-STEMPLUS', '12-YA-2P', 51),
(177, '2024-2025', 'SHS-STEMPLUS', '12-YA-3P', 49),
(178, '2024-2025', 'SHS-STEMPLUS', '12-YA-4P', 30),
(179, '2024-2025', 'SHS-STEMPLUS', '12-YA-5P', 49),
(180, '2021-2022', 'SHS-QWE', '', 0),
(181, '2021-2022', 'SHS-STEM', '', 0),
(182, '2021-2022', 'SHS-STEM', '', 0),
(183, '2021-2022', 'SHS-STEMPLUS', '', 0),
(184, '2021-2022', 'SHS-HUMSSPLUS', '', 0),
(185, '2021-2022', 'SHS-HUMSS', '', 0),
(186, '2021-2022', 'SHS-ABM', '', 0),
(187, '2021-2022', 'SHS-ABM', '', 0),
(188, '2021-2022', 'SHS-STEM', '', 0),
(189, '2021-2022', 'SHS-STEM', '', 0),
(190, '2024-2025', 'SHS-STEM', '', 0),
(191, '2023-2024', 'SHS-ABM', '', 0);

--
-- Indexes for dumped tables
--

--
-- Indexes for table `facultyschedule`
--
ALTER TABLE `facultyschedule`
  ADD PRIMARY KEY (`schedule_id`);

--
-- Indexes for table `historical_data`
--
ALTER TABLE `historical_data`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `student_records`
--
ALTER TABLE `student_records`
  ADD PRIMARY KEY (`id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `facultyschedule`
--
ALTER TABLE `facultyschedule`
  MODIFY `schedule_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `historical_data`
--
ALTER TABLE `historical_data`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=43;

--
-- AUTO_INCREMENT for table `student_records`
--
ALTER TABLE `student_records`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=198;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
