-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Nov 21, 2024 at 03:57 PM
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
-- Database: `coa_assignment_tracking`
--

-- --------------------------------------------------------

--
-- Table structure for table `activities`
--

CREATE TABLE `activities` (
  `id` int(11) NOT NULL,
  `name` varchar(255) NOT NULL,
  `description` text DEFAULT NULL,
  `duration` int(11) NOT NULL,
  `total_days` int(11) NOT NULL,
  `percentage` decimal(5,2) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `activities`
--

INSERT INTO `activities` (`id`, `name`, `description`, `duration`, `total_days`, `percentage`) VALUES
(1, 'PRELIMINARY REVIEW', 'EDSE, Facts of the Case, Documentary Requirement, Coordination Meeting w/ Agency\'s ATL', 5, 10, 10.00),
(2, 'TECHNICAL EVALUATION', 'Initial Evaluation, Criteria for Technical Evaluation', 10, 15, 20.00),
(3, 'TECHNICAL INSPECTION', 'Field Work, Verification of Accomplishments', 10, 15, 15.00),
(4, 'COMPUTATION', 'Cost Comparison, Quantity Take-off', 30, 30, 25.00),
(5, 'DRAFT OF FINAL REPORT', 'Technical Evaluation Report for Money Claim (incl. Contract Review and Technical Inspection)', 15, 20, 25.00),
(6, 'FINALIZATION', 'Correction from Section Chief, Division Chief and Directors', 3, 5, 3.00),
(7, 'RELEASED', 'Report Released from TAG-H', 2, 3, 2.00);

-- --------------------------------------------------------

--
-- Table structure for table `designations`
--

CREATE TABLE `designations` (
  `id` int(11) NOT NULL,
  `designations_name` varchar(255) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `designations`
--

INSERT INTO `designations` (`id`, `designations_name`) VALUES
(5, 'AO IV'),
(1, 'STAA II'),
(2, 'STAS I'),
(3, 'STAS II'),
(4, 'STAS III');

-- --------------------------------------------------------

--
-- Table structure for table `project_list`
--

CREATE TABLE `project_list` (
  `id` int(11) NOT NULL,
  `contractor_project` varchar(255) NOT NULL,
  `user_id` int(11) NOT NULL,
  `collaboration_id` int(11) NOT NULL,
  `subjects` text DEFAULT NULL,
  `document_no` varchar(255) DEFAULT NULL,
  `date_received` date DEFAULT NULL,
  `date_assigned` date DEFAULT NULL,
  `agency` varchar(255) DEFAULT NULL,
  `supervisor` varchar(255) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `roles`
--

CREATE TABLE `roles` (
  `id` int(11) NOT NULL,
  `role_name` varchar(50) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `roles`
--

INSERT INTO `roles` (`id`, `role_name`) VALUES
(1, 'admin'),
(2, 'receptionist'),
(3, 'staff');

-- --------------------------------------------------------

--
-- Table structure for table `sections`
--

CREATE TABLE `sections` (
  `id` int(11) NOT NULL,
  `section_name` varchar(255) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `sections`
--

INSERT INTO `sections` (`id`, `section_name`) VALUES
(1, 'Section A'),
(2, 'Section B');

-- --------------------------------------------------------

--
-- Table structure for table `section_heads`
--

CREATE TABLE `section_heads` (
  `id` int(11) NOT NULL,
  `section_heads_name` varchar(255) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `section_heads`
--

INSERT INTO `section_heads` (`id`, `section_heads_name`) VALUES
(7, 'ABAL'),
(2, 'ABB'),
(3, 'BAAS'),
(14, 'CKAP'),
(10, 'DBPE'),
(11, 'GCDG'),
(15, 'JMG'),
(12, 'JPD'),
(13, 'JRVM'),
(8, 'LEE'),
(9, 'LSNG'),
(6, 'PJTP'),
(5, 'RGF'),
(1, 'RLM'),
(4, 'RMG');

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `id` int(11) NOT NULL,
  `name` varchar(100) NOT NULL,
  `email` varchar(100) NOT NULL,
  `number` varchar(15) DEFAULT NULL,
  `position` varchar(100) DEFAULT NULL,
  `action_officer` varchar(100) DEFAULT NULL,
  `birthday` date DEFAULT NULL,
  `password` varchar(255) NOT NULL,
  `otp` int(6) DEFAULT NULL,
  `otp_expiry` int(11) DEFAULT NULL,
  `section_heads_id` int(11) DEFAULT NULL,
  `designation_id` int(11) DEFAULT NULL,
  `section_id` int(11) DEFAULT NULL,
  `role_id` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`id`, `name`, `email`, `number`, `position`, `action_officer`, `birthday`, `password`, `otp`, `otp_expiry`, `section_heads_id`, `designation_id`, `section_id`, `role_id`) VALUES
(18, 'Devgil', 'devgiltechnicalsolutions@gmail.com', '09165298478', 'developer', NULL, '2000-10-01', '$2y$10$4XIwQloEf8JtQKoKADjb4O2IMDtI9mXMwuzpxrwKbPJ2a/pbG7FWi', NULL, NULL, 3, 5, 1, 1),
(19, 'jeril', 'jeril@gmail.com', '56456456', 'dev', NULL, '2024-11-17', '$2y$10$qyBiVYIJNLWZ4oITraVZI.Yt/7oDitAMR/X0aMzIkm6vPW24vvzAG', NULL, NULL, 2, 5, 2, 3),
(20, 'test', 'test@gmail.com', '546', 'uawd', NULL, '2024-11-17', '$2y$10$201uXzIjwF0t1bSiBeYVkOHGo3OVUhw4d6fQvRsJIAsoyk0j6BF7m', NULL, NULL, 7, 5, 1, 2),
(21, 'john', 'john@gmail.com', '45345', 'dev', NULL, '2024-11-19', '$2y$10$gWc/2k5aRuy1P5ScPMJeDutmy7tZrgjJze24OF0iEzm/t0EhVFUhK', NULL, NULL, 7, 5, 1, 1),
(22, 'test2', 'test2@gmail.com', '456456', 'adawd', NULL, '2024-11-21', '$2y$10$/uob0tNOYBfd6oDYK0hkfuc.sV6GfOHtJvPi0Uotjvwz6/eoDmeDe', NULL, NULL, 7, 1, 2, 1),
(23, 'test3', 'test3@gmail.com', '567567567', 'awd', NULL, '2024-11-20', '$2y$10$oW1V7UfPAN6t4c78eh/.u.OmzEVJhwsjWX0lGh5le/cbMi7WqCFzC', NULL, NULL, 7, 5, 2, 3);

-- --------------------------------------------------------

--
-- Table structure for table `user_task_collaboration`
--

CREATE TABLE `user_task_collaboration` (
  `id` int(11) NOT NULL,
  `task_id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `user_task_collaboration`
--

INSERT INTO `user_task_collaboration` (`id`, `task_id`, `user_id`) VALUES
(302, 49, 22);

-- --------------------------------------------------------

--
-- Table structure for table `user_task_list`
--

CREATE TABLE `user_task_list` (
  `id` int(11) NOT NULL,
  `project_code_name` varchar(255) NOT NULL,
  `remarks_personnel` text DEFAULT NULL,
  `collaboration` varchar(255) DEFAULT NULL,
  `section_head` int(11) DEFAULT NULL,
  `start_date` datetime NOT NULL,
  `due_date` datetime NOT NULL,
  `day_left` int(11) GENERATED ALWAYS AS (to_days(`due_date`) - to_days(current_timestamp())) VIRTUAL,
  `comments_supervisor` text DEFAULT NULL,
  `progress` enum('Not Started','On Going','For Approval','For Correction','Submitted Memo for DocRec','Done') NOT NULL,
  `user_id` int(11) NOT NULL,
  `activity_id` int(11) NOT NULL,
  `last_updated_by` int(11) DEFAULT NULL,
  `updated_at` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `user_task_list`
--

INSERT INTO `user_task_list` (`id`, `project_code_name`, `remarks_personnel`, `collaboration`, `section_head`, `start_date`, `due_date`, `comments_supervisor`, `progress`, `user_id`, `activity_id`, `last_updated_by`, `updated_at`) VALUES
(49, 'test', 'awdawdaawd', '18,22', 3, '2024-11-21 00:00:00', '2024-11-28 00:00:00', '', 'Not Started', 22, 1, 1, NULL);

--
-- Indexes for dumped tables
--

--
-- Indexes for table `activities`
--
ALTER TABLE `activities`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `designations`
--
ALTER TABLE `designations`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `name` (`designations_name`);

--
-- Indexes for table `project_list`
--
ALTER TABLE `project_list`
  ADD PRIMARY KEY (`id`),
  ADD KEY `user_id` (`user_id`),
  ADD KEY `collaboration_id` (`collaboration_id`);

--
-- Indexes for table `roles`
--
ALTER TABLE `roles`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `role_name` (`role_name`);

--
-- Indexes for table `sections`
--
ALTER TABLE `sections`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `name` (`section_name`);

--
-- Indexes for table `section_heads`
--
ALTER TABLE `section_heads`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `name` (`section_heads_name`);

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `email` (`email`),
  ADD KEY `section_heads_id` (`section_heads_id`),
  ADD KEY `designation_id` (`designation_id`),
  ADD KEY `section_id` (`section_id`),
  ADD KEY `role_id` (`role_id`);

--
-- Indexes for table `user_task_collaboration`
--
ALTER TABLE `user_task_collaboration`
  ADD PRIMARY KEY (`id`),
  ADD KEY `task_id` (`task_id`);

--
-- Indexes for table `user_task_list`
--
ALTER TABLE `user_task_list`
  ADD PRIMARY KEY (`id`),
  ADD KEY `user_id` (`user_id`),
  ADD KEY `fk_activity` (`activity_id`),
  ADD KEY `fk_section_head` (`section_head`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `activities`
--
ALTER TABLE `activities`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;

--
-- AUTO_INCREMENT for table `designations`
--
ALTER TABLE `designations`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT for table `project_list`
--
ALTER TABLE `project_list`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `roles`
--
ALTER TABLE `roles`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `sections`
--
ALTER TABLE `sections`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `section_heads`
--
ALTER TABLE `section_heads`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=17;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=24;

--
-- AUTO_INCREMENT for table `user_task_collaboration`
--
ALTER TABLE `user_task_collaboration`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=303;

--
-- AUTO_INCREMENT for table `user_task_list`
--
ALTER TABLE `user_task_list`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=50;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `project_list`
--
ALTER TABLE `project_list`
  ADD CONSTRAINT `project_list_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `project_list_ibfk_2` FOREIGN KEY (`collaboration_id`) REFERENCES `user_task_collaboration` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `users`
--
ALTER TABLE `users`
  ADD CONSTRAINT `users_ibfk_1` FOREIGN KEY (`section_heads_id`) REFERENCES `section_heads` (`id`),
  ADD CONSTRAINT `users_ibfk_2` FOREIGN KEY (`designation_id`) REFERENCES `designations` (`id`),
  ADD CONSTRAINT `users_ibfk_3` FOREIGN KEY (`section_id`) REFERENCES `sections` (`id`),
  ADD CONSTRAINT `users_ibfk_4` FOREIGN KEY (`role_id`) REFERENCES `roles` (`id`);

--
-- Constraints for table `user_task_collaboration`
--
ALTER TABLE `user_task_collaboration`
  ADD CONSTRAINT `user_task_collaboration_ibfk_1` FOREIGN KEY (`task_id`) REFERENCES `user_task_list` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `user_task_list`
--
ALTER TABLE `user_task_list`
  ADD CONSTRAINT `fk_activity` FOREIGN KEY (`activity_id`) REFERENCES `activities` (`id`),
  ADD CONSTRAINT `fk_section_head` FOREIGN KEY (`section_head`) REFERENCES `section_heads` (`id`),
  ADD CONSTRAINT `user_task_list_ibfk_1` FOREIGN KEY (`section_head`) REFERENCES `section_heads` (`id`),
  ADD CONSTRAINT `user_task_list_ibfk_2` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
