-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Apr 29, 2023 at 08:35 AM
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
-- Database: `niceadmin`
--

-- --------------------------------------------------------

--
-- Table structure for table `c_status_types`
--

CREATE TABLE `c_status_types` (
  `id` int(11) NOT NULL,
  `type_name` varchar(255) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `c_status_types`
--

INSERT INTO `c_status_types` (`id`, `type_name`) VALUES
(1, 'WIP'),
(2, 'Hold'),
(3, 'Closed'),
(4, 'Reassigned'),
(5, 'Ready For Development'),
(6, 'Analysis'),
(7, 'Code Review'),
(8, 'Awaiting Customer Feedback'),
(9, 'Reassigned Closed'),
(10, 'Code review rework 1'),
(11, 'Code review rework 2'),
(12, 'Code review rework 3'),
(13, 'Gamma Testing'),
(14, 'Ready For Gamma'),
(15, 'Ready For Isolated'),
(16, 'Ready for Live'),
(17, 'Ready to Merge'),
(18, 'UAT Testing'),
(19, 'Live Verify');

-- --------------------------------------------------------

--
-- Table structure for table `log_history`
--

CREATE TABLE `log_history` (
  `id` int(11) NOT NULL,
  `ticket_id` int(10) NOT NULL,
  `user_id` int(11) DEFAULT NULL,
  `dates` datetime NOT NULL,
  `hrs` varchar(100) NOT NULL,
  `c_status` int(11) NOT NULL,
  `what_is_done` text NOT NULL,
  `what_is_pending` text NOT NULL DEFAULT 'NA',
  `what_support_required` text NOT NULL DEFAULT 'NA',
  `created_at` datetime DEFAULT current_timestamp(),
  `updated_at` datetime DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `log_timing`
--

CREATE TABLE `log_timing` (
  `id` int(11) NOT NULL,
  `ticket_id` int(11) NOT NULL,
  `user_id` int(11) DEFAULT NULL,
  `assignee_id` int(10) DEFAULT NULL,
  `c_status` int(11) NOT NULL,
  `activity_type` varchar(200) DEFAULT NULL,
  `details` text DEFAULT NULL,
  `datetime` datetime NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `tickets`
--

CREATE TABLE `tickets` (
  `id` int(11) NOT NULL,
  `ticket_id` varchar(100) NOT NULL,
  `type_id` int(11) NOT NULL,
  `c_status` varchar(100) NOT NULL,
  `assignee_id` int(11) NOT NULL DEFAULT 0,
  `assigned_date` datetime DEFAULT NULL,
  `plan_start_date` datetime DEFAULT NULL,
  `plan_end_date` datetime DEFAULT NULL,
  `actual_start_date` datetime DEFAULT NULL,
  `actual_end_date` datetime DEFAULT NULL,
  `planned_hrs` float(11,2) DEFAULT NULL,
  `actual_hrs` float(11,2) DEFAULT NULL,
  `created_at` datetime NOT NULL DEFAULT current_timestamp(),
  `updated_at` datetime DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `ticket_types`
--

CREATE TABLE `ticket_types` (
  `id` int(11) NOT NULL,
  `type_name` varchar(255) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `ticket_types`
--

INSERT INTO `ticket_types` (`id`, `type_name`) VALUES
(1, 'Bug'),
(2, 'Story'),
(3, 'Task'),
(4, 'Integration');

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `id` int(10) NOT NULL,
  `username` varchar(200) NOT NULL,
  `fname` varchar(255) DEFAULT NULL,
  `lname` varchar(255) DEFAULT NULL,
  `user_type` int(10) NOT NULL COMMENT '1:PM,\r\n2:TL,\r\n3:Dev,\r\n4:QA',
  `employee_id` int(10) NOT NULL,
  `designation` varchar(100) NOT NULL,
  `email` varchar(200) NOT NULL,
  `password` varchar(255) NOT NULL,
  `created_at` datetime NOT NULL DEFAULT current_timestamp(),
  `updated_at` datetime NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `users`
-- password is either 123 or 1234
--

INSERT INTO `users` (`id`, `username`, `fname`, `lname`, `user_type`, `employee_id`, `designation`, `email`, `password`, `created_at`, `updated_at`) VALUES
(1, 'dhirajtekade', 'Dhiraj', 'Tekade', 3, 4762, 'full s', 'dhirajtek13@gmail.com', '$2y$10$R8NtjFXOLjDglT7wOVL14utYQJQFJPQxFws4t0WBCRX24aSal2dtG', '2023-04-25 17:32:13', '2023-04-28 17:17:43'),
(2, 'vumesh', 'Umesh', 'Verma', 1, 111, 'PM', 'vumesh@espire.com', '$2y$10$/pVJVAzUYkMsjEC0frHReOOeAqyzsSXqVZRsFonzPVg8Iz7Z0Ovky', '2023-04-25 20:52:17', '2023-04-25 20:52:17'),
(3, 'kamit', 'Amit', 'Karki', 2, 222, 'LE', 'kamit@espire.com', '$2y$10$/pVJVAzUYkMsjEC0frHReOOeAqyzsSXqVZRsFonzPVg8Iz7Z0Ovky', '2023-04-25 20:54:53', '2023-04-25 20:54:53'),
(4, 'upendra', 'Upendra', 'Prasad', 3, 4555, 'lead eng', 'u@email.com', '$2y$10$a0nE4VkNRE5qZS/sLXTjsOzs2mNrKb/d/x4ua1qK.sNzfg.1K61IS', '2023-04-27 13:36:54', '2023-04-27 13:36:54');

-- --------------------------------------------------------

--
-- Table structure for table `user_type`
--

CREATE TABLE `user_type` (
  `id` int(10) NOT NULL,
  `type_name` varchar(200) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `user_type`
--

INSERT INTO `user_type` (`id`, `type_name`) VALUES
(1, 'PM'),
(2, 'TL'),
(3, 'Dev'),
(4, 'QA');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `c_status_types`
--
ALTER TABLE `c_status_types`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `log_history`
--
ALTER TABLE `log_history`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `log_timing`
--
ALTER TABLE `log_timing`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `tickets`
--
ALTER TABLE `tickets`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `ticket_types`
--
ALTER TABLE `ticket_types`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `username` (`username`);

--
-- Indexes for table `user_type`
--
ALTER TABLE `user_type`
  ADD PRIMARY KEY (`id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `c_status_types`
--
ALTER TABLE `c_status_types`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=20;

--
-- AUTO_INCREMENT for table `log_history`
--
ALTER TABLE `log_history`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `log_timing`
--
ALTER TABLE `log_timing`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `tickets`
--
ALTER TABLE `tickets`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `ticket_types`
--
ALTER TABLE `ticket_types`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `id` int(10) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT for table `user_type`
--
ALTER TABLE `user_type`
  MODIFY `id` int(10) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
