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

-- Insert Vehicle Types
INSERT INTO vehicle_types (type_name, type_code) VALUES 
('Motorcycle', 'MC');

-- Insert Sample Parking Areas
INSERT INTO parking_areas (area_code, area_name, location, total_capacity, motorcycle_capacity, car_capacity, status) VALUES 
('A', 'Area A', 'Gedung A', 200, 200, 0, 'active'),
('B', 'Area B', 'Gedung B', 150, 150, 0, 'active'),
('PASCA', 'Area PASCA', 'Gedung PASCA', 20, 20, 0, 'active');
