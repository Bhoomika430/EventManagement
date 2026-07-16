-- =========================================================
-- Event Registration Management System
-- Database Schema + Sample Data
-- =========================================================

CREATE DATABASE IF NOT EXISTS event_registration_db;
USE event_registration_db;

-- ---------------------------------------------------------
-- Table: admin  (login for the admin/organizer panel)
-- ---------------------------------------------------------
CREATE TABLE IF NOT EXISTS admin (
    admin_id INT AUTO_INCREMENT PRIMARY KEY,
    username VARCHAR(50) NOT NULL UNIQUE,
    password VARCHAR(255) NOT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

-- Default admin login -> username: admin | password: admin123
-- (NOTE: stored in plain text here to keep the mini-project simple.
--  For a real deployment, hash it with password_hash() and use password_verify().)
INSERT INTO admin (username, password) VALUES ('admin', 'admin123');

-- ---------------------------------------------------------
-- Table: events  (the events available for registration)
-- ---------------------------------------------------------
CREATE TABLE IF NOT EXISTS events (
    event_id INT AUTO_INCREMENT PRIMARY KEY,
    event_name VARCHAR(150) NOT NULL,
    event_type ENUM('Marriage','Birthday','Sportsday') NOT NULL,
    event_date DATE NOT NULL,
    event_time TIME DEFAULT NULL,
    venue VARCHAR(200) NOT NULL,
    description TEXT,
    max_participants INT DEFAULT 100,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

INSERT INTO events (event_name, event_type, event_date, event_time, venue, description, max_participants) VALUES
('Ananya & Rohit Wedding Reception', 'Marriage', '2026-11-14', '18:30:00', 'Grand Palace Convention Hall', 'A grand evening celebrating the wedding of Ananya and Rohit. Dinner and live music included.', 300),
('Baby Aarav Turns 1!', 'Birthday', '2026-08-20', '17:00:00', 'Sunshine Party Lawn', 'A fun-filled first birthday celebration with games, cake cutting, and entertainment for kids.', 80),
('Annual Inter-School Sports Day', 'Sportsday', '2026-09-05', '08:00:00', 'City Sports Stadium', 'Track and field events, relay races, and team sports for students of all age groups.', 500);

-- ---------------------------------------------------------
-- Table: registrations  (people registering for an event)
-- ---------------------------------------------------------
CREATE TABLE IF NOT EXISTS registrations (
    registration_id INT AUTO_INCREMENT PRIMARY KEY,
    event_id INT NOT NULL,
    full_name VARCHAR(100) NOT NULL,
    email VARCHAR(100) NOT NULL,
    phone VARCHAR(15) NOT NULL,
    age INT NOT NULL,
    gender ENUM('Male','Female','Other') NOT NULL,
    address VARCHAR(255),
    guests INT DEFAULT 0,
    notes TEXT,
    registered_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (event_id) REFERENCES events(event_id) ON DELETE CASCADE
);
