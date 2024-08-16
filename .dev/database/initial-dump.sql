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
    `name` VARCHAR(50) NOT NULL,
    `slug` VARCHAR(70) NOT NULL UNIQUE,
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
    `type` ENUM('access', 'refresh') NOT NULL,
    expires_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    FOREIGN KEY (fk_user) REFERENCES user(id)
);

INSERT INTO `role` (`id`, uid, `name`, `slug`, description) VALUES (1, '550e8400-e29b-41d4-a716-446655440000', 'User', 'role-user', 'User role with access to the regular funcionalities for authenticated users');
INSERT INTO `role` (`id`, uid, `name`, `slug`, description) VALUES (2, '550e8400-e29b-41d4-a716-446655440001', 'Admin', 'role-admin', 'Administrator role with full access to the system');

--                                                                                                       for the test purpose  'pass123@456!'
INSERT INTO `user`(`id`, `uid`, `username`, `password`) VALUES (1, '550e8400-e29b-41d4-a716-446655440011', 'admin@mega.co.nz', '$2y$10$UHlXLfEgM/axM6kH5FN4P.GlKPKJZii/XJBBfvnYR7ljNFxOP112y');
INSERT INTO `user_role`(`id`, `fk_user`, `fk_role`) VALUES (1, 1, 2);
