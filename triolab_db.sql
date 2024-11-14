-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: May 05, 2024 at 05:08 AM
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
(1, 'trioadmin', 'Ifc9DqEv');

-- --------------------------------------------------------

--
-- Table structure for table `appointment`
--

CREATE TABLE `appointment` (
  `id` int(11) NOT NULL,
  `service_id` int(11) NOT NULL,
  `patient_id` int(11) NOT NULL,
  `appointment_date` varchar(255) NOT NULL,
  `appointment_schedule` varchar(255) NOT NULL,
  `appointment_time` varchar(255) NOT NULL,
  `doctor_id` varchar(255) DEFAULT NULL,
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

INSERT INTO `appointment` (`id`, `service_id`, `patient_id`, `appointment_date`, `appointment_schedule`, `appointment_time`, `doctor_id`, `selectedPayment`, `medical`, `status`, `paid`, `date_added`, `is_archive`) VALUES
(4, 2, 9, '2024-05-04', 'Afternoon', '15:00', NULL, 'gcash', 'bubble bath', 'Pending', 'Unpaid', '2024-05-04 13:54:51', 0);

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
(1, 'Morning', '2024-05-04', '20', 0),
(2, 'Afternoon', '2024-05-04', '24', 0);

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
  `department` varchar(255) NOT NULL,
  `date_added` timestamp NOT NULL DEFAULT current_timestamp(),
  `is_archive` tinyint(4) NOT NULL DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `doctor`
--

INSERT INTO `doctor` (`employee_id`, `firstname`, `lastname`, `username`, `email`, `password`, `profile_img`, `department`, `date_added`, `is_archive`) VALUES
('TRLB6874', 'Mherwen Wiel', 'Romero', 'mherwen123', 'mherwen123@gmail.com', '$2y$10$ONkTVQwOinK2Q5XToWkwB.jhAfx1DR8dkoD/xQRqPDOiv6XOMyMQ.', NULL, 'Doctor/Physician', '2024-05-05 01:35:17', 0),
('TRLB7292', 'Erwin', 'Petil', 'erwin123', 'erwin@gmail.com', '$2y$10$dixFjSuNzEx1Z6XrCwqQh.w60ecMNqqIq3AHDCIU4qwSToctXbro2', NULL, 'Radiological Technologist', '2024-05-05 02:53:34', 0);

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
(9, 'Kyle Andre', 'Lim', 'kylemastercoder14@gmail.com', '2000-01-14', 'Cavite', '9152479693', '$2y$10$3PCQfGLAMDsHHnCjAH2np.2rBEr3XF.h6TlmZ9fdc4BtqyLoOioCq', 'Cavite', 'City of Dasmariñas', 'Santa Lucia', 'B111 L4 Ruby Street', '2024-05-04 13:34:34');

-- --------------------------------------------------------

--
-- Table structure for table `services`
--

CREATE TABLE `services` (
  `id` int(11) NOT NULL,
  `category` varchar(255) NOT NULL,
  `type` varchar(255) NOT NULL,
  `service` varchar(255) NOT NULL,
  `department` varchar(255) NOT NULL,
  `cost` varchar(255) NOT NULL,
  `date_added` timestamp NOT NULL DEFAULT current_timestamp(),
  `is_archive` tinyint(4) NOT NULL DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `services`
--

INSERT INTO `services` (`id`, `category`, `type`, `service`, `department`, `cost`, `date_added`, `is_archive`) VALUES
(1, 'Imaging Services', 'X-Ray', 'Spot Film', 'Radiological Technologist', '300.50', '2024-05-03 07:44:15', 0),
(2, 'Imaging Services', 'X-Ray', 'T-Cage', 'Radiological Technologist', '250.80', '2024-05-03 07:44:54', 0),
(3, 'Laboratory Services', 'CBC', 'Red Blood Cell', 'Radiological Technologist', '200', '2024-05-03 07:53:00', 0);

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
-- Indexes for table `doctor`
--
ALTER TABLE `doctor`
  ADD PRIMARY KEY (`employee_id`);

--
-- Indexes for table `patient`
--
ALTER TABLE `patient`
  ADD PRIMARY KEY (`id`);

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
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT for table `appointment_slots`
--
ALTER TABLE `appointment_slots`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `patient`
--
ALTER TABLE `patient`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=10;

--
-- AUTO_INCREMENT for table `services`
--
ALTER TABLE `services`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
