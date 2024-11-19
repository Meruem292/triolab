DROP TABLE IF EXISTS `admin`;
CREATE TABLE `admin` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `username` varchar(255) NOT NULL,
  `password` varchar(255) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

INSERT INTO `admin` (id,username,password) VALUES ('1','trioadmin','1');

DROP TABLE IF EXISTS `appointment`;
CREATE TABLE `appointment` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `service_id` int(11) NOT NULL,
  `patient_id` int(11) NOT NULL,
  `appointment_date` varchar(255) NOT NULL,
  `appointment_slot_id` int(255) NOT NULL,
  `appointment_time` varchar(255) NOT NULL,
  `doctor_id` varchar(255) DEFAULT NULL,
  `department_id` int(255) NOT NULL,
  `selectedPayment` varchar(255) NOT NULL,
  `medical` text DEFAULT NULL,
  `status` varchar(255) NOT NULL DEFAULT 'Pending',
  `paid` varchar(255) NOT NULL DEFAULT 'Unpaid',
  `date_added` timestamp NOT NULL DEFAULT current_timestamp(),
  `is_archive` tinyint(4) NOT NULL DEFAULT 0,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=17 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

INSERT INTO `appointment` (id,service_id,patient_id,appointment_date,appointment_slot_id,appointment_time,doctor_id,department_id,selectedPayment,medical,status,paid,date_added,is_archive) VALUES ('12','1','10','2024-11-16','3','16:30','','0','3','fracture','pending','0','2024-11-17 19:32:25','0');
INSERT INTO `appointment` (id,service_id,patient_id,appointment_date,appointment_slot_id,appointment_time,doctor_id,department_id,selectedPayment,medical,status,paid,date_added,is_archive) VALUES ('13','1','10','2024-11-09','4','09:42','','0','2','fracture','pending','0','2024-11-18 10:03:20','0');
INSERT INTO `appointment` (id,service_id,patient_id,appointment_date,appointment_slot_id,appointment_time,doctor_id,department_id,selectedPayment,medical,status,paid,date_added,is_archive) VALUES ('14','1','10','2024-11-16','3','11:17','','0','2','fracture','pending','0','2024-11-18 11:13:38','0');
INSERT INTO `appointment` (id,service_id,patient_id,appointment_date,appointment_slot_id,appointment_time,doctor_id,department_id,selectedPayment,medical,status,paid,date_added,is_archive) VALUES ('15','1','10','2024-11-09','4','11:43','','0','2','fracture','pending','0','2024-11-18 11:42:06','0');
INSERT INTO `appointment` (id,service_id,patient_id,appointment_date,appointment_slot_id,appointment_time,doctor_id,department_id,selectedPayment,medical,status,paid,date_added,is_archive) VALUES ('16','1','10','2024-11-16','3','11:56','','0','3','fracture','pending','0','2024-11-18 11:52:55','0');

DROP TABLE IF EXISTS `appointment_slots`;
CREATE TABLE `appointment_slots` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `schedule` varchar(255) NOT NULL,
  `date` varchar(255) NOT NULL,
  `slot` varchar(255) NOT NULL,
  `is_archive` tinyint(4) NOT NULL DEFAULT 0,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=5 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

INSERT INTO `appointment_slots` (id,schedule,date,slot,is_archive) VALUES ('1','Morning','2024-05-04','20','0');
INSERT INTO `appointment_slots` (id,schedule,date,slot,is_archive) VALUES ('2','Afternoon','2024-05-04','24','0');
INSERT INTO `appointment_slots` (id,schedule,date,slot,is_archive) VALUES ('3','Morning','2024-11-16','1','0');
INSERT INTO `appointment_slots` (id,schedule,date,slot,is_archive) VALUES ('4','Morning','2024-11-09','3','0');

DROP TABLE IF EXISTS `departments`;
CREATE TABLE `departments` (
  `id` int(255) NOT NULL AUTO_INCREMENT,
  `name` varchar(255) NOT NULL,
  `is_archive` tinyint(4) NOT NULL DEFAULT 0,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=5 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

INSERT INTO `departments` (id,name,is_archive) VALUES ('1','Physicist','0');
INSERT INTO `departments` (id,name,is_archive) VALUES ('2','RADIOLOGIC TECHNOLOGIST','0');
INSERT INTO `departments` (id,name,is_archive) VALUES ('3','MEDICAL TECHNICIAN','0');
INSERT INTO `departments` (id,name,is_archive) VALUES ('4','MEDICAL CONSULTANT','0');

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

INSERT INTO `doctor` (employee_id,firstname,lastname,username,email,password,profile_img,department_id,date_added,is_archive) VALUES ('TRLB1335','Ariel','Pornelosa','ariel','asmrsounds6437@gmail.com','ariel','','3','2024-11-16 10:19:31','0');
INSERT INTO `doctor` (employee_id,firstname,lastname,username,email,password,profile_img,department_id,date_added,is_archive) VALUES ('TRLB1690','Linda','Romero','Linda','linda@gmail.com','Linda','','1','2024-11-16 20:26:01','0');
INSERT INTO `doctor` (employee_id,firstname,lastname,username,email,password,profile_img,department_id,date_added,is_archive) VALUES ('TRLB2763','ely','buendia','ely','ely@gmail.com','ely','','4','2024-11-16 10:37:19','0');
INSERT INTO `doctor` (employee_id,firstname,lastname,username,email,password,profile_img,department_id,date_added,is_archive) VALUES ('TRLB5602','Angelo','Romero','angelo','angelo@gmail.com','angelo','','2','2024-11-16 20:21:49','0');
INSERT INTO `doctor` (employee_id,firstname,lastname,username,email,password,profile_img,department_id,date_added,is_archive) VALUES ('TRLB6482','Marlyn','Leano','Lyn','lyn@gmail.com','Lyn','','1','2024-11-16 20:57:11','0');
INSERT INTO `doctor` (employee_id,firstname,lastname,username,email,password,profile_img,department_id,date_added,is_archive) VALUES ('TRLB6874','Mherwen Wiel','Romero','mherwen123','mherwen123@gmail.com','$2y$10$ONkTVQwOinK2Q5XToWkwB.jhAfx1DR8dkoD/xQRqPDOiv6XOMyMQ.','','1','2024-05-05 09:35:17','0');
INSERT INTO `doctor` (employee_id,firstname,lastname,username,email,password,profile_img,department_id,date_added,is_archive) VALUES ('TRLB6908','qwe','qwe','qwe','qwe@gmail.com','qwe','','3','2024-11-16 20:27:52','0');
INSERT INTO `doctor` (employee_id,firstname,lastname,username,email,password,profile_img,department_id,date_added,is_archive) VALUES ('TRLB7292','Erwin','Petil','erwin123','erwin@gmail.com','$2y$10$dixFjSuNzEx1Z6XrCwqQh.w60ecMNqqIq3AHDCIU4qwSToctXbro2','','3','2024-05-05 10:53:34','0');

DROP TABLE IF EXISTS `logs`;
CREATE TABLE `logs` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `action` varchar(255) NOT NULL,
  `user` int(11) NOT NULL,
  `timestamp` timestamp NOT NULL DEFAULT current_timestamp(),
  `details` text DEFAULT NULL,
  `is_archive` tinyint(1) DEFAULT 0,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=9 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

INSERT INTO `logs` (id,action,user,timestamp,details,is_archive) VALUES ('1','Add Patient','1','2024-11-16 21:00:01','','0');
INSERT INTO `logs` (id,action,user,timestamp,details,is_archive) VALUES ('2','Add Patient','1','2024-11-16 21:01:07','','0');
INSERT INTO `logs` (id,action,user,timestamp,details,is_archive) VALUES ('3','upload payment method','0','2024-11-17 11:15:51','Payment method updated with ID: 1','0');
INSERT INTO `logs` (id,action,user,timestamp,details,is_archive) VALUES ('4','Add payment method error','0','2024-11-17 13:59:49','Failed to upload image for payment method: sample','0');
INSERT INTO `logs` (id,action,user,timestamp,details,is_archive) VALUES ('5','Add payment method error','0','2024-11-17 14:00:15','Failed to upload image for payment method: sample 2 ','0');
INSERT INTO `logs` (id,action,user,timestamp,details,is_archive) VALUES ('6','Add payment method error','0','2024-11-17 14:02:43','Payment method already exists: sample 2 ','0');
INSERT INTO `logs` (id,action,user,timestamp,details,is_archive) VALUES ('7','Add payment method error','0','2024-11-17 14:03:00','Failed to upload image for payment method: neww23','0');
INSERT INTO `logs` (id,action,user,timestamp,details,is_archive) VALUES ('8','Add payment method','0','2024-11-17 14:05:01','Payment method added: neww23','0');

DROP TABLE IF EXISTS `medical_records`;
CREATE TABLE `medical_records` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `patient_id` int(11) NOT NULL,
  `diagnosis` text NOT NULL,
  `treatment` text NOT NULL,
  `record_date` date NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  PRIMARY KEY (`id`),
  KEY `fk_patient_id` (`patient_id`),
  CONSTRAINT `fk_patient_id` FOREIGN KEY (`patient_id`) REFERENCES `patient` (`id`) ON DELETE CASCADE,
  CONSTRAINT `medical_records_ibfk_1` FOREIGN KEY (`patient_id`) REFERENCES `patient` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=4 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;


DROP TABLE IF EXISTS `patient`;
CREATE TABLE `patient` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
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
  `date_added` timestamp NOT NULL DEFAULT current_timestamp(),
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=26 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

INSERT INTO `patient` (id,firstname,lastname,email,dob,birthplace,contact,password,province,city,barangay,street,date_added) VALUES ('9','Kyle Andre','Lim','kylemastercoder14@gmail.com','2000-01-14','Cavite','9152479692','$2y$10$3PCQfGLAMDsHHnCjAH2np.2rBEr3XF.h6TlmZ9fdc4BtqyLoOioCq','Cavite','City of Dasmariñas','Santa Lucia','B111 L4 Ruby Street','2024-05-04 21:34:34');
INSERT INTO `patient` (id,firstname,lastname,email,dob,birthplace,contact,password,province,city,barangay,street,date_added) VALUES ('10','Mherwen Wiel','Romero','romeroqmherwen@gmail.com','2024-11-16','Dasma','09553471926','$2y$10$Kd4wh.OShR1hgpORWodxVu/HDR3MVn.kbzPbaErn4R.pXXaAetj3K','Cavite','Silang','','BLK D 8 LOT 16 SAN LUIS 1 DASMARIÑAS CAV','2024-11-15 19:33:41');
INSERT INTO `patient` (id,firstname,lastname,email,dob,birthplace,contact,password,province,city,barangay,street,date_added) VALUES ('25','Linda','Romero','Linda@gmail.com','2024-11-17','','0955237123','','cavite','dasmarinas','SABUTAN','blk d8 lot 15','2024-11-17 10:55:36');

DROP TABLE IF EXISTS `payment_mode`;
CREATE TABLE `payment_mode` (
  `id` int(255) NOT NULL AUTO_INCREMENT,
  `method` varchar(255) NOT NULL,
  `image_path` varchar(255) NOT NULL,
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=8 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

INSERT INTO `payment_mode` (id,method,image_path,updated_at) VALUES ('1','Gcash','uploads/payment_methods/gcash.jpg','2024-11-17 12:11:04');
INSERT INTO `payment_mode` (id,method,image_path,updated_at) VALUES ('2','Paymaya','uploads/payment_methods/maya.jpg','2024-11-17 12:11:14');
INSERT INTO `payment_mode` (id,method,image_path,updated_at) VALUES ('3','Cash','uploads/payment_methods/cash.jpg','2024-11-17 12:11:24');

DROP TABLE IF EXISTS `payment_receipts`;
CREATE TABLE `payment_receipts` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `appointment_id` int(11) DEFAULT NULL,
  `payment_receipt_path` varchar(255) DEFAULT NULL,
  `date` date DEFAULT NULL,
  `payment_mode_id` int(11) DEFAULT NULL,
  `amount` decimal(10,2) DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `appointment_id` (`appointment_id`),
  KEY `payment_mode_id` (`payment_mode_id`),
  CONSTRAINT `payment_receipts_ibfk_1` FOREIGN KEY (`appointment_id`) REFERENCES `appointment` (`id`) ON DELETE CASCADE,
  CONSTRAINT `payment_receipts_ibfk_2` FOREIGN KEY (`payment_mode_id`) REFERENCES `payment_mode` (`id`) ON DELETE SET NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;


DROP TABLE IF EXISTS `services`;
CREATE TABLE `services` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `category` varchar(255) NOT NULL,
  `type` varchar(255) NOT NULL,
  `service` varchar(255) NOT NULL,
  `department` varchar(255) NOT NULL,
  `cost` varchar(255) NOT NULL,
  `date_added` timestamp NOT NULL DEFAULT current_timestamp(),
  `is_archive` tinyint(4) NOT NULL DEFAULT 0,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=5 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

INSERT INTO `services` (id,category,type,service,department,cost,date_added,is_archive) VALUES ('1','Imaging Services','X-Ray','Spot Film','Radiological Technologist','300.50','2024-05-03 15:44:15','0');
INSERT INTO `services` (id,category,type,service,department,cost,date_added,is_archive) VALUES ('2','Imaging Services','X-Ray','T-Cage','Radiological Technologist','250.80','2024-05-03 15:44:54','0');
INSERT INTO `services` (id,category,type,service,department,cost,date_added,is_archive) VALUES ('3','Laboratory Services','CBC','Red Blood Cell','Radiological Technologist','200','2024-05-03 15:53:00','0');
INSERT INTO `services` (id,category,type,service,department,cost,date_added,is_archive) VALUES ('4','Imaging Services','CT SCAN','PELVIC','Radiological Technologist','100','2024-11-16 11:17:58','0');

