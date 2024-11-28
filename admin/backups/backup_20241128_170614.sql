DROP TABLE IF EXISTS `admin`;
CREATE TABLE `admin` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `username` varchar(255) NOT NULL,
  `password` varchar(255) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

INSERT INTO `admin` (id, username, password) VALUES ('1', 'trioadmin', '$2y$10$iJQSWF2QB/DQHz/EvN5zq.LdtuSSAo4LFqwubtosN3a36PphAjvJ6');

DROP TABLE IF EXISTS `appointment`;
CREATE TABLE `appointment` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
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
) ENGINE=InnoDB AUTO_INCREMENT=62 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

INSERT INTO `appointment` (id, service_id, patient_id, appointment_date, appointment_slot_id, appointment_time, doctor_id, department_id, selectedPayment, medical, status, paid, date_added, is_archive) VALUES ('55', '1', '10', '2024-11-25', '2', '10:29', 'TRLB6874', '1', '3', 'fracture', 'Pending', 'Pending', '2024-11-24 16:29:45', '0');
INSERT INTO `appointment` (id, service_id, patient_id, appointment_date, appointment_slot_id, appointment_time, doctor_id, department_id, selectedPayment, medical, status, paid, date_added, is_archive) VALUES ('56', '2', '10', '2024-11-25', '4', '13:04', 'TRLB7292', '3', '3', 'fracture', 'Pending', 'Pending', '2024-11-25 06:04:56', '0');
INSERT INTO `appointment` (id, service_id, patient_id, appointment_date, appointment_slot_id, appointment_time, doctor_id, department_id, selectedPayment, medical, status, paid, date_added, is_archive) VALUES ('57', '1', '10', '2024-11-25', '2', '13:46', 'TRLB6874', '1', '3', 'dadczcxz', 'Pending', 'Pending', '2024-11-25 14:47:02', '0');
INSERT INTO `appointment` (id, service_id, patient_id, appointment_date, appointment_slot_id, appointment_time, doctor_id, department_id, selectedPayment, medical, status, paid, date_added, is_archive) VALUES ('58', '1', '10', '2024-11-25', '2', '15:05', 'TRLB7292', '3', '3', 'fracture', 'Pending', 'Pending', '2024-11-28 08:06:07', '0');
INSERT INTO `appointment` (id, service_id, patient_id, appointment_date, appointment_slot_id, appointment_time, doctor_id, department_id, selectedPayment, medical, status, paid, date_added, is_archive) VALUES ('59', '1', '10', '2024-11-25', '2', '15:10', 'TRLB7292', '3', '3', 'fracture', 'Pending', 'Pending', '2024-11-28 08:11:07', '0');
INSERT INTO `appointment` (id, service_id, patient_id, appointment_date, appointment_slot_id, appointment_time, doctor_id, department_id, selectedPayment, medical, status, paid, date_added, is_archive) VALUES ('60', '1', '10', '2024-11-25', '2', '15:12', 'TRLB6979', '2', '3', 'fracture', 'Pending', 'Pending', '2024-11-28 08:12:46', '0');
INSERT INTO `appointment` (id, service_id, patient_id, appointment_date, appointment_slot_id, appointment_time, doctor_id, department_id, selectedPayment, medical, status, paid, date_added, is_archive) VALUES ('61', '1', '10', '2024-11-25', '2', '15:13', '0', '0', '1', 'fracture', 'Pending', 'Pending', '2024-11-28 08:14:19', '0');

DROP TABLE IF EXISTS `appointment_slots`;
CREATE TABLE `appointment_slots` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `schedule` varchar(255) NOT NULL,
  `date` datetime(6) NOT NULL DEFAULT current_timestamp(6),
  `slot` varchar(255) NOT NULL,
  `is_archive` tinyint(4) NOT NULL DEFAULT 0,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=5 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

INSERT INTO `appointment_slots` (id, schedule, date, slot, is_archive) VALUES ('1', 'Morning', '2024-05-04 00:00:00.000000', '18', '0');
INSERT INTO `appointment_slots` (id, schedule, date, slot, is_archive) VALUES ('2', 'Afternoon', '2024-11-25 00:00:00.000000', '77', '0');
INSERT INTO `appointment_slots` (id, schedule, date, slot, is_archive) VALUES ('3', 'Morning', '2024-11-16 00:00:00.000000', '0', '0');
INSERT INTO `appointment_slots` (id, schedule, date, slot, is_archive) VALUES ('4', 'Morning', '2024-11-09 00:00:00.000000', '0', '0');

DROP TABLE IF EXISTS `departments`;
CREATE TABLE `departments` (
  `id` int(255) NOT NULL AUTO_INCREMENT,
  `name` varchar(255) NOT NULL,
  `date_added` date NOT NULL DEFAULT current_timestamp(),
  `is_archive` tinyint(4) NOT NULL DEFAULT 0,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=5 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

INSERT INTO `departments` (id, name, date_added, is_archive) VALUES ('1', 'PHYSICIST', '2024-11-21', '0');
INSERT INTO `departments` (id, name, date_added, is_archive) VALUES ('2', 'RADIOLOGICAL TECHNOLOGIST', '2024-11-21', '0');
INSERT INTO `departments` (id, name, date_added, is_archive) VALUES ('3', 'MEDICAL TECHNICIAN', '2024-11-21', '0');
INSERT INTO `departments` (id, name, date_added, is_archive) VALUES ('4', 'MEDICAL CONSULTANT', '2024-11-21', '0');

DROP TABLE IF EXISTS `doctor`;
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
  `is_archive` tinyint(4) NOT NULL DEFAULT 0,
  PRIMARY KEY (`employee_id`),
  KEY `fk_doctor_department_new` (`department_id`),
  CONSTRAINT `fk_doctor_department_new` FOREIGN KEY (`department_id`) REFERENCES `departments` (`id`) ON DELETE SET NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

INSERT INTO `doctor` (employee_id, firstname, lastname, username, email, password, profile_img, department_id, date_added, is_archive) VALUES ('TRLB6874', 'Mherwen Wiel', 'Romero', 'mherwen123', 'mherwen123@gmail.com', '$2y$10$EGS8S58v20zx4qaBs3.hquI1.jlrFp54uTl8zbaC5tu1aGXeXqxym', 'uploads/Gaming_5000x3125.jpg', '1', '2024-05-05 09:35:17', '0');
INSERT INTO `doctor` (employee_id, firstname, lastname, username, email, password, profile_img, department_id, date_added, is_archive) VALUES ('TRLB6979', 'Ivy', 'Barrios', 'ivy', 'ivy@gmail.com', '$2y$10$IW6QrrhBiJjgCSKegXnueOkgjvqaeIjYzhzZqgLzfjdFn4ZtLVYn.', NULL, '2', '2024-11-28 15:56:13', '0');
INSERT INTO `doctor` (employee_id, firstname, lastname, username, email, password, profile_img, department_id, date_added, is_archive) VALUES ('TRLB7292', 'Erwin', 'Petil', 'erwin123', 'erwin@gmail.com', '$2y$10$dixFjSuNzEx1Z6XrCwqQh.w60ecMNqqIq3AHDCIU4qwSToctXbro2', NULL, '3', '2024-05-05 10:53:34', '0');

DROP TABLE IF EXISTS `logs`;
CREATE TABLE `logs` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `action` varchar(255) NOT NULL,
  `user` int(11) NOT NULL,
  `timestamp` timestamp NOT NULL DEFAULT current_timestamp(),
  `details` text DEFAULT NULL,
  `is_archive` tinyint(1) DEFAULT 0,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=33 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

INSERT INTO `logs` (id, action, user, timestamp, details, is_archive) VALUES ('1', 'Add Patient', '1', '2024-11-16 21:00:01', NULL, '0');
INSERT INTO `logs` (id, action, user, timestamp, details, is_archive) VALUES ('2', 'Add Patient', '1', '2024-11-16 21:01:07', NULL, '0');
INSERT INTO `logs` (id, action, user, timestamp, details, is_archive) VALUES ('3', 'upload payment method', '0', '2024-11-17 11:15:51', 'Payment method updated with ID: 1', '0');
INSERT INTO `logs` (id, action, user, timestamp, details, is_archive) VALUES ('4', 'Add payment method error', '0', '2024-11-17 13:59:49', 'Failed to upload image for payment method: sample', '0');
INSERT INTO `logs` (id, action, user, timestamp, details, is_archive) VALUES ('5', 'Add payment method error', '0', '2024-11-17 14:00:15', 'Failed to upload image for payment method: sample 2 ', '0');
INSERT INTO `logs` (id, action, user, timestamp, details, is_archive) VALUES ('6', 'Add payment method error', '0', '2024-11-17 14:02:43', 'Payment method already exists: sample 2 ', '0');
INSERT INTO `logs` (id, action, user, timestamp, details, is_archive) VALUES ('7', 'Add payment method error', '0', '2024-11-17 14:03:00', 'Failed to upload image for payment method: neww23', '0');
INSERT INTO `logs` (id, action, user, timestamp, details, is_archive) VALUES ('8', 'Add payment method', '0', '2024-11-17 14:05:01', 'Payment method added: neww23', '0');
INSERT INTO `logs` (id, action, user, timestamp, details, is_archive) VALUES ('9', 'edit patient', '0', '2024-11-19 12:35:56', 'Patient updated with ID: 9', '0');
INSERT INTO `logs` (id, action, user, timestamp, details, is_archive) VALUES ('10', 'Submit medical record', '0', '2024-11-19 12:36:25', 'Medical record added for patient ID: 9', '0');
INSERT INTO `logs` (id, action, user, timestamp, details, is_archive) VALUES ('11', 'edit department', '0', '2024-11-21 23:51:35', 'Department updated with ID: 1', '0');
INSERT INTO `logs` (id, action, user, timestamp, details, is_archive) VALUES ('12', 'edit department', '0', '2024-11-21 23:52:59', 'Department updated with ID: 1', '0');
INSERT INTO `logs` (id, action, user, timestamp, details, is_archive) VALUES ('13', 'edit department', '0', '2024-11-21 23:53:49', 'Department updated with ID: 1', '0');
INSERT INTO `logs` (id, action, user, timestamp, details, is_archive) VALUES ('14', 'edit department', '0', '2024-11-21 23:53:54', 'Department updated with ID: 1', '0');
INSERT INTO `logs` (id, action, user, timestamp, details, is_archive) VALUES ('15', 'add patient', '0', '2024-11-26 13:39:30', 'Patient added with email: marlyn@gmail.com', '0');
INSERT INTO `logs` (id, action, user, timestamp, details, is_archive) VALUES ('16', 'update appointment', '0', '2024-11-26 16:29:15', 'Updated appointment with ID: 56 for patient ID: 10', '0');
INSERT INTO `logs` (id, action, user, timestamp, details, is_archive) VALUES ('17', 'update appointment', '0', '2024-11-26 16:29:23', 'Updated appointment with ID: 56 for patient ID: 10', '0');
INSERT INTO `logs` (id, action, user, timestamp, details, is_archive) VALUES ('18', 'update appointment', '0', '2024-11-26 16:42:05', 'Updated appointment with ID: 56 for patient ID: 10', '0');
INSERT INTO `logs` (id, action, user, timestamp, details, is_archive) VALUES ('19', 'add appointment', '10', '2024-11-28 15:06:07', 'Appointment booked with ID: 58 for patient ID: 10', '0');
INSERT INTO `logs` (id, action, user, timestamp, details, is_archive) VALUES ('20', 'add appointment error', '10', '2024-11-28 15:06:07', 'Error booking appointment for patient ID: 10 - SQLSTATE[23000]: Integrity constraint violation: 4025 CONSTRAINT `medical_records.content` failed for `triolab_db`.`medical_records`', '0');
INSERT INTO `logs` (id, action, user, timestamp, details, is_archive) VALUES ('21', 'add appointment', '10', '2024-11-28 15:11:07', 'Appointment booked with ID: 59 for patient ID: 10', '0');
INSERT INTO `logs` (id, action, user, timestamp, details, is_archive) VALUES ('22', 'add appointment error', '10', '2024-11-28 15:11:07', 'Error booking appointment for patient ID: 10 - SQLSTATE[23000]: Integrity constraint violation: 4025 CONSTRAINT `medical_records.content` failed for `triolab_db`.`medical_records`', '0');
INSERT INTO `logs` (id, action, user, timestamp, details, is_archive) VALUES ('23', 'add appointment', '10', '2024-11-28 15:12:46', 'Appointment booked with ID: 60 for patient ID: 10', '0');
INSERT INTO `logs` (id, action, user, timestamp, details, is_archive) VALUES ('24', 'add appointment', '10', '2024-11-28 15:14:19', 'Appointment booked with ID: 61 for patient ID: 10', '0');
INSERT INTO `logs` (id, action, user, timestamp, details, is_archive) VALUES ('25', 'update appointment', '0', '2024-11-28 15:36:46', 'Updated appointment with ID: 56 for patient ID: 10', '0');
INSERT INTO `logs` (id, action, user, timestamp, details, is_archive) VALUES ('26', 'update appointment', '0', '2024-11-28 15:40:48', 'Updated appointment with ID: 56 for patient ID: 10', '0');
INSERT INTO `logs` (id, action, user, timestamp, details, is_archive) VALUES ('27', 'update appointment', '0', '2024-11-28 15:48:25', 'Updated appointment with ID: 56 for patient ID: 10', '0');
INSERT INTO `logs` (id, action, user, timestamp, details, is_archive) VALUES ('28', 'update appointment', '0', '2024-11-28 15:52:53', 'Updated appointment with ID: 56 for patient ID: 10', '0');
INSERT INTO `logs` (id, action, user, timestamp, details, is_archive) VALUES ('29', 'add doctor', '0', '2024-11-28 15:56:13', 'Doctor added with employee ID: TRLB6979', '0');
INSERT INTO `logs` (id, action, user, timestamp, details, is_archive) VALUES ('30', 'update appointment', '0', '2024-11-28 16:20:09', 'Updated appointment with ID: 58 for patient ID: 10', '0');
INSERT INTO `logs` (id, action, user, timestamp, details, is_archive) VALUES ('31', 'update appointment', '0', '2024-11-28 16:30:57', 'Updated appointment with ID: 58 for patient ID: 10', '0');
INSERT INTO `logs` (id, action, user, timestamp, details, is_archive) VALUES ('32', 'update appointment', '0', '2024-11-28 16:31:09', 'Updated appointment with ID: 58 for patient ID: 10', '0');

DROP TABLE IF EXISTS `medical_records`;
CREATE TABLE `medical_records` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `patient_id` int(11) NOT NULL,
  `appointment_id` int(255) NOT NULL,
  `doctor_id` varchar(255) NOT NULL,
  `diagnosis` text NOT NULL,
  `treatment` text NOT NULL,
  `prescription` varchar(255) NOT NULL,
  `content` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_bin DEFAULT NULL,
  `status` varchar(255) NOT NULL DEFAULT 'Pending',
  `record_date` date NOT NULL DEFAULT current_timestamp(),
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  PRIMARY KEY (`id`),
  KEY `fk_patient_id` (`patient_id`),
  CONSTRAINT `fk_patient_id` FOREIGN KEY (`patient_id`) REFERENCES `patient` (`id`) ON DELETE CASCADE,
  CONSTRAINT `medical_records_ibfk_1` FOREIGN KEY (`patient_id`) REFERENCES `patient` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=20 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

INSERT INTO `medical_records` (id, patient_id, appointment_id, doctor_id, diagnosis, treatment, prescription, content, status, record_date, created_at, updated_at) VALUES ('11', '10', '55', 'TRLB6874', 'Diagnosis', 'Treatment', 'Prescription', '{\"hemoglobin\":null,\"hematocrit\":null,\"wbc_count\":null,\"rbc_count\":null,\"segmenters\":null,\"lymphocytes\":null,\"eosinophils\":null,\"monocytes\":null,\"platelet_count\":null,\"blood_type\":null}', 'Pending', '2024-11-26', '2024-11-24 23:29:45', '2024-11-28 16:52:50');
INSERT INTO `medical_records` (id, patient_id, appointment_id, doctor_id, diagnosis, treatment, prescription, content, status, record_date, created_at, updated_at) VALUES ('12', '10', '56', 'TRLB7292', 'diagnossis', 'treatment', 'not  yet', '{\"date\":\"2024-11-28\",\"x_ray_number\":\"X-ray No. 24 - 7\",\"patient_name\":\"ROMERO, MHERWEN WIEL\",\"age_sex\":\"24\\/Male\",\"address\":\"City of Tagaytay\",\"request_by\":\"OP\",\"examination\":\"CHEST PA\",\"findings\":\"this is a data\",\"impression\":\"data\",\"doctor_name\":\"ERWIN PETIL\",\"doctor_title\":\"MD\",\"radiologist_signature\":\"ERWIN PETIL, MD\",\"radiologist_position\":\"Radiologist\"}', 'Pending', '2024-11-26', '2024-11-25 13:04:56', '2024-11-28 16:52:52');
INSERT INTO `medical_records` (id, patient_id, appointment_id, doctor_id, diagnosis, treatment, prescription, content, status, record_date, created_at, updated_at) VALUES ('15', '10', '57', 'TRLB6874', '', '', '', '{\"hemoglobin\":\"2\",\"hematocrit\":\"2\",\"wbc_count\":\"2\",\"rbc_count\":\"2\",\"segmenters\":\"2\",\"lymphocytes\":\"2\",\"eosinophils\":\"2\",\"monocytes\":\"2\",\"platelet_count\":\"2\",\"blood_type\":\"F\"}', 'Pending', '2024-11-27', '2024-11-27 23:07:29', '2024-11-27 23:30:43');
INSERT INTO `medical_records` (id, patient_id, appointment_id, doctor_id, diagnosis, treatment, prescription, content, status, record_date, created_at, updated_at) VALUES ('16', '10', '60', 'TRLB6979', '', '', '', 'No content provided', 'Pending', '2024-11-28', '2024-11-28 15:12:46', '2024-11-28 16:56:32');
INSERT INTO `medical_records` (id, patient_id, appointment_id, doctor_id, diagnosis, treatment, prescription, content, status, record_date, created_at, updated_at) VALUES ('17', '10', '61', '', '', '', '', 'No content provided', 'Pending', '2024-11-28', '2024-11-28 15:14:19', '2024-11-28 15:14:19');
INSERT INTO `medical_records` (id, patient_id, appointment_id, doctor_id, diagnosis, treatment, prescription, content, status, record_date, created_at, updated_at) VALUES ('18', '10', '58', 'TRLB7292', '', '', '', '{\"date\":\"2024-11-28\",\"x_ray_number\":\"X-ray No. 24 - 7\",\"patient_name\":\"ROMERO, MHERWEN WIEL\",\"age_sex\":\"24\\/Male\",\"address\":\"City of Tagaytay\",\"request_by\":\"OP\",\"examination\":\"CHEST PA\",\"findings\":\"CHEST PA\",\"impression\":\"CHEST PA\",\"doctor_name\":\"IVY BARRIOS\",\"doctor_title\":\"MD\",\"radiologist_signature\":\"IVY BARRIOS, MD\",\"radiologist_position\":\"Radiologist\"}', 'Pending', '2024-11-28', '2024-11-28 16:19:06', '2024-11-28 16:52:55');
INSERT INTO `medical_records` (id, patient_id, appointment_id, doctor_id, diagnosis, treatment, prescription, content, status, record_date, created_at, updated_at) VALUES ('19', '10', '59', 'TRLB7292', '', '', '', NULL, 'Pending', '2024-11-28', '2024-11-28 16:46:50', '2024-11-28 16:47:22');

DROP TABLE IF EXISTS `patient`;
CREATE TABLE `patient` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `firstname` varchar(255) NOT NULL,
  `lastname` varchar(255) NOT NULL,
  `age` varchar(255) NOT NULL,
  `sex` varchar(255) NOT NULL,
  `email` varchar(255) NOT NULL,
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
) ENGINE=InnoDB AUTO_INCREMENT=27 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

INSERT INTO `patient` (id, firstname, lastname, age, sex, email, dob, birthplace, contact, password, province, city, barangay, street, date_added) VALUES ('9', 'Kyle Andre', 'Lim', '24', 'Male', 'kylemastercoder14@gmail.com', '2000-01-14', 'Cavite', '9152479691', '$2y$10$3PCQfGLAMDsHHnCjAH2np.2rBEr3XF.h6TlmZ9fdc4BtqyLoOioCq', 'Cavite', 'City of Dasmariñas', 'Santa Lucia', 'B111 L4 Ruby Street', '2024-05-04 21:34:34');
INSERT INTO `patient` (id, firstname, lastname, age, sex, email, dob, birthplace, contact, password, province, city, barangay, street, date_added) VALUES ('10', 'Mherwen Wiel', 'Romero', '24', 'Male', 'romeroqmherwen@gmail.com', '2024-11-16', 'Dasma', '09553471926', '$2y$10$Kd4wh.OShR1hgpORWodxVu/HDR3MVn.kbzPbaErn4R.pXXaAetj3K', 'Cavite', 'City of Tagaytay', 'Tolentino West', 'BLK D 8 LOT 16 SAN LUIS 1 DASMARIÑAS CAV', '2024-11-15 19:33:41');
INSERT INTO `patient` (id, firstname, lastname, age, sex, email, dob, birthplace, contact, password, province, city, barangay, street, date_added) VALUES ('25', 'Lindaxxx', 'Romero', '70', 'Female', 'Linda@gmail.com', '2024-11-17', NULL, '0955237123', '', 'cavite', 'dasmarinas', 'SABUTAN', 'blk d8 lot 15', '2024-11-17 10:55:36');
INSERT INTO `patient` (id, firstname, lastname, age, sex, email, dob, birthplace, contact, password, province, city, barangay, street, date_added) VALUES ('26', 'Marlyn', 'Leano', '32', 'Female', 'marlyn@gmail.com', '2024-11-26', NULL, '09123823123', '$2y$10$xZpeDN7g64ZGcDFwcf8nauDdRzS06q5XUJCS1ldllNgpoqhJ7ZWl.', 'CAVITE', 'City of DASMARIÑAS', 'San Luis 1', 'BLK D 8 LOT 16 SAN LUIS 1 DASMARIÑAS CAV', '2024-11-26 13:39:30');

DROP TABLE IF EXISTS `payment_mode`;
CREATE TABLE `payment_mode` (
  `id` int(255) NOT NULL AUTO_INCREMENT,
  `method` varchar(255) NOT NULL,
  `image_path` varchar(255) NOT NULL,
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=8 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

INSERT INTO `payment_mode` (id, method, image_path, updated_at) VALUES ('1', 'Gcash', 'uploads/payment_methods/gcash.jpg', '2024-11-17 12:11:04');
INSERT INTO `payment_mode` (id, method, image_path, updated_at) VALUES ('3', 'Cash', 'uploads/payment_methods/cash.jpg', '2024-11-17 12:11:24');

DROP TABLE IF EXISTS `payment_receipts`;
CREATE TABLE `payment_receipts` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `appointment_id` int(11) DEFAULT NULL,
  `payment_receipt_path` varchar(255) DEFAULT NULL,
  `date` date DEFAULT NULL,
  `payment_mode_id` int(11) DEFAULT NULL,
  `amount` decimal(10,2) DEFAULT NULL,
  `status` varchar(255) NOT NULL DEFAULT 'pending',
  PRIMARY KEY (`id`),
  KEY `appointment_id` (`appointment_id`),
  KEY `payment_mode_id` (`payment_mode_id`),
  CONSTRAINT `payment_receipts_ibfk_1` FOREIGN KEY (`appointment_id`) REFERENCES `appointment` (`id`) ON DELETE CASCADE,
  CONSTRAINT `payment_receipts_ibfk_2` FOREIGN KEY (`payment_mode_id`) REFERENCES `payment_mode` (`id`) ON DELETE SET NULL
) ENGINE=InnoDB AUTO_INCREMENT=17 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

INSERT INTO `payment_receipts` (id, appointment_id, payment_receipt_path, date, payment_mode_id, amount, status) VALUES ('10', '55', '', '2024-11-24', '3', '300.50', 'Approved');
INSERT INTO `payment_receipts` (id, appointment_id, payment_receipt_path, date, payment_mode_id, amount, status) VALUES ('11', '56', '', '2024-11-26', '3', '400.50', 'Approved');
INSERT INTO `payment_receipts` (id, appointment_id, payment_receipt_path, date, payment_mode_id, amount, status) VALUES ('12', '57', '', '2024-11-26', '3', '300.50', 'Pending');
INSERT INTO `payment_receipts` (id, appointment_id, payment_receipt_path, date, payment_mode_id, amount, status) VALUES ('13', '61', 'uploads/payment_receipts/1732778059_Gaming_5000x3125.jpg', '2024-11-28', '1', '300.00', 'Pending');
INSERT INTO `payment_receipts` (id, appointment_id, payment_receipt_path, date, payment_mode_id, amount, status) VALUES ('14', '58', '', '2024-11-28', '3', '300.50', 'Pending');
INSERT INTO `payment_receipts` (id, appointment_id, payment_receipt_path, date, payment_mode_id, amount, status) VALUES ('15', '59', '', '2024-11-28', '3', '300.50', 'Pending');
INSERT INTO `payment_receipts` (id, appointment_id, payment_receipt_path, date, payment_mode_id, amount, status) VALUES ('16', '60', '', '2024-11-28', '3', '300.50', 'Pending');

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

INSERT INTO `services` (id, category, type, service, department_id, cost, date_added, is_archive) VALUES ('1', 'Imaging Services', 'X-Ray', 'Spot Film', '2', '300.50', '2024-05-03 15:44:15', '0');
INSERT INTO `services` (id, category, type, service, department_id, cost, date_added, is_archive) VALUES ('2', 'Imaging Services', 'X-Ray', 'T-Cage', '2', '250.80', '2024-05-03 15:44:54', '0');
INSERT INTO `services` (id, category, type, service, department_id, cost, date_added, is_archive) VALUES ('3', 'Laboratory Services', 'CBC', 'Red Blood Cell', '2', '200', '2024-05-03 15:53:00', '0');
INSERT INTO `services` (id, category, type, service, department_id, cost, date_added, is_archive) VALUES ('4', 'Imaging Services', 'CT SCAN', 'PELVIC', '2', '100', '2024-11-16 11:17:58', '0');

