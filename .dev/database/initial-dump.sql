CREATE DATABASE IF NOT EXISTS mega_api;

USE mega_api;

CREATE TABLE IF NOT EXISTS user (
    id INT AUTO_INCREMENT PRIMARY KEY,
    uid CHAR(36) NOT NULL UNIQUE,
    `username` VARCHAR(255) NOT NULL UNIQUE,
    password VARCHAR(255) NOT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
);

CREATE TABLE IF NOT EXISTS `role` (
    id INT AUTO_INCREMENT PRIMARY KEY,
    uid CHAR(36) NOT NULL UNIQUE,
    `name` VARCHAR(50) NOT NULL UNIQUE,
    description VARCHAR(255) NOT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
);

CREATE TABLE IF NOT EXISTS user_role (
    id INT AUTO_INCREMENT PRIMARY KEY,
    fk_user INT NOT NULL,
    fk_role INT NOT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    FOREIGN KEY (fk_user) REFERENCES user(id),
    FOREIGN KEY (fk_role) REFERENCES `role`(id)
);

CREATE TABLE IF NOT EXISTS user_access (
    id INT AUTO_INCREMENT PRIMARY KEY,
    fk_user INT NOT NULL,
    token CHAR(36) NOT NULL UNIQUE,
    role ENUM('access', 'refresh') NOT NULL,
    expires_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    FOREIGN KEY (fk_user) REFERENCES user(id)
);

INSERT INTO `role` (`id`, uid, `name`, description) VALUES (1, '550e8400-e29b-41d4-a716-446655440000', 'user', 'User role with access to the regular funcionalities for authenticated users');
INSERT INTO `role` (`id`, uid, `name`, description) VALUES (2, '550e8400-e29b-41d4-a716-446655440001', 'admin', 'Administrator role with full access to the system');
