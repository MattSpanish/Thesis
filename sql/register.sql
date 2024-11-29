-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Nov 29, 2024 at 10:31 AM
-- Server version: 10.4.28-MariaDB
-- PHP Version: 8.0.28

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
(33, 6, 'd754fa63d3e88149450cb9c8cf2d422b97758044e82e1e318bb8f1b0aeda5175', '2024-11-29 08:28:39');

-- --------------------------------------------------------

--
-- Table structure for table `schedule`
--

CREATE TABLE `schedule` (
  `id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `strand` varchar(255) NOT NULL,
  `time` varchar(255) NOT NULL,
  `days` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `schedule`
--

INSERT INTO `schedule` (`id`, `user_id`, `strand`, `time`, `days`) VALUES
(13, 2, 'Stem 4-YA-1', '10:00AM-12:00NN', 'Monday- Tueday');

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
(1, 'Fix computer', 3, '2024-11-18', 'complete', '2024-11-26 06:58:15'),
(2, 'Fix computer', 1, '2024-11-30', 'pending', '2024-11-26 06:58:50'),
(3, 'com', 2, '2024-11-30', 'complete', '2024-11-26 07:03:23'),
(4, 'Fix computer', 2, '2024-11-28', 'complete', '2024-11-26 08:20:57'),
(5, 'bilan mo kame kape', 2, '2024-11-26', 'pending', '2024-11-26 08:24:39'),
(6, 'bilan mo kame kape', 4, '2024-11-29', 'complete', '2024-11-26 08:29:15'),
(7, 'Fix computer', 4, '2024-11-26', 'complete', '2024-11-26 11:44:14'),
(8, '123123', 2, '2024-11-25', 'due', '2024-11-26 11:52:57'),
(9, 'midnight', 2, '2024-11-26', 'pending', '2024-11-26 11:59:12'),
(10, 'Welcome Task', 5, '2024-12-04', 'pending', '2024-11-27 11:19:05'),
(11, 'Welcome Task', 6, '2024-12-06', 'pending', '2024-11-29 06:28:18');

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
  `remember_token` varchar(64) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`id`, `fullname`, `username`, `email`, `password`, `profile_pic`, `reset_token`, `token_expiry`, `remember_token`) VALUES
(1, 'mak', 'guri', 'guriguri14@gmail.com', '$2y$10$hElzY2EMELqfZkfEOZ/A9uTLzXRknJvCCnctAAJJSkqKVMgvbzZta', '../uploads/profile_673b0fc1d383e0.75301373.jpg', NULL, NULL, NULL),
(2, 'Matthew Español', 'Matty', 'matthewrules08@gmail.com', '$2y$10$Vtytvlbsgi5EwPESp8EusupRS62CvEXv1YcXi3hYy6e7rxoUBIXeq', 'cy.jpg', 'ca2c344c0fcd7778cfa5df98dcc4c742824e229b4ea8a4cc1ef1f500c560e31da40c2b62fc7c663bd9bf6b63030eec8dcb58', '2024-11-27 13:17:50', NULL),
(3, 'Alysa Delgado', 'Elisa', 'alysa@gmail.com', '$2y$10$EA/AneP//baIu.9ayJwen.k9.tMo730zRNU402lj3gZIJb0KoQ.xq', 'ganda.jpg', NULL, NULL, NULL),
(4, 'Cymanuel Alon Alon', 'Cy', 'cymanuel@gmail.com', '$2y$10$gwgZT04U7dd6Aw2uQt6DyeZpnAcYjfbotL7LR556/j4mBS4vt8L.u', 'cy.jpg', NULL, NULL, NULL),
(5, 'JM', 'JM', 'jrespanol1485lag@student.fatima.edu.ph', '$2y$10$.CP1J87QFxCHWWHEEXF5XuvWiLz2NZRjI/tuPGcauosFDdOfYKtna', 'ganda.jpg', '8f968d705e533bd7a561630e2b9adc5e750efc9c55d5c43d71cf22bff4c35ec09cf641ae2bf9ff2a6dc07e96639bad4f6fd2', '2024-11-27 13:32:14', '06a0f23f06aaa89face2ce635ad0f15d4691efce846440c228a1701933b44187'),
(6, 'Santi Hukom', 'Santi', 'hukomsanti123@gmail.com', '$2y$10$RYvncSU7wIv64AziP2oey.OS29BLX.dpFU.AWUPZoNoBb36fOO/0y', NULL, NULL, NULL, NULL);

--
-- Indexes for dumped tables
--

--
-- Indexes for table `password_resets`
--
ALTER TABLE `password_resets`
  ADD PRIMARY KEY (`id`),
  ADD KEY `user_id` (`user_id`);

--
-- Indexes for table `schedule`
--
ALTER TABLE `schedule`
  ADD PRIMARY KEY (`id`),
  ADD KEY `user_id` (`user_id`);

--
-- Indexes for table `tasks`
--
ALTER TABLE `tasks`
  ADD PRIMARY KEY (`id`),
  ADD KEY `employee_id` (`employee_id`);

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
-- AUTO_INCREMENT for table `password_resets`
--
ALTER TABLE `password_resets`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=42;

--
-- AUTO_INCREMENT for table `schedule`
--
ALTER TABLE `schedule`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=14;

--
-- AUTO_INCREMENT for table `tasks`
--
ALTER TABLE `tasks`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=12;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `password_resets`
--
ALTER TABLE `password_resets`
  ADD CONSTRAINT `password_resets_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `schedule`
--
ALTER TABLE `schedule`
  ADD CONSTRAINT `schedule_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `tasks`
--
ALTER TABLE `tasks`
  ADD CONSTRAINT `tasks_ibfk_1` FOREIGN KEY (`employee_id`) REFERENCES `users` (`id`) ON DELETE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
