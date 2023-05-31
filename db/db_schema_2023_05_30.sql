-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: May 30, 2023 at 12:50 PM
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
-- Table structure for table `configurations`
--

CREATE TABLE `configurations` (
  `id` int(10) NOT NULL,
  `name` varchar(250) NOT NULL,
  `type` varchar(250) DEFAULT NULL,
  `label` varchar(250) DEFAULT NULL,
  `value1` varchar(250) DEFAULT NULL,
  `value2` varchar(250) DEFAULT NULL,
  `project_id` int(10) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `configurations`
--

INSERT INTO `configurations` (`id`, `name`, `type`, `label`, `value1`, `value2`, `project_id`) VALUES
(1, 'actual_hrs', 'number', 'Actual Hrs of the day', '7', NULL, 2),
(3, 'kpi_c_status_types', 'select', 'Ticket status for KPI (OTD)', '3,7', NULL, 2),
(4, 'ftr_c_status_types', 'select', 'FTR', '10,11,12', NULL, 2),
(6, 'ticket_status_c_status_types', 'select', 'Ticket Status columns', '1,2,3,6,7,8', NULL, 2);

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
-- Table structure for table `kpis`
--

CREATE TABLE `kpis` (
  `id` int(10) NOT NULL,
  `kpi_name` varchar(250) NOT NULL,
  `service_level` text DEFAULT NULL,
  `description` text DEFAULT NULL,
  `target_operator` enum('','<','>','<=','>=','=') DEFAULT NULL,
  `target_value` varchar(200) DEFAULT NULL,
  `project_id` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `kpis`
--

INSERT INTO `kpis` (`id`, `kpi_name`, `service_level`, `description`, `target_operator`, `target_value`, `project_id`) VALUES
(1, 'OTD', '% Tasks completed within defined timelines', '- No. of Tasks delivered\n- new update', '>=', '90', 2),
(2, 'ODD', '', '', '>=', '90', 2),
(3, 'productivity', '', '', '<=', '+10', NULL);

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

--
-- Dumping data for table `log_history`
--

INSERT INTO `log_history` (`id`, `ticket_id`, `user_id`, `dates`, `hrs`, `c_status`, `what_is_done`, `what_is_pending`, `what_support_required`, `created_at`, `updated_at`) VALUES
(1, 1, 3, '2023-05-09 15:36:00', '3', 1, 'done some work', 'NA', 'NA', '2023-05-11 15:36:23', '2023-05-11 15:36:23'),
(2, 2, 3, '2023-05-10 15:38:00', '4', 1, 'work osomethingg', 'NA', 'NA', '2023-05-11 15:39:20', '2023-05-11 15:39:20'),
(3, 1, 3, '2023-05-10 15:47:00', '5.4', 3, 'another log', 'NA', 'NA', '2023-05-11 15:47:25', '2023-05-12 13:19:21'),
(4, 3, 4, '2023-05-10 16:12:00', '7', 1, 'work done 1', 'NA', 'NA', '2023-05-11 16:12:54', '2023-05-11 16:12:54'),
(5, 4, 1, '2023-05-03 20:15:00', '5', 1, 'this is done', 'NA', 'NA', '2023-05-11 20:16:15', '2023-05-11 20:16:15'),
(6, 2, 3, '2023-05-11 12:56:00', '11', 1, '11 tarikh', 'NA', 'NA', '2023-05-12 12:57:01', '2023-05-12 12:57:01'),
(7, 2, 3, '2023-05-12 12:57:00', '12', 1, '12 tarikh', 'NA', 'NA', '2023-05-12 12:57:12', '2023-05-12 12:57:12'),
(8, 5, 1, '2023-05-12 20:01:00', '9', 1, 'doon', 'NA', 'NA', '2023-05-12 20:01:54', '2023-05-12 20:10:38'),
(9, 6, 1, '2023-05-15 15:01:00', '5', 1, 'done some part in this', 'need to edevug', 'NA', '2023-05-15 15:01:36', '2023-05-15 15:01:36'),
(10, 3, 4, '2023-05-23 15:50:00', '5', 1, 'work done ', 'NA', 'NA', '2023-05-23 15:51:05', '2023-05-23 15:51:05'),
(11, 3, 4, '2023-05-23 15:50:00', '5', 1, 'work done ', 'NA', 'NA', '2023-05-23 15:52:00', '2023-05-23 15:52:00'),
(12, 3, 4, '2023-05-23 15:50:00', '5', 1, 'work done ', 'NA', 'NA', '2023-05-23 15:53:00', '2023-05-23 15:53:00'),
(13, 3, 4, '2023-05-23 15:50:00', '5', 1, 'work done ', 'NA', 'NA', '2023-05-23 15:53:09', '2023-05-23 15:53:09'),
(14, 3, 4, '2023-05-23 15:56:00', '10', 1, 'NA', 'NA', 'NA', '2023-05-23 15:56:19', '2023-05-23 15:56:19');

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
  `remark` varchar(255) DEFAULT NULL,
  `activity_type` varchar(200) DEFAULT NULL,
  `details` text DEFAULT NULL,
  `datetime` datetime NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `log_timing`
--

INSERT INTO `log_timing` (`id`, `ticket_id`, `user_id`, `assignee_id`, `c_status`, `remark`, `activity_type`, `details`, `datetime`) VALUES
(1, 0, 2, 3, 5, NULL, 'ADD_TICKET', NULL, '2023-05-11 15:23:21'),
(2, 1, 3, 3, 1, NULL, 'UPDATE_TICKET', NULL, '2023-05-11 15:24:53'),
(3, 1, 3, 3, 1, '', 'ADD_LOG', '[\"1\",\"t1\",\"2023-05-11T15:36\",\"3\",\"1\",\"done some work\",\"\",\"\",\"0\",\"\",\"0\",\"0\"]', '2023-05-11 15:36:23'),
(4, 0, 2, 3, 5, NULL, 'ADD_TICKET', NULL, '2023-05-11 15:38:13'),
(5, 2, 3, 3, 1, NULL, 'UPDATE_TICKET', NULL, '2023-05-11 15:38:52'),
(6, 2, 3, 3, 1, '', 'ADD_LOG', '[\"2\",\"t2\",\"2023-05-11T15:38\",\"4\",\"1\",\"work osomethingg\",\"\",\"\",\"0\",\"\",\"0\",\"0\"]', '2023-05-11 15:39:20'),
(7, 1, 3, 3, 1, '', 'ADD_LOG', '[\"1\",\"t1\",\"2023-05-11T15:47\",\"4.5\",\"1\",\"another log\",\"\",\"\",\"0\",\"\",\"0\",\"0\"]', '2023-05-11 15:47:25'),
(8, 0, 2, 4, 5, NULL, 'ADD_TICKET', NULL, '2023-05-11 16:10:06'),
(9, 3, 4, 4, 1, NULL, 'UPDATE_TICKET', NULL, '2023-05-11 16:10:36'),
(10, 3, 4, 4, 1, '', 'ADD_LOG', '[\"3\",\"t3\",\"2023-05-11T16:12\",\"7\",\"1\",\"work done 1\",\"\",\"\",\"0\",\"\",\"0\",\"0\"]', '2023-05-11 16:12:54'),
(11, 0, 2, 1, 5, NULL, 'ADD_TICKET', NULL, '2023-05-11 19:44:36'),
(12, 4, 1, 1, 1, NULL, 'UPDATE_TICKET', NULL, '2023-05-11 20:01:20'),
(13, 4, 1, 1, 1, '', 'ADD_LOG', '[\"4\",\"t4\",\"2023-05-11T20:15\",\"5\",\"1\",\"this is done\",\"\",\"\",\"0\",\"\",\"0\",\"0\"]', '2023-05-11 20:16:15'),
(14, 1, 2, 3, 7, 'NA', 'UPDATE_LOG', '[\"1\",\"t1\",\"2023-05-10T15:47\",\"1.2\",\"7\",\"another log\",\"NA\",\"NA\",\"3\"]', '2023-05-12 12:43:08'),
(15, 1, 3, 3, 7, 'NA', 'UPDATE_LOG', '[\"1\",\"t1\",\"2023-05-10T15:47\",\"5.4\",\"7\",\"another log\",\"NA\",\"NA\",\"3\",\"\",\"Code Review\",\"Code Review\"]', '2023-05-12 12:55:52'),
(16, 2, 3, 3, 1, '', 'ADD_LOG', '[\"2\",\"t2\",\"2023-05-12T12:56\",\"11\",\"1\",\"11 tarikh\",\"\",\"\",\"0\",\"\",\"0\",\"0\"]', '2023-05-12 12:57:01'),
(17, 2, 3, 3, 1, '', 'ADD_LOG', '[\"2\",\"t2\",\"2023-05-12T12:57\",\"12\",\"1\",\"12 tarikh\",\"\",\"\",\"0\",\"\",\"0\",\"0\"]', '2023-05-12 12:57:12'),
(18, 1, 3, 3, 3, 'close', 'UPDATE_LOG', '[\"1\",\"t1\",\"2023-05-10T15:47\",\"5.4\",\"3\",\"another log\",\"NA\",\"NA\",\"3\",\"close\",\"WIP\",\"Closed\"]', '2023-05-12 13:19:21'),
(19, 0, 2, 1, 5, NULL, 'ADD_TICKET', NULL, '2023-05-12 19:54:55'),
(20, 5, 1, 1, 1, NULL, 'UPDATE_TICKET', NULL, '2023-05-12 19:55:54'),
(21, 5, 1, 1, 1, NULL, 'UPDATE_TICKET', NULL, '2023-05-12 19:55:57'),
(22, 5, 1, 1, 1, NULL, 'UPDATE_TICKET', NULL, '2023-05-12 19:57:46'),
(23, 5, 1, 1, 1, NULL, 'UPDATE_TICKET', NULL, '2023-05-12 19:57:55'),
(24, 5, 1, 1, 1, NULL, 'UPDATE_TICKET', NULL, '2023-05-12 19:57:56'),
(25, 5, 1, 1, 1, NULL, 'UPDATE_TICKET', NULL, '2023-05-12 19:58:56'),
(26, 5, 1, 1, 1, NULL, 'UPDATE_TICKET', NULL, '2023-05-12 19:59:13'),
(27, 5, 1, 1, 1, NULL, 'UPDATE_TICKET', NULL, '2023-05-12 20:01:36'),
(28, 5, 1, 1, 1, '', 'ADD_LOG', '[\"5\",\"t5\",\"2023-05-12T20:01\",\"9\",\"1\",\"doon\",\"\",\"\",\"0\",\"\",\"0\",\"0\"]', '2023-05-12 20:01:54'),
(29, 5, 1, 1, 2, 'w', 'UPDATE_LOG', '[\"5\",\"t5\",\"2023-05-12T20:01\",\"9\",\"2\",\"doon\",\"NA\",\"NA\",\"8\",\"w\",\"WIP\",\"Hold\"]', '2023-05-12 20:03:34'),
(30, 5, 1, 1, 1, 'ss', 'UPDATE_LOG', '[\"5\",\"t5\",\"2023-05-12T20:01\",\"9\",\"1\",\"doon\",\"NA\",\"NA\",\"8\",\"ss\",\"Hold\",\"WIP\"]', '2023-05-12 20:04:53'),
(31, 5, 1, 1, 2, 'ss', 'UPDATE_LOG', '[\"5\",\"t5\",\"2023-05-12T20:01\",\"9\",\"2\",\"doon\",\"NA\",\"NA\",\"8\",\"ss\",\"WIP\",\"Hold\"]', '2023-05-12 20:05:12'),
(32, 5, 1, 1, 1, 's', 'UPDATE_LOG', '[\"5\",\"t5\",\"2023-05-12T20:01\",\"9\",\"1\",\"doon\",\"NA\",\"NA\",\"8\",\"s\",\"Hold\",\"WIP\"]', '2023-05-12 20:09:08'),
(33, 5, 1, 1, 2, 's', 'UPDATE_LOG', '[\"5\",\"t5\",\"2023-05-12T20:01\",\"9\",\"2\",\"doon\",\"NA\",\"NA\",\"8\",\"s\",\"WIP\",\"Hold\"]', '2023-05-12 20:10:19'),
(34, 5, 1, 1, 1, 's', 'UPDATE_LOG', '[\"5\",\"t5\",\"2023-05-12T20:01\",\"9\",\"1\",\"doon\",\"NA\",\"NA\",\"8\",\"s\",\"Hold\",\"WIP\"]', '2023-05-12 20:10:38'),
(35, 0, 2, 1, 5, NULL, 'ADD_TICKET', NULL, '2023-05-15 14:59:08'),
(36, 6, 1, 1, 1, NULL, 'UPDATE_TICKET', NULL, '2023-05-15 15:00:16'),
(37, 6, 1, 1, 1, '', 'ADD_LOG', '[\"6\",\"tucuj\",\"2023-05-15T15:01\",\"5\",\"1\",\"done some part in this\",\"need to edevug\",\"NA\",\"0\",\"\",\"0\",\"0\"]', '2023-05-15 15:01:36'),
(38, 6, 1, 1, 1, NULL, 'UPDATE_TICKET', NULL, '2023-05-15 16:50:47'),
(39, 0, 2, 1, 5, NULL, 'ADD_TICKET', NULL, '2023-05-15 16:55:08'),
(40, 7, 1, 1, 5, NULL, 'UPDATE_TICKET', NULL, '2023-05-15 16:55:34'),
(41, 3, 4, 4, 1, '', 'ADD_LOG', '[\"3\",\"t3\",\"2023-05-23T15:50\",\"5\",\"1\",\"work done \",\"\",\"\",\"0\",\"\",\"0\",\"0\"]', '2023-05-23 15:51:05'),
(42, 3, 4, 4, 1, '', 'ADD_LOG', '[\"3\",\"t3\",\"2023-05-23T15:50\",\"5\",\"1\",\"work done \",\"\",\"\",\"0\",\"\",\"0\",\"0\"]', '2023-05-23 15:52:00'),
(43, 3, 4, 4, 1, '', 'ADD_LOG', '[\"3\",\"t3\",\"2023-05-23T15:50\",\"5\",\"1\",\"work done \",\"\",\"\",\"0\",\"\",\"0\",\"0\"]', '2023-05-23 15:53:00'),
(44, 3, 4, 4, 1, '', 'ADD_LOG', '[\"3\",\"t3\",\"2023-05-23T15:50\",\"5\",\"1\",\"work done \",\"\",\"\",\"0\",\"\",\"0\",\"0\"]', '2023-05-23 15:53:09'),
(45, 3, 4, 4, 1, '', 'ADD_LOG', '[\"3\",\"t3\",\"2023-05-23T15:56\",\"10\",\"1\",\"\",\"\",\"\",\"0\",\"\",\"0\",\"0\"]', '2023-05-23 15:56:19');

-- --------------------------------------------------------

--
-- Table structure for table `projects`
--

CREATE TABLE `projects` (
  `id` int(10) NOT NULL,
  `project_name` varchar(255) NOT NULL,
  `project_code` varchar(200) DEFAULT NULL,
  `region` varchar(100) DEFAULT NULL,
  `description` text DEFAULT NULL,
  `start_date` datetime DEFAULT NULL,
  `end_date` datetime DEFAULT NULL,
  `renewal_date` datetime DEFAULT NULL,
  `customer_name` varchar(200) DEFAULT NULL,
  `planned_billing` varchar(250) DEFAULT NULL,
  `actual_billing` varchar(250) DEFAULT NULL,
  `created_at` datetime NOT NULL,
  `updated_at` datetime NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `projects`
--

INSERT INTO `projects` (`id`, `project_name`, `project_code`, `region`, `description`, `start_date`, `end_date`, `renewal_date`, `customer_name`, `planned_billing`, `actual_billing`, `created_at`, `updated_at`) VALUES
(1, 'PMbook', 'PMB-01', '', '', '0000-00-00 00:00:00', '0000-00-00 00:00:00', '0000-00-00 00:00:00', '', '', '', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(2, 'tribepad', 'ATS', '1', 'tis', '2023-05-16 19:15:00', '2023-06-10 19:15:00', '2023-05-26 19:15:00', 'mark', '123', '234', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(3, 'vi', 'VI-02', '2', 'vi', '2023-05-18 19:16:00', '2023-05-27 19:16:00', '2023-05-20 19:16:00', 'kkk', '124', '43', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(5, 'HW', 'HW', '', '', '0000-00-00 00:00:00', '0000-00-00 00:00:00', '0000-00-00 00:00:00', 'jennie', '', '', '0000-00-00 00:00:00', '0000-00-00 00:00:00');

-- --------------------------------------------------------

--
-- Table structure for table `project_user_map`
--

CREATE TABLE `project_user_map` (
  `id` int(10) NOT NULL,
  `project_id` int(10) NOT NULL,
  `user_id` int(10) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `project_user_map`
--

INSERT INTO `project_user_map` (`id`, `project_id`, `user_id`) VALUES
(5, 2, 4),
(6, 2, 3),
(11, 1, 1),
(12, 2, 1),
(13, 1, 2),
(14, 2, 2),
(15, 3, 2);

-- --------------------------------------------------------

--
-- Table structure for table `tickets`
--

CREATE TABLE `tickets` (
  `id` int(11) NOT NULL,
  `ticket_id` varchar(100) NOT NULL,
  `type_id` int(11) NOT NULL,
  `project_id` int(10) NOT NULL,
  `c_status` varchar(100) NOT NULL,
  `wip_start_datetime` datetime DEFAULT NULL,
  `wip_close_datetime` datetime DEFAULT NULL,
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

--
-- Dumping data for table `tickets`
--

INSERT INTO `tickets` (`id`, `ticket_id`, `type_id`, `project_id`, `c_status`, `wip_start_datetime`, `wip_close_datetime`, `assignee_id`, `assigned_date`, `plan_start_date`, `plan_end_date`, `actual_start_date`, `actual_end_date`, `planned_hrs`, `actual_hrs`, `created_at`, `updated_at`) VALUES
(1, 't1', 1, 2, '3', '2023-05-08 00:00:00', '2023-05-12 00:00:00', 3, '2023-05-08 15:19:00', '2023-05-09 15:23:00', '2023-05-09 15:23:00', '0000-00-00 00:00:00', '0000-00-00 00:00:00', 30.00, 8.40, '2023-05-11 15:23:21', '2023-05-11 15:24:53'),
(2, 't2', 1, 2, '1', '2023-05-10 00:00:00', NULL, 3, '2023-05-08 15:37:00', '2023-05-08 15:38:00', '2023-05-30 15:38:00', '0000-00-00 00:00:00', '0000-00-00 00:00:00', 30.00, 27.00, '2023-05-11 15:38:13', '2023-05-11 15:38:52'),
(3, 't3', 1, 2, '1', '2023-05-10 00:00:00', NULL, 4, '2023-05-11 16:09:00', '2023-05-10 16:09:00', '2023-05-10 16:09:00', '2023-05-10 16:10:00', '2023-05-23 16:10:00', 24.00, 37.00, '2023-05-11 16:10:06', '2023-05-11 16:10:36'),
(4, 't4', 1, 2, '1', '2023-05-11 00:00:00', NULL, 1, '2023-05-11 19:44:00', '2023-05-03 19:44:00', '2023-05-03 19:44:00', '0000-00-00 00:00:00', '0000-00-00 00:00:00', 80.00, 5.00, '2023-05-11 19:44:36', '2023-05-11 20:01:20'),
(5, 't5', 1, 2, '1', '2023-05-12 00:00:00', NULL, 1, '2023-05-12 19:54:00', '2023-05-12 19:54:00', '2023-05-12 19:54:00', '0000-00-00 00:00:00', '0000-00-00 00:00:00', 50.00, 9.00, '2023-05-12 19:54:55', '2023-05-12 20:01:36'),
(6, 'tucuj', 2, 2, '1', '2023-05-15 00:00:00', NULL, 1, '2023-05-15 14:57:00', '2023-05-15 14:57:00', '2023-05-31 14:57:00', '2023-05-15 14:59:00', '2023-05-31 16:50:00', 60.00, 5.00, '2023-05-15 14:59:08', '2023-05-15 16:50:47'),
(7, 'new', 1, 1, '5', NULL, NULL, 1, '2023-05-15 16:54:00', '2023-05-15 16:54:00', '2023-05-15 16:55:00', '2023-05-15 16:55:00', '2023-05-15 16:55:00', 22.00, 0.00, '2023-05-15 16:55:08', '2023-05-15 16:55:34');

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
  `user_status` int(10) DEFAULT 1,
  `created_at` datetime NOT NULL DEFAULT current_timestamp(),
  `updated_at` datetime NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`id`, `username`, `fname`, `lname`, `user_type`, `employee_id`, `designation`, `email`, `password`, `user_status`, `created_at`, `updated_at`) VALUES
(1, 'dhirajtekade', 'Dhiraj', 'Tekade', 3, 4762, 'full s', 'dhirajtek13@gmail.com', '$2y$10$R8NtjFXOLjDglT7wOVL14utYQJQFJPQxFws4t0WBCRX24aSal2dtG', 1, '2023-04-25 17:32:13', '2023-05-17 14:34:09'),
(2, 'vumesh', 'Umesh', 'Verma', 1, 111, 'PM', 'vumesh@espire.com', '$2y$10$/pVJVAzUYkMsjEC0frHReOOeAqyzsSXqVZRsFonzPVg8Iz7Z0Ovky', 1, '2023-04-25 20:52:17', '2023-05-17 10:18:33'),
(3, 'kamit', 'Amit', 'Karki', 2, 222, 'LE', 'kamit@espire.com', '$2y$10$xrL2N5Xl1.xQn8o9iRNhxu3YxSy2PK2LZVJgxaxB1micC4NUMenVS', 1, '2023-04-25 20:54:53', '2023-05-17 09:47:10'),
(4, 'upendra', 'Upendra', 'Prasad', 3, 4555, 'lead eng', 'u@email.com', '$2y$10$a0nE4VkNRE5qZS/sLXTjsOzs2mNrKb/d/x4ua1qK.sNzfg.1K61IS', 1, '2023-04-27 13:36:54', '2023-05-17 09:46:54');

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

-- --------------------------------------------------------

--
-- Table structure for table `week_days`
--

CREATE TABLE `week_days` (
  `week_day_num` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `week_days`
--

INSERT INTO `week_days` (`week_day_num`) VALUES
(1),
(2),
(3),
(4),
(5),
(6),
(7);

--
-- Indexes for dumped tables
--

--
-- Indexes for table `configurations`
--
ALTER TABLE `configurations`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `c_status_types`
--
ALTER TABLE `c_status_types`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `kpis`
--
ALTER TABLE `kpis`
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
-- Indexes for table `projects`
--
ALTER TABLE `projects`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `project_code` (`project_code`);

--
-- Indexes for table `project_user_map`
--
ALTER TABLE `project_user_map`
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
-- AUTO_INCREMENT for table `configurations`
--
ALTER TABLE `configurations`
  MODIFY `id` int(10) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- AUTO_INCREMENT for table `c_status_types`
--
ALTER TABLE `c_status_types`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=21;

--
-- AUTO_INCREMENT for table `kpis`
--
ALTER TABLE `kpis`
  MODIFY `id` int(10) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `log_history`
--
ALTER TABLE `log_history`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=15;

--
-- AUTO_INCREMENT for table `log_timing`
--
ALTER TABLE `log_timing`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=46;

--
-- AUTO_INCREMENT for table `projects`
--
ALTER TABLE `projects`
  MODIFY `id` int(10) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT for table `project_user_map`
--
ALTER TABLE `project_user_map`
  MODIFY `id` int(10) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=16;

--
-- AUTO_INCREMENT for table `tickets`
--
ALTER TABLE `tickets`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;

--
-- AUTO_INCREMENT for table `ticket_types`
--
ALTER TABLE `ticket_types`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `id` int(10) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=13;

--
-- AUTO_INCREMENT for table `user_type`
--
ALTER TABLE `user_type`
  MODIFY `id` int(10) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
