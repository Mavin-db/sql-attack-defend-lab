CREATE DATABASE IF NOT EXISTS shop_lab;

USE shop_lab;

DROP TABLE IF EXISTS users;

CREATE TABLE users (
    id INT AUTO_INCREMENT PRIMARY KEY,
    username VARCHAR(50) NOT NULL,
    password VARCHAR(255) NOT NULL,
    role VARCHAR(20) NOT NULL DEFAULT 'user'
);

INSERT INTO users (username, password, role) VALUES
('admin', 'admin123', 'admin'),
('brad', 'mypassword', 'user');

CREATE USER IF NOT EXISTS 'shop_vuln'@'localhost' IDENTIFIED BY 'ShopVulnPass!';
GRANT ALL PRIVILEGES ON shop_lab.* TO 'shop_vuln'@'localhost';
FLUSH PRIVILEGES;
