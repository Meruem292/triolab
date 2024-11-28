DROP TABLE IF EXISTS `admin`;
CREATE TABLE `admin` (
  `id` int(11) NOT NULL,
  `username` varchar(255) NOT NULL,
  `password` varchar(255) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

INSERT INTO `admin` (id, username, password) VALUES ('1', 'trioadmin', '$2y$10$iJQSWF2QB/DQHz/EvN5zq.LdtuSSAo4LFqwubtosN3a36PphAjvJ6');

DROP TABLE IF EXISTS `appointment`;
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
  `is_archive` tinyint(4) NOT NULL DEFAULT 0,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

INSERT INTO `appointment` (id, service_id, patient_id, appointment_date, appointment_slot_id, appointment_time, doctor_id, department_id, selectedPayment, medical, status, paid, date_added, is_archive) VALUES ('55', '1', '10', '2024-11-25', '2', '10:29', 'TRLB6874', '1', '3', 'fracture', 'Completed', 'Approved', '2024-11-24 08:29:45', '0');
INSERT INTO `appointment` (id, service_id, patient_id, appointment_date, appointment_slot_id, appointment_time, doctor_id, department_id, selectedPayment, medical, status, paid, date_added, is_archive) VALUES ('56', '1', '10', '2024-11-25', '4', '13:04', 'TRLB7292', '3', '3', 'fracture', 'Pending', 'Pending', '2024-11-24 22:04:56', '0');
INSERT INTO `appointment` (id, service_id, patient_id, appointment_date, appointment_slot_id, appointment_time, doctor_id, department_id, selectedPayment, medical, status, paid, date_added, is_archive) VALUES ('57', '1', '10', '2024-11-25', '2', '13:46', 'TRLB6874', '1', '3', 'dadczcxz', 'Pending', 'Pending', '2024-11-25 06:47:02', '0');

DROP TABLE IF EXISTS `appointment_slots`;
CREATE TABLE `appointment_slots` (
  `id` int(11) NOT NULL,
  `schedule` varchar(255) NOT NULL,
  `date` datetime(6) NOT NULL DEFAULT current_timestamp(6),
  `slot` varchar(255) NOT NULL,
  `is_archive` tinyint(4) NOT NULL DEFAULT 0,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

INSERT INTO `appointment_slots` (id, schedule, date, slot, is_archive) VALUES ('1', 'Morning', '2024-05-04 00:00:00.000000', '18', '0');
INSERT INTO `appointment_slots` (id, schedule, date, slot, is_archive) VALUES ('2', 'Afternoon', '2024-11-25 00:00:00.000000', '81', '0');
INSERT INTO `appointment_slots` (id, schedule, date, slot, is_archive) VALUES ('3', 'Morning', '2024-11-16 00:00:00.000000', '0', '0');
INSERT INTO `appointment_slots` (id, schedule, date, slot, is_archive) VALUES ('4', 'Morning', '2024-11-09 00:00:00.000000', '0', '0');

DROP TABLE IF EXISTS `departments`;
CREATE TABLE `departments` (
  `id` int(255) NOT NULL,
  `name` varchar(255) NOT NULL,
  `date_added` date NOT NULL DEFAULT current_timestamp(),
  `is_archive` tinyint(4) NOT NULL DEFAULT 0,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

INSERT INTO `departments` (id, name, date_added, is_archive) VALUES ('1', 'PHYSICIST', '2024-11-21', '0');
INSERT INTO `departments` (id, name, date_added, is_archive) VALUES ('2', 'RADIOLOGICAL TECHNOLOGIST', '2024-11-21', '0');
INSERT INTO `departments` (id, name, date_added, is_archive) VALUES ('3', 'MEDICAL TECHNICIAN', '2024-11-21', '0');
INSERT INTO `departments` (id, name, date_added, is_archive) VALUES ('4', 'MEDICAL CONSULTANT', '2024-11-21', '0');

DROP TABLE IF EXISTS `doctor`;
CREATE TABLE `doctor` (
  `employee_id` varchar(255) NOT NULL,
  `title` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_bin NOT NULL CHECK (json_valid(`title`)),
  `firstname` varchar(255) NOT NULL,
  `lastname` varchar(255) NOT NULL,
  `username` varchar(255) NOT NULL,
  `email` varchar(255) NOT NULL,
  `password` varchar(255) NOT NULL,
  `profile_img` varchar(255) DEFAULT NULL,
  `department_id` int(11) DEFAULT NULL,
  `date_added` timestamp NOT NULL DEFAULT current_timestamp(),
  `is_archive` tinyint(4) NOT NULL DEFAULT 0,
  PRIMARY KEY (`employee_id`),
  KEY `fk_doctor_department_new` (`department_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;


DROP TABLE IF EXISTS `logs`;
CREATE TABLE `logs` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `action` varchar(255) NOT NULL,
  `user` int(11) NOT NULL,
  `timestamp` timestamp NOT NULL DEFAULT current_timestamp(),
  `details` text DEFAULT NULL,
  `is_archive` tinyint(1) DEFAULT 0,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=55 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

INSERT INTO `logs` (id, action, user, timestamp, details, is_archive) VALUES ('19', 'Add Patient', '1', '2024-11-16 13:00:01', NULL, '0');
INSERT INTO `logs` (id, action, user, timestamp, details, is_archive) VALUES ('20', 'Add Patient', '1', '2024-11-16 13:01:07', NULL, '0');
INSERT INTO `logs` (id, action, user, timestamp, details, is_archive) VALUES ('21', 'upload payment method', '0', '2024-11-17 03:15:51', 'Payment method updated with ID: 1', '0');
INSERT INTO `logs` (id, action, user, timestamp, details, is_archive) VALUES ('22', 'Add payment method error', '0', '2024-11-17 05:59:49', 'Failed to upload image for payment method: sample', '0');
INSERT INTO `logs` (id, action, user, timestamp, details, is_archive) VALUES ('23', 'Add payment method error', '0', '2024-11-17 06:00:15', 'Failed to upload image for payment method: sample 2 ', '0');
INSERT INTO `logs` (id, action, user, timestamp, details, is_archive) VALUES ('24', 'Add payment method error', '0', '2024-11-17 06:02:43', 'Payment method already exists: sample 2 ', '0');
INSERT INTO `logs` (id, action, user, timestamp, details, is_archive) VALUES ('25', 'Add payment method error', '0', '2024-11-17 06:03:00', 'Failed to upload image for payment method: neww23', '0');
INSERT INTO `logs` (id, action, user, timestamp, details, is_archive) VALUES ('26', 'Add payment method', '0', '2024-11-17 06:05:01', 'Payment method added: neww23', '0');
INSERT INTO `logs` (id, action, user, timestamp, details, is_archive) VALUES ('27', 'edit patient', '0', '2024-11-19 04:35:56', 'Patient updated with ID: 9', '0');
INSERT INTO `logs` (id, action, user, timestamp, details, is_archive) VALUES ('28', 'Submit medical record', '0', '2024-11-19 04:36:25', 'Medical record added for patient ID: 9', '0');
INSERT INTO `logs` (id, action, user, timestamp, details, is_archive) VALUES ('29', 'edit department', '0', '2024-11-21 15:51:35', 'Department updated with ID: 1', '0');
INSERT INTO `logs` (id, action, user, timestamp, details, is_archive) VALUES ('30', 'edit department', '0', '2024-11-21 15:52:59', 'Department updated with ID: 1', '0');
INSERT INTO `logs` (id, action, user, timestamp, details, is_archive) VALUES ('31', 'edit department', '0', '2024-11-21 15:53:49', 'Department updated with ID: 1', '0');
INSERT INTO `logs` (id, action, user, timestamp, details, is_archive) VALUES ('32', 'edit department', '0', '2024-11-21 15:53:54', 'Department updated with ID: 1', '0');
INSERT INTO `logs` (id, action, user, timestamp, details, is_archive) VALUES ('33', 'add patient', '0', '2024-11-26 05:39:30', 'Patient added with email: marlyn@gmail.com', '0');
INSERT INTO `logs` (id, action, user, timestamp, details, is_archive) VALUES ('34', 'update appointment', '0', '2024-11-26 08:29:15', 'Updated appointment with ID: 56 for patient ID: 10', '0');
INSERT INTO `logs` (id, action, user, timestamp, details, is_archive) VALUES ('35', 'update appointment', '0', '2024-11-26 08:29:23', 'Updated appointment with ID: 56 for patient ID: 10', '0');
INSERT INTO `logs` (id, action, user, timestamp, details, is_archive) VALUES ('36', 'update appointment', '0', '2024-11-26 08:42:05', 'Updated appointment with ID: 56 for patient ID: 10', '0');
INSERT INTO `logs` (id, action, user, timestamp, details, is_archive) VALUES ('37', 'Add Patient', '1', '2024-11-16 13:00:01', NULL, '0');
INSERT INTO `logs` (id, action, user, timestamp, details, is_archive) VALUES ('38', 'Add Patient', '1', '2024-11-16 13:01:07', NULL, '0');
INSERT INTO `logs` (id, action, user, timestamp, details, is_archive) VALUES ('39', 'upload payment method', '0', '2024-11-17 03:15:51', 'Payment method updated with ID: 1', '0');
INSERT INTO `logs` (id, action, user, timestamp, details, is_archive) VALUES ('40', 'Add payment method error', '0', '2024-11-17 05:59:49', 'Failed to upload image for payment method: sample', '0');
INSERT INTO `logs` (id, action, user, timestamp, details, is_archive) VALUES ('41', 'Add payment method error', '0', '2024-11-17 06:00:15', 'Failed to upload image for payment method: sample 2 ', '0');
INSERT INTO `logs` (id, action, user, timestamp, details, is_archive) VALUES ('42', 'Add payment method error', '0', '2024-11-17 06:02:43', 'Payment method already exists: sample 2 ', '0');
INSERT INTO `logs` (id, action, user, timestamp, details, is_archive) VALUES ('43', 'Add payment method error', '0', '2024-11-17 06:03:00', 'Failed to upload image for payment method: neww23', '0');
INSERT INTO `logs` (id, action, user, timestamp, details, is_archive) VALUES ('44', 'Add payment method', '0', '2024-11-17 06:05:01', 'Payment method added: neww23', '0');
INSERT INTO `logs` (id, action, user, timestamp, details, is_archive) VALUES ('45', 'edit patient', '0', '2024-11-19 04:35:56', 'Patient updated with ID: 9', '0');
INSERT INTO `logs` (id, action, user, timestamp, details, is_archive) VALUES ('46', 'Submit medical record', '0', '2024-11-19 04:36:25', 'Medical record added for patient ID: 9', '0');
INSERT INTO `logs` (id, action, user, timestamp, details, is_archive) VALUES ('47', 'edit department', '0', '2024-11-21 15:51:35', 'Department updated with ID: 1', '0');
INSERT INTO `logs` (id, action, user, timestamp, details, is_archive) VALUES ('48', 'edit department', '0', '2024-11-21 15:52:59', 'Department updated with ID: 1', '0');
INSERT INTO `logs` (id, action, user, timestamp, details, is_archive) VALUES ('49', 'edit department', '0', '2024-11-21 15:53:49', 'Department updated with ID: 1', '0');
INSERT INTO `logs` (id, action, user, timestamp, details, is_archive) VALUES ('50', 'edit department', '0', '2024-11-21 15:53:54', 'Department updated with ID: 1', '0');
INSERT INTO `logs` (id, action, user, timestamp, details, is_archive) VALUES ('51', 'add patient', '0', '2024-11-26 05:39:30', 'Patient added with email: marlyn@gmail.com', '0');
INSERT INTO `logs` (id, action, user, timestamp, details, is_archive) VALUES ('52', 'update appointment', '0', '2024-11-26 08:29:15', 'Updated appointment with ID: 56 for patient ID: 10', '0');
INSERT INTO `logs` (id, action, user, timestamp, details, is_archive) VALUES ('53', 'update appointment', '0', '2024-11-26 08:29:23', 'Updated appointment with ID: 56 for patient ID: 10', '0');
INSERT INTO `logs` (id, action, user, timestamp, details, is_archive) VALUES ('54', 'update appointment', '0', '2024-11-26 08:42:05', 'Updated appointment with ID: 56 for patient ID: 10', '0');

DROP TABLE IF EXISTS `medical_records`;
CREATE TABLE `medical_records` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `patient_id` int(11) NOT NULL,
  `appointment_id` int(255) NOT NULL,
  `doctor_id` varchar(255) NOT NULL,
  `diagnosis` text NOT NULL,
  `treatment` text NOT NULL,
  `prescription` varchar(255) NOT NULL,
  `content` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_bin NOT NULL CHECK (json_valid(`content`)),
  `status` varchar(255) NOT NULL DEFAULT 'Pending',
  `record_date` date NOT NULL DEFAULT current_timestamp(),
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;


DROP TABLE IF EXISTS `patient`;
CREATE TABLE `patient` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `firstname` varchar(255) NOT NULL,
  `lastname` varchar(255) NOT NULL,
  `email` varchar(255) NOT NULL,
  `age` int(255) NOT NULL,
  `sex` varchar(255) NOT NULL,
  `dob` varchar(255) DEFAULT NULL,
  `birthplace` varchar(255) DEFAULT NULL,
  `contact` varchar(255) DEFAULT NULL,
  `password` varchar(255) NOT NULL,
  `province` varchar(255) DEFAULT NULL,
  `city` varchar(255) DEFAULT NULL,
  `barangay` varchar(255) DEFAULT NULL,
  `street` varchar(255) DEFAULT NULL,
  `date_added` timestamp NOT NULL DEFAULT current_timestamp(),
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=30 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

INSERT INTO `patient` (id, firstname, lastname, email, age, sex, dob, birthplace, contact, password, province, city, barangay, street, date_added) VALUES ('9', 'Kyle Andre', 'Lim', 'kylemastercoder14@gmail.com', '0', '', '2000-01-14', 'Cavite', '9152479691', '$2y$10$3PCQfGLAMDsHHnCjAH2np.2rBEr3XF.h6TlmZ9fdc4BtqyLoOioCq', 'Cavite', 'City of Dasmariñas', 'Santa Lucia', 'B111 L4 Ruby Street', '2024-05-04 13:34:34');
INSERT INTO `patient` (id, firstname, lastname, email, age, sex, dob, birthplace, contact, password, province, city, barangay, street, date_added) VALUES ('10', 'Mherwen Wiel', 'Romero', 'romeroqmherwen@gmail.com', '24', 'Male', '2024-11-16', 'Dasma', '09553471926', '$2y$10$Kd4wh.OShR1hgpORWodxVu/HDR3MVn.kbzPbaErn4R.pXXaAetj3K', 'Cavite', 'City of Tagaytay', 'Tolentino West', 'BLK D 8 LOT 16 SAN LUIS 1 DASMARIÑAS CAV', '2024-11-15 11:33:41');
INSERT INTO `patient` (id, firstname, lastname, email, age, sex, dob, birthplace, contact, password, province, city, barangay, street, date_added) VALUES ('25', 'Lindaxxx', 'Romero', 'Linda@gmail.com', '0', '', '2024-11-17', NULL, '0955237123', '', 'cavite', 'dasmarinas', 'SABUTAN', 'blk d8 lot 15', '2024-11-17 02:55:36');
INSERT INTO `patient` (id, firstname, lastname, email, age, sex, dob, birthplace, contact, password, province, city, barangay, street, date_added) VALUES ('26', 'Marlyn', 'Leano', 'marlyn@gmail.com', '0', '', '2024-11-26', NULL, '09123823123', '$2y$10$xZpeDN7g64ZGcDFwcf8nauDdRzS06q5XUJCS1ldllNgpoqhJ7ZWl.', 'CAVITE', 'City of DASMARIÑAS', 'San Luis 1', 'BLK D 8 LOT 16 SAN LUIS 1 DASMARIÑAS CAV', '2024-11-26 05:39:30');
INSERT INTO `patient` (id, firstname, lastname, email, age, sex, dob, birthplace, contact, password, province, city, barangay, street, date_added) VALUES ('29', 'Ivy', 'Barrios', 'ivy@gmail.com', '22', 'Female', '2002-07-08', 'CITY OF DASMARIÑAS', '09165936399', '$2y$10$Gx6IB.NyNfB5r6PjZp.5oOmqPY0RXSgd.La4NrgsZucpzv7kSWHZa', 'CAVITE', 'CITY OF DASMARIÑAS', 'SAN LUIS 1', 'BLK D 8 LOT 16 SAN LUIS 1 DASMARIÑAS CAV', '2024-11-28 03:25:56');

DROP TABLE IF EXISTS `payment_mode`;
CREATE TABLE `payment_mode` (
  `id` int(255) NOT NULL AUTO_INCREMENT,
  `method` varchar(255) NOT NULL,
  `image_path` varchar(255) NOT NULL,
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=4 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

INSERT INTO `payment_mode` (id, method, image_path, updated_at) VALUES ('1', 'Gcash', 'uploads/payment_methods/gcash.jpg', '2024-11-17 04:11:04');
INSERT INTO `payment_mode` (id, method, image_path, updated_at) VALUES ('3', 'Cash', 'uploads/payment_methods/cash.jpg', '2024-11-17 04:11:24');

DROP TABLE IF EXISTS `payment_receipts`;
CREATE TABLE `payment_receipts` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `appointment_id` int(11) DEFAULT NULL,
  `payment_receipt_path` varchar(255) DEFAULT NULL,
  `date` date DEFAULT NULL,
  `payment_mode_id` int(11) DEFAULT NULL,
  `amount` decimal(10,2) DEFAULT NULL,
  `status` varchar(255) NOT NULL DEFAULT 'pending',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;


DROP TABLE IF EXISTS `services`;
CREATE TABLE `services` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `category` varchar(255) NOT NULL,
  `type` varchar(255) NOT NULL,
  `service` varchar(255) NOT NULL,
  `department_id` int(255) NOT NULL,
  `cost` varchar(255) NOT NULL,
  `date_added` timestamp NOT NULL DEFAULT current_timestamp(),
  `is_archive` tinyint(4) NOT NULL DEFAULT 0,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=5 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

INSERT INTO `services` (id, category, type, service, department_id, cost, date_added, is_archive) VALUES ('1', 'Imaging Services', 'X-Ray', 'Spot Film', '2', '300', '2024-05-03 07:44:15', '0');
INSERT INTO `services` (id, category, type, service, department_id, cost, date_added, is_archive) VALUES ('2', 'Imaging Services', 'X-Ray', 'T-Cage', '2', '250.80', '2024-05-03 07:44:54', '0');
INSERT INTO `services` (id, category, type, service, department_id, cost, date_added, is_archive) VALUES ('3', 'Laboratory Services', 'CBC', 'Red Blood Cell', '1', '200', '2024-05-03 07:53:00', '0');
INSERT INTO `services` (id, category, type, service, department_id, cost, date_added, is_archive) VALUES ('4', 'Imaging Services', 'CT SCAN', 'PELVIC', '2', '100', '2024-11-16 03:17:58', '0');

