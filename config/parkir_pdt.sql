-- Database AmanParkir
-- Sistem Manajemen Tempat Parkir Motor FMIPA

-- Create Database
CREATE DATABASE IF NOT EXISTS amanparkir;
USE amanparkir;

-- Table Parking Areas (Zona Parkir)
CREATE TABLE IF NOT EXISTS parking_areas (
    id INT PRIMARY KEY AUTO_INCREMENT,
    area_code VARCHAR(50) UNIQUE NOT NULL,
    area_name VARCHAR(100) NOT NULL,
    location VARCHAR(255),
    total_capacity INT NOT NULL,
    motorcycle_capacity INT NOT NULL,
    car_capacity INT NOT NULL,
    current_motorcycle INT DEFAULT 0,
    current_car INT DEFAULT 0,
    status ENUM('active', 'inactive') DEFAULT 'active',
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
);

-- Table Vehicle Types
CREATE TABLE IF NOT EXISTS vehicle_types (
    id INT PRIMARY KEY AUTO_INCREMENT,
    type_name VARCHAR(50) NOT NULL,
    type_code VARCHAR(10) UNIQUE NOT NULL
);

-- Table Vehicles
CREATE TABLE IF NOT EXISTS vehicles (
    id INT PRIMARY KEY AUTO_INCREMENT,
    plate_number VARCHAR(20) UNIQUE NOT NULL,
    vehicle_type_id INT NOT NULL,
    owner_name VARCHAR(100),
    owner_phone VARCHAR(20),
    owner_email VARCHAR(100),
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (vehicle_type_id) REFERENCES vehicle_types(id)
);

-- Table Parking Transactions (Laporan Masuk/Keluar)
CREATE TABLE IF NOT EXISTS parking_transactions (
    id INT PRIMARY KEY AUTO_INCREMENT,
    vehicle_id INT NOT NULL,
    parking_area_id INT NOT NULL,
    entry_time TIMESTAMP NOT NULL,
    exit_time TIMESTAMP NULL,
    duration_minutes INT NULL,
    is_active BOOLEAN DEFAULT TRUE,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    FOREIGN KEY (vehicle_id) REFERENCES vehicles(id),
    FOREIGN KEY (parking_area_id) REFERENCES parking_areas(id),
    INDEX idx_plate (vehicle_id),
    INDEX idx_area (parking_area_id),
    INDEX idx_entry (entry_time),
    INDEX idx_active (is_active)
);

-- Table Activity Logs
CREATE TABLE IF NOT EXISTS activity_logs (
    id INT PRIMARY KEY AUTO_INCREMENT,
    action_type VARCHAR(50) NOT NULL,
    description TEXT,
    user_ip VARCHAR(45),
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

-- Users

CREATE TABLE IF NOT EXISTS users (
    id INT PRIMARY KEY AUTO_INCREMENT,
    username VARCHAR(50) UNIQUE NOT NULL,
    password VARCHAR(255) NOT NULL,
    role ENUM('admin','petugas') DEFAULT 'petugas',
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

-- Backup Logs

CREATE TABLE IF NOT EXISTS backup_logs (
    id INT PRIMARY KEY AUTO_INCREMENT,
    backup_file VARCHAR(255),
    backup_date TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

-- Daily Statistics
CREATE TABLE IF NOT EXISTS daily_statistics (
    id INT PRIMARY KEY AUTO_INCREMENT,
    stat_date DATE,
    total_vehicles INT,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

-- Active Transactions View
CREATE OR REPLACE VIEW active_transactions AS
SELECT *
FROM parking_transactions
WHERE is_active = TRUE;

-- Completed Transactions View
CREATE OR REPLACE VIEW completed_transactions AS
SELECT *
FROM parking_transactions
WHERE is_active = FALSE;

-- Insert Vehicle Types
INSERT INTO vehicle_types (type_name, type_code) VALUES 
('Motorcycle', 'MC');

-- Insert Sample Parking Areas
INSERT INTO parking_areas (area_code, area_name, location, total_capacity, motorcycle_capacity, car_capacity, status) VALUES 
('A', 'Area A', 'Gedung A', 200, 200, 0, 'active'),
('B', 'Area B', 'Gedung B', 150, 150, 0, 'active'),
('PASCA', 'Area PASCA', 'Gedung PASCA', 20, 20, 0, 'active');

-- Insert Admin User
INSERT INTO users (
    username,
    password,
    role
) VALUES (
    'admin',
    '$2y$10$6c1H4e1K9vM6j3Q2B6W1OepMULqB9jWQ6x5KJXvK0B4e4l2zL3fQK',
    'admin'
);

-- Vehicle Entry Trigger
DELIMITER $$

CREATE TRIGGER trg_vehicle_entry
AFTER INSERT ON parking_transactions
FOR EACH ROW
BEGIN

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

END$$

DELIMITER ;

-- Vehicle Exit Trigger
DELIMITER $$

CREATE TRIGGER trg_vehicle_exit
AFTER UPDATE ON parking_transactions
FOR EACH ROW
BEGIN

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

END$$

DELIMITER ;

-- Enable Event Scheduler
SET GLOBAL event_scheduler = ON;

-- Delete Old Logs Event
CREATE EVENT IF NOT EXISTS delete_old_logs
ON SCHEDULE EVERY 1 DAY
DO
DELETE FROM activity_logs
WHERE created_at < DATE_SUB(NOW(), INTERVAL 30 DAY);

-- Daily Vehicle Report Event

CREATE EVENT IF NOT EXISTS daily_vehicle_report
ON SCHEDULE EVERY 1 DAY
STARTS CURRENT_TIMESTAMP
DO
INSERT INTO daily_statistics (
    stat_date,
    total_vehicles
)
SELECT
    CURDATE(),
    COUNT(*)
FROM parking_transactions
WHERE DATE(entry_time) = CURDATE();

-- Motorcycle Transactions View
CREATE OR REPLACE VIEW motorcycle_transactions AS
SELECT pt.*
FROM parking_transactions pt
JOIN vehicles v ON pt.vehicle_id = v.id
JOIN vehicle_types vt ON v.vehicle_type_id = vt.id
WHERE vt.type_code = 'MC';

-- Active Transactions View
CREATE OR REPLACE VIEW active_transactions AS
SELECT *
FROM parking_transactions
WHERE is_active = TRUE;
