-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Jul 06, 2023 at 01:26 PM
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
(1, 'actual_hrs', 'number', 'Actual Hrs of the day', '7.5', NULL, 2),
(2, 'working_days', 'number', 'Working Days', '5', NULL, 2),
(3, 'kpi_c_status_types', 'select', 'Ticket status for KPI (OTD)', '3,7', NULL, 2),
(4, 'ftr_c_status_types', 'select', 'FTR', '10,11,12', NULL, 2),
(6, 'ticket_status_c_status_types', 'select', 'Ticket Status columns', '1,2,3,6,7,8', NULL, 2),
(8, 'prod_c_status_types', 'select', 'Productivity KPI', '3,7', NULL, 2),
(9, 'reassigned_c_status_types', 'select', 'Reassigned KPI', '4', NULL, 2),
(10, 'default_project', 'select', 'Default Project', '2', NULL, 0);

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
(19, 'Live Verify'),
(21, 'Technical Approval required');

-- --------------------------------------------------------

--
-- Table structure for table `day_type`
--

CREATE TABLE `day_type` (
  `id` int(10) NOT NULL,
  `type_name` varchar(200) NOT NULL,
  `description` text DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `day_type`
--

INSERT INTO `day_type` (`id`, `type_name`, `description`) VALUES
(1, 'Full Day', NULL),
(2, 'First Half', NULL),
(3, 'Second Half', NULL);

-- --------------------------------------------------------

--
-- Table structure for table `holidays`
--

CREATE TABLE `holidays` (
  `id` int(10) NOT NULL,
  `hol_name` varchar(250) NOT NULL,
  `hol_desc` text NOT NULL,
  `holidays` int(10) NOT NULL,
  `hol_start_date` datetime NOT NULL,
  `hol_end_date` datetime NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `holidays`
--

INSERT INTO `holidays` (`id`, `hol_name`, `hol_desc`, `holidays`, `hol_start_date`, `hol_end_date`) VALUES
(1, 'Republic day', 'republic day', 1, '2023-01-26 15:20:00', '2023-01-26 15:21:00'),
(2, '3 days holiday', '', 3, '2023-06-12 22:21:00', '2023-06-14 22:21:00'),
(3, 'june vac', '', 1, '2023-06-28 00:00:00', '2023-06-28 00:00:00');

-- --------------------------------------------------------

--
-- Table structure for table `kpis`
--

CREATE TABLE `kpis` (
  `id` int(10) NOT NULL,
  `kpi_name` varchar(250) NOT NULL,
  `shortname` varchar(200) DEFAULT NULL,
  `service_level` text DEFAULT NULL,
  `description` text DEFAULT NULL,
  `target_operator` enum('','<','>','<=','>=','=') DEFAULT NULL,
  `target_value` varchar(200) DEFAULT NULL,
  `project_id` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `kpis`
--

INSERT INTO `kpis` (`id`, `kpi_name`, `shortname`, `service_level`, `description`, `target_operator`, `target_value`, `project_id`) VALUES
(1, 'OTD', 'otd', '% Tasks completed within defined timelines', '- No. of Tasks delivered\n- new update', '>=', '90', 2),
(2, 'ODD', 'odd', '', '', '>=', '90', 2),
(3, 'Productivity', 'prod', '', 'Total planned hours in a week vs total actual hour in a week', '<=', '+10', NULL),
(4, 'FTR', 'ftr', 'Defects raised against the tickets', 'Efforts spend in fixing defects raised during code review <=10% of actual\nefforts spend on initial development\nBug raised on tickets on live. (no of bugs / tickets in week) – Tribepad to\ngive the numbers\n', '>=', '90', NULL),
(5, 'Resource Utilization', 'ru', '% Productive resource utilization against overall capacity aligned for client work', '- Capacity person hours assigned per week\n- Actual effort spent in productive hours', '>=', '80', NULL),
(6, 'Reassigned Tickets', 'rt', 'Reassigned tickets', '', '<=', '10', NULL);

-- --------------------------------------------------------

--
-- Table structure for table `leave_tracker`
--

CREATE TABLE `leave_tracker` (
  `id` int(10) NOT NULL,
  `user_id` int(10) NOT NULL,
  `leave_desc` text DEFAULT NULL,
  `day_type` int(10) DEFAULT NULL,
  `leave_type` int(10) DEFAULT NULL,
  `leave_start_date` datetime NOT NULL,
  `leave_end_date` datetime NOT NULL,
  `leave_days` int(11) NOT NULL,
  `leave_apply_date` datetime NOT NULL,
  `leave_status` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `leave_tracker`
--

INSERT INTO `leave_tracker` (`id`, `user_id`, `leave_desc`, `day_type`, `leave_type`, `leave_start_date`, `leave_end_date`, `leave_days`, `leave_apply_date`, `leave_status`) VALUES
(10, 2, 's', 1, 1, '2023-06-06 00:00:00', '2023-06-06 00:00:00', 1, '2023-06-23 08:33:56', 0),
(11, 1, 'sd', 1, 1, '2023-06-06 00:00:00', '2023-06-06 00:00:00', 1, '2023-06-22 10:02:46', 0),
(12, 3, 'd', 1, 1, '2023-06-06 00:00:00', '2023-06-07 00:00:00', 2, '2023-06-22 14:05:13', 0),
(13, 3, 'd', 1, 1, '2023-06-02 00:00:00', '2023-06-02 00:00:00', 1, '2023-06-22 10:12:13', 0),
(15, 2, 'new', 2, 2, '2023-06-05 00:00:00', '2023-06-05 00:00:00', 1, '2023-06-22 13:42:15', 0),
(16, 2, 'ko', 1, 1, '2023-06-07 00:00:00', '2023-06-07 00:00:00', 1, '2023-06-23 08:34:58', 0),
(17, 1, 'may leave', 1, 1, '2023-05-05 00:00:00', '2023-05-08 00:00:00', 4, '2023-06-23 09:03:33', 0);

-- --------------------------------------------------------

--
-- Table structure for table `leave_type`
--

CREATE TABLE `leave_type` (
  `id` int(10) NOT NULL,
  `type_name` varchar(20) NOT NULL,
  `description` text DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `leave_type`
--

INSERT INTO `leave_type` (`id`, `type_name`, `description`) VALUES
(1, 'sick', NULL),
(2, 'Earned', NULL),
(3, 'Emergency2', NULL);

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
  `what_is_done` text DEFAULT NULL,
  `what_is_pending` text DEFAULT NULL,
  `what_support_required` text DEFAULT NULL,
  `created_at` datetime DEFAULT current_timestamp(),
  `updated_at` datetime DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `log_history`
--

INSERT INTO `log_history` (`id`, `ticket_id`, `user_id`, `dates`, `hrs`, `c_status`, `what_is_done`, `what_is_pending`, `what_support_required`, `created_at`, `updated_at`) VALUES
(1, 6, 3, '2023-06-12 12:00:00', '3.5', 1, '\"What is Done:- The issue has been successfully replicated on the alpha environment, and the cause has been identified.\n\n', 'What is pending:- Testing of the solution and internal review. ', 'What support is required:- NA\"', '2023-06-14 17:46:33', '2023-06-14 17:46:33'),
(2, 6, 3, '2023-06-13 12:00:00', '1.5', 7, '\"What is Done:- Updated Job Req logic to match that of the Index page. Pushed the code for review.\nWhat is pending:- NA. \nWhat support is required:- NA\"', 'NA', 'NA', '2023-06-14 17:49:24', '2023-06-14 17:49:24'),
(3, 5, 15, '2023-05-22 12:00:00', '1.5', 6, '\"What is done:- Analysed the requirement, faced an issue while running manage in nav.blade.php, fixed that. And ran into another table missing issue. \nWhat is pending:-  Development.\nWhat support is required:- while running the indeed settings page on manage, there is a table missing error (indeed_jobfeed_config) and the migation file doesn\'t simply add a table to the database, but runs a BrandSetupStep, so will not run migrate without consulting first. \"\n', 'NA', 'NA', '2023-06-14 17:52:08', '2023-06-14 17:52:08'),
(4, 5, 15, '2023-05-23 12:00:00', '3', 1, '\"What is done:- Analysed the requirement, faced an issue while running manage in nav.blade.php, fixed that. And ran into another table missing issue. \nWhat is pending:-  Development.\nWhat support is required:- while running the indeed settings page on manage, there is a table missing error (indeed_jobfeed_config) and the migation file doesn\'t simply add a table to the database, but runs a BrandSetupStep, so will not run migrate without consulting first. \"\n', 'NA', 'NA', '2023-06-14 18:06:45', '2023-06-14 18:06:45'),
(5, 5, 15, '2023-05-24 12:00:00', '3', 1, '\"What is done:- Created a dynamic table ui with individually editable & deletable rows, worked on adding different field types and their field options based on type. \nWhat is pending:-  NA\n', 'NA', 'What support is required:- NA \"', '2023-06-14 18:49:54', '2023-06-14 18:49:54'),
(6, 5, 15, '2023-05-25 12:00:00', '3', 1, 'Finished working on addition of different field types on frontend and finalized api response structures for the same as well.', ' NA \"', ' NA \"', '2023-06-14 18:52:04', '2023-06-14 18:52:04'),
(7, 5, 15, '2023-05-26 12:00:00', '3.5', 1, ' Finished working on the ui elements. Created the needed APIs, started working on data & database now.\n', 'NA', 'NA', '2023-06-14 18:53:12', '2023-06-14 18:53:12'),
(8, 5, 15, '2023-05-30 12:00:00', '3', 1, '\"What is done:-  As decided on call with mark, replaced select dropdown with select2 dropdown and started working on the apis for same.\nWhat is pending:-  NA\nWhat support is required:- NA \"', 'NA', 'NA', '2023-06-14 18:54:38', '2023-06-14 18:54:38'),
(9, 5, 15, '2023-05-31 12:00:00', '2', 1, '\"What is done:-  Worked on the select2 apis for dropdowns.\n', 'NA', 'What is pending:-  NA\nWhat support is required:- NA \"', '2023-06-14 18:55:27', '2023-06-14 18:55:27'),
(10, 5, 15, '2023-06-01 12:00:00', '2.5', 1, '\"What is done:-  Completed worked on select2 apis. Will start understanding config server & translation system.\nWhat is pending:-  NA\nWhat support is required:- NA \"', 'NA', 'NA', '2023-06-14 18:56:42', '2023-06-14 18:56:42'),
(11, 5, 15, '2023-06-02 12:00:00', '2', 1, '\"What is done:- Worked with config server to translate the jobnames.\nWhat is pending:-  NA\nWhat support is required:- NA \"', 'NA', 'NA', '2023-06-14 18:57:43', '2023-06-14 18:57:43'),
(12, 5, 15, '2023-06-05 12:00:00', '2', 1, '\"What is done:- Discussed on call with Mark about translations of job field names database side, which was not possible. So we decided on fetching job_field_mappings and custom_field jobnames separately and translating job_field_names and then then combining the results.\nWhat is pending:-  NA\nWhat support is required:- NA \n', 'NA', 'NA', '2023-06-14 18:58:17', '2023-06-14 18:58:17'),
(13, 5, 15, '2023-06-06 12:00:00', '1.5', 1, '\"What is done:- As Discussed on previous call with Mark about translations of job field names database side, which was not possible. So we decided on fetching job_field_mappings and custom_field jobnames separately and translating job_field_names and then then combining the results.\nWhat is pending:-  NA\nWhat support is required:- NA \"\n', 'NA', 'NA', '2023-06-14 18:58:53', '2023-06-14 18:58:53'),
(14, 5, 15, '2023-06-07 12:00:00', '1.5', 1, '\"What is done:- Worked on the selectionbox api for job_field_name.\nWhat is pending:-  NA\nWhat support is required:- NA \"', 'NA', 'NA', '2023-06-14 18:59:24', '2023-06-14 18:59:24'),
(15, 5, 15, '2023-07-05 20:40:00', '9', 1, 'mmm', 'NA', 'NA', '2023-06-14 19:51:26', '2023-07-06 12:02:04'),
(16, 3, 16, '2023-05-26 12:00:00', '3', 10, '\"What is done:- Worked on compilled custom style for ul list item \nWhat is pending:- NA\nWhat support is required:- NA\"', 'NA', 'NA', '2023-06-14 19:52:06', '2023-06-14 19:52:06'),
(17, 3, 16, '2023-06-09 12:00:00', '1', 10, '\"What is done:- Worked on create fresh new branch from v.4.44 and  varifying the changes are already existing in v4.44\nWhat is pending:- NA\nWhat support is required:- NA\"\n', 'NA', 'NA', '2023-06-14 19:52:46', '2023-06-14 19:52:46'),
(18, 3, 16, '2023-06-13 12:00:00', '3.5', 1, '\"What is done:- Resolved the inline style issues that are added to froala editor on run time.\nWhat is pending:- Checking by enabling the configuration setting.\nWhat support is required:-NA\nRework Reason:-  froala editor have some issues with default configuration with in-line style\"', 'NA', 'NA', '2023-06-14 19:53:40', '2023-06-14 19:53:40'),
(19, 4, 4, '2022-11-15 12:00:00', '4', 1, ' I am working on adding attachment feature for candidate search.', 'NA', 'NA', '2023-06-15 07:10:35', '2023-06-15 07:10:35'),
(20, 4, 4, '2022-11-16 12:00:00', '4.5', 1, ' I have added functionality of attachment on candidate search page.I have tested it is working fine.', 'NA', 'NA', '2023-06-15 07:17:00', '2023-06-15 07:20:21'),
(21, 4, 4, '2022-11-17 12:00:00', '1', 7, ' I have completed feature and pushed code review.I am working shared feedack.', 'NA', 'NA', '2023-06-15 07:17:45', '2023-06-15 07:28:34'),
(22, 4, 4, '2022-11-18 12:00:00', '1', 10, 'I have fixed shared feedback and pushed code for review.', 'NA', 'NA', '2023-06-15 07:18:36', '2023-06-15 07:28:49'),
(23, 4, 4, '2022-11-29 12:00:00', '3', 7, 'I have fixed email attachment issue and pushed code for review.', 'NA', 'NA', '2023-06-15 07:20:00', '2023-06-15 07:29:00'),
(24, 4, 4, '2023-03-27 12:00:00', '1', 10, ' I have merged code with master and fixed merge conflict.\n', 'NA', 'NA', '2023-06-15 07:30:37', '2023-06-15 07:30:37'),
(25, 4, 4, '2023-05-12 12:00:00', '0.5', 10, ' I have added a new module email_template of attachment while creating the email template with attachment. \n', 'NA', 'NA', '2023-06-15 07:31:38', '2023-06-15 07:31:38'),
(26, 4, 4, '2023-05-26 12:00:00', '1', 1, '  Working on code optimzation feedback.\n', 'NA', 'NA', '2023-06-15 07:33:00', '2023-06-15 07:33:00'),
(27, 4, 4, '2023-05-29 12:00:00', '1.5', 11, '\"What is done:-  I have done changes as shared feedback on code review\n', 'NA', 'NA', '2023-06-15 07:34:41', '2023-06-15 07:34:41'),
(28, 4, 4, '2023-05-31 12:00:00', '1', 12, '\"What is done:-  Optimized attachment code of email template as shared feedback\nWhat is pending:- NA\nWhat support is required:- NA\nReson for rework: Optimized code of attachment\"\n', 'NA', 'NA', '2023-06-15 07:52:08', '2023-06-15 07:52:08'),
(29, 4, 4, '2023-06-01 12:00:00', '1', 12, 'Created new function for get attachment with attachment Id', 'NA', 'NA', '2023-06-15 07:53:20', '2023-06-15 07:53:20'),
(30, 4, 4, '2023-06-07 12:00:00', '2', 12, '\"What is done:-  I have updated feedback as shared feedback,after discussion with Mark,I have revert changes and pushed code again.\n', 'NA', 'NA', '2023-06-15 07:54:00', '2023-06-15 07:54:00'),
(31, 2, 14, '2023-06-02 12:00:00', '2', 6, '\"What is done:- Analysed the ticket understood the task, working on a technical document.  ', 'NA', 'NA', '2023-06-15 09:57:21', '2023-06-15 09:57:21'),
(32, 2, 14, '2023-06-05 12:00:00', '6', 21, '\"What is done:- Worked on the technical document and created it.  \n', 'What is pending:- Internal review.\n', 'What support is required:- NA\"', '2023-06-15 09:59:24', '2023-06-15 09:59:24'),
(33, 2, 14, '2023-06-06 12:00:00', '2', 21, '\"What is done:- I have created the technical document Please find the link below for review. However, I am not able to configure SSO on my end so not able to understand the full flow. I  tried with Okta but was not able to setup it up.\n\nhttps://docs.google.com/document/d/1eyQGb7iMWpr5LiCl_LofLvgX4V0XqY22UjsyHr3g7Ig/edit#', 'What is pending:- Review\n', 'What support is required:- NA\"', '2023-06-15 10:00:20', '2023-06-15 10:00:20'),
(34, 2, 14, '2023-06-07 12:00:00', '1', 5, '\"What is done:- worked on  sso config setup as per comment in technical document , however not able to setup . There is no more ticket in backlog to work so i was ideal or work on mis ticket for 1.5 hours , So my total worked time is less then 7.5 hours.\n', 'What is pending:- need to setup sso config', 'What support is required:- Please add some ticket in backlog\"', '2023-06-15 10:02:17', '2023-06-15 10:02:17'),
(35, 2, 14, '2023-06-08 12:00:00', '6.5', 1, '\"What is done:- worked on  SSO setup and completed with provided URL. Created migration and model in v2 codebase, wrote code for insert errors in table, working on view to display errors in SSO module.  \n', 'What is pending:- display error in SSO module\nWhat support is required:- NA\"', 'NA', '2023-06-15 10:05:31', '2023-06-15 10:05:31'),
(36, 2, 14, '2023-06-09 12:00:00', '6.5', 1, 'What is done:- worked on log error display section with delete functionality and completed it. started work on refresh config section. ', 'What is pending:- NA\n', 'What support is required:- NA\"', '2023-06-15 10:11:42', '2023-06-15 10:11:42'),
(37, 2, 14, '2023-06-12 12:00:00', '6.5', 1, '\"What is done:- I worked on the refresh config section and completed it. I am facing some issues in testing because i am not able to open sso module for login. ', 'What is pending:- Need to test changes for login and refresh sso.\n', 'What support is required:- NA\"', '2023-06-15 10:12:29', '2023-06-15 10:12:29'),
(38, 2, 14, '2023-06-14 12:00:00', '4.5', 1, '\"What is done:- tested the entire flow and changes and created merge request for code review. \nWhat is pending:- Internal code review. ', 'NA', 'What support is required:- NA\"', '2023-06-15 10:13:12', '2023-06-15 10:13:12'),
(39, 1, 13, '2023-02-24 12:00:00', '3', 6, 'NA', 'NA', 'NA', '2023-06-15 10:39:08', '2023-06-15 10:39:08'),
(40, 1, 13, '2023-02-27 12:00:00', '6', 6, 'NA', 'NA', 'NA', '2023-06-15 10:39:41', '2023-06-15 10:39:41'),
(41, 1, 13, '2023-02-28 12:00:00', '3.5', 6, 'NA', 'NA', 'NA', '2023-06-15 10:40:09', '2023-06-15 10:40:09'),
(42, 1, 13, '2023-03-01 12:00:00', '6', 6, 'NA', 'NA', 'NA', '2023-06-15 10:48:28', '2023-06-15 10:48:28'),
(43, 1, 13, '2023-03-02 12:00:00', '0', 2, '\"What is done:- Provided flow and implementation plan, Waiting for the confirmation, So currently putting on hold.\nWhat is pending:- Needs to start work if confirmed.\nWhat support is required:- NA\"', 'NA', 'NA', '2023-06-15 10:49:19', '2023-06-15 10:49:19'),
(44, 1, 13, '2023-03-06 12:00:00', '1.5', 1, 'NA', 'NA', 'NA', '2023-06-15 10:50:01', '2023-06-15 10:50:01'),
(45, 1, 13, '2023-03-09 12:00:00', '5', 1, 'NA', 'NA', 'NA', '2023-06-15 10:51:23', '2023-06-15 10:51:23'),
(46, 1, 13, '2023-03-10 12:00:00', '0', 2, 'Satus changed: Put on hold, since needs to work on bugs', 'NA', 'NA', '2023-06-15 10:51:58', '2023-06-15 10:51:58'),
(47, 1, 13, '2023-03-13 12:00:00', '6', 1, 'NA', 'NA', 'NA', '2023-06-15 10:53:04', '2023-06-15 10:53:04'),
(48, 1, 13, '2023-03-14 12:00:00', '5', 1, 'NA', 'NA', 'NA', '2023-06-15 10:53:42', '2023-06-15 10:53:42'),
(49, 1, 13, '2023-03-15 12:00:00', '4', 1, 'NA', 'NA', 'NA', '2023-06-15 10:54:32', '2023-06-15 10:54:32'),
(50, 1, 13, '2023-03-16 12:00:00', '4', 1, 'NA', 'NA', 'NA', '2023-06-15 10:55:06', '2023-06-15 10:55:06'),
(51, 1, 13, '2023-03-17 12:00:00', '0', 2, 'status changed', 'NA', 'NA', '2023-06-15 10:56:12', '2023-06-15 10:56:12'),
(52, 1, 13, '2023-03-20 12:00:00', '6', 1, 'NA', 'NA', 'NA', '2023-06-15 10:57:23', '2023-06-15 10:57:23'),
(53, 1, 13, '2023-03-21 12:00:00', '6', 1, 'NA', 'NA', 'NA', '2023-06-15 11:44:02', '2023-06-15 11:44:02'),
(54, 1, 13, '2023-03-22 12:00:00', '6', 1, 'NA', 'NA', 'NA', '2023-06-15 11:44:30', '2023-06-15 11:44:30'),
(55, 1, 13, '2023-03-23 12:00:00', '5.5', 1, 'NA', 'NA', 'NA', '2023-06-15 11:45:03', '2023-06-15 11:45:03'),
(56, 1, 13, '2023-03-24 12:00:00', '0', 2, 'NA', 'NA', 'NA', '2023-06-15 11:45:53', '2023-06-15 11:45:53'),
(57, 1, 13, '2023-03-27 00:00:00', '6', 1, 'NA', 'NA', 'NA', '2023-06-15 11:51:17', '2023-06-15 11:51:17'),
(58, 1, 13, '2023-03-28 00:00:00', '6', 1, 'NA', 'NA', 'NA', '2023-06-15 11:51:37', '2023-06-15 11:51:37'),
(59, 1, 13, '2023-03-29 00:00:00', '6', 1, 'NA', 'NA', 'NA', '2023-06-15 11:51:54', '2023-06-15 11:51:54'),
(60, 1, 13, '2023-03-31 00:00:00', '0', 2, 'NA', 'NA', 'NA', '2023-06-15 11:52:18', '2023-06-15 11:52:18'),
(61, 1, 13, '2023-04-03 00:00:00', '4', 1, 'NA', 'NA', 'NA', '2023-06-15 11:53:28', '2023-06-15 11:53:28'),
(62, 1, 13, '2023-04-04 11:54:00', '4', 1, 'NA', 'NA', 'NA', '2023-06-15 11:54:41', '2023-06-15 11:54:41'),
(63, 1, 13, '2023-04-05 11:54:00', '5.5', 1, 'NA', 'NA', 'NA', '2023-06-15 11:54:58', '2023-06-15 11:54:58'),
(64, 1, 13, '2023-04-06 11:55:00', '0', 2, 'NA', 'NA', 'NA', '2023-06-15 11:55:14', '2023-06-15 11:55:14'),
(65, 1, 13, '2023-04-10 11:55:00', '5.5', 1, 'NA', 'NA', 'NA', '2023-06-15 11:55:45', '2023-06-15 11:55:45'),
(66, 1, 13, '2023-04-11 12:02:00', '5.5', 1, 'NA', 'NA', 'NA', '2023-06-15 12:02:32', '2023-06-15 12:02:32'),
(67, 1, 13, '2023-04-12 12:02:00', '5.5', 1, 'NA', 'NA', 'NA', '2023-06-15 12:02:46', '2023-06-15 12:02:46'),
(68, 1, 13, '2023-04-13 12:02:00', '5.5', 1, 'NA', 'NA', 'NA', '2023-06-15 12:03:05', '2023-06-15 12:03:05'),
(69, 1, 13, '2023-04-14 12:03:00', '0', 2, 'NA', 'NA', 'NA', '2023-06-15 12:03:26', '2023-06-15 12:03:26'),
(70, 1, 13, '2023-04-17 12:03:00', '5.5', 1, 'NA', 'NA', 'NA', '2023-06-15 12:03:51', '2023-06-15 12:03:51'),
(71, 1, 13, '2023-04-18 12:04:00', '5.5', 1, 'NA', 'NA', 'NA', '2023-06-15 12:04:11', '2023-06-15 12:04:11'),
(72, 1, 13, '2023-04-19 12:04:00', '5.5', 1, 'NA', 'NA', 'NA', '2023-06-15 12:04:23', '2023-06-15 12:04:23'),
(73, 1, 13, '2023-04-20 12:04:00', '0', 2, 'NA', 'NA', 'NA', '2023-06-15 12:04:45', '2023-06-15 12:04:45'),
(74, 1, 13, '2023-04-24 13:26:00', '0', 2, 'NA', 'NA', 'NA', '2023-06-15 13:27:07', '2023-06-15 13:27:07'),
(75, 1, 13, '2023-04-26 13:27:00', '5', 1, 'NA', 'NA', 'NA', '2023-06-15 13:27:30', '2023-06-15 13:27:30'),
(76, 1, 13, '2023-04-27 13:27:00', '5.5', 1, 'NA', 'NA', 'NA', '2023-06-15 13:27:47', '2023-06-15 13:27:47'),
(77, 1, 13, '2023-04-28 13:27:00', '0', 2, 'NA', 'NA', 'NA', '2023-06-15 13:28:05', '2023-06-15 13:28:05'),
(78, 1, 13, '2023-05-01 13:28:00', '5.5', 1, 'NA', 'NA', 'NA', '2023-06-15 13:28:22', '2023-06-15 13:28:22'),
(79, 1, 13, '2023-05-02 13:28:00', '5.5', 1, 'NA', 'NA', 'NA', '2023-06-15 13:28:34', '2023-06-15 13:28:34'),
(80, 1, 13, '2023-05-03 13:28:00', '4.5', 2, 'NA', 'NA', 'NA', '2023-06-15 13:28:48', '2023-06-15 13:29:25'),
(81, 1, 13, '2023-05-04 13:29:00', '2', 1, 'NA', 'NA', 'NA', '2023-06-15 13:29:48', '2023-06-15 13:29:48'),
(82, 1, 13, '2023-05-05 13:33:00', '0', 2, 'NA', 'NA', 'NA', '2023-06-15 13:33:19', '2023-06-15 13:33:19'),
(83, 1, 13, '2023-05-11 13:33:00', '2', 2, 'NA', 'NA', 'NA', '2023-06-15 13:33:43', '2023-06-15 13:33:43'),
(84, 1, 13, '2023-05-12 13:33:00', '0', 2, 'NA', 'NA', 'NA', '2023-06-15 13:34:00', '2023-06-15 13:34:00'),
(85, 1, 13, '2023-05-15 13:34:00', '4.5', 1, 'NA', 'NA', 'NA', '2023-06-15 13:34:26', '2023-06-15 13:34:26'),
(86, 1, 13, '2023-05-16 13:34:00', '5', 1, 'NA', 'NA', 'NA', '2023-06-15 13:34:50', '2023-06-15 13:34:50'),
(87, 1, 13, '2023-05-17 13:34:00', '4', 1, 'NA', 'NA', 'NA', '2023-06-15 13:35:05', '2023-06-15 13:35:05'),
(88, 1, 13, '2023-05-18 13:35:00', '5.5', 1, 'NA', 'NA', 'NA', '2023-06-15 13:35:38', '2023-06-15 13:35:38'),
(89, 1, 13, '2023-05-19 13:35:00', '0', 2, 'NA', 'NA', 'NA', '2023-06-15 13:36:00', '2023-06-15 13:36:00'),
(90, 1, 13, '2023-05-24 13:36:00', '2', 2, 'NA', 'NA', 'NA', '2023-06-15 13:36:20', '2023-06-15 13:36:20'),
(91, 1, 13, '2023-05-25 13:36:00', '2', 1, 'NA', 'NA', 'NA', '2023-06-15 13:36:55', '2023-06-15 13:36:55'),
(92, 1, 13, '2023-06-02 13:36:00', '0', 2, 'NA', 'NA', 'NA', '2023-06-15 13:37:14', '2023-06-15 13:37:14'),
(93, 1, 13, '2023-05-29 13:37:00', '4.5', 1, 'NA', 'NA', 'NA', '2023-06-15 13:38:01', '2023-06-15 13:38:01'),
(94, 1, 13, '2023-05-30 13:38:00', '2.5', 1, 'NA', 'NA', 'NA', '2023-06-15 13:38:26', '2023-06-15 13:38:26'),
(95, 1, 13, '2023-05-31 13:38:00', '5', 1, 'NA', 'NA', 'NA', '2023-06-15 13:38:48', '2023-06-15 13:38:48'),
(96, 1, 13, '2023-06-02 13:38:00', '5', 1, 'NA', 'NA', 'NA', '2023-06-15 13:39:30', '2023-06-15 13:39:30'),
(97, 1, 13, '2023-06-05 13:39:00', '5', 7, 'NA', 'NA', 'NA', '2023-06-15 13:39:52', '2023-06-15 13:39:52'),
(98, 1, 13, '2023-06-06 13:39:00', '4', 1, 'NA', 'NA', 'NA', '2023-06-15 13:40:15', '2023-06-15 13:40:15'),
(99, 1, 13, '2023-06-07 13:40:00', '5.5', 10, 'NA', 'NA', 'NA', '2023-06-15 13:40:43', '2023-06-15 13:40:43'),
(100, 1, 13, '2023-06-08 13:40:00', '5.5', 10, 'NA', 'NA', 'NA', '2023-06-15 13:41:07', '2023-06-15 13:41:07'),
(101, 1, 13, '2023-06-09 13:41:00', '6.5', 10, 'NA', 'NA', 'NA', '2023-06-15 13:41:22', '2023-06-15 13:41:22'),
(102, 1, 13, '2023-06-12 13:41:00', '6.5', 10, 'NA', 'NA', 'NA', '2023-06-15 13:41:38', '2023-06-15 13:41:38'),
(103, 1, 13, '2023-06-13 13:41:00', '6.5', 10, 'NA', 'NA', 'NA', '2023-06-15 13:41:52', '2023-06-15 13:41:52'),
(104, 10, 3, '2023-06-12 14:43:00', '3.5', 1, 'dne', 'NA', 'NA', '2023-06-16 14:44:23', '2023-06-16 14:44:23'),
(105, 9, 1, '2023-06-30 14:18:00', '3', 2, 'this is on hold', 'NA', 'NA', '2023-06-30 14:18:31', '2023-06-30 14:18:31'),
(106, 9, 1, '2023-06-30 14:21:00', '0', 1, 'sip ', 'shshh', 'NA', '2023-06-30 14:21:42', '2023-06-30 14:21:42'),
(107, 9, 1, '2023-06-30 14:24:00', '2', 2, 'a', 'b', 'c', '2023-06-30 14:26:58', '2023-06-30 14:26:58'),
(108, 9, 1, '2023-06-30 14:24:00', '2', 2, 'a', 'b', 'c', '2023-06-30 14:28:16', '2023-06-30 14:28:16'),
(109, 9, 1, '2023-06-30 14:24:00', '2', 2, 'a', 'b', 'c', '2023-06-30 14:30:24', '2023-06-30 14:30:24'),
(110, 8, 1, '2023-06-30 14:37:00', '11', 2, 'a', 'b', 'c', '2023-06-30 14:38:02', '2023-06-30 14:38:02'),
(111, 8, 1, '2023-06-30 14:38:00', '2', 1, 'NA', 'NA', 'NA', '2023-06-30 14:38:29', '2023-06-30 14:38:29'),
(112, 8, 1, '2023-06-30 14:38:00', '3', 2, 'a', 'b', 'c', '2023-06-30 14:38:47', '2023-06-30 14:38:47'),
(113, 5, 15, '2023-07-05 19:37:00', '22', 1, 'work don here ', 'pending ', 'support', '2023-07-05 20:37:58', '2023-07-05 20:37:58'),
(114, 15, 0, '2023-07-06 12:04:00', '7', 1, 'done', 'pending', 'NA', '2023-07-06 12:06:44', '2023-07-06 12:06:44');

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
  `temp_planned_hrs` float(11,2) DEFAULT NULL,
  `temp_actual_hrs` float(11,2) NOT NULL,
  `remark` varchar(255) DEFAULT NULL,
  `activity_type` varchar(200) DEFAULT NULL,
  `details` text DEFAULT NULL,
  `dates` datetime DEFAULT NULL,
  `datetime` datetime NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `log_timing`
--

INSERT INTO `log_timing` (`id`, `ticket_id`, `user_id`, `assignee_id`, `c_status`, `temp_planned_hrs`, `temp_actual_hrs`, `remark`, `activity_type`, `details`, `dates`, `datetime`) VALUES
(1, 1, 2, 1, 2, NULL, 0.00, NULL, 'ADD_TICKET', NULL, '2023-06-14 12:12:55', '2023-06-14 15:42:55'),
(2, 1, 2, 13, 2, NULL, 0.00, NULL, 'ADD_TICKET', NULL, '2023-06-14 12:15:56', '2023-06-14 15:45:56'),
(3, 2, 2, 14, 6, NULL, 0.00, NULL, 'ADD_TICKET', NULL, '2023-06-14 13:30:00', '2023-06-14 17:00:00'),
(4, 3, 2, 16, 5, NULL, 0.00, NULL, 'ADD_TICKET', NULL, '2023-06-14 13:33:01', '2023-06-14 17:03:01'),
(5, 4, 2, 4, 5, NULL, 0.00, NULL, 'ADD_TICKET', NULL, '2023-06-14 14:03:06', '2023-06-14 17:33:06'),
(6, 5, 2, 15, 5, NULL, 0.00, NULL, 'ADD_TICKET', NULL, '2023-06-14 14:05:20', '2023-06-14 17:35:20'),
(7, 6, 2, 3, 5, NULL, 0.00, NULL, 'ADD_TICKET', NULL, '2023-06-14 14:08:47', '2023-06-14 17:38:47'),
(8, 6, 3, 3, 1, NULL, 0.00, '', 'ADD_LOG', '[\"6\",\"TCI-19103\",\"2023-06-12T12:00\",\"3.5\",\"1\",\"\\\"What is Done:- The issue has been successfully replicated on the alpha environment, and the cause has been identified.\\n\\n\",\"What is pending:- Testing of the solution and internal review. \",\"What support is required:- NA\\\"\",\"0\",\"wip\",\"0\",\"WIP\"]', '2023-06-12 12:00:00', '2023-06-14 17:46:33'),
(9, 6, 3, 3, 7, NULL, 0.00, '', 'ADD_LOG', '[\"6\",\"TCI-19103\",\"2023-06-13T12:00\",\"1.5\",\"7\",\"\\\"What is Done:- Updated Job Req logic to match that of the Index page. Pushed the code for review.\\nWhat is pending:- NA. \\nWhat support is required:- NA\\\"\",\"\",\"\",\"0\",\"cr\",\"0\",\"Code Review\"]', '2023-06-13 12:00:00', '2023-06-14 17:49:24'),
(10, 5, 15, 15, 6, NULL, 0.00, '', 'ADD_LOG', '[\"5\",\"TCI-18902\",\"2023-05-22T12:00\",\"1.5\",\"6\",\"\\\"What is done:- Analysed the requirement, faced an issue while running manage in nav.blade.php, fixed that. And ran into another table missing issue. \\nWhat is pending:-  Development.\\nWhat support is required:- while running the indeed settings page on manage, there is a table missing error (indeed_jobfeed_config) and the migation file doesn\'t simply add a table to the database, but runs a BrandSetupStep, so will not run migrate without consulting first. \\\"\\n\",\"\",\"\",\"0\",\"analysis\",\"0\",\"Analysis\"]', '2023-05-22 12:00:00', '2023-06-14 17:52:08'),
(11, 5, 15, 15, 1, NULL, 0.00, '', 'ADD_LOG', '[\"5\",\"TCI-18902\",\"2023-05-23T12:00\",\"3\",\"1\",\"\\\"What is done:- Analysed the requirement, faced an issue while running manage in nav.blade.php, fixed that. And ran into another table missing issue. \\nWhat is pending:-  Development.\\nWhat support is required:- while running the indeed settings page on manage, there is a table missing error (indeed_jobfeed_config) and the migation file doesn\'t simply add a table to the database, but runs a BrandSetupStep, so will not run migrate without consulting first. \\\"\\n\",\"\",\"\",\"0\",\"wip\",\"0\",\"WIP\"]', '2023-05-23 12:00:00', '2023-06-14 18:06:45'),
(12, 5, 15, 15, 1, NULL, 0.00, '', 'ADD_LOG', '[\"5\",\"TCI-18902\",\"2023-05-24T12:00\",\"3\",\"1\",\"\\\"What is done:- Created a dynamic table ui with individually editable & deletable rows, worked on adding different field types and their field options based on type. \\nWhat is pending:-  NA\\n\",\"\",\"What support is required:- NA \\\"\",\"0\",\"\",\"0\",\"0\"]', '2023-05-24 12:00:00', '2023-06-14 18:49:54'),
(13, 5, 15, 15, 1, NULL, 0.00, '', 'ADD_LOG', '[\"5\",\"TCI-18902\",\"2023-05-25T12:00\",\"3\",\"1\",\"Finished working on addition of different field types on frontend and finalized api response structures for the same as well.\",\" NA \\\"\",\" NA \\\"\",\"0\",\"\",\"0\",\"0\"]', '2023-05-25 12:00:00', '2023-06-14 18:52:04'),
(14, 5, 15, 15, 1, NULL, 0.00, '', 'ADD_LOG', '[\"5\",\"TCI-18902\",\"2023-05-26T12:00\",\"3.5\",\"1\",\" Finished working on the ui elements. Created the needed APIs, started working on data & database now.\\n\",\"NA\",\"NA\",\"0\",\"\",\"0\",\"0\"]', '2023-05-26 12:00:00', '2023-06-14 18:53:12'),
(15, 5, 15, 15, 1, NULL, 0.00, '', 'ADD_LOG', '[\"5\",\"TCI-18902\",\"2023-05-30T12:00\",\"3\",\"1\",\"\\\"What is done:-  As decided on call with mark, replaced select dropdown with select2 dropdown and started working on the apis for same.\\nWhat is pending:-  NA\\nWhat support is required:- NA \\\"\",\"\",\"\",\"0\",\"\",\"0\",\"0\"]', '2023-05-30 12:00:00', '2023-06-14 18:54:38'),
(16, 5, 15, 15, 1, NULL, 0.00, '', 'ADD_LOG', '[\"5\",\"TCI-18902\",\"2023-05-31T12:00\",\"2\",\"1\",\"\\\"What is done:-  Worked on the select2 apis for dropdowns.\\n\",\"\",\"What is pending:-  NA\\nWhat support is required:- NA \\\"\",\"0\",\"\",\"0\",\"0\"]', '2023-05-31 12:00:00', '2023-06-14 18:55:27'),
(17, 5, 15, 15, 1, NULL, 0.00, '', 'ADD_LOG', '[\"5\",\"TCI-18902\",\"2023-06-01T12:00\",\"2.5\",\"1\",\"\\\"What is done:-  Completed worked on select2 apis. Will start understanding config server & translation system.\\nWhat is pending:-  NA\\nWhat support is required:- NA \\\"\",\"\",\"\",\"0\",\"\",\"0\",\"0\"]', '2023-06-01 12:00:00', '2023-06-14 18:56:42'),
(18, 5, 15, 15, 1, NULL, 0.00, '', 'ADD_LOG', '[\"5\",\"TCI-18902\",\"2023-06-02T12:00\",\"2\",\"1\",\"\\\"What is done:- Worked with config server to translate the jobnames.\\nWhat is pending:-  NA\\nWhat support is required:- NA \\\"\",\"\",\"\",\"0\",\"\",\"0\",\"0\"]', '2023-06-02 12:00:00', '2023-06-14 18:57:43'),
(19, 5, 15, 15, 1, NULL, 0.00, '', 'ADD_LOG', '[\"5\",\"TCI-18902\",\"2023-06-05T12:00\",\"2\",\"1\",\"\\\"What is done:- Discussed on call with Mark about translations of job field names database side, which was not possible. So we decided on fetching job_field_mappings and custom_field jobnames separately and translating job_field_names and then then combining the results.\\nWhat is pending:-  NA\\nWhat support is required:- NA \\n\",\"\",\"\",\"0\",\"\",\"0\",\"0\"]', '2023-06-05 12:00:00', '2023-06-14 18:58:17'),
(20, 5, 15, 15, 1, NULL, 0.00, '', 'ADD_LOG', '[\"5\",\"TCI-18902\",\"2023-06-06T12:00\",\"1.5\",\"1\",\"\\\"What is done:- As Discussed on previous call with Mark about translations of job field names database side, which was not possible. So we decided on fetching job_field_mappings and custom_field jobnames separately and translating job_field_names and then then combining the results.\\nWhat is pending:-  NA\\nWhat support is required:- NA \\\"\\n\",\"\",\"\",\"0\",\"\",\"0\",\"0\"]', '2023-06-06 12:00:00', '2023-06-14 18:58:53'),
(21, 5, 15, 15, 1, NULL, 0.00, '', 'ADD_LOG', '[\"5\",\"TCI-18902\",\"2023-06-07T12:00\",\"1.5\",\"1\",\"\\\"What is done:- Worked on the selectionbox api for job_field_name.\\nWhat is pending:-  NA\\nWhat support is required:- NA \\\"\",\"\",\"\",\"0\",\"\",\"0\",\"0\"]', '2023-06-07 12:00:00', '2023-06-14 18:59:24'),
(22, 3, 16, 16, 7, NULL, 0.00, '', 'ADD_LOG', '[\"3\",\"TCI-18882\",\"2023-05-19T12:00\",\"4\",\"7\",\"\\\"What is done:- Worked on adding custom style for ul list item \\nWhat is pending:- NA\\nWhat support is required:- NA\\\"\\n\",\"\",\"\",\"0\",\"cr\",\"0\",\"Code Review\"]', '2023-05-19 12:00:00', '2023-06-14 19:51:26'),
(23, 3, 16, 16, 10, NULL, 0.00, '', 'ADD_LOG', '[\"3\",\"TCI-18882\",\"2023-05-26T12:00\",\"3\",\"10\",\"\\\"What is done:- Worked on compilled custom style for ul list item \\nWhat is pending:- NA\\nWhat support is required:- NA\\\"\",\"\",\"\",\"0\",\"cr1\",\"0\",\"Code review rework 1\"]', '2023-05-26 12:00:00', '2023-06-14 19:52:06'),
(24, 3, 16, 16, 10, NULL, 0.00, '', 'ADD_LOG', '[\"3\",\"TCI-18882\",\"2023-06-09T12:00\",\"1\",\"10\",\"\\\"What is done:- Worked on create fresh new branch from v.4.44 and  varifying the changes are already existing in v4.44\\nWhat is pending:- NA\\nWhat support is required:- NA\\\"\\n\",\"\",\"\",\"0\",\"cr1\",\"0\",\"Code review rework 1\"]', '2023-06-09 12:00:00', '2023-06-14 19:52:46'),
(25, 3, 16, 16, 1, NULL, 0.00, '', 'ADD_LOG', '[\"3\",\"TCI-18882\",\"2023-06-13T12:00\",\"3.5\",\"1\",\"\\\"What is done:- Resolved the inline style issues that are added to froala editor on run time.\\nWhat is pending:- Checking by enabling the configuration setting.\\nWhat support is required:-NA\\nRework Reason:-  froala editor have some issues with default configuration with in-line style\\\"\",\"\",\"\",\"0\",\"wip\",\"0\",\"WIP\"]', '2023-06-13 12:00:00', '2023-06-14 19:53:40'),
(26, 4, 4, 4, 1, NULL, 0.00, '', 'ADD_LOG', '[\"4\",\"TCI-17383\",\"2022-11-15T12:00\",\"4\",\"1\",\" I am working on adding attachment feature for candidate search.\",\"\",\"\",\"0\",\"wip\",\"0\",\"WIP\"]', '2022-11-15 12:00:00', '2023-06-15 07:10:35'),
(27, 4, 4, 4, 1, NULL, 0.00, '', 'ADD_LOG', '[\"4\",\"TCI-17383\",\"2023-11-16T12:00\",\"4.5\",\"1\",\" I have added functionality of attachment on candidate search page.I have tested it is working fine.\",\"\",\"\",\"0\",\"\",\"0\",\"0\"]', '2023-11-16 12:00:00', '2023-06-15 07:17:00'),
(28, 4, 4, 4, 1, NULL, 0.00, '', 'ADD_LOG', '[\"4\",\"TCI-17383\",\"2023-11-17T12:00\",\"1\",\"1\",\" I have completed feature and pushed code review.I am working shared feedack.\",\"\",\"\",\"0\",\"\",\"0\",\"0\"]', '2023-11-17 12:00:00', '2023-06-15 07:17:45'),
(29, 4, 4, 4, 1, NULL, 0.00, '', 'ADD_LOG', '[\"4\",\"TCI-17383\",\"2023-11-18T12:00\",\"1\",\"1\",\"I have fixed shared feedback and pushed code for review.\",\"\",\"\",\"0\",\"\",\"0\",\"0\"]', '2023-11-18 12:00:00', '2023-06-15 07:18:36'),
(30, 4, 4, 4, 1, NULL, 0.00, '', 'ADD_LOG', '[\"4\",\"TCI-17383\",\"2022-11-29T12:00\",\"3\",\"1\",\"I have fixed email attachment issue and pushed code for review.\",\"\",\"\",\"0\",\"\",\"0\",\"0\"]', '2022-11-29 12:00:00', '2023-06-15 07:20:00'),
(31, 4, 4, 4, 1, NULL, 0.00, 'NA', 'UPDATE_LOG', '[\"4\",\"TCI-17383\",\"2022-11-17T12:00\",\"1\",\"1\",\" I have completed feature and pushed code review.I am working shared feedack.\",\"NA\",\"NA\",\"21\",\"\",\"WIP\",\"WIP\"]', '2022-11-17 12:00:00', '2023-06-15 07:20:13'),
(32, 4, 4, 4, 1, NULL, 0.00, 'NA', 'UPDATE_LOG', '[\"4\",\"TCI-17383\",\"2022-11-16T12:00\",\"4.5\",\"1\",\" I have added functionality of attachment on candidate search page.I have tested it is working fine.\",\"NA\",\"NA\",\"20\",\"\",\"WIP\",\"WIP\"]', '2022-11-16 12:00:00', '2023-06-15 07:20:21'),
(33, 4, 4, 4, 1, NULL, 0.00, 'NA', 'UPDATE_LOG', '[\"4\",\"TCI-17383\",\"2022-11-18T12:00\",\"1\",\"1\",\"I have fixed shared feedback and pushed code for review.\",\"NA\",\"NA\",\"22\",\"\",\"WIP\",\"WIP\"]', '2022-11-18 12:00:00', '2023-06-15 07:20:31'),
(34, 4, 4, 4, 7, NULL, 0.00, 'cr', 'UPDATE_LOG', '[\"4\",\"TCI-17383\",\"2022-11-17T12:00\",\"1\",\"7\",\" I have completed feature and pushed code review.I am working shared feedack.\",\"NA\",\"NA\",\"21\",\"cr\",\"WIP\",\"Code Review\"]', '2022-11-17 12:00:00', '2023-06-15 07:28:34'),
(35, 4, 4, 4, 10, NULL, 0.00, 'cr1', 'UPDATE_LOG', '[\"4\",\"TCI-17383\",\"2022-11-18T12:00\",\"1\",\"10\",\"I have fixed shared feedback and pushed code for review.\",\"NA\",\"NA\",\"22\",\"cr1\",\"WIP\",\"Code review rework 1\"]', '2022-11-18 12:00:00', '2023-06-15 07:28:49'),
(36, 4, 4, 4, 7, NULL, 0.00, 'cr', 'UPDATE_LOG', '[\"4\",\"TCI-17383\",\"2022-11-29T12:00\",\"3\",\"7\",\"I have fixed email attachment issue and pushed code for review.\",\"NA\",\"NA\",\"23\",\"cr\",\"WIP\",\"Code Review\"]', '2022-11-29 12:00:00', '2023-06-15 07:29:00'),
(37, 4, 4, 4, 10, NULL, 0.00, '', 'ADD_LOG', '[\"4\",\"TCI-17383\",\"2023-03-27T12:00\",\"1\",\"10\",\" I have merged code with master and fixed merge conflict.\\n\",\"\",\"\",\"0\",\"cr1\",\"0\",\"Code review rework 1\"]', '2023-03-27 12:00:00', '2023-06-15 07:30:37'),
(38, 4, 4, 4, 10, NULL, 0.00, '', 'ADD_LOG', '[\"4\",\"TCI-17383\",\"2023-05-12T12:00\",\"0.5\",\"10\",\" I have added a new module email_template of attachment while creating the email template with attachment. \\n\",\"\",\"\",\"0\",\"\",\"0\",\"0\"]', '2023-05-12 12:00:00', '2023-06-15 07:31:38'),
(39, 4, 4, 4, 1, NULL, 0.00, '', 'ADD_LOG', '[\"4\",\"TCI-17383\",\"2023-05-26T12:00\",\"1\",\"1\",\"  Working on code optimzation feedback.\\n\",\"\",\"\",\"0\",\"wip\",\"0\",\"WIP\"]', '2023-05-26 12:00:00', '2023-06-15 07:33:00'),
(40, 4, 4, 4, 11, NULL, 0.00, '', 'ADD_LOG', '[\"4\",\"TCI-17383\",\"2023-05-29T12:00\",\"1.5\",\"11\",\"\\\"What is done:-  I have done changes as shared feedback on code review\\n\",\"\",\"\",\"0\",\"cr2\",\"0\",\"Code review rework 2\"]', '2023-05-29 12:00:00', '2023-06-15 07:34:41'),
(41, 4, 4, 4, 12, NULL, 0.00, '', 'ADD_LOG', '[\"4\",\"TCI-17383\",\"2023-05-31T12:00\",\"1\",\"12\",\"\\\"What is done:-  Optimized attachment code of email template as shared feedback\\nWhat is pending:- NA\\nWhat support is required:- NA\\nReson for rework: Optimized code of attachment\\\"\\n\",\"\",\"\",\"0\",\"cr3\",\"0\",\"Code review rework 3\"]', '2023-05-31 12:00:00', '2023-06-15 07:52:08'),
(42, 4, 4, 4, 12, NULL, 0.00, '', 'ADD_LOG', '[\"4\",\"TCI-17383\",\"2023-06-01T12:00\",\"1\",\"12\",\"Created new function for get attachment with attachment Id\",\"\",\"\",\"0\",\"\",\"0\",\"0\"]', '2023-06-01 12:00:00', '2023-06-15 07:53:20'),
(43, 4, 4, 4, 12, NULL, 0.00, '', 'ADD_LOG', '[\"4\",\"TCI-17383\",\"2023-06-07T12:00\",\"2\",\"12\",\"\\\"What is done:-  I have updated feedback as shared feedback,after discussion with Mark,I have revert changes and pushed code again.\\n\",\"\",\"\",\"0\",\"\",\"0\",\"0\"]', '2023-06-07 12:00:00', '2023-06-15 07:54:00'),
(44, 2, 14, 14, 6, NULL, 0.00, '', 'ADD_LOG', '[\"2\",\"TCI-19010\",\"2023-06-02T12:00\",\"2\",\"6\",\"\\\"What is done:- Analysed the ticket understood the task, working on a technical document. \\u00a0\",\"\",\"\",\"0\",\"\",\"0\",\"0\"]', '2023-06-02 12:00:00', '2023-06-15 09:57:21'),
(45, 2, 14, 14, 21, NULL, 0.00, '', 'ADD_LOG', '[\"2\",\"TCI-19010\",\"2023-06-05T12:00\",\"6\",\"21\",\"\\\"What is done:- Worked on the technical document and created it.  \\n\",\"What is pending:- Internal review.\\n\",\"What support is required:- NA\\\"\",\"0\",\"tc\",\"0\",\"Technical Approval required\"]', '2023-06-05 12:00:00', '2023-06-15 09:59:24'),
(46, 2, 14, 14, 21, NULL, 0.00, '', 'ADD_LOG', '[\"2\",\"TCI-19010\",\"2023-06-06T12:00\",\"2\",\"21\",\"\\\"What is done:- I have created the technical document Please find the link below for review. However, I am not able to configure SSO on my end so not able to understand the full flow. I  tried with Okta but was not able to setup it up.\\n\\nhttps:\\/\\/docs.google.com\\/document\\/d\\/1eyQGb7iMWpr5LiCl_LofLvgX4V0XqY22UjsyHr3g7Ig\\/edit#\",\"What is pending:- Review\\n\",\"What support is required:- NA\\\"\",\"0\",\"\",\"0\",\"0\"]', '2023-06-06 12:00:00', '2023-06-15 10:00:20'),
(47, 2, 14, 14, 5, NULL, 0.00, '', 'ADD_LOG', '[\"2\",\"TCI-19010\",\"2023-06-07T12:00\",\"1\",\"5\",\"\\\"What is done:- worked on  sso config setup as per comment in technical document , however not able to setup . There is no more ticket in backlog to work so i was ideal or work on mis ticket for 1.5 hours , So my total worked time is less then 7.5 hours.\\n\",\"What is pending:- need to setup sso config\",\"What support is required:- Please add some ticket in backlog\\\"\",\"0\",\"rfd\",\"0\",\"Ready For Development\"]', '2023-06-07 12:00:00', '2023-06-15 10:02:17'),
(48, 2, 14, 14, 1, NULL, 0.00, '', 'ADD_LOG', '[\"2\",\"TCI-19010\",\"2023-06-08T12:00\",\"6.5\",\"1\",\"\\\"What is done:- worked on  SSO setup and completed with provided URL. Created migration and model in v2 codebase, wrote code for insert errors in table, working on view to display errors in SSO module.  \\n\",\"What is pending:- display error in SSO module\\nWhat support is required:- NA\\\"\",\"\",\"0\",\"wip\",\"0\",\"WIP\"]', '2023-06-08 12:00:00', '2023-06-15 10:05:31'),
(49, 2, 14, 14, 1, NULL, 0.00, '', 'ADD_LOG', '[\"2\",\"TCI-19010\",\"2023-06-09T12:00\",\"6.5\",\"1\",\"What is done:- worked on log error display section with delete functionality and completed it. started work on refresh config section. \",\"What is pending:- NA\\n\",\"What support is required:- NA\\\"\",\"0\",\"\",\"0\",\"0\"]', '2023-06-09 12:00:00', '2023-06-15 10:11:42'),
(50, 2, 14, 14, 1, NULL, 0.00, '', 'ADD_LOG', '[\"2\",\"TCI-19010\",\"2023-06-12T12:00\",\"6.5\",\"1\",\"\\\"What is done:- I worked on the refresh config section and completed it. I am facing some issues in testing because i am not able to open sso module for login. \",\"What is pending:- Need to test changes for login and refresh sso.\\n\",\"What support is required:- NA\\\"\",\"0\",\"\",\"0\",\"0\"]', '2023-06-12 12:00:00', '2023-06-15 10:12:29'),
(51, 2, 14, 14, 1, NULL, 0.00, '', 'ADD_LOG', '[\"2\",\"TCI-19010\",\"2023-06-14T12:00\",\"4.5\",\"1\",\"\\\"What is done:- tested the entire flow and changes and created merge request for code review. \\nWhat is pending:- Internal code review. \",\"\",\"What support is required:- NA\\\"\",\"0\",\"\",\"0\",\"0\"]', '2023-06-14 12:00:00', '2023-06-15 10:13:12'),
(52, 1, 13, 13, 6, NULL, 0.00, '', 'ADD_LOG', '[\"1\",\"TCI-18100\",\"2023-02-24T12:00\",\"3\",\"6\",\"\",\"\",\"\",\"0\",\"a\",\"0\",\"Analysis\"]', '2023-02-24 12:00:00', '2023-06-15 10:39:08'),
(53, 1, 13, 13, 6, NULL, 0.00, '', 'ADD_LOG', '[\"1\",\"TCI-18100\",\"2023-02-27T12:00\",\"6\",\"6\",\"\",\"\",\"\",\"0\",\"\",\"0\",\"0\"]', '2023-02-27 12:00:00', '2023-06-15 10:39:41'),
(54, 1, 13, 13, 6, NULL, 0.00, '', 'ADD_LOG', '[\"1\",\"TCI-18100\",\"2023-02-28T12:00\",\"3.5\",\"6\",\"\",\"\",\"\",\"0\",\"\",\"0\",\"0\"]', '2023-02-28 12:00:00', '2023-06-15 10:40:09'),
(55, 1, 13, 13, 6, NULL, 0.00, '', 'ADD_LOG', '[\"1\",\"TCI-18100\",\"2023-03-01T12:00\",\"6\",\"6\",\"\",\"\",\"\",\"0\",\"\",\"0\",\"0\"]', '2023-03-01 12:00:00', '2023-06-15 10:48:28'),
(56, 1, 13, 13, 2, NULL, 0.00, '', 'ADD_LOG', '[\"1\",\"TCI-18100\",\"2023-03-02T12:00\",\"0\",\"2\",\"\\\"What is done:- Provided flow and implementation plan, Waiting for the confirmation, So currently putting on hold.\\nWhat is pending:- Needs to start work if confirmed.\\nWhat support is required:- NA\\\"\",\"\",\"\",\"0\",\"hold\",\"0\",\"Hold\"]', '2023-03-02 12:00:00', '2023-06-15 10:49:19'),
(57, 1, 13, 13, 1, NULL, 0.00, '', 'ADD_LOG', '[\"1\",\"TCI-18100\",\"2023-03-06T12:00\",\"1.5\",\"1\",\"\",\"\",\"\",\"0\",\"wip\",\"0\",\"WIP\"]', '2023-03-06 12:00:00', '2023-06-15 10:50:01'),
(58, 1, 13, 13, 1, NULL, 0.00, '', 'ADD_LOG', '[\"1\",\"TCI-18100\",\"2023-03-09T12:00\",\"5\",\"1\",\"\",\"\",\"\",\"0\",\"\",\"0\",\"0\"]', '2023-03-09 12:00:00', '2023-06-15 10:51:23'),
(59, 1, 13, 13, 2, NULL, 0.00, '', 'ADD_LOG', '[\"1\",\"TCI-18100\",\"2023-03-10T12:00\",\"\",\"2\",\"Satus changed: Put on hold, since needs to work on bugs\",\"\",\"\",\"0\",\"hold\",\"0\",\"Hold\"]', '2023-03-10 12:00:00', '2023-06-15 10:51:58'),
(60, 1, 13, 13, 1, NULL, 0.00, '', 'ADD_LOG', '[\"1\",\"TCI-18100\",\"2023-03-13T12:00\",\"6\",\"1\",\"\",\"\",\"\",\"0\",\"wip\",\"0\",\"WIP\"]', '2023-03-13 12:00:00', '2023-06-15 10:53:05'),
(61, 1, 13, 13, 1, NULL, 0.00, '', 'ADD_LOG', '[\"1\",\"TCI-18100\",\"2023-03-14T12:00\",\"5\",\"1\",\"\",\"\",\"\",\"0\",\"\",\"0\",\"0\"]', '2023-03-14 12:00:00', '2023-06-15 10:53:42'),
(62, 1, 13, 13, 1, NULL, 0.00, '', 'ADD_LOG', '[\"1\",\"TCI-18100\",\"2023-03-15T12:00\",\"4\",\"1\",\"\",\"\",\"\",\"0\",\"\",\"0\",\"0\"]', '2023-03-15 12:00:00', '2023-06-15 10:54:32'),
(63, 1, 13, 13, 1, NULL, 0.00, '', 'ADD_LOG', '[\"1\",\"TCI-18100\",\"2023-03-16T12:00\",\"4\",\"1\",\"\",\"\",\"\",\"0\",\"\",\"0\",\"0\"]', '2023-03-16 12:00:00', '2023-06-15 10:55:06'),
(64, 1, 13, 13, 2, NULL, 0.00, '', 'ADD_LOG', '[\"1\",\"TCI-18100\",\"2023-03-17T12:00\",\"0\",\"2\",\"status changed\",\"\",\"\",\"0\",\"hold\",\"0\",\"Hold\"]', '2023-03-17 12:00:00', '2023-06-15 10:56:12'),
(65, 1, 13, 13, 1, NULL, 0.00, '', 'ADD_LOG', '[\"1\",\"TCI-18100\",\"2023-03-20T12:00\",\"6\",\"1\",\"\",\"\",\"\",\"0\",\"wip\",\"0\",\"WIP\"]', '2023-03-20 12:00:00', '2023-06-15 10:57:23'),
(66, 1, 13, 13, 1, NULL, 0.00, '', 'ADD_LOG', '[\"1\",\"TCI-18100\",\"2023-03-21T12:00\",\"6\",\"1\",\"\",\"\",\"\",\"0\",\"\",\"0\",\"0\"]', '2023-03-21 12:00:00', '2023-06-15 11:44:02'),
(67, 1, 13, 13, 1, NULL, 0.00, '', 'ADD_LOG', '[\"1\",\"TCI-18100\",\"2023-03-22T12:00\",\"6\",\"1\",\"\",\"\",\"\",\"0\",\"\",\"0\",\"0\"]', '2023-03-22 12:00:00', '2023-06-15 11:44:30'),
(68, 1, 13, 13, 1, NULL, 0.00, '', 'ADD_LOG', '[\"1\",\"TCI-18100\",\"2023-03-23T12:00\",\"5.5\",\"1\",\"\",\"\",\"\",\"0\",\"\",\"0\",\"0\"]', '2023-03-23 12:00:00', '2023-06-15 11:45:03'),
(69, 1, 13, 13, 2, NULL, 0.00, '', 'ADD_LOG', '[\"1\",\"TCI-18100\",\"2023-03-24T12:00\",\"0\",\"2\",\"\",\"\",\"\",\"0\",\"hold statsus changed\",\"0\",\"Hold\"]', '2023-03-24 12:00:00', '2023-06-15 11:45:53'),
(70, 1, 13, 13, 1, NULL, 0.00, '', 'ADD_LOG', '[\"1\",\"TCI-18100\",\"2023-03-27\",\"6\",\"1\",\"\",\"\",\"\",\"0\",\"wip\",\"0\",\"WIP\"]', '2023-03-27 00:00:00', '2023-06-15 11:51:17'),
(71, 1, 13, 13, 1, NULL, 0.00, '', 'ADD_LOG', '[\"1\",\"TCI-18100\",\"2023-03-28\",\"6\",\"1\",\"\",\"\",\"\",\"0\",\"\",\"0\",\"0\"]', '2023-03-28 00:00:00', '2023-06-15 11:51:37'),
(72, 1, 13, 13, 1, NULL, 0.00, '', 'ADD_LOG', '[\"1\",\"TCI-18100\",\"2023-03-29\",\"6\",\"1\",\"\",\"\",\"\",\"0\",\"\",\"0\",\"0\"]', '2023-03-29 00:00:00', '2023-06-15 11:51:54'),
(73, 1, 13, 13, 2, NULL, 0.00, '', 'ADD_LOG', '[\"1\",\"TCI-18100\",\"2023-03-31\",\"0\",\"2\",\"\",\"\",\"\",\"0\",\"hold\",\"0\",\"Hold\"]', '2023-03-31 00:00:00', '2023-06-15 11:52:18'),
(74, 1, 13, 13, 1, NULL, 0.00, '', 'ADD_LOG', '[\"1\",\"TCI-18100\",\"2023-04-03\",\"4\",\"1\",\"\",\"\",\"\",\"0\",\"wip\",\"0\",\"WIP\"]', '2023-04-03 00:00:00', '2023-06-15 11:53:28'),
(75, 1, 13, 13, 1, NULL, 0.00, '', 'ADD_LOG', '[\"1\",\"TCI-18100\",\"2023-04-04T11:54\",\"4\",\"1\",\"\",\"\",\"\",\"0\",\"\",\"0\",\"0\"]', '2023-04-04 11:54:00', '2023-06-15 11:54:41'),
(76, 1, 13, 13, 1, NULL, 0.00, '', 'ADD_LOG', '[\"1\",\"TCI-18100\",\"2023-04-05T11:54\",\"5.5\",\"1\",\"\",\"\",\"\",\"0\",\"\",\"0\",\"0\"]', '2023-04-05 11:54:00', '2023-06-15 11:54:58'),
(77, 1, 13, 13, 2, NULL, 0.00, '', 'ADD_LOG', '[\"1\",\"TCI-18100\",\"2023-04-06T11:55\",\"0\",\"2\",\"\",\"\",\"\",\"0\",\"hold\",\"0\",\"Hold\"]', '2023-04-06 11:55:00', '2023-06-15 11:55:14'),
(78, 1, 13, 13, 1, NULL, 0.00, '', 'ADD_LOG', '[\"1\",\"TCI-18100\",\"2023-04-10T11:55\",\"5.5\",\"1\",\"\",\"\",\"\",\"0\",\"wip\",\"0\",\"WIP\"]', '2023-04-10 11:55:00', '2023-06-15 11:55:45'),
(79, 1, 13, 13, 1, NULL, 0.00, '', 'ADD_LOG', '[\"1\",\"TCI-18100\",\"2023-04-11T12:02\",\"5.5\",\"1\",\"\",\"\",\"\",\"0\",\"\",\"0\",\"0\"]', '2023-04-11 12:02:00', '2023-06-15 12:02:32'),
(80, 1, 13, 13, 1, NULL, 0.00, '', 'ADD_LOG', '[\"1\",\"TCI-18100\",\"2023-04-12T12:02\",\"5.5\",\"1\",\"\",\"\",\"\",\"0\",\"\",\"0\",\"0\"]', '2023-04-12 12:02:00', '2023-06-15 12:02:46'),
(81, 1, 13, 13, 1, NULL, 0.00, '', 'ADD_LOG', '[\"1\",\"TCI-18100\",\"2023-04-13T12:02\",\"5.5\",\"1\",\"\",\"\",\"\",\"0\",\"\",\"0\",\"0\"]', '2023-04-13 12:02:00', '2023-06-15 12:03:05'),
(82, 1, 13, 13, 2, NULL, 0.00, '', 'ADD_LOG', '[\"1\",\"TCI-18100\",\"2023-04-14T12:03\",\"0\",\"2\",\"\",\"\",\"\",\"0\",\"hold\",\"0\",\"Hold\"]', '2023-04-14 12:03:00', '2023-06-15 12:03:26'),
(83, 1, 13, 13, 1, NULL, 0.00, '', 'ADD_LOG', '[\"1\",\"TCI-18100\",\"2023-04-17T12:03\",\"5.5\",\"1\",\"\",\"\",\"\",\"0\",\"wip\",\"0\",\"WIP\"]', '2023-04-17 12:03:00', '2023-06-15 12:03:51'),
(84, 1, 13, 13, 1, NULL, 0.00, '', 'ADD_LOG', '[\"1\",\"TCI-18100\",\"2023-04-18T12:04\",\"5.5\",\"1\",\"\",\"\",\"\",\"0\",\"\",\"0\",\"0\"]', '2023-04-18 12:04:00', '2023-06-15 12:04:11'),
(85, 1, 13, 13, 1, NULL, 0.00, '', 'ADD_LOG', '[\"1\",\"TCI-18100\",\"2023-04-19T12:04\",\"5.5\",\"1\",\"\",\"\",\"\",\"0\",\"\",\"0\",\"0\"]', '2023-04-19 12:04:00', '2023-06-15 12:04:23'),
(86, 1, 13, 13, 2, NULL, 0.00, '', 'ADD_LOG', '[\"1\",\"TCI-18100\",\"2023-04-20T12:04\",\"0\",\"2\",\"\",\"\",\"\",\"0\",\"hold\",\"0\",\"Hold\"]', '2023-04-20 12:04:00', '2023-06-15 12:04:45'),
(87, 1, 13, 13, 2, NULL, 0.00, '', 'ADD_LOG', '[\"1\",\"TCI-18100\",\"2023-04-24T13:26\",\"0\",\"2\",\"\",\"\",\"\",\"0\",\"\",\"0\",\"0\"]', '2023-04-24 13:26:00', '2023-06-15 13:27:07'),
(88, 1, 13, 13, 1, NULL, 0.00, '', 'ADD_LOG', '[\"1\",\"TCI-18100\",\"2023-04-26T13:27\",\"5\",\"1\",\"\",\"\",\"\",\"0\",\"wip\",\"0\",\"WIP\"]', '2023-04-26 13:27:00', '2023-06-15 13:27:30'),
(89, 1, 13, 13, 1, NULL, 0.00, '', 'ADD_LOG', '[\"1\",\"TCI-18100\",\"2023-04-27T13:27\",\"5.5\",\"1\",\"\",\"\",\"\",\"0\",\"\",\"0\",\"0\"]', '2023-04-27 13:27:00', '2023-06-15 13:27:47'),
(90, 1, 13, 13, 2, NULL, 0.00, '', 'ADD_LOG', '[\"1\",\"TCI-18100\",\"2023-04-28T13:27\",\"0\",\"2\",\"\",\"\",\"\",\"0\",\"hold\",\"0\",\"Hold\"]', '2023-04-28 13:27:00', '2023-06-15 13:28:05'),
(91, 1, 13, 13, 1, NULL, 0.00, '', 'ADD_LOG', '[\"1\",\"TCI-18100\",\"2023-05-01T13:28\",\"5.5\",\"1\",\"\",\"\",\"\",\"0\",\"wip\",\"0\",\"WIP\"]', '2023-05-01 13:28:00', '2023-06-15 13:28:22'),
(92, 1, 13, 13, 1, NULL, 0.00, '', 'ADD_LOG', '[\"1\",\"TCI-18100\",\"2023-05-02T13:28\",\"5.5\",\"1\",\"\",\"\",\"\",\"0\",\"\",\"0\",\"0\"]', '2023-05-02 13:28:00', '2023-06-15 13:28:34'),
(93, 1, 13, 13, 1, NULL, 0.00, '', 'ADD_LOG', '[\"1\",\"TCI-18100\",\"2023-05-03T13:28\",\"4.5\",\"1\",\"\",\"\",\"\",\"0\",\"\",\"0\",\"0\"]', '2023-05-03 13:28:00', '2023-06-15 13:28:48'),
(94, 1, 13, 13, 2, NULL, 0.00, 'hold', 'UPDATE_LOG', '[\"1\",\"TCI-18100\",\"2023-05-03T13:28\",\"4.5\",\"2\",\"NA\",\"NA\",\"NA\",\"80\",\"hold\",\"WIP\",\"Hold\"]', '2023-05-03 13:28:00', '2023-06-15 13:29:25'),
(95, 1, 13, 13, 1, NULL, 0.00, '', 'ADD_LOG', '[\"1\",\"TCI-18100\",\"2023-05-04T13:29\",\"2\",\"1\",\"\",\"\",\"\",\"0\",\"wip\",\"0\",\"WIP\"]', '2023-05-04 13:29:00', '2023-06-15 13:29:48'),
(96, 1, 13, 13, 2, NULL, 0.00, '', 'ADD_LOG', '[\"1\",\"TCI-18100\",\"2023-05-05T13:33\",\"0\",\"2\",\"\",\"\",\"\",\"0\",\"hold\",\"0\",\"Hold\"]', '2023-05-05 13:33:00', '2023-06-15 13:33:19'),
(97, 1, 13, 13, 2, NULL, 0.00, '', 'ADD_LOG', '[\"1\",\"TCI-18100\",\"2023-05-11T13:33\",\"2\",\"2\",\"\",\"\",\"\",\"0\",\"\",\"0\",\"0\"]', '2023-05-11 13:33:00', '2023-06-15 13:33:43'),
(98, 1, 13, 13, 2, NULL, 0.00, '', 'ADD_LOG', '[\"1\",\"TCI-18100\",\"2023-05-12T13:33\",\"0\",\"2\",\"\",\"\",\"\",\"0\",\"\",\"0\",\"0\"]', '2023-05-12 13:33:00', '2023-06-15 13:34:00'),
(99, 1, 13, 13, 1, NULL, 0.00, '', 'ADD_LOG', '[\"1\",\"TCI-18100\",\"2023-05-15T13:34\",\"4.5\",\"1\",\"\",\"\",\"\",\"0\",\"wip\",\"0\",\"WIP\"]', '2023-05-15 13:34:00', '2023-06-15 13:34:26'),
(100, 1, 13, 13, 1, NULL, 0.00, '', 'ADD_LOG', '[\"1\",\"TCI-18100\",\"2023-05-16T13:34\",\"5\",\"1\",\"\",\"\",\"\",\"0\",\"\",\"0\",\"0\"]', '2023-05-16 13:34:00', '2023-06-15 13:34:50'),
(101, 1, 13, 13, 1, NULL, 0.00, '', 'ADD_LOG', '[\"1\",\"TCI-18100\",\"2023-05-17T13:34\",\"4\",\"1\",\"\",\"\",\"\",\"0\",\"\",\"0\",\"0\"]', '2023-05-17 13:34:00', '2023-06-15 13:35:05'),
(102, 1, 13, 13, 1, NULL, 0.00, '', 'ADD_LOG', '[\"1\",\"TCI-18100\",\"2023-05-18T13:35\",\"5.5\",\"1\",\"\",\"\",\"\",\"0\",\"\",\"0\",\"0\"]', '2023-05-18 13:35:00', '2023-06-15 13:35:38'),
(103, 1, 13, 13, 2, NULL, 0.00, '', 'ADD_LOG', '[\"1\",\"TCI-18100\",\"2023-05-19T13:35\",\"0\",\"2\",\"\",\"\",\"\",\"0\",\"hold\",\"0\",\"Hold\"]', '2023-05-19 13:35:00', '2023-06-15 13:36:00'),
(104, 1, 13, 13, 2, NULL, 0.00, '', 'ADD_LOG', '[\"1\",\"TCI-18100\",\"2023-05-24T13:36\",\"2\",\"2\",\"\",\"\",\"\",\"0\",\"\",\"0\",\"0\"]', '2023-05-24 13:36:00', '2023-06-15 13:36:20'),
(105, 1, 13, 13, 1, NULL, 0.00, '', 'ADD_LOG', '[\"1\",\"TCI-18100\",\"2023-05-25T13:36\",\"2\",\"1\",\"\",\"\",\"\",\"0\",\"wip\",\"0\",\"WIP\"]', '2023-05-25 13:36:00', '2023-06-15 13:36:55'),
(106, 1, 13, 13, 2, NULL, 0.00, '', 'ADD_LOG', '[\"1\",\"TCI-18100\",\"2023-06-02T13:36\",\"0\",\"2\",\"\",\"\",\"\",\"0\",\"hold\",\"0\",\"Hold\"]', '2023-06-02 13:36:00', '2023-06-15 13:37:14'),
(107, 1, 13, 13, 1, NULL, 0.00, '', 'ADD_LOG', '[\"1\",\"TCI-18100\",\"2023-05-29T13:37\",\"4.5\",\"1\",\"\",\"\",\"\",\"0\",\"wip\",\"0\",\"WIP\"]', '2023-05-29 13:37:00', '2023-06-15 13:38:01'),
(108, 1, 13, 13, 1, NULL, 0.00, '', 'ADD_LOG', '[\"1\",\"TCI-18100\",\"2023-05-30T13:38\",\"2.5\",\"1\",\"\",\"\",\"\",\"0\",\"\",\"0\",\"0\"]', '2023-05-30 13:38:00', '2023-06-15 13:38:26'),
(109, 1, 13, 13, 1, NULL, 0.00, '', 'ADD_LOG', '[\"1\",\"TCI-18100\",\"2023-05-31T13:38\",\"5\",\"1\",\"\",\"\",\"\",\"0\",\"\",\"0\",\"0\"]', '2023-05-31 13:38:00', '2023-06-15 13:38:48'),
(110, 1, 13, 13, 1, NULL, 0.00, '', 'ADD_LOG', '[\"1\",\"TCI-18100\",\"2023-06-02T13:38\",\"5\",\"1\",\"\",\"\",\"\",\"0\",\"\",\"0\",\"0\"]', '2023-06-02 13:38:00', '2023-06-15 13:39:30'),
(111, 1, 13, 13, 7, NULL, 0.00, '', 'ADD_LOG', '[\"1\",\"TCI-18100\",\"2023-06-05T13:39\",\"5\",\"7\",\"\",\"\",\"\",\"0\",\"cr\",\"0\",\"Code Review\"]', '2023-06-05 13:39:00', '2023-06-15 13:39:52'),
(112, 1, 13, 13, 1, NULL, 0.00, '', 'ADD_LOG', '[\"1\",\"TCI-18100\",\"2023-06-06T13:39\",\"4\",\"1\",\"\",\"\",\"\",\"0\",\"wip\",\"0\",\"WIP\"]', '2023-06-06 13:39:00', '2023-06-15 13:40:15'),
(113, 1, 13, 13, 10, NULL, 0.00, '', 'ADD_LOG', '[\"1\",\"TCI-18100\",\"2023-06-07T13:40\",\"5.5\",\"10\",\"\",\"\",\"\",\"0\",\"cr1\",\"0\",\"Code review rework 1\"]', '2023-06-07 13:40:00', '2023-06-15 13:40:43'),
(114, 1, 13, 13, 10, NULL, 0.00, '', 'ADD_LOG', '[\"1\",\"TCI-18100\",\"2023-06-08T13:40\",\"5.5\",\"10\",\"\",\"\",\"\",\"0\",\"\",\"0\",\"0\"]', '2023-06-08 13:40:00', '2023-06-15 13:41:07'),
(115, 1, 13, 13, 10, NULL, 0.00, '', 'ADD_LOG', '[\"1\",\"TCI-18100\",\"2023-06-09T13:41\",\"6.5\",\"10\",\"\",\"\",\"\",\"0\",\"\",\"0\",\"0\"]', '2023-06-09 13:41:00', '2023-06-15 13:41:22'),
(116, 1, 13, 13, 10, NULL, 0.00, '', 'ADD_LOG', '[\"1\",\"TCI-18100\",\"2023-06-12T13:41\",\"6.5\",\"10\",\"\",\"\",\"\",\"0\",\"\",\"0\",\"0\"]', '2023-06-12 13:41:00', '2023-06-15 13:41:38'),
(117, 1, 13, 13, 10, NULL, 0.00, '', 'ADD_LOG', '[\"1\",\"TCI-18100\",\"2023-06-13T13:41\",\"6.5\",\"10\",\"\",\"\",\"\",\"0\",\"\",\"0\",\"0\"]', '2023-06-13 13:41:00', '2023-06-15 13:41:52'),
(118, 7, 2, 1, 5, NULL, 0.00, NULL, 'ADD_TICKET', NULL, '2023-06-15 18:26:37', '2023-06-15 21:56:37'),
(119, 8, 2, 1, 5, NULL, 0.00, NULL, 'ADD_TICKET', NULL, '2023-06-15 18:27:52', '2023-06-15 21:57:52'),
(120, 8, 2, 1, 5, NULL, 0.00, NULL, 'UPDATE_TICKET', NULL, '2023-06-15 18:31:23', '2023-06-15 22:01:23'),
(121, 9, 2, 1, 5, NULL, 0.00, NULL, 'ADD_TICKET', NULL, '2023-06-15 19:00:32', '2023-06-15 22:30:32'),
(122, 9, 1, 1, 5, NULL, 0.00, NULL, 'UPDATE_TICKET', NULL, '2023-06-16 10:56:07', '2023-06-16 14:26:07'),
(123, 10, 2, 3, 5, NULL, 0.00, NULL, 'ADD_TICKET', NULL, '2023-06-16 11:13:01', '2023-06-16 14:43:01'),
(124, 10, 3, 3, 1, NULL, 0.00, '', 'ADD_LOG', '[\"10\",\"t-amit\",\"2023-06-12T14:43\",\"3.5\",\"1\",\"dne\",\"\",\"\",\"0\",\"wip changd\",\"0\",\"WIP\"]', '2023-06-12 14:43:00', '2023-06-16 14:44:23'),
(125, 9, 2, 1, 5, NULL, 0.00, NULL, 'UPDATE_TICKET', NULL, '2023-06-16 12:16:08', '2023-06-16 15:46:08'),
(126, 11, 2, 1, 5, NULL, 0.00, NULL, 'ADD_TICKET', NULL, '2023-06-16 13:09:57', '2023-06-16 16:39:57'),
(127, 9, 1, 1, 2, NULL, 0.00, '', 'ADD_LOG', '[\"9\",\"t4\",\"2023-06-30T14:18\",\"3\",\"2\",\"this is on hold\",\"\",\"\",\"0\",\"HHHHHHHHHH hoold on this time\",\"0\",\"Hold\"]', '2023-06-30 14:18:00', '2023-06-30 14:18:31'),
(128, 9, 1, 1, 1, NULL, 0.00, '', 'ADD_LOG', '[\"9\",\"t4\",\"2023-06-30T14:21\",\"\",\"1\",\"sip \",\"shshh\",\"\",\"0\",\"WIP\",\"Hold\",\"WIP\"]', '2023-06-30 14:21:00', '2023-06-30 14:21:42'),
(129, 9, 1, 1, 2, NULL, 0.00, 'HHHHH111 HOLD AGAIN', 'ADD_LOG', '[\"9\",\"t4\",\"2023-06-30T14:24\",\"2\",\"2\",\"a\",\"b\",\"c\",\"0\",\"HHHHH111 HOLD AGAIN\",\"0\",\"Hold\"]', '2023-06-30 14:24:00', '2023-06-30 14:30:24'),
(130, 8, 1, 1, 2, NULL, 0.00, 'HOLD STATUS', 'ADD_LOG', '[\"8\",\"t2\",\"2023-06-30T14:37\",\"11\",\"2\",\"a\",\"b\",\"c\",\"0\",\"HOLD STATUS\",\"0\",\"Hold\"]', '2023-06-30 14:37:00', '2023-06-30 14:38:02'),
(131, 8, 1, 1, 1, NULL, 0.00, 'some worked', 'ADD_LOG', '[\"8\",\"t2\",\"2023-06-30T14:38\",\"2\",\"1\",\"\",\"\",\"\",\"0\",\"some worked\",\"0\",\"WIP\"]', '2023-06-30 14:38:00', '2023-06-30 14:38:29'),
(132, 8, 1, 1, 2, NULL, 0.00, 'HOLD2', 'ADD_LOG', '[\"8\",\"t2\",\"2023-06-30T14:38\",\"3\",\"2\",\"a\",\"b\",\"c\",\"0\",\"HOLD2\",\"0\",\"Hold\"]', '2023-06-30 14:38:00', '2023-06-30 14:38:47'),
(133, 16, 2, 15, 5, NULL, 0.00, NULL, 'ADD_TICKET', NULL, '2023-07-04 07:43:26', '2023-07-04 11:13:26'),
(134, 5, 15, 15, 1, NULL, 0.00, 'NA', 'ADD_LOG', '[\"5\",\"TCI-18902\",\"2023-07-05T19:37\",\"22\",\"1\",\"work don here \",\"pending \",\"support\",\"0\",\"\",\"0\",\"0\",\"12\"]', '2023-07-05 19:37:00', '2023-07-05 20:37:58'),
(135, 5, 15, 15, 1, NULL, 0.00, 'NA', 'UPDATE_LOG', '[\"5\",\"TCI-18902\",\"2023-07-05T20:40\",\"9\",\"1\",\"mmm\",\"\",\"\",\"0\",\"\",\"0\",\"0\",\"15\"]', '2023-07-05 20:40:00', '2023-07-06 12:02:04'),
(136, 15, 15, 0, 1, NULL, 0.00, 'NA', 'ADD_LOG', '[\"5\",\"TCI-18902\",\"2023-07-06T12:04\",\"7\",\"1\",\"done\",\"pending\",\"\",\"0\",\"\",\"0\",\"0\",\"15\"]', '2023-07-06 12:04:00', '2023-07-06 12:06:44');

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
(11, 1, 1),
(12, 2, 1),
(13, 1, 2),
(14, 2, 2),
(15, 3, 2),
(16, 2, 13),
(17, 2, 3),
(18, 2, 4),
(20, 2, 15),
(22, 2, 14),
(23, 2, 16),
(26, 2, 22),
(27, 1, 23);

-- --------------------------------------------------------

--
-- Table structure for table `tickets`
--

CREATE TABLE `tickets` (
  `id` int(11) NOT NULL,
  `ticket_id` varchar(100) NOT NULL,
  `parent_id` int(10) DEFAULT 0,
  `zira_link` text DEFAULT NULL,
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
  `actual_hrs` float(11,2) DEFAULT 0.00,
  `created_at` datetime NOT NULL DEFAULT current_timestamp(),
  `updated_at` datetime DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `tickets`
--

INSERT INTO `tickets` (`id`, `ticket_id`, `parent_id`, `zira_link`, `type_id`, `project_id`, `c_status`, `wip_start_datetime`, `wip_close_datetime`, `assignee_id`, `assigned_date`, `plan_start_date`, `plan_end_date`, `actual_start_date`, `actual_end_date`, `planned_hrs`, `actual_hrs`, `created_at`, `updated_at`) VALUES
(1, 'TCI-18100', 0, 'https://tickets-tribepad.atlassian.net/jira/software/c/projects/TCI/issues/TCI-18100', 2, 2, '10', '2023-06-15 00:00:00', NULL, 13, '2023-02-23 15:44:00', '2023-02-23 12:00:00', '2023-06-20 15:45:00', '0000-00-00 00:00:00', '0000-00-00 00:00:00', 155.00, 247.00, '2023-06-14 15:45:56', '2023-06-14 15:45:56'),
(2, 'TCI-19010', 0, '0', 2, 2, '1', '2023-06-15 00:00:00', NULL, 14, '2023-06-14 16:39:00', '2023-05-02 00:00:00', '2023-06-02 12:00:00', '0000-00-00 00:00:00', '0000-00-00 00:00:00', 20.00, 35.00, '2023-06-14 17:00:00', '2023-06-14 17:00:00'),
(3, 'TCI-18882', 0, '0', 1, 2, '1', '2023-06-14 00:00:00', NULL, 16, '2023-05-19 17:01:00', '2023-05-19 17:02:00', '2023-05-19 17:02:00', '0000-00-00 00:00:00', '0000-00-00 00:00:00', 8.00, 11.00, '2023-06-14 17:03:01', '2023-06-14 17:03:01'),
(4, 'TCI-17383', 0, '0', 2, 2, '12', '2023-06-15 00:00:00', NULL, 4, '2023-11-05 17:19:00', '2023-11-05 12:00:00', '2023-11-05 12:00:00', '0000-00-00 00:00:00', '0000-00-00 00:00:00', 12.00, 21.00, '2023-06-14 17:33:06', '2023-06-14 17:33:06'),
(5, 'TCI-18902', 0, '0', 2, 2, '1', '2023-06-14 00:00:00', NULL, 15, '2023-05-22 17:34:00', '2023-05-22 12:00:00', '2023-05-23 12:00:00', '0000-00-00 00:00:00', '0000-00-00 00:00:00', 5.00, 59.00, '2023-06-14 17:35:20', '2023-06-14 17:35:20'),
(6, 'TCI-19103', 0, '0', 1, 2, '7', '2023-06-14 00:00:00', NULL, 3, '2023-06-12 17:38:00', '2023-06-12 12:00:00', '2023-06-15 12:00:00', '0000-00-00 00:00:00', '0000-00-00 00:00:00', 6.00, 5.00, '2023-06-14 17:38:47', '2023-06-14 17:38:47'),
(7, 't1', 0, '0', 1, 2, '5', NULL, NULL, 1, '2023-06-15 21:55:00', '2023-06-15 21:56:00', '2023-06-19 21:56:00', '0000-00-00 00:00:00', '0000-00-00 00:00:00', 11.00, NULL, '2023-06-15 21:56:37', '2023-06-15 21:56:37'),
(8, 't2', 0, '0', 1, 2, '2', '2023-06-30 00:00:00', NULL, 1, '2023-06-15 21:57:00', '2023-06-14 21:57:00', '2023-06-20 21:57:00', '0000-00-00 00:00:00', '0000-00-00 00:00:00', 22.00, 16.00, '2023-06-15 21:57:52', '2023-06-15 22:01:23'),
(9, 't4', 0, '0', 1, 2, '2', '2023-06-30 00:00:00', NULL, 1, '2023-06-13 22:20:00', '2023-06-15 22:20:00', '0000-00-00 00:00:00', '0000-00-00 00:00:00', '0000-00-00 00:00:00', 22.00, 9.00, '2023-06-15 22:30:32', '2023-06-16 15:46:08'),
(10, 't-amit', 0, '0', 1, 2, '1', '2023-06-16 00:00:00', NULL, 3, '2023-06-16 14:42:00', '2023-06-16 14:42:00', '0000-00-00 00:00:00', '0000-00-00 00:00:00', '0000-00-00 00:00:00', 30.00, 3.00, '2023-06-16 14:43:01', '2023-06-16 14:43:01'),
(12, 'act1', 5, NULL, 7, 0, '', NULL, NULL, 0, NULL, NULL, NULL, NULL, NULL, 22.00, 0.00, '2023-07-03 17:40:37', '2023-07-03 17:40:37'),
(13, 'act2', 5, NULL, 7, 0, '', NULL, NULL, 0, NULL, NULL, NULL, NULL, NULL, 10.00, 0.00, '2023-07-04 10:41:53', '2023-07-04 10:41:53'),
(14, 'act3', 5, NULL, 7, 0, '', NULL, NULL, 0, NULL, NULL, NULL, NULL, NULL, 3.00, 0.00, '2023-07-04 10:43:55', '2023-07-04 10:43:55'),
(15, 'act4', 5, NULL, 7, 0, '', NULL, NULL, 0, NULL, NULL, NULL, NULL, NULL, 2.00, 7.00, '2023-07-04 11:10:06', '2023-07-04 11:10:06'),
(16, 'testStory', 0, '', 2, 2, '5', NULL, NULL, 15, '2023-07-04 11:13:00', '2023-07-04 11:13:00', '0000-00-00 00:00:00', '0000-00-00 00:00:00', '0000-00-00 00:00:00', 40.00, 0.00, '2023-07-04 11:13:26', '2023-07-04 11:13:26');

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
(4, 'Integration'),
(6, 'Support'),
(7, 'Activity');

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
(1, 'dhirajtekade', 'Dhiraj', 'Tekade', 3, 4762, 'full s', 'dhirajtek13@gmail.com', '$2y$10$RrYQOP0Et54xBsbfzo/U9OE/Tp9HseiFB7UmRgmgSXerUgw.eW21K', 1, '2023-04-25 17:32:13', '2023-06-08 15:10:11'),
(2, 'vumesh', 'Umesh', 'Verma', 1, 111, 'PM', 'vumesh@espire.com', '$2y$10$/pVJVAzUYkMsjEC0frHReOOeAqyzsSXqVZRsFonzPVg8Iz7Z0Ovky', 1, '2023-04-25 20:52:17', '2023-05-17 10:18:33'),
(3, 'kamit', 'Amit Singh ', 'Karki', 2, 4393, 'LE', 'kamit@espire.com', '$2y$10$xrL2N5Xl1.xQn8o9iRNhxu3YxSy2PK2LZVJgxaxB1micC4NUMenVS', 1, '2023-04-25 20:54:53', '2023-06-14 16:01:49'),
(4, 'upendra', 'Upendra', 'Prasad', 3, 4428, 'lead eng', 'u@email.com', '$2y$10$a0nE4VkNRE5qZS/sLXTjsOzs2mNrKb/d/x4ua1qK.sNzfg.1K61IS', 1, '2023-04-27 13:36:54', '2023-06-14 16:02:01'),
(13, 'mabhay', 'Abhay', 'Maurya', 3, 4310, 'Senior Software Engineer', 'abhay.maurya@espire.com', '$2y$10$mm6Gq26wxo6KonNEKftS4OXg4e9yp1rLstnFRclU4uV.dyHtZQ.Im', 1, '2023-06-14 15:43:42', '2023-06-14 15:44:00'),
(14, 'svivek', 'Vivek', 'Agrawal', 3, 10033, 'Senior Software Engineer', 'vivek.shrivastava@espire.com', '$2y$10$qXr/GJCsJfvBDPtzEp1c8u1l4531TXLaSGS.G24Yz2/V.HOxjljdq', 1, '2023-06-14 16:25:20', '2023-06-14 16:55:04'),
(15, 'spawan', 'Pawan', 'Sharma', 3, 10029, 'Senior Software Engineer', 'pawan.sharma@espire.com', '$2y$10$r3Kp8BLC.eew42aPtiGzUu/uuYsdaQAHwg8NSz/48bZKkFZW79tgi', 1, '2023-06-14 16:26:48', '2023-06-14 16:28:28'),
(16, 'kyogesh', 'Yogesh', 'Kumar', 3, 4935, 'Full Stack', 'yogesh.kumar@espire.com', '$2y$10$LSdBCB/c6nAzcwSCktnFhe7ltfOpuyXPWmwcU0MPsuRsGqvf8UwbW', 1, '2023-06-14 16:28:02', '2023-06-15 09:48:47'),
(22, 'u2', 'd11', 'l', 1, 0, '', 'd@d.com', '$2y$10$7q1ps4WqrxeY5nPKxKQrkOKn8DEHK3kIR8fccTmTsjWT/aKjDiDJK', 0, '2023-06-17 20:39:12', '2023-06-18 10:04:24'),
(23, 'ghghh', 'hfhf', '', 1, 0, '', 'd@d.co', '$2y$10$AsAKvufoAwtB7H18Q9zhjuu/6vOP282bigjIggRmRowiqTpCpxmmG', 1, '2023-06-19 14:06:23', '2023-06-19 14:06:23');

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
-- Indexes for table `day_type`
--
ALTER TABLE `day_type`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `holidays`
--
ALTER TABLE `holidays`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `kpis`
--
ALTER TABLE `kpis`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `leave_tracker`
--
ALTER TABLE `leave_tracker`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `leave_type`
--
ALTER TABLE `leave_type`
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
  MODIFY `id` int(10) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=11;

--
-- AUTO_INCREMENT for table `c_status_types`
--
ALTER TABLE `c_status_types`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=22;

--
-- AUTO_INCREMENT for table `day_type`
--
ALTER TABLE `day_type`
  MODIFY `id` int(10) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `holidays`
--
ALTER TABLE `holidays`
  MODIFY `id` int(10) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `kpis`
--
ALTER TABLE `kpis`
  MODIFY `id` int(10) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- AUTO_INCREMENT for table `leave_tracker`
--
ALTER TABLE `leave_tracker`
  MODIFY `id` int(10) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=18;

--
-- AUTO_INCREMENT for table `leave_type`
--
ALTER TABLE `leave_type`
  MODIFY `id` int(10) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `log_history`
--
ALTER TABLE `log_history`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=115;

--
-- AUTO_INCREMENT for table `log_timing`
--
ALTER TABLE `log_timing`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=137;

--
-- AUTO_INCREMENT for table `projects`
--
ALTER TABLE `projects`
  MODIFY `id` int(10) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT for table `project_user_map`
--
ALTER TABLE `project_user_map`
  MODIFY `id` int(10) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=28;

--
-- AUTO_INCREMENT for table `tickets`
--
ALTER TABLE `tickets`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=17;

--
-- AUTO_INCREMENT for table `ticket_types`
--
ALTER TABLE `ticket_types`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `id` int(10) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=24;

--
-- AUTO_INCREMENT for table `user_type`
--
ALTER TABLE `user_type`
  MODIFY `id` int(10) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
