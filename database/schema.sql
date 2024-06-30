-- CREATE DATABASE link_generator;

USE link_generator;

CREATE TABLE links (
    id INT AUTO_INCREMENT PRIMARY KEY,
    link VARCHAR(255) NOT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

CREATE TABLE submissions (
    id INT AUTO_INCREMENT PRIMARY KEY,
    link_id INT,
    name VARCHAR(100),
    email VARCHAR(100),
    phone VARCHAR(20),
    submitted_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (link_id) REFERENCES links(id)
);