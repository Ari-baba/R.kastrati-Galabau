-- SQL schema for Rezervim Rregullimit e Oborreve
-- Creates database, tables, constraints and indexes

-- 1) Create database
CREATE DATABASE IF NOT EXISTS `rezervim` CHARACTER SET = 'utf8mb4' COLLATE = 'utf8mb4_unicode_ci';
USE `rezervim`;

-- 2) Table: admins
CREATE TABLE IF NOT EXISTS `admins` (
	`id` INT UNSIGNED NOT NULL AUTO_INCREMENT,
	`username` VARCHAR(100) NOT NULL UNIQUE,
	`password` VARCHAR(255) NOT NULL,
	`created_at` TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
	PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- 3) Table: reservations
CREATE TABLE IF NOT EXISTS `reservations` (
	`id` INT UNSIGNED NOT NULL AUTO_INCREMENT,
	`first_name` VARCHAR(100) NOT NULL,
	`last_name` VARCHAR(100) NOT NULL,
	`phone` VARCHAR(30) NOT NULL,
	`email` VARCHAR(255) DEFAULT NULL,
	`location` VARCHAR(255) NOT NULL,
	`message` TEXT DEFAULT NULL,
	`created_at` TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
	PRIMARY KEY (`id`),
	INDEX `idx_reservations_phone` (`phone`),
	INDEX `idx_reservations_created_at` (`created_at`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- 4) Table: about
CREATE TABLE IF NOT EXISTS `about` (
	`id` INT UNSIGNED NOT NULL AUTO_INCREMENT,
	`title` VARCHAR(255) NOT NULL,
	`description` TEXT NOT NULL,
	`image` VARCHAR(255) DEFAULT NULL,
	PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- 5) Table: gallery
CREATE TABLE IF NOT EXISTS `gallery` (
	`id` INT UNSIGNED NOT NULL AUTO_INCREMENT,
	`image` VARCHAR(255) NOT NULL,
	`uploaded_at` TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
	PRIMARY KEY (`id`),
	INDEX `idx_gallery_uploaded_at` (`uploaded_at`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- 6) Table: homepage
CREATE TABLE IF NOT EXISTS `homepage` (
	`id` INT UNSIGNED NOT NULL AUTO_INCREMENT,
	`hero_title` VARCHAR(255) NOT NULL,
	`hero_description` TEXT NOT NULL,
	`hero_image` VARCHAR(255) DEFAULT NULL,
	PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- 7) Notes: Ensure uploads folder is protected from code execution on the webserver.

-- 8) Create application DB user (run these statements as a MySQL root/admin user)
CREATE USER IF NOT EXISTS 'mysql80'@'localhost' IDENTIFIED BY '12345678';
GRANT ALL PRIVILEGES ON `rezervim`.* TO 'mysql80'@'localhost';
FLUSH PRIVILEGES;

