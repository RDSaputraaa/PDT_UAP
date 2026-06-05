-- phpMyAdmin SQL Dump
-- version 5.2.0
-- https://www.phpmyadmin.net/
--
-- Host: localhost:3306
-- Generation Time: Jun 05, 2026 at 11:00 AM
-- Server version: 8.0.30
-- PHP Version: 8.1.10

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `aman_parkir`
--

-- --------------------------------------------------------

--
-- Stand-in structure for view `active_transactions`
-- (See below for the actual view)
--
CREATE TABLE `active_transactions` (
`created_at` timestamp
,`duration_minutes` int
,`entry_time` timestamp
,`exit_time` timestamp
,`id` int
,`is_active` tinyint(1)
,`parking_area_id` int
,`updated_at` timestamp
,`vehicle_id` int
);

-- --------------------------------------------------------

--
-- Table structure for table `activity_logs`
--

CREATE TABLE `activity_logs` (
  `id` int NOT NULL,
  `action_type` varchar(50) NOT NULL,
  `description` text,
  `user_ip` varchar(45) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

-- --------------------------------------------------------

--
-- Table structure for table `backup_logs`
--

CREATE TABLE `backup_logs` (
  `id` int NOT NULL,
  `backup_file` varchar(255) DEFAULT NULL,
  `backup_date` timestamp NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

-- --------------------------------------------------------

--
-- Stand-in structure for view `completed_transactions`
-- (See below for the actual view)
--
CREATE TABLE `completed_transactions` (
`created_at` timestamp
,`duration_minutes` int
,`entry_time` timestamp
,`exit_time` timestamp
,`id` int
,`is_active` tinyint(1)
,`parking_area_id` int
,`updated_at` timestamp
,`vehicle_id` int
);

-- --------------------------------------------------------

--
-- Table structure for table `daily_statistics`
--

CREATE TABLE `daily_statistics` (
  `id` int NOT NULL,
  `stat_date` date DEFAULT NULL,
  `total_vehicles` int DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data for table `daily_statistics`
--

INSERT INTO `daily_statistics` (`id`, `stat_date`, `total_vehicles`, `created_at`) VALUES
(1, '2026-06-05', 0, '2026-06-05 09:22:11');

-- --------------------------------------------------------

--
-- Stand-in structure for view `motorcycle_transactions`
-- (See below for the actual view)
--
CREATE TABLE `motorcycle_transactions` (
`created_at` timestamp
,`duration_minutes` int
,`entry_time` timestamp
,`exit_time` timestamp
,`id` int
,`is_active` tinyint(1)
,`parking_area_id` int
,`updated_at` timestamp
,`vehicle_id` int
);

-- --------------------------------------------------------

--
-- Table structure for table `parking_areas`
--

CREATE TABLE `parking_areas` (
  `id` int NOT NULL,
  `area_code` varchar(50) NOT NULL,
  `area_name` varchar(100) NOT NULL,
  `location` varchar(255) DEFAULT NULL,
  `total_capacity` int NOT NULL,
  `motorcycle_capacity` int NOT NULL,
  `car_capacity` int NOT NULL,
  `current_motorcycle` int DEFAULT '0',
  `current_car` int DEFAULT '0',
  `status` enum('active','inactive') DEFAULT 'active',
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data for table `parking_areas`
--

INSERT INTO `parking_areas` (`id`, `area_code`, `area_name`, `location`, `total_capacity`, `motorcycle_capacity`, `car_capacity`, `current_motorcycle`, `current_car`, `status`, `created_at`, `updated_at`) VALUES
(1, 'A', 'Area A', 'Gedung A', 200, 200, 0, 0, 0, 'active', '2026-06-05 09:22:11', '2026-06-05 09:22:11'),
(2, 'B', 'Area B', 'Gedung B', 150, 150, 0, 0, 0, 'active', '2026-06-05 09:22:11', '2026-06-05 09:22:11'),
(3, 'PASCA', 'Area PASCA', 'Gedung PASCA', 20, 20, 0, 0, 0, 'active', '2026-06-05 09:22:11', '2026-06-05 09:22:11');

-- --------------------------------------------------------

--
-- Table structure for table `parking_transactions`
--

CREATE TABLE `parking_transactions` (
  `id` int NOT NULL,
  `vehicle_id` int NOT NULL,
  `parking_area_id` int NOT NULL,
  `entry_time` timestamp NOT NULL,
  `exit_time` timestamp NULL DEFAULT NULL,
  `duration_minutes` int DEFAULT NULL,
  `is_active` tinyint(1) DEFAULT '1',
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Triggers `parking_transactions`
--
DELIMITER $$
CREATE TRIGGER `trg_vehicle_entry` AFTER INSERT ON `parking_transactions` FOR EACH ROW BEGIN
INSERT INTO activity_logs (
action_type,
description
)
VALUES (
'VEHICLE_ENTRY',
CONCAT(
'Vehicle ID ',
NEW.vehicle_id,
' entered parking area ',
NEW.parking_area_id
)
);
END
$$
DELIMITER ;
DELIMITER $$
CREATE TRIGGER `trg_vehicle_exit` AFTER UPDATE ON `parking_transactions` FOR EACH ROW BEGIN

    IF NEW.exit_time IS NOT NULL
       AND OLD.exit_time IS NULL THEN

        INSERT INTO activity_logs (
            action_type,
            description
        )
        VALUES (
            'VEHICLE_EXIT',
            CONCAT(
                'Vehicle ID ',
                NEW.vehicle_id,
                ' exited parking area ',
                NEW.parking_area_id
            )
        );

    END IF;

END
$$
DELIMITER ;

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `id` int NOT NULL,
  `username` varchar(50) NOT NULL,
  `password` varchar(255) NOT NULL,
  `role` enum('admin','petugas') DEFAULT 'petugas',
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  `email` varchar(100) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`id`, `username`, `password`, `role`, `created_at`, `email`) VALUES
(1, 'Karina', '$2y$10$3G8MdLUBOImp4s58d63tteERbadD3at2KZCJxQlFQweHZ9pZrLpRq', 'admin', '2026-06-05 10:04:53', 'karina@gmail.com'),
(3, 'lia', '$2y$10$QBS7bv.eSnXDuxbtJrvxt.ZWx.4fBRiHj0OV85EsvcxWyfZFl.V6e', 'petugas', '2026-06-05 10:50:43', 'lia@gmail.com'),
(4, 'rizky', '$2y$10$kvupBAdgaz79/9YyzYuP/.qH71RC9LK82HOPosEvTztfEas7UeG/.', 'petugas', '2026-06-05 10:59:24', 'rizky@gmail.com');

-- --------------------------------------------------------

--
-- Table structure for table `vehicles`
--

CREATE TABLE `vehicles` (
  `id` int NOT NULL,
  `plate_number` varchar(20) NOT NULL,
  `vehicle_type_id` int NOT NULL,
  `owner_name` varchar(100) DEFAULT NULL,
  `owner_phone` varchar(20) DEFAULT NULL,
  `owner_email` varchar(100) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

-- --------------------------------------------------------

--
-- Table structure for table `vehicle_types`
--

CREATE TABLE `vehicle_types` (
  `id` int NOT NULL,
  `type_name` varchar(50) NOT NULL,
  `type_code` varchar(10) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data for table `vehicle_types`
--

INSERT INTO `vehicle_types` (`id`, `type_name`, `type_code`) VALUES
(1, 'Motorcycle', 'MC');

-- --------------------------------------------------------

--
-- Structure for view `active_transactions`
--
DROP TABLE IF EXISTS `active_transactions`;

CREATE ALGORITHM=UNDEFINED DEFINER=`root`@`localhost` SQL SECURITY DEFINER VIEW `active_transactions`  AS SELECT `parking_transactions`.`id` AS `id`, `parking_transactions`.`vehicle_id` AS `vehicle_id`, `parking_transactions`.`parking_area_id` AS `parking_area_id`, `parking_transactions`.`entry_time` AS `entry_time`, `parking_transactions`.`exit_time` AS `exit_time`, `parking_transactions`.`duration_minutes` AS `duration_minutes`, `parking_transactions`.`is_active` AS `is_active`, `parking_transactions`.`created_at` AS `created_at`, `parking_transactions`.`updated_at` AS `updated_at` FROM `parking_transactions` WHERE (`parking_transactions`.`is_active` = true)  ;

-- --------------------------------------------------------

--
-- Structure for view `completed_transactions`
--
DROP TABLE IF EXISTS `completed_transactions`;

CREATE ALGORITHM=UNDEFINED DEFINER=`root`@`localhost` SQL SECURITY DEFINER VIEW `completed_transactions`  AS SELECT `parking_transactions`.`id` AS `id`, `parking_transactions`.`vehicle_id` AS `vehicle_id`, `parking_transactions`.`parking_area_id` AS `parking_area_id`, `parking_transactions`.`entry_time` AS `entry_time`, `parking_transactions`.`exit_time` AS `exit_time`, `parking_transactions`.`duration_minutes` AS `duration_minutes`, `parking_transactions`.`is_active` AS `is_active`, `parking_transactions`.`created_at` AS `created_at`, `parking_transactions`.`updated_at` AS `updated_at` FROM `parking_transactions` WHERE (`parking_transactions`.`is_active` = false)  ;

-- --------------------------------------------------------

--
-- Structure for view `motorcycle_transactions`
--
DROP TABLE IF EXISTS `motorcycle_transactions`;

CREATE ALGORITHM=UNDEFINED DEFINER=`root`@`localhost` SQL SECURITY DEFINER VIEW `motorcycle_transactions`  AS SELECT `pt`.`id` AS `id`, `pt`.`vehicle_id` AS `vehicle_id`, `pt`.`parking_area_id` AS `parking_area_id`, `pt`.`entry_time` AS `entry_time`, `pt`.`exit_time` AS `exit_time`, `pt`.`duration_minutes` AS `duration_minutes`, `pt`.`is_active` AS `is_active`, `pt`.`created_at` AS `created_at`, `pt`.`updated_at` AS `updated_at` FROM ((`parking_transactions` `pt` join `vehicles` `v` on((`pt`.`vehicle_id` = `v`.`id`))) join `vehicle_types` `vt` on((`v`.`vehicle_type_id` = `vt`.`id`))) WHERE (`vt`.`type_code` = 'MC')  ;

--
-- Indexes for dumped tables
--

--
-- Indexes for table `activity_logs`
--
ALTER TABLE `activity_logs`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `backup_logs`
--
ALTER TABLE `backup_logs`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `daily_statistics`
--
ALTER TABLE `daily_statistics`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `parking_areas`
--
ALTER TABLE `parking_areas`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `area_code` (`area_code`);

--
-- Indexes for table `parking_transactions`
--
ALTER TABLE `parking_transactions`
  ADD PRIMARY KEY (`id`),
  ADD KEY `idx_plate` (`vehicle_id`),
  ADD KEY `idx_area` (`parking_area_id`),
  ADD KEY `idx_entry` (`entry_time`),
  ADD KEY `idx_active` (`is_active`);

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `username` (`username`),
  ADD UNIQUE KEY `email` (`email`);

--
-- Indexes for table `vehicles`
--
ALTER TABLE `vehicles`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `plate_number` (`plate_number`),
  ADD KEY `vehicle_type_id` (`vehicle_type_id`);

--
-- Indexes for table `vehicle_types`
--
ALTER TABLE `vehicle_types`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `type_code` (`type_code`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `activity_logs`
--
ALTER TABLE `activity_logs`
  MODIFY `id` int NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `backup_logs`
--
ALTER TABLE `backup_logs`
  MODIFY `id` int NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `daily_statistics`
--
ALTER TABLE `daily_statistics`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `parking_areas`
--
ALTER TABLE `parking_areas`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `parking_transactions`
--
ALTER TABLE `parking_transactions`
  MODIFY `id` int NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT for table `vehicles`
--
ALTER TABLE `vehicles`
  MODIFY `id` int NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `vehicle_types`
--
ALTER TABLE `vehicle_types`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `parking_transactions`
--
ALTER TABLE `parking_transactions`
  ADD CONSTRAINT `parking_transactions_ibfk_1` FOREIGN KEY (`vehicle_id`) REFERENCES `vehicles` (`id`),
  ADD CONSTRAINT `parking_transactions_ibfk_2` FOREIGN KEY (`parking_area_id`) REFERENCES `parking_areas` (`id`);

--
-- Constraints for table `vehicles`
--
ALTER TABLE `vehicles`
  ADD CONSTRAINT `vehicles_ibfk_1` FOREIGN KEY (`vehicle_type_id`) REFERENCES `vehicle_types` (`id`);

DELIMITER $$
--
-- Events
--
CREATE DEFINER=`root`@`localhost` EVENT `delete_old_logs` ON SCHEDULE EVERY 1 DAY STARTS '2026-06-05 16:22:11' ON COMPLETION NOT PRESERVE ENABLE DO DELETE FROM activity_logs
WHERE created_at < DATE_SUB(NOW(), INTERVAL 30 DAY)$$

CREATE DEFINER=`root`@`localhost` EVENT `daily_vehicle_report` ON SCHEDULE EVERY 1 DAY STARTS '2026-06-05 16:22:11' ON COMPLETION NOT PRESERVE ENABLE DO INSERT INTO daily_statistics (
stat_date,
total_vehicles
)
SELECT
CURDATE(),
COUNT(*)
FROM parking_transactions
WHERE DATE(entry_time) = CURDATE()$$

DELIMITER ;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
