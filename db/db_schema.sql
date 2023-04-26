-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Apr 21, 2023 at 10:17 AM
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
-- Database: `bt`
--

-- --------------------------------------------------------

--
-- Table structure for table `assignees`
--

CREATE TABLE `assignees` (
  `id` int(11) NOT NULL,
  `name` varchar(255) NOT NULL,
  `roll_id` int(11) NOT NULL,
  `status` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `assignees`
--

INSERT INTO `assignees` (`id`, `name`, `roll_id`, `status`) VALUES
(1, 'No Assignee', 0, 0),
(2, 'Amit', 1, 1),
(3, 'Upendra', 2, 1);

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
(4, 'Reassigned');

-- --------------------------------------------------------

--
-- Table structure for table `log_history`
--

CREATE TABLE `log_history` (
  `id` int(11) NOT NULL,
  `ticket_id` int(10) NOT NULL,
  `dates` datetime NOT NULL,
  `hrs` varchar(100) NOT NULL,
  `c_status` int(11) NOT NULL,
  `what_is_done` text NOT NULL,
  `what_is_pending` text NOT NULL DEFAULT 'NA',
  `what_support_required` text NOT NULL DEFAULT 'NA'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `log_history`
--

INSERT INTO `log_history` (`id`, `ticket_id`, `dates`, `hrs`, `c_status`, `what_is_done`, `what_is_pending`, `what_support_required`) VALUES
(1, 9, '2023-04-12 00:00:00', '12', 2, 'ffslfjf sdf\r\nsfsf', ' sdfsfsdfsfds dfsfd', 'NA'),
(2, 5, '2023-04-11 00:00:00', '11', 4, 'dgdfgdf', 'NA1', 'NA'),
(3, 5, '2023-04-12 00:00:00', '2', 2, 'dgdfgdf', 'aajlksdjd', 'NA'),
(4, 9, '2023-04-12 00:00:00', '11', 1, 'this is it', 'NA', 'NA'),
(5, 9, '2023-04-12 00:00:00', '11', 3, 'this is closed', 'NA', ''),
(6, 9, '2023-04-20 00:00:00', '11', 3, 'again closed', 'NA', 'NA'),
(7, 9, '2023-04-12 00:00:00', '11', 3, 'closss', 'NA', 'NA'),
(8, 9, '2023-04-12 00:00:00', '11', 3, 'this', 'NA', 'NA'),
(9, 0, '2023-04-12 00:00:00', '12', 4, 'ss11', 'NA11', 'NA11');

-- --------------------------------------------------------

--
-- Table structure for table `log_timing`
--

CREATE TABLE `log_timing` (
  `id` int(11) NOT NULL,
  `ticket_id` int(11) NOT NULL,
  `status` int(11) NOT NULL,
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
  `created_at` datetime DEFAULT current_timestamp(),
  `updated_at` datetime DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `tickets`
--

INSERT INTO `tickets` (`id`, `ticket_id`, `type_id`, `c_status`, `assignee_id`, `assigned_date`, `plan_start_date`, `plan_end_date`, `actual_start_date`, `actual_end_date`, `planned_hrs`, `actual_hrs`, `created_at`, `updated_at`) VALUES
(1, '16750', 2, '2', 4, '2023-04-01 20:58:30', '2023-04-02 20:58:41', '2023-04-03 20:58:47', '2023-04-04 20:58:51', '2023-04-05 20:58:56', 20.20, 11.50, NULL, '2023-04-21 12:26:46'),
(5, '6768', 2, '2', 1, '0000-00-00 00:00:00', '0000-00-00 00:00:00', '0000-00-00 00:00:00', '0000-00-00 00:00:00', '0000-00-00 00:00:00', 11.00, 2.00, NULL, '2023-04-21 11:43:42'),
(7, '545345', 2, '1', 1, '2023-04-18 00:00:00', '0000-00-00 00:00:00', '0000-00-00 00:00:00', '2023-04-24 00:00:00', '0000-00-00 00:00:00', 0.00, 0.00, NULL, '2023-04-21 11:43:42'),
(8, '234234', 1, '1', 1, '2023-04-12 00:00:00', '0000-00-00 00:00:00', '0000-00-00 00:00:00', '0000-00-00 00:00:00', '0000-00-00 00:00:00', 0.00, 0.00, NULL, '2023-04-21 11:43:42'),
(9, 'TCI-123', 1, '1', 1, '0000-00-00 00:00:00', '0000-00-00 00:00:00', '0000-00-00 00:00:00', '0000-00-00 00:00:00', '0000-00-00 00:00:00', 75.00, 100.00, NULL, '2023-04-21 11:43:42'),
(10, '11zx', 1, '1', 1, '0000-00-00 00:00:00', '0000-00-00 00:00:00', '0000-00-00 00:00:00', '0000-00-00 00:00:00', '0000-00-00 00:00:00', 0.00, 0.00, NULL, '2023-04-21 11:43:42'),
(11, 'ssds', 1, '1', 1, '0000-00-00 00:00:00', '0000-00-00 00:00:00', '0000-00-00 00:00:00', '0000-00-00 00:00:00', '0000-00-00 00:00:00', 0.00, 0.00, NULL, '2023-04-21 11:43:42'),
(12, '3434345', 1, '1', 4, '0000-00-00 00:00:00', '0000-00-00 00:00:00', '0000-00-00 00:00:00', '0000-00-00 00:00:00', '0000-00-00 00:00:00', 11.00, 4.00, '2023-04-21 12:04:21', '2023-04-21 12:04:21'),
(13, 'ee', 1, '1', 1, '0000-00-00 00:00:00', '0000-00-00 00:00:00', '0000-00-00 00:00:00', '0000-00-00 00:00:00', '0000-00-00 00:00:00', 0.00, 0.00, '2023-04-21 12:46:29', '2023-04-21 12:46:29'),
(14, 'xxx', 1, '1', 1, '0000-00-00 00:00:00', '0000-00-00 00:00:00', '0000-00-00 00:00:00', '0000-00-00 00:00:00', '0000-00-00 00:00:00', 0.00, 0.00, '2023-04-21 12:46:47', '2023-04-21 12:46:47'),
(15, 'asg', 2, '1', 1, '0000-00-00 00:00:00', '0000-00-00 00:00:00', '0000-00-00 00:00:00', '0000-00-00 00:00:00', '0000-00-00 00:00:00', 11.00, 2.00, '2023-04-21 13:05:32', '2023-04-21 13:05:32');

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
(2, 'Story');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `assignees`
--
ALTER TABLE `assignees`
  ADD PRIMARY KEY (`id`);

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
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `assignees`
--
ALTER TABLE `assignees`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT for table `c_status_types`
--
ALTER TABLE `c_status_types`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT for table `log_history`
--
ALTER TABLE `log_history`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=10;

--
-- AUTO_INCREMENT for table `log_timing`
--
ALTER TABLE `log_timing`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `tickets`
--
ALTER TABLE `tickets`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=16;

--
-- AUTO_INCREMENT for table `ticket_types`
--
ALTER TABLE `ticket_types`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
