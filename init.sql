-- init.sql

-- Create the registration database if it does not exist
CREATE DATABASE IF NOT EXISTS registration;

-- Use the registration database
USE registration;

-- Create the users table if it does not exist
CREATE TABLE IF NOT EXISTS users (
    id INT AUTO_INCREMENT PRIMARY KEY,
    firstName VARCHAR(50) NOT NULL,
    lastName VARCHAR(50) NOT NULL,
    email VARCHAR(100) NOT NULL UNIQUE,
    password VARCHAR(255) NOT NULL,
    recover_password VARCHAR(255) DEFAULT NULL
);