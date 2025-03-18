-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: localhost
-- Generation Time: Mar 18, 2025 at 09:24 AM
-- Server version: 10.4.28-MariaDB
-- PHP Version: 8.2.4

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `ticketing_system`
--

-- --------------------------------------------------------

--
-- Table structure for table `admins`
--

CREATE TABLE `admins` (
  `id` int(11) NOT NULL,
  `name` varchar(100) NOT NULL,
  `email` varchar(100) NOT NULL,
  `password` varchar(255) NOT NULL,
  `category` varchar(100) DEFAULT NULL,
  `role` enum('admin','superadmin') DEFAULT 'admin',
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `profile_picture` varchar(255) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `admins`
--

INSERT INTO `admins` (`id`, `name`, `email`, `password`, `category`, `role`, `created_at`, `profile_picture`) VALUES
(1, 'Super Admin', 'superadmin@example.com', '$2y$10$mAZ5zBMCKWvGCR4LDjhgb.Dbb.VSkX.lN6FqnABhccZ/XryBl/1lq', NULL, 'superadmin', '2025-02-26 07:55:15', NULL),
(2, 'admin1', 'zzz@gmail.com', '$2y$12$t0GTZuvJ40vov7oaJt.AVeeSmeqtsQ28TyebMgAD3CC5AteDWGgA6', 'Technical Support', 'admin', '2025-03-01 09:28:54', 'uploads/profile_pictures/1740922555_705149ad4934fbda556f.jpg'),
(3, 'yyy', 'yyy@gmail.com', '$2y$12$6FcVDeUj34GEUV.7BZapDuYWwKj3Fyz5ShV97qbzfa6a6CF9D28uq', 'Billing', 'admin', '2025-03-01 09:50:46', NULL),
(4, 'www', 'www@gmail.com', '$2y$12$i4adAQrZiEyVkhVsz2bg0ux3JW438x2SSKhygABCh78LB8abB5cHa', 'General Inquiry', 'admin', '2025-03-01 09:51:17', NULL),
(5, 'vvv', 'vvv@gmail.com', '$2y$12$p.Xf1GuVOV2NKqd1Uakn6eDExXv7LaQCRe1l24CxmpgF2jdSRsMkW', 'Account Issues', 'admin', '2025-03-01 09:51:43', NULL),
(6, 'admin2', 'mmm@gmail.com', '$2y$12$mMM1SovF2u23ITTIZbmngu.cEGnTZpCu9CQ1/KlfDWkFQASkr7klK', 'Technical Support', 'admin', '2025-03-09 14:31:03', NULL);

-- --------------------------------------------------------

--
-- Table structure for table `admin_activity`
--

CREATE TABLE `admin_activity` (
  `id` int(11) NOT NULL,
  `admin_name` varchar(100) NOT NULL,
  `action` text NOT NULL,
  `status` enum('Success','Failed') NOT NULL,
  `timestamp` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `admin_log`
--

CREATE TABLE `admin_log` (
  `id` int(11) NOT NULL,
  `admin_id` int(11) NOT NULL,
  `action` enum('Login','Logout') NOT NULL,
  `ip_address` varchar(45) DEFAULT NULL,
  `user_agent` text DEFAULT NULL,
  `timestamp` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `messages`
--

CREATE TABLE `messages` (
  `id` int(11) NOT NULL,
  `ticket_id` varchar(50) DEFAULT NULL,
  `admin_id` int(11) NOT NULL,
  `user_id` int(11) DEFAULT NULL,
  `sender` enum('user','admin') NOT NULL DEFAULT 'user',
  `message` text DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `attachment` varchar(255) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `messages`
--

INSERT INTO `messages` (`id`, `ticket_id`, `admin_id`, `user_id`, `sender`, `message`, `created_at`, `attachment`) VALUES
(130, 'TICKET-8E5AF3E2', 2, 26, 'user', 'hi', '2025-03-18 05:36:21', NULL),
(131, 'TICKET-8E5AF3E2', 2, NULL, 'admin', 'hello', '2025-03-18 05:38:45', '');

-- --------------------------------------------------------

--
-- Table structure for table `tickets`
--

CREATE TABLE `tickets` (
  `id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `email` varchar(100) NOT NULL,
  `title` varchar(255) DEFAULT NULL,
  `description` varchar(255) DEFAULT NULL,
  `category` varchar(255) NOT NULL,
  `status` enum('Open','In Progress','Resolved') DEFAULT 'Open',
  `urgency` enum('Low','Medium','High') DEFAULT 'Low',
  `assigned_admin_id` int(11) DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `attachments` text DEFAULT NULL,
  `ticket_id` varchar(50) DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `tickets`
--

INSERT INTO `tickets` (`id`, `user_id`, `email`, `title`, `description`, `category`, `status`, `urgency`, `assigned_admin_id`, `created_at`, `attachments`, `ticket_id`, `updated_at`) VALUES
(106, 26, 'hello@gmail.com', 'hello', 'hello', 'Technical Support', 'Open', 'High', 2, '2025-03-18 05:35:49', '[\"uploads\\/tickets\\/1742276149_b1665aecf53e53acae4a.png\"]', 'TICKET-8E5AF3E2', '2025-03-18 05:44:10');

-- --------------------------------------------------------

--
-- Table structure for table `ticket_status_log`
--

CREATE TABLE `ticket_status_log` (
  `id` int(11) NOT NULL,
  `ticket_id` int(11) NOT NULL,
  `admin_id` int(11) NOT NULL,
  `old_status` enum('Open','In Progress','Resolved') NOT NULL,
  `new_status` enum('Open','In Progress','Resolved') NOT NULL,
  `changed_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `id` int(11) NOT NULL,
  `name` varchar(100) DEFAULT NULL,
  `email` varchar(100) DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`id`, `name`, `email`, `created_at`) VALUES
(1, '111', '111@gmail.com', '2025-02-26 08:00:30'),
(2, 'aaaa', 'aaaa@gmail.com', '2025-02-26 08:02:44'),
(5, 'dhanush.p', 'dhanush3542@gmail.com', '2025-03-01 05:16:56'),
(6, 'bbbb', 'bbbb@gmail.com', '2025-03-01 05:21:14'),
(7, 'ppp', 'ppp@gmail.com', '2025-03-02 11:51:08'),
(9, NULL, 'ttt@gmail.com', '2025-03-08 08:17:13'),
(10, NULL, 'sss@gmail.com', '2025-03-09 05:54:16'),
(11, NULL, 'fff@gmail.com', '2025-03-09 06:54:14'),
(12, NULL, 'ggg@gmail.com', '2025-03-09 16:26:12'),
(13, NULL, 'hhh@gmail.com', '2025-03-09 16:30:47'),
(14, NULL, 'jjj@gmail.com', '2025-03-09 17:49:15'),
(15, NULL, 'kkk@gmail.com', '2025-03-11 04:21:00'),
(16, NULL, 'lll@gmail.com', '2025-03-11 04:48:30'),
(17, NULL, 'nnn@gmail.com', '2025-03-11 06:38:58'),
(18, NULL, 'superadmin@example.com', '2025-03-13 04:14:51'),
(19, NULL, 'vvv@gmail.com', '2025-03-13 15:33:03'),
(20, NULL, 'bbb@gmail.com', '2025-03-13 16:48:02'),
(21, NULL, 'aaa@gmail.com', '2025-03-13 21:01:00'),
(22, NULL, 'qqqq@gmail.com', '2025-03-14 06:52:14'),
(23, NULL, 'gd97319741@gmail.com', '2025-03-16 06:04:41'),
(24, NULL, 'karthik@gmail.com', '2025-03-17 07:41:29'),
(25, NULL, 'ascscs@gmail.com', '2025-03-18 05:17:59'),
(26, NULL, 'hello@gmail.com', '2025-03-18 05:28:13');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `admins`
--
ALTER TABLE `admins`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `email` (`email`);

--
-- Indexes for table `admin_activity`
--
ALTER TABLE `admin_activity`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `admin_log`
--
ALTER TABLE `admin_log`
  ADD PRIMARY KEY (`id`),
  ADD KEY `admin_id` (`admin_id`);

--
-- Indexes for table `messages`
--
ALTER TABLE `messages`
  ADD PRIMARY KEY (`id`),
  ADD KEY `fk_admin` (`admin_id`),
  ADD KEY `messages_ibfk_1` (`ticket_id`);

--
-- Indexes for table `tickets`
--
ALTER TABLE `tickets`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `ticket_id` (`ticket_id`),
  ADD UNIQUE KEY `ticket_id_2` (`ticket_id`),
  ADD KEY `fk_tickets_user` (`user_id`),
  ADD KEY `fk_tickets_admin` (`assigned_admin_id`);

--
-- Indexes for table `ticket_status_log`
--
ALTER TABLE `ticket_status_log`
  ADD PRIMARY KEY (`id`),
  ADD KEY `ticket_id` (`ticket_id`),
  ADD KEY `admin_id` (`admin_id`);

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `email` (`email`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `admins`
--
ALTER TABLE `admins`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- AUTO_INCREMENT for table `admin_activity`
--
ALTER TABLE `admin_activity`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `admin_log`
--
ALTER TABLE `admin_log`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `messages`
--
ALTER TABLE `messages`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=132;

--
-- AUTO_INCREMENT for table `tickets`
--
ALTER TABLE `tickets`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=107;

--
-- AUTO_INCREMENT for table `ticket_status_log`
--
ALTER TABLE `ticket_status_log`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=17;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=27;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `admin_log`
--
ALTER TABLE `admin_log`
  ADD CONSTRAINT `admin_log_ibfk_1` FOREIGN KEY (`admin_id`) REFERENCES `admins` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `messages`
--
ALTER TABLE `messages`
  ADD CONSTRAINT `fk_admin` FOREIGN KEY (`admin_id`) REFERENCES `admins` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `messages_ibfk_1` FOREIGN KEY (`ticket_id`) REFERENCES `tickets` (`ticket_id`) ON DELETE CASCADE;

--
-- Constraints for table `tickets`
--
ALTER TABLE `tickets`
  ADD CONSTRAINT `fk_tickets_admin` FOREIGN KEY (`assigned_admin_id`) REFERENCES `admins` (`id`) ON DELETE SET NULL,
  ADD CONSTRAINT `fk_tickets_user` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `tickets_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `tickets_ibfk_2` FOREIGN KEY (`assigned_admin_id`) REFERENCES `admins` (`id`) ON DELETE SET NULL;

--
-- Constraints for table `ticket_status_log`
--
ALTER TABLE `ticket_status_log`
  ADD CONSTRAINT `ticket_status_log_ibfk_1` FOREIGN KEY (`ticket_id`) REFERENCES `tickets` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `ticket_status_log_ibfk_2` FOREIGN KEY (`admin_id`) REFERENCES `admins` (`id`) ON DELETE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
