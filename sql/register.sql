-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Nov 28, 2024 at 09:53 AM
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
(1, 5, 'c2492ea2ccb8dc9b31ebc82defd22ddb862b207fbd31db8b17e4c234517dca65', '2024-11-27 13:37:44'),
(2, 5, 'aae790912da343ed5a23a900a04e5321ab31a96a274e11e029ac01bf2ab734bf', '2024-11-27 13:40:33'),
(3, 5, '1f07aed52e18810c12de7611c460a74ed04a6b972dfd4bbc71f8ee77c3cf2976', '2024-11-27 13:43:44'),
(4, 5, '7659418c43d0274d344cdb4fc4e599a8abdae28caf6bd71499a2021081d67c7c', '2024-11-27 13:45:38'),
(5, 2, 'a7b1fc7a1cd013f076d68e4eafc07462d94391855bca8620dd775be4c9e8fb9f', '2024-11-27 13:45:49'),
(6, 2, 'a0df7bf0f90447786f59381698bde4f8c905b1156c08be6cf16573d2fed68334', '2024-11-27 13:48:17'),
(7, 5, '61e183a77906951d45903680221751563e887e2b2854a7afe171078909fe9df1', '2024-11-27 13:48:26'),
(8, 5, '5421332ef70439aeac048e94805c9ce36bb6ebcc1092183f3781d33aafb4770a', '2024-11-27 13:56:03'),
(9, 2, '78a769756fecbe0b1391ace21e6750f7eb2b852d6b6d13be194b298351523fa0', '2024-11-27 13:57:13'),
(10, 2, '709f8d5d93899f3196038583222651a34390d2f5bc60a562d73019a0aa97bc2d', '2024-11-27 14:12:44'),
(11, 2, '90479fe31c07f6126a548d826bf7096ad43b4486af18ae42e35a26bbdadbf16f', '2024-11-27 14:38:26'),
(12, 2, '8788241bb33fd45f43830392ef155700f901550f0edfd26d8ca8d67a8d5b655c', '2024-11-27 14:44:36'),
(13, 2, '5d143ea997bf02c548c08ffaf4fa2d6b50a932d9090fe5b06ebd3522729d5d6a', '2024-11-27 14:45:24'),
(14, 2, '56d73459ca725005a2d1ead4bdcd42a8d38c9f6fd1e8aa66e4d35f4282eb4ab5', '2024-11-27 14:46:49');

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
(10, 'Welcome Task', 5, '2024-12-04', 'pending', '2024-11-27 11:19:05');

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
  `token_expiry` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`id`, `fullname`, `username`, `email`, `password`, `profile_pic`, `reset_token`, `token_expiry`) VALUES
(1, 'mak', 'guri', 'guriguri14@gmail.com', '$2y$10$hElzY2EMELqfZkfEOZ/A9uTLzXRknJvCCnctAAJJSkqKVMgvbzZta', '../uploads/profile_673b0fc1d383e0.75301373.jpg', NULL, NULL),
(2, 'Matthew Español', 'Matty', 'matthewrules08@gmail.com', '$2y$10$ayg3VSe9qnOpa72KuncdB.B2QSCx/EFVecVuO7PIsR3oqRWDxE0gC', 'cy.jpg', 'ca2c344c0fcd7778cfa5df98dcc4c742824e229b4ea8a4cc1ef1f500c560e31da40c2b62fc7c663bd9bf6b63030eec8dcb58', '2024-11-27 13:17:50'),
(3, 'Alysa Delgado', 'Elisa', 'alysa@gmail.com', '$2y$10$JkRFXMatc.8Ce4M1xW5oqu3wGa8vdtPr91osOM.cnP5ihQ9nJz3s6', 'ganda.jpg', NULL, NULL),
(4, 'Cymanuel Alon Alon', 'Cy', 'cymanuel@gmail.com', '$2y$10$gwgZT04U7dd6Aw2uQt6DyeZpnAcYjfbotL7LR556/j4mBS4vt8L.u', 'cy.jpg', NULL, NULL),
(5, 'JM', 'JM', 'jrespanol1485lag@student.fatima.edu.ph', '$2y$10$K/YFTKQ363o//xndXA2KXeYwQyu6uC8SV6ZwyeWE4uiRktLYQM2Gi', NULL, '8f968d705e533bd7a561630e2b9adc5e750efc9c55d5c43d71cf22bff4c35ec09cf641ae2bf9ff2a6dc07e96639bad4f6fd2', '2024-11-27 13:32:14');

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
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=15;

--
-- AUTO_INCREMENT for table `schedule`
--
ALTER TABLE `schedule`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=14;

--
-- AUTO_INCREMENT for table `tasks`
--
ALTER TABLE `tasks`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=11;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

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
