-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Nov 24, 2024 at 03:13 PM
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
-- Database: `triolab_db`
--

-- --------------------------------------------------------

--
-- Table structure for table `admin`
--

CREATE TABLE `admin` (
  `id` int(11) NOT NULL,
  `username` varchar(255) NOT NULL,
  `password` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `admin`
--

INSERT INTO `admin` (`id`, `username`, `password`) VALUES
(1, 'trioadmin', '1');

-- --------------------------------------------------------

--
-- Table structure for table `appointment`
--

CREATE TABLE `appointment` (
  `id` int(11) NOT NULL,
  `service_id` int(11) NOT NULL,
  `patient_id` int(11) NOT NULL,
  `appointment_date` varchar(255) NOT NULL,
  `appointment_slot_id` int(255) NOT NULL,
  `appointment_time` varchar(255) NOT NULL,
  `doctor_id` varchar(255) DEFAULT NULL,
  `department_id` int(255) DEFAULT NULL,
  `selectedPayment` varchar(255) NOT NULL,
  `medical` text DEFAULT NULL,
  `status` varchar(255) NOT NULL DEFAULT 'Pending',
  `paid` varchar(255) NOT NULL DEFAULT 'Unpaid',
  `date_added` timestamp NOT NULL DEFAULT current_timestamp(),
  `is_archive` tinyint(4) NOT NULL DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `appointment`
--

INSERT INTO `appointment` (`id`, `service_id`, `patient_id`, `appointment_date`, `appointment_slot_id`, `appointment_time`, `doctor_id`, `department_id`, `selectedPayment`, `medical`, `status`, `paid`, `date_added`, `is_archive`) VALUES
(53, 1, 10, '2024-11-20', 2, '13:52', 'TRLB6874', 1, '3', 'qweqee', 'Pending', 'Pending', '2024-11-23 22:52:37', 0);

-- --------------------------------------------------------

--
-- Table structure for table `appointment_slots`
--

CREATE TABLE `appointment_slots` (
  `id` int(11) NOT NULL,
  `schedule` varchar(255) NOT NULL,
  `date` varchar(255) NOT NULL,
  `slot` varchar(255) NOT NULL,
  `is_archive` tinyint(4) NOT NULL DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `appointment_slots`
--

INSERT INTO `appointment_slots` (`id`, `schedule`, `date`, `slot`, `is_archive`) VALUES
(1, 'Morning', '2024-05-04', '18', 0),
(2, 'Afternoon', '2024-11-20', '84', 0),
(3, 'Morning', '2024-11-16', '0', 0),
(4, 'Morning', '2024-11-09', '1', 0);

-- --------------------------------------------------------

--
-- Table structure for table `database_backup`
--

CREATE TABLE `database_backup` (
  `id` int(255) NOT NULL,
  `path` varchar(255) NOT NULL,
  `date` datetime DEFAULT NULL,
  `is_archive` tinyint(255) NOT NULL DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `database_backup`
--

INSERT INTO `database_backup` (`id`, `path`, `date`, `is_archive`) VALUES
(6, 'backup_20241119_124027.sql', '2024-11-19 12:40:27', 0),
(7, 'backup_20241119_124122.sql', '2024-11-19 12:41:22', 0),
(8, 'backup_20241122_004019.sql', '2024-11-22 00:40:19', 0);

-- --------------------------------------------------------

--
-- Table structure for table `departments`
--

CREATE TABLE `departments` (
  `id` int(255) NOT NULL,
  `name` varchar(255) NOT NULL,
  `date_added` date NOT NULL DEFAULT current_timestamp(),
  `is_archive` tinyint(4) NOT NULL DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `departments`
--

INSERT INTO `departments` (`id`, `name`, `date_added`, `is_archive`) VALUES
(1, 'PHYSICIST', '2024-11-21', 0),
(2, 'RADIOLOGICAL TECHNOLOGIST', '2024-11-21', 0),
(3, 'MEDICAL TECHNICIAN', '2024-11-21', 0),
(4, 'MEDICAL CONSULTANT', '2024-11-21', 0);

-- --------------------------------------------------------

--
-- Table structure for table `doctor`
--

CREATE TABLE `doctor` (
  `employee_id` varchar(255) NOT NULL,
  `firstname` varchar(255) NOT NULL,
  `lastname` varchar(255) NOT NULL,
  `username` varchar(255) NOT NULL,
  `email` varchar(255) NOT NULL,
  `password` varchar(255) NOT NULL,
  `profile_img` varchar(255) DEFAULT NULL,
  `department_id` int(11) DEFAULT NULL,
  `date_added` timestamp NOT NULL DEFAULT current_timestamp(),
  `is_archive` tinyint(4) NOT NULL DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `doctor`
--

INSERT INTO `doctor` (`employee_id`, `firstname`, `lastname`, `username`, `email`, `password`, `profile_img`, `department_id`, `date_added`, `is_archive`) VALUES
('TRLB1335', 'Ariel', 'Pornelosa', 'ariel', 'asmrsounds6437@gmail.com', 'ariel', NULL, 3, '2024-11-16 02:19:31', 0),
('TRLB1690', 'Linda', 'Romero', 'Linda', 'linda@gmail.com', 'Linda', NULL, 1, '2024-11-16 12:26:01', 0),
('TRLB2763', 'ely', 'buendia', 'ely', 'ely@gmail.com', 'ely', NULL, 4, '2024-11-16 02:37:19', 0),
('TRLB5602', 'Angelo', 'Romero', 'angelo', 'angelo@gmail.com', 'angelo', NULL, 2, '2024-11-16 12:21:49', 0),
('TRLB6482', 'Marlyn', 'Leano', 'Lyn', 'lyn@gmail.com', 'Lyn', NULL, 1, '2024-11-16 12:57:11', 0),
('TRLB6874', 'Mherwen Wiel', 'Romero', 'mherwen123', 'mherwen123@gmail.com', '$2y$10$ONkTVQwOinK2Q5XToWkwB.jhAfx1DR8dkoD/xQRqPDOiv6XOMyMQ.', NULL, 1, '2024-05-05 01:35:17', 0),
('TRLB6908', 'qwe', 'qwe', 'qwe', 'qwe@gmail.com', 'qwe', NULL, 3, '2024-11-16 12:27:52', 0),
('TRLB7292', 'Erwin', 'Petil', 'erwin123', 'erwin@gmail.com', '$2y$10$dixFjSuNzEx1Z6XrCwqQh.w60ecMNqqIq3AHDCIU4qwSToctXbro2', NULL, 3, '2024-05-05 02:53:34', 0);

-- --------------------------------------------------------

--
-- Table structure for table `logs`
--

CREATE TABLE `logs` (
  `id` int(11) NOT NULL,
  `action` varchar(255) NOT NULL,
  `user` int(11) NOT NULL,
  `timestamp` timestamp NOT NULL DEFAULT current_timestamp(),
  `details` text DEFAULT NULL,
  `is_archive` tinyint(1) DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `logs`
--

INSERT INTO `logs` (`id`, `action`, `user`, `timestamp`, `details`, `is_archive`) VALUES
(1, 'Add Patient', 1, '2024-11-16 13:00:01', NULL, 0),
(2, 'Add Patient', 1, '2024-11-16 13:01:07', NULL, 0),
(3, 'upload payment method', 0, '2024-11-17 03:15:51', 'Payment method updated with ID: 1', 0),
(4, 'Add payment method error', 0, '2024-11-17 05:59:49', 'Failed to upload image for payment method: sample', 0),
(5, 'Add payment method error', 0, '2024-11-17 06:00:15', 'Failed to upload image for payment method: sample 2 ', 0),
(6, 'Add payment method error', 0, '2024-11-17 06:02:43', 'Payment method already exists: sample 2 ', 0),
(7, 'Add payment method error', 0, '2024-11-17 06:03:00', 'Failed to upload image for payment method: neww23', 0),
(8, 'Add payment method', 0, '2024-11-17 06:05:01', 'Payment method added: neww23', 0),
(9, 'edit patient', 0, '2024-11-19 04:35:56', 'Patient updated with ID: 9', 0),
(10, 'Submit medical record', 0, '2024-11-19 04:36:25', 'Medical record added for patient ID: 9', 0),
(11, 'edit department', 0, '2024-11-21 15:51:35', 'Department updated with ID: 1', 0),
(12, 'edit department', 0, '2024-11-21 15:52:59', 'Department updated with ID: 1', 0),
(13, 'edit department', 0, '2024-11-21 15:53:49', 'Department updated with ID: 1', 0),
(14, 'edit department', 0, '2024-11-21 15:53:54', 'Department updated with ID: 1', 0);

-- --------------------------------------------------------

--
-- Table structure for table `medical_records`
--

CREATE TABLE `medical_records` (
  `id` int(11) NOT NULL,
  `patient_id` int(11) NOT NULL,
  `appointment_id` int(255) NOT NULL,
  `doctor_id` varchar(255) NOT NULL,
  `diagnosis` text NOT NULL,
  `treatment` text NOT NULL,
  `prescription` varchar(255) NOT NULL,
  `status` varchar(255) NOT NULL DEFAULT 'Pending',
  `record_date` date NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `medical_records`
--

INSERT INTO `medical_records` (`id`, `patient_id`, `appointment_id`, `doctor_id`, `diagnosis`, `treatment`, `prescription`, `status`, `record_date`, `created_at`, `updated_at`) VALUES
(9, 10, 53, 'TRLB6874', 'malambot bituka', 'konting ligo', 'yakapsul', 'Pending', '0000-00-00', '2024-11-24 05:52:37', '2024-11-24 07:50:33');

-- --------------------------------------------------------

--
-- Table structure for table `patient`
--

CREATE TABLE `patient` (
  `id` int(11) NOT NULL,
  `firstname` varchar(255) NOT NULL,
  `lastname` varchar(255) NOT NULL,
  `email` varchar(255) NOT NULL,
  `dob` varchar(255) DEFAULT NULL,
  `birthplace` varchar(255) DEFAULT NULL,
  `contact` varchar(255) DEFAULT NULL,
  `password` varchar(255) NOT NULL,
  `province` varchar(255) DEFAULT NULL,
  `city` varchar(255) DEFAULT NULL,
  `barangay` varchar(255) DEFAULT NULL,
  `street` varchar(255) DEFAULT NULL,
  `date_added` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `patient`
--

INSERT INTO `patient` (`id`, `firstname`, `lastname`, `email`, `dob`, `birthplace`, `contact`, `password`, `province`, `city`, `barangay`, `street`, `date_added`) VALUES
(9, 'Kyle Andre', 'Lim', 'kylemastercoder14@gmail.com', '2000-01-14', 'Cavite', '9152479691', '$2y$10$3PCQfGLAMDsHHnCjAH2np.2rBEr3XF.h6TlmZ9fdc4BtqyLoOioCq', 'Cavite', 'City of Dasmariñas', 'Santa Lucia', 'B111 L4 Ruby Street', '2024-05-04 13:34:34'),
(10, 'Mherwen Wiel', 'Romero', 'romeroqmherwen@gmail.com', '2024-11-16', 'Dasma', '09553471926', '$2y$10$Kd4wh.OShR1hgpORWodxVu/HDR3MVn.kbzPbaErn4R.pXXaAetj3K', 'Cavite', 'City of Tagaytay', 'Tolentino West', 'BLK D 8 LOT 16 SAN LUIS 1 DASMARIÑAS CAV', '2024-11-15 11:33:41'),
(25, 'Lindaxxx', 'Romero', 'Linda@gmail.com', '2024-11-17', NULL, '0955237123', '', 'cavite', 'dasmarinas', 'SABUTAN', 'blk d8 lot 15', '2024-11-17 02:55:36');

-- --------------------------------------------------------

--
-- Table structure for table `payment_mode`
--

CREATE TABLE `payment_mode` (
  `id` int(255) NOT NULL,
  `method` varchar(255) NOT NULL,
  `image_path` varchar(255) NOT NULL,
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `payment_mode`
--

INSERT INTO `payment_mode` (`id`, `method`, `image_path`, `updated_at`) VALUES
(1, 'Gcash', 'uploads/payment_methods/gcash.jpg', '2024-11-17 04:11:04'),
(3, 'Cash', 'uploads/payment_methods/cash.jpg', '2024-11-17 04:11:24');

-- --------------------------------------------------------

--
-- Table structure for table `payment_receipts`
--

CREATE TABLE `payment_receipts` (
  `id` int(11) NOT NULL,
  `appointment_id` int(11) DEFAULT NULL,
  `payment_receipt_path` varchar(255) DEFAULT NULL,
  `date` date DEFAULT NULL,
  `payment_mode_id` int(11) DEFAULT NULL,
  `amount` decimal(10,2) DEFAULT NULL,
  `status` varchar(255) NOT NULL DEFAULT 'pending'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `payment_receipts`
--

INSERT INTO `payment_receipts` (`id`, `appointment_id`, `payment_receipt_path`, `date`, `payment_mode_id`, `amount`, `status`) VALUES
(9, 53, '', '2024-11-24', 3, 300.50, 'Pending');

-- --------------------------------------------------------

--
-- Table structure for table `services`
--

CREATE TABLE `services` (
  `id` int(11) NOT NULL,
  `category` varchar(255) NOT NULL,
  `type` varchar(255) NOT NULL,
  `service` varchar(255) NOT NULL,
  `department_id` int(255) NOT NULL,
  `cost` varchar(255) NOT NULL,
  `date_added` timestamp NOT NULL DEFAULT current_timestamp(),
  `is_archive` tinyint(4) NOT NULL DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `services`
--

INSERT INTO `services` (`id`, `category`, `type`, `service`, `department_id`, `cost`, `date_added`, `is_archive`) VALUES
(1, 'Imaging Services', 'X-Ray', 'Spot Film', 2, '300.50', '2024-05-03 07:44:15', 0),
(2, 'Imaging Services', 'X-Ray', 'T-Cage', 2, '250.80', '2024-05-03 07:44:54', 0),
(3, 'Laboratory Services', 'CBC', 'Red Blood Cell', 2, '200', '2024-05-03 07:53:00', 0),
(4, 'Imaging Services', 'CT SCAN', 'PELVIC', 2, '100', '2024-11-16 03:17:58', 0);

--
-- Indexes for dumped tables
--

--
-- Indexes for table `admin`
--
ALTER TABLE `admin`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `appointment`
--
ALTER TABLE `appointment`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `appointment_slots`
--
ALTER TABLE `appointment_slots`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `database_backup`
--
ALTER TABLE `database_backup`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `departments`
--
ALTER TABLE `departments`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `doctor`
--
ALTER TABLE `doctor`
  ADD PRIMARY KEY (`employee_id`),
  ADD KEY `fk_doctor_department_new` (`department_id`);

--
-- Indexes for table `logs`
--
ALTER TABLE `logs`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `medical_records`
--
ALTER TABLE `medical_records`
  ADD PRIMARY KEY (`id`),
  ADD KEY `fk_patient_id` (`patient_id`);

--
-- Indexes for table `patient`
--
ALTER TABLE `patient`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `payment_mode`
--
ALTER TABLE `payment_mode`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `payment_receipts`
--
ALTER TABLE `payment_receipts`
  ADD PRIMARY KEY (`id`),
  ADD KEY `appointment_id` (`appointment_id`),
  ADD KEY `payment_mode_id` (`payment_mode_id`);

--
-- Indexes for table `services`
--
ALTER TABLE `services`
  ADD PRIMARY KEY (`id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `admin`
--
ALTER TABLE `admin`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `appointment`
--
ALTER TABLE `appointment`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=54;

--
-- AUTO_INCREMENT for table `appointment_slots`
--
ALTER TABLE `appointment_slots`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT for table `database_backup`
--
ALTER TABLE `database_backup`
  MODIFY `id` int(255) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=9;

--
-- AUTO_INCREMENT for table `departments`
--
ALTER TABLE `departments`
  MODIFY `id` int(255) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT for table `logs`
--
ALTER TABLE `logs`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=15;

--
-- AUTO_INCREMENT for table `medical_records`
--
ALTER TABLE `medical_records`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=10;

--
-- AUTO_INCREMENT for table `patient`
--
ALTER TABLE `patient`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=26;

--
-- AUTO_INCREMENT for table `payment_mode`
--
ALTER TABLE `payment_mode`
  MODIFY `id` int(255) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;

--
-- AUTO_INCREMENT for table `payment_receipts`
--
ALTER TABLE `payment_receipts`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=10;

--
-- AUTO_INCREMENT for table `services`
--
ALTER TABLE `services`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `doctor`
--
ALTER TABLE `doctor`
  ADD CONSTRAINT `fk_doctor_department_new` FOREIGN KEY (`department_id`) REFERENCES `departments` (`id`) ON DELETE SET NULL;

--
-- Constraints for table `medical_records`
--
ALTER TABLE `medical_records`
  ADD CONSTRAINT `fk_patient_id` FOREIGN KEY (`patient_id`) REFERENCES `patient` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `medical_records_ibfk_1` FOREIGN KEY (`patient_id`) REFERENCES `patient` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `payment_receipts`
--
ALTER TABLE `payment_receipts`
  ADD CONSTRAINT `payment_receipts_ibfk_1` FOREIGN KEY (`appointment_id`) REFERENCES `appointment` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `payment_receipts_ibfk_2` FOREIGN KEY (`payment_mode_id`) REFERENCES `payment_mode` (`id`) ON DELETE SET NULL;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
