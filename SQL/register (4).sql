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
-- Database: `register`
--

-- --------------------------------------------------------

--
-- Table structure for table `faculty_responses`
--

CREATE TABLE `faculty_responses` (
  `id` int(11) NOT NULL,
  `message_id` int(11) NOT NULL,
  `response` text NOT NULL,
  `created_at` datetime DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `faculty_responses`
--

INSERT INTO `faculty_responses` (`id`, `message_id`, `response`, `created_at`) VALUES
(16, 1, 'what is it ?', '2024-12-20 15:13:02'),
(17, 1, 'approved', '2024-12-20 17:01:52');

-- --------------------------------------------------------

--
-- Table structure for table `otp_requests`
--

CREATE TABLE `otp_requests` (
  `id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `email` varchar(255) NOT NULL,
  `otp` varchar(6) NOT NULL,
  `created_at` datetime DEFAULT current_timestamp(),
  `expires_at` datetime NOT NULL,
  `is_used` tinyint(1) DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `password_resets`
--

CREATE TABLE `password_resets` (
  `id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `token` varchar(64) NOT NULL,
  `expiry` datetime NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `password_resets`
--

INSERT INTO `password_resets` (`id`, `user_id`, `token`, `expiry`) VALUES
(33, 6, 'd754fa63d3e88149450cb9c8cf2d422b97758044e82e1e318bb8f1b0aeda5175', '2024-11-29 08:28:39'),
(43, 1, '53538147c502ac24a4d98166307a2f99fdc376da5571adc3e3f00400e9893c19', '2024-12-19 21:19:45');

-- --------------------------------------------------------

--
-- Table structure for table `tasks`
--

CREATE TABLE `tasks` (
  `id` int(11) NOT NULL,
  `task_name` varchar(255) NOT NULL,
  `employee_id` int(11) NOT NULL,
  `due_date` date NOT NULL,
  `status` enum('complete','pending','due') NOT NULL DEFAULT 'pending',
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `tasks`
--

INSERT INTO `tasks` (`id`, `task_name`, `employee_id`, `due_date`, `status`, `created_at`) VALUES
(1, 'Proctor to STEM on Exam Day', 1, '2024-12-21', 'complete', '2024-12-19 19:26:48'),
(2, 'Proctor to ABM', 1, '2024-12-20', 'pending', '2024-12-20 07:15:20'),
(3, 'Proctor to hums', 1, '2024-12-21', 'pending', '2024-12-20 09:00:59'),
(4, 'Welcome Task', 2, '2024-12-27', 'complete', '2024-12-20 09:04:33');

-- --------------------------------------------------------

--
-- Table structure for table `time_logs`
--

CREATE TABLE `time_logs` (
  `id` int(11) NOT NULL,
  `fullname` varchar(255) NOT NULL,
  `date` date NOT NULL,
  `time_in` time NOT NULL,
  `time_out` time NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `time_logs`
--

INSERT INTO `time_logs` (`id`, `fullname`, `date`, `time_in`, `time_out`, `created_at`) VALUES
(1, 'JM', '2024-12-16', '09:46:00', '11:46:00', '2024-12-16 01:46:58'),
(2, 'JM', '2024-12-16', '10:33:00', '12:33:00', '2024-12-16 02:33:45'),
(3, 'JM', '2024-12-16', '10:43:00', '12:43:00', '2024-12-16 02:43:15'),
(4, 'JM', '2024-12-16', '10:47:00', '12:47:00', '2024-12-16 02:47:13'),
(5, 'Matthew espanol', '2024-12-18', '13:29:00', '15:29:00', '2024-12-18 05:30:03'),
(6, 'Matthew espanol', '2024-12-19', '22:01:00', '23:01:00', '2024-12-19 14:02:01'),
(7, 'Matthew espanol', '2024-12-20', '07:31:00', '10:32:00', '2024-12-19 19:32:17'),
(8, 'Nic Parreno', '2024-12-20', '17:06:00', '20:06:00', '2024-12-20 09:06:38');

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `id` int(11) NOT NULL,
  `fullname` varchar(255) NOT NULL,
  `username` varchar(255) NOT NULL,
  `email` varchar(255) NOT NULL,
  `password` varchar(255) NOT NULL,
  `profile_pic` varchar(255) DEFAULT NULL,
  `reset_token` varchar(255) DEFAULT NULL,
  `token_expiry` datetime DEFAULT NULL,
  `remember_token` varchar(64) DEFAULT NULL,
  `gender` varchar(10) DEFAULT NULL,
  `department` varchar(50) DEFAULT NULL,
  `subject` varchar(50) DEFAULT NULL,
  `status` varchar(20) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`id`, `fullname`, `username`, `email`, `password`, `profile_pic`, `reset_token`, `token_expiry`, `remember_token`, `gender`, `department`, `subject`, `status`) VALUES
(1, 'Matthew espanol', 'Matty', 'jrespanol1485lag@student.fatima.edu.ph', '$2y$10$W/hjnK4vUBX4.Jsttbi2A.wAhhIvK1AuwlknD5oecBB6m9M/pUgPG', 'profile_673b0fc1d383e0.75301373.jpg', NULL, NULL, NULL, 'Male', 'Senior High School', 'STEM', 'Fulltime'),
(2, 'Nic Parreno', 'Nic', 'nmparreno1258lag@student.fatima.edu.ph', '$2y$10$R3hqLUMySJFZxFBadruWYehmtXTntbFUBG06OETic6FoczovP3xVi', 'GEDC0082.JPG', NULL, NULL, NULL, 'Female', 'Senior High School', 'STEM', 'ACTIVE');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `faculty_responses`
--
ALTER TABLE `faculty_responses`
  ADD PRIMARY KEY (`id`),
  ADD KEY `message_id` (`message_id`);

--
-- Indexes for table `otp_requests`
--
ALTER TABLE `otp_requests`
  ADD PRIMARY KEY (`id`),
  ADD KEY `user_id` (`user_id`);

--
-- Indexes for table `password_resets`
--
ALTER TABLE `password_resets`
  ADD PRIMARY KEY (`id`),
  ADD KEY `user_id` (`user_id`);

--
-- Indexes for table `tasks`
--
ALTER TABLE `tasks`
  ADD PRIMARY KEY (`id`),
  ADD KEY `employee_id` (`employee_id`);

--
-- Indexes for table `time_logs`
--
ALTER TABLE `time_logs`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `email` (`email`),
  ADD UNIQUE KEY `username` (`username`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `faculty_responses`
--
ALTER TABLE `faculty_responses`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=18;

--
-- AUTO_INCREMENT for table `otp_requests`
--
ALTER TABLE `otp_requests`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `password_resets`
--
ALTER TABLE `password_resets`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=44;

--
-- AUTO_INCREMENT for table `tasks`
--
ALTER TABLE `tasks`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT for table `time_logs`
--
ALTER TABLE `time_logs`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=9;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `faculty_responses`
--
ALTER TABLE `faculty_responses`
  ADD CONSTRAINT `faculty_responses_ibfk_1` FOREIGN KEY (`message_id`) REFERENCES `hr_data`.`messages` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `otp_requests`
--
ALTER TABLE `otp_requests`
  ADD CONSTRAINT `otp_requests_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `password_resets`
--
ALTER TABLE `password_resets`
  ADD CONSTRAINT `password_resets_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `tasks`
--
ALTER TABLE `tasks`
  ADD CONSTRAINT `tasks_ibfk_1` FOREIGN KEY (`employee_id`) REFERENCES `users` (`id`) ON DELETE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
