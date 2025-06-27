-- Users table: Admins, Headteachers, Babysitters, Parents
CREATE TABLE `users` (
  `user_id` INT NOT NULL AUTO_INCREMENT,
  `first_name` VARCHAR(50) NOT NULL,
  `last_name` VARCHAR(50) NOT NULL,
  `email` VARCHAR(100) NOT NULL,
  `phone` VARCHAR(20),
  `password` VARCHAR(255) NOT NULL,
  `role` ENUM('admin','headteacher','babysitter','parent') NOT NULL,
  `status` ENUM('active','pending','inactive') DEFAULT 'pending',
  `created_at` TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`user_id`)
);

-- Children table
CREATE TABLE `children` (
  `child_id` INT NOT NULL AUTO_INCREMENT,
  `parent_id` INT NOT NULL,
  `first_name` VARCHAR(50) NOT NULL,
  `last_name` VARCHAR(50) NOT NULL,
  `date_of_birth` DATE NOT NULL,
  `gender` ENUM('male','female','other'),
  `medical_notes` TEXT,
  `allergies` TEXT,
  `enrollment_date` DATE,
  `teacher_id` INT,
  PRIMARY KEY (`child_id`),
  FOREIGN KEY (`parent_id`) REFERENCES `users`(`user_id`),
  FOREIGN KEY (`teacher_id`) REFERENCES `users`(`user_id`)
);

-- Daily reports
CREATE TABLE `daily_reports` (
  `report_id` INT NOT NULL AUTO_INCREMENT,
  `child_id` INT NOT NULL,
  `babysitter_id` INT NOT NULL,
  `report_date` DATE NOT NULL,
  `meals` TEXT,
  `nap_duration` VARCHAR(20),
  `sleep_quality` INT CHECK (`sleep_quality` BETWEEN 1 AND 5),
  `activities` TEXT,
  `mood` ENUM('happy','playful','calm','sleepy','sad'),
  `health_notes` TEXT,
  `created_at` TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`report_id`),
  FOREIGN KEY (`child_id`) REFERENCES `children`(`child_id`),
  FOREIGN KEY (`babysitter_id`) REFERENCES `users`(`user_id`)
);

-- Certifications for babysitters
CREATE TABLE `certifications` (
  `certification_id` INT NOT NULL AUTO_INCREMENT,
  `babysitter_id` INT NOT NULL,
  `name` VARCHAR(100) NOT NULL,
  `issuing_organization` VARCHAR(100) NOT NULL,
  `issue_date` DATE NOT NULL,
  `expiry_date` DATE,
  PRIMARY KEY (`certification_id`),
  FOREIGN KEY (`babysitter_id`) REFERENCES `users`(`user_id`)
);



-- Payments
CREATE TABLE `payments` (
  `payment_id` INT NOT NULL AUTO_INCREMENT,
  `parent_id` INT NOT NULL,
  `child_id` INT NOT NULL,
  `amount` DECIMAL(10,2) NOT NULL,
  `due_date` DATE NOT NULL,
  `status` ENUM('pending','paid','overdue') DEFAULT 'pending',
  `payment_date` DATE,
  `payment_method` VARCHAR(50),
  `invoice_number` VARCHAR(20),
  `created_at` TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`payment_id`),
  FOREIGN KEY (`parent_id`) REFERENCES `users`(`user_id`),
  FOREIGN KEY (`child_id`) REFERENCES `children`(`child_id`)
);
