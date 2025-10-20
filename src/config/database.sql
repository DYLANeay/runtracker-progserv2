-- Create database
CREATE DATABASE IF NOT EXISTS runtracker CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;

-- Use the database
USE runtracker;

-- Create users table
CREATE TABLE users (
    id INT PRIMARY KEY AUTO_INCREMENT,
    username VARCHAR(50) UNIQUE NOT NULL,
    email VARCHAR(100) UNIQUE NOT NULL,
    password VARCHAR(255) NOT NULL,
    created_at DATETIME DEFAULT CURRENT_TIMESTAMP,
    INDEX idx_username (username),
    INDEX idx_email (email)
) ENGINE = InnoDB DEFAULT CHARSET = utf8mb4 COLLATE = utf8mb4_unicode_ci;

-- Create runs table
CREATE TABLE runs (
    id INT PRIMARY KEY AUTO_INCREMENT,
    user_id INT NOT NULL,
    DATE DATE NOT NULL,
    distance DECIMAL(5, 2) NOT NULL,
    duration TIME NOT NULL,
    pace TIME NOT NULL,
    notes TEXT,
    created_at DATETIME DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (user_id) REFERENCES users (id) ON DELETE CASCADE ON UPDATE CASCADE,
    INDEX idx_user_id (user_id),
    INDEX idx_date (DATE),
    INDEX idx_user_date (user_id, DATE)
) ENGINE = InnoDB DEFAULT CHARSET = utf8mb4 COLLATE = utf8mb4_unicode_ci;

-- INSERT INTO users (username, email, password, created_at) VALUES
-- ('testuser', 'test@example.com', '$2y$10$YourHashedPasswordHere', NOW());

-- INSERT INTO runs (user_id, date, distance, duration, pace, notes, created_at) VALUES
-- (1, '2025-10-15', 5.00, '00:30:00', '00:06:00', 'Morning run, felt great!', NOW()),
-- (1, '2025-10-17', 10.00, '01:05:00', '00:06:30', 'Long run, a bit tired', NOW()),
-- (1, '2025-10-19', 7.50, '00:45:00', '00:06:00', 'Evening run', NOW());