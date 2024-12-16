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
  `app_id` varchar(255) NOT NULL,
  `service_id` int(11) NOT NULL,
  `patient_id` int(11) NOT NULL,
  `appointment_date` varchar(255) NOT NULL,
  `appointment_slot_id` int(255) NOT NULL,
  `appointment_time` varchar(255) NOT NULL,
  `doctor_id` varchar(255) DEFAULT NULL,
  `department_id` int(255) DEFAULT NULL,
  `selectedPayment` varchar(255) DEFAULT NULL,
  `medical` varchar(255) NOT NULL DEFAULT 'Pending',
  `status` varchar(255) NOT NULL DEFAULT 'Pending',
  `paid` varchar(255) NOT NULL DEFAULT 'Pending',
  `date_added` timestamp NOT NULL DEFAULT current_timestamp(),
  `is_archive` tinyint(4) NOT NULL DEFAULT 0,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=115 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

INSERT INTO `appointment` (id, app_id, service_id, patient_id, appointment_date, appointment_slot_id, appointment_time, doctor_id, department_id, selectedPayment, medical, status, paid, date_added, is_archive) VALUES ('114', 'APP-BSRMYNUI-20241215', '1', '10', '2024-12-08', '4', '12:00', 'TRLB7292', '2', NULL, 'Completed', 'Completed', 'Completed', '2024-12-15 14:47:30', '0');

DROP TABLE IF EXISTS `appointment_slots`;
CREATE TABLE `appointment_slots` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `schedule` varchar(255) NOT NULL,
  `doctor_id` varchar(255) NOT NULL,
  `date` datetime(6) NOT NULL DEFAULT current_timestamp(6),
  `slot` varchar(255) NOT NULL,
  `is_archive` tinyint(4) NOT NULL DEFAULT 0,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=8 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

INSERT INTO `appointment_slots` (id, schedule, doctor_id, date, slot, is_archive) VALUES ('1', 'Morning', 'TRLB7292', '2024-11-29 00:00:00.000000', '17', '0');
INSERT INTO `appointment_slots` (id, schedule, doctor_id, date, slot, is_archive) VALUES ('2', 'Afternoon', 'TRLB7292', '2024-11-25 00:00:00.000000', '75', '0');
INSERT INTO `appointment_slots` (id, schedule, doctor_id, date, slot, is_archive) VALUES ('3', 'Morning', 'TRLB7292', '2024-11-16 00:00:00.000000', '0', '0');
INSERT INTO `appointment_slots` (id, schedule, doctor_id, date, slot, is_archive) VALUES ('4', 'Morning', 'TRLB7292', '2024-12-09 00:00:00.000000', '4', '0');
INSERT INTO `appointment_slots` (id, schedule, doctor_id, date, slot, is_archive) VALUES ('5', 'Afternoon', 'TRLB6979', '2024-12-10 00:00:00.000000', '0', '0');
INSERT INTO `appointment_slots` (id, schedule, doctor_id, date, slot, is_archive) VALUES ('6', 'Morning', 'TRLB6874', '2024-12-02 00:00:00.000000', '5', '0');
INSERT INTO `appointment_slots` (id, schedule, doctor_id, date, slot, is_archive) VALUES ('7', 'Morning', 'TRLB6874', '2024-12-09 00:00:00.000000', '5', '0');

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
INSERT INTO `doctor` (employee_id, firstname, lastname, username, email, password, profile_img, department_id, date_added, is_archive) VALUES ('TRLB7292', 'Erwin', 'Petil', 'erwin123', 'erwin@gmail.com', '$2y$10$dixFjSuNzEx1Z6XrCwqQh.w60ecMNqqIq3AHDCIU4qwSToctXbro2', NULL, '2', '2024-05-05 10:53:34', '0');

DROP TABLE IF EXISTS `logs`;
CREATE TABLE `logs` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `action` varchar(255) NOT NULL,
  `user` int(11) NOT NULL,
  `timestamp` timestamp NOT NULL DEFAULT current_timestamp(),
  `details` text DEFAULT NULL,
  `is_archive` tinyint(1) DEFAULT 0,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=59 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

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
INSERT INTO `logs` (id, action, user, timestamp, details, is_archive) VALUES ('33', 'add appointment', '27', '2024-11-28 23:25:29', 'Appointment booked with ID: 62 for patient ID: 27', '0');
INSERT INTO `logs` (id, action, user, timestamp, details, is_archive) VALUES ('34', 'update appointment', '0', '2024-11-29 13:16:27', 'Updated appointment with ID: 57 for patient ID: 10', '0');
INSERT INTO `logs` (id, action, user, timestamp, details, is_archive) VALUES ('35', 'add appointment', '10', '2024-11-29 22:10:18', 'Appointment booked with ID: 63 for patient ID: 10', '0');
INSERT INTO `logs` (id, action, user, timestamp, details, is_archive) VALUES ('36', 'add appointment', '10', '2024-11-29 23:37:10', 'Appointment booked with ID: 64 for patient ID: 10', '0');
INSERT INTO `logs` (id, action, user, timestamp, details, is_archive) VALUES ('37', 'add appointment error', '10', '2024-11-29 23:45:53', 'Error booking appointment for patient ID: 10 - SQLSTATE[42S22]: Column not found: 1054 Unknown column \'s.department\' in \'field list\'', '0');
INSERT INTO `logs` (id, action, user, timestamp, details, is_archive) VALUES ('38', 'add appointment error', '10', '2024-11-29 23:50:14', 'Error booking appointment for patient ID: 10 - SQLSTATE[42S22]: Column not found: 1054 Unknown column \'s.department\' in \'field list\'', '0');
INSERT INTO `logs` (id, action, user, timestamp, details, is_archive) VALUES ('39', 'add appointment', '10', '2024-11-29 23:52:25', 'Appointment booked with ID: 65 for patient ID: 10', '0');
INSERT INTO `logs` (id, action, user, timestamp, details, is_archive) VALUES ('40', 'add appointment', '10', '2024-11-30 15:50:03', 'Appointment booked with ID: 66 for patient ID: 10', '0');
INSERT INTO `logs` (id, action, user, timestamp, details, is_archive) VALUES ('41', 'Add payment method error', '0', '2024-11-30 17:54:53', 'Image file size too large for payment method: abusasaff', '0');
INSERT INTO `logs` (id, action, user, timestamp, details, is_archive) VALUES ('42', 'add appointment', '10', '2024-12-01 21:49:19', 'Appointment booked with ID: 67 for patient ID: 10', '0');
INSERT INTO `logs` (id, action, user, timestamp, details, is_archive) VALUES ('43', 'add appointment', '10', '2024-12-01 21:55:16', 'Appointment booked with ID: 68 for patient ID: 10', '0');
INSERT INTO `logs` (id, action, user, timestamp, details, is_archive) VALUES ('44', 'add appointment error', '10', '2024-12-01 22:15:23', 'Error booking appointment for patient ID: 10 - SQLSTATE[23000]: Integrity constraint violation: 1048 Column \'appointment_slot_id\' cannot be null', '0');
INSERT INTO `logs` (id, action, user, timestamp, details, is_archive) VALUES ('45', 'add appointment', '10', '2024-12-01 22:16:30', 'Appointment booked with ID: 69 for patient ID: 10', '0');
INSERT INTO `logs` (id, action, user, timestamp, details, is_archive) VALUES ('46', 'Upload payment method', '0', '2024-12-02 12:08:44', 'Payment method updated with ID: 1', '0');
INSERT INTO `logs` (id, action, user, timestamp, details, is_archive) VALUES ('47', 'add appointment', '10', '2024-12-02 12:09:28', 'Appointment booked with ID: 70 for patient ID: 10', '0');
INSERT INTO `logs` (id, action, user, timestamp, details, is_archive) VALUES ('48', 'add appointment', '10', '2024-12-02 12:09:54', 'Appointment booked with ID: 71 for patient ID: 10', '0');
INSERT INTO `logs` (id, action, user, timestamp, details, is_archive) VALUES ('49', 'add appointment', '10', '2024-12-02 12:13:19', 'Appointment booked with ID: 72 for patient ID: 10', '0');
INSERT INTO `logs` (id, action, user, timestamp, details, is_archive) VALUES ('50', 'add appointment', '10', '2024-12-02 13:37:18', 'Appointment booked with ID: 73 for patient ID: 10', '0');
INSERT INTO `logs` (id, action, user, timestamp, details, is_archive) VALUES ('51', 'Submit medical record', '0', '2024-12-02 13:59:20', 'Medical record added for patient ID: 27', '0');
INSERT INTO `logs` (id, action, user, timestamp, details, is_archive) VALUES ('52', 'add appointment', '10', '2024-12-02 14:27:21', 'Appointment booked with ID: 74 for patient ID: 10', '0');
INSERT INTO `logs` (id, action, user, timestamp, details, is_archive) VALUES ('53', 'Upload payment method', '0', '2024-12-02 14:38:59', 'Payment method updated with ID: 1', '0');
INSERT INTO `logs` (id, action, user, timestamp, details, is_archive) VALUES ('54', 'add appointment error', '10', '2024-12-02 18:12:54', 'Error booking appointment for patient ID: 10 - SQLSTATE[23000]: Integrity constraint violation: 1048 Column \'appointment_slot_id\' cannot be null', '0');
INSERT INTO `logs` (id, action, user, timestamp, details, is_archive) VALUES ('55', 'add appointment error', '10', '2024-12-02 18:31:41', 'Error booking appointment for patient ID: 10 - SQLSTATE[23000]: Integrity constraint violation: 1048 Column \'appointment_slot_id\' cannot be null', '0');
INSERT INTO `logs` (id, action, user, timestamp, details, is_archive) VALUES ('56', 'add appointment', '10', '2024-12-02 21:35:29', 'Appointment booked with ID: 75 for patient ID: 10', '0');
INSERT INTO `logs` (id, action, user, timestamp, details, is_archive) VALUES ('57', 'add appointment', '29', '2024-12-02 23:35:35', 'Appointment booked with ID: 76 for patient ID: 29', '0');
INSERT INTO `logs` (id, action, user, timestamp, details, is_archive) VALUES ('58', 'add appointment error', '10', '2024-12-05 19:24:56', 'Error booking appointment for patient ID: 10 - SQLSTATE[23000]: Integrity constraint violation: 1048 Column \'appointment_slot_id\' cannot be null', '0');

DROP TABLE IF EXISTS `medical_records`;
CREATE TABLE `medical_records` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `patient_id` int(11) NOT NULL,
  `appointment_id` int(255) NOT NULL,
  `doctor_id` varchar(255) NOT NULL,
  `diagnosis` text DEFAULT NULL,
  `treatment` text DEFAULT NULL,
  `prescription` varchar(255) DEFAULT NULL,
  `content` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_bin DEFAULT NULL,
  `status` varchar(255) NOT NULL DEFAULT 'Pending',
  `record_date` date NOT NULL DEFAULT current_timestamp(),
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  PRIMARY KEY (`id`),
  KEY `fk_patient_id` (`patient_id`),
  CONSTRAINT `fk_patient_id` FOREIGN KEY (`patient_id`) REFERENCES `patient` (`id`) ON DELETE CASCADE,
  CONSTRAINT `medical_records_ibfk_1` FOREIGN KEY (`patient_id`) REFERENCES `patient` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=74 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

INSERT INTO `medical_records` (id, patient_id, appointment_id, doctor_id, diagnosis, treatment, prescription, content, status, record_date, created_at, updated_at) VALUES ('73', '10', '114', 'TRLB7292', NULL, NULL, NULL, '{\"date\":\"2024-12-15\",\"x_ray_number\":\"\",\"patient_name\":\"ROMERO, MHERWEN WIEL\",\"age_sex\":\"24\\/Male\",\"address\":\"City of Tagaytay\",\"request_by\":\"OP\",\"examination\":\"CHEST PA\",\"findings\":\"WALA PROBLEMA\",\"impression\":\"OKAY LANG NAMAN\",\"doctor_name\":\"ERWIN PETIL\",\"radiologist_signature\":\"ERWIN PETIL\",\"radiologist_position\":\"Radiologist\",\"specialization\":\"Medical Technologist\",\"doctor_title\":\"MD\",\"header\":\"ROENTGENOLOGICAL\"}', 'Completed', '2024-12-15', '2024-12-15 14:47:30', '2024-12-15 14:49:48');

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
) ENGINE=InnoDB AUTO_INCREMENT=30 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

INSERT INTO `patient` (id, firstname, lastname, age, sex, email, dob, birthplace, contact, password, province, city, barangay, street, date_added) VALUES ('9', 'Kyle Andre', 'Lim', '24', 'Male', 'kylemastercoder14@gmail.com', '2000-01-14', 'Cavite', '9152479691', '$2y$10$3PCQfGLAMDsHHnCjAH2np.2rBEr3XF.h6TlmZ9fdc4BtqyLoOioCq', 'Cavite', 'City of Dasmariñas', 'Santa Lucia', 'B111 L4 Ruby Street', '2024-05-04 21:34:34');
INSERT INTO `patient` (id, firstname, lastname, age, sex, email, dob, birthplace, contact, password, province, city, barangay, street, date_added) VALUES ('10', 'Mherwen Wiel', 'Romero', '24', 'Male', 'romeroqmherwen@gmail.com', '2024-11-16', 'Dasma', '09553471926', '$2y$10$Kd4wh.OShR1hgpORWodxVu/HDR3MVn.kbzPbaErn4R.pXXaAetj3K', 'Cavite', 'City of Tagaytay', 'Tolentino West', 'BLK D 8 LOT 16 SAN LUIS 1 DASMARIÑAS CAV', '2024-11-15 19:33:41');
INSERT INTO `patient` (id, firstname, lastname, age, sex, email, dob, birthplace, contact, password, province, city, barangay, street, date_added) VALUES ('25', 'Lindaxxx', 'Romero', '70', 'Female', 'Linda@gmail.com', '2024-11-17', NULL, '0955237123', '', 'cavite', 'dasmarinas', 'SABUTAN', 'blk d8 lot 15', '2024-11-17 10:55:36');
INSERT INTO `patient` (id, firstname, lastname, age, sex, email, dob, birthplace, contact, password, province, city, barangay, street, date_added) VALUES ('26', 'Marlyn', 'Leano', '32', 'Female', 'marlyn@gmail.com', '2024-11-26', NULL, '09123823123', '$2y$10$xZpeDN7g64ZGcDFwcf8nauDdRzS06q5XUJCS1ldllNgpoqhJ7ZWl.', 'CAVITE', 'City of DASMARIÑAS', 'San Luis 1', 'BLK D 8 LOT 16 SAN LUIS 1 DASMARIÑAS CAV', '2024-11-26 13:39:30');
INSERT INTO `patient` (id, firstname, lastname, age, sex, email, dob, birthplace, contact, password, province, city, barangay, street, date_added) VALUES ('27', 'Kian ', 'Torzar', '23', 'Male', 'kian@gmail.com', '2024-11-28', 'CITY OF DASMARIÑAS', '9152479692', '$2y$10$lSmZEqqQAz3UgZLpnQKOzu7rvCmQ7gM13Xr0dkfjX6uM6mXuqSJ8u', 'CAVITE', 'CITY OF DASMARIÑAS', 'SAN LUIS 1', 'BLK D 8 LOT 16 SAN LUIS 1 DASMARIÑAS CAV', '2024-11-28 23:24:06');
INSERT INTO `patient` (id, firstname, lastname, age, sex, email, dob, birthplace, contact, password, province, city, barangay, street, date_added) VALUES ('28', 'Gerry', 'Guevarra', '43', 'Male', 'gerryg@gmail.com', '1981-01-06', 'CITY OF DASMARIÑAS', '9152479692', '$2y$10$RPhshDc4xChI8l1Ozpk3Oeij4/i64r5ZpoSiy8j4DBqoLHAQBmKOy', 'CAVITE', 'CITY OF DASMARIñAS', 'BUROL', '', '2024-12-02 20:26:04');
INSERT INTO `patient` (id, firstname, lastname, age, sex, email, dob, birthplace, contact, password, province, city, barangay, street, date_added) VALUES ('29', 'Jason', 'Posadas', '24', 'Male', 'jason@gmail.com', '1997-02-06', 'CITY OF DASMARIÑAS', '9152479692', '$2y$10$Y9fGwByD/gcQhFNOew55PeoU0fNC.ohpZNca3mgcjEg/2NadM.E0i', 'CAVITE', 'CITY OF DASMARIñAS', 'PALIPARAN I', 'BLK D 8 LOT 16 SAN LUIS 1 DASMARIÑAS CAV', '2024-12-02 23:34:33');

DROP TABLE IF EXISTS `patient_files`;
CREATE TABLE `patient_files` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `patient_id` int(11) NOT NULL,
  `directory` varchar(255) NOT NULL,
  `file_name` varchar(255) NOT NULL,
  `uploaded_at` datetime DEFAULT current_timestamp(),
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=40 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

INSERT INTO `patient_files` (id, patient_id, directory, file_name, uploaded_at) VALUES ('1', '10', 'uploads\\user_10', 'Gaming_5000x3125.jpg', '2024-11-30 00:51:57');
INSERT INTO `patient_files` (id, patient_id, directory, file_name, uploaded_at) VALUES ('2', '10', 'uploads\\user_10', 'plannn.pdf', '2024-11-30 00:52:22');
INSERT INTO `patient_files` (id, patient_id, directory, file_name, uploaded_at) VALUES ('3', '10', 'uploads\\user_10', 'plannn.pdf', '2024-11-30 00:55:27');
INSERT INTO `patient_files` (id, patient_id, directory, file_name, uploaded_at) VALUES ('4', '10', 'uploads\\user_10', 'plannn.pdf', '2024-11-30 00:56:01');
INSERT INTO `patient_files` (id, patient_id, directory, file_name, uploaded_at) VALUES ('5', '10', 'uploads\\user_10', 'plannn.pdf', '2024-11-30 00:56:15');
INSERT INTO `patient_files` (id, patient_id, directory, file_name, uploaded_at) VALUES ('6', '9', 'uploads\\user_9', 'Screenshot 2024-03-18 220354.png', '2024-11-30 00:57:44');
INSERT INTO `patient_files` (id, patient_id, directory, file_name, uploaded_at) VALUES ('7', '9', 'uploads\\user_9', 'Screenshot 2024-03-18 220354.png', '2024-11-30 00:57:48');
INSERT INTO `patient_files` (id, patient_id, directory, file_name, uploaded_at) VALUES ('8', '10', 'uploads\\user_10', 'plannn.pdf', '2024-11-30 01:00:14');
INSERT INTO `patient_files` (id, patient_id, directory, file_name, uploaded_at) VALUES ('9', '9', 'uploads\\user_9', 'Screenshot 2024-03-18 220354.png', '2024-11-30 01:22:13');
INSERT INTO `patient_files` (id, patient_id, directory, file_name, uploaded_at) VALUES ('10', '9', 'uploads\\user_9', 'Screenshot 2024-03-18 230310.png', '2024-11-30 01:22:13');
INSERT INTO `patient_files` (id, patient_id, directory, file_name, uploaded_at) VALUES ('11', '9', 'uploads\\user_9', 'Screenshot 2024-03-18 230310.png', '2024-11-30 01:27:30');
INSERT INTO `patient_files` (id, patient_id, directory, file_name, uploaded_at) VALUES ('12', '9', 'uploads\\user_9', 'Screenshot 2024-03-25 171439.png', '2024-11-30 01:27:30');
INSERT INTO `patient_files` (id, patient_id, directory, file_name, uploaded_at) VALUES ('13', '9', 'uploads\\user_9', 'Screenshot 2024-03-28 135809.png', '2024-11-30 01:27:30');
INSERT INTO `patient_files` (id, patient_id, directory, file_name, uploaded_at) VALUES ('14', '9', 'uploads\\user_9', 'Screenshot 2024-03-18 230310.png', '2024-11-30 01:29:48');
INSERT INTO `patient_files` (id, patient_id, directory, file_name, uploaded_at) VALUES ('15', '9', 'uploads\\user_9', 'Screenshot 2024-03-25 171439.png', '2024-11-30 01:29:49');
INSERT INTO `patient_files` (id, patient_id, directory, file_name, uploaded_at) VALUES ('16', '9', 'uploads\\user_9', 'Screenshot 2024-03-28 135809.png', '2024-11-30 01:29:49');
INSERT INTO `patient_files` (id, patient_id, directory, file_name, uploaded_at) VALUES ('17', '10', 'uploads\\user_10', 'Gaming_5000x3125.jpg', '2024-11-30 01:47:28');
INSERT INTO `patient_files` (id, patient_id, directory, file_name, uploaded_at) VALUES ('18', '10', 'uploads\\user_10', 'Doc1.docx', '2024-11-30 01:51:43');
INSERT INTO `patient_files` (id, patient_id, directory, file_name, uploaded_at) VALUES ('19', '27', 'uploads\\user_27', 'Doc1.docx', '2024-11-30 01:56:49');
INSERT INTO `patient_files` (id, patient_id, directory, file_name, uploaded_at) VALUES ('20', '27', 'uploads\\user_27', 'Romero.docx', '2024-11-30 01:56:49');
INSERT INTO `patient_files` (id, patient_id, directory, file_name, uploaded_at) VALUES ('21', '10', 'uploads\\user_10', 'Romero_assessment1.pdf', '2024-11-30 02:09:20');
INSERT INTO `patient_files` (id, patient_id, directory, file_name, uploaded_at) VALUES ('22', '25', 'uploads\\user_25', 'Doc1.docx', '2024-11-30 02:16:52');
INSERT INTO `patient_files` (id, patient_id, directory, file_name, uploaded_at) VALUES ('23', '25', 'uploads\\user_25', 'Romero.docx', '2024-11-30 02:16:52');
INSERT INTO `patient_files` (id, patient_id, directory, file_name, uploaded_at) VALUES ('24', '10', 'uploads\\user_10', 'Gaming_5000x3125.jpg', '2024-11-30 15:51:06');
INSERT INTO `patient_files` (id, patient_id, directory, file_name, uploaded_at) VALUES ('25', '10', 'uploads\\user_10', 'romero_pelvic_x-ray_2024-12-01.jpg', '2024-12-01 12:39:49');
INSERT INTO `patient_files` (id, patient_id, directory, file_name, uploaded_at) VALUES ('26', '10', 'uploads\\user_10', 'romero_x-ray_spot film_2024-12-01.docx', '2024-12-01 12:52:25');
INSERT INTO `patient_files` (id, patient_id, directory, file_name, uploaded_at) VALUES ('27', '10', 'uploads\\user_10', 'romero_x-ray_spot film_2024-12-01.docx', '2024-12-01 12:52:25');
INSERT INTO `patient_files` (id, patient_id, directory, file_name, uploaded_at) VALUES ('28', '10', 'uploads\\user_10', 'romero_x-ray_t-cage_2024-12-01.docx', '2024-12-01 12:53:29');
INSERT INTO `patient_files` (id, patient_id, directory, file_name, uploaded_at) VALUES ('29', '10', 'uploads\\user_10', 'romero_x-ray_t-cage_2024-12-01.docx', '2024-12-01 12:53:29');
INSERT INTO `patient_files` (id, patient_id, directory, file_name, uploaded_at) VALUES ('30', '10', 'uploads\\user_10', 'romero_spot film_x-ray_2024-12-01.docx', '2024-12-01 12:55:05');
INSERT INTO `patient_files` (id, patient_id, directory, file_name, uploaded_at) VALUES ('31', '10', 'uploads\\user_10', 'romero_spot film_x-ray_2024-12-01.docx', '2024-12-01 12:55:05');
INSERT INTO `patient_files` (id, patient_id, directory, file_name, uploaded_at) VALUES ('32', '10', 'uploads\\user_10', 'romero_spot film_x-ray_2024-12-01.docx', '2024-12-01 12:55:53');
INSERT INTO `patient_files` (id, patient_id, directory, file_name, uploaded_at) VALUES ('33', '10', 'uploads\\user_10', 'romero_spot film_x-ray_2024-12-01.docx', '2024-12-01 12:55:53');
INSERT INTO `patient_files` (id, patient_id, directory, file_name, uploaded_at) VALUES ('34', '10', 'uploads\\user_10', 'romero_spot film_x-ray_2025-01-03.docx', '2024-12-01 12:57:43');
INSERT INTO `patient_files` (id, patient_id, directory, file_name, uploaded_at) VALUES ('35', '10', 'uploads\\user_10', 'romero_spot film_x-ray_2025-01-03_1.docx', '2024-12-01 12:57:43');
INSERT INTO `patient_files` (id, patient_id, directory, file_name, uploaded_at) VALUES ('36', '10', 'uploads\\user_10', 'romero_spot film_x-ray_2024-12-01.docx', '2024-12-01 12:58:11');
INSERT INTO `patient_files` (id, patient_id, directory, file_name, uploaded_at) VALUES ('37', '10', 'uploads\\user_10', 'romero_spot film_x-ray_2024-12-01_1.docx', '2024-12-01 12:58:11');
INSERT INTO `patient_files` (id, patient_id, directory, file_name, uploaded_at) VALUES ('38', '27', 'uploads\\user_27', 'torzar_spot film_x-ray_2024-12-02.png', '2024-12-02 14:38:06');
INSERT INTO `patient_files` (id, patient_id, directory, file_name, uploaded_at) VALUES ('39', '27', 'uploads\\user_27', 'torzar_spot film_x-ray_2024-12-02_1.png', '2024-12-02 14:38:06');

DROP TABLE IF EXISTS `payment_mode`;
CREATE TABLE `payment_mode` (
  `id` int(255) NOT NULL AUTO_INCREMENT,
  `method` varchar(255) NOT NULL,
  `image_path` varchar(255) NOT NULL,
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=8 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

INSERT INTO `payment_mode` (id, method, image_path, updated_at) VALUES ('1', 'Gcash', 'uploads/payment_methods/Adobe_Photoshop_CC_icon.svg.png', '2024-12-02 14:38:59');
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
) ENGINE=InnoDB AUTO_INCREMENT=24 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;


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
) ENGINE=InnoDB AUTO_INCREMENT=7 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

INSERT INTO `services` (id, category, type, service, department_id, cost, date_added, is_archive) VALUES ('1', 'Imaging Services', 'X-Ray', 'Spot Film', '2', '300.50', '2024-05-03 15:44:15', '0');
INSERT INTO `services` (id, category, type, service, department_id, cost, date_added, is_archive) VALUES ('2', 'Imaging Services', 'X-Ray', 'T-Cage', '2', '250.80', '2024-05-03 15:44:54', '0');
INSERT INTO `services` (id, category, type, service, department_id, cost, date_added, is_archive) VALUES ('3', 'Laboratory Services', 'CBC', 'Red Blood Cell', '1', '300', '2024-05-03 15:53:00', '0');
INSERT INTO `services` (id, category, type, service, department_id, cost, date_added, is_archive) VALUES ('4', 'Imaging Services', 'CT SCAN', 'PELVIC', '2', '100', '2024-11-16 11:17:58', '0');
INSERT INTO `services` (id, category, type, service, department_id, cost, date_added, is_archive) VALUES ('5', 'Laboratory Services', 'qwe', 'qwe', '1', '900', '2024-12-02 23:31:41', '0');
INSERT INTO `services` (id, category, type, service, department_id, cost, date_added, is_archive) VALUES ('6', 'General Services', 'ewq', 'ewq', '1', '145', '2024-12-02 23:31:59', '0');

